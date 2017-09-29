<?php

/**
 *
 *
 * @package cometchat
 */
    //include_once(ABSPATH.'wp-admin/includes/plugin.php');
    global $wp_roles;
    $roles = array_keys($wp_roles->get_names());

    $siteUrl = get_site_url();
    $admin = $siteUrl."/cometchat/admin/";
?>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ).'ccstyle.css';?>" >
    <script type="text/javascript" src = "<?php echo plugin_dir_url( __FILE__ ).'ccscript.js';?>"></script>

    <div class="tabs">
        <ul class="tab-links" id = "submenu">
            <li data-rel="cc_adminpanel" class="active menus"><a href="#cc_adminpanel">CometChat Administration Panel</a></li>
            <li data-rel="cc_settings" class="menus"><a href="#cc_settings">Additional Settings</a></li>
            <li data-rel="cc_upgrade" class="menus"><a href="#cc_upgrade">Upgrade</a></li>
        </ul>

        <div class="tab-content">
            <div id="cc_adminpanel" class="tab active">
                <iframe id="admin_iframe" src="<?php echo $admin; ?>" style=" width:100%;height:400px;px;border: 0; position: relative;"  scrolling="yes" onload= "cc_iFrameSize();"></iframe>
            </div>

            <div id="cc_settings" class="tab">
                <table cellspacing="1" style="margin-top:20px;">
                    <tr style="margin-top:20px;">
                        <td width="550" style="padding-top: 20px;">
                           Hide CometChat for which usergroups?
                        </td>
                        <td valign="top" style="padding-top: 20px;">
                        <?php
                            foreach ($roles as $key => $value) {
                        ?>
                                <input type = "checkbox" class = "test" value = <?php echo $value; ?> <?php if(get_option($value) === 'true') echo 'checked="checked"';?> /><label>&nbsp;<?php echo $value; ?></label><br />
                        <?php
                            }
                        ?>
                        </td>
                    </tr>


                    <tr style="margin-top:20px;">
                        <td width="550" style="padding-top: 20px;">
                            Enable Inbox synchronization (with BuddyPress Messages)
                        </td>
                        <td valign="top" style="padding-top: 20px;">
                            <input type = "checkbox" class = "sync" value = "inbox_sync" <?php if(get_option('inbox_sync') === 'true') echo 'checked="checked"';?> />
                        <td>
                    </tr>
                    <tr style="margin-top:20px;">
                        <td width="550" style="padding-top: 20px;">
                            Hide CometChat bar
                        </td>
                        <td valign="top" style="padding-top: 20px;">
                            <input type = "checkbox" class = "hide" value = "hide_bar" name="hide_bar" <?php if(get_option('hide_bar') === 'true') echo 'checked="checked"';?> /> Yes
                        <td>
                    </tr>
                    <tr>

                        <td style="padding-top: 20px;">
                            <button type="submit" value = "submit" id = "save" class = "button-primary">Save Settings</button>
                        </td>
                    </tr>
                </table>
                <div id = "success" class = "successmsg"></div>
            </div>

            <table id="cc_upgrade" class="tab">
                <div class="upload-plugin">
                    <tr>
                        <p class="install-help"><?php _e('Please select "cometchat.zip" which you have downloaded from our site and click "Install" to proceed.'); ?></p>
                    </tr>
                    <tr>
                        <td>
                            <form method="post" enctype="multipart/form-data" class="wp-upload-form" action = "admin.php?page=cometchat/cometchatinstall.php">
                                <label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
                                <input type="file" id="pluginzip" name="pluginzip" />
                                <?php submit_button('Install Now' , 'button', 'install-plugin-update', false ); ?>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><?php _e('You can download the latest version of '); ?><i><?php _e('cometchat.zip');?></i><?php _e(' from ');?><a href="http://www.cometchat.com" target="_blank">our site</a><?php _e('. You will need to purchase a CometChat license if you haven\'t already. Feel free to email us at '); ?><a href="mailto:sales@cometchat.com" target="_blank">sales@cometchat.com</a><?php _e(' if you have any questions.'); ?></p>
                        </td>
                    </tr>
                </div>
            </table>
        </div>
    </div>