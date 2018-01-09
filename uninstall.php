<?php
// Fired when the plugin is uninstalled.
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN'))
{
	exit();
}
$wsx_set_user_accs = get_option('wsx_set_user_accs');

// Removing all plugin related tables from the Database.
delete_option($wsx_set_user_accs);

// Drop the plugin relate tables from the Database.
global $wpdb;
$mcpu_customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
$mcpu_sql = "DROP TABLE IF EXISTS $mcpu_customized_plugins";
$wpdb->query($mcpu_sql);
?>