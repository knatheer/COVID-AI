CREATE TABLE `requests` (
  `req_id` int(11) NOT NULL,
  `img_name` varchar(128) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `img_size` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `process_time` decimal(5,5) DEFAULT NULL,
  `result` varchar(512) NOT NULL,
  `feedback` varchar(32) DEFAULT NULL,
  `status` varchar(64) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `requests`
  ADD PRIMARY KEY (`req_id`);


 ALTER TABLE `requests`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;
