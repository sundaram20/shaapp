-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2019 at 10:37 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `app`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_menus`
--

CREATE TABLE IF NOT EXISTS `app_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_module` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_order` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `app_menus`
--

INSERT INTO `app_menus` (`id`, `id_module`, `name`, `display_order`) VALUES
(1, 5, 'Hotel Master', 4),
(2, 5, 'User Master', 2),
(3, 4, 'Website', 2);

-- --------------------------------------------------------

--
-- Table structure for table `app_modules`
--

CREATE TABLE IF NOT EXISTS `app_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `app_modules`
--

INSERT INTO `app_modules` (`id`, `name`) VALUES
(1, 'crs'),
(2, 'pms'),
(3, 'be'),
(4, 'website'),
(5, 'master'),
(6, 'report');

-- --------------------------------------------------------

--
-- Table structure for table `app_shops`
--

CREATE TABLE IF NOT EXISTS `app_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_code` varchar(10) NOT NULL,
  `database` varchar(10) NOT NULL,
  `module_access` varchar(52) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `app_shops`
--

INSERT INTO `app_shops` (`id`, `shop_code`, `database`, `module_access`) VALUES
(1, 'WH2019', 'welcom', '1,2,3,4,5,6');

-- --------------------------------------------------------

--
-- Table structure for table `app_sub_menus`
--

CREATE TABLE IF NOT EXISTS `app_sub_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL COMMENT '0 means show on entry menu',
  `id_module` int(11) NOT NULL,
  `name` varchar(52) NOT NULL,
  `file_name` varchar(128) NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `app_sub_menus`
--

INSERT INTO `app_sub_menus` (`id`, `id_menu`, `id_module`, `name`, `file_name`, `display_order`) VALUES
(1, 2, 5, 'Users', 'manageUsers.php', 0),
(2, 2, 5, 'Security Levels', 'manageUserLevels.php', 0),
(3, 2, 5, 'Assign Permissions', 'manageUserPermissions.php', 0),
(4, 2, 5, 'Shop', 'manageShop.php', 0),
(5, 1, 5, 'Hotel Manager', 'manageHotels.php', 1),
(6, 1, 5, 'Hotel Category', 'manageHotelTypes.php', 1),
(7, 1, 5, 'Room Type', 'manageRoomTypes.php', 1),
(8, 1, 5, 'General Services', 'manageGeneralServices.php', 1),
(9, 1, 5, 'Outdoor Activities', 'manageOutdoorActivities.php', 1),
(10, 1, 5, 'Dining Services', 'manageDiningServices.php', 1),
(11, 1, 5, 'Conferences & Meetings', 'manageConferenceServices.php', 1),
(12, 1, 5, 'Kids Related Facilities', 'managekidsFacilities.php', 1),
(13, 1, 5, 'Room Amenities', 'manageRoomAmenities.php', 1),
(14, 0, 4, 'Website Landing Page', 'manageLandingPage.php', 6);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




