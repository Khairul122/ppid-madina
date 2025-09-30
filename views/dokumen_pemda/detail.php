<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'operator')) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<?php include('template/header.php'); ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">

              <!-- Alert Messages -->
              <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                  <i class="mdi mdi-file-document me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Detail Dokumen</span>
                </div>
                <a href="index.php?controller=dokumenpemda&action=index" class="btn btn-outline-secondary">
                  <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
              </div>

              <!-- Detail Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-identifier me-1"></i>ID Dokumen
                          </h6>
                          <p class="card-text fw-bold"><?php echo htmlspecialchars($data['dokumen']['id_dokumen_pemda']); ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-file me-1"></i>Nama Jenis
                          </h6>
                          <p class="card-text fw-bold"><?php echo htmlspecialchars($data['dokumen']['nama_jenis']); ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-tag me-1"></i>Kategori
                          </h6>
                          <p class="card-text"><?php echo !empty($data['dokumen']['nama_kategori']) ? htmlspecialchars($data['dokumen']['nama_kategori']) : '<em class="text-muted">Tidak ada data</em>'; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-earth me-1"></i>Area
                          </h6>
                          <p class="card-text"><?php echo htmlspecialchars($data['dokumen']['area']); ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-clock me-1"></i>Dibuat
                          </h6>
                          <p class="card-text"><?php echo !empty($data['dokumen']['created_at']) ? date('d M Y H:i', strtotime($data['dokumen']['created_at'])) : '<em class="text-muted">Tidak ada data</em>'; ?></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="card border-0 bg-light">
                        <div class="card-body">
                          <h6 class="card-title text-primary mb-2">
                            <i class="mdi mdi-update me-1"></i>Diupdate
                          </h6>
                          <p class="card-text"><?php echo !empty($data['dokumen']['updated_at']) ? date('d M Y H:i', strtotime($data['dokumen']['updated_at'])) : '<em class="text-muted">Tidak ada data</em>'; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex gap-2 mt-4">
                    <a href="index.php?controller=dokumenpemda&action=edit&id=<?php echo $data['dokumen']['id_dokumen_pemda']; ?>" class="btn btn-primary">
                      <i class="mdi mdi-pencil me-1"></i>Edit Dokumen
                    </a>
                    <a href="index.php?controller=dokumenpemda&action=index" class="btn btn-outline-secondary">
                      <i class="mdi mdi-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>

  <script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>

</html>