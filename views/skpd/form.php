<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$page_title = $is_edit ? 'Edit SKPD' : 'Tambah SKPD';
$action_url = $is_edit ? 'update' : 'create';
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
              <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                  <i class="mdi mdi-office-building me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;"><?php echo $page_title; ?></span>
                </div>
                <a href="index.php?controller=skpd&action=index" class="btn btn-outline-secondary">
                  <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
              </div>

              <!-- Form Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <form method="POST" action="index.php?controller=skpd&action=<?php echo $action_url; ?>" id="skpdForm">
                    <?php if ($is_edit): ?>
                      <input type="hidden" name="id_skpd" value="<?php echo $skpd_data['id_skpd']; ?>">
                    <?php endif; ?>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="nama_skpd" class="form-label fw-bold">
                          Nama SKPD <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nama_skpd"
                               name="nama_skpd"
                               value="<?php echo htmlspecialchars($form_data['nama_skpd'] ?? ($skpd_data['nama_skpd'] ?? '')); ?>"
                               placeholder="Masukkan nama SKPD/Operasional"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="nama_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control"
                                  id="alamat"
                                  name="alamat"
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap SKPD"
                                  maxlength="255"
                                  style="padding: 12px; border-radius: 6px;"><?php echo htmlspecialchars($form_data['alamat'] ?? ($skpd_data['alamat'] ?? '')); ?></textarea>
                        <small class="text-muted"><span id="alamat_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="telepon" class="form-label fw-bold">Telepon</label>
                        <input type="tel"
                               class="form-control"
                               id="telepon"
                               name="telepon"
                               value="<?php echo htmlspecialchars($form_data['telepon'] ?? ($skpd_data['telepon'] ?? '')); ?>"
                               placeholder="Contoh: 0621-12345"
                               maxlength="20"
                               style="padding: 12px; border-radius: 6px;">
                        <small class="text-muted"><span id="telepon_counter">0</span>/20 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?php echo htmlspecialchars($form_data['email'] ?? ($skpd_data['email'] ?? '')); ?>"
                               placeholder="contoh@skpd.go.id"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;">
                        <small class="text-muted"><span id="email_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori</label>
                        <input type="text"
                               class="form-control"
                               id="kategori"
                               name="kategori"
                               value="<?php echo htmlspecialchars($form_data['kategori'] ?? ($skpd_data['kategori'] ?? '')); ?>"
                               placeholder="Masukkan kategori SKPD/Operasional"
                               maxlength="100"
                               style="padding: 12px; border-radius: 6px;">
                        <small class="text-muted"><span id="kategori_counter">0</span>/100 karakter</small>
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
                      <a href="index.php?controller=skpd&action=index" class="btn btn-outline-danger" style="padding: 10px 20px;">
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
      updateCounter('nama_skpd', 'nama_counter', 255);
      updateCounter('alamat', 'alamat_counter', 255);
      updateCounter('telepon', 'telepon_counter', 20);
      updateCounter('email', 'email_counter', 255);
      updateCounter('kategori', 'kategori_counter', 100);

      // Form validation
      const form = document.getElementById('skpdForm');
      const namaInput = document.getElementById('nama_skpd');
      const teleponInput = document.getElementById('telepon');
      const emailInput = document.getElementById('email');

      // Real-time validation
      namaInput.addEventListener('input', function() {
        validateNama();
      });

      teleponInput.addEventListener('input', function() {
        validateTelepon();
      });

      emailInput.addEventListener('input', function() {
        validateEmail();
      });

      // Validation functions
      function validateNama() {
        const value = namaInput.value.trim();
        if (value === '') {
          setInvalid(namaInput, 'Nama SKPD wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(namaInput, 'Nama SKPD maksimal 255 karakter');
          return false;
        } else {
          setValid(namaInput);
          return true;
        }
      }

      function validateTelepon() {
        const value = teleponInput.value.trim();
        if (value !== '' && !/^[0-9\-\+\(\)\s]+$/.test(value)) {
          setInvalid(teleponInput, 'Format telepon tidak valid (hanya angka, spasi, tanda hubung, kurung, dan +)');
          return false;
        } else if (value.length > 20) {
          setInvalid(teleponInput, 'Telepon maksimal 20 karakter');
          return false;
        } else {
          setValid(teleponInput);
          return true;
        }
      }

      function validateEmail() {
        const value = emailInput.value.trim();
        if (value !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
          setInvalid(emailInput, 'Format email tidak valid');
          return false;
        } else if (value.length > 255) {
          setInvalid(emailInput, 'Email maksimal 255 karakter');
          return false;
        } else {
          setValid(emailInput);
          return true;
        }
      }

      function validateKategori() {
        const value = kategoriInput.value.trim();
        if (value !== '' && value.length > 100) {
          setInvalid(kategoriInput, 'Kategori maksimal 100 karakter');
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
        isValid &= validateTelepon();
        isValid &= validateEmail();
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
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(function(input) {
          input.classList.remove('is-valid', 'is-invalid');
        });

        // Reset character counters
        setTimeout(function() {
          updateCounter('nama_skpd', 'nama_counter', 255);
          updateCounter('alamat', 'alamat_counter', 255);
          updateCounter('telepon', 'telepon_counter', 20);
          updateCounter('email', 'email_counter', 255);
          updateCounter('kategori', 'kategori_counter', 100);
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