create table `shorturl_original` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`original_url` varchar(4096) NOT NULL,
	`created_at` datetime NOT NULL,
	`updated_at` datetime DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
