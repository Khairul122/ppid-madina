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
          <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Detail Permohonan Keberatan</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanpetugas&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanpetugas&action=keberatanIndex" class="text-decoration-none">Keberatan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="index.php?controller=permohonanpetugas&action=keberatanIndex" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div class="row g-4">
                <!-- Left Column -->
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
                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">No. Permohonan</label>
                            <div class="info-value">
                              <span class="badge bg-primary fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['no_permohonan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Status</label>
                            <div class="info-value">
                              <span class="badge bg-warning fs-6 px-3 py-2">Keberatan</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Judul Dokumen</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Kandungan Informasi</label>
                            <div class="info-value">
                              <div class="bg-light p-3 rounded">
                                <?php echo nl2br(htmlspecialchars($permohonan['kandungan_informasi'] ?? '')); ?>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Tujuan Permohonan</label>
                            <div class="info-value">
                              <span class="badge bg-info"><?php echo htmlspecialchars($permohonan['tujuan_permohonan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Tujuan Penggunaan</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['tujuan_penggunaan_informasi'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Tanggal Pengajuan</label>
                            <div class="info-value">
                              <?php echo date('d F Y, H:i', strtotime($permohonan['created_at'] ?? 'now')); ?> WIB
                            </div>
                          </div>
                        </div>

                        <?php if (!empty($permohonan['sumber_media'])): ?>
                          <div class="col-md-6">
                            <div class="info-item">
                              <label class="info-label">Sumber Media</label>
                              <div class="info-value"><?php echo htmlspecialchars($permohonan['sumber_media'] ?? ''); ?></div>
                            </div>
                          </div>
                        <?php endif; ?>
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
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">NIK</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['nik'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Alamat</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['alamat'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Provinsi / Kota</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['provinsi'] ?? ''); ?> / <?php echo htmlspecialchars($permohonan['city'] ?? ''); ?></div>
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

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Pekerjaan</label>
                            <div class="info-value"><?php echo htmlspecialchars($permohonan['pekerjaan'] ?? ''); ?></div>
                          </div>
                        </div>

                        <?php if (!empty($permohonan['nama_lembaga'])): ?>
                          <div class="col-12">
                            <div class="info-item">
                              <label class="info-label">Nama Lembaga</label>
                              <div class="info-value"><?php echo htmlspecialchars($permohonan['nama_lembaga'] ?? ''); ?></div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Right Column - Files & Documents -->
                <div class="col-lg-4">

                  <!-- Surat Bukti Permohonan -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                        Surat Bukti Permohonan
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid">
                        <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                           class="btn btn-danger btn-lg" target="_blank">
                          <i class="fas fa-file-pdf me-2"></i>Download Surat Bukti
                        </a>
                      </div>
                      <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        PDF bukti penerimaan permohonan
                      </small>
                    </div>
                  </div>

                  <!-- File Uploads -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-paperclip me-2 text-primary"></i>
                        File Upload Pemohon
                      </h5>
                    </div>
                    <div class="card-body p-4">

                      <!-- Foto Identitas -->
                      <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                        <div class="file-item mb-3 pb-3 border-bottom">
                          <label class="file-label">
                            <i class="fas fa-id-card me-2 text-primary"></i>
                            Foto Identitas
                          </label>
                          <div class="mt-2">
                            <a href="javascript:void(0)" onclick="showPhotoModal('<?php echo htmlspecialchars($permohonan['upload_foto_identitas']); ?>')" class="btn btn-sm btn-outline-primary w-100 mb-2">
                              <i class="fas fa-eye me-1"></i>Lihat
                            </a>
                            <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_foto_identitas']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                               class="btn btn-sm btn-primary w-100">
                              <i class="fas fa-download me-1"></i>Download
                            </a>
                          </div>
                        </div>
                      <?php endif; ?>

                      <!-- KTP -->
                      <?php if (!empty($permohonan['upload_ktp'])): ?>
                        <div class="file-item mb-3 pb-3 border-bottom">
                          <label class="file-label">
                            <i class="fas fa-id-card me-2 text-success"></i>
                            Upload KTP
                          </label>
                          <div class="mt-2">
                            <a href="javascript:void(0)" onclick="showPhotoModal('<?php echo htmlspecialchars($permohonan['upload_ktp']); ?>')" class="btn btn-sm btn-outline-success w-100 mb-2">
                              <i class="fas fa-eye me-1"></i>Lihat
                            </a>
                            <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_ktp']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                               class="btn btn-sm btn-success w-100">
                              <i class="fas fa-download me-1"></i>Download
                            </a>
                          </div>
                        </div>
                      <?php endif; ?>

                      <!-- Akta -->
                      <?php if (!empty($permohonan['upload_akta'])): ?>
                        <div class="file-item mb-3 pb-3 border-bottom">
                          <label class="file-label">
                            <i class="fas fa-certificate me-2 text-info"></i>
                            Upload Akta
                          </label>
                          <div class="mt-2">
                            <a href="javascript:void(0)" onclick="showPhotoModal('<?php echo htmlspecialchars($permohonan['upload_akta']); ?>')" class="btn btn-sm btn-outline-info w-100 mb-2">
                              <i class="fas fa-eye me-1"></i>Lihat
                            </a>
                            <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_akta']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                               class="btn btn-sm btn-info w-100">
                              <i class="fas fa-download me-1"></i>Download
                            </a>
                          </div>
                        </div>
                      <?php endif; ?>

                      <!-- Data Pendukung -->
                      <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                        <div class="file-item">
                          <label class="file-label">
                            <i class="fas fa-folder me-2 text-warning"></i>
                            Data Pendukung
                          </label>
                          <div class="mt-2">
                            <a href="index.php?controller=permohonanpetugas&action=downloadFile&file=<?php echo basename($permohonan['upload_data_pedukung']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                               class="btn btn-sm btn-warning w-100">
                              <i class="fas fa-download me-1"></i>Download
                            </a>
                          </div>
                        </div>
                      <?php endif; ?>

                      <?php if (empty($permohonan['upload_foto_identitas']) && empty($permohonan['upload_ktp']) && empty($permohonan['upload_akta']) && empty($permohonan['upload_data_pedukung'])): ?>
                        <div class="text-center py-3">
                          <i class="fas fa-folder-open text-muted" style="font-size: 2rem; opacity: 0.3;"></i>
                          <p class="text-muted mt-2 mb-0">Tidak ada file upload</p>
                        </div>
                      <?php endif; ?>
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
                        <div class="profile-photo-container mb-3">
                          <img src="<?php echo htmlspecialchars($permohonan['foto_profile']); ?>"
                            class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 200px; object-fit: cover;" alt="Foto Profile">
                        </div>
                      </div>
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

  <!-- Photo Modal -->
  <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="photoModalLabel">File Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img id="modalPhoto" src="" class="img-fluid rounded" alt="File Preview" style="max-width: 100%; max-height: 70vh;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

  <script>
    // Show photo modal
    function showPhotoModal(photoSrc) {
      document.getElementById('modalPhoto').src = photoSrc;
      const modal = new bootstrap.Modal(document.getElementById('photoModal'));
      modal.show();
    }
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

    .file-item {
      padding: 0.75rem;
      background-color: #f8fafc;
      border-radius: 6px;
    }

    .file-label {
      font-weight: 600;
      color: var(--gov-dark);
      font-size: 0.875rem;
      margin-bottom: 0.5rem;
      display: block;
    }

    .badge {
      font-size: 0.875rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
      border-radius: 6px;
    }

    .btn {
      font-weight: 500;
      border-radius: 6px;
      transition: all 0.2s ease;
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
  </style>

</body>

</html>
