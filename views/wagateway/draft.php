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
                  <h3 class="font-weight-bold">Draft Pesan</h3>
                </div>
                <div class="col-12 col-xl-4">
                  <div class="justify-content-end d-flex">
                    <a href="index.php?controller=wagateway&action=index" class="btn btn-sm btn-secondary me-2">
                      <i class="ti-arrow-left me-1"></i>
                      Kembali
                    </a>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#draftForm">
                      <i class="ti-plus me-1"></i>
                      Draft Baru
                    </button>
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

          <!-- Draft Form -->
          <div class="collapse <?= isset($editDraft) ? 'show' : '' ?>" id="draftForm">
            <div class="card mb-4">
              <div class="card-body">
                <h4 class="card-title">
                  <?= isset($editDraft) ? 'Edit Draft' : 'Buat Draft Baru' ?>
                </h4>

                <form method="POST" id="saveDraftForm">
                  <input type="hidden" name="action" value="save_draft">
                  <?php if(isset($editDraft)): ?>
                    <input type="hidden" name="draft_id" value="<?= $editDraft['id_wagateway'] ?>">
                  <?php endif; ?>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Nomor Tujuan</label>
                        <input type="text" class="form-control" name="no_tujuan"
                               value="<?= isset($editDraft) ? htmlspecialchars($editDraft['no_tujuan']) : '' ?>"
                               placeholder="628xxxxxxxxxx" required>
                        <small class="form-text text-muted">Format: 628xxxxxxxxxx (tanpa +)</small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Template Pesan</label>
                        <select class="form-control" id="templateSelect">
                          <option value="">Pilih template...</option>
                          <option value="greeting">Salam Pembuka</option>
                          <option value="reminder">Pengingat</option>
                          <option value="announcement">Pengumuman</option>
                          <option value="custom">Custom</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea class="form-control" name="pesan" rows="6" placeholder="Tulis draft pesan Anda di sini..." required><?= isset($editDraft) ? htmlspecialchars($editDraft['pesan']) : '' ?></textarea>
                    <small class="form-text text-muted">
                      <span id="draftCharCount">0</span>/1000 karakter
                    </small>
                  </div>

                  <div class="d-flex justify-content-between">
                    <div>
                      <button type="submit" class="btn btn-primary me-2">
                        <i class="ti-save me-1"></i>
                        Simpan Draft
                      </button>

                      <button type="button" class="btn btn-success me-2" onclick="saveAndSend()">
                        <i class="ti-check me-1"></i>
                        Simpan & Kirim
                      </button>

                      <?php if(isset($editDraft)): ?>
                        <a href="index.php?controller=wagateway&action=draft" class="btn btn-secondary">
                          <i class="ti-close me-1"></i>
                          Batal Edit
                        </a>
                      <?php endif; ?>
                    </div>

                    <div>
                      <button type="button" class="btn btn-info me-2" onclick="previewDraft()">
                        <i class="ti-eye me-1"></i>
                        Preview
                      </button>

                      <button type="button" class="btn btn-warning" onclick="clearForm()">
                        <i class="ti-reload me-1"></i>
                        Reset
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Draft Statistics -->
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body text-center">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                      <h4 class="mb-0"><?= count($drafts) ?></h4>
                      <p class="mb-0 text-muted">Total Draft</p>
                    </div>
                    <div>
                      <i class="ti-pencil icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-light text-dark border-0 shadow-sm">
                <div class="card-body text-center">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                      <h4 class="mb-0" id="todayDrafts">0</h4>
                      <p class="mb-0 text-muted">Draft Hari Ini</p>
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
                <div class="card-body text-center">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                      <h4 class="mb-0" id="selectedDrafts">0</h4>
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
                <div class="card-body text-center">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                      <button class="btn btn-primary btn-sm" onclick="sendAllSelected()">
                        <i class="ti-email"></i> Kirim Terpilih
                      </button>
                    </div>
                    <div>
                      <i class="ti-email icon-lg text-muted"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Draft List -->
          <div class="row">
            <div class="col-md-12">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0 text-dark">Daftar Draft</h4>
                    <div class="btn-group" role="group">
                      <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                        <i class="ti-check-box"></i> Pilih Semua
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        <i class="ti-close"></i> Batal Pilih
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-info" onclick="refreshDrafts()">
                        <i class="ti-reload"></i> Refresh
                      </button>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table class="table table-hover" id="draftsTable">
                      <thead class="table-light">
                        <tr class="text-muted">
                          <th width="5%">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                            </div>
                          </th>
                          <th width="15%">No. Tujuan</th>
                          <th width="40%">Pesan</th>
                          <th width="15%">Tanggal Dibuat</th>
                          <th width="25%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($drafts)): ?>
                          <?php foreach($drafts as $draft): ?>
                            <tr>
                              <td>
                                <div class="form-check">
                                  <input class="form-check-input draft-checkbox" type="checkbox" value="<?= $draft['id_wagateway'] ?>">
                                </div>
                              </td>
                              <td><?= htmlspecialchars($draft['no_tujuan']) ?></td>
                              <td>
                                <div class="message-preview text-dark" title="<?= htmlspecialchars($draft['pesan']) ?>">
                                  <?= htmlspecialchars(substr($draft['pesan'], 0, 100)) ?>
                                  <?= strlen($draft['pesan']) > 100 ? '...' : '' ?>
                                </div>
                              </td>
                              <td class="text-muted"><?= date('d/m/Y H:i', strtotime($draft['tanggal_kirim'])) ?></td>
                              <td>
                                <div class="btn-group" role="group">
                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="send_draft">
                                    <input type="hidden" name="id" value="<?= $draft['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Kirim draft ini sekarang?')" title="Kirim">
                                      <i class="ti-check"></i>
                                    </button>
                                  </form>

                                  <a href="index.php?controller=wagateway&action=draft&edit=<?= $draft['id_wagateway'] ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="ti-pencil"></i>
                                  </a>

                                  <button class="btn btn-sm btn-info" onclick="viewDraftDetail(<?= $draft['id_wagateway'] ?>)" title="Lihat Detail">
                                    <i class="ti-eye"></i>
                                  </button>

                                  <button class="btn btn-sm btn-warning" onclick="duplicateDraft(<?= $draft['id_wagateway'] ?>)" title="Duplikat">
                                    <i class="ti-files"></i>
                                  </button>

                                  <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_draft">
                                    <input type="hidden" name="id" value="<?= $draft['id_wagateway'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus draft ini?')" title="Hapus">
                                      <i class="ti-trash"></i>
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada draft pesan</td>
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

  <!-- Draft Detail Modal -->
  <div class="modal fade" id="draftDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Draft</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="draftDetailContent">
            <div class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="sendFromModal">
            <i class="ti-check"></i> Kirim
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Preview Modal -->
  <div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Preview Pesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="previewContent"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="sendPreviewedMessage()">
            <i class="ti-check"></i> Kirim Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Message templates
    const templates = {
      greeting: "Selamat pagi/siang/sore,\n\nSemoga hari Anda menyenangkan!\n\nTerima kasih.",
      reminder: "Pengingat penting:\n\n[Isi pengingat Anda di sini]\n\nJangan lupa untuk segera ditindaklanjuti.\n\nTerima kasih.",
      announcement: "Pengumuman:\n\n[Isi pengumuman Anda di sini]\n\nMohon untuk diperhatikan.\n\nTerima kasih.",
      custom: ""
    };

    // Template selection
    $('#templateSelect').change(function() {
      const template = templates[$(this).val()] || '';
      $('textarea[name="pesan"]').val(template);
      updateCharCount();
    });

    // Character counter
    function updateCharCount() {
      const length = $('textarea[name="pesan"]').val().length;
      $('#draftCharCount').text(length);

      if(length > 1000) {
        $('#draftCharCount').addClass('text-danger');
      } else {
        $('#draftCharCount').removeClass('text-danger');
      }
    }

    $('textarea[name="pesan"]').on('input', updateCharCount);

    // Initialize character count
    updateCharCount();

    // Count today's drafts
    let todayCount = 0;
    <?php foreach($drafts as $draft): ?>
      if('<?= date('Y-m-d', strtotime($draft['tanggal_kirim'])) ?>' === '<?= date('Y-m-d') ?>') {
        todayCount++;
      }
    <?php endforeach; ?>
    $('#todayDrafts').text(todayCount);

    // Save and send function
    function saveAndSend() {
      const form = $('#saveDraftForm');
      const formData = new FormData(form[0]);
      formData.set('action', 'save_draft');

      if(!form[0].checkValidity()) {
        form[0].reportValidity();
        return;
      }

      // Save first, then send
      $.ajax({
        url: 'index.php?controller=wagateway&action=draft',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Now send the message
          const sendData = new FormData();
          sendData.set('action', 'send_message');
          sendData.set('no_tujuan', $('input[name="no_tujuan"]').val());
          sendData.set('pesan', $('textarea[name="pesan"]').val());

          $.post('index.php?controller=wagateway&action=pesan_keluar', sendData, function() {
            alert('Draft berhasil disimpan dan dikirim!');
            location.reload();
          }).fail(function() {
            alert('Draft disimpan, tapi gagal mengirim pesan');
            location.reload();
          });
        },
        error: function() {
          alert('Gagal menyimpan draft');
        }
      });
    }

    function previewDraft() {
      const tujuan = $('input[name="no_tujuan"]').val();
      const pesan = $('textarea[name="pesan"]').val();

      if(!tujuan || !pesan) {
        alert('Harap isi nomor tujuan dan pesan');
        return;
      }

      const previewHtml = `
        <div class="card">
          <div class="card-header">
            <strong>Kepada:</strong> ${tujuan}
          </div>
          <div class="card-body">
            <div class="whatsapp-preview" style="background: #e5ddd5; padding: 15px; border-radius: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
              <div style="background: #dcf8c6; padding: 10px; border-radius: 15px; margin-left: 50px; position: relative;">
                ${pesan.replace(/\n/g, '<br>')}
                <div style="text-align: right; font-size: 11px; color: #666; margin-top: 5px;">
                  ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      $('#previewContent').html(previewHtml);
      $('#previewModal').modal('show');
    }

    function sendPreviewedMessage() {
      $('#previewModal').modal('hide');
      saveAndSend();
    }

    function clearForm() {
      $('#saveDraftForm')[0].reset();
      $('#templateSelect').val('');
      updateCharCount();
    }

    // Checkbox functionality
    function updateSelectedCount() {
      const count = $('.draft-checkbox:checked').length;
      $('#selectedDrafts').text(count);
    }

    $('.draft-checkbox').change(updateSelectedCount);

    $('#selectAllCheckbox').change(function() {
      $('.draft-checkbox').prop('checked', $(this).prop('checked'));
      updateSelectedCount();
    });

    function selectAll() {
      $('.draft-checkbox, #selectAllCheckbox').prop('checked', true);
      updateSelectedCount();
    }

    function clearSelection() {
      $('.draft-checkbox, #selectAllCheckbox').prop('checked', false);
      updateSelectedCount();
    }

    function sendAllSelected() {
      const selected = $('.draft-checkbox:checked').map(function() {
        return $(this).val();
      }).get();

      if(selected.length === 0) {
        alert('Pilih draft yang akan dikirim');
        return;
      }

      if(confirm(`Kirim ${selected.length} draft yang dipilih?`)) {
        // Send all selected drafts
        let completed = 0;
        selected.forEach(id => {
          const form = $('<form method="POST">');
          form.append('<input type="hidden" name="action" value="send_draft">');
          form.append('<input type="hidden" name="id" value="' + id + '">');

          $.post('index.php?controller=wagateway&action=draft', form.serialize())
            .always(() => {
              completed++;
              if(completed === selected.length) {
                alert('Semua draft telah diproses');
                location.reload();
              }
            });
        });
      }
    }

    function viewDraftDetail(id) {
      $('#draftDetailModal').modal('show');
      // Implement AJAX call to get draft details
      setTimeout(() => {
        $('#draftDetailContent').html(`
          <div class="row">
            <div class="col-md-12">
              <h6>Detail Draft #${id}</h6>
              <p>Loading draft details...</p>
            </div>
          </div>
        `);
      }, 500);
    }

    function duplicateDraft(id) {
      if(confirm('Duplikat draft ini?')) {
        // Implement duplication logic
        alert('Fitur duplikasi akan segera tersedia');
      }
    }

    function refreshDrafts() {
      location.reload();
    }
  </script>

  <style>
    .message-preview {
      max-width: 400px;
      word-wrap: break-word;
      cursor: pointer;
    }

    .whatsapp-preview {
      min-height: 100px;
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
  </style>
</body>
</html>