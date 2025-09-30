<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
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
                  <i class="mdi mdi-account-card-details me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Setting Profile</span>
                </div>
              </div>

              <!-- Note Card -->
              <div class="card mb-4">
                <div class="card-body py-3">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-information text-primary me-2" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                      <strong>Note:</strong> Ubah data pada halaman profile. Klik pada salah satu list data profile untuk memunculkan form ubah data.
                    </div>
                  </div>
                </div>
              </div>

              <!-- Profile Sections -->
              <?php if (empty($data['profiles'])): ?>
                <div class="card">
                  <div class="card-body text-center py-5">
                    <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted mt-2">Tidak ada data profile.</p>
                  </div>
                </div>
              <?php else: ?>
                <div class="accordion" id="profileAccordion">
                  <?php foreach ($data['profiles'] as $index => $profile): ?>
                    <div class="card mb-3">
                      <div class="card-header p-0" id="heading<?php echo $profile['id_profile']; ?>">
                        <h2 class="mb-0">
                          <button
                            class="btn btn-link btn-block text-left py-3 px-4 text-decoration-none collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse<?php echo $profile['id_profile']; ?>"
                            aria-expanded="false"
                            aria-controls="collapse<?php echo $profile['id_profile']; ?>"
                            style="border: none; background: none; width: 100%;"
                          >
                            <div class="d-flex justify-content-between align-items-center">
                              <div class="d-flex align-items-center">
                                <i class="mdi mdi-file-document-outline me-3 text-primary" style="font-size: 20px;"></i>
                                <strong style="font-size: 16px; color: #495057;">
                                  <?php echo ucwords(str_replace('_', ' ', htmlspecialchars($profile['keterangan']))); ?>
                                </strong>
                              </div>
                              <i class="mdi mdi-chevron-down text-muted"></i>
                            </div>
                          </button>
                        </h2>
                      </div>

                      <div
                        id="collapse<?php echo $profile['id_profile']; ?>"
                        class="collapse"
                        aria-labelledby="heading<?php echo $profile['id_profile']; ?>"
                        data-bs-parent="#profileAccordion"
                      >
                        <div class="card-body">
                          <!-- Edit Form -->
                          <form method="POST" action="index.php?controller=profile&action=update" enctype="multipart/form-data">
                            <input type="hidden" name="id_profile" value="<?php echo $profile['id_profile']; ?>">
                            <input type="hidden" name="keterangan" value="<?php echo htmlspecialchars($profile['keterangan']); ?>">

                            <div class="mb-3">
                              <label class="form-label fw-bold">Text</label>

                              <!-- TinyMCE Editor -->
                              <textarea
                                name="isi_text"
                                id="editor_<?php echo $profile['id_profile']; ?>"
                                class="form-control tinymce-editor"
                                rows="15"
                              ><?php echo htmlspecialchars_decode($profile['isi']); ?></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                              <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse<?php echo $profile['id_profile']; ?>"
                              >
                                <i class="mdi mdi-close"></i> Tutup
                              </button>
                              <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-content-save"></i> Simpan
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <!-- TinyMCE Editor -->
  <script src="assets/vendors/tinymce/tinymce.min.js"></script>
  <script>
    // Initialize TinyMCE for all textareas with tinymce-editor class
    tinymce.init({
      selector: '.tinymce-editor',
      height: 400,
      menubar: false,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | blocks | ' +
               'bold italic forecolor | alignleft aligncenter ' +
               'alignright alignjustify | bullist numlist outdent indent | ' +
               'removeformat | help',
      content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
      branding: false,
      elementpath: false,
      statusbar: true,
      resize: true,
      setup: function (editor) {
        editor.on('init', function () {
          // Editor is ready
          console.log('TinyMCE editor initialized for:', editor.id);
        });
      }
    });

    // Handle accordion collapse events to destroy/reinit TinyMCE if needed
    document.addEventListener('DOMContentLoaded', function() {
      const accordionItems = document.querySelectorAll('[data-bs-toggle="collapse"]');

      accordionItems.forEach(function(item) {
        item.addEventListener('click', function() {
          const target = this.getAttribute('data-bs-target');
          const targetElement = document.querySelector(target);

          // Small delay to ensure the collapse animation completes
          setTimeout(function() {
            if (targetElement && targetElement.classList.contains('show')) {
              // Accordion is opening, reinit TinyMCE if needed
              const textareas = targetElement.querySelectorAll('.tinymce-editor');
              textareas.forEach(function(textarea) {
                if (!tinymce.get(textarea.id)) {
                  tinymce.init({
                    selector: '#' + textarea.id,
                    height: 400,
                    menubar: false,
                    plugins: [
                      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                      'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                             'bold italic forecolor | alignleft aligncenter ' +
                             'alignright alignjustify | bullist numlist outdent indent | ' +
                             'removeformat | help',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                    branding: false,
                    elementpath: false,
                    statusbar: true,
                    resize: true
                  });
                }
              });
            }
          }, 350);
        });
      });
    });
  </script>
</body>

</html>