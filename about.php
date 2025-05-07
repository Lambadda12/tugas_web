<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Green Oasis Hotel</title>
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
        
        /* About page specific styles */
        .about-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://source.unsplash.com/random/1200x600/?eco-hotel');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0 100px;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }
        
        .about-hero:before {
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
        
        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 0;
        }
        
        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 25px;
        }
        
        .about-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.7s ease;
        }
        
        .about-image:hover img {
            transform: scale(1.05);
        }
        
        .about-image:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
            z-index: 1;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .about-image:hover:before {
            opacity: 1;
        }
        
        .about-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            border-bottom: 4px solid transparent;
        }
        
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
            border-bottom: 4px solid var(--primary-green);
        }
        
        .about-card-body {
            padding: 30px;
        }
        
        .about-card-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .about-card-text {
            color: #666;
            line-height: 1.7;
        }
        
        .facility-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .facility-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(46, 139, 87, 0.1);
        }
        
        .facility-icon {
            width: 50px;
            height: 50px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: var(--primary-green);
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .facility-item:hover .facility-icon {
            background-color: var(--primary-green);
            color: white;
        }
        
        .facility-text {
            font-weight: 500;
            color: #555;
        }
        
        .team-section {
            padding: 80px 0;
            background-color: #f5f9f7;
            position: relative;
            overflow: hidden;
        }
        
        .team-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBmaWxsPSIjMmU4YjU3IiBmaWxsLW9wYWNpdHk9IjAuMDMiIGQ9Ik0zMCAwbDMwIDMwLTMwIDMwTDAgMzAgMzAgMHptMCAxMkwxMiAzMGwxOCAxOCAxOC0xOEwzMCAxMnoiLz48L3N2Zz4=');
            opacity: 0.5;
        }
        
        .team-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            margin-bottom: 30px;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
        }
        
        .team-img-container {
            position: relative;
            overflow: hidden;
            height: 300px;
        }
        
        .team-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .team-card:hover .team-img {
            transform: scale(1.1);
        }
        
        .team-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            color: white;
            transform: translateY(100%);
            transition: all 0.3s ease;
        }
        
        .team-card:hover .team-overlay {
            transform: translateY(0);
        }
        
        .team-content {
            padding: 25px;
            text-align: center;
        }
        
        .team-name {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 5px;
            font-size: 1.3rem;
        }
        
        .team-position {
            color: var(--primary-green);
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .team-bio {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .team-social {
            display: flex;
            justify-content: center;
        }
        
        .social-link {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-green);
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-3px);
        }
        
        .timeline-section {
            padding: 80px 0;
            position: relative;
        }
        
        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: var(--light-green);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
            border-radius: 10px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: white;
            border: 4px solid var(--primary-green);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-left {
            left: 0;
        }
        
        .timeline-right {
            left: 50%;
        }
        
        .timeline-right::after {
            left: -12px;
        }
        
        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            position: relative;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(46, 139, 87, 0.1);
        }
        
        .timeline-year {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .timeline-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }
        
        .timeline-text {
            color: #666;
            line-height: 1.6;
        }
        
        .values-section {
            padding: 80px 0;
            background-color: #f5f9f7;
            position: relative;
            overflow: hidden;
        }
        
        .value-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            text-align: center;
            padding: 40px 30px;
            border-bottom: 4px solid transparent;
        }
        
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
            border-bottom: 4px solid var(--primary-green);
        }
        
        .value-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .value-icon:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px dashed var(--primary-green);
            animation: spin 20s linear infinite;
            opacity: 0.3;
        }
        
        .value-card:hover .value-icon {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
        }
        
        .value-card:hover .value-icon i {
            color: white;
            transform: rotateY(360deg);
        }
        
        .value-icon i {
            font-size: 35px;
            color: var(--primary-green);
            transition: all 0.6s ease;
        }
        
        .value-title {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .value-text {
            color: #666;
            line-height: 1.7;
        }
        
        .contact-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            padding: 30px;
        }
        
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.1);
        }
        
        .contact-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .contact-icon {
            width: 40px;
            height: 40px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-green);
            transition: all 0.3s ease;
        }
        
        .contact-card:hover .contact-icon {
            background-color: var(--primary-green);
            color: white;
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
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item::after {
                left: 18px;
            }
            
            .timeline-right {
                left: 0;
            }
        }
        
        @media (max-width: 767px) {
            .about-hero {
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
            
            .section-title {
                font-size: 1.8rem;
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
    
    <div class="about-hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1 class="hero-title">About Green Oasis Hotel</h1>
                <p class="hero-subtitle">Discover our story, our values, and our commitment to providing exceptional eco-friendly luxury experiences.</p>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image">
                    <img src="https://source.unsplash.com/random/600x400/?eco-hotel" alt="Green Oasis Hotel">
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="section-title">Our Story</h2>
                <p class="about-text">Founded in 2010, Green Oasis Hotel was born from a vision to create a luxury hospitality experience that harmonizes with nature rather than exploiting it. Our founders, passionate environmentalists and hospitality experts, set out to prove that sustainability and luxury can coexist beautifully.</p>
                <p class="about-text">Over the years, we have grown from a small eco-lodge to a renowned destination for environmentally conscious travelers seeking comfort without compromise. Our commitment to sustainable practices has earned us numerous awards and certifications in the hospitality industry.</p>
                <p class="about-text">Today, Green Oasis Hotel stands as a testament to our unwavering dedication to providing exceptional service while protecting the planet for future generations.</p>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-12 text-center" data-aos="fade-up">
                <h2 class="page-title">Our Mission & Vision</h2>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="about-card">
                    <div class="about-card-body">
                        <h3 class="about-card-title">Our Mission</h3>
                        <p class="about-card-text">To provide exceptional hospitality experiences that delight our guests while minimizing environmental impact through innovative sustainable practices. We strive to be a model for eco-friendly luxury in the hospitality industry, proving that comfort and conservation can go hand in hand.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="about-card">
                    <div class="about-card-body">
                        <h3 class="about-card-title">Our Vision</h3>
                        <p class="about-card-text">To be recognized globally as the leading eco-friendly luxury hotel chain, inspiring a shift in the hospitality industry towards more sustainable practices. We envision a future where every guest leaves not only with wonderful memories but also with a deeper appreciation for environmental stewardship.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-12" data-aos="fade-up">
                <h2 class="section-title">Our Facilities</h2>
                <p class="about-text mb-4">At Green Oasis Hotel, we offer a wide range of amenities and facilities designed to make your stay comfortable, enjoyable, and memorable. All our facilities are designed with sustainability in mind, using eco-friendly materials and energy-efficient systems.</p>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <div class="facility-text">24/7 Front Desk Service</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div class="facility-text">Free High-Speed Wi-Fi Throughout the Hotel</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="facility-text">Organic Farm-to-Table Restaurant</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-cocktail"></i>
                    </div>
                    <div class="facility-text">Eco-Friendly Bar with Sustainable Beverages</div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-swimming-pool"></i>
                    </div>
                    <div class="facility-text">Solar-Heated Swimming Pool</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="facility-text">Wellness Center with Natural Treatments</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="facility-text">Fitness Center with Energy-Generating Equipment</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="facility-text">Organic Garden Tours and Workshops</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Our Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5" data-aos="fade-up">
                    <h2 class="page-title">Our Core Values</h2>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3 class="value-title">Sustainability</h3>
                        <p class="value-text">We are committed to minimizing our environmental footprint through innovative eco-friendly practices in all aspects of our operations, from energy and water conservation to waste reduction and sustainable sourcing.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="value-title">Excellence</h3>
                        <p class="value-text">We strive for excellence in everything we do, from the quality of our accommodations and services to the training of our staff. We believe that sustainability should never compromise luxury or comfort.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3 class="value-title">Community</h3>
                        <p class="value-text">We value our connection to the local community and strive to make a positive impact through fair employment practices, supporting local businesses, and engaging in community development initiatives.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Journey Timeline -->
    <section class="timeline-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5" data-aos="fade-up">
                    <h2 class="page-title">Our Journey</h2>
                </div>
            </div>
        </div>
        
        <div class="timeline">
            <div class="timeline-item timeline-left" data-aos="fade-right">
                <div class="timeline-content">
                    <div class="timeline-year">2010</div>
                    <h3 class="timeline-title">The Beginning</h3>
                    <p class="timeline-text">Green Oasis Hotel was founded with a vision to create a luxury eco-friendly hospitality experience. Our first property opened with just 15 rooms.</p>
                </div>
            </div>
            <div class="timeline-item timeline-right" data-aos="fade-left">
                <div class="timeline-content">
                    <div class="timeline-year">2013</div>
                    <h3 class="timeline-title">Expansion</h3>
                    <p class="timeline-text">After our initial success, we expanded our property to include 30 additional rooms and introduced our farm-to-table restaurant concept.</p>
                </div>
            </div>
            <div class="timeline-item timeline-left" data-aos="fade-right">
                <div class="timeline-content">
                    <div class="timeline-year">2015</div>
                    <h3 class="timeline-title">Sustainability Award</h3>
                    <p class="timeline-text">Our commitment to eco-friendly practices was recognized with our first major sustainability award in the hospitality industry.</p>
                </div>
            </div>
            <div class="timeline-item timeline-right" data-aos="fade-left">
                <div class="timeline-content">
                    <div class="timeline-year">2018</div>
                    <h3 class="timeline-title">Wellness Center</h3>
                    <p class="timeline-text">We opened our state-of-the-art wellness center featuring natural treatments and a solar-heated swimming pool.</p>
                </div>
            </div>
            <div class="timeline-item timeline-left" data-aos="fade-right">
                <div class="timeline-content">
                    <div class="timeline-year">2020</div>
                    <h3 class="timeline-title">Carbon Neutral</h3>
                    <p class="timeline-text">We achieved our goal of becoming a carbon-neutral hotel through renewable energy implementation and carbon offset programs.</p>
                </div>
            </div>
            <div class="timeline-item timeline-right" data-aos="fade-left">
                <div class="timeline-content">
                    <div class="timeline-year">2023</div>
                    <h3 class="timeline-title">Today</h3>
                    <p class="timeline-text">Today, Green Oasis Hotel continues to lead the way in sustainable luxury hospitality, constantly innovating and improving our practices.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Team Section -->
    <section class="team-section">
        <div class="team-pattern"></div>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5" data-aos="fade-up">
                    <h2 class="page-title">Meet Our Team</h2>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Doe" class="team-img">
                            <div class="team-overlay">
                                <p>"I believe that luxury hospitality and environmental responsibility can go hand in hand. That's what drives us at Green Oasis."</p>
                            </div>
                        </div>
                        <div class="team-content">
                            <h3 class="team-name">John Doe</h3>
                            <p class="team-position">Founder & CEO</p>
                            <p class="team-bio">With over 20 years of experience in the hospitality industry, John founded Green Oasis Hotel with a vision to revolutionize eco-friendly luxury.</p>
                            <div class="team-social">
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Jane Smith" class="team-img">
                            <div class="team-overlay">
                                <p>"Creating exceptional guest experiences while preserving our environment is not just our job—it's our passion."</p>
                            </div>
                        </div>
                        <div class="team-content">
                            <h3 class="team-name">Jane Smith</h3>
                            <p class="team-position">Operations Director</p>
                            <p class="team-bio">Jane oversees all hotel operations, ensuring that our sustainability practices are implemented without compromising on service quality.</p>
                            <div class="team-social">
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="team-card">
                        <div class="team-img-container">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Robert Johnson" class="team-img">
                            <div class="team-overlay">
                                <p>"Our culinary philosophy is simple: local, organic, and delicious. We let the natural flavors speak for themselves."</p>
                            </div>
                        </div>
                        <div class="team-content">
                            <h3 class="team-name">Robert Johnson</h3>
                            <p class="team-position">Executive Chef</p>
                            <p class="team-bio">Chef Robert leads our culinary team, creating exquisite farm-to-table dishes using organic, locally-sourced ingredients.</p>
                            <div class="team-social">
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Information -->
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 text-center mb-5" data-aos="fade-up">
                <h2 class="page-title">Contact Information</h2>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card">
                    <h3 class="contact-title">Reach Us</h3>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">123 Nature Way, Eco District, Green City</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text"><a href="tel:+123456789">+123 456 789</a></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text"><a href="mailto:info@greenoasishotel.com">info@greenoasishotel.com</a></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">Reception: 24/7<br>Restaurant: 6:30 AM - 10:30 PM</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card">
                    <h3 class="contact-title">Connect With Us</h3>
                    <div class="embed-responsive embed-responsive-16by9 mb-4">
                        <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2904629576855!2d106.82796431476884!3d-6.226305395493599!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3f03a40784d%3A0x5d3a7c56a8366b9!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2sus!4v1625647158909!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <div class="text-center">
                        <a href="contact.php" class="btn btn-custom">
                            <i class="fas fa-paper-plane mr-2"></i> Send Us a Message
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center" data-aos="fade-up">
                    <h3 class="mb-4">Experience the Green Oasis Difference</h3>
                    <p class="mb-4">Ready to experience luxury that doesn't cost the Earth? Book your stay with us today and discover the perfect balance of comfort, elegance, and environmental responsibility.</p>
                    <a href="rooms.php" class="btn btn-custom">
                        <i class="fas fa-bed mr-2"></i> Explore Our Rooms
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

