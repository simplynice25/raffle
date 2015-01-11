-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2015 at 11:44 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `raffle`
--

-- --------------------------------------------------------

--
-- Table structure for table `raffle_consolations`
--

CREATE TABLE IF NOT EXISTS `raffle_consolations` (
`id` int(11) NOT NULL,
  `raffle_id` int(11) DEFAULT NULL,
  `prize_id` int(11) DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_encoded_receipts`
--

CREATE TABLE IF NOT EXISTS `raffle_encoded_receipts` (
`id` int(11) NOT NULL,
  `raffle_id` int(11) DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_prizes`
--

CREATE TABLE IF NOT EXISTS `raffle_prizes` (
`id` int(11) NOT NULL,
  `prize_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prize_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prize_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_profiles`
--

CREATE TABLE IF NOT EXISTS `raffle_profiles` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` longtext COLLATE utf8_unicode_ci,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_raffles`
--

CREATE TABLE IF NOT EXISTS `raffle_raffles` (
`id` int(11) NOT NULL,
  `raffle_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `winners` int(11) DEFAULT NULL,
  `consolations` int(11) DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `raffle_status` int(11) DEFAULT NULL,
  `raffle_description` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_receipt`
--

CREATE TABLE IF NOT EXISTS `raffle_receipt` (
`id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_users`
--

CREATE TABLE IF NOT EXISTS `raffle_users` (
`id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roles` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `raffle_winners`
--

CREATE TABLE IF NOT EXISTS `raffle_winners` (
`id` int(11) NOT NULL,
  `raffle_id` int(11) DEFAULT NULL,
  `prize_id` int(11) DEFAULT NULL,
  `view_status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `winner` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `raffle_consolations`
--
ALTER TABLE `raffle_consolations`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_66B5B0E3DBEC4B1F` (`raffle_id`), ADD KEY `IDX_66B5B0E3BBE43214` (`prize_id`);

--
-- Indexes for table `raffle_encoded_receipts`
--
ALTER TABLE `raffle_encoded_receipts`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_C4D38EEFDBEC4B1F` (`raffle_id`);

--
-- Indexes for table `raffle_prizes`
--
ALTER TABLE `raffle_prizes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raffle_profiles`
--
ALTER TABLE `raffle_profiles`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_2F5BD5DBA76ED395` (`user_id`);

--
-- Indexes for table `raffle_raffles`
--
ALTER TABLE `raffle_raffles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raffle_receipt`
--
ALTER TABLE `raffle_receipt`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_E90D3023A76ED395` (`user_id`);

--
-- Indexes for table `raffle_users`
--
ALTER TABLE `raffle_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raffle_winners`
--
ALTER TABLE `raffle_winners`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_462E920ADBEC4B1F` (`raffle_id`), ADD KEY `IDX_462E920ABBE43214` (`prize_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `raffle_consolations`
--
ALTER TABLE `raffle_consolations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `raffle_encoded_receipts`
--
ALTER TABLE `raffle_encoded_receipts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `raffle_prizes`
--
ALTER TABLE `raffle_prizes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `raffle_profiles`
--
ALTER TABLE `raffle_profiles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `raffle_raffles`
--
ALTER TABLE `raffle_raffles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `raffle_receipt`
--
ALTER TABLE `raffle_receipt`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `raffle_users`
--
ALTER TABLE `raffle_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `raffle_winners`
--
ALTER TABLE `raffle_winners`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `raffle_consolations`
--
ALTER TABLE `raffle_consolations`
ADD CONSTRAINT `FK_66B5B0E3BBE43214` FOREIGN KEY (`prize_id`) REFERENCES `raffle_prizes` (`id`),
ADD CONSTRAINT `FK_66B5B0E3DBEC4B1F` FOREIGN KEY (`raffle_id`) REFERENCES `raffle_raffles` (`id`);

--
-- Constraints for table `raffle_encoded_receipts`
--
ALTER TABLE `raffle_encoded_receipts`
ADD CONSTRAINT `FK_C4D38EEFDBEC4B1F` FOREIGN KEY (`raffle_id`) REFERENCES `raffle_raffles` (`id`);

--
-- Constraints for table `raffle_profiles`
--
ALTER TABLE `raffle_profiles`
ADD CONSTRAINT `FK_2F5BD5DBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `raffle_users` (`id`);

--
-- Constraints for table `raffle_receipt`
--
ALTER TABLE `raffle_receipt`
ADD CONSTRAINT `FK_E90D3023A76ED395` FOREIGN KEY (`user_id`) REFERENCES `raffle_users` (`id`);

--
-- Constraints for table `raffle_winners`
--
ALTER TABLE `raffle_winners`
ADD CONSTRAINT `FK_462E920ABBE43214` FOREIGN KEY (`prize_id`) REFERENCES `raffle_prizes` (`id`),
ADD CONSTRAINT `FK_462E920ADBEC4B1F` FOREIGN KEY (`raffle_id`) REFERENCES `raffle_raffles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
