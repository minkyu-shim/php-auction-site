-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 09:46 AM
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
-- Database: `auction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `item_id`, `user_id`, `bid_amount`, `bid_time`) VALUES
(1, 2, 1, 1100.00, '2025-05-26 15:36:02'),
(2, 3, 1, 1600.00, '2025-05-26 15:43:44'),
(3, 2, 1, 1111.00, '2025-05-26 16:50:47'),
(4, 3, 3, 1650.00, '2025-05-27 09:14:02'),
(5, 4, 3, 2400.00, '2025-05-28 07:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `starting_price` decimal(10,2) NOT NULL,
  `current_price` decimal(10,2) NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `user_id`, `title`, `description`, `image_url`, `starting_price`, `current_price`, `end_time`, `created_at`) VALUES
(2, 1, 'Mona Lisa', 'Mona Lisa by Leonardo da Vinci', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg/1200px-Mona_Lisa%2C_by_Leonardo_da_Vinci%2C_from_C2RMF_retouched.jpg', 1000.00, 1111.00, '2025-12-31 23:59:00', '2025-05-26 12:16:01'),
(3, 1, 'Portrait of a Beauty', 'A work by Shin Yun-bok, a late Joseon Dynasty painter', 'https://upload.wikimedia.org/wikipedia/commons/6/69/Hyewon-Miindo.jpg', 1500.00, 1650.00, '2025-12-31 23:59:59', '2025-05-26 15:04:10'),
(4, 3, 'Woman with a parasol', 'A masterpiece created by Claude Monet (1886)', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Femme_%C3%A0_l%27ombrelle_tourn%C3%A9e_vers_la_gauche_-_Claude_Monnet_-_Mus%C3%A9e_d%27Orsay_RF_2621.jpg/960px-Femme_%C3%A0_l%27ombrelle_tourn%C3%A9e_vers_la_gauche_-_Claude_Monnet_-_Mus%C3%A9e_d%27Orsay_RF_2621.jpg', 2300.00, 2400.00, '2025-11-22 23:59:59', '2025-05-27 19:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@auction.com', 'password', '2025-05-26 12:16:58'),
(2, 'Minkie', 'minkyu.shim@epita.fr', '$2y$10$WDH132v6VldOdB6IRP4kWe0K9JU5ZV3mUxfSjzKgKWspI6Su9rifq', '2025-05-26 13:40:40'),
(3, 'tristan', 'user@epita.fr', '$2y$10$qT3u.FvnoO9q6FTj3a1OXui5z/rkRcFYwwEjjbKFRAKl7F3IMyv4O', '2025-05-27 11:11:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
