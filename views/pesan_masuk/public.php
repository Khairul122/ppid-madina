<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'Hubungi Kami - PPID Mandailing Natal' ?></title>
    <meta name="description" content="<?= $pageInfo['description'] ?? 'Kirim pesan, pertanyaan, atau pengaduan kepada PPID Kabupaten Mandailing Natal' ?>">

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

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        /* Breadcrumb */
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
            color: var(--muted-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            padding: 100px 0;
            min-height: calc(100vh - 200px);
            background: white;
        }

        /* Contact Header */
        .contact-header {
            text-align: center;
            margin-bottom: 80px;
            padding: 0 20px;
            color: white;
        }

        .contact-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .contact-subtitle {
            font-size: 1.25rem;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Contact Container - Dinamis dan Responsif */
        .contact-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Contact Card - Diperbesar */
        .contact-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transform: translateY(0);
            transition: all 0.4s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        }

        /* Info Section - Diperbesar */
        .contact-info {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 80px 60px;
            min-height: 100%;
        }

        .contact-info h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 50px;
            border-bottom: 3px solid rgba(255,255,255,0.3);
            padding-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: start;
            margin-bottom: 40px;
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(5px);
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 25px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .info-item:hover .info-icon {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .info-icon i {
            font-size: 28px;
        }

        .info-content h5 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-content p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.8;
            opacity: 0.95;
        }

        /* Form Section - Diperbesar */
        .contact-form {
            padding: 80px 60px;
        }

        .contact-form h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--text-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 180px;
        }

        .required {
            color: #ef4444;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }

        /* Submit Button - Diperbesar */
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%,rgb(21, 0, 255) 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Alert Styling */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 18px 24px;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Responsive Design - Dinamis untuk Semua Device */

        /* Extra Large Screens */
        @media (min-width: 1400px) {
            .contact-container {
                max-width: 1600px;
            }

            .contact-info {
                padding: 100px 80px;
            }

            .contact-form {
                padding: 100px 80px;
            }
        }

        /* Large Screens */
        @media (max-width: 1199px) {
            .contact-container {
                max-width: 1140px;
            }

            .contact-title {
                font-size: 3rem;
            }

            .contact-info {
                padding: 60px 50px;
            }

            .contact-form {
                padding: 60px 50px;
            }
        }

        /* Medium Screens - Tablets */
        @media (max-width: 991px) {
            .contact-container {
                max-width: 960px;
            }

            .main-content {
                padding: 80px 0;
            }

            .contact-header {
                margin-bottom: 60px;
            }

            .contact-title {
                font-size: 2.5rem;
            }

            .contact-info,
            .contact-form {
                padding: 50px 40px;
            }

            .info-icon {
                width: 60px;
                height: 60px;
            }

            .info-icon i {
                font-size: 24px;
            }
        }

        /* Small Screens - Tablets Portrait */
        @media (max-width: 767px) {
            .contact-container {
                max-width: 720px;
                padding: 0 15px;
            }

            .main-content {
                padding: 60px 0;
            }

            .contact-header {
                margin-bottom: 40px;
                padding: 0 15px;
            }

            .contact-title {
                font-size: 2rem;
            }

            .contact-subtitle {
                font-size: 1rem;
            }

            .contact-card {
                border-radius: 16px;
            }

            .contact-info {
                padding: 40px 30px;
            }

            .contact-form {
                padding: 40px 30px;
            }

            .contact-info h3,
            .contact-form h3 {
                font-size: 1.5rem;
                margin-bottom: 30px;
            }

            .info-item {
                margin-bottom: 30px;
            }

            .info-icon {
                width: 55px;
                height: 55px;
                margin-right: 20px;
            }

            .info-icon i {
                font-size: 22px;
            }

            .info-content h5 {
                font-size: 1rem;
            }

            .info-content p {
                font-size: 0.9rem;
            }
        }

        /* Extra Small Screens - Mobile */
        @media (max-width: 575px) {
            .contact-container {
                max-width: 100%;
                padding: 0 10px;
            }

            .main-content {
                padding: 40px 0;
            }

            .contact-header {
                margin-bottom: 30px;
            }

            .contact-title {
                font-size: 1.75rem;
            }

            .contact-subtitle {
                font-size: 0.95rem;
            }

            .contact-card {
                border-radius: 12px;
            }

            .contact-info {
                padding: 30px 20px;
            }

            .contact-form {
                padding: 30px 20px;
            }

            .contact-info h3,
            .contact-form h3 {
                font-size: 1.3rem;
                margin-bottom: 25px;
            }

            .info-item {
                margin-bottom: 25px;
            }

            .info-icon {
                width: 50px;
                height: 50px;
                margin-right: 15px;
            }

            .info-icon i {
                font-size: 20px;
            }

            .info-content h5 {
                font-size: 0.9rem;
            }

            .info-content p {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 14px 16px;
                font-size: 0.95rem;
            }

            .btn-submit {
                padding: 15px 40px;
                font-size: 1rem;
            }
        }

        /* Very Small Screens */
        @media (max-width: 375px) {
            .contact-info,
            .contact-form {
                padding: 25px 15px;
            }

            .contact-title {
                font-size: 1.5rem;
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
                    <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="contact-header" data-aos="fade-down">
                <h1 class="contact-title" style="color: black;">Hubungi Kami</h1>
              
            </div>

            <!-- Contact Container -->
            <div class="contact-container">
                <div class="contact-card" data-aos="fade-up" data-aos-duration="800">
                    <div class="row g-0">
                        <!-- Contact Info -->
                        <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
                            <div class="contact-info h-100">
                                <h3>Informasi Kontak</h3>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Alamat</h5>
                                        <p>Jl. Merdeka No. 1, Panyabungan<br>Kabupaten Mandailing Natal<br>Sumatera Utara</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Telepon</h5>
                                        <p>(0636) 21xxx<br>0812-xxxx-xxxx</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Email</h5>
                                        <p>ppid@mandalingnatalkab.go.id<br>info@mandalingnatalkab.go.id</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Jam Kerja</h5>
                                        <p>Senin - Jumat: 08:00 - 16:00 WIB<br>Sabtu - Minggu: Libur</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form -->
                        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                            <div class="contact-form">
                                <h3>Kirim Pesan</h3>

                                <!-- Alert Messages -->
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <!-- Form -->
                                <form method="POST" action="index.php?controller=pesan_masuk&action=submit" id="contactForm">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">
                                            Nama Lengkap <span class="required">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nama"
                                            name="nama"
                                            placeholder="Masukkan nama lengkap Anda"
                                            required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">
                                                Email <span class="required">*</span>
                                            </label>
                                            <input
                                                type="email"
                                                class="form-control"
                                                id="email"
                                                name="email"
                                                placeholder="contoh@email.com"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="no_telp" class="form-label">
                                                No. Telepon
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="no_telp"
                                                name="no_telp"
                                                placeholder="08xxxxxxxxxx">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="subjek" class="form-label">
                                            Subjek <span class="required">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="subjek"
                                            name="subjek"
                                            placeholder="Masukkan subjek pesan"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="pesan" class="form-label">
                                            Pesan <span class="required">*</span>
                                        </label>
                                        <textarea
                                            class="form-control"
                                            id="pesan"
                                            name="pesan"
                                            rows="6"
                                            placeholder="Tuliskan pesan, pertanyaan, atau pengaduan Anda..."
                                            required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-submit">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Kirim Pesan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Form validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return false;
            }
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
