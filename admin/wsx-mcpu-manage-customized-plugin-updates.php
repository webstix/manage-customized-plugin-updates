<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $wpdb, $wsx_db_status;
$wsx_customized_plugins = "";
$wsx_customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';

if (isset($_POST['submit-pm']))
{
  if(empty( $_REQUEST['mcpu_form_submit_nonce'] ) && ! wp_verify_nonce( $_REQUEST['mcpu_form_submit_nonce'], 'mcpu_plugins_list_form' ) ) {
    wp_die( __( 'Action failed. Please refresh the page and retry.', 'manage-customized-plugin-updates' ) );
  } else {
    // Process here the form with proper sanitize inputs.
    $now = new DateTime();
    $current_date_time = $now->format('Y-m-d H:i:s');
    include_once(ABSPATH . 'wp-includes/pluggable.php');

    $user_query = new WP_User_Query(array(
      'role' => 'Administrator'
    ));
    if (!empty($user_query->results))
    {
      foreach($user_query->results as $user)
      {
        $current_user_id = get_current_user_id();
        if ($user->ID == $current_user_id)
        {
          $get_current_user_name = $user->user_login;
        }
      }
    }
    for ($i = 0; $i < $_POST['total_count']; $i++)
    {
      if (isset($_POST['wsx_pl_msg_list_' . $i]))
      {

        $customized_plugins_entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wsx_customized_plugins." WHERE `plugin_code` ='%s'", sanitize_text_field($_POST['wsx_pl_code_' . $i]) ));
        if (count($customized_plugins_entries) > 0)
        {
          $checkbox_status = "Disable";
          if (isset($_POST['wsx_pl_msg_list_' . $i])) 
          {
            $checkbox_status = "Enable";
          }
         
          $wpdb->query( $wpdb->prepare("UPDATE ".$wsx_customized_plugins." SET `customization_details`='%s', plugin_status='%s', `mc_current_status` ='%s' WHERE `plugin_name` = '%s'", sanitize_textarea_field($_POST['plugin_description_' . $i]), sanitize_text_field($_POST['wsx_plugin_current_status_' . $i]), sanitize_text_field($checkbox_status), sanitize_text_field($_POST['wsx_pl_names_' . $i]) ));
        } else
        {
          $wpdb->query( $wpdb->prepare("INSERT INTO `$wsx_customized_plugins` (`ID`, `plugin_name`, `plugin_code`, `customization_details`, `plugin_status`, `mc_current_status`, `plugin_deleted`, `plugin_marked_by`, `plugin_marked_on`) values (%d, %s, %s, %s, %s, %s, %d, %s, %s)", "", sanitize_text_field($_POST['wsx_pl_names_' . $i]), sanitize_text_field($_POST['wsx_pl_code_' . $i]), sanitize_textarea_field($_POST['plugin_description_' . $i]), sanitize_text_field($_POST['wsx_plugin_current_status_' . $i]), sanitize_text_field("Enable"), sanitize_text_field(0), sanitize_text_field($get_current_user_name), sanitize_text_field($current_date_time) ));
        }
      } else
      {
        $checkbox_status = "Disable";
        
      $customized_plugins_entries = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wsx_customized_plugins . " WHERE plugin_code ='%s'", sanitize_text_field($_POST['wsx_pl_code_' . $i]) ));


        if (count($customized_plugins_entries) > 0)
        {
          $wpdb->query( $wpdb->prepare("UPDATE ".$wsx_customized_plugins." SET customization_details='%s', plugin_status='%s', mc_current_status ='%s' WHERE `plugin_name` = '%s'", sanitize_textarea_field($_POST['plugin_description_' . $i]), sanitize_text_field($_POST['wsx_plugin_current_status_' . $i]), sanitize_text_field($checkbox_status), sanitize_text_field($_POST['wsx_pl_names_' . $i]) ));
        } // End of update part
      } // End of Insert/Update loop.
    } // End of for loop
  } // End of nonce else part.
} // End of form submit
function wsx_mcpu_plugin_messages()
{
  echo '<div class="wsx-plugin-management">';
  echo '<div class="wsx-block-plugin-messages">';
  echo "<h2><span class=\"wsx-mark-as-customized\"></span><span class=\"wsx-mark-as-customized-text\">" . __('Manage Customized Plugin Updates', 'manage-customized-plugin-updates') . "</span><span class=\"wsx-txt-small\">Plugin by <a rel=\"nofollow\" target=\"_blank\" href=\"https://www.webstix.com\"><img src=\"https://www.webstix.com/images/icon_webstix.gif\" title=\"Webstix\" alt=\"webstix\" class=\"webstix\"></a></span></h2><hr/>";
  echo '</div>';
  echo '</div>';
  // Show the "Changes saved successfully" alert note when someone saves something.
  if (isset($_POST['save-changes-message']))
  {
    echo '<div class="updated" id="message"><p>' . __('Changes saved successfully', 'manage-customized-plugin-updates') . '</p></div>';
  }
  if (!function_exists('get_plugins'))
  {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php');

  }

  $all_plugins_1 = get_plugins();
  echo '<div class="wsx-plugin-list">' . __('Select the plugin(s) to add a customization message to:', 'manage-customized-plugin-updates') . '</div>';
  echo '<div class="plugins-list-wrap white-shadow">';
  $wsx_count_id = 0;
  $wsx_db_status = 0;
  $checked_label = "";
  echo '<form class="form_plug_message" method="POST" action="">';
  echo '<input name="save-changes-message" type="hidden" value="1" />'; ?>

  <input type="submit" name="submit-pm" class="button-primary top-btn" value="<?php
  echo __('Save Changes', 'manage-customized-plugin-updates'); ?>" />

  <?php echo '<table class="widefat"><tbody>';
  foreach($all_plugins_1 as $key => $val)
  {

    $plugin_current_status = wsx_mcpu_get_plugin_status($key);
    global $wpdb;
    
    $customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
    $plugin_entries = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " .$customized_plugins. " WHERE `plugin_name` = '%s'", sanitize_text_field($val['Name']) ));

    if ($wpdb->num_rows > 0)
    {
      $plugin_description = $plugin_entries[0]->customization_details;
      $customized_plugin_current_status = $plugin_entries[0]->mc_current_status;
      $wsx_db_status = 1;
      $checked_label = 'checked="checked"';
      if ($customized_plugin_current_status == "Enable")
      {
        $checked_label = 'checked="checked"';
      }
      if ($customized_plugin_current_status == "Disable")
      {
        $checked_label = "";
      }
    }
    else
    {
      $checked_label = "";
      $wsx_db_status = 0;
      $plugin_description = "";
    }
  
    if($val['Name'] == "Manage Customized Plugin Updates") {

    } else {

      echo '<tr class="tr_plug_msg_names"><td>';?>
      <input type="checkbox" <?php
      if ($checked_label <> "")
      {
        echo $checked_label;
      } ?> id="wsx_pl_msgs_<?php
      echo $wsx_count_id; ?>" name="<?php
      echo "wsx_pl_msg_list_" . $wsx_count_id; ?>">
      
      <label for="<?php
      echo "wsx_pl_msgs_" . $wsx_count_id; ?>"><?php
      echo $val['Name']; ?></label>

      <?php echo "</td></tr>"; ?>  

      <tr <?php
      if ($checked_label <> "")
      {
        echo 'class="wsx-plg-message open"';
      }
      else
      {
        echo 'class="wsx-plg-message"';
      }
?>>
    <td>
    <?php
    _e('<p><strong>' . __('Customization Message', 'manage-customized-plugin-updates') . ':</strong></p>');
    $wsx_plugin_current_url = get_home_url(); ?>

    <textarea rows="5" cols="80" name="<?php
    echo "plugin_description_" . $wsx_count_id; ?>" placeholder="<?php
    echo __('Message that will be displayed.', 'manage-customized-plugin-updates'); ?>"><?php
    echo esc_attr(strip_tags($plugin_description)); ?></textarea><br/><br/>

    <input type="hidden" name="<?php
    echo "wsx_plugin_id_" . $wsx_count_id; ?>" value="<?php
    echo $wsx_count_id; ?>" /> 

    <input type="hidden" name="<?php
    echo "wsx_plugin_current_status_" . $wsx_count_id; ?>" value="<?php
    echo esc_attr($plugin_current_status); ?>" />

    <input type="hidden" name="<?php
    echo "wsx_pl_code_" . $wsx_count_id; ?>" value="<?php
    echo esc_attr($key); ?>" />

    <input type="hidden" name="<?php
    echo "wsx_db_status_" . $wsx_count_id; ?>" value="<?php
    echo $wsx_db_status; ?>" />

    <input type="hidden" name="<?php
    echo "wsx_pl_names_" . $wsx_count_id; ?>" value="<?php
    echo esc_attr($val['Name']); ?>" />

  <?php } // End of else part.
    $wsx_count_id++;
  } // End of foreach
  echo '</td></tr></tbody></table>'; ?>

  <input type="hidden" name="total_count" value="<?php
  echo $wsx_count_id; ?>" />

  <?php // Use nonce to secure you from from Cross Site Request Forgery (CSRF)
  wp_nonce_field( 'mcpu_plugins_list_form', 'mcpu_form_submit_nonce' );
   ?>

  <input type="submit" name="submit-pm" class="button-primary btn-btm" value="<?php
  echo __('Save Changes', 'manage-customized-plugin-updates'); ?>" />

  <?php
  echo '</form></div>';
} // End of function - wsx_mcpu_plugin_messages.

