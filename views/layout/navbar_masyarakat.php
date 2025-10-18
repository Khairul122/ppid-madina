<style>
  :root {
    --primary-color: #1e3a8a;
    --secondary-color: #f59e0b;
    --accent-color: #fbbf24;
    --text-color: #1f2937;
    --muted-color: #6b7280;
    --light-bg: #f8f9fa;
  }

  body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--light-bg);
  }

  /* Top Info Bar Styles */
  .top-info-bar {
    background-color: #e5e7eb;
    padding: 8px 0;
    font-size: 13px;
    color: var(--muted-color);
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
    color: var(--muted-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
  }

  .top-info-links a:hover {
    color: var(--text-color);
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

  .user-info {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .user-info i {
    color: var(--primary-color);
  }

  /* Navbar Styles */
  .navbar-custom {
    background: #000000;
    padding: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .main-navbar {
    padding: 12px 0;
  }

  .navbar-brand {
    display: flex;
    align-items: center;
    color: white !important;
    font-weight: 600;
    text-decoration: none;
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

  .navbar-nav {
    display: flex;
    flex-direction: row;
    gap: 8px;
    align-items: center;
    margin-left: auto;
  }

  .navbar-nav > a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 11px;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 4px;
    white-space: nowrap;
  }

  .navbar-nav > a:hover {
    color: #ddd;
    background-color: rgba(255, 255, 255, 0.1);
  }

  .mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
  }

  /* Dropdown Styles */
  .dropdown-wrapper {
    position: relative;
    display: inline-block;
  }

  .nav-link-main {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 10px 15px;
    font-weight: 500;
    font-size: 11px;
    white-space: nowrap;
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .nav-link-main:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .dropdown-icon {
    font-size: 10px;
    font-weight: 600;
    opacity: 0.9;
    transition: all 0.3s ease;
    margin-left: 6px;
  }

  .dropdown-wrapper:hover .dropdown-icon {
    transform: rotate(180deg);
    opacity: 1;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: #000000;
    min-width: 220px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    border-radius: 4px;
    margin-top: 2px;
  }

  .dropdown-wrapper:hover .dropdown-content {
    display: block;
  }

  .dropdown-content a {
    display: block;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #333;
    transition: all 0.3s;
    font-size: 13px;
  }

  .dropdown-content a:hover {
    background: #1a1a1a;
    padding-left: 30px;
  }

  .dropdown-content a:last-child {
    border-bottom: none;
  }

  /* User Dropdown */
  .user-dropdown-wrapper {
    position: relative;
    display: inline-block;
    margin-left: 15px;
    padding-left: 15px;
    border-left: 1px solid rgba(255, 255, 255, 0.3);
  }

  .user-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 13px;
  }

  .user-toggle:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  .user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
    font-weight: 500;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .user-dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: #000000;
    min-width: 200px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    border-radius: 4px;
    margin-top: 2px;
  }

  .user-dropdown-wrapper:hover .user-dropdown-content {
    display: block;
  }

  .user-dropdown-content a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #333;
    transition: all 0.3s;
    font-size: 13px;
  }

  .user-dropdown-content a:hover {
    background: #1a1a1a;
    padding-left: 25px;
  }

  .user-dropdown-content a:last-child {
    border-bottom: none;
  }

  .user-dropdown-content a i {
    width: 20px;
    text-align: center;
  }

  .dropdown-divider {
    height: 1px;
    margin: 0;
    background-color: #333;
    border: none;
  }

  /* Responsive Design */
  @media (max-width: 992px) {
    .top-info-bar {
      padding: 6px 0;
    }

    .top-info-contact {
      font-size: 12px;
      gap: 15px;
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

    .navbar-nav > a,
    .nav-link-main {
      width: 100%;
      padding: 12px 20px;
      border-radius: 0;
      font-size: 13px;
    }

    .mobile-menu-btn {
      display: block;
    }

    .dropdown-content {
      position: static;
      box-shadow: none;
      background: #1a1a1a;
      margin-top: 0;
    }

    .dropdown-wrapper.mobile-open .dropdown-content {
      display: block;
    }

    .user-dropdown-wrapper {
      border-left: none;
      margin-left: 0;
      padding-left: 0;
      width: 100%;
    }

    .user-toggle {
      width: 100%;
      padding: 12px 20px;
    }

    .user-dropdown-content {
      position: static;
      box-shadow: none;
      background: #1a1a1a;
      margin-top: 0;
    }

    .user-dropdown-wrapper.mobile-open .user-dropdown-content {
      display: block;
    }
  }

  @media (max-width: 768px) {
    .top-info-bar {
      display: none;
    }

    .logo-img {
      width: 40px;
      height: 40px;
    }

    .logo-img img {
      width: 30px;
      height: 30px;
    }
  }
</style>

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


<nav class="navbar-custom">
  <div class="container main-navbar">
    <div class="d-flex justify-content-between align-items-center w-100">
      <a href="index.php?controller=user&action=index" class="navbar-brand">
        <div class="logo-img">
          <img src="ppid_assets/logo.jpg" alt="Logo PPID">
        </div>
      </a>

      <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
      </button>

      <div class="navbar-nav" id="navLinks">
        <a href="index.php?controller=user&action=index">BERANDA</a>
        <a href="index.php?controller=user&action=profile">PROFIL SAYA</a>

        <!-- Permohonan Dropdown -->
        <div class="dropdown-wrapper">
          <a href="#" class="nav-link-main">
            PERMOHONAN <i class="fas fa-chevron-down dropdown-icon"></i>
          </a>
          <div class="dropdown-content">
            <a href="index.php?controller=AjukanPermohonan&action=index">
              <i class="fas fa-plus-circle me-2"></i>Ajukan Permohonan
            </a>
            <a href="index.php?controller=permohonan&action=index">
              <i class="fas fa-file-alt me-2"></i>Permohonan Saya
            </a>
            <a href="index.php?controller=keberatan&action=index">
              <i class="fas fa-exclamation-triangle me-2"></i>Keberatan Saya
            </a>
          </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="user-dropdown-wrapper">
          <a href="#" class="user-toggle">
            <div class="user-avatar">
              <?php if (isset($_SESSION['profile_photo']) && !empty($_SESSION['profile_photo'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Foto Profil">
              <?php else: ?>
                <i class="fas fa-user"></i>
              <?php endif; ?>
            </div>
            <span class="username"><?php echo isset($nama_lengkap) ? htmlspecialchars($nama_lengkap) : 'Pengguna'; ?></span>
            <i class="fas fa-chevron-down dropdown-icon"></i>
          </a>
          <div class="user-dropdown-content">
            <a href="index.php?controller=user&action=profile">
              <i class="fas fa-user"></i>
              Profil Saya
            </a>
            <a href="index.php?controller=user&action=settings">
              <i class="fas fa-cog"></i>
              Pengaturan
            </a>
            <hr class="dropdown-divider">
            <a href="index.php?controller=auth&action=logout">
              <i class="fas fa-sign-out-alt"></i>
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  // Initialize mobile menu functionality
  document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileMenuBtn) {
      mobileMenuBtn.addEventListener('click', function() {
        navLinks.classList.toggle('show');
      });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (navLinks && mobileMenuBtn &&
          !navLinks.contains(event.target) &&
          !mobileMenuBtn.contains(event.target)) {
        navLinks.classList.remove('show');
      }
    });

    // Mobile dropdown handling
    const mainLinks = document.querySelectorAll('.nav-link-main');
    mainLinks.forEach(function(link) {
      link.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
          e.preventDefault();
          const wrapper = this.closest('.dropdown-wrapper');
          if (wrapper) {
            wrapper.classList.toggle('mobile-open');
          }
        }
      });
    });

    // Mobile user dropdown handling
    const userToggle = document.querySelector('.user-toggle');
    if (userToggle) {
      userToggle.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
          e.preventDefault();
          const wrapper = this.closest('.user-dropdown-wrapper');
          if (wrapper) {
            wrapper.classList.toggle('mobile-open');
          }
        }
      });
    }
  });
</script>