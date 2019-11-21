-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2019 at 06:16 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ujian_dbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblx_item_soal`
--

CREATE TABLE `tblx_item_soal` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_soal` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_soal` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P',
  `soal` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi` text COLLATE utf8mb4_unicode_ci,
  `benar` tinyint(4) DEFAULT NULL,
  `acak_opsi` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_jadwal_ujian`
--

CREATE TABLE `tblx_jadwal_ujian` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_soal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mulai_ujian` datetime NOT NULL,
  `selesai_ujian` datetime NOT NULL,
  `lama_ujian` int(11) NOT NULL,
  `sesi_ujian` int(11) NOT NULL,
  `ruang_ujian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acak_soal` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `tampil_nilai` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `aktif` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_kelas`
--

CREATE TABLE `tblx_kelas` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_login`
--

CREATE TABLE `tblx_login` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noujian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soal_ujian` text COLLATE utf8mb4_unicode_ci,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_mapel`
--

CREATE TABLE `tblx_mapel` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_sekolah`
--

CREATE TABLE `tblx_sekolah` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `propinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kodepos` int(10) UNSIGNED DEFAULT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kop_kartu` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dept_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_siswa`
--

CREATE TABLE `tblx_siswa` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noujian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `real_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_soal`
--

CREATE TABLE `tblx_soal` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bobot` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_tes_ujian`
--

CREATE TABLE `tblx_tes_ujian` (
  `id` int(10) UNSIGNED NOT NULL,
  `noujian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_soal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soal_item` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi` text COLLATE utf8mb4_unicode_ci,
  `jawaban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblx_users`
--

CREATE TABLE `tblx_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblx_users`
--

INSERT INTO `tblx_users` (`id`, `uuid`, `username`, `password`, `nama`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'c657bd8f-ac97-4b8a-a8b4-36282e366906', 'admin', '$2y$10$ZWO2kKteN8e/o/Jbkzd0LOAH1k/wZDrCxRnRsFcESQA3citSFXlRe', 'Administrator', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblx_item_soal`
--
ALTER TABLE `tblx_item_soal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_item_soal_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_jadwal_ujian`
--
ALTER TABLE `tblx_jadwal_ujian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_jadwal_ujian_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_kelas`
--
ALTER TABLE `tblx_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_kelas_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_login`
--
ALTER TABLE `tblx_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_login_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_mapel`
--
ALTER TABLE `tblx_mapel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_mapel_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_sekolah`
--
ALTER TABLE `tblx_sekolah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_sekolah_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_siswa`
--
ALTER TABLE `tblx_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_siswa_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_soal`
--
ALTER TABLE `tblx_soal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_soal_uuid_unique` (`uuid`);

--
-- Indexes for table `tblx_tes_ujian`
--
ALTER TABLE `tblx_tes_ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblx_users`
--
ALTER TABLE `tblx_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tblx_users_uuid_unique` (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblx_item_soal`
--
ALTER TABLE `tblx_item_soal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_jadwal_ujian`
--
ALTER TABLE `tblx_jadwal_ujian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_kelas`
--
ALTER TABLE `tblx_kelas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_login`
--
ALTER TABLE `tblx_login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_mapel`
--
ALTER TABLE `tblx_mapel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_sekolah`
--
ALTER TABLE `tblx_sekolah`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_siswa`
--
ALTER TABLE `tblx_siswa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_soal`
--
ALTER TABLE `tblx_soal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_tes_ujian`
--
ALTER TABLE `tblx_tes_ujian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblx_users`
--
ALTER TABLE `tblx_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
