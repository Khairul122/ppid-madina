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
                <!-- Add New Button -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                  <i class="mdi mdi-plus"></i> Tambah Data Baru
                </button>
              </div>

              <!-- Note Card -->
              <div class="card mb-4">
                <div class="card-body py-3">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-information text-primary me-2" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                      <strong>Note:</strong> Ubah data pada halaman profile. Gunakan tab untuk mengelola data berdasarkan kategori.
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tab Navigation -->
              <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <?php foreach ($data['categories'] as $index => $category): ?>
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>"
                      id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                      type="button"
                      role="tab"
                      aria-controls="<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                      aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                      <?php echo htmlspecialchars($category); ?>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>

              <!-- Tab Content -->
              <div class="tab-content" id="profileTabContent">
                <?php foreach ($data['categories'] as $index => $category): ?>
                  <div
                    class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
                    id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                    role="tabpanel"
                    aria-labelledby="<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab">
                    <!-- Existing Profiles in this Category -->
                    <?php if (empty($data['groupedProfiles'][$category])): ?>
                      <div class="card">
                        <div class="card-body text-center py-5">
                          <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                          <p class="text-muted mt-2">Belum ada data dalam kategori ini.</p>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="accordion" id="profileAccordion_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                        <?php foreach ($data['groupedProfiles'][$category] as $profile): ?>
                          <div class="card mb-3">
                            <div class="card-header p-0" id="heading_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                              <h2 class="mb-0">
                                <button
                                  class="btn btn-link btn-block text-left py-3 px-4 text-decoration-none collapsed"
                                  type="button"
                                  data-bs-toggle="collapse"
                                  data-bs-target="#collapse_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                  aria-expanded="false"
                                  aria-controls="collapse_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                  style="border: none; background: none; width: 100%;">
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
                              id="collapse_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                              class="collapse"
                              aria-labelledby="heading_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                              data-bs-parent="#profileAccordion_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                              <div class="card-body">
                                <!-- Edit Form -->
                                <form method="POST" action="index.php?controller=profile&action=update" enctype="multipart/form-data">
                                  <input type="hidden" name="id_profile" value="<?php echo $profile['id_profile']; ?>">
                                  <input type="hidden" name="keterangan" value="<?php echo htmlspecialchars($profile['keterangan']); ?>">

                                  <?php 
                                  // Check if the profile content is a PDF file or image
                                  $extension = pathinfo($profile['isi'], PATHINFO_EXTENSION);
                                  $is_pdf = (strtolower($extension) === 'pdf' && file_exists($profile['isi'])); 
                                  $is_image = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($profile['isi']);
                                  ?>
                                  
                                  <?php if ($is_pdf || $is_image): ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">File Saat Ini</label>
                                      <div class="alert alert-info">
                                        <?php if ($is_pdf): ?>
                                          <i class="mdi mdi-file-pdf text-danger me-2" style="font-size: 1.5em;"></i>
                                          <span>File PDF: <?php echo basename($profile['isi']); ?></span>
                                          <a href="<?php echo $profile['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Klik Disini</a>
                                        <?php elseif ($is_image): ?>
                                          <i class="mdi mdi-file-image text-primary me-2" style="font-size: 1.5em;"></i>
                                          <span>File Gambar: <?php echo basename($profile['isi']); ?></span>
                                          <a href="<?php echo $profile['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat Gambar</a>
                                        <?php endif; ?>
                                      </div>
                                      <label class="form-label fw-bold mt-3">Upload File Baru (PDF atau Gambar)</label>
                                      <input type="file" name="isi" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp" />
                                      <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                                    </div>
                                  <?php else: ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">Text</label>
                                      <div id="editor_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>" class="quill-editor" style="height: 400px;"></div>
                                      <textarea
                                        name="isi_text"
                                        id="editor_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>_hidden"
                                        class="d-none"><?php echo htmlspecialchars_decode($profile['isi']); ?></textarea>
                                    </div>
                                  <?php endif; ?>

                                  <!-- Action Buttons -->
                                  <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button
                                      type="button"
                                      class="btn btn-secondary"
                                      data-bs-toggle="collapse"
                                      data-bs-target="#collapse_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
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
                <?php endforeach; ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Profile Modal -->
  <div class="modal fade" id="addProfileModal" tabindex="-1" aria-labelledby="addProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProfileModalLabel">Tambah Data Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=profile&action=create" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Keterangan</label>
                  <select class="form-select" name="keterangan" id="keteranganSelect" required>
                    <option value="" disabled selected>-- Pilih salah satu --</option>
                    <option value="PPID">PPID</option>
                    <option value="DAERAH">Daerah</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Nama Keterangan Baru</label>
                  <input type="text" class="form-control" name="keterangan_baru" placeholder="Masukkan nama keterangan baru" required>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Isi Konten (Text Editor)</label>
              <div id="newEditor" class="quill-editor" style="height: 400px;"></div>
              <textarea name="isi_text" id="newEditor_hidden" class="d-none"></textarea>
              <small class="text-muted">Gunakan toolbar untuk format text, upload gambar, dan sisipkan file</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <!-- Quill WYSIWYG Editor -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

  <style>
    /* Custom styling untuk Quill */
    .quill-editor {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
    }

    .ql-toolbar {
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #e0e0e0;
    }

    .ql-container {
      border-bottom-left-radius: 8px;
      border-bottom-right-radius: 8px;
      font-size: 14px;
      line-height: 1.6;
    }

    .ql-editor {
      min-height: 350px;
      padding: 15px;
    }

    .ql-editor.ql-blank::before {
      color: #adb5bd;
      font-style: italic;
    }

    /* Custom button untuk file upload */
    .ql-file {
      width: 28px !important;
      height: 24px !important;
    }

    .ql-file::before {
      content: '📎';
      font-size: 16px;
      line-height: 24px;
    }

    .ql-file:hover {
      color: #06c;
      cursor: pointer;
    }

    /* Tooltip untuk button file */
    .ql-file::after {
      content: 'Upload File (PDF, DOC, etc)';
      position: absolute;
      bottom: -30px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s;
      z-index: 1000;
    }

    .ql-file:hover::after {
      opacity: 1;
    }
  </style>

  <script>
    // Store all Quill instances
    let quillEditors = {};

    // Function untuk handle upload file (PDF, DOC, etc) di Quill
    function createFileHandler(quillInstance) {
      return function() {
        console.log('File handler called');

        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar');

        console.log('File input created, opening file picker...');
        input.click();

        input.onchange = async () => {
          const file = input.files[0];

          if (!file) {
            console.log('No file selected');
            return;
          }

          console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

          // Validasi ukuran file (max 10MB)
          if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB');
            return;
          }

          const formData = new FormData();
          formData.append('file', file);

          console.log('Uploading file...');

          try {
            const response = await fetch('index.php?controller=profile&action=uploadFile', {
              method: 'POST',
              body: formData
            });

            console.log('Response status:', response.status);

            const result = await response.json();
            console.log('Upload result:', result);

            if (result.success) {
              // Insert file as link with icon
              const range = quillInstance.getSelection(true) || { index: 0 };
              const fileName = result.filename || file.name;

              // Insert icon and filename as link
              const linkText = '📎 ' + fileName;
              quillInstance.insertText(range.index, linkText, 'link', result.url);
              quillInstance.insertText(range.index + linkText.length, ' ');
              quillInstance.setSelection(range.index + linkText.length + 1);

              console.log('File link inserted into editor');
              alert('File berhasil diupload: ' + fileName);
            } else {
              console.error('Upload failed:', result.message);
              alert('Gagal upload file: ' + result.message);
            }
          } catch (error) {
            console.error('Upload error:', error);
            alert('Error saat upload file: ' + error.message);
          }
        };
      };
    }

    // Function untuk handle upload image di Quill
    function createImageHandler(quillInstance) {
      return function() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
          const file = input.files[0];
          if (!file) return;

          // Validasi ukuran file (max 5MB)
          if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB');
            return;
          }

          const formData = new FormData();
          formData.append('image', file);

          try {
            const response = await fetch('index.php?controller=profile&action=uploadImage', {
              method: 'POST',
              body: formData
            });

            const result = await response.json();

            if (result.success) {
              // Get selection and insert image
              const range = quillInstance.getSelection(true);
              quillInstance.insertEmbed(range.index, 'image', result.url);
              quillInstance.setSelection(range.index + 1);
            } else {
              alert('Gagal upload image: ' + result.message);
            }
          } catch (error) {
            alert('Error saat upload image: ' + error.message);
          }
        };
      };
    }

    // Initialize Quill editor
    function initializeQuillEditor(elementId, hiddenTextareaId, initialContent = '') {
      const container = document.getElementById(elementId);

      if (!container) {
        console.error('Container not found:', elementId);
        return null;
      }

      console.log('Initializing Quill for:', elementId);

      // Destroy existing instance if any
      if (quillEditors[elementId]) {
        console.log('Destroying existing instance:', elementId);
        quillEditors[elementId] = null;
      }

      try {
        // Create Quill instance
        const quill = new Quill(`#${elementId}`, {
          theme: 'snow',
          modules: {
            toolbar: [
              [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
              [{ 'font': [] }],
              [{ 'size': ['small', false, 'large', 'huge'] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ 'color': [] }, { 'background': [] }],
              [{ 'script': 'sub'}, { 'script': 'super' }],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'indent': '-1'}, { 'indent': '+1' }],
              [{ 'align': [] }],
              ['link', 'image', 'video', 'file'],
              ['clean']
            ]
          },
          placeholder: 'Tulis konten di sini...'
        });

        console.log('Quill instance created for:', elementId);

        // Set custom handlers after Quill is created
        const toolbar = quill.getModule('toolbar');
        if (toolbar) {
          toolbar.addHandler('file', createFileHandler(quill));
          toolbar.addHandler('image', createImageHandler(quill));
          console.log('Handlers added for:', elementId);
        }

        // Set initial content
        if (initialContent) {
          quill.root.innerHTML = initialContent;
        }

        // Sync with hidden textarea
        const hiddenTextarea = document.getElementById(hiddenTextareaId);
        if (hiddenTextarea) {
          quill.on('text-change', function() {
            hiddenTextarea.value = quill.root.innerHTML;
          });
        }

        // Store instance
        quillEditors[elementId] = quill;

        console.log('Quill initialized successfully for:', elementId);
        return quill;
      } catch (error) {
        console.error('Error initializing Quill:', error);
        return null;
      }
    }

    // Initialize all Quill editors
    function initializeAllQuillEditors() {
      // Initialize existing profile editors
      document.querySelectorAll('.quill-editor').forEach(function(element) {
        const editorId = element.id;

        // Skip if element doesn't have an ID
        if (!editorId) return;

        // Skip if already initialized
        if (quillEditors[editorId]) return;

        const hiddenTextareaId = editorId + '_hidden';
        const hiddenTextarea = document.getElementById(hiddenTextareaId);
        const initialContent = hiddenTextarea ? hiddenTextarea.value : '';

        initializeQuillEditor(editorId, hiddenTextareaId, initialContent);
      });
    }

    // Destroy all Quill editors
    function destroyAllQuillEditors() {
      Object.keys(quillEditors).forEach(function(key) {
        if (quillEditors[key]) {
          quillEditors[key] = null;
        }
      });
      quillEditors = {};
    }

    // Document ready
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize on page load
      setTimeout(function() {
        initializeAllQuillEditors();
      }, 300);

      // Reinitialize when tab changes
      document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tabButton) {
        tabButton.addEventListener('shown.bs.tab', function() {
          setTimeout(function() {
            initializeAllQuillEditors();
          }, 150);
        });
      });

      // Reinitialize when accordion opens
      document.querySelectorAll('.collapse').forEach(function(collapse) {
        collapse.addEventListener('shown.bs.collapse', function() {
          setTimeout(function() {
            initializeAllQuillEditors();
          }, 150);
        });
      });

      // Modal events
      const addProfileModal = document.getElementById('addProfileModal');
      if (addProfileModal) {
        // Initialize when modal opens
        addProfileModal.addEventListener('shown.bs.modal', function() {
          console.log('Modal opened, initializing Quill editor...');

          // Destroy existing newEditor if any
          if (quillEditors['newEditor']) {
            console.log('Destroying existing newEditor');
            quillEditors['newEditor'] = null;
          }

          // Wait for modal animation to complete
          setTimeout(function() {
            const editorElement = document.getElementById('newEditor');
            console.log('Editor element found:', editorElement);

            if (editorElement) {
              // Clear any existing Quill instance
              editorElement.innerHTML = '';

              // Initialize new editor
              initializeQuillEditor('newEditor', 'newEditor_hidden', '');
              console.log('Quill editor initialized');
            } else {
              console.error('newEditor element not found!');
            }
          }, 300);
        });

        // Destroy and reset when modal closes
        addProfileModal.addEventListener('hidden.bs.modal', function() {
          console.log('Modal closed, cleaning up...');

          // Destroy editor
          if (quillEditors['newEditor']) {
            quillEditors['newEditor'] = null;
          }

          // Clear editor container
          const editorElement = document.getElementById('newEditor');
          if (editorElement) {
            editorElement.innerHTML = '';
          }

          // Reset form
          const form = addProfileModal.querySelector('form');
          if (form) {
            form.reset();
          }

          // Clear hidden textarea
          const hiddenTextarea = document.getElementById('newEditor_hidden');
          if (hiddenTextarea) {
            hiddenTextarea.value = '';
          }
        });
      }
    });
  </script>
</body>

</html>