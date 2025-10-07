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
                  <?php echo htmlspecialchars($success_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="mdi mdi-alert-circle me-2"></i>
                  <?php echo htmlspecialchars($error_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Header Section -->
              <div class="page-header mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                  <div class="mb-2 mb-md-0">
                    <h4 class="page-title mb-1 text-dark">Permohonan Selesai - <?php echo htmlspecialchars($petugas_skpd['nama_skpd'] ?? ''); ?></h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=user&action=index" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permohonan Selesai</li>
                      </ol>
                    </nav>
                  </div>
                </div>
              </div>

              <!-- Info Alert -->
              <div class="alert alert-info mb-4" role="alert">
                <div class="d-flex align-items-center">
                  <i class="fas fa-info-circle me-3" style="font-size: 1.5rem;"></i>
                  <div>
                    <strong>Informasi:</strong> Halaman ini menampilkan permohonan yang telah selesai diproses di <strong><?php echo htmlspecialchars($petugas_skpd['nama_skpd'] ?? ''); ?></strong>
                  </div>
                </div>
              </div>

              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanpetugas">
                    <input type="hidden" name="action" value="permohonanSelesai">

                    <div class="col-md-3">
                      <label class="form-label">Status</label>
                      <select name="status" class="form-select">
                        <option value="all" <?php echo (!isset($status) || $status === 'all') ? 'selected' : ''; ?>>Semua Status</option>
                        <option value="Diproses" <?php echo (isset($status) && $status === 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                        <option value="Disposisi" <?php echo (isset($status) && $status === 'Disposisi') ? 'selected' : ''; ?>>Disposisi</option>
                        <option value="Selesai" <?php echo (isset($status) && $status === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        <option value="Ditolak" <?php echo (isset($status) && $status === 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                      </select>
                    </div>

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
                      <a href="index.php?controller=permohonanpetugas&action=permohonanSelesai" class="btn btn-outline-secondary d-block w-100">
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
                    Daftar Permohonan Selesai
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
                            <th scope="col">Tanggal</th>
                            <th scope="col">Jatuh Tempo</th>
                            <th scope="col">Pemohon</th>
                            <th scope="col">Judul Dokumen</th>
                            <th scope="col">Status</th>
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
                                <?php
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
                                if ($permohonan['created_at'] && isset($permohonan['sisa_jatuh_tempo'])) {
                                    $createdDate = new DateTime($permohonan['created_at']);
                                    $interval = new DateInterval('P'.$permohonan['sisa_jatuh_tempo'].'D');
                                    $dueDate = clone $createdDate;
                                    $dueDate->add($interval);

                                    // Calculate days remaining
                                    $today = new DateTime();
                                    $diff = $today->diff($dueDate);
                                    $daysRemaining = $diff->invert ? -$diff->days : $diff->days;

                                    echo $dueDate->format('d/m/Y');

                                    // Show warning if approaching deadline
                                    if ($daysRemaining <= 3 && $daysRemaining > 0 && $permohonan['status'] !== 'Selesai') {
                                        echo ' <span class="badge bg-warning text-dark">H-'.$daysRemaining.'</span>';
                                    } elseif ($daysRemaining <= 0 && $permohonan['status'] !== 'Selesai') {
                                        echo ' <span class="badge bg-danger">Lewat</span>';
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                              </td>
                              <td>
                                <div class="d-flex flex-column">
                                  <span class="fw-medium"><?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username'] ?? ''); ?></span>
                                  <small class="text-muted"><?php echo htmlspecialchars($permohonan['email'] ?? ''); ?></small>
                                </div>
                              </td>
                              <td>
                                <div class="text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>">
                                  <?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? ''); ?>
                                </div>
                              </td>
                              <td>
                                <?php
                                $statusClass = '';
                                switch ($permohonan['status']) {
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
                                <span class="badge <?php echo $statusClass; ?>">
                                  <?php echo htmlspecialchars($permohonan['status'] ?? ''); ?>
                                </span>
                              </td>
                              <td>
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanpetugas&action=permohonanSelesaiView&id=<?php echo $permohonan['id_permohonan']; ?>"
                                     class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="mdi mdi-eye"></i>
                                  </a>
                                  <a href="index.php?controller=permohonanpetugas&action=generatePDF&id=<?php echo $permohonan['id_permohonan']; ?>"
                                     class="btn btn-sm btn-success" title="Download PDF" target="_blank">
                                    <i class="mdi mdi-file-pdf"></i>
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
                              <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanSelesai&page=<?php echo $page - 1; ?>&status=<?php echo urlencode($status ?? 'all'); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="mdi mdi-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                              <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanSelesai&page=<?php echo $i; ?>&status=<?php echo urlencode($status ?? 'all'); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                              <a class="page-link" href="index.php?controller=permohonanpetugas&action=permohonanSelesai&page=<?php echo $page + 1; ?>&status=<?php echo urlencode($status ?? 'all'); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="mdi mdi-chevron-right"></i>
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
                      <h5 class="text-muted mt-3">Tidak ada data permohonan</h5>
                      <p class="text-muted">Belum ada permohonan yang selesai di SKPD Anda.</p>
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
      --gov-info: #06b6d4;
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

    .card-stat {
      border-left: 4px solid var(--gov-primary);
      transition: transform 0.2s;
    }

    .card-stat:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .icon-box {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }

    .icon-box-primary {
      background-color: rgba(37, 99, 235, 0.1);
      color: var(--gov-primary);
    }

    .icon-box-info {
      background-color: rgba(6, 182, 212, 0.1);
      color: var(--gov-info);
    }

    .icon-box-warning {
      background-color: rgba(245, 158, 11, 0.1);
      color: var(--gov-warning);
    }

    .icon-box-success {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--gov-success);
    }

    .icon-box-danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--gov-danger);
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
    }

    .btn-primary {
      background-color: var(--gov-primary);
      border-color: var(--gov-primary);
    }

    .btn-primary:hover {
      background-color: #1d4ed8;
      border-color: #1d4ed8;
      transform: translateY(-1px);
    }

    .form-control,
    .form-select {
      border: 1.5px solid var(--gov-border);
      border-radius: 6px;
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