<?php
session_start();
include 'config/db_connect.php';

// Get available room types
$sql = "SELECT * FROM room_types";
$result = mysqli_query($conn, $sql);
$room_types = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Oasis Hotel | Luxury & Comfort</title>
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
        
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://source.unsplash.com/random/1200x600/?luxury-hotel');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 220px 0 180px;
            margin-bottom: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero:before {
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
            padding: 40px;
            border-radius: 15px;
            background-color: rgba(0, 0, 0, 0.6);
            border-left: 5px solid var(--accent-green);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.5s;
            backdrop-filter: blur(5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            background: linear-gradient(to right, #ffffff, var(--light-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            line-height: 1.6;
        }
        
        .btn-custom {
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            border: none;
            color: white;
            padding: 14px 32px;
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
        
        .btn-outline-custom {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 13px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-outline-custom:hover {
            background-color: white;
            color: var(--dark-green);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }
        
        .section-title {
            position: relative;
            margin-bottom: 60px;
            text-align: center;
            padding-bottom: 20px;
            font-weight: 700;
            color: var(--dark-green);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .section-title:before {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: var(--primary-green);
            border-radius: 50%;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 0 5px rgba(46, 139, 87, 0.2);
        }
        
        .room-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            margin-bottom: 30px;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeIn 0.8s ease forwards;
            background: white;
        }
        
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .room-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.15);
        }
        
        .room-img-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .room-img {
            transition: all 0.7s ease;
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
        
        .room-card:hover .room-img {
            transform: scale(1.1) rotate(2deg);
        }
        
        .room-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            letter-spacing: 0.5px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .card-title {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.4rem;
            transition: all 0.3s ease;
        }
        
        .card-text {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .room-features {
            display: flex;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
            color: #666;
            background-color: #f8f9fa;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .room-card:hover .feature {
            background-color: #e8f5e9;
        }
        
        .feature i {
            color: var(--primary-green);
            margin-right: 5px;
        }
        
        .price {
            font-size: 1.4rem;
            color: var(--primary-green);
            font-weight: 700;
            margin: 15px 0;
            display: flex;
            align-items: baseline;
        }
        
        .price small {
            font-size: 0.9rem;
            color: #888;
            margin-left: 5px;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.1);
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
        
        .shape-4 {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 30%;
            animation-duration: 22s;
            animation-delay: 1s;
        }
        
        .shape-5 {
            width: 80px;
            height: 80px;
            top: 40%;
            left: 20%;
            animation-duration: 18s;
            animation-delay: 3s;
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
        
        .benefits-section {
            background-color: #f5f9f7;
            padding: 100px 0;
            margin: 80px 0;
            position: relative;
            overflow: hidden;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCIgdmlld0JveD0iMCAwIDUwIDUwIj48cGF0aCBmaWxsPSIjMmU4YjU3IiBmaWxsLW9wYWNpdHk9IjAuMDUiIGQ9Ik0yNSAwYzEzLjgwNyAwIDI1IDExLjE5MyAyNSAyNVMzOC44MDcgNTAgMjUgNTAgMCwzOC44MDcgMCwyNSAxMS4xOTMsMCAyNSwwWm0wIDE0YTExIDExIDAgMTEwIDIyIDExIDExIDAgMDEwLTIyWiIvPjwvc3ZnPg==');
        }
        
        .benefit-item {
            text-align: center;
            padding: 40px 25px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            position: relative;
            z-index: 2;
            height: 100%;
            border-bottom: 4px solid transparent;
        }
        
        .benefit-item:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(46, 139, 87, 0.15);
            border-bottom: 4px solid var(--primary-green);
        }
        
        .benefit-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .benefit-icon:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px dashed var(--primary-green);
            animation: spin 20s linear infinite;
            opacity: 0.3;
        }
        
        .benefit-item:hover .benefit-icon {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
        }
        
        .benefit-item:hover .benefit-icon i {
            color: white;
            transform: rotateY(360deg);
        }
        
        .benefit-icon i {
            font-size: 40px;
            color: var(--primary-green);
            transition: all 0.6s ease;
        }
        
        .benefit-title {
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
            font-size: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .benefit-title:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.4s ease;
        }
        
        .benefit-item:hover .benefit-title:after {
            width: 50px;
        }
        
        .leaf-bg {
            position: absolute;
            opacity: 0.05;
            z-index: 1;
            color: var(--dark-green);
        }
        
        .leaf-1 {
            top: -50px;
            left: -50px;
            font-size: 250px;
            transform: rotate(45deg);
        }
        
        .leaf-2 {
            bottom: -50px;
            right: -50px;
            font-size: 300px;
            transform: rotate(-45deg);
        }
        
        .leaf-3 {
            top: 50%;
            left: 50%;
            font-size: 200px;
            transform: translate(-50%, -50%) rotate(20deg);
            opacity: 0.03;
        }
        
        footer {
            background-color: var(--dark-green);
            color: white;
            padding: 80px 0 30px;
            position: relative;
            overflow: hidden;
        }
        
        .footer-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBmaWxsPSIjZmZmZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiIGQ9Ik0zMCAwbDMwIDMwLTMwIDMwTDAgMzAgMzAgMHptMCAxMkwxMiAzMGwxOCAxOCAxOC0xOEwzMCAxMnoiLz48L3N2Zz4=');
            opacity: 0.1;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
            font-size: 1.5rem;
            color: white;
        }
        
        .footer-title:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--accent-green), transparent);
            bottom: 0;
            left: 0;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #ccc;
            transition: all 0.3s ease;
            display: block;
            position: relative;
            padding-left: 15px;
        }
        
        .footer-links a:before {
            content: '\f105';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--accent-green);
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-green);
            text-decoration: none;
            padding-left: 20px;
        }
        
        .footer-links a:hover:before {
            opacity: 1;
            left: 5px;
        }
        
        .social-links {
            display: flex;
            margin-top: 25px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .social-links a:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, var(--primary-green), var(--accent-green));
            top: 0;
            left: -100%;
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .social-links a:hover:before {
            left: 0;
        }
        
        .social-links a:hover {
            transform: translateY(-5px) rotate(10deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .copyright {
            text-align: center;
            margin-top: 50px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #aaa;
            font-size: 0.9rem;
        }
        
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(to right, var(--primary-green), var(--forest-green));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .scroll-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Animation delays for room cards */
        .room-card:nth-child(1) { animation-delay: 0.1s; }
        .room-card:nth-child(2) { animation-delay: 0.3s; }
        .room-card:nth-child(3) { animation-delay: 0.5s; }
        .room-card:nth-child(4) { animation-delay: 0.7s; }
        
        /* Testimonials section */
        .testimonial-section {
            padding: 100px 0;
            background-color: #fff;
            position: relative;
        }
        
        .testimonial-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBmaWxsPSIjMmU4YjU3IiBmaWxsLW9wYWNpdHk9IjAuMDMiIGQ9Ik0zMCAwbDMwIDMwLTMwIDMwTDAgMzAgMzAgMHptMCAxMkwxMiAzMGwxOCAxOCAxOC0xOEwzMCAxMnoiLz48L3N2Zz4=');
            opacity: 0.5;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin: 15px;
            position: relative;
            transition: all 0.4s ease;
            border: 1px solid #f0f0f0;
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(46, 139, 87, 0.1);
            border-color: #e8f5e9;
        }
        
        .testimonial-card:before {
            content: '\201C';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 80px;
            color: var(--light-green);
            font-family: Georgia, serif;
            opacity: 0.3;
            line-height: 1;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 25px;
            color: #555;
            position: relative;
            z-index: 1;
            line-height: 1.8;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }
        
        .author-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
            border: 3px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover .author-img {
            border-color: var(--light-green);
            transform: scale(1.05);
        }
        
        .author-info h5 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-green);
            font-size: 1.1rem;
        }
        
        .author-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #777;
        }
        
        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-green), var(--forest-green));
            padding: 80px 0;
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .newsletter-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBmaWxsPSIjZmZmZmZmIiBmaWxsLW9wYWNpdHk9IjAuMSIgZD0iTTMwIDBsMzAgMzAtMzAgMzBMMCAzMCAzMCAwem0wIDEyTDEyIDMwbDE4IDE4IDE4LTE4TDMwIDEyeiIvPjwvc3ZnPg==');
            opacity: 0.2;
        }
        
        .newsletter-section h3 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .newsletter-section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .newsletter-form {
            position: relative;
            max-width: 550px;
            margin: 0 auto;
        }
        
        .newsletter-input {
            height: 60px;
            border-radius: 30px;
            padding: 0 160px 0 30px;
            width: 100%;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            font-size: 1rem;
        }
        
        .newsletter-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            height: 50px;
            border-radius: 25px;
            background: linear-gradient(to right, var(--dark-green), var(--forest-green));
            color: white;
            border: none;
            padding: 0 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .newsletter-btn:hover {
            background: linear-gradient(to right, var(--forest-green), var(--dark-green));
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        
        /* About section */
        .about-section {
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .about-img-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .about-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .about-img-container:hover .about-img {
            transform: scale(1.05);
        }
        
        .about-content {
            padding: 30px;
        }
        
        .about-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .about-title:after {
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
        
        .about-feature {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .about-feature-icon {
            width: 40px;
            height: 40px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-green);
        }
        
        /* Gallery section */
        .gallery-section {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 15px;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 250px;
            cursor: pointer;
        }
        
        .gallery-item:nth-child(1) {
            grid-column: span 2;
            grid-row: span 2;
            height: auto;
        }
        
        .gallery-item:nth-child(4) {
            grid-column: span 2;
        }
        
        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 20px;
            color: white;
        }
        
        .gallery-item:hover .gallery-img {
            transform: scale(1.1);
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        /* Responsive styles */
        @media (max-width: 991px) {
            .hero h1 {
                font-size: 3rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .gallery-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .gallery-item:nth-child(1) {
                grid-column: span 2;
                grid-row: span 1;
            }
            
            .gallery-item:nth-child(4) {
                grid-column: span 1;
            }
        }
        
        @media (max-width: 767px) {
            .hero {
                padding: 150px 0 100px;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .benefit-item {
                margin-bottom: 30px;
            }
            
            .about-img-container {
                margin-bottom: 30px;
            }
            
            .gallery-container {
                grid-template-columns: 1fr;
            }
            
            .gallery-item:nth-child(1),
            .gallery-item:nth-child(4) {
                grid-column: span 1;
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
    
    <div class="hero">
        <!-- Floating shapes for animation -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="hero-content animate__animated animate__fadeInLeft">
                        <h1>Welcome to Green Oasis Hotel</h1>
                        <p>Experience nature-inspired luxury and exceptional comfort in our eco-friendly resort. Immerse yourself in tranquility and sustainable elegance.</p>
                        <div class="d-flex flex-wrap">
                            <a href="rooms.php" class="btn btn-custom mr-3 mb-2">
                                <i class="fas fa-bed mr-2"></i> Explore Rooms
                            </a>
                            <a href="#about" class="btn btn-outline-custom mb-2">
                                <i class="fas fa-info-circle mr-2"></i> Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-img-container" data-aos="fade-right">
                        <img src="https://source.unsplash.com/random/600x400/?eco-hotel" alt="Green Oasis Hotel" class="about-img">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content" data-aos="fade-left">
                        <h2 class="about-title">Discover Our Green Paradise</h2>
                        <p class="about-text">Green Oasis Hotel is a luxury eco-friendly resort nestled in the heart of nature. We combine sustainable practices with premium comfort to provide our guests with an unforgettable experience.</p>
                        <p class="about-text">Our commitment to environmental conservation is reflected in every aspect of our hotel, from energy-efficient systems to locally sourced organic cuisine.</p>
                        
                        <div class="about-features">
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div>Sustainable and eco-friendly practices</div>
                            </div>
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>Prime location surrounded by nature</div>
                            </div>
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>Award-winning luxury accommodations</div>
                            </div>
                        </div>
                        
                        <a href="about.php" class="btn btn-custom mt-4">
                            <i class="fas fa-arrow-right mr-2"></i> Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Section -->
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our Luxurious Rooms</h2>
        <div class="row">
            <?php foreach($room_types as $index => $room): ?>
                <div class="col-md-3 mb-4">
                    <div class="card room-card" data-aos="fade-up">
                        <div class="room-img-container">
                            <img src="https://source.unsplash.com/random/300x200/?luxury-hotel-room" class="room-img" alt="<?php echo $room['name']; ?>">
                            <div class="room-type-badge"><?php echo $room['name']; ?></div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $room['name']; ?></h5>
                            <p class="card-text"><?php echo substr($room['description'], 0, 80); ?>...</p>
                            
                            <div class="room-features">
                                <div class="feature">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo $room['capacity']; ?> Guests</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-wifi"></i>
                                    <span>Free WiFi</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-coffee"></i>
                                    <span>Breakfast</span>
                                </div>
                            </div>
                            
                            <div class="price">
                                Rp <?php echo number_format($room['price_per_night'], 0, ',', '.'); ?> <small>/ night</small>
                            </div>
                            
                            <a href="booking.php?type=<?php echo $room['id']; ?>" class="btn btn-custom btn-block">
                                <i class="fas fa-calendar-check mr-2"></i> Book Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="rooms.php" class="btn btn-outline-success btn-lg">
                <i class="fas fa-th-large mr-2"></i> View All Rooms
            </a>
        </div>
    </div>
    
    <!-- Benefits Section -->
    <section class="benefits-section">
        <i class="fas fa-leaf leaf-bg leaf-1"></i>
        <i class="fas fa-leaf leaf-bg leaf-2"></i>
        <i class="fas fa-leaf leaf-bg leaf-3"></i>
        
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Why Choose Green Oasis</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="benefit-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="benefit-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4 class="benefit-title">Eco-Friendly</h4>
                        <p>Our hotel is committed to sustainable practices and eco-friendly operations that minimize environmental impact.</p>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="benefit-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="benefit-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h4 class="benefit-title">Premium Service</h4>
                        <p>Experience world-class service from our dedicated and professional staff available 24/7 for your needs.</p>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="benefit-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="benefit-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4 class="benefit-title">Gourmet Dining</h4>
                        <p>Enjoy delicious meals prepared by our expert chefs using fresh, local, and organic ingredients.</p>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="benefit-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="benefit-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h4 class="benefit-title">Wellness Center</h4>
                        <p>Relax and rejuvenate at our state-of-the-art spa and wellness facilities with natural treatments.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Explore Our Hotel</h2>
            <div class="gallery-container" data-aos="fade-up">
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/600x400/?luxury-hotel" class="gallery-img" alt="Hotel Exterior">
                    <div class="gallery-overlay">
                        <h5>Hotel Exterior</h5>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/300x200/?hotel-room" class="gallery-img" alt="Luxury Room">
                    <div class="gallery-overlay">
                        <h5>Luxury Room</h5>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/300x200/?hotel-pool" class="gallery-img" alt="Swimming Pool">
                    <div class="gallery-overlay">
                        <h5>Swimming Pool</h5>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/600x200/?hotel-restaurant" class="gallery-img" alt="Restaurant">
                    <div class="gallery-overlay">
                        <h5>Restaurant</h5>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/300x200/?hotel-spa" class="gallery-img" alt="Spa">
                    <div class="gallery-overlay">
                        <h5>Spa & Wellness</h5>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://source.unsplash.com/random/300x200/?hotel-garden" class="gallery-img" alt="Garden">
                    <div class="gallery-overlay">
                        <h5>Green Garden</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonial-section">
        <div class="testimonial-pattern"></div>
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What Our Guests Say</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                        <p class="testimonial-text">The Green Oasis Hotel exceeded all my expectations. The rooms were immaculate, and the staff went above and beyond to make our stay memorable. The eco-friendly approach made me feel good about my choice.</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Johnson" class="author-img">
                            <div class="author-info">
                                <h5>Sarah Johnson</h5>
                                <p>Business Traveler</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                        <p class="testimonial-text">We loved the eco-friendly approach and the beautiful green surroundings. Perfect for our family vacation. The kids enjoyed the activities and we appreciated the sustainable practices. Will definitely come back!</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="David Chen" class="author-img">
                            <div class="author-info">
                                <h5>David Chen</h5>
                                <p>Family Traveler</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                        <p class="testimonial-text">The spa services were incredible, and the food at the restaurant was outstanding. A truly luxurious experience in harmony with nature. The organic menu options were delicious and the views were breathtaking.</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Maria Rodriguez" class="author-img">
                            <div class="author-info">
                                <h5>Maria Rodriguez</h5>
                                <p>Leisure Traveler</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="newsletter-pattern"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center" data-aos="fade-up">
                    <h3>Subscribe to Our Newsletter</h3>
                    <p>Stay updated with our latest offers, promotions, and eco-friendly initiatives. Join our green community today!</p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Your Email Address" required>
                        <button type="submit" class="newsletter-btn">Subscribe</button>
                    </form>
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
        
        // Room card hover effect enhancement
        $('.room-card').hover(
            function() {
                $(this).find('.card-title').css('color', 'var(--primary-green)');
            },
            function() {
                $(this).find('.card-title').css('color', 'var(--dark-green)');
            }
        );
        
        // Smooth scroll for anchor links
        $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
            if (
                location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
                location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            }
        });
        
        // Gallery item click effect
        $('.gallery-item').click(function() {
            $(this).find('.gallery-img').css('transform', 'scale(1.1)');
            setTimeout(() => {
                $(this).find('.gallery-img').css('transform', 'scale(1)');
            }, 200);
        });
    </script>
</body>
</html>
