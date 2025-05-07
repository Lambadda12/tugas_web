<?php
session_start();
include 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if reservation ID is specified
if(!isset($_GET['id'])) {
    header('Location: my_bookings.php');
    exit();
}

$reservation_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Get reservation details
$sql = "SELECT r.*, rt.name as room_type, rt.price_per_night, rm.room_number 
        FROM reservations r 
        JOIN rooms rm ON r.room_id = rm.id 
        JOIN room_types rt ON rm.room_type_id = rt.id 
        WHERE r.id = $reservation_id AND r.user_id = $user_id";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header('Location: my_bookings.php');
    exit();
}

$reservation = mysqli_fetch_assoc($result);
mysqli_free_result($result);

// Calculate number of nights
$check_in = new DateTime($reservation['check_in_date']);
$check_out = new DateTime($reservation['check_out_date']);
$interval = $check_in->diff($check_out);
$nights = $interval->days;

// Debug - uncomment to check values
// echo "<pre>";
// print_r($reservation);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Green Oasis Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
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
        
        /* Confirmation specific styles */
        .confirmation-section {
            padding: 80px 0;
            position: relative;
        }
        
        .confirmation-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.05) 0%, rgba(0, 100, 0, 0.02) 100%);
            z-index: -1;
        }
        
        .confirmation-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
        }
        
        .confirmation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .confirmation-header h4 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
            font-size: 1.8rem;
        }
        
        .confirmation-header:after {
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
        
        .confirmation-header:before {
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
        
        .confirmation-body {
            padding: 40px;
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--primary-green);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .confirmation-message {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .confirmation-message h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .confirmation-message p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .booking-details {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .booking-details h5 {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-item strong {
            color: var(--dark-green);
            font-weight: 600;
        }
        
        .detail-value {
            color: #555;
        }
        
        .badge-success {
            background-color: var(--primary-green);
            padding: 8px 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn-custom {
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
            margin: 0 10px 10px 0;
        }
        
        .btn-custom:before {
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
        
        .btn-custom:hover:before {
            left: 100%;
        }
        
        .btn-custom:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            box-shadow: 0 8px 20px rgba(0, 100, 0, 0.3);
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
        }
        
        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            padding: 11px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 0 10px 10px 0;
        }
        
        .btn-outline-custom:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 139, 87, 0.2);
            text-decoration: none;
        }
        
        /* Confetti animation */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--primary-green);
            opacity: 0.7;
            animation: confetti-fall 5s linear infinite;
        }
        
        .confetti:nth-child(2n) {
            background-color: var(--light-green);
        }
        
        .confetti:nth-child(3n) {
            background-color: var(--accent-green);
        }
        
        .confetti:nth-child(4n) {
            background-color: var(--forest-green);
        }
        
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(calc(100vh + 100px)) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 30px 0;
        }
        
        /* QR Code */
        .qr-code-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code {
            border: 10px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 150px;
            margin: 0 auto;
        }
        
        .qr-text {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
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
            .confirmation-section {
                padding: 60px 0;
            }
            
            .confirmation-body {
                padding: 25px;
            }
            
            .success-icon {
                font-size: 60px;
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
    
    <section class="confirmation-section">
        <!-- Confetti animation -->
        <div class="confetti-container" id="confettiContainer"></div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="card confirmation-card">
                        <div class="confirmation-header">
                            <h4><i class="fas fa-check-circle mr-2"></i> Booking Confirmed</h4>
                        </div>
                        <div class="confirmation-body">
                            <div class="confirmation-message">
                                <i class="fas fa-check-circle success-icon"></i>
                                <h5>Thank You for Your Reservation!</h5>
                                <p>Your booking has been confirmed and we're excited to welcome you to Green Oasis Hotel. Below are your booking details.</p>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="booking-details">
                                        <h5><i class="fas fa-info-circle mr-2"></i> Booking Information</h5>
                                        <div class="detail-item">
                                            <strong>Booking ID:</strong>
                                            <div class="detail-value">#<?php echo isset($reservation['id']) ? $reservation['id'] : 'N/A'; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Room Type:</strong>
                                            <div class="detail-value"><?php echo isset($reservation['room_type']) ? $reservation['room_type'] : 'N/A'; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Room Number:</strong>
                                            <div class="detail-value"><?php echo isset($reservation['room_number']) ? $reservation['room_number'] : 'N/A'; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Check-in Date:</strong>
                                            <div class="detail-value">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <?php echo isset($reservation['check_in_date']) ? date('d M Y', strtotime($reservation['check_in_date'])) : 'N/A'; ?>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Check-out Date:</strong>
                                            <div class="detail-value">
                                                <i class="far fa-calendar-alt mr-1"></i>
                                                <?php echo isset($reservation['check_out_date']) ? date('d M Y', strtotime($reservation['check_out_date'])) : 'N/A'; ?>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Number of Nights:</strong>
                                            <div class="detail-value">
                                                <i class="fas fa-moon mr-1"></i>
                                                <?php echo isset($nights) ? $nights : 'N/A'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="booking-details">
                                        <h5><i class="fas fa-credit-card mr-2"></i> Payment Information</h5>
                                        <div class="detail-item">
                                            <strong>Price per Night:</strong>
                                            <div class="detail-value">Rp <?php echo isset($reservation['price_per_night']) ? number_format($reservation['price_per_night'], 0, ',', '.') : 'N/A'; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Total Amount:</strong>
                                            <div class="detail-value" style="font-size: 1.2rem; color: var(--primary-green); font-weight: 700;">
                                                Rp <?php echo isset($reservation['total_price']) ? number_format($reservation['total_price'], 0, ',', '.') : 'N/A'; ?>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Status:</strong>
                                            <div class="detail-value">
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i> Confirmed
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- QR Code for mobile check-in -->
                                        <div class="qr-code-container">
                                            <div class="qr-code">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BOOKING<?php echo isset($reservation['id']) ? $reservation['id'] : '000'; ?>" alt="Check-in QR Code" width="100%">
                                            </div>
                                            <p class="qr-text">Scan for express check-in</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <div class="additional-info">
                                <h5 class="text-center mb-4" style="color: var(--dark-green);">Important Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <strong><i class="far fa-clock mr-2"></i> Check-in Time:</strong>
                                            <div class="detail-value">2:00 PM - 10:00 PM</div>
                                        </div>
                                        <div class="detail-item">
                                            <strong><i class="far fa-clock mr-2"></i> Check-out Time:</strong>
                                            <div class="detail-value">Until 12:00 PM</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <strong><i class="fas fa-map-marker-alt mr-2"></i> Address:</strong>
                                            <div class="detail-value">Jl. Green Oasis No. 123, Jakarta</div>
                                        </div>
                                        <div class="detail-item">
                                            <strong><i class="fas fa-phone-alt mr-2"></i> Contact:</strong>
                                            <div class="detail-value">+62 21 5555 8888</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="my_bookings.php" class="btn btn-custom">
                                    <i class="fas fa-list-alt mr-2"></i> View All Bookings
                                </a>
                                <a href="index.php" class="btn btn-outline-custom">
                                    <i class="fas fa-home mr-2"></i> Return to Home
                                </a>
                            </div>
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
        
        // Create confetti animation
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            const confettiCount = 100;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.classList.add('confetti');
                
                // Random position
                confetti.style.left = Math.random() * 100 + 'vw';
                
                // Random delay
                confetti.style.animationDelay = Math.random() * 5 + 's';
                
                // Random size
                const size = Math.random() * 10 + 5;
                confetti.style.width = size + 'px';
                confetti.style.height = size + 'px';
                
                // Random shape
                const shape = Math.floor(Math.random() * 3);
                if (shape === 0) {
                    confetti.style.borderRadius = '50%';
                } else if (shape === 1) {
                    confetti.style.borderRadius = '0';
                } else {
                    confetti.style.borderRadius = '50% 0 50% 0';
                }
                
                container.appendChild(confetti);
            }
        }
        
        // Run confetti on page load
        $(document).ready(function() {
            createConfetti();
            
            // Print functionality
            $('.btn-print').click(function() {
                window.print();
            });
        });
    </script>
</body>
</html>

