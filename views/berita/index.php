<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'petugas')) {
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
      <?php
      if ($_SESSION['role'] === 'admin') {
        include 'template/sidebar.php';
      } else {
        include 'template/sidebar_petugas.php';
      }
      ?>
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
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <span style="font-size: 18px; font-weight: 500;">Data Berita</span>
                </div>

                <div class="d-flex gap-2">
                  <!-- Search Form -->
                  <form method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="berita">
                    <input type="hidden" name="action" value="index">
                    <?php if (isset($data['limit'])): ?>
                      <input type="hidden" name="limit" value="<?php echo $data['limit']; ?>">
                    <?php endif; ?>
                    <div class="input-group" style="width: 300px;">
                      <input type="text"
                        class="form-control"
                        name="search"
                        style="padding: 22px; border-radius: 6px;"
                        value="<?php echo htmlspecialchars($data['search'] ?? ''); ?>"
                        placeholder="Cari berita...">
                      <button class="btn btn-outline-secondary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </form>

                  <?php if (!empty($data['berita'])): ?>
                    <button class="btn btn-info" onclick="window.location.href='index.php?controller=berita&action=export'">
                      <i class="mdi mdi-file-excel me-1"></i>Ekspor Excel
                    </button>
                  <?php endif; ?>

                  <button class="btn btn-primary" onclick="window.location.href='index.php?controller=berita&action=create'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Berita
                  </button>
                </div>
              </div>

              <!-- Table -->
              <?php if (!empty($data['berita'])): ?>
                <div class="table-responsive">
                  <table class="table table-hover" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <thead style="background: #4A90E2; color: white;">
                      <tr>
                        <th scope="col" style="padding: 15px; border: none; position: relative; width: 50px; text-align: center;">
                          NO
                          <i class="mdi mdi-menu-up float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; position: relative;">
                          JUDUL
                          <i class="mdi mdi-menu-up float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative; width: 120px;">
                          GAMBAR
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; position: relative;">
                          SUMMARY
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative; width: 120px;">
                          TANGGAL
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative; width: 150px;">
                          ACTION
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = ($data['currentPage'] - 1) * $data['limit'] + 1;
                      foreach ($data['berita'] as $berita):
                      ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                          <td style="padding: 15px; border: none; text-align: center; font-weight: 500;">
                            <?php echo $no++; ?>
                          </td>
                          <td style="padding: 15px; border: none;">
                            <div>
                              <strong style="color: #2c3e50;"><?php echo htmlspecialchars($berita['judul']); ?></strong>
                              <?php if (!empty($berita['url'])): ?>
                                <br>
                                <a href="<?php echo htmlspecialchars($berita['url']); ?>"
                                  target="_blank"
                                  class="text-primary"
                                  style="font-size: 12px; text-decoration: none;">
                                  <i class="mdi mdi-open-in-new"></i> Buka Link
                                </a>
                              <?php endif; ?>
                            </div>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php if (!empty($berita['image'])): ?>
                              <?php if (filter_var($berita['image'], FILTER_VALIDATE_URL)): ?>
                                <img src="<?php echo htmlspecialchars($berita['image']); ?>"
                                  alt="Gambar Berita"
                                  class="img-thumbnail"
                                  style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;">
                              <?php elseif (file_exists($berita['image'])): ?>
                                <img src="<?php echo htmlspecialchars($berita['image']); ?>"
                                  alt="Gambar Berita"
                                  class="img-thumbnail"
                                  style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;">
                              <?php else: ?>
                                <div style="width: 80px; height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 6px;">
                                  <i class="mdi mdi-image-off text-muted" style="font-size: 24px;"></i>
                                </div>
                              <?php endif; ?>
                            <?php else: ?>
                              <div style="width: 80px; height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 6px; margin: 0 auto;">
                                <i class="mdi mdi-image text-muted" style="font-size: 24px;"></i>
                              </div>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 15px; border: none;">
                            <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                              <?php
                              $summary = strip_tags($berita['summary']);
                              echo htmlspecialchars(strlen($summary) > 80 ? substr($summary, 0, 80) . '...' : $summary);
                              ?>
                            </div>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php
                            if (!empty($berita['created_at'])) {
                              $tanggal = new DateTime($berita['created_at']);
                              $bulan = [
                                1 => 'Jan',
                                2 => 'Feb',
                                3 => 'Mar',
                                4 => 'Apr',
                                5 => 'Mei',
                                6 => 'Jun',
                                7 => 'Jul',
                                8 => 'Agu',
                                9 => 'Sep',
                                10 => 'Okt',
                                11 => 'Nov',
                                12 => 'Des'
                              ];
                              echo $tanggal->format('d') . ' ' . $bulan[(int)$tanggal->format('n')] . ' ' . $tanggal->format('Y');
                            } else {
                              echo '-';
                            }
                            ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <div class="d-flex justify-content-center gap-1 flex-wrap">
                              <button class="btn btn-sm"
                                onclick="window.location.href='index.php?controller=berita&action=edit&id=<?php echo $berita['id_berita']; ?>'"
                                style="background: #ffc107; color: #212529; border: none; padding: 6px 12px; border-radius: 4px;"
                                title="Edit Data">
                                <i class="mdi mdi-pencil"></i> Edit
                              </button>
                              <button class="btn btn-sm"
                                onclick="confirmDelete(<?php echo $berita['id_berita']; ?>, '<?php echo addslashes($berita['judul']); ?>')"
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
                <?php if (isset($data['totalPages']) && $data['totalPages'] > 0): ?>
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                      Showing <?php echo (($data['currentPage'] - 1) * $data['limit']) + 1; ?> to <?php echo min($data['currentPage'] * $data['limit'], $data['totalCount']); ?> of <?php echo $data['totalCount']; ?> entries
                    </div>
                    <div class="d-flex">
                      <form method="GET" class="d-flex align-items-center">
                        <input type="hidden" name="controller" value="berita">
                        <input type="hidden" name="action" value="index">
                        <?php if (!empty($data['search'])): ?>
                          <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['search']); ?>">
                        <?php endif; ?>
                        <select name="limit" class="form-select form-select-sm me-2" style="width: 80px;" onchange="this.form.submit()">
                          <option value="10" <?php echo $data['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                          <option value="25" <?php echo $data['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                          <option value="50" <?php echo $data['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                          <option value="100" <?php echo $data['limit'] == 100 ? 'selected' : ''; ?>>100</option>
                        </select>
                      </form>
                      <span class="text-muted me-2">items per page</span>
                    </div>
                  </div>

                  <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="Page navigation" class="mt-3">
                      <ul class="pagination justify-content-center">
                        <!-- Previous Button -->
                        <?php if ($data['currentPage'] > 1): ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=berita&action=index&page=<?php echo $data['currentPage'] - 1; ?>&limit=<?php echo $data['limit']; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
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
                        $start_page = max(1, $data['currentPage'] - 2);
                        $end_page = min($data['totalPages'], $data['currentPage'] + 2);

                        // Show first page if not in range
                        if ($start_page > 1):
                        ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=berita&action=index&page=1&limit=<?php echo $data['limit']; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">1</a>
                          </li>
                          <?php if ($start_page > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                          <li class="page-item <?php echo $i == $data['currentPage'] ? 'active' : ''; ?>">
                            <a class="page-link" href="?controller=berita&action=index&page=<?php echo $i; ?>&limit=<?php echo $data['limit']; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
                              <?php echo $i; ?>
                            </a>
                          </li>
                        <?php endfor; ?>

                        <!-- Show last page if not in range -->
                        <?php if ($end_page < $data['totalPages']): ?>
                          <?php if ($end_page < $data['totalPages'] - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                          <?php endif; ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=berita&action=index&page=<?php echo $data['totalPages']; ?>&limit=<?php echo $data['limit']; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
                              <?php echo $data['totalPages']; ?>
                            </a>
                          </li>
                        <?php endif; ?>

                        <!-- Next Button -->
                        <?php if ($data['currentPage'] < $data['totalPages']): ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=berita&action=index&page=<?php echo $data['currentPage'] + 1; ?>&limit=<?php echo $data['limit']; ?><?php echo !empty($data['search']) ? '&search=' . urlencode($data['search']) : ''; ?>">
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
                  <i class="mdi mdi-newspaper" style="font-size: 64px; color: #ccc;"></i>
                  <h5 class="text-muted mt-3">
                    <?php if (!empty($data['search'])): ?>
                      Tidak ada berita yang ditemukan untuk pencarian "<?php echo htmlspecialchars($data['search']); ?>"
                    <?php else: ?>
                      Belum ada data berita
                    <?php endif; ?>
                  </h5>
                  <button class="btn btn-primary mt-2" onclick="window.location.href='index.php?controller=berita&action=create'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Berita Pertama
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
          <p>Apakah Anda yakin ingin menghapus berita "<span id="deleteName"></span>"?</p>
          <p class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Data yang sudah dihapus tidak dapat dikembalikan!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
            <i class="mdi mdi-delete me-1"></i>Ya, Hapus
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentBeritaId = null;

    function confirmDelete(id, name) {
      currentBeritaId = id;
      document.getElementById('deleteName').textContent = name;
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();
    }

    // Handle delete confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (currentBeritaId) {
        window.location.href = 'index.php?controller=berita&action=delete&id=' + currentBeritaId;
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

</body>

</html>