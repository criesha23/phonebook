CREATE TABLE `tbl_contacts` (
  `contact_id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `contact_name` varchar(225) DEFAULT NULL,
  `contact_company` varchar(225) DEFAULT NULL,
  `contact_phone` varchar(225) DEFAULT NULL,
  `contact_address` varchar(225) DEFAULT NULL,
  `contact_email` varchar(225) DEFAULT NULL,
  `contact_image` varchar(225) DEFAULT NULL
) 
