<?php
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php?controller=auth&action=login');
  exit();
}

$title = 'Daftar Permohonan Saya - PPID Mandailing';
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
      background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.1), transparent);
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
      color: var(--primary-color);
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
      border-left: 4px solid var(--primary-color);
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
      grid-template-columns: 1fr auto auto;
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
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    .search-btn {
      position: absolute;
      right: 5px;
      top: 50%;
      transform: translateY(-50%);
      background: var(--primary-color);
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
      background: var(--primary-dark);
    }

    .filter-select {
      padding: 12px 15px;
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius);
      font-size: 1rem;
      min-width: 150px;
      transition: var(--transition);
    }

    .filter-select:focus {
      border-color: var(--primary-color);
      outline: none;
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

    .btn-danger {
      background: var(--danger-color);
      color: var(--white);
    }

    .btn-danger:hover {
      background: #b91c1c;
      color: var(--white);
    }

    .btn-warning {
      background: var(--warning-color);
      color: var(--white);
    }

    .btn-warning:hover {
      background: #d97706;
      color: var(--white);
      transform: translateY(-2px);
    }

    .btn-sm {
      padding: 8px 16px;
      font-size: 0.875rem;
    }

    .permohonan-grid {
      display: grid;
      gap: var(--spacing-lg);
    }

    .permohonan-card {
      background: var(--white);
      border-radius: var(--border-radius-lg);
      box-shadow: 0 4px 20px var(--shadow-light);
      overflow: hidden;
      transition: var(--transition);
      position: relative;
    }

    .permohonan-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 40px var(--shadow-medium);
    }

    .permohonan-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.6s ease;
    }

    .permohonan-card:hover::before {
      left: 100%;
    }

    .permohonan-header {
      padding: var(--spacing-lg);
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      position: relative;
      z-index: 2;
    }

    .permohonan-number {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--text-primary);
      margin: 0;
    }

    .permohonan-date {
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

    .permohonan-body {
      padding: var(--spacing-lg);
      position: relative;
      z-index: 2;
    }

    .permohonan-title {
      font-weight: 600;
      font-size: 1rem;
      color: var(--text-primary);
      margin: 0 0 var(--spacing-sm) 0;
      line-height: 1.4;
    }

    .permohonan-meta {
      display: grid;
      gap: var(--spacing-xs);
      margin-bottom: var(--spacing-md);
      font-size: 0.875rem;
      color: var(--text-secondary);
    }

    .permohonan-meta strong {
      color: var(--text-primary);
    }

    .permohonan-actions {
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
      background: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
    }

    .pagination .page-link.active {
      background: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
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
    .permohonan-card {
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

      .permohonan-actions {
        flex-direction: column;
      }

      .permohonan-header {
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
  </style>
</head>

<body>

  <!-- Main Navigation Header -->
  <?php include 'views/layout/navbar_masyarakat.php'; ?>

  <!-- Breadcrumb -->
  <div class="breadcrumb-section">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?controller=user&action=index">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Permohonan Saya</li>
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
          <i class="fas fa-list-alt"></i>
          Permohonan Saya
        </h1>
        <p class="page-subtitle">Kelola dan pantau status permohonan informasi yang telah Anda ajukan</p>
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
     

      <!-- Filters -->
      <div class="filters-section">
        <form method="GET" action="index.php">
          <input type="hidden" name="controller" value="permohonan">
          <input type="hidden" name="action" value="index">

          <div class="filters-grid">
            <div class="search-wrapper">
              <input type="text" name="search" class="search-input" placeholder="Cari berdasarkan nomor permohonan, tujuan, atau dokumen..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
              <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
              </button>
            </div>

            <select name="status" class="filter-select" onchange="this.form.submit()">
              <option value="all" <?php echo ($status ?? '') === 'all' ? 'selected' : ''; ?>>Semua Status</option>
              <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
              <option value="process" <?php echo ($status ?? '') === 'process' ? 'selected' : ''; ?>>Diproses</option>
              <option value="approved" <?php echo ($status ?? '') === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
              <option value="rejected" <?php echo ($status ?? '') === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
              <option value="selesai" <?php echo ($status ?? '') === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
              <option value="ditolak" <?php echo ($status ?? '') === 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
            </select>

            <a href="index.php?controller=permohonan&action=export&format=csv&status=<?php echo $status ?? 'all'; ?>" class="btn btn-success">
              <i class="fas fa-download"></i>
              Export CSV
            </a>
          </div>
        </form>
      </div>

      <!-- Permohonan List -->
      <div class="permohonan-grid">
        <?php if (!empty($permohonan_list)): ?>
          <?php foreach ($permohonan_list as $index => $permohonan): ?>
          <div class="permohonan-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
            <div class="permohonan-header">
              <div>
                <h3 class="permohonan-number"><?php echo htmlspecialchars($permohonan['no_permohonan']); ?></h3>
                <p class="permohonan-date">Diajukan: <?php echo date('d M Y', strtotime($permohonan['created_at'] ?? 'now')); ?></p>
              </div>
              <span class="status-badge status-<?php echo strtolower($permohonan['status'] ?? 'pending'); ?>">
                <?php
                $statusText = $permohonan['status'] ?? 'pending';
                switch(strtolower($statusText)) {
                  case 'pending': echo '⏳ Menunggu'; break;
                  case 'process': echo '🔄 Diproses'; break;
                  case 'diproses': echo '🔄 Diproses'; break;
                  case 'approved': echo '✅ Disetujui'; break;
                  case 'selesai': echo '✅ Selesai'; break;
                  case 'rejected': echo '❌ Ditolak'; break;
                  case 'ditolak': echo '❌ Ditolak'; break;
                  default: echo '⏳ ' . ucfirst($statusText);
                }
                ?>
              </span>
            </div>

            <div class="permohonan-body">
              <h4 class="permohonan-title"><?php echo htmlspecialchars($permohonan['judul_dokumen']); ?></h4>

              <div class="permohonan-meta">
                <div><strong>Unit Tujuan:</strong> <?php echo htmlspecialchars($permohonan['komponen_tujuan']); ?></div>
                <div><strong>Tujuan:</strong> <?php echo htmlspecialchars(substr($permohonan['tujuan_permohonan'], 0, 100)); ?><?php echo strlen($permohonan['tujuan_permohonan']) > 100 ? '...' : ''; ?></div>
              </div>

              <div class="permohonan-actions">
                <a href="index.php?controller=permohonan&action=view&id=<?php echo $permohonan['id_permohonan']; ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye"></i>
                  Lihat Detail
                </a>

                <?php
                // Tombol ajukan keberatan hanya muncul jika status bukan keberatan atau selesai
                $forbiddenStatuses = ['Keberatan', 'Selesai'];
                $currentStatus = $permohonan['status'] ?? null;
                if (!in_array($currentStatus, $forbiddenStatuses)):
                ?>
                <button type="button" class="btn btn-warning btn-sm"
                        onclick='openKeberatanModal(<?php echo $permohonan['id_permohonan']; ?>, <?php echo json_encode($permohonan['no_permohonan'] ?? ''); ?>, <?php echo json_encode($permohonan['judul_dokumen'] ?? ''); ?>)'>
                  <i class="fas fa-exclamation-triangle me-1"></i>
                  Ajukan Keberatan
                </button>
                <?php endif; ?>

                <?php
                // Tampilkan tombol Layanan Kepuasan hanya untuk permohonan dengan status "Diproses"
                $currentStatus = $permohonan['status'] ?? null;
                if ($currentStatus === 'Diproses'):
                ?>
                <button type="button" class="btn btn-success btn-sm"
                        data-id="<?php echo $permohonan['id_permohonan']; ?>"
                        data-no="<?php echo htmlspecialchars($permohonan['no_permohonan']); ?>"
                        data-judul="<?php echo htmlspecialchars($permohonan['judul_dokumen']); ?>"
                        data-tanggal="<?php echo date('d M Y', strtotime($permohonan['created_at'] ?? 'now')); ?>"
                        onclick="openLayananKepuasanModal(this.dataset.id, this.dataset.no, this.dataset.judul, this.dataset.tanggal)">
                  <i class="fas fa-star"></i>
                  Layanan Kepuasan
                </button>
                <?php endif; ?>

                <?php
                // Tampilkan tombol Ajukan Sengketa hanya untuk permohonan dengan status "Ditolak"
                $currentStatus = $permohonan['status'] ?? null;
                if ($currentStatus === 'Ditolak'):
                ?>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="openSengketaModal(<?php echo $permohonan['id_permohonan']; ?>, '<?php echo addslashes($permohonan['no_permohonan'] ?? ''); ?>', '<?php echo addslashes($permohonan['judul_dokumen'] ?? ''); ?>')">
                  <i class="fas fa-gavel"></i>
                  Ajukan Sengketa
                </button>
                <?php endif; ?>

                <?php
                $currentStatus = $permohonan['status'] ?? null;
                if ($currentStatus === 'pending' || $currentStatus === '' || $currentStatus === null):
                ?>
                <a href="index.php?controller=permohonan&action=delete&id=<?php echo $permohonan['id_permohonan']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin ingin menghapus permohonan ini?')">
                  <i class="fas fa-trash"></i>
                  Hapus
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum Ada Permohonan</h3>
            <p>Anda belum mengajukan permohonan informasi apapun.</p>
            <a href="index.php?controller=AjukanPermohonan&action=index" class="btn btn-primary">
              <i class="fas fa-plus"></i>
              Ajukan Permohonan Sekarang
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?controller=permohonan&action=index&page=<?php echo $page - 1; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            <i class="fas fa-chevron-left"></i>
            Sebelumnya
          </a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
          <a href="?controller=permohonan&action=index&page=<?php echo $i; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>"
             class="page-link <?php echo $i === $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <a href="?controller=permohonan&action=index&page=<?php echo $page + 1; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="page-link">
            Selanjutnya
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Keberatan Modal -->
  <div class="modal fade" id="keberatanModal" tabindex="-1" aria-labelledby="keberatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="keberatanForm" method="POST">
          <div class="modal-header bg-warning text-dark border-0">
            <h5 class="modal-title fw-bold" id="keberatanModalLabel">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Ajukan Keberatan Permohonan
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id_permohonan" id="keberatan_id_permohonan" value="">

            <div class="alert alert-info border-0">
              <i class="fas fa-info-circle me-2"></i>
              <strong>Informasi:</strong> Anda akan mengajukan keberatan untuk permohonan berikut
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Nomor Permohonan</label>
                <input type="text" class="form-control bg-light" id="keberatan_no_permohonan" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Judul Dokumen</label>
                <input type="text" class="form-control bg-light" id="keberatan_judul_dokumen" readonly>
              </div>
            </div>

            <div class="mb-3">
              <label for="alasan_keberatan" class="form-label fw-bold">
                Alasan Keberatan <span class="text-danger">*</span>
              </label>
              <textarea class="form-control" id="alasan_keberatan" name="alasan_keberatan"
                        rows="5" required
                        placeholder="Jelaskan secara detail alasan keberatan Anda terhadap permohonan ini..."></textarea>
              <small class="text-muted">Minimal 20 karakter</small>
              <div class="invalid-feedback">Alasan keberatan minimal 20 karakter</div>
            </div>

            <div class="mb-3">
              <label for="keterangan_keberatan" class="form-label fw-bold">
                Keterangan Tambahan
              </label>
              <textarea class="form-control" id="keterangan_keberatan" name="keterangan"
                        rows="3"
                        placeholder="Tambahkan keterangan atau informasi pendukung lainnya (opsional)..."></textarea>
            </div>

            <div class="alert alert-warning border-0 mb-0">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Perhatian:</strong> Setelah keberatan diajukan, status permohonan akan berubah menjadi <strong>"Keberatan"</strong> dan akan ditinjau oleh admin PPID. Pastikan alasan yang diberikan jelas dan dapat dipertanggungjawabkan.
            </div>
          </div>

          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Batal
            </button>
            <button type="submit" class="btn btn-warning" id="submit-keberatan-btn">
              <i class="fas fa-paper-plane me-1"></i>Ajukan Keberatan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Form Layanan Kepuasan -->
  <div class="modal fade" id="layananKepuasanModal" tabindex="-1" aria-labelledby="layananKepuasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="layananKepuasanModalLabel">
            <i class="fas fa-star text-warning me-2"></i>
            Form Layanan Kepuasan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=permohonan&action=submitLayananKepuasan" id="layananKepuasanForm">
          <div class="modal-body px-4 py-3">
            <input type="hidden" name="id_permohonan" id="layanan_id_permohonan">

            <!-- Data Responden -->
            <div class="mb-4">
              <h6 class="mb-3">
                <i class="fas fa-user text-secondary me-2"></i>
                Data Responden
              </h6>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-medium text-dark mb-2">
                    Nama Lengkap <span class="text-danger">*</span>
                  </label>
                  <input type="text" class="form-control form-control-lg" name="nama" id="layanan_nama" required placeholder="Masukkan nama lengkap Anda">
                  <div class="invalid-feedback">Nama lengkap wajib diisi</div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label fw-medium text-dark mb-2">
                    Umur <span class="text-danger">*</span>
                  </label>
                  <input type="number" class="form-control form-control-lg" name="umur" id="layanan_umur" min="17" max="100" required placeholder="Masukkan umur Anda">
                  <div class="invalid-feedback">Umur wajib diisi (17-100 tahun)</div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label fw-medium text-dark mb-2">
                    Provinsi <span class="text-danger">*</span>
                  </label>
                  <select class="form-select form-control-lg" name="provinsi" id="layanan_provinsi" required>
                    <option value="">-- Pilih Provinsi --</option>
                  </select>
                  <div class="invalid-feedback">Provinsi wajib dipilih</div>
                </div>
                
                <div class="col-md-6">
                  <label class="form-label fw-medium text-dark mb-2">
                    Kota/Kabupaten <span class="text-danger">*</span>
                  </label>
                  <select class="form-select form-control-lg" name="kota" id="layanan_kota" required>
                    <option value="">-- Pilih Kota/Kabupaten --</option>
                  </select>
                  <div class="invalid-feedback">Kota/Kabupaten wajib dipilih</div>
                </div>
              </div>
            </div>

            <!-- Penilaian Kepuasan -->
            <div class="mb-4">
              <h6 class="mb-3">
                <i class="fas fa-star-half-alt text-warning me-2"></i>
                Penilaian Kepuasan
              </h6>
              
              <div class="card border">
                <div class="card-body">
                  <div class="mb-3">
                    <label class="form-label fw-medium text-dark mb-2">
                      Permohonan Informasi <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" name="permohonan_informasi" id="layanan_permohonan_informasi" rows="3" required placeholder="Jelaskan pengalaman Anda dalam proses permohonan informasi ini..."></textarea>
                    <div class="invalid-feedback">Permohonan informasi wajib diisi</div>
                  </div>
                  
                  <div class="mb-0">
                    <label class="form-label fw-medium text-dark mb-3">
                      Rating Pelayanan <span class="text-danger">*</span>
                    </label>
                    <div class="rating-stars d-flex justify-content-center gap-2">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" class="btn btn-outline-warning btn-rating" data-rating="<?php echo $i; ?>">
                          <i class="fas fa-star fa-2x"></i>
                        </button>
                      <?php endfor; ?>
                    </div>
                    <input type="hidden" name="rating" id="layanan_rating" required>
                    <div class="invalid-feedback text-center mt-2">Rating wajib dipilih</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>
              Batal
            </button>
            <button type="submit" class="btn btn-primary px-4">
              <i class="fas fa-paper-plane me-1"></i>
              Kirim Penilaian
            </button>
          </div>
        </form>
      </div>
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
    // Function to open keberatan modal
    function openKeberatanModal(permohonanId, noPermohonan, judulDokumen) {
      document.getElementById('keberatan_id_permohonan').value = permohonanId;
      document.getElementById('keberatan_no_permohonan').value = noPermohonan;
      document.getElementById('keberatan_judul_dokumen').value = judulDokumen;

      // Clear form
      document.getElementById('alasan_keberatan').value = '';
      document.getElementById('keterangan_keberatan').value = '';
      document.getElementById('alasan_keberatan').classList.remove('is-invalid');

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('keberatanModal'));
      modal.show();
    }

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
      document.querySelectorAll('.permohonan-card').forEach((card, index) => {
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

      // Keberatan form validation and submission
      const keberatanForm = document.getElementById('keberatanForm');
      if (keberatanForm) {
        keberatanForm.addEventListener('submit', function(e) {
          e.preventDefault();

          const alasanKeberatan = document.getElementById('alasan_keberatan');
          const submitBtn = document.getElementById('submit-keberatan-btn');

          // Validate alasan keberatan
          if (alasanKeberatan.value.trim().length < 20) {
            alasanKeberatan.classList.add('is-invalid');
            return;
          }

          // Show loading state
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengajukan...';
          submitBtn.disabled = true;

          // Prepare form data
          const formData = new FormData(keberatanForm);

          // Submit via AJAX
          fetch('index.php?controller=permohonan&action=submitKeberatan', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Keberatan berhasil diajukan! Status permohonan telah diubah menjadi Keberatan.');
              location.reload();
            } else {
              alert('Gagal mengajukan keberatan: ' + data.message);
              submitBtn.innerHTML = originalText;
              submitBtn.disabled = false;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengajukan keberatan');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
          });
        });

        // Clear validation on input
        document.getElementById('alasan_keberatan').addEventListener('input', function() {
          this.classList.remove('is-invalid');
        });
      }

      // Layanan Kepuasan form validation and functionality
      const layananKepuasanForm = document.getElementById('layananKepuasanForm');
      if (layananKepuasanForm) {
        // Handle rating stars
        const ratingButtons = document.querySelectorAll('.btn-rating');
        const ratingInput = document.getElementById('layanan_rating');
        
        ratingButtons.forEach(button => {
          button.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            
            // Update star appearance
            ratingButtons.forEach(btn => {
              const starIcon = btn.querySelector('i');
              if (parseInt(btn.getAttribute('data-rating')) <= parseInt(rating)) {
                starIcon.classList.remove('far', 'fa-star');
                starIcon.classList.add('fas', 'fa-star');
                btn.classList.add('active');
              } else {
                starIcon.classList.remove('fas', 'fa-star');
                starIcon.classList.add('far', 'fa-star');
                btn.classList.remove('active');
              }
            });
            
            // Clear validation error
            ratingInput.classList.remove('is-invalid');
          });
        });

        // Form validation
        layananKepuasanForm.addEventListener('submit', function(e) {
          let isValid = true;
          
          // Validate required fields
          const requiredFields = [
            'nama', 'umur', 'provinsi', 'kota', 'permohonan_informasi'
          ];
          
          requiredFields.forEach(fieldName => {
            const field = document.getElementById(`layanan_${fieldName}`);
            if (!field.value.trim()) {
              e.preventDefault();
              field.classList.add('is-invalid');
              isValid = false;
            } else {
              field.classList.remove('is-invalid');
            }
          });
          
          // Validate rating
          if (!ratingInput.value) {
            e.preventDefault();
            ratingInput.classList.add('is-invalid');
            isValid = false;
          } else {
            ratingInput.classList.remove('is-invalid');
          }
          
          if (!isValid) {
            return false;
          }
          
          // Show loading state
          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn.innerHTML;
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
          submitBtn.disabled = true;
        });

        // Clear validation on input
        layananKepuasanForm.addEventListener('input', function(e) {
          if (e.target.classList.contains('is-invalid')) {
            e.target.classList.remove('is-invalid');
          }
        });
      }

      // Handle province change for layanan kepuasan
      document.getElementById('layanan_provinsi').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceId = selectedOption.getAttribute('data-id');
        if (provinceId) {
          loadCities(provinceId, 'layanan_kota');
        } else {
          document.getElementById('layanan_kota').innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
        }
      });
    });

    // Function to load provinces data
    function loadProvinces(selectId) {
      const selectElement = document.getElementById(selectId);
      if (!selectElement) return;

      // Clear existing options
      selectElement.innerHTML = '<option value="">-- Loading provinsi... --</option>';

      // Fetch provinces data
      fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
        .then(response => response.json())
        .then(data => {
          // Clear loading option
          selectElement.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

          // Add provinces to select
          data.forEach(province => {
            const option = document.createElement('option');
            option.value = province.name;
            option.textContent = province.name;
            option.setAttribute('data-id', province.id);
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Error loading provinces:', error);
          selectElement.innerHTML = '<option value="">-- Gagal memuat provinsi --</option>';
        });
    }

    // Function to load cities data based on province
    function loadCities(provinceId, selectId) {
      const selectElement = document.getElementById(selectId);
      if (!selectElement) return;

      // Clear existing options
      selectElement.innerHTML = '<option value="">-- Loading kota/kabupaten... --</option>';

      // Fetch cities data
      fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
        .then(response => response.json())
        .then(data => {
          // Clear loading option
          selectElement.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';

          // Add cities to select
          data.forEach(city => {
            const option = document.createElement('option');
            option.value = city.name;
            option.textContent = city.name;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Error loading cities:', error);
          selectElement.innerHTML = '<option value="">-- Gagal memuat kota/kabupaten --</option>';
        });
    }

    // Function to open layanan kepuasan modal (defined outside DOMContentLoaded)
    function openLayananKepuasanModal(permohonanId, noPermohonan, judulDokumen, tanggalPermohonan) {
      document.getElementById('layanan_id_permohonan').value = permohonanId;

      // Clear form
      document.getElementById('layananKepuasanForm').reset();

      // Clear rating
      const ratingInput = document.getElementById('layanan_rating');
      ratingInput.value = '';
      const ratingButtons = document.querySelectorAll('.btn-rating');
      ratingButtons.forEach(btn => {
        const starIcon = btn.querySelector('i');
        starIcon.classList.remove('fas', 'fa-star');
        starIcon.classList.add('far', 'fa-star');
        btn.classList.remove('active');
      });

      // Clear validation
      document.querySelectorAll('#layananKepuasanForm .is-invalid').forEach(element => {
        element.classList.remove('is-invalid');
      });

      // Load provinces
      loadProvinces('layanan_provinsi');

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('layananKepuasanModal'));
      modal.show();
    }

    // Method untuk menghitung 7 hari kerja (Senin-Jumat) dan mengembalikan jumlah hari kalender
    function calculateWorkingDaysAsCalendarDays(workingDaysToAdd) {
      const startDate = new Date();
      const currentDate = new Date(startDate);
      let addedWorkingDays = 0;

      // Hitung maju sampai mendapatkan jumlah hari kerja yang dibutuhkan
      while (addedWorkingDays < workingDaysToAdd) {
        currentDate.setDate(currentDate.getDate() + 1);
        // Cek apakah hari ini weekday (Senin=1, Selasa=2, ..., Jumat=5)
        const dayOfWeek = currentDate.getDay();
        if (dayOfWeek > 0 && dayOfWeek < 6) { // 1=Monday, 5=Friday (0=Sunday, 6=Saturday)
          addedWorkingDays++;
        }
      }

      // Hitung selisih hari kalender antara tanggal awal dan tanggal akhir
      const diffTime = Math.abs(currentDate - startDate);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    }

    // Function to open sengketa modal
    function openSengketaModal(permohonanId, noPermohonan, judulDokumen) {
      document.getElementById('modal_sengketa_id_permohonan').value = permohonanId;
      document.getElementById('modal_sengketa_display_no_permohonan').value = noPermohonan;
      document.getElementById('modal_sengketa_display_judul_dokumen').value = judulDokumen;

      // Clear form
      document.getElementById('sengketaDecisionYa').checked = false;
      document.getElementById('sengketaDecisionTidak').checked = false;

      // Show modal
      const modal = new bootstrap.Modal(document.getElementById('sengketaModal'));
      modal.show();
    }
  </script>

  <!-- Modal Sengketa -->
  <div class="modal fade" id="sengketaModal" tabindex="-1" aria-labelledby="sengketaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="sengketaForm" method="POST" action="index.php?controller=permohonan&action=ajukanSengketa">
          <div class="modal-header">
            <h5 class="modal-title" id="sengketaModalLabel">
              <i class="fas fa-gavel"></i>
              Ajukan Sengketa Permohonan
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id_permohonan" id="modal_sengketa_id_permohonan" value="">

            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i>
              <strong>Informasi:</strong> Anda akan mengajukan sengketa untuk permohonan berikut:
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="modal_sengketa_display_no_permohonan" class="form-label">
                    <strong>Nomor Permohonan</strong>
                  </label>
                  <input type="text" class="form-control" id="modal_sengketa_display_no_permohonan" readonly
                         style="background-color: #f8f9fa; cursor: not-allowed;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="modal_sengketa_display_judul_dokumen" class="form-label">
                    <strong>Judul Dokumen</strong>
                  </label>
                  <input type="text" class="form-control" id="modal_sengketa_display_judul_dokumen" readonly
                         style="background-color: #f8f9fa; cursor: not-allowed;">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label class="form-label"><strong>Apakah Anda ingin mengajukan sengketa? <span class="text-danger">*</span></strong></label><br>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sengketa_decision" id="sengketaDecisionYa" value="ya" required>
                    <label class="form-check-label" for="sengketaDecisionYa">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sengketa_decision" id="sengketaDecisionTidak" value="tidak" required>
                    <label class="form-check-label" for="sengketaDecisionTidak">Tidak</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i>
              <strong>Perhatian:</strong> Jika Anda memilih "Ya", status permohonan akan diubah menjadi "sengketa".
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-times"></i>
              Batal
            </button>
            <button type="submit" class="btn btn-danger">
              <i class="fas fa-paper-plane"></i>
              Ajukan Sengketa
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
