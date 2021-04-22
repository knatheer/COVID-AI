CREATE TABLE `requests` (
  `req_id` int(11) NOT NULL,
  `img_name` varchar(128) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `img_size` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `process_time` decimal(5,5) DEFAULT NULL,
  `result` varchar(512) NOT NULL,
  `status` varchar(64) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;