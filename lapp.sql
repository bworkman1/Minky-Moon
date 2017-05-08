-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2017 at 06:23 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `change_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `group_name` varchar(30) NOT NULL,
  `sequence` int(11) NOT NULL,
  `group_title` varchar(50) NOT NULL,
  `can_delete` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `name`, `value`, `change_date`, `group_name`, `sequence`, `group_title`, `can_delete`) VALUES
(1, 'api_key', 'qCAsufwGNwqarbmXwtq8Fs4VhPvgt1mNJw1rSRdwprLhWdysM+lRSXouRsQ3Y9UcQEThBvjm2QPtDnu3GVZAOg==', '2017-05-06 17:23:59', '', 0, 'Authorize Settings', 0),
(2, 'auth_key', 'xbhMGs2i6tDfDcCBO77X5Do9c20A49tjvf1CQ+DjkJCPa/IKORrFfQeBQ9XOIWRtxN2eGway9R9hd/tZQE48vg==', '2017-05-06 17:23:59', '', 0, 'Authorize Settings', 0),
(3, 'failed', '8', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(4, 'time', '12', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(5, 'emails', '', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(6, 'google_api_key', 'AIzaSyACZ6JX6FlUdgwX-r0zVfPcOtoMQNq4ZPs', '2017-04-02 02:07:07', '    ', 0, 'Api Settings', 0),
(7, 'authorize_test_mode', 'y', '2017-05-06 17:23:59', '', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added_by` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `all_day` tinyint(1) NOT NULL DEFAULT '0',
  `link_to_form` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `name`, `description`, `added`, `updated`, `added_by`, `start`, `end`, `all_day`, `link_to_form`) VALUES
