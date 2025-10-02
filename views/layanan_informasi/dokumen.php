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
            --secondary-color: #64748b;
            --text-color: #1f2937;
            --muted-color: #6b7280;
            --light-bg: #f8f9fa;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            padding: 40px 0;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0;
            text-align: center;
        }

        .page-subtitle {
            color: rgba(255,255,255,0.9);
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
        }

        /* Content Section */
        .content-section {
            padding: 0 0 60px;
            background-color: white;
        }

        /* Filter and Search Section */
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-left: 40px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            height: 45px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-color);
        }

        .btn-search {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 30px;
            height: 45px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-search:hover {
            background: #1e40af;
            color: white;
        }

        /* Table Section */
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .badge-kategori {
            background: var(--secondary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-jenis {
            background: #f59e0b;
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-tipe {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-tipe.text {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-tipe.gambar {
            background: #fce7f3;
            color: #be185d;
        }

        .badge-tipe.video {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-tipe.audio {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-tipe.lainnya {
            background: #e5e7eb;
            color: #374151;
        }

        /* Action Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
        }

        .btn-download {
            background: var(--primary-color);
            color: white;
        }

        .btn-download:hover {
            background: #1e40af;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(30, 58, 138, 0.3);
        }

        .btn-preview {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-preview:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted-color);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h4 {
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--muted-color);
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            background: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #475569;
            color: white;
            transform: translateX(-5px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 800px;
            }

            .filter-section {
                padding: 20px;
            }

            .btn-action {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.25rem;
            }

            .filter-section {
                padding: 15px;
            }
        }

        /* Dropdown Filter */
        .dropdown-filter {
            position: relative;
        }

        .dropdown-filter select {
            height: 45px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0 15px;
            font-size: 0.9rem;
        }

        .dropdown-filter select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Daftar Dokumen <?php echo htmlspecialchars($data['nama_kategori']); ?></h1>
            <?php if (!empty($data['nama_jenis'])): ?>
                <p class="page-subtitle"><?php echo htmlspecialchars($data['nama_jenis']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Beranda
            </a>

            <!-- Filter and Search Section -->
            <div class="filter-section" data-aos="fade-up">
                <div class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter dan Pencarian Dokumen
                </div>
                <form method="GET" action="index.php">
                    <input type="hidden" name="controller" value="dokumen">
                    <input type="hidden" name="action" value="index">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark mb-2">Kategori</label>
                            <div class="dropdown-filter">
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
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-dark mb-2">Cari Dokumen</label>
                            <div class="input-group">
                                <div class="search-box flex-grow-1">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Cari berdasarkan judul, kandungan informasi, atau terbitkan sebagai..."
                                           value="<?php echo htmlspecialchars($data['keyword']); ?>">
                                </div>
                                <button type="submit" class="btn btn-search">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-container" data-aos="fade-up">
                <?php if (!empty($data['dokumenList'])): ?>
                    <table class="table">
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
                                    <td><?php echo htmlspecialchars($dokumen['terbitkan_sebagai']); ?></td>
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
                                        <div class="d-flex gap-1 flex-column">
                                            <?php if (!empty($dokumen['upload_file']) && file_exists($dokumen['upload_file'])): ?>
                                                <a href="<?php echo htmlspecialchars($dokumen['upload_file']); ?>"
                                                   target="_blank"
                                                   class="btn-action btn-preview"
                                                   title="Preview">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo htmlspecialchars($dokumen['upload_file']); ?>"
                                                   download
                                                   class="btn-action btn-download"
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.8rem;">Tidak ada file</span>
                                            <?php endif; ?>
                                        </div>
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
        </div>
    </section>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
