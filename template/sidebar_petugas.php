<?php
// Fungsi untuk memeriksa apakah menu aktif
function isActivePetugas($controller, $action = null)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  $currentAction = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action === null) {
    return $currentController === $controller;
  }
  return $currentController === $controller && $currentAction === $action;
}

// Fungsi untuk memeriksa apakah dropdown aktif
function isDropdownActivePetugas($controllers)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  return is_array($controllers) ? in_array($currentController, $controllers) : $currentController === $controllers;
}
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link <?php echo isActivePetugas('dashboard', 'index') ? 'active' : ''; ?>" href="index.php?controller=dashboard&action=index">
        <i class="fa fa-dashboard menu-icon fa-sm"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <!-- Download Panduan -->
    <li class="nav-item">
      <a class="nav-link" href="index.php?controller=download&action=download" target="_blank">
        <i class="fa fa-file-pdf menu-icon fa-sm"></i>
        <span class="menu-title">Download Panduan</span>
      </a>
    </li>

    <!-- Permohonan - Meja Layanan Petugas -->
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActivePetugas(['permohonanpetugas']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#mejalayananDropdown" aria-expanded="<?php echo isDropdownActivePetugas(['permohonanpetugas']) ? 'true' : 'false'; ?>" aria-controls="mejalayananDropdown">
        <i class="fa fa-envelope-open menu-icon fa-sm"></i>
        <span class="menu-title">Permohonan</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActivePetugas(['permohonanpetugas']) ? 'show' : ''; ?>" id="mejalayananDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'mejaLayanan') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=mejaLayanan">
              <i class="fa fa-desk fa-sm me-2"></i>
              Meja Layanan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'permohonanMasuk') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=permohonanMasuk">
              <i class="fa fa-inbox fa-sm me-2"></i>
              Permohonan Masuk
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'disposisiIndex') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=disposisiIndex">
              <i class="fa fa-share-alt fa-sm me-2"></i>
              Permohonan Disposisi
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'diprosesIndex') || isActivePetugas('permohonanpetugas', 'permohonanDiproses') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=diprosesIndex">
              <i class="fa fa-cogs fa-sm me-2"></i>
              Permohonan Diproses
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'selesaiIndex') || isActivePetugas('permohonanpetugas', 'permohonanSelesai') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=selesaiIndex">
              <i class="fa fa-check-circle fa-sm me-2"></i>
              Permohonan Selesai
            </a>
          </li>
           <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'ditolakIndex') || isActivePetugas('permohonanpetugas', 'permohonanDitolak') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=ditolakIndex">
             <i class="fa fa-times-circle fa-sm me-2"></i>
              Permohonan Ditolak
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('permohonanpetugas', 'layananKepuasanIndex') || isActivePetugas('permohonanpetugas', 'layananKepuasan') ? 'active' : ''; ?>" href="index.php?controller=permohonanpetugas&action=layananKepuasanIndex">
              <i class="fa fa-smile fa-sm me-2"></i>
              Layanan Kepuasan
            </a>
          </li>

        </ul>
      </div>
    </li>

    <!-- Dokumen Informasi Publik Pemda -->
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActivePetugas(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#informasiPublikDropdown" aria-expanded="<?php echo isDropdownActivePetugas(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'true' : 'false'; ?>" aria-controls="informasiPublikDropdown">
        <i class="fa fa-folder-open menu-icon fa-sm"></i>
        <span class="menu-title">Dokumen Informasi<br>Publik Pemda</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActivePetugas(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'show' : ''; ?>" id="informasiPublikDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('kategoriBerkala') ? 'active' : ''; ?>" href="index.php?controller=kategoriBerkala&action=index">
              <i class="fa fa-calendar-alt fa-sm me-2"></i>
              Kategori Berkala
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('kategoriSertaMerta') ? 'active' : ''; ?>" href="index.php?controller=kategoriSertaMerta&action=index">
              <i class="fa fa-bolt fa-sm me-2"></i>
              Kategori Serta Merta
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('kategoriSetiapSaat') ? 'active' : ''; ?>" href="index.php?controller=kategoriSetiapSaat&action=index">
              <i class="fa fa-clock fa-sm me-2"></i>
              Kategori Setiap Saat
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('kategoriDikecualikanPetugas') ? 'active' : ''; ?>" href="index.php?controller=kategoriDikecualikanPetugas&action=index">
              <i class="fa fa-ban fa-sm me-2"></i>
              Kategori Dikecualikan
            </a>
          </li>
        </ul>
      </div>
    </li>

    <!-- Berita -->
    <li class="nav-item">
      <div class="nav-link <?php echo isDropdownActivePetugas(['berita']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#beritaDropdown" aria-expanded="<?php echo isDropdownActivePetugas(['berita']) ? 'true' : 'false'; ?>" aria-controls="beritaDropdown">
        <i class="fa fa-newspaper menu-icon fa-sm"></i>
        <span class="menu-title">Berita</span>
        <i class="menu-arrow"></i>
      </div>
      <div class="collapse <?php echo isDropdownActivePetugas(['berita']) ? 'show' : ''; ?>" id="beritaDropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('berita', 'index') ? 'active' : ''; ?>" href="index.php?controller=berita&action=index">
              <i class="fa fa-list fa-sm me-2"></i>
              List Berita
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo isActivePetugas('berita', 'create') ? 'active' : ''; ?>" href="index.php?controller=berita&action=create">
              <i class="fa fa-plus-circle fa-sm me-2"></i>
              Tambah Berita
            </a>
          </li>
        </ul>
      </div>
    </li>
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
        if (window.innerWidth < 992) {
          document.body.classList.remove('sidebar-icon-only');
        }
      });
    });
  });
</script>