(11, 'Test', 'Test', '2017-05-08 05:16:07', '2017-05-08 05:16:07', 1, '2017-05-08 08:00:00', '2017-05-12 10:00:00', 0, 0),
(13, 'Meeting', 'meeting', '2017-05-08 05:49:02', '2017-05-08 05:49:02', 1, '2017-05-08 00:00:00', '2017-05-08 23:59:00', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_initial` varchar(1) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `customer_number` varchar(11) NOT NULL,
  `identifier` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_address`
--

CREATE TABLE `customer_address` (
  `id` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `additional_address` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` int(5) NOT NULL,
  `customer_number` varchar(11) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `current` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_phone`
--

CREATE TABLE `customer_phone` (
  `id` int(11) NOT NULL,
  `phone` int(11) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `current` tinyint(1) NOT NULL,
  `customer_number` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(30) NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cost` float NOT NULL,
  `min_cost` float NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `name`, `category`, `header`, `footer`, `added`, `updated`, `cost`, `min_cost`, `active`, `deleted`) VALUES
(2, 'Form 2 w 6 inputs', '', 's afa Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nunc dui, aliquet eu urna vel, elementum lacinia nibh. Vivamus mollis luctus quam, ac fermentum orci aliquet vitae. Quisque interdum dui sed nisl semper venenatis. Morbi a interdum velit, sit amet lacinia turpis. Duis gravida massa quis lectus convallis, at facilisis leo feugiat. Fusce interdum dui eros, malesuada lacinia turpis elementum et. Sed quis rhoncus urna. Pellentesque ac cursus mauris, in tempus est. Suspendisse pulvinar congue libero nec sodales. Aliquam placerat semper euismod. Ut venenatis vestibulum sapien. Sed placerat rutrum justo, quis aliquam elit facilisis convallis. Duis id ante ligula.', 'Nunc viverra ligula elementum, auctor dolor quis, scelerisque ligula. Sed blandit justo scelerisque velit efficitur mollis. Morbi mollis justo purus, ac fermentum sapien semper vitae. Sed quis erat egestas, sollicitudin felis sed, auctor ipsum. Praesent at velit at purus ornare euismod vulputate ac mi. <b>Duis mattis nec erat</b> nec viverra. Integer a neque risus.', '2017-03-18 15:44:08', '2017-05-06 22:11:31', 100, 50, 0, 0),
(3, 'Form 3 w 4 inputs', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nunc dui, aliquet eu urna vel, elementum lacinia nibh. Vivamus mollis luctus quam, ac fermentum orci aliquet vitae. Quisque interdum dui sed nisl semper venenatis. Morbi a interdum velit, sit amet lacinia turpis. Duis gravida massa quis lectus convallis, at facilisis leo feugiat. Fusce interdum dui eros, malesuada lacinia turpis elementum et. Sed quis rhoncus urna. Pellentesque ac cursus mauris, in tempus est. Suspendisse pulvinar congue libero nec sodales. Aliquam placerat semper euismod. Ut venenatis vestibulum sapien. Sed placerat rutrum justo, quis aliquam elit facilisis convallis. Duis id ante ligula.', 'Nunc viverra ligula elementum, auctor dolor quis, scelerisque ligula. Sed blandit justo scelerisque velit efficitur mollis. Morbi mollis justo purus, ac fermentum sapien semper vitae. Sed quis erat egestas, sollicitudin felis sed, auctor ipsum. Praesent at velit at purus ornare euismod vulputate ac mi. <b>Duis mattis nec erat</b> nec viverra. Integer a neque risus.', '2017-03-28 23:34:35', '2017-04-28 20:04:11', 0, 0, 0, 0),
(5, 'Form 5 with 3 inputs', '', 'Sed sed orci eros. Cras sed lacus eget dolor facilisis venenatis. Aliquam vestibulum ornare dui ac faucibus. Cras at pretium risus. Donec sed porta sem. Mauris bibendum, purus sit amet gravida convallis, velit dolor imperdiet tellus, a facilisis tellus felis ut mi. Proin pellentesque sapien magna, luctus hendrerit sapien placerat a. Etiam consectetur, erat non blandit auctor, magna nunc tristique tellus, quis elementum est eros ac orci. ', 'Sed sed orci eros. Cras sed lacus eget dolor facilisis venenatis. Aliquam vestibulum ornare dui ac faucibus. Cras at pretium risus. Donec sed porta sem. Mauris bibendum, purus sit amet gravida convallis, velit dolor imperdiet tellus, a facilisis tellus felis ut mi. Proin pellentesque sapien magna, luctus hendrerit sapien placerat a. Etiam consectetur, erat non blandit auctor, magna nunc tristique tellus, quis elementum est eros ac orci. ', '2017-04-01 19:05:40', '2017-04-12 02:46:05', 25, 25, 0, 1),
(6, 'Registration Form', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod dapibus efficitur. Nam pellentesque, sem sit amet interdum feugiat, turpis est pulvinar quam, sit amet porttitor ex turpis eget ante. Praesent est urna, accumsan in cursus ut, varius sed arcu. Integer id placerat lorem. Donec porta turpis vel magna vulputate consequat. Vestibulum a convallis lectus. Curabitur vitae massa consectetur, volutpat lectus vel, laoreet elit. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras vel turpis ante. Duis bibendum euismod quam vel fermentum. Donec ut condimentum metus. Curabitur turpis nunc, aliquet eu nisl in, faucibus pellentesque mauris. Aliquam vulputate neque ut elit mollis, sed ornare leo faucibus. Donec quis nisi hendrerit tortor dapibus placerat sed quis lectus. Nam aliquam vulputate purus ac fringilla.\n\n', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod dapibus efficitur. Nam pellentesque, sem sit amet interdum feugiat, turpis est pulvinar quam, sit amet porttitor ex turpis eget ante. Praesent est urna, accumsan in cursus ut, varius sed arcu. Integer id placerat lorem. Donec porta turpis vel magna vulputate consequat. Vestibulum a convallis lectus. Curabitur vitae massa consectetur, volutpat lectus vel, laoreet elit. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras vel turpis ante. Duis bibendum euismod quam vel fermentum. Donec ut condimentum metus. Curabitur turpis nunc, aliquet eu nisl in, faucibus pellentesque mauris. Aliquam vulputate neque ut elit mollis, sed ornare leo faucibus. Donec quis nisi hendrerit tortor dapibus placerat sed quis lectus. Nam aliquam vulputate purus ac fringilla.\n\n', '2017-05-07 20:56:40', '2017-05-08 00:56:40', 100, 40, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `form_data`
--

CREATE TABLE `form_data` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(11) NOT NULL,
  `added` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `value` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `form_id` int(11) NOT NULL,
  `submission_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `viewed` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_data`
--

INSERT INTO `form_data` (`id`, `customer_id`, `added`, `updated`, `value`, `name`, `form_id`, `submission_id`, `transaction_id`, `viewed`) VALUES
(15, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', 'car', 'transporation', 2, 1, '', 1),
(16, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', 'truck', 'transporation', 2, 1, '', 1),
(17, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', '147 Stanley St', 'address', 2, 1, '', 1),
(18, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', '545-64-6546', 'ssn', 2, 1, '', 1),
(19, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', 'Notes', 'notes', 2, 1, '', 1),
(20, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', 'test', 'total', 2, 1, '', 1),
(21, 'WO6546', '2017-04-02 23:25:37', '2017-04-07 06:13:20', 'Workman', 'last_name', 2, 1, '', 1),
(22, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'car', 'transporation', 2, 2, '60021188361', 1),
(23, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'boat', 'transporation', 2, 2, '60021188361', 1),
(24, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'truck', 'transporation', 2, 2, '60021188361', 1),
(25, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', '598 Main St', 'address', 2, 2, '60021188361', 1),
(26, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', '258-78-9844', 'ssn', 2, 2, '60021188361', 1),
(27, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'Notes goes here', 'notes', 2, 2, '60021188361', 1),
(28, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'test', 'total', 2, 2, '60021188361', 1),
(29, 'WA9844', '2017-04-02 23:32:18', '2017-04-11 04:47:33', 'Wayne', 'last_name', 2, 2, '60021188361', 1),
(30, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'car', 'transporation', 2, 3, '60021529851', 1),
(31, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'boat', 'transporation', 2, 3, '60021529851', 1),
(32, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'truck', 'transporation', 2, 3, '60021529851', 1),
(33, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', '57 Maple Ave', 'address', 2, 3, '60021529851', 1),
(34, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', '291-78-5161', 'ssn', 2, 3, '60021529851', 1),
(35, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'Notes goes here', 'notes', 2, 3, '60021529851', 1),
(36, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'Added', 'total', 2, 3, '60021529851', 1),
(37, 'GO5161', '2017-04-06 22:18:20', '2017-04-11 04:53:56', 'Goodman Sale', 'last_name', 2, 3, '60021529851', 1),
(38, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', 'car', 'transporation', 2, 4, '60021738233', 1),
(39, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', 'boat', 'transporation', 2, 4, '60021738233', 1),
(40, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', '461 American Way', 'address', 2, 4, '60021738233', 1),
(41, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', '556-67-7889', 'ssn', 2, 4, '60021738233', 1),
(42, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', 'Test Notes', 'notes', 2, 4, '60021738233', 1),
(43, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', 'test', 'total', 2, 4, '60021738233', 1),
(44, 'WO7889', '2017-04-10 22:54:30', '2017-04-11 04:54:14', 'Workman', 'last_name', 2, 4, '60021738233', 1),
(45, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', 'Brian', 'first_name', 3, 5, '60021779891', 1),
(46, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', 'Workman', 'last_name', 3, 5, '60021779891', 1),
(47, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', 'bike', 'transportation', 3, 5, '60021779891', 1),
(48, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', 'book', 'transportation', 3, 5, '60021779891', 1),
(49, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', '291-55-1111', 'ssn', 3, 5, '60021779891', 1),
(50, 'WH5555', '2017-04-11 06:02:21', '2017-04-11 06:08:08', 'Brian', 'first_name', 5, 6, '60021780713', 1),
(51, 'WH5555', '2017-04-11 06:02:21', '2017-04-11 06:08:08', 'M', 'middle_initial', 5, 6, '60021780713', 1),
(52, 'WH5555', '2017-04-11 06:02:21', '2017-04-11 06:08:08', '555-55-5555', 'ssn', 5, 6, '60021780713', 1),
(53, 'WH5555', '2017-04-11 06:02:21', '2017-04-11 06:08:08', 'What', 'last_name', 5, 6, '60021780713', 1),
(54, 'WO4884', '2017-04-11 06:07:51', '2017-04-11 06:07:59', 'Brian', 'first_name', 3, 7, '0', 1),
(55, 'WO4884', '2017-04-11 06:07:51', '2017-04-11 06:07:59', 'Workman', 'last_name', 3, 7, '0', 1),
(56, 'WO4884', '2017-04-11 06:07:51', '2017-04-11 06:07:59', 'bike', 'transportation', 3, 7, '0', 1),
(57, 'WO4884', '2017-04-11 06:07:51', '2017-04-11 06:07:59', 'book', 'transportation', 3, 7, '0', 1),
(58, 'WO4884', '2017-04-11 06:07:51', '2017-04-11 06:07:59', '564-65-4884', 'ssn', 3, 7, '0', 1),
(59, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'truck', 'transporation', 2, 8, '60023358726', 1),
(60, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'test', 'transporation', 2, 8, '60023358726', 1),
(61, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', '147 Stanley St', 'address', 2, 8, '60023358726', 1),
(62, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', '291-58-4161', 'ssn', 2, 8, '60023358726', 1),
(63, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'Notes should be made', 'notes', 2, 8, '60023358726', 1),
(64, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'Added', 'total', 2, 8, '60023358726', 1),
(65, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'Workman', 'last_name', 2, 8, '60023358726', 1),
(66, 'WO4161', '2017-05-07 21:45:21', '2017-05-08 04:21:16', 'Test', 'make', 2, 8, '60023358726', 1),
(67, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'truck', 'transporation', 2, 9, '60023358731', 0),
(68, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'test', 'transporation', 2, 9, '60023358731', 0),
(69, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', '147 Stanley St', 'address', 2, 9, '60023358731', 0),
(70, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', '291-58-4161', 'ssn', 2, 9, '60023358731', 0),
(71, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'Notes should be made', 'notes', 2, 9, '60023358731', 0),
(72, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'Added', 'total', 2, 9, '60023358731', 0),
(73, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'Workman', 'last_name', 2, 9, '60023358731', 0),
(74, 'WO4161', '2017-05-07 21:45:43', '2017-05-07 21:45:43', 'Test', 'make', 2, 9, '60023358731', 0),
(75, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'truck', 'transporation', 2, 10, '60023358746', 1),
(76, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'test', 'transporation', 2, 10, '60023358746', 1),
(77, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', '147 Stanley St', 'address', 2, 10, '60023358746', 1),
(78, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', '291-58-4161', 'ssn', 2, 10, '60023358746', 1),
(79, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'Notes should be made', 'notes', 2, 10, '60023358746', 1),
(80, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'Added', 'total', 2, 10, '60023358746', 1),
(81, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'Workman', 'last_name', 2, 10, '60023358746', 1),
(82, 'WO4161', '2017-05-07 21:47:00', '2017-05-07 23:53:36', 'Test', 'make', 2, 10, '60023358746', 1),
(83, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'truck', 'transporation', 2, 11, '60023358805', 0),
(84, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'boat', 'transporation', 2, 11, '60023358805', 0),
(85, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', '147 Stanley St', 'address', 2, 11, '60023358805', 0),
(86, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', '297-85-6545', 'ssn', 2, 11, '60023358805', 0),
(87, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'Notes goes here', 'notes', 2, 11, '60023358805', 0),
(88, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'Added', 'total', 2, 11, '60023358805', 0),
(89, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'Smith', 'last_name', 2, 11, '60023358805', 0),
(90, 'SM6545', '2017-05-07 21:52:05', '2017-05-07 21:52:05', 'Makeer', 'make', 2, 11, '60023358805', 0),
(91, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'truck', 'transporation', 2, 12, '60023358813', 1),
(92, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'boat', 'transporation', 2, 12, '60023358813', 1),
(93, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'test', 'transporation', 2, 12, '60023358813', 1),
(94, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'car', 'transporation', 2, 12, '60023358813', 1),
(95, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', '147 Stanley St', 'address', 2, 12, '60023358813', 1),
(96, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', '546-47-8845', 'ssn', 2, 12, '60023358813', 1),
(97, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'Notes goes here', 'notes', 2, 12, '60023358813', 1),
(98, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'test', 'total', 2, 12, '60023358813', 1),
(99, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', 'Jones', 'last_name', 2, 12, '60023358813', 1),
(100, 'JO8845', '2017-05-07 21:53:11', '2017-05-07 22:20:31', '94 are', 'make', 2, 12, '60023358813', 1),
(101, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', 'truck', 'transporation', 2, 13, '60023358832', 1),
(102, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', 'boat', 'transporation', 2, 13, '60023358832', 1),
(103, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', '548 Main St', 'address', 2, 13, '60023358832', 1),
(104, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', '493-49-3493', 'ssn', 2, 13, '60023358832', 1),
(105, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', 'Notes goes heree', 'notes', 2, 13, '60023358832', 1),
(106, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', 'test', 'total', 2, 13, '60023358832', 1),
(107, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', 'Jade', 'last_name', 2, 13, '60023358832', 1),
(108, 'JA3493', '2017-05-07 21:54:35', '2017-05-07 21:54:35', '49 Maker', 'make', 2, 13, '60023358832', 1),
(109, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', 'Brian', 'first_name', 6, 14, '60023360170', 1),
(110, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', 'Workman', 'last_name', 6, 14, '60023360170', 1),
(111, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', '95 Main St', 'address', 6, 14, '60023360170', 1),
(112, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', 'Utica', 'city', 6, 14, '60023360170', 1),
(113, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', 'OH', 'state', 6, 14, '60023360170', 1),
(114, 'WO4855', '2017-05-08 01:07:45', '2017-05-08 01:07:45', '548-48-4855', 'social_security_number', 6, 14, '60023360170', 1),
(115, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', 'John', 'first_name', 6, 15, '60023362375', 1),
(116, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', 'Smith', 'last_name', 6, 15, '60023362375', 1),
(117, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', '948 Smithers Lane', 'address', 6, 15, '60023362375', 1),
(118, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', 'Newark', 'city', 6, 15, '60023362375', 1),
(119, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', 'NJ', 'state', 6, 15, '60023362375', 1),
(120, 'SM8191', '2017-05-08 03:46:51', '2017-05-08 03:46:51', '198-87-8191', 'social_security_number', 6, 15, '60023362375', 1),
(121, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', 'Brian', 'first_name', 6, 16, '60023362608', 1),
(122, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', 'Workman', 'last_name', 6, 16, '60023362608', 1),
(123, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', '147 Stanley St', 'address', 6, 16, '60023362608', 1),
(124, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', 'Newark', 'city', 6, 16, '60023362608', 1),
(125, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', 'OH', 'state', 6, 16, '60023362608', 1),
(126, 'WO5888', '2017-05-08 03:55:39', '2017-05-08 03:55:39', '111-55-5888', 'social_security_number', 6, 16, '60023362608', 1),
(127, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', 'Joe', 'first_name', 6, 17, '60023362802', 1),
(128, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', 'Dirt', 'last_name', 6, 17, '60023362802', 1),
(129, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', '49 Main St', 'address', 6, 17, '60023362802', 1),
(130, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', 'Tooele', 'city', 6, 17, '60023362802', 1),
(131, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', 'UT', 'state', 6, 17, '60023362802', 1),
(132, 'DI9854', '2017-05-08 04:03:02', '2017-05-08 04:03:02', '798-78-9854', 'social_security_number', 6, 17, '60023362802', 1),
(133, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', 'truck', 'transporation', 2, 18, '60023363705', 1),
(134, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', 'boat', 'transporation', 2, 18, '60023363705', 1),
(135, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', '548 Main St', 'address', 2, 18, '60023363705', 1),
(136, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', '493-92-4439', 'ssn', 2, 18, '60023363705', 1),
(137, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', 'Notes', 'notes', 2, 18, '60023363705', 1),
(138, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', 'Added', 'total', 2, 18, '60023363705', 1),
(139, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', 'Smith', 'last_name', 2, 18, '60023363705', 1),
(140, 'SM4439', '2017-05-08 04:32:15', '2017-05-08 04:32:15', '456464564', 'make', 2, 18, '60023363705', 1);

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE `form_inputs` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `input_name` varchar(50) NOT NULL,
  `input_type` varchar(20) NOT NULL,
  `sequence` int(11) NOT NULL,
  `custom_class` varchar(50) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `input_label` varchar(100) NOT NULL,
  `input_validation` varchar(255) NOT NULL,
  `input_inline` tinyint(1) NOT NULL DEFAULT '0',
  `input_columns` varchar(20) NOT NULL,
  `encrypt_data` tinyint(1) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_inputs`
--

INSERT INTO `form_inputs` (`id`, `form_id`, `input_name`, `input_type`, `sequence`, `custom_class`, `added`, `input_label`, `input_validation`, `input_inline`, `input_columns`, `encrypt_data`, `updated`) VALUES
(20, 2, 'ssn', 'text', 3, 'ssn', '2017-03-16 02:27:20', 'SSN', '', 0, 'col-md-5', 0, '2017-03-29 02:49:23'),
(4, 2, 'address', 'text', 2, 'address', '2017-03-12 16:00:39', 'Address', 'required', 0, 'col-md-9', 0, '2017-04-02 02:25:46'),
(16, 2, 'transporation', 'checkbox', 1, '', '2017-03-16 00:15:33', 'Transporation', 'required|min_length[1]', 0, 'col-md-9', 0, '2017-03-30 04:01:21'),
(19, 2, 'last_name', 'textarea', 6, '', '2017-03-16 01:24:18', 'Last Name', '', 0, 'col-md-4', 0, '2017-03-29 02:50:40'),
(21, 2, 'notes', 'textarea', 4, '', '2017-03-18 12:51:50', 'Notes', '', 0, 'col-md-6', 0, '2017-03-29 02:49:23'),
(22, 2, 'total', 'select', 5, '', '2017-03-18 12:52:52', 'Total', '', 0, 'col-md-6', 0, '2017-03-29 02:50:40'),
(23, 3, 'transportation', 'checkbox', 3, '', '2017-03-19 14:21:32', 'Transportation', '', 0, 'col-md-2', 1, '2017-04-11 05:06:20'),
(24, 3, 'first_name', 'text', 1, '', '2017-03-19 17:19:40', 'First Name', 'required|min_length[3]', 0, 'col-md-4', 1, '2017-03-29 03:34:35'),
(25, 5, 'first_name', 'text', 1, '', '2017-04-01 18:06:15', 'First name', 'required|alpha|max_length[30]', 0, 'col-md-4', 0, '2017-04-11 06:01:18'),
(26, 5, 'last_name', 'text', 4, '', '2017-04-01 18:06:46', 'Last Name', '', 0, 'col-md-4', 0, '2017-04-11 06:01:13'),
(27, 5, 'middle_initial', 'text', 2, '', '2017-04-01 18:52:34', 'Middle Initial', 'alpha|max_length[1]', 0, 'col-md-3', 0, '2017-04-11 06:01:18'),
(28, 6, 'first_name', 'text', 1, '', '2017-04-01 19:08:30', 'First Name', 'required|alpha', 0, 'col-md-4', 0, '2017-05-08 00:56:41'),
(29, 3, 'ssn', 'text', 4, 'ssn', '2017-04-11 01:05:43', 'SSN', '', 0, 'col-md-3', 1, '2017-04-11 05:06:13'),
(30, 3, 'last_name', 'text', 2, '', '2017-04-11 01:06:13', 'Last Name', 'required', 0, 'col-md-3', 0, '2017-04-11 05:06:20'),
(31, 5, 'ssn', 'text', 3, 'ssn', '2017-04-11 02:01:13', 'SSN', 'required', 0, 'col-md-3', 1, '2017-04-11 06:01:18'),
(32, 2, 'make', 'text', 7, '', '2017-05-07 17:42:19', 'Make', '', 0, 'col-md-3', 0, '2017-05-07 21:42:19'),
(33, 6, 'last_name', 'text', 2, '', '2017-05-07 19:56:10', 'Last Name', 'required|alpha|max_length[35]', 0, 'col-md-4', 0, '2017-05-08 00:56:41'),
(34, 6, 'address', 'text', 3, '', '2017-05-07 20:01:52', 'Address', 'required|alpha_numeric_spaces|max_length[40]', 0, 'col-md-5', 0, '2017-05-08 00:56:41'),
(35, 6, 'city', 'text', 4, '', '2017-05-07 20:05:25', 'City', 'required|alpha_numeric_spaces', 0, 'col-md-3', 0, '2017-05-08 00:56:41'),
(38, 6, 'state', 'select', 5, 'input_states', '2017-05-07 20:23:47', 'State', '', 0, 'col-md-4', 0, '2017-05-08 00:56:41'),
(39, 6, 'social_security_number', 'text', 6, 'ssn', '2017-05-07 20:56:34', 'Social Security Number', '', 0, 'col-md-3', 1, '2017-05-08 00:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `form_input_options`
--

CREATE TABLE `form_input_options` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `form_id` int(11) NOT NULL,
  `input_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_input_options`
--

INSERT INTO `form_input_options` (`id`, `name`, `value`, `form_id`, `input_id`) VALUES
(160, 'Test', 'test', 2, 22),
(347, 'Select One', 'Select One', 6, 38),
(344, 'Test', 'test', 2, 16),
(343, 'Truck', 'truck', 2, 16),
(342, 'Car', 'car', 2, 16),
(341, 'Boat', 'boat', 2, 16),
(325, 'Motorcycle', 'bike', 3, 23),
(324, 'Car', 'car', 3, 23),
(323, 'Book', 'book', 3, 23),
(322, 'Test', 'test', 3, 23),
(321, 'Truck', 'truck', 3, 23),
(161, 'Added', 'Added', 2, 22);

-- --------------------------------------------------------

--
-- Table structure for table `form_input_rules`
--

CREATE TABLE `form_input_rules` (
  `id` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `label` varchar(100) NOT NULL,
  `parameter` tinyint(1) NOT NULL,
  `param_type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_input_rules`
--

INSERT INTO `form_input_rules` (`id`, `type`, `label`, `parameter`, `param_type`, `description`) VALUES
(1, 'required', 'Input Required', 0, '', 'Returns FALSE if the form element is empty.'),
(2, 'matches', 'Matches Another Input', 1, 'text', 'Returns FALSE if the form element does not match the one in the parameter. '),
(3, 'regex_match', 'Regex Match', 1, 'text', 'Returns FALSE if the form element does not match the regular expression.'),
(5, 'differs', 'Is input different then another input', 1, 'text', 'Returns FALSE if the form element does not differ from the one in the parameter.'),
(6, 'is_unique', 'Is a unique value in a database column', 1, 'text', 'Returns FALSE if the form element is not unique to the table and field name in the parameter. '),
(7, 'min_length', 'Min length of input', 1, 'number', 'Returns FALSE if the form element is shorter than the parameter value.	'),
(8, 'max_length', 'Max length of input', 1, 'number', 'Returns FALSE if the form element is longer than the parameter value.	'),
(9, 'exact_length', 'Input must be exactly this long', 1, 'number', 'Returns FALSE if the form element is not exactly the parameter value.	'),
(10, 'greater_than', 'Input must be greater then', 1, 'number', 'Returns FALSE if the form element is less than or equal to the parameter value or not numeric.	'),
(11, 'greater_than_equal_to', 'Input is greater than or equal to', 1, 'number', 'Returns FALSE if the form element is less than the parameter value, or not numeric.	'),
(12, 'less_than', 'Input must be less than', 1, 'number', 'Returns FALSE if the form element is greater than or equal to the parameter value or not numeric.	'),
(13, 'less_than_equal_to', 'Input is less than or equal to', 1, '', 'Returns FALSE if the form element is greater than the parameter value, or not numeric.	'),
(14, 'in_list', 'Input is part of the following list', 1, '', 'Returns FALSE if the form element is not within a predetermined list.	'),
(15, 'alpha', 'Is input all alpha characters. (No Spaces)', 0, '', 'Returns FALSE if the form element contains anything other than alphabetical characters.	'),
(16, 'alpha_numeric', 'Input is only letters and numbers', 0, '', 'Returns FALSE if the form element contains anything other than alpha-numeric characters.	'),
(17, 'alpha_numeric_spaces', 'Input is all alphanumeric and spaces', 0, '', 'Returns FALSE if the form element contains anything other than alpha-numeric characters or spaces. Should be used after trim to avoid spaces at the beginning or end.	'),
(18, 'alpha_dash', 'Input has only alpha characters and dashes', 0, '', 'Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.	'),
(19, 'numeric', 'Input is all numeric', 0, '', 'Returns FALSE if the form element contains anything other than numeric characters.	'),
(20, 'integer', 'Input must be an integer only', 0, '', 'Returns FALSE if the form element contains anything other than an integer.	'),
(21, 'decimal', 'Input must be a decimal', 0, '', 'Returns FALSE if the form element contains anything other than a decimal number.	'),
(22, 'is_natural', 'Input is a natural Number', 0, '', 'Returns FALSE if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.	'),
(23, 'is_natural_no_zero', 'Input is only numbers above 0', 0, '', 'Returns FALSE if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.	'),
(24, 'valid_url', 'Input is a valid url', 0, '', 'Returns FALSE if the form element does not contain a valid URL.	'),
(25, 'valid_email', 'Input is a valid email address', 0, '', 'Returns FALSE if the form element does not contain a valid email address.	'),
(26, 'valid_emails', 'Input is comma separated valid emails', 0, '', 'Returns FALSE if any value provided in a comma separated list is not a valid email.	'),
(27, 'valid_ip', 'Input is a valid ip address', 0, '', 'Returns FALSE if the supplied IP address is not valid. Accepts an optional parameter of ‘ipv4’ or ‘ipv6’ to specify an IP format.	'),
(28, 'valid_base64', 'Input is valid base 64 input', 0, '', 'Returns FALSE if the supplied string contains anything other than valid Base64 characters.	');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(35) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Highest level access and cannot be deleted. This access should not be given to just anyone.'),
(4, 'View Forms', 'User can view forms that appear on the website.'),
(3, 'Edit Forms', 'User can make changes to forms that appear on the website.'),
(5, 'Add New Forms', 'User can add new forms to the website.'),
(6, 'Add Users', 'User can add new users'),
(7, 'Edit Users', 'User can edit users account details'),
(9, 'View Submitted Forms', 'User can view submitted forms from users on the website.'),
(10, 'Submit Forms Manually', 'User can submit forms on a clients behalf.'),
(11, 'Submit Payments', 'User is allowed to submit payments.'),
(12, 'View Submitted Payments', 'User is allowed to view submitted payments.');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `login` varchar(100) DEFAULT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` float NOT NULL,
  `form_id` int(11) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `form_cost` float NOT NULL,
  `billing_name` varchar(255) NOT NULL,
  `billing_address` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_zip` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `approval_code` varchar(255) NOT NULL,
  `submission_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date`, `amount`, `form_id`, `customer_id`, `form_cost`, `billing_name`, `billing_address`, `billing_city`, `billing_state`, `billing_zip`, `transaction_id`, `approval_code`, `submission_id`) VALUES
(1, '2017-04-02 19:11:19', 50, 2, 'WO5161', 100, 'FWV5aXz4CpHNN2ROK0Vzt88LWKC7Qjuw/pgtxuzD+8BkKo6II1vLH7FRo2Lj/8/5EzzqJgwZHK0cICsLCGFaog==', 'a3rov/VB/vsqdJ2NLDauijRxWMdF277rbCT11P4Ex2NyX1mEWTKteOHHL3NzD0p01ElIT/Dq13zPH4Zc89QN0w==', 'oe44dk/p0sZpELQrDobKvHo0OE6u1CGGuhGdQ++lc2ZcnEKOxmAk38Bsz2vQ', 't8NojYnyhvCGDKwq0DfG', 'rPd4A6JbGXyEaoSJckii', '60021185050', 'S05XJ5', 0),
(2, '2017-04-02 20:04:43', 100, 2, 'WO5161', 100, 'fTCc8M5N9QiKyuT78G11gFq+6wrqFo3i0MKOhX4a1nR3cTJlBfQgkOxYJNXrXqIDsa0hOPMnHwI0ViMFDOxsVw==', 'KZnKojC4uB1f+fs1+SCgnVa26sybESAI9IJ1z9dd0aGUL29p0qVyp69wGw9oUespJ7aaFdxrOHuncOt00eXLEw==', 'NuBaNPAE2hOUCXJPYbQqm7SOSW6s7RINeiG7+JCCLK8q8aPL3i2Ju5VGZQbC', 'owhIphAvXOko+UhJKigV', 'cBCX95I8P58dyX8hU3xf', '60021185322', 'WBIH22', 0),
(3, '2017-04-02 20:06:12', 75.99, 2, 'WO5161', 100, 'nPdGKXMngzVyeRbb3K8L1ApUpZMewSteR0HPtvksfRhVWPnb3QRkv+vgF0auQEXvnwo27neRL0nBuFmgpJ80IQ==', 'KL9VO7PzJaomCAx2xcMVUuathLWvHJ7kTGGWXzPQV6ZJ8e29qfYU2mJ4EpcmluPWWZ1+tzk9kXTB8gFrzRmz5A==', 'FWfDLFSfKAA8eGPvDbeBsCPkw7egWHFKsWG96ctUkhM4iDxQUCR+cyvmRg6B', 'Y++S/lUqizawyBpJt4tL', 'D1fAsLWXTUyF9zGDvz92', '60021185341', 'C7ZB7S', 0),
(4, '2017-04-02 22:38:06', 100, 2, 'WO5645', 100, 'go1n2pwjjWdiJjtI9LhdlYzDFiIr/o2W4xfUzwXHtq50Kqpp6UJ3M47XBzpcPQEMx7P6CzdM9MdU3PZBWMJh9g==', 'OIE3EXZN4bEfQ9NnVYn5R1EIdigVd3GxsYX8vnfo4jEbre5A/IV1WDZPfCX57O487adhJ1uftK8phmgF7eF61g==', 'ztbnqDDVFFy9wErlmLp1WvettpDfTzQrXlIEDchp7RPmfgxw7X2pplaeo/8Z', 'RIt3n8m+KGc7naPEuUvQ', 'maDm9UQGHNGZ2doCuzwM', '60021187034', '1JIDM2', 0),
(5, '2017-04-02 22:42:49', 100, 2, 'WO5645', 100, 'zxQ2rJ2v8JzWdvKhnYwo0ijbtQN8GsDcqPdcOf0rUfw5AEDW+sw/xshk4UAf/tEK3TxdpzT4FauWT5eFYBJpPQ==', '5Olmw9e4pMf0c+qgXj0jZk3/derGcwPW1UXjCM8r3V9lHK6VcLoK+e+BdjWyBXyhyZv8qqQURXfOSJyTHTnbdg==', '2h12OvAgN1Lyz8L/AE7/V1OFFDHvKycx4/FLNoGNSSEqBR2OzVIM/NIv8RwJ', 'sLJmEOrpqz3QjHq+gr67', 'Zqc1SMQS/4eOqRHc449W', '60021187218', 'V5FUOD', 0),
(6, '2017-04-02 22:44:22', 100, 2, 'WO5645', 100, '+/zl3n6bMjIrJYlbJcDTcKVSNEatltOCTDjCA9buzKqy28PY8Yof/YmsPfiRS0cbJKVuhxbO0qs91pWOvTw2yQ==', 'crF11BeERj2pAuE6R1Eu4ypXVAxXz3dc2mSqsgjPt+x5I5VPNZRZqm7JIR+nlp83o92DqSokzTuH+tan/Im/sQ==', 'x8XkK5HNOqcGQBh5SEJrtpG8VDPgaPqXR/FywBkQg4/f05n+c2Zv7BvDWxTF', '/Ts04ut2DnJF3DAXKpKG', 'Eg9k+SR+wp1j2SgW7EOC', '60021187274', 'O7QIQ9', 0),
(7, '2017-04-02 23:02:40', 100, 2, 'TE5161', 100, 'CER0cD/nVSdgNf91jlRdbgEqk+e+csWH0dcILI8SJrdex8nJTMqpzoZ1cvuPzWnyJYAEZ37cbYQLscLyOYm4ag==', '3thkRzmwyRI+8rc0EMQZwecPEo7Hi8lBv+RRuyDTctq8+9y9gPTAm85qjqBQXzYsx9z3tCRi7glHnzU/SuNLQQ==', 'lM0zfRLYHV37D09TcpW1dK1TcpWxA61cjVF4qpP0nl3tRxiPAiNdd+PHHCyM', 'KSLjE8xhUAeZjMfRs8Yw', 'n9dzCmZKZRIXaR6w1VeN', '60021187790', '0F09X2', 0),
(8, '2017-04-02 23:22:08', 100, 2, 'WO5555', 100, 'K/fl5V7nvCeEddQgIaR7x889MtDu2YHC7wXbW4RZaV1ByFHrZ+M/l1liDuWP8NOiQ8hMrSFEZlRlVVAmQZYlTQ==', 'jyxbNRRBZfpPsnE4wYbL4wGD97B1Q3omilTIA/NLCQhO9rvyB0plC7EG1DObmqP/g05sydgTm9h25Hhod8mzsg==', 'UkMuZSp7bq560G4wv0h835SpG3BcjdNqhYIUi8IdCpR31TURrE9NhAfLAe/D', 'dDlkdaKV1kMEzssHHumN', '9w74ZXmOn7bvrIi/3Wv9', '60021188199', '357IKL', 0),
(9, '2017-04-02 23:25:37', 100, 2, 'WO6546', 100, 'mdrH+U5THvlEZSRNPFRkKREpoYVkvqEl5N4XCIRjby8EY4KE5zL6fy7tTQ1DsgUuujtXNxqqEXyOJfyRbZBrbg==', 'kj5iRzv6jerlECeiUcO6o1kdC+Og9d8m1Brqb+ljlrkQ3bzLR0nx8Vftp8JPg65+NOC0OpBUth2B6yy/EUlX0Q==', '64JddYiCW7zlnIrYxhNy/QLx1iA9DX8IJTYdtK5CHCMvuvEvkmzvWKhxBaYo', 'pKW3gjXpi25HB0ztm0OI', 'vKlycEExGQAHVHDj0FP+', '60021188240', 'K5X05X', 0),
(10, '2017-04-02 23:32:18', 75, 2, 'WA9844', 100, '9bxT9YSjL9vyeqj6ISNpKAlJVfxvZVLaHuIzYC04hTIz3HfhsTkOBNlKdOsM6Ozuw1vCLVSBofjYivgCvH2VQg==', 'VcM8bmi+/EgaaBkaTaY+vvkgT8XiTy+X1qG20emMaxax2ifS8fjC3VVGs+KgCN9v1pcA7NThU7zoAjJwxvIYEA==', 'lSkFRys5qxQht2ycWIn0sslXJ+01mu2zbr8XooU+MFuXbxdrgAmTyqYaFUDq', 'tfCwgM8TOEIJZp18RMuK', 'tBc6GFNOps7Rda+9EG7/', '60021188361', 'GCWP5N', 2),
(11, '2017-04-06 22:18:20', 100, 2, 'GO5161', 100, 'lIcB4lgwQbAnJWq0I4lmAoVGWXJPE2qokZ+rhJyg5uT+0a0Y5npuzAhPkxUK7sOBFUSZavTA8AAWyV2xUi7NdQ==', 'eWyCiWJKRWsUMbJDtUqxJXt7aehpfP0yjvuD4j6xwtcQLvKa0ulRcmYeWM6oy2fT0RxV0uBKI7GJ7kKzzJEY6A==', 'P7HhkEvCKYbin9kyJmvdqFAPWb9iRHEdbLg7ocAhfimMSbdQ1NuZj7LxTARl', '5VYg9hSb7QCQbOGAeevp', 'a1tBvb7TkdsemMkuqszU', '60021529851', '8EE8KX', 0),
(12, '2017-04-10 18:54:30', 100, 2, 'WO7889', 100, '80VJdVs9tDqdkFfE3GQeKlCfLEH9+6it8iWkT4HW6wePwosFfaEcG0G2Ws7galN93oNIfsZvYHdO3CDkUmplkA==', 'AfGxpAydH/yzZYfbFWzSVG+cI0C/rKRAGEtkNssuLgLTNYHsmbQouPjGPI3vE3Ya7Xc2GLMpOflOD6b4aFocIg==', '7/74Mp5ebB+ompdSL9Vm32z4+55hvYAyHc8DfvTnU13JYG96Yc2MYpp1Vu9z', 'vWsgIQ9/l6J+LocoKZ7Z', 'azgncGL/o4UzI1ZfA8Ys', '60021738233', '50KT2B', 0),
(13, '2017-04-11 01:07:28', 50, 3, 'WO1111', 50, 'z4+NPGOVL8oKD4R4ZqHz2C6zIUHofrbYIS5D/a39EQ9uljfUjY7BwZaWFfVoZH7NJ6yMNbHTgd8pZ93IxbBGnA==', 'WicQdtEh99kbUE8a21aDz8mcNkxmWg23Q8CecDMCMdbdb+n7pDHAyTiIdy9P7qxXvuLGUvFyhLbOCVggP1eQBA==', 'wyB0EB4On0gN8fscMWvyJULlKbbEmEb2RLDaQphoMf1M/7CPSk9hiDHIfIDPsH1kmk+xcdPRwTlV2h4Rz8spIA==', 'DHm4C01cJxbf8YeAZ99C3JyO/IEBNF2VJgH2dt4sEYSSEzvv5yYqOTi6Rv0P55KhvjHDK9mkOPIZSmm8b3FNhQ==', 'NzHbZloIj0aRU/JDfbRy89aWsawfMFWT6LLxCANQ0WsEVWOPHZRhSk/t4WyAYxJ+mQLkCCtfvhmS+zE2OKRtBw==', '60021779891', '2IUFIG', 0),
(14, '2017-04-11 02:02:21', 25, 5, 'WH5555', 25, 'wMuGKBZVuRsS6161NJPC7RYWZ3gaC9LasQB51IuOhCZVByQDf+kQ23iQHuaQ+6pHtzkNnAWIMWIOzGoE0s7+LQ==', 'uvnSsOUmuqd1INMF6+1rweFTA4+57CP240Y+tBH3fYozl9SPnQnRsDwwjsfH6UIY+HaoH9J1zRfsmXwIGrgohg==', 'FQd/FxPYGhI4N6g1AiZ0dQxy/4e4rtTxdmxjdPhA1s7RZfyVv+jlofld2YodwLA6okVvM/2Cx7qC0ldYD4Jewg==', '0PWMYKJq215bLN1FeUHcCJdvXV8XUOTNaXLnE0Brj/FTu1pPddQFKnXLv5EKPaHfUEPJ6IMuo0bgXC7iLQj+MA==', '6sP4h5Hb/IrdAnI1TzulfIJMGRbW0laJvFjMlOZ1p/WS/Pg9rtUg1Xidqwe3wjpLVBjwPsVK5CxN0u0z01Uq5w==', '60021780713', '22AKY2', 0),
(15, '2017-04-30 16:27:13', 55, 0, 'WO5161', 0, 'hmSyJUN5b9dM4gY6pCVCwWKMKKJtLL7lsBX2xmBee4g4IlsMTHogy3efGsuyYVYZ/kbUHP/6rEk9Y6IgFYzzQQ==', '9VZT0UXOhEiiXU4tDoSA+KKOGd5AkyPk15+PSpvMCFB6ANak0u44thATSOBhF3FkgHfYpmWJysU+CiySr86uBA==', 'xCdUXagE+JdZtvcA2cEcEEBwavpz2OjEXQmIrjYZ9PfQke3oWbxAAF2pUluCSRL7iQSmXwGhTy0vQXAmOUudGw==', '8tm0yGsZzcdw7wC0BN8ZCD8xG/UF/gyERFPh8jFHQn/l4LKchSxmseoi6Bvb2IEhs+LAZVcfThPHX86+RssqIA==', 'gWt1FI0PAtbmCWlJj9124J7TK9H4A5EjjH2aFYtV7uYOHntFpgOChrVxQNAghwcqKtmGL3bTbV7qHIxjamF13g==', '60023015044', '448K6N', 0),
(16, '2017-04-30 21:33:48', 25, 2, 'WA9844', 100, '8G4iZz/PjnKyvuukzT6G8yYY4xUj5YeRMTPlIwOYKI3jpZloPBSyOAIODQPgdyh0vG+AgOLjQV+RBe1hWVfl1g==', 'KtyvLk7hzj5WM6A0xNdvBzGWv24CBxfspLjqQspBYjEdmIeBz4LW8wTRMKcOV1mI0AeWtI9l6e5tCt1PEVqzdA==', 'ximc1rzSb+lYibWjGlK4+530/KVBCQgodx5PQvHL+rSlfo2AXQ3WRmbPSi1yKc4OiipthzWdX9SEU5Lad1/Hjw==', 'wFjoXoukcHW7vynz0atgz2cxmb3f6lpDqmQTIng18xfInpCGAWQO6UWO2iJytBg3pywHlZHU+b3uJ4/zt8IuKA==', 'G2WVxrlW0OB+1f4i6ORqg5JPlrXerAo/PIo54l9/TFweZytmydft49wPs/6AG4bsWqdtSAUsehsVrW7ZpVv8Dw==', '60023017381', 'QLNIZ1', 2),
(17, '2017-05-07 17:45:21', 50, 2, 'WO4161', 100, 'VypgzzJ6ZIRqb9G5AI114w8eUzl4OW++Ku6+slpR6VfxRAMP9b9pm5ltHxTE+mzk0vihjz6RM/P4hQij+tUs4A==', 'DYQIo/y3AJwIUSLVM9M5w5aIS5wSiFxmmd9fXVyn/sa/0817GKVB55D+4UasyyI6WqAyDFoqFowr6uzAlbm08Q==', 'I74RVybV/ZQHEtPQxvheg33SpN8NtajtaLFg6r/dcxbAnrVlsN2nLbuKozdmdzc6fyUqdL0ZHxfb7KTqXGlIRg==', 'm9d66rHTWWxmtOSsuf7jIsP7XQNow1ZUl2PAVYLzSEZlvDGwEKor2QXLWfqor7nGsUMR2qN/r6BlxcBKV21Emg==', 'ctFDXpyxFiMkNVRoC+TIo2oFdQcisWKdJfWBoaTofVYQJvSjROpuWQn1LvTjdPrHv60ur3sdO5ORsAMfVbHADA==', '60023358726', 'O6LO5O', 8),
(18, '2017-05-07 17:45:43', 50, 2, 'WO4161', 100, 'qKtssudvM7tEmCUDvqoNAnHv7us+f8/EFGeizSa+EUb8JbVSJfoWcv5hVpbcmG9EPggi689UUx7SKi1rEMz0Jw==', 'P2X05ZIMsDPC2VWA0VCyQdi5x6+4LJvMY7gi2Ql0BhWgmPCXYSF+FrtTH6eHHBfaywwZKLWVQFi+ziaBZMfUcg==', 'Nv7d+Z2KjYuh++fbQ9W2Yp5i/1OTusi0EeoDxU0JW8W9M99fL0ztCkH6MYlIEEbST+EBsz4IcP4NLaGo/pR6Zw==', 'jHgl7QfoycLf/NcXFjwbYsXsmNbOKFV8IPxEke+AD1UAOGdRBx3NKYZa5q1wWkS3YF/UjgrnGeTCF0ekRUay1g==', 'RNLOwdIlZtp/3igzSYHgQUtWkTppdkIjj+3lPPLVJMQUm5BX7Q25wedclgNskrpKJdGoluMQ5vrjX1c8QO7nBw==', '60023358731', '8VUS7V', 9),
(19, '2017-05-07 17:47:00', 50, 2, 'WO4161', 100, 'WgvXdfxEY9J9oNku3KcEATKg6n5kpI+rXb/lyFULmRQ+M2Xj/+925wL6ZOstiwe4qPvdX5LM2XyQtiaf+mYXYQ==', '0CVACIhBwKrcQuoKz62KoXrKo6G+sTwSjXFHMxcgbkQ/rh0B65LxXh/4KW25wGQf5FwLd8LmU1M1ZI7KRKmF6Q==', 'px8IfHIefWKbxFrGIZJvICDTu6V5n0haGwMHgDsWo1SzqFmB3o4BC/73ehFjjPojCQZlTbEdSglRz9GheJZg0Q==', '3vky0L3cmfm5JSti1FizgCa9NImUbbWDCnUnq73sOEQh7NvjYIkZ1+Dvchhk/G3NQn8hC7uF83cnWJNh0mGvmA==', 'dbO6A2h6FnE5aFvd5/1XwI6HDE0OGoKKWMapt6CiLBNovDDzktZRoe0ndwAelv/2kKL1xNsLctUAEuw39Kdoeg==', '60023358746', '30UFVF', 10),
(20, '2017-05-07 17:52:05', 50, 2, 'SM6545', 100, 'Rgg8LvIyQa7cYZIMBRn3wYaldv9BGhTPJYjGbmYGMxtUEN+ywQTybssBtnWowNFqV0FvL9/ubDk5+vxdcreRXA==', '7BJVMq0fTfVs81JZKA8r0fy/35PLGHKnhCrXSVkLuLzpW3AhonfZFnS7qLbTxBkhLIsU5DEVUchwuk69OLmmAw==', '8vzNFcjtux1aZXOEq+XBwNO4qAe29i5eQqxIBazxnTzJtv8ALhk7ajeC2f4YY9GlFTAOJkwLrbM5K2Jx4mKOWQ==', 'Wsam2UObiCSptvOQjpu1kAqS0FsBtEv2YQ0YoV+34psKHav/WyTGgr2NdUgtssp0cEJpLDYiOGcYl9Jfxh7iAw==', 'IHDefh0ptAxY6NR80DEKP6FLWY+sUchu4FBJHHFch9v+co38pOoKmGtUZKqLNjK3IYqyOhToMyP2zWY0OFTUZg==', '60023358805', 'AXWO94', 11),
(21, '2017-05-07 17:53:11', 50, 2, 'JO8845', 100, 'TUinkJ5r4j6mtdKeyIyhBXsXtIgBTnAOrSAotg2E55+6vlHFX9qnIK4eFOfr0uoQR8Mf1r+APVUxihbURfAfLQ==', '+Er9pA3BXIeaIC/a/8uj9bbqGe0BdfmnMFuWQ9WT+eAef5uESpPzUUlHhTSXZFfpONyu8XpKZvOFPTd65x2Rmg==', 'hK0zGV14tjBu6253F2qFRtIcX7Lh/GOglPXjL30B6oHDfvzJW1f+l71lLJEtIs80lLzzczZ8uwfHBv7RK1ob9A==', '73BJ7o2P8DkhFYx0D2hG986vhdah4q352PARfQXQvILMB+NFV9BNLzMdLwiViBSDBXr4w4MzXiUSwtr+/4ZBqw==', 'PJI/Ip0FCaG1oYnQ58foCKqii1xBKday+0wfKW3+buJX2zbM9iFWKiljxMRs4egZtrC3xsmbU+nh45LOtY3J+A==', '60023358813', '8H5YXY', 12),
(22, '2017-05-07 17:54:35', 50, 2, 'JA3493', 100, 'bE2jF3P48sgXie06WYRc4FQwRvDpqK5yB/iJo+I3gcND+shAr1hYo/8se/GT9ea29sHGCnWxRpY7qycg+TIdhw==', 'wsbrC91WLwBWi911LOnB33u63nQjFjs5FgrKb4WuNlPRcE6hys1RuaaSv13GZNRUqe4+EABBTbykhgElJ9qkXg==', 'eSCU36iUzBj0bS6RXzCGvwIk19i/ZCfhhftrGogDS8IO2NyKK2gCpk9ua0Z5ZDvBcv1dJ3SQtEz2METrj1e4xA==', 'kFqdk9OxyQ/zLt+N8lWrf+huLxu5knVpU81spuo5vxIIz0SVoLImG+l2FUm5NR+N4HXFF2qH91P7uDsZR4S2qg==', 'BnQGJl6vJedS0PBp5VsvHi+Y6EAUnyHRg3/MEa1OlEIQtsbo0tVXTNfy+zkKfrxzHtBc1axPN+67Rj0FJDW3mw==', '60023358832', '6NXA3Z', 13),
(23, '2017-05-07 21:07:45', 40, 6, 'WO4855', 100, 'hG7hyivFM9HlxoW8cbVewKiah9ItTMK6sZzEdSRf9gIDmImawQpNIDDJL4zVfxT0Ts7Fjz9V3PC9BUM9+pTNDw==', 'JBrkIEE1i0wtuu8ZreyewpnVWjmSTOlDSSD/4rEnBRMxH6zY1HGTqfy521ZU5mRUfMYM8TkBkJcNaC/w6X5YiA==', 'JaZHVrWFQaXWjrpYSgQ8pOnxjH5XLHCtQoSaL57PdAUZVtwLMt7JTtbpTSWZepd13cBUpOCw8NY3FmYqs0ARGw==', 'hhEJbIq1V97fQuV1Rvs7ZprtH6R87Ff3m8iUXepWQ9eXLq8LiIZsIKkc9kepCBFwTRX+XD3A0yRS6hsGjUeDaA==', 'R14sYr/Ez/hI1XBzpNOaB6vIEaoAjJ0gU+vvbJe/colDx+x5wh724vrRSXt3skTg52RuK7FKBmPlmfU655H+/w==', '60023360170', 'O62LK7', 14),
(24, '2017-05-07 23:35:20', 40, 6, 'WO4855', 100, 'R/l3AViXQdRn+hVIdKa/0JjlQqK2z6XGv3HaeeTji/qoPbGcoSkoCCxA+eZxtkoefyFRVlUqkmuSELhqsA3FWA==', 'zmNwZfLkLXzV67bzdvzTP+5fi5YhLOH9/nPKtLdI8Pp3sHyAfgUEV/qxlJn63++ScMZINzCJ3FA2MhjwLzHHcw==', 'tK3Jqe0SewSkvbd/2TJIj6W5NGrsa34VnVUb0OmOtdq1OkUBtRuvuLkiuIzCsECT5veLN3eDfK/aq+m32YM3cw==', '+9aCzEkgJ2zSbxfqnEgdv7zzPR0WiXsNmxbMzHy02ZqKGcwhBtDQ8L+2eGrHVQvJ+H1mv7kNS1lJ76G2wE31qw==', 'ouCa0AMNNLNiAdg3vj5RzzIMpbGihtfl+rjdqG+6XzrH2TZkSS5liIpqx/6ThiffW9ggPEX5hZzFsRBMGjkp7A==', '60023362132', 'YXMSXN', 14),
(25, '2017-05-07 23:44:49', 20, 6, 'WO4855', 100, 'qcoTtB3bodtQc/liQRPmvgkGbiWMZJSduTpOZMGgRLlnMG1PjXo3dZWc2fxhFYVUP78c2h9tfqyBfB8JCIDu/A==', 'D5TreJiKbuOfxHpuI8nbjkDglnnXIbE015we/3VliBk1oexXLWyco4mIZb+y/3+y0OPYvLj+fOJrG+5Pun101g==', '1j4kG3MXmstO9lpaZ18wPdeaH62Cvy2sVt1Pe4cLLFn4lv+6cog3RVqYBFiFYYWYqQUkzvjCBsmGIJ4hpzramQ==', '/ce9n66FJ5NdCJslCdTlzs4zCMCNPQoDNf/f1vqRMXlSMq3oaUfCEJ8l8GgsP5AFwtjBfj9ag8q1Wm3E6CFOnQ==', 'hDG2A0nSEzvL+TvRDCr5PSStUbT3m0g7dADQEsz2lnmnZXCG2tMj70WifDqe4O4wSVirQmMisPvqBk5mx+2YFA==', '60023362334', 'BO1STP', 14),
(26, '2017-05-07 23:46:51', 100, 6, 'SM8191', 100, 'mSEeG1e6XG2Ipqq7zYp0nuDOXGlEqwpCh04RiXzuup211J451xefPsQwU5qIk9YVNS2cmYuuThLoXMUOCCoqMA==', 'U6pTxvQ21OvJ1yXOcy6zBKqnH+PxeBKR2M4cbDLjitTM02f2WOP958qkiWH/e858heN2wZPcD6I+LFsZ+4+Tyw==', '7JNn0HITK8rraIJB+DTTy1Pfw75/pPk/Cq8Ir8g4OmuVDWt7ADLJTo2+9iaxJraSu8FKhGe/M6TleWOyqaUJHQ==', 'ZttaPNBRZAjtWb0TXpjS8t15SPntMcFOHPDUUT7tyWJ2uQs2cK1mqFW5m7zYMFCf4pkEY8mfI1PWBhdDqwJ+Zg==', 'wIUtBg3te6fOqdlHpF2yeUdxq5Q7HWm9DZGAU5MBOrg7Voygse/PKtKv+KSnBnxfd9VhQbcf7HkB11g5Ljbviw==', '60023362375', 'FK3CJ0', 15),
(27, '2017-05-07 23:55:39', 40, 6, 'WO5888', 100, '+Y3hMCvqc6aQWtXZyoJyX5HK7o9pa/GM35ILt8l3iW99BeLrNo3Psk+uQFtvrgEWjIB6rOG4AbEE1ZX4Y0FHdQ==', 'E/Z1uyhHSgUxa7HM0AYRpruDEul2F1m7kfJ4et5LuYaJtnEDtgCLq+8c4+26FVti6XCHoYGm3LtzUFOO0QKtXQ==', 'Db/ppgYDAsS0220eteuSTcScFqRkJKBJI7PDnNSAyPyEwt85ImugWCgzz7dQDulttrA+ze/kOqFjJN8E8uG6Tg==', '5+c98cQgmuIVrAjRezDxU64U+r8xkcg4ldTvH6oUuNOiLZTA8qWm+0ArznSm0aQP4SaDrbdTQUlItauKU8CKZA==', 'oXBxnGKdEmFY3YTkIdQxu3jtvjrfXdCH5lX6AmAJiAoYYQEwZOjaWCuR0pcAJGBoPhFgEBaZsMUosWdH3fD5Mw==', '60023362608', 'V1ZYL2', 16),
(28, '2017-05-08 00:03:02', 100, 6, 'DI9854', 100, 'O1mEp+B6akB5bt9Xp9lQgiElYhRtirg1GDbmRPVdOKHWCSLejbOdPLit8BH5mU8C/eWJcy2rTF6Fg1LvicRMGg==', 'tKJ4ET62on97XxspDD9QqKy+5U/bF4BEKnay52wTx5jHvMc1wPfN4aaq+pK8s3ssNPUOvLMjTwPuIlVy9kqkzg==', 'DktM3HxTuR1drzZdUgIwLhW3SekoAyezGxZe6sEnOO4KvhmbcVZ522kARQroFOjX4LnteSqVf1+mcZqsHEYIDA==', 'SFP/BplQsRweYDLveCfwFV4QjeRXUK+C7RfpTPech6UgVBsRcinlhdKmkJ4FbkHdHvaJ9z7bWtDdghTbMJakvQ==', 'YryTkpetiXvAcQ7ifqyQXInJsEBk/Raxn3lWDwxxt7z95EA4MTj2LS35cY+lKQA8fRfrM13ogA2hW7zw+dpF8Q==', '60023362802', 'K51QHA', 17),
(29, '2017-05-08 00:05:55', 60, 6, 'WO5888', 100, 'ZxUUt4zcA2opi2eNn4ZqeWb0cUHA0ODK/wYMNrSOAKOlmt6CFq5ITnKO8P/TWWmtjQw+cBLxmNEozZCgJSdnQQ==', 'c/fKrFwT3ZJ91iSqNqTMSQGoNoUg18pD4yDao9x8csTcMMgyGNtjah4dHJUnlASWv2ucBxeWptOlT4hXoLkgdw==', 'XztgAQuqlxuxgcImriMNen263ClgPZQbp5uJcOTLw0WK7apO87fRHnsahG4F7+vXZk5KppeN4Ug/frddoZXSAw==', 'Kt3VtpyhMATEjEADBQIvCtgtYi6ABD5USnYWnMx69SWoJc9zLHp9BmP6FfJf3t6JAwu3/EhWZoRtDBAZhjB0rw==', '1uArygv3qk249p5BPUEw+xT/x5OAK8ftzrGEKZSJB2YPTfQ/cDxR6FleFL1CM2Rksk2C1zVQ1pgPXMtsLWW4wg==', '60023362930', 'PRH65W', 16),
(30, '2017-05-08 00:32:15', 75, 2, 'SM4439', 100, 'APCNZ4K2ZVSzfCgJdSSEX8BZW+lYlJMOC0AAbe3WK6TMST4Qni+zSwgY1Zc0POdU+ZSx1IQaEOv5J+YwuzO1Gw==', 'CD/e546ZGkmDsgIzyW9BfFh5vLr0RllSbEfq5dHx+RCz/1KzEhD+jwUc5tdQqOKWFjV9dDSiHmORskW17zXhdg==', '7+0QyHrbsJ0ySby7/Bnf+dD3/utwWf73q620vpVsp90Eg4a7l7ZG2mBxoTwtuVDJD9mur15Wk6Ggqx3ntiLn0Q==', 't/whCUZ+JVLCQFWkECRc1ifWIHzLzIX8y3Re9jlGNgq6eq1/zBkMDu/VGweyQ9eSr/h78hCeofJl+BFtcmoerA==', 'X2sTqvGBfGcyls/sBI+7El8UIW0Hn+piypvnkLyBo5eWmIMoIiNziwfJ5QVwAm6SB/+BTYsI18T8b7cBnNfRNg==', '60023363705', 'MHSK5U', 18);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`) VALUES
(1, '127.0.0.1', 'admin', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, 'Q.55./dHQtO8dfS5D41cDO', 1268889823, 1494193074, 1, 'Admin', 'istrator'),
(13, '::1', 'bworkman01', '$2y$08$3HdoJKdpl0gx9FTrzHZMgu/qpjI5PIAOH755puNlt06wi3BcUF1X2', '', 'brian.workman43055@gmail.com', NULL, NULL, NULL, 'PBGW2QxpxDkDQlRAv53ck.', 1494094253, 1494107598, 1, 'Limited', 'Account');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(48, 13, 9),
(49, 13, 10),
(47, 13, 5),
(46, 13, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_phone`
--
ALTER TABLE `customer_phone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_data`
--
ALTER TABLE `form_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_inputs`
--
ALTER TABLE `form_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_input_options`
--
ALTER TABLE `form_input_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_input_rules`
--
ALTER TABLE `form_input_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_phone`
--
ALTER TABLE `customer_phone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `form_data`
--
ALTER TABLE `form_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;
--
-- AUTO_INCREMENT for table `form_inputs`
--
ALTER TABLE `form_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `form_input_options`
--
ALTER TABLE `form_input_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;
--
-- AUTO_INCREMENT for table `form_input_rules`
--
ALTER TABLE `form_input_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
