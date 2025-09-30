<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

// Get form data from session if available (for validation errors)
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

$page_title = $is_edit ? 'Edit Petugas' : 'Tambah Petugas';
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
                  <i class="mdi mdi-account-multiple me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;"><?php echo $page_title; ?></span>
                </div>
                <a href="index.php?controller=petugas&action=index" class="btn btn-outline-secondary">
                  <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
              </div>

              <!-- Form Card -->
              <div class="card" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body p-4">
                  <form method="POST" action="index.php?controller=petugas&action=<?php echo $action_url; ?>" id="petugasForm">
                    <?php if ($is_edit): ?>
                      <input type="hidden" name="id_petugas" value="<?php echo $petugas_data['id_petugas']; ?>">
                    <?php endif; ?>

                    <!-- SKPD Selection -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="id_skpd" class="form-label fw-bold">
                          SKPD <span style="color: red;">*</span>
                        </label>
                        <select class="form-select"
                                id="id_skpd"
                                name="id_skpd"
                                style="padding: 12px; border-radius: 6px;"
                                required>
                          <option value="">-- Pilih SKPD --</option>
                          <?php foreach ($skpd_list as $skpd): ?>
                            <option value="<?php echo $skpd['id_skpd']; ?>"
                                    <?php echo (($form_data['id_skpd'] ?? ($petugas_data['id_skpd'] ?? '')) == $skpd['id_skpd']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($skpd['nama_skpd']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- Nama Petugas -->
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label for="nama_petugas" class="form-label fw-bold">
                          Nama Petugas <span style="color: red;">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nama_petugas"
                               name="nama_petugas"
                               value="<?php echo htmlspecialchars($form_data['nama_petugas'] ?? ($petugas_data['nama_petugas'] ?? '')); ?>"
                               placeholder="Masukkan nama lengkap petugas"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted"><span id="nama_counter">0</span>/255 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- Email and No Kontak -->
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-bold">
                          Email <span style="color: red;">*</span>
                        </label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?php echo htmlspecialchars($form_data['email'] ?? ($petugas_data['email'] ?? '')); ?>"
                               placeholder="contoh@email.com"
                               maxlength="255"
                               style="padding: 12px; border-radius: 6px;"
                               required>
                        <small class="text-muted">Email akan digunakan untuk login</small>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="no_kontak" class="form-label fw-bold">No Kontak</label>
                        <input type="tel"
                               class="form-control"
                               id="no_kontak"
                               name="no_kontak"
                               value="<?php echo htmlspecialchars($form_data['no_kontak'] ?? ($petugas_data['no_kontak'] ?? '')); ?>"
                               placeholder="Contoh: 0812-3456-7890"
                               maxlength="20"
                               style="padding: 12px; border-radius: 6px;">
                        <small class="text-muted"><span id="kontak_counter">0</span>/20 karakter</small>
                        <div class="invalid-feedback"></div>
                      </div>
                    </div>

                    <!-- Password -->
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-bold">
                          Password <?php echo $is_edit ? '' : '<span style="color: red;">*</span>'; ?>
                        </label>
                        <div class="input-group">
                          <input type="password"
                                 class="form-control"
                                 id="password"
                                 name="password"
                                 placeholder="<?php echo $is_edit ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password'; ?>"
                                 minlength="6"
                                 style="padding: 12px; border-radius: 6px 0 0 6px;"
                                 <?php echo $is_edit ? '' : 'required'; ?>>
                          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="mdi mdi-eye" id="passwordIcon"></i>
                          </button>
                        </div>
                        <small class="text-muted">
                          <?php echo $is_edit ? 'Kosongkan jika tidak ingin mengubah password' : 'Password minimal 6 karakter'; ?>
                        </small>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label fw-bold">
                          Konfirmasi Password <?php echo $is_edit ? '' : '<span style="color: red;">*</span>'; ?>
                        </label>
                        <div class="input-group">
                          <input type="password"
                                 class="form-control"
                                 id="confirm_password"
                                 name="confirm_password"
                                 placeholder="<?php echo $is_edit ? 'Konfirmasi password baru' : 'Ulangi password'; ?>"
                                 minlength="6"
                                 style="padding: 12px; border-radius: 6px 0 0 6px;"
                                 <?php echo $is_edit ? '' : 'required'; ?>>
                          <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                            <i class="mdi mdi-eye" id="confirmPasswordIcon"></i>
                          </button>
                        </div>
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
                      <a href="index.php?controller=petugas&action=index" class="btn btn-outline-danger" style="padding: 10px 20px;">
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

        if (input && counter) {
          input.addEventListener('input', updateCount);
          updateCount(); // Initial count
        }
      }

      // Initialize character counters
      updateCounter('nama_petugas', 'nama_counter', 255);
      updateCounter('no_kontak', 'kontak_counter', 20);

      // Password visibility toggles
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      const passwordIcon = document.getElementById('passwordIcon');

      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const confirmPassword = document.getElementById('confirm_password');
      const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

      togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        passwordIcon.classList.toggle('mdi-eye');
        passwordIcon.classList.toggle('mdi-eye-off');
      });

      toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        confirmPasswordIcon.classList.toggle('mdi-eye');
        confirmPasswordIcon.classList.toggle('mdi-eye-off');
      });

      // Form validation
      const form = document.getElementById('petugasForm');
      const namaInput = document.getElementById('nama_petugas');
      const emailInput = document.getElementById('email');
      const kontakInput = document.getElementById('no_kontak');
      const skpdSelect = document.getElementById('id_skpd');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirm_password');

      const isEdit = <?php echo $is_edit ? 'true' : 'false'; ?>;

      // Real-time validation
      namaInput.addEventListener('input', function() {
        validateNama();
      });

      emailInput.addEventListener('input', function() {
        validateEmail();
      });

      kontakInput.addEventListener('input', function() {
        validateKontak();
      });

      skpdSelect.addEventListener('change', function() {
        validateSKPD();
      });

      passwordInput.addEventListener('input', function() {
        validatePassword();
        validateConfirmPassword();
      });

      confirmPasswordInput.addEventListener('input', function() {
        validateConfirmPassword();
      });

      // Validation functions
      function validateNama() {
        const value = namaInput.value.trim();
        if (value === '') {
          setInvalid(namaInput, 'Nama petugas wajib diisi');
          return false;
        } else if (value.length > 255) {
          setInvalid(namaInput, 'Nama petugas maksimal 255 karakter');
          return false;
        } else {
          setValid(namaInput);
          return true;
        }
      }

      function validateEmail() {
        const value = emailInput.value.trim();
        if (value === '') {
          setInvalid(emailInput, 'Email wajib diisi');
          return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
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

      function validateKontak() {
        const value = kontakInput.value.trim();
        if (value !== '' && !/^[0-9\-\+\(\)\s]+$/.test(value)) {
          setInvalid(kontakInput, 'Format no kontak tidak valid (hanya angka, spasi, tanda hubung, kurung, dan +)');
          return false;
        } else if (value.length > 20) {
          setInvalid(kontakInput, 'No kontak maksimal 20 karakter');
          return false;
        } else {
          setValid(kontakInput);
          return true;
        }
      }

      function validateSKPD() {
        const value = skpdSelect.value;
        if (value === '') {
          setInvalid(skpdSelect, 'SKPD wajib dipilih');
          return false;
        } else {
          setValid(skpdSelect);
          return true;
        }
      }

      function validatePassword() {
        const value = passwordInput.value;
        if (!isEdit && value === '') {
          setInvalid(passwordInput, 'Password wajib diisi untuk petugas baru');
          return false;
        } else if (value !== '' && value.length < 6) {
          setInvalid(passwordInput, 'Password minimal 6 karakter');
          return false;
        } else {
          setValid(passwordInput);
          return true;
        }
      }

      function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (!isEdit && password !== '' && confirmPassword === '') {
          setInvalid(confirmPasswordInput, 'Konfirmasi password wajib diisi');
          return false;
        } else if (password !== confirmPassword) {
          setInvalid(confirmPasswordInput, 'Konfirmasi password tidak cocok');
          return false;
        } else {
          setValid(confirmPasswordInput);
          return true;
        }
      }

      function setInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedback = input.parentElement.querySelector('.invalid-feedback') ||
                        input.nextElementSibling?.nextElementSibling;
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

        isValid &= validateSKPD();
        isValid &= validateNama();
        isValid &= validateEmail();
        isValid &= validateKontak();
        isValid &= validatePassword();
        isValid &= validateConfirmPassword();

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
          updateCounter('nama_petugas', 'nama_counter', 255);
          updateCounter('no_kontak', 'kontak_counter', 20);
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