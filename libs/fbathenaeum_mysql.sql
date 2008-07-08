CREATE TABLE `locations`(
	`uid` bigint(20) NOT NULL,
	`x` int(11) NOT NULL,
	`y` int(11) NOT NULL,
	`floor` int(11) NOT NULL,
	`updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
