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
            <div class="col-12 col-xl-11">

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Detail Permohonan Selesai</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=selesaiIndex" class="text-decoration-none">Selesai</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?controller=permohonanadmin&action=selesaiIndex" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div class="row g-4">
                <!-- Permohonan Info -->
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
                              <span class="badge bg-success fs-6 px-3 py-2">Selesai</span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">SKPD Tujuan</label>
                            <div class="info-value">
                              <span class="badge bg-info fs-6 px-3 py-2"><?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? '-'); ?></span>
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

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Tanggal Permohonan</label>
                            <div class="info-value">
                              <?php echo date('d/m/Y H:i', strtotime($permohonan['created_at'] ?? 'now')); ?>
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

                <!-- Status & Files -->
                <div class="col-lg-4">
                  <!-- Aksi Dokumen -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning border-bottom">
                      <h5 class="card-title mb-0 text-white fw-normal">
                        <i class="fas fa-file-download me-2"></i>
                        Aksi Dokumen
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-3">
                        <!-- Download Foto Identitas -->
                        <?php if (!empty($permohonan['upload_foto_identitas'])): ?>
                          <a href="index.php?controller=permohonanadmin&action=downloadFile&file=<?php echo basename($permohonan['upload_foto_identitas']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                             class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-camera me-2"></i>Download Foto Identitas
                          </a>
                        <?php else: ?>
                          <button class="btn btn-outline-secondary btn-lg" disabled>
                            <i class="fas fa-camera me-2"></i>Foto Identitas Tidak Ada
                          </button>
                        <?php endif; ?>

                        <!-- Preview Surat Bukti Permohonan -->
                        <a href="index.php?controller=permohonanadmin&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                           class="btn btn-success btn-lg" target="_blank">
                          <i class="fas fa-file-pdf me-2"></i>Surat Bukti Permohonan
                        </a>

                        <!-- Preview Bukti Selesai -->
                        <a href="index.php?controller=permohonanadmin&action=generateBuktiSelesaiPDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                           class="btn btn-success btn-lg" target="_blank">
                          <i class="fas fa-file-check me-2"></i>Bukti Selesai
                        </a>
                      </div>

                      <div class="alert alert-success mt-3 mb-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <small><strong>Catatan:</strong> Permohonan ini sudah selesai. Dokumen bukti selesai siap untuk dicetak dan diberikan kepada pemohon.</small>
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

                  <!-- File Pendukung Lainnya -->
                  <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-paperclip me-2 text-primary"></i>
                        Dokumen Pendukung
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-2">
                        <?php if (!empty($permohonan['upload_ktp'])): ?>
                          <a href="index.php?controller=permohonanadmin&action=downloadFile&file=<?php echo basename($permohonan['upload_ktp']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-id-card me-2"></i>Download KTP
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_akta'])): ?>
                          <a href="index.php?controller=permohonanadmin&action=downloadFile&file=<?php echo basename($permohonan['upload_akta']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-primary">
                            <i class="fas fa-certificate me-2"></i>Download Akta Lembaga
                          </a>
                        <?php endif; ?>

                        <?php if (!empty($permohonan['upload_data_pedukung'])): ?>
                          <a href="index.php?controller=permohonanadmin&action=downloadFile&file=<?php echo basename($permohonan['upload_data_pedukung']); ?>&id=<?php echo $permohonan['id_permohonan']; ?>"
                            class="btn btn-outline-info">
                            <i class="fas fa-file-upload me-2"></i>Download Data Pendukung
                          </a>
                        <?php endif; ?>

                        <?php if (
                          empty($permohonan['upload_ktp']) && empty($permohonan['upload_akta']) &&
                          empty($permohonan['upload_data_pedukung'])
                        ): ?>
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
        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>

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

    .btn-outline-warning {
      border: 1.5px solid var(--gov-warning);
      color: var(--gov-warning);
      background-color: white;
    }

    .btn-outline-warning:hover {
      background-color: var(--gov-warning);
      color: white;
      transform: translateY(-1px);
    }

    .btn-success {
      background-color: var(--gov-success);
      color: white;
    }

    .btn-success:hover {
      background-color: #059669;
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

    .gap-3 {
      gap: 1rem !important;
    }

    .text-muted {
      color: #94a3b8 !important;
    }

    .shadow-sm {
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .alert {
      border-radius: 8px;
      border: none;
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
