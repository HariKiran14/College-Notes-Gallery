-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 09:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_notes`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subject` varchar(255) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_id`, `title`, `content`, `description`, `file_name`, `file_path`, `uploaded_at`, `subject`, `year`) VALUES
(13, 22, 'se', '', 'dd', NULL, 'uploads/Module3_question_bank[1].docx', '2024-07-25 19:08:13', 'dd', 2),
(14, 22, 'se', '', 'dfgg', NULL, 'uploads/ISE Logo.png', '2024-07-25 19:10:10', 'gg', 2),
(15, 25, 'ppt', '', 'jdjd', NULL, 'uploads/Module3_question_bank[1] (1).docx', '2024-07-25 19:13:54', 'gggggf', 3),
(16, 22, 'miniproject', '', '2 exam', NULL, 'uploads/mini project 6th sem.pdf', '2024-07-25 19:17:24', 'project', 3),
(17, 22, 'presention', '', 'drohhh', NULL, 'uploads/Presentation.pptx', '2024-07-25 19:18:16', 'ai', 4),
(18, 30, 'spark', '', 'spark in hadoop', NULL, 'uploads/Spark for Dummies.pdf', '2024-07-26 01:47:45', 'Big Data', 3),
(19, 30, 'timetable', '', '3 rd year', NULL, 'uploads/WhatsApp Image 2024-07-18 at 10.31.28_5b454787.jpg', '2024-07-26 01:49:01', 'all', 3);

-- --------------------------------------------------------

--
-- Table structure for table `note_categories`
--

CREATE TABLE `note_categories` (
  `note_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `two_fa_codes`
--

CREATE TABLE `two_fa_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Student','Faculty') NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `profile_image`, `join_date`, `security_question`, `security_answer`) VALUES
(22, 'admin', 'fejego.ofocem@rungel.net', '$2y$10$vTQjIqQtdFbwhYMJbHyWquqXCHU2rDBm4.knAN9YQVXxiCleY7pma', 'Faculty', NULL, '2024-07-25 16:50:11', '', ''),
(23, 'user', 'fejego.oocem@rungel.net', '$2y$10$fv9R/muYWJcKfH/Ad07O/esCrS1OiDzsWbLyUesSclWZKCZgEStie', 'Student', NULL, '2024-07-25 16:50:11', '', ''),
(25, 'faculty', 'feego.oocem@rungel.net', '$2y$10$svh/mPWLX7l6lNM4FXMeheUC53elp.OpJo/xN7r9Mz6OMRmkblevq', 'Faculty', NULL, '2024-07-25 16:50:11', '', ''),
(26, 'Dileep', 'dileep@gmail.com', '$2y$10$XealnhnlAFlst3PfH3FXYuRs4l1KMqLiMCPZSH84aHBXL4MjjU6Fa', 'Student', NULL, '2024-07-25 18:41:40', '', ''),
(29, 'aru', 'dile@gmail.com', '$2y$10$U8CUDaRqg2DF6q5swFp1p.W.YKgPPOc4vR.KC/PpWyXqUeF7QIubm', 'Student', 'uploads/66a29fe0449d86.32691334.jpg', '2024-07-25 18:56:32', '', ''),
(30, 'Vinutha M R', 'mrv@mcehassan.ac.in', '$2y$10$KAFZC.mG/4WOOK0FRB2sBOugLp1rCvNln5Ntbew5KZBNGaUTjblG6', 'Faculty', 'uploads/66a2ffbe34e0b5.32396347.jpg', '2024-07-26 01:45:34', '', ''),
(32, 'Dileep1', 'dileepkmrc@gmail.com', '$2y$10$WwMJB2MUukyMz5Incgnf6OXe.rdxIbP4OTmnJozHFHLF/EgwMHF4C', 'Student', 'uploads/66a3e690c9fa45.10355616.jpg', '2024-07-26 18:10:24', '', ''),
(39, 'hari', 'kkjhj@gmail.com', '$2y$10$pmn/LoFFwBRsDDW9wpqDJexs1eWs3OyC48ZnxmjUkC9Kvcs1gtB4C', 'Student', 'uploads/66a3ed90b46cd4.05884017.jpeg', '2024-07-26 18:40:16', 'nick name', '$2y$10$mjfpKBiJ239TIxVilkE.3.ZwtowXhxWK/qZJwru50G4BemqFMZqN6'),
(40, 'Deepith', 'deepithgc@gmail.com', '$2y$10$YH56ByzcTxvIaqN.elQyOOw5XdvWI5IvvVIGrKkVLdX56eBWJzmCK', 'Student', 'uploads/66a4e6f3e25736.57971769.jpg', '2024-07-27 12:24:20', 'place', '$2y$10$u6JNgUJ3JrkAsenHAH4uieBy1.JGutUMTE4KzycBHb1p62MmNqGKG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `note_categories`
--
ALTER TABLE `note_categories`
  ADD KEY `note_id` (`note_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `two_fa_codes`
--
ALTER TABLE `two_fa_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `two_fa_codes`
--
ALTER TABLE `two_fa_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `note_categories`
--
ALTER TABLE `note_categories`
  ADD CONSTRAINT `note_categories_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`note_id`),
  ADD CONSTRAINT `note_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `two_fa_codes`
--
ALTER TABLE `two_fa_codes`
  ADD CONSTRAINT `two_fa_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
