-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 12:01 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irecord`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE `assessment` (
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `area_1` decimal(5,2) DEFAULT NULL,
  `area_2` decimal(5,2) DEFAULT NULL,
  `total` decimal(5,2) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessment_id`, `student_id`, `class`, `school_year`, `semester`, `subject`, `area_1`, `area_2`, `total`, `grade`, `remark`) VALUES
(35, 13, '1A', '2023/24', 'One', 'Computing', '48.00', '43.00', '91.00', '1', 'Excellent'),
(36, 19, '1A', '2023/24', 'One', 'Computing', '23.00', '41.00', '64.00', '4', 'Pass'),
(37, 25, '1A', '2023/24', 'One', 'Computing', '24.00', '45.00', '69.00', '3', 'Good'),
(38, 7, '1A', '2023/24', 'One', 'Carrer Technology', '12.00', '12.00', '24.00', '9', 'Fail'),
(39, 13, '1A', '2023/24', 'One', 'Carrer Technology', '23.00', '31.00', '54.00', '6', 'Pass'),
(40, 19, '1A', '2023/24', 'One', 'Carrer Technology', '41.00', '32.00', '73.00', '2', 'Very Good'),
(41, 25, '1A', '2023/24', 'One', 'Carrer Technology', '14.00', '42.00', '56.00', '5', 'Pass'),
(42, 7, '1A', '2023/24', 'One', 'Integrated Science', '0.00', '98.00', '98.00', '1', 'Excellent'),
(43, 13, '1A', '2023/24', 'One', 'Integrated Science', '0.00', '42.00', '42.00', '8', 'Pass'),
(44, 19, '1A', '2023/24', 'One', 'Integrated Science', '0.00', '32.00', '32.00', '9', 'Fail'),
(45, 25, '1A', '2023/24', 'One', 'Integrated Science', '0.00', '46.00', '46.00', '7', 'Pass');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `class` varchar(100) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `total_attendance` int(20) NOT NULL,
  `total_present` int(20) NOT NULL,
  `total_absent` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `class`, `school_year`, `semester`, `total_attendance`, `total_present`, `total_absent`) VALUES
(4, 7, '1A', '2023/24', 'One', 56, 45, 11),
(5, 13, '1A', '2023/24', 'One', 56, 56, 0),
(6, 19, '1A', '2023/24', 'One', 56, 54, 2),
(7, 25, '1A', '2023/24', 'One', 56, 54, 2);

-- --------------------------------------------------------

--
-- Table structure for table `behavior`
--

