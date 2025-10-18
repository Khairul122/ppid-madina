<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'petugas')) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$is_edit = isset($data['dokumen']) && !empty($data['dokumen']);
$dokumen = $data['dokumen'] ?? [];
$dokumen_pemda_options = $data['dokumen_pemda_options'] ?? [];

$page_title = $is_edit ? 'Edit Dokumen Berkala' : 'Tambah Dokumen Berkala';
$action_url = $is_edit ? 'update' : 'store';
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

              <!-- Form Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <form method="POST" action="index.php?controller=kategoriberkala&action=<?php echo $action_url; ?>" id="dokumenForm" enctype="multipart/form-data">
                    <?php if ($is_edit): ?>
                      <input type="hidden" name="id" value="<?php echo $dokumen['id_dokumen']; ?>">
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
                               value="<?php echo htmlspecialchars($form_data['judul'] ?? ($dokumen['judul'] ?? '')); ?>"
                               placeholder="Masukkan judul dokumen"
                               maxlength="255"
                               style="padding: 20px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="judul_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="kandungan_informasi" class="form-label fw-bold">Kandungan Informasi</label>
                        <textarea class="form-control"
                                  id="kandungan_informasi"
                                  name="kandungan_informasi"
                                  rows="4"
                                  placeholder="Masukkan kandungan informasi dokumen (opsional)"
                                  style="padding: 20px; border-radius: 6px;"><?php echo htmlspecialchars($form_data['kandungan_informasi'] ?? ($dokumen['kandungan_informasi'] ?? '')); ?></textarea>
                        <small class="text-muted">Deskripsi detail tentang dokumen (opsional)</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="terbitkan_sebagai" class="form-label fw-bold">
                          Terbitkan Sebagai <span style="color: red;">*</span>
                        </label>
                        <select class="form-select"
                                id="terbitkan_sebagai"
                                name="terbitkan_sebagai"
                                style="padding: 20px; border-radius: 6px;"
                                required>
                          <option value="">-- Pilih SKPD --</option>
                          <?php if (!empty($data['skpd_list']) && is_array($data['skpd_list'])): ?>
                            <?php foreach ($data['skpd_list'] as $skpd): ?>
                              <?php if (is_array($skpd) && isset($skpd['nama_skpd'])): ?>
                              <option value="<?php echo htmlspecialchars($skpd['nama_skpd']); ?>"
                                <?php
                                  $selected = false;
                                  if (isset($form_data['terbitkan_sebagai']) && $form_data['terbitkan_sebagai'] == $skpd['nama_skpd']) {
                                    $selected = true;
                                  } elseif (isset($dokumen['terbitkan_sebagai']) && $dokumen['terbitkan_sebagai'] == $skpd['nama_skpd']) {
                                    $selected = true;
                                  }
                                  echo $selected ? 'selected' : '';
                                ?>>
                                <?php echo htmlspecialchars($skpd['nama_skpd']); ?>
                              </option>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="id_dokumen_pemda" class="form-label fw-bold">Referensi Dokumen Pemda</label>
                        <select class="form-select"
                                id="id_dokumen_pemda"
                                name="id_dokumen_pemda"
                                style="padding: 20px; border-radius: 6px;">
                          <option value="">-- Pilih Dokumen Pemda (Opsional) --</option>
                          <?php if (!empty($dokumen_pemda_options)): ?>
                            <?php foreach ($dokumen_pemda_options as $pemda): ?>
                              <option value="<?php echo $pemda['id_dokumen_pemda']; ?>"
                                      <?php echo (($form_data['id_dokumen_pemda'] ?? ($dokumen['id_dokumen_pemda'] ?? '')) == $pemda['id_dokumen_pemda']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pemda['nama_jenis']); ?>
                              </option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                        <small class="text-muted">Pilih dokumen pemda sebagai referensi (opsional)</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="tipe_file" class="form-label fw-bold">
                          Tipe File <span style="color: red;">*</span>
                        </label>
                        <select class="form-select"
                                id="tipe_file"
                                name="tipe_file"
                                style="padding: 20px; border-radius: 6px;"
                                required>
                          <option value="">-- Pilih Tipe File --</option>
                          <?php
                          $tipe_files = [
                              'text' => 'Dokumen Teks (PDF, DOC, TXT)',
                              'audio' => 'File Audio (MP3, WAV)',
                              'video' => 'File Video (MP4, AVI)',
                              'gambar' => 'File Gambar (JPG, PNG)',
                              'lainnya' => 'File Lainnya'
                          ];
                          $selected_tipe = $form_data['tipe_file'] ?? ($dokumen['tipe_file'] ?? '');
                          foreach ($tipe_files as $value => $label):
                          ?>
                            <option value="<?php echo $value; ?>" <?php echo ($selected_tipe == $value) ? 'selected' : ''; ?>>
                              <?php echo $label; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="status" class="form-label fw-bold">
                          Status <span style="color: red;">*</span>
                        </label>
                        <select class="form-select"
                                id="status"
                                name="status"
                                style="padding: 20px; border-radius: 6px;"
                                required>
                          <?php $selected_status = $form_data['status'] ?? ($dokumen['status'] ?? 'draft'); ?>
                          <option value="draft" <?php echo ($selected_status == 'draft') ? 'selected' : ''; ?>>
                            Draft (Tidak Dipublikasikan)
                          </option>
                          <option value="publikasi" <?php echo ($selected_status == 'publikasi') ? 'selected' : ''; ?>>
                            Publikasi (Dipublikasikan)
                          </option>
                        </select>
                        <small class="text-muted">Draft: Belum dipublikasi | Publikasi: Dapat diakses publik</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="upload_file" class="form-label fw-bold">Upload File</label>
                        <input type="file"
                               class="form-control"
                               id="upload_file"
                               name="upload_file"
                               accept=".pdf,.doc,.docx,.txt,.mp3,.wav,.mp4,.avi,.jpg,.jpeg,.png,.gif"
                               style="padding: 20px; border-radius: 6px;">

                        <?php if ($is_edit && !empty($dokumen['upload_file'])): ?>
                          <div class="mt-2">
                            <small class="text-muted">
                              <i class="mdi mdi-file me-1"></i>
                              File saat ini: <?php echo basename($dokumen['upload_file']); ?>
                            </small>
                          </div>
                        <?php endif; ?>

                        <small class="text-muted">
                          File maksimal 10MB. Format yang didukung: PDF, DOC, DOCX, TXT, MP3, WAV, MP4, AVI, JPG, PNG, GIF
                          <?php echo $is_edit ? '<br>Biarkan kosong jika tidak ingin mengubah file' : ''; ?>
                        </small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                      <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                        <i class="mdi mdi-content-save me-1"></i>
                        <?php echo $is_edit ? 'Update Data' : 'Simpan Data'; ?>
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

      // Form validation
      const form = document.getElementById('dokumenForm');
      const judulInput = document.getElementById('judul');
      const terbitkanInput = document.getElementById('terbitkan_sebagai');
      const tipeFileInput = document.getElementById('tipe_file');
      const statusInput = document.getElementById('status');

      // Real-time validation
      judulInput.addEventListener('input', function() {
        validateJudul();
      });

      terbitkanInput.addEventListener('change', function() {
        validateTerbitkan();
      });

      tipeFileInput.addEventListener('change', function() {
        validateTipeFile();
      });

      statusInput.addEventListener('change', function() {
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

      function validateTerbitkan() {
        const value = terbitkanInput.value.trim();
        if (value === '') {
          setInvalid(terbitkanInput, 'Terbitkan sebagai wajib dipilih');
          return false;
        } else {
          setValid(terbitkanInput);
          return true;
        }
      }

      function validateTipeFile() {
        const value = tipeFileInput.value;
        if (value === '') {
          setInvalid(tipeFileInput, 'Tipe file wajib dipilih');
          return false;
        } else {
          setValid(tipeFileInput);
          return true;
        }
      }

      function validateStatus() {
        const value = statusInput.value;
        if (value === '') {
          setInvalid(statusInput, 'Status wajib dipilih');
          return false;
        } else {
          setValid(statusInput);
          return true;
        }
      }

      function setInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        if (feedback) {
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
        isValid &= validateTerbitkan();
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
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(function(input) {
          input.classList.remove('is-valid', 'is-invalid');
        });

        // Reset character counters
        setTimeout(function() {
          updateCounter('judul', 'judul_counter', 255);
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

</body>

</html>
