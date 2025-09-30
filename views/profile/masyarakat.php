<?php
// Check session - data already passed from controller
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Set title
$title = 'Profil Saya - PPID Mandailing';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $title; ?></title>

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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
    }

    .navbar-nav a:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .navbar-nav a.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
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
      flex: 1;
      padding: 40px 0;
    }

    .profile-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      overflow: hidden;
    }

    .profile-header {
      background: linear-gradient(135deg, #3b82f6, #1e3a8a);
      padding: 30px;
      color: white;
      text-align: center;
    }

    .profile-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      color: white;
      margin: 0 auto 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .profile-name {
      font-size: 24px;
      font-weight: 700;
      margin: 0 0 8px 0;
    }

    .profile-status {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      backdrop-filter: blur(10px);
    }

    .profile-content {
      padding: 30px;
    }

    .section-title {
      font-size: 20px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #3b82f6;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .info-item {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 10px;
      border-left: 4px solid #3b82f6;
    }

    .info-label {
      font-size: 14px;
      font-weight: 600;
      color: #6b7280;
      margin-bottom: 5px;
    }

    .info-value {
      font-size: 16px;
      font-weight: 600;
      color: #1f2937;
    }

    .form-section {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 20px;
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
      outline: none;
    }

    .form-control:disabled {
      background-color: #f3f4f6;
      color: #6b7280;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: #3b82f6;
      color: white;
    }

    .btn-primary:hover {
      background: #2563eb;
      transform: translateY(-2px);
      color: white;
    }

    .btn-secondary {
      background: #6b7280;
      color: white;
    }

    .btn-secondary:hover {
      background: #4b5563;
      color: white;
    }

    .btn-warning {
      background: #f59e0b;
      color: white;
    }

    .btn-warning:hover {
      background: #d97706;
      color: white;
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

    .activity-table {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table {
      margin: 0;
    }

    .table thead th {
      background: #f8f9fa;
      border: none;
      padding: 15px;
      font-weight: 600;
      color: #374151;
    }

    .table tbody td {
      border: none;
      padding: 15px;
      border-bottom: 1px solid #e5e7eb;
    }

    .badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .badge-success {
      background: #10b981;
      color: white;
    }

    .badge-info {
      background: #3b82f6;
      color: white;
    }

    .badge-warning {
      background: #f59e0b;
      color: white;
    }

    .alert {
      border-radius: 8px;
      border: none;
      padding: 15px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .alert-danger {
      background-color: #fee2e2;
      color: #dc2626;
      border-left: 4px solid #dc2626;
    }

    .alert-success {
      background-color: #d1fae5;
      color: #059669;
      border-left: 4px solid #059669;
    }

    .alert i {
      margin-right: 10px;
      font-size: 16px;
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

    /* Photo Upload Styles */
    .photo-preview {
      width: 150px;
      height: 150px;
      border-radius: 15px;
      border: 3px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: #f8f9fa;
      margin: 0 auto;
    }

    .current-photo-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 12px;
    }

    .no-photo {
      text-align: center;
      color: #6b7280;
    }

    .no-photo i {
      font-size: 48px;
      margin-bottom: 10px;
      opacity: 0.5;
    }

    .no-photo p {
      margin: 0;
      font-size: 14px;
      font-weight: 500;
    }

    .text-muted {
      color: #6b7280 !important;
      font-size: 13px;
    }

    /* Navigation Dropdown Styles */
    .nav-dropdown {
      position: relative;
      display: inline-block;
    }

    .nav-dropdown .dropdown-toggle {
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
      display: flex;
      align-items: center;
    }

    .nav-dropdown .dropdown-toggle:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .dropdown-menu {
      background: black;
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 8px 0;
      min-width: 200px;
      margin-top: 8px;
    }

    .dropdown-item {
      padding: 10px 16px;
      color: #374151;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .dropdown-item:hover {
      background-color: #f3f4f6;
      color: #1e3a8a;
    }

    .dropdown-divider {
      margin: 8px 0;
      border-color: #e5e7eb;
    }

    /* User Dropdown Styles */
    .user-dropdown .dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 12px;
    }

    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .user-avatar i {
      color: white;
      font-size: 14px;
    }

    .username {
      font-size: 14px;
      font-weight: 500;
      color: white;
      max-width: 120px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px 0;
      }

      .profile-content {
        padding: 20px;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
      }

      .profile-header {
        padding: 20px;
      }

      .profile-avatar {
        width: 80px;
        height: 80px;
        font-size: 36px;
      }

      .profile-name {
        font-size: 20px;
      }

      .top-info-bar {
        display: none;
      }

      .navbar-nav {
        display: none;
      }
    }

    @media (max-width: 576px) {
      .form-section {
        padding: 20px 15px;
      }

      .section-title {
        font-size: 18px;
      }

      .btn {
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
      }
    }

    /* Animation */
    .profile-card {
      animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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
        <span><i class="fas fa-envelope"></i> ppid@mandailingnatal.go.id</span>
        <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
      </div>
    </div>
  </div>

  <!-- Main Navigation Header -->
  <?php include 'views/layout/navbar_masyarakat.php'; ?>

  <!-- Breadcrumb -->
  <div class="breadcrumb-section">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?controller=user&action=index">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Profil Saya</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- Profile Header -->
          <div class="profile-card">
            <div class="profile-header">
              <div class="profile-avatar">
                <?php if (!empty($user_data['foto_profile']) && file_exists($user_data['foto_profile'])): ?>
                  <img src="<?php echo htmlspecialchars($user_data['foto_profile']); ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                  <i class="fas fa-user"></i>
                <?php endif; ?>
              </div>
              <h2 class="profile-name"><?php echo htmlspecialchars($nama_lengkap); ?></h2>
              <span class="profile-status"><?php echo ucfirst(htmlspecialchars($status_pengguna)); ?></span>
            </div>

            <div class="profile-content">
              <!-- Basic Information -->
              <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                Informasi Dasar
              </h3>

              <div class="info-grid">
                <div class="info-item">
                  <div class="info-label">Nama Lengkap</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['nama_lengkap'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Email</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['email'] ?? $_SESSION['email'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">NIK</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['nik'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">No. Kontak</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['no_kontak'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Jenis Kelamin</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['jenis_kelamin'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Status Pengguna</div>
                  <div class="info-value"><?php echo ucfirst(htmlspecialchars($user_data['status_pengguna'] ?? 'publik')); ?></div>
                </div>
              </div>

              <!-- Address Information -->
              <h3 class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Informasi Alamat
              </h3>

              <div class="info-grid">
                <div class="info-item">
                  <div class="info-label">Alamat</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['alamat'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Provinsi</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['provinsi'] ?? 'Tidak tersedia'); ?></div>
                </div>
                <div class="info-item">
                  <div class="info-label">Kota/Kabupaten</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['city'] ?? 'Tidak tersedia'); ?></div>
                </div>
              </div>

              <?php if ($user_data['status_pengguna'] === 'lembaga'): ?>
              <!-- Institution Information -->
              <h3 class="section-title">
                <i class="fas fa-building"></i>
                Informasi Lembaga
              </h3>

              <div class="info-grid">
                <div class="info-item">
                  <div class="info-label">Nama Lembaga</div>
                  <div class="info-value"><?php echo htmlspecialchars($user_data['nama_lembaga'] ?? 'Tidak tersedia'); ?></div>
                </div>
              </div>
              <?php endif; ?>

              <!-- Profile Photo Section -->
              <h3 class="section-title">
                <i class="fas fa-camera"></i>
                Foto Profil
              </h3>

              <div class="form-section">
                <div class="row">
                  <div class="col-md-4">
                    <div class="current-photo">
                      <div class="photo-preview">
                        <?php if (!empty($user_data['foto_profile']) && file_exists($user_data['foto_profile'])): ?>
                          <img src="<?php echo htmlspecialchars($user_data['foto_profile']); ?>" alt="Foto Profil" class="current-photo-img">
                        <?php else: ?>
                          <div class="no-photo">
                            <i class="fas fa-user"></i>
                            <p>Belum ada foto</p>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <form method="POST" action="index.php?controller=user&action=uploadProfilePhoto" enctype="multipart/form-data">
                      <div class="form-group">
                        <label for="profile_photo" class="form-label">Upload Foto Profil Baru</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/jpeg,image/jpg,image/png" required>
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                      </div>
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i>
                        Upload Foto
                      </button>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Change Password Section -->
              <h3 class="section-title">
                <i class="fas fa-lock"></i>
                Keamanan Akun
              </h3>

              <div class="form-section">
                <?php if (isset($_SESSION['error_message'])): ?>
                  <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php
                      echo htmlspecialchars($_SESSION['error_message']);
                      unset($_SESSION['error_message']);
                    ?>
                  </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success_message'])): ?>
                  <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php
                      echo htmlspecialchars($_SESSION['success_message']);
                      unset($_SESSION['success_message']);
                    ?>
                  </div>
                <?php endif; ?>

                <form id="passwordForm" method="POST" action="index.php?controller=user&action=changePassword">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <div class="password-field">
                          <input type="password" class="form-control" id="current_password" name="current_password" required>
                          <button type="button" class="password-toggle" onclick="togglePassword('current_password', 'current-password-icon')">
                            <i class="fas fa-eye" id="current-password-icon"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <div class="password-field">
                          <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                          <button type="button" class="password-toggle" onclick="togglePassword('new_password', 'new-password-icon')">
                            <i class="fas fa-eye" id="new-password-icon"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-field">
                          <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                          <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'confirm-password-icon')">
                            <i class="fas fa-eye" id="confirm-password-icon"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                      <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary">
                          <i class="fas fa-save"></i>
                          Ubah Password
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Action Buttons -->
              <div class="d-flex gap-3 flex-wrap">
                <a href="index.php?controller=user&action=index" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i>
                  Kembali ke Dashboard
                </a>
                <button type="button" class="btn btn-warning" onclick="editProfile()">
                  <i class="fas fa-edit"></i>
                  Edit Profil
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 PPID Kemendagri ALL Rights Reserved</p>
    </div>
  </footer>

  <!-- Accessibility Button -->
  <button class="accessibility-btn" title="Accessibility">
    <i class="fas fa-universal-access"></i>
  </button>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Toggle password visibility
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

    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function(e) {
      const password = document.getElementById('new_password').value;
      const confirmPassword = this.value;

      if (password !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok');
      } else {
        this.setCustomValidity('');
      }
    });

    // Form submission handling
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
      const password = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok!');
        return;
      }

      // Show loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
      submitBtn.disabled = true;

      // Revert button state after 5 seconds in case of error
      setTimeout(() => {
        if (submitBtn.disabled) {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }
      }, 5000);
    });

    // Edit profile function (placeholder)
    function editProfile() {
      alert('Fitur edit profil akan segera hadir!');
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Add fade-in animation
      document.querySelectorAll('.profile-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });

      // Add accessibility enhancements
      document.querySelectorAll('.btn').forEach(btn => {
        btn.setAttribute('tabindex', '0');
      });
    });
  </script>
</body>

</html>