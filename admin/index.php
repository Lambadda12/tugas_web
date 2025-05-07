<?php
session_start();
include '../config/db_connect.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get statistics
// Total rooms
$sql = "SELECT COUNT(*) as total FROM rooms";
$result = mysqli_query($conn, $sql);
$total_rooms = mysqli_fetch_assoc($result)['total'];

// Available rooms
$sql = "SELECT COUNT(*) as available FROM rooms WHERE status = 'available'";
$result = mysqli_query($conn, $sql);
$available_rooms = mysqli_fetch_assoc($result)['available'];

// Total reservations
$sql = "SELECT COUNT(*) as total FROM reservations";
$result = mysqli_query($conn, $sql);
$total_reservations = mysqli_fetch_assoc($result)['total'];

// Active reservations
$sql = "SELECT COUNT(*) as active FROM reservations WHERE status = 'confirmed'";
$result = mysqli_query($conn, $sql);
$active_reservations = mysqli_fetch_assoc($result)['active'];

// Total users
$sql = "SELECT COUNT(*) as total FROM users";
$result = mysqli_query($conn, $sql);
$total_users = mysqli_fetch_assoc($result)['total'];

// Recent reservations
$sql = "SELECT r.*, u.name as user_name, u.email as user_email, rm.room_number, rt.name as room_type 
        FROM reservations r 
        JOIN users u ON r.user_id = u.id 
        JOIN rooms rm ON r.room_id = rm.id 
        JOIN room_types rt ON rm.room_type_id = rt.id 
        ORDER BY r.created_at DESC LIMIT 5";
