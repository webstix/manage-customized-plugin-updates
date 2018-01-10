<?php
/*
* Plugin Name: Manage Customized Plugin Updates
* Description: Plugin that can help you better manage customized plugins and display a message to your clients warning them about doing the upgrade.
* Plugin URI: https://www.webstix.com/wordpress-plugin-development
* Author: Webstix
* Author URI: https://www.webstix.com/
* Version: 1.0
* Text Domain: manage-customized-plugin-updates
* Domain Path: /languages
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/
if ( ! defined( 'ABSPATH' ) ) exit;

function wsx_mcpu_plugin_activate()
{
    /* Create transient data */
    set_transient('wsx-mcpu-active-admin-notice', true, 5);
    include ('admin/wsx-plugin-tables.php');
    add_option('wsx_mcpu_plugin_do_activation_redirect', true);
}
add_action('admin_init', 'wsx_mcpu_plugin_redirect');

// After activating the plugin, redirects to the plugin's settings page.
function wsx_mcpu_plugin_redirect() {
    if (get_option('wsx_mcpu_plugin_do_activation_redirect', false)) {
        delete_option('wsx_mcpu_plugin_do_activation_redirect');
        wp_redirect(esc_url(get_admin_url(null, 'options-general.php?page=manage-customized-plugin-updates-master%2Fadmin%2Fwsx-plugin-interface.php')));
    }
}

register_activation_hook(__FILE__, "wsx_mcpu_plugin_activate");


// Settings link on the plugins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'wsx_mcpu_plugin_settings_link');
function wsx_mcpu_plugin_settings_link($wsx_mcpu_link)
{
    $wsx_mcpu_link[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=manage-customized-plugin-updates-master%2Fadmin%2Fwsx-plugin-interface.php')) . '">' . __('Settings', 'wsx_mcpu_plugin_messages') . '</a>';
    return $wsx_mcpu_link;
}
include ('admin/wsx-plugin-interface.php');

// enqueues our external font awesome stylesheet
function wsx_mcpu_enqueue_our_required_stylesheets()
{
    wp_enqueue_style('plugin-upgrade-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'wsx_mcpu_enqueue_our_required_stylesheets');

function wsx_mcpu_get_plugin_status($location = '')
{
    if (is_plugin_active($location))
    {
        return "Active";
    }
    if (!file_exists(trailingslashit(WP_PLUGIN_DIR) . $location))
    {
        return "Uninstalled";
    }
    if (is_plugin_inactive($location))
    {
        return "Inactive";
    }
} ?>
