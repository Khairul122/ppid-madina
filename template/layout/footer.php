<style>
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
</style>
<footer class="footer" data-aos="fade-in">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-right" data-aos-delay="100">
                <h5>PPID Madina</h5>
                <p><?= isset($data['kontak']['alamat']) ? htmlspecialchars($data['kontak']['alamat']) : 'Alamat Kantor PPID' ?></p>
                <p><i class="fas fa-phone me-2"></i> <?= isset($data['kontak']['telepon']) ? htmlspecialchars($data['kontak']['telepon']) : '(0635) 1234567' ?></p>
                <p><i class="fas fa-envelope me-2"></i> <?= isset($data['kontak']['email']) ? htmlspecialchars($data['kontak']['email']) : 'ppid@madinakab.go.id' ?></p>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <h5>Link Terkait</h5>
                <a href="index.php">Beranda</a>
                <a href="index.php?halaman=profil">Profil</a>
                <a href="index.php?halaman=layanan">Layanan Informasi</a>
                <a href="index.php?halaman=informasi">Daftar Informasi Publik</a>
                <a href="index.php?halaman=tata_kelola">Tata Kelola</a>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-left" data-aos-delay="300">
                <h5>Media Sosial</h5>
                <div class="social-links">
                    <?php if (isset($data['kontak']['media_sosial']) && is_array($data['kontak']['media_sosial'])): ?>
                        <?php foreach($data['kontak']['media_sosial'] as $social): ?>
                        <a href="<?= $social['url'] ?>" title="<?= $social['platform'] ?>" target="_blank"><i class="<?= $social['icon'] ?>"></i></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="#" title="Facebook" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="#" title="Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="YouTube" target="_blank"><i class="fab fa-youtube"></i></a>
                    <?php endif; ?>
                </div>
                <p class="mt-3">Jam Operasional:</p>
                <p>Senin - Kamis: <?= isset($data['kontak']['jam_operasional']['senin_kamis']) ? htmlspecialchars($data['kontak']['jam_operasional']['senin_kamis']) : '08.00 - 16.00 WIB' ?></p>
                <p>Jumat: <?= isset($data['kontak']['jam_operasional']['jumat']) ? htmlspecialchars($data['kontak']['jam_operasional']['jumat']) : '08.00 - 16.30 WIB' ?></p>
            </div>
        </div>
        <div class="copyright" data-aos="fade-in" data-aos-delay="500">
            <p>&copy; 2025 PPID Mandailing Natal. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>