-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 23, 2025 lúc 04:08 AM
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
-- Cấu trúc bảng cho bảng `assignment_permissions`
--

CREATE TABLE `assignment_permissions` (
  `id` int(11) NOT NULL,
  `assigner_user_id` int(11) DEFAULT NULL,
  `assigner_position_id` int(11) NOT NULL,
  `assigner_unit_id` int(11) NOT NULL,
  `target_unit_id` int(11) DEFAULT NULL,
  `target_position_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model`, `model_id`, `data`, `created`) VALUES
(1, NULL, 'update', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(2, NULL, 'update', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(3, NULL, 'create', 'Users', 2, NULL, '0000-00-00 00:00:00'),
(4, 1, 'update', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(5, 1, 'create', 'Users', 3, NULL, '0000-00-00 00:00:00'),
(6, 1, 'delete', 'Users', 3, NULL, '0000-00-00 00:00:00'),
(7, 1, 'create', 'Users', 4, NULL, '0000-00-00 00:00:00'),
(8, 1, 'delete', 'Users', 4, NULL, '0000-00-00 00:00:00'),
(9, 1, 'create', 'Users', 5, NULL, '0000-00-00 00:00:00'),
(10, 1, 'delete', 'Users', 5, NULL, '0000-00-00 00:00:00'),
(11, 1, 'create', 'Users', 6, NULL, '0000-00-00 00:00:00'),
(12, 1, 'delete', 'Users', 6, NULL, '0000-00-00 00:00:00'),
(13, 1, 'create', 'Users', 7, NULL, '0000-00-00 00:00:00'),
(14, 1, 'delete', 'Users', 7, NULL, '0000-00-00 00:00:00'),
(15, 1, 'create', 'Users', 8, NULL, '0000-00-00 00:00:00'),
(16, 1, 'create', 'Users', 9, NULL, '0000-00-00 00:00:00'),
(17, 1, 'create', 'Users', 10, NULL, '0000-00-00 00:00:00'),
(18, 1, 'delete', 'Users', 10, NULL, '0000-00-00 00:00:00'),
(19, 1, 'delete', 'Users', 9, NULL, '0000-00-00 00:00:00'),
(20, 1, 'delete', 'Users', 8, NULL, '0000-00-00 00:00:00'),
(21, 1, 'create', 'Users', 11, NULL, '0000-00-00 00:00:00'),
(22, 1, 'create', 'Users', 12, NULL, '0000-00-00 00:00:00'),
(23, 1, 'create', 'Users', 13, NULL, '0000-00-00 00:00:00'),
(24, 1, 'create', 'Users', 14, NULL, '0000-00-00 00:00:00'),
(25, 1, 'delete', 'Users', 17, NULL, '0000-00-00 00:00:00'),
(26, 1, 'create', 'Users', 18, NULL, '0000-00-00 00:00:00'),
(27, 1, 'update', 'Users', 18, NULL, '0000-00-00 00:00:00'),
(28, 1, 'create', 'Users', 19, NULL, '0000-00-00 00:00:00'),
(29, 1, 'create', 'Users', 20, NULL, '0000-00-00 00:00:00'),
(30, 1, 'update', 'Users', 20, NULL, '0000-00-00 00:00:00'),
(31, 1, 'create', 'Users', 21, NULL, '0000-00-00 00:00:00'),
(32, 1, 'upload_avatar', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(33, 1, 'delete_avatar', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(34, 1, 'upload_avatar', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(35, 21, 'update', 'Users', 21, NULL, '0000-00-00 00:00:00'),
(36, 21, 'upload_avatar', 'Users', 21, NULL, '0000-00-00 00:00:00'),
(37, 1, 'login', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(38, 1, 'login', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(39, 1, 'create', 'Users', 22, NULL, '0000-00-00 00:00:00'),
(40, 1, 'update', 'Users', 22, NULL, '0000-00-00 00:00:00'),
(41, 1, 'update', 'Users', 19, NULL, '0000-00-00 00:00:00'),
(42, 1, 'update', 'Users', 1, NULL, '0000-00-00 00:00:00'),
(43, 1, 'update', 'Users', 20, NULL, '0000-00-00 00:00:00'),
(44, 1, 'update', 'Users', 18, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cat_tables`
--

CREATE TABLE `cat_tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_id` int(10) UNSIGNED NOT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cat_tables`
--

INSERT INTO `cat_tables` (`id`, `name`, `unit_id`, `created`, `modified`) VALUES
(1, 'Tổ chức hành chính', 3, '2025-07-19 22:43:38', '2025-07-19 22:43:38'),
(2, 'Cán bộ, công chức, viên chức', 3, '2025-07-19 22:44:16', '2025-07-19 23:45:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `column_aliases`
--

CREATE TABLE `column_aliases` (
  `id` int(11) NOT NULL,
  `user_table_id` int(11) NOT NULL,
  `column_name` varchar(100) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `data_type` varchar(50) NOT NULL,
  `original_type` varchar(50) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `column_aliases`
--

INSERT INTO `column_aliases` (`id`, `user_table_id`, `column_name`, `original_name`, `data_type`, `original_type`, `sort_order`, `created`, `modified`) VALUES
(12, 10, 'cot_2', 'cot 22', 'varchar', 'file', 1, '2025-07-21 00:36:45', '2025-07-21 00:39:08'),
(14, 10, 'cot_3', 'cot 3', 'varchar', 'varchar', 2, '2025-07-21 00:37:54', '2025-07-21 00:38:32'),
(15, 10, 'cot_4', 'cot 4', 'float', 'float', 3, '2025-07-21 00:38:05', '2025-07-21 00:38:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20250707162626, 'CreateUnits', '2025-07-07 16:29:46', '2025-07-07 16:29:46', 0),
(20250707162741, 'CreateUsers', '2025-07-07 16:29:46', '2025-07-07 16:29:46', 0),
(20250707162813, 'CreateAuditLogs', '2025-07-07 16:29:46', '2025-07-07 16:29:46', 0),
(20250707162857, 'CreateTasks', '2025-07-07 16:29:46', '2025-07-07 16:29:46', 0),
(20250709134414, 'AddIsAdminToUsers', '2025-07-09 13:44:53', '2025-07-09 13:44:53', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `positions`
--

INSERT INTO `positions` (`id`, `name`, `code`, `level`, `created`, `modified`) VALUES
(1, 'Chủ tịch', 'CT', 4, '2025-07-11 16:50:29', '2025-07-11 17:10:17'),
(3, 'Phó Chủ tịch', 'PCT', 3, '2025-07-11 17:06:51', '2025-07-11 17:10:30'),
(4, 'Trưởng phòng', 'TP', 2, '2025-07-11 17:07:40', '2025-07-11 17:07:40'),
(5, 'Phó Trưởng phòng', 'PTP', 1, '2025-07-11 17:10:59', '2025-07-11 17:10:59'),
(7, 'Phó Giám đốc', 'PGĐ', 1, '2025-07-11 17:17:13', '2025-07-11 17:17:13'),
(8, 'Chuyên viên', 'CV', 0, '2025-07-13 12:59:58', '2025-07-13 12:59:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quan_ly_khu_phos`
--

CREATE TABLE `quan_ly_khu_phos` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cot_2` varchar(255) NOT NULL DEFAULT '',
  `cot_3` varchar(255) NOT NULL DEFAULT '',
  `cot_4` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `record_categories`
--

CREATE TABLE `record_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `level` varchar(10) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `record_files`
--

CREATE TABLE `record_files` (
  `id` int(11) NOT NULL,
  `record_category_id` int(11) DEFAULT NULL,
  `number` varchar(20) NOT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `retention_period` varchar(50) DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `is_repeat` tinyint(1) NOT NULL DEFAULT 0,
  `repeat_type` varchar(255) DEFAULT NULL,
  `repeat_until` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) NOT NULL DEFAULT 0,
  `rght` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `description`, `parent_id`, `lft`, `rght`, `level`, `created`, `modified`) VALUES
(2, 'Ủy ban nhân dân phường Bình Tây', 'UBND', NULL, NULL, 1, 6, 0, '2025-07-11 14:26:21', '2025-07-11 14:26:42'),
(3, 'Phòng Văn hóa - Xã hội', 'VHXH', NULL, 2, 2, 3, 0, '2025-07-11 14:31:06', '2025-07-11 14:31:06'),
(4, 'Admin', 'A', NULL, NULL, 7, 8, 0, '2025-07-11 17:13:22', '2025-07-11 17:13:22'),
(5, 'Phòng Kinh tế, Hạ tầng và Đô thị', 'KTHTĐT', NULL, 2, 4, 5, 0, '2025-07-11 17:14:01', '2025-07-11 17:14:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `can_assign_tasks` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `avatar`, `unit_id`, `position_id`, `level`, `is_active`, `is_admin`, `can_assign_tasks`, `created`, `modified`) VALUES
(1, 'admin', '$2y$10$sagnrpwymvV1NarZbpBj7OcIJQhMLHDk7x5NrDbHbUcOC1z2oFiFS', 'Quản trị hệ thống', 'admin@yahoo.com', '0123456789', 'uploads/avatars/1_1752429188.jpg', 4, 8, 0, 1, 1, 0, '2025-07-07 16:45:20', '2025-07-15 22:27:24'),
(18, 'admin7', '$2y$10$4UjOPPF2SV./Q2Ox.3jTxelduMM0Ha1lN7eyIs5NxsOY6ZZPf/n76', 'admin_7', 'admin7@yahoo.com', '', NULL, 4, 8, 0, 1, 0, 0, '2025-07-13 04:26:32', '2025-07-15 22:28:29'),
(19, 'admin1', '$2y$10$wqazWwfHKbJAt5ESc8R8Ke6pm6oBtwz7BJvYi/wQCAoGqUdCRN4V.', 'admin 1', 'admin1@yahoo.com', '', NULL, 4, 8, 0, 1, 0, 0, '2025-07-13 04:29:56', '2025-07-13 23:20:08'),
(20, 'admin3', '$2y$10$vkVGXAu..9KMNrs4OuzGsuymRPjFqqoYMCyViztmdGSnX4LW49Am2', 'admin 3', 'admin3@yahoo.com', '', NULL, 4, 8, 0, 1, 0, 0, '2025-07-13 11:36:42', '2025-07-15 22:27:59'),
(21, 'h1', '$2y$10$Ftm49AW.V2bTxyKRSLfEIe11hwPchhOHJttUeYLTvsL3Fx.sgKWjy', 'h1', 'h1@yahoo.com', '0123456789', 'uploads/avatars/21_1752419497.jpg', 3, 8, 0, 1, 0, 0, '2025-07-13 13:36:46', '2025-07-13 22:11:37'),
(22, 'h2', '$2y$10$UyFyt4igvTiTQMfjkyVZKOFXZjS6WEBym9MDZ6zja25QkBvqq4lv.', 'h2', 'h2@yahoo.com', '0123456789', 'uploads/avatars/22_1752429238.jpg', 3, 5, 0, 1, 0, 1, '2025-07-13 22:27:45', '2025-07-14 00:53:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_tables`
--

CREATE TABLE `user_tables` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `original_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_tables`
--

INSERT INTO `user_tables` (`id`, `user_id`, `table_name`, `created`, `original_name`, `status`, `is_active`) VALUES
(10, 22, 'quan_ly_khu_phos', '2025-07-20 23:25:41', 'quản lý khu phố', 0, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `assignment_permissions`
--
ALTER TABLE `assignment_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_assigner_position_id` (`assigner_position_id`),
  ADD KEY `idx_assigner_unit_id` (`assigner_unit_id`),
  ADD KEY `idx_target_unit_id` (`target_unit_id`),
  ADD KEY `idx_target_position_id` (`target_position_id`),
  ADD KEY `idx_assigner_user_id` (`assigner_user_id`);

--
-- Chỉ mục cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cat_tables`
--
ALTER TABLE `cat_tables`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `column_aliases`
--
ALTER TABLE `column_aliases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_table_id` (`user_table_id`,`column_name`);

--
-- Chỉ mục cho bảng `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Chỉ mục cho bảng `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_positions_name` (`name`);

--
-- Chỉ mục cho bảng `quan_ly_khu_phos`
--
ALTER TABLE `quan_ly_khu_phos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx` (`user_id`);

--
-- Chỉ mục cho bảng `record_categories`
--
ALTER TABLE `record_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `record_files`
--
ALTER TABLE `record_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_category_id` (`record_category_id`),
  ADD KEY `creator_id` (`creator_id`);

--
-- Chỉ mục cho bảng `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_assigned_by` (`assigned_by`),
  ADD KEY `idx_assigned_to` (`assigned_to`);

--
-- Chỉ mục cho bảng `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_unit_id` (`unit_id`),
  ADD KEY `fk_users_position` (`position_id`);

--
-- Chỉ mục cho bảng `user_tables`
--
ALTER TABLE `user_tables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `assignment_permissions`
--
ALTER TABLE `assignment_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cat_tables`
--
ALTER TABLE `cat_tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `column_aliases`
--
ALTER TABLE `column_aliases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `quan_ly_khu_phos`
--
ALTER TABLE `quan_ly_khu_phos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `record_categories`
--
ALTER TABLE `record_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `record_files`
--
ALTER TABLE `record_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `user_tables`
--
ALTER TABLE `user_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `assignment_permissions`
--
ALTER TABLE `assignment_permissions`
  ADD CONSTRAINT `fk_ap_assigner_position` FOREIGN KEY (`assigner_position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ap_assigner_unit` FOREIGN KEY (`assigner_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ap_assigner_user` FOREIGN KEY (`assigner_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ap_target_position` FOREIGN KEY (`target_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ap_target_unit` FOREIGN KEY (`target_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `column_aliases`
--
ALTER TABLE `column_aliases`
  ADD CONSTRAINT `column_aliases_ibfk_1` FOREIGN KEY (`user_table_id`) REFERENCES `user_tables` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `record_categories`
--
ALTER TABLE `record_categories`
  ADD CONSTRAINT `record_categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `record_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `record_files`
--
ALTER TABLE `record_files`
  ADD CONSTRAINT `record_files_ibfk_1` FOREIGN KEY (`record_category_id`) REFERENCES `record_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `record_files_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_position` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_users_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
