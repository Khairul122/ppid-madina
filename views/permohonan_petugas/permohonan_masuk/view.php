<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
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
      <?php include 'template/sidebar_petugas.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12">

              <!-- Alert Messages -->
              <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                  <i class="mdi mdi-check-circle me-2"></i>
                  <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                  ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>


              <!-- Page Header -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Detail Permohonan Informasi</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanpetugas&action=permohonanMasuk" class="text-decoration-none">Permohonan Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Permohonan</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-success btn-sm" target="_blank">
                      <i class="fas fa-file-pdf me-1"></i>Cetak Bukti Permohonan
                    </a>
                    <a href="index.php?controller=permohonanpetugas&action=permohonanMasuk" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <!-- Permohonan Info -->
              <div class="row g-4">
                <div class="col-lg-8">
                  <!-- Informasi Permohonan -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Informasi Permohonan
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">No. Permohonan</label>
                            <div class="info-value">
                              <span class="badge bg-primary fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['no_permohonan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Status Permohonan</label>
                            <div class="info-value">
                              <?php
                              $status = $permohonan['status'] ?? 'Diproses';
                              $statusClass = '';
                              switch ($status) {
                                case 'Selesai':
                                case 'approved':
                                  $statusClass = 'bg-success';
                                  break;
                                case 'Ditolak':
                                case 'rejected':
                                  $statusClass = 'bg-danger';
                                  break;
                                default:
                                  $statusClass = 'bg-warning text-dark';
                              }
                              ?>
                              <span class="badge <?php echo $statusClass; ?> fs-6 px-3 py-2"><?php echo htmlspecialchars($status); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">SKPD Tujuan</label>
                            <div class="info-value">
                              <span class="badge bg-info fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Judul Dokumen Informasi</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Kandungan Informasi / Tujuan Penggunaan</label>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? '')); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Sumber Media</label>
                            <div class="info-value">
                              <span class="badge bg-info fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['sumber_media'] ?? 'Website'); ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Data Pemohon -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Data Pemohon
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username']); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">NIK</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nik'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Email</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['email'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">No. Kontak</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['no_kontak'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="info-item">
                            <label class="info-label">Jenis Kelamin</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['jenis_kelamin'] ?? '-'); ?></div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="info-item">
                            <label class="info-label">Usia</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['usia'] ?? '-'); ?> tahun</div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="info-item">
                            <label class="info-label">Pendidikan</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['pendidikan'] ?? '-'); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Pekerjaan</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['pekerjaan'] ?? '-'); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Status Pemohon</label>
                            <div class="info-value">
                              <span class="badge <?php echo ($permohonan['status_pengguna'] ?? '') === 'lembaga' ? 'bg-primary' : 'bg-secondary'; ?> fs-6 px-3 py-2">
                                <?php echo htmlspecialchars(ucfirst($permohonan['status_pengguna'] ?? 'pribadi')); ?>
                              </span>
                            </div>
                          </div>
                        </div>

                        <?php if (($permohonan['status_pengguna'] ?? '') === 'lembaga'): ?>
                          <div class="col-12">
                            <div class="info-item">
                              <label class="info-label">Nama Lembaga/Organisasi</label>
                              <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lembaga'] ?? ''); ?></div>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Alamat Lengkap</label>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($permohonan['alamat'] ?? '')); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Provinsi / Kota</label>
                            <div class="info-value"><?php echo htmlspecialchars(($permohonan['provinsi'] ?? '') . ', ' . ($permohonan['city'] ?? '')); ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <!-- Update Status Diproses -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Update Status
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <form id="status-form">
                        <input type="hidden" name="id" value="<?php echo $permohonan['id_permohonan']; ?>">
                        <div class="mb-3">
                          <label class="form-label text-dark fw-medium">Status Permohonan</label>
                          <select name="status" class="form-select form-control-lg" id="status-select">
                            <option value="Diproses" <?php echo ($permohonan['status'] ?? '') == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="Disposisi" <?php echo ($permohonan['status'] ?? '') == 'Disposisi' ? 'selected' : ''; ?>>Disposisi</option>
                            <option value="Selesai" <?php echo ($permohonan['status'] ?? '') == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Ditolak" <?php echo ($permohonan['status'] ?? '') == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                          </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                          <i class="fas fa-save me-1"></i>Update Status
                        </button>
                      </form>

                      <div class="mt-4">
                        <button type="button" class="btn btn-warning w-100 btn-lg" id="perpanjang-jatuh-tempo" data-id="<?php echo $permohonan['id_permohonan']; ?>">
                          <i class="fas fa-calendar-plus me-1"></i>Perpanjang Jatuh Tempo 7 Hari Kerja
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Foto Profile -->
                  <?php if (!empty($permohonan['foto_profile'])): ?>
                    <div class="card shadow-sm border-0 mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0 text-dark fw-normal">
                          <i class="fas fa-user-circle me-2 text-primary"></i>
                          Foto Profile
                        </h5>
                      </div>
                      <div class="card-body p-4 text-center">
                        <div class="profile-photo-container">
                          <img src="<?php echo htmlspecialchars($permohonan['foto_profile']); ?>"
                            class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 200px; object-fit: cover;" alt="Foto Profile">
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- File Downloads -->
                  <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-download me-2 text-primary"></i>
                        File Dokumen
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-2">
                        <?php if (!empty($permohonan['upload_ktp'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_ktp']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-id-card me-2"></i>Download KTP
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_akta'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_akta']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-certificate me-2"></i>Download Akta Lembaga
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_foto_identitas']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-info">
                            <i class="fas fa-camera me-2"></i>Download Foto Identitas
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                          <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_data_pedukung']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-info">
                            <i class="fas fa-file-upload me-2"></i>Download Data Pendukung
                          </a>
                        <?php endif; ?>

                        <?php if (
                          empty($permohonan['upload_ktp']) && empty($permohonan['upload_akta']) &&
                          empty($permohonan['upload_foto_identitas']) && empty($permohonan['upload_data_pedukung'])
                        ): ?>
                          <div class="text-center py-4">
                            <i class="fas fa-file-alt text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3 mb-0">Tidak ada file upload</p>
                          </div>
                        <?php endif; ?>
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

  <!-- Modal Disposisi -->
  <div class="modal fade" id="disposisiModal" tabindex="-1" aria-labelledby="disposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="disposisiModalLabel">
            <i class="fas fa-share-alt me-2"></i>Disposisi Permohonan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="disposisi-form">
            <input type="hidden" name="id" value="<?php echo $permohonan['id_permohonan']; ?>">
            <input type="hidden" name="status" value="Disposisi">

            <div class="mb-3">
              <label for="tujuan_permohonan" class="form-label fw-bold">Tujuan Permohonan <span class="text-danger"> *</span></label>
              <select name="tujuan_permohonan" id="tujuan_permohonan" class="form-select" required>
                <option value="">-- Pilih Tujuan Permohonan --</option>
              </select>
              <div class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>Memuat...
              </div>
            </div>

            <div class="mb-3">
              <label for="komponen_tujuan" class="form-label fw-bold">Komponen Tujuan <span class="text-danger"> *</span></label>
              <select name="komponen_tujuan" id="komponen_tujuan" class="form-select" required disabled>
                <option value="">-- Pilih Tujuan Permohonan Terlebih Dahulu --</option>
              </select>
              <div class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>Pilih tujuan permohonan terlebih dahulu untuk menampilkan daftar SKPD
              </div>
            </div>

            <div class="mb-4">
              <label for="catatan_petugas" class="form-label fw-bold">Catatan Petugas</label>
              <textarea name="catatan_petugas" id="catatan_petugas" class="form-control" rows="4"
                        placeholder="Masukkan catatan atau instruksi khusus untuk dinas/komponen tujuan..."></textarea>
              <small class="form-text text-muted">Opsional: Berikan catatan atau instruksi khusus terkait disposisi ini</small>
            </div>

            <div class="alert alert-info" role="alert">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Informasi:</strong> Permohonan yang telah didisposisi akan diteruskan ke dinas/komponen yang bersangkutan untuk ditindaklanjuti.
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="button" class="btn btn-warning" id="btn-confirm-disposisi">
            <i class="fas fa-share-alt me-1"></i>Lanjutkan Disposisi
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Disposisi -->
  <div class="modal fade" id="confirmDisposisiModal" tabindex="-1" aria-labelledby="confirmDisposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title text-white" id="confirmDisposisiModalLabel">
            <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Disposisi
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <i class="fas fa-share-alt text-warning" style="font-size: 3rem;"></i>
          </div>
          <h6 class="text-center mb-3">Disposisi permohonan ini?</h6>
          <p class="text-center text-muted">
            Permohonan yang telah diposisi akan diteruskan ke dinas/komponen yang bersangkutan
          </p>

          <div class="card bg-light">
            <div class="card-body">
              <h6 class="card-title">Detail Disposisi:</h6>
              <div class="row">
                <div class="col-sm-5"><strong>Tujuan Permohonan:</strong></div>
                <div class="col-sm-7" id="confirm-kategori">-</div>
              </div>
              <div class="row">
                <div class="col-sm-5"><strong>Komponen Tujuan:</strong></div>
                <div class="col-sm-7" id="confirm-komponen">-</div>
              </div>
              <div class="row" id="confirm-catatan-row" style="display: none;">
                <div class="col-sm-5"><strong>Catatan:</strong></div>
                <div class="col-sm-7" id="confirm-catatan">-</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="button" class="btn btn-warning" id="btn-submit-disposisi">
            <i class="fas fa-check me-1"></i>Ya, Disposisi Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Penolakan -->
  <div class="modal fade" id="penolakanModal" tabindex="-1" aria-labelledby="penolakanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white" id="penolakanModalLabel">
            <i class="fas fa-times-circle me-2"></i>Keputusan PPID DITOLAK
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="penolakan-form">
            <input type="hidden" name="id" value="<?php echo $permohonan['id_permohonan']; ?>">
            <input type="hidden" name="status" value="Ditolak">

            <div class="mb-3">
              <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger"> *</span></label>
              <select name="alasan_penolakan" id="alasan_penolakan" class="form-select" required>
                <option value="">-- Pilih Alasan Penolakan --</option>
                <option value="Belum Dikuasai">Belum Dikuasai</option>
                <option value="Belum Dikomentasikan">Belum Dikomentasikan</option>
                <option value="Otoritas Instansi Lain">Otoritas Instansi Lain</option>
                <option value="Informasi Dikecualikan">Informasi Dikecualikan</option>
              </select>
              <div class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>Pilih alasan penolakan permohonan informasi
              </div>
            </div>

            <div class="mb-4">
              <label for="catatan_petugas_penolakan" class="form-label fw-bold">Catatan Petugas <span class="text-danger"> *</span></label>
              <textarea name="catatan_petugas" id="catatan_petugas_penolakan" class="form-control" rows="4"
                        placeholder="Masukkan catatan atau penjelasan tambahan terkait penolakan..." required></textarea>
              <small class="form-text text-muted">Berikan penjelasan detail terkait alasan penolakan permohonan ini</small>
            </div>

            <div class="alert alert-warning" role="alert">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Perhatian:</strong> Permohonan yang ditolak tidak dapat diproses lebih lanjut. Pastikan alasan penolakan dan catatan sudah sesuai.
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Batal
          </button>
          <button type="button" class="btn btn-danger" id="btn-submit-penolakan">
            <i class="fas fa-times-circle me-1"></i>Tolak Permohonan
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Handle status update
    $('#status-form').on('submit', function(e) {
      e.preventDefault();

      const selectedStatus = $('#status-select').val();

      // Jika status yang dipilih adalah Disposisi, tampilkan modal
      if (selectedStatus === 'Disposisi') {
        $('#disposisiModal').modal('show');
        return;
      }

      // Jika status yang dipilih adalah Ditolak, tampilkan modal penolakan
      if (selectedStatus === 'Ditolak') {
        $('#penolakanModal').modal('show');
        return;
      }

      // Untuk status selain disposisi dan ditolak, lanjutkan dengan update biasa
      const formData = new FormData(this);

      // Debug: log form data
      console.log('Form data:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=updateStatus',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          console.log('Raw response:', response);
          console.log('Response type:', typeof response);

          // Check if response is already an object (jQuery auto-parsed)
          let result;
          if (typeof response === 'object') {
            result = response;
          } else {
            try {
              result = JSON.parse(response);
            } catch (e) {
              console.error('Error parsing JSON:', e);
              console.log('Response content:', response);
              console.log('Response length:', response.length);

              // Try to clean the response and parse again
              const cleanedResponse = response.trim().replace(/^[^{]*/, '').replace(/[^}]*$/, '');
              console.log('Cleaned response:', cleanedResponse);

              try {
                result = JSON.parse(cleanedResponse);
              } catch (e2) {
                alert('Error: Response tidak valid dari server\n\nResponse: ' + response.substring(0, 200));
                return;
              }
            }
          }

          if (result.success) {
            alert('Status berhasil diupdate');
            location.reload();
          } else {
            alert('Gagal mengupdate status: ' + result.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX error:', error);
          console.log('Status:', status);
          console.log('Response Text:', xhr.responseText);
          alert('Terjadi kesalahan saat mengupdate status: ' + error);
        }
      });
    });

    // Handle perpanjang jatuh tempo
    $('#perpanjang-jatuh-tempo').on('click', function() {
      const id = $(this).data('id');

      if (confirm('Apakah Anda yakin ingin memperpanjang jatuh tempo permohonan ini 7 hari kerja?')) {
        $.ajax({
          url: 'index.php?controller=permohonanpetugas&action=perpanjangJatuhTempo',
          type: 'POST',
          data: {
            id: id
          },
          dataType: 'json',
          success: function(response) {
            console.log('Perpanjang response:', response);
            if (response.success) {
              alert('Jatuh tempo berhasil diperpanjang');
              location.reload();
            } else {
              alert('Gagal memperpanjang jatuh tempo: ' + response.message);
            }
          },
          error: function(xhr, status, error) {
            console.error('Perpanjang AJAX error:', error);
            console.log('Response Text:', xhr.responseText);
            alert('Terjadi kesalahan saat memperpanjang jatuh tempo: ' + error);
          }
        });
      }
    });

    // Handle modal disposisi
    $('#btn-confirm-disposisi').on('click', function() {
      // Validasi form disposisi
      const kategori = $('#tujuan_permohonan').val();
      const komponen = $('#komponen_tujuan').val();
      const catatan = $('#catatan_petugas').val();

      if (!kategori) {
        alert('Tujuan Permohonan harus dipilih!');
        return;
      }

      if (!komponen) {
        alert('Komponen tujuan harus dipilih!');
        return;
      }

      // Tutup modal disposisi dan tampilkan modal konfirmasi
      $('#disposisiModal').modal('hide');

      // Set data konfirmasi
      $('#confirm-kategori').text(kategori);
      $('#confirm-komponen').text(komponen);

      if (catatan.trim()) {
        $('#confirm-catatan').text(catatan);
        $('#confirm-catatan-row').show();
      } else {
        $('#confirm-catatan-row').hide();
      }

      // Tampilkan modal konfirmasi
      $('#confirmDisposisiModal').modal('show');
    });

    // Handle submit disposisi
    $('#btn-submit-disposisi').on('click', function() {
      const formData = new FormData();

      // Data dari form utama
      formData.append('id', $('input[name="id"]').val());
      formData.append('status', 'Disposisi');

      // Data dari modal disposisi
      formData.append('tujuan_permohonan', $('#tujuan_permohonan').val());
      formData.append('komponen_tujuan', $('#komponen_tujuan').val());
      formData.append('catatan_petugas', $('#catatan_petugas').val());

      // Debug: log form data
      console.log('Disposisi form data:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=updateStatus',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          console.log('Disposisi response:', response);

          // Check if response is already an object (jQuery auto-parsed)
          let result;
          if (typeof response === 'object') {
            result = response;
          } else {
            try {
              result = JSON.parse(response);
            } catch (e) {
              console.error('Error parsing JSON:', e);
              console.log('Response content:', response);

              // Try to clean the response and parse again
              const cleanedResponse = response.trim().replace(/^[^{]*/, '').replace(/[^}]*$/, '');
              console.log('Cleaned response:', cleanedResponse);

              try {
                result = JSON.parse(cleanedResponse);
              } catch (e2) {
                alert('Error: Response tidak valid dari server\n\nResponse: ' + response.substring(0, 200));
                return;
              }
            }
          }

          $('#confirmDisposisiModal').modal('hide');

          if (result.success) {
            alert('Permohonan berhasil' + $('#komponen_tujuan').val());
            location.reload();
          } else {
            alert('Gagal melakukan disposisi: ' + result.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Disposisi AJAX error:', error);
          console.log('Response Text:', xhr.responseText);
          $('#confirmDisposisiModal').modal('hide');
          alert('Terjadi kesalahan saat melakukan disposisi: ' + error);
        }
      });
    });

    // Reset modal ketika ditutup
    $('#disposisiModal').on('hidden.bs.modal', function () {
      $('#disposisi-form')[0].reset();
    });

    $('#confirmDisposisiModal').on('hidden.bs.modal', function () {
      // Jika modal konfirmasi ditutup tanpa submit, reset status select
      $('#status-select').val($('#status-select option:selected').data('current-status') || 'Diproses');
    });

    // Load SKPD data dinamis
    function loadSKPDData() {
      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=getSKPDData',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // Simpan data SKPD untuk penggunaan selanjutnya
            window.skpdData = response.data;

            // Populate kategori dropdown
            const kategoriSelect = $('#tujuan_permohonan');
            kategoriSelect.empty();
            kategoriSelect.append('<option value="">-- Pilih Tujuan Permohonan --</option>');

            response.data.categories.forEach(function(category) {
              kategoriSelect.append(`<option value="${category}">${category}</option>`);
            });

            // Update form text
            $('#tujuan_permohonan').parent().find('.form-text').html(
            
            );
          } else {
            console.error('Gagal memuat data SKPD:', response.message);
            $('#tujuan_permohonan').parent().find('.form-text').html(
              '<i class="fas fa-exclamation-triangle me-1 text-warning"></i>Gagal memuat kategori: ' + response.message
            );
          }
        },
        error: function(xhr, status, error) {
          console.error('Error loading SKPD data:', error);
          $('#tujuan_permohonan').parent().find('.form-text').html(
            '<i class="fas fa-exclamation-triangle me-1 text-danger"></i>Error memuat kategori dari server'
          );
        }
      });
    }

    // Handle perubahan kategori
    $('#tujuan_permohonan').on('change', function() {
      const selectedCategory = $(this).val();
      const komponenSelect = $('#komponen_tujuan');

      if (!selectedCategory) {
        komponenSelect.prop('disabled', true);
        komponenSelect.empty();
        komponenSelect.append('<option value="">-- Pilih Tujuan Permohonan Terlebih Dahulu --</option>');
        return;
      }

      // Filter SKPD berdasarkan kategori yang dipilih
      if (window.skpdData && window.skpdData.skpd_list) {
        const filteredSKPD = window.skpdData.skpd_list.filter(function(skpd) {
          return skpd.kategori === selectedCategory;
        });

        komponenSelect.empty();
        komponenSelect.append('<option value="">-- Pilih Dinas/Komponen --</option>');

        filteredSKPD.forEach(function(skpd) {
          komponenSelect.append(`<option value="${skpd.nama_skpd}">${skpd.nama_skpd}</option>`);
        });

        komponenSelect.prop('disabled', false);

        // Update form text
        $('#komponen_tujuan').parent().find('.form-text').html(
          `<i class="fas fa-info-circle me-1"></i>Ditemukan ${filteredSKPD.length} SKPD untuk kategori "${selectedCategory}"`
        );
      }
    });

    // Load data SKPD saat modal disposisi dibuka
    $('#disposisiModal').on('show.bs.modal', function() {
      if (!window.skpdData) {
        loadSKPDData();
      }
    });

    // Handle submit penolakan
    $('#btn-submit-penolakan').on('click', function() {
      const alasanPenolakan = $('#alasan_penolakan').val();
      const catatanPetugas = $('#catatan_petugas_penolakan').val();

      // Validasi form
      if (!alasanPenolakan) {
        alert('Alasan penolakan harus dipilih!');
        return;
      }

      if (!catatanPetugas.trim()) {
        alert('Catatan petugas harus diisi!');
        return;
      }

      const formData = new FormData();
      formData.append('id', $('input[name="id"]').val());
      formData.append('status', 'Ditolak');
      formData.append('alasan_penolakan', alasanPenolakan);
      formData.append('catatan_petugas', catatanPetugas);

      // Debug: log form data
      console.log('Penolakan form data:');
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }

      $.ajax({
        url: 'index.php?controller=permohonanpetugas&action=updateStatus',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          console.log('Penolakan response:', response);

          let result;
          if (typeof response === 'object') {
            result = response;
          } else {
            try {
              result = JSON.parse(response);
            } catch (e) {
              console.error('Error parsing JSON:', e);
              const cleanedResponse = response.trim().replace(/^[^{]*/, '').replace(/[^}]*$/, '');
              try {
                result = JSON.parse(cleanedResponse);
              } catch (e2) {
                alert('Error: Response tidak valid dari server\n\nResponse: ' + response.substring(0, 200));
                return;
              }
            }
          }

          $('#penolakanModal').modal('hide');

          if (result.success) {
            alert('Permohonan berhasil ditolak');
            location.reload();
          } else {
            alert('Gagal menolak permohonan: ' + result.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Penolakan AJAX error:', error);
          console.log('Response Text:', xhr.responseText);
          $('#penolakanModal').modal('hide');
          alert('Terjadi kesalahan saat menolak permohonan: ' + error);
        }
      });
    });

    // Reset modal penolakan ketika ditutup
    $('#penolakanModal').on('hidden.bs.modal', function () {
      $('#penolakan-form')[0].reset();
      $('#status-select').val($('#status-select option:selected').data('current-status') || 'Diproses');
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
      background: white;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #f8fafc;
      border-bottom: 1px solid var(--gov-border);
      padding: 1rem 1.5rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 500;
      margin: 0;
    }

    .card-body {
      padding: 1.5rem;
      background: white;
    }

    .info-item {
      margin-bottom: 1.5rem;
    }

    .info-item:last-child {
      margin-bottom: 0;
    }

    .info-label {
      font-weight: 600;
      color: var(--gov-dark);
      font-size: 0.875rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
      display: block;
    }

    .info-value {
      color: var(--gov-dark);
      font-size: 1rem;
      line-height: 1.5;
      padding: 0.75rem 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .badge {
      font-size: 0.875rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
      border-radius: 6px;
    }

    .bg-primary {
      background-color: var(--gov-primary) !important;
      color: white;
    }

    .bg-success {
      background-color: var(--gov-success) !important;
      color: white;
    }

    .bg-warning {
      background-color: var(--gov-warning) !important;
      color: white;
    }

    .bg-danger {
      background-color: var(--gov-danger) !important;
      color: white;
    }

    .bg-info {
      background-color: #0ea5e9 !important;
      color: white;
    }

    .bg-secondary {
      background-color: var(--gov-secondary) !important;
      color: white;
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

    .btn-outline-primary {
      border: 1.5px solid var(--gov-primary);
      color: var(--gov-primary);
      background-color: white;
    }

    .btn-outline-primary:hover {
      background-color: var(--gov-primary);
      color: white;
      transform: translateY(-1px);
    }

    .btn-outline-info {
      border: 1.5px solid #0ea5e9;
      color: #0ea5e9;
      background-color: white;
    }

    .btn-outline-info:hover {
      background-color: #0ea5e9;
      color: white;
      transform: translateY(-1px);
    }

    .btn-warning {
      background-color: var(--gov-warning);
      color: white;
    }

    .btn-warning:hover {
      background-color: #d97706;
      transform: translateY(-1px);
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
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--gov-border);
      border-radius: 6px;
      padding: 0.75rem 1rem;
      background-color: white;
      color: var(--gov-dark);
      transition: all 0.2s ease;
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

    .form-label {
      color: var(--gov-dark);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .profile-photo-container {
      display: inline-block;
      position: relative;
    }

    .profile-photo-container img {
      border: 3px solid var(--gov-border);
    }

    .d-grid {
      display: grid !important;
    }

    .gap-2 {
      gap: 0.5rem !important;
    }

    .text-muted {
      color: #94a3b8 !important;
    }

    .shadow-sm {
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Responsive Design */
    @media (max-width: 992px) {

      .col-lg-8,
      .col-lg-4 {
        margin-bottom: 2rem;
      }

      .card-body {
        padding: 1rem;
      }

      .page-header {
        padding: 1rem;
      }
    }

    @media (max-width: 768px) {
      .container-fluid {
        padding: 1rem;
      }

      .info-item {
        margin-bottom: 1rem;
      }

      .info-label {
        font-size: 0.8rem;
      }

      .info-value {
        padding: 0.5rem 0;
      }

      .btn-lg {
        padding: 0.625rem 1.25rem;
        font-size: 0.95rem;
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

      .breadcrumb {
        font-size: 0.875rem;
      }

      .card-body {
        padding: 1rem;
      }

      .badge {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
      }
    }

    /* Animation */
    .btn:active {
      transform: translateY(0);
    }

    .card {
      transition: box-shadow 0.2s ease;
    }

    .card:hover {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
  </style>

</body>

</html>