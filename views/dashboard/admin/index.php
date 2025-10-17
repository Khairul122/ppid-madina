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
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Dashboard</a>
                    </li>
                  </ul>
                  <div>
                    <div class="btn-wrapper">
                      <a href="#" class="btn btn-outline-secondary">Laporan Mingguan</a>
                    </div>
                  </div>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-lg-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                          <div class="card-body">
                            <div class="d-sm-flex justify-content-between align-items-start">
                              <div>
                                <h4 class="card-title card-title-dash">Panel Administrasi</h4>
                                <h5 class="card-subtitle card-subtitle-dash">Selamat datang di dashboard PPID Mandailing Natal</h5>
                              </div>
                              <div id="performance-line-legend"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row">
                      <div class="col-lg-3 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-lg-0">
                                  <div>
                                    <h3 class="text-primary mb-0 fw-bold"><?= isset($data['stats']['dokumen']) ? number_format((int)$data['stats']['dokumen']) : '0' ?></h3>
                                    <h6 class="text-muted mb-0">Dokumen Publik</h6>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4 d-flex justify-content-end">
                                <div class="icon-rounded-primary icon-rounded-md">
                                  <i class="mdi mdi-file-document text-primary"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-3 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-lg-0">
                                  <div>
                                    <h3 class="text-success mb-0 fw-bold"><?= isset($data['stats']['permohonan']) ? number_format((int)$data['stats']['permohonan']) : '0' ?></h3>
                                    <h6 class="text-muted mb-0">Permohonan Masuk</h6>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4 d-flex justify-content-end">
                                <div class="icon-rounded-success icon-rounded-md">
                                  <i class="mdi mdi-email text-success"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-3 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-lg-0">
                                  <div>
                                    <h3 class="text-info mb-0 fw-bold"><?= isset($data['stats']['permohonan_selesai']) ? number_format((int)$data['stats']['permohonan_selesai']) : '0' ?></h3>
                                    <h6 class="text-muted mb-0">Permohonan Selesai</h6>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4 d-flex justify-content-end">
                                <div class="icon-rounded-info icon-rounded-md">
                                  <i class="mdi mdi-checkbox-marked-circle text-info"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-3 col-md-6 col-sm-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-lg-8">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-lg-0">
                                  <div>
                                    <h3 class="text-warning mb-0 fw-bold"><?= isset($data['stats']['pengguna_masyarakat']) ? number_format((int)$data['stats']['pengguna_masyarakat']) : '0' ?></h3>
                                    <h6 class="text-muted mb-0">Pengguna</h6>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4 d-flex justify-content-end">
                                <div class="icon-rounded-warning icon-rounded-md">
                                  <i class="mdi mdi-account-group text-warning"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Charts Row -->
                    <div class="row">
                      <div class="col-lg-6 d-flex grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                              <div>
                                <h4 class="card-title card-title-dash">Distribusi Dokumen per Kategori</h4>
                              </div>
                            </div>
                            <div style="height: 300px;">
                              <canvas id="kategoriChart"></canvas>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-6 d-flex grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                              <div>
                                <h4 class="card-title card-title-dash">Status Permohonan</h4>
                              </div>
                            </div>
                            <div style="height: 300px;">
                              <canvas id="statusChart"></canvas>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Recent Activity Row -->
                    <div class="row">
                      <div class="col-lg-6 d-flex grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title card-title-dash">Permohonan Terbaru</h4>
                            <div class="list-wrapper">
                              <ul class="recent-activity-list list-unstyled">
                                <?php if (isset($data['recent_permohonan']) && !empty($data['recent_permohonan'])): ?>
                                  <?php foreach ($data['recent_permohonan'] as $permohonan): ?>
                                    <li class="activity-item">
                                      <div class="activity-info">
                                        <div class="activity-details">
                                          <p class="text-muted d-block mb-1"><?= isset($permohonan['created_at']) ? date('d M Y H:i', strtotime($permohonan['created_at'])) : '-' ?></p>
                                          <p class="mb-1"><?= isset($permohonan['judul_dokumen']) ? htmlspecialchars($permohonan['judul_dokumen']) : (isset($permohonan['kandungan_informasi']) ? htmlspecialchars(substr($permohonan['kandungan_informasi'], 0, 50) . '...') : 'Permohonan Informasi') ?></p>
                                          <p class="text-muted mb-0">oleh <?= isset($permohonan['username']) ? htmlspecialchars($permohonan['username']) : 'Pengguna' ?></p>
                                        </div>
                                        <div>
                                          <?php if (isset($permohonan['status'])): ?>
                                            <span class="badge badge-outline-<?php 
                                              $status = strtolower($permohonan['status']); 
                                              if (strpos($status, 'selesai') !== false) echo 'success';
                                              elseif (strpos($status, 'diproses') !== false) echo 'warning';
                                              elseif (strpos($status, 'ditolak') !== false) echo 'danger';
                                              else echo 'primary';
                                            ?>"><?= htmlspecialchars($permohonan['status']) ?></span>
                                          <?php endif; ?>
                                        </div>
                                      </div>
                                    </li>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <li class="activity-item">
                                    <div class="activity-info">
                                      <div class="activity-details">
                                        <p class="text-muted mb-0">Tidak ada permohonan terbaru</p>
                                      </div>
                                    </div>
                                  </li>
                                <?php endif; ?>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-lg-6 d-flex grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title card-title-dash">Berita Terbaru</h4>
                            <div class="list-wrapper">
                              <ul class="recent-activity-list list-unstyled">
                                <?php if (isset($data['recent_berita']) && !empty($data['recent_berita'])): ?>
                                  <?php foreach ($data['recent_berita'] as $berita): ?>
                                    <li class="activity-item">
                                      <div class="activity-info">
                                        <div class="activity-details">
                                          <p class="text-muted d-block mb-1"><?= isset($berita['created_at']) ? date('d M Y', strtotime($berita['created_at'])) : '-' ?></p>
                                          <p class="mb-1"><?= isset($berita['judul']) ? htmlspecialchars($berita['judul']) : 'Berita' ?></p>
                                        </div>
                                      </div>
                                    </li>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <li class="activity-item">
                                    <div class="activity-info">
                                      <div class="activity-details">
                                        <p class="text-muted mb-0">Tidak ada berita terbaru</p>
                                      </div>
                                    </div>
                                  </li>
                                <?php endif; ?>
                              </ul>
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
        
        <!-- Chart Scripts -->
        <script>
          // Chart for Dokumen by Kategori
          document.addEventListener('DOMContentLoaded', function() {
            // Check if Chart.js is loaded
            if (typeof Chart !== 'undefined') {
              // Prepare data for charts
              <?php 
              // Prepare kategori data
              $kategori_labels = [];
              $kategori_values = [];
              if (isset($data['kategori_data']) && is_array($data['kategori_data'])) {
                foreach ($data['kategori_data'] as $kategori) {
                  $kategori_labels[] = isset($kategori['nama_kategori']) ? addslashes($kategori['nama_kategori']) : 'Kategori';
                  $kategori_values[] = isset($kategori['jumlah']) ? (int)$kategori['jumlah'] : 0;
                }
              }
              
              // Prepare status data
              $status_labels = [];
              $status_values = [];
              if (isset($data['status_data']) && is_array($data['status_data'])) {
                foreach ($data['status_data'] as $status) {
                  $status_labels[] = isset($status['status']) ? addslashes($status['status']) : 'Status';
                  $status_values[] = isset($status['jumlah']) ? (int)$status['jumlah'] : 0;
                }
              }
              ?>
              
              // Kategori Chart
              const kategoriCtx = document.getElementById('kategoriChart');
              if (kategoriCtx) {
                const kategoriChart = new Chart(kategoriCtx.getContext('2d'), {
                  type: 'doughnut',
                  data: {
                    labels: [<?php echo '"' . implode('","', $kategori_labels) . '"'; ?>],
                    datasets: [{
                      data: [<?php echo implode(',', $kategori_values); ?>],
                      backgroundColor: [
                        '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', 
                        '#ef4444', '#8b5cf6', '#ec4899', '#f97316'
                      ],
                      borderWidth: 0
                    }]
                  },
                  options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'bottom',
                      }
                    }
                  }
                });
              }

              // Status Chart
              const statusCtx = document.getElementById('statusChart');
              if (statusCtx) {
                const statusChart = new Chart(statusCtx.getContext('2d'), {
                  type: 'bar',
                  data: {
                    labels: [<?php echo '"' . implode('","', $status_labels) . '"'; ?>],
                    datasets: [{
                      label: 'Jumlah',
                      data: [<?php echo implode(',', $status_values); ?>],
                      backgroundColor: '#3b82f6',
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
                          precision: 0
                        }
                      }
                    }
                  }
                });
              }
            }
          });
        </script>
      </div>
    </div>
  </div>
  <?php include 'template/script.php'; ?>
</body>

</html>