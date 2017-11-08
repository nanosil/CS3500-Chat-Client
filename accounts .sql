-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 08, 2017 at 12:15 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE `avatars` (
  `name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `avatars`
--

INSERT INTO `avatars` (`name`) VALUES
('angel'),
('comfort'),
('confused'),
('cool'),
('doggo'),
('drool'),
('ghost'),
('glaring'),
('happy'),
('inlove'),
('mad'),
('pika'),
('scared'),
('sheepish'),
('skeptical'),
('snake'),
('tired'),
('xd');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `color` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`color`) VALUES
('navy'),
('blue'),
('green'),
('teal'),
('deepskyblue'),
('dodgerblue'),
('seagreen'),
('darkslategray'),
('royalblue'),
('indigo'),
('purple'),
('slateblue'),
('chartreuse'),
('maroon'),
('red'),
('brown'),
('sienna'),
('darkgoldenrod'),
('chocolate'),
('goldenrod'),
('yellow'),
('orange'),
('gold'),
('coral'),
('hotpink'),
('white'),
('black');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `mid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8_bin NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`mid`, `uid`, `content`, `time`) VALUES
(581, 40, 'p', '2017-11-07 00:18:58'),
(582, 40, 'p', '2017-11-07 00:20:40'),
(583, 40, 'o', '2017-11-07 00:20:42'),
(584, 40, 'llll', '2017-11-07 00:20:45'),
(585, 40, 'lll llll lll', '2017-11-07 00:20:48'),
(586, 40, 'llll', '2017-11-07 00:22:04'),
(587, 40, 'lllll', '2017-11-07 00:22:07'),
(588, 40, 'aaaaaa', '2017-11-07 00:22:19'),
(589, 40, 'p', '2017-11-07 00:23:22'),
(590, 40, 'eee', '2017-11-07 00:24:00'),
(591, 40, 'eeee', '2017-11-07 00:24:03'),
(592, 40, 'ee', '2017-11-07 00:24:04'),
(593, 40, 'w', '2017-11-07 00:24:07'),
(594, 40, 's', '2017-11-07 00:24:17'),
(595, 40, 's', '2017-11-07 00:24:21'),
(596, 37, 'kjkh', '2017-11-07 17:51:44'),
(597, 37, 'asdf', '2017-11-07 17:51:47'),
(598, 37, 'hl', '2017-11-07 18:41:10'),
(599, 41, 'new', '2017-11-07 18:44:51'),
(600, 40, 'mesage', '2017-11-07 23:08:22'),
(601, 40, 'test', '2017-11-07 23:10:40'),
(602, 40, 'test2', '2017-11-07 23:11:02'),
(603, 40, '3', '2017-11-07 23:12:01'),
(604, 40, '4', '2017-11-07 23:15:31'),
(605, 40, '5', '2017-11-07 23:15:42'),
(606, 40, '6', '2017-11-07 23:15:50'),
(607, 42, 'hello', '2017-11-07 23:16:49'),
(608, 42, '&#x1f914;', '2017-11-07 23:28:19'),
(609, 42, 'testing emojis &#x1f914;&#x1f914;&#x1f914;', '2017-11-07 23:44:25'),
(610, 42, '&#x1f914; ðŸŒ', '2017-11-07 23:53:30'),
(611, 42, 'ðŸ‘€ðŸ‘€ðŸ‘€ðŸŽ©ðŸŽ©ðŸŽ©ðŸ§ðŸ§ðŸ§ðŸŸðŸŸðŸŸ', '2017-11-07 23:55:02'),
(612, 43, 'message', '2017-11-08 00:01:07'),
(613, 43, 'âš™', '2017-11-08 00:01:39'),
(614, 43, 'ðŸ§ðŸ§ðŸ§ðŸ§ðŸ§ðŸ§ðŸ§', '2017-11-08 00:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `prefs`
--

CREATE TABLE `prefs` (
  `uid` int(11) NOT NULL,
  `avatar` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'happy',
  `bg` int(11) NOT NULL DEFAULT '0',
  `theme` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'black'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `prefs`
--

INSERT INTO `prefs` (`uid`, `avatar`, `bg`, `theme`, `color`) VALUES
(34, 'comfort', 0, 0, 'blue'),
(35, 'confused', 0, 0, 'green'),
(36, 'glaring', 0, 0, 'orange'),
(37, 'angel', 0, 0, 'purple'),
(38, 'snake', 0, 0, 'black'),
(39, 'sheepish', 0, 0, 'red'),
(40, 'snake', 0, 0, 'black'),
(41, 'doggo', 0, 0, 'black'),
(42, 'pika', 0, 0, 'green'),
(43, 'cool', 0, 0, 'blue');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `id` int(11) NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`name`, `id`, `password`) VALUES
('josh', 34, 'f94adcc3ddda04a8f34928d862f404b4'),
('test', 35, '098f6bcd4621d373cade4e832627b4f6'),
('user', 36, 'ee11cbb19052e40b07aac0ca060c23ee'),
('xcx', 37, '4ffc8a0cd651e1ce774c3e8fa7441b76'),
('Fernando', 38, 'e807f1fcf82d132f9bb018ca6738a19f'),
('root', 39, '63a9f0ea7bb98050796b649e85481845'),
('blob', 40, 'ee26908bf9629eeb4b37dac350f4754a'),
('new', 41, '22af645d1859cb5ca6da0c484f1f37ea'),
('newaccount', 42, '4288a009bb6e9c4560c9e1a467ffb656'),
('penguin', 43, '24f7ca5f6ff1a5afb9032aa5e533ad95');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `prefs`
--
ALTER TABLE `prefs`
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=615;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
