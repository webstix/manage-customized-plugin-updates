<?php

if ( ! defined( 'ABSPATH' ) ) exit;

wsx_mcpu_pl_mng_create_table();

function wsx_mcpu_pl_mng_create_table()
{
 // Create plugin related tables after activating the plugin.
 global $wpdb;
 $charset_collate = $wpdb->get_charset_collate();
 // Customized plugins
 $wsx_customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
 if ($wpdb->get_var("SHOW TABLES LIKE '$wsx_customized_plugins'") != $wsx_customized_plugins)
 {
  // table not in database. Create new table
  $wsx_customized_plugins_sql = "CREATE TABLE $wsx_customized_plugins (
	`ID` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`plugin_name` varchar(250) NOT NULL,
	`plugin_code` varchar(250) NOT NULL,
	`customization_details` text NOT NULL,
	`plugin_status` enum('Active','Inactive','','') NOT NULL,
	`mc_current_status` enum('Enable','Disable','','') NOT NULL,
	`plugin_deleted` tinyint(1) NOT NULL DEFAULT '0',
	`plugin_marked_by` varchar(250) NOT NULL,
	`plugin_marked_on` datetime NOT NULL
	) $charset_collate;";
  include_once (ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($wsx_customized_plugins_sql);
 }
} ?>