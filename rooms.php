<?php
session_start();
include 'config/db_connect.php';

// Get room types
$sql = "SELECT * FROM room_types";
$result = mysqli_query($conn, $sql);
$room_types = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

// Get available rooms count for each type
foreach($room_types as $key => $type) {
    $type_id = $type['id'];
    $sql = "SELECT COUNT(*) as count FROM rooms WHERE room_type_id = $type_id AND status = 'available'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_fetch_assoc($result);
    $room_types[$key]['available_count'] = $count['count'];
    mysqli_free_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Green Oasis Hotel</title>
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
        
        /* Rooms page specific styles */
        .rooms-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://source.unsplash.com/random/1200x600/?luxury-hotel-room');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0 100px;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }
        
        .rooms-hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-green) 0%, transparent 50%);
            opacity: 0.6;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 50px;
            text-align: center;
            position: relative;
        }
        
        .page-title:after {
            content: '';
            position: absolute;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .page-title:before {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: var(--primary-green);
            border-radius: 50%;
            bottom: -21px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 0 5px rgba(46, 139, 87, 0.2);
        }
        
        .room-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            margin-bottom: 40px;
            background: white;
            height: 100%;
        }
        
        .room-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .room-img-container {
            position: relative;
            overflow: hidden;
            height: 300px;
        }
        
        .room-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.7s ease;
        }
        
        .room-card:hover .room-img {
            transform: scale(1.1);
        }
        
        .room-type-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
        }
        
        .room-price-badge {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .room-price-badge span {
            color: var(--accent-green);
        }
        
        .card-body {
            padding: 30px;
        }
        
        .card-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 15px;
            display: inline-block;
        }
        
        .card-title:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 0;
        }
        
        .card-text {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .room-features {
            display: flex;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-right: 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .room-card:hover .feature {
            background-color: #e8f5e9;
        }
        
        .feature i {
            color: var(--primary-green);
            margin-right: 8px;
        }
        
        .availability {
            margin: 20px 0;
            font-weight: 500;
        }
        
        .availability-count {
            color: var(--primary-green);
            font-weight: 700;
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
            display: inline-block;
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
        
        .btn-disabled {
            background: #6c757d;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: not-allowed;
            opacity: 0.7;
            display: inline-block;
        }
        
        .filter-section {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .filter-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .filter-form .form-group {
            margin-bottom: 15px;
        }
        
        .filter-form label {
            font-weight: 500;
            color: #555;
        }
        
        .filter-form .form-control {
            border-radius: 30px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .filter-form .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .filter-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .btn-filter {
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
            margin-right: 10px;
        }
        
        .btn-filter:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 100, 0, 0.2);
        }
        
        .btn-reset {
            background: transparent;
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .btn-reset:hover {
            background-color: #f8f9fa;
            color: var(--dark-green);
            transform: translateY(-3px);
        }
        
        .room-amenities {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .amenities-title {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
        }
        
        .amenity-item {
            display: flex;
            align-items: center;
            width: 50%;
            margin-bottom: 10px;
            color: #666;
        }
        
        .amenity-item i {
            color: var(--primary-green);
            margin-right: 8px;
            font-size: 0.9rem;
        }
        
        .room-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .view-details {
            color: var(--primary-green);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .view-details i {
            margin-left: 5px;
            transition: all 0.3s ease;
        }
        
        .view-details:hover {
            color: var(--dark-green);
            text-decoration: none;
        }
        
        .view-details:hover i {
            transform: translateX(5px);
        }
        
        .no-rooms-message {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            margin: 30px 0;
        }
        
        .no-rooms-icon {
            font-size: 60px;
            color: #adb5bd;
            margin-bottom: 20px;
        }
        
        .no-rooms-title {
            font-weight: 700;
            color: #495057;
            margin-bottom: 15px;
        }
        
        .no-rooms-text {
            color: #6c757d;
            margin-bottom: 20px;
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
            .hero-title {
                font-size: 2.8rem;
            }
            
            .room-img-container {
                height: 250px;
            }
        }
        
        @media (max-width: 767px) {
            .rooms-hero {
                padding: 100px 0 70px;
            }
            
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .amenity-item {
                width: 100%;
            }
            
            .room-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .view-details {
                margin-top: 15px;
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
    
    <div class="rooms-hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1 class="hero-title">Our Luxurious Rooms</h1>
                <p class="hero-subtitle">Experience the perfect blend of comfort, luxury, and eco-friendly design in our thoughtfully crafted accommodations.</p>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="filter-section" data-aos="fade-up">
            <h3 class="filter-title">Find Your Perfect Room</h3>
            <form class="filter-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="checkInDate">Check-in Date</label>
                            <input type="date" class="form-control" id="checkInDate" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="checkOutDate">Check-out Date</label>
                            <input type="date" class="form-control" id="checkOutDate" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="guestCount">Number of Guests</label>
                            <select class="form-control" id="guestCount">
                                <option value="1">1 Guest</option>
                                <option value="2" selected>2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                                <option value="5">5+ Guests</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="filter-buttons">
                    <button type="button" class="btn-filter" id="applyFilter">
                        <i class="fas fa-search mr-2"></i> Search Rooms
                    </button>
                    <button type="button" class="btn-reset" id="resetFilter">
                        <i class="fas fa-redo-alt mr-2"></i> Reset
                    </button>
                </div>
            </form>
        </div>
        
        <h2 class="page-title" data-aos="fade-up">Explore Our Room Collection</h2>
        
        <?php if(empty($room_types)): ?>
            <div class="no-rooms-message" data-aos="fade-up">
                <i class="fas fa-bed no-rooms-icon"></i>
                <h3 class="no-rooms-title">No Rooms Available</h3>
                <p class="no-rooms-text">We're sorry, but there are currently no rooms available. Please check back later or contact us for assistance.</p>
                <a href="contact.php" class="btn btn-custom">Contact Us</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($room_types as $index => $room): ?>
                    <div class="col-lg-6 mb-4 room-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>" data-capacity="<?php echo $room['capacity']; ?>">
                        <div class="room-card">
                            <div class="room-img-container">
                                <img src="https://source.unsplash.com/random/600x400/?luxury-hotel-room-<?php echo $index+1; ?>" class="room-img" alt="<?php echo $room['name']; ?>">
                                <div class="room-type-badge"><?php echo $room['name']; ?></div>
                                <div class="room-price-badge">From <span>Rp <?php echo number_format($room['price_per_night'], 0, ',', '.'); ?></span> / night</div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $room['name']; ?> Room</h3>
                                <p class="card-text"><?php echo $room['description']; ?></p>
                                
                                <div class="room-features">
                                    <div class="feature">
                                        <i class="fas fa-user"></i>
                                        <span>Up to <?php echo $room['capacity']; ?> Guests</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-expand-arrows-alt"></i>
                                        <span>40-60 m²</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-bed"></i>
                                        <span>King Bed</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-wifi"></i>
                                        <span>Free WiFi</span>
                                    </div>
                                </div>
                                
                                <div class="room-amenities">
                                    <h4 class="amenities-title">Room Amenities</h4>
                                    <div class="amenities-list">
                                        <div class="amenity-item">
                                            <i class="fas fa-snowflake"></i> Air Conditioning
                                        </div>
                                        <div class="amenity-item">
                                            <i class="fas fa-tv"></i> Smart TV
                                        </div>
                                        <div class="amenity-item">
                                            <i class="fas fa-coffee"></i> Coffee Maker
                                        </div>
                                        <div class="amenity-item">
                                            <i class="fas fa-bath"></i> Private Bathroom
                                        </div>
                                        <div class="amenity-item">
                                            <i class="fas fa-concierge-bell"></i> Room Service
                                        </div>
                                        <div class="amenity-item">
                                            <i class="fas fa-lock"></i> Safe Deposit Box
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="availability">
                                    <strong>Availability:</strong> 
                                    <?php if($room['available_count'] > 0): ?>
                                        <span class="availability-count"><?php echo $room['available_count']; ?> rooms available</span>
                                    <?php else: ?>
                                        <span class="text-danger">No rooms available</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="room-actions">
                                    <?php if($room['available_count'] > 0): ?>
                                        <a href="booking.php?type=<?php echo $room['id']; ?>" class="btn btn-custom">
                                            <i class="fas fa-calendar-check mr-2"></i> Book Now
                                        </a>
                                    <?php else: ?>
                                        <button class="btn-disabled" disabled>
                                            <i class="fas fa-calendar-times mr-2"></i> Not Available
                                        </button>
                                    <?php endif; ?>
                                    <a href="room_details.php?id=<?php echo $room['id']; ?>" class="view-details">
                                        View Details <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-5 mb-5" data-aos="fade-up">
            <p>Can't find what you're looking for? Contact our reservation team for assistance.</p>
            <a href="contact.php" class="btn btn-outline-success">
                <i class="fas fa-phone-alt mr-2"></i> Contact Reservation Team
            </a>
        </div>
    </div>
    
    <!-- Room Booking Policy Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4" data-aos="fade-up">Room Booking Policies</h3>
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clock text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Check-in & Check-out</h5>
                            <p class="card-text">Check-in time starts at 2:00 PM<br>Check-out time is 12:00 PM</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-ban text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Cancellation Policy</h5>
                            <p class="card-text">Free cancellation up to 24 hours before check-in. Cancellations made less than 24 hours in advance may be subject to charges.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-child text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Children & Extra Beds</h5>
                            <p class="card-text">Children of all ages are welcome. Children under 6 years stay free when using existing beds.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'templates/footer.php'; ?>
    
    <!-- Scroll to top button -->
    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
    
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
        
        // Scroll to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.scroll-to-top').addClass('active');
            } else {
                $('.scroll-to-top').removeClass('scrolled');
            }
        });
        
        $('.scroll-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
        
        // Room filtering functionality
        $(document).ready(function() {
            // Set default dates
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            $('#checkInDate').val(today.toISOString().substr(0, 10));
            $('#checkOutDate').val(tomorrow.toISOString().substr(0, 10));
            
            // Apply filter button click
            $('#applyFilter').click(function() {
                const checkIn = $('#checkInDate').val();
                const checkOut = $('#checkOutDate').val();
                const guests = $('#guestCount').val();
                
                if (!checkIn || !checkOut) {
                    alert('Please select both check-in and check-out dates');
                    return;
                }
                
                if (new Date(checkOut) <= new Date(checkIn)) {
                    alert('Check-out date must be after check-in date');
                    return;
                }
                
                // Filter rooms based on capacity
                $('.room-item').each(function() {
                    const capacity = parseInt($(this).data('capacity'));
                    if (capacity < guests) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
                
                // Check if any rooms are visible
                if ($('.room-item:visible').length === 0) {
                    // No rooms match the criteria
                    if ($('.no-results-message').length === 0) {
                        const noResults = `
                            <div class="col-12 no-results-message" data-aos="fade-up">
                                <div class="alert alert-info text-center py-4">
                                    <i class="fas fa-info-circle mb-3" style="font-size: 2rem;"></i>
                                    <h4>No rooms match your search criteria</h4>
                                    <p>Please try different dates or guest count, or contact us for assistance.</p>
                                </div>
                            </div>
                        `;
                        $('.room-item').last().after(noResults);
                    }
                } else {
                    $('.no-results-message').remove();
                }
            });
            
            // Reset filter button click
            $('#resetFilter').click(function() {
                $('#checkInDate').val(today.toISOString().substr(0, 10));
                $('#checkOutDate').val(tomorrow.toISOString().substr(0, 10));
                $('#guestCount').val(2);
                
                // Show all rooms
                $('.room-item').show();
                $('.no-results-message').remove();
            });
            
            // Validate check-out date is after check-in date
            $('#checkInDate').change(function() {
                const checkIn = new Date($(this).val());
                const checkOut = new Date($('#checkOutDate').val());
                
                if (checkOut <= checkIn) {
                    const nextDay = new Date(checkIn);
                    nextDay.setDate(nextDay.getDate() + 1);
                    $('#checkOutDate').val(nextDay.toISOString().substr(0, 10));
                }
            });
        });
    </script>
</body>
</html>

