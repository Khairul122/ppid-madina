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
                  <i class="mdi mdi-information-outline me-2" style="font-size: 24px;"></i>
                  <span style="font-size: 18px; font-weight: 500;">Layanan Informasi Publik</span>
                </div>
                <!-- Add New Button -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLayananModal">
                  <i class="mdi mdi-plus"></i> Tambah Data Baru
                </button>
              </div>

              <!-- Note Card -->
              <div class="card mb-4">
                <div class="card-body py-3">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-information text-primary me-2" style="font-size: 20px; margin-top: 2px;"></i>
                    <div>
                      <strong>Note:</strong> Kelola data layanan informasi publik. Gunakan tab untuk mengelola data berdasarkan kategori layanan.
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tab Navigation -->
              <ul class="nav nav-tabs" id="layananTabs" role="tablist">
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
              <div class="tab-content" id="layananTabContent">
                <?php foreach ($data['categories'] as $index => $category): ?>
                  <div
                    class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>"
                    id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                    role="tabpanel"
                    aria-labelledby="<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab">
                    <!-- Existing Layanan in this Category -->
                    <?php if (empty($data['groupedLayanan'][$category])): ?>
                      <div class="card">
                        <div class="card-body text-center py-5">
                          <i class="mdi mdi-information-outline" style="font-size: 48px; color: #ccc;"></i>
                          <p class="text-muted mt-2">Belum ada data dalam kategori ini.</p>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="accordion" id="layananAccordion_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                        <?php foreach ($data['groupedLayanan'][$category] as $layanan): ?>
                          <div class="card mb-3">
                            <div class="card-header p-0" id="heading_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                              <h2 class="mb-0">
                                <button
                                  class="btn btn-link btn-block text-left py-3 px-4 text-decoration-none collapsed"
                                  type="button"
                                  data-bs-toggle="collapse"
                                  data-bs-target="#collapse_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                  aria-expanded="false"
                                  aria-controls="collapse_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                  style="border: none; background: none; width: 100%;">
                                  <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                      <i class="mdi mdi-file-document-outline me-3 text-primary" style="font-size: 20px;"></i>
                                      <div>
                                        <strong style="font-size: 16px; color: #495057;">
                                          <?php echo htmlspecialchars($layanan['nama_layanan']); ?>
                                        </strong>
                                        <?php if (!empty($layanan['sub_layanan'])): ?>
                                          <br>
                                          <small class="text-muted">Sub: <?php echo htmlspecialchars($layanan['sub_layanan']); ?></small>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                    <i class="mdi mdi-chevron-down text-muted"></i>
                                  </div>
                                </button>
                              </h2>
                            </div>

                            <div
                              id="collapse_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                              class="collapse"
                              aria-labelledby="heading_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                              data-bs-parent="#layananAccordion_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                              <div class="card-body">
                                <!-- Edit Form -->
                                <form method="POST" action="index.php?controller=layanan_informasi&action=update" enctype="multipart/form-data">
                                  <input type="hidden" name="id_layanan" value="<?php echo $layanan['id_layanan']; ?>">

                                  <!-- Sub Layanan Field -->
                                  <div class="mb-3">
                                    <div class="form-check mb-2">
                                      <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="has_sub_layanan"
                                        id="hasSubLayanan_<?php echo $layanan['id_layanan']; ?>"
                                        onchange="toggleSubLayanan(<?php echo $layanan['id_layanan']; ?>)"
                                        <?php echo !empty($layanan['sub_layanan']) ? 'checked' : ''; ?>>
                                      <label class="form-check-label" for="hasSubLayanan_<?php echo $layanan['id_layanan']; ?>">
                                        Aktifkan Sub Layanan
                                      </label>
                                    </div>
                                    <input
                                      type="text"
                                      class="form-control sub-layanan-input"
                                      name="sub_layanan"
                                      id="subLayanan_<?php echo $layanan['id_layanan']; ?>"
                                      placeholder="Masukkan sub layanan"
                                      value="<?php echo htmlspecialchars($layanan['sub_layanan'] ?? ''); ?>"
                                      style="display: <?php echo !empty($layanan['sub_layanan']) ? 'block' : 'none'; ?>;">
                                  </div>

                                  <?php
                                  // Check if the layanan content is a PDF file or image
                                  $extension = pathinfo($layanan['isi'], PATHINFO_EXTENSION);
                                  $is_pdf = (strtolower($extension) === 'pdf' && file_exists($layanan['isi']));
                                  $is_image = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($layanan['isi']);
                                  ?>

                                  <?php if ($is_pdf || $is_image): ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">File Saat Ini</label>
                                      <div class="alert alert-info">
                                        <?php if ($is_pdf): ?>
                                          <i class="mdi mdi-file-pdf text-danger me-2" style="font-size: 1.5em;"></i>
                                          <span>File PDF: <?php echo basename($layanan['isi']); ?></span>
                                          <a href="<?php echo $layanan['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Klik Disini</a>
                                        <?php elseif ($is_image): ?>
                                          <i class="mdi mdi-file-image text-primary me-2" style="font-size: 1.5em;"></i>
                                          <span>File Gambar: <?php echo basename($layanan['isi']); ?></span>
                                          <a href="<?php echo $layanan['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat Gambar</a>
                                        <?php endif; ?>
                                      </div>
                                      <label class="form-label fw-bold mt-3">Upload File Baru (PDF atau Gambar)</label>
                                      <input type="file" name="isi" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp" />
                                      <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                                    </div>
                                  <?php else: ?>
                                    <div class="mb-3">
                                      <label class="form-label fw-bold">Isi Konten</label>

                                      <!-- TinyMCE Editor -->
                                      <textarea
                                        name="isi_text"
                                        id="editor_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                        class="form-control tinymce-editor"
                                        rows="15"><?php echo htmlspecialchars_decode($layanan['isi']); ?></textarea>
                                    </div>
                                  <?php endif; ?>

                                  <!-- Action Buttons -->
                                  <div class="d-flex justify-content-between gap-2 mt-4">
                                    <button
                                      type="button"
                                      class="btn btn-danger"
                                      onclick="confirmDelete(<?php echo $layanan['id_layanan']; ?>, '<?php echo htmlspecialchars($layanan['nama_layanan']); ?>')">
                                      <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                    <div class="d-flex gap-2">
                                      <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse_<?php echo $layanan['id_layanan']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                                        <i class="mdi mdi-close"></i> Tutup
                                      </button>
                                      <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-content-save"></i> Simpan
                                      </button>
                                    </div>
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

  <!-- Add Layanan Modal -->
  <div class="modal fade" id="addLayananModal" tabindex="-1" aria-labelledby="addLayananModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addLayananModalLabel">Tambah Data Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=layanan_informasi&action=create" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label fw-bold">Nama Layanan</label>
                  <input type="text" class="form-control" name="nama_layanan" placeholder="Contoh: Prosedur Permohonan" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <div class="form-check mb-2">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="has_sub_layanan"
                      id="hasSubLayananNew"
                      onchange="toggleSubLayananNew()">
                    <label class="form-check-label" for="hasSubLayananNew">
                      Aktifkan Sub Layanan
                    </label>
                  </div>
                  <input
                    type="text"
                    class="form-control"
                    name="sub_layanan"
                    id="subLayananNew"
                    placeholder="Masukkan sub layanan"
                    style="display: none;">
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Jenis Konten</label>
                <select class="form-select" name="content_type" id="contentTypeSelect" onchange="toggleContentFields()">
                  <option value="text">Teks (Menggunakan Editor)</option>
                  <option value="file">File (PDF atau Gambar)</option>
                </select>
              </div>
            </div>

            <div id="textContentField">
              <label class="form-label fw-bold">Isi Konten</label>
              <!-- TinyMCE Editor -->
              <textarea
                name="isi_text"
                id="newEditor"
                class="form-control"
                rows="15"></textarea>
            </div>

            <div id="fileContentField" style="display: none;">
              <label class="form-label fw-bold">Upload File</label>
              <input type="file" name="isi" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp" />
              <small class="text-muted">Pilih file PDF atau gambar</small>
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

  <!-- Delete Confirmation Form (Hidden) -->
  <form id="deleteForm" method="POST" action="index.php?controller=layanan_informasi&action=delete" style="display: none;">
    <input type="hidden" name="id_layanan" id="deleteId">
  </form>

  <?php include 'template/script.php'; ?>

  <!-- TinyMCE Editor -->
  <script src="https://cdn.tiny.cloud/1/z0t4wwtn9a2wpsk59ee400jsup9j2wusunqyvvezelo6imd8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
  <script>
    // Function to toggle sub layanan field
    function toggleSubLayanan(id) {
      const checkbox = document.getElementById('hasSubLayanan_' + id);
      const input = document.getElementById('subLayanan_' + id);
      input.style.display = checkbox.checked ? 'block' : 'none';
      if (!checkbox.checked) {
        input.value = '';
      }
    }

    // Function to toggle sub layanan field for new entry
    function toggleSubLayananNew() {
      const checkbox = document.getElementById('hasSubLayananNew');
      const input = document.getElementById('subLayananNew');
      input.style.display = checkbox.checked ? 'block' : 'none';
      if (!checkbox.checked) {
        input.value = '';
      }
    }

    // Function to toggle content fields based on content type
    function toggleContentFields() {
      const contentType = document.getElementById('contentTypeSelect').value;
      const textContentField = document.getElementById('textContentField');
      const fileContentField = document.getElementById('fileContentField');

      if (contentType === 'text') {
        textContentField.style.display = 'block';
        fileContentField.style.display = 'none';
      } else if (contentType === 'file') {
        textContentField.style.display = 'none';
        fileContentField.style.display = 'block';
      }
    }

    // Function to confirm delete
    function confirmDelete(id, nama) {
      if (confirm('Apakah Anda yakin ingin menghapus layanan "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
      }
    }

    // Initialize TinyMCE for all textareas with tinymce-editor class with file picker
    function initializeTinyMCE() {
      tinymce.init({
        selector: '.tinymce-editor',
        height: 400,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        file_picker_types: 'file image media',
        file_picker_callback: function(cb, value, meta) {
          var input = document.createElement('input');
          input.setAttribute('type', 'file');

          // Set accept attribute based on file type
          if (meta.filetype === 'image') {
            input.setAttribute('accept', 'image/*');
          } else if (meta.filetype === 'media') {
            input.setAttribute('accept', 'video/*,audio/*');
          } else {
            input.setAttribute('accept', '.pdf,.doc,.docx');
          }

          input.onchange = function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?controller=layanan_informasi&action=upload_image');

            xhr.onload = function() {
              if (xhr.status === 200) {
                console.log('Raw response:', xhr.responseText);
                try {
                  var response = JSON.parse(xhr.responseText);
                  console.log('Parsed response:', response);

                  if (response && response.success === true) {
                    if (response.url) {
                      console.log('Upload success, URL:', response.url);
                      cb(response.url, { title: file.name });
                    } else if (response.location) {
                      console.log('Upload success, location:', response.location);
                      cb(response.location, { title: file.name });
                    } else {
                      console.error('No URL in response');
                      alert('No URL returned from server');
                    }
                  } else {
                    var errorMsg = response && response.message ? response.message : 'Upload failed';
                    console.error('Upload failed:', errorMsg);
                    alert(errorMsg);
                  }
                } catch (e) {
                  console.error('JSON parse error:', e, 'Response:', xhr.responseText);
                  alert('Invalid response from server: ' + e.message);
                }
              } else if (xhr.status === 403) {
                alert('Session expired or unauthorized. Please refresh the page.');
              } else {
                console.error('HTTP Error:', xhr.status);
                alert('HTTP Error: ' + xhr.status);
              }
            };

            xhr.onerror = function() {
              console.error('Network error during file upload');
              alert('File upload failed due to a network error.');
            };

            xhr.send(formData);
          };

          input.click();
        }
      });
    }

    // Initialize TinyMCE after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      initializeTinyMCE();

      // Initialize TinyMCE for the new editor in modal
      setTimeout(function() {
        if (!tinymce.get('newEditor')) {
          tinymce.init({
            selector: '#newEditor',
            height: 400,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            file_picker_types: 'file image media',
            file_picker_callback: function(cb, value, meta) {
              var input = document.createElement('input');
              input.setAttribute('type', 'file');

              // Set accept attribute based on file type
              if (meta.filetype === 'image') {
                input.setAttribute('accept', 'image/*');
              } else if (meta.filetype === 'media') {
                input.setAttribute('accept', 'video/*,audio/*');
              } else {
                input.setAttribute('accept', '.pdf,.doc,.docx');
              }

              input.onchange = function() {
                var file = this.files[0];
                var formData = new FormData();
                formData.append('file', file);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'index.php?controller=layanan_informasi&action=upload_image');

                xhr.onload = function() {
                  if (xhr.status === 200) {
                    console.log('Raw response:', xhr.responseText);
                    try {
                      var response = JSON.parse(xhr.responseText);
                      console.log('Parsed response:', response);

                      if (response && response.success === true) {
                        if (response.url) {
                          console.log('Upload success, URL:', response.url);
                          cb(response.url, { title: file.name });
                        } else if (response.location) {
                          console.log('Upload success, location:', response.location);
                          cb(response.location, { title: file.name });
                        } else {
                          console.error('No URL in response');
                          alert('No URL returned from server');
                        }
                      } else {
                        var errorMsg = response && response.message ? response.message : 'Upload failed';
                        console.error('Upload failed:', errorMsg);
                        alert(errorMsg);
                      }
                    } catch (e) {
                      console.error('JSON parse error:', e, 'Response:', xhr.responseText);
                      alert('Invalid response from server: ' + e.message);
                    }
                  } else if (xhr.status === 403) {
                    alert('Session expired or unauthorized. Please refresh the page.');
                  } else {
                    console.error('HTTP Error:', xhr.status);
                    alert('HTTP Error: ' + xhr.status);
                  }
                };

                xhr.onerror = function() {
                  console.error('Network error during file upload');
                  alert('File upload failed due to a network error.');
                };

                xhr.send(formData);
              };

              input.click();
            }
          });
        }
      }, 500);
    });

    // Reinitialize TinyMCE when switching tabs
    document.addEventListener('shown.bs.tab', function(event) {
      setTimeout(function() {
        initializeTinyMCE();
      }, 100);
    });

    // Destroy TinyMCE when modal is closed
    document.getElementById('addLayananModal').addEventListener('hidden.bs.modal', function() {
      if (tinymce.get('newEditor')) {
        tinymce.get('newEditor').remove();
      }

      setTimeout(function() {
        if (!tinymce.get('newEditor')) {
          tinymce.init({
            selector: '#newEditor',
            height: 400,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            file_picker_types: 'file image media',
            file_picker_callback: function(cb, value, meta) {
              var input = document.createElement('input');
              input.setAttribute('type', 'file');

              // Set accept attribute based on file type
              if (meta.filetype === 'image') {
                input.setAttribute('accept', 'image/*');
              } else if (meta.filetype === 'media') {
                input.setAttribute('accept', 'video/*,audio/*');
              } else {
                input.setAttribute('accept', '.pdf,.doc,.docx');
              }

              input.onchange = function() {
                var file = this.files[0];
                var formData = new FormData();
                formData.append('file', file);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'index.php?controller=layanan_informasi&action=upload_image');

                xhr.onload = function() {
                  if (xhr.status === 200) {
                    console.log('Raw response:', xhr.responseText);
                    try {
                      var response = JSON.parse(xhr.responseText);
                      console.log('Parsed response:', response);

                      if (response && response.success === true) {
                        if (response.url) {
                          console.log('Upload success, URL:', response.url);
                          cb(response.url, { title: file.name });
                        } else if (response.location) {
                          console.log('Upload success, location:', response.location);
                          cb(response.location, { title: file.name });
                        } else {
                          console.error('No URL in response');
                          alert('No URL returned from server');
                        }
                      } else {
                        var errorMsg = response && response.message ? response.message : 'Upload failed';
                        console.error('Upload failed:', errorMsg);
                        alert(errorMsg);
                      }
                    } catch (e) {
                      console.error('JSON parse error:', e, 'Response:', xhr.responseText);
                      alert('Invalid response from server: ' + e.message);
                    }
                  } else if (xhr.status === 403) {
                    alert('Session expired or unauthorized. Please refresh the page.');
                  } else {
                    console.error('HTTP Error:', xhr.status);
                    alert('HTTP Error: ' + xhr.status);
                  }
                };

                xhr.onerror = function() {
                  console.error('Network error during file upload');
                  alert('File upload failed due to a network error.');
                };

                xhr.send(formData);
              };

              input.click();
            }
          });
        }
      }, 100);
    });
  </script>
</body>

</html>