CREATE TABLE `behavior` (
  `id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `class` varchar(100) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `conduct` varchar(100) NOT NULL,
  `interest` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `behavior`
--

INSERT INTO `behavior` (`id`, `student_id`, `class`, `school_year`, `semester`, `conduct`, `interest`, `remark`) VALUES
(3, 7, '1A', '2023/24', 'One', 'Honest', 'Collaborative', 'Actively participates in class discussions and activities.'),
(4, 13, '1A', '2023/24', 'One', 'Honest', 'Community', 'Asks questions and seeks deeper understanding.'),
(5, 19, '1A', '2023/24', 'One', 'punctual', 'Collaborative', 'Maintains steady progress in studies.'),
(6, 25, '1A', '2023/24', 'One', 'Courteous', 'Curius', 'Works hard and stays focused on tasks.');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `conduct`
--

CREATE TABLE `conduct` (
  `id` int(11) NOT NULL,
  `conduct` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conduct`
--

INSERT INTO `conduct` (`id`, `conduct`) VALUES
(7, 'Attentive'),
(5, 'Cooperative'),
(8, 'Courteous'),
(10, 'Deligent'),
(9, 'Empathetic'),
(3, 'Engaged'),
(6, 'Honest'),
(11, 'punctual'),
(2, 'Respectful'),
(4, 'resposible');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Staff') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `graduate`
--

CREATE TABLE `graduate` (
  `student_id` int(11) NOT NULL,
  `index_number` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `admission_date` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `hall` varchar(100) NOT NULL,
  `program` varchar(100) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `parent` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `passport_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `graduate_assessment`
--

CREATE TABLE `graduate_assessment` (
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `area_1` decimal(5,2) DEFAULT NULL,
  `area_2` decimal(5,2) DEFAULT NULL,
  `area_3` decimal(5,2) DEFAULT NULL,
  `area_4` decimal(5,2) DEFAULT NULL,
  `area_5` decimal(5,2) DEFAULT NULL,
  `area_6` decimal(5,2) DEFAULT NULL,
  `total` decimal(5,2) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `graduate_attendance`
--

CREATE TABLE `graduate_attendance` (
  `id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `class` varchar(100) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `total_attendance` int(20) NOT NULL,
  `total_present` int(20) NOT NULL,
  `total_absent` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `graduate_behavior`
--

CREATE TABLE `graduate_behavior` (
  `id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `class` varchar(100) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `conduct` varchar(100) NOT NULL,
  `interest` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hall`
--

CREATE TABLE `hall` (
  `id` int(20) NOT NULL,
  `hall` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hall`
--

INSERT INTO `hall` (`id`, `hall`) VALUES
(0, 'House 1'),
(0, 'House 2'),
(0, 'House 3'),
(0, 'House 4');

-- --------------------------------------------------------

--
-- Table structure for table `interest`
--

CREATE TABLE `interest` (
  `id` int(11) NOT NULL,
  `interest` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `interest`
--

INSERT INTO `interest` (`id`, `interest`) VALUES
(3, 'Collaborative'),
(2, 'Community'),
(4, 'Crreative'),
(5, 'Curius');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `program` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `total` decimal(5,2) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`assessment_id`, `student_id`, `class`, `school_year`, `semester`, `subject`, `total`, `grade`, `remark`) VALUES
(26, 14, '1B', '2023/24', 'One', 'Integrated Science', '78.00', 'B2', 'Very Good'),
(27, 20, '1B', '2023/24', 'One', 'Integrated Science', '89.00', 'A1', 'Excellent'),
(28, 26, '1B', '2023/24', 'One', 'Integrated Science', '98.00', 'A1', 'Excellent');

-- --------------------------------------------------------

--
-- Table structure for table `remark`
--

CREATE TABLE `remark` (
  `id` int(11) NOT NULL,
  `remark` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `remark`
--

INSERT INTO `remark` (`id`, `remark`) VALUES
(3, 'Actively participates in class discussions and activities.'),
(4, 'Asks questions and seeks deeper understanding.'),
(5, 'Maintains steady progress in studies.'),
(2, 'Works hard and stays focused on tasks.');

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `school_year_id` int(11) NOT NULL,
  `school_year` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`school_year_id`, `school_year`) VALUES
(6, '2024/25');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semester_id`, `semester_name`) VALUES
(5, 'One'),
(7, 'Three'),
(6, 'Two');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `index_number` varchar(50) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `admission_date` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `hall` varchar(100) NOT NULL,
  `program` varchar(100) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `index_number`, `fname`, `mname`, `surname`, `name`, `date_of_birth`, `admission_date`, `gender`, `hall`, `program`, `class`, `school_year`, `location`, `password`) VALUES
(7, '101010101', '', '', '', 'Adansie', '0000-00-00', '0000-00-00', 'Male', 'Mathew', 'BECE', '1A', '2023/24', 'Abira', ''),
(8, '123456789', '', '', '', 'Animonyam', '0000-00-00', '0000-00-00', 'Female', 'Mark', 'BECE', '1B', '2023/24', 'Abira', ''),
(9, '122345432', '', '', '', 'Amoah', '0000-00-00', '0000-00-00', 'Male', 'Luke', 'BECE', '2A', '2023/24', 'Abira', ''),
(10, '767675656', '', '', '', 'Clara', '0000-00-00', '0000-00-00', 'Female', 'John', 'BECE', '2B', '2023/24', 'Abira', ''),
(11, '132435463', '', '', '', 'Mansah', '0000-00-00', '0000-00-00', 'Male', 'Mathew', 'BECE', '3A', '2023/24', 'Abira', ''),
(12, '325673213', '', '', '', 'yeboah', '0000-00-00', '0000-00-00', 'Female', 'Mark', 'BECE', '3B', '2023/24', 'Abira', ''),
(13, '423214532', '', '', '', 'Ciara', '0000-00-00', '0000-00-00', 'Male', 'Luke', 'BECE', '1A', '2023/24', 'Abira', ''),
(14, '533221144', '', '', '', 'Baby', '0000-00-00', '0000-00-00', 'Female', 'John', 'BECE', '1B', '2023/24', 'Abira', ''),
(15, '443332344', '', '', '', 'Hamza', '0000-00-00', '0000-00-00', 'Male', 'Mathew', 'BECE', '2A', '2023/24', 'Abira', ''),
(16, '452223552', '', '', '', 'gilbert', '0000-00-00', '0000-00-00', 'Female', 'Mark', 'BECE', '2B', '2023/24', 'Abira', ''),
(17, '443315512', '', '', '', 'Fonzy', '0000-00-00', '0000-00-00', 'Male', 'Luke', 'BECE', '3A', '2023/24', 'Abira', ''),
(18, '112334224', '', '', '', 'Elom', '0000-00-00', '0000-00-00', 'Female', 'John', 'BECE', '3B', '2023/24', 'Abira', ''),
(19, '442114556', '', '', '', 'Nana Agyei', '0000-00-00', '0000-00-00', 'Male', 'Mathew', 'BECE', '1A', '2023/24', 'Abira', ''),
(20, '664664432', '', '', '', 'Pamela', '0000-00-00', '0000-00-00', 'Female', 'Mark', 'BECE', '1B', '2023/24', 'Abira', ''),
(21, '221221678', '', '', '', 'Frank', '0000-00-00', '0000-00-00', 'Male', 'Luke', 'BECE', '2A', '2023/24', 'Abira', ''),
(22, '657564336', '', '', '', 'Hannah', '0000-00-00', '0000-00-00', 'Female', 'John', 'BECE', '2B', '2023/24', 'Abira', ''),
(23, '556754322', '', '', '', 'Toby', '0000-00-00', '0000-00-00', 'Male', 'Mathew', 'BECE', '3A', '2023/24', 'Abira', ''),
(24, '442525116', '', '', '', 'Uri', '0000-00-00', '0000-00-00', 'Female', 'Mark', 'BECE', '3B', '2023/24', 'Abira', ''),
(25, '4664318989', '', '', '', 'Nelly', '0000-00-00', '0000-00-00', 'Male', 'Luke', 'BECE', '1A', '2023/24', 'Abira', ''),
(26, '2355677832', '', '', '', 'Vans', '0000-00-00', '0000-00-00', 'Female', 'John', 'BECE', '1B', '2023/24', 'Abira', ''),
(27, '5191040095', 'Ken', '', 'Boakye', 'Ken  Boakye', '2024-11-12', '2024-11-12', 'Male', 'HOUSE 1', 'GENERAL SCIENCE', '1A1', '2023/24', 'Tafo', '$2y$10$iuKUjf5UHuXlbD3wh1Sy9ukUh47RL.cJolu/t9XSegAjfQUPDwfVC');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(20) NOT NULL,
  `subject_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject_name`) VALUES
(7, 'English Language'),
(8, 'Mathematics'),
(9, 'Integrated Science'),
(10, 'Social Studies'),
(11, 'Asante ( Twi )'),
(12, 'History'),
(13, 'Computing'),
(14, 'Government');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class_subject`
--

CREATE TABLE `teacher_class_subject` (
  `id` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Staff') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`) VALUES
