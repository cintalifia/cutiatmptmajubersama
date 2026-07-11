-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2016 at 06:08 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cutiatm`
--

-- --------------------------------------------------------

--
-- Table structure for table `ijincuti`
--

CREATE TABLE IF NOT EXISTS `ijincuti` (
  `kdijincuti` varchar(5) NOT NULL,
  `nik` varchar(12) NOT NULL,
  `kd_cuti` varchar(5) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keterangan` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ijincuti`
--


-- --------------------------------------------------------

--
-- Table structure for table `jenis_cuti`
--

CREATE TABLE IF NOT EXISTS `jenis_cuti` (
  `kd_cuti` varchar(5) NOT NULL,
  `nama_cuti` varchar(20) NOT NULL,
  `lama_cuti` varchar(5) NOT NULL,
  `Keterangan` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jenis_cuti`
--


-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `nik` varchar(12) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `divisi` varchar(50) NOT NULL,
  `status_karyawan` varchar(20) NOT NULL,
  `tgl_joinkerja` date NOT NULL,
  `sisa_cuti` int(10) NOT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama`, `divisi`, `status_karyawan`, `tgl_joinkerja`, `sisa_cuti`) VALUES
('120025', 'Adam jordan', 'FSO2', 'Tetap', '2014-05-14', 6),
('120221', 'Hulk Magicion', 'GA2', 'Tetap', '2014-05-01', 9),
('321333', 'Dimitrinov', 'SS22', 'Kontrak', '2014-05-01', 92),
('0', 'er', 'wer', 'Kontrak', '0000-00-00', 3),
('454', 'fd', 'sdf', 'Kontrak', '0000-00-00', 3),
('98989', 'mhmhm', '4', 'Tetap', '0000-00-00', 4),
('89898', 'fgdfg', '4535', 'Kontrak', '0000-00-00', 4545),
('9090', 'dgdfgd', 'fgdg', 'Kontrak', '0000-00-00', 5656),
('656565', 'dgdgdg', 'bcvb', 'Tetap', '0000-00-00', 4),
('3434', '3434', 'fsdf', 'Kontrak', '2016-10-10', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pemakai`
--

CREATE TABLE IF NOT EXISTS `pemakai` (
  `idpemakai` varchar(7) NOT NULL,
  `nik` varchar(12) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `level` varchar(18) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemakai`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
