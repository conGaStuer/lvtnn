-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 06, 2024 at 09:05 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lvtn`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `masach` int NOT NULL,
  `madon` int NOT NULL,
  `soluong` int NOT NULL,
  `dongia` float NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_chi_tiet_don_hang` (`masach`,`madon`),
  KEY `chi_tiet_don_hang_ibfk_2` (`madon`)
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`ID`, `masach`, `madon`, `soluong`, `dongia`, `note`) VALUES
(318, 1, 362, 1, 900, NULL),
(319, 4, 362, 2, 600, NULL),
(320, 4, 366, 1, 600, NULL),
(321, 10, 362, 1, 800, NULL),
(322, 66, 362, 1, 900, NULL),
(323, 66, 368, 1, 900, NULL),
(324, 4, 370, 1, 600, NULL),
(325, 66, 370, 1, 900, NULL),
(326, 6, 371, 1, 800, NULL),
(327, 6, 373, 1, 800, NULL),
(328, 5, 371, 1, 900, NULL),
(329, 6, 374, 1, 800, NULL),
(330, 6, 375, 1, 800, NULL),
(331, 6, 376, 1, 800, NULL),
(332, 6, 377, 1, 800, NULL),
(333, 5, 378, 1, 900, NULL),
(334, 4, 379, 7, 600, NULL),
(335, 66, 380, 1, 900, NULL),
(336, 10, 381, 1, 800, NULL),
(337, 66, 381, 1, 900, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

DROP TABLE IF EXISTS `danh_gia`;
CREATE TABLE IF NOT EXISTS `danh_gia` (
  `maDG` int NOT NULL AUTO_INCREMENT,
  `noidung` varchar(255) NOT NULL,
  `ngayDG` date NOT NULL,
  `maND` int NOT NULL,
  `maSach` int NOT NULL,
  `rating` int DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `reply_to` int DEFAULT NULL,
  `is_staff` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`maDG`),
  KEY `maND` (`maND`),
  KEY `danh_gia_ibfk_2` (`maSach`),
  KEY `fk_parent_danh_gia` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danh_gia`
--

INSERT INTO `danh_gia` (`maDG`, `noidung`, `ngayDG`, `maND`, `maSach`, `rating`, `parent_id`, `reply_to`, `is_staff`) VALUES
(10, 'sach rat hay', '2024-06-07', 20, 2, 3, NULL, NULL, 0),
(11, 'sach nay con khong ?', '2024-06-07', 20, 2, 1, NULL, NULL, 0),
(12, 'sach nay rat te', '2024-06-07', 1, 2, 4, NULL, NULL, 0),
(13, 'sach nay hay nha', '2024-06-07', 1, 2, 5, NULL, NULL, 0),
(14, 'saaâ', '2024-06-07', 1, 2, 5, NULL, NULL, 0),
(15, 'sách rất hay, admin rep cmt tôi với', '2024-06-07', 1, 2, 5, NULL, NULL, 0),
(16, 'sahcs nay con khong', '2024-06-07', 1, 6, 3, NULL, NULL, 0),
(17, 'sahcs này có giảm giá k', '2024-06-07', 13, 6, 0, NULL, NULL, 0),
(18, 'dqdqdq', '2024-06-08', 13, 2, 0, NULL, NULL, 0),
(19, '', '2024-06-12', 13, 3, 0, NULL, NULL, 0),
(21, 'sach nay hay qua', '2024-06-14', 14, 4, 5, NULL, NULL, 0),
(22, 'sach hay', '2024-06-14', 1, 4, 4, NULL, NULL, 0),
(23, 'dq', '2024-06-27', 1, 1, 0, NULL, NULL, 0),
(26, 'eth', '2024-06-27', 21, 1, 0, NULL, 23, 1),
(27, 'fgf', '2024-06-27', 21, 1, 0, NULL, 0, 1),
(28, 'dq', '2024-06-27', 21, 1, 0, NULL, 23, 1),
(29, 'qfqffq', '2024-06-27', 21, 1, 0, NULL, 23, 1),
(30, 'q', '2024-06-27', 13, 1, 0, NULL, NULL, 0),
(33, 'dqdqdq', '2024-06-27', 21, 1, 0, NULL, 30, 1),
(34, 'dqdqd', '2024-06-27', 21, 1, 0, NULL, 32, 1),
(35, 'fa', '2024-06-27', 21, 1, 0, NULL, 32, 1),
(36, 'dqdqd', '2024-06-27', 21, 1, 0, NULL, 32, 1),
(37, 'sách rất hay bạn nhé', '2024-06-28', 21, 6, 5, NULL, 20, 1),
(38, 'dqdqdq', '2024-06-28', 21, 6, 0, NULL, 16, 1),
(39, 'bạn cần hỗ trợ gì không', '2024-06-28', 21, 1, 5, NULL, 31, 1),
(40, 'dqdqdqd', '2024-06-28', 21, 1, 0, NULL, 31, 1),
(41, 'êggegegeq', '2024-06-28', 19, 10, 0, NULL, NULL, 0),
(42, 'sbsdbsdb', '2024-06-28', 21, 10, 0, NULL, 41, 1),
(43, 'fqêqf', '2024-06-28', 21, 1, 0, NULL, 30, 1),
(45, 'uhguo', '2024-06-28', 21, 8, 0, NULL, 44, 1),
(46, 'dqdqd', '2024-06-29', 21, 1, 0, NULL, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `maDM` int NOT NULL AUTO_INCREMENT,
  `tenDM` varchar(255) NOT NULL,
  PRIMARY KEY (`maDM`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`maDM`, `tenDM`) VALUES
(2, 'Tự do'),
(4, 'Sáng tạo'),
(5, 'Thiết kế nội thất'),
(6, 'Lịch sử'),
(7, 'Văn học'),
(8, 'Tâm lý'),
(9, 'Âm nhạc'),
(18, 'Truyện Tranh'),
(19, 'kiếm hiệp'),
(20, 'Sức Khỏe'),
(21, 'Nhiếp Ảnh');

-- --------------------------------------------------------

--
-- Table structure for table `dm_sach`
--

DROP TABLE IF EXISTS `dm_sach`;
CREATE TABLE IF NOT EXISTS `dm_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maDM` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_dm_sach` (`maDM`,`maSach`),
  KEY `dm_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dm_sach`
--

INSERT INTO `dm_sach` (`ID`, `maDM`, `maSach`) VALUES
(42, 2, 2),
(4, 2, 5),
(5, 2, 9),
(7, 4, 4),
(8, 4, 7),
(9, 5, 5),
(51, 5, 66),
(10, 6, 51),
(11, 6, 61),
(12, 6, 64),
(31, 7, 9),
(13, 7, 50),
(14, 7, 55),
(15, 7, 57),
(16, 7, 58),
(17, 7, 60),
(18, 7, 62),
(19, 7, 63),
(20, 7, 65),
(21, 7, 67),
(22, 8, 10),
(23, 8, 53),
(24, 8, 56),
(26, 9, 6),
(27, 9, 8),
(28, 9, 49),
(29, 9, 54),
(30, 9, 66),
(59, 20, 3),
(66, 20, 4),
(65, 20, 7),
(61, 21, 1),
(62, 21, 2),
(63, 21, 3),
(64, 21, 7);

-- --------------------------------------------------------

--
-- Table structure for table `don_dat_hang`
--

DROP TABLE IF EXISTS `don_dat_hang`;
CREATE TABLE IF NOT EXISTS `don_dat_hang` (
  `madon` int NOT NULL AUTO_INCREMENT,
  `ngaydat` date NOT NULL,
  `maND` int NOT NULL,
  `trangthai` varchar(255) NOT NULL,
  `diachi` varchar(255) NOT NULL,
  PRIMARY KEY (`madon`),
  KEY `maND` (`maND`)
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `don_dat_hang`
--

INSERT INTO `don_dat_hang` (`madon`, `ngaydat`, `maND`, `trangthai`, `diachi`) VALUES
(362, '2024-07-02', 1, 'giohang', ''),
(366, '2024-07-03', 1, 'giaohangthanhcong', ''),
(368, '2024-07-03', 1, 'danggiao', ''),
(370, '2024-07-03', 1, 'daduyet', ''),
(371, '2024-07-03', 20, 'giohang', ''),
(373, '2024-07-03', 20, 'choduyet', ''),
(374, '2024-07-04', 20, 'choduyet', ''),
(375, '2024-07-04', 20, 'choduyet', ''),
(376, '2024-07-04', 20, 'choduyet', ''),
(377, '2024-07-04', 20, 'daduyet', ''),
(378, '2024-07-04', 20, 'choduyet', ''),
(379, '2024-07-04', 21, 'giohang', ''),
(380, '2024-07-05', 1, 'choduyet', ''),
(381, '2024-07-05', 1, 'giaohangthanhcong', '');

-- --------------------------------------------------------

--
-- Table structure for table `email_confirmations`
--

DROP TABLE IF EXISTS `email_confirmations`;
CREATE TABLE IF NOT EXISTS `email_confirmations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `email_confirmations`
--

INSERT INTO `email_confirmations` (`id`, `user_id`, `email`, `token`, `created_at`) VALUES
(4, 14, 'xuankhuong1402@gmail.com', 'abba182a5542684c7fa20925db0c6430', '2024-05-28 02:42:44'),
(6, 14, 'khuongtranxuan9@gmail.com', 'd54f21d362f67f268503062ae5003cfb', '2024-05-28 02:50:36'),
(8, 14, 'khuongtranxuan9@gmail.com', 'f51db1c04f830a5e2ab857faa38a23e1', '2024-05-28 06:57:35'),
(15, 14, 'khuongtranxuan9@gmail.com', 'ef9b875ee265b1673442fe75e5812f96', '2024-05-28 07:50:55'),
(22, 14, 'khuongtranxuan9@gmail.com', 'a9059d4e6769bf5f5909cfaa7c5f4e44', '2024-05-28 08:03:34'),
(19, 14, 'khuongtranxuan9@gmail.com', '453e298b724f897ddb9e0999a0a7bef2', '2024-05-28 07:59:37'),
(21, 14, 'khuongtranxuan9@gmail.com', 'ff1aaa70b945c51d5f507ed0f652a23f', '2024-05-28 08:03:19'),
(23, 14, 'khuongtranxuan9@gmail.com', 'adece30de059a4b4de440531c996e3db', '2024-05-28 08:04:11'),
(24, 14, 'xuankhuong1402@gmail.com', '0a7e3695bcbcd3507a7d6dbc5a0844f0', '2024-05-28 08:06:45'),
(25, 14, 'xuankhuong1402@gmail.com', '19234b709bc3fc4dbe8fb9a05f54c629', '2024-05-28 08:07:23'),
(28, 14, 'd@gmail.com', 'f95a37bfbe294741ff94b5ff8da8250e', '2024-05-28 11:33:00'),
(29, 14, 'xuankhuoddng1402@gmail.com', '089314dd1df9ab33e3dc9ca8b79c80b6', '2024-05-28 11:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `khuyen_mai`
--

DROP TABLE IF EXISTS `khuyen_mai`;
CREATE TABLE IF NOT EXISTS `khuyen_mai` (
  `maKM` int NOT NULL AUTO_INCREMENT,
  `luongKM` float NOT NULL,
  PRIMARY KEY (`maKM`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`maKM`, `luongKM`) VALUES
(1, 20),
(2, 10),
(3, 30),
(4, 40),
(5, 50),
(6, 15),
(7, 25),
(8, 35);

-- --------------------------------------------------------

--
-- Table structure for table `ngon_ngu`
--

DROP TABLE IF EXISTS `ngon_ngu`;
CREATE TABLE IF NOT EXISTS `ngon_ngu` (
  `maNN` int NOT NULL AUTO_INCREMENT,
  `tenNN` varchar(255) NOT NULL,
  PRIMARY KEY (`maNN`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ngon_ngu`
--

INSERT INTO `ngon_ngu` (`maNN`, `tenNN`) VALUES
(1, 'Tiếng Anh'),
(3, 'Tiếng Pháp'),
(4, 'Tiếng Đức'),
(5, 'Tiếng Trung'),
(6, 'Tiếng Nhật'),
(7, 'Tiếng Thái'),
(8, 'tiếng thổ nhĩ kỳ'),
(9, 'Tiếng Việt');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `maND` int NOT NULL AUTO_INCREMENT,
  `taikhoan` varchar(255) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `tenKH` varchar(255) NOT NULL,
  `sdt` varchar(12) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hinh` varchar(255) NOT NULL,
  `diachi` varchar(255) NOT NULL,
  `anhbia` varchar(255) NOT NULL,
  `emailConfirmed` varchar(255) NOT NULL,
  `maVaiTro` int NOT NULL,
  `ngayLapTaiKhoan` date NOT NULL,
  PRIMARY KEY (`maND`),
  KEY `maVaiTro` (`maVaiTro`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`maND`, `taikhoan`, `matkhau`, `tenKH`, `sdt`, `email`, `hinh`, `diachi`, `anhbia`, `emailConfirmed`, `maVaiTro`, `ngayLapTaiKhoan`) VALUES
(1, 'khach1', 'Q7XPS1R4Cd', 'Kang Haerin', '0854222663', 'xuankhuong1402@gmail.com', 'upload/home-bg.jpg', 'Phú Nhuận', 'upload/aespa-drama-karina-ningning-winter-giselle-the-scene-4k-wallpaper-uhdpaper.com-743@1@m.jpg', '', 1, '2024-06-29'),
(13, 'khach3', 'EInl63xgcQ', 'Nguyễn Văn Húc', '0412559875', 'xuankhuong1404@gmail.com', 'upload/Screenshot 2024-05-29 154045.png', 'Quận 7', 'upload/league-of-legends-world-2020-take-over-uhdpaper.com-4K-7.2779.jpg', '', 1, '2024-06-29'),
(14, 'khach4', 'ccccccccccccccccccc@@@', 'sqsdqdq3nu', '0854219725', 'khuongtranxuan9@gmail.com', 'upload/home-bg.jpg', '', 'upload/bg.png', 'true', 1, '2024-06-29'),
(19, 'khach111', 'u2ZwfFKhnN', 'Trà Xuân', '', 'xuankhuong1402@gmail.com', 'upload/home-bg.jpg', 'Quận 2', 'upload/bg.png', '', 1, '2024-06-29'),
(20, 'khach666', '1111111111@@@', 'Khong lien quan', '', 'xuankhuong1402@gmail.com', 'upload/bg.jpg', 'tan phu', 'upload/bg.png', '', 1, '2024-06-29'),
(21, 'nhanvien1', '1', 'nhanvien1', '022233355', 'nhanvien1@gmail.com', 'https://png.pngtree.com/png-clipart/20231216/original/pngtree-vector-office-worker-staff-avatar-employee-icon-png-image_13863941.png', 'v', 'v', '', 2, '2024-06-29'),
(22, 'admin', '1', 'admin', '0852222667', 'admin@gmail.com', '', '', '', '', 3, '2024-06-29'),
(34, 'nhanvien5', '1', 'nhanvien5', NULL, NULL, '', '', '', '', 2, '2024-07-04');

-- --------------------------------------------------------

--
-- Table structure for table `nha_xuat_ban`
--

DROP TABLE IF EXISTS `nha_xuat_ban`;
CREATE TABLE IF NOT EXISTS `nha_xuat_ban` (
  `maNXB` int NOT NULL AUTO_INCREMENT,
  `tenNXB` varchar(255) NOT NULL,
  PRIMARY KEY (`maNXB`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nha_xuat_ban`
--

INSERT INTO `nha_xuat_ban` (`maNXB`, `tenNXB`) VALUES
(1, 'KIm'),
(2, 'Kim Đồng'),
(3, 'Pháp'),
(4, 'SBTC'),
(5, 'Riot Games'),
(6, 'Vanguard'),
(7, 'Steam'),
(8, 'SM Entertainment'),
(9, 'Ador'),
(10, 'KIm'),
(11, 'Kimm'),
(12, 'STU');

-- --------------------------------------------------------

--
-- Table structure for table `nn_sach`
--

DROP TABLE IF EXISTS `nn_sach`;
CREATE TABLE IF NOT EXISTS `nn_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maNN` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_nn_sach` (`maNN`,`maSach`),
  KEY `nn_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nn_sach`
--

INSERT INTO `nn_sach` (`ID`, `maNN`, `maSach`) VALUES
(1, 1, 1),
(39, 1, 2),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 54),
(12, 1, 57),
(13, 1, 64),
(14, 1, 65),
(15, 1, 67),
(17, 3, 50),
(18, 3, 58),
(19, 3, 62),
(35, 3, 89),
(20, 4, 53),
(21, 4, 59),
(22, 4, 63),
(23, 5, 51),
(24, 5, 55),
(25, 5, 61),
(26, 6, 52),
(27, 6, 56),
(28, 6, 60),
(29, 6, 66),
(30, 6, 87),
(38, 6, 88),
(42, 7, 90),
(48, 9, 49);

-- --------------------------------------------------------

--
-- Table structure for table `order_events`
--

DROP TABLE IF EXISTS `order_events`;
CREATE TABLE IF NOT EXISTS `order_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `event` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_events`
--

INSERT INTO `order_events` (`id`, `order_id`, `event`, `timestamp`) VALUES
(1, '203', 'daduyet', '2024-06-21 20:34:44'),
(2, '203', 'danggiao', '2024-06-22 08:59:22'),
(3, '228', 'daduyet', '2024-06-22 09:02:05'),
(4, '228', 'danggiao', '2024-06-22 09:02:11'),
(5, '230', 'choduyet', '2024-06-22 09:06:22'),
(6, '230', 'daduyet', '2024-06-22 09:07:14'),
(7, '230', 'danggiao', '2024-06-22 09:07:27'),
(8, '230', 'giaohangthanhcong', '2024-06-22 09:07:39'),
(9, '232', 'choduyet', '2024-06-22 09:10:39'),
(10, '232', 'daduyet', '2024-06-22 09:11:17'),
(11, '232', 'danggiao', '2024-06-22 09:11:28'),
(12, '232', 'giaohangthanhcong', '2024-06-22 09:11:38'),
(13, '235', 'choduyet', '2024-06-22 13:37:36'),
(14, '235', 'daduyet', '2024-06-22 13:39:21'),
(15, '235', 'danggiao', '2024-06-22 13:39:45'),
(16, '235', 'giaohangthanhcong', '2024-06-22 13:39:56'),
(17, '237', 'choduyet', '2024-06-22 18:49:38'),
(18, '239', 'choduyet', '2024-06-22 18:50:16'),
(19, '239', 'daduyet', '2024-06-22 18:51:09'),
(20, '239', 'danggiao', '2024-06-22 18:51:18'),
(21, '223', 'danggiao', '2024-06-22 18:58:57'),
(22, '242', 'choduyet', '2024-06-22 19:10:46'),
(23, '242', 'daduyet', '2024-06-22 19:11:24'),
(24, '242', 'danggiao', '2024-06-22 19:11:44'),
(25, '242', 'giaohangthanhcong', '2024-06-22 19:11:57'),
(26, '244', 'choduyet', '2024-06-24 09:47:25'),
(27, '247', 'choduyet', '2024-06-24 10:04:51'),
(28, '249', 'choduyet', '2024-06-24 10:05:00'),
(29, '251', 'choduyet', '2024-06-24 10:09:29'),
(30, '253', 'choduyet', '2024-06-24 10:10:01'),
(31, '256', 'choduyet', '2024-06-24 10:10:26'),
(32, '258', 'choduyet', '2024-06-24 10:11:29'),
(33, '260', 'choduyet', '2024-06-24 10:13:10'),
(34, '263', 'choduyet', '2024-06-24 10:16:03'),
(35, '266', 'choduyet', '2024-06-24 10:17:26'),
(36, '268', 'choduyet', '2024-06-24 10:18:20'),
(37, '270', 'choduyet', '2024-06-24 10:19:29'),
(38, '272', 'choduyet', '2024-06-24 10:21:19'),
(39, '274', 'choduyet', '2024-06-24 10:23:15'),
(40, '276', 'choduyet', '2024-06-24 10:26:40'),
(41, '278', 'choduyet', '2024-06-24 10:49:33'),
(42, '280', 'choduyet', '2024-06-24 19:01:11'),
(43, '282', 'choduyet', '2024-06-24 19:01:38'),
(44, '284', 'choduyet', '2024-06-24 20:07:33'),
(45, '286', 'choduyet', '2024-06-24 20:08:09'),
(46, '288', 'choduyet', '2024-06-24 20:10:32'),
(47, '290', 'choduyet', '2024-06-24 20:11:29'),
(48, '292', 'choduyet', '2024-06-25 08:07:56'),
(49, '294', 'choduyet', '2024-06-25 08:11:24'),
(50, '296', 'choduyet', '2024-06-25 08:36:56'),
(51, '298', 'choduyet', '2024-06-25 08:41:00'),
(52, '300', 'choduyet', '2024-06-25 16:41:07'),
(53, '303', 'choduyet', '2024-06-27 16:27:13'),
(54, '303', 'daduyet', '2024-06-27 16:28:29'),
(55, '305', 'choduyet', '2024-06-27 16:29:58'),
(56, '307', 'choduyet', '2024-06-28 06:56:15'),
(57, '310', 'choduyet', '2024-06-28 06:57:25'),
(58, '312', 'choduyet', '2024-06-28 06:59:19'),
(59, '314', 'choduyet', '2024-06-28 07:00:45'),
(60, '314', 'daduyet', '2024-06-28 07:05:20'),
(61, '314', 'danggiao', '2024-06-28 07:05:35'),
(62, '314', 'giaohangthanhcong', '2024-06-28 07:05:46'),
(63, '303', 'danggiao', '2024-06-28 07:08:38'),
(64, '303', 'giaohangthanhcong', '2024-06-28 07:08:44'),
(65, '317', 'choduyet', '2024-06-28 08:30:12'),
(66, '317', 'daduyet', '2024-06-28 08:30:24'),
(67, '317', 'danggiao', '2024-06-28 08:30:32'),
(68, '317', 'giaohangthanhcong', '2024-06-28 08:30:46'),
(69, '321', 'choduyet', '2024-06-28 08:37:52'),
(70, '321', 'daduyet', '2024-06-28 08:38:05'),
(71, '321', 'danggiao', '2024-06-28 08:38:06'),
(72, '321', 'giaohangthanhcong', '2024-06-28 08:38:50'),
(73, '323', 'choduyet', '2024-06-29 17:22:56'),
(74, '323', 'daduyet', '2024-06-29 17:23:30'),
(75, '323', 'danggiao', '2024-06-29 17:23:31'),
(76, '323', 'giaohangthanhcong', '2024-06-29 17:23:39'),
(77, '326', 'choduyet', '2024-06-29 20:51:27'),
(78, '328', 'choduyet', '2024-06-29 20:51:48'),
(79, '330', 'choduyet', '2024-06-29 20:51:55'),
(80, '332', 'choduyet', '2024-06-29 21:22:09'),
(81, '334', 'choduyet', '2024-06-29 21:22:29'),
(82, '336', 'choduyet', '2024-06-29 21:24:40'),
(83, '338', 'choduyet', '2024-06-29 21:25:05'),
(84, '341', 'choduyet', '2024-06-29 21:30:04'),
(85, '344', 'choduyet', '2024-06-30 12:16:37'),
(86, '346', 'choduyet', '2024-06-30 12:21:20'),
(87, '350', 'choduyet', '2024-06-30 12:23:35'),
(88, '354', 'choduyet', '2024-06-30 12:25:38'),
(89, '361', 'choduyet', '2024-06-30 19:36:29'),
(90, '366', 'choduyet', '2024-07-03 11:19:13'),
(91, '368', 'choduyet', '2024-07-03 11:45:58'),
(92, '370', 'choduyet', '2024-07-03 11:46:16'),
(93, '370', 'daduyet', '2024-07-03 11:52:59'),
(94, '366', 'daduyet', '2024-07-03 11:53:33'),
(95, '366', 'danggiao', '2024-07-03 11:53:48'),
(96, '366', 'giaohangthanhcong', '2024-07-03 11:54:41'),
(97, '373', 'choduyet', '2024-07-03 11:58:23'),
(98, '377', 'daduyet', '2024-07-04 21:56:45'),
(99, '368', 'daduyet', '2024-07-05 08:51:31'),
(100, '368', 'danggiao', '2024-07-05 08:51:32'),
(101, '380', 'choduyet', '2024-07-05 10:14:23'),
(102, '381', 'choduyet', '2024-07-05 10:15:19'),
(103, '381', 'daduyet', '2024-07-05 10:17:13'),
(104, '381', 'danggiao', '2024-07-05 10:25:34'),
(105, '381', 'giaohangthanhcong', '2024-07-05 10:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
CREATE TABLE IF NOT EXISTS `otp_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `otp_code`, `created_at`, `email`) VALUES
(1, 0, '301256', '2024-05-29 13:34:31', NULL),
(2, 0, '437070', '2024-05-29 13:36:23', NULL),
(3, 0, '754387', '2024-05-29 13:38:09', NULL),
(4, 0, '205142', '2024-05-29 13:38:45', NULL),
(5, 0, '355290', '2024-05-29 13:39:23', NULL),
(6, 0, '530888', '2024-05-29 13:39:51', NULL),
(7, 0, '587892', '2024-05-29 13:41:16', NULL),
(8, 0, '498036', '2024-05-29 13:45:47', 'khuongtranxuan9@gmail.com'),
(9, 0, '861535', '2024-05-29 13:48:35', 'khuongtranxuan9@gmail.com'),
(10, 0, '259231', '2024-05-29 13:50:30', 'khuongtranxuan9@gmail.com'),
(11, 0, '230357', '2024-05-30 09:51:55', 'khuongtranxuan9@gamail.com'),
(12, 0, '812972', '2024-05-30 09:52:08', 'khuongtranxuan9@gamail.com'),
(13, 0, '263964', '2024-05-30 09:52:29', 'khuongtranxuan9@gmail.com'),
(14, 0, '424177', '2024-05-30 09:58:49', 'khuongtranxuan9@gmail.com'),
(15, 0, '423942', '2024-05-30 10:01:52', 'khuongtranxuan9@gmail.com'),
(16, 0, '375382', '2024-05-30 10:03:52', 'khuongtranxuan9@gmail.com'),
(17, 0, '123072', '2024-05-30 10:05:20', 'khuongtranxuan9@gmail.com'),
(18, 0, '864696', '2024-05-30 10:07:03', 'khuongtranxuan9@gmail.com'),
(19, 0, '769798', '2024-05-30 10:09:04', 'khuongtranxuan9@gmail.com'),
(20, 0, '638521', '2024-05-30 10:15:12', 'khuongtranxuan9@gmail.com'),
(21, 0, '781638', '2024-05-30 10:16:54', 'xuankhuong1402@gmail.com'),
(22, 0, '235682', '2024-05-30 10:18:14', 'khuongtranxuan9@gmail.com'),
(23, 0, '844530', '2024-05-30 10:24:15', 'xuankhuong1402@gmail.com'),
(24, 0, '203332', '2024-05-30 10:25:01', 'xuankhuong1402@gmail.com'),
(25, 0, '486500', '2024-05-30 10:25:51', 'xuankhuong1402@gmail.com'),
(26, 0, '798918', '2024-05-30 10:27:42', 'xuankhuong1402@gmail.com'),
(27, 0, '654845', '2024-05-30 10:28:36', 'xuankhuong1402@gmail.com'),
(28, 0, '574115', '2024-05-30 10:29:53', 'xuankhuong1402@gmail.com'),
(29, 0, '261225', '2024-05-30 10:34:22', 'xuankhuong1402@gmail.com'),
(30, 0, '286086', '2024-05-31 01:03:36', 'xuankhuong1402@gmail.com'),
(31, 0, '671717', '2024-05-31 01:40:38', 'xuankhuong1402@gmail.com'),
(32, 0, '446901', '2024-06-30 05:30:03', 'xuankhuong1402@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

DROP TABLE IF EXISTS `quyen`;
CREATE TABLE IF NOT EXISTS `quyen` (
  `maQuyen` int NOT NULL AUTO_INCREMENT,
  `tenQuyen` varchar(255) NOT NULL,
  PRIMARY KEY (`maQuyen`),
  UNIQUE KEY `tenQuyen` (`tenQuyen`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quyen`
--

INSERT INTO `quyen` (`maQuyen`, `tenQuyen`) VALUES
(10, 'chamsockhachhang'),
(9, 'muahang'),
(7, 'quanlynguoidung'),
(8, 'quanlysanpham');

-- --------------------------------------------------------

--
-- Table structure for table `sach`
--

DROP TABLE IF EXISTS `sach`;
CREATE TABLE IF NOT EXISTS `sach` (
  `maSach` int NOT NULL AUTO_INCREMENT,
  `tenSach` varchar(255) NOT NULL,
  `soLuong` int NOT NULL,
  `donGia` float NOT NULL,
  `chiTiet` varchar(255) NOT NULL,
  `hinhAnh` varchar(255) NOT NULL,
  `maKM` int DEFAULT NULL,
  `maNXB` int NOT NULL,
  PRIMARY KEY (`maSach`),
  KEY `maKM` (`maKM`),
  KEY `maNXB` (`maNXB`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sach`
--

INSERT INTO `sach` (`maSach`, `tenSach`, `soLuong`, `donGia`, `chiTiet`, `hinhAnh`, `maKM`, `maNXB`) VALUES
(1, 'A beautiful image is a perfect moment frozen\r\n', 8, 1000, 'Một hình ảnh đẹp là một khoảnh khắc hoàn hảo được đóng băng: Hình ảnh là cách tuyệt vời để lưu giữ những khoảnh khắc đáng nhớ. Một bức ảnh đẹp có thể nắm bắt được tinh hoa của thời khắc, từ nụ cười hồn nhiên của trẻ thơ, ánh mắt yêu thương của người yêu, ', 'https://buku.one/wp-content/uploads/2021/03/mastering-photography-572x764-1.jpg', 2, 1),
(2, 'Celebration of Freedom', 10, 1000, 'Lễ kỷ niệm Tự do: Tự do là giá trị thiêng liêng và cao quý nhất của con người. Mỗi năm, chúng ta tổ chức lễ kỷ niệm để tưởng nhớ những cuộc đấu tranh không mệt mỏi của cha ông để giành lấy độc lập. Lễ kỷ niệm tự do không chỉ là dịp để chúng ta nhìn lại lị', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2023/02/celebration-of-freedom-572x764-1-550x680.jpg', 1, 4),
(3, 'Eating Your Way to a Better Life', 10, 1000, 'Ăn uống để có cuộc sống tốt hơn: Chế độ ăn uống lành mạnh là chìa khóa dẫn đến cuộc sống khỏe mạnh và hạnh phúc. Việc lựa chọn thực phẩm không chỉ ảnh hưởng đến thể chất mà còn có tác động sâu sắc đến tinh thần.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/healthy-eating-550x680.jpg', 3, 2),
(4, 'Exploring the Creative Mind: Thinking Outside ', 0, 1000, 'Khám phá Tư duy Sáng tạo: Suy nghĩ Ngoài khuôn khổ: Sự sáng tạo không chỉ là đặc quyền của nghệ sĩ mà là một kỹ năng cần thiết trong mọi lĩnh vực. Cuốn sách này dẫn dắt bạn qua hành trình khám phá tư duy sáng tạo.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/creativity-572x764-1-550x680.jpg', 4, 3),
(5, 'Interior design is more than a hobby', 8, 1000, 'Thiết kế nội thất không chỉ là một sở thích: Thiết kế nội thất là nghệ thuật biến không gian sống trở nên ấm cúng, tiện nghi và phản ánh cá tính của chủ nhân.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/interior-design-550x680.jpg', 2, 1),
(6, 'Master of Photography – 2 Series Bundle', 8, 1000, 'Bậc thầy Nhiếp ảnh – Bộ sưu tập 2 loạt: Cuốn sách này là một kho tàng kiến thức về nghệ thuật nhiếp ảnh, được chia thành hai phần chính.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/mastering-photography-572x764-1-550x680.jpg', 1, 4),
(7, 'Mastering the Kitchen Series', 9, 1000, 'Làm chủ Nhà bếp: Cuốn sách này là bộ hướng dẫn toàn diện về nấu ăn, từ những món ăn đơn giản đến những công thức phức tạp đòi hỏi kỹ thuật cao.', 'https://takibook.com/wp-content/uploads/2023/08/mastering-the-kitchen-572x764-1.jpg', 3, 2),
(8, 'Music Of Love – The Sound of Your Dreams', 9, 1000, 'Âm nhạc của Tình yêu – Âm thanh của Giấc mơ bạn: Âm nhạc có sức mạnh kỳ diệu, có thể chạm đến những ngóc ngách sâu thẳm nhất của tâm hồn.', 'https://i0.wp.com/marvelousblake.com/wp-content/uploads/2021/03/guitar.jpg?fit=572%2C764&ssl=1', 4, 3),
(9, 'Raining – the most beloved love story of all time', 8, 1000, 'Mưa – câu chuyện tình yêu được yêu thích nhất mọi thời đại: \"Mưa\" là một câu chuyện tình yêu đầy xúc động và lãng mạn, xoay quanh hai nhân vật chính với những tình tiết đầy bất ngờ và cảm động.', 'https://safawritings.com/wp-content/uploads/2021/03/couple-under-umbrella.png', 2, 1),
(10, 'The Inner Face: A Guide to the Emotions We Hide', 8, 1000, 'Gương mặt bên trong: Hướng dẫn về những cảm xúc chúng ta che giấu: Con người thường có xu hướng che giấu những cảm xúc thật của mình. Cuốn sách này khám phá những khía cạnh tâm lý sâu kín.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDDKxoSKE5V5_YdfBhvLTK_HBgmQ4lGoSMHp25in3mOM00KRwJRrIzuY7OcZO7bjGufLs&usqp=CAU', 1, 4),
(49, 'Bố Già', 9, 1000, 'Bố Già là tiểu thuyết nổi tiếng của Mario Puzo, kể về gia đình mafia Corleone ở New York.', 'https://duabookpalace.com/cdn/shop/products/godfather.jpg?v=1665502754', 1, 2),
(50, 'Les Misérables', 9, 1000, 'Les Misérables là tác phẩm văn học kinh điển của Victor Hugo, kể về cuộc sống của Jean Valjean sau khi ra tù.', 'https://m.media-amazon.com/images/I/71eQUDwCBfL._AC_UF1000,1000_QL80_.jpg', 2, 3),
(51, '红楼梦', 9, 1000, '《红楼梦》是中国古典小说四大名著之一，由曹雪芹创作，描写了荣国府和宁国府两大家族的兴衰。', 'https://m.media-amazon.com/images/I/919iOLnIuaL._AC_UF1000,1000_QL80_.jpg', 3, 4),
(52, 'Norwegian Wood', 10, 1000, 'Norwegian Wood là tiểu thuyết của Haruki Murakami, kể về câu chuyện tình yêu và sự trưởng thành của nhân vật chính.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1713542603i/11297.jpg', 4, 5),
(53, 'Der Vorleser', 10, 1000, 'Der Vorleser là tiểu thuyết của Bernhard Schlink, kể về mối quan hệ phức tạp giữa một thiếu niên và một người phụ nữ lớn tuổi.', 'https://upload.wikimedia.org/wikipedia/de/4/41/Der_Vorleser_-_detebe_22953_1997.jpg', 1, 4),
(54, 'Sapiens: A Brief History of Humankind', 9, 1000, 'Sapiens là sách của Yuval Noah Harari, kể về lịch sử loài người từ thời tiền sử đến hiện đại.', 'https://m.media-amazon.com/images/I/61ZKK6Y1nFL._AC_UF1000,1000_QL80_.jpg', 2, 7),
(55, '百年孤独', 10, 1000, '《百年孤独》是哥伦比亚作家加夫列尔·加西亚·马尔克斯的著名小说，讲述了布恩迪亚家族的七代人。', 'https://winfo.crc.com.cn/crc_mobile/crc/magazine/248/202009/W020200902710292658150.jpg', 3, 5),
(56, 'Kafka on the Shore', 9, 1000, 'Kafka on the Shore là tiểu thuyết của Haruki Murakami, kể về hành trình của một cậu bé bỏ nhà ra đi.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1429638085i/4929.jpg', 4, 2),
(57, 'The Catcher in the Rye', 9, 1000, 'The Catcher in the Rye là tiểu thuyết của J.D. Salinger, kể về cuộc phiêu lưu của thiếu niên Holden Caulfield.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/The_Catcher_in_the_Rye_%281951%2C_first_edition_cover%29.jpg/640px-The_Catcher_in_the_Rye_%281951%2C_first_edition_cover%29.jpg', 1, 1),
(58, 'Notre-Dame de Paris', 9, 1000, 'Notre-Dame de Paris là tiểu thuyết của Victor Hugo, kể về câu chuyện tình yêu và bi kịch xung quanh nhà thờ Đức Bà Paris.', 'https://i0.wp.com/www.raptisrarebooks.com/images/173397/notre-dame-de-paris-victor-hugo-first-edition.jpg?fit=660%2C1000&ssl=1', 2, 6),
(59, 'Mein Kampf', 10, 1000, 'Mein Kampf là cuốn tự truyện và tuyên ngôn chính trị của Adolf Hitler, viết về tư tưởng và kế hoạch của ông.', 'https://play-lh.googleusercontent.com/36aGUrapjhY5bkbm0nJdAaweEgUJibpmEfMNCvwrz4KQ_J4-i1rGRdIuaYF3Fu4FE3pH=w526-h296-rw', 3, 7),
(60, 'Fifty Shades of Grey', 9, 1000, 'Fifty Shades of Grey là tiểu thuyết lãng mạn của E.L. James, kể về mối quan hệ giữa sinh viên Anastasia Steele và tỷ phú Christian Grey.', 'https://upload.wikimedia.org/wikipedia/en/5/5e/50ShadesofGreyCoverArt.jpg', 4, 6),
(61, 'Don Quixote', 10, 1000, 'Don Quixote là tiểu thuyết của Miguel de Cervantes, kể về cuộc phiêu lưu của một hiệp sĩ tưởng tượng.', 'https://bizweb.dktcdn.net/thumb/1024x1024/100/363/455/products/donquixotetap1.jpg?v=1710306237443', 1, 3),
(62, 'La Peste', 10, 1000, 'La Peste là tiểu thuyết của Albert Camus, kể về sự bùng phát của bệnh dịch hạch tại thành phố Oran.', 'https://upload.wikimedia.org/wikipedia/commons/c/c6/La_Peste_book_cover.jpg', 2, 2),
(63, 'Mein Name sei Gantenbein', 10, 1000, 'Mein Name sei Gantenbein là tiểu thuyết của Max Frisch, kể về cuộc sống và những tưởng tượng của nhân vật chính.', 'https://m.media-amazon.com/images/I/81hjz9Q1nTS._AC_UF894,1000_QL80_.jpg', 3, 1),
(64, 'Nhật ký Anne Frank', 10, 1000, 'Nhật ký Anne Frank là cuốn nhật ký của cô gái Do Thái Anne Frank trong thời gian trốn tránh Đức Quốc Xã.', 'https://salt.tikicdn.com/cache/w1200/media/catalog/product/a/n/anne_frank.jpg', 4, 8),
(65, 'To Kill a Mockingbird', 9, 1000, 'To Kill a Mockingbird là tiểu thuyết của Harper Lee, kể về sự bất công và phân biệt chủng tộc ở miền Nam nước Mỹ.', 'https://m.media-amazon.com/images/I/81gepf1eMqL._AC_UF1000,1000_QL80_.jpg', 1, 5),
(66, 'Le Petit Prince', 8, 1000, 'Le Petit Prince là cuốn sách của Antoine de Saint-Exupéry, kể về cuộc hành trình của một hoàng tử nhỏ từ hành tinh này đến hành tinh khác.', 'https://m.media-amazon.com/images/I/61NGp-UxolL._AC_UF1000,1000_QL80_.jpg', 2, 7),
(67, 'The Great Gatsby', 9, 1000, 'The Great Gatsby là tiểu thuyết của F. Scott Fitzgerald, kể về cuộc sống xa hoa và bi kịch của Jay Gatsby.', 'https://upload.wikimedia.org/wikipedia/commons/7/7a/The_Great_Gatsby_Cover_1925_Retouched.jpg', 3, 9),
(74, 'wfwf', 10, 1000, 'fqfqf', 'qffq', 6, 5),
(75, '2r2r2', 10, 1000, '1wfwf', 'fwfwf', 7, 7),
(76, 'qf', 10, 1000, 'fwgw', 'gwegw', 1, 7),
(77, 'dq', 10, 1000, 'f', 'q', 8, 8),
(78, 'dqww', 10, 1000, 'qfqf', 'qfqf', 7, 3),
(79, '1141', 10, 1000, '1', '1', 7, 8),
(80, 'master of leak', 10, 1000, 'đây là 1 sản phẩm hay', 'fqwwwwwwwwwwwwww', 8, 8),
(81, '1', 10, 1000, '1', '1', 7, 8),
(82, '11111', 10, 1000, '1111', '11', 7, 7),
(83, '1e1r1', 10, 1000, 'fqfqf', 'fqfqf', 1, 8),
(84, 'abc', 10, 1000, '1', '1', 2, 1),
(85, '42y', 10, 1000, '3g2', 'ggw', 2, 5),
(86, 'gqe', 10, 1000, 'wg', 'wegew', 1, 7),
(87, 'fwwhwr', 10, 1000, 'e1', '111', 2, 3),
(88, 'fwwhwrq3f', 10, 1000, 'e1', '111', 2, 3),
(89, 'qrqrqrnnkmn', 10, 1000, 'gưh', 'gq', 1, 4),
(90, 'sạc ww', 10, 1000, 'eqgg', 'ưgưg', 8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tac_gia`
--

DROP TABLE IF EXISTS `tac_gia`;
CREATE TABLE IF NOT EXISTS `tac_gia` (
  `maTG` int NOT NULL AUTO_INCREMENT,
  `tenTG` varchar(255) NOT NULL,
  PRIMARY KEY (`maTG`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tac_gia`
--

INSERT INTO `tac_gia` (`maTG`, `tenTG`) VALUES
(1, 'Mario Puzo'),
(2, 'Victor Hugo'),
(3, 'Yuval Noah Harari'),
(4, 'Haruki Murakami'),
(6, 'E.L. James'),
(7, 'Miguel de Cervantes'),
(8, 'Albert Camus'),
(9, 'Max Frisch'),
(10, 'Anne Frank'),
(11, 'Harper Lee'),
(12, 'Kim'),
(13, 'Kimm'),
(14, 'Bernhard Schlink');

-- --------------------------------------------------------

--
-- Table structure for table `tg_sach`
--

DROP TABLE IF EXISTS `tg_sach`;
CREATE TABLE IF NOT EXISTS `tg_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maTG` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_tg_sach` (`maTG`,`maSach`),
  KEY `tg_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tg_sach`
--

INSERT INTO `tg_sach` (`ID`, `maTG`, `maSach`) VALUES
(1, 1, 1),
(2, 1, 7),
(3, 1, 10),
(4, 1, 49),
(5, 1, 50),
(7, 2, 8),
(8, 2, 51),
(9, 2, 58),
(10, 2, 61),
(11, 3, 6),
(12, 3, 9),
(13, 3, 54),
(14, 4, 5),
(15, 4, 52),
(16, 4, 56),
(19, 6, 4),
(20, 6, 60),
(21, 6, 63),
(30, 6, 87),
(22, 7, 57),
(29, 7, 87),
(42, 7, 90),
(23, 8, 62),
(24, 8, 64),
(41, 8, 90),
(25, 9, 59),
(26, 10, 65),
(27, 11, 66),
(28, 11, 67),
(50, 14, 66);

-- --------------------------------------------------------

--
-- Table structure for table `vaitro`
--

DROP TABLE IF EXISTS `vaitro`;
CREATE TABLE IF NOT EXISTS `vaitro` (
  `maVaiTro` int NOT NULL AUTO_INCREMENT,
  `tenVaiTro` varchar(255) NOT NULL,
  PRIMARY KEY (`maVaiTro`),
  UNIQUE KEY `tenVaiTro` (`tenVaiTro`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vaitro`
--

INSERT INTO `vaitro` (`maVaiTro`, `tenVaiTro`) VALUES
(3, 'admin'),
(1, 'khachhang'),
(2, 'nhanvien');

-- --------------------------------------------------------

--
-- Table structure for table `vaitro_quyen`
--

DROP TABLE IF EXISTS `vaitro_quyen`;
CREATE TABLE IF NOT EXISTS `vaitro_quyen` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maVaiTro` int NOT NULL,
  `maQuyen` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_vaitro_quyen` (`maVaiTro`,`maQuyen`),
  KEY `vaitro_quyen_ibfk_2` (`maQuyen`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vaitro_quyen`
--

INSERT INTO `vaitro_quyen` (`ID`, `maVaiTro`, `maQuyen`) VALUES
(9, 1, 9),
(13, 2, 8),
(12, 2, 10),
(10, 3, 7),
(11, 3, 8);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`masach`) REFERENCES `sach` (`maSach`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`madon`) REFERENCES `don_dat_hang` (`madon`);

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`maND`) REFERENCES `nguoi_dung` (`maND`),
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`),
  ADD CONSTRAINT `fk_parent_danh_gia` FOREIGN KEY (`parent_id`) REFERENCES `danh_gia` (`maDG`);

--
-- Constraints for table `dm_sach`
--
ALTER TABLE `dm_sach`
  ADD CONSTRAINT `dm_sach_ibfk_1` FOREIGN KEY (`maDM`) REFERENCES `danh_muc` (`maDM`),
  ADD CONSTRAINT `dm_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Constraints for table `don_dat_hang`
--
ALTER TABLE `don_dat_hang`
  ADD CONSTRAINT `don_dat_hang_ibfk_1` FOREIGN KEY (`maND`) REFERENCES `nguoi_dung` (`maND`);

--
-- Constraints for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_ibfk_1` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);

--
-- Constraints for table `nn_sach`
--
ALTER TABLE `nn_sach`
  ADD CONSTRAINT `nn_sach_ibfk_1` FOREIGN KEY (`maNN`) REFERENCES `ngon_ngu` (`maNN`),
  ADD CONSTRAINT `nn_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Constraints for table `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`maKM`) REFERENCES `khuyen_mai` (`maKM`),
  ADD CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`maNXB`) REFERENCES `nha_xuat_ban` (`maNXB`);

--
-- Constraints for table `tg_sach`
--
ALTER TABLE `tg_sach`
  ADD CONSTRAINT `tg_sach_ibfk_1` FOREIGN KEY (`maTG`) REFERENCES `tac_gia` (`maTG`),
  ADD CONSTRAINT `tg_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Constraints for table `vaitro_quyen`
--
ALTER TABLE `vaitro_quyen`
  ADD CONSTRAINT `vaitro_quyen_ibfk_1` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`),
  ADD CONSTRAINT `vaitro_quyen_ibfk_2` FOREIGN KEY (`maQuyen`) REFERENCES `quyen` (`maQuyen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