$result = mysqli_query($conn, $sql);
$recent_reservations = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hotel Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #81C784;
            --light-color: #E8F5E9;
            --dark-color: #1B5E20;
            --success-color: #43A047;
            --info-color: #26A69A;
            --warning-color: #FFA000;
            --danger-color: #E53935;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .sidebar {
            background: var(--primary-color);
            background: linear-gradient(180deg, var(--dark-color) 0%, var(--primary-color) 100%);
            color: white;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .logo-container {
            padding: 20px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
        }
        
        .logo {
            max-width: 80%;
            height: auto;
        }
        
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            overflow: hidden;
            border: none;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .card-icon {
            background-color: rgba(255,255,255,0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
        }
        
        .bg-warning {
            background-color: var(--warning-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
        }
        
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .table th {
            border-top: none;
            border-bottom: 2px solid var(--accent-color);
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .badge-success {
            background-color: var(--success-color);
        }
        
        .badge-danger {
            background-color: var(--danger-color);
        }
        
        .badge-info {
            background-color: var(--info-color);
        }
        
        .badge-warning {
            background-color: var(--warning-color);
        }
        
        .btn-sm {
            border-radius: 20px;
            padding: 0.25rem 0.8rem;
        }
        
        .welcome-message {
            background: linear-gradient(120deg, var(--primary-color), var(--dark-color));
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .welcome-message h2 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .counter {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .counter-title {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-dropdown img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: none;
            padding: 10px;
        }
        
        .dropdown-item {
            border-radius: 5px;
            padding: 8px 15px;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-color);
        }
        
        .dropdown-divider {
            margin: 5px 0;
        }
        
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .quick-action-btn {
            flex: 1;
            text-align: center;
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            color: var(--dark-color);
            text-decoration: none;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .quick-action-btn i {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .progress-bar {
            background-color: var(--primary-color);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        .card-header {
            background-color: var(--light-color);
            border-bottom: none;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 text-success">Hotel Reservation System</h4>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="position-relative mr-3">
                            <a href="#" class="btn btn-light rounded-circle p-2" data-toggle="tooltip" title="Notifications">
                                <i class="fas fa-bell text-muted"></i>
                                <span class="notification-badge">3</span>
                            </a>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark dropdown-toggle" id="userDropdown" data-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name=Admin&background=2E7D32&color=fff" alt="Admin">
                                <span>Admin</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user-circle mr-2 text-success"></i> Profile
                                </a>
                                <a class="dropdown-item" href="settings.php">
                                    <i class="fas fa-cog mr-2 text-success"></i> Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt mr-2 text-success"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <div class="logo-container">
                    <img src="https://via.placeholder.com/150x50/2E7D32/FFFFFF?text=HOTEL+ADMIN" alt="Hotel Logo" class="logo">
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservations.php">
                            <i class="fas fa-calendar-check"></i> Reservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rooms.php">
                            <i class="fas fa-bed"></i> Rooms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="room_types.php">
                            <i class="fas fa-door-open"></i> Room Types
                        </a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <main class="col-md-10 ml-sm-auto px-4 py-4">
                <div class="welcome-message animate__animated animate__fadeIn">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2>Welcome back, Admin!</h2>
                            <p>Here's what's happening with your hotel today.</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <p class="mb-0"><?php echo date('l, d F Y'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.1s;">
                        <div class="dashboard-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="counter-title">Total Rooms</p>
                                        <h2 class="counter"><?php echo $total_rooms; ?></h2>
                                        <p class="mb-0 mt-2"><small>All hotel rooms</small></p>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-bed fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.2s;">
                        <div class="dashboard-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="counter-title">Available Rooms</p>
                                        <h2 class="counter"><?php echo $available_rooms; ?></h2>
                                        <div class="progress">
                                            <div class="progress-bar bg-light" style="width: <?php echo ($available_rooms/$total_rooms)*100; ?>%"></div>
                                        </div>
                                        <p class="mb-0 mt-2"><small><?php echo round(($available_rooms/$total_rooms)*100); ?>% availability</small></p>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-door-open fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.3s;">
                        <div class="dashboard-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="counter-title">Reservations</p>
                                        <h2 class="counter"><?php echo $total_reservations; ?></h2>
                                        <p class="mb-0 mt-2"><small><?php echo $active_reservations; ?> active now</small></p>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-calendar-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-4 fade-in-up" style="animation-delay: 0.4s;">
                        <div class="dashboard-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="counter-title">Total Users</p>
                                        <h2 class="counter"><?php echo $total_users; ?></h2>
                                        <p class="mb-0 mt-2"><small>Registered guests</small></p>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="quick-actions fade-in-up" style="animation-delay: 0.5s;">
                    <a href="add_reservation.php" class="quick-action-btn">
                        <i class="fas fa-calendar-plus"></i>
                        <div>New Reservation</div>
                    </a>
                    <a href="add_room.php" class="quick-action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <div>Add Room</div>
                    </a>
                    <a href="add_user.php" class="quick-action-btn">
                        <i class="fas fa-user-plus"></i>
                        <div>Add User</div>
                    </a>
                    <a href="reports.php" class="quick-action-btn">
                        <i class="fas fa-chart-line"></i>
                        <div>View Reports</div>
                    </a>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4 fade-in-up" style="animation-delay: 0.6s;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Reservations</h5>
                                <a href="reservations.php" class="btn btn-sm btn-outline-success">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Guest</th>
                                                <th>Room</th>
                                                <th>Check-in</th>
                                                <th>Check-out</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recent_reservations as $reservation): ?>
                                                <tr class="animate__animated animate__fadeIn" style="animation-delay: <?php echo 0.1 * $loop_count ?? 0; ?>s;">
                                                    <td>#<?php echo $reservation['id']; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($reservation['user_name']); ?>&background=4CAF50&color=fff" alt="User" class="mr-2" style="width: 30px; height: 30px; border-radius: 50%;">
                                                            <div>
                                                                <?php echo $reservation['user_name']; ?><br>
                                                                <small class="text-muted"><?php echo $reservation['user_email']; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo $reservation['room_type']; ?><br>
                                                        <small class="text-muted">Room <?php echo $reservation['room_number']; ?></small>
                                                    </td>
                                                    <td><?php echo date('d M Y', strtotime($reservation['check_in_date'])); ?></td>
                                                    <td><?php echo date('d M Y', strtotime($reservation['check_out_date'])); ?></td>
                                                    <td>Rp <?php echo number_format($reservation['total_price'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <?php if($reservation['status'] == 'confirmed'): ?>
                                                            <span class="badge badge-success">Confirmed</span>
                                                        <?php elseif($reservation['status'] == 'cancelled'): ?>
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        <?php elseif($reservation['status'] == 'completed'): ?>
                                                            <span class="badge badge-info">Completed</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" href="reservation_details.php?id=<?php echo $reservation['id']; ?>">
                                                                    <i class="fas fa-eye text-info mr-2"></i> View Details
                                                                </a>
                                                                <a class="dropdown-item" href="edit_reservation.php?id=<?php echo $reservation['id']; ?>">
                                                                    <i class="fas fa-edit text-primary mr-2"></i> Edit
                                                                </a>
                                                                <?php if($reservation['status'] == 'pending'): ?>
                                                                <a class="dropdown-item" href="confirm_reservation.php?id=<?php echo $reservation['id']; ?>">
                                                                    <i class="fas fa-check text-success mr-2"></i> Confirm
                                                                </a>
                                                                <?php endif; ?>
                                                                <?php if($reservation['status'] != 'cancelled' && $reservation['status'] != 'completed'): ?>
                                                                <a class="dropdown-item" href="cancel_reservation.php?id=<?php echo $reservation['id']; ?>">
                                                                    <i class="fas fa-times text-danger mr-2"></i> Cancel
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            
                                            <?php if(empty($recent_reservations)): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <img src="https://img.icons8.com/color/96/000000/empty-box.png" alt="No Data" style="width: 64px; height: 64px;">
                                                        <p class="mt-3 text-muted">No reservations found</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-4 fade-in-up" style="animation-delay: 0.7s;">
                            <div class="card-header">
                                <h5 class="mb-0">Room Occupancy</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <canvas id="occupancyChart" width="200" height="200"></canvas>
                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <h3 class="mb-0"><?php echo round(100 - (($available_rooms/$total_rooms)*100)); ?>%</h3>
                                            <p class="text-muted mb-0">Occupied</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Available Rooms</span>
                                    <span class="text-success"><?php echo $available_rooms; ?></span>
                                </div>
                                <div class="progress mb-4" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo ($available_rooms/$total_rooms)*100; ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Occupied Rooms</span>
                                    <span class="text-danger"><?php echo $total_rooms - $available_rooms; ?></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: <?php echo (($total_rooms - $available_rooms)/$total_rooms)*100; ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4 fade-in-up" style="animation-delay: 0.8s;">
                            <div class="card-header">
                                <h5 class="mb-0">Upcoming Check-ins</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php 
                                    // This is a placeholder - you would need to add the actual query for upcoming check-ins
                                    $has_upcoming = false;
                                    foreach($recent_reservations as $index => $res): 
                                        if($index < 3 && strtotime($res['check_in_date']) >= strtotime('today')):
                                            $has_upcoming = true;
                                        ?>
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo $res['user_name']; ?></h6>
                                                        <p class="text-muted mb-0">Room <?php echo $res['room_number']; ?> (<?php echo $res['room_type']; ?>)</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="badge badge-primary"><?php echo date('d M', strtotime($res['check_in_date'])); ?></span>
                                                        <p class="text-muted mb-0"><small><?php echo date('D', strtotime($res['check_in_date'])); ?></small></p>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        
                                        if(!$has_upcoming):
                                        ?>
                                            <li class="list-group-item text-center py-4">
                                                <i class="fas fa-calendar-day text-muted" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-0 text-muted">No upcoming check-ins</p>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                    <div class="card-footer bg-white text-center">
                                        <a href="check_ins.php" class="text-success">View all check-ins</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4 fade-in-up" style="animation-delay: 0.9s;">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Notes</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Write a note..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">Save Note</button>
                                    </form>
                                    <hr>
                                    <div class="note-item p-2 mb-2 bg-light rounded">
                                        <p class="mb-1">Check room 203 AC maintenance</p>
                                        <small class="text-muted">Added 2 hours ago</small>
                                    </div>
                                    <div class="note-item p-2 mb-2 bg-light rounded">
                                        <p class="mb-1">Call supplier about new towels</p>
                                        <small class="text-muted">Added yesterday</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4 fade-in-up" style="animation-delay: 1s;">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Reservation Statistics</h5>
                                    <div>
                                        <select class="custom-select custom-select-sm" id="statPeriod">
                                            <option value="week">This Week</option>
                                            <option value="month" selected>This Month</option>
                                            <option value="year">This Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="reservationChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <footer class="mt-5 mb-3">
                        <div class="text-center text-muted">
                            <p>&copy; <?php echo date('Y'); ?> Hotel Reservation System. All rights reserved.</p>
                        </div>
                    </footer>
                </main>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script>
            $(function () {
                // Initialize tooltips
                $('[data-toggle="tooltip"]').tooltip();
                
                // Animate counters
                $('.counter').each(function () {
                    $(this).prop('Counter', 0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 1000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });
                
                // Occupancy Chart
                var occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
                var occupancyChart = new Chart(occupancyCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Occupied', 'Available'],
                        datasets: [{
                            data: [<?php echo $total_rooms - $available_rooms; ?>, <?php echo $available_rooms; ?>],
                            backgroundColor: ['#E53935', '#43A047'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutoutPercentage: 75,
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                                    return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                });
                
                // Reservation Chart (example data - replace with actual data)
                var reservationCtx = document.getElementById('reservationChart').getContext('2d');
                var reservationChart = new Chart(reservationCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Reservations',
                            data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 90],
                            backgroundColor: 'rgba(46, 125, 50, 0.1)',
                            borderColor: '#2E7D32',
                            borderWidth: 2,
                            pointBackgroundColor: '#2E7D32',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                },
                                gridLines: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    zeroLineColor: 'rgba(0, 0, 0, 0.1)'
                                }
                            }],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                        legend: {
                            display: false
                        }
                    }
                });
                
                // Change chart data based on selected period
                $('#statPeriod').change(function() {
                    var period = $(this).val();
                    var newData = [];
                    var newLabels = [];
                    
                    if (period === 'week') {
                        newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        newData = [12, 19, 15, 8, 22, 30, 25];
                    } else if (period === 'month') {
                        newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                        newData = [65, 80, 56, 90];
                    } else if (period === 'year') {
                        newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        newData = [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 90];
                    }
                    
                    reservationChart.data.labels = newLabels;
                    reservationChart.data.datasets[0].data = newData;
                    reservationChart.update();
                });
                
                // Add animation to cards
                $('.fade-in-up').each(function(index) {
                    var $this = $(this);
                    setTimeout(function() {
                        $this.addClass('animate__animated animate__fadeInUp');
                    }, 100 * index);
                });
            });
        </script>
    </body>
    </html>
    