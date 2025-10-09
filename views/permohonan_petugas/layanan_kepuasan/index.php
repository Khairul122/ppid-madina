<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'petugas') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Get SKPD name from petugas
$user_id = $_SESSION['user_id'];
global $database;
$db = $database->getConnection();
$stmt = $db->prepare("SELECT s.nama_skpd
                      FROM petugas p
                      JOIN skpd s ON p.id_skpd = s.id_skpd
                      WHERE p.id_users = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$petugas_data = $stmt->fetch(PDO::FETCH_ASSOC);
$skpd_name = $petugas_data['nama_skpd'] ?? 'SKPD Tidak Ditemukan';
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
            <div class="col-12">

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Layanan Kepuasan</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanpetugas&action=mejaLayanan" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layanan Kepuasan</li>
                      </ol>
                    </nav>
                    <div class="mt-2">
                      <span class="badge bg-info fs-6 px-3 py-2">
                        <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($skpd_name); ?>
                      </span>
                    </div>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="index.php?controller=permohonanpetugas&action=mejaLayanan" class="btn btn-outline-secondary btn-sm">
                      <i class="fas fa-arrow-left me-1"></i>Kembali ke Meja Layanan
                    </a>
                  </div>
                </div>
              </div>

              <!-- Alert Messages -->
              <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <!-- Stats Cards -->
              <div class="row mb-4">
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-muted mb-1">Total Penilaian</h6>
                          <h3 class="mb-0"><?php echo number_format($stats['total'] ?? 0); ?></h3>
                        </div>
                        <div class="icon icon-box-primary">
                          <i class="fas fa-star fa-2x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-muted mb-1">Rata-rata Rating</h6>
                          <h3 class="mb-0"><?php echo number_format($stats['avg_rating'] ?? 0, 1); ?> <small class="text-muted">/5</small></h3>
                        </div>
                        <div class="icon icon-box-warning">
                          <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-muted mb-1">Puas (≥4)</h6>
                          <h3 class="mb-0"><?php echo number_format($stats['satisfied'] ?? 0); ?></h3>
                        </div>
                        <div class="icon icon-box-success">
                          <i class="fas fa-smile fa-2x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="text-muted mb-1">Tidak Puas (≤2)</h6>
                          <h3 class="mb-0"><?php echo number_format($stats['unsatisfied'] ?? 0); ?></h3>
                        </div>
                        <div class="icon icon-box-danger">
                          <i class="fas fa-frown fa-2x"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanpetugas">
                    <input type="hidden" name="action" value="layananKepuasanIndex">

                    <div class="col-md-6">
                      <label class="form-label">Pencarian</label>
                      <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama, nomor permohonan, atau judul..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>

                    <div class="col-md-3">
                      <label class="form-label">&nbsp;</label>
                      <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-search me-1"></i>Filter
                      </button>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label">&nbsp;</label>
                      <a href="index.php?controller=permohonanpetugas&action=layananKepuasanIndex" class="btn btn-outline-secondary d-block w-100">
                        <i class="fas fa-refresh me-1"></i>Reset
                      </a>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Data Table -->
              <div class="card">
                <div class="card-header bg-light border-bottom">
                  <h5 class="card-title mb-0 text-dark fw-normal">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Daftar Layanan Kepuasan - <?php echo htmlspecialchars($skpd_name); ?>
                  </h5>
                </div>
                <div class="card-body">
                  <?php if (!empty($layanan_kepuasan_list)): ?>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead class="table-light">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Permohonan</th>
                            <th scope="col">Nama Responden</th>
                            <th scope="col">Judul Dokumen</th>
                            <th scope="col">Provinsi/Kota</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = $offset + 1;
                          foreach ($layanan_kepuasan_list as $lk):
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($lk['no_permohonan'] ?? ''); ?></span>
                              </td>
                              <td>
                                <div class="d-flex flex-column">
                                  <span class="fw-medium"><?php echo htmlspecialchars($lk['nama'] ?? ''); ?></span>
                                  <small class="text-muted">Umur: <?php echo htmlspecialchars($lk['umur'] ?? ''); ?> tahun</small>
                                </div>
                              </td>
                              <td>
                                <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($lk['judul_dokumen'] ?? ''); ?>">
                                  <?php echo htmlspecialchars($lk['judul_dokumen'] ?? ''); ?>
                                </div>
                              </td>
                              <td>
                                <small class="text-muted">
                                  <?php echo htmlspecialchars($lk['provinsi'] ?? ''); ?> / <?php echo htmlspecialchars($lk['kota'] ?? ''); ?>
                                </small>
                              </td>
                              <td>
                                <?php
                                $rating = $lk['rating'] ?? 0;
                                $ratingClass = 'bg-secondary';
                                if ($rating >= 4) {
                                  $ratingClass = 'bg-success';
                                } elseif ($rating == 3) {
                                  $ratingClass = 'bg-warning';
                                } elseif ($rating <= 2) {
                                  $ratingClass = 'bg-danger';
                                }
                                ?>
                                <span class="badge <?php echo $ratingClass; ?>">
                                  <?php for ($i = 0; $i < $rating; $i++): ?>
                                    <i class="fas fa-star"></i>
                                  <?php endfor; ?>
                                  (<?php echo $rating; ?>)
                                </span>
                              </td>
                              <td>
                                <small class="text-muted">
                                  <?php echo date('d/m/Y H:i', strtotime($lk['created_at'] ?? 'now')); ?>
                                </small>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanpetugas&action=layananKepuasanView&id=<?php echo $lk['id_layanan_kepuasan']; ?>"
                                     class="btn btn-primary btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                          onclick="confirmDelete(<?php echo $lk['id_layanan_kepuasan']; ?>)">
                                    <i class="fas fa-trash"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                      <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                          <?php if ($page > 1): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=permohonanpetugas&action=layananKepuasanIndex&page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="fas fa-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                              <a class="page-link" href="?controller=permohonanpetugas&action=layananKepuasanIndex&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=permohonanpetugas&action=layananKepuasanIndex&page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="fas fa-chevron-right"></i>
                              </a>
                            </li>
                          <?php endif; ?>
                        </ul>
                      </nav>

                      <div class="text-center mt-3">
                        <small class="text-muted">
                          Menampilkan <?php echo min($offset + 1, $total_records); ?> - <?php echo min($offset + $limit, $total_records); ?>
                          dari <?php echo $total_records; ?> data
                        </small>
                      </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="fas fa-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                      <h5 class="text-muted mt-3">Tidak ada data layanan kepuasan</h5>
                      <p class="text-muted">Belum ada penilaian layanan kepuasan untuk SKPD Anda.</p>
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

  <?php include 'template/script.php'; ?>

  <script>
    function confirmDelete(id) {
      if (confirm('Apakah Anda yakin ingin menghapus layanan kepuasan ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
        window.location.href = 'index.php?controller=permohonanpetugas&action=deleteLayananKepuasan&id=' + id;
      }
    }

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
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

    .table-responsive {
      border-radius: 8px;
    }

    .table th {
      background-color: var(--gov-light);
      border-color: var(--gov-border);
      color: var(--gov-dark);
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.875rem;
      letter-spacing: 0.5px;
    }

    .table td {
      border-color: var(--gov-border);
      vertical-align: middle;
    }

    .badge {
      font-size: 0.75rem;
      padding: 0.375rem 0.75rem;
      font-weight: 500;
      border-radius: 6px;
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
    }

    .btn-primary:hover {
      background-color: #1d4ed8;
      transform: translateY(-1px);
    }

    .btn-danger {
      background-color: var(--gov-danger);
      color: white;
    }

    .btn-outline-secondary {
      border: 1.5px solid var(--gov-secondary);
      color: var(--gov-secondary);
      background-color: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--gov-secondary);
      color: white;
    }

    .icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .icon-box-primary {
      background-color: rgba(37, 99, 235, 0.1);
      color: var(--gov-primary);
    }

    .icon-box-success {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--gov-success);
    }

    .icon-box-warning {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--gov-warning);
    }

    .icon-box-danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--gov-danger);
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--gov-border);
      border-radius: 6px;
      background-color: white;
      color: var(--gov-dark);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--gov-primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .pagination .page-link {
      border-color: var(--gov-border);
      color: var(--gov-secondary);
    }

    .pagination .page-item.active .page-link {
      background-color: var(--gov-primary);
      border-color: var(--gov-primary);
    }

    .alert {
      border-radius: 8px;
      border: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .btn-group {
        flex-direction: column;
      }

      .btn-group .btn {
        margin-bottom: 0.25rem;
      }

      .table-responsive {
        font-size: 0.875rem;
      }
    }
  </style>

</body>

</html>
