-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2025 at 07:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppid_mandailing`
--

-- --------------------------------------------------------

--
-- Table structure for table `benner`
--

CREATE TABLE `benner` (
  `id_banner` int NOT NULL,
  `upload` varchar(512) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `teks` text COLLATE utf8mb3_swedish_ci,
  `urutan` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `benner`
--

INSERT INTO `benner` (`id_banner`, `upload`, `judul`, `teks`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 'uploads/banner/1759104242_68d9ccf25c3b5.png', '', '', 1, '2025-09-29 07:04:02', '2025-09-29 07:04:02'),
(2, 'uploads/banner/1759105815_68d9d31737429.png', '', '', 2, '2025-09-29 07:30:15', '2025-09-29 07:30:15'),
(3, 'uploads/banner/1759105822_68d9d31e27579.png', '', '', 3, '2025-09-29 07:30:22', '2025-09-29 07:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb3_swedish_ci,
  `image` text CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul`, `url`, `summary`, `image`, `created_at`, `updated_at`) VALUES
(2, 'Jenguk Korban Kebakaran, Wabup Madina Pastikan Fasilitasi Administrasi Kependudukan ', '', 'Diskominfo, Panyabungan - Wakil Bupati Mandailing Natal (Madina) Atika Azmi Utammi Nasution menjenguk keluarga korban kebakaran di Desa Hutagodang Muda, Kecamatan Siabu, pada Jumat, 26 September 2025, dengan membawa bantuan sandang dan pangan.\r\nWabup Atika yang hadir bersama Kepala Badan Penanggulangan Bencana Daerah (BPBD) Mukhsin Nasution dan Kepala Dinas PUPR Elpi Yanti Harahap menyampaikan, dia mengetahui adanya kebakaran itu sekitar pukul 04.00 WIB atau beberapa saat usai kejadian.\r\n\"Musibah ini sesuatu yang tidak disangka-sangka dan tidak diduga,\" kata dia sebelum menyerahkan bantuan sementara itu.\r\nPemerintah, lanjut dia, akan memfasilitasi pembuatan ulang administrasi kependudukan milik korban. Termasuk pembuatan surat pengganti ijazah. \"Ini ada Pak Camat dan Pak Kades, koordinasi dengan mereka dan saya akan pantau langsung,\" kata dia.\r\nUntuk bantuan pembangunan rumah, Wabup Atika menjelaskan Dinas Sosial akan terlebih dahulu melakukan survei dan verifikasi data. \"Nanti kalau sudah', 'uploads/berita/1759033602_68d8b902388d5.jpg', '2025-09-28 11:26:42', '2025-09-28 11:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `biodata_pengguna`
--

CREATE TABLE `biodata_pengguna` (
  `id_biodata` int NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb3_swedish_ci NOT NULL,
  `alamat` text COLLATE utf8mb3_swedish_ci,
  `provinsi` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `usia` int DEFAULT NULL,
  `pendidikan` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `pekerjaan` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `no_kontak` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `foto_profile` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status_pengguna` enum('pribadi','lembaga') COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_lembaga` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `upload_ktp` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `upload_akta` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `biodata_pengguna`
--

INSERT INTO `biodata_pengguna` (`id_biodata`, `nama_lengkap`, `nik`, `alamat`, `provinsi`, `city`, `jenis_kelamin`, `usia`, `pendidikan`, `pekerjaan`, `no_kontak`, `email`, `foto_profile`, `status_pengguna`, `nama_lembaga`, `upload_ktp`, `upload_akta`) VALUES
(5, 'Khairul Huda', '1377020610010009', 'Lhoksuemawe\r\nBlang Pulo', 'ACEH', 'KOTA LHOKSEUMAWE', 'Laki-laki', 56, 'S2', 'TNI', '082165443677', 'khairulhuda242@gmail.com', 'uploads/1377020610010009_foto_profile_1759057010.png', 'lembaga', 'TNI AD', 'uploads/1377020610010009_upload_ktp_1759057010.png', 'uploads/1377020610010009_upload_akta_1759057010.png'),
(6, 'fatimah zahro', '1213015008950005', 'dalan lidang,panyabungan', 'SUMATERA UTARA', 'KABUPATEN MANDAILING NATAL', 'Perempuan', 20, 'SMA/SMK', 'mahasiswa', '081260842677', 'fatimahzahro1008@gmail.com', NULL, 'pribadi', NULL, 'uploads/ktp_1759117518_68da00cec0471.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pemda`
--

CREATE TABLE `dokumen_pemda` (
  `id_dokumen_pemda` int NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_kategori` int NOT NULL,
  `area` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL DEFAULT 'pemda',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `dokumen_pemda`
--

INSERT INTO `dokumen_pemda` (`id_dokumen_pemda`, `nama_jenis`, `id_kategori`, `area`, `created_at`, `updated_at`) VALUES
(1, 'ABCD', 1, 'pemda', '2025-09-29 06:57:20', '2025-09-29 06:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Berkala', '2025-09-29 03:56:13', '2025-09-29 03:56:13'),
(2, 'Serta Merta', '2025-09-29 03:56:23', '2025-09-29 03:56:23'),
(3, 'Setiap Saat', '2025-09-29 03:56:29', '2025-09-29 03:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `keberatan`
--

CREATE TABLE `keberatan` (
  `id_keberatan` int NOT NULL,
  `id_permohonan` int NOT NULL,
  `id_users` int NOT NULL,
  `alasan_keberatan` text COLLATE utf8mb3_swedish_ci,
  `keterangan` text COLLATE utf8mb3_swedish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permohonan`
--

CREATE TABLE `permohonan` (
  `id_permohonan` int NOT NULL,
  `id_user` int NOT NULL,
  `no_permohonan` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `sisa_jatuh_tempo` int DEFAULT NULL,
  `tujuan_permohonan` text COLLATE utf8mb3_swedish_ci,
  `komponen_tujuan` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `judul_dokumen` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `kandungan_informasi` text COLLATE utf8mb3_swedish_ci,
  `tujuan_penggunaan_informasi` text COLLATE utf8mb3_swedish_ci,
  `upload_foto_identitas` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `upload_data_pedukung` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT 'Diproses\r\n',
  `sumber_media` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `catatan_petugas` text COLLATE utf8mb3_swedish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `permohonan`
--

INSERT INTO `permohonan` (`id_permohonan`, `id_user`, `no_permohonan`, `sisa_jatuh_tempo`, `tujuan_permohonan`, `komponen_tujuan`, `judul_dokumen`, `kandungan_informasi`, `tujuan_penggunaan_informasi`, `upload_foto_identitas`, `upload_data_pedukung`, `status`, `sumber_media`, `catatan_petugas`, `created_at`, `updated_at`) VALUES
(2, 10, 'PMH000001', 18, 'Kementerian Dalam Negeri', 'Biro Organisasi dan Tatalaksana', 'ABCD', 'ABCD', 'ABCD', 'uploads/1377020610010009_upload_foto_identitas_1759057010.png', 'uploads/1377020610010009_upload_data_pedukung_1759057010.png', 'Disposisi', 'Website', NULL, '2025-09-28 10:56:50', '2025-09-28 14:44:53'),
(3, 11, 'PMH2025090001', NULL, 'untuk permintaan data', 'Dinas Pekerjaan Umum', 'permintaan data', NULL, 'untuk melengkapi data', 'uploads/1213015008950005_identitas_1759117653.jpg', 'uploads/1213015008950005_pendukung_1759117653.jpg', 'Diproses\r\n', NULL, NULL, '2025-09-29 03:47:33', '2025-09-29 03:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `id_users` int NOT NULL,
  `id_skpd` int NOT NULL,
  `nama_petugas` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `no_kontak` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `id_users`, `id_skpd`, `nama_petugas`, `no_kontak`) VALUES
(3, 12, 4, 'fitria', '');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id_profile` int NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `isi` text COLLATE utf8mb3_swedish_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id_profile`, `keterangan`, `isi`, `created_at`, `updated_at`) VALUES
(1, 'Profile', '<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"EN-US\"><a href=\"https://ppid.lampungprov.go.id/detail-dokumen/Undang-undang-Nomor-14-Tahun-2008-tentang-Keterbukaan-Informasi-Publik\" target=\"_blank\" rel=\"noopener\">Undang Undang No 14 Tahun 2008</a>&nbsp;tentang Keterbukaan Informasi Publik (KIP)&nbsp; mengamanatkan, setiap Badan Publik Pemerintah maupun Badan Publik Non Pemerintah mempunyai kewajiban untuk menyediakan Informasi Publik yang berada di bawah&nbsp; kewenangannya kepada masyarakat dengan cepat, actual, tepat waktu , biaya ringan dan cara sederhana.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"EN-US\">Sisi lain Undang Undang Keterbukaan Informasi Publik, menuntut kinerja Badan Publik yang transparan, efektif, efesien dan akuntabel. Oleh karena itu pelayanan informasi publiK&nbsp; harus mendapat perhatian yang serius bagi kita semua sebagai Badan Publik penyedia informasi, dengan meningkatkan pengelolaan informasi yang berkualitas serta memberikan pelayanan dan menyediakan informasi public yang mudah diakses oleh masyarakat.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"EN-US\">Untuk tujuan inilah setiap Badan Publik wajib menunjuk Pejabat Pengelola Informasi dan Dokumentasi (PPID), yang tugas pokok dan fungsinya adalah bertanggung jawab di bidang penyimpanan, pendokumentasian, penyediaan dan pelayanan informasi.</span></p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2025-09-26 17:09:23', '2025-09-28 11:30:35'),
(2, 'Tugas dan Kewenangan', '<h4>TUGAS</h4>\r\n<ul>\r\n<li>Pengkoordinasikan dan mengkonsolidasikan pengumpulan bahan informasi dan dokumentasi dari PPID pembantu</li>\r\n<li>Menyimpan, mendokumentasikan, menyediakan dan memberi pelayanan informasi kepada publik</li>\r\n<li>Melakukan verifikasi bahan informasi publik</li>\r\n<li>Melakukan uji konsekuensi atas informasi yang dikecualikan</li>\r\n<li>Melakukan kemutakhiran informasi dan dokumentasi</li>\r\n<li>Menyediakan informasi dan dokumentasi untuk diakses oleh masyarakat.</li>\r\n</ul>\r\n<h4>KEWENANGAN</h4>\r\n<ul>\r\n<li>Menolak memberikan informasi yang dikecualikan sesuai dengan ketentuan peraturan perundang-undangan</li>\r\n<li>Meminta dan memperoleh informasi dari unit kerja/komponen/satuan kerja yang menjadi cakupan kerjanya</li>\r\n<li>Mengkoordinasikan pemberian pelayanan informasi dengan PPID Pembantu dan/atau Pejabat Fungsional yang menjadi cakupan kerjanya</li>\r\n<li>Menentukan atau menetapkan suatu informasi dapat/tidaknya diakses oleh publik</li>\r\n<li>Menugaskan PPID Pembantu dan/atau Pejabat Fungsional untuk membuat, mengumpulkan, serta memelihara informasi dan dokumentasi untuk kebutuhan organisasi.</li>\r\n</ul>', '2025-09-26 17:09:23', '2025-09-29 11:06:22'),
(3, 'Struktur, Visi dan Misi', NULL, '2025-09-26 17:09:23', '2025-09-26 17:09:23'),
(4, 'Standar Layanan', NULL, '2025-09-26 17:09:23', '2025-09-26 17:09:23'),
(5, 'Tentang PPID', '<p>Kabag Perencanaan, Setditjen Keuangan Daerah, Wisnu Hidayat, kepada Media Keuangan Daerah, di Jakarta, menilai undang-undang tersebut harus disikapi oleh seluruh instansi pemerintah terkait. &ldquo;Artinya, ketika UU ini sudah diberlakukan maka Badan Publik termasuk Kementerian Dalam Negeri (Kemendagri) dan pemerintah daerah agar mulai melakukan keterbukaan informasi, yang memang diminta oleh publik,&rdquo; ujur Wisnu. Pasal 7 UU No. 14/2008 mengamatkan bahwa Badan Publik wajib menyediakan, memberikan dan/atau menerbitkan Informasi Publik yang berada di bawah kewenangannya kepada Pemohon Informasi Publik, selain informasi yang dikecualikan sesuai dengan ketentuan. Badan Publik wajib menyediakan Informasi Publik yang akurat, benar, dan tidak menyesatkan.</p>\r\n<p>Untuk melaksanakan kewajiban tersebut, Badan Publik harus membangun dan mengembangkan sistem informasi dan dokumentasi untuk mengelola Informasi Publik secara baik dan efisien sehingga dapat diakses dengan mudah. Selanjutnya, Badan Publik wajib membuat pertimbangan secara tertulis setiap kebijakan yang diambil untuk memenuhi hak setiap orang atas Informasi Publik. Pertimbangan tersebut antara lain memuat pertimbangan politik, ekonomi, sosial, budaya, dan/atau pertahanan dan keamanan negara. Dalam rangka memenuhi kewajiban tersebut Badan Publik dapat memanfaatkan sarana dan/atau media elektronik dan non elektronik.</p>\r\n<p>Selain kewajiban tersebut, UU tersebut juga mengamanatkan bahwa setiap Badan Publik wajib mengumumkan Informasi Publik secara berkala, yang meliputi informasi yang terkait dengan Badan Publik; informasi mengenai kegiatan dan kinerja Badan Publik terkait; informasi mengenai laporan keuangan; dan/atau informasi lain yang diatur dalam peraturan perundang-undangan. Kewajiban memberikan dan menyampaikan Informasi Publik dilakukan paling singkat enam bulan sekali.</p>\r\n<p>Kewajiban menyebarluaskan Informasi Publik disampaikan dengan cara yang mudah dijangkau oleh masyarakat dan dalam bahasa yang mudah dipahami, Cara-cara tersebut ditentukan lebih lanjut oleh Pejabat Pengelola Informasi dan Dokumentasi (PPID) di Badan Publik terkait. Ketentuan lebih lanjut mengenai kewajiban Badan Publik memberikan dan menyampaikan Informasi Publik secara berkala diatur dengan Petunjuk Teknis Komisi Informasi.</p>\r\n<p>Sementara itu, untuk mewujudkan pelayanan cepat, tepat, dan sederhana setiap Badan Publik menunjuk PPID; dan membuat dan mengembangkan sistem penyediaan layanan informasi secara cepat, mudah, dan wajar sesuai dengan petunjuk teknis&nbsp; standar layanan Informasi Publik yang berlaku secara nasional. PPID dibantu oleh pejabat fungsional. Sebagai implementasi dari UU No. 14/2008, pemerintah menerbitkan PP No. 61/2010 tentang Pelaksanaan Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik. Sementara itu, sesuai PP No. 61/2010, PPID bertugas dan bertanggungjawab dalam hal, antara lain</p>\r\n<ol>\r\n<li>penyediaan, penyimpanan, pendokumentasian, dan pengamanan informasi;</li>\r\n<li>pelayanan informasi sesuai dengan aturan yang berlaku;</li>\r\n<li>pelayanan informasi publik yang cepat, tepat, dan sederhana;</li>\r\n<li>penetapan prosedur operasional penyebarluasan informasi publik; selanjutnya;</li>\r\n<li>Pengujian konsekuensi;</li>\r\n<li>Pengklasifikasian informasi dan/atau pengubahannya;</li>\r\n<li>penetapan informasi yang dikecualikan yang telah habis jangka waktu pengecualiannya sebagai informasi publik yang dapat diakses; dan penetapan pertimbangan tertulis atas setiap kebijakan yang diambil untuk memenuhi hak setiap orang atas informasi publik. Selain ketentuan tersebut, PPID dapat menjalankan tugas dan tanggungjawabnya sesuai dengan ketentuan peraturan perundang-undangan.</li>\r\n</ol>\r\n<p>PPID di Kemendagri diketahui oleh Kepala Pusat Penerangan (Kapuspen) Kemendagri. Selain Kapuspen, juga ditetapkan pejabat penghubung pada masing-masing komponen (sekretariat) yang membidangi atau memiliki tanggungjawab terhadap pengelolaan data dan informasi. Khususnya di Ditjen Keuangan Daerah, PPID ditangani oleh Bagian Perencanaan Sesdijen Keuangan Daerah.</p>\r\n<p>Dalam rangka pengelolaan informasi publik, Presiden juga mengeluarkan Inpres No. 17/2011 tentang Aksi Pencegahan dan Pemberantasan Korupsi Tahun 2012.Inpres tersebut mengamanatkan kepada seluruh Kementerian/Lembaga (K/L) serta pemerintah daerah terkait dengan upaya pencegahan korupsi.</p>\r\n<p>Dalam rangka pelaksanaan Inpres tersebut, pemerintah telah menyusun rencana aksi nasional. Untuk pemerintah pusat, rencana aksi menjadi domain Kementerian Keuangan (Kemenkeu) terkait dengan transparansi pengelolaan anggaran K/L. Sedangkan untuk transparansi pengelolaan anggaran daerah (TPAD) dilaksanakan oleh Kemendagri. Instruksi tersebut dinilai cukup berat karena baru pertama kali dilakukan oleh Badan Publik, baik di pusat maupun daerah terkait dengan penganggaran. Dalam hal ini, UKP4 meminta Kemendagri untuk menyusun pedoman agar provinsi dan kab/kota menindaklanjuti UU No. 14/2008 serta Inpres No. 17/2011. Kemendagri telah menyelenggarakan rapat dengan&nbsp; UKP4 untuk mendorong daerah agar lebih transparan terhadap anggaran daerah.</p>\r\n<p>Diakui, saat ini belum banyak daerah yang menyediakan anggaran untuk mendanai rencana aksi tersebut. Dalam rangka mendorong daerah untuk menyelenggarakan transparasi anggaran, Kemendagri telah mengeluarkan Instruksi Mendagri No. 188.52/1797/SC/2012 tentang Transparasi Pengelolaan Anggaran Daerah (TPAD). Instruksi tersebut ditujukan kepada gubernur seluruh Indonesia dalam rangka pelaksanaan TPAD. Instruksi Mendagri tersebut mengamanatkan pemerintah provinsi untuk menyiapkan menu content dengan nama TPAD dalam website resmi pemerintah provinsi (Pemprov). Pemprov juga perlu mempublikasikan data mutakhir Pemprov pada menu content yang terdiri dari 12 items.</p>\r\n<p>Selanjutnya, Gubernur membuat Instruksi Gubernur yang ditujukan kepada bupati/walikota untuk menyiapkan menu content dengan nama TPAD dalam website resmi pemerintah kab/kota. Selain itu, Pemprov perlu melaksanakan monitoring dan evaluasi atas pelaksanaan Instruksi Gubernur tersebut. Pemprov juga berkoordinasi dengan bupati/walikota di wilayah masing-masing agar segara melakukan percepatan bagi daerah yang belum mengimplementasikan Instruksi Gubernur serta melaporkan perkembangan data dan menu content TPAD kepada Mendagri. Tahun 2013, UKP4 menetapkan rencana aksi di daerah.</p>\r\n<p>Saat ini, UKP4 telah menetapkan daerah-daerah sebagai proyek percontohan (pilot project) pelaksanaan TAPD. Sebagai tahap awal, TAPD dilaksanakan di 99 daerah provinsi dan kab/kota, yakni 33 provinsi, 33 kabupaten, dan 33 kota. Dalam hal ini, daerah bertanggungjawab langsung terhadap UKP4 terkait penilaian terhadap rencana aksi daerah. Ditjen Keuangan Daerah berkewajiban membina TPAD pada 99 daerah (provinsi dan kab/kota). Tugas Kemendagri c.q. Ditjen Keuangan Daerah adalah mendorong daerah agar mulai melaksanakan TAPD, termasuk melakukan verifikasi atas rencana aksi yang sudah disepakati oleh UKP4 dan daerah</p>', '2025-09-26 17:09:23', '2025-09-29 11:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `skpd`
--

CREATE TABLE `skpd` (
  `id_skpd` int NOT NULL,
  `nama_skpd` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `skpd`
--

INSERT INTO `skpd` (`id_skpd`, `nama_skpd`, `kategori`, `alamat`, `telepon`, `email`) VALUES
(3, 'RS Bengkulu', 'Kementrian Dalam Negeri', 'Begkulu', '082165443677', 'bengkulu@gmail.com'),
(4, 'DINAS PEKERJAAN UMUM', '', 'komplek perkantoran payaloting', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sosial_media`
--

CREATE TABLE `sosial_media` (
  `id_sosial_media` int NOT NULL,
  `site` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `facebook_link` varchar(512) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `instagram_link` varchar(512) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `instagram_post` varchar(512) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `sosial_media`
--

INSERT INTO `sosial_media` (`id_sosial_media`, `site`, `facebook_link`, `instagram_link`, `instagram_post`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'https://www.facebook.com/PemkabMandailingNatal', 'https://www.instagram.com/pemkabmandailingnatal/', 'https://www.instagram.com/p/DPEdJ_tEuEB/?img_index=1', '2025-09-29 09:25:14', '2025-09-29 09:25:14'),
(2, 'Instagram', 'https://www.facebook.com/PemkabMandailingNatal', 'https://www.instagram.com/pemkabmandailingnatal/', 'https://www.instagram.com/p/DPEdJ_tEuEB/?img_index=1', '2025-09-29 11:19:23', '2025-09-29 11:19:23'),
(3, 'youtube', 'https://www.youtube.com/@DISKOMINFOMADINA', 'https://www.youtube.com/@DISKOMINFOMADINA', 'https://www.youtube.com/watch?v=rJANgmE76f4', '2025-09-29 11:22:37', '2025-09-29 11:25:02'),
(4, 'tiktok', 'https://www.tiktok.com/@diskominfomadina2', 'https://www.tiktok.com/@diskominfomadina2', 'https://www.tiktok.com/@diskominfomadina2/video/7554675151720189195', '2025-09-29 11:26:31', '2025-09-29 11:26:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_biodata` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `email`, `username`, `password`, `jabatan`, `role`, `id_biodata`) VALUES
(1, 'admin@gmail.com', 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'admin', NULL),
(10, 'khairulhuda242@gmail.com', 'Khairul Huda', '25d55ad283aa400af464c76d713c07ad', NULL, 'masyarakat', 5),
(11, 'fatimahzahro1008@gmail.com', 'fatimah zahro', '83d1ce17786b733b695b2a189bb708bc', NULL, 'masyarakat', 6),
(12, 'fitria12@gmail.com', 'fitria', '83d1ce17786b733b695b2a189bb708bc', 'Petugas', 'petugas', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wagateway`
--

CREATE TABLE `wagateway` (
  `id_wagateway` int NOT NULL,
  `no_tujuan` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `pesan` text COLLATE utf8mb3_swedish_ci NOT NULL,
  `tanggal_kirim` datetime DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT 'Terjadwal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `wagateway`
--

INSERT INTO `wagateway` (`id_wagateway`, `no_tujuan`, `pesan`, `tanggal_kirim`, `status`) VALUES
(1, '62895409446565', 'Hai', '2025-09-26 14:30:58', 'gagal'),
(2, '62895409446565', 'Hai', '2025-09-26 14:37:00', 'gagal'),
(3, '62895409446565', 'Halo', '2025-09-26 14:38:01', 'gagal'),
(4, '62895409446565', 'Halo', '2025-09-26 14:38:03', 'gagal'),
(5, '62895409446565', 'HALLLL', '2025-09-26 14:38:25', 'gagal'),
(6, '62895409446565', 'HALLLL', '2025-09-26 14:38:27', 'gagal'),
(7, '62895409446565', 'asa', '2025-09-26 14:40:20', 'terjadwal'),
(8, '62895409446565', 'asa', '2025-09-26 14:42:19', 'gagal'),
(9, '62895409446565', 'asa22', '2025-09-26 14:43:05', 'gagal'),
(10, '62895409446565', '123', '2025-09-26 14:45:55', 'gagal'),
(11, '62895409446565', '1234567', '2025-09-26 14:47:35', 'arsip'),
(12, '62895409446565', '123', '2025-09-26 14:59:17', 'terkirim');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `benner`
--
ALTER TABLE `benner`
  ADD PRIMARY KEY (`id_banner`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Indexes for table `biodata_pengguna`
--
ALTER TABLE `biodata_pengguna`
  ADD PRIMARY KEY (`id_biodata`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dokumen_pemda`
--
ALTER TABLE `dokumen_pemda`
  ADD PRIMARY KEY (`id_dokumen_pemda`),
  ADD KEY `fk_dokumen_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `keberatan`
--
ALTER TABLE `keberatan`
  ADD PRIMARY KEY (`id_keberatan`),
  ADD KEY `id_permohonan` (`id_permohonan`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD PRIMARY KEY (`id_permohonan`),
  ADD UNIQUE KEY `no_permohonan` (`no_permohonan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_skpd` (`id_skpd`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`);

--
-- Indexes for table `skpd`
--
ALTER TABLE `skpd`
  ADD PRIMARY KEY (`id_skpd`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sosial_media`
--
ALTER TABLE `sosial_media`
  ADD PRIMARY KEY (`id_sosial_media`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id_biodata` (`id_biodata`);

--
-- Indexes for table `wagateway`
--
ALTER TABLE `wagateway`
  ADD PRIMARY KEY (`id_wagateway`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `benner`
--
ALTER TABLE `benner`
  MODIFY `id_banner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `biodata_pengguna`
--
ALTER TABLE `biodata_pengguna`
  MODIFY `id_biodata` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dokumen_pemda`
--
ALTER TABLE `dokumen_pemda`
  MODIFY `id_dokumen_pemda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `keberatan`
--
ALTER TABLE `keberatan`
  MODIFY `id_keberatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id_permohonan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `skpd`
--
ALTER TABLE `skpd`
  MODIFY `id_skpd` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sosial_media`
--
ALTER TABLE `sosial_media`
  MODIFY `id_sosial_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wagateway`
--
ALTER TABLE `wagateway`
  MODIFY `id_wagateway` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen_pemda`
--
ALTER TABLE `dokumen_pemda`
  ADD CONSTRAINT `fk_dokumen_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `keberatan`
--
ALTER TABLE `keberatan`
  ADD CONSTRAINT `keberatan_ibfk_1` FOREIGN KEY (`id_permohonan`) REFERENCES `permohonan` (`id_permohonan`),
  ADD CONSTRAINT `keberatan_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD CONSTRAINT `permohonan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `petugas_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `petugas_ibfk_2` FOREIGN KEY (`id_skpd`) REFERENCES `skpd` (`id_skpd`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
