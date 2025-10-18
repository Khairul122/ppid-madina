<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'petugas')) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$isEdit = isset($data['action']) && $data['action'] === 'edit';
$berita = $data['berita'] ?? null;

$page_title = $isEdit ? 'Edit Berita' : 'Tambah Berita';
$action_url = $isEdit ? 'update' : 'store';
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
                  <i class="mdi mdi-newspaper me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;"><?php echo $page_title; ?></span>
                </div>
                <a href="index.php?controller=berita&action=index" class="btn btn-outline-secondary">
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
                  <form method="POST" action="index.php?controller=berita&action=<?php echo $action_url; ?>" id="beritaForm" enctype="multipart/form-data">
                    <?php if ($isEdit && $berita): ?>
                      <input type="hidden" name="id" value="<?php echo $berita['id_berita']; ?>">
                    <?php endif; ?>

                    <!-- Judul -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="judul" class="form-label fw-bold">
                          Judul Berita <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="judul"
                               name="judul"
                               value="<?php echo htmlspecialchars($form_data['judul'] ?? ($berita['judul'] ?? '')); ?>"
                               placeholder="Masukkan judul berita"
                               maxlength="255"
                               style="padding: 20px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="judul_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- URL -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="url" class="form-label fw-bold">URL Berita</label>
                        <input type="url"
                               class="form-control"
                               id="url"
                               name="url"
                               value="<?php echo htmlspecialchars($form_data['url'] ?? ($berita['url'] ?? '')); ?>"
                               placeholder="https://example.com/berita-terbaru"
                               style="padding: 20px; border-radius: 6px;">
                        <small class="text-muted">URL eksternal ke berita (opsional)</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- Summary -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="summary" class="form-label fw-bold">
                          Summary <span style="color: red;">*</span>
                        </label>
                        <textarea class="form-control"
                                  id="summary"
                                  name="summary"
                                  rows="6"
                                  placeholder="Masukkan ringkasan berita"
                                  style="padding: 20px; border-radius: 6px;"
                                  required><?php echo htmlspecialchars($form_data['summary'] ?? ($berita['summary'] ?? '')); ?></textarea>
                        <small class="text-muted">Ringkasan atau konten berita</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- Image Section -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Gambar Berita</label>

                        <!-- Current Image Preview (Edit Mode) -->
                        <?php if ($isEdit && $berita && !empty($berita['image'])): ?>
                          <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 6px;">
                            <label class="form-text fw-bold">Gambar saat ini:</label>
                            <div class="mt-2">
                              <?php if (filter_var($berita['image'], FILTER_VALIDATE_URL)): ?>
                                <img src="<?php echo htmlspecialchars($berita['image']); ?>"
                                     alt="Current Image"
                                     class="img-thumbnail"
                                     style="max-width: 300px; border-radius: 6px;">
                                <div class="form-text mt-2">
                                  <i class="mdi mdi-link-variant"></i>
                                  URL: <?php echo htmlspecialchars($berita['image']); ?>
                                </div>
                              <?php elseif (file_exists($berita['image'])): ?>
                                <img src="<?php echo htmlspecialchars($berita['image']); ?>"
                                     alt="Current Image"
                                     class="img-thumbnail"
                                     style="max-width: 300px; border-radius: 6px;">
                              <?php else: ?>
                                <div class="alert alert-warning p-2 mb-0">
                                  <i class="mdi mdi-alert-outline"></i>
                                  File gambar tidak ditemukan: <?php echo htmlspecialchars($berita['image']); ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          </div>
                        <?php endif; ?>

                        <!-- Image Options -->
                        <div class="row">
                          <div class="col-md-6 mb-2">
                            <div class="card" style="border: 2px solid #e0e0e0;">
                              <div class="card-body p-3">
                                <h6 class="card-title mb-3">
                                  <i class="mdi mdi-upload text-primary"></i> Upload File Gambar
                                </h6>
                                <input type="file"
                                       class="form-control"
                                       id="image_file"
                                       name="image_file"
                                       accept="image/jpeg,image/jpg,image/png,image/gif"
                                       style="padding: 12px;">
                                <div class="form-text mt-2">
                                  <i class="mdi mdi-information-outline"></i>
                                  Format: JPG, PNG, GIF. Maksimal: 2MB
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-2">
                            <div class="card" style="border: 2px solid #e0e0e0;">
                              <div class="card-body p-3">
                                <h6 class="card-title mb-3">
                                  <i class="mdi mdi-link-variant text-info"></i> Atau URL Gambar
                                </h6>
                                <input type="url"
                                       class="form-control"
                                       id="image_url"
                                       name="image_url"
                                       placeholder="https://example.com/image.jpg"
                                       style="padding: 12px;">
                                <div class="form-text mt-2">
                                  <i class="mdi mdi-information-outline"></i>
                                  Link gambar dari internet
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="alert alert-info mt-2 py-2">
                          <i class="mdi mdi-lightbulb-outline"></i>
                          <small>
                            Jika kedua opsi diisi, file upload akan diprioritaskan.
                            <?php echo $isEdit ? 'Kosongkan keduanya untuk mempertahankan gambar lama.' : ''; ?>
                          </small>
                        </div>
                      </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 mt-4">
                      <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                        <i class="mdi mdi-content-save me-1"></i>
                        <?php echo $isEdit ? 'Update Berita' : 'Simpan Berita'; ?>
                      </button>
                      <button type="reset" class="btn btn-outline-secondary" style="padding: 10px 20px;">
                        <i class="mdi mdi-refresh me-1"></i>Reset
                      </button>
                      <a href="index.php?controller=berita&action=index" class="btn btn-outline-danger" style="padding: 10px 20px;">
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
      const form = document.getElementById('beritaForm');
      const judulInput = document.getElementById('judul');
      const summaryInput = document.getElementById('summary');
      const urlInput = document.getElementById('url');

      // Real-time validation
      judulInput.addEventListener('input', function() {
        validateJudul();
      });

      summaryInput.addEventListener('input', function() {
        validateSummary();
      });

      urlInput.addEventListener('input', function() {
        validateUrl();
      });

      // Validation functions
      function validateJudul() {
        const value = judulInput.value.trim();
        if (value === '') {
          setInvalid(judulInput, 'Judul berita wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(judulInput, 'Judul berita maksimal 255 karakter');
          return false;
        } else {
          setValid(judulInput);
          return true;
        }
      }

      function validateSummary() {
        const value = summaryInput.value.trim();
        if (value === '') {
          setInvalid(summaryInput, 'Summary berita wajib diisi');
          return false;
        } else {
          setValid(summaryInput);
          return true;
        }
      }

      function validateUrl() {
        const value = urlInput.value.trim();
        if (value !== '') {
          // Simple URL validation
          const urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
          if (!urlPattern.test(value)) {
            setInvalid(urlInput, 'Format URL tidak valid');
            return false;
          }
        }
        setValid(urlInput);
        return true;
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
        isValid &= validateSummary();
        isValid &= validateUrl();

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

      // Image file validation
      document.getElementById('image_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          // Check file size (2MB)
          if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            e.target.value = '';
            return;
          }

          // Check file type
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
          if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            e.target.value = '';
            return;
          }

          // Preview image
          const reader = new FileReader();
          reader.onload = function(e) {
            // You can add image preview here if needed
          };
          reader.readAsDataURL(file);
        }
      });

      // Clear image_url when file is selected
      document.getElementById('image_file').addEventListener('change', function() {
        if (this.files.length > 0) {
          document.getElementById('image_url').value = '';
        }
      });

      // Clear image_file when URL is entered
      document.getElementById('image_url').addEventListener('input', function() {
        if (this.value.trim() !== '') {
          document.getElementById('image_file').value = '';
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
