<?php include('template/header.php'); ?>

<style>
  .gov-card {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: none;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    transition: all 0.3s ease;
  }

  .gov-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 12px 12px 0 0;
    padding: 30px 35px;
    color: white;
    margin: -20px -20px 25px -20px;
  }

  .gov-title {
    font-size: 1.85rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .gov-title i {
    color: #ffd700;
  }

  .gov-form-group {
    margin-bottom: 30px;
  }

  .gov-form-group label {
    font-weight: 600;
    color: #1e3c72;
    margin-bottom: 12px;
    font-size: 1.1rem;
    display: block;
  }

  .gov-form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 16px 20px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }

  .gov-form-control:focus {
    border-color: #2a5298;
    box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.15);
    background: white;
  }

  .gov-form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 16px 20px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: white;
  }

  .gov-form-select:focus {
    border-color: #2a5298;
    box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.15);
  }

  .custom-file-upload {
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
  }

  .custom-file-upload:hover {
    border-color: #2a5298;
    background: white;
  }

  .custom-file-upload i {
    font-size: 3rem;
    color: #2a5298;
    margin-bottom: 15px;
  }

  .file-upload-text {
    color: #6c757d;
    margin-bottom: 10px;
  }

  .file-name-display {
    margin-top: 15px;
    padding: 12px;
    background: #e7f3ff;
    border-radius: 6px;
    color: #1e3c72;
    font-weight: 500;
  }

  .btn-gov-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    color: white;
    padding: 16px 35px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
  }

  .btn-gov-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: white;
  }

  .btn-gov-secondary {
    background: white;
    border: 2px solid #6c757d;
    color: #6c757d;
    padding: 16px 35px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }

  .btn-gov-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
  }

  .form-text {
    color: #6c757d;
    font-size: 0.95rem;
    margin-top: 6px;
  }

  .required-indicator {
    color: #dc3545;
    font-weight: 700;
  }

  .current-file {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
  }

  .current-file img,
  .current-file video {
    max-width: 200px;
    max-height: 200px;
    border-radius: 6px;
    margin-top: 10px;
  }

  @media (max-width: 768px) {
    .gov-title {
      font-size: 1.3rem;
    }

    .gov-header {
      padding: 20px 20px;
    }

    .btn-gov-primary,
    .btn-gov-secondary {
      padding: 12px 24px;
      font-size: 1rem;
      width: 100%;
      margin-bottom: 10px;
    }
  }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-lg-11">
              <div class="card gov-card">
                <div class="card-body">
                  <div class="gov-header">
                    <h4 class="gov-title">
                      <i class="fas fa-<?= $action === 'create' ? 'plus-circle' : 'edit' ?>"></i>
                      <?= $action === 'create' ? 'Tambah' : 'Edit' ?> Album
                    </h4>
                  </div>

                  <form id="albumForm" method="POST" action="index.php?controller=album&action=save" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $album ? $album['id_album'] : '' ?>">

                    <div class="row">
                      <div class="col-md-6">
                        <div class="gov-form-group">
                          <label for="kategori">
                            Kategori <span class="required-indicator">*</span>
                          </label>
                          <select class="form-select gov-form-select"
                                  id="kategori"
                                  name="kategori"
                                  style="height: 65px;"
                                  required>
                            <option value="">Pilih Kategori</option>
                            <option value="foto" <?= ($album && $album['kategori'] === 'foto') ? 'selected' : '' ?>>
                              <i class="fas fa-camera"></i> Foto
                            </option>
                            <option value="video" <?= ($album && $album['kategori'] === 'video') ? 'selected' : '' ?>>
                              <i class="fas fa-video"></i> Video
                            </option>
                          </select>
                          <small class="form-text">Pilih jenis album: Foto atau Video</small>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="gov-form-group">
                          <label for="nama_album">
                            Nama Album <span class="required-indicator">*</span>
                          </label>
                          <input type="text"
                                 class="form-control gov-form-control"
                                 style="height: 65px;"
                                 id="nama_album"
                                 name="nama_album"
                                 value="<?= $album ? htmlspecialchars($album['nama_album']) : '' ?>"
                                 placeholder="Contoh: Kegiatan PPID 2024"
                                 required>
                          <small class="form-text">Masukkan nama album</small>
                        </div>
                      </div>

                      <div class="col-12">
                        <div class="gov-form-group">
                          <label for="upload">
                            Upload File <?= $action === 'create' ? '<span class="required-indicator">*</span>' : '(Opsional)' ?>
                          </label>

                          <label for="upload" class="custom-file-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="file-upload-text">
                              <strong>Klik untuk upload file</strong><br>
                              atau drag & drop file di sini
                            </div>
                            <small class="text-muted">
                              Format: JPG, PNG, GIF, MP4, AVI, MOV (Maks. 50MB)
                            </small>
                          </label>

                          <input type="file"
                                 class="d-none"
                                 id="upload"
                                 name="upload"
                                 accept="image/jpeg,image/jpg,image/png,image/gif,video/mp4,video/avi,video/mov"
                                 <?= $action === 'create' ? 'required' : '' ?>>

                          <div id="fileNameDisplay" class="file-name-display d-none">
                            <i class="fas fa-file me-2"></i>
                            <span id="fileName"></span>
                          </div>

                          <?php if ($album && !empty($album['upload'])): ?>
                            <div class="current-file">
                              <strong>File Saat Ini:</strong>
                              <div class="mt-2">
                                <?php if ($album['kategori'] === 'foto'): ?>
                                  <img src="<?= htmlspecialchars($album['upload']) ?>" alt="Current File">
                                <?php else: ?>
                                  <video controls>
                                    <source src="<?= htmlspecialchars($album['upload']) ?>" type="video/mp4">
                                  </video>
                                <?php endif; ?>
                              </div>
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>

                    <div class="mt-4 d-flex gap-3 flex-wrap">
                      <button type="submit" class="btn btn-gov-primary">
                        <i class="fas fa-save me-2"></i>
                        <?= $action === 'create' ? 'Simpan Album' : 'Perbarui Album' ?>
                      </button>
                      <a href="index.php?controller=album&action=index" class="btn btn-gov-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali
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
    // Handle file input display
    document.getElementById('upload').addEventListener('change', function(e) {
      const fileNameDisplay = document.getElementById('fileNameDisplay');
      const fileName = document.getElementById('fileName');

      if (this.files.length > 0) {
        fileName.textContent = this.files[0].name;
        fileNameDisplay.classList.remove('d-none');
      } else {
        fileNameDisplay.classList.add('d-none');
      }
    });

    // Handle form submission
    document.getElementById('albumForm').addEventListener('submit', function(e) {
      e.preventDefault();

      if (confirm('Apakah Anda yakin ingin menyimpan data ini?')) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              window.location.href = data.redirect;
            } else {
              alert(data.message);
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          });
      }
    });
  </script>
</body>

</html>
