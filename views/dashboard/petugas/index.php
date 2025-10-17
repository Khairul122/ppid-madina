<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=auth&action=login');
    exit();
}
?>

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
  }

  .stat-card.card-danger::before { background: var(--danger-color); }
  .stat-card.card-info::before { background: var(--info-color); }
  .stat-card.card-success::before { background: var(--success-color); }
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

  .stat-icon.icon-danger {
    background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    color: white;
  }
  .stat-icon.icon-info {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: white;
  }
  .stat-icon.icon-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
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

  .stat-sublabel {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 8px;
  }

  .quick-action-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
  }

  .quick-action-btn {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    text-decoration: none;
    color: #334155;
  }

  .quick-action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: translateX(4px);
  }

  .quick-action-btn i {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 10px;
    margin-right: 16px;
    font-size: 20px;
  }

  .quick-action-btn:hover i {
    background: rgba(255, 255, 255, 0.2);
  }

  .permohonan-table {
    background: white;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
  }

  .table-header {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }

  .table-header i {
    margin-right: 12px;
    color: var(--primary-color);
  }

  .modern-table {
    width: 100%;
  }

  .modern-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px;
    border: none;
  }

  .modern-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #334155;
  }

  .modern-table tbody tr:hover {
    background: #f8fafc;
  }

  .status-badge {
    padding: 6px 14px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
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

  .status-badge.badge-info {
    background: #dbeafe;
    color: #1e40af;
  }

  .action-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
  }

  .action-btn i {
    margin-right: 6px;
  }

  .action-btn.btn-primary {
    background: var(--primary-color);
    color: white;
  }

  .action-btn.btn-primary:hover {
    background: #1e40af;
    transform: translateY(-2px);
  }

  .pemohon-info {
    display: flex;
    align-items: center;
  }

  .pemohon-avatar {
    width: 36px;
    height: 36px;
    background: var(--primary-color);
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-weight: 600;
    font-size: 14px;
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

    .modern-table {
      font-size: 12px;
    }

    .modern-table thead th,
    .modern-table tbody td {
      padding: 12px 8px;
    }
  }
