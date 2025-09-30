<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pendaftaran - PPID Mandailing Natal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .top-info-bar {
            background-color: #e5e7eb;
            padding: 8px 0;
            font-size: 13px;
            color: #6b7280;
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
            color: #6b7280;
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

        .top-info-contact span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .top-info-contact i {
            font-size: 12px;
        }

        .navbar-custom {
            background: #000000;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-navbar {
            padding: 12px 0;
        }

        .main-navbar .d-flex {
            flex-wrap: nowrap !important;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: 600;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: #e5e7eb !important;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .logo-img img {
            width: 40px;
            height: 40px;
        }

        .nav-text {
            display: flex;
            flex-direction: column;
        }

        .nav-title {
            font-size: 14px;
            font-weight: 500;
            line-height: 1.2;
            margin: 0;
        }

        .nav-subtitle {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
        }

        .navbar-nav {
            display: flex;
            flex-direction: row;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
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

        .nav-social a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .login-btn {
            background: #fbbf24;
            color: #1e3a8a !important;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            text-decoration: none;
        }

        .login-btn:hover {
            background: #f59e0b;
            color: #1e3a8a !important;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .breadcrumb-section {
            background-color: #e5e7eb;
            padding: 15px 0;
        }

        .breadcrumb {
            background: none;
            margin: 0;
            padding: 0;
        }

        .breadcrumb-item {
            font-size: 14px;
        }

        .breadcrumb-item a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #1e3a8a;
            font-weight: 600;
        }

        .main-content {
            padding: 40px 0;
            min-height: calc(100vh - 200px);
        }

        .card-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .card-container:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .form-container {
            padding: 40px;
        }

        .terms-container {
            padding: 40px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            color: #6b7280;
            margin-bottom: 40px;
            font-size: 16px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 18px;
        }

        .btn-register {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-register:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 4px solid #3b82f6;
        }

        .info-title {
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 0;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px;
            margin-bottom: 25px;
        }

        .required {
            color: #dc3545;
        }

        .terms-section {
            margin-top: 0;
        }

        .terms-title {
            color: #1e3a8a;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
        }

        .collapsible-item {
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .collapsible-item:hover {
            border-color: #d1d5db;
        }

        .collapsible-header {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            transition: all 0.3s ease;
            border-bottom: 1px solid #e5e7eb;
        }

        .collapsible-header:hover {
            background: #f3f4f6;
        }

        .collapsible-header h6 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }

        .collapsible-content {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            background: white;
        }

        .collapsible-content.show {
            padding: 20px;
        }

        .collapsible-content p {
            margin-bottom: 15px;
            color: #6b7280;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            text-align: justify;
        }

        .collapsible-content p:last-child {
            margin-bottom: 0;
        }

        .collapsible-content ol {
            margin-bottom: 0;
            color: #6b7280;
            line-height: 1.6;
            padding-left: 20px;
        }

        .collapsible-content li {
            margin-bottom: 10px;
        }

        .collapsible-content li:last-child {
            margin-bottom: 0;
        }

        /* Style untuk paragraf dengan konten panjang */
        .collapsible-content p.long-content {
            line-height: 1.5;
            margin-bottom: 20px;
        }

        /* Style untuk paragraf dengan konten sangat panjang */
        .collapsible-content p.very-long-content {
            line-height: 1.4;
            margin-bottom: 25px;
            font-size: 0.95em;
        }

        .collapsible-icon {
            transition: transform 0.3s ease;
            color: #6b7280;
            font-size: 18px;
        }

        .collapsible-icon.rotate {
            transform: rotate(180deg);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #3b82f6;
            text-decoration: underline;
        }

        .accessibility-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            z-index: 1000;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .accessibility-btn:hover {
            background: #059669;
            transform: scale(1.1);
        }

        /* Responsive styles */
        @media (max-width: 1200px) {
            .navbar-nav {
                gap: 6px;
            }

            .navbar-nav a {
                font-size: 13px;
                padding: 6px 12px;
            }

            .nav-social {
                margin-left: 10px;
                padding-left: 10px;
            }

            .top-info-contact {
                gap: 15px;
            }
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 30px 0;
            }

            .form-container,
            .terms-container {
                padding: 30px;
            }

            .col-lg-6 {
                margin-bottom: 30px;
            }

            .col-lg-6:last-child {
                margin-bottom: 0;
            }

            .navbar-nav {
                gap: 4px;
            }

            .navbar-nav a {
                font-size: 12px;
                padding: 5px 8px;
            }

            .nav-social {
                display: none;
            }

            .top-info-contact span {
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 0;
            }
            
            .form-container,
            .terms-container {
                padding: 25px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .terms-title {
                font-size: 20px;
            }
            
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

            .navbar-nav a {
                padding: 12px 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 15px;
            }

            .nav-social {
                margin-left: 0;
                padding-left: 0;
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 10px;
                margin-top: 10px;
                justify-content: center;
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }
            
            .logo-img {
                width: 40px;
                height: 40px;
                margin-right: 10px;
            }
            
            .logo-img img {
                width: 30px;
                height: 30px;
            }
            
            .nav-text {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px 0;
            }
            
            .form-container,
            .terms-container {
                padding: 20px 15px;
            }
            
            .form-title {
                font-size: 22px;
            }
            
            .terms-title {
                font-size: 18px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-control,
            .form-select {
                font-size: 14px;
                padding: 10px 12px;
            }
            
            .btn-register {
                font-size: 14px;
                padding: 12px;
            }
            
            .collapsible-header {
                padding: 12px 15px;
            }
            
            .collapsible-header h6 {
                font-size: 14px;
            }
        }

        /* Extra small devices */
        @media (max-width: 400px) {
            .navbar-brand {
                font-size: 12px;
            }
            
            .nav-title {
                font-size: 10px;
            }
            
            .nav-subtitle {
                font-size: 14px;
            }
            
            .logo-img {
                width: 35px;
                height: 35px;
                margin-right: 8px;
            }
            
            .logo-img img {
                width: 25px;
                height: 25px;
            }
        }

        /* Animation for form elements */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Focus styles for accessibility */
        .form-control:focus,
        .form-select:focus,
        .btn-register:focus,
        .password-toggle:focus,
        .collapsible-header:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 1em;
            height: 1em;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
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
                <span><i class="fas fa-envelope"></i> ppid@lampungprov.go.id</span>
                <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <nav class="navbar-custom">
        <div class="container main-navbar">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo and Title -->
                <a href="index.php" class="navbar-brand">
                     <div class="logo-img">
                        <img src="ppid_assets/logo-new.png" alt="Logo">
                    </div>
                </a>

                <!-- Mobile menu button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Navigation Links -->
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

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?controller=auth&action=login">Pengguna</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pendaftaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Left Side - Registration Form -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card-container">
                        <div class="form-container">
                            <h2 class="form-title">Pendaftaran</h2>
                            <p class="form-subtitle">Silahkan masukan informasi Anda</p>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?php echo htmlspecialchars($success); ?>
                                    <br><a href="index.php?controller=auth&action=login" class="alert-link">Klik di sini untuk login</a>
                                </div>
                            <?php endif; ?>

                            <!-- Info Section -->
                            <div class="info-section">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <h5 class="info-title">Informasi Pribadi</h5>
                                </div>
                            </div>

                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nama_lengkap"
                                        name="nama_lengkap"
                                        value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan) <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nik"
                                        name="nik"
                                        maxlength="16"
                                        pattern="[0-9]{16}"
                                        value="<?php echo isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : ''; ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="alamat" class="form-label">Alamat <span class="required">*</span></label>
                                    <textarea
                                        class="form-control"
                                        id="alamat"
                                        name="alamat"
                                        rows="3"
                                        required><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="provinsi" class="form-label">Provinsi <span class="required">*</span></label>
                                    <select class="form-select" id="provinsi" name="provinsi" required>
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="city" class="form-label">Kota/Kabupaten <span class="required">*</span></label>
                                    <select class="form-select" id="city" name="city" required disabled>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="required">*</span></label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="usia" class="form-label">Usia</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="usia"
                                        name="usia"
                                        min="17"
                                        max="100"
                                        value="<?php echo isset($_POST['usia']) ? htmlspecialchars($_POST['usia']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="pendidikan" class="form-label">Pendidikan Terakhir</label>
                                    <select class="form-select" id="pendidikan" name="pendidikan">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'SD') ? 'selected' : ''; ?>>SD</option>
                                        <option value="SMP" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'SMP') ? 'selected' : ''; ?>>SMP</option>
                                        <option value="SMA/SMK" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'SMA/SMK') ? 'selected' : ''; ?>>SMA/SMK</option>
                                        <option value="D3" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'D3') ? 'selected' : ''; ?>>D3</option>
                                        <option value="S1" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'S1') ? 'selected' : ''; ?>>S1</option>
                                        <option value="S2" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'S2') ? 'selected' : ''; ?>>S2</option>
                                        <option value="S3" <?php echo (isset($_POST['pendidikan']) && $_POST['pendidikan'] == 'S3') ? 'selected' : ''; ?>>S3</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="pekerjaan"
                                        name="pekerjaan"
                                        value="<?php echo isset($_POST['pekerjaan']) ? htmlspecialchars($_POST['pekerjaan']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="no_kontak" class="form-label">No. Kontak</label>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="no_kontak"
                                        name="no_kontak"
                                        value="<?php echo isset($_POST['no_kontak']) ? htmlspecialchars($_POST['no_kontak']) : ''; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email <span class="required">*</span></label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password <span class="required">*</span></label>
                                    <div class="password-field">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            minlength="6"
                                            required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'password-icon')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password <span class="required">*</span></label>
                                    <div class="password-field">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="confirm_password"
                                            name="confirm_password"
                                            minlength="6"
                                            required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'confirm-password-icon')">
                                            <i class="fas fa-eye" id="confirm-password-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status_pengguna" class="form-label">Status Pengguna <span class="required">*</span></label>
                                    <select class="form-select" id="status_pengguna" name="status_pengguna" required>
                                        <option value="">Pilih Status Pengguna</option>
                                        <option value="pribadi" <?php echo (isset($_POST['status_pengguna']) && $_POST['status_pengguna'] == 'pribadi') ? 'selected' : ''; ?>>Pribadi</option>
                                        <option value="lembaga" <?php echo (isset($_POST['status_pengguna']) && $_POST['status_pengguna'] == 'lembaga') ? 'selected' : ''; ?>>Lembaga</option>
                                    </select>
                                </div>

                                <div class="form-group" id="nama_lembaga_group" style="display: none;">
                                    <label for="nama_lembaga" class="form-label">Nama Lembaga</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nama_lembaga"
                                        name="nama_lembaga"
                                        value="<?php echo isset($_POST['nama_lembaga']) ? htmlspecialchars($_POST['nama_lembaga']) : ''; ?>">
                                </div>

                                <div class="form-group" id="upload_ktp_group">
                                    <label for="upload_ktp" class="form-label">Upload KTP <span class="required">*</span></label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="upload_ktp"
                                        name="upload_ktp"
                                        accept="image/*,application/pdf"
                                        required>
                                </div>

                                <div class="form-group" id="upload_akta_group" style="display: none;">
                                    <label for="upload_akta" class="form-label">Upload Akta (untuk lembaga)</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="upload_akta"
                                        name="upload_akta"
                                        accept="image/*,application/pdf">
                                </div>

                                <button type="submit" class="btn-register">
                                    <i class="fas fa-user-plus me-2"></i>
                                    DAFTAR
                                </button>
                            </form>

                            <a href="index.php?controller=auth&action=login" class="login-link">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sudah Punya Akun? Login
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Terms Section -->
                <div class="col-lg-6">
                    <div class="card-container">
                        <div class="terms-container">
                            <h3 class="terms-title">
                                <i class="fas fa-user-friends me-2"></i>
                                Ketentuan Pengguna
                            </h3>

                            <div class="terms-section">
                                <div class="collapsible-item">
                                    <div class="collapsible-header" onclick="toggleCollapsible('umum')">
                                        <h6>Umum</h6>
                                        <i class="fas fa-chevron-up collapsible-icon" id="umum-icon"></i>
                                    </div>
                                    <div class="collapsible-content show" id="umum-content">
                                        <ol>
                                            <li class="mb-2">Pengguna adalah siapa saja yang memanfaatkan layanan SIP PPID baik sebagai individu maupun lembaga.</li>
                                            <li class="mb-2">Dengan menggunakan Layanan ini Pengguna menyetujui sepenuhnya Persyaratan dan Ketentuan Layanan yang diuraikan di dalam dokumen ini. Jika Pengguna tidak menyetujui Persyaratan dan Ketentuan Layanan ini, harap jangan gunakan Layanan ini.</li>
                                        </ol>
                                    </div>
                                </div>

                                <div class="collapsible-item">
                                    <div class="collapsible-header" onclick="toggleCollapsible('ketentuan')">
                                        <h6>Ketentuan Pengguna</h6>
                                        <i class="fas fa-chevron-down collapsible-icon" id="ketentuan-icon"></i>
                                    </div>
                                    <div class="collapsible-content" id="ketentuan-content">
                                        <p>Seluruh sumber data dan informasi yang disimpan/direkam/diolah pada Layanan ini termasuk validasi dan verifikasi keabsahannya sepenuhnya menjadi tanggung jawab dari Pengguna Layanan.</p>
                                        <p>Operasional pengelolaan seluruh data dan informasi mencakup pemutakhiran data dan informasi dimaksud sepenuhnya menjadi tanggung jawab dari Pengguna sesuai hak akses masing-masing.</p>
                                        <p>Pengguna dilarang mendistribusikan data dan informasi dari Layanan ini kepada pihak ketiga kecuali telah mendapat ijin resmi dan tertulis dari Pengelola.</p>
                                        <p>Pengguna berkewajiban memahami dan mematuhi Kebijakan Privasi Data dan Informasi dan Kebijakan Keamanan yang ditetapkan oleh pengelola.</p>
                                    </div>
                                </div>

                                <div class="collapsible-item">
                                    <div class="collapsible-header" onclick="toggleCollapsible('privasi')">
                                        <h6>Kebijakan Privasi Data & Informasi</h6>
                                        <i class="fas fa-chevron-down collapsible-icon" id="privasi-icon"></i>
                                    </div>
                                    <div class="collapsible-content" id="privasi-content">
                                        <p>
                                            Pengelola akan mencatat informasi tentang semua aktifitas dari pengguna. Mencakup semua transaksi data dan informasi dari atau antar pengguna. Pengelola juga dapat mengumpulkan data dan informasi tentang pengguna dari pengguna lain. Hal ini untuk memberikan pengalaman yang lebih baik dalam Layanan untuk para pengguna.
                                        </p>
                                        <p>
                                            Penggunaan Layanan dan isinya disediakan dan dikembangkan oleh Pengelola secara berkesinambungan untuk kemudahan pengelolaan data dan informasi sesuai kepentingan dan kebutuhan Pengguna. Oleh sebab itu, pengelolaan dan pemanfaatan data dan informasi yang tersedia merupakan sepenuhnya tanggung jawab dari Pengguna. Tersedia apa adanya dan sebagaimana tersedia, tanpa jaminan jenis apapun dari Pengelola.
                                        </p>
                                        <p>
                                            Pengelola tidak akan dikenakan tanggung jawab atas kerusakan langsung, tidak langsung, tidak disengaja, khusus atau secara konsekuensi, kerugian atau gangguan yang timbul dari penggunaan atau kesalahan informasi yang diberikan oleh Pengguna.
                                        </p>
                                        <p>
                                            Pengguna memahami sepenuhnya terhadap privasi data dan informasi yang mereka kelola. Pemanfaatan data dan informasi serta fasilitas pada Layanan ini sepenuhnya merupakan tanggung jawab pengguna.
                                        </p>
                                    </div>
                                </div>

                                <div class="collapsible-item">
                                    <div class="collapsible-header" onclick="toggleCollapsible('penggunaan')">
                                        <h6>Ketentuan Penggunaan Akun</h6>
                                        <i class="fas fa-chevron-down collapsible-icon" id="penggunaan-icon"></i>
                                    </div>
                                    <div class="collapsible-content" id="penggunaan-content">
                                        <p>Pengguna bertanggung jawab atas keamanan akun dan semua aktivitas yang dilakukan. Dilarang menyalahgunakan akun untuk kepentingan yang melanggar hukum atau merugikan pihak lain.</p>
                                    </div>
                                </div>

                                <div class="collapsible-item">
                                    <div class="collapsible-header" onclick="toggleCollapsible('perubahan')">
                                        <h6>Perubahan Aturan</h6>
                                        <i class="fas fa-chevron-down collapsible-icon" id="perubahan-icon"></i>
                                    </div>
                                    <div class="collapsible-content" id="perubahan-content">
                                        <p>
                                            Kebijakan Pengelola memainkan peranan penting dalam mempertahankan pengalaman positif bagi Pengguna. Harap patuhi kebijakan tersebut saat menggunakan Layanan ini. Saat Pengelola diberi tahu tentang kemungkinan pelanggaran kebijakan, kami dapat meninjau dan mengambil tindakan, termasuk membatasi atau menghentikan akses pengguna ke Layanan ini.
                                        </p>
                                        <p>
                                            Pengelola dapat memperbaiki, menambah, atau mengurangi ketentuan ini setiap saat, dengan atau tanpa pemberitahuan sebelumnya. Pengguna diharapkan memantau Persyaratan dan Ketentuan Layanan ini sewaktu-waktu. Seluruh Pengguna terikat dan tunduk kepada ketentuan yang telah diperbaiki/ditambah/dikurangi oleh Pengelola.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Accessibility Button -->
    <button class="accessibility-btn" title="Accessibility">
        <i class="fas fa-universal-access"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Load provinces on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProvinces();
            // Initialize collapsibles
            initializeCollapsibles();
            // Adjust layout on resize
            window.addEventListener('resize', debounce(adjustLayout, 250));
        });

        // Debounce function to limit how often a function can be called
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Function to adjust layout based on screen size
        function adjustLayout() {
            // Get all collapsible contents that are currently shown
            const openCollapsibles = document.querySelectorAll('.collapsible-content.show');
            openCollapsibles.forEach(collapsible => {
                updateCollapsibleHeight(collapsible);
            });
        }

        // Function to initialize all collapsibles
        function initializeCollapsibles() {
            // Set initial state for all collapsibles
            const collapsibles = document.querySelectorAll('.collapsible-content');
            collapsibles.forEach(collapsible => {
                if (collapsible.classList.contains('show')) {
                    updateCollapsibleHeight(collapsible);
                }
            });
            
            // Add keyboard accessibility
            const headers = document.querySelectorAll('.collapsible-header');
            headers.forEach(header => {
                header.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const sectionId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                        toggleCollapsible(sectionId);
                    }
                });
            });
        }

        // Load provinces from API
        async function loadProvinces() {
            try {
                const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                const provinces = await response.json();
                const provinsiSelect = document.getElementById('provinsi');

                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.textContent = province.name;
                    option.setAttribute('data-id', province.id);
                    provinsiSelect.appendChild(option);
                });

                // Restore selected province if form was submitted with errors
                <?php if (isset($_POST['provinsi'])): ?>
                    provinsiSelect.value = '<?php echo htmlspecialchars($_POST['provinsi']); ?>';
                    if (provinsiSelect.value) {
                        const selectedOption = provinsiSelect.options[provinsiSelect.selectedIndex];
                        if (selectedOption && selectedOption.getAttribute('data-id')) {
                            loadCities(selectedOption.getAttribute('data-id'));
                        }
                    }
                <?php endif; ?>
            } catch (error) {
                console.error('Error loading provinces:', error);
                // Fallback: add some common provinces
                const provinsiSelect = document.getElementById('provinsi');
                const fallbackProvinces = [
                    'Sumatera Utara', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
                    'Jawa Timur', 'Sumatera Barat', 'Sumatera Selatan', 'Kalimantan Timur'
                ];
                fallbackProvinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province;
                    option.textContent = province;
                    provinsiSelect.appendChild(option);
                });
            }
        }

        // Load cities based on selected province
        async function loadCities(provinceId) {
            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                const cities = await response.json();
                const citySelect = document.getElementById('city');

                // Clear previous options
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                citySelect.disabled = false;

                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.name;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });

                // Restore selected city if form was submitted with errors
                <?php if (isset($_POST['city'])): ?>
                    citySelect.value = '<?php echo htmlspecialchars($_POST['city']); ?>';
                <?php endif; ?>
            } catch (error) {
                console.error('Error loading cities:', error);
                // Enable manual input if API fails
                const citySelect = document.getElementById('city');
                citySelect.disabled = false;
            }
        }

        // Handle province change
        document.getElementById('provinsi').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const citySelect = document.getElementById('city');

            if (selectedOption && selectedOption.getAttribute('data-id')) {
                loadCities(selectedOption.getAttribute('data-id'));
            } else {
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                citySelect.disabled = true;
            }
        });

        // Handle user status change
        document.getElementById('status_pengguna').addEventListener('change', function() {
            const status = this.value;
            const namaLembagaGroup = document.getElementById('nama_lembaga_group');
            const uploadAktaGroup = document.getElementById('upload_akta_group');

            if (status === 'lembaga') {
                namaLembagaGroup.style.display = 'block';
                uploadAktaGroup.style.display = 'block';
                // Set required attribute for AKTA when status is lembaga
                document.getElementById('upload_akta').required = true;
            } else {
                namaLembagaGroup.style.display = 'none';
                uploadAktaGroup.style.display = 'none';
                // Remove required attribute when status is not lembaga
                document.getElementById('upload_akta').required = false;
            }
        });

        // Initialize visibility based on existing value (in case of validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            const statusPenggunaSelect = document.getElementById('status_pengguna');
            if (statusPenggunaSelect.value === 'lembaga') {
                document.getElementById('nama_lembaga_group').style.display = 'block';
                document.getElementById('upload_akta_group').style.display = 'block';
                document.getElementById('upload_akta').required = true;
            }
        });

        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Function to update collapsible height based on content
        function updateCollapsibleHeight(collapsibleElement) {
            // Adjust paragraph styling first
            adjustParagraphStyling();

            // Set to auto to get the actual content height
            collapsibleElement.style.maxHeight = 'auto';
            const height = collapsibleElement.scrollHeight + 'px';
            // Set back to specific height for transition
            collapsibleElement.style.maxHeight = height;
        }

        // Enhanced toggle function for collapsibles
        function toggleCollapsible(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const icon = document.getElementById(sectionId + '-icon');

            // If opening this one, close all others
            if (!content.classList.contains('show')) {
                // Close all other collapsibles
                const allContents = document.querySelectorAll('.collapsible-content');
                const allIcons = document.querySelectorAll('.collapsible-icon');

                allContents.forEach(cont => {
                    if (cont.id !== sectionId + '-content') {
                        cont.classList.remove('show');
                        cont.style.maxHeight = '0px';
                        // Update icon for closed items
                        const contIcon = document.getElementById(cont.id.replace('-content', '-icon'));
                        if (contIcon) {
                            contIcon.classList.remove('fa-chevron-up');
                            contIcon.classList.add('fa-chevron-down');
                        }
                    }
                });
            }

            // Toggle the clicked collapsible
            if (content.classList.contains('show')) {
                // Closing
                content.style.maxHeight = '0px';
                setTimeout(() => {
                    content.classList.remove('show');
                }, 300);
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                // Opening
                content.classList.add('show');
                // Update height for smooth transition
                setTimeout(() => {
                    updateCollapsibleHeight(content);
                }, 10);
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }

        // Auto-adjust collapsible height based on content
        function adjustCollapsibleHeight() {
            const collapsibles = document.querySelectorAll('.collapsible-content.show');
            collapsibles.forEach(collapsible => {
                updateCollapsibleHeight(collapsible);
            });
        }

        // Call adjustCollapsibleHeight when window is resized
        window.addEventListener('resize', debounce(adjustCollapsibleHeight, 250));

        // NIK validation
        document.getElementById('nik').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });

        // Phone number validation
        document.getElementById('no_kontak').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '');
        });

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return;
            }

            // Show loading state
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner me-2"></span>Memproses...';
            button.disabled = true;
            
            // Revert button state after 5 seconds in case of error
            setTimeout(() => {
                if (button.disabled) {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }, 5000);
        });

        // Function to adjust paragraph styling based on word count
        function adjustParagraphStyling() {
            const paragraphs = document.querySelectorAll('.collapsible-content p');
            paragraphs.forEach(paragraph => {
                const wordCount = paragraph.textContent.trim().split(/\s+/).length;

                // Remove existing dynamic classes
                paragraph.classList.remove('long-content', 'very-long-content');

                // Apply styling based on word count
                if (wordCount > 100) {
                    paragraph.classList.add('very-long-content');
                } else if (wordCount > 50) {
                    paragraph.classList.add('long-content');
                }
            });
        }

        // Initialize paragraph styling on page load
        document.addEventListener('DOMContentLoaded', function() {
            adjustParagraphStyling();
        });

        // Accessibility enhancements
        document.addEventListener('keydown', function(e) {
            // Close all collapsibles with Escape key
            if (e.key === 'Escape') {
                const openCollapsibles = document.querySelectorAll('.collapsible-content.show');
                openCollapsibles.forEach(collapsible => {
                    const sectionId = collapsible.id.replace('-content', '');
                    const icon = document.getElementById(sectionId + '-icon');
                    collapsible.style.maxHeight = '0px';
                    setTimeout(() => {
                        collapsible.classList.remove('show');
                    }, 300);
                    if (icon) {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            }
        });

        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.getElementById('navLinks');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');

            if (!navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navLinks.classList.remove('show');
            }
        });
    </script>
</body>

</html>