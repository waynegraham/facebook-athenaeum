/**
$Id$
*/
CREATE TABLE `locations`(
	`uid` bigint(20) NOT NULL,
	`x` int(11),
	`y` int(11),
	`floor` int(11),
	`updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
