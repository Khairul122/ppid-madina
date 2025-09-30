<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'operator')) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$page_title = isset($data['dokumen']) ? 'Edit Dokumen Berkala' : 'Tambah Dokumen Berkala';
$action_url = isset($data['dokumen']) ? 'update' : 'store';
$method = 'POST';
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
                  <i class="mdi mdi-folder-clock me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;"><?php echo $page_title; ?></span>
                </div>
                <a href="index.php?controller=kategoriberkala&action=index" class="btn btn-outline-secondary">
                  <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
              </div>

              <!-- Form Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <form method="<?php echo $method; ?>" action="index.php?controller=kategoriberkala&action=<?php echo $action_url; ?><?php if (isset($data['dokumen'])): ?>&id=<?php echo $data['dokumen']['id_dokumen']; ?><?php endif; ?>" id="dokumenForm" enctype="multipart/form-data">
                    <?php if (isset($data['dokumen'])): ?>
                      <input type="hidden" name="id" value="<?php echo $data['dokumen']['id_dokumen']; ?>">
                    <?php endif; ?>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="judul" class="form-label fw-bold">
                          Judul Dokumen <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="judul"
                               name="judul"
                               value="<?php echo htmlspecialchars($form_data['judul'] ?? ($data['dokumen']['judul'] ?? '')); ?>"
                               placeholder="Masukkan judul dokumen"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="judul_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="kandungan_informasi" class="form-label fw-bold">
                          Kandungan Informasi
                        </label>
                        <textarea class="form-control"
                                  id="kandungan_informasi"
                                  name="kandungan_informasi"
                                  rows="6"
                                  placeholder="Masukkan kandungan informasi (opsional)"
                                  style="padding: 12px; border-radius: 6px;"><?php echo htmlspecialchars($form_data['kandungan_informasi'] ?? ($data['dokumen']['kandungan_informasi'] ?? '')); ?></textarea>
                        <small class="text-muted">Kandungan informasi bersifat opsional</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="terbitkan_sebagai" class="form-label fw-bold">
                          Terbitkan Sebagai <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="terbitkan_sebagai"
                               name="terbitkan_sebagai"
                               value="<?php echo htmlspecialchars($form_data['terbitkan_sebagai'] ?? ($data['dokumen']['terbitkan_sebagai'] ?? '')); ?>"
                               placeholder="Contoh: Peraturan Daerah, Keputusan Bupati"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="terbitkan_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="id_dokumen_pemda" class="form-label fw-bold">
                          Dokumen Pemda (Opsional)
                        </label>
                        <select class="form-control"
                                id="id_dokumen_pemda"
                                name="id_dokumen_pemda"
                                style="padding: 12px; border-radius: 6px;">
                          <option value="">Pilih Dokumen Pemda (Opsional)</option>
                          <?php foreach ($data['dokumen_pemda_options'] as $dokumen_pemda): ?>
                            <option value="<?php echo $dokumen_pemda['id_dokumen_pemda']; ?>"
                              <?php echo (isset($form_data['id_dokumen_pemda']) && $form_data['id_dokumen_pemda'] == $dokumen_pemda['id_dokumen_pemda']) || (isset($data['dokumen']) && $data['dokumen']['id_dokumen_pemda'] == $dokumen_pemda['id_dokumen_pemda']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($dokumen_pemda['nama_jenis']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="tipe_file" class="form-label fw-bold">
                          Tipe File <span style="color: red;">*</span>
                        </label>
                        <select class="form-control"
                                id="tipe_file"
                                name="tipe_file"
                                style="padding: 12px; border-radius: 6px;"
                                required>
                          <option value="">Pilih Tipe File</option>
                          <option value="text" <?php echo (isset($form_data['tipe_file']) && $form_data['tipe_file'] == 'text') || (isset($data['dokumen']) && $data['dokumen']['tipe_file'] == 'text') ? 'selected' : ''; ?>>Text/Dokumen</option>
                          <option value="audio" <?php echo (isset($form_data['tipe_file']) && $form_data['tipe_file'] == 'audio') || (isset($data['dokumen']) && $data['dokumen']['tipe_file'] == 'audio') ? 'selected' : ''; ?>>Audio</option>
                          <option value="video" <?php echo (isset($form_data['tipe_file']) && $form_data['tipe_file'] == 'video') || (isset($data['dokumen']) && $data['dokumen']['tipe_file'] == 'video') ? 'selected' : ''; ?>>Video</option>
                          <option value="gambar" <?php echo (isset($form_data['tipe_file']) && $form_data['tipe_file'] == 'gambar') || (isset($data['dokumen']) && $data['dokumen']['tipe_file'] == 'gambar') ? 'selected' : ''; ?>>Gambar</option>
                          <option value="lainnya" <?php echo (isset($form_data['tipe_file']) && $form_data['tipe_file'] == 'lainnya') || (isset($data['dokumen']) && $data['dokumen']['tipe_file'] == 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-bold">
                          Status <span style="color: red;">*</span>
                        </label>
                        <select class="form-control"
                                id="status"
                                name="status"
                                style="padding: 12px; border-radius: 6px;"
                                required>
                          <option value="">Pilih Status</option>
                          <option value="draft" <?php echo (isset($form_data['status']) && $form_data['status'] == 'draft') || (isset($data['dokumen']) && $data['dokumen']['status'] == 'draft') || (!isset($data['dokumen'])) ? 'selected' : ''; ?>>Draft</option>
                          <option value="publikasi" <?php echo (isset($form_data['status']) && $form_data['status'] == 'publikasi') || (isset($data['dokumen']) && $data['dokumen']['status'] == 'publikasi') ? 'selected' : ''; ?>>Publikasi</option>
                        </select>
                        <small class="text-muted">Draft = Tidak terlihat publik, Publikasi = Terlihat publik</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="upload_file" class="form-label fw-bold">
                          Upload File <?php echo isset($data['dokumen']) ? '(Kosongkan jika tidak ingin mengubah file)' : '(Opsional)'; ?>
                        </label>
                        <input type="file"
                               class="form-control"
                               id="upload_file"
                               name="upload_file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.mp4,.avi,.mov"
                               style="padding: 12px; border-radius: 6px;">
                        <?php if (isset($data['dokumen']) && !empty($data['dokumen']['upload_file'])): ?>
                          <small class="text-muted">
                            File saat ini: <a href="<?php echo $data['dokumen']['upload_file']; ?>" target="_blank" class="text-primary">
                              <?php echo basename($data['dokumen']['upload_file']); ?>
                            </a>
                          </small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format yang didukung: PDF, DOC, XLS, PPT, TXT, JPG, PNG, MP3, MP4, dll.</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                      <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                        <i class="mdi mdi-content-save me-1"></i>
                        <?php echo isset($data['dokumen']) ? 'Update Data' : 'Simpan Data'; ?>
                      </button>
                      <button type="reset" class="btn btn-outline-secondary" style="padding: 10px 20px;">
                        <i class="mdi mdi-refresh me-1"></i>Reset
                      </button>
                      <a href="index.php?controller=kategoriberkala&action=index" class="btn btn-outline-danger" style="padding: 10px 20px;">
                        <i class="mdi mdi-close me-1"></i>Batal
                      </a>
                    </div>
                  </form>
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
    document.addEventListener('DOMContentLoaded', function() {
      // Character counter function
      function updateCounter(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);

        function updateCount() {
          const currentLength = input.value.length;
          counter.textContent = currentLength;

          if (currentLength > maxLength * 0.9) {
            counter.parentElement.classList.add('text-warning');
          } else {
            counter.parentElement.classList.remove('text-warning');
          }

          if (currentLength >= maxLength) {
            counter.parentElement.classList.add('text-danger');
          } else {
            counter.parentElement.classList.remove('text-danger');
          }
        }

        input.addEventListener('input', updateCount);
        updateCount(); // Initial count
      }

      // Initialize character counters
      updateCounter('judul', 'judul_counter', 255);
      updateCounter('terbitkan_sebagai', 'terbitkan_counter', 255);

      // Form validation
      const form = document.getElementById('dokumenForm');
      const judulInput = document.getElementById('judul');
      const terbitkanInput = document.getElementById('terbitkan_sebagai');
      const tipeFileSelect = document.getElementById('tipe_file');
      const statusSelect = document.getElementById('status');

      // Real-time validation
      judulInput.addEventListener('input', function() {
        validateJudul();
      });

      terbitkanInput.addEventListener('input', function() {
        validateTerbitkanSebagai();
      });

      tipeFileSelect.addEventListener('change', function() {
        validateTipeFile();
      });

      statusSelect.addEventListener('change', function() {
        validateStatus();
      });

      // Validation functions
      function validateJudul() {
        const value = judulInput.value.trim();
        if (value === '') {
          setInvalid(judulInput, 'Judul dokumen wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(judulInput, 'Judul dokumen maksimal 255 karakter');
          return false;
        } else {
          setValid(judulInput);
          return true;
        }
      }

      function validateTerbitkanSebagai() {
        const value = terbitkanInput.value.trim();
        if (value === '') {
          setInvalid(terbitkanInput, 'Terbitkan sebagai wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(terbitkanInput, 'Terbitkan sebagai maksimal 255 karakter');
          return false;
        } else {
          setValid(terbitkanInput);
          return true;
        }
      }

      function validateTipeFile() {
        const value = tipeFileSelect.value;
        if (value === '') {
          setInvalid(tipeFileSelect, 'Tipe file wajib dipilih');
          return false;
        } else {
          setValid(tipeFileSelect);
          return true;
        }
      }

      function validateStatus() {
        const value = statusSelect.value;
        if (value === '') {
          setInvalid(statusSelect, 'Status wajib dipilih');
          return false;
        } else {
          setValid(statusSelect);
          return true;
        }
      }

      function setInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        while (feedback && !feedback.classList.contains('invalid-feedback')) {
          feedback = feedback.nextElementSibling;
        }
        if (feedback && feedback.classList.contains('invalid-feedback')) {
          feedback.textContent = message;
        }
      }

      function setValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
      }

      // Form submit validation
      form.addEventListener('submit', function(e) {
        let isValid = true;

        isValid &= validateJudul();
        isValid &= validateTerbitkanSebagai();
        isValid &= validateTipeFile();
        isValid &= validateStatus();

        if (!isValid) {
          e.preventDefault();
          e.stopPropagation();

          // Show error alert
          const alertDiv = document.createElement('div');
          alertDiv.className = 'alert alert-danger alert-dismissible fade show';
          alertDiv.innerHTML = `
            <i class="mdi mdi-alert-circle me-2"></i>
            Mohon perbaiki kesalahan pada form sebelum melanjutkan
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          `;

          const cardBody = document.querySelector('.card-body');
          cardBody.insertBefore(alertDiv, cardBody.firstChild);

          // Auto-hide alert after 5 seconds
          setTimeout(function() {
            const alert = new bootstrap.Alert(alertDiv);
            alert.close();
          }, 5000);
        }
      });

      // Reset form
      const resetBtn = document.querySelector('button[type="reset"]');
      resetBtn.addEventListener('click', function() {
        // Clear validation classes
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(function(input) {
          input.classList.remove('is-valid', 'is-invalid');
        });

        // Reset character counters
        setTimeout(function() {
          updateCounter('judul', 'judul_counter', 255);
          updateCounter('terbitkan_sebagai', 'terbitkan_counter', 255);
        }, 10);
      });
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