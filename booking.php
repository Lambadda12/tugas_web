<?php
session_start();
include 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}

// Check if room type is specified
if(!isset($_GET['type'])) {
    header('Location: rooms.php');
    exit();
}

$type_id = mysqli_real_escape_string($conn, $_GET['type']);

// Get room type details
$sql = "SELECT * FROM room_types WHERE id = $type_id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header('Location: rooms.php');
    exit();
}

$room_type = mysqli_fetch_assoc($result);
mysqli_free_result($result);

// Process booking form
$errors = ['check_in' => '', 'check_out' => '', 'booking' => '', 'recaptcha' => ''];
$check_in = $check_out = '';

if(isset($_POST['submit'])) {
    // Validate reCAPTCHA
    if(isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = '6LdP0ScrAAAAAHraPTaHZjwAazOkfQs202LqQ2KY';
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
        $responseKeys = json_decode($response, true);
        
        if(!$responseKeys["success"]) {
            $errors['recaptcha'] = 'reCAPTCHA verification failed. Please try again.';
        }
    } else {
        $errors['recaptcha'] = 'Please complete the reCAPTCHA.';
    }
    
    // Validate check-in date
    if(empty($_POST['check_in'])) {
        $errors['check_in'] = 'Check-in date is required';
    } else {
        $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
        $today = date('Y-m-d');
        if($check_in < $today) {
            $errors['check_in'] = 'Check-in date cannot be in the past';
        }
    }
    
    // Validate check-out date
    if(empty($_POST['check_out'])) {
        $errors['check_out'] = 'Check-out date is required';
    } else {
        $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
        if($check_out <= $check_in) {
            $errors['check_out'] = 'Check-out date must be after check-in date';
        }
    }
    
    // If no errors, process booking
    if(!array_filter($errors)) {
        // Find available room
        $sql = "SELECT r.id FROM rooms r 
                WHERE r.room_type_id = $type_id AND r.status = 'available' 
                AND r.id NOT IN (
                    SELECT res.room_id FROM reservations res 
                    WHERE (res.check_in_date <= '$check_out' AND res.check_out_date >= '$check_in')
                    AND res.status != 'cancelled'
                ) 
                LIMIT 1";
        
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            $room = mysqli_fetch_assoc($result);
            $room_id = $room['id'];
            $user_id = $_SESSION['user_id'];
            
            // Calculate total price
            $check_in_obj = new DateTime($check_in);
            $check_out_obj = new DateTime($check_out);
            $interval = $check_in_obj->diff($check_out_obj);
            $nights = $interval->days;
            $total_price = $nights * $room_type['price_per_night'];
            
            // Create reservation
            $sql = "INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_price, status) 
                    VALUES ($user_id, $room_id, '$check_in', '$check_out', $total_price, 'confirmed')";
            
            if(mysqli_query($conn, $sql)) {
                $reservation_id = mysqli_insert_id($conn);
                
                // Update room status
                $sql = "UPDATE rooms SET status = 'occupied' WHERE id = $room_id";
                mysqli_query($conn, $sql);
                
                // Redirect to booking confirmation
                header("Location: booking_confirmation.php?id=$reservation_id");
                exit();
            } else {
                $errors['booking'] = 'Error creating reservation: ' . mysqli_error($conn);
            }
        } else {
            $errors['booking'] = 'No rooms available for the selected dates. Please try different dates.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo $room_type['name']; ?> Room - Green Oasis Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Add reCAPTCHA API script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        :root {
            --primary-green: #2E8B57;
            --secondary-green: #3CB371;
            --light-green: #98FB98;
            --dark-green: #006400;
            --accent-green: #00FF7F;
            --forest-green: #228B22;
            --mint-green: #BDFCC9;
            --olive-green: #556B2F;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            overflow-x: hidden;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgdmlld0JveD0iMCAwIDUwIDUwIj48cGF0aCBmaWxsPSIjMmU4YjU3IiBmaWxsLW9wYWNpdHk9IjAuMDMiIGQ9Ik0yNSAwYzEzLjgwNyAwIDI1IDExLjE5MyAyNSAyNVMzOC44MDcgNTAgMjUgNTAgMCwzOC44MDcgMCwyNSAxMS4xOTMsMCAyNSwwWm0wIDE0YTExIDExIDAgMTEwIDIyIDExIDExIDAgMDEwLTIyWiIvPjwvc3ZnPg==');
            background-attachment: fixed;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        .navbar {
            background-color: rgba(46, 139, 87, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            transition: all 0.4s ease;
        }
        
        .navbar.scrolled {
            background-color: rgba(0, 100, 0, 0.98) !important;
            padding: 10px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
        }
        
        .logo {
            width: 45px;
            height: 45px;
            margin-right: 10px;
            background: linear-gradient(135deg, #fff, #e8f5e9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .logo:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%); }
            20% { transform: translateX(100%); }
            100% { transform: translateX(100%); }
        }
        
        .logo-leaf {
            color: var(--primary-green);
            font-size: 22px;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.1));
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 12px;
            position: relative;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--accent-green);
            bottom: -2px;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover:after {
            width: 100%;
        }
        
        .nav-link:hover {
            color: var(--accent-green) !important;
        }
        
        /* Booking specific styles */
        .booking-section {
            padding: 80px 0;
            position: relative;
        }
        
        .booking-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.05) 0%, rgba(0, 100, 0, 0.02) 100%);
            z-index: -1;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: -10px;
            left: 0;
        }
        
        .room-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            margin-bottom: 30px;
        }
        
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .room-img {
            height: 300px;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .room-card:hover .room-img {
            transform: scale(1.05);
        }
        
        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
        }
        
        .card-text {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .room-feature {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #555;
        }
        
        .room-feature i {
            color: var(--primary-green);
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 20px 0;
            display: flex;
            align-items: baseline;
        }
        
        .price-tag small {
            font-size: 1rem;
            color: #777;
            margin-left: 5px;
        }
        
        .booking-form-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
        }
        
        .booking-form-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .booking-form-header {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .booking-form-header h4 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .booking-form-header:after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -70px;
            right: -70px;
            z-index: 1;
        }
        
        .booking-form-header:before {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -30px;
            left: -30px;
            z-index: 1;
        }
        
        .booking-form-body {
            padding: 30px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e8f5e9;
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }
        
        .invalid-feedback {
            font-weight: 500;
        }
        
        .btn-book {
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(0, 100, 0, 0.2);
        }
        
        .btn-book:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
            z-index: -1;
        }
        
        .btn-book:hover:before {
            left: 100%;
        }
        
        .btn-book:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            box-shadow: 0 8px 20px rgba(0, 100, 0, 0.3);
            transform: translateY(-3px);
        }
        
        .alert {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .alert-danger {
            background-color: #ffe5e5;
            color: #d63031;
            border-left: 4px solid #d63031;
        }
        
        .g-recaptcha {
            margin: 20px 0;
        }
        
        .booking-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .summary-title {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #555;
        }
        
        .summary-total {
            font-weight: 700;
            color: var(--dark-green);
            border-top: 2px solid #e9ecef;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .date-picker-wrapper {
            position: relative;
        }
        
        .date-picker-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
            pointer-events: none;
        }
        
        /* Room amenities */
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .room-card:hover .amenity-item {
            background-color: #e8f5e9;
        }
        
        .amenity-item i {
            color: var(--primary-green);
            margin-right: 8px;
        }
        
        /* Loader animation */
        .loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .loader {
            position: relative;
            width: 100px;
            height: 100px;
        }
        
        .loader:before, .loader:after {
            content: '';
            position: absolute;
            border-radius: 50%;
            animation: pulsOut 1.8s ease-in-out infinite;
            filter: drop-shadow(0 0 1rem rgba(46, 139, 87, 0.5));
        }
        
        .loader:before {
            width: 100%;
            height: 100%;
            background-color: rgba(46, 139, 87, 0.6);
            animation-delay: 0.5s;
        }
        
        .loader:after {
            width: 75%;
            height: 75%;
            background-color: rgba(46, 139, 87, 0.9);
            top: 12.5%;
            left: 12.5%;
        }
        
        @keyframes pulsOut {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(1); opacity: 0; }
        }
        
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }
        
        /* Responsive styles */
        @media (max-width: 767px) {
            .booking-section {
                padding: 60px 0;
            }
            
            .room-img {
                height: 200px;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>

    <?php include 'templates/header.php'; ?>
    
    <section class="booking-section">
        <div class="container">
            <h2 class="page-title" data-aos="fade-right">Book Your Stay</h2>
            
            <div class="row">
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card room-card">
                        <img src="https://source.unsplash.com/random/600x400/?luxury-hotel-room" class="card-img-top room-img" alt="<?php echo $room_type['name']; ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $room_type['name']; ?> Room</h3>
                            <p class="card-text"><?php echo $room_type['description']; ?></p>
                            
                            <div class="amenities-list">
                                <div class="amenity-item">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo $room_type['capacity']; ?> Guests</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-wifi"></i>
                                    <span>Free WiFi</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-coffee"></i>
                                    <span>Breakfast</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-snowflake"></i>
                                    <span>Air Conditioning</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-tv"></i>
                                    <span>Smart TV</span>
                                </div>
                                <div class="amenity-item">
                                    <i class="fas fa-bath"></i>
                                    <span>Private Bathroom</span>
                                </div>
                            </div>
                            
                            <div class="price-tag">
                                Rp <?php echo number_format($room_type['price_per_night'], 0, ',', '.'); ?> <small>/ night</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card booking-form-card">
                        <div class="booking-form-header">
                            <h4><i class="fas fa-calendar-check mr-2"></i> Booking Details</h4>
                        </div>
                        <div class="booking-form-body">
                            <?php if($errors['booking']): ?>
                                <div class="alert alert-danger animate__animated animate__shakeX">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <?php echo $errors['booking']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="booking.php?type=<?php echo $type_id; ?>" method="POST" id="bookingForm">
                                <div class="form-group">
                                    <label for="check_in"><i class="fas fa-calendar-alt mr-2"></i>Check-in Date</label>
                                    <div class="date-picker-wrapper">
                                        <input type="date" class="form-control <?php echo $errors['check_in'] ? 'is-invalid' : ''; ?>" id="check_in" name="check_in" value="<?php echo $check_in; ?>" min="<?php echo date('Y-m-d'); ?>">
                                        <i class="fas fa-calendar"></i>
                                        <div class="invalid-feedback"><?php echo $errors['check_in']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="check_out"><i class="fas fa-calendar-alt mr-2"></i>Check-out Date</label>
                                    <div class="date-picker-wrapper">
                                        <input type="date" class="form-control <?php echo $errors['check_out'] ? 'is-invalid' : ''; ?>" id="check_out" name="check_out" value="<?php echo $check_out; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                        <i class="fas fa-calendar"></i>
                                        <div class="invalid-feedback"><?php echo $errors['check_out']; ?></div>
                                    </div>
                                </div>
                                
                                <div class="booking-summary" id="bookingSummary" style="display: none;">
                                    <h5 class="summary-title"><i class="fas fa-receipt mr-2"></i>Booking Summary</h5>
                                    <div class="summary-item">
                                        <span>Room Type:</span>
                                        <span><?php echo $room_type['name']; ?></span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Check-in Date:</span>
                                        <span id="summaryCheckIn">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Check-out Date:</span>
                                        <span id="summaryCheckOut">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Number of Nights:</span>
                                        <span id="summaryNights">-</span>
                                    </div>
                                    <div class="summary-item">
                                        <span>Price per Night:</span>
                                        <span>Rp <?php echo number_format($room_type['price_per_night'], 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="summary-item summary-total">
                                        <span>Total Price:</span>
                                        <span id="summaryTotal">-</span>
                                    </div>
                                </div>
                                
                                <!-- Add reCAPTCHA widget -->
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LdP0ScrAAAAADO-18hSDeCYe083idVUhEKnn9EF"></div>
                                    <?php if($errors['recaptcha']): ?>
                                        <div class="text-danger mt-2"><?php echo $errors['recaptcha']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" name="submit" class="btn btn-book btn-block">
                                    <i class="fas fa-check-circle mr-2"></i> Confirm Booking
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'templates/footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease',
            once: true,
            offset: 100
        });
        
        // Loader
        $(window).on('load', function() {
            setTimeout(function() {
                $('.loader-wrapper').addClass('loader-hidden');
            }, 800);
        });
        
        // Navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });
        
        // Calculate booking summary
        function updateBookingSummary() {
            var checkIn = $('#check_in').val();
            var checkOut = $('#check_out').val();
            
            if (checkIn && checkOut) {
                // Calculate number of nights
                var checkInDate = new Date(checkIn);
                var checkOutDate = new Date(checkOut);
                var timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                var nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (nights > 0) {
                    // Format dates for display
                    var checkInFormatted = formatDate(checkInDate);
                    var checkOutFormatted = formatDate(checkOutDate);
                    
                    // Calculate total price
                    var pricePerNight = <?php echo $room_type['price_per_night']; ?>;
                    var totalPrice = nights * pricePerNight;
                    
                    // Update summary
                    $('#summaryCheckIn').text(checkInFormatted);
                    $('#summaryCheckOut').text(checkOutFormatted);
                    $('#summaryNights').text(nights);
                    $('#summaryTotal').text('Rp ' + formatNumber(totalPrice));
                    
                    // Show summary
                    $('#bookingSummary').slideDown();
                }
            }
        }
        
        // Format date as DD Month YYYY
        function formatDate(date) {
            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            return date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        }
        
        // Format number with thousand separators
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Update booking summary when dates change
        $('#check_in, #check_out').on('change', function() {
            updateBookingSummary();
        });
        
        // Form validation
        $('#bookingForm').on('submit', function(e) {
            let isValid = true;
            
            // Validate check-in date
            if ($('#check_in').val() === '') {
                $('#check_in').addClass('is-invalid');
                isValid = false;
            } else {
                $('#check_in').removeClass('is-invalid');
            }
            
            // Validate check-out date
            if ($('#check_out').val() === '') {
                $('#check_out').addClass('is-invalid');
                isValid = false;
            } else {
                var checkIn = new Date($('#check_in').val());
                var checkOut = new Date($('#check_out').val());
                
                if (checkOut <= checkIn) {
                    $('#check_out').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#check_out').removeClass('is-invalid');
                }
            }
            
            // Validate reCAPTCHA
            if (grecaptcha.getResponse() === '') {
                $('.g-recaptcha').after('<div class="text-danger mt-2">Please complete the reCAPTCHA.</div>');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Set minimum check-out date based on check-in date
        $('#check_in').on('change', function() {
            var checkInDate = $(this).val();
            if (checkInDate) {
                var nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                
                var month = nextDay.getMonth() + 1;
                if (month < 10) month = '0' + month;
                
                var day = nextDay.getDate();
                if (day < 10) day = '0' + day;
                
                var nextDayFormatted = nextDay.getFullYear() + '-' + month + '-' + day;
                $('#check_out').attr('min', nextDayFormatted);
                
                // If current check-out date is before new min date, update it
                if ($('#check_out').val() && new Date($('#check_out').val()) <= new Date(checkInDate)) {
                    $('#check_out').val(nextDayFormatted);
                }
                
                updateBookingSummary();
            }
        });
        
        // Input focus effect
        $('.form-control').focus(function() {
            $(this).parent().find('label').addClass('text-primary');
        }).blur(function() {
            $(this).parent().find('label').removeClass('text-primary');
        });
        
        // Initialize with today's date if not set
        $(document).ready(function() {
            if (!$('#check_in').val()) {
                var today = new Date();
                var month = today.getMonth() + 1;
                if (month < 10) month = '0' + month;
                
                var day = today.getDate();
                if (day < 10) day = '0' + day;
                
                var todayFormatted = today.getFullYear() + '-' + month + '-' + day;
                $('#check_in').val(todayFormatted);
                
                // Set check-out to tomorrow by default
                var tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                month = tomorrow.getMonth() + 1;
                if (month < 10) month = '0' + month;
                
                day = tomorrow.getDate();
                if (day < 10) day = '0' + day;
                
                var tomorrowFormatted = tomorrow.getFullYear() + '-' + month + '-' + day;
                $('#check_out').val(tomorrowFormatted);
                
                updateBookingSummary();
            }
        });
    </script>
</body>
</html>
