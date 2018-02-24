-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2018 at 05:18 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marrick`
--

-- --------------------------------------------------------

--
-- Table structure for table `law_firms`
--

CREATE TABLE `law_firms` (
  `lawFirmID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `links_patients_to_law_firms`
--

CREATE TABLE `links_patients_to_law_firms` (
  `linkID` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `lawFirmID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patientID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `law_firms`
--
ALTER TABLE `law_firms`
  ADD PRIMARY KEY (`lawFirmID`);

--
-- Indexes for table `links_patients_to_law_firms`
--
ALTER TABLE `links_patients_to_law_firms`
  ADD PRIMARY KEY (`linkID`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `lawFirmID` (`lawFirmID`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patientID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `law_firms`
--
ALTER TABLE `law_firms`
  MODIFY `lawFirmID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `links_patients_to_law_firms`
--
ALTER TABLE `links_patients_to_law_firms`
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `links_patients_to_law_firms`
--
ALTER TABLE `links_patients_to_law_firms`
  ADD CONSTRAINT `links_patients_to_law_firms_ibfk_1` FOREIGN KEY (`patientID`) REFERENCES `patients` (`patientID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `links_patients_to_law_firms_ibfk_2` FOREIGN KEY (`lawFirmID`) REFERENCES `law_firms` (`lawFirmID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
