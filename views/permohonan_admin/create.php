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
          <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

              <!-- Alert Messages -->
              <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                  <i class="fas fa-check-circle me-2"></i>
                  <?php echo $success_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo $error_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Form Permohonan Informasi Lengkap</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Permohonan</li>
                      </ol>
                    </nav>
                  </div>
                  <a href="index.php?controller=permohonanadmin&action=index" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                  </a>
                </div>
              </div>

              <!-- Form Card -->
              <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                  <h5 class="card-title mb-0 text-dark fw-normal">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Formulir Permohonan Informasi Baru
                  </h5>
                </div>
                <div class="card-body p-4">
                  <form action="index.php?controller=permohonanadmin&action=store" method="POST" enctype="multipart/form-data" id="comprehensive-form">

                    <!-- Data Pribadi Section -->
                    <div class="form-section mb-5">
                      <div class="section-header mb-4">
                        <h6 class="section-title text-dark mb-2">
                          <i class="fas fa-user me-2 text-secondary"></i>Data Pemohon
                        </h6>
                        <div class="border-bottom border-2 border-secondary" style="width: 60px;"></div>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg" name="nama_lengkap" required placeholder="Masukkan nama lengkap sesuai KTP" value="<?php echo isset($old_input['nama_lengkap']) ? htmlspecialchars($old_input['nama_lengkap']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Kategori SKPD <span class="text-danger">*</span></label>
                          <select class="form-select form-control-lg" name="tujuan_permohonan" id="tujuan_permohonan" required>
                            <option value="">-- Pilih Kategori SKPD --</option>
                            <?php if (!empty($skpd_list)): ?>
                              <?php
                                $kategori_list = [];
                                foreach ($skpd_list as $skpd) {
                                  if (!empty($skpd['kategori']) && !in_array($skpd['kategori'], $kategori_list)) {
                                    $kategori_list[] = $skpd['kategori'];
                                  }
                                }
                                foreach ($kategori_list as $kategori):
                                  $selected = (isset($old_input['tujuan_permohonan']) && $old_input['tujuan_permohonan'] === $kategori) ? 'selected' : '';
                              ?>
                                <option value="<?php echo htmlspecialchars($kategori); ?>" <?php echo $selected; ?>>
                                  <?php echo htmlspecialchars($kategori); ?>
                                </option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">NIK (16 Digit) <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg" name="nik" maxlength="16" pattern="\d{16}" required placeholder="Nomor Induk Kependudukan" value="<?php echo isset($old_input['nik']) ? htmlspecialchars($old_input['nik']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Nama SKPD <span class="text-danger">*</span></label>
                          <select class="form-select form-control-lg" name="komponen_tujuan" id="komponen_tujuan" required>
                            <option value="">-- Pilih kategori SKPD terlebih dahulu --</option>
                            <?php if (isset($old_input['komponen_tujuan'])): ?>
                              <option value="<?php echo htmlspecialchars($old_input['komponen_tujuan']); ?>" selected><?php echo htmlspecialchars($old_input['komponen_tujuan']); ?></option>
                            <?php endif; ?>
                          </select>
                          <small class="text-muted">Nama SKPD akan muncul setelah memilih kategori</small>
                        </div>

                        <div class="col-12">
                          <label class="form-label text-dark fw-medium">Alamat Lengkap <span class="text-danger">*</span></label>
                          <textarea class="form-control" name="alamat" rows="3" required placeholder="Alamat lengkap sesuai KTP"><?php echo isset($old_input['alamat']) ? htmlspecialchars($old_input['alamat']) : ''; ?></textarea>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Judul Dokumen Informasi <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg" name="judul_dokumen" required placeholder="Judul dokumen atau informasi yang dimohon" value="<?php echo isset($old_input['judul_dokumen']) ? htmlspecialchars($old_input['judul_dokumen']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Provinsi <span class="text-danger">*</span></label>
                          <select class="form-select form-control-lg" name="provinsi" id="provinsi" required>
                            <option value="">-- Pilih Provinsi --</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Kota/Kabupaten <span class="text-danger">*</span></label>
                          <select class="form-select form-control-lg" name="city" id="city" required>
                            <option value="">-- Pilih Kota/Kabupaten --</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Kandungan Informasi <span class="text-danger">*</span></label>
                          <textarea class="form-control" name="kandungan_informasi" rows="3" required placeholder="Jelaskan kandungan informasi yang dibutuhkan"><?php echo isset($old_input['kandungan_informasi']) ? htmlspecialchars($old_input['kandungan_informasi']) : ''; ?></textarea>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">No. Kontak <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg" name="no_kontak" required placeholder="Nomor telepon/HP aktif" value="<?php echo isset($old_input['no_kontak']) ? htmlspecialchars($old_input['no_kontak']) : ''; ?>">
                        </div>


                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Foto Profile</label>
                          <input type="file" class="form-control form-control-lg" name="foto_profile" accept=".jpg,.jpeg,.png">
                          <small class="text-muted">JPG, JPEG, PNG (Max: 2MB)</small>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Email Aktif <span class="text-danger">*</span></label>
                          <input type="email" class="form-control form-control-lg" name="email" required placeholder="email@example.com" value="<?php echo isset($old_input['email']) ? htmlspecialchars($old_input['email']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Password <span class="text-danger">*</span></label>
                          <input type="password" class="form-control form-control-lg" name="password" required placeholder="Minimal 6 karakter" value="<?php echo isset($old_input['password']) ? htmlspecialchars($old_input['password']) : ''; ?>">
                        </div>
                      </div>
                    </div>

                    <!-- Data Tambahan Section -->
                    <div class="form-section mb-5">
                      <div class="section-header mb-4">
                        <h6 class="section-title text-dark mb-2">
                          <i class="fas fa-info-circle me-2 text-secondary"></i>Data Tambahan (Opsional)
                        </h6>
                        <div class="border-bottom border-2 border-secondary" style="width: 60px;"></div>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Username</label>
                          <input type="text" class="form-control form-control-lg" name="username" placeholder="Username untuk login" value="<?php echo isset($old_input['username']) ? htmlspecialchars($old_input['username']) : ''; ?>">
                        </div>

                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Jenis Kelamin</label>
                          <select class="form-select form-control-lg" name="jenis_kelamin">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" <?php echo (isset($old_input['jenis_kelamin']) && $old_input['jenis_kelamin'] === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="Perempuan" <?php echo (isset($old_input['jenis_kelamin']) && $old_input['jenis_kelamin'] === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                          </select>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Usia</label>
                          <input type="number" class="form-control form-control-lg" name="usia" min="17" max="100" placeholder="Tahun" value="<?php echo isset($old_input['usia']) ? htmlspecialchars($old_input['usia']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Pendidikan Terakhir</label>
                          <select class="form-select form-control-lg" name="pendidikan">
                            <option value="">-- Pilih Pendidikan --</option>
                            <option value="SD" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'SD') ? 'selected' : ''; ?>>SD</option>
                            <option value="SMP" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'SMP') ? 'selected' : ''; ?>>SMP</option>
                            <option value="SMA/SMK" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'SMA/SMK') ? 'selected' : ''; ?>>SMA/SMK</option>
                            <option value="Diploma" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'Diploma') ? 'selected' : ''; ?>>Diploma</option>
                            <option value="S1" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'S1') ? 'selected' : ''; ?>>Sarjana (S1)</option>
                            <option value="S2" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'S2') ? 'selected' : ''; ?>>Magister (S2)</option>
                            <option value="S3" <?php echo (isset($old_input['pendidikan']) && $old_input['pendidikan'] === 'S3') ? 'selected' : ''; ?>>Doktor (S3)</option>
                          </select>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Pekerjaan</label>
                          <input type="text" class="form-control form-control-lg" name="pekerjaan" placeholder="Contoh: PNS, Wiraswasta, Mahasiswa" value="<?php echo isset($old_input['pekerjaan']) ? htmlspecialchars($old_input['pekerjaan']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                          <label class="form-label text-dark fw-medium">Status Pemohon</label>
                          <select class="form-select form-control-lg" name="status_pengguna" id="status_pengguna">
                            <option value="pribadi" <?php echo (!isset($old_input['status_pengguna']) || $old_input['status_pengguna'] === 'pribadi') ? 'selected' : ''; ?>>Perorangan/Pribadi</option>
                            <option value="lembaga" <?php echo (isset($old_input['status_pengguna']) && $old_input['status_pengguna'] === 'lembaga') ? 'selected' : ''; ?>>Kelompok/Lembaga</option>
                          </select>
                        </div>

                        <div class="col-md-6" id="lembaga-field" style="<?php echo (isset($old_input['status_pengguna']) && $old_input['status_pengguna'] === 'lembaga') ? '' : 'display: none;'; ?>">
                          <label class="form-label text-dark fw-medium">Nama Lembaga/Organisasi</label>
                          <input type="text" class="form-control form-control-lg" name="nama_lembaga" placeholder="Nama lembaga/organisasi" value="<?php echo isset($old_input['nama_lembaga']) ? htmlspecialchars($old_input['nama_lembaga']) : ''; ?>">
                        </div>
                      </div>
                    </div>

                    <!-- Informasi Permohonan Section -->
                    <div class="form-section mb-5">
                      <div class="section-header mb-4">
                        <h6 class="section-title text-dark mb-2">
                          <i class="fas fa-file-alt me-2 text-secondary"></i>Detail Informasi Permohonan
                        </h6>
                        <div class="border-bottom border-2 border-secondary" style="width: 60px;"></div>
                      </div>

                      <div class="row g-3">


                        <div class="col-12">
                          <label class="form-label text-dark fw-medium">Kandungan Informasi <span class="text-danger">*</span></label>
                          <textarea class="form-control" name="kandungan_informasi" rows="4" required placeholder="Jelaskan secara detail informasi apa yang diminta dan kandungan informasi yang dibutuhkan"><?php echo isset($old_input['kandungan_informasi']) ? htmlspecialchars($old_input['kandungan_informasi']) : ''; ?></textarea>
                        </div>

                        <div class="col-12">
                          <label class="form-label text-dark fw-medium">Tujuan Penggunaan Informasi <span class="text-danger">*</span></label>
                          <textarea class="form-control" name="tujuan_penggunaan_informasi" rows="3" required placeholder="Jelaskan untuk apa informasi ini akan digunakan"><?php echo isset($old_input['tujuan_penggunaan_informasi']) ? htmlspecialchars($old_input['tujuan_penggunaan_informasi']) : ''; ?></textarea>
                        </div>


                      </div>
                    </div>

                    <!-- Upload Dokumen Section -->
                    <div class="form-section mb-5">
                      <div class="section-header mb-4">
                        <h6 class="section-title text-dark mb-2">
                          <i class="fas fa-upload me-2 text-secondary"></i>Upload Dokumen Pendukung
                        </h6>
                        <div class="border-bottom border-2 border-secondary" style="width: 60px;"></div>
                      </div>

                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Upload KTP</label>
                          <input type="file" class="form-control form-control-lg" name="upload_ktp" accept=".jpg,.jpeg,.png,.pdf">
                          <small class="text-muted">JPG, PNG, PDF (Max: 5MB)</small>
                        </div>

                        <div class="col-md-4" id="akta-field" style="display: none;">
                          <label class="form-label text-dark fw-medium">Upload Akta Lembaga</label>
                          <input type="file" class="form-control form-control-lg" name="upload_akta" accept=".jpg,.jpeg,.png,.pdf">
                          <small class="text-muted">JPG, PNG, PDF (Max: 5MB)</small>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Upload Foto Identitas</label>
                          <input type="file" class="form-control form-control-lg" name="upload_foto_identitas" accept=".jpg,.jpeg,.png,.pdf">
                          <small class="text-muted">JPG, PNG, PDF (Max: 5MB)</small>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label text-dark fw-medium">Upload Data Pendukung</label>
                          <input type="file" class="form-control form-control-lg" name="upload_data_pedukung" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <small class="text-muted">JPG, PNG, PDF, DOC, DOCX (Max: 10MB)</small>
                        </div>
                      </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                      <a href="index.php?controller=permohonanadmin&action=index" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-times me-2"></i>Batal
                      </a>
                      <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-save me-2"></i>Simpan Permohonan
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
    // Province and City API integration
    $(document).ready(function() {
      // Load provinces on page load
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

      // Trigger status_pengguna change on page load for old input
      $('#status_pengguna').trigger('change');

      // Handle province change
      $('#provinsi').change(function() {
        const provinceId = $(this).val();
        if (provinceId) {
          loadCities(provinceId);
        } else {
          $('#city').html('<option value="">Pilih Kota/Kabupaten</option>');
        }
      });

      // Auto-load SKPD dropdown if tujuan_permohonan has value (from old_input)
      <?php if (isset($old_input['tujuan_permohonan']) && !empty($old_input['tujuan_permohonan'])): ?>
        $('#tujuan_permohonan').trigger('change');
      <?php endif; ?>
    });

    function loadProvinces() {
      $.ajax({
        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
        type: 'GET',
        success: function(data) {
          let options = '<option value="">Pilih Provinsi</option>';
          data.forEach(function(province) {
            options += `<option value="${province.id}" data-name="${province.name}">${province.name}</option>`;
          });
          $('#provinsi').html(options);
        },
        error: function() {
          console.error('Failed to load provinces');
        }
      });
    }

    function loadCities(provinceId) {
      $('#city').html('<option value="">Loading...</option>');

      $.ajax({
        url: `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
        type: 'GET',
        success: function(data) {
          let options = '<option value="">Pilih Kota/Kabupaten</option>';
          data.forEach(function(city) {
            options += `<option value="${city.name}">${city.name}</option>`;
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

    // Handle kategori SKPD change to update nama SKPD
    $('#tujuan_permohonan').on('change', function() {
      const selectedKategori = $(this).val();
      const namaSkpdSelect = $('#komponen_tujuan');

      // Clear previous options
      namaSkpdSelect.html('<option value="">-- Loading... --</option>');

      if (selectedKategori === '') {
        namaSkpdSelect.html('<option value="">-- Pilih kategori SKPD terlebih dahulu --</option>');
        return;
      }

      // Make AJAX request to get SKPD list based on category
      $.ajax({
        url: 'index.php?controller=permohonanadmin&action=getKomponen',
        method: 'GET',
        data: { tujuan_permohonan: selectedKategori },
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data.length > 0) {
            let options = '<option value="">-- Pilih Nama SKPD --</option>';
            response.data.forEach(function(skpd) {
              options += `<option value="${skpd.nama_tujuan_permohonan}">${skpd.nama_tujuan_permohonan}</option>`;
            });
            namaSkpdSelect.html(options);
          } else {
            namaSkpdSelect.html('<option value="">-- Tidak ada SKPD tersedia --</option>');
          }
        },
        error: function() {
          namaSkpdSelect.html('<option value="">-- Error loading SKPD --</option>');
          alert('Gagal memuat nama SKPD. Silakan refresh halaman.');
        }
      });
    });
  </script>

  <style>
    /* Government Standard Styling */
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

    .btn-lg {
      padding: 0.875rem 2rem;
      font-size: 1rem;
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

      .col-md-6, .col-md-4 {
        flex: 1 0 100%; /* Make all columns full width on small screens */
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
  </style>

</body>
</html>