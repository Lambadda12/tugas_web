<?php
session_start();
include 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle booking cancellation
if(isset($_POST['cancel_booking']) && isset($_POST['reservation_id'])) {
    $reservation_id = mysqli_real_escape_string($conn, $_POST['reservation_id']);
    
    // Get room ID
    $sql = "SELECT room_id FROM reservations WHERE id = $reservation_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $reservation = mysqli_fetch_assoc($result);
        $room_id = $reservation['room_id'];
        
        // Update reservation status
        $sql = "UPDATE reservations SET status = 'cancelled' WHERE id = $reservation_id";
        mysqli_query($conn, $sql);
        
        // Update room status
        $sql = "UPDATE rooms SET status = 'available' WHERE id = $room_id";
        mysqli_query($conn, $sql);
        
        $_SESSION['success'] = 'Booking cancelled successfully.';
        header('Location: my_bookings.php');
        exit();
    }
}

// Get user's bookings
$sql = "SELECT r.*, rt.name as room_type, rm.room_number 
        FROM reservations r 
        JOIN rooms rm ON r.room_id = rm.id 
        JOIN room_types rt ON rm.room_type_id = rt.id 
        WHERE r.user_id = $user_id 
        ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $sql);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Green Oasis Hotel</title>
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
        
        /* My Bookings specific styles */
        .bookings-section {
            padding: 80px 0;
            position: relative;
        }
        
        .bookings-section:before {
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
        
        .alert {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
        }
        
        .alert-info {
            background-color: #e3f2fd;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        
        .bookings-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            margin-bottom: 30px;
        }
        
        .bookings-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        
        .booking-id {
            font-weight: 700;
            color: var(--dark-green);
        }
        
        .room-type {
            font-weight: 600;
        }
        
        .date-cell {
            white-space: nowrap;
        }
        
        .price-cell {
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .badge {
            padding: 8px 12px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-success {
            background-color: var(--primary-green);
            color: white;
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
        
        .btn-action {
            border-radius: 50px;
            padding: 8px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 5px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-view {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        
        .btn-view:hover {
            background-color: #138496;
            border-color: #138496;
            color: white;
        }
        
        .btn-cancel {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .btn-cancel:hover {
            background-color: #c82333;
            border-color: #c82333;
            color: white;
        }
        
        .empty-bookings {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .empty-icon {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .btn-book-now {
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
            margin-top: 20px;
            display: inline-block;
        }
        
        .btn-book-now:before {
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
        
        .btn-book-now:hover:before {
            left: 100%;
        }
        
        .btn-book-now:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            box-shadow: 0 8px 20px rgba(0, 100, 0, 0.3);
            transform: translateY(-3px);
            color: white;
            text-decoration: none;
        }
        
        /* Filter section */
        .filter-section {
            margin-bottom: 30px;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .filter-title {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-form .form-group {
            margin-right: 15px;
            margin-bottom: 0;
        }
        
        .filter-form label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        
        .filter-form select {
            border: 2px solid #e8f5e9;
            border-radius: 8px;
            padding: 8px 15px;
            height: auto;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .filter-form select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .btn-filter {
            background-color: var(--primary-green);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 24px;
        }
        
        .btn-filter:hover {
            background-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 100, 0, 0.2);
        }
        
        .btn-reset {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            color: #555;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 24px;
            margin-left: 10px;
        }
        
        .btn-reset:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Pagination */
        .pagination {
            margin-top: 30px;
            justify-content: center;
        }
        
        .page-item:first-child .page-link {
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
        }
        
        .page-item:last-child .page-link {
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
        }
        
        .page-link {
            color: var(--primary-green);
            border: 1px solid #e9ecef;
            padding: 10px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .page-link:hover {
            background-color: #e8f5e9;
            color: var(--dark-green);
            border-color: #e8f5e9;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
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
            .filter-form .form-group {
                margin-bottom: 15px;
            }
            
            .btn-filter, .btn-reset {
                margin-top: 0;
            }
        }
        
        @media (max-width: 767px) {
            .bookings-section {
                padding: 60px 0;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 20px;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                overflow: hidden;
            }
            
            .table td {
                text-align: right;
                padding: 12px 15px;
                position: relative;
                border-top: none;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .table td:last-child {
                border-bottom: none;
            }
            
            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                font-weight: 600;
                color: var(--dark-green);
            }
            
            .btn-action {
                display: block;
                width: 100%;
                margin-bottom: 5px;
                margin-right: 0;
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
    
    <section class="bookings-section">
        <div class="container">
            <h2 class="page-title" data-aos="fade-right">My Bookings</h2>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success animate__animated animate__fadeIn" data-aos="fade-up">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(empty($bookings)): ?>
                <div class="empty-bookings" data-aos="fade-up">
                    <i class="fas fa-calendar-times empty-icon"></i>
                    <h4>No Bookings Found</h4>
                    <p>You don't have any bookings yet. Start your journey with Green Oasis Hotel today!</p>
                    <a href="rooms.php" class="btn btn-book-now">
                        <i class="fas fa-bed mr-2"></i> Book a Room Now
                    </a>
                </div>
            <?php else: ?>
                <!-- Filter section -->
                <div class="filter-section" data-aos="fade-up">
                    <h5 class="filter-title"><i class="fas fa-filter mr-2"></i> Filter Bookings</h5>
                    <form class="filter-form">
                        <div class="form-group">
                            <label for="statusFilter">Status</label>
                            <select class="form-control" id="statusFilter">
                                <option value="all">All Statuses</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dateFilter">Date Range</label>
                            <select class="form-control" id="dateFilter">
                                <option value="all">All Time</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="past">Past</option>
                                <option value="current">Current Stay</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-filter" id="applyFilter">
                            <i class="fas fa-search mr-2"></i> Apply Filter
                        </button>
                        <button type="button" class="btn btn-reset" id="resetFilter">
                            <i class="fas fa-redo-alt mr-2"></i> Reset
                        </button>
                    </form>
                </div>
                
                <div class="card bookings-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="table-responsive">
                        <table class="table table-hover" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Room Type</th>
                                    <th>Room Number</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($bookings as $booking): ?>
                                    <tr class="booking-row" data-status="<?php echo $booking['status']; ?>">
                                        <td data-label="Booking ID" class="booking-id">#<?php echo $booking['id']; ?></td>
                                        <td data-label="Room Type" class="room-type"><?php echo $booking['room_type']; ?></td>
                                        <td data-label="Room Number"><?php echo $booking['room_number']; ?></td>
                                        <td data-label="Check-in" class="date-cell">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?php echo date('d M Y', strtotime($booking['check_in_date'])); ?>
                                        </td>
                                        <td data-label="Check-out" class="date-cell">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?php echo date('d M Y', strtotime($booking['check_out_date'])); ?>
                                        </td>
                                        <td data-label="Total Price" class="price-cell">
                                            Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                                        </td>
                                        <td data-label="Status">
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
                                        </td>
                                        <td data-label="Actions">
                                            <a href="booking_details.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-view btn-action">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </a>
                                            
                                            <?php if($booking['status'] == 'confirmed' && strtotime($booking['check_in_date']) > time()): ?>
                                                <form action="my_bookings.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    <input type="hidden" name="reservation_id" value="<?php echo $booking['id']; ?>">
                                                    <button type="submit" name="cancel_booking" class="btn btn-sm btn-cancel btn-action">
                                                        <i class="fas fa-times-circle mr-1"></i> Cancel
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Bookings pagination" data-aos="fade-up">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
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
        
        // Filter functionality
        $(document).ready(function() {
            // Apply filter
            $('#applyFilter').click(function() {
                filterBookings();
            });
            
            // Reset filter
            $('#resetFilter').click(function() {
                $('#statusFilter').val('all');
                $('#dateFilter').val('all');
                filterBookings();
            });
            
            function filterBookings() {
                const statusFilter = $('#statusFilter').val();
                const dateFilter = $('#dateFilter').val();
                
                $('.booking-row').each(function() {
                    let showRow = true;
                    
                    // Filter by status
                    if (statusFilter !== 'all') {
                        const rowStatus = $(this).data('status');
                        if (rowStatus !== statusFilter) {
                            showRow = false;
                        }
                    }
                    
                    // Filter by date
                    if (dateFilter !== 'all' && showRow) {
                        const checkInDate = new Date($(this).find('td:eq(3)').text().trim());
                        const checkOutDate = new Date($(this).find('td:eq(4)').text().trim());
                        const today = new Date();
                        
                        if (dateFilter === 'upcoming' && checkInDate <= today) {
                            showRow = false;
                        } else if (dateFilter === 'past' && checkOutDate >= today) {
                            showRow = false;
                        } else if (dateFilter === 'current' && (checkInDate > today || checkOutDate < today)) {
                            showRow = false;
                        }
                    }
                    
                    // Show or hide row
                    if (showRow) {
                        $(this).fadeIn(300);
                    } else {
                        $(this).fadeOut(300);
                    }
                });
                
                // Check if any rows are visible
                setTimeout(function() {
                    if ($('.booking-row:visible').length === 0) {
                        if ($('#noResultsMessage').length === 0) {
                            $('#bookingsTable').after('<div id="noResultsMessage" class="alert alert-info mt-3"><i class="fas fa-info-circle mr-2"></i>No bookings match your filter criteria.</div>');
                        }
                    } else {
                        $('#noResultsMessage').remove();
                    }
                }, 350);
            }
            
            // Highlight row on hover
            $('.booking-row').hover(
                function() {
                    $(this).css('background-color', '#f8f9fa');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
            
            // Confirm cancellation
            $('form').on('submit', function(e) {
                const confirmed = confirm('Are you sure you want to cancel this booking? This action cannot be undone.');
                if (!confirmed) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
            
            // Add animation to newly added elements
            if ($('.alert-success').length) {
                setTimeout(function() {
                    $('.alert-success').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    </script>
</body>
</html>
