<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

$nama_lengkap = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '-';
$role = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'Masyarakat';
$created_at = isset($_SESSION['created_at']) ? date('d F Y', strtotime($_SESSION['created_at'])) : '-';
$profile_photo = isset($_SESSION['profile_photo']) ? $_SESSION['profile_photo'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil Pengguna - PPID Mandailing Natal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #f59e0b;
            --accent-color: #fbbf24;
            --text-color: #1f2937;
            --muted-color: #6b7280;
            --light-bg: #f8f9fa;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .profile-wrapper {
            padding: 40px 0;
            flex: 1;
        }

        .footer {
            background: #1f2937;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
            font-size: 15px;
        }

        /* Profile Info Card */
        .profile-info-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .profile-info-header {
            display: flex;
            align-items: center;
            gap: 25px;
            padding-bottom: 25px;
            border-bottom: 2px solid var(--light-bg);
            margin-bottom: 25px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid var(--primary-color);
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar i {
            font-size: 48px;
            color: white;
        }

        .profile-info-details h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .profile-badge {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .profile-meta {
            display: flex;
            gap: 25px;
            color: var(--muted-color);
            font-size: 14px;
            flex-wrap: wrap;
        }

        .profile-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-meta-item i {
            color: var(--primary-color);
            width: 18px;
        }

        /* Alert Messages */
        .alert-custom {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid;
        }

        .alert-custom.alert-success {
            background: #f0fdf4;
            border-color: #22c55e;
            color: #166534;
        }

        .alert-custom.alert-error {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .alert-custom i {
            font-size: 20px;
        }

        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            opacity: 0.6;
            font-size: 18px;
            padding: 4px 8px;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Form Cards */
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .form-card-header {
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-bg);
            margin-bottom: 25px;
        }

        .form-card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-color);
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-card-title i {
            color: var(--primary-color);
            font-size: 22px;
        }

        .form-card-subtitle {
            color: var(--muted-color);
            font-size: 14px;
            margin: 0;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label i {
            color: var(--primary-color);
            width: 18px;
            margin-right: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-hint {
            font-size: 13px;
            color: var(--muted-color);
            margin-top: 6px;
            display: block;
        }

        /* Upload Area */
        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--light-bg);
            margin-bottom: 20px;
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: white;
        }

        .upload-area i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
        }

        .upload-area h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .upload-area p {
            font-size: 14px;
            color: var(--muted-color);
            margin: 0 0 15px 0;
        }

        .btn-browse {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-browse:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary-custom:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-primary-custom i {
            font-size: 16px;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 4px solid var(--primary-color);
            padding: 18px 20px;
            border-radius: 8px;
            margin-top: 25px;
        }

        .info-box-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box ul {
            margin: 0;
            padding-left: 20px;
            list-style: none;
        }

        .info-box li {
            font-size: 14px;
            color: var(--text-color);
            margin-bottom: 6px;
            position: relative;
            padding-left: 20px;
        }

        .info-box li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-wrapper {
                padding: 20px 0;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .profile-info-card,
            .form-card {
                padding: 20px;
            }

            .profile-info-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
            }

            .profile-meta {
                flex-direction: column;
                gap: 10px;
            }

            .upload-area {
                padding: 30px 15px;
            }

            .upload-area i {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <?php include 'views/layout/navbar_masyarakat.php'; ?>

    <div class="profile-wrapper">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>
                    <i class="fas fa-user-circle"></i>
                    Profil Pengguna
                </h1>
                <p>Kelola informasi profil dan keamanan akun Anda</p>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-custom alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-custom alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Profile Information Card -->
            <div class="profile-info-card">
                <div class="profile-info-header">
                    <div class="profile-avatar">
                        <?php if (!empty($profile_photo) && file_exists($profile_photo)): ?>
                            <img src="<?php echo htmlspecialchars($profile_photo); ?>" alt="Foto Profil">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info-details">
                        <h2><?php echo htmlspecialchars($nama_lengkap); ?></h2>
                        <div class="profile-badge">
                            <i class="fas fa-shield-alt"></i> <?php echo htmlspecialchars($role); ?>
                        </div>
                        <div class="profile-meta">
                            <div class="profile-meta-item">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($email); ?></span>
                            </div>
                            <div class="profile-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Bergabung <?php echo $created_at; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Change Password Form -->
                <div class="col-lg-6">
                    <div class="form-card">
                        <div class="form-card-header">
                            <h3 class="form-card-title">
                                <i class="fas fa-lock"></i>
                                Ubah Password
                            </h3>
                            <p class="form-card-subtitle">
                                Perbarui password Anda secara berkala untuk keamanan akun
                            </p>
                        </div>

                        <form method="POST" action="index.php?controller=user&action=changePassword">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-key"></i>
                                    Password Saat Ini
                                </label>
                                <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Password Baru
                                </label>
                                <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru" required>
                                <small class="form-hint">Minimal 8 karakter dengan kombinasi huruf dan angka</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required>
                            </div>

                            <button type="submit" class="btn-primary-custom">
                                <i class="fas fa-save"></i>
                                Simpan Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Upload Photo Form -->
                <div class="col-lg-6">
                    <div class="form-card">
                        <div class="form-card-header">
                            <h3 class="form-card-title">
                                <i class="fas fa-camera"></i>
                                Foto Profil
                            </h3>
                            <p class="form-card-subtitle">
                                Upload foto profil untuk personalisasi akun Anda
                            </p>
                        </div>

                        <form method="POST" action="index.php?controller=user&action=uploadProfilePhoto" enctype="multipart/form-data">
                            <div class="upload-area" id="uploadArea">
                                <input type="file" name="profile_photo" id="profilePhoto" accept="image/jpeg,image/png,image/jpg" required hidden>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h4>Pilih atau Drop Foto</h4>
                                <p>Format: JPG, PNG (Maksimal 2MB)</p>
                                <button type="button" class="btn-browse" onclick="document.getElementById('profilePhoto').click()">
                                    Pilih File
                                </button>
                            </div>

                            <button type="submit" class="btn-primary-custom">
                                <i class="fas fa-upload"></i>
                                Upload Foto
                            </button>

                            <div class="info-box">
                                <h4 class="info-box-title">
                                    <i class="fas fa-lightbulb"></i>
                                    Tips Foto Profil
                                </h4>
                                <ul>
                                    <li>Gunakan latar belakang yang netral dan profesional</li>
                                    <li>Pastikan wajah terlihat jelas dan terang</li>
                                    <li>Resolusi minimal 300x300 pixel</li>
                                    <li>Hindari foto yang buram atau gelap</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File upload drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        const profilePhoto = document.getElementById('profilePhoto');

        if (uploadArea && profilePhoto) {
            // Click to select file
            uploadArea.addEventListener('click', function(e) {
                if (e.target.tagName !== 'BUTTON') {
                    profilePhoto.click();
                }
            });

            // Drag over
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--primary-color)';
                this.style.background = 'white';
            });

            // Drag leave
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--border-color)';
                this.style.background = 'var(--light-bg)';
            });

            // Drop
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--border-color)';
                this.style.background = 'var(--light-bg)';

                if (e.dataTransfer.files.length) {
                    profilePhoto.files = e.dataTransfer.files;
                    updateFileName(e.dataTransfer.files[0].name);
                }
            });

            // File input change
            profilePhoto.addEventListener('change', function(e) {
                if (e.target.files.length) {
                    updateFileName(e.target.files[0].name);
                }
            });

            function updateFileName(fileName) {
                const h4 = uploadArea.querySelector('h4');
                h4.textContent = fileName;
                h4.style.color = 'var(--primary-color)';
            }
        }
    </script>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 PPID Kemendagri ALL Rights Reserved</p>
        </div>
    </footer>
</body>

</html>
