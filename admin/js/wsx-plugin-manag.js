jQuery(document).ready(function() {

        // Core updates page - start

        // ==================
    jQuery('form.upgrade #update-plugins-table .check-column input').each(function() {
        var check_b_val = jQuery(this).val();
        jQuery(this).attr('data-plugin', check_b_val);
    });

    jQuery(".wsx_pl_upd_core_cont input").each(function(){
        var plg_val = jQuery(this).val();
        
        jQuery('form.upgrade #update-plugins-table .check-column input[data-plugin="'+plg_val+'"]').next('label').remove();

        jQuery('form.upgrade #update-plugins-table .check-column input[data-plugin="'+plg_val+'"]').remove();

    });

    // Core updates page - end

    jQuery('tr.plugin-update-tr.wsx_plugin_note').next('tr.plugin-update-tr').addClass('site_def_upd');

    jQuery('tr.plugin-update-tr.wsx_plugin_note').prev('tr').addClass('wsx-orig-plug-info');

    jQuery('tr.wsx-orig-plug-info.update th input[type="checkbox"]').remove();
    jQuery('tr.wsx-orig-plug-info.update th label').remove();

    jQuery('tr[data-slug="webstix-plugin-management"] th input[type="checkbox"]').remove();
    jQuery('tr[data-slug="webstix-plugin-management"] th label').remove();

    jQuery("tr.site_def_upd a.update-link").each(function() {
        var pl_up_link = jQuery(this).attr('href');
        var pl_up_link = '<input type="hidden" class="wsx_temp_upd_link" value="' + pl_up_link + '" />';
        jQuery(this).closest('.site_def_upd').prev('.wsx_plugin_note').append(pl_up_link);
    });


    jQuery('tr.site_def_upd .notice-warning a.update-link').addClass('wsx_pl_upd_btn').removeClass('update-link');

    jQuery('tr.site_def_upd .notice-warning a.wsx_pl_upd_btn').removeAttr('href');

    jQuery('tr.site_def_upd .notice-warning a.wsx_pl_upd_btn').click(function() {
        jQuery(this).parent().parent().parent().parent().prev('.wsx_plugin_note').addClass('wsx_open_container');
        var temp_url = jQuery('.wsx_open_container .wsx_temp_upd_link').val();
        
        // Confirm update the plugin.   
        jQuery('.wsx_open_container .wsx_plug_page_alert_note_container .wsx_pop_upd_btn').attr('href', temp_url).click(function() {
            var update_plugin_own = confirm('Are you sure you want to update the customized plugin? All the customization will get lost if you proceed with "Ok". Click "Cancel" to skip the upgrade.');
            if (update_plugin_own == true) {
                return true;
            } else {
                jQuery('.wsx_open_container .wsx_plug_note_overlay').hide();
                return false;
            }
        });
        jQuery('.wsx_open_container .wsx_plug_note_overlay').show();
        jQuery('.wsx_open_container .wsx_plug_page_alert_note_container').show();
    });

    jQuery('a.wsx_close_pop').click(function() {
        jQuery('.wsx_open_container .wsx_plug_note_overlay').hide();
        jQuery('.wsx_open_container .wsx_plug_page_alert_note_container').show();
        jQuery('tr.wsx_open_container').removeClass('wsx_open_container');
        jQuery('.wsx_plug_page_alert_form_container.active_form').hide();
        jQuery('.wsx_plug_page_alert_form_container.active_form').removeClass('active_form');
    });

    jQuery("a.wsx_pop_cnt_btn").click(function() {
        jQuery('.wsx_plug_page_alert_note_container').hide();
        jQuery(this).parent().parent().next('.wsx_plug_page_alert_form_container').addClass('active_form');
        jQuery('.wsx_plug_page_alert_form_container.active_form').show();
    });


    // Admin dashboard pop-up starts here
    jQuery("a.mcpu-cpn-plugin").click(function() {
        jQuery(this).next().show();
        jQuery(this).next().find(".mcpu-plugin-desc-wrap").show();
    });

    jQuery("a.wsx_mcpu_close_pop").click(function() {
        jQuery(this).parent().hide();
        jQuery(this).parent().parent().hide();
    });
    // Admin dashboard pop-up ends here

    jQuery(".plugins-list-wrap.white-shadow").children("form").each(function() {
        jQuery(this).find('tr td input[type="checkbox"]').on('change', function() {
            jQuery(this).parent().parent().toggleClass("checkbox-active");
            jQuery(this).parent().parent().next().toggleClass('open');

            if (jQuery(this).parent().parent().hasClass("checkbox-active")) {
                jQuery(this).parent().parent().next().addClass('open');
            }
        });
    });
});
