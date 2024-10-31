<?php
	
class MyC_Database
{
	function install ()
	{
		global $wpdb;
		
		// Create database
		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}course` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `name` mediumtext NOT NULL,
		  `location` mediumtext NOT NULL,
		  `address` mediumtext NOT NULL,
		  `start_date` date NOT NULL,
		  `end_date` date NOT NULL,
		  `modified` datetime NOT NULL,
		  `created` datetime NOT NULL,
		  PRIMARY KEY  (`id`)
		)");
		
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}course` AUTO_INCREMENT = 2359");

		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}course_time` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `course_id` int(11) unsigned NOT NULL,
		  `day` tinytext NOT NULL,
		  `start_time` mediumtext NOT NULL,
		  `end_time` time NOT NULL,
		  `created` datetime NOT NULL,
		  PRIMARY KEY  (`id`)
		)");
	}
	
	function uninstall()
	{
		global $wpdb;
		
		// Too dangerous
		//$wpdb->query ("DROP TABLE {$wpdb->prefix}course;");
		//$wpdb->query ("DROP TABLE {$wpdb->prefix}course_time;");
	}
}
?>