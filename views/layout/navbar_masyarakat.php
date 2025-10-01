<nav class="navbar-custom">
    <div class="container main-navbar">
      <div class="d-flex justify-content-between align-items-center w-100">
        <!-- Logo and Title -->
        <a href="index.php?controller=user&action=index" class="navbar-brand">
          <div class="logo-img">
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiMwMDAwMDAiLz4KPHN2ZyB4PSI4IiB5PSI4IiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSI+CjxwYXRoIGQ9Ik0xMiAyQzEzLjEgMiAxNCAyLjkgMTQgNFYxNkMxNCAxNi41NSAxMy41NSAxNyAxMyAxN0gxMUMxMC40NSAxNyAxMCAxNi41NSAxMCAxNlY0QzEwIDIuOSAxMC45IDIgMTIgMloiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0yIDEyQzIgMTMuMSAyLjkgMTQgNCAxNEgxNkMxNi41NSAxNSAxNyAxNC41NSAxNyAxNFYxMkMxNyAxMC45IDE2LjEgMTAgMTUgMTBIM0MyLjkgMTAgMiAxMC45IDIgMTJaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4KPC9zdmc+" alt="Logo">
          </div>
         
        </a>

        <!-- Navigation Links -->
        <div class="navbar-nav">
          <a href="index.php?controller=user&action=index">BERANDA</a>
          <a href="index.php?controller=user&action=profile">PROFILE</a>

          <!-- Permohonan Dropdown -->
          <div class="nav-dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              PERMOHONAN <i class="fas fa-chevron-down ms-1"></i>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="index.php?controller=AjukanPermohonan&action=index">Ajukan Permohonan</a></li>
              <li><a class="dropdown-item" href="index.php?controller=permohonan&action=index">Permohonan Saya</a></li>
              <li><a class="dropdown-item" href="index.php?controller=keberatan&action=index">Keberatan Saya</a></li>
              <li><a class="dropdown-item" href="index.php?controller=dokumen&action=index">Dokumen Saya</a></li>
            </ul>
          </div>

          <!-- User Profile Dropdown -->
          <div class="nav-dropdown user-dropdown">
            <a href="#" class="dropdown-toggle user-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="user-avatar">
                <?php if (!empty($user_data['foto_profile']) && file_exists($user_data['foto_profile'])): ?>
                  <img src="<?php echo htmlspecialchars($user_data['foto_profile']); ?>" alt="Foto Profil">
                <?php else: ?>
                  <i class="fas fa-user"></i>
                <?php endif; ?>
              </div>
              <span class="username"><?php echo htmlspecialchars($nama_lengkap); ?></span>
              <i class="fas fa-chevron-down ms-1"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="index.php?controller=user&action=profile"><i class="fas fa-user me-2"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?controller=auth&action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>