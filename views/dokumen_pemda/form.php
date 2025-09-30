<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'operator')) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$page_title = isset($data['dokumen']) ? 'Edit Dokumen' : 'Tambah Dokumen';
$action_url = isset($data['dokumen']) ? 'update' : 'store';
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
                  <i class="mdi mdi-file-document me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;"><?php echo $page_title; ?></span>
                </div>
                <a href="index.php?controller=dokumenpemda&action=index" class="btn btn-outline-secondary">
                  <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
              </div>

              <!-- Form Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <form method="POST" action="index.php?controller=dokumenpemda&action=<?php echo $action_url; ?><?php if (isset($data['dokumen'])): ?>&id=<?php echo $data['dokumen']['id_dokumen_pemda']; ?><?php endif; ?>" id="dokumenForm">
                    <?php if (isset($data['dokumen'])): ?>
                      <input type="hidden" name="id" value="<?php echo $data['dokumen']['id_dokumen_pemda']; ?>">
                    <?php endif; ?>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="nama_jenis" class="form-label fw-bold">
                          Nama Jenis <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nama_jenis"
                               name="nama_jenis"
                               value="<?php echo htmlspecialchars($form_data['nama_jenis'] ?? ($data['dokumen']['nama_jenis'] ?? '')); ?>"
                               placeholder="Masukkan nama jenis dokumen"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="nama_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="id_kategori" class="form-label fw-bold">Kategori</label>
                        <select class="form-select" id="id_kategori" name="id_kategori" style="padding: 12px; border-radius: 6px;">
                          <option value="">Pilih Kategori</option>
                          <?php foreach ($data['kategoriOptions'] as $kategori): ?>
                            <option value="<?php echo $kategori['id_kategori']; ?>" 
                                    <?php echo (isset($form_data['id_kategori']) && $form_data['id_kategori'] == $kategori['id_kategori']) || 
                                               (isset($data['dokumen']) && $data['dokumen']['id_kategori'] == $kategori['id_kategori']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="area" class="form-label fw-bold">Area</label>
                        <input type="text"
                               class="form-control"
                               id="area"
                               name="area"
                               value="<?php echo htmlspecialchars($form_data['area'] ?? (isset($data['dokumen']) ? $data['dokumen']['area'] : 'pemda')); ?>"
                               placeholder="Masukkan area (default: pemda)"
                               maxlength="50"
                               style="padding: 12px; border-radius: 6px;"
                               readonly>
                        <small class="text-muted"><span id="area_counter">0</span>/50 karakter</small>
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
                      <a href="index.php?controller=dokumenpemda&action=index" class="btn btn-outline-danger" style="padding: 10px 20px;">
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
      updateCounter('nama_jenis', 'nama_counter', 255);
      updateCounter('area', 'area_counter', 50);

      // Form validation
      const form = document.getElementById('dokumenForm');
      const namaInput = document.getElementById('nama_jenis');
      const kategoriInput = document.getElementById('id_kategori');

      // Real-time validation
      namaInput.addEventListener('input', function() {
        validateNama();
      });

      // Validation functions
      function validateNama() {
        const value = namaInput.value.trim();
        if (value === '') {
          setInvalid(namaInput, 'Nama jenis wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(namaInput, 'Nama jenis maksimal 255 karakter');
          return false;
        } else {
          setValid(namaInput);
          return true;
        }
      }

      function validateKategori() {
        const value = kategoriInput.value.trim();
        if (value === '') {
          setInvalid(kategoriInput, 'Kategori wajib dipilih');
          return false;
        } else {
          setValid(kategoriInput);
          return true;
        }
      }

      function setInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling.nextElementSibling;
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

        isValid &= validateNama();
        isValid &= validateKategori();

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