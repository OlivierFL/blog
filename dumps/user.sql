-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Oct 04, 2020 at 12:29 PM
-- Server version: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(33, 'admin', 'Admin', 'Admin', 'admin@example.com', '$2y$10$tW7iQ1ik0hZt385j.C32neXaIHynXOObQGX.yJBzStu4/0esIJyEq', 'admin', '2020-10-04 12:27:52', '2020-10-04 12:28:16'),
(34, 'user', 'User', 'User', 'user@example.com', '$2y$10$iOIIoT5zT.YPgiWGUqUFBuces7ZkvNMAROmiXGZ9IR5u.eN7DR04.', 'user', '2020-10-04 12:28:53', '2020-10-04 12:28:53');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
