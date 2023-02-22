-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 22, 2023 at 05:08 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PMS`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `pr_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `pr_id`, `user_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 7, '2023-02-15', '1:30 PM - 2:30 PM', 1, '2023-02-15', '2023-02-15'),
(2, 2, 8, '2023-02-15', '5:52 PM - 5:53 PM', 1, '2023-02-15', '2023-02-15'),
(4, 2, 8, '2023-02-15', '2:30 PM - 3:30 PM', 1, '2023-02-15', '2023-02-15'),
(6, 2, 7, '2023-02-15', '1:30 PM - 2:30 PM', 1, '2023-02-15', '2023-02-15'),
(10, 3, 8, '2023-02-16', '9:30 AM - 10:30 AM', 1, '2023-02-16', '2023-02-16'),
(11, 4, 16, '2023-02-16', '4:30 PM - 5:30 PM', 1, '2023-02-16', '2023-02-16'),
(12, 4, 16, '2023-02-16', '4:30 PM - 5:30 PM', 1, '2023-02-16', '2023-02-16'),
(13, 1, 16, '2023-02-16', '9:00 AM - 10:00 AM', 1, '2023-02-16', '2023-02-16'),
(14, 3, 8, '2023-02-16', '9:30 AM - 10:30 AM', 1, '2023-02-16', '2023-02-16'),
(15, 2, 16, '2023-02-16', '4:30 PM - 5:30 PM', 1, '2023-02-16', '2023-02-16'),
(16, 6, 16, '2023-02-17', '9:00 AM - 10:00 AM', 1, '2023-02-17', '2023-02-17'),
(19, 2, 16, '2023-02-20', '9:00 AM - 10:00 AM', 1, '2023-02-19', '2023-02-20'),
(21, 3, 18, '2023-02-21', '2:00 PM - 4:00 PM', 1, '2023-02-21', '2023-02-21'),
(22, 7, 18, '2023-02-21', '2:00 PM - 4:00 PM', 0, '2023-02-21', '2023-02-21'),
(23, 4, 18, '2023-02-22', '2:00 PM - 4:00 PM', 3, '2023-02-21', '2023-02-22'),
(24, 7, 18, '2023-02-22', '2:00 PM - 4:00 PM', 3, '2023-02-21', '2023-02-22'),
(25, 3, 16, '2023-02-22', '9:10 AM - 10:30 AM', 3, '2023-02-22', '2023-02-22'),
(26, 4, 16, '2023-02-22', '9:10 AM - 10:30 AM', 3, '2023-02-22', '2023-02-22'),
(27, 4, 16, '2023-02-22', '9:10 AM - 10:30 AM', 3, '2023-02-22', '2023-02-22'),
(28, 7, 16, '2023-02-22', '9:10 AM - 10:30 AM', 3, '2023-02-22', '2023-02-22'),
(29, 7, 16, '2023-02-22', '9:10 AM - 10:30 AM', 3, '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `category_id`, `name`, `type_id`, `description`, `company`, `brand`, `created_at`, `updated_at`) VALUES
(1, 2, 'Pineapple', 7, 'Badfsdfasdfaknsdg', 'Pinapple', 'Pinapple', '2023-02-22', '2023-02-22'),
(2, 6, 'Biogestic', 7, 'Badfsdfasdfaknsdg', 'Biogestic', 'Biogestic', '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `medi_category`
--

CREATE TABLE `medi_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medi_category`
--

INSERT INTO `medi_category` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(2, 'Tablets', '2023-01-28', '2023-01-28'),
(3, 'doses', '2023-01-05', '2023-01-05'),
(6, 'biogesic', '2023-01-28', '2023-01-28'),
(7, 'BPI', '2023-01-28', '2023-01-28'),
(8, 'headache', '2023-01-29', '2023-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `medi_stocks`
--

CREATE TABLE `medi_stocks` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `qty` int(100) NOT NULL,
  `price` varchar(255) NOT NULL,
  `per_price` int(10) NOT NULL,
  `man_date` date NOT NULL,
  `exp_date` date NOT NULL,
  `enter_date` date NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medi_stocks`
--

