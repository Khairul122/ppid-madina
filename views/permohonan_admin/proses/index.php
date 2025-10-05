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
            <div class="col-12">

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Permohonan Diproses</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Diproses</li>
                      </ol>
                    </nav>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="index.php?controller=permohonanadmin&action=index" class="btn btn-outline-secondary btn-sm">
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

              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanadmin">
                    <input type="hidden" name="action" value="diprosesIndex">

                    <div class="col-md-4">
                      <label class="form-label">Pencarian</label>
                      <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nomor, nama, atau judul..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>

                    <div class="col-md-2">
                      <label class="form-label">&nbsp;</label>
                      <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-search me-1"></i>Filter
                      </button>
                    </div>

                    <div class="col-md-2">
                      <label class="form-label">&nbsp;</label>
                      <a href="index.php?controller=permohonanadmin&action=diprosesIndex" class="btn btn-outline-secondary d-block w-100">
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
                    Daftar Permohonan Diproses
                  </h5>
                </div>
                <div class="card-body">
                  <?php if (!empty($permohonan_list)): ?>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead class="table-light">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Permohonan</th>
                            <th scope="col">Pemohon</th>
                            <th scope="col">Judul Dokumen</th>
                            <th scope="col">SKPD Tujuan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = $offset + 1;
                          foreach ($permohonan_list as $permohonan):
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($permohonan['no_permohonan'] ?? ''); ?></span>
                              </td>
                              <td>
                                <div class="d-flex flex-column">
                                  <span class="fw-medium"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username']); ?></span>
                                  <small class="text-muted"><?php echo htmlspecialchars($permohonan['email'] ?? ''); ?></small>
                                </div>
                              </td>
                              <td>
                                <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>">
                                  <?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>
                                </div>
                              </td>
                              <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? '-'); ?></span>
                              </td>
                              <td>
                                <span class="badge bg-warning text-dark">Diproses</span>
                              </td>
                              <td>
                                <small class="text-muted">
                                  <?php echo date('d/m/Y H:i', strtotime($permohonan['created_at'] ?? 'now')); ?>
                                </small>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanadmin&action=diprosesDetail&id=<?php echo $permohonan['id_permohonan']; ?>"
                                     class="btn btn-primary btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                  </a>
                                  <a href="index.php?controller=permohonanadmin&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                                     class="btn btn-success btn-sm" title="Cetak PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                  </a>
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
                              <a class="page-link" href="?controller=permohonanadmin&action=diprosesIndex&page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="fas fa-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                              <a class="page-link" href="?controller=permohonanadmin&action=diprosesIndex&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=permohonanadmin&action=diprosesIndex&page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search ?? ''); ?>">
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
                      <h5 class="text-muted mt-3">Tidak ada data permohonan diproses</h5>
                      <p class="text-muted">Belum ada permohonan dengan status diproses.</p>
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

    .btn-success {
      background-color: var(--gov-success);
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

    .icon-box-warning {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--gov-warning);
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
