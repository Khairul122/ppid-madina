<?php
// Fungsi untuk memeriksa apakah menu aktif
function isActive($controller, $action = null) {
    $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
    $currentAction = isset($_GET['action']) ? $_GET['action'] : '';
    
    if ($action === null) {
        return $currentController === $controller;
    }
    return $currentController === $controller && $currentAction === $action;
}

// Fungsi untuk memeriksa apakah dropdown aktif
function isDropdownActive($controllers) {
    $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
    return is_array($controllers) ? in_array($currentController, $controllers) : $currentController === $controllers;
}

// Fungsi untuk memeriksa peran pengguna
function hasRole($allowedRoles) {
    $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
    if ($allowedRoles === 'all') return true;
    return in_array($userRole, $allowedRoles);
}
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php if (hasRole(['admin', 'petugas', 'masyarakat'])): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('user', 'index') ? 'active' : ''; ?>" href="index.php?controller=user&action=index">
        <i class="fa fa-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <a class="nav-link" href="index.php?controller=download&action=download" target="_blank">
        <i class="fa fa-download menu-icon"></i>
        <span class="menu-title">Download Panduan</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['permohonanadmin']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#mejalayananDropdown" aria-expanded="<?php echo isDropdownActive(['permohonanadmin']) ? 'true' : 'false'; ?>" aria-controls="mejalayananDropdown">
        <i class="fa fa-desktop menu-icon"></i>
        <span class="menu-title">Permohonan</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['permohonanadmin']) ? 'show' : ''; ?>" id="mejalayananDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('permohonanadmin', 'index') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=index">List Permohonan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('permohonanadmin', 'create') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=create">Form Permohonan Lengkap</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('permohonanadmin', 'disposisi') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=disposisiIndex">Permohonan Disposisi</a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#informasiPublikDropdown" aria-expanded="<?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'true' : 'false'; ?>" aria-controls="informasiPublikDropdown">
        <i class="fa fa-file-text-o menu-icon"></i>
        <span class="menu-title">Dokumen Informasi<br>Publik Pemda</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'show' : ''; ?>" id="informasiPublikDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('kategoriBerkala') ? 'active' : ''; ?>" href="index.php?controller=kategoriBerkala&action=index">Kategori Berkala</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('kategoriSertaMerta') ? 'active' : ''; ?>" href="index.php?controller=kategoriSertaMerta&action=index">Kategori Serta Merta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('kategoriSetiapSaat') ? 'active' : ''; ?>" href="index.php?controller=kategoriSetiapSaat&action=index">Kategori Setiap Saat</a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('dokumenPemda') ? 'active' : ''; ?>" href="index.php?controller=dokumenPemda&action=index">
        <i class="fa fa-download menu-icon"></i>
        <span class="menu-title">Master Jenis<br>Dokumen Pemda</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['skpd', 'petugas']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#penggunaDropdown" aria-expanded="<?php echo isDropdownActive(['skpd', 'petugas']) ? 'true' : 'false'; ?>" aria-controls="penggunaDropdown">
        <i class="fa fa-users menu-icon"></i>
        <span class="menu-title">Pengguna</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['skpd', 'petugas']) ? 'show' : ''; ?>" id="penggunaDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('skpd') ? 'active' : ''; ?>" href="index.php?controller=skpd&action=index">Operasional/SKPD</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('petugas') ? 'active' : ''; ?>" href="index.php?controller=petugas&action=index">Petugas</a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['wagateway']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#wagatewayDropdown" aria-expanded="<?php echo isDropdownActive(['wagateway']) ? 'true' : 'false'; ?>" aria-controls="wagatewayDropdown">
        <i class="fa fa-whatsapp menu-icon"></i>
        <span class="menu-title">WA Gateway</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['wagateway']) ? 'show' : ''; ?>" id="wagatewayDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('wagateway', 'index') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=index">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('wagateway', 'pesan_keluar') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=pesan_keluar">Kirim Pesan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('wagateway', 'draft') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=draft">Draft</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('wagateway', 'arsip') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=arsip">Arsip</a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['berita']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#beritaDropdown" aria-expanded="<?php echo isDropdownActive(['berita']) ? 'true' : 'false'; ?>" aria-controls="beritaDropdown">
        <i class="fa fa-newspaper-o menu-icon"></i>
        <span class="menu-title">Berita</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['berita']) ? 'show' : ''; ?>" id="beritaDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('berita', 'index') ? 'active' : ''; ?>" href="index.php?controller=berita&action=index">List Berita</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('berita', 'create') ? 'active' : ''; ?>" href="index.php?controller=berita&action=create">Tambah Berita</a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#pengaturanDropdown" aria-expanded="<?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'true' : 'false'; ?>" aria-controls="pengaturanDropdown">
        <i class="fa fa-cog menu-icon"></i>
        <span class="menu-title">Pengaturan</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'show' : ''; ?>" id="pengaturanDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('banner') ? 'active' : ''; ?>" href="index.php?controller=banner&action=index">
              <i class="fa fa-image menu-icon"></i>
              <span class="menu-title">Banner</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>" href="index.php?controller=profile&action=index">
              <i class="fa fa-user menu-icon"></i>
              <span class="menu-title">Profile</span>
            </a>
          </li>
           <li class="nav-item">
            <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>"href="index.php?controller=layananInformasi&action=index">
              <i class="fa fa-user menu-icon"></i>
              <span class="menu-title">Layanan Informasi Publik</span>
            </a>
          </li>
                     <li class="nav-item">
            <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>"href="index.php?controller=informasiPublik&action=index">
              <i class="fa fa-user menu-icon"></i>
              <span class="menu-title">Informasi Publik</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActive('sosialmedia') ? 'active' : ''; ?>" href="index.php?controller=sosialmedia&action=index">
              <i class="fa fa-instagram menu-icon"></i>
              <span class="menu-title">Sosial Media</span>
            </a>
          </li>
        </ul>
      </div>
    </li>
    <?php endif; ?>
  </ul>
</nav>

<script>
  // Fungsi untuk menangani aktifasi item menu saat dibuka dari dropdown
  document.addEventListener('DOMContentLoaded', function() {
    // Membuka dropdown otomatis jika ada item aktif didalamnya
    const activeItems = document.querySelectorAll('.sub-menu .nav-link.active');
    activeItems.forEach(function(item) {
      const parentCollapse = item.closest('.collapse');
      if (parentCollapse) {
        parentCollapse.classList.add('show');
        // Update aria-expanded attribute juga
        const correspondingToggle = document.querySelector('[href="#' + parentCollapse.id + '"]');
        if (correspondingToggle) {
          correspondingToggle.setAttribute('aria-expanded', 'true');
        }
      }
    });
    
    // Menangani klik pada mobile untuk menutup sidebar
    const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
    sidebarLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        // Menutup sidebar di mobile setelah klik
        if(window.innerWidth < 992) {
          document.body.classList.remove('sidebar-icon-only');
        }
      });
    });
  });
</script>