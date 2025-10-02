-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 02, 2025 at 09:02 AM
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
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `id_album` int UNSIGNED NOT NULL,
  `kategori` enum('foto','video') COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_album` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `upload` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`id_album`, `kategori`, `nama_album`, `upload`, `created_at`, `updated_at`) VALUES
(1, 'foto', 'Bupati', 'uploads/album/68de3213f0c95_1759392275.jpeg', '2025-10-02 08:04:35', '2025-10-02 08:04:35'),
(2, 'foto', 'Bupati', 'uploads/album/68de359c02ac9_1759393180.png', '2025-10-02 08:19:40', '2025-10-02 08:19:40'),
(3, 'foto', 'Camat', 'uploads/album/68de35a825732_1759393192.jpg', '2025-10-02 08:19:52', '2025-10-02 08:19:52'),
(4, 'video', 'Camat', 'uploads/album/68de36755e557_1759393397.mp4', '2025-10-02 08:23:17', '2025-10-02 08:23:17');

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
(6, 'uploads/banner/1759285026_68dc8f22e694c.png', '', '', 2, '2025-10-01 09:17:06', '2025-10-01 09:17:06'),
(7, 'uploads/banner/1759285104_68dc8f7044637.png', '', '', 3, '2025-10-01 09:18:24', '2025-10-01 09:18:24'),
(12, 'uploads/banner/1759307760_68dce7f06847f.png', '', '', 1, '2025-10-01 15:36:00', '2025-10-01 15:36:00');

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
(19, 'Madina Terima Penghargaan UHC dan Sertifikat DBH dari Pemprov Sumut', '', 'Madina Terima Penghargaan UHC dan Sertifikat DBH dari Pemprov Sumut\r\n\r\nDiskominfo, Lubuk Pakam - Pemerintah Kabupaten Mandailing Natal (Pemkab Madina) menerima Penghargaan Universal Health Coverage (UHC) dari Direktur Kepesertaan BPJS Kesehatan David Bangun serta Sertifikat Pembagian Dana Bagi Hasil (DBH) dari Gubernur Sumatera Utara (Sumut) Muhammad Bobby Afif Nasution. \r\n\r\nPenyerahan dilakukan pada acara Launching UHC Prioritas Program Berobat Gratis (Probis) Sumut Berkah di Lubuk Pakam, Kabupaten Deli Serdang, Senin (29/9/2025). Kedua penghargaan tersebut diterima oleh Wakil Bupati Atika Azmi Utammi Nasution.\r\n\r\nSelain Madina, kabupaten/kota se-Sumut juga menerima piagam UHC serta sertifikat DBH.\r\n\r\nUsai menerima penghargaan, Wabup Atika Azmi berterima kasih kepada Pemerintah Provinsi Sumut dan BPJS atas apresiasi yang dterima Pemkab Madina.\r\n\r\nMenurut dia, pencapaian UHC ini menunjukkan komitmen pemerintah daerah dalam menjamin biaya berobat bagi masyarakat kurang mampu.\r\n\r\n“UHC artinya Pemkab Madina berkomitmen menanggung biaya berobat masyarakat yang tidak mampu. Untuk masyarakat yang mampu, kami mengimbau agar tetap menggunakan BPJS mandiri,” jelas dia.\r\n\r\nWabup Atika menambahkan, Madina termasuk salah satu kabupaten tercepat yang berhasil mencapai UHC di Sumut, yakni sejak 17 Januari 2024.\r\n\r\nPemkab Madina, kata wabup, akan terus fokus meningkatkan akses layanan kesehatan terutama bagi masyarakat yang membutuhkan.\r\n\r\nSementara itu, jumlah DBH yang diterima Pemkab Madina dari Pemprovsu senilai Rp.16.410.238.912. Dana tersebut, lanjut Wabup Atika, akan dimanfaatkan untuk mendukung berbagai program pembangunan, terutama yang telah tercatat dalam Perubahan APBD Madina tahun 2025.\r\n\r\n\"Infrastruktur, pendidikan, hingga kesehatan semuanya masuk dalam prioritas kegiatan dari DBH ini,\" sebut dia.\r\n\r\nWabup Atika pun mengajak seluruh masyarakat Madina, baik yang berada di kampung halaman maupun di perantauan, untuk mendukung penuh program pemkab demi kepentingan bersama.\r\n\r\nSebelumnya, Gubsu Bobby Afif Nasution menekankan agar makna UHC benar-benar dipahami dan dijalankan oleh seluruh pihak terkait.\r\n\r\nMenurut dia, UHC bukan hanya sebatas masyarakat bisa datang ke rumah sakit dengan menggunakan kartu identitas atau BPJS, melainkan bagaimana mendapatkan layanan kesehatan yang baik hingga sembuh.\r\n\r\n“Makna UHC itu adalah ketika masyarakat mengalami gangguan kesehatan, mereka datang ke rumah sakit bukan sekadar menunjukkan kartu identitas, tapi harus bisa sembuh dan benar-benar dilayani,” jelas dia.\r\n\r\nBobby meminta kepada seluruh kepala daerah, direktur rumah sakit, hingga jajaran tenaga kesehatan agar serius memastikan layanan UHC berjalan sebagaimana mestinya, serta aktif memantau rumah sakit di wilayahnya agar benar-benar memberikan pelayanan maksimal.', 'uploads/berita/1759295656_68dcb8a8b4400.jpeg', '2025-10-01 12:14:16', '2025-10-01 12:14:16'),
(29, 'Ketua Dekranasda Madina Minta Pandan Kembali Ditanam', '', 'Diskominfo, Panyabungan - Ketua Dewan Kerajinan Nasional Daerah (Dekranasda) Kabupaten Mandailing Natal (Madina) Ny. Yupri Astuti H. Saipullah Nasution meminta kepada Camat Bukitmalintang agar mengajak masyarakat agar kembali menanam pandan untuk bahan anyaman.\r\nHal itu disampaikan ketua Dekranasda saat meninjau pelatihan Penumbuhan dan Pengembangan Wirausaha Baru Industri Kecil berupa anyaman pandan di aula kantor Camat Bukitmalintang pada Selasa, 30 September 2025.\r\nKegiatan yang berlangsung mulai 29 September sampai 2 Oktober 2025 ini diinisiasi oleh Direktorat Industri Kecil Kimia, Sandang, dan Kerajinan. Kegiatan berlangsung di dua kecamatan, yakni Bukitmalintang berupa anyaman pandan dan Kecamatan Ulupungkut dengan pelatihan barista.\r\nDalam peninjauan itu, Ny. Yupri Astuti berdialog dengan sejumlah peserta dan berpesan agar mengikuti pelatihan dengan baik sehingga bisa bermanfaat secara ekonomi. \"Ambil ilmunya dan manfaatkan untuk kebutuhan keluarga,\" sebut dia.\r\nNy. Yupri menilai kerajinan yang dibuat oleh peserta cukup bagus. Dia pun berharap anyaman ini nantinya selalu ditampilkan dalam bazar Pemberdayaan Kesejahteraan Keluarga (PKK). \"Setiap pertemuan nantinya agar anyaman pandan ini dapat ditampilkan pada bazarnya PKK,\" harap dia.\r\nCamat Bukitmalintang Mahdi Gultom mengapresiasi Direktorat Industri Kecil Kimia, Sandang, dan Kerajinan yang menunjuk kecamatan tersebut sebagai salah satu lokasi pelatihan. \"Hasil anyaman nantinya akan dipromosikan di gerai Drekanasda,\" kata dia.\r\nPara peserta terlihat membuat beragam anyaman seperti tikar, tempat mukena, dompet, tatakan gelas, tas jinjing, tempat makeup.', 'uploads/berita/1759300150_68dcca365cf71.jpg', '2025-10-01 13:29:10', '2025-10-01 13:29:10'),
(31, 'Pelantikan DPC FKDT Madina Periode 2025-2030 Berlangsung Khidmat', '', 'Diskominfo, Panyabungan - Pelantikan Dewan Pengurus Cabang Cabang Forum Komunikasi Diniyah Takmiliyah (DPC FKDT) periode 2025-2030 di Aula Kantor Bupati Mandailing Natal (Madina), Kompleks Perkantoran Payaloting, Panyabungan, pada Selasa, 30 September 2025, berlangsung khidmat.\r\nStaf Ahli Bidang Ekonomi dan Pembangunan Setdakab Madina Dr. Ahmad Duroni menyampaikan beberapa pesan dari Bupati H. Saipullah Nasution. Antara lain mengingatkan pengurus yang baru untuk dapat mendorong pembangunan pendidikan MDTA. Terlebih sebutan Serambi Mekkah Sumatera Utara yang melekat pada daerah ini.\r\n\"Dengan dilantiknya FKDT dapat menjadi pengembang nilai-nilai ajaran Islam yang dapat meletakkan dasar-dasar keilmuan untuk menciptakan masyarakat beriman, bertaqwa, dan berakhlakulkarimah,\" kata dia.\r\nDr. Duroni juga meminta para pendidik di MDTA agar menjaga anak-anak di tengah banyaknya ancaman di masa kini, baik itu melalui media sosial maupun pelecehan yang belakangan sering terjadi.\r\n\"Mari jaga diri, lingkungan sekolah, dan lingkungan sekitar kita. Tanamkan ilmu agama bagi anak-anak sehingga akhlaknya baik dan terhindar dari perbuatan tercela,\" sebut dia.\r\nKetua DPW FKDT Sumut Khalid Daulay, S.Ag, S.Pdi, menyampaikan, para guru MDTA harus tetap semangat mengajar meskipun gajinya masih kecil. Dia juga berharap kondisi ini menjadi perhatian bagi bupati Madina.\r\n\"Semoga dengan kepengurusan FKDT yang baru dilantik dapat meningkatkan kualitas guru MDTA dan terjaminnya kesejahteraan,\" kata Khalid.\r\nBerikut pengurus FKDT Madina periode 2025-2030, \r\n1. Muhammad Daud Lubis sebagai  ketua,\r\n2. Ali Mulki, S.Th.I sebagai sekretaris,\r\n3. Syukur Saleh S.Pd sebagai bendahara,\r\n5. Pardamean sebagai  wakil Ketua I,\r\n6. Hoirun Ahmad S.Pd sebagai wakil sekretaris I,\r\n7. Sobirin sebagai wakil sekretaris II,\r\n8. Ali Aspi sebagai wakil bendahara I,\r\n9. Ikhwan sebagai wakil Bendahara II,\r\n10 . Rizki Inayah Putri Nasution sebagai ketua Departemen Kurikulum dan Pendidikan,\r\n11. Ahmad Hanafi sebagai ketua Departemen Dana dan Usaha,\r\n12. Arfah sebagai ketua Departemen Humas dan Kerja Sama, dan\r\n13. Ikhwan Rangkuti sebagai ketua Departemen Pengembangan Kader dan Sumber Daya Manusia', 'uploads/berita/1759300246_68dcca96d1922.jpg', '2025-10-01 13:30:46', '2025-10-01 13:30:46'),
(32, 'Bupati Madina Bagikan Paket Sembako Kepada 63 Lansia', '', 'Diskominfo, Panyabungan - Bupati Mandailing Natal (Madina) H. Saipullah Nasution membagikan paket sembako kepada 63 orang kategori lanjut usia (lansia) di Sopo Godang, pendopo Rumah Dinas Bupati Madina, Desa Parbangunan, Panyabungan, pada Selasa, 30 September 2025.\r\nPenyerahan bingkisan ini merupakan bagian dari rasa syukur Bupati Saipullah atas pertambahan usia. Hari ini merupakan peringatan hari lahir ke-64 orang nomor satu di jajaran Pemkab Madina itu.\r\nBupati Saipullah mengatakan, ulang tahun hanya sebatas sarana. Sementara esensi dari pertambahan usia adalah terus menjaga silaturahmi, berbagi pengalaman, dan bermanfaat bagi manusia maupun lingkungan.\r\n\"Dengan amanah sebagai bupati, hubungan dengan masyarakat juga sangat penting. Banyak harapan rakyat yang harus kita jawab melalui pembangunan dan peningkatan kesejahteraan,\" kata dia.\r\nBupati Saipullah mengungkapkan tugas memimpin daerah tidaklah mudah. Untuk itu, dia berharap doa dan dukungan masyarakat tetap mengalir, terutama dalam menghadapi berbagai tantangan pembangunan maupun keterbatasan anggaran.\r\n\"Mudah-mudahan ada kemudahan melalui berbagai jaringan yang bisa membantu kita menutup kekurangan,\" sebut dia.\r\nSementara itu, Ketua TP PKK Madina Ny. Yupri Astuti menyampaikan, sebagai istri akan terus mendoakan yang terbaik. \"Semoga Allah menjaga beliau dengan baik, memberikan kesehatan, dan keberkahan dalam memimpin Madina,\" kata dia.\r\nNy. Yupri menambahkan, doa dan dukungan dari keluarga, sahabat, kerabat, serta masyarakat Madina akan begitu berarti bagi sang suami dalam menjalankan amanah sebagai kepala daerah.\r\n\"Kalau Allah meridhoi, insyaallah Madina akan menjadi daerah yang semakin maju, berwarna, dan memberi manfaat untuk semua. Dengan istiqomah dan semangat kebersamaan, kita bisa mewujudkan itu,\" tambah dia.\r\nAcara tersebut turut dihadiri Pj. Sekdakab Madina Drs. M. Sahnan Pasaribu, Kakan Kemenag H. Maranaik Hasibuan, Ketua MUI Muhammad Natsir, ketua BWI, para Asisten, sejumlah kepala OPD, pimpinan Parpol, serta masyarakat', 'uploads/berita/1759300358_68dccb060f138.jpg', '2025-10-01 13:32:38', '2025-10-01 13:32:38'),
(33, 'Diskominfo Kak Fatimah: Bupati Madina Saksikan Film G30S/PKI di Kampung Jenderal AH Nasution ', '', 'Diskominfo Kak Fatimah: Bupati Madina Saksikan Film G30S/PKI di Kampung Jenderal AH Nasution \r\n\r\nDiskominfo, Kotanopan - Bupati Mandailing Natal (Madina) H. Saipullah Nasution menyaksikan film Penumpasan Penghianatan G30S/PKI di kampung halaman Jenderal Besar Abdul Haris (AH) Nasution, Hutapungkut, Kecamatan Kotanopan, pada Senin, 29 September 2025.\r\n\r\nJenderal AH Nasution lolos dari penculikan gerakan yang hendak mengudeta kepemimpinan di Indonesia pada 1965. Dalam pemberontakan itu, sebanyak enam jenderal dan satu kapten tewas di tangan kelompok PKI.\r\n\r\nBupati Saipullah mengingatkan setiap anak bangsa harus terus mengobarkan semangat melawan pihak-pihak yang hendak merusak persatuan bangsa ini. \r\n\r\nMenurut dia, gerakan berdarah yang terjadi 60 tahun silam itu harus terus dikenang bahwa di masa lalu ada sekelompok orang yang ingin mengganti Pancasila dengan ideologi lain. \"Bangsa yang besar adalah yang mengingat sejarah bangsanya,\" kata dia.\r\n\r\nG30/SPKI, jelas bupati Madina, merupakan sejarah kelam bangsa ini. Dia berharap dengan penayangan film itu dapat menimbulkan keteguhan dan semangat bagi generasi muda untuk mengisi kemerdekaan dengan hal-hal baik.\r\n\r\nBupati berpesan semangat orang-orang besar yang lahir dari kawasan ini seperti Jenderal Besar AH Nasution, Adam Malik Batubara, para pahlawan perintis, Todung Mulya Lubis, dan Adnan Buyung Nasution harus menjadi motivasi untuk menguatkan persatuan dalam memajukan kabupaten ini.\r\n\r\nSementara itu Abdullah Batubara, ketua Ikatan Pemuda Hutapungkut (IPH) sekaligus ketua panitia nonton bareng ini mengatakan, kegiatan tersebut rutin dilaksanakan setiap tahun. \r\n\r\nTujuan penayangan ini, kata dia, sebagai pendidikan sejarah dan mengenalkan Jenderal Besar AH Nasution maupun orang-orang besar lainnya kepada generasi muda. \"Kami ingin menjadikan Hutapungkut ini sebagai desa sejarah,\" sebut dia.\r\n\r\nDalam kesempatan ini, bupati didampingi Asisten Administrasi Umum Lismulyadi Nasution, Kepala Diskominfo Azhar Paras Muda Hasibuan, Kepala Badan Kesbangpol Kapsan Usman, Kepala Disdukcapil Yamna Nasution, Kabag Umum Irsan Nasution, Kabag Kesra Bahruddin Juliadi, Camat Kotanopan Muslih Lubis, dan Camat Ulupungkut Tajuddin Nasution.\r\n\r\nDi lokasi terlihat ratusan masyarakat, termasuk tokoh agama, tokoh masyarakat, dan anak-anak yang ikut menonton film tersebut.\r\n[13.35, 1/10/2025] Diskominfo Kak Fatimah: Bupati Madina Lepas Kontingen MQK, 18 Orang Santri Ponpes Musthafawiyah\r\n\r\nDiskominfo, Panyabungan - Bupati Mandailing Natal (Madina) H. Saipullah Nasution melepas keberangkatan kontingen Musabaqah Qiroatul Kutub (MQK) yang akan berkompetisi di tingkat nasional pada awal Oktober ini.\r\n\r\nPelepasan kontingen dilaksanakan di Kantor Kementerian Agama (Kemenag) Madina, Kompleks Perkantoran Payaloting, Desa Parbangunan, Kecamatan Panyabungan, pada Senin, 29 September 2025. Mereka akan bertolak ke  Pondok Pesantren As\'adiyah, Sengkang, Wajo, Sulawesi Selatan, tempat pelaksanaan MQKN 2025.', 'uploads/berita/1759300597_68dccbf52f889.jpeg', '2025-10-01 13:36:37', '2025-10-01 13:36:37'),
(34, 'Bupati Madina Lepas Kontingen MQK, 18 Orang Santri Ponpes Musthafawiyah', '', 'Bupati Madina Lepas Kontingen MQK, 18 Orang Santri Ponpes Musthafawiyah\r\n\r\nDiskominfo, Panyabungan - Bupati Mandailing Natal (Madina) H. Saipullah Nasution melepas keberangkatan kontingen Musabaqah Qiroatul Kutub (MQK) yang akan berkompetisi di tingkat nasional pada awal Oktober ini.\r\n\r\nPelepasan kontingen dilaksanakan di Kantor Kementerian Agama (Kemenag) Madina, Kompleks Perkantoran Payaloting, Desa Parbangunan, Kecamatan Panyabungan, pada Senin, 29 September 2025. Mereka akan bertolak ke  Pondok Pesantren As\'adiyah, Sengkang, Wajo, Sulawesi Selatan, tempat pelaksanaan MQKN 2025.\r\n\r\nDari 20 peserta yang diberangkatkan, 18 orang merupakan santri Pondok Pesantren Musthafawiyah Purba Baru. Sementara itu, Pondok Pesantren Darul Ikhlas dan Pondok Pesantren Abinnur Al Islami masing-masing mengirim satu santri.\r\n\r\nBupati Saipullah berpesan kepada para santri untuk menjaga kekompakan selama berada di Sulawesi Selatan. Dia juga mengingatkan agar mereka menjaga kesehatan sehingga bisa bertanding secara maksimal.\r\n\r\nBupati Saipullah menerangkan, sejatinya kontingen ini merupakan tanggung jawab pemerintah karena peserta merupakan pemenang tingkat provinsi.\r\n\r\n\"Madina telah meraih kemenangan di tingkat provinsi. Meskipun demikian, kita harus bangga menjadi perwakilan Sumatera Utara untuk bertanding di tingkat nasional,\" sebut dia.\r\n\r\nBupati pun mendoakan agar kontingen ini bisa menampilkan kemampuan secara maksimal dan berhasil mengharumkan nama Sumut di kancah nasional.\r\n\r\nKakan Kemenag H. Maranaik Hasibuan melaporkan Madina menjadi wakil Sumut untuk mengikuti MQKN ke di Sulawesi Selatan. \"80 persen kontingen MQKN berasal dari Madina,\" kata dia.\r\n\r\nDi sisi lain, dia mengungkapkan, baru-baru ini, Madina menjadi pemenang lomba bahasa Inggris dan bahasa Arab tingkat provinsi.', 'uploads/berita/1759300676_68dccc4400f91.jpeg', '2025-10-01 13:37:56', '2025-10-01 13:37:56'),
(35, 'Jenguk Korban Kebakaran, Wabup Madina Pastikan Fasilitasi Administrasi Kependudukan ', '', 'Jenguk Korban Kebakaran, Wabup Madina Pastikan Fasilitasi Administrasi Kependudukan \r\n\r\nDiskominfo, Panyabungan - Wakil Bupati Mandailing Natal (Madina) Atika Azmi Utammi Nasution menjenguk keluarga korban kebakaran di Desa Hutagodang Muda, Kecamatan Siabu, pada Jumat, 26 September 2025, dengan membawa bantuan sandang dan pangan.\r\n\r\nWabup Atika yang hadir bersama Kepala Badan Penanggulangan Bencana Daerah (BPBD) Mukhsin Nasution dan Kepala Dinas PUPR Elpi Yanti Harahap menyampaikan, dia mengetahui adanya kebakaran itu sekitar pukul 04.00 WIB atau beberapa saat usai kejadian.\r\n\r\n\"Musibah ini sesuatu yang tidak disangka-sangka dan tidak diduga,\" kata dia sebelum menyerahkan bantuan sementara itu.\r\n\r\nPemerintah, lanjut dia, akan memfasilitasi pembuatan ulang administrasi kependudukan milik korban. Termasuk pembuatan surat pengganti ijazah. \"Ini ada Pak Camat dan Pak Kades, koordinasi dengan mereka dan saya akan pantau langsung,\" kata dia.\r\n\r\nUntuk bantuan pembangunan rumah, Wabup Atika menjelaskan Dinas Sosial akan terlebih dahulu melakukan survei dan verifikasi data. \"Nanti kalau sudah rampung, bantuannya akan langsung ditransfer ke rekening ibu,\" sebut dia.\r\n\r\nKepada masyarakat, Wabup Atika berpesan dan meminta agar nantinya turut membantu keluarga korban kebakaran saat pembangunan ulang rumah.  \"Tidak ada yang berharap kejadian ini menimpa siapa pun,\" ujar dia.\r\n\r\nDi sisi lain, dia kembali mengimbau masyarakat untuk tidak membakar sampah sembarangan dan memperhatikan kapasitas listrik dengan daya yang digunakan. \"Kalau memang sampah harus dibakar, tunggu apinya padam baru ditinggalkan,\" harap dia.\r\n\r\nSebelumnya, Kepala Desa Satriya Wira berterima kasih atas kedatangan wakil bupati dan rombongan. Dia malaporkan peristiwa kebakaran itu berdampak pada empat bangunan dengan satu rumah dan satu gudang penampungan komoditas perkebunan ludes dilalap si Jago Merah.\r\n\r\n\"Dua lagi di sisi kiri dan kanan rumah yang hangus. Dindingnya terdampak,\" kata dia.\r\n\r\nKebakaran ini terjadi sekitar pukul 03.15 WIB. Berdasarkan keterangan Nur Kholilah, api berasal dari luar rumahnya. Tengah malam itu, dia melihat api sudah membara di dinding rumah yang dia tinggali bersama suami dan satu anaknya itu', 'uploads/berita/1759300766_68dccc9ebeb6c.jpeg', '2025-10-01 13:39:26', '2025-10-01 13:39:26');

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
(6, 'fatimah zahro', '1213015008950005', 'dalan lidang,panyabungan', 'SUMATERA UTARA', 'KABUPATEN MANDAILING NATAL', 'Perempuan', 20, 'SMA/SMK', 'mahasiswa', '081260842677', 'fatimahzahro1008@gmail.com', NULL, 'pribadi', NULL, 'uploads/ktp_1759117518_68da00cec0471.jpg', NULL),
(7, 'Wara Ulan Saputri', '1203045703980002', 'Dusun Mandurana, Desa Situmba Julu, Kecamatan Sipirok, Provinsi Sumatera Utara', 'SUMATERA UTARA', 'KABUPATEN MANDAILING NATAL', 'Perempuan', 29, 'S3', 'PNS', '081377241610', 'wara.ulan17@gmail.com', 'uploads/1203045703980002_foto_profile_1759289403.png', 'pribadi', '', NULL, NULL),
(8, 'Khairul Huda', '1377020610010009', 'Lhoksuemawe\r\nBlang Pulo', 'ACEH', 'KOTA LHOKSEUMAWE', 'Laki-laki', 52, 'S1', 'TNI', '082165443677', 'khairulhuda242@gmail.com', NULL, 'pribadi', NULL, 'uploads/ktp_1759296874_68dcbd6a803a8.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL,
  `id_kategori` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `kandungan_informasi` text COLLATE utf8mb3_swedish_ci,
  `terbitkan_sebagai` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_dokumen_pemda` int DEFAULT NULL,
  `tipe_file` enum('audio','video','text','gambar','lainnya') COLLATE utf8mb3_swedish_ci NOT NULL,
  `upload_file` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status` enum('draft','publikasi') COLLATE utf8mb3_swedish_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `id_kategori`, `judul`, `kandungan_informasi`, `terbitkan_sebagai`, `id_dokumen_pemda`, `tipe_file`, `upload_file`, `status`, `created_at`, `updated_at`) VALUES
(3, 1, 'Pedoman Penggunaan Dana Desa Di Kabupaten Mandailing Natal Tahun Anggaran 2025', '', 'Keputusan Bupati', NULL, 'text', 'uploads/dokumen_berkala/68dca777c7444_1759291255.pdf', 'publikasi', '2025-10-01 04:00:55', '2025-10-01 04:00:55');

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

-- --------------------------------------------------------

--
-- Table structure for table `informasi_publik`
--

CREATE TABLE `informasi_publik` (
  `id_informasi_publik` int NOT NULL,
  `nama_informasi_publik` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `sub_informasi_publik` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `isi` text COLLATE utf8mb3_swedish_ci,
  `tags` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `informasi_publik`
--

INSERT INTO `informasi_publik` (`id_informasi_publik`, `nama_informasi_publik`, `sub_informasi_publik`, `isi`, `tags`, `created_at`, `updated_at`) VALUES
(1, 'AHAYYYYYY YAH', NULL, '<p><a title=\"alifirdaus,+828-836+Ali+Firdaus (1).pdf\" href=\"uploads/informasi_documents/1759383688_68de10881b321.pdf\">uploads/informasi_documents/1759383688_68de10881b321.pdf</a></p>\r\n<p><img src=\"uploads/informasi_images/1759383695_68de108fb25c2.png\" alt=\"\" width=\"585\" height=\"1024\"></p>', 'ABCD', '2025-10-02 05:41:40', '2025-10-02 05:41:40');

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
-- Table structure for table `layanan_informasi_publik`
--

CREATE TABLE `layanan_informasi_publik` (
  `id_layanan` int NOT NULL,
  `nama_layanan` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `sub_layanan` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `isi` text COLLATE utf8mb3_swedish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `layanan_informasi_publik`
--

INSERT INTO `layanan_informasi_publik` (`id_layanan`, `nama_layanan`, `sub_layanan`, `isi`, `created_at`, `updated_at`) VALUES
(1, 'YAREU', NULL, '<p><a title=\"9a1ecfcaf5200b05e58d6134c733311563ef.pdf\" href=\"uploads/layanan_documents/1759381015_68de0617593ac.pdf\" target=\"_blank\" rel=\"noopener\">uploads/layanan_documents/1759381015_68de0617593ac.pdf</a></p>\n<p><img src=\"uploads/layanan_images/1759381036_68de062cc046e.png\" alt=\"\" width=\"1000\" height=\"1000\"></p>', '2025-10-02 04:57:24', '2025-10-02 05:12:12'),
(2, 'AHAY', 'AHAY 2', '<p><img src=\"uploads/layanan_images/1759381061_68de0645107ee.jpg\" alt=\"\" width=\"3024\" height=\"4032\"></p>', '2025-10-02 04:57:42', '2025-10-02 04:57:42');

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
(3, 11, 'PMH2025090001', NULL, 'untuk permintaan data', 'Dinas Pekerjaan Umum', 'permintaan data', NULL, 'untuk melengkapi data', 'uploads/1213015008950005_identitas_1759117653.jpg', 'uploads/1213015008950005_pendukung_1759117653.jpg', 'Diterima', NULL, NULL, '2025-09-29 03:47:33', '2025-09-30 04:54:31'),
(4, 11, 'PMH2025100001', NULL, 'Untuk permintaan data', 'Dinas Pekerjaan Umum', 'Permintaan data', NULL, 'Permintaan data', 'uploads/1213015008950005_identitas_1759285775.png', 'uploads/1213015008950005_pendukung_1759285775.jpg', 'Ditolak', NULL, NULL, '2025-10-01 02:29:35', '2025-10-01 02:42:07'),
(5, 13, 'PMH000003', 9, 'Dinas Pekerjaan Umum', 'DINAS PEKERJAAN UMUM', 'Perbub Keuangan', 'membutuhkan informasi spbe', 'untuk tugas akhir', NULL, NULL, 'Diproses', 'Website', NULL, '2025-10-01 03:30:03', '2025-10-01 03:30:03');

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
  `nama_kategori` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `isi` text COLLATE utf8mb3_swedish_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id_profile`, `nama_kategori`, `keterangan`, `isi`, `created_at`, `updated_at`) VALUES
