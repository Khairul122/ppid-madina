<?php include('template/header.php'); ?>

<style>
  :root {
    --primary-color: #1e3a8a;
    --secondary-color: #f59e0b;
    --accent-color: #fbbf24;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
  }

  .dashboard-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
    border-radius: 20px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
  }

  .dashboard-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .dashboard-hero h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 10px;
  }

  .dashboard-hero p {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 0;
  }

  .stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.15);
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--card-color);
  }

  .stat-card.card-primary::before { background: var(--primary-color); }
  .stat-card.card-success::before { background: var(--success-color); }
  .stat-card.card-info::before { background: var(--info-color); }
  .stat-card.card-warning::before { background: var(--secondary-color); }

  .stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    margin-bottom: 16px;
  }

  .stat-icon.icon-primary {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
  }
  .stat-icon.icon-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
  }
  .stat-icon.icon-info {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: white;
  }
  .stat-icon.icon-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
  }

  .stat-value {
    font-size: 36px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
  }

  .stat-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .stat-change {
    font-size: 13px;
    margin-top: 12px;
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    background: #f1f5f9;
  }

  .stat-change.positive {
    color: var(--success-color);
    background: #d1fae5;
  }

  .chart-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
    height: 100%;
  }

  .chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .activity-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
    height: 100%;
  }

  .activity-item {
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
    background: #f8fafc;
  }

  .activity-item:hover {
    background: #f1f5f9;
    border-left-color: var(--primary-color);
  }

  .activity-time {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 4px;
  }

  .activity-content {
    font-size: 14px;
    color: #334155;
    font-weight: 500;
    margin-bottom: 4px;
  }

  .activity-meta {
    font-size: 12px;
    color: #64748b;
  }

  .status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-badge.badge-success {
    background: #d1fae5;
    color: #065f46;
  }

  .status-badge.badge-warning {
    background: #fef3c7;
    color: #92400e;
  }

  .status-badge.badge-danger {
    background: #fee2e2;
    color: #991b1b;
  }

  .status-badge.badge-primary {
    background: #dbeafe;
    color: #1e40af;
  }

  .quick-action-btn {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    transition: all 0.2s ease;
  }

  .quick-action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
  }

  .section-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }

  .section-title i {
    margin-right: 12px;
    color: var(--primary-color);
  }

  @media (max-width: 768px) {
    .dashboard-hero {
      padding: 24px;
    }

    .dashboard-hero h2 {
      font-size: 24px;
    }

    .stat-value {
      font-size: 28px;
    }
  }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">

          <!-- Dashboard Hero -->
          <div class="dashboard-hero">
            <div class="row align-items-center">
              <div class="col-lg-8">
                <h2>Selamat Datang, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?> 👋</h2>
                <p>Dashboard PPID Kabupaten Mandailing Natal - Sistem Informasi Publik</p>
              </div>
              <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <button class="quick-action-btn">
                  <i class="mdi mdi-download me-2"></i> Export Laporan
                </button>
              </div>
            </div>
          </div>

          <!-- Statistics Cards -->
          <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-primary">
                <div class="stat-icon icon-primary">
                  <i class="mdi mdi-file-document-multiple"></i>
                </div>
                <div class="stat-value"><?= isset($data['stats']['dokumen']) ? number_format((int)$data['stats']['dokumen']) : '0' ?></div>
                <div class="stat-label">Dokumen Publik</div>
                <div class="stat-change positive">
                  <i class="mdi mdi-arrow-up me-1"></i> 12% Bulan ini
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-success">
                <div class="stat-icon icon-success">
                  <i class="mdi mdi-email-open-multiple"></i>
                </div>
                <div class="stat-value"><?= isset($data['stats']['permohonan']) ? number_format((int)$data['stats']['permohonan']) : '0' ?></div>
                <div class="stat-label">Total Permohonan</div>
                <div class="stat-change positive">
                  <i class="mdi mdi-arrow-up me-1"></i> 8% Bulan ini
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-info">
                <div class="stat-icon icon-info">
                  <i class="mdi mdi-checkbox-marked-circle"></i>
                </div>
                <div class="stat-value"><?= isset($data['stats']['permohonan_selesai']) ? number_format((int)$data['stats']['permohonan_selesai']) : '0' ?></div>
                <div class="stat-label">Permohonan Selesai</div>
                <div class="stat-change positive">
                  <i class="mdi mdi-arrow-up me-1"></i> 15% Bulan ini
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-warning">
                <div class="stat-icon icon-warning">
                  <i class="mdi mdi-account-group"></i>
                </div>
                <div class="stat-value"><?= isset($data['stats']['pengguna_masyarakat']) ? number_format((int)$data['stats']['pengguna_masyarakat']) : '0' ?></div>
                <div class="stat-label">Pengguna Terdaftar</div>
                <div class="stat-change positive">
                  <i class="mdi mdi-arrow-up me-1"></i> 5% Bulan ini
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="row">
            <div class="col-lg-6">
              <div class="chart-card">
                <div class="chart-title">
                  <span><i class="mdi mdi-chart-donut text-primary"></i> Distribusi Dokumen per Kategori</span>
                  <span class="badge bg-light text-dark">
                    <?php
                    $kategori_count = isset($data['kategori_data']) ? count($data['kategori_data']) : 0;
                    echo $kategori_count . ' Kategori';
                    ?>
                  </span>
                </div>
                <div style="height: 320px;">
                  <canvas id="kategoriChart"></canvas>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="chart-card">
                <div class="chart-title">
                  <span><i class="mdi mdi-chart-bar text-success"></i> Status Permohonan</span>
                  <span class="badge bg-light text-dark">
                    <?php
                    $status_count = isset($data['status_data']) ? count($data['status_data']) : 0;
                    echo $status_count . ' Status';
                    ?>
                  </span>
                </div>
                <div style="height: 320px;">
                  <canvas id="statusChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Activity Section -->
          <div class="row">
            <div class="col-lg-6">
              <div class="activity-card">
                <h5 class="section-title">
                  <i class="mdi mdi-clock-outline"></i> Permohonan Terbaru
                </h5>
                <div class="activity-list">
                  <?php if (isset($data['recent_permohonan']) && !empty($data['recent_permohonan'])): ?>
                    <?php foreach ($data['recent_permohonan'] as $permohonan): ?>
                      <div class="activity-item">
                        <div class="activity-time">
                          <i class="mdi mdi-clock-outline me-1"></i>
                          <?= isset($permohonan['created_at']) ? date('d M Y H:i', strtotime($permohonan['created_at'])) : '-' ?>
                        </div>
                        <div class="activity-content">
                          <?= isset($permohonan['judul_dokumen']) ? htmlspecialchars($permohonan['judul_dokumen']) : (isset($permohonan['kandungan_informasi']) ? htmlspecialchars(substr($permohonan['kandungan_informasi'], 0, 50) . '...') : 'Permohonan Informasi') ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                          <div class="activity-meta">
                            <i class="mdi mdi-account me-1"></i>
                            <?= isset($permohonan['username']) ? htmlspecialchars($permohonan['username']) : 'Pengguna' ?>
                          </div>
                          <?php if (isset($permohonan['status'])): ?>
                            <span class="status-badge badge-<?php
                              $status = strtolower($permohonan['status']);
                              if (strpos($status, 'selesai') !== false) echo 'success';
                              elseif (strpos($status, 'diproses') !== false) echo 'warning';
                              elseif (strpos($status, 'ditolak') !== false) echo 'danger';
                              else echo 'primary';
                            ?>"><?= htmlspecialchars($permohonan['status']) ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="mdi mdi-inbox mdi-48px text-muted"></i>
                      <p class="text-muted mt-3">Tidak ada permohonan terbaru</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="activity-card">
                <h5 class="section-title">
                  <i class="mdi mdi-newspaper-variant-outline"></i> Berita Terbaru
                </h5>
                <div class="activity-list">
                  <?php if (isset($data['recent_berita']) && !empty($data['recent_berita'])): ?>
                    <?php foreach ($data['recent_berita'] as $berita): ?>
                      <div class="activity-item">
                        <div class="activity-time">
                          <i class="mdi mdi-calendar me-1"></i>
                          <?= isset($berita['created_at']) ? date('d M Y', strtotime($berita['created_at'])) : '-' ?>
                        </div>
                        <div class="activity-content">
                          <?= isset($berita['judul']) ? htmlspecialchars($berita['judul']) : 'Berita' ?>
                        </div>
                        <div class="activity-meta mt-2">
                          <i class="mdi mdi-tag-outline me-1"></i> Berita Daerah
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="text-center py-5">
                      <i class="mdi mdi-newspaper-variant-outline mdi-48px text-muted"></i>
                      <p class="text-muted mt-3">Tidak ada berita terbaru</p>
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

  <!-- Chart Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof Chart !== 'undefined') {
        // Prepare data from PHP
        <?php
        // Prepare kategori data
        $kategori_labels = [];
        $kategori_values = [];
        if (isset($data['kategori_data']) && is_array($data['kategori_data']) && count($data['kategori_data']) > 0) {
          foreach ($data['kategori_data'] as $kategori) {
            if (isset($kategori['nama_kategori'])) {
              $kategori_labels[] = $kategori['nama_kategori'];
              $kategori_values[] = (int)($kategori['jumlah'] ?? 0);
            }
          }
        }

        // Prepare status data
        $status_labels = [];
        $status_values = [];
        if (isset($data['status_data']) && is_array($data['status_data']) && count($data['status_data']) > 0) {
          foreach ($data['status_data'] as $status) {
            if (isset($status['status'])) {
              $status_labels[] = $status['status'];
              $status_values[] = (int)($status['jumlah'] ?? 0);
            }
          }
        }

        // Use json_encode for safer data transfer
        $kategori_labels_json = json_encode($kategori_labels);
        $kategori_values_json = json_encode($kategori_values);
        $status_labels_json = json_encode($status_labels);
        $status_values_json = json_encode($status_values);
        ?>

        // Data from database
        const kategoriLabels = <?php echo $kategori_labels_json; ?>;
        const kategoriValues = <?php echo $kategori_values_json; ?>;
        const statusLabels = <?php echo $status_labels_json; ?>;
        const statusValues = <?php echo $status_values_json; ?>;

        // Debug logs - Data dari database
        console.log('=== DASHBOARD DATA DEBUG ===');
        console.log('Kategori Labels:', kategoriLabels);
        console.log('Kategori Values:', kategoriValues);
        console.log('Status Labels:', statusLabels);
        console.log('Status Values:', statusValues);
        console.log('Total Kategori:', kategoriLabels.length);
        console.log('Total Status:', statusLabels.length);
        console.log('==========================');

        // Kategori Chart
        const kategoriCtx = document.getElementById('kategoriChart');
        if (kategoriCtx) {
          if (kategoriLabels.length > 0) {
            new Chart(kategoriCtx.getContext('2d'), {
              type: 'doughnut',
              data: {
                labels: kategoriLabels,
                datasets: [{
                  data: kategoriValues,
                  backgroundColor: ['#1e3a8a', '#10b981', '#f59e0b', '#3b82f6', '#ef4444', '#8b5cf6', '#ec4899', '#f97316'],
                  borderWidth: 0,
                  borderRadius: 5,
                  spacing: 5
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
                      font: { size: 12, weight: '500' },
                      usePointStyle: true,
                      pointStyle: 'circle'
                    }
                  },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return context.label + ': ' + context.parsed + ' dokumen';
                      }
                    }
                  }
                }
              }
            });
          } else {
            // Show no data message
            kategoriCtx.parentElement.innerHTML = '<div class="text-center py-5"><i class="mdi mdi-chart-donut mdi-48px text-muted"></i><p class="text-muted mt-3">Tidak ada data kategori</p></div>';
          }
        }

        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
          if (statusLabels.length > 0) {
            new Chart(statusCtx.getContext('2d'), {
              type: 'bar',
              data: {
                labels: statusLabels,
                datasets: [{
                  label: 'Jumlah Permohonan',
                  data: statusValues,
                  backgroundColor: '#1e3a8a',
                  borderRadius: 8,
                  borderWidth: 0
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: {
                      precision: 0,
                      font: { size: 12 },
                      stepSize: 1
                    },
                    grid: {
                      display: true,
                      drawBorder: false,
                      color: '#f1f5f9'
                    }
                  },
                  x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                  }
                },
                plugins: {
                  legend: { display: false },
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return 'Jumlah: ' + context.parsed.y + ' permohonan';
                      }
                    }
                  }
                }
              }
            });
          } else {
            // Show no data message
            statusCtx.parentElement.innerHTML = '<div class="text-center py-5"><i class="mdi mdi-chart-bar mdi-48px text-muted"></i><p class="text-muted mt-3">Tidak ada data permohonan</p></div>';
          }
        }
      } else {
        console.error('Chart.js tidak ditemukan!');
      }
    });
  </script>

  <?php include 'template/script.php'; ?>
</body>
</html>
