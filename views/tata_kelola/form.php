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
                      <?= $action === 'create' ? 'Tambah' : 'Edit' ?> Data Tata Kelola
                    </h4>
                  </div>

                  <form id="tataKelolaForm" method="POST" action="index.php?controller=tataKelola&action=save">
                    <input type="hidden" name="id" value="<?= $tataKelola ? $tataKelola['id_tata_kelola'] : '' ?>">

                    <div class="row">
                      <div class="col-12">
                        <div class="gov-form-group">
                          <label for="nama_tata_kelola">
                            Nama Tata Kelola <span class="required-indicator">*</span>
                          </label>
                          <input type="text"
                            class="form-control gov-form-control"
                            style="height: 50px;"
                            id="nama_tata_kelola"
                            name="nama_tata_kelola"
                            value="<?= $tataKelola ? htmlspecialchars($tataKelola['nama_tata_kelola']) : '' ?>"
                            placeholder="Contoh: Struktur Organisasi"
                            required>
                          <small class="form-text">Masukkan nama dokumen atau halaman tata kelola</small>
                        </div>
                      </div>

                      <div class="col-12">
                        <div class="gov-form-group">
                          <label for="link">Link Dokumen/Halaman</label>
                          <input type="url"
                            class="form-control gov-form-control"
                            style="height: 50px;"
                            id="link"
                            name="link"
                            value="<?= $tataKelola ? htmlspecialchars($tataKelola['link']) : '' ?>"
                            placeholder="https://contoh.com/dokumen-tata-kelola.pdf">
                          <small class="form-text">Masukkan URL link tata kelola (opsional)</small>
                        </div>
                      </div>
                    </div>

                    <div class="mt-4 d-flex gap-3 flex-wrap">
                      <button type="submit" class="btn btn-gov-primary">
                        <i class="fas fa-save me-2"></i>
                        <?= $action === 'create' ? 'Simpan Data' : 'Perbarui Data' ?>
                      </button>
                      <a href="index.php?controller=tataKelola&action=index" class="btn btn-gov-secondary">
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
    document.getElementById('tataKelolaForm').addEventListener('submit', function(e) {
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