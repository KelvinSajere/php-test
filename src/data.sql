--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patientID`, `name`) VALUES
(1, 'Patient 1'),
(2, 'Patient 2'),
(3, 'Patient 3'),
(4, 'Patient 4');

--
-- Dumping data for table `law_firms`
--
INSERT INTO `law_firms` (`lawFirmID`, `name`) VALUES
(1, 'Law1'),
(2, 'Law2'),
(3, 'Law3'),
(4, 'Law4'),
(5, 'Law5'),
(6, 'Law6'),
(7, 'Law7');

--
-- Dumping data for table `links_patients_to_law_firms`
--

INSERT INTO `links_patients_to_law_firms` (`linkID`, `patientID`, `lawFirmID`) VALUES
(1, 1, 1),
(2, 2, 3),
(3, 3, 1),
(4, 4, 1);

-- --------------------------------------------------------
