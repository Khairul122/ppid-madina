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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Arsip Pesan</h3>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="justify-content-end d-flex">
                    <a href="index.php?controller=wagateway&action=index" class="btn btn-sm btn-secondary me-2">
                      <i class="ti-arrow-left me-1"></i>
                      Kembali
                    </a>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ti-settings me-1"></i>
                        Aksi Massal
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="restoreSelected()">
                          <i class="ti-back-left me-2"></i>Pulihkan Terpilih
                        </a></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSelectedPermanent()">
                          <i class="ti-trash me-2"></i>Hapus Permanen
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="exportArchives()">
                          <i class="ti-download me-2"></i>Export Arsip
                        </a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Alert Messages -->
          <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?= $_SESSION['success']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
          <?php endif; ?>

          <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?= $_SESSION['error']; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <!-- Archive Statistics -->
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="mb-0"><?= count($archives) ?></h4>
                      <p class="mb-0 text-muted">Total Arsip</p>
                    </div>
                    <div>
                      <i class="ti-archive icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="mb-0" id="todayArchives">0</h4>
                      <p class="mb-0 text-muted">Diarsip Hari Ini</p>
                    </div>
                    <div>
                      <i class="ti-calendar icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="mb-0" id="selectedArchives">0</h4>
                      <p class="mb-0 text-muted">Dipilih</p>
                    </div>
                    <div>
                      <i class="ti-check-box icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h4 class="mb-0"><?php
                        $weekAgo = date('Y-m-d', strtotime('-7 days'));
                        $oldCount = 0;
                        foreach($archives as $archive) {
                          if(date('Y-m-d', strtotime($archive['tanggal_kirim'])) < $weekAgo) {
                            $oldCount++;
                          }
                        }
                        echo $oldCount;
                      ?></h4>
                      <p class="mb-0 text-muted">Lebih dari 7 hari</p>
                    </div>
                    <div>
                      <i class="ti-time icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Filter and Search -->
          <div class="row mb-4">
            <div class="col-md-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <label class="form-label text-dark">Pencarian</label>
                      <input type="text" class="form-control" id="searchInput" placeholder="Cari nomor atau pesan...">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label text-dark">Filter Tanggal</label>
                      <select class="form-control" id="dateFilter">
                        <option value="">Semua Tanggal</option>
                        <option value="today">Hari Ini</option>
                        <option value="yesterday">Kemarin</option>
                        <option value="week">7 Hari Terakhir</option>
                        <option value="month">30 Hari Terakhir</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label text-dark">Urutkan</label>
                      <select class="form-control" id="sortOrder">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="number">Nomor A-Z</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">&nbsp;</label>
                      <div class="d-flex">
                        <button class="btn btn-primary me-2" onclick="applyFilters()">
                          <i class="ti-search"></i> Filter
                        </button>
                        <button class="btn btn-secondary" onclick="resetFilters()">
                          <i class="ti-reload"></i> Reset
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Archives List -->
          <div class="row">
            <div class="col-md-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0 text-dark">Daftar Arsip Pesan</h4>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                        <i class="ti-check-box"></i> Pilih Semua
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="ti-close"></i> Batal Pilih
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-info" onclick="refreshArchives()">
                        <i class="ti-reload"></i> Refresh
                      </button>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-hover" id="archivesTable">
                      <thead class="table-light">
                        <tr class="text-muted">
                          <th width="5%">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                            </div>
                          </th>
                          <th width="5%">#</th>
                          <th width="15%">No. Tujuan</th>
                          <th width="35%">Pesan</th>
                          <th width="15%">Tanggal Kirim</th>
                          <th width="10%">Durasi Arsip</th>
                          <th width="15%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody id="archivesTableBody">
                        <?php if(!empty($archives)): ?>
                          <?php foreach($archives as $index => $archive): ?>
                            <tr class="archive-row" data-date="<?= $archive['tanggal_kirim'] ?>" data-number="<?= htmlspecialchars($archive['no_tujuan']) ?>" data-message="<?= htmlspecialchars(strtolower($archive['pesan'])) ?>">
                              <td>
                                <div class="form-check">
                                  <input class="form-check-input archive-checkbox" type="checkbox" value="<?= $archive['id_wagateway'] ?>">
                                </div>
                              </td>
                              <td><?= $index + 1 ?></td>
                              <td><?= htmlspecialchars($archive['no_tujuan']) ?></td>
                              <td>
                                <div class="message-preview text-dark" title="<?= htmlspecialchars($archive['pesan']) ?>">
                                  <?= htmlspecialchars(substr($archive['pesan'], 0, 100)) ?>
                                  <?= strlen($archive['pesan']) > 100 ? '...' : '' ?>
                                </div>
                              </td>
                              <td class="text-muted"><?= date('d/m/Y H:i', strtotime($archive['tanggal_kirim'])) ?></td>
                              <td class="text-muted">
                                <?php
                                $archiveDate = new DateTime($archive['tanggal_kirim']);
                                $now = new DateTime();
                                $diff = $now->diff($archiveDate);

                                if($diff->days == 0) {
                                  echo 'Hari ini';
                                } elseif($diff->days == 1) {
                                  echo 'Kemarin';
                                } elseif($diff->days < 7) {
                                  echo $diff->days . ' hari';
                                } elseif($diff->days < 30) {
                                  echo ceil($diff->days / 7) . ' minggu';
                                } else {
                                  echo ceil($diff->days / 30) . ' bulan';
                                }
                                ?>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <button class="btn btn-sm btn-primary" onclick="viewArchiveDetail(<?= $archive['id_wagateway'] ?>)" title="Lihat Detail">
                                    <i class="ti-eye"></i>
                                  </button>

                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="restore_message">
                                    <input type="hidden" name="id" value="<?= $archive['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Pulihkan pesan ini ke daftar terkirim?')" title="Pulihkan">
                                      <i class="ti-back-left"></i>
                                    </button>
                                  </form>

                                  <button class="btn btn-sm btn-info" onclick="duplicateMessage(<?= $archive['id_wagateway'] ?>)" title="Kirim Ulang">
                                    <i class="ti-control-forward"></i>
                                  </button>

                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_permanent">
                                    <input type="hidden" name="id" value="<?= $archive['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesan ini secara permanen? Aksi ini tidak dapat dibatalkan!')" title="Hapus Permanen">
                                      <i class="ti-trash"></i>
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada pesan yang diarsipkan</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                      Menampilkan <span id="showingCount"><?= count($archives) ?></span> dari <span id="totalCount"><?= count($archives) ?></span> arsip
                    </div>
                    <nav>
                      <ul class="pagination pagination-sm mb-0" id="pagination">
                        <!-- Pagination will be generated by JavaScript -->
                      </ul>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Archive Detail Modal -->
  <div class="modal fade" id="archiveDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Arsip</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="archiveDetailContent">
            <div class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="restoreFromModal">
            <i class="ti-back-left"></i> Pulihkan
          </button>
          <button type="button" class="btn btn-info" id="resendFromModal">
            <i class="ti-control-forward"></i> Kirim Ulang
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p id="confirmationMessage"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="confirmButton">Konfirmasi</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Calculate today's archives
    let todayCount = 0;
    <?php foreach($archives as $archive): ?>
      if('<?= date('Y-m-d', strtotime($archive['tanggal_kirim'])) ?>' === '<?= date('Y-m-d') ?>') {
        todayCount++;
      }
    <?php endforeach; ?>
    $('#todayArchives').text(todayCount);

    // Checkbox functionality
    function updateSelectedCount() {
      const count = $('.archive-checkbox:checked').length;
      $('#selectedArchives').text(count);
    }

    $('.archive-checkbox').change(updateSelectedCount);

    $('#selectAllCheckbox').change(function() {
      $('.archive-checkbox').prop('checked', $(this).prop('checked'));
      updateSelectedCount();
    });

    function selectAll() {
      $('.archive-checkbox, #selectAllCheckbox').prop('checked', true);
      updateSelectedCount();
    }

    function clearSelection() {
      $('.archive-checkbox, #selectAllCheckbox').prop('checked', false);
      updateSelectedCount();
    }

    // Filter and search functionality
    function applyFilters() {
      const searchTerm = $('#searchInput').val().toLowerCase();
      const dateFilter = $('#dateFilter').val();
      const sortOrder = $('#sortOrder').val();

      let rows = $('.archive-row');

      // Apply search filter
      if(searchTerm) {
        rows.each(function() {
          const number = $(this).data('number').toString().toLowerCase();
          const message = $(this).data('message');
          const visible = number.includes(searchTerm) || message.includes(searchTerm);
          $(this).toggle(visible);
        });
        rows = $('.archive-row:visible');
      }

      // Apply date filter
      if(dateFilter) {
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];

        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayStr = yesterday.toISOString().split('T')[0];

        const weekAgo = new Date(today);
        weekAgo.setDate(weekAgo.getDate() - 7);

        const monthAgo = new Date(today);
        monthAgo.setDate(monthAgo.getDate() - 30);

        rows.each(function() {
          const rowDate = new Date($(this).data('date'));
          const rowDateStr = rowDate.toISOString().split('T')[0];
          let visible = true;

          switch(dateFilter) {
            case 'today':
              visible = rowDateStr === todayStr;
              break;
            case 'yesterday':
              visible = rowDateStr === yesterdayStr;
              break;
            case 'week':
              visible = rowDate >= weekAgo;
              break;
            case 'month':
              visible = rowDate >= monthAgo;
              break;
          }

          if($(this).is(':visible')) {
            $(this).toggle(visible);
          }
        });
      }

      // Apply sorting
      const visibleRows = $('.archive-row:visible').get();
      visibleRows.sort(function(a, b) {
        switch(sortOrder) {
          case 'newest':
            return new Date($(b).data('date')) - new Date($(a).data('date'));
          case 'oldest':
            return new Date($(a).data('date')) - new Date($(b).data('date'));
          case 'number':
            return $(a).data('number').localeCompare($(b).data('number'));
          default:
            return 0;
        }
      });

      $('#archivesTableBody').append(visibleRows);
      updateCounts();
    }

    function resetFilters() {
      $('#searchInput').val('');
      $('#dateFilter').val('');
      $('#sortOrder').val('newest');
      $('.archive-row').show();
      applyFilters();
    }

    function updateCounts() {
      const visible = $('.archive-row:visible').length;
      const total = $('.archive-row').length;
      $('#showingCount').text(visible);
      $('#totalCount').text(total);
    }

    // Mass actions
    function restoreSelected() {
      const selected = $('.archive-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      if(selected.length === 0) {
        alert('Pilih arsip yang akan dipulihkan');
        return;
      }

      if(confirm(`Pulihkan ${selected.length} arsip yang dipilih?`)) {
        selected.forEach(id => {
          const form = $('<form method="POST">');
          form.append('<input type="hidden" name="action" value="restore_message">');
          form.append('<input type="hidden" name="id" value="' + id + '">');
          $('body').append(form);
          form.submit();
        });
      }
    }

    function deleteSelectedPermanent() {
      const selected = $('.archive-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      if(selected.length === 0) {
        alert('Pilih arsip yang akan dihapus permanen');
        return;
      }

      $('#confirmationMessage').text(`Hapus ${selected.length} arsip secara permanen? Aksi ini tidak dapat dibatalkan!`);
      $('#confirmationModal').modal('show');

      $('#confirmButton').off('click').on('click', function() {
        selected.forEach(id => {
          const form = $('<form method="POST">');
          form.append('<input type="hidden" name="action" value="delete_permanent">');
          form.append('<input type="hidden" name="id" value="' + id + '">');
          $('body').append(form);
          form.submit();
        });
        $('#confirmationModal').modal('hide');
      });
    }

    function exportArchives() {
      // Implement export functionality
      alert('Fitur export akan segera tersedia');
    }

    function viewArchiveDetail(id) {
      $('#archiveDetailModal').modal('show');
      // Implement AJAX call to get archive details
      setTimeout(() => {
        $('#archiveDetailContent').html(`
          <div class="row">
            <div class="col-md-12">
              <h6>Detail Arsip #${id}</h6>
              <p>Loading archive details...</p>
            </div>
          </div>
        `);
      }, 500);
    }

    function duplicateMessage(id) {
      if(confirm('Kirim ulang pesan ini?')) {
        // Implement message duplication and resend
        alert('Fitur kirim ulang akan segera tersedia');
      }
    }

    function refreshArchives() {
      location.reload();
    }

    // Initialize
    updateCounts();
  </script>

  <style>
    .message-preview {
      max-width: 350px;
      word-wrap: break-word;
      cursor: pointer;
    }

    .card {
      border: 1px solid #e9ecef;
      transition: box-shadow 0.15s ease;
    }

    .card:hover {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    .table {
      color: #495057;
    }

    .table th {
      border-top: 0;
      color: #495057;
      font-weight: 600;
    }

    .table td {
      vertical-align: middle;
    }

    .btn {
      border-radius: 0.375rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
    }

    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
    }

    .btn-secondary:hover {
      background-color: #5c636a;
      border-color: #565e64;
    }

    .btn-success {
      background-color: #198754;
      border-color: #198754;
    }

    .btn-success:hover {
      background-color: #157347;
      border-color: #146c43;
    }

    .btn-info {
      background-color: #0dcaf0;
      border-color: #0dcaf0;
      color: #000;
    }

    .btn-info:hover {
      background-color: #31d2f2;
      border-color: #25cff2;
      color: #000;
    }

    .btn-warning {
      background-color: #ffc107;
      border-color: #ffc107;
      color: #000;
    }

    .btn-warning:hover {
      background-color: #ffca2c;
      border-color: #ffc720;
      color: #000;
    }

    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
    }

    .btn-danger:hover {
      background-color: #bb2d3b;
      border-color: #b02a37;
    }

    .btn-group .btn {
      margin: 0 1px;
    }

    .archive-row {
      transition: background-color 0.2s;
    }

    .archive-row:hover {
      background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
      .col-md-3 {
        margin-bottom: 1rem;
      }

      .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
      }

      .table-responsive {
        font-size: 0.875rem;
      }

      .message-preview {
        max-width: 200px;
      }
    }

    .pagination-sm .page-link {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
  </style>
</body>
</html>