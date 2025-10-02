<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Get single FAQ
$faq = $this->faqModel->getSingleFAQ();
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
                  <i class="mdi mdi-help-circle-outline me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Edit FAQ</span>
                </div>
              </div>

              <!-- Note Card -->
              <div class="card mb-4">
                <div class="card-body py-3">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-information text-primary me-2" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                      <strong>Note:</strong> Halaman ini untuk mengedit konten FAQ yang akan ditampilkan kepada publik.
                    </div>
                  </div>
                </div>
              </div>

              <!-- FAQ Edit Form -->
              <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                  <form method="POST" action="index.php?controller=faq&action=update" enctype="multipart/form-data">
                    <!-- Penulis Field -->
                    <div class="mb-4">
                      <label class="form-label fw-semibold text-dark mb-2">
                        <i class="mdi mdi-account-outline me-1 text-primary"></i>
                        Penulis <span class="text-danger">*</span>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-lg"
                        name="penulis"
                        placeholder="Masukkan nama penulis"
                        value="<?php echo htmlspecialchars($faq['penulis'] ?? ''); ?>"
                        required>
                      <small class="text-muted d-block mt-1">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Nama penulis atau admin yang mengelola FAQ ini
                      </small>
                    </div>

                    <!-- Tags Field -->
                    <div class="mb-4">
                      <label class="form-label fw-semibold text-dark mb-2">
                        <i class="mdi mdi-tag-multiple-outline me-1 text-primary"></i>
                        Tags (Opsional)
                      </label>
                      <input
                        type="text"
                        class="form-control"
                        name="tags"
                        placeholder="Contoh: umum, layanan, prosedur"
                        value="<?php echo htmlspecialchars($faq['tags'] ?? ''); ?>">
                      <small class="text-muted d-block mt-1">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Pisahkan dengan koma untuk multiple tags
                      </small>
                    </div>

                    <!-- Isi Konten -->
                    <div class="mb-4">
                      <label class="form-label fw-semibold text-dark mb-2">
                        Isi Konten FAQ <span class="text-danger">*</span>
                      </label>
                      <div class="editor-wrapper">
                        <textarea
                          name="isi_text"
                          id="faqEditor"
                          class="form-control"
                          rows="15"><?php echo htmlspecialchars_decode($faq['isi']); ?></textarea>
                      </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                      <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
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

  <style>
    /* Form Control */
    .form-control-lg {
      padding: 0.75rem 1rem;
      font-size: 1rem;
      border-radius: 6px;
      border-color: #dee2e6;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    /* Editor Wrapper */
    .editor-wrapper {
      border-radius: 6px;
      overflow: hidden;
    }

    /* Buttons */
    .btn-lg {
      border-radius: 6px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    /* Card */
    .card {
      border-radius: 8px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .form-control-lg {
        font-size: 0.95rem;
        padding: 0.65rem 0.9rem;
      }

      .btn-lg {
        font-size: 0.9rem;
        padding: 0.5rem 2rem;
      }
    }
  </style>

  <?php include 'template/script.php'; ?>

  <!-- TinyMCE Editor -->
  <script src="https://cdn.tiny.cloud/1/z0t4wwtn9a2wpsk59ee400jsup9j2wusunqyvvezelo6imd8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
  <script>
    // Initialize TinyMCE
    document.addEventListener('DOMContentLoaded', function() {
      tinymce.init({
        selector: '#faqEditor',
        height: 500,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        file_picker_types: 'file image media',
        file_picker_callback: function(cb, value, meta) {
          var input = document.createElement('input');
          input.setAttribute('type', 'file');

          if (meta.filetype === 'image') {
            input.setAttribute('accept', 'image/*');
          } else if (meta.filetype === 'media') {
            input.setAttribute('accept', 'video/*,audio/*');
          } else {
            input.setAttribute('accept', 'image/*,.pdf,.doc,.docx');
          }

          input.onchange = function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?controller=faq&action=upload_image');

            xhr.onload = function() {
              if (xhr.status === 200) {
                try {
                  var response = JSON.parse(xhr.responseText);

                  if (response && response.success === true) {
                    if (response.url) {
                      cb(response.url, { title: file.name });
                    } else if (response.location) {
                      cb(response.location, { title: file.name });
                    } else {
                      alert('No URL returned from server');
                    }
                  } else {
                    var errorMsg = response && response.message ? response.message : 'Upload failed';
                    alert(errorMsg);
                  }
                } catch (e) {
                  alert('Invalid response from server: ' + e.message);
                }
              } else if (xhr.status === 403) {
                alert('Session expired or unauthorized. Please refresh the page.');
              } else {
                alert('HTTP Error: ' + xhr.status);
              }
            };

            xhr.onerror = function() {
              alert('File upload failed due to a network error.');
            };

            xhr.send(formData);
          };

          input.click();
        }
      });
    });
  </script>
</body>

</html>