(16, 'DAERAH', 'Profil Pemimpin Daerah', '<p style=\"text-align: center;\"><img src=\"http://cg4hopftx.localto.net/ppid-mandailing/uploads/profile_images/1759301943_68dcd13732983.png\" alt=\"\" width=\"169\" height=\"226\"></p>\r\n<p style=\"text-align: center;\"><strong>Saipullah Nasution</strong>&nbsp;(lahir 30 September 1961) adalah pensiunan&nbsp;<a title=\"Aparatur Sipil Negara\" href=\"https://id.wikipedia.org/wiki/Aparatur_Sipil_Negara\">Aparatur Sipil Negara</a>&nbsp;yang menjabat sebagai&nbsp;<a class=\"mw-redirect\" title=\"Bupati Mandailing Natal\" href=\"https://id.wikipedia.org/wiki/Bupati_Mandailing_Natal\">Bupati Mandailing Natal</a>&nbsp;masa jabatan 2025&ndash;2030. Ia menjabat sejak 21 Maret 2025 setelah dilantik&nbsp;<a title=\"Bobby Nasution\" href=\"https://id.wikipedia.org/wiki/Bobby_Nasution\">Gubernur Sumatera Utara Bobby Nasution</a>&nbsp;di Aula Raja Inal Siregar, Kantor Gubernur Sumatera Utara,&nbsp;<a class=\"mw-redirect\" title=\"Medan\" href=\"https://id.wikipedia.org/wiki/Medan\">Medan</a>.</p>\r\n<p style=\"text-align: center;\"><img src=\"http://cg4hopftx.localto.net/ppid-mandailing/uploads/profile_images/1759301997_68dcd16d91cff.png\" alt=\"\" width=\"188\" height=\"250\"></p>\r\n<p style=\"text-align: center;\"><strong>Atika Azmi Utammi Nasution</strong>, B.App.Fin, M.Fin<sup id=\"cite_ref-1\" class=\"reference\"><a href=\"https://id.wikipedia.org/wiki/Atika_Azmi_Utammi#cite_note-1\"><span class=\"cite-bracket\">[</span>1<span class=\"cite-bracket\">]</span></a></sup>&nbsp;(lahir 1 Desember 1993) adalah politikus&nbsp;<a title=\"Milenial\" href=\"https://id.wikipedia.org/wiki/Milenial\">milenial</a>&nbsp;yang menjabat&nbsp;<a title=\"Daftar Wakil Bupati Mandailing Natal\" href=\"https://id.wikipedia.org/wiki/Daftar_Wakil_Bupati_Mandailing_Natal\">Wakil Bupati Mandailing Natal</a>&nbsp;dua periode (2021-2025, 2025-saat ini).<sup id=\"cite_ref-2\" class=\"reference\"><a href=\"https://id.wikipedia.org/wiki/Atika_Azmi_Utammi#cite_note-2\"><span class=\"cite-bracket\">[</span>2<span class=\"cite-bracket\">]</span></a></sup> Atika meraih rekor MURI dalam kategori wakil bupati perempuan termuda di Indonesia. Anak kedelapan dari sembilan bersaudara ini adalah dalah anak dari tokoh Mandailing Natal (wilayah Mandailing Julu), Khoiruddin Nasution dan Hamidah Lubis</p>', '2025-10-01 14:01:13', '2025-10-01 14:01:13'),
(17, 'DAERAH', 'Visi Misi', '<p>Visi Msi Mandailing Natal</p>', '2025-10-01 14:12:30', '2025-10-01 14:12:30'),
(18, 'DAERAH', 'Alamat Kantor', '<p><a title=\"Alamat Dinas Komunikasi Dan Informatika\" href=\"QHWH+MHG, Parbangunan, Kec. Panyabungan, Kabupaten Mandailing Natal, Sumatera Utara\">QHWH+MHG, Parbangunan, Kec. Panyabungan, Kabupaten Mandailing Natal, Sumatera Utara</a>Komplek Perkantoran Payaloting, Parbangunan - Kecamatan Panyabungan, Kabupaten Mandailing Natal, Provinsi Sumatera Utara, Kode Pos 22978</p>', '2025-10-01 14:15:09', '2025-10-01 14:47:15'),
(19, 'DAERAH', 'Janji Kerja', '<p>Janji Kerja Bupati</p>', '2025-10-01 14:22:10', '2025-10-01 14:22:10'),
(20, 'DAERAH', 'Sejarah Mandailing Natal', '<p>Sejarah Mandailing Natal</p>', '2025-10-01 14:22:44', '2025-10-01 14:22:44'),
(21, 'DAERAH', 'Struktur Organisasi Perangkat Daerah', '<p>Struktur Organisasi Perangkat Daerah</p>', '2025-10-01 14:23:15', '2025-10-01 14:23:15'),
(22, 'DAERAH', 'Susunan Organisasi, Tugas dan Fungsi, Tata Kerja PD', '<p>Susunan Organisasi, Tugas dan Fungsi, Tata Kerja PD</p>', '2025-10-01 14:25:07', '2025-10-01 14:25:07'),
(23, 'PPID', 'Profil PPID', '<p>Profil PPID</p>', '2025-10-01 14:30:17', '2025-10-01 14:30:17'),
(24, 'PPID', 'Dasar Hukum PPID', '<p>Dasar Hukum PPID</p>', '2025-10-01 14:30:46', '2025-10-01 14:30:46'),
(25, 'PPID', 'Struktur Organisasi PPID', '<p>Struktur Organisasi PPID</p>', '2025-10-01 14:31:13', '2025-10-01 14:31:13'),
(26, 'PPID', 'Visi dan Misi PPID', '<p class=\"MsoNormal\"><span style=\"font-family: \'Times New Roman\',\'serif\';\">Visi PPID:</span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-family: \'Times New Roman\',\'serif\';\">Terwujudnya pelayanan informasi yang transparan dan akuntabel untuk memenuhi hak informasi sesuai dengan ketentuan peaturan perundng-undangan yang berlaku.</span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: 1.0pt; mso-bidi-font-size: 11.0pt; line-height: 115%; font-family: \'Times New Roman\',\'serif\';\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-family: \'Times New Roman\',\'serif\';\">Misi PPID:</span></p>\r\n<p class=\"MsoListParagraphCxSpFirst\" style=\"margin-left: 54.0pt; mso-add-space: auto; text-indent: -18.0pt; mso-list: l0 level1 lfo1;\"><!-- [if !supportLists]--><span style=\"font-family: \'Times New Roman\',\'serif\'; mso-fareast-font-family: \'Times New Roman\';\"><span style=\"mso-list: Ignore;\">1.<span style=\"font: 7.0pt \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span><!--[endif]--><span style=\"font-family: \'Times New Roman\',\'serif\';\">Meningkatkan pengelolaan dan pelayanan informasi yang berkualitas, benar dan bertanggung jawab.</span></p>\r\n<p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left: 54.0pt; mso-add-space: auto; text-indent: -18.0pt; mso-list: l0 level1 lfo1;\"><!-- [if !supportLists]--><span style=\"font-family: \'Times New Roman\',\'serif\'; mso-fareast-font-family: \'Times New Roman\';\"><span style=\"mso-list: Ignore;\">2.<span style=\"font: 7.0pt \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span><!--[endif]--><span style=\"font-family: \'Times New Roman\',\'serif\';\">Membangun dan mengembangkan penyediaan dan layanan informasi.</span></p>\r\n<p class=\"MsoListParagraphCxSpMiddle\" style=\"margin-left: 54.0pt; mso-add-space: auto; text-indent: -18.0pt; mso-list: l0 level1 lfo1;\"><!-- [if !supportLists]--><span style=\"font-family: \'Times New Roman\',\'serif\'; mso-fareast-font-family: \'Times New Roman\';\"><span style=\"mso-list: Ignore;\">3.<span style=\"font: 7.0pt \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span><!--[endif]--><span style=\"font-family: \'Times New Roman\',\'serif\';\">Meningkatkan dan mengembangkan kompetensi dan kualitas SDM dalam bidang pelayanan informasi.</span></p>\r\n<p class=\"MsoListParagraphCxSpLast\" style=\"margin-left: 54.0pt; mso-add-space: auto; text-indent: -18.0pt; mso-list: l0 level1 lfo1;\"><!-- [if !supportLists]--><span style=\"font-family: \'Times New Roman\',\'serif\'; mso-fareast-font-family: \'Times New Roman\';\"><span style=\"mso-list: Ignore;\">4.<span style=\"font: 7.0pt \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span><!--[endif]--><span style=\"font-family: \'Times New Roman\',\'serif\';\">Mewujudkan keterbukaan informasi<span style=\"mso-spacerun: yes;\">&nbsp; </span>Pemerintahan Kabupaten Mandailing Natal dengan proses yang cepat, tepat, mudah, dan sederhana.</span></p>', '2025-10-01 14:32:39', '2025-10-01 14:32:39'),
(27, 'PPID', 'Tugas Dan Fungsi PPID', '<p>Tugas Dan Fungsi PPID</p>', '2025-10-01 14:33:07', '2025-10-01 14:33:07'),
(28, 'PPID', 'Maklumat Pelayanan PPID', '<p><em><strong><span style=\"font-size: 24pt;\">\"SIAP MEMBERIKAN PELAYANAN SESUAI DENGAN KEWAJIBAN DAN BERKOMITMEN AKAN MELAKUKAN PERBAIKAN SECARA TERUS MENURUS\"</span></strong></em></p>', '2025-10-01 14:35:48', '2025-10-01 14:35:48'),
(29, 'PPID', 'Waktu dan Biaya Layanan', '<p>Foto Edit</p>', '2025-10-01 14:38:04', '2025-10-01 14:38:04'),
(30, 'PPID', 'Formulir Layanan Informasi (Ofline)', '<p>Formulir Layanan Informasi (Ofline)</p>', '2025-10-01 14:39:58', '2025-10-01 14:39:58'),
(32, 'DAERAH', 'ABCD', '<p><a title=\"alifirdaus,+828-836+Ali+Firdaus (1).pdf\" href=\"uploads/profile_documents/1759388789_68de247566690.pdf\" target=\"_blank\" rel=\"noopener\">http://localhost/ppid-mandailing/uploads/profile_documents/1759388789_68de247566690.pdf</a></p>\r\n<p><img src=\"uploads/profile_images/1759388812_68de248c137b5.png\" alt=\"\" width=\"1000\" height=\"1000\"></p>', '2025-10-02 14:06:53', '2025-10-02 14:06:53');

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
(4, 'DINAS PEKERJAAN UMUM', 'Dinas Pekerjaan Umum', 'komplek perkantoran payaloting', '', 'fitria12@gmail.com');

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
-- Table structure for table `tata_kelola`
--

