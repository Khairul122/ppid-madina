<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Calculate pagination variables
$total_pages = ceil($total_records / $limit);
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
                    <h4 class="page-title mb-1 text-dark">Semua Permohonan</h4>
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb mb-0 fs-6">
                        <li class="breadcrumb-item"><a href="index.php?controller=permohonanadmin&action=index" class="text-decoration-none">Meja Layanan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Semua Permohonan</li>
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

              <!-- Chart Section -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Grafik Status Permohonan (Bar Chart)
                      </h5>
                    </div>
                    <div class="card-body">
                      <canvas id="barChart" height="300"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header bg-light border-bottom">
                      <h5 class="card-title mb-0 text-dark fw-normal">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Grafik Status Permohonan (Pie Chart)
                      </h5>
                    </div>
                    <div class="card-body">
                      <canvas id="pieChart" height="300"></canvas>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Komponen Statistics Table -->
              <div class="card mb-4">
                <div class="card-header bg-light border-bottom">
                  <h5 class="card-title mb-0 text-dark fw-normal">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Rincian Permohonan Berdasarkan Komponen Tujuan
                  </h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="table-light">
                        <tr>
                          <th scope="col" class="text-center">No</th>
                          <th scope="col">Komponen Tujuan</th>
                          <th scope="col" class="text-center">Masuk</th>
                          <th scope="col" class="text-center">Disposisi</th>
                          <th scope="col" class="text-center">Diproses</th>
                          <th scope="col" class="text-center">Selesai</th>
                          <th scope="col" class="text-center">Ditolak</th>
                          <th scope="col" class="text-center">Keberatan</th>
                          <th scope="col" class="text-center">Sengketa</th>
                          <th scope="col" class="text-center">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($komponen_stats)): ?>
                          <?php
                          $no = 1;
                          foreach ($komponen_stats as $stat):
                          ?>
                            <tr>
                              <td class="text-center"><?php echo $no++; ?></td>
                              <td><?php echo htmlspecialchars($stat['komponen_tujuan'] ?? 'Tidak Diketahui'); ?></td>
                              <td class="text-center"><?php echo number_format($stat['masuk'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['disposisi'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['diproses'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['selesai'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['ditolak'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['keberatan'] ?? 0); ?></td>
                              <td class="text-center"><?php echo number_format($stat['sengketa'] ?? 0); ?></td>
                              <td class="text-center"><strong><?php echo number_format($stat['total'] ?? 0); ?></strong></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="10" class="text-center text-muted">Tidak ada data statistik</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-body">
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanadmin">
                    <input type="hidden" name="action" value="semuaIndex">

                    <div class="col-md-3">
                      <label class="form-label">Status</label>
                      <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo ($status_filter === 'pending') ? 'selected' : ''; ?>>Masuk</option>
                        <option value="Disposisi" <?php echo ($status_filter === 'Disposisi') ? 'selected' : ''; ?>>Disposisi</option>
                        <option value="Diproses" <?php echo ($status_filter === 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                        <option value="Selesai" <?php echo ($status_filter === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        <option value="Ditolak" <?php echo ($status_filter === 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        <option value="Keberatan" <?php echo ($status_filter === 'Keberatan') ? 'selected' : ''; ?>>Keberatan</option>
                        <option value="Sengketa" <?php echo ($status_filter === 'Sengketa') ? 'selected' : ''; ?>>Sengketa</option>
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label class="form-label">Komponen Tujuan</label>
                      <select name="komponen" class="form-select">
                        <option value="">Semua Komponen</option>
                        <?php if (!empty($all_komponen)): ?>
                          <?php foreach ($all_komponen as $k): ?>
                            <option value="<?php echo htmlspecialchars($k['komponen_tujuan']); ?>"
                                    <?php echo ($komponen_filter === $k['komponen_tujuan']) ? 'selected' : ''; ?>>
                              <?php echo htmlspecialchars($k['komponen_tujuan']); ?>
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <label class="form-label">Pencarian</label>
                      <input type="text" name="search" class="form-control"
                             placeholder="Cari berdasarkan nomor, judul, nama pemohon..."
                             value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>

                    <div class="col-md-1">
                      <label class="form-label">&nbsp;</label>
                      <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>

                    <div class="col-md-1">
                      <label class="form-label">&nbsp;</label>
                      <a href="index.php?controller=permohonanadmin&action=semuaIndex" class="btn btn-outline-secondary d-block w-100">
                        <i class="fas fa-refresh"></i>
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
                    Semua Data Permohonan
                  </h5>
                </div>
                <div class="card-body">
                  <?php if (!empty($permohonan_list)): ?>
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead class="table-light">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kode</th>
                            <th scope="col">Judul Permohonan</th>
                            <th scope="col">Nama Pemohon</th>
                            <th scope="col">Komponen Tujuan</th>
                            <th scope="col">Status</th>
                            <th scope="col">Sumber Media</th>
                            <th scope="col">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = $offset + 1;
                          foreach ($permohonan_list as $p):
                          ?>
                            <tr>
                              <td><?php echo $no++; ?></td>
                              <td>
                                <small class="text-muted">
                                  <?php echo date('d/m/Y', strtotime($p['created_at'] ?? 'now')); ?>
                                </small>
                              </td>
                              <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($p['no_permohonan'] ?? ''); ?></span>
                              </td>
                              <td>
                                <div class="text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($p['judul_dokumen'] ?? ''); ?>">
                                  <?php echo htmlspecialchars($p['judul_dokumen'] ?? ''); ?>
                                </div>
                              </td>
                              <td>
                                <div class="d-flex flex-column">
                                  <span class="fw-medium"><?php echo htmlspecialchars($p['nama_lengkap'] ?? ''); ?></span>
                                  <small class="text-muted">NIK: <?php echo htmlspecialchars($p['nik'] ?? ''); ?></small>
                                </div>
                              </td>
                              <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($p['komponen_tujuan'] ?? ''); ?></span>
                              </td>
                              <td>
                                <?php
                                $status = strtolower($p['status'] ?? 'pending');
                                $badge_class = 'bg-secondary';
                                $status_text = 'Masuk';

                                switch($status) {
                                  case 'disposisi':
                                    $badge_class = 'bg-info';
                                    $status_text = 'Disposisi';
                                    break;
                                  case 'diproses':
                                    $badge_class = 'bg-warning';
                                    $status_text = 'Diproses';
                                    break;
                                  case 'selesai':
                                    $badge_class = 'bg-success';
                                    $status_text = 'Selesai';
                                    break;
                                  case 'ditolak':
                                    $badge_class = 'bg-danger';
                                    $status_text = 'Ditolak';
                                    break;
                                  case 'keberatan':
                                    $badge_class = 'bg-warning';
                                    $status_text = 'Keberatan';
                                    break;
                                  case 'sengketa':
                                    $badge_class = 'bg-danger';
                                    $status_text = 'Sengketa';
                                    break;
                                }
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                              </td>
                              <td>
                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($p['sumber_media'] ?? '-'); ?></span>
                              </td>
                              <td>
                                <a href="index.php?controller=permohonanadmin&action=view&id=<?php echo $p['id_permohonan']; ?>"
                                   class="btn btn-primary btn-sm" title="Lihat Detail">
                                  <i class="fas fa-eye"></i>
                                </a>
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
                              <a class="page-link" href="?controller=permohonanadmin&action=semuaIndex&page=<?php echo ($page - 1); ?>&status=<?php echo urlencode($status_filter ?? ''); ?>&komponen=<?php echo urlencode($komponen_filter ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <i class="fas fa-chevron-left"></i>
                              </a>
                            </li>
                          <?php endif; ?>

                          <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                              <a class="page-link" href="?controller=permohonanadmin&action=semuaIndex&page=<?php echo $i; ?>&status=<?php echo urlencode($status_filter ?? ''); ?>&komponen=<?php echo urlencode($komponen_filter ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>">
                                <?php echo $i; ?>
                              </a>
                            </li>
                          <?php endfor; ?>

                          <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                              <a class="page-link" href="?controller=permohonanadmin&action=semuaIndex&page=<?php echo ($page + 1); ?>&status=<?php echo urlencode($status_filter ?? ''); ?>&komponen=<?php echo urlencode($komponen_filter ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>">
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
                      <h5 class="text-muted mt-3">Tidak ada data permohonan</h5>
                      <p class="text-muted">Belum ada permohonan yang masuk atau sesuai dengan filter yang dipilih.</p>
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);

    // Prepare data from PHP
    const statusData = <?php echo json_encode($status_stats ?? []); ?>;

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Masuk', 'Disposisi', 'Diproses', 'Selesai', 'Ditolak', 'Keberatan', 'Sengketa'],
        datasets: [{
          label: 'Jumlah Permohonan',
          data: [
            statusData.masuk || 0,
            statusData.disposisi || 0,
            statusData.diproses || 0,
            statusData.selesai || 0,
            statusData.ditolak || 0,
            statusData.keberatan || 0,
            statusData.sengketa || 0
          ],
          backgroundColor: [
            'rgba(100, 116, 139, 0.8)',    // Masuk - gray
            'rgba(59, 130, 246, 0.8)',     // Disposisi - blue
            'rgba(245, 158, 11, 0.8)',     // Diproses - yellow
            'rgba(16, 185, 129, 0.8)',     // Selesai - green
            'rgba(239, 68, 68, 0.8)',      // Ditolak - red
            'rgba(245, 158, 11, 0.8)',     // Keberatan - yellow
            'rgba(239, 68, 68, 0.8)'       // Sengketa - red
          ],
          borderColor: [
            'rgb(100, 116, 139)',
            'rgb(59, 130, 246)',
            'rgb(245, 158, 11)',
            'rgb(16, 185, 129)',
            'rgb(239, 68, 68)',
            'rgb(245, 158, 11)',
            'rgb(239, 68, 68)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Masuk', 'Disposisi', 'Diproses', 'Selesai', 'Ditolak', 'Keberatan', 'Sengketa'],
        datasets: [{
          data: [
            statusData.masuk || 0,
            statusData.disposisi || 0,
            statusData.diproses || 0,
            statusData.selesai || 0,
            statusData.ditolak || 0,
            statusData.keberatan || 0,
            statusData.sengketa || 0
          ],
          backgroundColor: [
            'rgba(100, 116, 139, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)'
          ],
          borderColor: [
            'rgb(100, 116, 139)',
            'rgb(59, 130, 246)',
            'rgb(245, 158, 11)',
            'rgb(16, 185, 129)',
            'rgb(239, 68, 68)',
            'rgb(245, 158, 11)',
            'rgb(239, 68, 68)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 15,
              usePointStyle: true,
              font: {
                size: 12
              }
            }
          }
        }
      }
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

    .btn-outline-secondary {
      border: 1.5px solid var(--gov-secondary);
      color: var(--gov-secondary);
      background-color: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--gov-secondary);
      color: white;
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

    /* Chart containers */
    canvas {
      max-height: 300px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .table-responsive {
        font-size: 0.875rem;
      }

      canvas {
        max-height: 250px;
      }
    }
  </style>

</body>

</html>
