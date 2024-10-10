-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 06:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjamanbarang1`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang_lab`
--

CREATE TABLE `barang_lab` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kondisi` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_lab`
--

INSERT INTO `barang_lab` (`id_barang`, `nama_barang`, `stok`, `kondisi`, `foto`) VALUES
(4, 'Keyboard LOGITECH', 29, 'Sangat Baik', 'logi.jpg'),
(5, 'Pc DELL', 30, 'Sangat Baik', 'del.jpg'),
(6, 'Monitor LG', 30, 'Sangat Baik', 'lg-en33-led-lcd-monitor-large01.jpg'),
(7, 'Mouse LOGITECH', 29, 'Sangat Baik', 'g203-hero.jpg'),
(8, 'printer EPSON', 30, 'Sangat Baik', 'R.jpg'),
(14, 'Headset LOGITECH', 14, 'Sangat Baik', 'pro-headset-hero.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_barang`, `id_user`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(23, 4, 7, '2024-09-26', '2024-09-26', 'dikembalikan'),
(24, 6, 7, '2024-09-27', '2024-09-27', 'dikembalikan'),
(25, 4, 7, '2024-10-01', '2024-10-01', 'dikembalikan'),
(26, 4, 7, '2024-10-01', '2024-10-01', 'dikembalikan'),
(27, 4, 5, '2024-10-01', '2024-10-01', 'dikembalikan'),
(28, 8, 5, '2024-10-01', '2024-10-01', 'dikembalikan'),
(29, 5, 7, '2024-10-01', '2024-10-01', 'dikembalikan'),
(30, 14, 7, '2024-10-07', '2024-10-07', 'dikembalikan'),
(31, 8, 7, '2024-10-07', '2024-10-07', 'dikembalikan'),
(32, 14, 7, '2024-10-07', '2024-10-08', 'dikembalikan'),
(33, 14, 7, '2024-10-08', '2024-10-08', 'dikembalikan'),
(34, 14, 7, '2024-10-08', '2024-10-08', 'dikembalikan'),
(35, 14, 7, '2024-10-08', '2024-10-08', 'dikembalikan'),
(36, 5, 7, '2024-10-08', '2024-10-08', 'dikembalikan'),
(37, 14, 7, '2024-10-09', NULL, 'dipinjam'),
(38, 4, 7, '2024-10-09', NULL, 'dipinjam'),
(39, 7, 9, '2024-10-10', NULL, 'dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Genius Divine', 'gedivine', 'gediv1234', 'siswa'),
(2, 'Fabio Tubagus', 'tubagus', 'bagus1234', 'siswa'),
(3, 'Atha Syahputra', 'syahputra', 'putra1234', 'siswa'),
(4, 'Farrel Teguh', 'teguh', 'teguh1234', 'siswa'),
(5, 'grandio', 'dio12345', '80f8348f72e22c2662ea56662c362fdccfda55ebb4c9a0038e8fd4c22e1269eb', 'siswa'),
(6, 'Kartika M.K', 'kartika@gmail.com', 'ecc590d6482056dfea97997fcb3342e522f36de144c1b59802a0e69f5061ec46', 'Admin'),
(7, 'Divine', 'divine123', '51ccf498bd89b034fa19b4685c38032a21aeb10559b054d8a9cc0e6eac082a38', 'siswa'),
(8, 'bimalaksana', 'bimalaksana', 'bima12345', 'admin'),
(9, 'Daffa Satria ', 'daffa123', '7377d263b2a896c22cc48f1af8f750f141e0f6419c2be83b8d90916520a0a926', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang_lab`
--
ALTER TABLE `barang_lab`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_lab`
--
ALTER TABLE `barang_lab`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang_lab` (`id_barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
