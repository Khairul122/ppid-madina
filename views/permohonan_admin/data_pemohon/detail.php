<?php
// Memastikan file ini diakses melalui index.php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Include header template
include('template/header.php');
?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Page Title -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0"><?php echo htmlspecialchars($title) ?></h4>
                </div>
                <div>
                  <a href="index.php?controller=permohonan_admin&action=data_pemohon" 
                     class="btn btn-light btn-icon-text btn-rounded">
                    <i class="ti-arrow-left btn-icon-prepend"></i>Kembali
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Detail Pemohon -->
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Detail Informasi Pemohon</h4>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nama Lengkap:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label"><?php echo htmlspecialchars($pemohon['nama_lengkap']) ?></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Alamat:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label"><?php echo htmlspecialchars($pemohon['alamat']) ?></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Kota:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label"><?php echo htmlspecialchars($pemohon['city']) ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Email:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label"><?php echo htmlspecialchars($pemohon['email']) ?></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">No Telp:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label"><?php echo htmlspecialchars($pemohon['no_kontak']) ?></p>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Status Pengguna:</label>
                        <div class="col-sm-8">
                          <p class="col-form-label">
                            <?php 
                            // Kita tidak menampilkan status pengguna di tabel detail karena data ini ada di tabel lain
                            // Tapi jika dibutuhkan, kita bisa mengambilnya dari tabel users
                            ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="mt-4">
                    <a href="index.php?controller=permohonan_admin&action=data_pemohon" 
                       class="btn btn-light">
                      <i class="ti-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Footer -->
        <?php include('template/footer.php') ?>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <?php include('template/script.php') ?>
</body>
</html>