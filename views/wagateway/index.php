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
                  <h3 class="font-weight-bold">Data Pesan</h3>
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

          <!-- Statistics Cards -->
          <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted">Total Pesan</p>
                      <h3 class="mb-2 number-font"><?= $stats['total'] ?? 0 ?></h3>
                      <p class="text-muted mb-0 font-weight-medium small">
                        <i class="ti-arrow-up"></i>
                        Semua pesan
                      </p>
                    </div>
                    <div class="ms-auto">
                      <i class="ti-email icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted">Terkirim</p>
                      <h3 class="mb-2 number-font"><?= $stats['terkirim'] ?? 0 ?></h3>
                      <p class="text-muted mb-0 font-weight-medium small">
                        <i class="ti-check"></i>
                        Berhasil dikirim
                      </p>
                    </div>
                    <div class="ms-auto">
                      <i class="ti-check-box icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted">Terjadwal</p>
                      <h3 class="mb-2 number-font"><?= $stats['terjadwal'] ?? 0 ?></h3>
                      <p class="text-muted mb-0 font-weight-medium small">
                        <i class="ti-time"></i>
                        Menunggu kirim
                      </p>
                    </div>
                    <div class="ms-auto">
                      <i class="ti-time icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex">
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted">Hari Ini</p>
                      <h3 class="mb-2 number-font"><?= $stats['today'] ?? 0 ?></h3>
                      <p class="text-muted mb-0 font-weight-medium small">
                        <i class="ti-calendar"></i>
                        Pesan hari ini
                      </p>
                    </div>
                    <div class="ms-auto">
                      <i class="ti-calendar icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="row">
            <div class="col-md-12 mb-4">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h4 class="card-title text-dark">Aksi Cepat</h4>
                  <div class="row justify-content-center">
                    <div class="col-md-3 mb-2">
                      <a href="index.php?controller=wagateway&action=pesan_keluar" class="btn btn-primary btn-block">
                        <i class="ti-email me-2"></i>
                        Kirim Pesan
                      </a>
                    </div>
                    <div class="col-md-3 mb-2">
                      <a href="index.php?controller=wagateway&action=draft" class="btn btn-secondary btn-block">
                        <i class="ti-pencil me-2"></i>
                        Draft Pesan
                      </a>
                    </div>
                    <div class="col-md-3 mb-2">
                      <a href="index.php?controller=wagateway&action=arsip" class="btn btn-info btn-block">
                        <i class="ti-archive me-2"></i>
                        Arsip
                      </a>
                    </div>
                    <div class="col-md-3 mb-2">
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Messages -->
          <div class="row">
            <div class="col-md-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h4 class="card-title text-dark">Pesan Terbaru</h4>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr class="text-muted">
                          <th>No. Tujuan</th>
                          <th>Pesan</th>
                          <th>Tanggal Kirim</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($recentMessages)): ?>
                          <?php foreach($recentMessages as $message): ?>
                            <tr>
                              <td><?= htmlspecialchars($message['no_tujuan']) ?></td>
                              <td>
                                <div class="message-preview text-dark">
                                  <?= htmlspecialchars(substr($message['pesan'], 0, 50)) ?>
                                  <?= strlen($message['pesan']) > 50 ? '...' : '' ?>
                                </div>
                              </td>
                              <td class="text-muted"><?= date('d/m/Y H:i', strtotime($message['tanggal_kirim'])) ?></td>
                              <td>
                                <?php
                                $badgeClass = 'badge bg-light text-dark';
                                $statusText = ucfirst($message['status']);
                                switch($message['status']) {
                                  case 'terkirim':
                                    $badgeClass = 'badge bg-success text-white';
                                    $statusText = 'Terkirim';
                                    break;
                                  case 'terjadwal':
                                    $badgeClass = 'badge bg-warning text-dark';
                                    $statusText = 'Terjadwal';
                                    break;
                                  case 'arsip':
                                    $badgeClass = 'badge bg-info text-white';
                                    $statusText = 'Arsip';
                                    break;
                                  case 'gagal':
                                    $badgeClass = 'badge bg-danger text-white';
                                    $statusText = 'Gagal';
                                    break;
                                }
                                ?>
                                <span class="<?= $badgeClass ?>"><?= $statusText ?></span>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <?php if($message['status'] === 'terjadwal'): ?>
                                    <form method="POST" action="index.php?controller=wagateway&action=draft" style="display:inline;">
                                      <input type="hidden" name="action" value="send_draft">
                                      <input type="hidden" name="id" value="<?= $message['id_wagateway'] ?>">
                                      <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Kirim pesan sekarang?')">
                                        <i class="ti-check"></i>
                                      </button>
                                    </form>
                                  <?php endif; ?>

                                  <?php if($message['status'] === 'terkirim'): ?>
                                    <form method="POST" action="index.php?controller=wagateway&action=pesan_keluar" style="display:inline;">
                                      <input type="hidden" name="action" value="archive_message">
                                      <input type="hidden" name="id" value="<?= $message['id_wagateway'] ?>">
                                      <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Arsipkan pesan ini?')">
                                        <i class="ti-archive"></i>
                                      </button>
                                    </form>
                                  <?php endif; ?>

                                  <button class="btn btn-sm btn-primary" onclick="viewMessage(<?= $message['id_wagateway'] ?>)">
                                    <i class="ti-eye"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pesan</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Message Detail Modal -->
  <div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Pesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="messageDetail">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    function viewMessage(id) {
      $('#messageModal').modal('show');
      $('#messageDetail').html('<div class="text-center"><div class="spinner-border"></div><p class="mt-2">Loading...</p></div>');

      $.ajax({
        url: 'index.php?controller=wagateway&action=getMessageDetail',
        method: 'POST',
        data: { id: id },
        success: function(response) {
          if(response.success) {
            const message = response.data;
            $('#messageDetail').html(`
              <div class="row">
                <div class="col-md-12">
                  <h6 class="mb-3">Detail Pesan #${message.id_wagateway}</h6>

                  <div class="card">
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col-sm-4"><strong>Nomor Tujuan:</strong></div>
                        <div class="col-sm-8">${message.no_tujuan}</div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-4"><strong>Status:</strong></div>
                        <div class="col-sm-8">
                          <span class="badge badge-light">${message.status.charAt(0).toUpperCase() + message.status.slice(1)}</span>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-4"><strong>Tanggal Kirim:</strong></div>
                        <div class="col-sm-8">${new Date(message.tanggal_kirim).toLocaleString('id-ID')}</div>
                      </div>

                      <div class="mb-3">
                        <strong>Pesan:</strong>
                        <div class="mt-2 p-3 border rounded bg-light">
                          ${message.pesan.replace(/\n/g, '<br>')}
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4"><strong>Panjang Pesan:</strong></div>
                        <div class="col-sm-8">${message.pesan.length} karakter</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `);
          } else {
            $('#messageDetail').html(`
              <div class="alert alert-danger">
                <i class="ti-alert-circle"></i> ${response.message}
              </div>
            `);
          }
        },
        error: function() {
          $('#messageDetail').html(`
            <div class="alert alert-danger">
              <i class="ti-alert-circle"></i> Gagal memuat detail pesan
            </div>
          `);
        }
      });
    }

    $('#checkConnection').click(function() {
      $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Checking...');

      $.post('index.php?controller=wagateway&action=testConnection', function(response) {
        if(response.success) {
          alert('WhatsApp terhubung dengan baik!\n\nDetail: ' + JSON.stringify(response.device_info, null, 2));
        } else {
          alert('Gagal terhubung ke WhatsApp:\n' + response.message + '\n\nDetail: ' + JSON.stringify(response.details, null, 2));
        }
      }).fail(function(xhr, status, error) {
        alert('Error checking connection: ' + error);
      }).always(function() {
        $('#checkConnection').prop('disabled', false).html('<i class="ti-mobile me-2"></i>Cek Koneksi WA');
      });
    });

    // Auto refresh stats every 30 seconds
    setInterval(function() {
      $.get('index.php?controller=wagateway&action=getMessageStats', function(stats) {
        $('.number-font').each(function(index) {
          const keys = ['total', 'terkirim', 'terjadwal', 'today'];
          $(this).text(stats[keys[index]] || 0);
        });
      });
    }, 30000);
  </script>

  <style>
    .message-preview {
      max-width: 300px;
      word-wrap: break-word;
    }

    .bg-light {
      background-color: #f8f9fa !important;
    }

    .text-muted {
      color: #6c757d !important;
    }

    .btn-block {
      width: 100%;
    }

    .badge {
      font-size: 0.75em;
      padding: 0.3em 0.6em;
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

    @media (max-width: 768px) {
      .col-lg-3 {
        margin-bottom: 1rem;
      }
    }
  </style>
</body>
</html>