if (!function_exists('get_plugins'))
{
  require_once(ABSPATH . 'wp-admin/includes/plugin.php');

}
$wsx_all_plugins = get_plugins();
if ($wsx_all_plugins > 0)
{
  foreach($wsx_all_plugins as $key => $val)
  {
    $plugin_file = $key;
    add_action("after_plugin_row_" . $plugin_file, 'wsx_mcpu_action_after_plugin_row', 10, 3);
  }
}

function wsx_mcpu_action_after_plugin_row($plugin_name)
{
  global $wpdb;

  $prefix = $wpdb->base_prefix;

  $alert = "";
  $wsx_show_hide_alert = "";
  $wsx_home_url = get_home_url();

  $customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
  $plugins_marked_as_customized = $plugin_name;

  $plugins_marked_entries = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $customized_plugins . " WHERE `plugin_code` = '%s' AND `mc_current_status` = '%s'", sanitize_text_field($plugins_marked_as_customized), sanitize_text_field("Enable") ));

  if (count($plugins_marked_entries) > 0)
  {
    foreach($plugins_marked_entries as $marked_entries)
    {
      $pop_up = $marked_entries->customization_details;
      $marked_plugin_name = $marked_entries->plugin_name;
            
      $content = '<tr class="plugin-update-tr wsx_plugin_note" data-plugin="' . $plugins_marked_as_customized . '"><td colspan="3" class="plugin-update"><div class="update-message">';
      
      $content.= '<p class="cblockcircle-wrap"><span class="cblockcircle" title="Please do not update this plugin"><span class="cblock" title="Please do not update this plugin">' . __('Customized') . '</span></span></p>';

      $content.= '<input type="hidden" class="wsx_hid_plug_upd_link" value=""/>';

      if($pop_up <> "") { $mcpu_plgn_desc = nl2br($pop_up); } else { $mcpu_plgn_desc = "No description available."; }
      
      $content.= '<div class="wsx_plug_note_overlay"> <div class="wsx_plug_page_alert_note"><a class="wsx_close_pop" title="Close" href="javascript:void(0)">×</a><div class="wsx_plug_page_alert_note_container"><h3 class="txt-center">Customization Details</h3><p>' . $mcpu_plgn_desc . '</p>';
     
      $content.= '<a class="wsx_pop_upd_btn" href="#">' . __('Update at your own risk!', 'manage-customized-plugin-updates') . '</a>';
     
      $content.= '<input type="hidden" name="wsx_hid_plg_name_plug_upd" class="wsx_hid_plg_name_plug_upd" value="' . $marked_plugin_name . '">';
     
      $content.= '</div><div class="wsx_plug_page_alert_form_container" style="display:none;">';
     
      $content.= '<p><label>' . __('Plugin Name', 'manage-customized-plugin-updates') . ': </label><br />' . $marked_plugin_name;

      $content.= '<input type="hidden" name="wsx_plug_id" class="wsx_plug_id" value="' . $marked_entries->ID . '">';
      $content.= '<input type="hidden" name="wsx_rand_id" class="wsx_rand_id" value="' . $rand_id . '"></p>';

      // Subject Field
      $content.= '<p><label>' . __('Subject (Optional)', 'manage-customized-plugin-updates') . ': </label><br /><input type="text" name="email_subj" id="email_subj" placeholder="Enter your message about doing a plugin upgrade."></p><p><label>' . __('Message (Optional)', 'manage-customized-plugin-updates') . ':</label><br /><textarea rows="4" cols="50" class="email_cont" placeholder="Enter additional information about this plugin upgrade."></textarea></p><p><input class="hid_plug_pos" type="hidden" value="' . sanitize_text_field($plugins_marked_as_customized). '"><input class="send_email" type="button" value="Send Upgrade Request"></p><div class="message_status"></div></div></div></div>';

      $content.= '</td></tr>';

      echo $content;
    } // End of foreach
  } // End of if
} // End of function - wsx_mcpu_action_after_plugin_row

