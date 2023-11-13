-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 13, 2023 at 11:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `seller` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `date`, `amount`, `seller`) VALUES
(11, '2023-09-15', 16000, 'عمرو'),
(12, '2023-11-12', 15000, 'سعد');

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE `cats` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `adding_date` date NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `del` tinyint(1) NOT NULL DEFAULT 0,
  `del_id` int(11) DEFAULT NULL,
  `del_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`cat_id`, `cat_name`, `adding_date`, `user_id`, `del`, `del_id`, `del_date`) VALUES
(9, 'حلل', '2023-08-19', 1, 0, NULL, NULL),
(10, 'مقلاية', '2023-08-19', 1, 0, NULL, NULL),
(11, 'زجاج', '2023-08-31', 4, 0, 4, '0000-00-00'),
(12, 'اطقم سفرة', '2023-08-19', 1, 0, NULL, NULL),
(13, 'رفايع', '2023-08-30', 4, 0, NULL, NULL),
(14, 'صوانى مطبخ', '2023-09-01', 4, 0, 4, '0000-00-00'),
(15, 'أجهزة كهربائية', '2023-09-03', 4, 0, NULL, NULL),
(18, 'كاسات', '2023-09-09', 4, 0, NULL, NULL),
(19, 'صوانى تقديم', '2023-09-11', 4, 0, NULL, NULL),
(20, 'صوانى', '2023-09-14', 4, 0, NULL, NULL),
(21, 'صوانى جميلة', '2023-09-14', 4, 0, NULL, NULL),
(22, 'قلم', '2023-09-14', 4, 0, NULL, NULL),
(23, 'شعاع', '2023-09-14', 4, 0, NULL, NULL),
(24, 'طواجن', '2023-11-12', 4, 0, NULL, NULL),
(25, 'كتب', '2023-11-12', 4, 0, NULL, NULL),
(26, 'علب', '2023-11-12', 4, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `installments_items`
--

CREATE TABLE `installments_items` (
  `log_id` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `subcat_id` int(11) NOT NULL,
  `selling_date` date NOT NULL DEFAULT current_timestamp(),
  `selling_amount` int(11) NOT NULL,
  `unit_selling_price` float NOT NULL,
  `total_selling_price` float NOT NULL,
  `total_insta_price` float NOT NULL,
  `total_purchase_price` float NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_date` date DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `installments_items`
--

