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
                  <?= htmlspecialchars($_SESSION['success']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
              <?php endif; ?>

              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?= htmlspecialchars($_SESSION['error']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <span style="font-size: 18px; font-weight: 500;">Semua Dokumen</span>
                </div>

                <div class="d-flex gap-2">
                  <!-- Search Form -->
                  <form method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="kategorisetiapsaat">
                    <input type="hidden" name="action" value="index">
                    <?php if (isset($pagination['limit'])): ?>
                      <input type="hidden" name="limit" value="<?= $pagination['limit']; ?>">
                    <?php endif; ?>
                    <div class="input-group" style="width: 300px;">
                      <input type="text"
                             class="form-control"
                             name="search"
                             value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>"
                             placeholder="Cari dokumen setiap saat...">
                      <button class="btn btn-outline-secondary" type="submit">
                        <i class="mdi mdi-magnify"></i>
                      </button>
                    </div>
                  </form>

                  <button class="btn btn-warning" onclick="window.location.href='index.php?controller=kategorisetiapsaat&action=draft'">
                    <i class="mdi mdi-file-document-edit me-1"></i>Draft (<?= $this->kategoriSetiapSaatModel->getTotalDraftCount(); ?>)
                  </button>

                  <?php if (!empty($dokumen_list)): ?>
                    <button class="btn btn-info" onclick="window.location.href='index.php?controller=kategorisetiapsaat&action=export'">
                      <i class="mdi mdi-file-excel me-1"></i>Ekspor Excel
                    </button>
                  <?php endif; ?>

                  <button class="btn btn-primary" onclick="window.location.href='index.php?controller=kategorisetiapsaat&action=create'">
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
                        <th scope="col" style="padding: 15px; border: none; position: relative;">
                          JUDUL DOKUMEN
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
                          ACTION
                          <i class="mdi mdi-swap-vertical float-end"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($dokumen_list as $dokumen): ?>
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                          <td style="padding: 15px; border: none;">
                            <div style="color: #4A90E2; font-weight: 500;">
                              <?= htmlspecialchars($dokumen['judul']); ?>
                            </div>
                            <?php if (!empty($dokumen['kandungan_informasi'])): ?>
                              <small class="text-muted">
                                <?= htmlspecialchars(strlen($dokumen['kandungan_informasi']) > 80 ?
                                     substr($dokumen['kandungan_informasi'], 0, 80) . '...' :
                                     $dokumen['kandungan_informasi']); ?>
                              </small>
                            <?php endif; ?>
                            <?php if (!empty($dokumen['nama_dokumen_pemda'])): ?>
                              <br><small class="text-info">Ref: <?= htmlspecialchars($dokumen['nama_dokumen_pemda']); ?></small>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?= htmlspecialchars($dokumen['terbitkan_sebagai']); ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php
                            $icon = 'mdi-file';
                            $color = '#6c757d';
                            switch($dokumen['tipe_file']) {
                                case 'audio': $icon = 'mdi-file-music'; $color = '#17a2b8'; break;
                                case 'video': $icon = 'mdi-file-video'; $color = '#dc3545'; break;
                                case 'text': $icon = 'mdi-file-document'; $color = '#007bff'; break;
                                case 'gambar': $icon = 'mdi-file-image'; $color = '#ffc107'; break;
                                case 'lainnya': $icon = 'mdi-file'; $color = '#6c757d'; break;
                            }
                            ?>
                            <i class="mdi <?= $icon; ?>" style="color: <?= $color; ?>; font-size: 16px;"></i>
                            <span class="text-capitalize ms-1"><?= htmlspecialchars($dokumen['tipe_file']); ?></span>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <?php if ($dokumen['status'] == 'publikasi'): ?>
                              <span class="badge bg-success">Publikasi</span>
                            <?php else: ?>
                              <span class="badge bg-warning text-dark">Draft</span>
                            <?php endif; ?>
                          </td>
                          <td style="padding: 15px; border: none; text-align: center;">
                            <div class="d-flex justify-content-center gap-1">
                              <button class="btn btn-sm"
                                      onclick="viewDetail(<?= $dokumen['id_dokumen']; ?>)"
                                      style="background: #17a2b8; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Lihat Detail">
                                <i class="mdi mdi-eye"></i> Detail
                              </button>
                              <button class="btn btn-sm"
                                      onclick="window.location.href='index.php?controller=kategorisetiapsaat&action=edit&id=<?= $dokumen['id_dokumen']; ?>'"
                                      style="background: #ffc107; color: #212529; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Edit Data">
                                <i class="mdi mdi-pencil"></i> Edit
                              </button>

                              <?php if ($dokumen['status'] == 'publikasi'): ?>
                                <button class="btn btn-sm"
                                        onclick="confirmDraftStatus(<?= $dokumen['id_dokumen']; ?>)"
                                        style="background: #fd7e14; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                        title="Ubah ke Draft">
                                  <i class="mdi mdi-file-document-edit"></i> Draft
                                </button>
                              <?php else: ?>
                                <button class="btn btn-sm"
                                        onclick="confirmPublishStatus(<?= $dokumen['id_dokumen']; ?>)"
                                        style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                        title="Publikasikan">
                                  <i class="mdi mdi-publish"></i> Publish
                                </button>
                              <?php endif; ?>

                              <button class="btn btn-sm"
                                      onclick="copyLink('<?= $dokumen['id_dokumen']; ?>')"
                                      style="background: #6f42c1; color: white; border: none; padding: 6px 12px; border-radius: 4px;"
                                      title="Copy Link">
                                <i class="mdi mdi-link"></i> Link
                              </button>
                              <button class="btn btn-sm"
                                      onclick="confirmDelete(<?= $dokumen['id_dokumen']; ?>, '<?= addslashes($dokumen['judul']); ?>')"
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
                    Showing <?= $pagination['start_record']; ?> to <?= $pagination['end_record']; ?> of <?= $pagination['total_records']; ?> entries
                  </div>
                  <div class="d-flex">
                    <form method="GET" class="d-flex align-items-center">
                      <input type="hidden" name="controller" value="kategorisetiapsaat">
                      <input type="hidden" name="action" value="index">
                      <?php if (!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']); ?>">
                      <?php endif; ?>
                      <select name="limit" class="form-select form-select-sm me-2" style="width: 80px;" onchange="this.form.submit()">
                        <option value="10" <?= ($pagination['limit'] ?? 10) == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="25" <?= ($pagination['limit'] ?? 10) == 25 ? 'selected' : ''; ?>>25</option>
                        <option value="50" <?= ($pagination['limit'] ?? 10) == 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?= ($pagination['limit'] ?? 10) == 100 ? 'selected' : ''; ?>>100</option>
                      </select>
                    </form>
                    <span class="text-muted me-2">items per page</span>
                  </div>
                </div>

                <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                <nav aria-label="Page navigation" class="mt-3">
                  <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <?php if (($pagination['current_page'] ?? 1) > 1): ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=kategorisetiapsaat&action=index&page=<?= $pagination['current_page'] - 1; ?>&limit=<?= $pagination['limit']; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
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
                    $start_page = max(1, ($pagination['current_page'] ?? 1) - 2);
                    $end_page = min(($pagination['total_pages'] ?? 1), ($pagination['current_page'] ?? 1) + 2);

                    // Show first page if not in range
                    if ($start_page > 1):
                    ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=kategorisetiapsaat&action=index&page=1&limit=<?= $pagination['limit']; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">1</a>
                      </li>
                      <?php if ($start_page > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                      <li class="page-item <?= $i == ($pagination['current_page'] ?? 1) ? 'active' : ''; ?>">
                        <a class="page-link" href="?controller=kategorisetiapsaat&action=index&page=<?= $i; ?>&limit=<?= $pagination['limit']; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                          <?= $i; ?>
                        </a>
                      </li>
                    <?php endfor; ?>

                    <!-- Show last page if not in range -->
                    <?php if ($end_page < ($pagination['total_pages'] ?? 1)): ?>
                      <?php if ($end_page < ($pagination['total_pages'] ?? 1) - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                      <?php endif; ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=kategorisetiapsaat&action=index&page=<?= $pagination['total_pages']; ?>&limit=<?= $pagination['limit']; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                          <?= $pagination['total_pages']; ?>
                        </a>
                      </li>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <?php if (($pagination['current_page'] ?? 1) < ($pagination['total_pages'] ?? 1)): ?>
                      <li class="page-item">
                        <a class="page-link" href="?controller=kategorisetiapsaat&action=index&page=<?= $pagination['current_page'] + 1; ?>&limit=<?= $pagination['limit']; ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
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
                  <i class="mdi mdi-clock-fast" style="font-size: 64px; color: #ccc;"></i>
                  <h5 class="text-muted mt-3">
                    <?php if (!empty($_GET['search'])): ?>
                      Tidak ada dokumen yang ditemukan untuk pencarian "<?= htmlspecialchars($_GET['search']); ?>"
                    <?php else: ?>
                      Belum ada data dokumen setiap saat
                    <?php endif; ?>
                  </h5>
                  <button class="btn btn-primary mt-2" onclick="window.location.href='index.php?controller=kategorisetiapsaat&action=create'">
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

  <!-- Modal Detail Dokumen -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: #4A90E2; color: white;">
          <h5 class="modal-title" id="detailModalLabel">
            <i class="mdi mdi-eye me-2"></i>Detail Dokumen Setiap Saat
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
          <p>Apakah Anda yakin ingin menghapus dokumen "<span id="deleteName"></span>"?</p>
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

  <!-- Modal Draft Status -->
  <div class="modal fade" id="draftModal" tabindex="-1" aria-labelledby="draftModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #fd7e14; color: white;">
          <h5 class="modal-title" id="draftModalLabel">
            <i class="mdi mdi-file-document-edit me-2"></i>Konfirmasi Ubah ke Draft
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mengubah status dokumen ini menjadi <strong>Draft</strong>?</p>
          <div class="alert alert-warning">
            <i class="mdi mdi-information me-2"></i>
            Dokumen akan disembunyikan dari publik dan dapat diedit kembali.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-warning" id="confirmDraftBtn">
            <i class="mdi mdi-file-document-edit me-1"></i>Ubah ke Draft
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Publish Status -->
  <div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: #28a745; color: white;">
          <h5 class="modal-title" id="publishModalLabel">
            <i class="mdi mdi-publish me-2"></i>Konfirmasi Publikasi
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mempublikasikan dokumen ini?</p>
          <div class="alert alert-success">
            <i class="mdi mdi-check-circle me-2"></i>
            Dokumen akan dapat diakses oleh publik setelah dipublikasikan.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="confirmPublishBtn">
            <i class="mdi mdi-publish me-1"></i>Publikasikan
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentDokumenId = null;
    let deleteId = null;
    let draftId = null;
    let publishId = null;

    function viewDetail(id) {
      currentDokumenId = id;
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
      fetch(`index.php?controller=kategorisetiapsaat&action=detail&id=${id}&ajax=1`)
        .then(response => response.json())
        .then(data => {
          const content = `
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-identifier me-1"></i>ID Dokumen
                    </h6>
                    <p class="card-text fw-bold">${data.id_dokumen}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-file-document me-1"></i>Judul
                    </h6>
                    <p class="card-text fw-bold">${data.judul}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-text me-1"></i>Kandungan Informasi
                    </h6>
                    <p class="card-text">${data.kandungan_informasi || '<em class="text-muted">Tidak ada data</em>'}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-publish me-1"></i>Terbitkan Sebagai
                    </h6>
                    <p class="card-text">${data.terbitkan_sebagai}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-file me-1"></i>Tipe File
                    </h6>
                    <p class="card-text text-capitalize">${data.tipe_file}</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-toggle-switch me-1"></i>Status
                    </h6>
                    <p class="card-text">
                      ${data.status === 'publikasi' ?
                        '<span class="badge bg-success">Publikasi</span>' :
                        '<span class="badge bg-warning text-dark">Draft</span>'}
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-calendar me-1"></i>Tanggal Dibuat
                    </h6>
                    <p class="card-text">${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                  </div>
                </div>
              </div>
              ${data.upload_file ? `
              <div class="col-md-12">
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title text-primary mb-2">
                      <i class="mdi mdi-file-download me-1"></i>File
                    </h6>
                    <a href="${data.upload_file}" target="_blank" class="btn btn-sm btn-outline-primary">
                      <i class="mdi mdi-download me-1"></i>Download File
                    </a>
                  </div>
                </div>
              </div>` : ''}
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
      deleteId = id;
      document.getElementById('deleteName').textContent = name;
      const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
      modal.show();
    }

    function confirmDraftStatus(id) {
      draftId = id;
      const modal = new bootstrap.Modal(document.getElementById('draftModal'));
      modal.show();
    }

    function confirmPublishStatus(id) {
      publishId = id;
      const modal = new bootstrap.Modal(document.getElementById('publishModal'));
      modal.show();
    }

    function copyLink(id) {
      const link = `${window.location.origin}${window.location.pathname}?controller=kategorisetiapsaat&action=view&id=${id}`;
      navigator.clipboard.writeText(link).then(() => {
        // Show success alert
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
        setTimeout(() => {
          const alert = new bootstrap.Alert(alertDiv);
          alert.close();
        }, 3000);
      }).catch(() => {
        alert('Gagal menyalin link');
      });
    }

    // Edit from detail modal
    document.getElementById('editFromDetail').addEventListener('click', function() {
      if (currentDokumenId) {
        window.location.href = `index.php?controller=kategorisetiapsaat&action=edit&id=${currentDokumenId}`;
      }
    });

    // Delete confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (deleteId) {
        window.location.href = `index.php?controller=kategorisetiapsaat&action=destroy&id=${deleteId}`;
      }
    });

    // Draft status change
    document.getElementById('confirmDraftBtn').addEventListener('click', function() {
      if (draftId) {
        const formData = new FormData();
        formData.append('id', draftId);

        fetch('index.php?controller=kategorisetiapsaat&action=updateToDraft', {
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

    // Publish status change
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