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
                                <?php echo htmlspecialchars($data['profile']['keterangan']); ?>
                            </h2>
                        </div>

                        <div class="content-body">
                            <?php 
                            $content = $data['profile']['isi'];
                            $category = strtolower($data['profile']['nama_kategori']);
                            
                            // Check if content is a PDF file
                            $is_pdf = (pathinfo($content, PATHINFO_EXTENSION) === 'pdf' && file_exists($content));
                            
                            if ($is_pdf) {
                                // Display PDF preview link
                                echo '<div class="pdf-content text-center py-5">';
                                echo '<div class="pdf-icon mb-4">';
                                echo '<i class="fas fa-file-pdf fa-5x text-danger"></i>';
                                echo '</div>';
                                echo '<h4 class="text-primary mb-3">Dokumen PDF</h4>';
                                echo '<p class="text-muted">Klik tombol di bawah untuk melihat atau mengunduh file PDF</p>';
                                echo '<a href="' . $content . '" target="_blank" class="btn btn-primary btn-lg">';
                                echo '<i class="fas fa-file-pdf me-2"></i>Klik Disini';
                                echo '</a>';
                                echo '<div class="mt-3">';
                                echo '<a href="' . $content . '" class="btn btn-outline-secondary" download>';
                                echo '<i class="fas fa-download me-2"></i>Unduh File';
                                echo '</a>';
                                echo '</div>';
                                echo '</div>';
                            } else {
                                // Clean and professional content display for text content
                                switch($category) {
                                    case 'sejarah':
                                    case 'profil':
                                        echo '<div class="profile-content">';
                                        echo '<div class="row align-items-center mb-5">';
                                        echo '<div class="col-lg-5 mb-4 mb-lg-0">';
                                        echo '<div class="bg-light p-4 rounded">';
                                        echo '<img src="ppid_assets/images/profil-kabupaten.jpg" alt="Profile" class="img-fluid rounded shadow-sm">';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="col-lg-7">';
                                        echo '<h3 class="text-primary mb-4">Tentang Kabupaten Mandailing Natal</h3>';
                                        echo htmlspecialchars_decode($content);
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        break;
                                        
                                    case 'visi dan misi':
                                        // Parse content for vision and mission
                                        $lines = explode("\n", $content);
                                        $visi = '';
                                        $missions = [];
                                        
                                        foreach($lines as $line) {
                                            $line = trim($line);
                                            if(stripos($line, 'visi:') === 0) {
                                                $visi = substr($line, 5);
                                            } elseif(stripos($line, 'misi:') === 0) {
                                                $missions[] = substr($line, 5);
                                            } elseif(!empty($line)) {
                                                $missions[] = $line;
                                            }
                                        }
                                        
                                        echo '<div class="vision-mission-content">';
                                        if(!empty($visi)) {
                                            echo '<div class="mb-5">';
                                            echo '<h3 class="text-primary mb-4"><i class="fas fa-eye me-2"></i>Visi</h3>';
                                            echo '<div class="bg-light p-4 rounded">';
                                            echo '<p class="lead text-center mb-0">' . htmlspecialchars($visi) . '</p>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        
                                        if(!empty($missions)) {
                                            echo '<div>';
                                            echo '<h3 class="text-primary mb-4"><i class="fas fa-bullseye me-2"></i>Misi</h3>';
                                            echo '<ul class="list-group list-group-flush">';
                                            foreach($missions as $index => $mission) {
                                                if(!empty(trim($mission))) {
                                                    echo '<li class="list-group-item border-0 px-0 pb-3">';
                                                    echo '<div class="d-flex align-items-start">';
                                                    echo '<span class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px;">' . ($index + 1) . '</span>';
                                                    echo '<div>' . htmlspecialchars($mission) . '</div>';
                                                    echo '</div>';
                                                    echo '</li>';
                                                }
                                            }
                                            echo '</ul>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        break;
                                        
                                    case 'struktur organisasi':
                                        echo '<div class="org-structure-content text-center mb-5">';
                                        echo '<img src="ppid_assets/images/struktur-organisasi.png" alt="Struktur Organisasi" class="img-fluid rounded border shadow-sm" style="max-width: 100%; height: auto;">';
                                        echo '</div>';
                                        echo '<div class="org-description">';
                                        echo '<h3 class="text-primary mb-4">Deskripsi Struktur Organisasi</h3>';
                                        echo htmlspecialchars_decode($content);
                                        echo '</div>';
                                        break;
                                        
                                    case 'tugas dan fungsi':
                                        $items = preg_split('/\n\s*\n|\r\s*\r|\n\r\s*\n\r/', $content);
                                        echo '<div class="duties-functions-content">';
                                        echo '<div class="row g-4">';
                                        
                                        $item_count = 0;
                                        foreach($items as $item) {
                                            $item = trim($item);
                                            if(!empty($item)) {
                                                echo '<div class="col-md-6 mb-4">';
                                                echo '<div class="d-flex">';
                                                echo '<div class="me-3 mt-1">';
                                                echo '<span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="min-width: 30px; height: 30px;">' . ($item_count + 1) . '</span>';
                                                echo '</div>';
                                                echo '<div>';
                                                echo '<h5 class="text-primary">Tugas dan Fungsi ' . ($item_count + 1) . '</h5>';
                                                echo '<p>' . htmlspecialchars($item) . '</p>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                                $item_count++;
                                            }
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                        break;
                                        
                                    case 'dasar hukum':
                                        $items = explode("\n", $content);
                                        echo '<div class="legal-basis-content">';
                                        echo '<h3 class="text-primary mb-4">Dasar Hukum</h3>';
                                        echo '<div class="table-responsive">';
                                        echo '<table class="table table-hover">';
                                        echo '<tbody>';
                                        $item_count = 0;
                                        foreach($items as $item) {
                                            $item = trim($item);
                                            if(!empty($item)) {
                                                echo '<tr>';
                                                echo '<td class="border-0 pb-3 ps-0">' . ($item_count + 1) . '.</td>';
                                                echo '<td class="border-0 pb-3">' . htmlspecialchars($item) . '</td>';
                                                echo '</tr>';
                                                $item_count++;
                                            }
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                        echo '</div>';
                                        break;
                                        
                                    case 'alamat kantor':
                                        $lines = explode("\n", $content);
                                        echo '<div class="office-info-content">';
                                        echo '<div class="row g-4">';
                                        echo '<div class="col-md-8">';
                                        echo '<h3 class="text-primary mb-4">Alamat Kantor</h3>';
                                        echo '<div class="bg-light p-4 rounded">';
                                        foreach($lines as $line) {
                                            $line = trim($line);
                                            if(!empty($line)) {
                                                echo '<p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> ' . htmlspecialchars($line) . '</p>';
                                            }
                                        }
                                        echo '</div>';
                                        echo '</div>';
                                        echo '<div class="col-md-4">';
                                        echo '<div class="bg-primary text-white p-4 rounded h-100 d-flex flex-column">';
                                        echo '<h4 class="mb-3"><i class="fas fa-directions me-2"></i> Lokasi</h4>';
                                        echo '<p class="flex-grow-1">Kantor PPID Kabupaten Mandailing Natal dapat diakses melalui berbagai jalur transportasi umum.</p>';
                                        echo '<a href="#" class="btn btn-light text-primary mt-auto">Lihat di Peta</a>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        break;
                                        
                                    default:
                                        echo '<div class="default-content">';
                                        // Replace URL text in links with "Klik Disini"
                                        $content_decoded = htmlspecialchars_decode($content);
                                        // Pattern to match links with URL as text
                                        $content_decoded = preg_replace(
                                            '/<a([^>]*href=["\']([^"\']*\.pdf)["\'][^>]*)>https?:\/\/[^<]+<\/a>/i',
                                            '<a$1>Klik Disini</a>',
                                            $content_decoded
                                        );
                                        echo $content_decoded;
                                        echo '</div>';
                                }
                            }
                            ?>
                        </div>

                        <div class="content-footer">
                            <a href="index.php" class="back-button">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Beranda
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
