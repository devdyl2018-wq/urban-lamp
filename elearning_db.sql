-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 08:28 PM
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
-- Database: `elearning_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `mentor` varchar(100) DEFAULT 'Tim E-Learning',
  `duration` varchar(50) DEFAULT '1 Jam',
  `level` enum('Pemula','Menengah','Mahir') DEFAULT 'Pemula'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `price`, `mentor`, `duration`, `level`) VALUES
(1, 'Python Dasar', 'Belajar sintaks dasar Python untuk pemula.', 150000.00, 'Budi Santoso', '2 Jam', 'Pemula'),
(2, 'Fullstack Web Laravel', 'Membangun website toko online dengan Laravel.', 250000.00, 'Eko Kurniawan', '10 Jam', 'Mahir'),
(3, 'Desain UI/UX Figma', 'Membuat desain aplikasi mobile yang user friendly.', 99000.00, 'Siska Kohl', '5 Jam', 'Menengah'),
(4, 'React JS Frontend', 'Membangun SPA interaktif dengan React.', 300000.00, 'Sandhika Galih', '8 Jam', 'Menengah'),
(5, 'Digital Marketing Ads', 'Strategi beriklan di Facebook dan Google.', 120000.00, 'Denny Santoso', '3 Jam', 'Pemula'),
(6, 'Data Science Intro', 'Pengenalan Python untuk data analisis.', 250000.00, 'Sarah Wijaya', '6 Jam', 'Menengah'),
(7, 'Android Kotlin', 'Membuat aplikasi Android Native.', 150000.00, 'Rudi Hartono', '12 Jam', 'Mahir'),
(8, 'Copywriting 101', 'Teknik menulis yang menjual.', 99000.00, 'Dian Sastro', '2 Jam', 'Pemula'),
(9, 'SEO Masterclass', 'Optimasi website agar muncul di Google.', 300000.00, 'Andi SEO', '4 Jam', 'Menengah'),
(10, 'Cyber Security Basic', 'Dasar keamanan jaringan.', 120000.00, 'Mr. Robot', '5 Jam', 'Pemula');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enroll_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enroll_date`) VALUES
(4, 3, 7, '2025-12-16 18:57:21'),
(5, 1, 5, '2025-12-16 18:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `dob` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `gender`, `dob`, `created_at`, `role`) VALUES
(1, 'test1', 'Test@gmail.com', '11223344', 'Male', '2000-12-12', '2025-12-16 18:54:37', 'student'),
(2, 'test2', 'test2@gmail.com', '11223344', 'Male', '2000-12-12', '2025-12-16 18:55:26', 'student'),
(3, 'admin1', 'admin@gmail.com', '11223344', 'Male', '2000-12-12', '2025-12-16 18:55:53', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
