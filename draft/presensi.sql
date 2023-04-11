-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2023 at 09:28 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absen`
--

INSERT INTO `absen` (`id_absen`, `userid`, `status_id`, `tanggal_absen`, `jam_masuk`, `tgl_keluar`, `jam_keluar`, `keterangan`) VALUES
(10, 123, 1, '2023-03-11', '17:45:32', '2023-03-11', '17:47:55', '33,597 kilometer'),
(15, 124, 3, '2023-03-11', NULL, NULL, NULL, ''),
(16, 124, 1, '2023-03-10', '17:45:32', '2023-03-10', '17:47:55', ''),
(17, 124, 3, '2023-03-12', NULL, '2023-03-12', '17:37:51', ''),
(19, 123, 3, '2023-03-13', NULL, NULL, NULL, 'Saya sakit.'),
(20, 123, 1, '2023-03-19', '22:41:29', '2023-03-19', '22:41:59', '33,597 kilometer'),
(21, 123, 1, '2023-03-20', '13:38:39', '2023-03-20', '13:38:48', '33,597 kilometer'),
(22, 124, 1, '2023-03-20', '22:04:11', NULL, NULL, ''),
(49, 125, 2, '2023-03-23', '17:45:52', '2023-03-23', '17:45:52', 'Saya izin tidak hadir.'),
(63, 125, 1, '2023-03-24', '14:39:43', '2023-03-24', '14:59:46', '33,597 kilometer');

-- --------------------------------------------------------

--
-- Table structure for table `gambar`
--

CREATE TABLE `gambar` (
  `id` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `nama_file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gambar`
--

INSERT INTO `gambar` (`id`, `nip`, `nama_file`) VALUES
(1, 123, '123_2203083.jpg'),
(3, 124, '124_2202072.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `jabatan_id` int(11) NOT NULL,
  `jabatan_nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`jabatan_id`, `jabatan_nama`) VALUES
(1, 'Guru'),
(2, 'Tata Usaha'),
(3, 'PDH');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `guru` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nip`, `password`, `nama`, `jabatan_id`, `guru`) VALUES
(1, 123, '202cb962ac59075b964b07152d234b70', 'Qurrotu Aini', 1, 'SMP SMA'),
(2, 124, 'c8ffe9a587b126f152ed3d89a146b445', 'Hilal SF', 2, 'SMP'),
(12, 125, '3def184ad8f4755ff269862ea77393dd', 'Rayya R', 1, 'SMA');

-- --------------------------------------------------------

--
-- Table structure for table `status_absen`
--

CREATE TABLE `status_absen` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status_absen`
--

INSERT INTO `status_absen` (`id_status`, `nama_status`) VALUES
(1, 'Hadir'),
(2, 'Izin'),
(3, 'Sakit');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `gambar`
--
ALTER TABLE `gambar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`jabatan_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indexes for table `status_absen`
--
ALTER TABLE `status_absen`
  ADD PRIMARY KEY (`id_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `gambar`
--
ALTER TABLE `gambar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `jabatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
