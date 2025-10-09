<?php
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

$title = 'Detail Permohonan - PPID Mandailing';
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
    :root {
      --primary-color: #3b82f6;
      --primary-dark: #1e3a8a;
      --secondary-color: #6b7280;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #dc2626;
      --light-bg: #f8f9fa;
      --white: #ffffff;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --shadow-light: rgba(0, 0, 0, 0.1);
      --shadow-medium: rgba(0, 0, 0, 0.15);
      --border-radius: 8px;
      --border-radius-lg: 15px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --spacing-xs: 4px;
      --spacing-sm: 8px;
      --spacing-md: 16px;
      --spacing-lg: 24px;
      --spacing-xl: 32px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
      color: var(--text-primary);
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
      padding: var(--spacing-md) 0;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      color: var(--white) !important;
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }

    .navbar-brand:hover {
      color: #e5e7eb !important;
      transform: translateY(-2px);
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

    .detail-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: 0 10px 40px var(--shadow-light);
      margin-bottom: var(--spacing-xl);
      overflow: hidden;
      animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .detail-header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      padding: var(--spacing-xl);
      color: var(--white);
      position: relative;
      overflow: hidden;
    }

    .detail-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: -50%;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transform: skewX(-20deg);
      animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
      0%, 100% { right: -50%; }
      50% { right: 150%; }
    }

    .detail-title {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: var(--spacing-md);
      position: relative;
      z-index: 2;
    }

    .permohonan-number {
      font-size: clamp(1.5rem, 3vw, 2rem);
      font-weight: 700;
      margin: 0;
      line-height: 1.2;
    }

    .permohonan-date {
      font-size: 0.9rem;
      opacity: 0.9;
      margin: var(--spacing-xs) 0 0 0;
    }

    .status-badge {
      padding: var(--spacing-sm) var(--spacing-lg);
      border-radius: 25px;
      font-size: 0.875rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-flex;
      align-items: center;
      gap: var(--spacing-sm);
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
    }

    .detail-body {
      padding: var(--spacing-xl);
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: var(--spacing-xl);
      margin-bottom: var(--spacing-xl);
    }

    .info-section {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-lg);
      border: 1px solid var(--border-color);
    }

    .info-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--text-primary);
      margin: 0 0 var(--spacing-md) 0;
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
    }

    .info-title i {
      color: var(--primary-color);
      font-size: 1em;
    }

    .info-item {
      margin-bottom: var(--spacing-md);
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .info-label {
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: var(--spacing-xs);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      color: var(--text-primary);
      font-size: 1rem;
      line-height: 1.5;
      background: var(--white);
      padding: var(--spacing-sm) var(--spacing-md);
      border-radius: var(--border-radius);
      border: 1px solid var(--border-color);
    }

    .files-section {
      margin-top: var(--spacing-xl);
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: var(--spacing-lg);
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
    }

    .section-title i {
      color: var(--primary-color);
    }

    .file-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: var(--spacing-lg);
    }

    .file-card {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-lg);
      text-align: center;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .file-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px var(--shadow-medium);
      border-color: var(--primary-color);
    }

    .file-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
      transition: left 0.6s ease;
    }

    .file-card:hover::before {
      left: 100%;
    }

    .file-icon {
      font-size: 3rem;
      margin-bottom: var(--spacing-md);
      color: var(--primary-color);
      position: relative;
      z-index: 2;
    }

    .file-name {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: var(--spacing-sm);
      word-break: break-all;
      position: relative;
      z-index: 2;
    }

    .file-info {
      color: var(--text-secondary);
      font-size: 0.875rem;
      margin-bottom: var(--spacing-md);
      position: relative;
      z-index: 2;
    }

    .btn {
      padding: 12px 24px;
      border-radius: var(--border-radius);
      font-weight: 600;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: var(--spacing-sm);
      position: relative;
      overflow: hidden;
    }

    .btn::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn:active::after {
      width: 300px;
      height: 300px;
    }

    .btn-primary {
      background: var(--primary-color);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      color: var(--white);
      transform: translateY(-2px);
    }

    .btn-secondary {
      background: var(--secondary-color);
      color: var(--white);
    }

    .btn-secondary:hover {
      background: #4b5563;
      color: var(--white);
    }

    .btn-danger {
      background: var(--danger-color);
      color: var(--white);
    }

    .btn-danger:hover {
      background: #b91c1c;
      color: var(--white);
    }

    .actions-section {
      margin-top: var(--spacing-xl);
      padding-top: var(--spacing-xl);
      border-top: 2px solid var(--border-color);
      display: flex;
      gap: var(--spacing-md);
      flex-wrap: wrap;
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

    /* Status colors */
    .status-pending { color: #fbbf24 !important; }
    .status-process { color: #60a5fa !important; }
    .status-approved { color: #34d399 !important; }
    .status-rejected { color: #f87171 !important; }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-content {
        padding: var(--spacing-lg) 0;
      }

      .detail-header {
        padding: var(--spacing-lg);
      }

      .detail-body {
        padding: var(--spacing-lg);
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
      }

      .file-grid {
        grid-template-columns: 1fr;
      }

      .detail-title {
        flex-direction: column;
        gap: var(--spacing-md);
        align-items: flex-start;
      }

      .actions-section {
        flex-direction: column;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .top-info-bar {
        display: none;
      }

      .navbar-nav {
        display: none;
      }
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

    .nav-dropdown .dropdown-toggle.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
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

    .dropdown-item.active {
      background-color: #3b82f6;
      color: white;
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
          <li class="breadcrumb-item"><a href="index.php?controller=permohonan&action=index">Permohonan Saya</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail Permohonan</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <!-- Detail Card -->
      <div class="detail-card">
        <div class="detail-header">
          <div class="detail-title">
            <div>
              <h1 class="permohonan-number"><?php echo htmlspecialchars($permohonan['no_permohonan']); ?></h1>
              <p class="permohonan-date">Diajukan: <?php echo date('d F Y, H:i', strtotime($permohonan['created_at'] ?? 'now')); ?> WIB</p>
            </div>
            <span class="status-badge status-<?php echo $permohonan['status'] ?? 'pending'; ?>">
              <?php
              $statusText = $permohonan['status'] ?? 'pending';
              switch($statusText) {
                case 'pending': echo '⏳ Menunggu Proses'; break;
                case 'process': echo '🔄 Sedang Diproses'; break;
                case 'approved': echo '✅ Disetujui'; break;
                case 'rejected': echo '❌ Ditolak'; break;
                default: echo '⏳ Menunggu Proses';
              }
              ?>
            </span>
          </div>
        </div>

        <div class="detail-body">
          <!-- Information Grid -->
          <div class="info-grid">
            <!-- Informasi Permohonan -->
            <div class="info-section">
              <h3 class="info-title">
                <i class="fas fa-info-circle"></i>
                Informasi Permohonan
              </h3>

              <div class="info-item">
                <div class="info-label">Tujuan Permohonan</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['tujuan_permohonan'])); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Unit/Komponen Tujuan</div>
                <div class="info-value"><?php echo htmlspecialchars($permohonan['komponen_tujuan']); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Judul/Rincian Informasi</div>
                <div class="info-value"><?php echo htmlspecialchars($permohonan['judul_dokumen']); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Tujuan Penggunaan Informasi</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['tujuan_penggunaan_informasi'])); ?></div>
              </div>
            </div>

            <!-- Informasi Pemohon -->
            <div class="info-section">
              <h3 class="info-title">
                <i class="fas fa-user"></i>
                Informasi Pemohon
              </h3>

              <div class="info-item">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username']); ?></div>
              </div>

              <?php if (!empty($permohonan['nik'])): ?>
              <div class="info-item">
                <div class="info-label">NIK</div>
                <div class="info-value"><?php echo htmlspecialchars($permohonan['nik']); ?></div>
              </div>
              <?php endif; ?>

              <div class="info-item">
                <div class="info-label">Status Permohonan</div>
                <div class="info-value">
                  <span class="status-badge status-<?php echo $permohonan['status'] ?? 'pending'; ?>">
                    <?php
                    $statusText = $permohonan['status'] ?? 'pending';
                    switch($statusText) {
                      case 'pending': echo '⏳ Menunggu Proses'; break;
                      case 'process': echo '🔄 Sedang Diproses'; break;
                      case 'approved': echo '✅ Disetujui'; break;
                      case 'rejected': echo '❌ Ditolak'; break;
                      default: echo '⏳ Menunggu Proses';
                    }
                    ?>
                  </span>
                </div>
              </div>

              <div class="info-item">
                <div class="info-label">Tanggal Permohonan</div>
                <div class="info-value"><?php echo date('d F Y', strtotime($permohonan['created_at'] ?? 'now')); ?></div>
              </div>
            </div>
          </div>

          <!-- Catatan Petugas Section -->
          <?php if (!empty($permohonan['catatan_petugas'])): ?>
          <div class="info-section" style="margin-bottom: var(--spacing-xl);">
            <h3 class="info-title">
              <i class="fas fa-clipboard-list"></i>
              Catatan Petugas
            </h3>
            <div class="info-item">
              <div class="info-value" style="background: #fef3c7; border-color: #fbbf24;">
                <i class="fas fa-info-circle" style="color: #f59e0b; margin-right: 8px;"></i>
                <?php echo nl2br(htmlspecialchars($permohonan['catatan_petugas'])); ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Files Section -->
          <div class="files-section">
            <h3 class="section-title">
              <i class="fas fa-paperclip"></i>
              Dokumen Pendukung
            </h3>

            <div class="file-grid">
              <!-- Foto Identitas -->
              <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
              <div class="file-card">
                <div class="file-icon">
                  <i class="fas fa-id-card"></i>
                </div>
                <div class="file-name">Foto Identitas (KTP)</div>
                <div class="file-info">
                  <?php
                  $filename = basename($permohonan['upload_foto_identitas']);
                  $filesize = file_exists($permohonan['upload_foto_identitas']) ? filesize($permohonan['upload_foto_identitas']) : 0;
                  $filesize_mb = round($filesize / 1024 / 1024, 2);
                  echo "File: " . $filename . "<br>";
                  echo "Ukuran: " . $filesize_mb . " MB";
                  ?>
                </div>
                <a href="index.php?controller=permohonan&action=downloadFile&id=<?php echo $permohonan['id_permohonan']; ?>&file=<?php echo urlencode(basename($permohonan['upload_foto_identitas'])); ?>" class="btn btn-primary">
                  <i class="fas fa-download"></i>
                  Download
                </a>
              </div>
              <?php endif; ?>

              <!-- Data Pendukung -->
              <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
              <div class="file-card">
                <div class="file-icon">
                  <i class="fas fa-file-alt"></i>
                </div>
                <div class="file-name">Data Pendukung</div>
                <div class="file-info">
                  <?php
                  $filename = basename($permohonan['upload_data_pedukung']);
                  $filesize = file_exists($permohonan['upload_data_pedukung']) ? filesize($permohonan['upload_data_pedukung']) : 0;
                  $filesize_mb = round($filesize / 1024 / 1024, 2);
                  echo "File: " . $filename . "<br>";
                  echo "Ukuran: " . $filesize_mb . " MB";
                  ?>
                </div>
                <a href="index.php?controller=permohonan&action=downloadFile&id=<?php echo $permohonan['id_permohonan']; ?>&file=<?php echo urlencode(basename($permohonan['upload_data_pedukung'])); ?>" class="btn btn-primary">
                  <i class="fas fa-download"></i>
                  Download
                </a>
              </div>
              <?php endif; ?>

              <?php if (empty($permohonan['upload_foto_identitas']) && empty($permohonan['upload_data_pedukung'])): ?>
              <div class="file-card">
                <div class="file-icon">
                  <i class="fas fa-folder-open"></i>
                </div>
                <div class="file-name">Tidak ada dokumen</div>
                <div class="file-info">Belum ada dokumen yang diunggah</div>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Actions -->
          <div class="actions-section">
            <a href="index.php?controller=permohonan&action=index" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i>
              Kembali ke Daftar
            </a>

            <!-- Bukti Permohonan (Selalu tampil) -->
            <a href="index.php?controller=permohonan&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
               class="btn btn-primary" target="_blank">
              <i class="fas fa-file-pdf"></i>
              Bukti Permohonan
            </a>

            <?php
            $currentStatus = $permohonan['status'] ?? null;

            // Bukti Proses (jika status = Diproses)
            if ($currentStatus === 'Diproses'):
            ?>
            <a href="index.php?controller=permohonan&action=generateBuktiProsesPDF&id=<?php echo $permohonan['id_permohonan']; ?>"
               class="btn btn-primary" target="_blank">
              <i class="fas fa-file-pdf"></i>
              Bukti Proses
            </a>
            <?php endif; ?>

            <?php
            // Bukti Selesai (jika status = Selesai)
            if ($currentStatus === 'Selesai'):
            ?>
            <a href="index.php?controller=permohonan&action=generateBuktiSelesaiPDF&id=<?php echo $permohonan['id_permohonan']; ?>"
               class="btn btn-primary" target="_blank" style="background: var(--success-color);">
              <i class="fas fa-file-pdf"></i>
              Bukti Selesai
            </a>
            <?php endif; ?>

            <?php
            // Bukti Ditolak (jika status = Ditolak)
            if ($currentStatus === 'Ditolak'):
            ?>
            <a href="index.php?controller=permohonan&action=generateBuktiDitolakPDF&id=<?php echo $permohonan['id_permohonan']; ?>"
               class="btn btn-danger" target="_blank">
              <i class="fas fa-file-pdf"></i>
              Bukti Ditolak
            </a>
            <?php endif; ?>

            <?php
            // Tombol ajukan keberatan hanya muncul jika status bukan keberatan, sengketa, atau selesai
            $forbiddenStatuses = ['keberatan', 'sengketa', 'selesai', 'Selesai'];
            $currentStatusLower = $currentStatus ? strtolower($currentStatus) : '';
            if (!in_array($currentStatusLower, $forbiddenStatuses) && !in_array($currentStatus, $forbiddenStatuses)):
            ?>
            <button type="button" class="btn btn-warning" 
                    onclick="openKeberatanModal(<?php echo $permohonan['id_permohonan']; ?>, 
                    <?php echo json_encode(htmlspecialchars($permohonan['no_permohonan'])); ?>, 
                    <?php echo json_encode(htmlspecialchars($permohonan['judul_dokumen'])); ?>)">
              <i class="fas fa-gavel"></i>
              Ajukan Keberatan
            </button>
            <?php endif; ?>

            <?php
            if ($currentStatus === 'pending' || $currentStatus === '' || $currentStatus === null):
            ?>
            <a href="index.php?controller=permohonan&action=delete&id=<?php echo $permohonan['id_permohonan']; ?>"
               class="btn btn-danger"
               onclick="return confirm('Yakin ingin menghapus permohonan ini? Tindakan ini tidak dapat dibatalkan.')">
              <i class="fas fa-trash"></i>
              Hapus Permohonan
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Keberatan Modal -->
  <div class="modal fade" id="keberatanModal" tabindex="-1" aria-labelledby="keberatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="keberatanForm" method="POST" action="index.php?controller=keberatan&action=create">
          <div class="modal-header">
            <h5 class="modal-title" id="keberatanModalLabel">
              <i class="fas fa-gavel"></i>
              Ajukan Keberatan Permohonan
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id_permohonan" value="<?php echo $permohonan['id_permohonan']; ?>">

            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i>
              <strong>Informasi:</strong> Anda akan mengajukan keberatan untuk permohonan berikut:
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="display_no_permohonan" class="form-label">
                    <strong>Nomor Permohonan</strong>
                  </label>
                  <input type="text" class="form-control" id="display_no_permohonan" readonly
                         value="<?php echo htmlspecialchars($permohonan['no_permohonan']); ?>"
                         style="background-color: #f8f9fa; cursor: not-allowed;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="display_judul_dokumen" class="form-label">
                    <strong>Judul Dokumen</strong>
                  </label>
                  <input type="text" class="form-control" id="display_judul_dokumen" readonly
                         value="<?php echo htmlspecialchars($permohonan['judul_dokumen']); ?>"
                         style="background-color: #f8f9fa; cursor: not-allowed;">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="alasan_keberatan" class="form-label">
                    <strong>Alasan Keberatan <span class="text-danger">*</span></strong>
                  </label>
                  <textarea class="form-control" id="alasan_keberatan" name="alasan_keberatan"
                            rows="4" required
                            placeholder="Jelaskan alasan keberatan Anda terhadap permohonan ini..."></textarea>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="keterangan" class="form-label">
                    <strong>Keterangan Tambahan</strong>
                  </label>
                  <textarea class="form-control" id="keterangan" name="keterangan"
                            rows="3"
                            placeholder="Tambahkan keterangan atau informasi pendukung lainnya (opsional)..."></textarea>
                </div>
              </div>
            </div>

            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i>
              <strong>Perhatian:</strong> Pastikan alasan keberatan yang Anda berikan jelas dan dapat dipertanggungjawabkan. Keberatan yang sudah diajukan tidak dapat dibatalkan.
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times"></i>
              Batal
            </button>
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-paper-plane"></i>
              Ajukan Keberatan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 PPID Kemendagri ALL Rights Reserved</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add loading state to download buttons
      document.querySelectorAll('a[href*="downloadFile"]').forEach(link => {
        link.addEventListener('click', function() {
          const icon = this.querySelector('i');
          const originalClass = icon.className;

          icon.className = 'fas fa-spinner fa-spin';
          this.style.pointerEvents = 'none';

          setTimeout(() => {
            icon.className = originalClass;
            this.style.pointerEvents = 'auto';
          }, 2000);
        });
      });

      // Keberatan form validation
      const keberatanForm = document.getElementById('keberatanForm');
      if (keberatanForm) {
        keberatanForm.addEventListener('submit', function(e) {
          const alasanKeberatan = document.getElementById('alasan_keberatan');
          const submitBtn = this.querySelector('button[type="submit"]');

          // Validate alasan keberatan
          if (alasanKeberatan.value.trim().length < 20) {
            e.preventDefault();
            alasanKeberatan.classList.add('is-invalid');
            alasanKeberatan.nextElementSibling.textContent = 'Alasan keberatan minimal 20 karakter';
            return;
          }

          // Show loading state
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengajukan...';
          submitBtn.disabled = true;

          // Allow form submission to continue
        });

        // Clear validation on input
        document.getElementById('alasan_keberatan').addEventListener('input', function() {
          this.classList.remove('is-invalid');
        });
      }

      // Add ripple effect to buttons
      document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
          const ripple = document.createElement('span');
          const rect = this.getBoundingClientRect();
          const size = Math.max(rect.width, rect.height);
          const x = e.clientX - rect.left - size / 2;
          const y = e.clientY - rect.top - size / 2;

          ripple.style.width = ripple.style.height = size + 'px';
          ripple.style.left = x + 'px';
          ripple.style.top = y + 'px';
          ripple.classList.add('ripple-effect');

          this.appendChild(ripple);

          setTimeout(() => {
            ripple.remove();
          }, 600);
        });
      });
    });

    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
      .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
      }

      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>

</html>