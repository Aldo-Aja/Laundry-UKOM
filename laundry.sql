-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 17, 2025 at 06:42 AM
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
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int NOT NULL,
  `id_transaksi` int DEFAULT NULL,
  `id_paket` int DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tlp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`id`, `nama`, `alamat`, `jenis_kelamin`, `tlp`) VALUES
(18, 'Muhammad Erlangga Putra Witanto', 'Ceger Jaktim', 'L', '0182742172'),
(19, 'Dimas', 'Bekasi', 'L', '081627328291'),
(20, 'Firman', 'Condet', 'L', '08129831'),
(21, 'Aldo', 'Wahana Pondok Gede, Bekasi', 'L', '085156230199');

-- --------------------------------------------------------

--
-- Table structure for table `tb_outlet`
--

CREATE TABLE `tb_outlet` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text,
  `tlp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_outlet`
--

INSERT INTO `tb_outlet` (`id`, `nama`, `alamat`, `tlp`) VALUES
(1, 'Outlet Bekasi', 'Jl. Raya Bekasi no 34, Jawa Barat', '0812345678'),
(2, 'Outlet Jakarta', 'Jl. Cipayung', '0182742172'),
(3, 'Outlet Jogja', 'Jl. Malioboro', '08627482917');

-- --------------------------------------------------------

--
-- Table structure for table `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id` int NOT NULL,
  `id_outlet` int DEFAULT NULL,
  `jenis` enum('kiloan','selimut','bed_cover','kaos','lain') DEFAULT NULL,
  `nama_paket` varchar(100) DEFAULT NULL,
  `harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_paket`
--

INSERT INTO `tb_paket` (`id`, `id_outlet`, `jenis`, `nama_paket`, `harga`) VALUES
(1, 1, 'kiloan', 'Cuci & Gosok', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int NOT NULL,
  `id_outlet` int DEFAULT NULL,
  `kode_invoice` varchar(100) DEFAULT NULL,
  `id_member` int DEFAULT NULL,
  `id_paket` int DEFAULT NULL,
  `tgl` datetime DEFAULT NULL,
  `batas_waktu` datetime DEFAULT NULL,
  `tgl_bayar` datetime DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `diskon` double DEFAULT NULL,
  `berat` double DEFAULT NULL,
  `pajak` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `status` enum('baru','proses','selesai','diambil') DEFAULT NULL,
  `dibayar` enum('dibayar','belum_dibayar') DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `id_outlet`, `kode_invoice`, `id_member`, `id_paket`, `tgl`, `batas_waktu`, `tgl_bayar`, `biaya_tambahan`, `diskon`, `berat`, `pajak`, `total_harga`, `status`, `dibayar`, `id_user`) VALUES
(19, 2, 'INV20250213022137698', 18, 1, '2025-02-13 09:21:00', '2025-02-25 09:21:00', NULL, 0, 0, NULL, 75, 10075, 'proses', 'belum_dibayar', 1),
(20, 3, 'INV20250213022448623', 20, 1, '2025-02-13 09:24:00', '2025-02-20 09:24:00', NULL, 0, 0, NULL, 75, 10075, 'selesai', 'dibayar', 1),
(25, 1, '0', 19, 1, '2025-02-16 09:27:00', '2025-02-23 09:27:00', NULL, 0, 0, NULL, 75, 10075, 'baru', 'belum_dibayar', 1),
(26, 1, '0', 18, 1, '2025-02-16 09:33:00', '2025-02-24 09:33:00', NULL, 0, 0, NULL, 75, 10075, 'proses', 'dibayar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int NOT NULL,
  `id_outlet` int DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` text,
  `nama` varchar(100) DEFAULT NULL,
  `role` enum('admin','kasir','owner') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `id_outlet`, `username`, `password`, `nama`, `role`) VALUES
(1, 1, 'Admin', '12345', 'Admin Bekasi', 'admin'),
(2, 2, 'Kasir', '12345', 'Kasir', 'kasir'),
(3, 3, 'Owner', '12345', 'Owner', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_outlet`
--
ALTER TABLE `tb_outlet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_outlet` (`id_outlet`),
  ADD KEY `id_member` (`id_member`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `fk_paket` (`id_paket`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_outlet`
--
ALTER TABLE `tb_outlet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_paket`
--
ALTER TABLE `tb_paket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`),
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id`);

--
-- Constraints for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD CONSTRAINT `tb_paket_ibfk_1` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_paket` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id`),
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`),
  ADD CONSTRAINT `tb_transaksi_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `tb_member` (`id`),
  ADD CONSTRAINT `tb_transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
