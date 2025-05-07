<?php
session_start();
include 'config/db_connect.php';

$email = '';
$password = '';
$errors = ['email' => '', 'password' => '', 'login' => ''];

if(isset($_POST['submit'])) {
    // Validate email
    if(empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }
    
    // Validate password
    if(empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }
    
    // If no validation errors, attempt login
    if(!array_filter($errors)) {
        // Check for admin login first (hardcoded for simplicity)
        if($email === 'admin@example.com' && $password === 'admin123') {
            // Admin login successful
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_name'] = 'Admin';
            
            header('Location: admin/index.php');
            exit();
        }
        
        // If not admin, check regular user login
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if(password_verify($password, $user['password'])) {
                // User login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                header('Location: index.php');
                exit();
            } else {
                $errors['login'] = 'Invalid email or password';
            }
        } else {
            $errors['login'] = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Green Oasis Hotel</title>
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
        
        /* Login specific styles */
        .login-section {
            padding: 100px 0;
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .login-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.1) 0%, rgba(0, 100, 0, 0.05) 100%);
            z-index: -1;
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            position: relative;
        }
        
        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .login-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-green), var(--forest-green));
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .login-header h4 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .login-header:after {
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
        
        .login-header:before {
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
        
        .login-body {
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
        
        .btn-login {
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
        
        .btn-login:before {
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
        
        .btn-login:hover:before {
            left: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            box-shadow: 0 8px 20px rgba(0, 100, 0, 0.3);
            transform: translateY(-3px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-weight: 500;
        }
        
        .register-link a {
            color: var(--primary-green);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .register-link a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            bottom: -2px;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .register-link a:hover:after {
            width: 100%;
        }
        
        .register-link a:hover {
            color: var(--forest-green);
            text-decoration: none;
        }
        
        .admin-note {
            text-align: center;
            margin-top: 10px;
            font-size: 0.85rem;
            color: #777;
            font-style: italic;
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
        
        .alert-success {
            background-color: #e8f5e9;
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
        }
        
        /* Floating shapes for animation */
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            background-color: rgba(46, 139, 87, 0.05);
            backdrop-filter: blur(3px);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }
        
        .shape-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-duration: 25s;
        }
        
        .shape-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            left: 80%;
            animation-duration: 30s;
            animation-delay: 2s;
        }
        
        .shape-3 {
            width: 70px;
            height: 70px;
            top: 30%;
            left: 60%;
            animation-duration: 20s;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(100px, 50px) rotate(90deg);
            }
            50% {
                transform: translate(50px, 100px) rotate(180deg);
            }
            75% {
                transform: translate(-50px, 50px) rotate(270deg);
            }
            100% {
                transform: translate(0, 0) rotate(360deg);
            }
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
            .login-section {
                padding: 60px 0;
            }
            
            .login-card:before {
                width: 100%;
                height: 5px;
                top: 0;
                left: 0;
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
    
    <section class="login-section">
        <!-- Floating shapes for animation -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5" data-aos="fade-up">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php 
                                echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card login-card">
                        <div class="login-header">
                            <h4><i class="fas fa-user-circle mr-2"></i> Login to Your Account</h4>
                        </div>
                        <div class="login-body">
                            <?php if($errors['login']): ?>
                                <div class="alert alert-danger animate__animated animate__shakeX">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <?php echo $errors['login']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="login.php" method="POST">
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope mr-2"></i>Email Address</label>
                                    <input type="email" class="form-control <?php echo $errors['email'] ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" placeholder="Enter your email">
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="password"><i class="fas fa-lock mr-2"></i>Password</label>
                                    <input type="password" class="form-control <?php echo $errors['password'] ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter your password">
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <button type="submit" name="submit" class="btn btn-login btn-block">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </button>
                            </form>
                            
                            <div class="register-link">
                                <p>Don't have an account? <a href="register.php">Register Now</a></p>
                            </div>
                            
                            <div class="admin-note">
                                <p><i class="fas fa-info-circle mr-1"></i> Admin can login with admin credentials</p>
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
        
        // Form validation enhancement
        $('form').on('submit', function() {
            let isValid = true;
            
            if ($('#email').val() === '') {
                $('#email').addClass('is-invalid');
                isValid = false;
            } else {
                $('#email').removeClass('is-invalid');
            }
            
            if ($('#password').val() === '') {
                $('#password').addClass('is-invalid');
                isValid = false;
            } else {
                $('#password').removeClass('is-invalid');
            }
            
            return isValid;
        });
        
        // Input focus effect
        $('.form-control').focus(function() {
            $(this).parent().find('label').addClass('text-primary');
        }).blur(function() {
            $(this).parent().find('label').removeClass('text-primary');
        });
    </script>
</body>
</html>
