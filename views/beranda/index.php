<?php
// Initialize data if not set
if (!isset($data)) {
    $data = [
        'slider' => [],
        'layanan' => [],
        'informasi' => [],
        'statistik' => [],
        'berita' => [],
        'kontak' => [],
        'quick_links' => []
    ];
}

// Get database connection
global $database;
$conn = null;
if (isset($database)) {
    $conn = $database->getConnection();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Pejabat Pengelola Informasi dan Dokumentasi Kabupaten Mandailing Natal - Melayani Transparansi Informasi Publik">
    <meta name="keywords" content="PPID, Mandailing Natal, Informasi Publik, Transparansi, Pemerintahan">
    <meta name="author" content="PPID Mandailing Natal">
    <title>PPID Mandailing Natal - Beranda</title>

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="ppid_assets/css/beranda-modern.css">
    <link rel="stylesheet" href="ppid_assets/css/micro-interactions.css">
    <link rel="stylesheet" href="ppid_assets/css/sections-modern.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-color: #1e3a8a; /* Biru tua */
            --secondary-color: #f59e0b;
            --accent-color: #fbbf24; /* Emas */
            --text-color: #1f2937;
            --muted-color: #6b7280;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Styles */
        .top-info-bar {
            background-color: var(--primary-color);
            padding: 8px 0;
            font-size: 13px;
            color: var(--white);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-info-bar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-info-links {
            display: flex;
            gap: 20px;
        }

        .top-info-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
        }

        .top-info-links a:hover {
            color: var(--accent-color);
        }

        .top-info-contact {
            display: flex;
            gap: 25px;
            align-items: center;
            color: var(--white);
        }

        .navbar-custom {
            background: var(--primary-color);
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-navbar {
            padding: 12px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: var(--white) !important;
            font-weight: 700;
            text-decoration: none;
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            background: var(--white);
            border-radius: 8px;
        }

        .logo-img img {
            width: 35px;
            height: 35px;
        }

        .navbar-nav {
            display: flex;
            flex-direction: row;
            gap: 8px;
            align-items: center;
            margin-left: auto;
        }

        .navbar-nav a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 4px;
            white-space: nowrap;
            position: relative;
        }

        .navbar-nav a:hover {
            color: var(--accent-color);
            background-color: rgba(251, 191, 36, 0.1);
        }

        .navbar-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav a:hover::after {
            width: 70%;
        }

        .nav-social {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: 15px;
            padding-left: 15px;
            border-left: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-social a {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            font-size: 14px;
            padding: 0;
            color: var(--white);
            transition: all 0.3s ease;
        }

        .nav-social a:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            color: var(--accent-color);
        }

        /* Banner Styles */
        .banner-container {
            position: relative;
            width: 100%;
            height: max-content;
            overflow: hidden;
        }

        .banner-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s ease-in-out;
        }

        .banner-slide {
            min-width: 100%;
            height: 100%;
            position: relative;
        }

        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.5) 0%, rgba(30, 58, 138, 0.3) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner-content {
            text-align: center;
            color: var(--white);
            max-width: 900px;
            padding: 30px;
            z-index: 2;
        }

        .banner-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.2;
        }

        .banner-content p {
            font-size: 1.4rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .banner-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-primary-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--accent-color);
            background: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-primary-hero:hover {
            background: transparent;
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
        }

        .btn-outline-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--white);
            background: transparent;
            color: var(--white);
        }

        .btn-outline-hero:hover {
            background: var(--white);
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
        }

        .banner-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .dot.active {
            background: var(--accent-color);
            border: 2px solid var(--white);
            transform: scale(1.2);
        }

        .banner-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .banner-arrow:hover {
            background: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .banner-arrow-prev {
            left: 30px;
        }

        .banner-arrow-next {
            right: 30px;
        }

        .banner-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: var(--accent-color);
            transition: width 5s linear;
        }

        .empty-banner {
            height: 650px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: var(--white);
            text-align: center;
        }

        .empty-banner-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .empty-banner-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        /* Section Styles */
        .features-section {
            padding: 80px 0;
            background-color: var(--white);
        }

        .section-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--primary-color);
            position: relative;
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.3rem;
            color: var(--muted-color);
            margin-bottom: 60px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 400;
        }

        .section-bg-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: var(--white);
        }

        .section-bg-primary .section-title {
            color: var(--white);
        }

        .section-bg-primary .section-title::after {
            background: var(--accent-color);
        }

        .section-bg-primary .section-subtitle {
            color: rgba(255, 255, 255, 0.85);
        }

        .section-bg-light {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .section-bg-accent {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: var(--card-shadow);
            background: var(--white);
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .info-card {
            background: white;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .news-image {
            height: 220px;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover .news-image img {
            transform: scale(1.05);
        }

        .stat-card {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        }

        .news-card {
            background: white;
            cursor: pointer;
            border: 1px solid #e2e8f0;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .dokumen-category {
            text-align: center;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .dokumen-category:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .btn-section {
            display: block;
            margin: 20px auto;
            text-align: center;
        }

        .btn-outline-primary {
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-gold {
            background: linear-gradient(45deg, var(--accent-color), #f59e0b);
            color: var(--primary-color);
            border: none;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', 'Inter', sans-serif;
            cursor: pointer;
            border: none;
        }

        .btn-gold:hover {
            background: linear-gradient(45deg, #f59e0b, var(--accent-color));
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4);
            color: var(--primary-color);
        }

        .btn-outline-gold {
            background: transparent;
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', 'Inter', sans-serif;
            cursor: pointer;
        }

        .btn-outline-gold:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .top-info-bar {
                display: none;
            }

            .navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #000000;
                flex-direction: column;
                gap: 0;
                padding: 10px 0;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                margin-left: 0;
            }

            .navbar-nav.show {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .banner-container {
                height: 500px;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.8rem;
            }

            .banner-container {
                height: 400px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .banner-arrow {
                width: 40px;
                height: 40px;
            }

            .chart-container {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .banner-container {
                height: 300px;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }
        
        /* Additional Responsive Improvements */
        @media (max-width: 992px) {
            .top-info-bar {
                display: none;
            }

            .navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary-color);
                flex-direction: column;
                gap: 0;
                padding: 10px 0;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                margin-left: 0;
                border-radius: 0 0 10px 10px;
            }

            .navbar-nav.show {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .banner-container {
                height: 500px;
            }

            .banner-content h1 {
                font-size: 2.5rem;
            }

            .banner-content p {
                font-size: 1.1rem;
            }
            
            .nav-social {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .banner-container {
                height: 450px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .banner-arrow {
                width: 45px;
                height: 45px;
            }

            .banner-content h1 {
                font-size: 2rem;
            }

            .banner-content p {
                font-size: 1rem;
            }

            .banner-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn-gold,
            .btn-outline-gold {
                width: 100%;
                max-width: 280px;
                margin: 0 auto;
            }
            
            .stat-card .card-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .stat-number {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 576px) {
            .banner-container {
                height: 350px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .banner-arrow-prev {
                left: 10px;
            }

            .banner-arrow-next {
                right: 10px;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .stat-label {
                font-size: 0.9rem;
            }
            
            .card {
                border-radius: 12px;
            }
            
            .section-subtitle {
                font-size: 1.1rem;
            }
        }
        
        /* Additional Mobile Improvements */
        @media (max-width: 480px) {
            .banner-content h1 {
                font-size: 1.6rem;
            }
            
            .banner-content p {
                font-size: 0.9rem;
            }
            
            .stat-card {
                padding: 20px 15px;
            }
            
            .news-grid {
                gap: 20px;
            }
        }
        
        /* High Contrast Accessibility */
        @media (prefers-contrast: high) {
            .section-title {
                color: #000;
            }
            
            .section-title::after {
                background: var(--accent-color);
            }
            
            .stat-number {
                color: var(--primary-color);
            }
        }
        
        /* Reduced Motion Support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* News Modal Styles */
        .news-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow-y: auto;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .news-modal-content {
            background-color: var(--white);
            margin: 5% auto;
            max-width: 900px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-close {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-close:hover {
            background: var(--accent-color);
            transform: rotate(90deg);
        }

        .modal-close i {
            font-size: 20px;
            color: var(--text-color);
        }

        .news-modal-header {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .news-modal-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-modal-body {
            padding: 40px;
        }

        .news-modal-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            line-height: 1.3;
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .news-modal-meta {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .news-modal-meta span {
            color: var(--muted-color);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .news-modal-meta i {
            color: var(--primary-color);
        }

        .news-modal-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
            text-align: justify;
        }

        @media (max-width: 768px) {
            .news-modal-content {
                margin: 0;
                width: 100%;
                height: 100%;
                border-radius: 0;
            }

            .news-modal-header {
                height: 250px;
            }

            .news-modal-body {
                padding: 25px;
            }

            .news-modal-title {
                font-size: 1.8rem;
            }

            .news-modal-text {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Banner Slider -->
    <section class="hero-section" data-aos="fade-in">
        <?php if (!empty($data['slider'])): ?>
            <div class="banner-container">
                <div class="banner-wrapper" id="bannerWrapper">
                    <div class="banner-track" id="bannerTrack">
                        <?php foreach ($data['slider'] as $index => $banner): ?>
                            <div class="banner-slide" data-index="<?= $index ?>">
                                <img src="<?= $banner['image'] ?>" alt="Banner <?= $index + 1 ?>" class="banner-image">
                                <div class="banner-overlay"></div>
                                <?php if ($index === 0): // Only show content on first slide ?>
                             
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Navigation Dots -->
                <div class="banner-dots" id="bannerDots">
                    <?php for ($i = 0; $i < count($data['slider']); $i++): ?>
                        <span class="dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></span>
                    <?php endfor; ?>
                </div>

                <!-- Navigation Arrows -->
                <button class="banner-arrow banner-arrow-prev" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="banner-arrow banner-arrow-next" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Progress Bar -->
                <div class="banner-progress" id="bannerProgress">
                    <div class="progress-bar-fill"></div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-banner">
                <div class="empty-banner-content">
                    <h1>Pejabat Pengelola Informasi dan Dokumentasi<br>Kabupaten Mandailing Natal</h1>
                    <p>Melayani transparansi informasi publik dengan cepat, akurat, dan profesional sesuai dengan Undang-Undang Keterbukaan Informasi Publik</p>
                    <div class="banner-buttons">
                        <a href="index.php?controller=auth&action=register" class="btn btn-gold">Ajukan Informasi</a>
                        <a href="#dokumen" class="btn btn-outline-gold">Lihat Dokumen</a>
                    </div>
                    <div class="mt-4">
                        <i class="fas fa-images fa-3x mb-3"></i>
                        <p>Belum ada banner yang ditampilkan</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- Berita Section -->
    <section class="features-section" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title pb-4" data-aos="fade-left">Berita Terbaru</h2>

            <div class="row">
                <!-- Kiri (Slider Berita Terbaru) -->
                <div class="col-md-8 mb-4">
                    <div id="beritaCarousel" class="carousel slide h-100 shadow-sm rounded">
                        <div class="carousel-inner h-100">
                            <?php if (!empty($data['berita'])): ?>
                                <?php foreach ($data['berita'] as $index => $news): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> h-100">
                                        <div style="position: relative;">
                                            <img src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="d-block w-100" style="height: 400px; object-fit: cover;">
                                            <div style="position: absolute; top: 15px; left: 15px; background: #1e40af; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem;">
                                                BERITA
                                            </div>
                                            <div class="p-4" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white;">
                                                <h3 class="text-white"><?= htmlspecialchars($news['title']) ?></h3>
                                                <p class="text-white"><?= strlen($news['summary']) > 200 ? substr(htmlspecialchars($news['summary']), 0, 200) . '...' : htmlspecialchars($news['summary']) ?></p>
                                                <?php
                                                $date = new DateTime($news['published_at']);
                                                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                $months = [
                                                    1 => 'Januari',
                                                    2 => 'Februari',
                                                    3 => 'Maret',
                                                    4 => 'April',
                                                    5 => 'Mei',
                                                    6 => 'Juni',
                                                    7 => 'Juli',
                                                    8 => 'Agustus',
                                                    9 => 'September',
                                                    10 => 'Oktober',
                                                    11 => 'November',
                                                    12 => 'Desember'
                                                ];
                                                $dayName = $days[$date->format('w')];
                                                $day = $date->format('d');
                                                $month = $months[(int)$date->format('n')];
                                                $year = $date->format('Y');
                                                $indonesianDate = "$dayName, $day $month $year";
                                                ?>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-calendar-alt me-1"></i> <?= $indonesianDate ?></span>
                                                    <span><i class="fas fa-eye me-1"></i> <?= $news['views'] ?> views</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active h-100">
                                    <div class="text-center d-flex align-items-center justify-content-center h-100" style="background: #e2e8f0; color: #64748b;">
                                        <div>
                                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                                            <h4 class="text-muted">Belum Ada Berita</h4>
                                            <p class="text-muted">Berita terbaru akan ditampilkan di sini</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#beritaCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#beritaCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <div class="carousel-indicators">
                            <?php if (!empty($data['berita'])): ?>
                                <?php for ($i = 0; $i < min(count($data['berita']), 5); $i++): ?>
                                    <button type="button" data-bs-target="#beritaCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="true" aria-label="Slide <?= $i + 1 ?>"></button>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Kanan (List Berita Terbaru) -->
                <div class="col-md-4">
                    <div class="card shadow-sm rounded h-100">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($data['berita'])): ?>
                                    <?php
                                    $listNews = array_slice($data['berita'], 0, 5); // Ambil 5 berita untuk list
                                    foreach ($listNews as $index => $news):
                                    ?>
                                        <div class="list-group-item d-flex" style="cursor: pointer;" onclick="openNewsModal(<?= htmlspecialchars(json_encode($news), ENT_QUOTES, 'UTF-8') ?>)">
                                            <img src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="me-3 rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($news['title']) ?></h6>
                                                <?php
                                                $date = new DateTime($news['published_at']);
                                                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                $months = [
                                                    1 => 'Januari',
                                                    2 => 'Februari',
                                                    3 => 'Maret',
                                                    4 => 'April',
                                                    5 => 'Mei',
                                                    6 => 'Juni',
                                                    7 => 'Juli',
                                                    8 => 'Agustus',
                                                    9 => 'September',
                                                    10 => 'Oktober',
                                                    11 => 'November',
                                                    12 => 'Desember'
                                                ];
                                                $dayName = $days[$date->format('w')];
                                                $day = $date->format('d');
                                                $month = $months[(int)$date->format('n')];
                                                $year = $date->format('Y');
                                                $indonesianDate = "$dayName, $day $month $year";
                                                ?>
                                                <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> <?= $indonesianDate ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="list-group-item text-center">
                                        <i class="fas fa-newspaper fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum Ada Berita</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($data['berita']) && count($data['berita']) > 5): ?>
                                <div class="card-footer text-center">
                                    <a href="?controller=berita&action=public" class="btn btn-sm btn-outline-primary">Berita Selengkapnya</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Section -->
    <section class="features-section section-bg-primary" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title text-white pb-4" data-aos="fade-right">Statistik Singkat</h2>
            <div class="row">
                <?php
                // Hitung statistik dari data yang ada
                $totalDokumen = 0;
                $totalPermohonan = 0;
                $totalUnduhan = 0;
                $totalPemohon = 0;

                // Ambil total dokumen dari database
                global $database;
                $conn = $database->getConnection();

                if ($conn) {
                    $dokumenQuery = "SELECT COUNT(*) as total FROM dokumen WHERE status = 'publikasi'";
                    $dokumenStmt = $conn->prepare($dokumenQuery);
                    $dokumenStmt->execute();
                    $dokumenResult = $dokumenStmt->fetch(PDO::FETCH_ASSOC);
                    $totalDokumen = $dokumenResult['total'];

                    $permohonanQuery = "SELECT COUNT(*) as total FROM permohonan";
                    $permohonanStmt = $conn->prepare($permohonanQuery);
                    $permohonanStmt->execute();
                    $permohonanResult = $permohonanStmt->fetch(PDO::FETCH_ASSOC);
                    $totalPermohonan = $permohonanResult['total'];

                    // Hitung total unduhan
                    $unduhanQuery = "SELECT COUNT(*) as total FROM permohonan WHERE status = 'publikasi'";
                    $unduhanStmt = $conn->prepare($unduhanQuery);
                    $unduhanStmt->execute();
                    $unduhanResult = $unduhanStmt->fetch(PDO::FETCH_ASSOC);
                    $totalUnduhan = $unduhanResult['total'];

                    $pemohonQuery = "SELECT COUNT(*) as total FROM users WHERE role = 'masyarakat'";
                    $pemohonStmt = $conn->prepare($pemohonQuery);
                    $pemohonStmt->execute();
                    $pemohonResult = $pemohonStmt->fetch(PDO::FETCH_ASSOC);
                    $totalPemohon = $pemohonResult['total'];
                }
                ?>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card stat-card stat-card-gold" data-aos="zoom-in" data-aos-delay="100">
                        <div class="p-4 text-center">
                            <div class="card-icon mx-auto mb-3">
                                <i class="fas fa-file"></i>
                            </div>
                            <span class="stat-number" data-count="<?= $totalDokumen ?>">0</span>
                            <p class="stat-label">Jumlah Dokumen</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card stat-card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="p-4 text-center">
                            <div class="card-icon mx-auto mb-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="stat-number" data-count="<?= $totalPermohonan ?>">0</span>
                            <p class="stat-label">Jumlah Permohonan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card stat-card" data-aos="zoom-in" data-aos-delay="300">
                        <div class="p-4 text-center">
                            <div class="card-icon mx-auto mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="stat-number" data-count="<?= $totalPemohon ?>">0</span>
                            <p class="stat-label">Jumlah Pemohon</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="card stat-card stat-card-gold" data-aos="zoom-in" data-aos-delay="400">
                        <div class="p-4 text-center">
                            <div class="card-icon mx-auto mb-3">
                                <i class="fas fa-download"></i>
                            </div>
                            <span class="stat-number" data-count="<?= $totalUnduhan ?>">0</span>
                            <p class="stat-label">Jumlah Unduhan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Dokumen Section -->
    <section class="features-section section-bg-light" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title pb-5" data-aos="fade-left">Download Dokumen Berdasarkan Kategori</h2>

            <div class="row">
                <?php
                // Ambil dokumen terbaru per kategori
                $kategoriDokumen = [];
                // Ambil jumlah dokumen per kategori untuk chart
                $kategoriCounts = [];
                if ($conn) {
                    // Ambil 3 dokumen terbaru per kategori
                    $query_berkala = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                     FROM dokumen d 
                                     JOIN kategori k ON d.id_kategori = k.id_kategori 
                                     WHERE k.nama_kategori = 'Berkala' AND d.status = 'publikasi' 
                                     ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_berkala = $conn->prepare($query_berkala);
                    $stmt_berkala->execute();
                    $kategoriDokumen['berkala'] = $stmt_berkala->fetchAll(PDO::FETCH_ASSOC);

                    $query_serta_merta = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                         FROM dokumen d 
                                         JOIN kategori k ON d.id_kategori = k.id_kategori 
                                         WHERE k.nama_kategori = 'Serta Merta' AND d.status = 'publikasi' 
                                         ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_serta_merta = $conn->prepare($query_serta_merta);
                    $stmt_serta_merta->execute();
                    $kategoriDokumen['serta_merta'] = $stmt_serta_merta->fetchAll(PDO::FETCH_ASSOC);

                    $query_setiap_saat = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                         FROM dokumen d 
                                         JOIN kategori k ON d.id_kategori = k.id_kategori 
                                         WHERE k.nama_kategori = 'Setiap Saat' AND d.status = 'publikasi' 
                                         ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_setiap_saat = $conn->prepare($query_setiap_saat);
                    $stmt_setiap_saat->execute();
                    $kategoriDokumen['setiap_saat'] = $stmt_setiap_saat->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Ambil jumlah dokumen per kategori untuk chart
                    $countQuery = "SELECT k.nama_kategori, COUNT(d.id_dokumen) as count 
                                  FROM kategori k 
                                  LEFT JOIN dokumen d ON k.id_kategori = d.id_kategori AND d.status = 'publikasi'
                                  GROUP BY k.id_kategori, k.nama_kategori";
                    $countStmt = $conn->prepare($countQuery);
                    $countStmt->execute();
                    $countResults = $countStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($countResults as $result) {
                        $kategoriName = strtolower($result['nama_kategori']);
                        $kategoriCounts[$kategoriName] = $result['count'];
                    }
                    $query_berkala = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                     FROM dokumen d 
                                     JOIN kategori k ON d.id_kategori = k.id_kategori 
                                     WHERE k.nama_kategori = 'Berkala' AND d.status = 'publikasi' 
                                     ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_berkala = $conn->prepare($query_berkala);
                    $stmt_berkala->execute();
                    $kategoriDokumen['berkala'] = $stmt_berkala->fetchAll(PDO::FETCH_ASSOC);

                    $query_serta_merta = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                         FROM dokumen d 
                                         JOIN kategori k ON d.id_kategori = k.id_kategori 
                                         WHERE k.nama_kategori = 'Serta Merta' AND d.status = 'publikasi' 
                                         ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_serta_merta = $conn->prepare($query_serta_merta);
                    $stmt_serta_merta->execute();
                    $kategoriDokumen['serta_merta'] = $stmt_serta_merta->fetchAll(PDO::FETCH_ASSOC);

                    $query_setiap_saat = "SELECT d.judul, d.upload_file, d.created_at, d.kandungan_informasi, d.created_at as tanggal_upload 
                                         FROM dokumen d 
                                         JOIN kategori k ON d.id_kategori = k.id_kategori 
                                         WHERE k.nama_kategori = 'Setiap Saat' AND d.status = 'publikasi' 
                                         ORDER BY d.created_at DESC LIMIT 3";
                    $stmt_setiap_saat = $conn->prepare($query_setiap_saat);
                    $stmt_setiap_saat->execute();
                    $kategoriDokumen['setiap_saat'] = $stmt_setiap_saat->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Ambil jumlah dokumen per kategori untuk chart
                    $countQuery = "SELECT k.nama_kategori, COUNT(d.id_dokumen) as count 
                                  FROM kategori k 
                                  LEFT JOIN dokumen d ON k.id_kategori = d.id_kategori AND d.status = 'publikasi'
                                  GROUP BY k.id_kategori, k.nama_kategori";
                    $countStmt = $conn->prepare($countQuery);
                    $countStmt->execute();
                    $countResults = $countStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($countResults as $result) {
                        $kategoriName = strtolower($result['nama_kategori']);
                        $kategoriCounts[$kategoriName] = $result['count'];
                    }
                }
                ?>

                <!-- Kategori Berkala -->
                <div class="col-md-4 mb-4">
                    <div class="card dokumen-category-card shadow-sm rounded h-100" data-aos="zoom-in" data-aos-delay="100">
                        <div class="card-header bg-gradient text-white text-center py-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary-color), #1e40af);">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div class="position-relative">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <h5 class="card-title mb-0 font-weight-bold" style="color: black">Berkala</h5>
                                <div class="d-flex justify-content-center mt-2">
                                    <span class="badge bg-white px-3 py-2" style="color: black"><?= $kategoriCounts['berkala'] ?? 0 ?> dokumen</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($kategoriDokumen['berkala'])): ?>
                                <div class="dokumen-list">
                                    <?php foreach ($kategoriDokumen['berkala'] as $index => $dok): ?>
                                        <div class="dokumen-item mb-3 pb-3 border-bottom" style="border-bottom: 1px solid #e2e8f0 !important;">
                                            <div class="d-flex align-items-center">
                                                <div class="dokumen-icon me-3">
                                                    <i class="fas fa-file-pdf text-danger fa-lg" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 dokumen-title" style="font-size: 0.9rem;"><?= htmlspecialchars(substr($dok['judul'], 0, 60)) . (strlen($dok['judul']) > 60 ? '...' : '') ?></h6>
                                                    <div class="dokumen-meta text-muted" style="font-size: 0.75rem;">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <?php
                                                        $date = new DateTime($dok['tanggal_upload']);
                                                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                        $months = [
                                                            1 => 'Jan',
                                                            2 => 'Feb',
                                                            3 => 'Mar',
                                                            4 => 'Apr',
                                                            5 => 'Mei',
                                                            6 => 'Jun',
                                                            7 => 'Jul',
                                                            8 => 'Agu',
                                                            9 => 'Sep',
                                                            10 => 'Okt',
                                                            11 => 'Nov',
                                                            12 => 'Des'
                                                        ];
                                                        $dayName = $days[$date->format('w')];
                                                        $day = $date->format('d');
                                                        $month = $months[(int)$date->format('n')];
                                                        $year = $date->format('Y');
                                                        $indonesianDate = "$day $month $year";
                                                        ?>
                                                        <?= $indonesianDate ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada dokumen</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="index.php?controller=dokumen&action=index&kategori=1" class="btn btn-outline-primary w-100 rounded-pill shadow-sm py-2">
                                <i class="fas fa-download me-2"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori Serta Merta -->
                <div class="col-md-4 mb-4">
                    <div class="card dokumen-category-card shadow-sm rounded h-100" data-aos="zoom-in" data-aos-delay="200">
                        <div class="card-header bg-gradient text-white text-center py-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--secondary-color), #d97706);">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div class="position-relative">
                                <i class="fas fa-bolt fa-2x mb-2"></i>
                                <h5 class="card-title mb-0 font-weight-bold" style="color: black">Serta Merta</h5>
                                <div class="d-flex justify-content-center mt-2">
                                    <span class="badge bg-white px-3 py-2" style="color: black"><?= $kategoriCounts['serta merta'] ?? 0 ?> dokumen</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($kategoriDokumen['serta_merta'])): ?>
                                <div class="dokumen-list">
                                    <?php foreach ($kategoriDokumen['serta_merta'] as $index => $dok): ?>
                                        <div class="dokumen-item mb-3 pb-3 border-bottom" style="border-bottom: 1px solid #e2e8f0 !important;">
                                            <div class="d-flex align-items-center">
                                                <div class="dokumen-icon me-3">
                                                    <i class="fas fa-file-pdf text-danger fa-lg" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 dokumen-title" style="font-size: 0.9rem;"><?= htmlspecialchars(substr($dok['judul'], 0, 60)) . (strlen($dok['judul']) > 60 ? '...' : '') ?></h6>
                                                    <div class="dokumen-meta text-muted" style="font-size: 0.75rem;">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <?php
                                                        $date = new DateTime($dok['tanggal_upload']);
                                                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                        $months = [
                                                            1 => 'Jan',
                                                            2 => 'Feb',
                                                            3 => 'Mar',
                                                            4 => 'Apr',
                                                            5 => 'Mei',
                                                            6 => 'Jun',
                                                            7 => 'Jul',
                                                            8 => 'Agu',
                                                            9 => 'Sep',
                                                            10 => 'Okt',
                                                            11 => 'Nov',
                                                            12 => 'Des'
                                                        ];
                                                        $dayName = $days[$date->format('w')];
                                                        $day = $date->format('d');
                                                        $month = $months[(int)$date->format('n')];
                                                        $year = $date->format('Y');
                                                        $indonesianDate = "$day $month $year";
                                                        ?>
                                                        <?= $indonesianDate ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada dokumen</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="index.php?controller=dokumen&action=index&kategori=2" class="btn btn-outline-primary text-dark w-100 rounded-pill shadow-sm py-2">
                                <i class="fas fa-download me-2"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori Setiap Saat -->
                <div class="col-md-4 mb-4">
                    <div class="card dokumen-category-card shadow-sm rounded h-100" data-aos="zoom-in" data-aos-delay="300">
                        <div class="card-header bg-gradient text-white text-center py-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--accent-color), #e2c044);">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div class="position-relative">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h5 class="card-title mb-0 font-weight-bold" style="color: black">Setiap Saat</h5>
                                <div class="d-flex justify-content-center mt-2">
                                    <span class="badge bg-white text-accent px-3 py-2" style="color: black"><?= $kategoriCounts['setiap saat'] ?? 0 ?> dokumen</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($kategoriDokumen['setiap_saat'])): ?>
                                <div class="dokumen-list">
                                    <?php foreach ($kategoriDokumen['setiap_saat'] as $index => $dok): ?>
                                        <div class="dokumen-item mb-3 pb-3 border-bottom" style="border-bottom: 1px solid #e2e8f0 !important;">
                                            <div class="d-flex align-items-center">
                                                <div class="dokumen-icon me-3">
                                                    <i class="fas fa-file-pdf text-danger fa-lg" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 dokumen-title" style="font-size: 0.9rem;"><?= htmlspecialchars(substr($dok['judul'], 0, 60)) . (strlen($dok['judul']) > 60 ? '...' : '') ?></h6>
                                                    <div class="dokumen-meta text-muted" style="font-size: 0.75rem;">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        <?php
                                                        $date = new DateTime($dok['tanggal_upload']);
                                                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                        $months = [
                                                            1 => 'Jan',
                                                            2 => 'Feb',
                                                            3 => 'Mar',
                                                            4 => 'Apr',
                                                            5 => 'Mei',
                                                            6 => 'Jun',
                                                            7 => 'Jul',
                                                            8 => 'Agu',
                                                            9 => 'Sep',
                                                            10 => 'Okt',
                                                            11 => 'Nov',
                                                            12 => 'Des'
                                                        ];
                                                        $dayName = $days[$date->format('w')];
                                                        $day = $date->format('d');
                                                        $month = $months[(int)$date->format('n')];
                                                        $year = $date->format('Y');
                                                        $indonesianDate = "$day $month $year";
                                                        ?>
                                                        <?= $indonesianDate ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada dokumen</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="index.php?controller=dokumen&action=index&kategori=3" class="btn btn-outline-primary w-100 rounded-pill shadow-sm py-2">
                                <i class="fas fa-download me-2"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Statistik Layanan Informasi Publik Section -->
    <section class="features-section section-bg-light" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title" data-aos="fade-right">Statistik Layanan Informasi Publik</h2>
            <p class="section-subtitle" data-aos="fade-up">Memberikan visualisasi data layanan publik PPID secara real-time</p>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card chart-card p-4 shadow-sm">
                        <h5>Informasi Publik</h5>
                        <div id="pieChart1" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card chart-card p-4 shadow-sm">
                        <h5>Status Penanganan Permohonan</h5>
                        <div id="pieChart2" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card chart-card p-4 shadow-sm">
                        <h5>Permohonan Informasi Publik Berkala</h5>
                        <div id="areaChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri Foto Section -->
    <section class="features-section section-bg-light" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title pb-4" data-aos="fade-left">Galeri Foto</h2>

            <div id="galeriCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php
                    $albumQuery = "SELECT * FROM album WHERE kategori = 'foto' ORDER BY created_at DESC LIMIT 6";
                    $albumStmt = $conn->prepare($albumQuery);
                    $albumStmt->execute();
                    $albums = $albumStmt->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($albums); $i++):
                    ?>
                        <button type="button" data-bs-target="#galeriCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                    <?php endfor; ?>
                </div>
                <div class="carousel-inner rounded">
                    <?php if (!empty($albums)): ?>
                        <?php foreach ($albums as $index => $album): ?>
                            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                <img src="<?= $album['upload'] ?>" class="d-block w-100" alt="<?= htmlspecialchars($album['nama_album']) ?>" style="height: 400px; object-fit: cover;">
                                <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 4px; padding: 10px;">
                                    <h5><?= htmlspecialchars($album['nama_album']) ?></h5>
                                    <p>Diunggah pada <?= date('d M Y', strtotime($album['created_at'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <div class="d-flex align-items-center justify-content-center" style="height: 400px; background: #e2e8f0; color: #64748b;">
                                <div class="text-center">
                                    <i class="fas fa-images fa-3x mb-3"></i>
                                    <h4>Belum Ada Galeri</h4>
                                    <p>Galeri foto akan ditampilkan di sini</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#galeriCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galeriCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="text-center">
                <a href="index.php?controller=album&action=public&kategori=foto" class="btn btn-primary">Galeri Selengkapnya <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- Layanan Kepuasan Section -->
    <section class="features-section section" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title pb-4" data-aos="fade-right">Layanan Kepuasan</h2>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card rating-card shadow-sm">
                        <div class="card-body text-center p-5">
                            <?php 
                            // Get average rating from layanan_kepuasan table
                            $averageRating = 0;
                            $totalRespondents = 0;
                            
                            // Get connection if not already available
                            if (!isset($conn) && isset($database)) {
                                $conn = $database->getConnection();
                            }
                            
                            if ($conn) {
                                try {
                                    $ratingQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_respondents FROM layanan_kepuasan";
                                    $ratingStmt = $conn->prepare($ratingQuery);
                                    $ratingStmt->execute();
                                    $ratingResult = $ratingStmt->fetch(PDO::FETCH_ASSOC);
                                    
                                    if ($ratingResult) {
                                        $averageRating = round((float)$ratingResult['avg_rating'], 1);
                                        $totalRespondents = (int)$ratingResult['total_respondents'];
                                    }
                                } catch (PDOException $e) {
                                    error_log("Error fetching rating data: " . $e->getMessage());
                                    // Use default values
                                    $averageRating = 0;
                                    $totalRespondents = 0;
                                }
                            }
                            ?>
                            
                            <div class="rating-display mb-4">
                                <div style="font-size: 4rem; font-weight: bold; color: #f59e0b;">
                                    <?= $averageRating ?>/5
                                </div>
                                <div class="stars mb-3">
                                    <?php
                                    // Display star rating
                                    for($i = 1; $i <= 5; $i++) {
                                        if($i <= $averageRating) {
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        } elseif($i == ceil($averageRating) && $averageRating != floor($averageRating)) {
                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                        } else {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <p class="text-muted">Berdasarkan <?= $totalRespondents ?> responden</p>
                            </div>
                            
                            <div class="progress-bars mt-4 text-start">
                                <?php
                                // Get rating distribution (1-5 stars)
                                $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                                
                                if ($conn) {
                                    try {
                                        for($star = 1; $star <= 5; $star++) {
                                            $distQuery = "SELECT COUNT(*) as count FROM layanan_kepuasan WHERE rating = ?";
                                            $distStmt = $conn->prepare($distQuery);
                                            $distStmt->execute([$star]);
                                            $distResult = $distStmt->fetch(PDO::FETCH_ASSOC);
                                            $ratingDistribution[$star] = (int)$distResult['count'];
                                        }
                                    } catch (PDOException $e) {
                                        error_log("Error fetching rating distribution: " . $e->getMessage());
                                        // Keep default values of 0
                                    }
                                }
                                
                                // Calculate percentages
                                $maxCount = max($ratingDistribution);
                                if ($maxCount == 0) $maxCount = 1; // Avoid division by zero
                                
                                for($star = 5; $star >= 1; $star--) {
                                    $count = $ratingDistribution[$star];
                                    $percentage = ($count / $maxCount) * 100;
                                    echo '<div class="d-flex align-items-center mb-2">';
                                    echo '<div class="me-3" style="width: 50px;">';
                                    echo '<i class="fas fa-star text-warning"></i> ' . $star;
                                    echo '</div>';
                                    echo '<div class="flex-grow-1">';
                                    echo '<div class="progress" style="height: 10px;">';
                                    echo '<div class="progress-bar bg-warning" role="progressbar" style="width: ' . $percentage . '%"></div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="ms-3" style="width: 40px;">' . $count . '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'template/layout/footer.php'; ?>

    <!-- News Modal -->
    <div id="newsModal" class="news-modal">
        <div class="news-modal-content">
            <button class="modal-close" onclick="closeNewsModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="news-modal-header">
                <img id="modalNewsImage" src="" alt="" class="news-modal-image">
            </div>
            <div class="news-modal-body">
                <h1 id="modalNewsTitle" class="news-modal-title"></h1>
                <div class="news-modal-meta">
                    <span id="modalNewsDate"><i class="fas fa-calendar-alt me-1"></i> </span>
                    <span id="modalNewsAuthor"><i class="fas fa-user me-1"></i> </span>
                    <span id="modalNewsCategory"><i class="fas fa-tag me-1"></i> </span>
                </div>
                <div id="modalNewsContent" class="news-modal-text"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100
        });

        // Animate statistics numbers when they come into view
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number[data-count]');
            
            const animateValue = (element, start, end, duration) => {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    const value = Math.floor(progress * (end - start) + start);
                    element.textContent = value.toLocaleString('id-ID');
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const finalValue = parseInt(element.getAttribute('data-count'));
                        animateValue(element, 0, finalValue, 2000);
                        observer.unobserve(element);
                    }
                });
            }, {
                threshold: 0.5
            });

            statNumbers.forEach(stat => {
                observer.observe(stat);
            });
        });
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.getElementById('navLinks');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            if (!navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navLinks.classList.remove('show');
            }
        });

        // Banner slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            const bannerTrack = document.getElementById('bannerTrack');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const slides = document.querySelectorAll('.banner-slide');
            const progressBar = document.querySelector('.progress-bar-fill');

            let currentIndex = 0;
            let autoSlideInterval;

            function updateBannerPosition() {
                if (!bannerTrack || slides.length === 0) return;

                const translateXValue = -currentIndex * 100;
                bannerTrack.style.transform = `translateX(${translateXValue}%)`;

                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });

                if (progressBar) {
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.transition = 'width 4s linear';
                        progressBar.style.width = '100%';
                    }, 10);
                }
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                updateBannerPosition();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateBannerPosition();
            }

            function startAutoSlide() {
                if (slides.length <= 1) return;
                clearInterval(autoSlideInterval);
                autoSlideInterval = setInterval(nextSlide, 5000);
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Event listeners
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    updateBannerPosition();
                    startAutoSlide();
                });
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    startAutoSlide();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    startAutoSlide();
                });
            }

            // Initialize
            if (slides.length > 0) {
                updateBannerPosition();
                startAutoSlide();

                const bannerContainer = document.querySelector('.banner-container');
                if (bannerContainer) {
                    bannerContainer.addEventListener('mouseenter', stopAutoSlide);
                    bannerContainer.addEventListener('mouseleave', startAutoSlide);
                }
            }

            // Ambil data untuk grafik status permohonan dari database
            <?php
            $statusCounts = [
                'masuk' => 0,
                'disposisi' => 0,
                'diproses' => 0,
                'selesai' => 0
            ];
            
            if ($conn) {
                try {
                    // Hitung status permohonan berdasarkan data sebenarnya dari database
                    $statusQuery = "SELECT status, COUNT(*) as count FROM permohonan GROUP BY status";
                    $statusStmt = $conn->prepare($statusQuery);
                    $statusStmt->execute();
                    $statusResults = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($statusResults as $result) {
                        $status = trim(strtolower($result['status']));
                        
                        // Normalisasi status ke format yang diharapkan berdasarkan nilai sebenarnya
                        if(strpos($status, 'selesai') !== false || strpos($status, 'publikasi') !== false || strpos($status, 'pengiriman') !== false) {
                            $statusCounts['selesai'] += (int)$result['count'];
                        } elseif(strpos($status, 'disposisi') !== false) {
                            $statusCounts['disposisi'] += (int)$result['count'];
                        } elseif(strpos($status, 'diproses') !== false) {
                            $statusCounts['diproses'] += (int)$result['count'];
                        } else {
                            // Asumsikan status lainnya sebagai status masuk
                            $statusCounts['masuk'] += (int)$result['count'];
                        }
                    }
                } catch (PDOException $e) {
                    error_log("Error fetching status counts: " . $e->getMessage());
                }
                
                try {
                    // Ambil data permohonan per bulan untuk grafik tren
                    $monthlyData = [];
                    $monthlySelesai = [];
                    
                    for($i = 1; $i <= 12; $i++) {
                        $monthlyQuery = "SELECT COUNT(*) as count FROM permohonan WHERE MONTH(created_at) = ? AND YEAR(created_at) = YEAR(CURDATE())";
                        $monthlyStmt = $conn->prepare($monthlyQuery);
                        $monthlyStmt->execute([$i]);
                        $monthlyResult = $monthlyStmt->fetch(PDO::FETCH_ASSOC);
                        $monthlyData[] = (int)$monthlyResult['count'];
                        
                        // Hitung jumlah permohonan yang selesai per bulan
                        $monthlySelesaiQuery = "SELECT COUNT(*) as count FROM permohonan WHERE MONTH(created_at) = ? AND YEAR(created_at) = YEAR(CURDATE()) AND (status = 'Selesai' OR status LIKE '%publikasi%' OR status LIKE '%pengiriman%' OR status LIKE '%selesai%')";
                        $monthlySelesaiStmt = $conn->prepare($monthlySelesaiQuery);
                        $monthlySelesaiStmt->execute([$i]);
                        $monthlySelesaiResult = $monthlySelesaiStmt->fetch(PDO::FETCH_ASSOC);
                        $monthlySelesai[] = (int)$monthlySelesaiResult['count'];
                    }
                    
                    $monthlyDataJson = json_encode($monthlyData);
                    $monthlySelesaiJson = json_encode($monthlySelesai);
                } catch (PDOException $e) {
                    error_log("Error fetching monthly data: " . $e->getMessage());
                    $monthlyDataJson = json_encode(array_fill(0, 12, 0));
                    $monthlySelesaiJson = json_encode(array_fill(0, 12, 0));
                }
            } else {
                // Default values jika koneksi tidak tersedia
                $statusCounts = ['masuk' => 0, 'disposisi' => 0, 'diproses' => 0, 'selesai' => 0];
                $monthlyDataJson = json_encode(array_fill(0, 12, 0));
                $monthlySelesaiJson = json_encode(array_fill(0, 12, 0));
            }
            
            // Filter status dengan jumlah 0
            $filteredStatus = array_filter($statusCounts, function($count) {
                return $count > 0;
            });
            ?>

            // Highcharts
            // Chart 1: Proporsi Kategori Informasi
            Highcharts.chart('pieChart1', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Kategori Dokumen Publik'
                },
                accessibility: {
                    point: {
                        valueDescriptionFormat: '{point.name}: {point.y} dokumen'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y} dokumen'
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    colorByPoint: true,
                    data: [
                        <?php if(($kategoriCounts['berkala'] ?? 0) > 0): ?>
                        ,{
                            name: 'Berkala',
                            y: <?= $kategoriCounts['berkala'] ?? 0 ?>,
                            color: '#87CEEB' // biru muda
                        },
                        <?php endif; ?>
                        <?php if(($kategoriCounts['serta merta'] ?? 0) > 0): ?>
                        ,{
                            name: 'Serta Merta',
                            y: <?= $kategoriCounts['serta merta'] ?? 0 ?>,
                            color: '#90EE90' // hijau
                        },
                        <?php endif; ?>
                        <?php if(($kategoriCounts['setiap saat'] ?? 0) > 0): ?>
                        ,{
                            name: 'Setiap Saat',
                            y: <?= $kategoriCounts['setiap saat'] ?? 0 ?>,
                            color: '#DDA0DD' // ungu
                        }
                        <?php endif; ?>
                    ]
                }]
            });

            // Chart 2: Status Penanganan Permohonan - Dynamic from database
            Highcharts.chart('pieChart2', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Status Penanganan Permohonan'
                },
                accessibility: {
                    point: {
                        valueDescriptionFormat: '{point.name}: {point.y} permohonan'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y} permohonan'
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    colorByPoint: true,
                    data: [
                        <?php if($statusCounts['masuk'] > 0): ?>
                        ,{
                            name: 'Masuk',
                            y: <?= $statusCounts['masuk'] ?>,
                            color: '#93C5FD' // biru muda
                        },
                        <?php endif; ?>
                        <?php if($statusCounts['disposisi'] > 0): ?>
                        ,{
                            name: 'Disposisi',
                            y: <?= $statusCounts['disposisi'] ?>,
                            color: '#87CEEB' // biru langit
                        },
                        <?php endif; ?>
                        <?php if($statusCounts['diproses'] > 0): ?>
                        ,{
                            name: 'Diproses',
                            y: <?= $statusCounts['diproses'] ?>,
                            color: '#F0E68C' // kuning
                        },
                        <?php endif; ?>
                        <?php if($statusCounts['selesai'] > 0): ?>
                        ,{
                            name: 'Selesai',
                            y: <?= $statusCounts['selesai'] ?>,
                            color: '#90EE90' // hijau
                        }
                        <?php endif; ?>
                    ]
                }]
            });

            // Chart 3: Tren Permohonan Informasi - Dynamic from database
            Highcharts.chart('areaChart', {
                chart: {
                    type: 'area'
                },
                title: {
                    text: 'Permohonan Informasi Publik Berkala'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Permohonan'
                    }
                },
                plotOptions: {
                    area: {
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[0]],
                                [1, Highcharts.color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                            ]
                        },
                        marker: {
                            enabled: false
                        },
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        threshold: null
                    }
                },
                series: [{
                    name: 'Permohonan Informasi',
                    data: <?= $monthlyDataJson ?>,
                    color: '#87CEEB'
                }, {
                    name: 'Permohonan Selesai',
                    data: <?= $monthlySelesaiJson ?>,
                    color: '#90EE90'
                }]
            });
        });

        // Global functions for info actions
        function previewInfo(id) {
            // Implement preview functionality
            alert(`Preview informasi dengan ID: ${id}`);
        }

        function shareInfo(id) {
            // Implement share functionality
            if (navigator.share) {
                navigator.share({
                    title: 'Informasi Publik',
                    text: 'Lihat informasi publik ini',
                    url: `${window.location.origin}${window.location.pathname}?info=${id}`
                });
            } else {
                // Fallback - copy to clipboard
                const url = `${window.location.origin}${window.location.pathname}?info=${id}`;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link berhasil disalin ke clipboard!');
                }).catch(() => {
                    alert('Gagal menyalin link');
                });
            }
        }

        // News Modal Functions
        function openNewsModal(news) {
            const modal = document.getElementById('newsModal');
            const modalImage = document.getElementById('modalNewsImage');
            const modalTitle = document.getElementById('modalNewsTitle');
            const modalDate = document.getElementById('modalNewsDate');
            const modalAuthor = document.getElementById('modalNewsAuthor');
            const modalCategory = document.getElementById('modalNewsCategory');
            const modalContent = document.getElementById('modalNewsContent');

            if (!modal) return;

            modalImage.src = news.image || 'ppid_assets/images/default-news.png';
            modalImage.alt = news.title || 'Berita';
            modalTitle.textContent = news.title || 'Judul Berita';
            // Format date to Indonesian for modal
            let indonesianModalDate = 'Tanggal tidak tersedia';
            if (news.published_at) {
                try {
                    const date = new Date(news.published_at);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    const dayName = days[date.getDay()];
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();
                    indonesianModalDate = `${dayName}, ${day} ${month} ${year}`;
                } catch (e) {
                    console.error('Error formatting date:', e);
                }
            }
            modalDate.innerHTML = '<i class="fas fa-calendar-alt me-1"></i> ' + indonesianModalDate;
            modalAuthor.innerHTML = '<i class="fas fa-user me-1"></i> ' + (news.author || 'Admin PPID');
            modalCategory.innerHTML = '<i class="fas fa-tag me-1"></i> ' + (news.category || 'Berita');
            modalContent.textContent = news.summary || 'Konten tidak tersedia';

            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeNewsModal() {
            const modal = document.getElementById('newsModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside or pressing Escape
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('newsModal');
            if (modal && event.target === modal) {
                closeNewsModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeNewsModal();
            }
        });
    </script>
</body>

</html>