(5, 'Shalem', '$2y$10$98oTRhI2gLF9EoMlEzPgAuEm8NNmOgfvQBpk0iX/aifoJ/WdCHXey', 'Admin'),
(6, 'Staff', '$2y$10$OoueFLlESSlM1FUMgiFYI.qQ8L825F5HyG62.btNWfnleGaJlm9.6', 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `wkop`
--

CREATE TABLE `wkop` (
  `assessment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `school_year` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `week` varchar(20) NOT NULL,
  `wkop_type` varchar(20) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `score` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wkop`
--

INSERT INTO `wkop` (`assessment_id`, `student_id`, `class`, `school_year`, `semester`, `week`, `wkop_type`, `subject`, `score`) VALUES
(1, 7, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Computing', '7.00'),
(2, 13, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Computing', '7.00'),
(3, 19, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Computing', '8.00'),
(4, 25, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Computing', '9.00'),
(5, 7, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Integrated Science', '7.00'),
(6, 13, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Integrated Science', '6.00'),
(7, 19, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Integrated Science', '8.00'),
(8, 25, '1A', '2023/24', 'One', 'week1', 'Class Exercise', 'Integrated Science', '9.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `behavior`
--
ALTER TABLE `behavior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_name` (`class_name`);

--
-- Indexes for table `conduct`
--
ALTER TABLE `conduct`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`conduct`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `graduate`
--
ALTER TABLE `graduate`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `index_number` (`index_number`);

--
-- Indexes for table `graduate_assessment`
--
ALTER TABLE `graduate_assessment`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `graduate_attendance`
--
ALTER TABLE `graduate_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graduate_behavior`
--
ALTER TABLE `graduate_behavior`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest`
--
ALTER TABLE `interest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`interest`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `remark`
--
ALTER TABLE `remark`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_name` (`remark`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`school_year_id`),
  ADD UNIQUE KEY `school_year` (`school_year`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester_id`),
  ADD UNIQUE KEY `semester_name` (`semester_name`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `index_number` (`index_number`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `teacher_class_subject`
--
ALTER TABLE `teacher_class_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wkop`
--
ALTER TABLE `wkop`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `behavior`
--
ALTER TABLE `behavior`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `conduct`
--
ALTER TABLE `conduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graduate`
--
ALTER TABLE `graduate`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graduate_assessment`
--
ALTER TABLE `graduate_assessment`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graduate_attendance`
--
ALTER TABLE `graduate_attendance`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `graduate_behavior`
--
ALTER TABLE `graduate_behavior`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interest`
--
ALTER TABLE `interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `remark`
--
ALTER TABLE `remark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `school_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teacher_class_subject`
--
ALTER TABLE `teacher_class_subject`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wkop`
--
ALTER TABLE `wkop`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `assessment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
