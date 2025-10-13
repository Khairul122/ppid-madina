<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($data['title']); ?> - PPID Mandailing Natal</title>

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #f59e0b;
            --accent-color: #fbbf24;
            --text-color: #1f2937;
            --muted-color: #6b7280;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
        }

        /* Header Styles */
        .top-info-bar {
            background-color: #e5e7eb;
            padding: 8px 0;
            font-size: 13px;
            color: var(--muted-color);
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
            color: var(--muted-color);
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

        .navbar-custom {
            background: #000000;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-navbar {
            padding: 12px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: 600;
            text-decoration: none;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            background: white;
            border-radius: 8px;
        }

        .logo-img img {
            width: 35px;
            height: 35px;
        }

        .navbar-nav {
            display: flex;
            flex-direction: row;
            gap: 8px;
            align-items: center;
            margin-left: auto;
        }

        .navbar-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 11px;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .navbar-nav a:hover {
            color: #ddd;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-social {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: 15px;
            padding-left: 15px;
            border-left: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-social a {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 14px;
            padding: 0;
            color: white;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* Breadcrumb Section */
        .breadcrumb-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            padding: 30px 0;
            margin-top: 0;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            background: transparent;
            padding: 0;
            margin: 0;
            color: white;
        }

        .breadcrumb-custom a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb-custom a:hover {
            color: white;
        }

        .breadcrumb-custom .active {
            color: white;
            font-weight: 500;
        }

        .breadcrumb-custom i {
            color: rgba(255,255,255,0.7);
        }

        /* Content Section */
        .content-section {
            padding: 60px 0 80px;
            background-color: #ffffff;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            color: var(--primary-color);
            position: relative;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .content-header {
            background: white;
            padding: 40px;
            border-bottom: 3px solid var(--primary-color);
        }

        .content-category {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .content-title {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
            color: var(--text-color);
            text-align: center;
        }

        .content-body {
            padding: 40px;
            color: var(--text-color);
            line-height: 1.8;
            font-size: 16px;
        }

        /* Content Body Styling */
        .content-body h1,
        .content-body h2,
        .content-body h3,
        .content-body h4 {
            color: var(--primary-color);
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .content-body h1 { font-size: 28px; }
        .content-body h2 { font-size: 24px; }
        .content-body h3 { font-size: 20px; }
        .content-body h4 { font-size: 18px; }

        .content-body p {
            margin-bottom: 20px;
            text-align: justify;
        }

        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 25px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .content-body ul,
        .content-body ol {
            margin: 20px 0;
            padding-left: 40px;
        }

        .content-body li {
            margin-bottom: 12px;
        }

        .content-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .content-body table th {
            background: var(--primary-color);
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }

        .content-body table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .content-body table tr:last-child td {
            border-bottom: none;
        }

        .content-body table tr:hover {
            background: #f7fafc;
        }

        .content-body blockquote {
            border-left: 4px solid var(--primary-color);
            background: var(--light-bg);
            padding: 20px 25px;
            margin: 25px 0;
            border-radius: 8px;
            font-style: italic;
            color: var(--muted-color);
        }

        .content-body a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .content-body a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Content Footer */
        .content-footer {
            padding: 25px 40px;
            background: var(--light-bg);
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .content-meta {
            display: flex;
            gap: 25px;
            color: var(--muted-color);
            font-size: 14px;
            flex-wrap: wrap;
        }

        .content-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .content-meta-item i {
            color: var(--primary-color);
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            background: blue;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-left: 40px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-color);
        }

        .btn-search {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-search:hover {
            background: #d97706;
            color: white;
        }

        /* Badges */
        .badge-kategori {
            background: #64748b;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-jenis {
            background: var(--secondary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-tipe {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-tipe.text { background: #dbeafe; color: #1e40af; }
        .badge-tipe.gambar { background: #fce7f3; color: #be185d; }
        .badge-tipe.video { background: #dcfce7; color: #15803d; }
        .badge-tipe.audio { background: #fef3c7; color: #92400e; }
        .badge-tipe.lainnya { background: #e5e7eb; color: #374151; }

        /* Action Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-download {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-download:hover {
            background: #1e40af;
            color: white;
        }

        .btn-preview {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-preview:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
            color: #9ca3af;
        }

        .empty-state h4 {
            color: var(--text-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state p {
            color: var(--muted-color);
        }

        /* Footer */
        .footer {
            background: rgb(0, 0, 0);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .footer a {
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 10px;
            display: block;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #cbd5e1;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .top-info-bar {
                display: none;
            }

            .navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #000000;
                flex-direction: column;
                gap: 0;
                padding: 10px 0;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                margin-left: 0;
            }

            .navbar-nav.show {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.8rem;
            }

            .content-title {
                font-size: 24px;
            }

            .content-body {
                padding: 25px 20px;
                font-size: 15px;
            }

            .content-header {
                padding: 25px 20px;
            }

            .content-footer {
                padding: 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .content-meta {
                flex-direction: column;
                gap: 12px;
            }

            .breadcrumb-custom {
                font-size: 14px;
                flex-wrap: wrap;
            }

            .content-body table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Content Section -->
    <section class="content-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card content-card">
                        <div class="content-header">
                            <h2 class="content-title">
                                Daftar Dokumen <?php echo htmlspecialchars($data['nama_kategori']); ?>
                            </h2>
                        </div>

                        <div class="content-body">
                            <!-- Filter Form -->
                            <form method="GET" action="index.php" class="mb-4">
                                <input type="hidden" name="controller" value="dokumen">
                                <input type="hidden" name="action" value="index">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">
                                            <i class="fas fa-filter me-2"></i>Filter Kategori
                                        </label>
                                        <select name="kategori" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- Semua Kategori --</option>
                                            <?php foreach ($data['kategoriList'] as $kategori): ?>
                                                <option value="<?php echo $kategori['id_kategori']; ?>"
                                                        <?php echo ($data['id_kategori'] == $kategori['id_kategori']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">
                                            <i class="fas fa-search me-2"></i>Pencarian Dokumen
                                        </label>
                                        <div class="row g-2">
                                            <div class="col-md-8">
                                                <div class="search-box">
                                                    <i class="fas fa-search"></i>
                                                    <input type="text" name="search" class="form-control"
                                                           placeholder="Cari berdasarkan judul, kandungan informasi..."
                                                           value="<?php echo htmlspecialchars($data['keyword']); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn-search">
                                                    <i class="fas fa-search me-2"></i>Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <?php if (!empty($data['dokumenList'])): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 25%;">Judul Dokumen</th>
                                            <th style="width: 20%;">Kandungan Informasi</th>
                                            <th style="width: 15%;">Terbitkan Sebagai</th>
                                            <th style="width: 10%;">Kategori</th>
                                            <th style="width: 10%;">Jenis</th>
                                            <th style="width: 8%;">Tipe</th>
                                            <th style="width: 7%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($data['dokumenList'] as $dokumen): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($dokumen['judul']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="far fa-calendar-alt"></i>
                                                        <?php echo date('d M Y', strtotime($dokumen['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td><?php echo htmlspecialchars(substr($dokumen['kandungan_informasi'], 0, 100)) . (strlen($dokumen['kandungan_informasi']) > 100 ? '...' : ''); ?></td>
                                                <td><?php echo isset($dokumen['terbitkan_sebagai']) ? htmlspecialchars($dokumen['terbitkan_sebagai']) : '-'; ?></td>
                                                <td>
                                                    <span class="badge-kategori">
                                                        <?php echo htmlspecialchars($dokumen['nama_kategori']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($dokumen['nama_jenis'])): ?>
                                                        <span class="badge-jenis">
                                                            <?php echo htmlspecialchars($dokumen['nama_jenis']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge-tipe <?php echo $dokumen['tipe_file']; ?>">
                                                        <?php echo ucfirst($dokumen['tipe_file']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($dokumen['upload_file']) && file_exists($dokumen['upload_file'])): ?>
                                                        <a href="<?php echo htmlspecialchars($dokumen['upload_file']); ?>"
                                                           target="_blank"
                                                           class="btn-action btn-preview mb-1"
                                                           title="Preview">
                                                            <i class="fas fa-eye"></i> Preview
                                                        </a>
                                                        <br>
                                                        <a href="<?php echo htmlspecialchars($dokumen['upload_file']); ?>"
                                                           download
                                                           class="btn-action btn-download"
                                                           title="Download">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted" style="font-size: 12px;">Tidak ada file</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <h4>Tidak Ada Dokumen</h4>
                                    <p>Belum ada dokumen yang dipublikasikan untuk kategori ini.</p>
                                    <?php if (!empty($data['keyword'])): ?>
                                        <p class="mt-3">
                                            <a href="index.php?controller=dokumen&action=index&kategori=<?php echo $data['id_kategori']; ?>" class="btn-action btn-download">
                                                <i class="fas fa-redo"></i> Reset Pencarian
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="content-footer">
                            <a href="index.php" class="back-button">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Berand
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100
        });

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.getElementById('navLinks');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    navLinks.classList.toggle('show');
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (navLinks && !navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                    navLinks.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
