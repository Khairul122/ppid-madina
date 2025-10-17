<?php
// Fungsi untuk memeriksa apakah menu aktif
function isActive($controller, $action = null)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  $currentAction = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action === null) {
    return $currentController === $controller;
  }
  return $currentController === $controller && $currentAction === $action;
}

// Fungsi untuk memeriksa apakah dropdown aktif
function isDropdownActive($controllers)
{
  $currentController = isset($_GET['controller']) ? $_GET['controller'] : '';
  return is_array($controllers) ? in_array($currentController, $controllers) : $currentController === $controllers;
}

// Fungsi untuk memeriksa peran pengguna
function hasRole($allowedRoles)
{
  $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
  if ($allowedRoles === 'all') return true;
  return in_array($userRole, $allowedRoles);
}
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php if (hasRole(['admin', 'petugas', 'masyarakat'])): ?>
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('dashboard', 'index') ? 'active' : ''; ?>" href="index.php?controller=dashboard&action=index">
          <i class="fa fa-dashboard menu-icon fa-sm"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="index.php?controller=download&action=download" target="_blank">
          <i class="fa fa-file-pdf menu-icon fa-sm"></i>
          <span class="menu-title">Download Panduan</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['permohonanadmin']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#mejalayananDropdown" aria-expanded="<?php echo isDropdownActive(['permohonanadmin']) ? 'true' : 'false'; ?>" aria-controls="mejalayananDropdown">
          <i class="fa fa-envelope-open menu-icon fa-sm"></i>
          <span class="menu-title">Permohonan</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['permohonanadmin']) ? 'show' : ''; ?>" id="mejalayananDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'create') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=create">
                <i class="fa fa-desk fa-sm me-2"></i>
                Meja Layanan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'index') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=index">
                <i class="fa fa-inbox fa-sm me-2"></i>
                Permohonan Masuk
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'disposisi') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=disposisiIndex">
                <i class="fa fa-share-alt fa-sm me-2"></i>
                Permohonan Disposisi
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'diproses') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=diprosesIndex">
                <i class="fa fa-cogs fa-sm me-2"></i>
                Permohonan Diproses
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'selesai') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=selesaiIndex">
                <i class="fa fa-check-circle fa-sm me-2"></i>
                Permohonan Selesai
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'ditolak') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=ditolakIndex">
                <i class="fa fa-times-circle fa-sm me-2"></i>
                Permohonan Ditolak
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'keberatan') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=keberatanIndex">
                <i class="fa fa-exclamation-triangle fa-sm me-2"></i>
                Keberatan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'sengketa') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=sengketaIndex">
                <i class="fa fa-gavel fa-sm me-2"></i>
                Sengketa
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'semua') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=semuaIndex">
                <i class="fa fa-list fa-sm me-2"></i>
                Semua Permohonan
              </a>
            </li>
              <li class="nav-item">
              <a class="nav-link <?php echo isActive('permohonanadmin', 'dataPemohon') ? 'active' : ''; ?>" href="index.php?controller=permohonanadmin&action=dataPemohonIndex">
                <i class="fa fa-user-friends fa-sm me-2"></i>
                Data Pemohon
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#informasiPublikDropdown" aria-expanded="<?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'true' : 'false'; ?>" aria-controls="informasiPublikDropdown">
          <i class="fa fa-folder-open menu-icon fa-sm"></i>
          <span class="menu-title">Dokumen Informasi<br>Publik Pemda</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['kategoriBerkala', 'kategoriSertaMerta', 'kategoriSetiapSaat']) ? 'show' : ''; ?>" id="informasiPublikDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('kategoriBerkala') ? 'active' : ''; ?>" href="index.php?controller=kategoriBerkala&action=index">
                <i class="fa fa-calendar-alt fa-sm me-2"></i>
                Kategori Berkala
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('kategoriSertaMerta') ? 'active' : ''; ?>" href="index.php?controller=kategoriSertaMerta&action=index">
                <i class="fa fa-bolt fa-sm me-2"></i>
                Kategori Serta Merta
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('kategoriSetiapSaat') ? 'active' : ''; ?>" href="index.php?controller=kategoriSetiapSaat&action=index">
                <i class="fa fa-clock fa-sm me-2"></i>
                Kategori Setiap Saat
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link <?php echo isActive('kategoriDikecualikanAdmin') ? 'active' : ''; ?>" href="index.php?controller=kategoriDikecualikanAdmin&action=index">
                <i class="fa fa-ban fa-sm me-2"></i>
                Kategori Dikecualikan
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <a class="nav-link <?php echo isActive('dokumenPemda') ? 'active' : ''; ?>" href="index.php?controller=dokumenPemda&action=index">
          <i class="fa fa-database menu-icon fa-sm"></i>
          <span class="menu-title">Master Jenis<br>Dokumen Pemda</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['skpd', 'petugas']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#penggunaDropdown" aria-expanded="<?php echo isDropdownActive(['skpd', 'petugas']) ? 'true' : 'false'; ?>" aria-controls="penggunaDropdown">
          <i class="fa fa-users-cog menu-icon fa-sm"></i>
          <span class="menu-title">Pengguna</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['skpd', 'petugas']) ? 'show' : ''; ?>" id="penggunaDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('skpd') ? 'active' : ''; ?>" href="index.php?controller=skpd&action=index">
                <i class="fa fa-building fa-sm me-2"></i>
                Operasional/SKPD
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('petugas') ? 'active' : ''; ?>" href="index.php?controller=petugas&action=index">
                <i class="fa fa-user-tie fa-sm me-2"></i>
                Petugas
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['wagateway']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#wagatewayDropdown" aria-expanded="<?php echo isDropdownActive(['wagateway']) ? 'true' : 'false'; ?>" aria-controls="wagatewayDropdown">
          <i class="fab fa-whatsapp menu-icon fa-sm"></i>
          <span class="menu-title">WA Gateway</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['wagateway']) ? 'show' : ''; ?>" id="wagatewayDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('wagateway', 'index') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=index">
                <i class="fa fa-tachometer-alt fa-sm me-2"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('wagateway', 'pesan_keluar') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=pesan_keluar">
                <i class="fa fa-paper-plane fa-sm me-2"></i>
                Kirim Pesan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('wagateway', 'draft') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=draft">
                <i class="fa fa-file-alt fa-sm me-2"></i>
                Draft
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('wagateway', 'arsip') ? 'active' : ''; ?>" href="index.php?controller=wagateway&action=arsip">
                <i class="fa fa-archive fa-sm me-2"></i>
                Arsip
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['berita']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#beritaDropdown" aria-expanded="<?php echo isDropdownActive(['berita']) ? 'true' : 'false'; ?>" aria-controls="beritaDropdown">
          <i class="fa fa-newspaper menu-icon fa-sm"></i>
          <span class="menu-title">Berita</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['berita']) ? 'show' : ''; ?>" id="beritaDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('berita', 'index') ? 'active' : ''; ?>" href="index.php?controller=berita&action=index">
                <i class="fa fa-list fa-sm me-2"></i>
                List Berita
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('berita', 'create') ? 'active' : ''; ?>" href="index.php?controller=berita&action=create">
                <i class="fa fa-plus-circle fa-sm me-2"></i>
                Tambah Berita
              </a>
            </li>
          </ul>
        </div>
      </li>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'petugas'])): ?>
      <li class="nav-item">
        <div class="nav-link <?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#pengaturanDropdown" aria-expanded="<?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'true' : 'false'; ?>" aria-controls="pengaturanDropdown">
          <i class="fa fa-cogs menu-icon fa-sm"></i>
          <span class="menu-title">Pengaturan</span>
          <i class="menu-arrow"></i>
        </div>
        <div class="collapse <?php echo isDropdownActive(['banner', 'profile', 'sosialmedia']) ? 'show' : ''; ?>" id="pengaturanDropdown">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('banner') ? 'active' : ''; ?>" href="index.php?controller=banner&action=index">
                <i class="fa fa-images menu-icon fa-sm"></i>
                <span class="menu-title">Banner</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>" href="index.php?controller=profile&action=index">
                <i class="fa fa-id-card menu-icon fa-sm"></i>
                <span class="menu-title">Profile</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>" href="index.php?controller=layananInformasi&action=index">
                <i class="fa fa-info-circle menu-icon fa-sm"></i>
                <span class="menu-title">Layanan Informasi <br>
                Publik</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>" href="index.php?controller=tataKelola&action=index">
                <i class="fa fa-gavel menu-icon fa-sm"></i>
                <span class="menu-title">Tata Kelola</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('profile') ? 'active' : ''; ?>" href="index.php?controller=informasiPublik&action=index">
                <i class="fa fa-bullhorn menu-icon fa-sm"></i>
                <span class="menu-title">Informasi Publik</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('faq') ? 'active' : ''; ?>" href="index.php?controller=faq&action=index">
                <i class="fa fa-question-circle menu-icon fa-sm"></i>
                <span class="menu-title">FAQ</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo isActive('sosialmedia') ? 'active' : ''; ?>" href="index.php?controller=sosialmedia&action=index">
                <i class="fab fa-instagram menu-icon fa-sm"></i>
                <span class="menu-title">Sosial Media</span>
              </a>
            </li>
            <?php if (hasRole(['admin', 'petugas'])): ?>
              <li class="nav-item">
                <a class="nav-link <?php echo isActive('album') ? 'active' : ''; ?>" href="index.php?controller=album&action=index">
                  <i class="fa fa-photo-video menu-icon fa-sm"></i>
                  <span class="menu-title">Album</span>
                </a>
              </li>
            <?php endif; ?>
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
        if (window.innerWidth < 992) {
          document.body.classList.remove('sidebar-icon-only');
        }
      });
    });
  });
</script>