<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PPID Mandailing Natal - Beranda</title>

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #f59e0b;
            --accent-color: #fbbf24;
            --text-color: #1f2937;
            --muted-color: #6b7280;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
        }

        /* Header Styles */
        .top-info-bar {
            background-color: #e5e7eb;
            padding: 8px 0;
            font-size: 13px;
            color: var(--muted-color);
            border-bottom: 1px solid #d1d5db;
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
            color: var(--muted-color);
            text-decoration: none;
            font-weight: 500;
        }

        .top-info-links a:hover {
            color: #374151;
        }

        .top-info-contact {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .navbar-custom {
            background: #000000;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-navbar {
            padding: 12px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: 600;
            text-decoration: none;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            background: white;
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
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 11px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .navbar-nav a:hover {
            color: #ddd;
            background-color: rgba(255, 255, 255, 0.1);
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
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 14px;
            padding: 0;
            color: white;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* Banner Styles */
        .banner-container {
            position: relative;
            width: 100%;
            height: 600px;
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
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        .banner-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: var(--accent-color);
            transform: scale(1.2);
        }

        .banner-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.3);
            border: none;
            width: 50px;
            height: 50px;
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
            background: rgba(255, 255, 255, 0.5);
            transform: translateY(-50%) scale(1.1);
        }

        .banner-arrow-prev {
            left: 20px;
        }

        .banner-arrow-next {
            right: 20px;
        }

        .banner-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: var(--accent-color);
            transition: width 4s linear;
        }

        .empty-banner {
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #999 0%, #666 100%);
            color: white;
            text-align: center;
        }

        /* Section Styles */
        .features-section {
            padding: 60px 0 80px;
            background-color: #ffffff;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            color: var(--primary-color);
            position: relative;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
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
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        /* Information Section Styles */
        .info-filter-tabs .nav-pills {
            background: #f8fafc;
            border-radius: 50px;
            padding: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .info-filter-tabs .nav-link {
            border-radius: 50px;
            padding: 10px 25px;
            margin: 0 2px;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .info-filter-tabs .nav-link.active {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .info-filter-tabs .nav-link:hover:not(.active) {
            background: #e2e8f0;
            color: #475569;
        }

        .info-search-bar .input-group {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 50px;
            overflow: hidden;
        }

        .info-search-bar .form-control {
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
            font-size: 16px;
        }

        .info-search-bar .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-item {
            transition: all 0.3s ease;
        }

        .info-item.hide {
            opacity: 0;
            transform: scale(0.8);
            height: 0;
            margin: 0;
            overflow: hidden;
        }

        .info-card-header {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 2;
        }

        .info-category-badge .badge {
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .info-actions {
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .info-card:hover .info-actions {
            opacity: 1;
        }

        .info-actions .btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .info-card-body {
            padding-top: 60px;
        }

        .info-icon {
            text-align: center;
            margin-bottom: 15px;
        }

        .info-meta {
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            padding: 10px 0;
            margin: 15px 0;
        }

        .info-actions-bottom .btn {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .info-actions-bottom .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .info-filter-tabs .nav-link {
                padding: 8px 16px;
                font-size: 14px;
            }

            .info-search-bar {
                margin-bottom: 30px;
            }
        }

        @media (max-width: 576px) {
            .info-filter-tabs .nav-pills {
                flex-wrap: wrap;
                justify-content: center;
            }

            .info-filter-tabs .nav-link {
                margin: 2px;
                padding: 6px 12px;
                font-size: 13px;
            }
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

        /* Layanan Section */
        .layanan-section {
            padding: 80px 0;
        }

        .layanan-container {
            display: flex;
            gap: 40px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            min-height: 600px;
        }

        .layanan-sidebar {
            width: 350px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 30px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 20px 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-item:hover {
            background-color: #f8fafc;
        }

        .sidebar-item.active {
            background-color: #eff6ff;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            background-color: #e2e8f0;
            transition: all 0.3s ease;
        }

        .sidebar-item.active .icon-wrapper {
            background-color: #3b82f6;
        }

        .sidebar-item.active .icon-wrapper i {
            color: white;
        }

        .layanan-content {
            flex: 1;
            padding: 40px;
        }

        .content-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cards-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .card-item {
            flex: 1;
            min-width: 250px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.4s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .card-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }

        /* Footer */
        .footer {
            background: rgb(0, 0, 0);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .footer a {
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #cbd5e1;
        }

        /* News Modal */
        .news-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            animation: fadeIn 0.3s ease;
        }

        .news-modal-content {
            background-color: #ffffff;
            margin: 2% auto;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideIn 0.3s ease;
        }

        .news-modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .news-modal-body {
            padding: 30px;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
            z-index: 10000;
        }

        .modal-close:hover {
            background: rgba(0,0,0,0.7);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

            .layanan-container {
                flex-direction: column;
                min-height: auto;
            }

            .layanan-sidebar {
                width: 100%;
                padding: 20px 0;
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

            .cards-container {
                flex-direction: column;
            }

            .banner-arrow {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 576px) {
            .banner-container {
                height: 300px;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .layanan-section {
                padding: 60px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Top Info Bar -->
    <div class="top-info-bar">
        <div class="container">
            <div class="top-info-links">
                <a href="#">TENTANG PPID</a>
                <a href="#">KONTAK PPID</a>
            </div>
            <div class="top-info-contact">
                <span><i class="fas fa-envelope"></i> ppid@mandailing.go.id</span>
                <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <nav class="navbar-custom">
        <div class="container main-navbar">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="index.php" class="navbar-brand">
                    <div class="logo-img">
                        <img src="ppid_assets/logo-new.png" alt="Logo">
                    </div>
                </a>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="navbar-nav" id="navLinks">
                    <a href="index.php">BERANDA</a>
                    <a href="index.php?controller=user&action=profile">PROFIL</a>
                    <a href="#">LAYANAN INFORMASI PUBLIK</a>
                    <a href="#">DAFTAR INFORMASI PUBLIK</a>
                    <a href="#">TATA KELOLA</a>
                    <a href="#">INFO</a>
                    <a href="index.php?controller=auth&action=login">LOGIN</a>

                    <div class="nav-social">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="Search"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Banner Slider -->
    <section class="hero-section" data-aos="fade-in">
        <?php if (!empty($data['slider'])): ?>
        <div class="banner-container">
            <div class="banner-wrapper" id="bannerWrapper">
                <div class="banner-track" id="bannerTrack">
                    <?php foreach($data['slider'] as $index => $banner): ?>
                    <div class="banner-slide" data-index="<?= $index ?>">
                        <img src="<?= $banner['image'] ?>" alt="Banner <?= $index+1 ?>" class="banner-image">
                        <div class="banner-overlay"></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Navigation Dots -->
            <div class="banner-dots" id="bannerDots">
                <?php for($i = 0; $i < count($data['slider']); $i++): ?>
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
                <i class="fas fa-images fa-3x mb-3"></i>
                <p>Belum ada banner yang ditampilkan</p>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Layanan Informasi Publik -->
    <section class="layanan-section features-section" style="background-color: #f8f9fa;" data-aos="fade-up">
        <div class="container">
            <div class="layanan-container">
                <div class="layanan-sidebar" data-aos="fade-right" data-aos-delay="100">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        <li class="sidebar-item active" data-target="daftar-informasi">
                            <div class="icon-wrapper">
                                <i class="fas fa-database"></i>
                            </div>
                            <span class="sidebar-text">Daftar Informasi Publik</span>
                        </li>
                        <li class="sidebar-item" data-target="permohonan-informasi">
                            <div class="icon-wrapper">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span class="sidebar-text">Permohonan Informasi</span>
                        </li>
                        <li class="sidebar-item" data-target="laporan-pelayanan">
                            <div class="icon-wrapper">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="sidebar-text">Laporan Pelayanan</span>
                        </li>
                        <li class="sidebar-item" data-target="layanan-kepuasan">
                            <div class="icon-wrapper">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <span class="sidebar-text">Layanan Kepuasan Masyarakat</span>
                        </li>
                    </ul>
                </div>

                <div class="layanan-content" data-aos="fade-left" data-aos-delay="200">
                    <div class="content-section active" id="daftar-informasi">
                        <h3 class="content-title">Daftar Informasi Publik</h3>
                        <p class="content-description">Salah satu kewajiban badan publik yang dinyatakan dalam Undang-Undang No 14 Tahun 2008 adalah menyediakan Daftar Informasi Publik (DIP). DIP adalah catatan yang berisi keterangan sistematis tentang informasi publik yang berada dibawah penguasaan badan publik.</p>

                        <div class="cards-container">
                            <div class="card-item" data-aos="zoom-in" data-aos-delay="100">
                                <div class="card-icon" style="background-color: #dbeafe; color: #3b82f6;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span>Informasi Berkala</span>
                            </div>
                            <div class="card-item" data-aos="zoom-in" data-aos-delay="200">
                                <div class="card-icon" style="background-color: #ffedd5; color: #f97316;">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <span>Informasi Serta Merta</span>
                            </div>
                            <div class="card-item" data-aos="zoom-in" data-aos-delay="300">
                                <div class="card-icon" style="background-color: #d1fae5; color: #10b981;">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <span>Informasi Setiap Saat</span>
                            </div>
                        </div>
                    </div>

                    <div class="content-section" id="permohonan-informasi">
                        <h3 class="content-title">Permohonan Informasi</h3>
                        <p class="content-description">Setiap orang berhak memperoleh informasi publik sesuai dengan ketentuan Undang-Undang No 14 Tahun 2008. Melalui aplikasi PPID Kemendagri ini setiap orang dapat mengajukan permohonan informasi secara mudah.</p>
                    </div>

                    <div class="content-section" id="laporan-pelayanan">
                        <h3 class="content-title">Laporan Pelayanan</h3>
                        <p class="content-description">Peraturan Komisi Informasi No. 1 Tahun 2008 tentang Standar Layanan Informasi Publik menyatakan bahwa badan publik memiliki kewajiban membuat dan mengumumkan laporan tentang layanan informasi publik.</p>
                    </div>

                    <div class="content-section" id="layanan-kepuasan">
                        <h3 class="content-title">Layanan Kepuasan Masyarakat</h3>
                        <p class="content-description">Partisipasi masyarakat untuk ikut mengawasi, memberikan masukan dan saran untuk peningkatan kualitas pelayanan pengelolaan informasi sangat dibutuhkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Information Section -->
    <section class="features-section" style="background-color: #f8fafc;" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title" data-aos="fade-left">Informasi Publik</h2>

            <!-- Filter Tabs -->
            <div class="info-filter-tabs d-flex justify-content-center mb-5" data-aos="fade-up">
                <div class="nav nav-pills" id="info-tabs">
                    <button class="nav-link active" data-filter="all">Semua</button>
                    <button class="nav-link" data-filter="berkala">Berkala</button>
                    <button class="nav-link" data-filter="serta-merta">Serta Merta</button>
                    <button class="nav-link" data-filter="setiap-saat">Setiap Saat</button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="info-search-bar mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="input-group">
                            <span class="input-group-text" style="background: white; border-right: none;">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="infoSearch" placeholder="Cari informasi publik..." style="border-left: none;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="info-grid" id="infoGrid">
                <?php if (!empty($data['informasi'])): ?>
                    <?php foreach($data['informasi'] as $index => $info): ?>
                    <div class="info-item" data-category="<?= strtolower(str_replace(' ', '-', $info['category'])) ?>" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                        <div class="card info-card h-100">
                            <div class="info-card-header">
                                <div class="info-category-badge">
                                    <span class="badge" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <?= $info['category'] ?>
                                    </span>
                                </div>
                                <div class="info-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="previewInfo(<?= $info['id'] ?>)" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="shareInfo(<?= $info['id'] ?>)" title="Share">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="info-card-body p-4">
                                <div class="info-icon mb-3">
                                    <i class="<?= getInfoIcon($info['file_type']) ?> fa-2x" style="color: #667eea;"></i>
                                </div>
                                <h5 class="card-title mb-3"><?= $info['title'] ?></h5>
                                <p class="card-text text-muted small mb-3"><?= $info['description'] ?></p>

                                <div class="info-meta mb-3">
                                    <div class="d-flex justify-content-between align-items-center text-sm">
                                        <span class="text-muted">
                                            <i class="fas fa-file-<?= strtolower($info['file_type']) ?> me-1"></i>
                                            <?= strtoupper($info['file_type']) ?>
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-download me-1"></i>
                                            <?= number_format($info['download_count']) ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="info-actions-bottom">
                                    <a href="index.php?controller=download&action=informasi&id=<?= $info['id'] ?>"
                                       class="btn btn-primary w-100"
                                       style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border: none;">
                                        <i class="fas fa-download me-2"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum Ada Informasi</h4>
                            <p class="text-muted">Informasi publik akan ditampilkan di sini</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Load More Button -->
            <?php if (!empty($data['informasi']) && count($data['informasi']) >= 8): ?>
            <div class="text-center mt-5" data-aos="fade-up">
                <button class="btn btn-outline-primary btn-lg" id="loadMoreInfo">
                    <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                </button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php
    function getInfoIcon($fileType) {
        switch(strtolower($fileType)) {
            case 'pdf': return 'fas fa-file-pdf';
            case 'doc':
            case 'docx': return 'fas fa-file-word';
            case 'xls':
            case 'xlsx': return 'fas fa-file-excel';
            case 'ppt':
            case 'pptx': return 'fas fa-file-powerpoint';
            case 'jpg':
            case 'jpeg':
            case 'png': return 'fas fa-file-image';
            default: return 'fas fa-file-alt';
        }
    }
    ?>

    <!-- Statistics Section -->
    <section class="features-section" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title" data-aos="fade-right">Statistik Layanan</h2>
            <div class="row">
                <?php foreach($data['statistik'] as $index => $stat): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card stat-card" data-aos="zoom-in" data-aos-delay="<?= $index * 100 ?>">
                        <div class="p-4 text-center">
                            <div class="card-icon mx-auto mb-3" style="background-color: rgba(16, 185, 129, 0.2);">
                                <i class="<?= $stat['icon'] ?>" style="color: #10b981;"></i>
                            </div>
                            <span class="stat-number"><?= $stat['value'] ?><?= isset($stat['unit']) ? $stat['unit'] : '' ?></span>
                            <p class="stat-label"><?= $stat['label'] ?></p>
                            <p class="text-success small"><?= $stat['growth'] ?> <?= $stat['description'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="features-section" style="background-color: #f8fafc;" data-aos="fade-up">
        <div class="container">
            <h2 class="section-title" data-aos="fade-left">Berita Terbaru</h2>
            <?php if (!empty($data['berita'])): ?>
            <div class="news-grid">
                <?php foreach($data['berita'] as $index => $news): ?>
                <div class="card news-card" data-aos="fade-up" data-aos-delay="<?= $index * 75 ?>" onclick="openNewsModal(<?= htmlspecialchars(json_encode($news), ENT_QUOTES, 'UTF-8') ?>)">
                    <div class="news-image">
                        <img src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="img-fluid">
                    </div>
                    <div class="p-4">
                        <?php
                        // Convert date to Indonesian format
                        $date = new DateTime($news['published_at']);
                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $dayName = $days[$date->format('w')];
                        $day = $date->format('d');
                        $month = $months[(int)$date->format('n')];
                        $year = $date->format('Y');
                        $indonesianDate = "$dayName, $day $month $year";
                        ?>
                        <span class="news-date text-warning"><i class="fas fa-calendar-alt me-1"></i> <?= $indonesianDate ?></span>
                        <h5 class="card-title"><?= htmlspecialchars($news['title']) ?></h5>
                        <p class="card-text"><?= strlen($news['summary']) > 100 ? substr(htmlspecialchars($news['summary']), 0, 100) . '...' : htmlspecialchars($news['summary']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-info"><?= htmlspecialchars($news['category']) ?></span>
                            <span class="text-muted"><i class="fas fa-eye me-1"></i> <?= $news['views'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum Ada Berita</h4>
                <p class="text-muted">Berita terbaru akan ditampilkan di sini</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" data-aos="fade-in">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4" data-aos="fade-right" data-aos-delay="100">
                    <h5>PPID Madina</h5>
                    <p><?= $data['kontak']['alamat'] ?></p>
                    <p><i class="fas fa-phone me-2"></i> <?= $data['kontak']['telepon'] ?></p>
                    <p><i class="fas fa-envelope me-2"></i> <?= $data['kontak']['email'] ?></p>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <h5>Link Terkait</h5>
                    <a href="#">Beranda</a>
                    <a href="#">Profil</a>
                    <a href="#">Layanan Informasi</a>
                    <a href="#">Daftar Informasi Publik</a>
                    <a href="#">Tata Kelola</a>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-left" data-aos-delay="300">
                    <h5>Media Sosial</h5>
                    <div class="social-links">
                        <?php foreach($data['kontak']['media_sosial'] as $social): ?>
                        <a href="<?= $social['url'] ?>" title="<?= $social['platform'] ?>"><i class="<?= $social['icon'] ?>"></i></a>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-3">Jam Operasional:</p>
                    <p>Senin - Kamis: <?= $data['kontak']['jam_operasional']['senin_kamis'] ?></p>
                    <p>Jumat: <?= $data['kontak']['jam_operasional']['jumat'] ?></p>
                </div>
            </div>
            <div class="copyright" data-aos="fade-in" data-aos-delay="500">
                <p>&copy; 2025 PPID Mandailing Natal. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

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

    <script>
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

            // Layanan sidebar functionality
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            const contentSections = document.querySelectorAll('.content-section');

            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');

                    sidebarItems.forEach(i => i.classList.remove('active'));
                    contentSections.forEach(s => s.classList.remove('active'));

                    this.classList.add('active');
                    const targetSection = document.getElementById(target);
                    if (targetSection) {
                        targetSection.classList.add('active');
                    }
                });
            });

            // Information Section Filter and Search Functionality
            const filterTabs = document.querySelectorAll('#info-tabs .nav-link');
            const searchInput = document.getElementById('infoSearch');
            const infoItems = document.querySelectorAll('.info-item');

            // Filter functionality
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Update active tab
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Filter items
                    filterItems(filter, searchInput.value);
                });
            });

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const activeFilter = document.querySelector('#info-tabs .nav-link.active').getAttribute('data-filter');
                    filterItems(activeFilter, this.value);
                });
            }

            function filterItems(category, searchTerm) {
                infoItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    const itemTitle = item.querySelector('.card-title').textContent.toLowerCase();
                    const itemDescription = item.querySelector('.card-text').textContent.toLowerCase();

                    const matchesCategory = category === 'all' || itemCategory === category;
                    const matchesSearch = searchTerm === '' ||
                        itemTitle.includes(searchTerm.toLowerCase()) ||
                        itemDescription.includes(searchTerm.toLowerCase());

                    if (matchesCategory && matchesSearch) {
                        item.classList.remove('hide');
                        setTimeout(() => {
                            item.style.display = 'block';
                        }, 10);
                    } else {
                        item.classList.add('hide');
                        setTimeout(() => {
                            if (item.classList.contains('hide')) {
                                item.style.display = 'none';
                            }
                        }, 300);
                    }
                });
            }

            // Load More functionality
            const loadMoreBtn = document.getElementById('loadMoreInfo');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
                    this.disabled = true;

                    // Simulate loading more content
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        // In real application, you would load more content via AJAX
                        alert('Fitur load more akan terhubung ke backend untuk memuat lebih banyak informasi');
                    }, 1000);
                });
            }
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