<?php
session_start();
include 'config/db_connect.php';

$name = $email = $password = $phone = '';
$errors = ['name' => '', 'email' => '', 'password' => '', 'phone' => ''];

if(isset($_POST['submit'])) {
    // Validate name
    if(empty($_POST['name'])) {
        $errors['name'] = 'Name is required';
    } else {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
    }
    
    // Validate email
    if(empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address';
        }
        
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            $errors['email'] = 'Email already exists';
        }
    }
    
    // Validate password
    if(empty($_POST['password'])) {
        $errors['password'] = 'Password is required';
    } else {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        if(strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
    }
    
    // Validate phone
    if(!empty($_POST['phone'])) {
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    }
    
    // If no errors, register user
    if(!array_filter($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$hashed_password', '$phone')";
        
        if(mysqli_query($conn, $sql)) {
            $_SESSION['success'] = 'Registration successful! You can now login.';
            header('Location: login.php');
            exit();
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Green Oasis Hotel</title>
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
        
        /* Register specific styles */
        .register-section {
            padding: 80px 0;
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .register-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.1) 0%, rgba(0, 100, 0, 0.05) 100%);
            z-index: -1;
        }
        
        .register-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            position: relative;
        }
        
        .register-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(46, 139, 87, 0.15);
        }
        
        .register-card:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-green), var(--forest-green));
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            color: white;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .register-header h4 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .register-header:after {
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
        
        .register-header:before {
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
        
        .register-body {
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
        
        .btn-register {
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
        
        .btn-register:before {
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
        
        .btn-register:hover:before {
            left: 100%;
        }
        
        .btn-register:hover {
            background: linear-gradient(to right, var(--forest-green), var(--primary-green));
            box-shadow: 0 8px 20px rgba(0, 100, 0, 0.3);
            transform: translateY(-3px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-weight: 500;
        }
        
        .login-link a {
            color: var(--primary-green);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .login-link a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            bottom: -2px;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .login-link a:hover:after {
            width: 100%;
        }
        
        .login-link a:hover {
            color: var(--forest-green);
            text-decoration: none;
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
        
        /* Password strength indicator */
        .password-strength {
            height: 5px;
            margin-top: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .strength-weak {
            background: linear-gradient(to right, #ff4757, #ff6b81);
            width: 30%;
        }
        
        .strength-medium {
            background: linear-gradient(to right, #ffa502, #ff7f50);
            width: 60%;
        }
        
        .strength-strong {
            background: linear-gradient(to right, #2ed573, #7bed9f);
            width: 100%;
        }
        
        .strength-text {
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 500;
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
            .register-section {
                padding: 60px 0;
            }
            
            .register-card:before {
                width: 100%;
                height: 5px;
                top: 0;
                right: 0;
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
    
    <section class="register-section">
        <!-- Floating shapes for animation -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5" data-aos="fade-up">
                    <div class="card register-card">
                        <div class="register-header">
                            <h4><i class="fas fa-user-plus mr-2"></i> Create an Account</h4>
                        </div>
                        <div class="register-body">
                            <form action="register.php" method="POST" id="registerForm">
                                <div class="form-group">
                                    <label for="name"><i class="fas fa-user mr-2"></i>Full Name</label>
                                    <input type="text" class="form-control <?php echo $errors['name'] ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $name; ?>" placeholder="Enter your full name">
                                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope mr-2"></i>Email Address</label>
                                    <input type="email" class="form-control <?php echo $errors['email'] ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" placeholder="Enter your email">
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password"><i class="fas fa-lock mr-2"></i>Password</label>
                                    <input type="password" class="form-control <?php echo $errors['password'] ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Create a password">
                                    <div class="password-strength" id="passwordStrength"></div>
                                    <div class="strength-text" id="strengthText"></div>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone"><i class="fas fa-phone mr-2"></i>Phone Number (optional)</label>
                                    <input type="text" class="form-control <?php echo $errors['phone'] ? 'is-invalid' : ''; ?>" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="Enter your phone number">
                                    <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                </div>
                                
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label" for="terms">I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a></label>
                                </div>
                                
                                <button type="submit" name="submit" class="btn btn-register btn-block">
                                    <i class="fas fa-user-plus mr-2"></i> Register
                                </button>
                            </form>
                            
                            <div class="login-link">
                                <p>Already have an account? <a href="login.php">Login Here</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>1. Account Registration</h6>
                    <p>By registering an account with Green Oasis Hotel, you agree to provide accurate and complete information. You are responsible for maintaining the confidentiality of your account credentials.</p>
                    
                    <h6>2. Privacy Policy</h6>
                    <p>Your personal information will be handled in accordance with our Privacy Policy. We respect your privacy and will only use your information for legitimate business purposes.</p>
                    
                    <h6>3. Booking and Cancellation</h6>
                    <p>Bookings made through your account are subject to our booking and cancellation policies. Please review these policies before making a reservation.</p>
                    
                    <h6>4. User Conduct</h6>
                    <p>You agree to use our services in a manner consistent with all applicable laws and regulations. Any misuse of our services may result in termination of your account.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="document.getElementById('terms').checked = true;">I Agree</button>
                </div>
            </div>
        </div>
    </div>
    
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
        
        // Password strength indicator
        $('#password').on('input', function() {
            var password = $(this).val();
            var strength = 0;
            
            if (password.length >= 6) {
                strength += 1;
            }
            
            if (password.match(/[A-Z]/)) {
                strength += 1;
            }
            
            if (password.match(/[0-9]/)) {
                strength += 1;
            }
            
            if (password.match(/[^a-zA-Z0-9]/)) {
                strength += 1;
            }
            
            var strengthBar = $('#passwordStrength');
            var strengthText = $('#strengthText');
            
            if (password.length === 0) {
                strengthBar.removeClass('strength-weak strength-medium strength-strong');
                strengthBar.css('width', '0');
                strengthText.text('');
            } else if (strength <= 1) {
                strengthBar.removeClass('strength-medium strength-strong').addClass('strength-weak');
                strengthText.text('Weak password').css('color', '#ff4757');
            } else if (strength <= 3) {
                strengthBar.removeClass('strength-weak strength-strong').addClass('strength-medium');
                strengthText.text('Medium password').css('color', '#ffa502');
            } else {
                strengthBar.removeClass('strength-weak strength-medium').addClass('strength-strong');
                strengthText.text('Strong password').css('color', '#2ed573');
            }
        });
        
        // Form validation enhancement
        $('#registerForm').on('submit', function() {
            let isValid = true;
            
            if ($('#name').val() === '') {
                $('#name').addClass('is-invalid');
                isValid = false;
            } else {
                $('#name').removeClass('is-invalid');
            }
            
            if ($('#email').val() === '') {
                $('#email').addClass('is-invalid');
                isValid = false;
            } else {
                $('#email').removeClass('is-invalid');
            }
            
            if ($('#password').val() === '') {
                $('#password').addClass('is-invalid');
                isValid = false;
            } else if ($('#password').val().length < 6) {
                $('#password').addClass('is-invalid');
                isValid = false;
            } else {
                $('#password').removeClass('is-invalid');
            }
            
            if (!$('#terms').is(':checked')) {
                alert('Please agree to the Terms and Conditions');
                isValid = false;
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

