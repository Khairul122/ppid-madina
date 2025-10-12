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
                  <h3 class="font-weight-bold mb-0">Detail Pemohon</h3>
                  <h6 class="mb-0">Informasi lengkap pemohon permohonan informasi publik</h6>
                </div>
                <div class="header-right d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <a href="index.php?controller=permohonanadmin&action=dataPemohonIndex" class="btn btn-outline-primary">
                      <i class="ti-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Section -->
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3 text-center mb-4">
                      <div class="profile-image-container mx-auto mb-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                          <i class="fas fa-user fa-5x text-muted"></i>
                        </div>
                      </div>
                      <h4 class="font-weight-bold"><?php echo htmlspecialchars($pemohon['nama_lengkap'] ?? ''); ?></h4>
                      <p class="text-muted mb-1">Pemohon Informasi</p>
                      <span class="badge bg-primary">ID: <?php echo htmlspecialchars($pemohon['id_biodata'] ?? ''); ?></span>
                    </div>
                    
                    <div class="col-md-9">
                      <h4 class="card-title mb-4"><i class="ti-id-badge me-2"></i>Informasi Identitas</h4>
                      
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">NIK:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['nik'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Nama Lengkap:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['nama_lengkap'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Jenis Kelamin:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['jenis_kelamin'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Usia:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['usia'] ?? '-') . ' tahun'; ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Pendidikan:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['pendidikan'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Pekerjaan:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['pekerjaan'] ?? '-'); ?></p>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Email:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['email'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Telepon:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['no_kontak'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Provinsi:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['provinsi'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Kota:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['city'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Alamat:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['alamat'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Status Pengguna:</label>
                            <div class="col-sm-8">
                              <span class="badge bg-info"><?php echo htmlspecialchars($pemohon['status_pengguna'] ?? '-'); ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-md-12">
                      <h4 class="card-title mb-4"><i class="ti-user me-2"></i>Informasi Akun</h4>
                      
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Username:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['username'] ?? '-'); ?></p>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Email Akun:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['user_email'] ?? '-'); ?></p>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Role:</label>
                            <div class="col-sm-8">
                              <span class="badge bg-success"><?php echo htmlspecialchars($pemohon['role'] ?? '-'); ?></span>
                            </div>
                          </div>
                          
                          <div class="form-group row mb-3">
                            <label class="col-sm-4 col-form-label fw-bold">Nama Lembaga:</label>
                            <div class="col-sm-8">
                              <p class="col-form-label"><?php echo htmlspecialchars($pemohon['nama_lembaga'] ?? '-'); ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="d-flex justify-content-between mt-4 pt-4 border-top">
                    <a href="index.php?controller=permohonanadmin&action=dataPemohonIndex" class="btn btn-outline-primary">
                      <i class="ti-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    <button class="btn btn-primary" disabled>
                      <i class="ti-printer me-2"></i>Cetak Profil
                    </button>
                  </div>
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
    }

    .card-body {
      padding: 2.5rem;
    }

    .card-title {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--gov-border);
    }

    .card-title i {
      font-size: 1.5rem;
      color: var(--secondary-color);
    }

    /* Profile Section */
    .profile-image-container {
      border: 4px solid var(--primary-color);
      border-radius: 50%;
      padding: 15px;
      background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
      display: inline-block;
      box-shadow: 0 8px 24px rgba(30, 58, 138, 0.15);
    }

    .profile-image-container .bg-light {
      background: white !important;
    }

    /* Form Group Styling */
    .form-group.row {
      margin-bottom: 1.25rem;
      padding: 0.75rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .form-group.row:hover {
      background-color: rgba(30, 58, 138, 0.02);
    }

    .form-label,
    .col-form-label.fw-bold {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .col-form-label {
      color: var(--text-color);
      font-size: 0.95rem;
      font-weight: 500;
    }

    /* Profile Header */
    .text-center h4 {
      color: var(--text-color);
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .text-center p.text-muted {
      color: var(--muted-color);
      font-size: 0.9rem;
      margin-bottom: 1rem;
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

    .btn-primary:hover:not(:disabled) {
      background-color: #152a68;
      border-color: #152a68;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn-primary:disabled {
      background-color: var(--muted-color);
      border-color: var(--muted-color);
      opacity: 0.6;
      cursor: not-allowed;
    }

    .btn-outline-primary {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      background: white;
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    .btn i {
      font-size: 1rem;
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

    .badge.bg-success {
      background-color: var(--success-color) !important;
    }

    /* Section Divider */
    .border-top {
      border-top: 2px solid var(--gov-border) !important;
      margin-top: 2rem;
      padding-top: 2rem;
    }

    /* Info Rows */
    .row.mt-4 {
      background-color: rgba(248, 249, 250, 0.5);
      padding: 2rem;
      border-radius: 12px;
      margin-top: 2rem !important;
      border-left: 4px solid var(--secondary-color);
    }

    /* Empty State for No Data */
    .col-form-label:empty::before {
      content: '-';
      color: var(--muted-color);
      opacity: 0.5;
    }

    /* Profile Stats */
    .text-center .badge {
      font-size: 0.9rem;
      padding: 0.6rem 1.2rem;
      margin-top: 0.5rem;
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
        padding: 2rem;
      }

      .profile-image-container {
        width: 120px !important;
        height: 120px !important;
      }

      .profile-image-container i {
        font-size: 3.5rem !important;
      }
    }

    @media (max-width: 768px) {
      .page-header {
        padding: 1.25rem;
      }

      .card-body {
        padding: 1.5rem;
      }

      .btn {
        padding: 0.65rem 1.25rem;
        font-size: 0.9rem;
      }

      .form-group.row {
        margin-bottom: 1rem;
        padding: 0.5rem;
      }

      .col-sm-4,
      .col-sm-8 {
        flex: 0 0 100%;
        max-width: 100%;
      }

      .form-label,
      .col-form-label.fw-bold {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
      }

      .col-form-label {
        font-size: 0.9rem;
        padding-left: 0 !important;
      }

      .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
      }

      .d-flex.justify-content-between .btn {
        width: 100%;
        justify-content: center;
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

      .profile-image-container {
        width: 100px !important;
        height: 100px !important;
        padding: 10px;
        border-width: 3px;
      }

      .profile-image-container i {
        font-size: 3rem !important;
      }

      .text-center h4 {
        font-size: 1.25rem;
      }

      .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
      }

      .row.mt-4 {
        padding: 1.25rem;
      }
    }

    /* Animation */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card {
      animation: fadeIn 0.5s ease-out;
    }

    .form-group.row {
      animation: fadeIn 0.6s ease-out;
    }

    /* Print Styles */
    @media print {
      .page-header,
      .btn {
        display: none;
      }

      .card {
        box-shadow: none;
        border: 1px solid #ddd;
      }
    }
  </style>
</body>
</html>