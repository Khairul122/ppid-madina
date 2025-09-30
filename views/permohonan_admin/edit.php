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
              <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php echo $success_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php echo $error_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Edit Permohonan Informasi</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="text-decoration-none">Detail Permohonan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-info btn-sm">
                      <i class="fas fa-eye me-1"></i>Lihat Detail
                    </a>
                    <a href="index.php?controller=permohonanadmin&action=index" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Form Card -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <i class="mdi mdi-pencil me-2"></i>
                    Edit Permohonan: <?php echo htmlspecialchars($permohonan['no_permohonan'] ?? ''); ?>
                  </h5>
                </div>
                <div class="card-body">
                  <form action="index.php?controller=permohonanadmin&action=update" method="POST" enctype="multipart/form-data" id="comprehensive-form">
                    <input type="hidden" name="id_permohonan" value="<?php echo $permohonan['id_permohonan']; ?>">

                    <!-- Data Pribadi Section -->
                    <div class="row mb-4">
                      <div class="col-12">
                        <h6 class="section-title">
                          <i class="mdi mdi-account-circle me-2"></i>Data Pribadi
                        </h6>
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_lengkap" value="<?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? ''); ?>" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">NIK (16 digit) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nik" value="<?php echo htmlspecialchars($permohonan['nik'] ?? ''); ?>" maxlength="16" pattern="\d{16}" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat" rows="3" required><?php echo htmlspecialchars($permohonan['alamat'] ?? ''); ?></textarea>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <select class="form-control" name="provinsi" id="provinsi" required>
                          <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" id="current-province" value="<?php echo htmlspecialchars($permohonan['provinsi'] ?? ''); ?>">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                        <select class="form-control" name="city" id="city" required>
                          <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                        <input type="hidden" id="current-city" value="<?php echo htmlspecialchars($permohonan['city'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin">
                          <option value="">Pilih Jenis Kelamin</option>
                          <option value="Laki-laki" <?php echo ($permohonan['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                          <option value="Perempuan" <?php echo ($permohonan['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Usia</label>
                        <input type="number" class="form-control" name="usia" value="<?php echo htmlspecialchars($permohonan['usia'] ?? ''); ?>" min="17" max="100">
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">No. Kontak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="no_kontak" value="<?php echo htmlspecialchars($permohonan['no_kontak'] ?? ''); ?>" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select class="form-control" name="pendidikan">
                          <option value="">Pilih Pendidikan</option>
                          <?php
                          $pendidikan_options = ['SD', 'SMP', 'SMA/SMK', 'Diploma', 'S1', 'S2', 'S3'];
                          foreach ($pendidikan_options as $option):
                          ?>
                            <option value="<?php echo $option; ?>" <?php echo ($permohonan['pendidikan'] ?? '') == $option ? 'selected' : ''; ?>><?php echo $option; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" name="pekerjaan" value="<?php echo htmlspecialchars($permohonan['pekerjaan'] ?? ''); ?>" placeholder="Contoh: PNS, Wiraswasta, Mahasiswa">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Status Pengguna</label>
                        <select class="form-control" name="status_pengguna" id="status_pengguna">
                          <option value="pribadi" <?php echo ($permohonan['status_pengguna'] ?? '') == 'pribadi' ? 'selected' : ''; ?>>Pribadi</option>
                          <option value="lembaga" <?php echo ($permohonan['status_pengguna'] ?? '') == 'lembaga' ? 'selected' : ''; ?>>Lembaga</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3" id="lembaga-field" style="display: <?php echo ($permohonan['status_pengguna'] ?? '') == 'lembaga' ? 'block' : 'none'; ?>;">
                        <label class="form-label">Nama Lembaga</label>
                        <input type="text" class="form-control" name="nama_lembaga" value="<?php echo htmlspecialchars($permohonan['nama_lembaga'] ?? ''); ?>">
                      </div>
                    </div>

                    <!-- Login Information Section -->
                    <div class="row mb-4 mt-4">
                      <div class="col-12">
                        <h6 class="section-title">
                          <i class="mdi mdi-lock me-2"></i>Informasi Login
                        </h6>
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($permohonan['email'] ?? ''); ?>" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($permohonan['username'] ?? ''); ?>" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" class="form-control" name="password">
                      </div>
                    </div>

                    <!-- Upload Documents Section -->
                    <div class="row mb-4 mt-4">
                      <div class="col-12">
                        <h6 class="section-title">
                          <i class="mdi mdi-file-upload me-2"></i>Upload Dokumen
                        </h6>
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Profile</label>
                        <input type="file" class="form-control" name="foto_profile" accept=".jpg,.jpeg,.png">
                        <?php if (!empty($permohonan['foto_profile'])): ?>
                          <small class="text-success">File saat ini: <?php echo basename($permohonan['foto_profile']); ?></small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Upload KTP</label>
                        <input type="file" class="form-control" name="upload_ktp" accept=".jpg,.jpeg,.png,.pdf">
                        <?php if (!empty($permohonan['upload_ktp'])): ?>
                          <small class="text-success">File saat ini: <?php echo basename($permohonan['upload_ktp']); ?></small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format: JPG, JPEG, PNG, PDF (Max: 5MB)</small>
                      </div>
                      <div class="col-md-4 mb-3" id="akta-field" style="display: <?php echo ($permohonan['status_pengguna'] ?? '') == 'lembaga' ? 'block' : 'none'; ?>;">
                        <label class="form-label">Upload Akta Lembaga</label>
                        <input type="file" class="form-control" name="upload_akta" accept=".jpg,.jpeg,.png,.pdf">
                        <?php if (!empty($permohonan['upload_akta'])): ?>
                          <small class="text-success">File saat ini: <?php echo basename($permohonan['upload_akta']); ?></small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format: JPG, JPEG, PNG, PDF (Max: 5MB)</small>
                      </div>
                    </div>

                    <!-- Information Request Section -->
                    <div class="row mb-4 mt-4">
                      <div class="col-12">
                        <h6 class="section-title">
                          <i class="mdi mdi-file-document me-2"></i>Informasi Permohonan
                        </h6>
                        <hr>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Komponen yang Dituju (SKPD) <span class="text-danger">*</span></label>
                        <select class="form-control" name="komponen_tujuan" required>
                          <option value="">Pilih SKPD</option>
                          <?php foreach ($skpd_list as $skpd): ?>
                            <option value="<?php echo htmlspecialchars($skpd['nama_skpd']); ?>" <?php echo ($permohonan['komponen_tujuan'] ?? '') == $skpd['nama_skpd'] ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($skpd['nama_skpd']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label class="form-label">Judul Dokumen Informasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_dokumen" value="<?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>" required>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label class="form-label">Kandungan Informasi / Tujuan Penggunaan Informasi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tujuan_penggunaan_informasi" rows="4" required><?php echo htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? ''); ?></textarea>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Foto Identitas</label>
                        <input type="file" class="form-control" name="upload_foto_identitas" accept=".jpg,.jpeg,.png,.pdf">
                        <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                          <small class="text-success">File saat ini: <?php echo basename($permohonan['upload_foto_identitas']); ?></small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format: JPG, JPEG, PNG, PDF (Max: 5MB)</small>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Upload Data Pendukung</label>
                        <input type="file" class="form-control" name="upload_data_pedukung" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                          <small class="text-success">File saat ini: <?php echo basename($permohonan['upload_data_pedukung']); ?></small>
                        <?php endif; ?>
                        <small class="text-muted d-block">Format: JPG, JPEG, PNG, PDF, DOC, DOCX (Max: 10MB)</small>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Status Permohonan</label>
                        <select class="form-control" name="status">
                          <option value="Diproses" <?php echo ($permohonan['status'] ?? '') == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                          <option value="Selesai" <?php echo ($permohonan['status'] ?? '') == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                          <option value="Ditolak" <?php echo ($permohonan['status'] ?? '') == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Sumber Media</label>
                        <select class="form-control" name="sumber_media">
                          <option value="Website" <?php echo ($permohonan['sumber_media'] ?? '') == 'Website' ? 'selected' : ''; ?>>Website</option>
                          <option value="Offline" <?php echo ($permohonan['sumber_media'] ?? '') == 'Offline' ? 'selected' : ''; ?>>Offline</option>
                          <option value="Aplikasi" <?php echo ($permohonan['sumber_media'] ?? '') == 'Aplikasi' ? 'selected' : ''; ?>>Aplikasi</option>
                        </select>
                      </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="row mt-4">
                      <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                          <a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-secondary">
                            <i class="mdi mdi-close me-1"></i>Batal
                          </a>
                          <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i>Update Permohonan
                          </button>
                        </div>
                      </div>
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
    $(document).ready(function() {
      // Load provinces and set current values
      loadProvinces();

      // Handle status pengguna change
      $('#status_pengguna').change(function() {
        if ($(this).val() === 'lembaga') {
          $('#lembaga-field').show();
          $('#akta-field').show();
        } else {
          $('#lembaga-field').hide();
          $('#akta-field').hide();
        }
      });

      // Handle province change
      $('#provinsi').change(function() {
        const provinceId = $(this).val();
        if (provinceId) {
          loadCities(provinceId);
        } else {
          $('#city').html('<option value="">Pilih Kota/Kabupaten</option>');
        }
      });
    });

    function loadProvinces() {
      const currentProvince = $('#current-province').val();

      $.ajax({
        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
        type: 'GET',
        success: function(data) {
          let options = '<option value="">Pilih Provinsi</option>';
          let selectedProvinceId = '';

          data.forEach(function(province) {
            const selected = province.name === currentProvince ? 'selected' : '';
            if (province.name === currentProvince) {
              selectedProvinceId = province.id;
            }
            options += `<option value="${province.id}" data-name="${province.name}" ${selected}>${province.name}</option>`;
          });

          $('#provinsi').html(options);

          // Load cities if province is selected
          if (selectedProvinceId) {
            loadCities(selectedProvinceId, true);
          }
        },
        error: function() {
          console.error('Failed to load provinces');
        }
      });
    }

    function loadCities(provinceId, setSelected = false) {
      const currentCity = $('#current-city').val();

      $('#city').html('<option value="">Loading...</option>');

      $.ajax({
        url: `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
        type: 'GET',
        success: function(data) {
          let options = '<option value="">Pilih Kota/Kabupaten</option>';
          data.forEach(function(city) {
            const selected = (setSelected && city.name === currentCity) ? 'selected' : '';
            options += `<option value="${city.name}" ${selected}>${city.name}</option>`;
          });
          $('#city').html(options);
        },
        error: function() {
          $('#city').html('<option value="">Gagal memuat kota</option>');
          console.error('Failed to load cities');
        }
      });
    }

    // Form validation
    $('#comprehensive-form').on('submit', function(e) {
      const nik = $('input[name="nik"]').val();
      if (nik.length !== 16) {
        e.preventDefault();
        alert('NIK harus 16 digit');
        return false;
      }

      // Set province name in hidden input
      const selectedProvince = $('#provinsi option:selected').attr('data-name');
      if (selectedProvince) {
        $('input[name="provinsi"]').remove();
        $(this).append(`<input type="hidden" name="provinsi" value="${selectedProvince}">`);
      }
    });
  </script>

  <style>
    /* Government Standard Styling - Same as create.php for consistency */
    :root {
      --gov-primary: #2563eb;
      --gov-secondary: #64748b;
      --gov-success: #10b981;
      --gov-danger: #ef4444;
      --gov-warning: #f59e0b;
      --gov-light: #f8fafc;
      --gov-dark: #1e293b;
      --gov-border: #e2e8f0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--gov-dark);
      background-color: #f1f5f9;
    }

    .page-header {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      border: 1px solid var(--gov-border);
      margin-bottom: 1.5rem;
    }

    .page-title {
      color: var(--gov-dark);
      font-weight: 600;
      margin: 0;
    }

    .breadcrumb {
      background: none;
      padding: 0;
      margin: 0;
    }

    .breadcrumb-item a {
      color: var(--gov-secondary);
      text-decoration: none;
    }

    .breadcrumb-item.active {
      color: var(--gov-dark);
    }

    .card {
      border: 1px solid var(--gov-border);
      border-radius: 8px;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: white;
      border-bottom: 2px solid var(--gov-border);
      padding: 1.5rem;
    }

    .card-body {
      background-color: white;
    }

    .form-section {
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 2rem;
    }

    .form-section:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }

    .section-title {
      color: var(--gov-dark);
      font-weight: 600;
      font-size: 1.1rem;
    }

    .form-label {
      color: var(--gov-dark);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--gov-border);
      border-radius: 6px;
      padding: 0.75rem 1rem;
      transition: all 0.2s ease;
      background-color: white;
      color: var(--gov-dark);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--gov-primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
      background-color: white;
    }

    .form-control-lg {
      padding: 0.875rem 1.25rem;
      font-size: 1rem;
    }

    .btn {
      font-weight: 500;
      border-radius: 6px;
      transition: all 0.2s ease;
      border: none;
    }

    .btn-primary {
      background-color: var(--gov-primary);
      color: white;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
      background-color: #1d4ed8;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-outline-secondary {
      border: 1.5px solid var(--gov-secondary);
      color: var(--gov-secondary);
      background-color: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--gov-secondary);
      color: white;
      transform: translateY(-1px);
    }

    .btn-info {
      background-color: #0ea5e9;
      color: white;
    }

    .btn-info:hover {
      background-color: #0284c7;
      transform: translateY(-1px);
    }

    .btn-lg {
      padding: 0.875rem 2rem;
      font-size: 1rem;
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .alert {
      border: none;
      border-radius: 8px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }

    .alert-success {
      background-color: #f0fdf4;
      color: #15803d;
      border-left: 4px solid var(--gov-success);
    }

    .alert-danger {
      background-color: #fef2f2;
      color: #dc2626;
      border-left: 4px solid var(--gov-danger);
    }

    .text-danger {
      color: var(--gov-danger) !important;
    }

    .text-success {
      color: var(--gov-success) !important;
    }

    .text-primary {
      color: var(--gov-primary) !important;
    }

    .text-secondary {
      color: var(--gov-secondary) !important;
    }

    .text-muted {
      color: #94a3b8 !important;
      font-size: 0.875rem;
    }

    .border-top {
      border-top: 2px solid var(--gov-border) !important;
    }

    .shadow-sm {
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container-fluid {
        padding: 1rem;
      }

      .page-header {
        padding: 1rem;
      }

      .card-body {
        padding: 1.5rem !important;
      }

      .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
      }

      .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
      }

      .col-md-6,
      .col-md-4 {
        margin-bottom: 1rem;
      }

      .d-flex.gap-3 {
        flex-direction: column;
        gap: 1rem !important;
      }

      .d-flex.gap-3 .btn {
        width: 100%;
      }

      .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
      }

      .d-flex.gap-2 .btn {
        width: 100%;
      }
    }

    @media (max-width: 576px) {
      .page-title {
        font-size: 1.5rem;
      }

      .section-title {
        font-size: 1rem;
      }

      .breadcrumb {
        font-size: 0.875rem;
      }
    }

    /* Loading Animation */
    .form-control:disabled {
      background-color: #f8fafc;
      opacity: 0.6;
    }

    /* File Input Styling */
    input[type="file"] {
      padding: 0.5rem;
    }

    input[type="file"]:focus {
      outline: 2px solid var(--gov-primary);
      outline-offset: 2px;
    }

    /* Edit-specific styles */
    .text-success.d-block {
      display: block !important;
      margin-top: 0.25rem;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .section-header {
      margin-bottom: 1.5rem;
    }

    .section-header .border-bottom {
      border-color: var(--gov-secondary) !important;
    }
  </style>

</body>
</html>