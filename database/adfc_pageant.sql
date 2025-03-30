-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 04:52 PM
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
-- Database: `adfc_pageant`
--

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

CREATE TABLE `contestants` (
  `id` int(11) NOT NULL,
  `candidate_number` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `department` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `year` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `motto` text NOT NULL,
  `bio` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `block` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`id`, `candidate_number`, `full_name`, `age`, `gender`, `department`, `course`, `year`, `category`, `motto`, `bio`, `photo`, `block`) VALUES
(5, 1, 'Shuuana Shasasa', 21, 'Female', 'Computer Studies', 'BSIT', 'First Year', 'MISS ADFC', 'Bilog ang mundo, kaya kahit talikuran mo ang problema mo, sa huli haharapin mo rin yan sa ayaw mo at gusto.', 'Mia Garcia is a creative and adventurous soul who loves art, music, and storytelling. She believes in kindness, chasing dreams, and making the most of every moment.', '1742343730_sample.jpg', ''),
(8, 1, 'Niano Guerrero', 20, 'Male', 'Liberal Arts', 'BSEDs', 'Second Year', 'MISTER ADFC', 'Be Nice', 'Nice One', '1743178032_man1.jpg', ''),
(20, 2, 'HakSSS', 19, 'Male', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', 'SS', '1743178082_man2.jpg', ''),
(22, 2, 'Mila', 23, 'Female', 'Liberal Arts', 'BSCRIM', '2000', 'MISS ADFC', 'Be good', 'Beyou', '1743177700_can2.jpeg', ''),
(23, 3, 'Marts', 22, 'Female', 'Tycoons', 'BSCRIM', '2000', 'MISS ADFC', 'Be good', 'Eat lang', '1743177800_can3.jpg', ''),
(24, 4, 'Anda', 22, 'Female', 'Knights', 'BSN', '2000', 'MISS ADFC', 'Be good', 'Be nice', '1743177886_CAN4.jpg', ''),
(25, 5, 'Bonie', 22, 'Female', 'Knights', 'BSN', '2000', 'MISS ADFC', 'Be good', 'Nice one', '1743177954_can5.jpg', ''),
(26, 3, 'Bornok', 22, 'Male', 'Knights', 'BSN', '2000', 'MISTER ADFC', 'Be good', '', '1743178303_man3.jpg', ''),
(27, 4, 'Lebron', 22, 'Male', 'Phoenix', 'BSIT', '2000', 'MISTER ADFC', 'Be good', 'GGG', '1743178395_man4.png', ''),
(28, 5, 'Steph', 22, 'Male', 'Warriors', 'BSIT', '2000', 'MISTER ADFC', 'Be good', '', '1743178498_man5.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `top5` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `name`, `percentage`, `category`, `top5`) VALUES
(9, 'Best in costume', 4, 'MISTER ADFC', 0),
(10, 'Miss Congeniality', 8, 'MISS ADFC', 0),
(11, 'Mister Suave', 10, 'MISTER ADFC', 0),
(12, 'Miss Flawless', 10, 'MISS ADFC', 0),
(13, 'Mister Pogi', 10, 'MISTER ADFC', 0),
(14, 'Miss Mamaw', 10, 'MISS ADFC', 0),
(15, 'Final Q&A', 10, 'MISTER ADFC', 1),
(16, 'Final Q&A', 10, 'MISS ADFC', 1);

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `judge_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `criterion_id` int(11) NOT NULL,
  `category` enum('Miss ADFC','Mister ADFC') NOT NULL,
  `score` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `judge_id`, `contestant_id`, `criterion_id`, `category`, `score`) VALUES
(5, 18, 20, 9, 'Mister ADFC', 68.00),
(7, 18, 5, 10, 'Miss ADFC', 10.00),
(11, 18, 8, 9, 'Mister ADFC', 86.00),
(12, 18, 8, 11, 'Mister ADFC', 78.00),
(13, 18, 8, 13, 'Mister ADFC', 89.00),
(14, 18, 20, 11, 'Mister ADFC', 56.00),
(15, 18, 20, 13, 'Mister ADFC', 99.00),
(16, 18, 26, 9, 'Mister ADFC', 99.00),
(17, 18, 26, 11, 'Mister ADFC', 86.00),
(18, 18, 26, 13, 'Mister ADFC', 86.00),
(19, 18, 27, 9, 'Mister ADFC', 89.00),
(20, 18, 27, 11, 'Mister ADFC', 86.00),
(21, 18, 27, 13, 'Mister ADFC', 88.00),
(22, 18, 28, 9, 'Mister ADFC', 88.00),
(23, 18, 28, 11, 'Mister ADFC', 88.00),
(24, 18, 28, 13, 'Mister ADFC', 88.00),
(25, 18, 5, 12, 'Miss ADFC', 95.00),
(26, 18, 5, 14, 'Miss ADFC', 95.00),
(27, 18, 22, 10, 'Miss ADFC', 88.00),
(28, 18, 22, 12, 'Miss ADFC', 85.00),
(29, 18, 22, 14, 'Miss ADFC', 86.00),
(30, 18, 23, 10, 'Miss ADFC', 56.00),
(31, 18, 23, 12, 'Miss ADFC', 86.00),
(32, 18, 23, 14, 'Miss ADFC', 85.00),
(33, 18, 24, 10, 'Miss ADFC', 45.00),
(34, 18, 24, 12, 'Miss ADFC', 86.00),
(35, 18, 24, 14, 'Miss ADFC', 48.00),
(36, 18, 25, 10, 'Miss ADFC', 99.00),
(37, 18, 25, 12, 'Miss ADFC', 99.00),
(38, 18, 25, 14, 'Miss ADFC', 99.00),
(39, 19, 8, 9, 'Mister ADFC', 99.00),
(40, 19, 8, 11, 'Mister ADFC', 99.00),
(41, 19, 8, 13, 'Mister ADFC', 99.00),
(42, 19, 20, 9, 'Mister ADFC', 89.00),
(43, 19, 20, 11, 'Mister ADFC', 99.00),
(44, 19, 20, 13, 'Mister ADFC', 99.00),
(45, 19, 27, 9, 'Mister ADFC', 99.00),
(46, 19, 27, 11, 'Mister ADFC', 99.00),
(47, 19, 24, 10, 'Miss ADFC', 99.00),
(48, 19, 24, 12, 'Miss ADFC', 89.00),
(49, 19, 24, 14, 'Miss ADFC', 89.00),
(50, 19, 23, 10, 'Miss ADFC', 89.00),
(51, 19, 23, 12, 'Miss ADFC', 89.00),
(52, 19, 23, 14, 'Miss ADFC', 90.00),
(53, 19, 5, 10, 'Miss ADFC', 99.00),
(54, 19, 5, 12, 'Miss ADFC', 99.00);

-- --------------------------------------------------------

--
-- Table structure for table `top5toggle`
--

CREATE TABLE `top5toggle` (
  `adfc_edition` varchar(255) NOT NULL DEFAULT concat('adfc ',year(curdate())),
  `top5_enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `top5toggle`
--

INSERT INTO `top5toggle` (`adfc_edition`, `top5_enabled`) VALUES
('adfc 2025', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','judge') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
(17, 'Simon Cowell', '$2y$10$QLJKvxXMZE0yRTXbsRVZD.J1ZB9TzakPsfsQ6QJzVacnDtDd2qXq6', 'judge'),
(18, 'pedro', '81dc9bdb52d04dc20036dbd8313ed055', 'judge'),
(19, 'Martin', '81dc9bdb52d04dc20036dbd8313ed055', 'judge');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contestants`
--
ALTER TABLE `contestants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_score` (`judge_id`,`contestant_id`,`criterion_id`),
  ADD KEY `scores_ibfk_2` (`contestant_id`),
  ADD KEY `scores_ibfk_3` (`criterion_id`);

--
-- Indexes for table `top5toggle`
--
ALTER TABLE `top5toggle`
  ADD PRIMARY KEY (`adfc_edition`);

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
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`judge_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scores_ibfk_3` FOREIGN KEY (`criterion_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
