-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2021 at 09:02 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kata_dasar`
--

CREATE TABLE `kata_dasar` (
  `kata_id` int(11) NOT NULL,
  `term` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kata_kunci`
--

CREATE TABLE `kata_kunci` (
  `id` int(10) NOT NULL,
  `kata` text CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kata_kunci`
--

INSERT INTO `kata_kunci` (`id`, `kata`) VALUES
(1, 'certainty'),
(2, 'factor'),
(3, 'augmented'),
(4, 'reality'),
(5, 'sistem'),
(6, 'aplikasi'),
(7, 'cloud'),
(8, 'server'),
(9, 'mirroring'),
(10, 'nextcloud'),
(11, 'informasi'),
(12, 'web'),
(13, 'forward'),
(14, 'chaining'),
(15, 'automatic'),
(16, 'testing'),
(17, 'semantic'),
(18, 'similarity'),
(19, 'consine'),
(20, 'fuzzy'),
(21, 'mikrotik'),
(22, 'mobile'),
(23, 'simple'),
(24, 'additive'),
(25, 'weighting'),
(26, 'jaringan'),
(27, 'naive'),
(28, 'bayes'),
(29, 'hybrid'),
(30, 'android'),
(31, 'linux'),
(32, 'product'),
(33, 'weighted'),
(34, 'tsukamto'),
(35, 'computer');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(10) NOT NULL,
  `nama` varchar(20) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(1, 'AI'),
(2, 'RPL'),
(3, 'Jaringan');

-- --------------------------------------------------------

--
-- Table structure for table `skripsi1`
--

CREATE TABLE `skripsi1` (
  `id` int(11) NOT NULL,
  `judul_skripsi` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `jurusan` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `skripsi1`
--

INSERT INTO `skripsi1` (`id`, `judul_skripsi`, `tahun`, `jurusan`, `kategori`) VALUES
(1, 'Implementasi AUGMENTED REALITY dalam Pengenalan Sistem Tata Surya\r\n', 2019, 'Teknik Informatika', 1),
(2, 'Pembuatan Aplikasi DAY CARE Service untuk Lansia Berbasis Mobile (Studi Kasus : Muhammadiyah Senior Club)\r\n', 2019, 'Teknik Informatika', 2),
(3, 'Rancang Bangun CLOUD Server dan MIRRORING Menggunakan NEXTCLOUD\r\n', 2019, 'Teknik Informatika', 3),
(4, 'Sistem Informasi Pemesanan Percetakan Pada CV Sinar Semesta (SINSE) Berbasis WEB\r\n', 2019, 'Teknik Informatika', 2),
(5, 'Perancangan Aplikasi Penyaluran Dana Program Keluarga Harapan (Studi Kasus : Kelurahan Pekayon Jaya)\r\n', 2019, 'Teknik Informatika', 2),
(6, 'Sistem Pendeteksi Dini Kesehatan Mental Emosional Anak Usia 4-17 Menggunakan Metode FORWARD CHAINING\r\n', 2019, 'Teknik Informatika', 1),
(7, 'Pembuatan TOOLS AUTOMATIC Penetrasi TESTING Untuk Menguji Sistem Keamanan Server\r\n', 2019, 'Teknik Informatika', 3),
(8, 'Perancangan Web Portal MARKETPLACE Produk Usaha Mikro dan Menengah (UMKM)\r\n', 2019, 'Teknik Informatika', 2),
(9, 'Pembuatan Aplikasi Identifikasi Tingkat Depresi Pada Lansia dengan Menggunakan Metode NAÏVE BAYES Berbasis WEB\r\n', 2019, 'Teknik Informatika', 1),
(10, 'Implementasi Penggunaan SEMANTIC SIMILARITY Untuk Menentukan Kemiripan Jawaban ESSAY\r\n', 2019, 'Teknik Informatika', 1),
(11, 'Rancang Bangun Penggunaan Portsentry untuk mengamankan Server dan Aktifitas Scaning\r\n', 2019, 'Teknik Informatika', 3),
(12, 'Sistem Informasi Penyedia Layanan Bazzar untuk UMKM di Wilayah Kemayoran Berbasis WEB\r\n', 2019, 'Teknik Informatika', 2),
(13, 'Aplikasi SKRINING Gizi Anak Menggunakan Metode FORWARD CHAINING (Studi Kasus : Puskesmas Cakung Jakarta Timur)\r\n', 2019, 'Teknik Informatika', 1),
(14, 'Sistem Informasi Pembuatan Perkembangan Akademik Siswa (Studi Kasus : MTS.N 15 JAKARTA\r\n', 2019, 'Teknik Informatika', 2),
(15, 'Pembuatan MAIL Server Berbasis LINUX Menggunakan MTA (MAIL TRANSFER AGENT)\r\n', 2019, 'Teknik Informatika', 3),
(16, 'Aplikasi Deteksi Kemiripan Judul Skripsi dengan Metode Cosine Similarity berbasis Web\r\n', 2020, 'Teknik Informatika', 1),
(17, 'Sistem Informasi Penjualan untuk Distributor Air Minum\r\n', 2020, 'Teknik Informatika', 2),
(18, 'Sistem Pendeteksi Penyakit Kolestrol Pada Remaja menggunakan Metode CERTAINTY FACTOR\r\n', 2020, 'Teknik Informatika', 1),
(19, 'Sistem Informasi Penjualan Pada DEZU SHOP Berbasis HYBRID\r\n', 2020, 'Teknik Informatika', 2),
(20, 'Identifikasi Gangguan Kepribadian HISTRONIK menggunakan Metode CERTAINTY FACTOR\r\n', 2020, 'Teknik Informatika', 1),
(21, 'Menentukan Stres dan Depresi dengan Metode Certainty Factor (Studi Kasus FT-UMJ)\r\n', 2020, 'Teknik Informatika', 1),
(22, 'Aplikasi Media Konsultasi Ikan Cupang Berbasis Android menggunakan CHATBOT dengan Metode COSINE SIMILARITY\r\n', 2020, 'Teknik Informatika', 1),
(23, 'Sistem Pendukung Keputusan Deteksi masalah Kehamilan dengan menggunakan Metode FORWARD CHAINING\r\n', 2020, 'Teknik Informatika', 1),
(24, 'Aplikasi Pendeteksi Kecanduan HANPHONE menggunakan Metode FUZZY\r\n', 2020, 'Teknik Informatika', 1),
(25, 'Aplikasi Penggajian Pegawai Honorer Berbasis HYBIRD\r\n', 2020, 'Teknik Informatika', 2),
(26, 'Pembuatan WEB Pelaporan dan Pendataan Komputer Rusak Pada Gedung DPR-RI menggunakan Metode FORWARD CHAINING\r\n', 2020, 'Teknik Informatika', 1),
(27, 'Aplikasi Pembelajaran TAEKWONDO menggunakan AUGMENTED REALITY Berbasis Android\r\n', 2020, 'Teknik Informatika', 1),
(28, 'Penentuan Kenaikan Jabatan menggunakan Metode SIMPLE ADDITIVE WEIGHTING dan FUZZY LOGIC TSUKAMTO\r\n', 2020, 'Teknik Informatika', 1),
(29, 'Sistem Pengambilan Keputusan Penerimaan Karyawan Baru menggunakan WP (WEIGHTED PRODUCT) dan SAW (Simple Additive Weighting) di PT. IRADAT\r\n', 2020, 'Teknik Informatika', 1),
(30, 'Perancangan Sistem Pendeteksi Penyusup Pada Jaringan Komputer menggunakan IDS (Instrusion Detection Sistem) Pada MIKROTIK melalui TELEGRAM\r\n', 2020, 'Teknik Informatika', 3);

-- --------------------------------------------------------

--
-- Table structure for table `skripsi_backup`
--

CREATE TABLE `skripsi_backup` (
  `id` int(10) NOT NULL,
  `judul_skripsi` varchar(200) CHARACTER SET latin1 NOT NULL,
  `tahun` year(4) NOT NULL,
  `jurusan` varchar(50) CHARACTER SET latin1 NOT NULL,
  `nama` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `skripsi_backup`
--

INSERT INTO `skripsi_backup` (`id`, `judul_skripsi`, `tahun`, `jurusan`, `nama`) VALUES
(1, 'Implementasi AUGMENTED REALITY dalam Pengenalan Sistem Tata Surya\r\n', 2019, 'Teknik Informatika', 0),
(2, 'Pembuatan Aplikasi DAY CARE Service untuk Lansia Berbasis Mobile (Studi Kasus : Muhammadiyah Senior Club)\r\n', 2019, 'Teknik Informatika', 0),
(3, 'Rancang Bangun CLOUD Server dan MIRRORING Menggunakan NEXTCLOUD\r\n', 2019, 'Teknik Informatika', 0),
(4, 'Sistem Informasi Pemesanan Percetakan Pada CV Sinar Semesta (SINSE) Berbasis WEB\r\n', 2019, 'Teknik Informatika', 0),
(5, 'Perancangan Aplikasi Penyaluran Dana Program Keluarga Harapan (Studi Kasus : Kelurahan Pekayon Jaya)\r\n', 2019, 'Teknik Informatika', 0),
(6, 'Sistem Pendeteksi Dini Kesehatan Mental Emosional Anak Usia 4-17 Menggunakan Metode FORWARD CHAINING\r\n', 2019, 'Teknik Informatika', 0),
(7, 'Pembuatan TOOLS AUTOMATIC Penetrasi TESTING Untuk Menguji Sistem Keamanan Server\r\n', 2019, 'Teknik Informatika', 0),
(8, 'Perancangan Web Portal MARKETPLACE Produk Usaha Mikro dan Menengah (UMKM)\r\n', 2019, 'Teknik Informatika', 0),
(9, 'Pembuatan Aplikasi Identifikasi Tingkat Depresi Pada Lansia dengan Menggunakan Metode NAÏVE BAYES Berbasis WEB\r\n', 2019, 'Teknik Informatika', 0),
(10, 'Implementasi Penggunaan SEMANTIC SIMILARITY Untuk Menentukan Kemiripan Jawaban ESSAY\r\n', 2019, 'Teknik Informatika', 0),
(11, 'Rancang Bangun Penggunaan Portsentry untuk mengamankan Server dan Aktifitas Scaning\r\n', 2019, 'Teknik Informatika', 0),
(12, 'Sistem Informasi Penyedia Layanan Bazzar untuk UMKM di Wilayah Kemayoran Berbasis WEB\r\n', 2019, 'Teknik Informatika', 0),
(13, 'Aplikasi SKRINING Gizi Anak Menggunakan Metode FORWARD CHAINING (Studi Kasus : Puskesmas Cakung Jakarta Timur)\r\n', 2019, 'Teknik Informatika', 0),
(14, 'Sistem Informasi Pembuatan Perkembangan Akademik Siswa (Studi Kasus : MTS.N 15 JAKARTA\r\n', 2019, 'Teknik Informatika', 0),
(15, 'Pembuatan MAIL Server Berbasis LINUX Menggunakan MTA (MAIL TRANSFER AGENT)\r\n', 2019, 'Teknik Informatika', 0),
(16, 'Aplikasi Deteksi Kemiripan Judul Skripsi dengan Metode Cosine Similarity berbasis Web\r\n', 2020, 'Teknik Informatika', 0),
(17, 'Sistem Informasi Penjualan untuk Distributor Air Minum\r\n', 2020, 'Teknik Informatika', 0),
(18, 'Sistem Pendeteksi Penyakit Kolestrol Pada Remaja menggunakan Metode CERTAINTY FACTOR\r\n', 2020, 'Teknik Informatika', 0),
(19, 'Sistem Informasi Penjualan Pada DEZU SHOP Berbasis HYBRID\r\n', 2020, 'Teknik Informatika', 0),
(20, 'Identifikasi Gangguan Kepribadian HISTRONIK menggunakan Metode CERTAINTY FACTOR\r\n', 2020, 'Teknik Informatika', 0),
(21, 'Menentukan Stres dan Depresi dengan Metode Certainty Factor (Studi Kasus FT-UMJ)\r\n', 2020, 'Teknik Informatika', 0),
(22, 'Aplikasi Media Konsultasi Ikan Cupang Berbasis Android menggunakan CHATBOT dengan Metode COSINE SIMILARITY\r\n', 2020, 'Teknik Informatika', 0),
(23, 'Sistem Pendukung Keputusan Deteksi masalah Kehamilan dengan menggunakan Metode FORWARD CHAINING\r\n', 2020, 'Teknik Informatika', 0),
(24, 'Aplikasi Pendeteksi Kecanduan HANPHONE menggunakan Metode FUZZY\r\n', 2020, 'Teknik Informatika', 0),
(25, 'Aplikasi Penggajian Pegawai Honorer Berbasis HYBIRD\r\n', 2020, 'Teknik Informatika', 0),
(26, 'Pembuatan WEB Pelaporan dan Pendataan Komputer Rusak Pada Gedung DPR-RI menggunakan Metode FORWARD CHAINING\r\n', 2020, 'Teknik Informatika', 0),
(27, 'Aplikasi Pembelajaran TAEKWONDO menggunakan AUGMENTED REALITY Berbasis Android\r\n', 2020, 'Teknik Informatika', 0),
(28, 'Penentuan Kenaikan Jabatan menggunakan Metode SIMPLE ADDITIVE WEIGHTING dan FUZZY LOGIC TSUKAMTO\r\n', 2020, 'Teknik Informatika', 0),
(29, 'Sistem Pengambilan Keputusan Penerimaan Karyawan Baru menggunakan WP (WEIGHTED PRODUCT) dan SAW (Simple Additive Weighting) di PT. IRADAT\r\n', 2020, 'Teknik Informatika', 0),
(30, 'Perancangan Sistem Pendeteksi Penyusup Pada Jaringan Komputer menggunakan IDS (Instrusion Detection Sistem) Pada MIKROTIK melalui TELEGRAM\r\n', 2020, 'Teknik Informatika', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tes`
--

CREATE TABLE `tbl_tes` (
  `id` int(11) NOT NULL,
  `kategori` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(50) CHARACTER SET latin1 NOT NULL,
  `password` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kata_dasar`
--
ALTER TABLE `kata_dasar`
  ADD PRIMARY KEY (`kata_id`);

--
-- Indexes for table `kata_kunci`
--
ALTER TABLE `kata_kunci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skripsi1`
--
ALTER TABLE `skripsi1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori` (`kategori`);

--
-- Indexes for table `skripsi_backup`
--
ALTER TABLE `skripsi_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tes`
--
ALTER TABLE `tbl_tes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori` (`kategori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kata_dasar`
--
ALTER TABLE `kata_dasar`
  MODIFY `kata_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kata_kunci`
--
ALTER TABLE `kata_kunci`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skripsi1`
--
ALTER TABLE `skripsi1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `skripsi_backup`
--
ALTER TABLE `skripsi_backup`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_tes`
--
ALTER TABLE `tbl_tes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
