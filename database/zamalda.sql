-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 21, 2025 at 08:03 AM
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
-- Database: `zamalda`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `idBarang` int NOT NULL,
  `kodeBarang` varchar(20) NOT NULL,
  `kodeKategori` varchar(20) NOT NULL,
  `namaBarang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`idBarang`, `kodeBarang`, `kodeKategori`, `namaBarang`) VALUES
(1, 'br001', 'kat001', 'korean '),
(2, ' br002', 'kat002', 'Jeans lucu gemoy'),
(3, 'br003', 'kat003', 'Hoodie Hitam '),
(4, 'br004', 'kat003', 'Kemeja Kotak Lengan Panjang'),
(5, 'br005', 'kat004', 'Celana Panjang Pria Hitam'),
(6, 'br006', 'kat001', 'kaos polos');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail_transaksi` int NOT NULL,
  `kodeTransaksi` varchar(20) NOT NULL,
  `kodeBarang` varchar(20) NOT NULL,
  `tglTransaksi` timestamp NOT NULL,
  `status` enum('belum dibayar','dikemas','dikirim') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail_transaksi`, `kodeTransaksi`, `kodeBarang`, `tglTransaksi`, `status`) VALUES
(1, 'tr001', 'br001', '2025-05-06 20:01:04', 'belum dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `kategoribarang`
--

CREATE TABLE `kategoribarang` (
  `idKategori` int NOT NULL,
  `kodeKategori` varchar(20) NOT NULL,
  `namaKategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategoribarang`
--

INSERT INTO `kategoribarang` (`idKategori`, `kodeKategori`, `namaKategori`) VALUES
(1, 'kat001', 'wanita - atasan'),
(2, 'kat002', 'wanita - bawahan'),
(3, 'kat003', 'pria - atasan'),
(4, 'kat004', 'pria - bawahan');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `idPelanggan` int NOT NULL,
  `kodePelanggan` varchar(10) NOT NULL,
  `namaPelanggan` varchar(50) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `noTelp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`idPelanggan`, `kodePelanggan`, `namaPelanggan`, `foto`, `noTelp`, `email`, `alamat`) VALUES
