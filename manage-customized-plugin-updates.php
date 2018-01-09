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


function wsx_mcpu_plugin_redirect() {
    if (get_option('wsx_mcpu_plugin_do_activation_redirect', false)) {
        delete_option('wsx_mcpu_plugin_do_activation_redirect');
        wp_redirect(get_admin_url(null, 'options-general.php?page=manage-customized-plugin-updates%2Fadmin%2Fwsx-plugin-interface.php'));
    }
}

/* Add admin notice */
add_action('admin_notices', 'wsx_mcpu_active_admin_notice');
/**
 * Admin Notice on Activation.
 */
function wsx_mcpu_active_admin_notice()
{
    /* Check transient, if available display notice */
    if (get_transient('wsx-mcpu-active-admin-notice'))
    {
?>
        <div class="updated notice is-dismissible">
        <?php
        echo '<p>';
        echo __('Thank you for using the <strong>Manage Customized Plugin Updates</strong> plugin! Go to Plugin ', 'manage-customized-plugin-updates');
        echo '</p>';
?>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient('wsx-mcpu-active-admin-notice');
    }
}
register_activation_hook(__FILE__, "wsx_mcpu_plugin_activate");
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 Upon deleting the plugin all the plugin tables will be removed from the database.
 */
function wsx_mcpu_plugin_deactivate()
{
}
register_deactivation_hook(__FILE__, "wsx_mcpu_plugin_deactivate");
register_uninstall_hook('uninstall.php', 'wsx_mcpu_plugin_uninstall');
// Settings link on the plugins page
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'wsx_mcpu_plugin_settings_link');
function wsx_mcpu_plugin_settings_link($wsx_mcpu_link)
{
    $wsx_mcpu_link[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=manage-customized-plugin-updates%2Fadmin%2Fwsx-plugin-interface.php')) . '">' . __('Settings', 'wsx_mcpu_plugin_messages') . '</a>';
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