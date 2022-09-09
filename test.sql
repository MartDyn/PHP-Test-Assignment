-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 07, 2022 at 05:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `sectors`
--

CREATE TABLE `sectors` (
  `sector_id` int(10) UNSIGNED NOT NULL,
  `sector_name` char(255) NOT NULL,
  `parent_sector_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sectors`
--

INSERT INTO `sectors` (`sector_id`, `sector_name`, `parent_sector_id`) VALUES
(1, 'Manufacturing', 0),
(2, 'Construction materials', 1),
(3, 'Electronics and Optics', 1),
(4, 'Food and Beverage', 1),
(5, 'Bakery & confectionery products', 4),
(6, 'Beverages', 4),
(7, 'Fish & fish products', 4),
(8, 'Meat & meat products', 4),
(9, 'Milk & dairy products', 4),
(10, 'Other', 4),
(11, 'Sweets & snack food', 4),
(12, 'Furniture', 1),
(13, 'Bathroom/sauna', 12),
(14, 'Bedroom', 12),
(17, 'Children\'s room', 12),
(18, 'Kitchen', 12),
(19, 'Living room', 12),
(20, 'Office', 12),
(21, 'Other (Furniture)', 12),
(22, 'Outdoor', 12),
(23, 'Project furniture', 12),
(24, 'Machinery', 1),
(25, 'Machinery components', 24),
(26, 'Machinery equipment/tools', 24),
(27, 'Manufacture of machinery', 24),
(28, 'Maritime', 24),
(29, 'Aluminium and steel workboats', 28),
(30, 'Boat/Yacht building', 28),
(31, 'Ship repair and conversions', 28),
(32, 'Metal structures', 24),
(33, 'Other', 24),
(34, 'Repair and maintenance service', 24),
(35, 'Metalworking', 0),
(36, 'Construction of metal surfaces', 35),
(37, 'Houses and buildings', 35),
(38, 'Metal products', 35),
(39, 'Metal works', 35),
(40, 'CNC-machining', 39),
(41, 'Forgings, Fasteners', 39),
(42, 'Gas, Plasma, Laser cutting', 39),
(43, 'MIG, TIG, Aluminum welding', 39),
(44, 'Plastic and Rubber', 0),
(45, 'Packaging', 44),
(46, 'Plastic goods', 44),
(47, 'Plastic processing technology', 44),
(48, 'Blowing', 47),
(49, 'Moulding', 47),
(50, 'Plastics welding and processing', 47),
(51, 'Plastic profiles', 44),
(52, 'Printing', 0),
(53, 'Advertising', 52),
(54, 'Book/Periodicals printing', 52),
(55, 'Labelling and packaging printing', 52),
(56, 'Textile and clothing', 0),
(57, 'Clothing', 56),
(58, 'Textile', 56),
(59, 'Wood', 0),
(60, 'Other (Wood)', 59),
(61, 'Wooden building materials', 59),
(62, 'Wooden houses', 59),
(63, 'Other', 0),
(72, 'Creative industries', 63),
(73, 'Energy technology', 63),
(74, 'Environment', 63),
(75, 'Service', 0),
(76, 'Business services', 75),
(77, 'Engineering', 75),
(78, 'Information Technology and Telecommunications', 75),
(79, 'Data processing, Web portals, E-marketing', 78),
(80, 'Programming, Consultancy', 78),
(81, 'Software, Hardware', 78),
(82, 'Telecommunications', 78),
(83, 'Tourism', 75),
(84, 'Translation services', 75),
(85, 'Transport and Logistics', 75),
(86, 'Air', 85),
(87, 'Rail', 85),
(88, 'Road', 85),
(89, 'Water', 85);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_name` char(255) NOT NULL,
  `terms_agreed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_sector_data`
--

CREATE TABLE `user_sector_data` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `sector_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sectors`
--
ALTER TABLE `sectors`
  ADD PRIMARY KEY (`sector_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_sector_data`
--
ALTER TABLE `user_sector_data`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sector_id` (`sector_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sectors`
--
ALTER TABLE `sectors`
  MODIFY `sector_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_sector_data`
--
ALTER TABLE `user_sector_data`
  ADD CONSTRAINT `user_sector_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_sector_data_ibfk_2` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`sector_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
