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
    }

    .profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #f1f5f9;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f1f5f9;
        margin-right: 24px;
    }

    .profile-info h2 {
        font-size: 24px;
        color: var(--primary-color);
        margin: 0 0 8px 0;
    }

    .profile-info p {
        color: #64748b;
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #334155;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #1e40af;
        transform: translateY(-2px);
    }

    .btn-warning {
        background-color: var(--secondary-color);
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .tab-container {
        margin-top: 30px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .tab-nav {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }

    .tab-nav button {
        padding: 12px 24px;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        font-weight: 500;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .tab-nav button.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    .form-row {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
    }

    .form-col {
        flex: 1;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-avatar {
            margin-right: 0;
            margin-bottom: 16px;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
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
                    <div class="profile-container">
                        <div class="profile-card">
                            <div class="profile-header">
                                <img src="<?php echo !empty($foto_profile) ? $foto_profile : 'ppid_assets/images/default-avatar.png'; ?>" 
                                     alt="Profile Photo" 
                                     class="profile-avatar"
                                     id="profilePhotoPreview">
                                <div class="profile-info">
                                    <h2>Profil Administrator</h2>
                                    <p>Atur informasi akun Anda</p>
                                </div>
                            </div>

                            <!-- Alert Messages -->
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success">
                                    <?php 
                                    echo htmlspecialchars($_SESSION['success_message']); 
                                    unset($_SESSION['success_message']);
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger">
                                    <?php 
                                    echo htmlspecialchars($_SESSION['error_message']); 
                                    unset($_SESSION['error_message']);
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Profile Tabs -->
                            <div class="tab-container">
                                <div class="tab-nav">
                                    <button class="tab-nav-btn active" onclick="showTab('profile')">Profil</button>
                                    <button class="tab-nav-btn" onclick="showTab('password')">Password</button>
                                    <button class="tab-nav-btn" onclick="showTab('photo')">Foto Profil</button>
                                </div>

                                <!-- Profile Tab -->
                                <div id="profile" class="tab-content active">
                                    <form method="POST" action="index.php?controller=profileadmin&action=updateProfile">
                                        <div class="form-row">
                                            <div class="form-col">
                                                <div class="form-group">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" 
                                                           name="email" 
                                                           class="form-control" 
                                                           value="<?php echo htmlspecialchars($email); ?>"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="form-col">
                                                <div class="form-group">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" 
                                                           name="username" 
                                                           class="form-control" 
                                                           value="<?php echo htmlspecialchars($username); ?>"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                                    </form>
                                </div>

                                <!-- Password Tab -->
                                <div id="password" class="tab-content">
                                    <form method="POST" action="index.php?controller=profileadmin&action=updatePassword">
                                        <div class="form-group">
                                            <label class="form-label">Password Saat Ini</label>
                                            <input type="password" 
                                                   name="current_password" 
                                                   class="form-control" 
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" 
                                                   name="new_password" 
                                                   class="form-control" 
                                                   required
                                                   minlength="8">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" 
                                                   name="confirm_password" 
                                                   class="form-control" 
                                                   required
                                                   minlength="8">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ganti Password</button>
                                    </form>
                                </div>

                                <!-- Photo Tab -->
                                <div id="photo" class="tab-content">
                                    <form method="POST" action="index.php?controller=profileadmin&action=updatePhoto" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="form-label">Upload Foto Profil Baru</label>
                                            <input type="file" 
                                                   name="foto_profile" 
                                                   class="form-control" 
                                                   accept="image/*"
                                                   onchange="previewPhoto(this)">
                                        </div>
                                        <p class="text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal ukuran: 5MB</p>
                                        <button type="submit" class="btn btn-primary">Upload Foto</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'template/script.php'; ?>
    
    <script>
        function showTab(tabId) {
            // Hide all tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all nav buttons
            const navButtons = document.querySelectorAll('.tab-nav-btn');
            navButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabId).classList.add('active');

            // Add active class to clicked nav button
            event.target.classList.add('active');
        }

        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('profilePhotoPreview').src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>