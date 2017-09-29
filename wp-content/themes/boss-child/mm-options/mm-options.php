<?php



// Add "Mirror Muscles Options" link to the "Appearance" menu
function mm_menu_options() {
    // add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function);
     add_menu_page('Mirror Muscles Options', 'Mirror Muscles Options', 'manage_options', 'mm-settings', 'mm_admin_options_page');
}
// Load the Admin Options page
add_action('admin_menu', 'mm_menu_options');

function mm_options_enqueue_scripts($suffix) {
    if($suffix=='toplevel_page_mm-settings'){
        wp_enqueue_script('jquery');
        wp_register_script( 'mm-upload', get_stylesheet_directory_uri() .'/mm-options/js/mm-upload.js', array('jquery','media-upload','thickbox') );
        
            wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
            wp_enqueue_style( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css', false ); 
        


        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('mm-upload');
     }
}
add_action('admin_enqueue_scripts', 'mm_options_enqueue_scripts');






function mm_admin_options_page() {
    ?>
    <?php if(isset($_POST['message'])):?>
        <div class="wrap">
            <div id="message" class="updated notice is-dismissible"><p><?php echo $_POST['message'];?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
        </div>
    <?php endif;?>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Cron</a></li>
            <li><a href="#tabs-2">Spam</a></li>
            <li><a href="#tabs-3">Connections</a></li>
            <li><a href="#tabs-4">Reg/Index Page</a></li>
            <li><a href="#tabs-5">Photo Progress</a></li>
            <li><a href="#tabs-6">Share Apps</a></li>
            <li><a href="#tabs-7">Diaries</a></li>
            <li><a href="#tabs-8">1 Rep Max</a></li>
            <li><a href="#tabs-9">Frontpage</a></li>
            <li><a href="#tabs-10">Nutrition Guidelines</a></li>
            <li><a href="#tabs-11">FatSecret</a></li>
            <li><a href="#tabs-12">Fitbit</a></li>
            <li><a href="#tabs-13">Wger</a></li>
        </ul>
        <div id="tabs-1">
            <?php 
                $mm_cron_file_path = get_option("mm_cron_file_path");
                $cron_file_path = $mm_cron_file_path["mm_cron_file_path"];
                $cron_file_url = $mm_cron_file_path["mm_cron_file_url"];
                echo '<h2>Cron File Path</h2><p>'.$cron_file_path.'</p><br><hr>';
                echo '<h2>Cron File URL</h2><p>'.$cron_file_url.'</p><br><hr>';
            ?>
        </div>

        <div id="tabs-2">
            <div>
                <?php

                    if (isset($_POST["mm_spamer_options_update"])) {

                        $settings = array(
                          'mm_spamer_threshold'=>$_POST["mm_spamer_threshold"],
                          'mm_spamer_notification'=>stripslashes_deep($_POST["mm_spamer_notification"]),
                          'mm_notspamer_notification'=>stripslashes_deep($_POST["mm_notspamer_notification"])
                        );

                        update_option("mm_spamer_options", $settings);
                   }

                    $mm_spamer_options = get_option("mm_spamer_options");
                    $mm_spamer_threshold = intval($mm_spamer_options["mm_spamer_threshold"]);
                    $mm_spamer_notification = stripslashes_deep($mm_spamer_options["mm_spamer_notification"]);
                    $mm_notspamer_notification = stripslashes_deep($mm_spamer_options["mm_notspamer_notification"]);
                ?>
                <h2>Spamer Connections Request Threshold</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Spamer Connections Request Threshold options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_spamer_threshold">
                                    Request Threshold Number<br><hr>
                                    <small>after how many rejected "Connection Requests" user marked as spammer</small>
                                </label> 
                            </th>
                            <td>
                                <input type="number" min="1" step="1" name="mm_spamer_threshold" style="width:60%;padding:10px;" value="<?php echo $mm_spamer_threshold;?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="mm_spamer_notification">
                                    Notification for spam-marked user
                                </label> 
                            </th>
                            <td>
                                <textarea name="mm_spamer_notification" style="width:60%;padding:10px;" rows="5"><?php echo $mm_spamer_notification;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="mm_not_spamer_notification">
                                    Notification for not-spam-marked user
                                </label> 
                            </th>
                            <td>
                                <textarea name="mm_notspamer_notification" style="width:60%;padding:10px;" rows="5"><?php echo $mm_notspamer_notification;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_spamer_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-3">
            <div>
                <?php


                    if (isset($_POST["mm_connection_options_update"])) {

                        $settings = array(
                          'connection_request_add_trainer'=>stripslashes_deep($_POST["connection_request_add_trainer"]),
                          'connection_request_add_gym'=>stripslashes_deep($_POST["connection_request_add_gym"]),
                          'connection_request_add_client'=>stripslashes_deep($_POST["connection_request_add_client"]),
                          'connection_request_cancel'=>stripslashes_deep($_POST["connection_request_cancel"]),
                          'connection_request_accept'=>stripslashes_deep($_POST["connection_request_accept"]),
                          'connection_request_reject'=>stripslashes_deep($_POST["connection_request_reject"]),
                          'connection_request_refuse'=>stripslashes_deep($_POST["connection_request_refuse"]),
                        );

                        update_option("mm_connection_options", $settings);
                    }

                    $mm_connection_options = get_option("mm_connection_options");
                    $connection_request_add_trainer = stripslashes_deep($mm_connection_options["connection_request_add_trainer"]);
                    $connection_request_add_gym = stripslashes_deep($mm_connection_options["connection_request_add_gym"]);
                    $connection_request_add_client = stripslashes_deep($mm_connection_options["connection_request_add_client"]);
                    $connection_request_cancel = stripslashes_deep($mm_connection_options["connection_request_cancel"]);
                    $connection_request_accept = stripslashes_deep($mm_connection_options["connection_request_accept"]);
                    $connection_request_reject = stripslashes_deep($mm_connection_options["connection_request_reject"]);
                    $connection_request_refuse = stripslashes_deep($mm_connection_options["connection_request_refuse"]);

                ?>
                <h2>Connection Requests options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Connection Options Saved successfully">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_add_trainer">
                                    Request text for "Add to Trainers"
                                    <br><hr>
                                    <small>if "initiator" is Standart User/GYM</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_add_trainer" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_add_trainer;?></textarea>
                            
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_add_gym">
                                    Request text for "Add to GYM"
                                    <br><hr>
                                    <small>if "initiator" is Standart User/Trainer</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_add_gym" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_add_gym;?></textarea>
                            
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_add_client">
                                    Request text for "Add to Clients"
                                    <br><hr>
                                    <small>if "initiator" is Trainer/GYM</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_add_client" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_add_client;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_cancel">
                                    Cancel connection text<br><hr>
                                    <small>if "initiator" cancel his request</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_cancel" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_cancel;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_accept">
                                    Accept connection text<br><hr>
                                    <small>if "friend" accepts request</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_accept" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_accept;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_reject">
                                    Reject connection text<br><hr>
                                    <small>if "friend" rejects request</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_reject" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_reject;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="connection_request_refuse">
                                    Refuse connection text<br><hr>
                                    <small>if connected users refuses connection between each other</small>
                                </label> 
                            </th>
                            <td>
                                 <textarea name="connection_request_refuse" style="width:60%;padding:10px;" rows="3"><?php echo $connection_request_refuse;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_connection_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-4">
            <div>
                <?php

                    if (isset($_POST["mm_regpage_options_update"])) {

                        $settings = array(
                            'regpage_image'=>esc_attr($_POST["regpage_image"]),
                            'regpage_image_std'=>esc_attr($_POST["regpage_image_std"]),
                            'regpage_image_enc'=>esc_attr($_POST["regpage_image_enc"]),
                            'regpage_title_std'=>stripslashes_deep($_POST["regpage_title_std"]),
                            'regpage_title_enc'=>stripslashes_deep($_POST["regpage_title_enc"]),
                            'regpage_desc_std'=>stripslashes_deep($_POST["regpage_desc_std"]),
                            'regpage_desc_enc'=>stripslashes_deep($_POST["regpage_desc_enc"]),
                        );

                        update_option("mm_regpage_options", $settings);
                    }

                    if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );


                            $upload_overrides = array( 'test_form' => false );
                            $movefile1 = wp_handle_upload( $_FILES['regpage_image'], $upload_overrides );
                            $movefile2 = wp_handle_upload( $_FILES['regpage_image_std'], $upload_overrides );
                            $movefile3 = wp_handle_upload( $_FILES['regpage_image_enc'], $upload_overrides );
                            
                    $mm_regpage_options = get_option("mm_regpage_options");
                    $regpage_image = $mm_regpage_options["regpage_image"];
                    $regpage_image_std = $mm_regpage_options["regpage_image_std"];
                    $regpage_image_enc = $mm_regpage_options["regpage_image_enc"];
                    $regpage_title_std = stripslashes_deep($mm_regpage_options["regpage_title_std"]);
                    $regpage_title_enc = stripslashes_deep($mm_regpage_options["regpage_title_enc"]);
                    $regpage_desc_std = stripslashes_deep($mm_regpage_options["regpage_desc_std"]);
                    $regpage_desc_enc = stripslashes_deep($mm_regpage_options["regpage_desc_enc"]);
                ?>
                <h2>Reg/Index Page options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Reg/Index images Saved successfully">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="regpage_image">
                                    Registration page image
                                </label> 
                            </th>
                            <td>
                                <input id="regpage_image" class="image-upl" type="text" size="36" name="regpage_image" value="<?php echo $regpage_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="regpage_image_btn" type="button" value="Upload Image" />
                                <div id="regpage_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $regpage_image ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="regpage_image_std">
                                    Standard User background image
                                </label> 
                            </th>
                            <td>
                                <input id="regpage_image_std" class="image-upl" type="text" size="36" name="regpage_image_std" value="<?php echo $regpage_image_std;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="regpage_image_std_btn" type="button" value="Upload Image" />
                                <div id="regpage_image_std_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $regpage_image_std ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="regpage_image_enc">
                                    Enchanced User background image
                                </label> 
                            </th>
                            <td>
                                <input id="regpage_image_enc" class="image-upl" type="text" size="36" name="regpage_image_enc" value="<?php echo $regpage_image_enc;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="regpage_image_enc_btn" type="button" value="Upload Image" />
                                <div id="regpage_image_enc_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $regpage_image_enc ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="regpage_title_std">
                                    Text header for Standard User
                                </label> 
                            </th>
                            <td>
                                <input type="text" size="36" name="regpage_title_std" value="<?php echo $regpage_title_std;?>" style="width:60%;height:40px;padding:10px;" />
                            </td>
                        </tr>  
                        <tr>
                            <th scope="row">
                                <label for="regpage_desc_std">
                                    Text block for Standard User
                                </label> 
                            </th>
                            <td>
                                <textarea name="regpage_desc_std" style="width:60%;padding:10px;" rows="10"><?php echo $regpage_desc_std;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="mpi_help_text">
                                    Text header for Enchanced User
                                </label> 
                            </th>
                            <td>
                                <input id="regpage_title_enc" type="text" size="36" name="regpage_title_enc" value="<?php echo $regpage_title_enc;?>" style="width:60%;height:40px;padding:10px;" />
                            </td>
                        </tr>  
                        <tr>
                            <th scope="row">
                                <label for="mpi_help_text">
                                    Text block for Enchanced User
                                </label> 
                            </th>
                            <td>
                                <textarea name="regpage_desc_enc" style="width:60%;padding:10px;" rows="10"><?php echo $regpage_desc_enc;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_regpage_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-5">
            <div>
                <?php

                    if (isset($_POST["mpi_options_update"])) {

                        $settings = array(
                            'mpi_header_text'=>stripslashes_deep($_POST["mpi_header_text"]),
                            'mpi_help_text'=>stripslashes_deep($_POST["mpi_help_text"]),
                            'mpi_upload_days'=>intval($_POST["mpi_upload_days"]),
                            'mpi_share_photo_desc'=>stripslashes_deep($_POST["mpi_share_photo_desc"]),
                        );
                        

                        update_option("mpi_options", $settings);
                    }

                    $mpi_options = get_option("mpi_options");
                    $mpi_header_text = stripslashes_deep($mpi_options["mpi_header_text"]);
                    $mpi_help_text = stripslashes_deep($mpi_options["mpi_help_text"]);
                    $mpi_share_photo_desc = stripslashes_deep($mpi_options["mpi_share_photo_desc"]);
                    $mpi_upload_days = intval($mpi_options["mpi_upload_days"]);
                ?>
                <h2>Photo Progress options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="My Progress Images options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mpi_header_text">
                                    Photo Progress Header Text
                                </label> 
                            </th>
                            <td>
                                <textarea type="text" name="mpi_header_text" style="width:60%;padding:10px;" rows="10"/><?php echo $mpi_header_text;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mpi_upload_days">
                                    Photo Progress Upload Days Interval
                                </label> 
                            </th>
                            <td>
                                <input type="number" min="0" step="1" name="mpi_upload_days" value="<?php echo $mpi_upload_days;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mpi_help_text">
                                    Photo Progress help text
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mpi_help_text" value="<?php echo $mpi_help_text;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mpi_share_photo_desc">
                                    Progress photo sharing text
                                </label> 
                            </th>
                            <td>
                                 <textarea name="mpi_share_photo_desc" style="width:60%;padding:10px;" rows="10"><?php echo $mpi_share_photo_desc;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mpi_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-6">
            <div>
                <?php

                    if (isset($_POST["mmshare_options_update"])) {

                        $settings = array(
                          'mmshare_facebook_app'=>esc_attr($_POST["mmshare_facebook_app"]),
                          'mmshare_google_app'=>esc_attr($_POST["mmshare_google_app"])
                        );

                        update_option("mmshare_options", $settings);
                    }

                    $mmshare_options = get_option("mmshare_options");
                    $mmshare_facebook_app = $mmshare_options["mmshare_facebook_app"];
                    $mmshare_google_app = $mmshare_options["mmshare_google_app"];
                ?>
                <h2>My Progress Share options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="My Progress Share options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mmshare_facebook_app">
                                    Facebook app ID
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mmshare_facebook_app" value="<?php echo $mmshare_facebook_app;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mmshare_google_app">
                                    Google+ Client ID
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mmshare_google_app" value="<?php echo $mmshare_google_app;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mmshare_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-7">
            <div>
                <?php


                    if (isset($_POST["mm_foodsupp_diary_options_update"])) {

                        $settings = array(
                          'food_diary_image'=>esc_attr($_POST["food_diary_image"]),
                          'supplement_diary_image'=>esc_attr($_POST["supplement_diary_image"]),
                          'food_diary_desc'=>stripslashes_deep($_POST["food_diary_desc"]),
                          'supplement_diary_desc'=>stripslashes_deep($_POST["supplement_diary_desc"]),
						  'fatsecret_badge_img'=>esc_attr($_POST["fatsecret_badge_img"]),
                        );

                        update_option("mm_foodsupp_diary_options", $settings);
                    }

                    if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );


                            $upload_overrides = array( 'test_form' => false );

                            $movefile1 = wp_handle_upload( $_FILES['food_diary_image'], $upload_overrides );
                            $movefile2 = wp_handle_upload( $_FILES['supplement_diary_image'], $upload_overrides );
                            $movefile3 = wp_handle_upload( $_FILES['fatsecret_badge_img'], $upload_overrides );
                            
                    $mm_foodsupp_diary_options = get_option("mm_foodsupp_diary_options");
                    $food_diary_image = $mm_foodsupp_diary_options["food_diary_image"];
                    $supplement_diary_image = $mm_foodsupp_diary_options["supplement_diary_image"];
                    $fatsecret_badge_img = $mm_foodsupp_diary_options["fatsecret_badge_img"];
                    $food_diary_desc = stripslashes_deep($mm_foodsupp_diary_options["food_diary_desc"]);
                    $supplement_diary_desc = stripslashes_deep($mm_foodsupp_diary_options["supplement_diary_desc"]);
                ?>
                <h2>Food/Supplement Diary options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Food/Supplement Diary options saved successfully">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="food_diary_image">
                                    Food Diary logo
                                </label> 
                            </th>
                            <td>
                                <input id="food_diary_image" class="image-upl" type="text" size="36" name="food_diary_image" value="<?php echo $food_diary_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="food_diary_image_btn" type="button" value="Upload Image" />
                                <div id="food_diary_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $food_diary_image ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="food_diary_desc">
                                    Food Diary description
                                </label> 
                            </th>
                            <td>
                                <textarea name="food_diary_desc" style="width:60%;padding:10px;" rows="10"><?php echo $food_diary_desc;?></textarea>
                            </td>
                        </tr>  
						<tr>
                            <th scope="row">
                                <label for="food_diary_desc">
                                    Fatsecret Badge
                                </label> 
                            </th>
                            <td>
                                <input id="fatsecret_badge_img" class="image-upl" type="text" size="36" name="fatsecret_badge_img" value="<?php echo $fatsecret_badge_img;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="fatsecret_badge_btn" type="button" value="Upload Image" />
                                <div id="fatsecret_badge_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $fatsecret_badge_img ); ?>" />
                                </div>
                            </td>
                        </tr> 
                        <tr valign="top">
                            <th scope="row">
                                <label for="supplement_diary_image">
                                    Supplement Diary logo
                                </label> 
                            </th>
                            <td>
                                <input id="supplement_diary_image" class="image-upl" type="text" size="36" name="supplement_diary_image" value="<?php echo $supplement_diary_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="supplement_diary_image_btn" type="button" value="Upload Image" />
                                <div id="supplement_diary_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $supplement_diary_image ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="supplement_diary_desc">
                                    Supplement Diary description
                                </label> 
                            </th>
                            <td>
                                <textarea name="supplement_diary_desc" style="width:60%;padding:10px;" rows="10"><?php echo $supplement_diary_desc;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_foodsupp_diary_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-8">
            <div>
                <?php

                    if (isset($_POST["mm_onerepmax_options_update"])) {

                        $settings = array(
                            'onerepmax_intro_text'=>stripslashes_deep($_POST["onerepmax_intro_text"]),
                            'onerepmax_outro_text'=>stripslashes_deep($_POST["onerepmax_outro_text"]),
                        );
                        

                        update_option("mm_onerepmax_options", $settings);
                    }

                    $mm_onerepmax_options = get_option("mm_onerepmax_options");
                    $onerepmax_intro_text = stripslashes_deep($mm_onerepmax_options["onerepmax_intro_text"]);
                    $onerepmax_outro_text = stripslashes_deep($mm_onerepmax_options["onerepmax_outro_text"]);

                ?>
                <h2>1 Rep Max options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="1 Rep Max options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="onerepmax_intro_text">
                                    Top page text
                                </label> 
                            </th>
                            <td>
                                <textarea type="text" name="onerepmax_intro_text" style="width:60%;padding:10px;" rows="10"/><?php echo $onerepmax_intro_text;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="onerepmax_outro_text">
                                    Bottom page instruction text
                                </label> 
                            </th>
                            <td>
                                <textarea type="text" name="onerepmax_outro_text" style="width:60%;padding:10px;" rows="10"/><?php echo $onerepmax_outro_text;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_onerepmax_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-9">
            <div>
                <?php


                    if (isset($_POST["mm_frontpage_options_update"])) {

                        $settings = array(
                          'next_training_image'=>$_POST["next_training_image"],
                          'std_track_progress_desc'=>stripslashes_deep($_POST["std_track_progress_desc"]),
                          'enc_track_progress_desc'=>stripslashes_deep($_POST["enc_track_progress_desc"]),
                          'std_activity_desc'=>stripslashes_deep($_POST["std_activity_desc"]),
                          'std_getdiet_image'=>$_POST["std_getdiet_image"],
                          'std_getdiet_desc'=>stripslashes_deep($_POST["std_getdiet_desc"]),
                          'enc_getplans_image'=>$_POST["enc_getplans_image"],
                          'enc_getplans_desc'=>stripslashes_deep($_POST["enc_getplans_desc"]),
                          'std_techniques_image'=>$_POST["std_techniques_image"],
                          'std_techniques_desc'=>stripslashes_deep($_POST["std_techniques_desc"]),
                          'std_transformation_desc'=>stripslashes_deep($_POST["std_transformation_desc"]),
                          'enc_engage_desc'=>stripslashes_deep($_POST["enc_engage_desc"]),
                          'enc_manage_desc'=>stripslashes_deep($_POST["enc_manage_desc"]),

                        );

                        update_option("mm_frontpage_options", $settings);
                    }

                    if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );


                            $upload_overrides = array( 'test_form' => false );

                            $movefile1 = wp_handle_upload( $_FILES['next_training_image'], $upload_overrides );

                    $mm_frontpage_options = get_option("mm_frontpage_options");
                    
                    $next_training_image = $mm_frontpage_options["next_training_image"];
                    $std_getdiet_image = $mm_frontpage_options["std_getdiet_image"];
                    $enc_getplans_image = $mm_frontpage_options["enc_getplans_image"];
                    $std_techniques_image = $mm_frontpage_options["std_techniques_image"];
                    
                    $std_track_progress_desc = stripslashes_deep($mm_frontpage_options["std_track_progress_desc"]);
                    $enc_track_progress_desc = stripslashes_deep($mm_frontpage_options["enc_track_progress_desc"]);
                    $std_activity_desc = stripslashes_deep($mm_frontpage_options["std_activity_desc"]);
                    $std_getdiet_desc = stripslashes_deep($mm_frontpage_options["std_getdiet_desc"]);
                    $enc_getplans_desc = stripslashes_deep($mm_frontpage_options["enc_getplans_desc"]);
                    $std_techniques_desc =  stripslashes_deep($mm_frontpage_options["std_techniques_desc"]);
                    $std_transformation_desc =  stripslashes_deep($mm_frontpage_options["std_transformation_desc"]);
                    $enc_engage_desc = stripslashes_deep($mm_frontpage_options["enc_engage_desc"]);
                    $enc_manage_desc = stripslashes_deep($mm_frontpage_options["enc_manage_desc"]);
                ?>
                <h2>Frontpage options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Frontpage options saved successfully">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="next_training_image">
                                    Next Training Session background
                                </label> 
                            </th>
                            <td>
                                <input id="next_training_image" class="image-upl" type="text" size="36" name="next_training_image" value="<?php echo $next_training_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="next_training_image_btn" type="button" value="Upload Image" />
                                <div id="next_training_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $next_training_image ); ?>" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="std_track_progress_desc">
                                    Standard User Track Your Progress description
                                </label> 
                            </th>
                            <td>
                                <textarea name="std_track_progress_desc" style="width:60%;padding:10px;" rows="10"><?php echo $std_track_progress_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enc_track_progress_desc">
                                    Standard User Friends / Trainer / GYM latest updates description
                                </label> 
                            </th>
                            <td>
                                <textarea name="std_activity_desc" style="width:60%;padding:10px;" rows="10"><?php echo $std_activity_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="std_getdiet_image">
                                    Standard User Get a Diet background
                                </label> 
                            </th>
                            <td>
                                <input id="std_getdiet_image" class="image-upl" type="text" size="36" name="std_getdiet_image" value="<?php echo $std_getdiet_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="std_getdiet_image_btn" type="button" value="Upload Image" />
                                <div id="std_getdiet_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $std_getdiet_image ); ?>" />
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <th scope="row">
                                <label for="enc_track_progress_desc">
                                     Standard User Get a Diet description
                                </label> 
                            </th>
                            <td>
                                <textarea name="std_getdiet_desc" style="width:60%;padding:10px;" rows="10"><?php echo $std_getdiet_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="std_techniques_image">
                                    Standard User Training Techniques background
                                </label> 
                            </th>
                            <td>
                                <input id="std_techniques_image" class="image-upl" type="text" size="36" name="std_techniques_image" value="<?php echo $std_techniques_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="std_techniques_image_btn" type="button" value="Upload Image" />
                                <div id="std_techniques_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $std_techniques_image ); ?>" />
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <th scope="row">
                                <label for="std_techniques_desc">
                                     Standard User Training Techniques description
                                </label> 
                            </th>
                            <td>
                                <textarea name="std_techniques_desc" style="width:60%;padding:10px;" rows="10"><?php echo $std_techniques_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="std_techniques_desc">
                                     Standard User Biggest Transformation description
                                </label> 
                            </th>
                            <td>
                                <textarea name="std_transformation_desc" style="width:60%;padding:10px;" rows="10"><?php echo $std_transformation_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enc_track_progress_desc">
                                    Enchanced User Track Your Progress description
                                </label> 
                            </th>
                            <td>
                                <textarea name="enc_track_progress_desc" style="width:60%;padding:10px;" rows="10"><?php echo $enc_track_progress_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enc_getplans_image">
                                    Enchanced User Get Nutrition and Training Plans background
                                </label> 
                            </th>
                            <td>
                                <input id="enc_getplans_image" class="image-upl" type="text" size="36" name="enc_getplans_image" value="<?php echo $enc_getplans_image;?>" style="width:60%;height:40px;padding:10px;" />
                                <input id="enc_getplans_image_btn" type="button" value="Upload Image" />
                                <div id="enc_getplans_image_preview" style="min-height: 100px; max-width: 250px;">
                                    <img style="max-width:100%;" src="<?php echo esc_url( $enc_getplans_image ); ?>" />
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <th scope="row">
                                <label for="enc_getplans_desc">
                                    Enchanced User Get Nutrition and Training Plans description
                                </label> 
                            </th>
                            <td>
                                <textarea name="enc_getplans_desc" style="width:60%;padding:10px;" rows="10"><?php echo $enc_getplans_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enc_engage_desc">
                                    Enchanced User Engage with your Clients description
                                </label> 
                            </th>
                            <td>
                                <textarea name="enc_engage_desc" style="width:60%;padding:10px;" rows="10"><?php echo $enc_engage_desc;?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enc_manage_desc">
                                    Enchanced User Manage your Clients description
                                </label> 
                            </th>
                            <td>
                                <textarea name="enc_manage_desc" style="width:60%;padding:10px;" rows="10"><?php echo $enc_manage_desc;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_frontpage_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-10">
            <div>
                <?php

                    if (isset($_POST["mm_nutrition_guidelines_options_update"])) {

                        $settings = array(
                          'mm_nutritions_macronutrient_desc'=>stripslashes_deep($_POST["mm_nutritions_macronutrient_desc"]),
                          'mm_nutritions_keto_desc'=>stripslashes_deep($_POST["mm_nutritions_keto_desc"]),
                          'mm_nutritions_iifym_desc'=>stripslashes_deep($_POST["mm_nutritions_iifym_desc"]),
                          'mm_nutritions_zonediet_desc'=>stripslashes_deep($_POST["mm_nutritions_zonediet_desc"]),
                        );

                        update_option("mm_nutrition_guidelines_options", $settings);
                    }

                    $mm_nutrition_guidelines_options = get_option("mm_nutrition_guidelines_options");
                    $mm_nutritions_macronutrient = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_macronutrient_desc"]);
                    $mm_nutritions_keto = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_keto_desc"]);
                    $mm_nutritions_iifym = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_iifym_desc"]);
                    $mm_nutritions_zonediet = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_zonediet_desc"]);
                ?>
                <h2>NutritionGuidelines options</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Nutrition Guidelines options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_nutritions_macronutrient_desc">
                                    Macronutrien calculator description
                                </label> 
                            </th>
                            <td>
                            	<textarea name="mm_nutritions_macronutrient_desc" style="width:60%;padding:10px;" rows="10"><?php echo $mm_nutritions_macronutrient;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_nutritions_keto_desc">
                                    Keto calculator description
                                </label> 
                            </th>
                            <td>
                                <textarea name="mm_nutritions_keto_desc" style="width:60%;padding:10px;" rows="10"><?php echo $mm_nutritions_keto;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_iifym_macronutrient_desc">
                                    IIFYM calculator description
                                </label> 
                            </th>
                            <td>
                                <textarea name="mm_nutritions_iifym_desc" style="width:60%;padding:10px;" rows="10"><?php echo $mm_nutritions_iifym;?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_nutritions_zonediet_desc">
                                    Zonediet calculator description
                                </label> 
                            </th>
                            <td>
                                <textarea name="mm_nutritions_zonediet_desc" style="width:60%;padding:10px;" rows="10"><?php echo $mm_nutritions_zonediet;?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_nutrition_guidelines_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-11">
            <div>
                <?php

                    if (isset($_POST["mm_fatsecret_options_update"])) {

                        $settings = array(
                            'mm_fatsecret_consumer_key'=>$_POST["mm_fatsecret_consumer_key"],
                            'mm_fatsecret_shared_secret'=>$_POST["mm_fatsecret_shared_secret"],
                        );
                        

                        update_option("mm_fatsecret_options", $settings);
                    }

                    $mm_fatsecret_options = get_option("mm_fatsecret_options");
                    $mm_fatsecret_consumer_key = $mm_fatsecret_options["mm_fatsecret_consumer_key"];
                    $mm_fatsecret_shared_secret = $mm_fatsecret_options["mm_fatsecret_shared_secret"];
                ?>
                <h2>FatSecret API keys</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Fatsecret options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_fatsecret_consumer_key">
                                    REST API Consumer Key
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_fatsecret_consumer_key" value="<?php echo $mm_fatsecret_consumer_key;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_fatsecret_shared_secret">
                                    REST API Shared Secret
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_fatsecret_shared_secret" value="<?php echo $mm_fatsecret_shared_secret;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_fatsecret_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-12">
            <div>
                <?php

                    if (isset($_POST["mm_fitbit_options_update"])) {

                        $settings = array(
                            'mm_fitbit_client_id'=>$_POST["mm_fitbit_client_id"],
                            'mm_fitbit_consumer_key'=>$_POST["mm_fitbit_consumer_key"],
                            'mm_fitbit_consumer_secret'=>$_POST["mm_fitbit_consumer_secret"],
                        );
                        

                        update_option("mm_fitbit_options", $settings);
                    }

                    $mm_fitbit_options = get_option("mm_fitbit_options");
                    $mm_fitbit_client_id = $mm_fitbit_options["mm_fitbit_client_id"];
                    $mm_fitbit_consumer_key = $mm_fitbit_options["mm_fitbit_consumer_key"];
                    $mm_fitbit_consumer_secret = $mm_fitbit_options["mm_fitbit_consumer_secret"];
                ?>
                <h2>Fitbit API keys</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Fitbit options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_fitbit_client_id">
                                    OAuth 2.0 Client ID
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_fitbit_client_id" value="<?php echo $mm_fitbit_client_id;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_fitbit_consumer_key">
                                    Client(Consumer) Key
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_fitbit_consumer_key" value="<?php echo $mm_fitbit_consumer_key;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_fitbit_consumer_secret">
                                    Client(Consumer) Secret
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_fitbit_consumer_secret" value="<?php echo $mm_fitbit_consumer_secret;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_fitbit_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

        <div id="tabs-13">
            <div>
                <?php

                    if (isset($_POST["mm_wger_options_update"])) {
                        $settings = array(
                            'mm_wger_apikey'=>$_POST["mm_wger_apikey"]
                        );
                        update_option("mm_wger_options", $settings);
                    }

                    $mm_wger_options = get_option("mm_wger_options");
                    $mm_wger_apikey = $mm_wger_options["mm_wger_apikey"];
                ?>
                <h2>Wger API keys</h2>
                <form method="POST" action="">
                    <input type="hidden" name="message" value="Wger options Saved">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="mm_wger_apikey">
                                    Wger API key
                                </label> 
                            </th>
                            <td>
                                <input type="text" name="mm_wger_apikey" value="<?php echo $mm_wger_apikey;?>" style="width:60%;height:40px;padding:10px;"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="mm_wger_options_update" value="Y" />
                    <p><input type="submit" value="Save" class="button-primary"/></p>
                </form>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        jQuery(function() {
            jQuery( "#tabs" ).tabs({
                select: function(event, ui) {                   
                    window.location.replace(ui.tab.hash);
                },
            });
        });
    </script>

    <?php  
}

function mm_options_setup() {
    global $pagenow;
 
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
        add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
    }
}
add_action( 'admin_init', 'mm_options_setup' );


function replace_thickbox_text($translated_text, $text, $domain) {
    if ('Insert into Post' == $text) {
        $referer = strpos( wp_get_referer(), 'mm-settings' );
        if ( $referer != '' ) {
            return __('Add image', 'mm' );
        }
    }
    return $translated_text;
}

function delete_image( $image_url ) {
    global $wpdb;
 
    // We need to get the image's meta ID.
    $query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";
    $results = $wpdb->get_results($query);
 
    // And delete it
    foreach ( $results as $row ) {
        wp_delete_attachment( $row->ID );
    }
}


?>