// add content on the update core page
add_action('core_upgrade_preamble', 'wsx_mcpu_custom_upgrade_core_message');
function wsx_mcpu_custom_upgrade_core_message()
{
  global $wpdb;
  echo "<div class='wsx_pl_upd_core_cont'>";
  $wsx_customized_plugins = "";
  $wsx_customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
  
  $plugins_customized_entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM " .$wsx_customized_plugins));

  foreach($plugins_customized_entries as $plugin_customized_entries)
  {
    $wsx_customized_plugin_code = sanitize_text_field($plugin_customized_entries->plugin_code);
    echo "<input type='hidden' class='wsx_pl_upd_core_plg' value='" . $wsx_customized_plugin_code . "' />";
  }
  echo "</div>";
}

// Initiate the admin Dashboard widget:
function wsx_mcpu_custom_register_widgets()
{
  global $wp_meta_boxes;
  wp_add_dashboard_widget('wsx_mcpu_custom_plugins', __($wsx_company_name . ' Customized Plugin(s) List', 'wsx_rss_feeds') , 'wsx_mcpu_custom_plugins_box');
}
add_action('wp_dashboard_setup', 'wsx_mcpu_custom_register_widgets');


// Show the Customized plugin(s) list on the Dashboard widget:
function wsx_mcpu_custom_plugins_box()
{
  global $wpdb;
  $prefix = $wpdb->base_prefix;
 
  $customized_plugins = $wpdb->prefix . 'mcpu_customized_plugins';
  $plugins_marked_entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$customized_plugins." WHERE `mc_current_status` = '%s'", sanitize_text_field("Enable") ));

  if (count($plugins_marked_entries) > 0)
  {
    echo '<div class="mcpu-head-wrap"><span> S.No</span><span>Plugin Name</span><span>Customization Details</span></div>';

    echo '<ol class="wsx-mcpu-admin-list">';

    foreach($plugins_marked_entries as $marked_entries)
    {
      $marked_plugin_name = $marked_entries->plugin_name;
      $customization_details = $marked_entries->customization_details;

        echo '<li><div class="mcpu-plugin-details"><span>'.$marked_plugin_name.'</span>';
        
        echo '<a class="mcpu-cpn-plugin" href="#">View Details</a>';

        echo '<div class="wsx_plug_note_overlay_wrap">';
        echo '<div class="mcpu-plugin-desc-wrap"><a class="wsx_mcpu_close_pop" title="Close" href="javascript:void(0)">×</a>';
        
        if($customization_details <> "")
        { 
          echo '<div class="mcpu-plugin-desc"><h3 class="txt-center">Customization Details</h3><p>'.nl2br($customization_details).'</p></div>';
        } else {
          echo '<div class="mcpu-plugin-desc"><h3 class="txt-center">Customization Details</h3><p>No description available.</p></div>';
        }  
        echo '</div></div>';

        echo '</div></li>';
      
    }
    echo '</ol>';
    $wsx_mcpu_plugin_settings_url = esc_url(get_admin_url(null, 'options-general.php?page=manage-customized-plugin-updates-master%2Fadmin%2Fwsx-plugin-interface.php'));
    echo '<a class="wsx_mcpu_read_more" href="'.$wsx_mcpu_plugin_settings_url.'">' . __('Go to Plugin settings', 'manage-customized-plugin-updates') . '</a>';
  } else {
      echo "<h3>No plugin(s) customized yet.</h3>";
  }
} 
// Initiate the admin Dashboard widget ends here:
?>
