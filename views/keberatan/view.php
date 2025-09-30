<?php
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

$title = 'Detail Keberatan - PPID Mandailing';
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
      background: linear-gradient(135deg, var(--warning-color), #d97706);
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

    .keberatan-number {
      font-size: clamp(1.5rem, 3vw, 2rem);
      font-weight: 700;
      margin: 0;
      line-height: 1.2;
    }

    .keberatan-date {
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
      color: var(--warning-color);
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

    .content-section {
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
      color: var(--warning-color);
    }

    .content-card {
      background: linear-gradient(135deg, #fef9c3, #fef3c7);
      border: 2px solid #fbbf24;
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-lg);
      margin-bottom: var(--spacing-lg);
    }

    .content-meta {
      font-weight: 600;
      color: var(--warning-color);
      margin-bottom: var(--spacing-sm);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .content-text {
      color: var(--text-primary);
      line-height: 1.6;
      font-size: 1rem;
      white-space: pre-wrap;
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

    .btn-warning {
      background: var(--warning-color);
      color: var(--white);
    }

    .btn-warning:hover {
      background: #d97706;
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

    .btn-primary {
      background: var(--primary-color);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
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
          <li class="breadcrumb-item"><a href="index.php?controller=keberatan&action=index">Keberatan Saya</a></li>
          <li class="breadcrumb-item active" aria-current="page">Detail Keberatan</li>
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
              <h1 class="keberatan-number">Keberatan #<?php echo $keberatan['id_keberatan']; ?></h1>
              <p class="keberatan-date">Diajukan: <?php echo date('d F Y, H:i', strtotime($keberatan['created_at'] ?? 'now')); ?> WIB</p>
            </div>
            <span class="status-badge status-<?php echo $keberatan['status'] ?? 'pending'; ?>">
              <?php
              $statusText = $keberatan['status'] ?? 'pending';
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
                <i class="fas fa-file-alt"></i>
                Informasi Permohonan
              </h3>

              <div class="info-item">
                <div class="info-label">Nomor Permohonan</div>
                <div class="info-value"><?php echo htmlspecialchars($keberatan['no_permohonan']); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Judul Dokumen</div>
                <div class="info-value"><?php echo htmlspecialchars($keberatan['judul_dokumen']); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Unit Tujuan</div>
                <div class="info-value"><?php echo htmlspecialchars($keberatan['komponen_tujuan']); ?></div>
              </div>

              <div class="info-item">
                <div class="info-label">Status Permohonan</div>
                <div class="info-value">
                  <span class="status-badge status-<?php echo $keberatan['status_permohonan'] ?? 'pending'; ?>">
                    <?php
                    $statusPermohonan = $keberatan['status_permohonan'] ?? 'pending';
                    switch($statusPermohonan) {
                      case 'pending': echo '⏳ Menunggu'; break;
                      case 'process': echo '🔄 Diproses'; break;
                      case 'approved': echo '✅ Disetujui'; break;
                      case 'rejected': echo '❌ Ditolak'; break;
                      default: echo '⏳ Menunggu';
                    }
                    ?>
                  </span>
                </div>
              </div>
            </div>

            <!-- Informasi Keberatan -->
            <div class="info-section">
              <h3 class="info-title">
                <i class="fas fa-gavel"></i>
                Informasi Keberatan
              </h3>

              <div class="info-item">
                <div class="info-label">Nama Pengaju</div>
                <div class="info-value"><?php echo htmlspecialchars($keberatan['nama_lengkap'] ?? $keberatan['username']); ?></div>
              </div>

              <?php if (!empty($keberatan['nik'])): ?>
              <div class="info-item">
                <div class="info-label">NIK</div>
                <div class="info-value"><?php echo htmlspecialchars($keberatan['nik']); ?></div>
              </div>
              <?php endif; ?>

              <div class="info-item">
                <div class="info-label">Status Keberatan</div>
                <div class="info-value">
                  <span class="status-badge status-<?php echo $keberatan['status'] ?? 'pending'; ?>">
                    <?php
                    $statusText = $keberatan['status'] ?? 'pending';
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
                <div class="info-label">Tanggal Keberatan</div>
                <div class="info-value"><?php echo date('d F Y', strtotime($keberatan['created_at'] ?? 'now')); ?></div>
              </div>
            </div>
          </div>

          <!-- Content Section -->
          <div class="content-section">
            <h3 class="section-title">
              <i class="fas fa-comment-alt"></i>
              Alasan Keberatan
            </h3>

            <div class="content-card">
              <div class="content-meta">Alasan Keberatan</div>
              <div class="content-text"><?php echo nl2br(htmlspecialchars($keberatan['alasan_keberatan'])); ?></div>
            </div>

            <?php if (!empty($keberatan['keterangan'])): ?>
            <div class="content-card">
              <div class="content-meta">Keterangan Tambahan</div>
              <div class="content-text"><?php echo nl2br(htmlspecialchars($keberatan['keterangan'])); ?></div>
            </div>
            <?php endif; ?>
          </div>

          <!-- Actions -->
          <div class="actions-section">
            <a href="index.php?controller=keberatan&action=index" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i>
              Kembali ke Daftar
            </a>

            <a href="index.php?controller=permohonan&action=view&id=<?php echo $keberatan['id_permohonan']; ?>" class="btn btn-primary">
              <i class="fas fa-file-alt"></i>
              Lihat Permohonan Asli
            </a>
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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