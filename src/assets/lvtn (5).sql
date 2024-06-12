-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th6 12, 2024 lúc 09:06 AM
-- Phiên bản máy phục vụ: 8.0.31
-- Phiên bản PHP: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lvtn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

DROP TABLE IF EXISTS `danh_gia`;
CREATE TABLE IF NOT EXISTS `danh_gia` (
  `maDG` int NOT NULL AUTO_INCREMENT,
  `noidung` varchar(255) NOT NULL,
  `ngayDG` date NOT NULL,
  `maND` int NOT NULL,
  `maSach` int NOT NULL,
  `rating` int DEFAULT NULL,
  PRIMARY KEY (`maDG`),
  KEY `maND` (`maND`),
  KEY `danh_gia_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gia`
--

INSERT INTO `danh_gia` (`maDG`, `noidung`, `ngayDG`, `maND`, `maSach`, `rating`) VALUES
(10, 'sach rat hay', '2024-06-07', 20, 2, 3),
(11, 'sach nay con khong ?', '2024-06-07', 20, 2, 1),
(12, 'sach nay rat te', '2024-06-07', 1, 2, 4),
(13, 'sach nay hay nha', '2024-06-07', 1, 2, 5),
(14, 'saaâ', '2024-06-07', 1, 2, 5),
(15, 'sách rất hay, admin rep cmt tôi với', '2024-06-07', 1, 2, 5),
(16, 'sahcs nay con khong', '2024-06-07', 1, 6, 3),
(17, 'sahcs này có giảm giá k', '2024-06-07', 13, 6, 0),
(18, 'dqdqdq', '2024-06-08', 13, 2, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `maDM` int NOT NULL AUTO_INCREMENT,
  `tenDM` varchar(255) NOT NULL,
  PRIMARY KEY (`maDM`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`maDM`, `tenDM`) VALUES
(1, 'Nhiếp ảnh'),
(2, 'Tự do'),
(3, 'Sức khỏe'),
(4, 'Sáng tạo'),
(5, 'Thiết kế nội thất'),
(6, 'Lịch sử'),
(7, 'Văn học'),
(8, 'Tâm lý'),
(9, 'Âm nhạc'),
(18, 'Truyện Tranh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dm_sach`
--

DROP TABLE IF EXISTS `dm_sach`;
CREATE TABLE IF NOT EXISTS `dm_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maDM` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_dm_sach` (`maDM`,`maSach`),
  KEY `dm_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `dm_sach`
--

INSERT INTO `dm_sach` (`ID`, `maDM`, `maSach`) VALUES
(1, 1, 1),
(2, 1, 6),
(33, 1, 87),
(42, 2, 2),
(4, 2, 5),
(5, 2, 9),
(32, 2, 87),
(45, 3, 3),
(7, 4, 4),
(8, 4, 7),
(9, 5, 5),
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
(30, 9, 66);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_dat_hang`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_confirmations`
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
-- Đang đổ dữ liệu cho bảng `email_confirmations`
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
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

DROP TABLE IF EXISTS `khuyen_mai`;
CREATE TABLE IF NOT EXISTS `khuyen_mai` (
  `maKM` int NOT NULL AUTO_INCREMENT,
  `luongKM` float NOT NULL,
  PRIMARY KEY (`maKM`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
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
-- Cấu trúc bảng cho bảng `ngon_ngu`
--

DROP TABLE IF EXISTS `ngon_ngu`;
CREATE TABLE IF NOT EXISTS `ngon_ngu` (
  `maNN` int NOT NULL AUTO_INCREMENT,
  `tenNN` varchar(255) NOT NULL,
  PRIMARY KEY (`maNN`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `ngon_ngu`
--

INSERT INTO `ngon_ngu` (`maNN`, `tenNN`) VALUES
(1, 'Tiếng Anh'),
(2, 'Tiếng Việt'),
(3, 'Tiếng Pháp'),
(4, 'Tiếng Đức'),
(5, 'Tiếng Trung'),
(6, 'Tiếng Nhật'),
(7, 'Tiếng Thái');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
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
  PRIMARY KEY (`maND`),
  KEY `maVaiTro` (`maVaiTro`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`maND`, `taikhoan`, `matkhau`, `tenKH`, `sdt`, `email`, `hinh`, `diachi`, `anhbia`, `emailConfirmed`, `maVaiTro`) VALUES
(1, 'khach1', 'Q7XPS1R4Cd', 'Kang Haerin', '0854222663', '', 'upload/home-bg.jpg', 'Phú Nhuận', 'https://marketplace.canva.com/EAFZcvZNUFc/1/0/1131w/canva-green-pink-minimalist-doodle-a4-document-fiDfeTwmI8g.jpg', '', 1),
(13, 'khach3', 'EInl63xgcQ', 'Nguyễn Văn Húc', '0412559875', 'xuankhuong1404@gmail.com', 'upload/Screenshot 2024-05-29 154045.png', 'Quận 7', 'upload/league-of-legends-world-2020-take-over-uhdpaper.com-4K-7.2779.jpg', '', 1),
(14, 'khach4', 'ccccccccccccccccccc@@@', 'sqsdqdq3nu', '0854219725', 'khuongtranxuan9@gmail.com', 'upload/home-bg.jpg', '', 'upload/bg.png', 'true', 1),
(19, 'khach111', 'u2ZwfFKhnN', 'Trà Xuân', '', 'xuankhuong1402@gmail.com', 'upload/home-bg.jpg', 'Quận 2', 'upload/bg.png', '', 1),
(20, 'khach666', '1111111111@@@', 'Khong lien quan', '', 'xuankhuong1402@gmail.com', 'upload/bg.jpg', '', 'upload/bg.png', '', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_xuat_ban`
--

DROP TABLE IF EXISTS `nha_xuat_ban`;
CREATE TABLE IF NOT EXISTS `nha_xuat_ban` (
  `maNXB` int NOT NULL AUTO_INCREMENT,
  `tenNXB` varchar(255) NOT NULL,
  PRIMARY KEY (`maNXB`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_xuat_ban`
--

INSERT INTO `nha_xuat_ban` (`maNXB`, `tenNXB`) VALUES
(1, 'Wibu'),
(2, 'Kim Đồng'),
(3, 'Pháp'),
(4, 'SBTC'),
(5, 'Riot Games'),
(6, 'Vanguard'),
(7, 'Steam'),
(8, 'SM Entertainment'),
(9, 'Ador'),
(10, 'KIm'),
(11, 'Kimm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nn_sach`
--

DROP TABLE IF EXISTS `nn_sach`;
CREATE TABLE IF NOT EXISTS `nn_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maNN` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_nn_sach` (`maNN`,`maSach`),
  KEY `nn_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `nn_sach`
--

INSERT INTO `nn_sach` (`ID`, `maNN`, `maSach`) VALUES
(1, 1, 1),
(39, 1, 2),
(41, 1, 3),
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
(16, 2, 49),
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
(38, 6, 88);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `otp_codes`
--

DROP TABLE IF EXISTS `otp_codes`;
CREATE TABLE IF NOT EXISTS `otp_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `otp_codes`
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
(31, 0, '671717', '2024-05-31 01:40:38', 'xuankhuong1402@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quyen`
--

DROP TABLE IF EXISTS `quyen`;
CREATE TABLE IF NOT EXISTS `quyen` (
  `maQuyen` int NOT NULL AUTO_INCREMENT,
  `tenQuyen` varchar(255) NOT NULL,
  PRIMARY KEY (`maQuyen`),
  UNIQUE KEY `tenQuyen` (`tenQuyen`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `quyen`
--

INSERT INTO `quyen` (`maQuyen`, `tenQuyen`) VALUES
(6, 'danhgia'),
(5, 'dathang'),
(3, 'sua'),
(1, 'them'),
(4, 'xem'),
(2, 'xoa');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sach`
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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `sach`
--

INSERT INTO `sach` (`maSach`, `tenSach`, `soLuong`, `donGia`, `chiTiet`, `hinhAnh`, `maKM`, `maNXB`) VALUES
(1, 'A beautiful image is a perfect moment frozen\r\n', 10, 25000, 'Một hình ảnh đẹp là một khoảnh khắc hoàn hảo được đóng băng: Hình ảnh là cách tuyệt vời để lưu giữ những khoảnh khắc đáng nhớ. Một bức ảnh đẹp có thể nắm bắt được tinh hoa của thời khắc, từ nụ cười hồn nhiên của trẻ thơ, ánh mắt yêu thương của người yêu, ', 'https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg', 2, 1),
(2, 'Celebration of Freedom', 10, 35000, 'Lễ kỷ niệm Tự do: Tự do là giá trị thiêng liêng và cao quý nhất của con người. Mỗi năm, chúng ta tổ chức lễ kỷ niệm để tưởng nhớ những cuộc đấu tranh không mệt mỏi của cha ông để giành lấy độc lập. Lễ kỷ niệm tự do không chỉ là dịp để chúng ta nhìn lại lị', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2023/02/celebration-of-freedom-572x764-1-550x680.jpg', 1, 4),
(3, 'Eating Your Way to a Better Life', 10, 60000, 'Ăn uống để có cuộc sống tốt hơn: Chế độ ăn uống lành mạnh là chìa khóa dẫn đến cuộc sống khỏe mạnh và hạnh phúc. Việc lựa chọn thực phẩm không chỉ ảnh hưởng đến thể chất mà còn có tác động sâu sắc đến tinh thần.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/healthy-eating-550x680.jpg', 3, 2),
(4, 'Exploring the Creative Mind: Thinking Outside ', 10, 60000, 'Khám phá Tư duy Sáng tạo: Suy nghĩ Ngoài khuôn khổ: Sự sáng tạo không chỉ là đặc quyền của nghệ sĩ mà là một kỹ năng cần thiết trong mọi lĩnh vực. Cuốn sách này dẫn dắt bạn qua hành trình khám phá tư duy sáng tạo.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/creativity-572x764-1-550x680.jpg', 4, 3),
(5, 'Interior design is more than a hobby', 10, 65000, 'Thiết kế nội thất không chỉ là một sở thích: Thiết kế nội thất là nghệ thuật biến không gian sống trở nên ấm cúng, tiện nghi và phản ánh cá tính của chủ nhân.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/interior-design-550x680.jpg', 2, 1),
(6, 'Master of Photography – 2 Series Bundle', 10, 70000, 'Bậc thầy Nhiếp ảnh – Bộ sưu tập 2 loạt: Cuốn sách này là một kho tàng kiến thức về nghệ thuật nhiếp ảnh, được chia thành hai phần chính.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/mastering-photography-572x764-1-550x680.jpg', 1, 4),
(7, 'Mastering the Kitchen Series', 10, 75000, 'Làm chủ Nhà bếp: Cuốn sách này là bộ hướng dẫn toàn diện về nấu ăn, từ những món ăn đơn giản đến những công thức phức tạp đòi hỏi kỹ thuật cao.', 'https://takibook.com/wp-content/uploads/2023/08/mastering-the-kitchen-572x764-1.jpg', 3, 2),
(8, 'Music Of Love – The Sound of Your Dreams', 10, 80000, 'Âm nhạc của Tình yêu – Âm thanh của Giấc mơ bạn: Âm nhạc có sức mạnh kỳ diệu, có thể chạm đến những ngóc ngách sâu thẳm nhất của tâm hồn.', 'https://i0.wp.com/marvelousblake.com/wp-content/uploads/2021/03/guitar.jpg?fit=572%2C764&ssl=1', 4, 3),
(9, 'Raining – the most beloved love story of all time', 10, 85000, 'Mưa – câu chuyện tình yêu được yêu thích nhất mọi thời đại: \"Mưa\" là một câu chuyện tình yêu đầy xúc động và lãng mạn, xoay quanh hai nhân vật chính với những tình tiết đầy bất ngờ và cảm động.', 'https://safawritings.com/wp-content/uploads/2021/03/couple-under-umbrella.png', 2, 1),
(10, 'The Inner Face: A Guide to the Emotions We Hide', 10, 90000, 'Gương mặt bên trong: Hướng dẫn về những cảm xúc chúng ta che giấu: Con người thường có xu hướng che giấu những cảm xúc thật của mình. Cuốn sách này khám phá những khía cạnh tâm lý sâu kín.', 'https://hostacmee.space/demo/bookchoix/wp-content/uploads/2021/03/inner-face-572x764-1-550x680.jpg', 1, 4),
(49, 'Bố Già', 10, 80000, 'Bố Già là tiểu thuyết nổi tiếng của Mario Puzo, kể về gia đình mafia Corleone ở New York.', 'https://salt.tikicdn.com/cache/w1200/media/catalog/product/b/o/bo-gia_1_1.jpg', 1, 2),
(50, 'Les Misérables', 10, 90000, 'Les Misérables là tác phẩm văn học kinh điển của Victor Hugo, kể về cuộc sống của Jean Valjean sau khi ra tù.', 'https://m.media-amazon.com/images/I/71eQUDwCBfL._AC_UF1000,1000_QL80_.jpg', 2, 3),
(51, '红楼梦', 10, 95000, '《红楼梦》是中国古典小说四大名著之一，由曹雪芹创作，描写了荣国府和宁国府两大家族的兴衰。', 'https://m.media-amazon.com/images/I/919iOLnIuaL._AC_UF1000,1000_QL80_.jpg', 3, 4),
(52, 'Norwegian Wood', 10, 85000, 'Norwegian Wood là tiểu thuyết của Haruki Murakami, kể về câu chuyện tình yêu và sự trưởng thành của nhân vật chính.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1713542603i/11297.jpg', 4, 5),
(53, 'Der Vorleser', 10, 70000, 'Der Vorleser là tiểu thuyết của Bernhard Schlink, kể về mối quan hệ phức tạp giữa một thiếu niên và một người phụ nữ lớn tuổi.', 'https://upload.wikimedia.org/wikipedia/de/4/41/Der_Vorleser_-_detebe_22953_1997.jpg', 1, 4),
(54, 'Sapiens: A Brief History of Humankind', 10, 100000, 'Sapiens là sách của Yuval Noah Harari, kể về lịch sử loài người từ thời tiền sử đến hiện đại.', 'https://m.media-amazon.com/images/I/61ZKK6Y1nFL._AC_UF1000,1000_QL80_.jpg', 2, 7),
(55, '百年孤独', 10, 85000, '《百年孤独》是哥伦比亚作家加夫列尔·加西亚·马尔克斯的著名小说，讲述了布恩迪亚家族的七代人。', 'https://winfo.crc.com.cn/crc_mobile/crc/magazine/248/202009/W020200902710292658150.jpg', 3, 5),
(56, 'Kafka on the Shore', 10, 95000, 'Kafka on the Shore là tiểu thuyết của Haruki Murakami, kể về hành trình của một cậu bé bỏ nhà ra đi.', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1429638085i/4929.jpg', 4, 2),
(57, 'The Catcher in the Rye', 10, 75000, 'The Catcher in the Rye là tiểu thuyết của J.D. Salinger, kể về cuộc phiêu lưu của thiếu niên Holden Caulfield.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/The_Catcher_in_the_Rye_%281951%2C_first_edition_cover%29.jpg/640px-The_Catcher_in_the_Rye_%281951%2C_first_edition_cover%29.jpg', 1, 1),
(58, 'Notre-Dame de Paris', 10, 90000, 'Notre-Dame de Paris là tiểu thuyết của Victor Hugo, kể về câu chuyện tình yêu và bi kịch xung quanh nhà thờ Đức Bà Paris.', 'https://m.media-amazon.com/images/I/81mypeo8dKL._AC_UF1000,1000_QL80_.jpg', 2, 6),
(59, 'Mein Kampf', 10, 95000, 'Mein Kampf là cuốn tự truyện và tuyên ngôn chính trị của Adolf Hitler, viết về tư tưởng và kế hoạch của ông.', 'https://play-lh.googleusercontent.com/36aGUrapjhY5bkbm0nJdAaweEgUJibpmEfMNCvwrz4KQ_J4-i1rGRdIuaYF3Fu4FE3pH=w526-h296-rw', 3, 7),
(60, 'Fifty Shades of Grey', 10, 80000, 'Fifty Shades of Grey là tiểu thuyết lãng mạn của E.L. James, kể về mối quan hệ giữa sinh viên Anastasia Steele và tỷ phú Christian Grey.', 'https://upload.wikimedia.org/wikipedia/en/5/5e/50ShadesofGreyCoverArt.jpg', 4, 6),
(61, 'Don Quixote', 10, 100000, 'Don Quixote là tiểu thuyết của Miguel de Cervantes, kể về cuộc phiêu lưu của một hiệp sĩ tưởng tượng.', 'https://bizweb.dktcdn.net/thumb/1024x1024/100/363/455/products/donquixotetap1.jpg?v=1710306237443', 1, 3),
(62, 'La Peste', 10, 85000, 'La Peste là tiểu thuyết của Albert Camus, kể về sự bùng phát của bệnh dịch hạch tại thành phố Oran.', 'https://upload.wikimedia.org/wikipedia/commons/c/c6/La_Peste_book_cover.jpg', 2, 2),
(63, 'Mein Name sei Gantenbein', 10, 95000, 'Mein Name sei Gantenbein là tiểu thuyết của Max Frisch, kể về cuộc sống và những tưởng tượng của nhân vật chính.', 'https://m.media-amazon.com/images/I/81hjz9Q1nTS._AC_UF894,1000_QL80_.jpg', 3, 1),
(64, 'Nhật ký Anne Frank', 10, 75000, 'Nhật ký Anne Frank là cuốn nhật ký của cô gái Do Thái Anne Frank trong thời gian trốn tránh Đức Quốc Xã.', 'https://salt.tikicdn.com/cache/w1200/media/catalog/product/a/n/anne_frank.jpg', 4, 8),
(65, 'To Kill a Mockingbird', 10, 80000, 'To Kill a Mockingbird là tiểu thuyết của Harper Lee, kể về sự bất công và phân biệt chủng tộc ở miền Nam nước Mỹ.', 'https://m.media-amazon.com/images/I/81gepf1eMqL._AC_UF1000,1000_QL80_.jpg', 1, 5),
(66, 'Le Petit Prince', 10, 85000, 'Le Petit Prince là cuốn sách của Antoine de Saint-Exupéry, kể về cuộc hành trình của một hoàng tử nhỏ từ hành tinh này đến hành tinh khác.', 'https://m.media-amazon.com/images/I/61NGp-UxolL._AC_UF1000,1000_QL80_.jpg', 2, 7),
(67, 'The Great Gatsby', 10, 90000, 'The Great Gatsby là tiểu thuyết của F. Scott Fitzgerald, kể về cuộc sống xa hoa và bi kịch của Jay Gatsby.', 'https://upload.wikimedia.org/wikipedia/commons/7/7a/The_Great_Gatsby_Cover_1925_Retouched.jpg', 3, 9),
(74, 'wfwf', 1, 1, 'fqfqf', 'qffq', 6, 5),
(75, '2r2r2', 1, 1, '1wfwf', 'fwfwf', 7, 7),
(76, 'qf', 1, 2, 'fwgw', 'gwegw', 1, 7),
(77, 'dq', 1, 1, 'f', 'q', 8, 8),
(78, 'dqww', 1, 1, 'qfqf', 'qfqf', 7, 3),
(79, '1141', 1, 1, '1', '1', 7, 8),
(80, 'master of leak', 10, 20000, 'đây là 1 sản phẩm hay', 'fqwwwwwwwwwwwwww', 8, 8),
(81, '1', 1, 1, '1', '1', 7, 8),
(82, '11111', 1, 1, '1111', '11', 7, 7),
(83, '1e1r1', 1, 10001, 'fqfqf', 'fqfqf', 1, 8),
(84, 'abc', 1, 1, '1', '1', 2, 1),
(85, '42y', 1, 1, '3g2', 'ggw', 2, 5),
(86, 'gqe', 1, 1, 'wg', 'wegew', 1, 7),
(87, 'fwwhwr', 1, 1, 'e1', '111', 2, 3),
(88, 'fwwhwrq3f', 1, 1, 'e1', '111', 2, 3),
(89, 'qrqrqrnnkmn', 1, 1, 'gưh', 'gq', 1, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tac_gia`
--

DROP TABLE IF EXISTS `tac_gia`;
CREATE TABLE IF NOT EXISTS `tac_gia` (
  `maTG` int NOT NULL AUTO_INCREMENT,
  `tenTG` varchar(255) NOT NULL,
  PRIMARY KEY (`maTG`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tac_gia`
--

INSERT INTO `tac_gia` (`maTG`, `tenTG`) VALUES
(1, 'Mario Puzo'),
(2, 'Victor Hugo'),
(3, 'Yuval Noah Harari'),
(4, 'Haruki Murakami'),
(5, 'Bernhard Schlink'),
(6, 'E.L. James'),
(7, 'Miguel de Cervantes'),
(8, 'Albert Camus'),
(9, 'Max Frisch'),
(10, 'Anne Frank'),
(11, 'Harper Lee'),
(12, 'Kim'),
(13, 'Kimm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tg_sach`
--

DROP TABLE IF EXISTS `tg_sach`;
CREATE TABLE IF NOT EXISTS `tg_sach` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maTG` int NOT NULL,
  `maSach` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_tg_sach` (`maTG`,`maSach`),
  KEY `tg_sach_ibfk_2` (`maSach`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tg_sach`
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
(40, 5, 3),
(18, 5, 53),
(19, 6, 4),
(20, 6, 60),
(21, 6, 63),
(30, 6, 87),
(22, 7, 57),
(29, 7, 87),
(23, 8, 62),
(24, 8, 64),
(25, 9, 59),
(26, 10, 65),
(27, 11, 66),
(28, 11, 67);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro`
--

DROP TABLE IF EXISTS `vaitro`;
CREATE TABLE IF NOT EXISTS `vaitro` (
  `maVaiTro` int NOT NULL AUTO_INCREMENT,
  `tenVaiTro` varchar(255) NOT NULL,
  PRIMARY KEY (`maVaiTro`),
  UNIQUE KEY `tenVaiTro` (`tenVaiTro`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro`
--

INSERT INTO `vaitro` (`maVaiTro`, `tenVaiTro`) VALUES
(1, 'khachhang'),
(2, 'nhanvien');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vaitro_quyen`
--

DROP TABLE IF EXISTS `vaitro_quyen`;
CREATE TABLE IF NOT EXISTS `vaitro_quyen` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `maVaiTro` int NOT NULL,
  `maQuyen` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_vaitro_quyen` (`maVaiTro`,`maQuyen`),
  KEY `vaitro_quyen_ibfk_2` (`maQuyen`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vaitro_quyen`
--

INSERT INTO `vaitro_quyen` (`ID`, `maVaiTro`, `maQuyen`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 1, 6),
(4, 2, 1),
(5, 2, 2),
(6, 2, 3),
(7, 2, 4),
(8, 2, 6);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`masach`) REFERENCES `sach` (`maSach`),
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`madon`) REFERENCES `don_dat_hang` (`madon`);

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`maND`) REFERENCES `nguoi_dung` (`maND`),
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Các ràng buộc cho bảng `dm_sach`
--
ALTER TABLE `dm_sach`
  ADD CONSTRAINT `dm_sach_ibfk_1` FOREIGN KEY (`maDM`) REFERENCES `danh_muc` (`maDM`),
  ADD CONSTRAINT `dm_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Các ràng buộc cho bảng `don_dat_hang`
--
ALTER TABLE `don_dat_hang`
  ADD CONSTRAINT `don_dat_hang_ibfk_1` FOREIGN KEY (`maND`) REFERENCES `nguoi_dung` (`maND`);

--
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `nguoi_dung_ibfk_1` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`);

--
-- Các ràng buộc cho bảng `nn_sach`
--
ALTER TABLE `nn_sach`
  ADD CONSTRAINT `nn_sach_ibfk_1` FOREIGN KEY (`maNN`) REFERENCES `ngon_ngu` (`maNN`),
  ADD CONSTRAINT `nn_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Các ràng buộc cho bảng `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`maKM`) REFERENCES `khuyen_mai` (`maKM`),
  ADD CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`maNXB`) REFERENCES `nha_xuat_ban` (`maNXB`);

--
-- Các ràng buộc cho bảng `tg_sach`
--
ALTER TABLE `tg_sach`
  ADD CONSTRAINT `tg_sach_ibfk_1` FOREIGN KEY (`maTG`) REFERENCES `tac_gia` (`maTG`),
  ADD CONSTRAINT `tg_sach_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`);

--
-- Các ràng buộc cho bảng `vaitro_quyen`
--
ALTER TABLE `vaitro_quyen`
  ADD CONSTRAINT `vaitro_quyen_ibfk_1` FOREIGN KEY (`maVaiTro`) REFERENCES `vaitro` (`maVaiTro`),
  ADD CONSTRAINT `vaitro_quyen_ibfk_2` FOREIGN KEY (`maQuyen`) REFERENCES `quyen` (`maQuyen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
