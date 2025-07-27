-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 27, 2025 lúc 07:46 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlcv`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quan_ly_khu_phos`
--

CREATE TABLE `quan_ly_khu_phos` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cot_3` varchar(255) NOT NULL DEFAULT '',
  `cot_4` float NOT NULL DEFAULT 0,
  `cot_thu_5` text NOT NULL DEFAULT '',
  `cot_6` varchar(255) NOT NULL DEFAULT '',
  `cot_7` varchar(255) NOT NULL DEFAULT '',
  `cot_8` varchar(255) NOT NULL DEFAULT '',
  `cot_9` varchar(255) NOT NULL DEFAULT '',
  `cot_10` varchar(255) NOT NULL DEFAULT '',
  `cot_11` varchar(255) NOT NULL DEFAULT '',
  `cot_13` varchar(255) NOT NULL DEFAULT '',
  `cot_14` varchar(255) NOT NULL DEFAULT '',
  `cot_15` varchar(255) NOT NULL DEFAULT '',
  `cot_16` datetime NOT NULL,
  `cot_thu_17` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `quan_ly_khu_phos`
--

INSERT INTO `quan_ly_khu_phos` (`id`, `user_id`, `created`, `modified`, `cot_3`, `cot_4`, `cot_thu_5`, `cot_6`, `cot_7`, `cot_8`, `cot_9`, `cot_10`, `cot_11`, `cot_13`, `cot_14`, `cot_15`, `cot_16`, `cot_thu_17`) VALUES
(1, 22, '2025-07-27 11:10:48', '2025-07-27 11:10:48', '1', 434.234, 'zxczxc', 'qweqwe', 'zxczxc', 'dfgdf', '6885a6c8abd6b_133902122813475483.jpg', 'bdf', 'zxcz', 'ads', 'zxczxc', 'asdasd', '2025-07-27 13:13:00', '2025-07-19'),
(2, 22, '2025-07-27 11:38:08', '2025-07-27 11:38:08', '0', 343, 'zxczxc asidhasid akjdslknf kljdlks jfkslj fklsj flksj fcj ksd jcsdklj fkljs kjs fljksd fslkdjf sdklfj skljf lksdjf ksldjf klsdjf klsdfj klsdjf ksldjf klsdjf lksdjf klsdjf lksdjf klsdjf ksldfj skldfj skldjf dslkjf sdlkjf klsd jflksdj flksdj fkljsd f', 'dfvdhgfn fbngfn', 'zxczxczxczxc', 'dfbdfb fgnfgn', '6885ad307a44f_RobloxScreenShot20230822_140642623 - Copy.png', 'fgnfgn fgnfgn', 'zxxzczxc', 'ghnng fngn', 'zx zx zx ', 'fgbnfgnfgmn', '2025-07-26 01:39:00', '2025-07-27');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `quan_ly_khu_phos`
--
ALTER TABLE `quan_ly_khu_phos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `quan_ly_khu_phos`
--
ALTER TABLE `quan_ly_khu_phos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
