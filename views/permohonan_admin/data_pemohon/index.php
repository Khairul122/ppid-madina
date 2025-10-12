<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

// Include header template
include('template/header.php');
?>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'template/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'template/setting_panel.php'; ?>
      <?php include 'template/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Page Header -->
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="page-header flex-wrap">
                <div class="header-left">
                  <h3 class="font-weight-bold mb-0">Data Pemohon</h3>
                  <h6 class="mb-0">Daftar lengkap pemohon permohonan informasi publik</h6>
                </div>
                <div class="header-right d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="me-3">
                      <button class="btn btn-primary">
                        <i class="ti-stats-up me-2"></i>Total Pemohon: <?php echo number_format($total_pemohon); ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Search and Filter Section -->
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-filter me-2"></i>Filter Data</h4>
                  <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="controller" value="permohonanadmin">
                    <input type="hidden" name="action" value="dataPemohonIndex">
                    
                    <div class="col-md-8">
                      <label class="form-label">Kata Kunci Pencarian</label>
                      <input type="text" name="search" class="form-control" 
                             placeholder="Cari berdasarkan nama, NIK, email, atau nomor telepon..." 
                             value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary btn-block w-100">
                        <i class="fas fa-search me-1"></i>Cari
                      </button>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                      <a href="index.php?controller=permohonanadmin&action=dataPemohonIndex" class="btn btn-outline-secondary btn-block w-100">
                        <i class="fas fa-sync-alt me-1"></i>Reset
                      </a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Data Table Section -->
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-4"><i class="ti-agenda me-2"></i>Daftar Pemohon</h4>
                  <p class="card-description mb-4">Menampilkan data pemohon permohonan informasi publik</p>
                  
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th width="5%">No</th>
                          <th width="20%">Nama Lengkap</th>
                          <th width="15%">NIK</th>
                          <th width="15%">Email</th>
                          <th width="10%">Telepon</th>
                          <th width="10%">Kota</th>
                          <th width="10%" class="text-center">Total Permohonan</th>
                          <th width="15%" class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($pemohon_list)): ?>
                          <?php 
                          $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                          $limit = 10;
                          $no = ($page - 1) * $limit + 1; 
                          ?>
                          <?php foreach ($pemohon_list as $pemohon): ?>
                            <tr>
                              <td><?php echo $no++ ?></td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <div class="ms-3">
                                    <p class="fw-bold mb-1"><?php echo htmlspecialchars($pemohon['nama_lengkap']) ?></p>
                                    <p class="text-muted mb-0"><?php echo htmlspecialchars($pemohon['alamat']) ?></p>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($pemohon['nik']) ?></span>
                              </td>
                              <td><?php echo htmlspecialchars($pemohon['email']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['no_kontak']) ?></td>
                              <td><?php echo htmlspecialchars($pemohon['city']) ?></td>
                              <td class="text-center">
                                <span class="badge bg-primary rounded-pill"><?php echo (int)$pemohon['total_permohonan'] ?></span>
                              </td>
                              <td class="text-center">
                                <div class="btn-group" role="group">
                                  <a href="index.php?controller=permohonanadmin&action=detail_pemohon&id=<?php echo isset($pemohon['id_biodata']) ? htmlspecialchars($pemohon['id_biodata']) : ''; ?>" 
                                     class="btn btn-sm btn-outline-info" 
                                     title="Detail">
                                    <i class="fas fa-eye me-1"></i>Lihat
                                  </a>
                                </div>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="8" class="text-center py-5">
                              <div class="text-center">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Data Pemohon Tidak Ditemukan</h4>
                                <p class="text-muted">Belum ada data pemohon atau tidak sesuai dengan kriteria pencarian</p>
                              </div>
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Pagination -->
                  <?php if (isset($total_pages) && $total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                      <ul class="pagination justify-content-center">
                        <?php 
                        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; 
                        $search_param = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; 
                        ?>
                        
                        <?php if ($current_page > 1): ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=permohonanadmin&action=dataPemohonIndex&page=1<?php echo $search_param; ?>" aria-label="First">
                              <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                          <li class="page-item">
                            <a class="page-link" href="?controller=permohonanadmin&action=dataPemohonIndex&page=<?php echo ($current_page - 1) . $search_param; ?>" aria-label="Previous">
                              <span aria-hidden="true">&lsaquo;</span>
                            </a>
                          </li>
                        <?php endif; ?>

                        <?php 
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++): 
                        ?>
                          <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?controller=permohonanadmin&action=dataPemohonIndex&page=<?php echo $i . $search_param; ?>">
                              <?php echo $i; ?>
                            </a>
                          </li>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                          <li class="page-item">
                            <a class="page-link" href="?controller=permohonanadmin&action=dataPemohonIndex&page=<?php echo ($current_page + 1) . $search_param; ?>" aria-label="Next">
                              <span aria-hidden="true">&rsaquo;</span>
                            </a>
                          </li>
                          <li class="page-item">
                            <a class="page-link" href="?controller=permohonanadmin&action=dataPemohonIndex&page=<?php echo $total_pages . $search_param; ?>" aria-label="Last">
                              <span aria-hidden="true">&raquo;</span>
                            </a>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </nav>

                    <div class="text-center mt-3">
                      <small class="text-muted">
                        Menampilkan <?php echo number_format(min(($current_page - 1) * $limit + 1, $total_records)); ?> - <?php echo number_format(min($current_page * $limit, $total_records)); ?>
                        dari <?php echo number_format($total_records); ?> data
                      </small>
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
  
  <!-- Scripts -->
  <?php include('template/script.php') ?>
  
  <style>
    /* Import Inter Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* Government Theme Styles - PPID Mandailing Natal */
    :root {
      --primary-color: #1e3a8a;      /* Dark Blue - Professional & Trustworthy */
      --secondary-color: #f59e0b;    /* Amber - Energetic & Accessible */
      --accent-color: #fbbf24;       /* Light Amber - Highlight */
      --text-color: #1f2937;         /* Dark Gray */
      --muted-color: #6b7280;        /* Gray */
      --light-bg: #f8f9fa;           /* Light Background */
      --gov-border: #e2e8f0;         /* Border Color */
      --success-color: #10b981;      /* Green */
      --info-color: #0ea5e9;         /* Sky Blue */
      --warning-color: #f59e0b;      /* Amber */
      --danger-color: #ef4444;       /* Red */
    }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--text-color);
      background-color: var(--light-bg);
    }

    /* Page Header - Enhanced Design */
    .page-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 50%, #3b82f6 100%);
      color: white;
      padding: 2rem 2rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(30, 58, 138, 0.2);
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      transform: translate(30%, -30%);
    }

    .page-header h3 {
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 1.75rem;
      position: relative;
      z-index: 1;
    }

    .page-header h6 {
      opacity: 0.95;
      font-weight: 400;
      font-size: 0.95rem;
      position: relative;
      z-index: 1;
    }

    .page-header .btn-primary {
      background-color: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
      font-weight: 600;
    }

    .page-header .btn-primary:hover {
      background-color: rgba(255, 255, 255, 0.3);
      border-color: rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Card Styling - Professional Look */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      margin-bottom: 2rem;
      transition: all 0.3s ease;
      background: white;
    }

    .card:hover {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      transform: translateY(-2px);
    }

    .card-body {
      padding: 2rem;
    }

    .card-title {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-title i {
      font-size: 1.5rem;
      color: var(--secondary-color);
    }

    .card-description {
      color: var(--muted-color);
      font-size: 0.9rem;
      line-height: 1.6;
    }

    /* Form Controls - Enhanced */
    .form-label {
      font-weight: 500;
      color: var(--text-color);
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    .form-control {
      border: 2px solid var(--gov-border);
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.15);
      outline: none;
    }

    .form-control::placeholder {
      color: var(--muted-color);
      opacity: 0.6;
    }

    /* Buttons - Government Style */
    .btn {
      border-radius: 8px;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: #152a68;
      border-color: #152a68;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-outline-secondary {
      border: 2px solid var(--gov-border);
      color: var(--text-color);
      background: white;
    }

    .btn-outline-secondary:hover {
      background-color: var(--light-bg);
      border-color: var(--muted-color);
      color: var(--text-color);
      transform: translateY(-2px);
    }

    .btn-outline-info {
      border: 2px solid var(--info-color);
      color: var(--info-color);
      background: white;
    }

    .btn-outline-info:hover {
      background-color: var(--info-color);
      border-color: var(--info-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }

    .btn i {
      font-size: 1rem;
    }

    /* Table Styling - Professional */
    .table-responsive {
      border-radius: 8px;
      overflow: hidden;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead {
      background-color: var(--primary-color);
    }

    .table thead th {
      color: white;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 1.25rem 1rem;
      border: none;
      white-space: nowrap;
    }

    .table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid var(--gov-border);
    }

    .table tbody tr:hover {
      background-color: rgba(30, 58, 138, 0.03);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
      padding: 1.25rem 1rem;
      vertical-align: middle;
      font-size: 0.9rem;
      color: var(--text-color);
    }

    .table tbody td p {
      margin-bottom: 0;
    }

    .table tbody td .fw-bold {
      color: var(--text-color);
      font-weight: 600;
    }

    .table tbody td .text-muted {
      color: var(--muted-color);
      font-size: 0.85rem;
    }

    /* Badges - Enhanced */
    .badge {
      padding: 0.5rem 1rem;
      font-weight: 500;
      font-size: 0.85rem;
      border-radius: 6px;
      letter-spacing: 0.3px;
    }

    .badge.bg-primary {
      background-color: var(--primary-color) !important;
    }

    .badge.bg-info {
      background-color: var(--info-color) !important;
    }

    .badge.rounded-pill {
      padding: 0.4rem 0.9rem;
      font-weight: 600;
    }

    /* Pagination - Government Style */
    .pagination {
      gap: 0.5rem;
    }

    .pagination .page-item {
      margin: 0;
    }

    .pagination .page-link {
      color: var(--primary-color);
      border: 2px solid var(--gov-border);
      border-radius: 8px;
      padding: 0.65rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
      margin: 0 0.25rem;
    }

    .pagination .page-link:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    .pagination .page-item.active .page-link {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Empty State */
    .text-center.py-5 {
      padding: 4rem 2rem !important;
    }

    .text-center.py-5 i {
      color: var(--muted-color);
      opacity: 0.4;
      margin-bottom: 1.5rem;
    }

    .text-center.py-5 h4 {
      color: var(--text-color);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .text-center.py-5 p {
      color: var(--muted-color);
      font-size: 0.95rem;
    }

    /* Button Group */
    .btn-group .btn-sm {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      border-radius: 6px;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .page-header {
        padding: 1.5rem;
      }

      .page-header h3 {
        font-size: 1.5rem;
      }

      .card-body {
        padding: 1.5rem;
      }

      .table thead th,
      .table tbody td {
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
      }
    }

    @media (max-width: 768px) {
      .page-header {
        padding: 1.25rem;
      }

      .page-header .header-right {
        margin-top: 1rem;
      }

      .card-body {
        padding: 1.25rem;
      }

      .btn {
        padding: 0.65rem 1.25rem;
        font-size: 0.9rem;
      }

      .table-responsive {
        border-radius: 6px;
      }

      .table thead th,
      .table tbody td {
        padding: 0.85rem 0.65rem;
        font-size: 0.8rem;
      }

      .pagination .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
      }
    }

    @media (max-width: 576px) {
      .page-header h3 {
        font-size: 1.25rem;
      }

      .page-header h6 {
        font-size: 0.875rem;
      }

      .card-title {
        font-size: 1.1rem;
      }

      .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }

      .btn-group .btn-sm {
        width: 100%;
      }
    }
  </style>
</body>
</html>