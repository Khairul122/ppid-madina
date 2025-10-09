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
                    <h4 class="page-title mb-1 text-dark">Data Permohonan Informasi</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=user&action=index" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permohonan Masuk</li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>

              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanpetugas">
                    <input type="hidden" name="action" value="permohonanMasuk">

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
                      <a href="index.php?controller=permohonanpetugas&action=index" class="btn btn-outline-secondary d-block w-100">
                        <i class="fas fa-refresh me-1"></i>Reset
                      </a>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Data Table -->
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Tanggal</th>
                          <th>Tanggal Jatuh Tempo</th>
                          <th>Tujuan Permohonan</th>
                          <th>Nama Pemohon</th>
                          <th>Komponen Tujuan</th>
                          <th>Status</th>
                          <th>Sumber Media</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($permohonan_list)): ?>
                          <tr>
                            <td colspan="9" class="text-center">
                              <div class="py-4">
                                <i class="mdi mdi-file-document-outline" style="font-size: 48px; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Tidak ada data permohonan</p>
                              </div>
                            </td>
                          </tr>
                        <?php else: ?>
                          <?php
                          $no = ($page - 1) * $limit + 1;
                          foreach ($permohonan_list as $permohonan):
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <?php 
                                // Format tanggal dari created_at
                                if ($permohonan['created_at']) {
                                  $date = date_create($permohonan['created_at']);
                                  echo $date ? date_format($date, 'd/m/Y') : 'N/A';
                                } else {
                                  echo 'N/A';
                                }
                                ?>
                              </td>
                              <td>
                                <?php 
                                // Hitung tanggal jatuh tempo dari created_at + sisa_jatuh_tempo hari
                                if ($permohonan['created_at'] && isset($permohonan['sisa_jatuh_tempo'])) {
                                    $createdDate = new DateTime($permohonan['created_at']);
                                    $interval = new DateInterval('P'.$permohonan['sisa_jatuh_tempo'].'D');
                                    $dueDate = clone $createdDate;
                                    $dueDate->add($interval);
                                    echo $dueDate->format('d/m/Y');
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                              </td>
                              <td>
                                <?php echo htmlspecialchars($permohonan['tujuan_permohonan'] ?? ''); ?>
                              </td>
                              <td>
                                <?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username'] ?? ''); ?>
                              </td>
                              <td>
                                <span class="badge bg-info text-dark">
                                  <?php echo htmlspecialchars($permohonan['komponen_tujuan'] ?? ''); ?>
                                </span>
                              </td>
                              <td>
                                <span class="badge bg-info">Masuk</span>
                              </td>
                              <td>
                                <?php echo htmlspecialchars($permohonan['sumber_media'] ?? ''); ?>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanpetugas&action=permohonanMasukView&id=<?php echo $permohonan['id_permohonan']; ?>"
                                     class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                  </a>
                                  <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                          onclick="confirmDelete(<?php echo $permohonan['id_permohonan']; ?>)">
                                    <i class="mdi mdi-delete"></i>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                      <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                          <li class="page-item">
                            <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanMasuk&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                              <i class="mdi mdi-chevron-left"></i>
                            </a>
                          </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                          <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanMasuk&page=<?php echo $i; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                              <?php echo $i; ?>
                            </a>
                          </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                          <li class="page-item">
                            <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanMasuk&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search ?? ''); ?>">
                              <i class="mdi mdi-chevron-right"></i>
                            </a>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </nav>
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
      if (confirm('Apakah Anda yakin ingin menghapus permohonan ini? Data yang terkait akan ikut terhapus.')) {
        window.location.href = 'index.php?controller=permohonanpetugas&action=deletePermohonanMasuk&id=' + id;
      }
    }

    // Auto hide alerts after 5 seconds
    $(document).ready(function() {
      setTimeout(function() {
        $('.alert').fadeOut('slow');
      }, 5000);
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

    .stats-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border: 1px solid var(--gov-border);
      background: white;
    }

    .stats-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .stats-icon {
      font-size: 2rem;
      opacity: 0.8;
    }

    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--gov-dark);
      line-height: 1;
    }

    .stats-label {
      color: var(--gov-secondary);
      font-size: 0.875rem;
      font-weight: 500;
      margin-top: 0.25rem;
    }

    .card {
      border: 1px solid var(--gov-border);
      border-radius: 8px;
      background: white;
    }

    .card-header {
      background-color: #fafafa;
      border-bottom: 1px solid var(--gov-border);
      padding: 1rem 1.5rem;
    }

    .card-body {
      padding: 1.5rem;
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

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .btn-outline-secondary {
      border: 1px solid var(--gov-secondary);
      color: var(--gov-secondary);
      background-color: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--gov-secondary);
      color: white;
    }

    .btn-info {
      background-color: #0ea5e9;
      color: white;
    }

    .btn-info:hover {
      background-color: #0284c7;
    }

    .btn-warning {
      background-color: var(--gov-warning);
      color: white;
    }

    .btn-warning:hover {
      background-color: #d97706;
    }

    .btn-danger {
      background-color: var(--gov-danger);
      color: white;
    }

    .btn-danger:hover {
      background-color: #dc2626;
    }

    .form-control,
    .form-select {
      border: 1px solid var(--gov-border);
      border-radius: 6px;
      padding: 0.5rem 0.75rem;
      background-color: white;
      color: var(--gov-dark);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--gov-primary);
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
      background-color: white;
    }

    .table {
      background: white;
      border-radius: 8px;
      overflow: hidden;
    }

    .table th {
      background-color: #f8fafc;
      border-bottom: 2px solid var(--gov-border);
      font-weight: 600;
      color: var(--gov-dark);
      font-size: 0.875rem;
      padding: 1rem 0.75rem;
    }

    .table td {
      padding: 1rem 0.75rem;
      vertical-align: middle;
      border-bottom: 1px solid #f1f5f9;
    }

    .table-striped > tbody > tr:nth-of-type(odd) > td {
      background-color: #fafafa;
    }

    .badge {
      font-size: 0.75rem;
      padding: 0.5rem 0.75rem;
      font-weight: 500;
      border-radius: 6px;
    }

    .bg-info {
      background-color: #0ea5e9 !important;
    }

    .bg-success {
      background-color: var(--gov-success) !important;
    }

    .bg-warning {
      background-color: var(--gov-warning) !important;
      color: white !important;
    }

    .bg-danger {
      background-color: var(--gov-danger) !important;
    }

    .text-dark {
      color: var(--gov-dark) !important;
    }

    .text-success {
      color: var(--gov-success) !important;
    }

    .text-warning {
      color: var(--gov-warning) !important;
    }

    .text-danger {
      color: var(--gov-danger) !important;
    }

    .text-secondary {
      color: var(--gov-secondary) !important;
    }

    .pagination .page-link {
      color: var(--gov-secondary);
      border: 1px solid var(--gov-border);
      background: white;
    }

    .pagination .page-link:hover {
      background-color: var(--gov-light);
      border-color: var(--gov-primary);
      color: var(--gov-primary);
    }

    .pagination .page-item.active .page-link {
      background-color: var(--gov-primary);
      border-color: var(--gov-primary);
      color: white;
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

    /* Responsive Design */
    @media (max-width: 768px) {
      .container-fluid {
        padding: 1rem;
      }

      .page-header {
        padding: 1rem;
      }

      .card-body {
        padding: 1rem;
      }

      .table-responsive {
        border-radius: 8px;
      }

      .stats-number {
        font-size: 1.5rem;
      }

      .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
      }

      .btn-group .btn {
        width: 100%;
        margin: 0;
      }
    }

    @media (max-width: 576px) {
      .page-title {
        font-size: 1.5rem;
      }

      .breadcrumb {
        font-size: 0.875rem;
      }

      .stats-card .card-body {
        padding: 1rem;
      }

      .stats-icon {
        font-size: 1.5rem;
      }

      .stats-number {
        font-size: 1.25rem;
      }

      .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
      }

      .btn {
        width: 100%;
      }
    }

    /* Loading states and interactions */
    .btn:active {
      transform: translateY(0);
    }

    .table-responsive {
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
  </style>

</body>
</html>