INSERT INTO `installments_items` (`log_id`, `item_code`, `cat_id`, `subcat_id`, `selling_date`, `selling_amount`, `unit_selling_price`, `total_selling_price`, `total_insta_price`, `total_purchase_price`, `paid`, `paid_date`, `user_id`, `seller_id`) VALUES
(29, 21, 11, 12, '2023-11-11', 2, 120, 240, 280, 220, 0, NULL, 4, 4),
(30, 24, 19, 18, '2023-11-11', 1, 450, 450, 500, 400, 0, NULL, 4, 4),
(31, 32, 13, 20, '2023-11-11', 1, 110, 110, 120, 100, 0, NULL, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `installments_money`
--

CREATE TABLE `installments_money` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `installments_money`
--

INSERT INTO `installments_money` (`id`, `user_id`, `amount`, `date`) VALUES
(50, 0, 20, '2023-11-10'),
(70, 0, 30, '2023-11-10'),
(88, 4, 100, '2023-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `installments_users`
--

CREATE TABLE `installments_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `adding_date` date NOT NULL DEFAULT current_timestamp(),
  `total` float NOT NULL DEFAULT 0,
  `done` float NOT NULL DEFAULT 0,
  `remain` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `installments_users`
--

INSERT INTO `installments_users` (`user_id`, `username`, `adding_date`, `total`, `done`, `remain`) VALUES
(2, 'عمرو', '2023-08-28', 0, 0, 0),
(3, 'سعيد', '2023-09-09', 0, 0, 0),
(4, 'أحمد', '2023-09-09', 900, 100, 800),
(5, 'عمر', '2023-09-11', 0, 0, 0),
(6, 'سعد', '2023-09-11', 0, 0, 0),
(7, 'فؤاد', '2023-09-11', 0, 0, 0),
(8, 'محمود', '2023-09-11', 0, 0, 0),
(11, 'محمد', '2023-09-11', 0, 0, 0),
(12, 'عباس', '2023-09-11', 0, 0, 0),
(13, 'بسام', '2023-09-11', 0, 0, 0),
(17, 'مصطفى', '2023-11-11', 0, 0, 0),
(18, 'باسم', '2023-11-11', 0, 0, 0),
(19, 'بشر', '2023-11-11', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_code` int(11) NOT NULL COMMENT 'the code identifies the same item with same id',
  `item_name` varchar(255) NOT NULL,
  `adding_date` date NOT NULL DEFAULT current_timestamp(),
  `purchase_price` float NOT NULL,
  `cat_id` int(11) NOT NULL,
  `subcat_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `least_amount` int(11) NOT NULL,
  `amount_sold` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `del` tinyint(1) NOT NULL DEFAULT 0,
  `del_id` int(11) NOT NULL,
  `del_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_code`, `item_name`, `adding_date`, `purchase_price`, `cat_id`, `subcat_id`, `amount`, `least_amount`, `amount_sold`, `user_id`, `del`, `del_id`, `del_date`) VALUES
(9, 6, 'بنجلاديشى 125 ق', '2023-09-11', 10000, 12, 24, 2, 1, 0, 4, 0, 0, NULL),
(10, 7, 'فلات', '2023-09-11', 14500, 12, 24, 1, 0, 0, 4, 0, 0, NULL),
(11, 8, 'أكسفورد', '2023-09-11', 7000, 12, 25, 1, 0, 0, 4, 0, 0, NULL),
(12, 9, 'لامايا 66 ق', '2023-09-11', 6300, 12, 25, 3, 1, 0, 4, 0, 0, NULL),
(13, 10, 'طيبة', '2023-09-11', 4300, 9, 4, 4, 1, 0, 4, 0, 0, NULL),
(14, 11, 'الامراء 21 ق', '2023-09-11', 3900, 9, 2, 2, 1, 0, 4, 0, 0, NULL),
(15, 12, 'سما الملكة', '2023-09-11', 8450, 9, 2, 2, 2, 1, 4, 0, 0, NULL),
(16, 13, 'سباتيولا صب', '2023-09-11', 20, 13, 15, 25, 5, 3, 4, 0, 0, NULL),
(17, 14, 'سباتيولا يد خشب', '2023-09-11', 18, 13, 15, 15, 4, 3, 4, 0, 0, NULL),
(18, 15, 'مريلة مطبخ', '2023-09-11', 30, 13, 20, 45, 5, 0, 4, 0, 0, NULL),
(19, 16, 'أبو حمدة مطعم ذهبى', '2023-09-11', 112, 13, 14, 6, 1, 0, 4, 0, 0, NULL),
(20, 17, 'أبو حمدة عادى', '2023-09-11', 75, 13, 14, 6, 2, 5, 4, 0, 0, NULL),
(21, 18, 'جرانيت', '2023-09-11', 25, 13, 19, 20, 5, 0, 4, 0, 0, NULL),
(22, 19, 'استانليس', '2023-09-11', 25, 13, 19, 15, 4, 0, 4, 0, 0, NULL),
(23, 20, 'طقم اكواب شاى', '2023-09-11', 75, 11, 12, 4, 1, 0, 4, 0, 0, NULL),
(24, 21, 'طقم فنجان شاى بالطبق', '2023-09-11', 110, 11, 12, 3, 1, 2, 4, 0, 0, NULL),
(25, 22, 'طقم أكواب شاى', '2023-09-11', 120, 11, 6, 5, 2, 0, 4, 0, 0, NULL),
(26, 23, 'سيلفر', '2023-09-11', 1050, 19, 17, 2, 2, 0, 4, 0, 0, NULL),
(27, 24, 'صوانى خشب', '2023-09-11', 400, 19, 18, 4, 1, 1, 4, 0, 0, NULL),
(28, 25, 'نوفال', '2023-09-11', 850, 14, 16, 3, 1, 0, 4, 0, 0, NULL),
(29, 26, 'السعد', '2023-09-11', 700, 14, 11, 4, 1, 0, 4, 0, 0, NULL),
(30, 27, 'نوفال', '2023-09-11', 560, 10, 5, 4, 1, 0, 4, 0, 0, NULL),
(31, 28, 'السعد', '2023-09-11', 560, 10, 7, 4, 1, 0, 4, 0, 0, NULL),
(32, 29, 'بوهيمى', '2023-09-11', 680, 18, 23, 4, 2, 0, 4, 0, 0, NULL),
(33, 30, 'قارورة ذهبى', '2023-09-11', 1850, 18, 22, 2, 1, 0, 4, 0, 0, NULL),
(34, 31, 'ار سى ار', '2023-09-11', 450, 18, 21, 10, 3, 0, 4, 0, 0, NULL),
(35, 12, 'سما الملكة', '2023-09-12', 9000, 9, 2, 6, 2, 0, 4, 0, 0, NULL),
(36, 32, 'حاجات', '2023-09-14', 100, 13, 20, 500, 10, 1, 4, 0, 0, NULL),
(37, 33, 'حلل ممغنطة', '2023-09-14', 2525, 9, 2, 5, 1, 0, 4, 0, 0, NULL),
(38, 17, 'أبو حمدة عادى', '2023-09-15', 40, 13, 14, 10, 2, 0, 4, 0, 0, NULL),
(39, 34, 'ماليزى', '2023-09-15', 6100, 12, 24, 3, 0, 0, 4, 0, 0, NULL),
(40, 35, 'صينى', '2023-09-15', 5000, 12, 24, 10, 2, 0, 4, 0, 0, NULL),
(41, 36, 'مقشة', '2023-09-26', 15, 13, 20, 5, 1, 0, 4, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `subcat_id` int(11) NOT NULL,
  `selling_date` date NOT NULL DEFAULT current_timestamp(),
  `selling_amount` int(11) NOT NULL,
  `unit_selling_price` float NOT NULL,
  `total_selling_price` float NOT NULL,
  `total_purchase_price` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `del` tinyint(1) NOT NULL DEFAULT 0,
  `del_id` int(11) NOT NULL,
  `del_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `item_code`, `cat_id`, `subcat_id`, `selling_date`, `selling_amount`, `unit_selling_price`, `total_selling_price`, `total_purchase_price`, `user_id`, `del`, `del_id`, `del_date`) VALUES
(68, 17, 13, 14, '2023-09-26', 5, 60, 300, 375, 4, 0, 0, NULL),
(70, 12, 9, 2, '2023-11-07', 1, 150, 150, 8450, 4, 0, 0, NULL),
(71, 13, 13, 15, '2023-11-08', 3, 25, 75, 60, 4, 0, 0, NULL),
(74, 14, 13, 15, '2023-11-08', 2, 30, 60, 36, 4, 0, 0, NULL),
(75, 14, 13, 15, '2023-11-08', 1, 25, 25, 18, 4, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `spendings`
--

CREATE TABLE `spendings` (
  `spendingID` int(11) NOT NULL,
  `spendingName` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `spendings`
--

INSERT INTO `spendings` (`spendingID`, `spendingName`, `date`, `amount`) VALUES
(16, 'كهرباء', '2023-09-15', 250),
(17, 'كهرباء', '2023-09-15', 20),
(23, 'مياه', '2023-09-15', 100),
(24, 'مياه', '2023-09-15', 50),
(25, 'كهرباء', '2023-09-15', 10.2),
(26, 'كهرباء', '2023-11-08', 150),
(27, 'مياه', '2023-11-08', 100),
(28, 'غاز', '2023-11-12', 100),
(29, 'مياه', '2023-11-12', 50),
(30, 'كهرباء', '2023-11-12', 120),
(31, 'طوارئ', '2023-11-12', 100);

-- --------------------------------------------------------

--
-- Table structure for table `subcats`
--

CREATE TABLE `subcats` (
  `subcat_id` int(11) NOT NULL,
  `subcat_name` varchar(255) NOT NULL,
  `adding_date` date NOT NULL DEFAULT current_timestamp(),
  `cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `del` tinyint(1) NOT NULL DEFAULT 0,
  `del_id` int(11) NOT NULL,
  `del_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `subcats`
--

INSERT INTO `subcats` (`subcat_id`, `subcat_name`, `adding_date`, `cat_id`, `user_id`, `del`, `del_id`, `del_date`) VALUES
(2, 'تيفال', '2023-08-21', 9, 1, 0, 1, '0000-00-00'),
(3, 'جرانيت', '2023-08-22', 9, 4, 0, 0, NULL),
(4, 'استانليس', '2023-08-22', 9, 4, 0, 0, NULL),
(5, 'تيفال', '2023-08-22', 10, 4, 0, 0, NULL),
(6, 'لومينارك', '2023-08-22', 11, 4, 0, 0, NULL),
(7, 'جرانيت', '2023-08-31', 10, 4, 0, 4, '0000-00-00'),
(10, 'كاتل', '2023-09-03', 15, 4, 0, 0, NULL),
(11, 'جرانيت', '2023-09-03', 14, 4, 0, 0, NULL),
(12, 'بلينك ماكس', '2023-09-03', 11, 4, 0, 0, NULL),
(14, 'معالق', '2023-09-09', 13, 4, 0, 0, NULL),
(15, 'سباتيولا', '2023-09-09', 13, 4, 0, 0, NULL),
(16, 'تيفال', '2023-09-11', 14, 4, 0, 0, NULL),
(17, 'استانليس', '2023-09-11', 19, 4, 0, 0, NULL),
(18, 'خشب', '2023-09-11', 19, 4, 0, 0, NULL),
(19, 'هراسة فول', '2023-09-11', 13, 4, 0, 0, NULL),
(20, 'رفايع', '2023-09-11', 13, 4, 0, 0, NULL),
(21, 'طقم كاسات', '2023-09-11', 18, 4, 0, 0, NULL),
(22, 'طقم شربات', '2023-09-11', 18, 4, 0, 0, NULL),
(23, 'شوب 12 قطعة', '2023-09-11', 18, 4, 0, 0, NULL),
(24, 'طقم صينى كامل', '2023-09-11', 12, 4, 0, 0, NULL),
(25, 'طقم صينى نص', '2023-09-11', 12, 4, 0, 0, NULL),
(33, 'هلال', '2023-09-14', 21, 4, 0, 0, NULL),
(34, 'عمان', '2023-09-14', 20, 4, 0, 0, NULL),
(35, 'عواد', '2023-09-14', 20, 4, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `access` int(11) NOT NULL DEFAULT 0,
  `adding_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `access`, `adding_date`) VALUES
(1, 'user1', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', 'user1', 1, '2023-08-19'),
(8, 'user2', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', 'user2', 0, '2023-08-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `installments_items`
--
ALTER TABLE `installments_items`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `installments_money`
--
ALTER TABLE `installments_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installments_users`
--
ALTER TABLE `installments_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `spendings`
--
ALTER TABLE `spendings`
  ADD PRIMARY KEY (`spendingID`);

--
-- Indexes for table `subcats`
--
ALTER TABLE `subcats`
  ADD PRIMARY KEY (`subcat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `installments_items`
--
ALTER TABLE `installments_items`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `installments_money`
--
ALTER TABLE `installments_money`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `installments_users`
--
ALTER TABLE `installments_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `spendings`
--
ALTER TABLE `spendings`
  MODIFY `spendingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `subcats`
--
ALTER TABLE `subcats`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
