<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
              <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <i class="mdi mdi-account-multiple me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Data Petugas</span>
                </div>

                <div class="d-flex gap-2">
                  <!-- Search Form -->
                  <form method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="petugas">
                    <input type="hidden" name="action" value="index">
                    <?php if (isset($pagination['limit'])): ?>
                      <input type="hidden" name="limit" value="<?php echo $pagination['limit']; ?>">
                    <?php endif; ?>
                    <div class="input-group" style="width: 300px;">
                      <input type="text"
                             class="form-control"
                             name="search"
                             value="<?php echo htmlspecialchars($search); ?>"
                             placeholder="Cari petugas...">
                      <button class="btn btn-outline-secondary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </form>

                  <?php if (!empty($petugas_list)): ?>
                    <button class="btn btn-info" onclick="window.location.href='index.php?controller=petugas&action=export'">
                      <i class="mdi mdi-file-excel me-1"></i>Ekspor Excel
                    </button>
                  <?php endif; ?>

                  <button class="btn btn-primary" onclick="window.location.href='index.php?controller=petugas&action=form'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Petugas
                  </button>
                </div>
              </div>

              <!-- Table -->
              <?php if (!empty($petugas_list)): ?>
                <div class="table-responsive">
                  <table class="table table-hover" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <thead style="background: #4A90E2; color: white;">
                      <tr>
                        <th scope="col" style="padding: 15px; border: none; position: relative;">
                          NAMA PETUGAS
                          <i class="mdi mdi-menu-up float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          SKPD
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          EMAIL
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          ACTION
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($petugas_list as $petugas): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                          <td style="padding: 15px; border: none;">
                            <div>
                              <strong style="color: #4A90E2;"><?php echo htmlspecialchars($petugas['nama_petugas']); ?></strong>
                              <?php if (!empty($petugas['no_kontak'])): ?>
                                <br>
                                <small class="text-muted">
                                  <i class="mdi mdi-phone"></i> <?php echo htmlspecialchars($petugas['no_kontak']); ?>
                                </small>
                              <?php endif; ?>
                            </div>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php echo htmlspecialchars($petugas['nama_skpd']); ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php echo htmlspecialchars($petugas['email']); ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <div class="d-flex justify-content-center gap-1">
                              <button class="btn btn-sm"
                                      onclick="viewDetail(<?php echo $petugas['id_petugas']; ?>)"
                                      style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Lihat Detail">
                                <i class="mdi mdi-eye"></i> Detail
                              </button>
                              <button class="btn btn-sm"
                                      onclick="window.location.href='index.php?controller=petugas&action=form&id=<?php echo $petugas['id_petugas']; ?>'"
                                      style="background: #ffc107; color: #212529; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Edit Data">
                                <i class="mdi mdi-pencil"></i> Edit
                              </button>
                              <button class="btn btn-sm"
                                      onclick="confirmDelete(<?php echo $petugas['id_petugas']; ?>, '<?php echo addslashes($petugas['nama_petugas']); ?>')"
                                      style="background: #E74C3C; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Hapus Data">
                                <i class="mdi mdi-delete"></i> Hapus
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_records'] > 0): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div class="text-muted">
                    Showing <?php echo $pagination['start_record']; ?> to <?php echo $pagination['end_record']; ?> of <?php echo $pagination['total_records']; ?> entries
                  </div>
                  <div class="d-flex">
                    <form method="GET" class="d-flex align-items-center">
                      <input type="hidden" name="controller" value="petugas">
                      <input type="hidden" name="action" value="index">
                      <?php if (!empty($search)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                      <?php endif; ?>
                      <select name="limit" class="form-select form-select-sm me-2" style="width: 80px;" onchange="this.form.submit()">
                        <option value="10" <?php echo $pagination['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?php echo $pagination['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?php echo $pagination['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $pagination['limit'] == 100 ? 'selected' : ''; ?>>100</option>
                      </select>
                    </form>
                    <span class="text-muted me-2">items per page</span>
                  </div>
                </div>

                <?php if ($pagination['total_pages'] > 1): ?>
                <nav aria-label="Page navigation" class="mt-3">
                  <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <?php if ($pagination['current_page'] > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=petugas&action=index&page=<?php echo $pagination['current_page'] - 1; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                          Previous
                        </a>
                      </li>
                    <?php else: ?>
                      <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                      </li>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php
                    $start_page = max(1, $pagination['current_page'] - 2);
                    $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);

                    // Show first page if not in range
                    if ($start_page > 1):
                    ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=petugas&action=index&page=1&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">1</a>
                      </li>
                      <?php if ($start_page > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                      <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                        <a class="page-link" href="?controller=petugas&action=index&page=<?php echo $i; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                          <?php echo $i; ?>
                        </a>
                      </li>
                    <?php endfor; ?>

                    <!-- Show last page if not in range -->
                    <?php if ($end_page < $pagination['total_pages']): ?>
                      <?php if ($end_page < $pagination['total_pages'] - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=petugas&action=index&page=<?php echo $pagination['total_pages']; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                          <?php echo $pagination['total_pages']; ?>
                        </a>
                      </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=petugas&action=index&page=<?php echo $pagination['current_page'] + 1; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                          Next
                        </a>
                      </li>
                    <?php else: ?>
                      <li class="page-item disabled">
                        <span class="page-link">Next</span>
                      </li>
                    <?php endif; ?>
                  </ul>
                </nav>
                <?php endif; ?>
                <?php endif; ?>
              <?php else: ?>
                <div class="text-center py-5">
                  <i class="mdi mdi-account-multiple-outline" style="font-size: 64px; color: #ccc;"></i>
                  <h5 class="text-muted mt-3">
                    <?php if (!empty($search)): ?>
                      Tidak ada petugas yang ditemukan untuk pencarian "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                      Belum ada data petugas
                    <?php endif; ?>
                  </h5>
                  <button class="btn btn-primary mt-2" onclick="window.location.href='index.php?controller=petugas&action=form'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Petugas Pertama
                  </button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>

  <!-- Modal Detail Petugas -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: #4A90E2; color: white;">
          <h5 class="modal-title" id="detailModalLabel">
            <i class="mdi mdi-eye me-2"></i>Detail Petugas
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="detailContent">
          <div class="text-center py-3">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="mdi mdi-close me-1"></i>Tutup
          </button>
          <button type="button" class="btn btn-primary" id="editFromDetail">
            <i class="mdi mdi-pencil me-1"></i>Edit Data
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">
            <i class="mdi mdi-delete me-2"></i>Konfirmasi Hapus
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus petugas "<span id="deleteName"></span>"?</p>
          <p class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Data yang sudah dihapus tidak dapat dikembalikan!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form method="POST" action="index.php?controller=petugas&action=delete" style="display: inline;">
            <input type="hidden" name="id" id="deleteId">
            <button type="submit" class="btn btn-danger">
              <i class="mdi mdi-delete me-1"></i>Ya, Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentPetugasId = null;

    function viewDetail(id) {
      currentPetugasId = id;
      const modal = new bootstrap.Modal(document.getElementById('detailModal'));
      modal.show();

      // Reset content
      document.getElementById('detailContent').innerHTML = `
        <div class="text-center py-3">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Memuat data...</p>
        </div>
      `;

      // Fetch data
      fetch(`index.php?controller=petugas&action=detail&id=${id}&ajax=1`)
        .then(response => response.json())
        .then(data => {
          const content = `
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-identifier me-1"></i>ID Petugas
                    </h6>
                    <p class="card-text fw-bold">${data.id_petugas}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-account me-1"></i>Nama Petugas
                    </h6>
                    <p class="card-text fw-bold">${data.nama_petugas}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-office-building me-1"></i>SKPD
                    </h6>
                    <p class="card-text fw-bold">${data.nama_skpd}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-email me-1"></i>Email
                    </h6>
                    <p class="card-text">${data.email}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-phone me-1"></i>No Kontak
                    </h6>
                    <p class="card-text">${data.no_kontak || '<em class="text-muted">Tidak ada data</em>'}</p>
                  </div>
                </div>
              </div>
            </div>
          `;
          document.getElementById('detailContent').innerHTML = content;
        })
        .catch(error => {
          document.getElementById('detailContent').innerHTML = `
            <div class="alert alert-danger">
              <i class="mdi mdi-alert-circle me-2"></i>
              Error loading data. Please try again.
            </div>
          `;
        });
    }

    function confirmDelete(id, name) {
      document.getElementById('deleteId').value = id;
      document.getElementById('deleteName').textContent = name;
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();
    }

    // Edit from detail modal
    document.getElementById('editFromDetail').addEventListener('click', function() {
      if (currentPetugasId) {
        window.location.href = `index.php?controller=petugas&action=form&id=${currentPetugasId}`;
      }
    });

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