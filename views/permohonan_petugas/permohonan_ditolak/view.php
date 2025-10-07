<?php
// Check if user is logged in and has petugas role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

<?php include 'template/header.php'; ?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>

    <div class="container-fluid page-body-wrapper">
      <?php include 'template/sidebar_petugas.php'; ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12">
              <!-- Page Header -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div>
                    <h4 class="page-title mb-1 text-dark">
                      <i class="fas fa-times-circle me-2 text-danger"></i>Detail Permohonan Ditolak
                    </h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb breadcrumb-custom mb-0">
                        <li class="breadcrumb-item">
                          <a href="index.php?controller=permohonanpetugas&action=index">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                          <a href="index.php?controller=permohonanpetugas&action=permohonanDitolak">Permohonan Ditolak</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                          <span>Detail Permohonan</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                  <div class="mt-2 mt-md-0">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                      <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>" 
                         class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i>Cetak Bukti
                      </a>
                      <a href="index.php?controller=permohonanpetugas&action=permohonanDitolak" 
                         class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Success/Error Messages -->
              <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fas fa-check-circle me-2"></i>
                  <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                  <?php unset($_SESSION['success_message']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                  <?php unset($_SESSION['error_message']); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Permohonan Details -->
              <div class="row">
                <div class="col-lg-8">
                  <!-- Informasi Umum -->
                  <div class="card mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Permohonan
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label fw-bold">No. Permohonan</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <span class="badge bg-primary fs-6">
                              <?php echo htmlspecialchars($permohonan['no_permohonan'] ?? '-'); ?>
                            </span>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Status</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php
                            $status_class = '';
                            switch ($permohonan['status']) {
                                case 'Diproses':
                                    $status_class = 'bg-warning';
                                    break;
                                case 'Disposisi':
                                    $status_class = 'bg-primary';
                                    break;
                                case 'Selesai':
                                    $status_class = 'bg-success';
                                    break;
                                case 'Ditolak':
                                    $status_class = 'bg-danger';
                                    break;
                                default:
                                    $status_class = 'bg-secondary';
                            }
                            ?>
                            <span class="badge <?php echo $status_class; ?> fs-6">
                              <?php echo htmlspecialchars($permohonan['status'] ?? '-'); ?>
                            </span>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Tanggal Pengajuan</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php 
                            if ($permohonan['created_at']) {
                              $date = date_create($permohonan['created_at']);
                              echo $date ? date_format($date, 'd F Y H:i:s') : 'N/A';
                            } else {
                              echo 'N/A';
                            }
                            ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">SKPD Tujuan</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <span class="badge bg-info fs-6">
                              <?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? '-'); ?>
                            </span>
                          </div>
                        </div>

                        <div class="col-12">
                          <label class="form-label fw-bold">Judul Dokumen</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-12">
                          <label class="form-label fw-bold">Tujuan Penggunaan Informasi</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo nl2br(htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? '-')); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Sumber Media</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <span class="badge bg-secondary fs-6">
                              <?php echo htmlspecialchars($permohonan['sumber_media'] ?? 'Website'); ?>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Data Pemohon -->
                  <div class="card mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>Data Pemohon
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label fw-bold">Nama Lengkap</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">NIK</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['nik'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Email</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['email'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">No. Kontak</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['no_kontak'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label fw-bold">Jenis Kelamin</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['jenis_kelamin'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label fw-bold">Usia</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['usia'] ?? '-'); ?> tahun
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label class="form-label fw-bold">Pendidikan</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['pendidikan'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Pekerjaan</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['pekerjaan'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Status Pemohon</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <span class="badge <?php echo ($permohonan['status_pengguna'] ?? '') === 'lembaga' ? 'bg-primary' : 'bg-secondary'; ?> fs-6">
                              <?php echo htmlspecialchars(ucfirst($permohonan['status_pengguna'] ?? 'pribadi')); ?>
                            </span>
                          </div>
                        </div>

                        <?php if (($permohonan['status_pengguna'] ?? '') === 'lembaga'): ?>
                          <div class="col-12">
                            <label class="form-label fw-bold">Nama Lembaga/Organisasi</label>
                            <div class="form-control-plaintext border-bottom pb-2">
                              <?php echo htmlspecialchars($permohonan['nama_lembaga'] ?? '-'); ?>
                            </div>
                          </div>
                        <?php endif; ?>

                        <div class="col-12">
                          <label class="form-label fw-bold">Alamat Lengkap</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo nl2br(htmlspecialchars($permohonan['alamat'] ?? '-')); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Provinsi</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['provinsi'] ?? '-'); ?>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label class="form-label fw-bold">Kota/Kabupaten</label>
                          <div class="form-control-plaintext border-bottom pb-2">
                            <?php echo htmlspecialchars($permohonan['city'] ?? '-'); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Alasan Penolakan -->
                  <?php if (!empty($permohonan['alasan_penolakan']) || !empty($permohonan['catatan_petugas'])): ?>
                    <div class="card mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0">
                          <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Informasi Penolakan
                        </h5>
                      </div>
                      <div class="card-body">
                        <?php if (!empty($permohonan['alasan_penolakan'])): ?>
                          <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Penolakan</label>
                            <div class="alert alert-danger mb-0">
                              <i class="fas fa-times-circle me-2"></i>
                              <?php echo htmlspecialchars($permohonan['alasan_penolakan']); ?>
                            </div>
                          </div>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['catatan_petugas'])): ?>
                          <div>
                            <label class="form-label fw-bold">Catatan Petugas</label>
                            <div class="alert alert-info mb-0">
                              <i class="fas fa-sticky-note me-2"></i>
                              <?php echo nl2br(htmlspecialchars($permohonan['catatan_petugas'])); ?>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="col-lg-4">
                  <!-- Foto Profile -->
                  <?php if (!empty($permohonan['foto_profile'])): ?>
                    <div class="card mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0">
                          <i class="fas fa-user-circle me-2 text-primary"></i>Foto Profile
                        </h5>
                      </div>
                      <div class="card-body text-center">
                        <img src="<?php echo htmlspecialchars($permohonan['foto_profile']); ?>" 
                             alt="Foto Profile" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 300px; object-fit: cover;">
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Dokumen Pendukung -->
                  <div class="card">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0">
                        <i class="fas fa-file-download me-2 text-primary"></i>Dokumen Pendukung
                      </h5>
                    </div>
                    <div class="card-body">
                      <?php
                      $documents = [
                          'KTP' => $permohonan['upload_ktp'] ?? '',
                          'Akta Lembaga' => $permohonan['upload_akta'] ?? '',
                          'Foto Identitas' => $permohonan['upload_foto_identitas'] ?? '',
                          'Data Pendukung' => $permohonan['upload_data_pedukung'] ?? ''
                      ];

                      $has_documents = false;
                      foreach ($documents as $doc_name => $doc_path) {
                          if (!empty($doc_path)) {
                              $has_documents = true;
                              $file_extension = pathinfo($doc_path, PATHINFO_EXTENSION);
                              $is_image = in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']) && file_exists($doc_path);
                              ?>
                              <div class="mb-3">
                                <label class="form-label fw-bold"><?php echo htmlspecialchars($doc_name); ?></label>
                                <div class="d-grid gap-2">
                                  <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo urlencode(basename($doc_path)); ?>&id=<?php echo $permohonan['id_permohonan']; ?>" 
                                     class="btn btn-outline-primary btn-sm">
                                    <i class="<?php echo $is_image ? 'fas fa-image' : 'fas fa-file'; ?> me-2"></i>
                                    Download <?php echo htmlspecialchars($doc_name); ?>
                                  </a>
                                </div>
                              </div>
                              <?php
                          }
                      }

                      if (!$has_documents): ?>
                        <div class="text-center py-4">
                          <i class="fas fa-file-alt text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                          <p class="text-muted mt-3 mb-0">Tidak ada dokumen pendukung</p>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php include 'template/footer.php'; ?>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>
</body>

</html>