CREATE TABLE `tata_kelola` (
  `id_tata_kelola` int NOT NULL,
  `nama_tata_kelola` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `link` text COLLATE utf8mb3_swedish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `tata_kelola`
--

INSERT INTO `tata_kelola` (`id_tata_kelola`, `nama_tata_kelola`, `link`, `created_at`, `updated_at`) VALUES
(8, 'ABCD1', 'https://gemini.google.com/', '2025-10-02 07:39:30', '2025-10-02 07:39:30');

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
(11, 'fatimahzahro1008@gmail.com', 'fatimah zahro', '83d1ce17786b733b695b2a189bb708bc', NULL, 'masyarakat', 6),
(12, 'fitria12@gmail.com', 'fitria', '83d1ce17786b733b695b2a189bb708bc', 'Petugas', 'petugas', NULL),
(13, 'wara.ulan17@gmail.com', 'waraulan', '25f9e794323b453885f5181f1b624d0b', NULL, 'masyarakat', 7),
(14, 'khairulhuda242@gmail.com', 'Khairul Huda', '25d55ad283aa400af464c76d713c07ad', NULL, 'masyarakat', 8);

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
(12, '62895409446565', '123', '2025-09-26 14:59:17', 'terkirim'),
(13, '6281260842677', 'HALO', '2025-09-29 16:49:22', 'arsip'),
(14, '6281377241610', 'APA KABAR COYY?', '2025-10-01 13:43:24', 'gagal'),
(15, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:44:55', 'terjadwal'),
(16, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:44:59', 'terjadwal'),
(17, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:01', 'terjadwal'),
(18, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:02', 'terjadwal'),
(19, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:06', 'terjadwal'),
(20, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:44', 'terjadwal'),
(21, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(22, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(23, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(24, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(25, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(26, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:45', 'terjadwal'),
(27, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(28, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(29, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(30, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(31, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(32, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:46', 'terjadwal'),
(33, '6281260842677', 'Selamat pagi/siang/sore,\r\n\r\nSemoga hari Anda menyenangkan!\r\n\r\nTerima kasih.', '2025-10-01 13:45:47', 'terjadwal');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id_album`);

--
-- Indexes for table `benner`
--
ALTER TABLE `benner`
  ADD PRIMARY KEY (`id_banner`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `biodata_pengguna`
--
ALTER TABLE `biodata_pengguna`
  ADD PRIMARY KEY (`id_biodata`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `fk_dokumen_to_kategori` (`id_kategori`),
  ADD KEY `fk_dokumen_to_pemda` (`id_dokumen_pemda`);

--
-- Indexes for table `dokumen_pemda`
--
ALTER TABLE `dokumen_pemda`
  ADD PRIMARY KEY (`id_dokumen_pemda`),
  ADD KEY `fk_dokumen_kategori` (`id_kategori`);

--
-- Indexes for table `informasi_publik`
--
ALTER TABLE `informasi_publik`
  ADD PRIMARY KEY (`id_informasi_publik`);

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
-- Indexes for table `layanan_informasi_publik`
--
ALTER TABLE `layanan_informasi_publik`
  ADD PRIMARY KEY (`id_layanan`);

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
-- Indexes for table `tata_kelola`
--
ALTER TABLE `tata_kelola`
  ADD PRIMARY KEY (`id_tata_kelola`);

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
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `id_album` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `benner`
--
ALTER TABLE `benner`
  MODIFY `id_banner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `biodata_pengguna`
--
ALTER TABLE `biodata_pengguna`
  MODIFY `id_biodata` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dokumen_pemda`
--
ALTER TABLE `dokumen_pemda`
  MODIFY `id_dokumen_pemda` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `informasi_publik`
--
ALTER TABLE `informasi_publik`
  MODIFY `id_informasi_publik` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `layanan_informasi_publik`
--
ALTER TABLE `layanan_informasi_publik`
  MODIFY `id_layanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id_permohonan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
-- AUTO_INCREMENT for table `tata_kelola`
--
ALTER TABLE `tata_kelola`
  MODIFY `id_tata_kelola` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `wagateway`
--
ALTER TABLE `wagateway`
  MODIFY `id_wagateway` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_dokumen_to_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dokumen_to_pemda` FOREIGN KEY (`id_dokumen_pemda`) REFERENCES `dokumen_pemda` (`id_dokumen_pemda`) ON DELETE SET NULL ON UPDATE CASCADE;

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
