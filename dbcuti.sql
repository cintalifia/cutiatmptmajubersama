-- Database: dbcuti
-- Struktur dan data awal sistem E-Cuti PT Maju Bersama

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `dbcuti` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dbcuti`;

DROP TABLE IF EXISTS `approvecuti`;
CREATE TABLE `approvecuti` (
  `idapprovecuti` varchar(10) NOT NULL,
  `idpengajuancuti` varchar(10) NOT NULL,
  `tanggalapprove` date NOT NULL,
  `approveby` varchar(50) NOT NULL,
  PRIMARY KEY (`idapprovecuti`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jeniscuti`;
CREATE TABLE `jeniscuti` (
  `idcuti` varchar(5) NOT NULL,
  `jeniscuti` varchar(30) NOT NULL,
  PRIMARY KEY (`idcuti`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `jeniscuti` (`idcuti`, `jeniscuti`) VALUES
('CT001', 'Sakit'),
('CT002', 'Urusan Keluarga'),
('CT003', 'Melahirkan'),
('CT004', 'Tahunan');

DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE `karyawan` (
  `nik` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `divisi` varchar(50) NOT NULL,
  `level` varchar(20) NOT NULL,
  `sisacuti` int(11) NOT NULL DEFAULT 12,
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `karyawan` (`nik`, `nama`, `divisi`, `level`, `sisacuti`) VALUES
('1234', 'Anas', 'PSDM', 'Manager', 12),
('2012231035', 'Cinta Alifia Putri', 'Bendahara', 'Direktur', 20),
('2012231045', 'Davia Sherin', 'PPM', 'Direktur', 10),
('2012231007', 'Fatmah Rohmah Tika', 'PSDM', 'Direktur', 12);

DROP TABLE IF EXISTS `pengajuancuti`;
CREATE TABLE `pengajuancuti` (
  `idpengajuancuti` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `idcuti` varchar(10) NOT NULL,
  `tanggalpengajuan` date NOT NULL,
  `tanggalmulai` date NOT NULL,
  `lamacuti` int(11) NOT NULL,
  `alasancuti` varchar(100) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`idpengajuancuti`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `pengajuancuti` (`idpengajuancuti`, `nik`, `idcuti`, `tanggalpengajuan`, `tanggalmulai`, `lamacuti`, `alasancuti`, `status`) VALUES
('PC001', '2012231007', 'CT002', '2026-07-07', '2026-07-07', 3, 'Urusan Keluarga', 'Approved'),
('PC002', '2012231035', 'CT001', '2026-07-08', '2026-07-08', 3, 'Sakit', 'Rejected'),
('PC003', '2012231035', 'CT002', '2026-07-08', '2026-07-08', 2, 'Urusan Keluarga', 'Rejected');

DROP TABLE IF EXISTS `userlogin`;
CREATE TABLE `userlogin` (
  `username` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `userlogin` (`username`, `password`) VALUES
('admin', 'admin'),
('1234', '123456'),
('2012231035', '123456'),
('2012231045', '123456'),
('2012231007', '123456');

INSERT INTO `approvecuti` (`idapprovecuti`, `idpengajuancuti`, `tanggalapprove`, `approveby`) VALUES
('AP001', 'PC001', '2026-07-08', 'Administrator');