INSERT INTO `medi_stocks` (`id`, `medicine_id`, `qty`, `price`, `per_price`, `man_date`, `exp_date`, `enter_date`, `created_at`, `updated_at`) VALUES
(1, 1, 300, '5000', 17, '2023-02-22', '2023-02-22', '2023-02-22', '2023-02-22', '2023-02-22'),
(2, 2, 200, '30000', 150, '2023-02-22', '2023-02-22', '2023-02-22', '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `medi_type`
--

CREATE TABLE `medi_type` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medi_type`
--

INSERT INTO `medi_type` (`id`, `type`, `created_at`, `updated_at`) VALUES
(2, 'hello', '2023-01-28', '2023-01-28'),
(7, 'diagonsic', '2023-01-28', '2023-01-29'),
(8, 'aaa21', '2023-01-29', '2023-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `medi_warehouses`
--

CREATE TABLE `medi_warehouses` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `total_qty` int(100) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medi_warehouses`
--

INSERT INTO `medi_warehouses` (`id`, `medicine_id`, `total_qty`, `created_at`, `updated_at`) VALUES
(1, 1, 220, '2023-02-22', '2023-02-22'),
(2, 2, 180, '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `pr_code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `blood_type` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `pr_code`, `name`, `phone`, `age`, `weight`, `height`, `gender`, `blood_type`, `created_at`, `updated_at`) VALUES
(1, 'pr-0001', 'Aye Myint Mo Theint', '0982324555', '21', '70 kg', '5 ft 4 in', 1, 'A+', '2023-02-02', '2023-02-02'),
(2, 'pr-0002', 'Maung Aung', '09450999875', '24', '90 kg', '5 ft 8 in', 2, 'O+', '2023-02-02', '2023-02-02'),
(3, 'pr-0003', 'Aye Maung', '09823231245', '31', '100 kg', '5 ft 8 in', 2, 'AB+', '2023-02-02', '2023-02-02'),
(4, 'pr-0004', 'Thiri', '+9599999999', '22', '90.7185 kg', '5 ft 5 in', 2, 'A+', '2023-02-03', '2023-02-03'),
(6, 'pr-0005', 'Thiri', '+95988888888', '23', '90.7185 kg', '5 ft 5 in', 1, 'A-', '2023-02-03', '2023-02-03'),
(7, 'pr-0006', 'Hello', '09450999874', '21', '100 kg', '5 ft 4 in', 1, 'B+', '2023-02-16', '2023-02-16'),
(8, 'pr-0007', 'Pepperoni', '09450999874', '21', '90 kg', '5 ft 8 in', 2, 'A-', '2023-02-16', '2023-02-16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `treatment_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, '5340', '2023-02-22', '2023-02-22'),
(2, 2, '5340', '2023-02-22', '2023-02-22'),
(3, 3, '8340', '2023-02-22', '2023-02-22'),
(4, 4, '5170', '2023-02-22', '2023-02-22'),
(5, 5, '5170', '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `treatment_id` int(11) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2023-01-24', '2023-01-24'),
(2, 'doctor', '2023-01-24', '2023-01-24'),
(3, 'reception', '2023-01-24', '2023-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shift_day` date DEFAULT NULL,
  `shift_start` time DEFAULT NULL,
  `shift_end` time DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `shift_day`, `shift_start`, `shift_end`, `created_at`, `updated_at`) VALUES
(1, 6, '2023-02-05', '14:30:00', '16:30:00', '2023-02-05', '2023-02-05'),
(3, 6, '2023-02-10', '09:00:00', '10:30:00', '2023-02-06', '2023-02-06'),
(5, 7, '2023-02-10', '10:30:00', '11:30:00', '2023-02-06', '2023-02-06'),
(7, 8, '2023-02-07', '10:30:00', '12:00:00', '2023-02-07', '2023-02-07'),
(10, 7, '2023-02-13', '09:30:00', '10:30:00', '2023-02-09', '2023-02-09'),
(11, 8, '2023-02-14', '09:00:00', '11:30:00', '2023-02-09', '2023-02-09'),
(12, 7, '2023-02-09', '19:35:00', '20:35:00', '2023-02-09', '2023-02-09'),
(13, 8, '2023-02-08', '13:30:00', '14:03:00', '2023-02-09', '2023-02-09'),
(14, 7, '2023-02-08', '09:30:00', '10:30:00', '2023-02-09', '2023-02-09'),
(15, 7, '2023-02-08', '14:30:00', '15:30:00', '2023-02-09', '2023-02-09'),
(16, 8, '2023-02-08', '16:30:00', '17:30:00', '2023-02-09', '2023-02-09'),
(17, 7, '2023-02-08', '18:00:00', '19:00:00', '2023-02-09', '2023-02-09'),
(18, 7, '2023-02-15', '13:30:00', '14:30:00', '2023-02-15', '2023-02-15'),
(19, 8, '2023-02-15', '14:30:00', '15:30:00', '2023-02-15', '2023-02-15'),
(22, 8, '2023-02-15', '17:52:00', '17:53:00', '2023-02-15', '2023-02-15'),
(23, 8, '2023-02-16', '09:30:00', '10:30:00', '2023-02-16', '2023-02-16'),
(26, 16, '2023-02-16', '09:00:00', '10:00:00', '2023-02-16', '2023-02-16'),
(27, 16, '2023-02-16', '16:30:00', '17:30:00', '2023-02-16', '2023-02-16'),
(28, 16, '2023-02-17', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(29, 17, '2023-02-17', '22:39:00', '23:30:00', '2023-02-17', '2023-02-17'),
(30, 16, '2023-02-07', '14:30:00', '15:30:00', '2023-02-17', '2023-02-17'),
(31, 16, '2023-02-14', '14:30:00', '15:30:00', '2023-02-17', '2023-02-17'),
(32, 16, '2023-02-21', '14:30:00', '15:30:00', '2023-02-17', '2023-02-17'),
(33, 17, '2023-02-15', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(34, 17, '2023-02-22', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(35, 16, '2023-03-01', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(36, 16, '2023-03-08', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(37, 16, '2023-03-15', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(38, 16, '2023-03-22', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(39, 16, '2023-03-29', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(40, 17, '2023-02-16', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(41, 17, '2023-02-23', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(42, 16, '2023-02-23', '09:00:00', '10:00:00', '2023-02-17', '2023-02-17'),
(43, 17, '2023-03-02', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(44, 17, '2023-03-09', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(45, 17, '2023-03-16', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(46, 17, '2023-03-23', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(47, 17, '2023-03-30', '14:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(48, 16, '2023-03-07', '15:00:00', '16:00:00', '2023-02-17', '2023-02-17'),
(51, 17, '2023-02-25', '13:00:00', '14:00:00', '2023-02-18', '2023-02-18'),
(52, 16, '2023-02-18', '14:30:00', '15:30:00', '2023-02-18', '2023-02-18'),
(53, 17, '2023-02-18', '14:30:00', '15:30:00', '2023-02-18', '2023-02-18'),
(54, 17, '2023-02-20', '10:30:00', '11:30:00', '2023-02-20', '2023-02-20'),
(55, 18, '2023-03-03', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(56, 18, '2023-03-10', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(57, 18, '2023-03-17', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(58, 18, '2023-03-24', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(59, 18, '2023-03-31', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(60, 18, '2023-02-21', '14:00:00', '16:00:00', '2023-02-21', '2023-02-21'),
(61, 16, '2023-02-22', '09:10:00', '10:30:00', '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `service_prices`
--

CREATE TABLE `service_prices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_price` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_prices`
--

INSERT INTO `service_prices` (`id`, `user_id`, `service_price`, `created_at`, `updated_at`) VALUES
(1, 16, '5000', '2023-02-16', '2023-02-16'),
(2, 18, '2000', '2023-02-21', '2023-02-21');

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` int(11) NOT NULL,
  `pr_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `treatment_date` date DEFAULT NULL,
  `duration` int(50) DEFAULT NULL,
  `note` longtext NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `pr_id`, `user_id`, `treatment_date`, `duration`, `note`, `created_at`, `updated_at`) VALUES
(1, 3, 16, '2023-02-22', 3, 'akjdfkasjdfkasjfkajsfkajdkf', '2023-02-22', '2023-02-22'),
(2, 4, 16, '2023-02-22', 3, 'asfasdfkjasodfjaojefoawejof', '2023-02-22', '2023-02-22'),
(3, 4, 16, '2023-02-22', 10, 'တစ်နေ့၂လုံးပုံမှန်သောက်ရန်', '2023-02-22', '2023-02-22'),
(4, 7, 16, '2023-02-22', 4, 'တစ်နေ့၁လုံး', '2023-02-22', '2023-02-22'),
(5, 7, 16, '2023-02-22', 2, 'asdfaksdjfkasdjfkasjdfkasjdfk', '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `treat_medi_lists`
--

CREATE TABLE `treat_medi_lists` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `medi_per_price` int(10) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `treat_medi_lists`
--

INSERT INTO `treat_medi_lists` (`id`, `medicine_id`, `treatment_id`, `qty`, `medi_per_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '20', 17, '2023-02-22', '2023-02-22'),
(2, 1, 2, '20', 17, '2023-02-22', '2023-02-22'),
(3, 1, 3, '20', 17, '2023-02-22', '2023-02-22'),
(4, 2, 3, '20', 150, '2023-02-22', '2023-02-22'),
(5, 1, 4, '10', 17, '2023-02-22', '2023-02-22'),
(6, 1, 5, '10', 17, '2023-02-22', '2023-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `acc_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `token` longtext DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `acc_name`, `password`, `role_id`, `token`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$/YzNNtZXLnZUkqsbjXh2regSonW31E2lIo1yswlNGFPrzzs2Vh6Oa', 1, '052d63d2de2673a14ea0d25310e40394', '2023-01-24', '2023-01-24'),
(3, 'myo2@gmail.com', '$2y$10$e/btID2mmWZyl9rX6dLXfukeGJ0P4oM7Oumg0GNd6.ipe2Iv6q/bu', 3, NULL, '2023-01-24', '2023-01-24'),
(11, 'reception@gmail.com', '$2y$10$N56lRsQcLvy8xulMWi6eIOCf6Fl5C8DtubHTYsRR3vD1doeJbLiw6', 3, '6021ca6fb0c57ae93d43f77ae7dc95af', '2023-02-16', '2023-02-16'),
(16, 'arkar@gmail.com', '$2y$10$K2Sh7L4MYXu7l7lmeuP93uRaL9FTEDgZSFtg6wMXhtzBITq0fbvzO', 2, '532d89b7b7c1ab7d7e3cfae64a1448bd', '2023-02-16', '2023-02-16'),
(17, 'maung2@gamil.com', '$2y$10$vdBJba2n1ogi9.VbqkRX7OBByLES13cvRcVkksBc9v4Vn4h8I75l6', 2, NULL, '2023-02-17', '2023-02-17'),
(18, 'yegyi@gmail.com', '$2y$10$GZBkER.rJQ0VqUXGrMFiL.lqnYTKqvpqWn.WIBsZv3Rpvo3Vo2.He', 2, 'bfa19456159869a35aaf235a0416cc5c', '2023-02-21', '2023-02-21');

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

CREATE TABLE `user_infos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_code` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `martial_status` varchar(255) DEFAULT NULL,
  `nrc` varchar(255) DEFAULT NULL,
  `gender` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `specialities` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_infos`
--

INSERT INTO `user_infos` (`id`, `user_id`, `name`, `user_code`, `age`, `education`, `martial_status`, `nrc`, `gender`, `img`, `phone`, `specialities`, `created_at`, `updated_at`) VALUES
(2, 3, 'Myo Myo', 'rp-0001', '22', 'Accounting', 'Single', '9/KhaAhSa(N)123456', 0, '', '', 0, '2023-01-24', '2023-01-24'),
(8, 10, 'Arkar', 'dr-0004', '2023-02-03', 'M.B.B.S', 'Single', '9/KhaMaSa(N)928123', 2, '1676443625Me.jpg', '0932482151', 4, '2023-02-15', '2023-02-15'),
(13, 16, 'Arkar', 'dr-0005', '2023-02-16', 'M.B.B.S', 'Single', '9/KhaMaSa(N)928123', 2, '1676526675Me.jpg', '+9599999999', 1, '2023-02-16', '2023-02-16'),
(14, 17, 'Maung Maung', 'dr-0006', '2023-02-17', 'M.B.B.S', 'Married', '9/KMS(N)9123415', 2, '1676605631GitHub-Mark.png', '09450999875', 2, '2023-02-17', '2023-02-17'),
(15, 18, 'Ye Gyi', 'dr-0007', '2023-02-21', 'M.B.B.S', '4', '9/KMS(N)9123415', 2, '1676968849Me.jpg', '09450999873', 1, '2023-02-21', '2023-02-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `medi_category`
--
ALTER TABLE `medi_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medi_stocks`
--
ALTER TABLE `medi_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medi_type`
--
ALTER TABLE `medi_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medi_warehouses`
--
ALTER TABLE `medi_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pr_id` (`pr_id`);

--
-- Indexes for table `treat_medi_lists`
--
ALTER TABLE `treat_medi_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medi_category`
--
ALTER TABLE `medi_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medi_stocks`
--
ALTER TABLE `medi_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medi_type`
--
ALTER TABLE `medi_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medi_warehouses`
--
ALTER TABLE `medi_warehouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `service_prices`
--
ALTER TABLE `service_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `treat_medi_lists`
--
ALTER TABLE `treat_medi_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_infos`
--
ALTER TABLE `user_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `medi_category` (`id`),
  ADD CONSTRAINT `medicines_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `medi_type` (`id`);

--
-- Constraints for table `treatments`
--
ALTER TABLE `treatments`
  ADD CONSTRAINT `treatments_ibfk_1` FOREIGN KEY (`pr_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
