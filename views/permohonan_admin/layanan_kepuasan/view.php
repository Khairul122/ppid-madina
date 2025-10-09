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

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Detail Layanan Kepuasan</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=layananKepuasanIndex" class="text-decoration-none">Layanan Kepuasan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="index.php?controller=permohonanadmin&action=layananKepuasanIndex" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                  </div>
                </div>
              </div>

              <div class="row g-4">
                <!-- Left Column - Layanan Kepuasan Info -->
                <div class="col-lg-7">
                  <!-- Data Responden -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Data Responden
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Nama Lengkap</label>
                            <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['nama'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Umur</label>
                            <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['umur'] ?? ''); ?> tahun</div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Provinsi</label>
                            <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['provinsi'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="info-item">
                            <label class="info-label">Kota/Kabupaten</label>
                            <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['kota'] ?? ''); ?></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

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
                              <span class="badge bg-primary fs-6 px-3 py-2"><?php echo htmlspecialchars($layanan_kepuasan['no_permohonan'] ?? ''); ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Judul Dokumen</label>
                            <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['judul_dokumen'] ?? ''); ?></div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Tanggal Permohonan</label>
                            <div class="info-value">
                              <?php echo date('d F Y, H:i', strtotime($layanan_kepuasan['tanggal_permohonan'] ?? 'now')); ?> WIB
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Penilaian -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-star me-2 text-warning"></i>
                        Penilaian Layanan
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="row g-3">
                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Pengalaman Permohonan Informasi</label>
                            <div class="info-value">
                              <div class="bg-light p-3 rounded">
                                <?php echo nl2br(htmlspecialchars($layanan_kepuasan['permohonan_informasi'] ?? '')); ?>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Rating Pelayanan</label>
                            <div class="info-value">
                              <?php
                              $rating = $layanan_kepuasan['rating'] ?? 0;
                              $ratingClass = 'bg-secondary';
                              $ratingText = 'Tidak ada rating';

                              if ($rating >= 4) {
                                $ratingClass = 'bg-success';
                                $ratingText = 'Puas';
                              } elseif ($rating == 3) {
                                $ratingClass = 'bg-warning';
                                $ratingText = 'Cukup';
                              } elseif ($rating > 0) {
                                $ratingClass = 'bg-danger';
                                $ratingText = 'Tidak Puas';
                              }
                              ?>
                              <div class="d-flex align-items-center gap-3">
                                <span class="badge <?php echo $ratingClass; ?> fs-5 px-4 py-3">
                                  <?php for ($i = 0; $i < $rating; $i++): ?>
                                    <i class="fas fa-star"></i>
                                  <?php endfor; ?>
                                  <?php for ($i = $rating; $i < 5; $i++): ?>
                                    <i class="far fa-star"></i>
                                  <?php endfor; ?>
                                </span>
                                <div>
                                  <div class="fw-bold"><?php echo $rating; ?> / 5</div>
                                  <small class="text-muted"><?php echo $ratingText; ?></small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="info-item">
                            <label class="info-label">Tanggal Penilaian</label>
                            <div class="info-value">
                              <?php echo date('d F Y, H:i', strtotime($layanan_kepuasan['created_at'] ?? 'now')); ?> WIB
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Right Column - Info Pemohon & Actions -->
                <div class="col-lg-5">
                  <!-- Data Pemohon -->
                  <?php if (!empty($layanan_kepuasan['pemohon_nama'])): ?>
                    <div class="card shadow-sm border-0 mb-4">
                      <div class="card-header bg-light border-bottom">
                        <h5 class="card-title mb-0 text-dark fw-normal">
                          <i class="fas fa-user-circle me-2 text-primary"></i>
                          Data Pemohon
                        </h5>
                      </div>
                      <div class="card-body p-4">
                        <div class="row g-3">
                          <div class="col-12">
                            <div class="info-item">
                              <label class="info-label">Nama Pemohon</label>
                              <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['pemohon_nama'] ?? ''); ?></div>
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="info-item">
                              <label class="info-label">Email</label>
                              <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['pemohon_email'] ?? ''); ?></div>
                            </div>
                          </div>

                          <div class="col-12">
                            <div class="info-item">
                              <label class="info-label">No. Kontak</label>
                              <div class="info-value"><?php echo htmlspecialchars($layanan_kepuasan['pemohon_kontak'] ?? ''); ?></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Rating Statistics Card -->
                  <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Statistik Rating
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="text-center mb-3">
                        <div class="display-3 fw-bold text-primary mb-2">
                          <?php echo number_format($rating, 1); ?>
                        </div>
                        <div class="text-warning fs-4 mb-2">
                          <?php for ($i = 0; $i < floor($rating); $i++): ?>
                            <i class="fas fa-star"></i>
                          <?php endfor; ?>
                          <?php if ($rating - floor($rating) >= 0.5): ?>
                            <i class="fas fa-star-half-alt"></i>
                          <?php endif; ?>
                          <?php for ($i = ceil($rating); $i < 5; $i++): ?>
                            <i class="far fa-star"></i>
                          <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0">dari 5 bintang</p>
                      </div>

                      <div class="progress-group mt-4">
                        <?php
                        $ratingPercentage = ($rating / 5) * 100;
                        $progressClass = 'bg-success';
                        if ($rating < 3) $progressClass = 'bg-danger';
                        elseif ($rating < 4) $progressClass = 'bg-warning';
                        ?>
                        <div class="d-flex justify-content-between mb-2">
                          <span class="text-muted">Kepuasan</span>
                          <span class="fw-bold"><?php echo number_format($ratingPercentage, 0); ?>%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                          <div class="progress-bar <?php echo $progressClass; ?>" role="progressbar"
                               style="width: <?php echo $ratingPercentage; ?>%"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-cogs me-2 text-primary"></i>
                        Aksi
                      </h5>
                    </div>
                    <div class="card-body p-4">
                      <div class="d-grid gap-2">
                        <a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $layanan_kepuasan['id_permohonan']; ?>"
                           class="btn btn-info">
                          <i class="fas fa-file-alt me-2"></i>Lihat Detail Permohonan
                        </a>

                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $layanan_kepuasan['id_layanan_kepuasan']; ?>)">
                          <i class="fas fa-trash me-2"></i>Hapus Penilaian
                        </button>
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
    // Confirm delete
    function confirmDelete(id) {
      if (confirm('Apakah Anda yakin ingin menghapus penilaian layanan kepuasan ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        window.location.href = 'index.php?controller=permohonanadmin&action=deleteLayananKepuasan&id=' + id;
      }
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
      border: none;
    }

    .btn-info {
      background-color: #0ea5e9;
      color: white;
    }

    .btn-info:hover {
      background-color: #0284c7;
      transform: translateY(-1px);
    }

    .btn-danger {
      background-color: var(--gov-danger);
      color: white;
    }

    .btn-danger:hover {
      background-color: #dc2626;
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

    .progress {
      border-radius: 8px;
      background-color: #e2e8f0;
    }

    .progress-bar {
      border-radius: 8px;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .col-lg-7,
      .col-lg-5 {
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
