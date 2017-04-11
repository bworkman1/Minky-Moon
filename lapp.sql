-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2017 at 05:16 AM
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
(1, 'api_key', 'Wj/YK3+aDKrdZiaVtmgyoGIodEIG/zDBzzucmy8xoFGokAGYPrWW34KGj+RRsuXnWMuYzaMOTbJq/AeLgElOFA==', '2017-03-29 03:51:41', '', 0, 'Authorize Settings', 0),
(2, 'auth_key', 'ru5v5QfNt0la3qXW5+NFRQdo2gvfw6tvnELr7LE8gwZDuvKrknAdFMlYaIM4CPmH2z215gZxPna1lQZy9ayZQQ==', '2017-03-29 03:51:41', '', 0, 'Authorize Settings', 0),
(3, 'failed', '8', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(4, 'time', '12', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(5, 'emails', '', '2017-03-30 04:03:30', '', 0, 'Security Settings', 0),
(6, 'google_api_key', 'AIzaSyACZ6JX6FlUdgwX-r0zVfPcOtoMQNq4ZPs', '2017-04-02 02:07:07', '    ', 0, 'Api Settings', 0);

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
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `name`, `category`, `header`, `footer`, `added`, `updated`, `cost`, `min_cost`, `active`) VALUES
(2, 'Form 2 w 6 inputs', '', 's afa Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nunc dui, aliquet eu urna vel, elementum lacinia nibh. Vivamus mollis luctus quam, ac fermentum orci aliquet vitae. Quisque interdum dui sed nisl semper venenatis. Morbi a interdum velit, sit amet lacinia turpis. Duis gravida massa quis lectus convallis, at facilisis leo feugiat. Fusce interdum dui eros, malesuada lacinia turpis elementum et. Sed quis rhoncus urna. Pellentesque ac cursus mauris, in tempus est. Suspendisse pulvinar congue libero nec sodales. Aliquam placerat semper euismod. Ut venenatis vestibulum sapien. Sed placerat rutrum justo, quis aliquam elit facilisis convallis. Duis id ante ligula.', 'Nunc viverra ligula elementum, auctor dolor quis, scelerisque ligula. Sed blandit justo scelerisque velit efficitur mollis. Morbi mollis justo purus, ac fermentum sapien semper vitae. Sed quis erat egestas, sollicitudin felis sed, auctor ipsum. Praesent at velit at purus ornare euismod vulputate ac mi. <b>Duis mattis nec erat</b> nec viverra. Integer a neque risus.', '2017-03-18 15:44:08', '2017-04-11 04:57:43', 100, 50, 0),
(3, 'Form 3 w 4 inputs', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nunc dui, aliquet eu urna vel, elementum lacinia nibh. Vivamus mollis luctus quam, ac fermentum orci aliquet vitae. Quisque interdum dui sed nisl semper venenatis. Morbi a interdum velit, sit amet lacinia turpis. Duis gravida massa quis lectus convallis, at facilisis leo feugiat. Fusce interdum dui eros, malesuada lacinia turpis elementum et. Sed quis rhoncus urna. Pellentesque ac cursus mauris, in tempus est. Suspendisse pulvinar congue libero nec sodales. Aliquam placerat semper euismod. Ut venenatis vestibulum sapien. Sed placerat rutrum justo, quis aliquam elit facilisis convallis. Duis id ante ligula.', 'Nunc viverra ligula elementum, auctor dolor quis, scelerisque ligula. Sed blandit justo scelerisque velit efficitur mollis. Morbi mollis justo purus, ac fermentum sapien semper vitae. Sed quis erat egestas, sollicitudin felis sed, auctor ipsum. Praesent at velit at purus ornare euismod vulputate ac mi. <b>Duis mattis nec erat</b> nec viverra. Integer a neque risus.', '2017-03-28 23:34:35', '2017-04-11 05:06:41', 50, 25, 1),
(5, 'Form 5 with 3 inputs', '', 'Sed sed orci eros. Cras sed lacus eget dolor facilisis venenatis. Aliquam vestibulum ornare dui ac faucibus. Cras at pretium risus. Donec sed porta sem. Mauris bibendum, purus sit amet gravida convallis, velit dolor imperdiet tellus, a facilisis tellus felis ut mi. Proin pellentesque sapien magna, luctus hendrerit sapien placerat a. Etiam consectetur, erat non blandit auctor, magna nunc tristique tellus, quis elementum est eros ac orci. ', 'Sed sed orci eros. Cras sed lacus eget dolor facilisis venenatis. Aliquam vestibulum ornare dui ac faucibus. Cras at pretium risus. Donec sed porta sem. Mauris bibendum, purus sit amet gravida convallis, velit dolor imperdiet tellus, a facilisis tellus felis ut mi. Proin pellentesque sapien magna, luctus hendrerit sapien placerat a. Etiam consectetur, erat non blandit auctor, magna nunc tristique tellus, quis elementum est eros ac orci. ', '2017-04-01 19:05:40', '2017-04-11 04:58:06', 25, 25, 0);

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
(49, 'WO1111', '2017-04-11 05:07:28', '2017-04-11 05:09:42', '291-55-1111', 'ssn', 3, 5, '60021779891', 1);

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
(25, 5, 'first_name', 'text', 1, '', '2017-04-01 18:06:15', 'First name', 'required|alpha|max_length[30]', 0, 'col-md-4', 0, '2017-04-01 23:05:40'),
(26, 5, 'last_name', 'text', 3, '', '2017-04-01 18:06:46', 'Last Name', '', 0, 'col-md-4', 0, '2017-04-01 23:05:40'),
(27, 5, 'middle_initial', 'text', 2, '', '2017-04-01 18:52:34', 'Middle Initial', 'alpha|max_length[1]', 0, 'col-md-3', 0, '2017-04-01 23:05:40'),
(28, 999999, 'asdfads', 'text', 1, '', '2017-04-01 19:08:30', 'asdfads', '', 0, 'col-md-2', 0, '2017-04-01 23:08:30'),
(29, 3, 'ssn', 'text', 4, 'ssn', '2017-04-11 01:05:43', 'SSN', '', 0, 'col-md-3', 1, '2017-04-11 05:06:13'),
(30, 3, 'last_name', 'text', 2, '', '2017-04-11 01:06:13', 'Last Name', 'required', 0, 'col-md-3', 0, '2017-04-11 05:06:20');

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
(340, 'Boat', 'boat', 2, 16),
(339, 'Car', 'car', 2, 16),
(338, 'Truck', 'truck', 2, 16),
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
  `name` varchar(20) NOT NULL,
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
(8, 'Print Forms', 'User can print forms that have been submitted.');

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
  `billing_address` varchar(100) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_zip` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `approval_code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date`, `amount`, `form_id`, `customer_id`, `form_cost`, `billing_name`, `billing_address`, `billing_city`, `billing_state`, `billing_zip`, `transaction_id`, `approval_code`) VALUES
(1, '2017-04-02 19:11:19', 50, 2, 'WO5161', 100, 'FWV5aXz4CpHNN2ROK0Vzt88LWKC7Qjuw/pgtxuzD+8BkKo6II1vLH7FRo2Lj/8/5EzzqJgwZHK0cICsLCGFaog==', 'a3rov/VB/vsqdJ2NLDauijRxWMdF277rbCT11P4Ex2NyX1mEWTKteOHHL3NzD0p01ElIT/Dq13zPH4Zc89QN0w==', 'oe44dk/p0sZpELQrDobKvHo0OE6u1CGGuhGdQ++lc2ZcnEKOxmAk38Bsz2vQ', 't8NojYnyhvCGDKwq0DfG', 'rPd4A6JbGXyEaoSJckii', '60021185050', 'S05XJ5'),
(2, '2017-04-02 20:04:43', 100, 2, 'WO5161', 100, 'fTCc8M5N9QiKyuT78G11gFq+6wrqFo3i0MKOhX4a1nR3cTJlBfQgkOxYJNXrXqIDsa0hOPMnHwI0ViMFDOxsVw==', 'KZnKojC4uB1f+fs1+SCgnVa26sybESAI9IJ1z9dd0aGUL29p0qVyp69wGw9oUespJ7aaFdxrOHuncOt00eXLEw==', 'NuBaNPAE2hOUCXJPYbQqm7SOSW6s7RINeiG7+JCCLK8q8aPL3i2Ju5VGZQbC', 'owhIphAvXOko+UhJKigV', 'cBCX95I8P58dyX8hU3xf', '60021185322', 'WBIH22'),
(3, '2017-04-02 20:06:12', 75.99, 2, 'WO5161', 100, 'nPdGKXMngzVyeRbb3K8L1ApUpZMewSteR0HPtvksfRhVWPnb3QRkv+vgF0auQEXvnwo27neRL0nBuFmgpJ80IQ==', 'KL9VO7PzJaomCAx2xcMVUuathLWvHJ7kTGGWXzPQV6ZJ8e29qfYU2mJ4EpcmluPWWZ1+tzk9kXTB8gFrzRmz5A==', 'FWfDLFSfKAA8eGPvDbeBsCPkw7egWHFKsWG96ctUkhM4iDxQUCR+cyvmRg6B', 'Y++S/lUqizawyBpJt4tL', 'D1fAsLWXTUyF9zGDvz92', '60021185341', 'C7ZB7S'),
(4, '2017-04-02 22:38:06', 100, 2, 'WO5645', 100, 'go1n2pwjjWdiJjtI9LhdlYzDFiIr/o2W4xfUzwXHtq50Kqpp6UJ3M47XBzpcPQEMx7P6CzdM9MdU3PZBWMJh9g==', 'OIE3EXZN4bEfQ9NnVYn5R1EIdigVd3GxsYX8vnfo4jEbre5A/IV1WDZPfCX57O487adhJ1uftK8phmgF7eF61g==', 'ztbnqDDVFFy9wErlmLp1WvettpDfTzQrXlIEDchp7RPmfgxw7X2pplaeo/8Z', 'RIt3n8m+KGc7naPEuUvQ', 'maDm9UQGHNGZ2doCuzwM', '60021187034', '1JIDM2'),
(5, '2017-04-02 22:42:49', 100, 2, 'WO5645', 100, 'zxQ2rJ2v8JzWdvKhnYwo0ijbtQN8GsDcqPdcOf0rUfw5AEDW+sw/xshk4UAf/tEK3TxdpzT4FauWT5eFYBJpPQ==', '5Olmw9e4pMf0c+qgXj0jZk3/derGcwPW1UXjCM8r3V9lHK6VcLoK+e+BdjWyBXyhyZv8qqQURXfOSJyTHTnbdg==', '2h12OvAgN1Lyz8L/AE7/V1OFFDHvKycx4/FLNoGNSSEqBR2OzVIM/NIv8RwJ', 'sLJmEOrpqz3QjHq+gr67', 'Zqc1SMQS/4eOqRHc449W', '60021187218', 'V5FUOD'),
(6, '2017-04-02 22:44:22', 100, 2, 'WO5645', 100, '+/zl3n6bMjIrJYlbJcDTcKVSNEatltOCTDjCA9buzKqy28PY8Yof/YmsPfiRS0cbJKVuhxbO0qs91pWOvTw2yQ==', 'crF11BeERj2pAuE6R1Eu4ypXVAxXz3dc2mSqsgjPt+x5I5VPNZRZqm7JIR+nlp83o92DqSokzTuH+tan/Im/sQ==', 'x8XkK5HNOqcGQBh5SEJrtpG8VDPgaPqXR/FywBkQg4/f05n+c2Zv7BvDWxTF', '/Ts04ut2DnJF3DAXKpKG', 'Eg9k+SR+wp1j2SgW7EOC', '60021187274', 'O7QIQ9'),
(7, '2017-04-02 23:02:40', 100, 2, 'TE5161', 100, 'CER0cD/nVSdgNf91jlRdbgEqk+e+csWH0dcILI8SJrdex8nJTMqpzoZ1cvuPzWnyJYAEZ37cbYQLscLyOYm4ag==', '3thkRzmwyRI+8rc0EMQZwecPEo7Hi8lBv+RRuyDTctq8+9y9gPTAm85qjqBQXzYsx9z3tCRi7glHnzU/SuNLQQ==', 'lM0zfRLYHV37D09TcpW1dK1TcpWxA61cjVF4qpP0nl3tRxiPAiNdd+PHHCyM', 'KSLjE8xhUAeZjMfRs8Yw', 'n9dzCmZKZRIXaR6w1VeN', '60021187790', '0F09X2'),
(8, '2017-04-02 23:22:08', 100, 2, 'WO5555', 100, 'K/fl5V7nvCeEddQgIaR7x889MtDu2YHC7wXbW4RZaV1ByFHrZ+M/l1liDuWP8NOiQ8hMrSFEZlRlVVAmQZYlTQ==', 'jyxbNRRBZfpPsnE4wYbL4wGD97B1Q3omilTIA/NLCQhO9rvyB0plC7EG1DObmqP/g05sydgTm9h25Hhod8mzsg==', 'UkMuZSp7bq560G4wv0h835SpG3BcjdNqhYIUi8IdCpR31TURrE9NhAfLAe/D', 'dDlkdaKV1kMEzssHHumN', '9w74ZXmOn7bvrIi/3Wv9', '60021188199', '357IKL'),
(9, '2017-04-02 23:25:37', 100, 2, 'WO6546', 100, 'mdrH+U5THvlEZSRNPFRkKREpoYVkvqEl5N4XCIRjby8EY4KE5zL6fy7tTQ1DsgUuujtXNxqqEXyOJfyRbZBrbg==', 'kj5iRzv6jerlECeiUcO6o1kdC+Og9d8m1Brqb+ljlrkQ3bzLR0nx8Vftp8JPg65+NOC0OpBUth2B6yy/EUlX0Q==', '64JddYiCW7zlnIrYxhNy/QLx1iA9DX8IJTYdtK5CHCMvuvEvkmzvWKhxBaYo', 'pKW3gjXpi25HB0ztm0OI', 'vKlycEExGQAHVHDj0FP+', '60021188240', 'K5X05X'),
(10, '2017-04-02 23:32:18', 75, 2, 'WA9844', 100, '9bxT9YSjL9vyeqj6ISNpKAlJVfxvZVLaHuIzYC04hTIz3HfhsTkOBNlKdOsM6Ozuw1vCLVSBofjYivgCvH2VQg==', 'VcM8bmi+/EgaaBkaTaY+vvkgT8XiTy+X1qG20emMaxax2ifS8fjC3VVGs+KgCN9v1pcA7NThU7zoAjJwxvIYEA==', 'lSkFRys5qxQht2ycWIn0sslXJ+01mu2zbr8XooU+MFuXbxdrgAmTyqYaFUDq', 'tfCwgM8TOEIJZp18RMuK', 'tBc6GFNOps7Rda+9EG7/', '60021188361', 'GCWP5N'),
(11, '2017-04-06 22:18:20', 100, 2, 'GO5161', 100, 'lIcB4lgwQbAnJWq0I4lmAoVGWXJPE2qokZ+rhJyg5uT+0a0Y5npuzAhPkxUK7sOBFUSZavTA8AAWyV2xUi7NdQ==', 'eWyCiWJKRWsUMbJDtUqxJXt7aehpfP0yjvuD4j6xwtcQLvKa0ulRcmYeWM6oy2fT0RxV0uBKI7GJ7kKzzJEY6A==', 'P7HhkEvCKYbin9kyJmvdqFAPWb9iRHEdbLg7ocAhfimMSbdQ1NuZj7LxTARl', '5VYg9hSb7QCQbOGAeevp', 'a1tBvb7TkdsemMkuqszU', '60021529851', '8EE8KX'),
(12, '2017-04-10 18:54:30', 100, 2, 'WO7889', 100, '80VJdVs9tDqdkFfE3GQeKlCfLEH9+6it8iWkT4HW6wePwosFfaEcG0G2Ws7galN93oNIfsZvYHdO3CDkUmplkA==', 'AfGxpAydH/yzZYfbFWzSVG+cI0C/rKRAGEtkNssuLgLTNYHsmbQouPjGPI3vE3Ya7Xc2GLMpOflOD6b4aFocIg==', '7/74Mp5ebB+ompdSL9Vm32z4+55hvYAyHc8DfvTnU13JYG96Yc2MYpp1Vu9z', 'vWsgIQ9/l6J+LocoKZ7Z', 'azgncGL/o4UzI1ZfA8Ys', '60021738233', '50KT2B'),
(13, '2017-04-11 01:07:28', 50, 3, 'WO1111', 50, 'z4+NPGOVL8oKD4R4ZqHz2C6zIUHofrbYIS5D/a39EQ9uljfUjY7BwZaWFfVoZH7NJ6yMNbHTgd8pZ93IxbBGnA==', 'WicQdtEh99kbUE8a21aDz8mcNkxmWg23Q8CecDMCMdbdb+n7pDHAyTiIdy9P7qxXvuLGUvFyhLbOCVggP1eQBA==', 'wyB0EB4On0gN8fscMWvyJULlKbbEmEb2RLDaQphoMf1M/7CPSk9hiDHIfIDPsH1kmk+xcdPRwTlV2h4Rz8spIA==', 'DHm4C01cJxbf8YeAZ99C3JyO/IEBNF2VJgH2dt4sEYSSEzvv5yYqOTi6Rv0P55KhvjHDK9mkOPIZSmm8b3FNhQ==', 'NzHbZloIj0aRU/JDfbRy89aWsawfMFWT6LLxCANQ0WsEVWOPHZRhSk/t4WyAYxJ+mQLkCCtfvhmS+zE2OKRtBw==', '60021779891', '2IUFIG');

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
(1, '127.0.0.1', 'admin', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, 'e4pzUueVsdnubkjl7XYyte', 1268889823, 1491874186, 1, 'Admin', 'istrator'),
(7, '::1', 'bworkm01', '$2y$08$JxWREEQayFQIlZIlrqtSiutEXjoz.x3rXJhf/jlATmp4EYkxRhoS.', '', 'brianworkman43055@gmail.com', NULL, NULL, NULL, 'yoyZmdEP.KIabCFW/5A49O', 1488259668, 1488327015, 1, 'Brian', 'Workman'),
(11, '::1', 'test', '$2y$08$oub2OXTy8Bx3uGd//nS9TOD2F.5MC6Z3gJ/45Gdt3HetuLGbY1wTG', '', 'test@user.com', NULL, NULL, NULL, NULL, 1490581456, NULL, 1, 'Test', 'User');

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
(22, 7, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `form_data`
--
ALTER TABLE `form_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `form_inputs`
--
ALTER TABLE `form_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `form_input_options`
--
ALTER TABLE `form_input_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;
--
-- AUTO_INCREMENT for table `form_input_rules`
--
ALTER TABLE `form_input_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
