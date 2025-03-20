-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 11:28 PM
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
(5, 2, 'Shuuana Shasasa', 21, 'Female', 'Computer Studies', 'BSIT', 'First Year', 'MISS ADFC', 'Bilog ang mundo, kaya kahit taliuran mo ang problema mo, sa huli haharapin mo rin yan sa ayaw mo at gusto.', 'Mia Garcia is a creative and adventurous soul who loves art, music, and storytelling. She believes in kindness, chasing dreams, and making the most of every moment.', '1742343730_sample.jpg', ''),
(8, 3, 'Niana Guerrero', 20, 'Female', 'Liberal Arts', 'BSEDs', 'Second Year', 'MISTER ADFC', 'Be Nice', 'Nice One', '1742030026_Picture1.jpg', ''),
(9, 2, 'Ivana Kawali', 27, 'Female', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', 'Gg', '1742348766_c02207204592863.66ac32fe39e7b.png', ''),
(12, 4, 'ShuanaF', 23, 'Male', 'LA', 'BSCRIMs', '2000', 'MISS ADFC', 'Be good', 'ggg', '1742344709_c02207204592863.66ac32fe39e7b.png', ''),
(15, 5, 'Boy', 23, 'Male', 'LA', 'BSCRIMs', '2000', 'MISTER ADFC', 'Be good', 'gg', '1742034443_Picture1.jpg', ''),
(17, 6, 'Marcos', 19, 'Male', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', 'gg', '1742343882_c02207204592863.66ac32fe39e7b.png', ''),
(18, 3, 'Martha', 19, 'Male', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', '', '1742344676_2b5309217435473.6790af569419a.png', ''),
(19, 10, 'Hak', 19, 'Male', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', 'gg', '1742348733_24eac3205034487.66b394f17dade.jpg', ''),
(20, 9, 'HakSSS', 19, 'Male', 'Liberal Arts', 'BSED', 'Second Year', 'MISTER ADFC', 'Be Nice', 'SS', '1742366345_sample.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `judge_id` int(11) DEFAULT NULL,
  `contestant_id` int(11) DEFAULT NULL,
  `criterion_id` int(11) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin');

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
  ADD KEY `judge_id` (`judge_id`),
  ADD KEY `contestant_id` (`contestant_id`),
  ADD KEY `criterion_id` (`criterion_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`judge_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`),
  ADD CONSTRAINT `scores_ibfk_3` FOREIGN KEY (`criterion_id`) REFERENCES `criteria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
