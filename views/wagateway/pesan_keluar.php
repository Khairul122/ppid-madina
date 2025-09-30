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
                  <h3 class="font-weight-bold">Pesan Keluar</h3>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="justify-content-end d-flex">
                    <a href="index.php?controller=wagateway&action=index" class="btn btn-sm btn-secondary">
                      <i class="ti-arrow-left me-1"></i>
                      Kembali
                    </a>
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

          <div class="row">
            <!-- Send Message Form -->
            <div class="col-md-12 mb-4">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <h4 class="card-title text-dark">Kirim Pesan WhatsApp</h4>
                  <form method="POST" id="singleMessageForm">
                    <input type="hidden" name="action" value="send_message">

                    <div class="mb-3">
                      <label class="form-label text-dark">Nomor Tujuan</label>
                      <input type="text" class="form-control" name="no_tujuan" placeholder="628xxxxxxxxxx" required>
                      <small class="form-text text-muted">Format: 628xxxxxxxxxx (tanpa +)</small>
                    </div>

                    <div class="mb-3">
                      <label class="form-label text-dark">Pesan</label>
                      <textarea class="form-control" name="pesan" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                      <small class="form-text text-muted">
                        <span id="charCount">0</span>/1000 karakter
                      </small>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">
                      <i class="ti-check me-1"></i>
                      Kirim Sekarang
                    </button>

                    <button type="button" class="btn btn-secondary me-2" onclick="saveToDraft()">
                      <i class="ti-pencil me-1"></i>
                      Simpan ke Draft
                    </button>
                  </form>
                </div>
              </div>
            </div>

          </div>

          <!-- Sent Messages List -->
          <div class="row">
            <div class="col-md-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0 text-dark">Pesan Terkirim</h4>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshMessages()">
                        <i class="ti-reload"></i> Refresh
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-info" onclick="exportMessages()">
                        <i class="ti-download"></i> Export
                      </button>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-hover" id="messagesTable">
                      <thead class="table-light">
                        <tr class="text-muted">
                          <th width="5%">#</th>
                          <th width="15%">No. Tujuan</th>
                          <th width="35%">Pesan</th>
                          <th width="15%">Tanggal Kirim</th>
                          <th width="10%">Status</th>
                          <th width="20%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($messages)): ?>
                          <?php foreach($messages as $index => $message): ?>
                            <tr>
                              <td><?= $index + 1 ?></td>
                              <td><?= htmlspecialchars($message['no_tujuan']) ?></td>
                              <td>
                                <div class="message-cell text-dark" title="<?= htmlspecialchars($message['pesan']) ?>">
                                  <?= htmlspecialchars(substr($message['pesan'], 0, 80)) ?>
                                  <?= strlen($message['pesan']) > 80 ? '...' : '' ?>
                                </div>
                              </td>
                              <td class="text-muted"><?= date('d/m/Y H:i', strtotime($message['tanggal_kirim'])) ?></td>
                              <td>
                                <span class="badge bg-success text-white">Terkirim</span>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <button class="btn btn-sm btn-primary" onclick="viewFullMessage(<?= $message['id_wagateway'] ?>)" title="Lihat Detail">
                                    <i class="ti-eye"></i>
                                  </button>

                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="archive_message">
                                    <input type="hidden" name="id" value="<?= $message['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Arsipkan pesan ini?')" title="Arsip">
                                      <i class="ti-archive"></i>
                                    </button>
                                  </form>

                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_message">
                                    <input type="hidden" name="id" value="<?= $message['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesan ini secara permanen?')" title="Hapus">
                                      <i class="ti-trash"></i>
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pesan terkirim</td>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Pesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="messageDetail">
            <div class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
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
    // Character counter for single message
    $('textarea[name="pesan"]').first().on('input', function() {
      const length = $(this).val().length;
      $('#charCount').text(length);

      if(length > 1000) {
        $('#charCount').addClass('text-danger');
      } else {
        $('#charCount').removeClass('text-danger');
      }
    });


    function saveToDraft() {
      const form = $('#singleMessageForm');
      const formData = new FormData(form[0]);
      formData.set('action', 'save_draft');

      $.ajax({
        url: 'index.php?controller=wagateway&action=draft',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          alert('Draft berhasil disimpan!');
          form[0].reset();
          $('#charCount').text('0');
        },
        error: function() {
          alert('Gagal menyimpan draft');
        }
      });
    }

    function viewFullMessage(id) {
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


    function refreshMessages() {
      location.reload();
    }

    function exportMessages() {
      // Implement export functionality
      alert('Fitur export akan segera tersedia');
    }

    function testMessage() {
      const phoneNumber = $('input[name="no_tujuan"]').val();
      const message = $('textarea[name="pesan"]').first().val();

      if(!phoneNumber || !message) {
        alert('Harap isi nomor tujuan dan pesan untuk test');
        return;
      }

      if(!phoneNumber.match(/^62\d{9,13}$/)) {
        alert('Format nomor tidak valid. Gunakan format: 628xxxxxxxxxx');
        return;
      }

      if(confirm('Test kirim pesan ke ' + phoneNumber + '?\n\nPerhatian: Ini akan mengirim pesan sesungguhnya!')) {
        $.ajax({
          url: 'index.php?controller=wagateway&action=testSendMessage',
          method: 'POST',
          data: {
            no_tujuan: phoneNumber,
            pesan: message
          },
          beforeSend: function() {
            $('button[onclick="testMessage()"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Testing...');
          },
          success: function(response) {
            console.log('Full Response:', response);

            if(response.success) {
              let successMsg = '✅ Test berhasil! Pesan telah dikirim.\n\n';
              if(response.success_reason) {
                successMsg += 'Success Reason: ' + response.success_reason + '\n\n';
              }
              successMsg += 'API Response:\n' + JSON.stringify(response.data, null, 2);

              if(response.debug) {
                successMsg += '\n\n=== DEBUG INFO ===\n';
                successMsg += 'HTTP Code: ' + response.debug.http_code + '\n';
                successMsg += 'Raw Response: ' + response.debug.raw_response;
              }

              alert(successMsg);
            } else {
              let errorMsg = '❌ Test gagal: ' + response.message + '\n\n';

              if(response.debug) {
                errorMsg += '=== DEBUG INFO ===\n';
                errorMsg += 'URL: ' + response.debug.url + '\n';
                errorMsg += 'HTTP Code: ' + response.debug.http_code + '\n';
                errorMsg += 'Sent Data: ' + JSON.stringify(response.debug.sent_data, null, 2) + '\n';
                errorMsg += 'Raw Response: ' + response.debug.raw_response + '\n';

                if(response.debug.parsed_response) {
                  errorMsg += 'Parsed Response: ' + JSON.stringify(response.debug.parsed_response, null, 2);
                }
              }

              alert(errorMsg);
            }
          },
          error: function(xhr, status, error) {
            alert('Error test message: ' + error);
          },
          complete: function() {
            $('button[onclick="testMessage()"]').prop('disabled', false).html('<i class="ti-settings me-1"></i>Test Kirim');
          }
        });
      }
    }

    function debugResponse() {
      $.ajax({
        url: 'index.php?controller=wagateway&action=debugLastResponse',
        method: 'GET',
        success: function(response) {
          if(response.success && response.last_responses.length > 0) {
            let debugInfo = 'Last Fonnte API Responses:\n\n';
            response.last_responses.forEach((line, index) => {
              debugInfo += `${index + 1}. ${line.trim()}\n\n`;
            });
            alert(debugInfo);
          } else {
            alert('No recent API responses found in log.\n\nTry sending a test message first.');
          }
        },
        error: function() {
          alert('Error fetching debug information');
        }
      });
    }

    // Form validation
    $('#singleMessageForm').on('submit', function(e) {
      const phoneNumber = $('input[name="no_tujuan"]').val();
      const message = $('textarea[name="pesan"]').first().val();

      if(phoneNumber && !phoneNumber.match(/^62\d{9,13}$/)) {
        e.preventDefault();
        alert('Format nomor tidak valid. Gunakan format: 628xxxxxxxxxx');
        return false;
      }

      if(message.length > 1000) {
        e.preventDefault();
        alert('Pesan terlalu panjang (maksimal 1000 karakter)');
        return false;
      }
    });

  </script>

  <style>
    .message-cell {
      max-width: 300px;
      word-wrap: break-word;
      cursor: pointer;
    }

    .card-title {
      color: #495057;
      font-weight: 600;
    }

    .form-label {
      font-weight: 500;
      color: #495057;
    }

    .badge {
      font-size: 0.75rem;
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

    .btn-group .btn {
      margin: 0 1px;
    }

    @media (max-width: 768px) {
      .col-md-6 {
        margin-bottom: 1rem;
      }

      .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
      }

      .table-responsive {
        font-size: 0.875rem;
      }
    }

    .text-danger {
      color: #dc3545 !important;
    }
  </style>
</body>
</html>