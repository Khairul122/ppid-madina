<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Member - PPID Mandailing Natal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .nav-text {
            display: flex;
            flex-direction: column;
        }

        .nav-title {
            font-size: 12px;
            font-weight: 500;
            line-height: 1.2;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .nav-subtitle {
            font-size: 16px;
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
            padding: 60px 0;
            min-height: calc(100vh - 200px);
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
        }

        .login-left {
            padding: 60px 50px;
            background: white;
        }

        .login-right {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: #6b7280;
            margin-bottom: 40px;
            font-size: 16px;
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
        }

        .form-control:focus {
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

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .forgot-link {
            color: #f59e0b;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #d97706;
        }

        .btn-login {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #d97706;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .register-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            background: white;
            color: #1e3a8a;
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
        }

        .row.g-0 {
            display: flex;
        }

        .login-left,
        .login-right {
            display: flex;
            flex-direction: column;
        }

        .login-right {
            background-color: #0d6efd;
            color: #fff;
            padding: 40px;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            text-align: center;
            min-height: 100%;
        }

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
            .login-container {
                margin: 20px;
            }

            .login-left,
            .login-right {
                padding: 40px 30px;
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
                font-size: 10px;
            }

            .navbar-nav.show {
                display: flex;
            }

            .navbar-nav a {
                padding: 12px 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 11px;
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
                padding: 30px 0;
            }

            .login-left,
            .login-right {
                padding: 30px 20px;
            }

            .form-title {
                font-size: 24px;
            }

            .welcome-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Login Member</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="login-container">
                <div class="row g-0">
                    <!-- Left Side - Login Form -->
                    <div class="col-md-6">
                        <div class="login-left">
                            <h2 class="form-title">Sudah Punya Akun?</h2>
                            <p class="form-subtitle">Silahkan masukan email dan password Anda</p>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value=""
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-field">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            value=""
                                            required>
                                        <button type="button" class="password-toggle" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="text-end mt-2">
                                        <a href="#" class="forgot-link">Lupa password?</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Ingat Saya
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn-login">
                                    MASUK
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Side - Welcome Message -->
                    <div class="col-md-6">
                        <div class="login-right">
                            <h2 class="welcome-title" style="font-size: 25px; text-align: center;">Sistem Informasi Publik Pejabat Pengelola Informasi dan Dokumentasi <br>(SIP PPID)</h2>
                            <p class="welcome-text" style="font-size: 15px; text-align: justify;">
                                Jika anda belum memiliki akun silahkan daftar terlebih dahulu agar anda dapat
                                menggunakan semua fitur-fitur yang ada disini dengan bijak. Akun ini dapat
                                digunakan untuk permohonan informasi dan keberatan informasi.
                            </p>
                            <a href="index.php?controller=auth&action=register" class="register-btn">Daftar Akun</a>
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
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

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

        // Form submission handling
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Harap isi semua field yang diperlukan!');
                return;
            }

            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            button.disabled = true;
        });
    </script>

    <script>
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
</script>
</body>

</html>