</style>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar_petugas.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">

          <!-- Dashboard Hero -->
          <div class="dashboard-hero">
            <div class="row align-items-center">
              <div class="col-lg-8">
                <h2>Selamat Datang, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Petugas' ?> 👋</h2>
                <p>Dashboard Petugas - Kelola Permohonan Informasi Publik dengan Efisien</p>
              </div>
              <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="index.php?controller=permohonan_petugas&action=index" class="btn btn-light">
                  <i class="mdi mdi-file-document me-2"></i> Lihat Semua Permohonan
                </a>
              </div>
            </div>
          </div>

          <!-- Statistics Cards -->
          <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-danger">
                <div class="stat-icon icon-danger">
                  <i class="mdi mdi-file-document-alert"></i>
                </div>
                <div class="stat-value"><?= isset($data['permohonan_stats']['permohonan_baru']) ? number_format($data['permohonan_stats']['permohonan_baru']) : '0' ?></div>
                <div class="stat-label">Permohonan Baru</div>
                <div class="stat-sublabel">Menunggu diproses</div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-info">
                <div class="stat-icon icon-info">
                  <i class="mdi mdi-progress-clock"></i>
                </div>
                <div class="stat-value"><?= isset($data['permohonan_stats']['permohonan_proses']) ? number_format($data['permohonan_stats']['permohonan_proses']) : '0' ?></div>
                <div class="stat-label">Sedang Diproses</div>
                <div class="stat-sublabel">Dalam penanganan</div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-success">
                <div class="stat-icon icon-success">
                  <i class="mdi mdi-check-circle"></i>
                </div>
                <div class="stat-value"><?= isset($data['permohonan_stats']['permohonan_selesai']) ? number_format($data['permohonan_stats']['permohonan_selesai']) : '0' ?></div>
                <div class="stat-label">Selesai</div>
                <div class="stat-sublabel">Permohonan selesai</div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
              <div class="stat-card card-warning">
                <div class="stat-icon icon-warning">
                  <i class="mdi mdi-close-circle"></i>
                </div>
                <div class="stat-value"><?= isset($data['permohonan_stats']['permohonan_ditolak']) ? number_format($data['permohonan_stats']['permohonan_ditolak']) : '0' ?></div>
                <div class="stat-label">Ditolak</div>
                <div class="stat-sublabel">Permohonan ditolak</div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Quick Actions -->
            <div class="col-lg-4">
              <div class="quick-action-card">
                <h5 class="table-header">
                  <i class="mdi mdi-lightning-bolt"></i> Aksi Cepat
                </h5>
                <a href="index.php?controller=permohonan_petugas&action=meja_layanan" class="quick-action-btn">
                  <i class="mdi mdi-inbox-arrow-down text-danger"></i>
                  <div>
                    <div style="font-weight: 600;">Permohonan Masuk</div>
                    <div style="font-size: 12px; color: #94a3b8;">Lihat permohonan baru</div>
                  </div>
                </a>
                <a href="index.php?controller=permohonan_petugas&action=permohonan_diproses" class="quick-action-btn">
                  <i class="mdi mdi-cog text-info"></i>
                  <div>
                    <div style="font-weight: 600;">Sedang Diproses</div>
                    <div style="font-size: 12px; color: #94a3b8;">Kelola yang sedang diproses</div>
                  </div>
                </a>
                <a href="index.php?controller=permohonan_petugas&action=permohonan_selesai" class="quick-action-btn">
                  <i class="mdi mdi-check-circle text-success"></i>
                  <div>
                    <div style="font-weight: 600;">Permohonan Selesai</div>
                    <div style="font-size: 12px; color: #94a3b8;">Lihat yang telah selesai</div>
                  </div>
                </a>
                <a href="index.php?controller=permohonan_petugas&action=permohonan_ditolak" class="quick-action-btn">
                  <i class="mdi mdi-close-circle text-warning"></i>
                  <div>
                    <div style="font-weight: 600;">Permohonan Ditolak</div>
                    <div style="font-size: 12px; color: #94a3b8;">Lihat yang ditolak</div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Recent Permohonan Table -->
            <div class="col-lg-8">
              <div class="permohonan-table">
                <h5 class="table-header">
                  <i class="mdi mdi-clock-outline"></i> Permohonan Terbaru
                </h5>
                <div class="table-responsive">
                  <table class="modern-table">
                    <thead>
                      <tr>
                        <th>Pemohon</th>
                        <th>Permohonan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($data['recent_permohonan']) && !empty($data['recent_permohonan'])): ?>
                        <?php foreach ($data['recent_permohonan'] as $index => $permohonan): ?>
                          <?php if ($index < 5): // Only show 5 latest ?>
                            <tr>
                              <td>
                                <div class="pemohon-info">
                                  <div class="pemohon-avatar">
                                    <?= isset($permohonan['username']) ? strtoupper(substr($permohonan['username'], 0, 1)) : 'U' ?>
                                  </div>
                                  <div>
                                    <?= isset($permohonan['username']) ? htmlspecialchars($permohonan['username']) : 'Pengguna' ?>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <?= isset($permohonan['judul_dokumen']) && !empty($permohonan['judul_dokumen'])
                                    ? htmlspecialchars(substr($permohonan['judul_dokumen'], 0, 30) . (strlen($permohonan['judul_dokumen']) > 30 ? '...' : ''))
                                    : 'Permohonan Informasi' ?>
                              </td>
                              <td><?= isset($permohonan['created_at']) ? date('d M Y', strtotime($permohonan['created_at'])) : '-' ?></td>
                              <td>
                                <?php
                                $status = isset($permohonan['status']) ? strtolower($permohonan['status']) : 'pending';
                                $badge_class = 'badge-info';
                                if (strpos($status, 'selesai') !== false) $badge_class = 'badge-success';
                                elseif (strpos($status, 'diproses') !== false) $badge_class = 'badge-warning';
                                elseif (strpos($status, 'ditolak') !== false) $badge_class = 'badge-danger';
                                ?>
                                <span class="status-badge <?= $badge_class ?>">
                                  <?= isset($permohonan['status']) ? htmlspecialchars($permohonan['status']) : 'Pending' ?>
                                </span>
                              </td>
                              <td>
                                <a href="index.php?controller=permohonan_petugas&action=detail&id=<?= $permohonan['id_permohonan'] ?>"
                                   class="action-btn btn-primary">
                                  <i class="mdi mdi-eye"></i> Lihat
                                </a>
                              </td>
                            </tr>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center py-5">
                            <i class="mdi mdi-inbox mdi-48px text-muted d-block mb-3"></i>
                            <p class="text-muted">Tidak ada permohonan terbaru</p>
                          </td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <?php include 'template/script.php'; ?>
</body>
</html>
