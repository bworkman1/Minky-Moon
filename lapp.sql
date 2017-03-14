-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2017 at 12:16 AM
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
  `name` varchar(50) NOT NULL,
  `category` varchar(30) NOT NULL,
  `header` text NOT NULL,
  `footer` text NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cost` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `form_inputs`
--

CREATE TABLE `form_inputs` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `data_type` varchar(50) NOT NULL,
  `input_type` varchar(20) NOT NULL,
  `max_length` int(11) NOT NULL,
  `min_length` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `sequence` int(11) NOT NULL,
  `col_span` varchar(20) NOT NULL,
  `custom_class` varchar(50) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `help_block` varchar(50) NOT NULL,
  `access_level` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `form_input_options`
--

CREATE TABLE `form_input_options` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

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
  `payment_type` varchar(30) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` float NOT NULL,
  `partial` tinyint(1) NOT NULL,
  `form_id` int(11) NOT NULL,
  `customer_number` varchar(20) NOT NULL,
  `form_cost` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
(1, '127.0.0.1', 'admin', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', NULL, NULL, 'FAVfeR54n3ZC/G9lDc01Pe', 1268889823, 1489266157, 1, 'Admin', 'istrator'),
(7, '::1', 'bworkm01', '$2y$08$JxWREEQayFQIlZIlrqtSiutEXjoz.x3rXJhf/jlATmp4EYkxRhoS.', '', 'brianworkman43055@gmail.com', NULL, NULL, NULL, 'yoyZmdEP.KIabCFW/5A49O', 1488259668, 1488327015, 1, 'Brian', 'Workman'),
(8, '::1', 'bworkm011', '$2y$08$nsQ38UUOyzjfK6NOEm7uG.fBFJe8KonmmhAqZ0jVlq7MV8HmANfk6', '', 'brianwo.rkman43055@gmail.com', NULL, NULL, NULL, NULL, 1488263473, NULL, 1, 'Brian', 'Workman'),
(9, '::1', 'bworkm0111', '$2y$08$16ZKvRK61q5jwzNbAs8KNeTqvyvUmCp1EcoQMIkagd2esPHeN2gAO', '', 'brianworkman413055@gmail.com', NULL, NULL, NULL, NULL, 1488326996, NULL, 1, 'Brian', 'Workman');

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
(2, 1, 2),
(18, 7, 1),
(15, 8, 1),
(17, 9, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_data`
--
ALTER TABLE `form_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_inputs`
--
ALTER TABLE `form_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_input_options`
--
ALTER TABLE `form_input_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `form_input_rules`
--
ALTER TABLE `form_input_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
