<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageInfo['title'] ?? 'FAQ - PPID Mandailing Natal' ?></title>
    <meta name="description" content="<?= $pageInfo['description'] ?? 'Frequently Asked Questions (FAQ) - Pertanyaan yang Sering Diajukan' ?>">

    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            color: var(--text-color);
        }

        /* Navbar */
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

        /* Breadcrumb */
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
            color: var(--muted-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            padding: 60px 0;
            min-height: calc(100vh - 200px);
            background-color: var(--light-bg);
        }

        /* FAQ Header */
        .faq-header {
            text-align: center;
            margin-bottom: 50px;
            padding: 0 20px;
        }

        .faq-title {
            font-size: 42px;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .faq-subtitle {
            font-size: 18px;
            color: var(--muted-color);
            max-width: 800px;
            margin: 0 auto;
        }

        /* FAQ Container */
        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* FAQ Content Card */
        .faq-content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 40px;
            margin-bottom: 30px;
        }

        .faq-content {
            font-size: 16px;
            line-height: 1.8;
            color: #374151;
        }

        .faq-content h1,
        .faq-content h2,
        .faq-content h3,
        .faq-content h4,
        .faq-content h5,
        .faq-content h6 {
            color: var(--text-color);
            font-weight: 700;
            margin-top: 1.5em;
            margin-bottom: 0.75em;
        }

        .faq-content h2 {
            font-size: 28px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-top: 2em;
        }

        .faq-content h3 {
            font-size: 22px;
        }

        .faq-content p {
            margin-bottom: 1.25em;
        }

        .faq-content ul,
        .faq-content ol {
            margin-left: 25px;
            margin-bottom: 1.25em;
            padding-left: 10px;
        }

        .faq-content li {
            margin-bottom: 0.5em;
        }

        .faq-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .faq-content a {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .faq-content a:hover {
            color: #1e40af;
        }

        .faq-content blockquote {
            border-left: 4px solid var(--secondary-color);
            padding-left: 20px;
            margin: 1.5em 0;
            font-style: italic;
            color: var(--muted-color);
        }

        .faq-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5em 0;
        }

        .faq-content table th,
        .faq-content table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }

        .faq-content table th {
            background-color: #f3f4f6;
            font-weight: 600;
        }

        /* FAQ Meta */
        .faq-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 14px;
            color: var(--muted-color);
            flex-wrap: wrap;
        }

        .faq-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .faq-meta-item i {
            color: var(--primary-color);
        }

        .faq-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 15px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 100px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: #d1d5db;
            margin-bottom: 25px;
        }

        .empty-state h3 {
            color: var(--muted-color);
            font-size: 24px;
            margin-bottom: 15px;
        }

        .empty-state p {
            color: #9ca3af;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .faq-header {
                padding: 0 15px;
            }

            .faq-title {
                font-size: 32px;
            }

            .faq-subtitle {
                font-size: 16px;
            }

            .faq-content-card {
                padding: 25px 20px;
            }

            .faq-content {
                font-size: 15px;
            }

            .faq-content h2 {
                font-size: 24px;
            }

            .faq-content h3 {
                font-size: 20px;
            }

            .faq-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 40px 0;
            }

            .faq-title {
                font-size: 28px;
            }

            .faq-content-card {
                padding: 20px 15px;
            }

            .faq-content {
                font-size: 14px;
            }

            .faq-content h2 {
                font-size: 22px;
            }

            .faq-content h3 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <?php include 'template/layout/navbar_beranda.php'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Header -->
            <div class="faq-header">
                <h1 class="faq-title">Frequently Asked Questions</h1>
                <p class="faq-subtitle">
                    Pertanyaan yang Sering Diajukan seputar layanan PPID Kabupaten Mandailing Natal
                </p>
            </div>

            <?php
            // Get single FAQ
            $faq = $this->faqModel->getSingleFAQ();

            if ($faq && !empty($faq['isi'])):
            ?>
                <div class="faq-container">
                    <!-- FAQ Content -->
                    <div class="faq-content-card">
                        <div class="faq-content">
                            <?= htmlspecialchars_decode($faq['isi']) ?>
                        </div>

                        <!-- FAQ Meta Information -->
                        <div class="faq-meta">
                            <?php if (!empty($faq['penulis'])): ?>
                                <div class="faq-meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>Oleh: <strong><?= htmlspecialchars($faq['penulis']) ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($faq['updated_at'])): ?>
                                <div class="faq-meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Terakhir diperbarui:
                                        <?php
                                        $date = new DateTime($faq['updated_at']);
                                        echo $date->format('d F Y');
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($faq['tags'])): ?>
                                <div class="faq-meta-item">
                                    <?php
                                    $tags = explode(',', $faq['tags']);
                                    foreach ($tags as $tag):
                                        $tag = trim($tag);
                                        if (!empty($tag)):
                                    ?>
                                        <span class="faq-tag">
                                            <i class="fas fa-tag"></i>
                                            <?= htmlspecialchars($tag) ?>
                                        </span>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h3>Belum Ada FAQ</h3>
                    <p>Konten FAQ akan ditampilkan di sini setelah admin melakukan pengaturan</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'template/layout/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
