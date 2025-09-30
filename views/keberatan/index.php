<?php
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

$title = 'Keberatan Saya - PPID Mandailing';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $title; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #3b82f6;
      --primary-dark: #1e3a8a;
      --secondary-color: #6b7280;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #dc2626;
      --light-bg: #f8f9fa;
      --white: #ffffff;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --shadow-light: rgba(0, 0, 0, 0.1);
      --shadow-medium: rgba(0, 0, 0, 0.15);
      --border-radius: 8px;
      --border-radius-lg: 15px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --spacing-xs: 4px;
      --spacing-sm: 8px;
      --spacing-md: 16px;
      --spacing-lg: 24px;
      --spacing-xl: 32px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
      color: var(--text-primary);
    }

    .top-info-bar {
      background-color: #e5e7eb;
      padding: 8px 0;
      font-size: 13px;
      color: #6b7280;
      border-bottom: 1px solid #d1d5db;
    }

    .top-info-bar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .top-info-links {
      display: flex;
      gap: 20px;
    }

    .top-info-links a {
      color: #6b7280;
      text-decoration: none;
      font-weight: 500;
    }

    .top-info-links a:hover {
      color: #374151;
    }

    .top-info-contact {
      display: flex;
      gap: 25px;
      align-items: center;
    }

    .top-info-contact span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .top-info-contact i {
      font-size: 12px;
    }

    .navbar-custom {
      background: #000000;
      padding: 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .main-navbar {
      padding: var(--spacing-md) 0;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      color: var(--white) !important;
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }

    .navbar-brand:hover {
      color: #e5e7eb !important;
      transform: translateY(-2px);
    }

    .logo-img {
      width: 50px;
      height: 50px;
      background: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .logo-img img {
      width: 40px;
      height: 40px;
    }

    .nav-text {
      display: flex;
      flex-direction: column;
    }

    .nav-title {
      font-size: 14px;
      font-weight: 500;
      line-height: 1.2;
      margin: 0;
    }

    .nav-subtitle {
      font-size: 18px;
      font-weight: 700;
      line-height: 1.2;
      margin: 0;
    }

    .navbar-nav {
      display: flex;
      flex-direction: row;
      gap: 8px;
      align-items: center;
      flex-wrap: nowrap;
      margin-left: auto;
    }

    .navbar-nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
    }

    .navbar-nav a:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .navbar-nav a.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }

    .breadcrumb-section {
      background-color: #e5e7eb;
      padding: 15px 0;
    }

    .breadcrumb {
      background: none;
      margin: 0;
      padding: 0;
    }

    .breadcrumb-item {
      font-size: 14px;
    }

    .breadcrumb-item a {
      color: #6b7280;
      text-decoration: none;
    }

    .breadcrumb-item.active {
      color: #1e3a8a;
      font-weight: 600;
    }

    .main-content {
      flex: 1;
      padding: 40px 0;
    }

    .page-header {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-xl);
      margin-bottom: var(--spacing-xl);
      box-shadow: 0 4px 20px var(--shadow-light);
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
      content: '';
      position: absolute;
      top: 0;
      right: -50%;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent, rgba(245, 158, 11, 0.1), transparent);
      transform: skewX(-20deg);
      animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
      0%, 100% { right: -50%; }
      50% { right: 150%; }
    }

    .page-title {
      font-size: clamp(1.5rem, 4vw, 2.5rem);
      font-weight: 700;
      margin: 0 0 var(--spacing-sm) 0;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: var(--spacing-md);
      position: relative;
      z-index: 2;
    }

    .page-title i {
      color: var(--warning-color);
      font-size: 0.8em;
    }

    .page-subtitle {
      color: var(--text-secondary);
      margin: 0;
      font-size: 1.1rem;
      position: relative;
      z-index: 2;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: var(--spacing-lg);
      margin-bottom: var(--spacing-xl);
    }

    .stat-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-xl);
      box-shadow: 0 4px 20px var(--shadow-light);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      border-left: 4px solid var(--warning-color);
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 40px var(--shadow-medium);
    }

    .stat-card.pending { border-left-color: var(--warning-color); }
    .stat-card.approved { border-left-color: var(--success-color); }
    .stat-card.rejected { border-left-color: var(--danger-color); }
    .stat-card.process { border-left-color: var(--secondary-color); }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      color: var(--text-primary);
    }

    .stat-label {
      color: var(--text-secondary);
      margin: 0;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .filters-section {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      padding: var(--spacing-lg);
      margin-bottom: var(--spacing-xl);
      box-shadow: 0 2px 10px var(--shadow-light);
    }

    .filters-grid {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: var(--spacing-md);
      align-items: end;
    }

    .search-wrapper {
      position: relative;
    }

    .search-input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: var(--transition);
    }

    .search-input:focus {
      border-color: var(--warning-color);
      box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
      outline: none;
    }

    .search-btn {
      position: absolute;
      right: 5px;
      top: 50%;
      transform: translateY(-50%);
      background: var(--warning-color);
      color: var(--white);
      border: none;
      border-radius: var(--border-radius);
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
    }

    .search-btn:hover {
      background: #d97706;
    }

    .btn {
      padding: 12px 24px;
      border-radius: var(--border-radius);
      font-weight: 600;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: var(--spacing-sm);
    }

    .btn-primary {
      background: var(--primary-color);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      color: var(--white);
      transform: translateY(-2px);
    }

    .btn-warning {
      background: var(--warning-color);
      color: var(--white);
    }

    .btn-warning:hover {
      background: #d97706;
      color: var(--white);
    }

    .btn-success {
      background: var(--success-color);
      color: var(--white);
    }

    .btn-success:hover {
      background: #059669;
      color: var(--white);
    }

    .btn-secondary {
      background: var(--secondary-color);
      color: var(--white);
    }

    .btn-secondary:hover {
      background: #4b5563;
      color: var(--white);
    }

    .btn-sm {
      padding: 8px 16px;
      font-size: 0.875rem;
    }

    .keberatan-grid {
      display: grid;
      gap: var(--spacing-lg);
    }

    .keberatan-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: 0 4px 20px var(--shadow-light);
      overflow: hidden;
      transition: var(--transition);
      position: relative;
    }

    .keberatan-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 40px var(--shadow-medium);
    }

    .keberatan-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.6s ease;
    }

    .keberatan-card:hover::before {
      left: 100%;
    }

    .keberatan-header {
      padding: var(--spacing-lg);
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      position: relative;
      z-index: 2;
      background: linear-gradient(135deg, #fef9c3, #fef3c7);
    }

    .keberatan-title {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--text-primary);
      margin: 0;
    }

    .keberatan-date {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin: var(--spacing-xs) 0 0 0;
    }

    .status-badge {
      padding: var(--spacing-xs) var(--spacing-md);
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.025em;
      display: inline-flex;
      align-items: center;
      gap: var(--spacing-xs);
    }

    .status-pending {
      background: linear-gradient(135deg, #fef3c7, #fde68a);
      color: #92400e;
    }

    .status-approved {
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      color: #047857;
    }

    .status-rejected {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #b91c1c;
    }

    .status-process {
      background: linear-gradient(135deg, #dbeafe, #bfdbfe);
      color: #1d4ed8;
    }

    .keberatan-body {
      padding: var(--spacing-lg);
      position: relative;
      z-index: 2;
    }

    .permohonan-info {
      background: linear-gradient(135deg, #f1f5f9, #f8fafc);
      padding: var(--spacing-md);
      border-radius: var(--border-radius);
      margin-bottom: var(--spacing-md);
      border: 1px solid var(--border-color);
    }

    .permohonan-number {
      font-weight: 600;
      color: var(--primary-color);
      font-size: 0.9rem;
      margin: 0 0 var(--spacing-xs) 0;
    }

    .permohonan-title {
      font-weight: 500;
      color: var(--text-primary);
      font-size: 0.875rem;
      margin: 0;
    }

    .keberatan-content {
      margin-bottom: var(--spacing-md);
    }

    .keberatan-meta {
      font-size: 0.875rem;
      color: var(--text-secondary);
      margin-bottom: var(--spacing-xs);
      font-weight: 600;
    }

    .keberatan-text {
      color: var(--text-primary);
      line-height: 1.5;
      font-size: 0.9rem;
    }

    .keberatan-actions {
      display: flex;
      gap: var(--spacing-sm);
      flex-wrap: wrap;
    }

    .pagination {
      margin-top: var(--spacing-xl);
      display: flex;
      justify-content: center;
      gap: var(--spacing-sm);
    }

    .pagination .page-link {
      padding: 8px 16px;
      color: var(--text-primary);
      text-decoration: none;
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius);
      transition: var(--transition);
    }

    .pagination .page-link:hover {
      background: var(--warning-color);
      color: var(--white);
      border-color: var(--warning-color);
    }

    .pagination .page-link.active {
      background: var(--warning-color);
      color: var(--white);
      border-color: var(--warning-color);
    }

    .pagination .page-link.disabled {
      opacity: 0.5;
      pointer-events: none;
    }

    .alert {
      border-radius: var(--border-radius);
      border: none;
      padding: var(--spacing-md);
      margin-bottom: var(--spacing-lg);
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .alert-success {
      background-color: #d1fae5;
      color: #059669;
      border-left: 4px solid #059669;
    }

    .alert-danger {
      background-color: #fee2e2;
      color: #dc2626;
      border-left: 4px solid #dc2626;
    }

    .alert i {
      margin-right: var(--spacing-sm);
      font-size: 1rem;
    }

    .empty-state {
      text-align: center;
      padding: var(--spacing-xl) var(--spacing-lg);
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 4rem;
      color: var(--border-color);
      margin-bottom: var(--spacing-md);
    }

    .empty-state h3 {
      font-size: 1.25rem;
      margin-bottom: var(--spacing-sm);
      color: var(--text-primary);
    }

    .footer {
      background: #1f2937;
      color: white;
      text-align: center;
      padding: 20px 0;
      margin-top: auto;
    }

    .footer p {
      margin: 0;
      font-size: 14px;
    }

    /* Animations */
    .keberatan-card {
      animation: slideInUp 0.5s ease-out forwards;
      opacity: 0;
      transform: translateY(20px);
    }

    @keyframes slideInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-content {
        padding: var(--spacing-lg) 0;
      }

      .page-header {
        padding: var(--spacing-lg);
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
      }

      .filters-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .keberatan-actions {
        flex-direction: column;
      }

      .keberatan-header {
        flex-direction: column;
        gap: var(--spacing-sm);
        align-items: flex-start;
      }

      .top-info-bar {
        display: none;
      }

      .navbar-nav {
        display: none;
      }

      .pagination {
        flex-wrap: wrap;
      }
    }

    /* Navigation Dropdown Styles */
    .nav-dropdown {
      position: relative;
      display: inline-block;
    }

    .nav-dropdown .dropdown-toggle {
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 4px;
      white-space: nowrap;
      display: flex;
      align-items: center;
    }

    .nav-dropdown .dropdown-toggle:hover {
      color: #ddd;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-dropdown .dropdown-toggle.active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }

    .dropdown-menu {
      background: black;
      border: none;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 8px 0;
      min-width: 200px;
      margin-top: 8px;
    }

    .dropdown-item {
      padding: 10px 16px;
      color: #374151;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .dropdown-item:hover {
      background-color: #f3f4f6;
      color: #1e3a8a;
    }

    .dropdown-item.active {
      background-color: #3b82f6;
      color: white;
    }

    .dropdown-divider {
      margin: 8px 0;
      border-color: #e5e7eb;
    }

    /* User Dropdown Styles */
    .user-dropdown .dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 12px;
    }

    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .user-avatar i {
      color: white;
      font-size: 14px;
    }

    .username {
      font-size: 14px;
      font-weight: 500;
      color: white;
      max-width: 120px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  </style>
</head>

<body>
  <!-- Top Info Bar -->
  <div class="top-info-bar">
    <div class="container">
      <div class="top-info-links">
        <a href="#">TENTANG PPID</a>
        <a href="#">KONTAK PPID</a>
      </div>
      <div class="top-info-contact">
        <span><i class="fas fa-envelope"></i> ppid@mandailingnatal.go.id</span>
        <span><i class="fas fa-phone"></i> Call Center: +628117905000</span>
      </div>
    </div>
  </div>

  <!-- Main Navigation Header -->
  <?php include 'views/layout/navbar_masyarakat.php'; ?>

  <!-- Breadcrumb -->
  <div class="breadcrumb-section">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?controller=user&action=index">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Keberatan Saya</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">
      <!-- Page Header -->
      <div class="page-header">
        <h1 class="page-title">
          <i class="fas fa-gavel"></i>
          Keberatan Saya
        </h1>
        <p class="page-subtitle">Kelola dan pantau status keberatan terhadap permohonan informasi Anda</p>
      </div>

      <!-- Success/Error Messages -->
      <?php if (!empty($success_message)): ?>
        <div class="alert alert-success" role="alert">
          <i class="fas fa-check-circle"></i>
          <?php echo htmlspecialchars($success_message); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <i class="fas fa-exclamation-triangle"></i>
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>

      <!-- Statistics -->
      <div class="stats-grid">
        <div class="stat-card">
          <h3 class="stat-number"><?php echo $stats['total']; ?></h3>
          <p class="stat-label">Total Keberatan</p>
        </div>
        <div class="stat-card pending">
          <h3 class="stat-number"><?php echo $stats['pending']; ?></h3>
          <p class="stat-label">Menunggu</p>
        </div>
        <div class="stat-card process">
          <h3 class="stat-number"><?php echo $stats['process'] ?? 0; ?></h3>
          <p class="stat-label">Diproses</p>
        </div>
        <div class="stat-card approved">
          <h3 class="stat-number"><?php echo $stats['approved']; ?></h3>
          <p class="stat-label">Disetujui</p>
        </div>
        <div class="stat-card rejected">
          <h3 class="stat-number"><?php echo $stats['rejected']; ?></h3>
          <p class="stat-label">Ditolak</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="filters-section">
        <form method="GET" action="index.php">
          <input type="hidden" name="controller" value="keberatan">
          <input type="hidden" name="action" value="index">

          <div class="filters-grid">
            <div class="search-wrapper">
              <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan nomor permohonan, alasan keberatan..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
              <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
              </button>
            </div>

            <a href="index.php?controller=keberatan&action=export&format=csv" class="btn btn-warning">
              <i class="fas fa-download"></i>
              Export CSV
            </a>
          </div>
        </form>
      </div>

      <!-- Keberatan List -->
      <div class="keberatan-grid">
        <?php if (!empty($keberatan_list)): ?>
          <?php foreach ($keberatan_list as $index => $keberatan): ?>
          <div class="keberatan-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
            <div class="keberatan-header">
              <div>
                <h3 class="keberatan-title">Keberatan #<?php echo $keberatan['id_keberatan']; ?></h3>
                <p class="keberatan-date">Diajukan: <?php echo date('d M Y', strtotime($keberatan['created_at'] ?? 'now')); ?></p>
              </div>
              <span class="status-badge status-<?php echo $keberatan['status'] ?? 'pending'; ?>">
                <?php
                $statusText = $keberatan['status'] ?? 'pending';
                switch($statusText) {
                  case 'pending': echo '⏳ Menunggu'; break;
                  case 'process': echo '🔄 Diproses'; break;
                  case 'approved': echo '✅ Disetujui'; break;
                  case 'rejected': echo '❌ Ditolak'; break;
                  default: echo '⏳ Menunggu';
                }
                ?>
              </span>
            </div>

            <div class="keberatan-body">
              <div class="permohonan-info">
                <div class="permohonan-number">Permohonan: <?php echo htmlspecialchars($keberatan['no_permohonan']); ?></div>
                <div class="permohonan-title"><?php echo htmlspecialchars($keberatan['judul_dokumen']); ?></div>
              </div>

              <div class="keberatan-content">
                <div class="keberatan-meta">Alasan Keberatan:</div>
                <div class="keberatan-text"><?php echo htmlspecialchars(substr($keberatan['alasan_keberatan'], 0, 150)); ?><?php echo strlen($keberatan['alasan_keberatan']) > 150 ? '...' : ''; ?></div>
              </div>

              <?php if (!empty($keberatan['keterangan'])): ?>
              <div class="keberatan-content">
                <div class="keberatan-meta">Keterangan Tambahan:</div>
                <div class="keberatan-text"><?php echo htmlspecialchars(substr($keberatan['keterangan'], 0, 100)); ?><?php echo strlen($keberatan['keterangan']) > 100 ? '...' : ''; ?></div>
              </div>
              <?php endif; ?>

              <div class="keberatan-actions">
                <a href="index.php?controller=keberatan&action=view&id=<?php echo $keberatan['id_keberatan']; ?>" class="btn btn-warning btn-sm">
                  <i class="fas fa-eye"></i>
                  Lihat Detail
                </a>
                <a href="index.php?controller=permohonan&action=view&id=<?php echo $keberatan['id_permohonan']; ?>" class="btn btn-secondary btn-sm">
                  <i class="fas fa-file-alt"></i>
                  Lihat Permohonan
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-gavel"></i>
            <h3>Belum Ada Keberatan</h3>
            <p>Anda belum mengajukan keberatan apapun. Keberatan dapat diajukan untuk permohonan informasi yang Anda ajukan.</p>
            <a href="index.php?controller=permohonan&action=index" class="btn btn-primary">
              <i class="fas fa-list"></i>
              Lihat Permohonan Saya
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?controller=keberatan&action=index&page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            <i class="fas fa-chevron-left"></i>
            Sebelumnya
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
          <a href="?controller=keberatan&action=index&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
             class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <a href="?controller=keberatan&action=index&page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            Selanjutnya
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 PPID Kemendagri ALL Rights Reserved</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-hide alerts
      setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
          alert.style.transition = 'all 0.5s ease-out';
          alert.style.opacity = '0';
          alert.style.transform = 'translateY(-20px)';
          setTimeout(() => alert.remove(), 500);
        });
      }, 5000);

      // Add staggered animation to cards
      document.querySelectorAll('.keberatan-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });

      // Smooth scroll for pagination
      document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
          if (!this.classList.contains('active') && !this.classList.contains('disabled')) {
            document.body.style.opacity = '0.8';
            setTimeout(() => {
              document.body.style.opacity = '1';
            }, 300);
          }
        });
      });
    });
  </script>
</body>

</html>