(1, 'plg001', 'Mark', 'mark.JPEG', '09876531', 'mark@gmail.com', 'Jl. Kwangya 1000');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `idPengguna` int NOT NULL,
  `kodePengguna` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` varchar(20) NOT NULL,
  `status` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`idPengguna`, `kodePengguna`, `username`, `password`, `level`, `status`) VALUES
(1, 'pnjl001', 'Lee Jeno', '123', 'penjual', '1'),
(2, 'plg001', 'Mark', '123', 'Pelanggan', '1');

-- --------------------------------------------------------

--
-- Table structure for table `penjual`
--

CREATE TABLE `penjual` (
  `idPenjual` int NOT NULL,
  `kodePenjual` varchar(20) NOT NULL,
  `namaPenjual` varchar(50) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `noTelp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penjual`
--

INSERT INTO `penjual` (`idPenjual`, `kodePenjual`, `namaPenjual`, `foto`, `alamat`, `noTelp`) VALUES
(1, 'pnjl001', 'Lee Jeno', 'jeno.png', 'Jl. planet101', '089522');

-- --------------------------------------------------------

--
-- Table structure for table `profil_aplikasi`
--

CREATE TABLE `profil_aplikasi` (
  `id` int NOT NULL,
  `nama_aplikasi` varchar(30) NOT NULL,
  `alamat` varchar(30) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `website` varchar(50) NOT NULL,
  `logo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profil_aplikasi`
--

INSERT INTO `profil_aplikasi` (`id`, `nama_aplikasi`, `alamat`, `no_telp`, `website`, `logo`) VALUES
(1, 'zamelda', 'Jl. Kwangya 101', '098765432', 'www.zamelda-shop.com', 'ADS.png');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `idTransaksi` int NOT NULL,
  `kodeTransaksi` varchar(10) NOT NULL,
  `kodePelanggan` varchar(10) NOT NULL,
  `tanggal` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`idTransaksi`, `kodeTransaksi`, `kodePelanggan`, `tanggal`) VALUES
(1, 'tr001', 'plg001', '2025-05-06 19:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `varianbarang`
--

CREATE TABLE `varianbarang` (
  `idVarian` int NOT NULL,
  `kodeVarian` varchar(20) NOT NULL,
  `kodeBarang` varchar(20) NOT NULL,
  `typeVarian` varchar(100) NOT NULL,
  `size` varchar(20) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `stok` int NOT NULL,
  `gambarBarang` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `varianbarang`
--

INSERT INTO `varianbarang` (`idVarian`, `kodeVarian`, `kodeBarang`, `typeVarian`, `size`, `harga`, `stok`, `gambarBarang`) VALUES
(1, 'var001', 'br001', 'pink', 'L', '200000', 12, 'TONIQUE.JPG'),
(2, 'var002', ' br002', 'pink', '30', '120000', 10, 'pinkJeans.JPG'),
(3, 'var003', 'br003', 'hitam', 'all size L', '750000', 5, 'blackHooedie.JPG'),
(4, 'var004', 'br004', 'lengan panjang', 'fit to L', '56000', 120, 'kemejaKotak.JPG'),
(5, 'var005', 'br005', 'celana hitam', '40', '299999', 20, 'shortPantsBlack.JPG'),
(6, 'var006', 'br006', 'putih', 'fit to L', '20000', 20, 'kaos-putih.JPEG'),
(7, 'var007', 'br006', 'pink', 'XL', '250000', 2, 'kaos-pink.JPEG'),
(8, 'var008', 'br006', 'kuning', 'fit to L', '20000', 5, 'kaoss-kuning.JPEG'),
(9, 'var009', 'br006', 'cream', 'fit to L', '210000', 10, 'kaos-cream.JPEG'),
(10, 'var010', 'br006', 'biru', 's', '100000', 20, 'kaos-biru.JPEG'),
(11, 'var011', 'br006', 'hijau', 'fit to L', '200000', 2, 'kaos-hijau.JPEG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`idBarang`),
  ADD UNIQUE KEY `kodeBarang` (`kodeBarang`),
  ADD KEY `kodeKategori` (`kodeKategori`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `kodeBarang` (`kodeBarang`),
  ADD KEY `detail_transaksi_ibfk_2` (`kodeTransaksi`);

--
-- Indexes for table `kategoribarang`
--
ALTER TABLE `kategoribarang`
  ADD PRIMARY KEY (`idKategori`),
  ADD UNIQUE KEY `kodeKategori` (`kodeKategori`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`idPelanggan`),
  ADD UNIQUE KEY `kodePelanggan` (`kodePelanggan`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`idPengguna`),
  ADD UNIQUE KEY `kodePengguna` (`kodePengguna`);

--
-- Indexes for table `penjual`
--
ALTER TABLE `penjual`
  ADD PRIMARY KEY (`idPenjual`),
  ADD UNIQUE KEY `kodePenjual` (`kodePenjual`);

--
-- Indexes for table `profil_aplikasi`
--
ALTER TABLE `profil_aplikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`idTransaksi`),
  ADD UNIQUE KEY `kodeTransaksi` (`kodeTransaksi`),
  ADD KEY `kodePelanggan` (`kodePelanggan`);

--
-- Indexes for table `varianbarang`
--
ALTER TABLE `varianbarang`
  ADD PRIMARY KEY (`idVarian`),
  ADD UNIQUE KEY `kodeVarian` (`kodeVarian`),
  ADD KEY `kodeBarang` (`kodeBarang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `idBarang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategoribarang`
--
ALTER TABLE `kategoribarang`
  MODIFY `idKategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `idPelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `idPengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penjual`
--
ALTER TABLE `penjual`
  MODIFY `idPenjual` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profil_aplikasi`
--
ALTER TABLE `profil_aplikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `idTransaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `varianbarang`
--
ALTER TABLE `varianbarang`
  MODIFY `idVarian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`kodeKategori`) REFERENCES `kategoribarang` (`kodeKategori`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`kodeBarang`) REFERENCES `barang` (`kodeBarang`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`kodeTransaksi`) REFERENCES `transaksi` (`kodeTransaksi`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`kodePelanggan`) REFERENCES `pelanggan` (`kodePelanggan`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `varianbarang`
--
ALTER TABLE `varianbarang`
  ADD CONSTRAINT `varianbarang_ibfk_1` FOREIGN KEY (`kodeBarang`) REFERENCES `barang` (`kodeBarang`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
