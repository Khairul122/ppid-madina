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
                      <i class="fas fa-times-circle me-2 text-danger"></i>Permohonan Ditolak
                    </h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb breadcrumb-custom mb-0">
                        <li class="breadcrumb-item">
                          <a href="index.php?controller=permohonanpetugas&action=index">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                          <span>Permohonan Ditolak</span>
                        </li>
                      </ol>
                    </nav>
                  </div>
                  <div class="mt-2 mt-md-0">
                    <span class="text-muted small">
                      <i class="fas fa-database me-1"></i>
                      Total: <?php echo number_format($total_records); ?> permohonan
                    </span>
                  </div>
                </div>
              </div>

              <!-- Stats Cards -->
              <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                  <div class="card card-stat border-start border-danger border-4 h-100">
                    <div class="card-body d-flex align-items-center">
                      <div class="icon-box icon-box-danger me-3">
                        <i class="fas fa-times-circle"></i>
                      </div>
                      <div>
                        <h5 class="card-title mb-1 text-muted">Ditolak</h5>
                        <p class="card-text mb-0">
                          <span class="h4 text-danger"><?php echo number_format($stats['ditolak'] ?? 0); ?></span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                  <div class="card card-stat border-start border-primary border-4 h-100">
                    <div class="card-body d-flex align-items-center">
                      <div class="icon-box icon-box-primary me-3">
                        <i class="fas fa-clock"></i>
                      </div>
                      <div>
                        <h5 class="card-title mb-1 text-muted">Diproses</h5>
                        <p class="card-text mb-0">
                          <span class="h4 text-primary"><?php echo number_format($stats['diproses'] ?? 0); ?></span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                  <div class="card card-stat border-start border-warning border-4 h-100">
                    <div class="card-body d-flex align-items-center">
                      <div class="icon-box icon-box-warning me-3">
                        <i class="fas fa-share-alt"></i>
                      </div>
                      <div>
                        <h5 class="card-title mb-1 text-muted">Disposisi</h5>
                        <p class="card-text mb-0">
                          <span class="h4 text-warning"><?php echo number_format($stats['disposisi'] ?? 0); ?></span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                  <div class="card card-stat border-start border-success border-4 h-100">
                    <div class="card-body d-flex align-items-center">
                      <div class="icon-box icon-box-success me-3">
                        <i class="fas fa-check-circle"></i>
                      </div>
                      <div>
                        <h5 class="card-title mb-1 text-muted">Selesai</h5>
                        <p class="card-text mb-0">
                          <span class="h4 text-success"><?php echo number_format($stats['selesai'] ?? 0); ?></span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Filters and Search -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanpetugas">
                    <input type="hidden" name="action" value="permohonanDitolak">

                    <div class="col-md-3">
                      <label for="status-filter" class="form-label">Status</label>
                      <select name="status" id="status-filter" class="form-select">
                        <option value="all" <?php echo ($status === 'all') ? 'selected' : ''; ?>>Semua Status</option>
                        <option value="Diproses" <?php echo ($status === 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                        <option value="Disposisi" <?php echo ($status === 'Disposisi') ? 'selected' : ''; ?>>Disposisi</option>
                        <option value="Selesai" <?php echo ($status === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        <option value="Ditolak" <?php echo ($status === 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <label for="search-input" class="form-label">Pencarian</label>
                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" id="search-input" class="form-control" 
                               placeholder="Cari berdasarkan nomor, judul, atau nama..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                      </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                      <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                          <i class="fas fa-filter me-1"></i>Terapkan
                        </button>
                        <a href="index.php?controller=permohonanpetugas&action=permohonanDitolak" class="btn btn-outline-secondary">
                          <i class="fas fa-refresh me-1"></i>Reset
                        </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Success/Error Messages -->
              <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fas fa-check-circle me-2"></i>
                  <?php echo htmlspecialchars($success_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo htmlspecialchars($error_message); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <!-- Permohonan Table -->
              <div class="card">
                <div class="card-header bg-light border-bottom">
                  <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Daftar Permohonan Ditolak
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
                            <th scope="col">Pemohon</th>
                            <th scope="col">Judul Dokumen</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = $offset + 1;
                          foreach ($permohonan_list as $permohonan):
                            ?>
                            <tr>
                              <th scope="row"><?php echo $no++; ?></th>
                              <td>
                                <span class="badge bg-primary">
                                  <?php echo htmlspecialchars($permohonan['no_permohonan'] ?? '-'); ?>
                                </span>
                              </td>
                              <td>
                                <?php 
                                if ($permohonan['created_at']) {
                                  $date = date_create($permohonan['created_at']);
                                  echo $date ? date_format($date, 'd/m/Y H:i') : 'N/A';
                                } else {
                                  echo 'N/A';
                                }
                                ?>
                              </td>
                              <td>
                                <div class="d-flex flex-column">
                                  <span class="fw-medium">
                                    <?php echo htmlspecialchars($permohonan['nama_lengkap'] ?? $permohonan['username'] ?? '-'); ?>
                                  </span>
                                  <small class="text-muted">
                                    <?php echo htmlspecialchars($permohonan['email'] ?? '-'); ?>
                                  </small>
                                </div>
                              </td>
                              <td>
                                <div class="text-truncate" style="max-width: 250px;" 
                                     title="<?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? '-'); ?>">
                                  <?php echo htmlspecialchars($permohonan['judul_dokumen'] ?? '-'); ?>
                                </div>
                              </td>
                              <td>
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
                                <span class="badge <?php echo $status_class; ?>">
                                  <?php echo htmlspecialchars($permohonan['status'] ?? '-'); ?>
                                </span>
                              </td>
                              <td class="text-center">
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanpetugas&action=permohonanDitolakView&id=<?php echo $permohonan['id_permohonan']; ?>" 
                                     class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
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
                              <a class="page-link" 
                                 href="index.php?controller=permohonanpetugas&action=permohonanDitolak&page=<?php echo $page - 1; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                              <a class="page-link" 
                                 href="index.php?controller=permohonanpetugas&action=permohonanDitolak&page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                              <a class="page-link" 
                                 href="index.php?controller=permohonanpetugas&action=permohonanDitolak&page=<?php echo $page + 1; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-right"></i>
                              </a>
                            </li>
                          <?php endif; ?>
                        </ul>
                      </nav>

                      <div class="text-center mt-3">
                        <small class="text-muted">
                          Menampilkan <?php echo min($offset + 1, $total_records); ?> - <?php echo min($offset + $limit, $total_records); ?>
                          dari <?php echo number_format($total_records); ?> data
                        </small>
                      </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="fas fa-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                      <h5 class="text-muted mt-3">Tidak ada data permohonan</h5>
                      <p class="text-muted">Belum ada permohonan yang ditolak untuk SKPD Anda.</p>
                    </div>
                  <?php endif; ?>
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

  <script>
    // Auto-hide alerts after 5 seconds
    $(document).ready(function() {
      setTimeout(function() {
        $('.alert').fadeOut('slow');
      }, 5000);
    });
  </script>
</body>

</html>