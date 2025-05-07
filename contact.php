<?php
session_start();
include 'config/db_connect.php';

$name = $email = $subject = $message = '';
$errors = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$success = '';

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
    }
    
    // Validate subject
    if(empty($_POST['subject'])) {
        $errors['subject'] = 'Subject is required';
    } else {
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    }
    
    // Validate message
    if(empty($_POST['message'])) {
        $errors['message'] = 'Message is required';
    } else {
        $message = mysqli_real_escape_string($conn, $_POST['message']);
    }
    
    // If no errors, process form
    if(!array_filter($errors)) {
        // In a real application, you would send an email or store the message in a database
        // For this example, we'll just show a success message
        $success = 'Thank you for your message! We will get back to you soon.';
        $name = $email = $subject = $message = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Green Oasis Hotel</title>
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
        
        /* Contact page specific styles */
        .contact-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://source.unsplash.com/random/1200x600/?hotel-reception');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0 100px;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }
        
        .contact-hero:before {
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
        
        .contact-form-container {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 40px;
            transition: all 0.4s ease;
            margin-bottom: 30px;
        }
        
        .contact-form-container:hover {
            box-shadow: 0 15px 40px rgba(46, 139, 87, 0.1);
        }
        
        .form-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 15px;
        }
        
        .form-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 0;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
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
        }
        
        .contact-info-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            padding: 30px;
        }
        
        .contact-info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
        }
        
        .contact-info-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .contact-info-title:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background-color:#e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-green);
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .contact-info-card:hover .contact-icon {
            background-color: var(--primary-green);
            color: white;
            transform: scale(1.1);
        }
        
        .contact-text {
            color: #555;
            font-weight: 500;
        }
        
        .contact-text a {
            color: #555;
            transition: all 0.3s ease;
        }
        
        .contact-text a:hover {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-green);
            margin: 0 10px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .social-link:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-5px);
            text-decoration: none;
        }
        
        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .map-container:hover {
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
        }
        
        .faq-section {
            padding: 80px 0;
            background-color: #f5f9f7;
            position: relative;
            overflow: hidden;
        }
        
        .faq-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBmaWxsPSIjMmU4YjU3IiBmaWxsLW9wYWNpdHk9IjAuMDMiIGQ9Ik0zMCAwbDMwIDMwLTMwIDMwTDAgMzAgMzAgMHptMCAxMkwxMiAzMGwxOCAxOCAxOC0xOEwzMCAxMnoiLz48L3N2Zz4=');
            opacity: 0.5;
        }
        
        .accordion .card {
            border: none;
            margin-bottom: 15px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .accordion .card:hover {
            box-shadow: 0 10px 25px rgba(46, 139, 87, 0.1);
        }
        
        .accordion .card-header {
            background-color: white;
            border-bottom: none;
            padding: 0;
        }
        
        .accordion .btn-link {
            color: var(--dark-green);
            font-weight: 600;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            padding: 20px;
            position: relative;
            font-family: 'Playfair Display', serif;
        }
        
        .accordion .btn-link:hover {
            text-decoration: none;
        }
        
        .accordion .btn-link:after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }
        
        .accordion .btn-link.collapsed:after {
            transform: translateY(-50%) rotate(-90deg);
        }
        
        .accordion .card-body {
            padding: 0 20px 20px;
            color: #555;
            line-height: 1.7;
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
            
            .contact-info-card {
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 767px) {
            .contact-hero {
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
            
            .form-title {
                font-size: 1.6rem;
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
    
    <div class="contact-hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1 class="hero-title">Contact Us</h1>
                <p class="hero-subtitle">We'd love to hear from you. Reach out to us with any questions, feedback, or booking inquiries.</p>
            </div>
        </div>
    </div>
    
    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="contact-form-container">
                    <h2 class="form-title">Send Us a Message</h2>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="contact.php" method="POST">
                        <div class="form-group">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control <?php echo $errors['name'] ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $name; ?>" placeholder="Enter your name">
                            <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?php echo $errors['email'] ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $email; ?>" placeholder="Enter your email">
                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control <?php echo $errors['subject'] ? 'is-invalid' : ''; ?>" id="subject" name="subject" value="<?php echo $subject; ?>" placeholder="Enter subject">
                            <div class="invalid-feedback"><?php echo $errors['subject']; ?></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control <?php echo $errors['message'] ? 'is-invalid' : ''; ?>" id="message" name="message" rows="5" placeholder="Enter your message"><?php echo $message; ?></textarea>
                            <div class="invalid-feedback"><?php echo $errors['message']; ?></div>
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-custom">
                            <i class="fas fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5" data-aos="fade-left">
                <div class="contact-info-card mb-4">
                    <h3 class="contact-info-title">Contact Information</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            123 Nature Way, Eco District<br>
                            Green City, Country
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <a href="tel:+123456789">+123 456 789</a><br>
                            <a href="tel:+987654321">+987 654 321</a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <a href="mailto:info@greenoasishotel.com">info@greenoasishotel.com</a><br>
                            <a href="mailto:reservations@greenoasishotel.com">reservations@greenoasishotel.com</a>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">
                            <strong>Reception:</strong> 24/7<br>
                            <strong>Restaurant:</strong> 6:30 AM - 10:30 PM
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="map-container" data-aos="fade-up" data-aos-delay="200">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2904629576855!2d106.82796431476884!3d-6.226305395493599!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3f03a40784d%3A0x5d3a7c56a8366b9!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2sus!4v1625647158909!5m2!1sen!2sus" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-pattern"></div>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5" data-aos="fade-up">
                    <h2 class="page-title">Frequently Asked Questions</h2>
                </div>
                
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <div class="accordion" id="faqAccordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        What are your check-in and check-out times?
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Our standard check-in time is 2:00 PM and check-out time is 12:00 PM. Early check-in and late check-out may be available upon request, subject to availability and additional charges may apply.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Do you offer airport transfers?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Yes, we offer airport transfer services using our eco-friendly electric vehicles. You can arrange this service during booking or by contacting our concierge at least 24 hours before your arrival.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Is breakfast included in the room rate?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Yes, our room rates include a complimentary organic breakfast buffet featuring locally sourced ingredients. Breakfast is served from 6:30 AM to 10:30 AM in our main restaurant.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        What sustainability practices does the hotel follow?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Our hotel implements numerous sustainability practices including solar power generation, rainwater harvesting, waste recycling programs, energy-efficient systems, plastic-free initiatives, and supporting local communities through responsible sourcing.
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header" id="headingFive">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Do you have facilities for guests with disabilities?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordion">
                                <div class="card-body">
                                    Yes, we have specially designed rooms and facilities for guests with disabilities. Our property features wheelchair-accessible paths, elevators, and bathrooms. Please inform us of any specific requirements when booking.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center" data-aos="fade-up">
                    <h3 class="mb-4">Ready to Experience Green Oasis?</h3>
                    <p class="mb-4">Book your stay with us today and discover the perfect balance of luxury, comfort, and environmental responsibility.</p>
                    <a href="rooms.php" class="btn btn-custom">
                        <i class="fas fa-bed mr-2"></i> Book Your Stay Now
                    </a>
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
                $('.scroll-to-top').removeClass('active');
            }
        });
        
        $('.scroll-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    </script>
</body>
</html>

