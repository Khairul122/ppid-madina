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
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <span style="font-size: 18px; font-weight: 500;">Semua Dokumen</span>
                </div>

                <div class="d-flex gap-2">
                  <!-- Search Form -->
                  <form method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="kategoriberkala">
                    <input type="hidden" name="action" value="index">
                    <?php if (isset($pagination['limit'])): ?>
                      <input type="hidden" name="limit" value="<?php echo $pagination['limit']; ?>">
                    <?php endif; ?>
                    <div class="input-group" style="width: 300px;">
                      <input type="text"
                             class="form-control"
                             name="search"
                             value="<?php echo htmlspecialchars($search); ?>"
                             placeholder="Cari dokumen berkala...">
                      <button class="btn btn-outline-secondary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </form>

                  <button class="btn btn-warning" onclick="window.location.href='index.php?controller=kategoriberkala&action=draft'">
                    <i class="mdi mdi-file-document-edit me-1"></i>Draft
                  </button>

                  <?php if (!empty($dokumen_list)): ?>
                    <button class="btn btn-info" onclick="window.location.href='index.php?controller=kategoriberkala&action=export'">
                      <i class="mdi mdi-file-excel me-1"></i>Ekspor Excel
                    </button>
                  <?php endif; ?>

                  <button class="btn btn-primary" onclick="window.location.href='index.php?controller=kategoriberkala&action=create'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Dokumen
                  </button>
                </div>
              </div>

              <!-- Table -->
              <?php if (!empty($dokumen_list)): ?>
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
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          TERBITKAN SEBAGAI
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          TIPE FILE
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          STATUS
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          TANGGAL BUAT
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                        <th scope="col" style="padding: 15px; border: none; text-align: center; position: relative;">
                          ACTION
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = ($pagination['current_page'] - 1) * $pagination['limit'] + 1;
                      foreach ($dokumen_list as $dokumen):
                      ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                          <td style="padding: 15px; border: none; text-align: center; font-weight: 500;">
                            <?php echo $no++; ?>
                          </td>
                          <td style="padding: 15px; border: none;">
                            <a href="#" style="color: #4A90E2; text-decoration: none;" onclick="copyLink(<?php echo $dokumen['id_dokumen']; ?>)">
                              <?php echo htmlspecialchars($dokumen['judul']); ?>
                            </a>
                            <?php if (!empty($dokumen['kandungan_informasi'])): ?>
                              <br><small class="text-muted"><?php echo substr(strip_tags($dokumen['kandungan_informasi']), 0, 100) . '...'; ?></small>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php echo isset($dokumen['terbitkan_sebagai']) ? htmlspecialchars($dokumen['terbitkan_sebagai']) : '-'; ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php
                            $tipe_icons = [
                                'audio' => '<i class="mdi mdi-volume-high text-success"></i> Audio',
                                'video' => '<i class="mdi mdi-video text-danger"></i> Video',
                                'text' => '<i class="mdi mdi-file-document text-primary"></i> Text',
                                'gambar' => '<i class="mdi mdi-image text-warning"></i> Gambar',
                                'lainnya' => '<i class="mdi mdi-file text-secondary"></i> Lainnya'
                            ];
                            echo $tipe_icons[$dokumen['tipe_file']] ?? $dokumen['tipe_file'];
                            ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php if ($dokumen['status'] === 'publikasi'): ?>
                              <span class="badge bg-success">Publikasi</span>
                            <?php else: ?>
                              <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php
                            if (!empty($dokumen['created_at'])) {
                                $tanggal = new DateTime($dokumen['created_at']);
                                $bulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
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
                                      onclick="window.location.href='index.php?controller=kategoriberkala&action=edit&id=<?php echo $dokumen['id_dokumen']; ?>'"
                                      style="background: #ffc107; color: #212529; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Edit Data">
                                <i class="mdi mdi-pencil"></i> Edit
                              </button>
                              <?php if ($dokumen['status'] === 'publikasi'): ?>
                                <button class="btn btn-sm"
                                        onclick="confirmDraft(<?php echo $dokumen['id_dokumen']; ?>, '<?php echo addslashes($dokumen['judul']); ?>')"
                                        style="background: #fd7e14; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                        title="Ubah ke Draft">
                                  <i class="mdi mdi-file-document-edit"></i> Draft
                                </button>
                              <?php endif; ?>
                              <button class="btn btn-sm"
                                      onclick="copyLink(<?php echo $dokumen['id_dokumen']; ?>)"
                                      style="background: #6f42c1; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Copy Link">
                                <i class="mdi mdi-link"></i> Link
                              </button>
                              <button class="btn btn-sm"
                                      onclick="confirmDelete(<?php echo $dokumen['id_dokumen']; ?>, '<?php echo addslashes($dokumen['judul']); ?>')"
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
                      <input type="hidden" name="controller" value="kategoriberkala">
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
                        <a class="page-link" href="?controller=kategoriberkala&action=index&page=<?php echo $pagination['current_page'] - 1; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
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
                        <a class="page-link" href="?controller=kategoriberkala&action=index&page=1&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">1</a>
                      </li>
                      <?php if ($start_page > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                      <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                        <a class="page-link" href="?controller=kategoriberkala&action=index&page=<?php echo $i; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
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
                        <a class="page-link" href="?controller=kategoriberkala&action=index&page=<?php echo $pagination['total_pages']; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                          <?php echo $pagination['total_pages']; ?>
                        </a>
                      </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=kategoriberkala&action=index&page=<?php echo $pagination['current_page'] + 1; ?>&limit=<?php echo $pagination['limit']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
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
                  <i class="mdi mdi-folder-clock-outline" style="font-size: 64px; color: #ccc;"></i>
                  <h5 class="text-muted mt-3">
                    <?php if (!empty($search)): ?>
                      Tidak ada dokumen berkala yang ditemukan untuk pencarian "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                      Belum ada data dokumen berkala
                    <?php endif; ?>
                  </h5>
                  <button class="btn btn-primary mt-2" onclick="window.location.href='index.php?controller=kategoriberkala&action=create'">
                    <i class="mdi mdi-plus me-1"></i>Tambah Dokumen Pertama
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

  <!-- Modal Konfirmasi Draft -->
  <div class="modal fade" id="draftModal" tabindex="-1" aria-labelledby="draftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="draftModalLabel">
            <i class="mdi mdi-file-document-edit me-2"></i>Konfirmasi Ubah ke Draft
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mengubah status dokumen "<span id="draftName"></span>" menjadi draft?</p>
          <p class="text-warning"><i class="mdi mdi-alert me-1"></i>Dokumen akan tidak terlihat oleh publik!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-warning" id="confirmDraftBtn">
            <i class="mdi mdi-file-document-edit me-1"></i>Ya, Ubah ke Draft
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
          <p>Apakah Anda yakin ingin menghapus Dokumen "<span id="deleteName"></span>"?</p>
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
    let currentDokumenId = null;

    function confirmDraft(id, name) {
      currentDokumenId = id;
      document.getElementById('draftName').textContent = name;
      const modal = new bootstrap.Modal(document.getElementById('draftModal'));
      modal.show();
    }

    function confirmDelete(id, name) {
      currentDokumenId = id;
      document.getElementById('deleteName').textContent = name;
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();
    }

    function copyLink(id) {
      // Create a link to the document - adjust URL as needed
      const link = window.location.origin + '/index.php?controller=kategoriberkala&action=view&id=' + id;

      // Copy to clipboard
      navigator.clipboard.writeText(link).then(function() {
        // Show success toast
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
          <i class="mdi mdi-check-circle me-2"></i>
          Link berhasil disalin ke clipboard!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const container = document.querySelector('.col-sm-12');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-hide after 3 seconds
        setTimeout(function() {
          const alert = new bootstrap.Alert(alertDiv);
          alert.close();
        }, 3000);
      }).catch(function(err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = link;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        alert('Link disalin: ' + link);
      });
    }

    // Handle draft confirmation
    document.getElementById('confirmDraftBtn').addEventListener('click', function() {
      if (currentDokumenId) {
        const formData = new FormData();
        formData.append('id', currentDokumenId);

        fetch('index.php?controller=kategoriberkala&action=updateToDraft', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          alert('Terjadi kesalahan: ' + error);
        });

        bootstrap.Modal.getInstance(document.getElementById('draftModal')).hide();
      }
    });

    // Handle delete confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (currentDokumenId) {
        fetch('index.php?controller=kategoriberkala&action=destroy&id=' + currentDokumenId, {
          method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          alert('Terjadi kesalahan: ' + error);
        });

        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
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