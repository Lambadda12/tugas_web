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
$sql = "SELECT r.*, rt.name as room_type, rt.description, rt.price_per_night, rt.capacity, rm.room_number 
        FROM reservations r 
        JOIN rooms rm ON r.room_id = rm.id 
        JOIN room_types rt ON rm.room_type_id = rt.id 
        WHERE r.id = $reservation_id AND r.user_id = $user_id";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header('Location: my_bookings.php');
    exit();
}

$booking = mysqli_fetch_assoc($result);
mysqli_free_result($result);

// Calculate number of nights
$check_in = new DateTime($booking['check_in_date']);
$check_out = new DateTime($booking['check_out_date']);
$interval = $check_in->diff($check_out);
$nights = $interval->days;

// Handle booking cancellation
if(isset($_POST['cancel_booking'])) {
    if($booking['status'] == 'confirmed' && strtotime($booking['check_in_date']) > time()) {
        // Update reservation status
        $sql = "UPDATE reservations SET status = 'cancelled' WHERE id = $reservation_id";
        mysqli_query($conn, $sql);
        
        // Update room status
        $sql = "UPDATE rooms SET status = 'available' WHERE id = " . $booking['room_id'];
        mysqli_query($conn, $sql);
        
        $_SESSION['success'] = 'Booking cancelled successfully.';
        header('Location: my_bookings.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Green Oasis Hotel</title>
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
        
        /* Booking Details specific styles */
        .details-section {
            padding: 80px 0;
            position: relative;
        }
        
        .details-section:before {
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
        
        .details-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            margin-bottom: 30px;
        }
        
        .details-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            padding: 20px 25px;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .card-header:after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -70px;
            right: -70px;
        }
        
        .card-header:before {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -30px;
            left: -30px;
        }
        
        .booking-id {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
        }
        
        .badge {
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85rem;
            position: relative;
            z-index: 2;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .badge-info {
            background-color: #17a2b8;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .section-heading {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 20px;
            font-size: 1.3rem;
            position: relative;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .section-heading:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 0;
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
        
        .room-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .room-image:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 40px rgba(46, 139, 87, 0.2);
        }
        
        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.6s ease;
        }
        
        .room-image:hover img {
            transform: scale(1.05);
        }
        
        .room-info-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .room-info-card .card-body {
            padding: 25px;
        }
        
        .room-info-card .card-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .room-info-card .card-text {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .room-features {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
        }
        
        .feature i {
            color: var(--primary-green);
            margin-right: 8px;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e9ecef, transparent);
            margin: 30px 0;
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
            margin-left: 10px;
        }
        
        .btn-outline-custom:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 139, 87, 0.2);
        }
        
        .btn-danger-custom {
            background: linear-gradient(to right, #dc3545, #c82333);
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
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }
        
        .btn-danger-custom:before {
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
        
        .btn-danger-custom:hover:before {
            left: 100%;
        }
        
        .btn-danger-custom:hover {
            background: linear-gradient(to right, #c82333, #dc3545);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
        }
        
        .price-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .price-total {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #ddd;
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.1rem;
        }
        
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
        
        .additional-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .info-title {
            color: var(--dark-green);
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-item i {
            color: var(--primary-green);
            margin-right: 10px;
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
        @media (max-width: 991px) {
            .room-image {
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 767px) {
            .details-section {
                padding: 60px 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .booking-id {
                font-size: 1.3rem;
            }
            
            .action-buttons {
                display: flex;
                flex-direction: column;
            }
            
            .btn-outline-custom {
                margin-left: 0;
                margin-top: 10px;
            }
            
            .btn-danger-custom {
                margin-top: 10px;
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
    
    <section class="details-section">
        <div class="container">
            <h2 class="page-title" data-aos="fade-right">Booking Details</h2>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card details-card" data-aos="fade-up">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="booking-id">Booking #<?php echo $booking['id']; ?></h5>
                                <div>
                                    <?php if($booking['status'] == 'confirmed'): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmed
                                        </span>
                                    <?php elseif($booking['status'] == 'cancelled'): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelled
                                        </span>
                                    <?php elseif($booking['status'] == 'completed'): ?>
                                        <span class="badge badge-info">
                                            <i class="fas fa-flag-checkered mr-1"></i> Completed
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="section-heading"><i class="fas fa-bed mr-2"></i> Room Information</h5>
                                    <div class="detail-item">
                                        <strong>Room Type:</strong>
                                        <div class="detail-value"><?php echo $booking['room_type']; ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Room Number:</strong>
                                        <div class="detail-value"><?php echo $booking['room_number']; ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Capacity:</strong>
                                        <div class="detail-value"><i class="fas fa-user mr-1"></i> <?php echo $booking['capacity']; ?> persons</div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Description:</strong>
                                        <div class="detail-value"><?php echo $booking['description']; ?></div>
                                    </div>
                                    
                                    <div class="room-features">
                                        <div class="feature">
                                            <i class="fas fa-wifi"></i> Free WiFi
                                        </div>
                                        <div class="feature">
                                            <i class="fas fa-snowflake"></i> AC
                                        </div>
                                        <div class="feature">
                                            <i class="fas fa-tv"></i> Smart TV
                                        </div>
                                        <div class="feature">
                                            <i class="fas fa-coffee"></i> Coffee Maker
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="section-heading"><i class="fas fa-calendar-alt mr-2"></i> Booking Information</h5>
                                    <div class="detail-item">
                                        <strong>Check-in Date:</strong>
                                        <div class="detail-value">
                                            <i class="far fa-calendar-alt mr-1"></i> 
                                            <?php echo date('d M Y', strtotime($booking['check_in_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Check-out Date:</strong>
                                        <div class="detail-value">
                                            <i class="far fa-calendar-alt mr-1"></i> 
                                            <?php echo date('d M Y', strtotime($booking['check_out_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Number of Nights:</strong>
                                        <div class="detail-value">
                                            <i class="fas fa-moon mr-1"></i> <?php echo $nights; ?>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Booking Date:</strong>
                                        <div class="detail-value">
                                            <i class="far fa-clock mr-1"></i> 
                                            <?php echo date('d M Y H:i', strtotime($booking['created_at'])); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="price-summary">
                                        <h6 class="mb-3">Price Summary</h6>
                                        <div class="price-item">
                                            <span>Room Rate:</span>
                                            <span>Rp <?php echo number_format($booking['price_per_night'], 0, ',', '.'); ?> / night</span>
                                        </div>
                                        <div class="price-item">
                                            <span>Number of Nights:</span>
                                            <span><?php echo $nights; ?> nights</span>
                                        </div>
                                        <div class="price-total">
                                            <span>Total Amount:</span>
                                            <span>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="divider"></div>
                            
                            <div class="additional-info">
                                <h6 class="info-title">Important Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="far fa-clock"></i> <strong>Check-in Time:</strong> 2:00 PM - 10:00 PM
                                        </div>
                                        <div class="info-item">
                                            <i class="far fa-clock"></i> <strong>Check-out Time:</strong> Until 12:00 PM
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Jl. Green Oasis No. 123, Jakarta
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-phone-alt"></i> <strong>Contact:</strong> +62 21 5555 8888
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="my_bookings.php" class="btn btn-custom">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to My Bookings
                                </a>
                                
                                <?php if($booking['status'] == 'confirmed'): ?>
                                    <a href="#" class="btn btn-outline-custom" onclick="printBookingDetails()">
                                        <i class="fas fa-print mr-2"></i> Print Details
                                    </a>
                                    
                                    <?php if(strtotime($booking['check_in_date']) > time()): ?>
                                        <form action="booking_details.php?id=<?php echo $booking['id']; ?>" method="POST" class="d-inline" id="cancelForm">
                                            <button type="button" class="btn btn-danger-custom" onclick="confirmCancellation()">
                                                <i class="fas fa-times-circle mr-2"></i> Cancel Booking
                                            </button>
                                            <input type="hidden" name="cancel_booking" value="1">
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="room-image" data-aos="fade-left">
                        <img src="https://source.unsplash.com/random/600x800/?luxury-hotel-room" alt="<?php echo $booking['room_type']; ?> Room">
                    </div>
                    
                    <?php if($booking['status'] == 'confirmed'): ?>
                    <div class="card details-card mt-4" data-aos="fade-up">
                        <div class="card-body">
                            <h5 class="section-heading text-center"><i class="fas fa-qrcode mr-2"></i> Booking QR Code</h5>
                            <div class="qr-code-container">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BOOKING-<?php echo $booking['id']; ?>-GREENOASIS" alt="Booking QR Code" class="qr-code">
                                <p class="qr-text">Show this QR code at check-in</p>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=BOOKING-<?php echo $booking['id']; ?>-GREENOASIS&download=1" class="btn btn-sm btn-outline-custom" download>
                                    <i class="fas fa-download mr-1"></i> Download QR Code
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
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
        
        // Confirm cancellation
        function confirmCancellation() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to cancel this booking. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelForm').submit();
                }
            });
        }
        
        // Print booking details
        function printBookingDetails() {
            window.print();
        }
    </script>
    
    <!-- SweetAlert2 for better confirmation dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>
