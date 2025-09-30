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
              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <div style="color: #666; font-size: 14px; margin-bottom: 5px;">
                    Kategori / <span style="color: #333; font-weight: 500;">Setiap Saat</span> / <span style="color: #333; font-weight: 500;">Draft</span>
                  </div>
                  <h1 style="color: #333; font-size: 28px; font-weight: 600; margin: 0;">Draft Dokumen Setiap Saat</h1>
                  <p style="color: #666; font-size: 14px; margin: 5px 0 0 0;">Manajemen dokumen draft kategori setiap saat</p>
                </div>
                <div class="d-flex gap-2">
                  <a href="index.php?controller=kategorisetiapsaat&action=index" class="btn btn-outline-primary" style="padding: 8px 16px; border-radius: 6px;">
                    <i class="mdi mdi-format-list-bulleted me-1"></i>Semua Dokumen
                  </a>
                  <a href="index.php?controller=kategorisetiapsaat&action=create" class="btn btn-primary" style="padding: 8px 16px; border-radius: 6px;">
                    <i class="mdi mdi-plus me-1"></i>Tambah Dokumen
                  </a>
                </div>
              </div>

              <!-- Alert Messages -->
              <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?= htmlspecialchars($_SESSION['success']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
              <?php endif; ?>

              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?= htmlspecialchars($_SESSION['error']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
              <?php endif; ?>

              <!-- Stats Card -->
              <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                  <div class="card bg-warning text-white">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="card-title mb-0"><?= $pagination['total_records'] ?? 0; ?></h5>
                          <small>Total Draft</small>
                        </div>
                        <i class="mdi mdi-file-document fa-2x opacity-75"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                  <div class="card bg-info text-white">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="card-title mb-0"><?= $this->kategoriSetiapSaatModel->getTotalCount() - ($pagination['total_records'] ?? 0); ?></h5>
                          <small>Sudah Publikasi</small>
                        </div>
                        <i class="mdi mdi-check-circle fa-2x opacity-75"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Search and Filter Section -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="kategorisetiapsaat">
                    <input type="hidden" name="action" value="draft">
                    <div class="col-md-8">
                      <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                        <input type="text" class="form-control" name="search"
                               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>"
                               placeholder="Cari draft berdasarkan judul, kandungan informasi, terbitkan sebagai...">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                          <i class="mdi mdi-magnify me-1"></i>Cari
                        </button>
                        <a href="index.php?controller=kategorisetiapsaat&action=draft" class="btn btn-outline-secondary">
                          <i class="mdi mdi-refresh me-1"></i>Reset
                        </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Data Table Section -->
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #4A90E2; color: white;">
                  <h5 class="card-title mb-0">
                    <i class="mdi mdi-file-document me-2"></i>Draft Dokumen Setiap Saat
                  </h5>
                  <small style="color: rgba(255,255,255,0.8);"
                    Menampilkan <?= $pagination['start_record'] ?? 0; ?>-<?= $pagination['end_record'] ?? 0; ?>
                    dari <?= $pagination['total_records'] ?? 0; ?> draft
                  </small>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                      <thead style="background-color: #4A90E2; color: white;">
                        <tr>
                          <th width="5%" class="text-center">NO</th>
                          <th width="25%">JUDUL</th>
                          <th width="20%">TERBITKAN SEBAGAI</th>
                          <th width="15%">TIPE FILE</th>
                          <th width="15%" class="text-center">TANGGAL BUAT</th>
                          <th width="20%" class="text-center">AKSI</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($dokumen_list)): ?>
                          <?php
                          $start_number = (($pagination['current_page'] ?? 1) - 1) * ($pagination['limit'] ?? 10) + 1;
                          foreach ($dokumen_list as $index => $dokumen):
                          ?>
                            <tr>
                              <td class="text-center fw-bold"><?= $start_number + $index; ?></td>
                              <td>
                                <div class="fw-bold text-warning"><?= htmlspecialchars($dokumen['judul']); ?></div>
                                <?php if (!empty($dokumen['kandungan_informasi'])): ?>
                                  <small class="text-muted">
                                    <?= htmlspecialchars(strlen($dokumen['kandungan_informasi']) > 80 ?
                                         substr($dokumen['kandungan_informasi'], 0, 80) . '...' :
                                         $dokumen['kandungan_informasi']); ?>
                                  </small>
                                <?php endif; ?>
                              </td>
                              <td>
                                <span class="fw-medium"><?= htmlspecialchars($dokumen['terbitkan_sebagai']); ?></span>
                                <?php if (!empty($dokumen['nama_dokumen_pemda'])): ?>
                                  <br><small class="text-muted">Ref: <?= htmlspecialchars($dokumen['nama_dokumen_pemda']); ?></small>
                                <?php endif; ?>
                              </td>
                              <td>
                                <?php
                                $icon = 'fas fa-file';
                                $color = 'text-secondary';
                                switch($dokumen['tipe_file']) {
                                    case 'audio': $icon = 'mdi mdi-file-music'; $color = 'text-info'; break;
                                    case 'video': $icon = 'mdi mdi-file-video'; $color = 'text-danger'; break;
                                    case 'text': $icon = 'mdi mdi-file-document'; $color = 'text-primary'; break;
                                    case 'gambar': $icon = 'mdi mdi-file-image'; $color = 'text-warning'; break;
                                    case 'lainnya': $icon = 'mdi mdi-file'; $color = 'text-secondary'; break;
                                }
                                ?>
                                <i class="<?= $icon; ?> <?= $color; ?> me-2"></i>
                                <span class="text-capitalize"><?= htmlspecialchars($dokumen['tipe_file']); ?></span>
                              </td>
                              <td class="text-center">
                                <div class="small">
                                  <?= date('d/m/Y', strtotime($dokumen['created_at'])); ?><br>
                                  <span class="text-muted"><?= date('H:i', strtotime($dokumen['created_at'])); ?></span>
                                </div>
                              </td>
                              <td class="text-center">
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=kategorisetiapsaat&action=edit&id=<?= $dokumen['id_dokumen']; ?>"
                                     class="btn btn-sm btn-outline-primary" title="Edit" style="border-radius: 4px; margin: 1px;">
                                    <i class="mdi mdi-pencil"></i>
                                  </a>

                                  <button type="button" class="btn btn-sm btn-outline-success"
                                          onclick="confirmPublishStatus(<?= $dokumen['id_dokumen']; ?>)" title="Publikasikan" style="border-radius: 4px; margin: 1px;">
                                    <i class="mdi mdi-check-circle"></i>
                                  </button>

                                  <button type="button" class="btn btn-sm btn-outline-info"
                                          onclick="copyLink('<?= $dokumen['id_dokumen']; ?>')" title="Copy Link" style="border-radius: 4px; margin: 1px;">
                                    <i class="mdi mdi-link"></i>
                                  </button>

                                  <button type="button" class="btn btn-sm btn-outline-danger"
                                          onclick="confirmDelete(<?= $dokumen['id_dokumen']; ?>, '<?= htmlspecialchars($dokumen['judul']); ?>')" title="Hapus" style="border-radius: 4px; margin: 1px;">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center py-5">
                              <div class="text-muted">
                                <i class="mdi mdi-file-document fa-3x mb-3 text-warning"></i>
                                <h5>Belum Ada Draft</h5>
                                <p class="mb-3">
                                  <?php if (!empty($_GET['search'])): ?>
                                    Tidak ada draft yang ditemukan untuk pencarian "<?= htmlspecialchars($_GET['search']); ?>"
                                  <?php else: ?>
                                    Belum ada dokumen dengan status draft
                                  <?php endif; ?>
                                </p>
                                <?php if (empty($_GET['search'])): ?>
                                  <a href="index.php?controller=kategorisetiapsaat&action=create" class="btn btn-primary">
                                    <i class="mdi mdi-plus me-1"></i>Tambah Dokumen Pertama
                                  </a>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Pagination -->
                <?php if (($pagination['total_pages'] ?? 0) > 1): ?>
                  <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                      <small class="text-muted">
                        Halaman <?= $pagination['current_page']; ?> dari <?= $pagination['total_pages']; ?>
                      </small>

                      <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm mb-0">
                          <!-- Previous Page -->
                          <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=kategorisetiapsaat&action=draft&page=<?= $pagination['current_page'] - 1; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                <i class="mdi mdi-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <!-- Page Numbers -->
                          <?php
                          $start_page = max(1, $pagination['current_page'] - 2);
                          $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);

                          for ($i = $start_page; $i <= $end_page; $i++):
                          ?>
                            <li class="page-item <?= ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                              <a class="page-link" href="?controller=kategorisetiapsaat&action=draft&page=<?= $i; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                <?= $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <!-- Next Page -->
                          <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=kategorisetiapsaat&action=draft&page=<?= $pagination['current_page'] + 1; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                <i class="mdi mdi-chevron-right"></i>
                              </a>
                            </li>
                          <?php endif; ?>
                        </ul>
                      </nav>
                    </div>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Quick Actions Section -->
              <?php if (!empty($dokumen_list)): ?>
                <div class="card mt-4">
                  <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                      <i class="mdi mdi-flash me-2"></i>Aksi Cepat
                    </h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="d-grid">
                          <button type="button" class="btn btn-outline-success" onclick="publishAllDrafts()">
                            <i class="mdi mdi-check-circle me-1"></i>Publikasikan Semua Draft
                          </button>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="d-grid">
                          <button type="button" class="btn btn-outline-danger" onclick="deleteAllDrafts()">
                            <i class="mdi mdi-delete me-1"></i>Hapus Semua Draft
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">
            <i class="mdi mdi-alert me-2"></i>Konfirmasi Hapus
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus draft dokumen:</p>
          <div class="alert alert-warning">
            <strong id="deleteDocumentTitle"></strong>
          </div>
          <p class="text-danger">
            <i class="mdi mdi-alert me-1"></i>
            Tindakan ini tidak dapat dibatalkan!
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
            <i class="mdi mdi-delete me-1"></i>Hapus
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Publish Status Confirmation Modal -->
  <div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">
            <i class="mdi mdi-check-circle me-2"></i>Konfirmasi Publikasi
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mempublikasikan dokumen draft ini?</p>
          <div class="alert alert-success">
            <i class="mdi mdi-check-circle me-2"></i>
            Dokumen akan dapat diakses oleh publik setelah dipublikasikan.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="confirmPublishBtn">
            <i class="mdi mdi-check-circle me-1"></i>Publikasikan
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
  let deleteId = null;
  let publishId = null;

  function confirmDelete(id, title) {
      deleteId = id;
      document.getElementById('deleteDocumentTitle').textContent = title;
      new bootstrap.Modal(document.getElementById('deleteModal')).show();
  }

  function confirmPublishStatus(id) {
      publishId = id;
      new bootstrap.Modal(document.getElementById('publishModal')).show();
  }

  function copyLink(id) {
      const link = `${window.location.origin}${window.location.pathname}?controller=kategorisetiapsaat&action=view&id=${id}`;
      navigator.clipboard.writeText(link).then(() => {
          // Show success toast
          const toast = document.createElement('div');
          toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
          toast.style.cssText = 'top: 20px; right: 20px; z-index: 1056;';
          toast.innerHTML = `
              <div class="d-flex">
                  <div class="toast-body">
                      <i class="mdi mdi-check me-2"></i>Link berhasil disalin!
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
              </div>
          `;
          document.body.appendChild(toast);
          const bsToast = new bootstrap.Toast(toast);
          bsToast.show();
          toast.addEventListener('hidden.bs.toast', () => {
              document.body.removeChild(toast);
          });
      }).catch(() => {
          alert('Gagal menyalin link');
      });
  }

  function publishAllDrafts() {
      if (confirm('Apakah Anda yakin ingin mempublikasikan semua draft dokumen setiap saat?')) {
          // This would need a custom endpoint to handle bulk actions
          alert('Fitur ini akan segera tersedia');
      }
  }

  function deleteAllDrafts() {
      if (confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SEMUA draft dokumen setiap saat?\n\nTindakan ini tidak dapat dibatalkan!')) {
          // This would need a custom endpoint to handle bulk actions
          alert('Fitur ini akan segera tersedia');
      }
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (deleteId) {
          window.location.href = `index.php?controller=kategorisetiapsaat&action=destroy&id=${deleteId}`;
      }
  });

  document.getElementById('confirmPublishBtn').addEventListener('click', function() {
      if (publishId) {
          const formData = new FormData();
          formData.append('id', publishId);

          fetch('index.php?controller=kategorisetiapsaat&action=updateToPublikasi', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert(data.message || 'Terjadi kesalahan');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan jaringan');
          });
      }
  });

  // Auto-refresh every 5 minutes to keep data fresh
  setInterval(() => {
      const urlParams = new URLSearchParams(window.location.search);
      if (!urlParams.get('search')) {
          // Only auto-refresh if not searching
          window.location.reload();
      }
  }, 300000); // 5 minutes
  </script>

</body>

</html>