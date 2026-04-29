-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2026 at 08:01 PM
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
-- Database: `habit_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `habit_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `goal` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habits`
--

INSERT INTO `habits` (`id`, `username`, `habit_name`, `created_at`, `goal`) VALUES
(1, 'mohitshahh', 'running', '2026-04-26 19:26:04', 30),
(2, 'mohitshahh', 'gyming', '2026-04-26 19:26:18', 30),
(3, 'mohitshahh', 'cycling ', '2026-04-26 19:29:44', 30),
(4, 'mohitshahh', 'cycling ', '2026-04-26 19:30:33', 30),
(5, 'mohitm', 'gjhgjh', '2026-04-29 03:20:08', 30),
(6, 'mohitm', 'mohit shah', '2026-04-29 14:59:26', 30),
(7, 'mohitm', 'hi', '2026-04-29 14:59:46', 30);

-- --------------------------------------------------------

--
-- Table structure for table `habit_logs`
--

CREATE TABLE `habit_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `habit_id` int(11) DEFAULT NULL,
  `status` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_logs`
--

INSERT INTO `habit_logs` (`id`, `username`, `habit_id`, `status`) VALUES
(20, 'mohitm', 5, '2026-04-29'),
(22, 'mohitm', 6, '2026-04-29'),
(17, 'mohitshahh', 0, '2026-04-28'),
(1, 'mohitshahh', 1, '2026-04-26'),
(13, 'mohitshahh', 1, '2026-04-28'),
(2, 'mohitshahh', 2, '2026-04-26'),
(14, 'mohitshahh', 2, '2026-04-28'),
(7, 'mohitshahh', 3, '2026-04-26'),
(15, 'mohitshahh', 3, '2026-04-28'),
(11, 'mohitshahh', 4, '2026-04-26'),
(16, 'mohitshahh', 4, '2026-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `username`, `content`, `created_at`, `image`) VALUES
(1, '', '', '2026-04-18 08:05:00', NULL),
(24, 'm', 'so now the elete button is also wokking here ', '2026-04-18 11:17:24', NULL),
(25, 'm', 'ytjygjyghjguk', '2026-04-18 11:17:35', NULL),
(26, 'm', 'hgfhfgjhvgcgcgfcfgcjghfghhjghvghvgjcgjcfgcfgcghfjyfghfghcgfcfjggfcfghfgjfyftftyfp[oiuygfdsdfghjklkjhgfdsertyui9876532qasdftyujnbvdertyjklp-0987trew2345tygcxswertghjiytfdswerfvbhutdawerfghjtdzawerfvbnjiuytfxzawerfhutdszawertyhnbvcxsweruioplkjhgdsaqwertyuikjhgfdswertyuijhgdswertyuikjhgfdswertyuiolkjhgfdsqwertyuiopbvcx', '2026-04-18 11:18:02', NULL),
(28, 'mo', 'mobjbfbfj\r\n', '2026-04-18 16:26:45', NULL),
(31, 'mo', 'uiyugjhgjh', '2026-04-19 03:52:38', NULL),
(32, 'm', 'jhgjhgjhgjhhjbhjbjh\r\n', '2026-04-19 04:50:03', NULL),
(33, 'mohitshahh', 'ijidhfkj\r\n', '2026-04-26 19:33:58', NULL),
(34, 'mohitshahh', 'this is the first post with image', '2026-04-26 20:33:36', '134152809493086772.jpg'),
(36, 'mohitshahh', 'secod post with picture', '2026-04-26 20:40:23', '134204956716255618.jpg'),
(37, 'mohitm', 'jfgjgjh', '2026-04-29 14:59:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `privacy` varchar(10) DEFAULT 'public',
  `name` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `status` int(11) DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `bio`, `privacy`, `name`, `profile_pic`, `status`, `token`, `reset_token`) VALUES
(26, 'mohitshahh', 'mohit@gmail.com', '$2y$10$Iz6Qjz00veZJLcN0bR6PB.FVOwSbpGqUYz4940o.pLcYmsd2GlGw.', 'this is the final and structured also well formed account\r\n', 'public', 'mohitshahh', '134204956716255618.jpg', 0, NULL, NULL),
(27, 'mohit', 'mohitm416x@gmail.com', '$2y$10$sJ1qKR8z6Yvmg6/TezvEQ.sgmBzdmVoHjgelj6Xa33GH4kQcqPdgK', NULL, 'public', 'mohit', 'default.png', 0, NULL, NULL),
(28, 'mohits', 'mohitm16x@gmail.com', '$2y$10$KZe2TeAMGwrZ4cyhErBM5eyCGPV8QVnwtLqde7dK9H4hDepV5m1h2', NULL, 'public', 'mohits', 'default.png', 0, NULL, NULL),
(29, 'mohitshahhh', 'mohitk.shah345410@gmail.com', '$2y$10$aae1.9.NXt4ykW/RXVlUpeKUEH6/cDu26VH9fNr2.75Oalh4fbxtu', NULL, 'public', 'mohit', 'default.png', 0, NULL, NULL),
(30, 'mohitm', 'mohitk.shah345410@gmail.com', '$2y$10$aae1.9.NXt4ykW/RXVlUpeKUEH6/cDu26VH9fNr2.75Oalh4fbxtu', '', 'public', 'mohitm', '134204956716255618.jpg', 1, 'afa3e29dbbe008baee3266a8b1c7a365', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `habit_logs`
--
ALTER TABLE `habit_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`habit_id`,`status`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `habit_logs`
--
ALTER TABLE `habit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
