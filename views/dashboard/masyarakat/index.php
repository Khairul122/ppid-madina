<?php
// Check session - data already passed from controller
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Set title
$title = 'Dashboard Masyarakat - PPID Mandailing';

// Facilities data for dynamic rendering
$facilities = [
  [
    'title' => 'Dashboard',
    'icon' => 'fas fa-th-large',
    'url' => 'index.php?controller=user&action=index',
    'description' => 'Halaman utama dashboard'
  ],
  [
    'title' => 'Ajukan Permohonan',
    'icon' => 'fas fa-file',
    'url' => 'index.php?controller=AjukanPermohonan&action=index',
    'description' => 'Buat permohonan informasi baru'
  ],
  [
    'title' => 'Permohonan Saya',
    'icon' => 'fas fa-envelope-open-text',
    'url' => 'index.php?controller=permohonan&action=index',
    'description' => 'Lihat status permohonan Anda'
  ],
  [
    'title' => 'Keberatan Saya',
    'icon' => 'fas fa-exclamation-triangle',
    'url' => 'index.php?controller=keberatan&action=index',
    'description' => 'Ajukan keberatan atas permohonan'
  ],
  [
    'title' => 'Dokumen Saya',
    'icon' => 'fas fa-folder-open',
    'url' => 'index.php?controller=dokumen&action=index',
    'description' => 'Kelola dokumen pribadi'
  ]
];
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

    .dropdown {
      position: relative;
    }

    .dropdown-menu {
      background: black;
      border: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      margin-top: 5px;
    }

    .dropdown-item {
      padding: 10px 20px;
      color: #374151;
      font-weight: 500;
    }

    .dropdown-item:hover {
      background-color: #f3f4f6;
      color: #1e3a8a;
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

    .main-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 40px;
      overflow: hidden;
    }

    .card-content {
      display: flex;
      min-height: 400px;
    }

    .user-info-section {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 30px;
      border-right: 1px solid #e5e7eb;
      flex: 1;
      min-width: 300px;
    }

    .user-info-title {
      font-size: 24px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 25px;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #e5e7eb, #d1d5db);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      color: #6b7280;
      border: 3px solid #e5e7eb;
    }

    .profile-info h3 {
      font-size: 20px;
      font-weight: 600;
      color: #1f2937;
      margin: 0 0 5px 0;
    }

    .profile-status {
      display: inline-block;
      background: #3b82f6;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .profile-login-time {
      color: #6b7280;
      font-size: 14px;
      margin: 5px 0;
    }

    .btn-profile {
      background: #f59e0b;
      color: white;
      border: none;
      padding: 8px 20px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      margin-top: 10px;
      transition: all 0.3s ease;
    }

    .btn-profile:hover {
      background: #d97706;
      transform: translateY(-2px);
      color: white;
    }

    .facilities-section {
      padding: 30px;
      flex: 2;
    }

    .facilities-title {
      font-size: 24px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 25px;
      text-align: center;
    }

    .facilities-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 20px;
      max-width: 100%;
      margin: 0 auto;
    }

    .facility-card {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 20px;
      padding: 25px 20px;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      color: inherit;
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }

    .facility-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
      transition: left 0.5s;
    }

    .facility-card:hover::before {
      left: 100%;
    }

    .facility-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15);
      border-color: #3b82f6;
      color: inherit;
      text-decoration: none;
    }

    .facility-card:active {
      transform: translateY(-4px) scale(1.01);
      transition: all 0.1s;
    }

    .facility-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #3b82f6, #1e3a8a);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 28px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .facility-icon::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      transition: all 0.3s ease;
    }

    .facility-card:hover .facility-icon::before {
      width: 100px;
      height: 100px;
    }

    .facility-card:hover .facility-icon {
      transform: rotate(10deg) scale(1.1);
    }

    .facility-card h5 {
      font-size: 16px;
      font-weight: 600;
      color: #1f2937;
      margin: 0;
      line-height: 1.4;
      transition: color 0.3s ease;
    }

    .facility-card:hover h5 {
      color: #3b82f6;
    }

    /* Loading animation */
    .facility-card.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .facility-card.loading .facility-icon {
      animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
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

    /* Large screens - Desktop */
    @media (max-width: 1200px) {
      .facilities-grid {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 18px;
      }
    }

    /* Medium screens - Tablet */
    @media (max-width: 992px) {
      .main-content {
        padding: 30px 0;
      }

      .card-content {
        flex-direction: column;
        min-height: auto;
      }

      .user-info-section {
        border-right: none;
        border-bottom: 2px solid #e5e7eb;
        padding: 25px;
        min-width: auto;
      }

      .facilities-section {
        padding: 25px;
      }

      .facilities-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
      }

      .facility-card {
        padding: 20px 15px;
      }

      .facility-icon {
        width: 60px;
        height: 60px;
        font-size: 24px;
      }
    }

    /* Small screens - Mobile Landscape */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px 0;
      }

      .user-info-section,
      .facilities-section {
        padding: 20px;
      }

      .user-profile {
        flex-direction: column;
        text-align: center;
        gap: 15px;
      }

      .facilities-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
      }

      .facility-card {
        padding: 18px 12px;
      }

      .facility-icon {
        width: 55px;
        height: 55px;
        font-size: 22px;
        margin-bottom: 15px;
      }

      .facility-card h5 {
        font-size: 14px;
      }

      .top-info-bar {
        display: none;
      }

      .navbar-nav {
        display: none;
      }
    }

    /* Extra small screens - Mobile Portrait */
    @media (max-width: 576px) {
      .main-content {
        padding: 15px 0;
      }

      .user-info-section,
      .facilities-section {
        padding: 15px;
      }

      .user-info-title,
      .facilities-title {
        font-size: 18px;
        margin-bottom: 20px;
      }

      .profile-avatar {
        width: 60px;
        height: 60px;
        font-size: 24px;
      }

      .profile-info h3 {
        font-size: 18px;
      }

      .facilities-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
      }

      .facility-card {
        padding: 15px 10px;
        border-radius: 15px;
      }

      .facility-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
        margin-bottom: 12px;
        border-radius: 12px;
      }

      .facility-card h5 {
        font-size: 13px;
        line-height: 1.3;
      }

      .btn-profile {
        padding: 6px 15px;
        font-size: 13px;
      }
    }

    /* Very small screens */
    @media (max-width: 400px) {
      .facilities-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }

      .facility-card {
        padding: 15px;
        max-width: 280px;
        margin: 0 auto;
      }

      .facility-card h5 {
        font-size: 14px;
      }

      .facility-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
      }
    }

    /* Hover effects only on devices that support it */
    @media (hover: hover) {
      .facility-card:hover {
        transform: translateY(-8px) scale(1.02);
      }
    }

    /* Reduce animations on devices that prefer reduced motion */
    @media (prefers-reduced-motion: reduce) {
      .facility-card,
      .facility-icon,
      .facility-card::before {
        transition: none;
      }

      .facility-card:hover {
        transform: none;
      }

      .facility-card.loading .facility-icon {
        animation: none;
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
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dokumen Saya</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <div class="row">
        <!-- Combined Card: User Information & Facilities -->
        <div class="col-12">
          <div class="main-card">
            <div class="card-content">
              <!-- Left Side: User Information -->
              <div class="user-info-section">
                <h2 class="user-info-title">Informasi Pengguna</h2>
                <div class="user-profile">
                  <div class="profile-avatar">
                    <?php if (!empty($user_data['foto_profile']) && file_exists($user_data['foto_profile'])): ?>
                      <img src="<?php echo htmlspecialchars($user_data['foto_profile']); ?>" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                      <i class="fas fa-user"></i>
                    <?php endif; ?>
                  </div>
                  <div class="profile-info">
                    <h3><?php echo htmlspecialchars($nama_lengkap); ?></h3>
                    <span class="profile-status"><?php echo htmlspecialchars($status_pengguna); ?></span>
                    <div class="profile-login-time">
                      <strong>Terakhir Login</strong><br>
                      <?php echo $current_datetime; ?>
                    </div>
                    <button class="btn btn-profile" onclick="window.location.href='index.php?controller=user&action=profile'">
                      <i class="fas fa-edit me-2"></i>Lihat Profil
                    </button>
                  </div>
                </div>
              </div>

              <!-- Right Side: Facilities -->
              <div class="facilities-section">
                <h2 class="facilities-title">Fasilitas Saya</h2>
                <div class="facilities-grid">
                  <?php foreach ($facilities as $facility): ?>
                    <a href="<?php echo htmlspecialchars($facility['url']); ?>" class="facility-card" title="<?php echo htmlspecialchars($facility['description']); ?>">
                      <div class="facility-icon">
                        <i class="<?php echo htmlspecialchars($facility['icon']); ?>"></i>
                      </div>
                      <h5><?php echo str_replace(' ', '<br>', htmlspecialchars($facility['title'])); ?></h5>
                    </a>
                  <?php endforeach; ?>
                </div>
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
    // Initialize dashboard functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Add loading state to facility cards when clicked
      const facilityCards = document.querySelectorAll('.facility-card');

      facilityCards.forEach(card => {
        card.addEventListener('click', function(e) {
          // Add loading state
          this.classList.add('loading');

          // Remove loading state after navigation (fallback)
          setTimeout(() => {
            this.classList.remove('loading');
          }, 2000);
        });

        // Add keyboard accessibility
        card.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
          }
        });

        // Add focus styles
        card.setAttribute('tabindex', '0');
      });

      // Smooth scrolling for internal links
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

      // Update last login time every minute
      updateLoginTime();
      setInterval(updateLoginTime, 60000);

      // Add intersection observer for animations
      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.style.opacity = '1';
              entry.target.style.transform = 'translateY(0)';
            }
          });
        }, { threshold: 0.1 });

        // Observe facility cards
        facilityCards.forEach(card => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
          observer.observe(card);
        });
      }
    });

    // Function to update login time display
    function updateLoginTime() {
      const loginTimeElement = document.querySelector('.profile-login-time');
      if (loginTimeElement) {
        const now = new Date();
        const options = {
          day: 'numeric',
          month: 'long',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        };
        const currentTime = now.toLocaleDateString('id-ID', options);

        // Update the second line of the element
        const lines = loginTimeElement.innerHTML.split('<br>');
        if (lines.length > 1) {
          lines[1] = currentTime;
          loginTimeElement.innerHTML = lines.join('<br>');
        }
      }
    }

    // Add error handling for failed navigation
    window.addEventListener('error', function(e) {
      console.error('Navigation error:', e.error);
      // Remove loading states on error
      document.querySelectorAll('.facility-card.loading').forEach(card => {
        card.classList.remove('loading');
      });
    });

    // Handle offline/online status
    window.addEventListener('online', function() {
      document.body.classList.remove('offline');
      console.log('Connection restored');
    });

    window.addEventListener('offline', function() {
      document.body.classList.add('offline');
      console.log('Connection lost');
    });

    // Performance optimization - lazy load images if any
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.classList.remove('lazy');
              imageObserver.unobserve(img);
            }
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
      });
    }

    // Add touch support for better mobile experience
    let touchStartY = 0;
    let touchEndY = 0;

    document.addEventListener('touchstart', e => {
      touchStartY = e.changedTouches[0].screenY;
    });

    document.addEventListener('touchend', e => {
      touchEndY = e.changedTouches[0].screenY;
      handleGesture();
    });

    function handleGesture() {
      const swipeThreshold = 50;
      const diff = touchStartY - touchEndY;

      if (Math.abs(diff) > swipeThreshold) {
        // Add custom swipe handling if needed
        console.log(diff > 0 ? 'Swiped up' : 'Swiped down');
      }
    }
  </script>
</body>

</html>