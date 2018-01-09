<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Show plugin navigation in the WordPress sidebar.
function wsx_mcpu_register_custom_menu_page()
{
  add_options_page('Manage Customized Plugin Updates', 'Customized Plugin Notice', 'manage_options', __FILE__, 'wsx_mcpu_plugin_messages');
}
add_action('admin_menu', 'wsx_mcpu_register_custom_menu_page');


add_action( 'plugins_loaded', 'wsx_mcpu_check_current_user' );
function wsx_mcpu_check_current_user() {
    // Your CODE with user data
    $wsx_mcpu_current_user = wp_get_current_user();
    // Your CODE with user capability check
    if ( current_user_can('activate_plugins') ) {
        include ('wsx-mcpu-manage-customized-plugin-updates.php');
    } 
}

function wsx_mcpu_plugin_styles_scripts()
{
  wp_register_style('plugin-upgrade-css-styles1', plugin_dir_url(__FILE__) . 'css/style.css');
  wp_enqueue_script('plugin_upgrade_my_custom_script', plugin_dir_url(__FILE__) . 'js/wsx-plugin-manag.js');
  wp_enqueue_style('plugin-upgrade-css-styles1');
}
add_action('admin_enqueue_scripts', 'wsx_mcpu_plugin_styles_scripts');
function wsx_mcpu_disable_plugin_deactivation($actions, $plugin_file, $plugin_data, $context)
{
  // Remove edit link for all plugins
  if (array_key_exists('edit', $actions)) unset($actions['edit']);
  // Remove deactivate link for important plugins
  if (array_key_exists('deactivate', $actions) && in_array($plugin_file, array(
    'manage-customized-plugin-updates/manage-customized-plugin-updates.php'
  ))) unset($actions['deactivate']);
  return $actions;
} ?>
