<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<?php include('template/header.php'); ?>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.form-label {
    color: #495057;
    font-weight: 500;
}

.page-header {
    background: white;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-left: 4px solid #6c757d;
}

.text-muted {
    color: #6c757d !important;
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

              <!-- Page Header -->
              <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h4 class="mb-1" style="color: #495057; font-weight: 600;">
                      <i class="mdi mdi-pencil me-2" style="color: #6c757d;"></i>
                      Edit Permohonan
                    </h4>
                    <p class="text-muted mb-0">Form untuk mengubah data permohonan informasi</p>
                  </div>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item"><a href="index.php?controller=dashboard" class="text-muted">Dashboard</a></li>
                      <li class="breadcrumb-item text-muted">Permohonan Admin</li>
                      <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-muted">Daftar</a></li>
                      <li class="breadcrumb-item active">Edit</li>
                    </ol>
                  </nav>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-8">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="mb-0">
                        <i class="mdi mdi-form-select me-2"></i>
                        Form Edit Permohonan
                      </h5>
                    </div>
                    <div class="card-body">
                      <form method="POST" action="index.php?controller=permohonanadmin&action=update" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="id_permohonan" value="<?php echo $permohonan['id_permohonan']; ?>">

                        <!-- Komponen Tujuan -->
                        <div class="mb-3">
                          <label for="komponen_tujuan" class="form-label">
                            Komponen Tujuan <span class="text-danger">*</span>
                          </label>
                          <select class="form-select" name="komponen_tujuan" id="komponen_tujuan_edit" required>
                            <option value="">-- Loading komponen tujuan... --</option>
                          </select>
                          <small class="text-muted">Komponen akan muncul setelah memilih tujuan permohonan</small>
                        </div>

                        <!-- Judul Dokumen -->
                        <div class="mb-3">
                          <label for="judul_dokumen" class="form-label">
                            Judul Dokumen <span class="text-danger">*</span>
                          </label>
                          <input type="text" class="form-control" name="judul_dokumen"
                                 value="<?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>"
                                 required placeholder="Judul dokumen informasi">
                        </div>

                        <!-- Kandungan Informasi -->
                        <div class="mb-3">
                          <label for="kandungan_informasi" class="form-label">
                            Kandungan Informasi <span class="text-danger">*</span>
                          </label>
                          <textarea class="form-control" name="kandungan_informasi" rows="4" required
                                    placeholder="Jelaskan secara detail informasi yang diminta"><?php echo htmlspecialchars($permohonan['kandungan_informasi'] ?? ''); ?></textarea>
                        </div>

                        <!-- Tujuan Penggunaan Informasi -->
                        <div class="mb-3">
                          <label for="tujuan_penggunaan_informasi" class="form-label">
                            Tujuan Penggunaan Informasi <span class="text-danger">*</span>
                          </label>
                          <textarea class="form-control" name="tujuan_penggunaan_informasi" rows="3" required
                                    placeholder="Jelaskan untuk apa informasi ini akan digunakan"><?php echo htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? ''); ?></textarea>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                          <label for="status" class="form-label">
                            Status Permohonan
                          </label>
                          <select class="form-select" name="status">
                            <option value="Diproses" <?php echo ($permohonan['status'] == 'Diproses') ? 'selected' : ''; ?>>Sedang Diproses</option>
                            <option value="Selesai" <?php echo ($permohonan['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Ditolak" <?php echo ($permohonan['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                          </select>
                        </div>
                        
                        <!-- Sumber Media -->
                        <div class="mb-3">
                          <label for="sumber_media" class="form-label">
                            Sumber Media
                          </label>
                          <select class="form-select" name="sumber_media">
                            <option value="Website" <?php echo ($permohonan['sumber_media'] == 'Website') ? 'selected' : ''; ?>>Website</option>
                            <option value="Offline" <?php echo ($permohonan['sumber_media'] == 'Offline') ? 'selected' : ''; ?>>Offline</option>
                            <option value="Aplikasi" <?php echo ($permohonan['sumber_media'] == 'Aplikasi') ? 'selected' : ''; ?>>Aplikasi</option>
                          </select>
                        </div>

                        <!-- Upload Foto Identitas -->
                        <div class="mb-3">
                          <label for="upload_foto_identitas" class="form-label">
                            Upload Foto Identitas
                          </label>
                          <input type="file" class="form-control" name="upload_foto_identitas" accept=".jpg,.jpeg,.png,.pdf">
                          <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                            <small class="text-muted">File saat ini: <?php echo basename($permohonan['upload_foto_identitas']); ?></small>
                          <?php endif; ?>
                        </div>

                        <!-- Upload Data Pendukung -->
                        <div class="mb-3">
                          <label for="upload_data_pedukung" class="form-label">
                            Upload Data Pendukung
                          </label>
                          <input type="file" class="form-control" name="upload_data_pedukung" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                            <small class="text-muted">File saat ini: <?php echo basename($permohonan['upload_data_pedukung']); ?></small>
                          <?php endif; ?>
                        </div>


                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                          <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i>
                            Update Permohonan
                          </button>
                          <a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i>
                            Kembali
                          </a>
                        </div>

                      </form>
                    </div>
                  </div>
                </div>

                <!-- Info Card -->
                <div class="col-lg-4">
                  <div class="card" style="background-color: #f8f9fa;">
                    <div class="card-header">
                      <h6 class="mb-0">
                        <i class="mdi mdi-information text-muted"></i>
                        Informasi Permohonan
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-3">
                        <small class="text-muted"><strong>No. Permohonan:</strong></small><br>
                        <span class="text-dark"><?php echo htmlspecialchars($permohonan['no_permohonan'] ?? '-'); ?></span>
                      </div>
                      <div class="mb-3">
                        <small class="text-muted"><strong>Pemohon:</strong></small><br>
                        <span class="text-dark"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? '-'); ?></span>
                      </div>
                      <div class="mb-3">
                        <small class="text-muted"><strong>Email:</strong></small><br>
                        <span class="text-dark"><?php echo htmlspecialchars($permohonan['email'] ?? '-'); ?></span>
                      </div>
                      <div class="mb-3">
                        <small class="text-muted"><strong>Tanggal Dibuat:</strong></small><br>
                        <span class="text-dark"><?php echo date('d/m/Y H:i', strtotime($permohonan['created_at'] ?? 'now')); ?></span>
                      </div>
                      <div class="mb-3">
                        <small class="text-muted"><strong>Status Saat Ini:</strong></small><br>
                        <span class="badge bg-<?php
                          echo ($permohonan['status'] == 'Selesai') ? 'success' :
                               (($permohonan['status'] == 'Ditolak') ? 'danger' : 'warning');
                        ?>">
                          <?php echo htmlspecialchars($permohonan['status'] ?? 'Diproses'); ?>
                        </span>
                      </div>
                    </div>
                  </div>
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
    // Bootstrap form validation
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

    // Handle tujuan permohonan change to update komponen tujuan
    $('#tujuan_permohonan_edit').on('change', function() {
      const selectedTujuan = $(this).val();
      const komponenSelect = $('#komponen_tujuan_edit');
      const currentKomponen = '<?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? ''); ?>';

      // Clear previous options
      komponenSelect.html('<option value="">-- Loading... --</option>');

      if (selectedTujuan === '') {
        komponenSelect.html('<option value="">-- Pilih tujuan permohonan terlebih dahulu --</option>');
        return;
      }

      // Make AJAX request to get komponen list
      $.ajax({
        url: 'index.php?controller=permohonanadmin&action=getKomponen',
        method: 'GET',
        data: { tujuan_permohonan: selectedTujuan },
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data.length > 0) {
            let options = '<option value="">-- Pilih Komponen Tujuan --</option>';
            response.data.forEach(function(komponen) {
              const selected = komponen.nama_tujuan_permohonan === currentKomponen ? 'selected' : '';
              options += `<option value="${komponen.nama_tujuan_permohonan}" ${selected}>${komponen.nama_tujuan_permohonan}</option>`;
            });
            komponenSelect.html(options);
          } else {
            komponenSelect.html('<option value="">-- Tidak ada komponen tersedia --</option>');
          }
        },
        error: function() {
          komponenSelect.html('<option value="">-- Error loading komponen --</option>');
          alert('Gagal memuat komponen tujuan. Silakan refresh halaman.');
        }
      });
    });

    // Load komponen on page load if tujuan is already selected
    $(document).ready(function() {
      if ($('#tujuan_permohonan_edit').val() !== '') {
        $('#tujuan_permohonan_edit').trigger('change');
      }
    });
  </script>
</body>

</html>
