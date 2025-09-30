<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}

$isEdit = isset($data['action']) && $data['action'] === 'edit';
$berita = $data['berita'] ?? null;
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
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <span style="font-size: 18px; font-weight: 500;">
                    <?php echo $isEdit ? 'Edit Berita' : 'Tambah Berita'; ?>
                  </span>
                </div>
                <a href="index.php?controller=berita" class="btn btn-secondary btn-sm">
                  <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
              </div>

              <!-- Form -->
              <div class="card">
                <div class="card-body">
                  <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                    <!-- Judul -->
                    <div class="mb-3">
                      <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                      <input
                        type="text"
                        class="form-control"
                        id="judul"
                        name="judul"
                        value="<?php echo $isEdit ? htmlspecialchars($berita['judul']) : ''; ?>"
                        required
                        maxlength="255"
                      >
                      <div class="invalid-feedback">
                        Judul berita wajib diisi.
                      </div>
                    </div>

                    <!-- URL -->
                    <div class="mb-3">
                      <label for="url" class="form-label">URL Berita</label>
                      <input
                        type="url"
                        class="form-control"
                        id="url"
                        name="url"
                        value="<?php echo $isEdit ? htmlspecialchars($berita['url']) : ''; ?>"
                        placeholder="https://example.com/berita-terbaru"
                      >
                      <div class="form-text">URL eksternal ke berita (opsional)</div>
                    </div>

                    <!-- Summary -->
                    <div class="mb-3">
                      <label for="summary" class="form-label">Summary <span class="text-danger">*</span></label>
                      <textarea
                        class="form-control"
                        id="summary"
                        name="summary"
                        rows="5"
                        required
                      ><?php echo $isEdit ? htmlspecialchars($berita['summary']) : ''; ?></textarea>
                      <div class="invalid-feedback">
                        Summary berita wajib diisi.
                      </div>
                    </div>

                    <!-- Image Section -->
                    <div class="mb-3">
                      <label class="form-label">Gambar Berita</label>

                      <!-- Current Image Preview (Edit Mode) -->
                      <?php if ($isEdit && !empty($berita['image'])): ?>
                        <div class="mb-2">
                          <label class="form-text">Gambar saat ini:</label>
                          <div class="mt-1">
                            <?php if (filter_var($berita['image'], FILTER_VALIDATE_URL)): ?>
                              <img src="<?php echo htmlspecialchars($berita['image']); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                              <div class="form-text mt-1">URL: <?php echo htmlspecialchars($berita['image']); ?></div>
                            <?php elseif (file_exists($berita['image'])): ?>
                              <img src="<?php echo htmlspecialchars($berita['image']); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            <?php else: ?>
                              <div class="alert alert-warning p-2 mb-0">
                                <small>File gambar tidak ditemukan: <?php echo htmlspecialchars($berita['image']); ?></small>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endif; ?>

                      <!-- Image Options -->
                      <div class="row">
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body p-3">
                              <h6 class="card-title mb-2">
                                <i class="mdi mdi-upload"></i> Upload File Gambar
                              </h6>
                              <input
                                type="file"
                                class="form-control"
                                id="image_file"
                                name="image_file"
                                accept="image/jpeg,image/jpg,image/png,image/gif"
                              >
                              <div class="form-text">Format: JPG, PNG, GIF. Max: 2MB</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card">
                            <div class="card-body p-3">
                              <h6 class="card-title mb-2">
                                <i class="mdi mdi-link"></i> Atau URL Gambar
                              </h6>
                              <input
                                type="url"
                                class="form-control"
                                id="image_url"
                                name="image_url"
                                placeholder="https://example.com/image.jpg"
                              >
                              <div class="form-text">Link gambar dari internet</div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-text mt-2">
                        <i class="mdi mdi-information-outline"></i>
                        Jika kedua opsi diisi, file upload akan diprioritaskan.
                        <?php echo $isEdit ? 'Kosongkan keduanya untuk mempertahankan gambar lama.' : ''; ?>
                      </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                      <a href="index.php?controller=berita" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Batal
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i>
                        <?php echo $isEdit ? 'Update Berita' : 'Simpan Berita'; ?>
                      </button>
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

    // Form validation
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();

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
  </script>
</body>

</html>