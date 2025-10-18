<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<?php include('template/header.php'); ?>

<style>
    :root {
        --primary-color: #1e3a8a;
        --secondary-color: #f59e0b;
        --accent-color: #fbbf24;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --light-bg: #f8f9fa;
        --text-color: #1f2937;
        --muted-color: #6b7280;
        --border-color: #e2e8f0;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--light-bg);
    }

    .profile-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .profile-header-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.15);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .profile-header-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .profile-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar-wrapper {
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        background-color: rgba(255, 255, 255, 0.1);
    }

    .profile-header-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        color: white;
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .profile-content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .card-header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color), var(--info-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .card-header-text h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-color);
        margin: 0 0 0.25rem 0;
    }

    .card-header-text p {
        font-size: 0.875rem;
        color: var(--muted-color);
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--text-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
        background-color: var(--light-bg);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
    }

    .form-control:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #1e40af);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1e40af, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(30, 58, 138, 0.3);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9375rem;
        font-weight: 500;
        border: 1px solid;
        animation: slideInDown 0.3s ease;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert i {
        font-size: 1.25rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .password-requirements {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .password-requirements h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--info-color);
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.5rem;
        font-size: 0.8125rem;
        color: #0c4a6e;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-wrapper {
            padding: 1rem 0.75rem;
        }

        .profile-header-section {
            padding: 1.5rem;
        }

        .profile-header-content {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .profile-header-info h1 {
            font-size: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }

        .profile-card {
            padding: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .card-header {
            flex-direction: row;
            text-align: left;
        }

        .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .profile-header-section::before {
            width: 200px;
            height: 200px;
        }

        .card-header-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .card-header-text h2 {
            font-size: 1.125rem;
        }
    }
</style>

<body class="with-welcome-text">
    <div class="container-scroller">
        <?php include 'template/navbar.php'; ?>
        <div class="container-fluid page-body-wrapper">
            <?php include 'template/setting_panel.php'; ?>
            <?php include 'template/sidebar.php'; ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="profile-wrapper">
                        <!-- Profile Header -->
                        <div class="profile-header-section">
                            <div class="profile-header-content">
                                <div class="profile-avatar-wrapper">
                                    <img src="ppid_assets/user.png"
                                         alt="Profile Photo"
                                         class="profile-avatar">
                                </div>
                                <div class="profile-header-info">
                                    <h1><?php echo htmlspecialchars($username); ?></h1>
                                    <div class="profile-role-badge">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Administrator</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alert Messages -->
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <span><?php
                                    echo htmlspecialchars($_SESSION['success_message']);
                                    unset($_SESSION['success_message']);
                                ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span><?php
                                    echo htmlspecialchars($_SESSION['error_message']);
                                    unset($_SESSION['error_message']);
                                ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Content -->
                        <div class="profile-content-grid">
                            <!-- Profile Information Card -->
                            <div class="profile-card">
                                <div class="card-header">
                                    <div class="card-header-icon">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <div class="card-header-text">
                                        <h2>Informasi Profil</h2>
                                        <p>Perbarui informasi akun Anda</p>
                                    </div>
                                </div>
                                <form method="POST" action="index.php?controller=profileadmin&action=updateProfile">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-envelope"></i>
                                                Email
                                            </label>
                                            <input type="email"
                                                   name="email"
                                                   class="form-control"
                                                   value="<?php echo htmlspecialchars($email); ?>"
                                                   placeholder="Masukkan email"
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i>
                                                Username
                                            </label>
                                            <input type="text"
                                                   name="username"
                                                   class="form-control"
                                                   value="<?php echo htmlspecialchars($username); ?>"
                                                   placeholder="Masukkan username"
                                                   required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i>
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>

                            <!-- Change Password Card -->
                            <div class="profile-card">
                                <div class="card-header">
                                    <div class="card-header-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="card-header-text">
                                        <h2>Keamanan Akun</h2>
                                        <p>Ubah password untuk keamanan akun Anda</p>
                                    </div>
                                </div>
                                <form method="POST" action="index.php?controller=profileadmin&action=updatePassword">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-lock"></i>
                                            Password Saat Ini
                                        </label>
                                        <input type="password"
                                               name="current_password"
                                               class="form-control"
                                               placeholder="Masukkan password saat ini"
                                               required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i>
                                                Password Baru
                                            </label>
                                            <input type="password"
                                                   name="new_password"
                                                   class="form-control"
                                                   placeholder="Masukkan password baru"
                                                   required
                                                   minlength="8">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i>
                                                Konfirmasi Password
                                            </label>
                                            <input type="password"
                                                   name="confirm_password"
                                                   class="form-control"
                                                   placeholder="Konfirmasi password baru"
                                                   required
                                                   minlength="8">
                                        </div>
                                    </div>

                                    <div class="password-requirements">
                                        <h4>
                                            <i class="fas fa-info-circle"></i>
                                            Persyaratan Password
                                        </h4>
                                        <ul>
                                            <li>Minimal 8 karakter</li>
                                            <li>Gunakan kombinasi huruf, angka, dan simbol</li>
                                            <li>Hindari menggunakan informasi pribadi</li>
                                        </ul>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shield-alt"></i>
                                        Ubah Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'template/script.php'; ?>
</body>

</html>
