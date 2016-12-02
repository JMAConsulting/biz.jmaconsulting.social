--
-- Drop existing table
--
DROP TABLE IF EXISTS `civicrm_civisocial_user`;
DROP TABLE IF EXISTS `civicrm_civisocial_facebook_event`;

--
-- Table structure for table `civisocial user`
--

CREATE TABLE IF NOT EXISTS `civicrm_civisocial_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique User ID',
  `contact_id` int(10) unsigned NULL DEFAULT NULL COMMENT 'FK to Contact ID that owns that account',
  `oauth_provider` varchar(128) NOT NULL COMMENT 'OAuth Provider',
  `social_user_id` varchar(128) NULL DEFAULT NULL COMMENT 'User identifier given by OAuth Provider',
  `access_token` varchar(1024) NULL DEFAULT NULL COMMENT 'Access Token provided by OAuth Provider',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'When was the civisocial user was created.',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When was the the civisocial user was created or modified or deleted.',
  PRIMARY KEY ( `id` ),
  UNIQUE KEY `unique_social_user` ( `contact_id`, `oauth_provider`, `social_user_id`),
  CONSTRAINT FK_civicrm_civisocial_user_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;

--
-- Table structure for table `civicrm_civisocial_facebook_event`
--

CREATE TABLE IF NOT EXISTS `civicrm_civisocial_facebook_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL COMMENT 'FK to civicrm_event table',
  `facebook_event_id` varchar(32) NOT NULL COMMENT 'Facebook event ID',
  PRIMARY KEY ( `id` ),
  UNIQUE KEY `unique_event_id` (`event_id`),
  CONSTRAINT FK_civicrm_civisocial_user_event_id FOREIGN KEY (`event_id`) REFERENCES `civicrm_event`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
