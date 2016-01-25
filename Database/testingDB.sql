-- this SQL code is for testing 
--
-- Database: `IntroNet`
--
-- --------------------------------------------------------

--
-- Table structure for table `Login_Details`
--

CREATE TABLE `Login_Details` (
  `UserName` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`UserName`)
);
