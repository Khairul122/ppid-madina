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
                                          <a href="<?php echo $profile['isi']; ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat PDF</a>
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

                                      <!-- TinyMCE Editor -->
                                      <textarea
                                        name="isi_text"
                                        id="editor_<?php echo $profile['id_profile']; ?>_<?php echo strtolower(str_replace(' ', '-', $category)); ?>"
                                        class="form-control tinymce-editor"
                                        rows="15"><?php echo htmlspecialchars_decode($profile['isi']); ?></textarea>
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
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Jenis Konten</label>
                <select class="form-select" name="content_type" id="contentTypeSelect" onchange="toggleContentFields()">
                  <option value="text">Teks (Menggunakan Editor)</option>
                  <option value="pdf">File (PDF atau Gambar)</option>
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

  <?php include 'template/script.php'; ?>

  <!-- TinyMCE Editor -->
  <script src="https://cdn.tiny.cloud/1/z0t4wwtn9a2wpsk59ee400jsup9j2wusunqyvvezelo6imd8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
  <script>
    // Function to toggle content fields based on content type
    function toggleContentFields() {
      const contentType = document.getElementById('contentTypeSelect').value;
      const textContentField = document.getElementById('textContentField');
      const fileContentField = document.getElementById('fileContentField');
      
      if (contentType === 'text') {
        textContentField.style.display = 'block';
        fileContentField.style.display = 'none';
      } else if (contentType === 'pdf') {
        textContentField.style.display = 'none';
        fileContentField.style.display = 'block';
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
            xhr.open('POST', 'index.php?controller=profile&action=upload_image');

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
                xhr.open('POST', 'index.php?controller=profile&action=upload_image');

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
    document.getElementById('addProfileModal').addEventListener('hidden.bs.modal', function() {
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
                xhr.open('POST', 'index.php?controller=profile&action=upload_image');

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