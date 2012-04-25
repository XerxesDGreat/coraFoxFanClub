CREATE TABLE IF NOT EXISTS `#__itpc_users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `users_id` int(11) UNSIGNED NOT NULL,
  `fbuser_id` varchar(50) NOT NULL DEFAULT '0',
  `twuser_id` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY(`id`)
)
ENGINE=MYISAM
ROW_FORMAT=default
CHARACTER SET utf8 
COLLATE utf8_general_ci ;

CREATE TABLE IF NOT EXISTS `#__itpc_sessions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fbuser_id` varchar(50) NOT NULL DEFAULT '0',
  `twuser_id` varchar(50) NOT NULL DEFAULT '0',
  `logout` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
)
ENGINE=MYISAM
ROW_FORMAT=default
CHARACTER SET utf8 
COLLATE utf8_general_ci ;