<?php
if (!defined('PROREVS_ROOT')) {
    return;
}
if (session_id() == '')
    session_start();
require_once(ABSPATH . 'wp-admin/admin-functions.php');
global $wpdb, $blog_id;

function prorevs_add_review($user_id, $component, $type, $action, $content, $primary_link, $item_id, $secondary_item_id, $date_recorded, $hide_sitewide, $mptt_left, $mptt_right, $star, $usercheck, $anonymous, $anonoymous_userid, $is_activated) {
    global $wpdb;
    $result = $wpdb->query($wpdb->prepare(
                    "
            INSERT INTO {$wpdb->prefix}bp_activity
            ( user_id, component, type ,action, content, primary_link,
              item_id, secondary_item_id, date_recorded, hide_sitewide,
              mptt_left, mptt_right, star, usercheck, anonymous,logged_out,is_activated)
            VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d)
        ", array(
                $user_id,
                $component,
                $type,
                $action,
                $content,
                $primary_link,
                $item_id,
                $secondary_item_id,
                $date_recorded,
                $hide_sitewide,
                $mptt_left,
                $mptt_right,
                $star,
                $usercheck,
                $anonymous,
                $anonoymous_userid,
                $is_activated
                    )
    ));
    $activityID = $wpdb->get_var('SELECT MAX(`id`) FROM ' . $wpdb->prefix . 'bp_activity');
    return $activityID;
}

function save_activity_meta($activityID, $metaName, $metaValue) {
    global $wpdb;
    $result = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}bp_activity_meta(`activity_id`, `meta_key`, `meta_value`) VALUES (%d,%s,%s)", array($activityID, $metaName, $metaValue)));
}

$options = get_option('reviews_options');

if ($options['profile'] == "profile" ) {
    add_action('bp_after_member_header', 'prorevs_add_star_loop_header');
    add_action('bp_directory_members_actions', 'prorevs_add_star_loop_content');

    function prorevs_add_star_loop_header() {
        return prorevs_add_star_loop(2);
    }

    function prorevs_add_star_loop_content() {
        return prorevs_add_star_loop(1);
    }

    function prorevs_add_star_loop($checkitem) {
        global $wpdb;
        $unconfirmed = ')';
        if (bp_is_user()) {
            $current_user_id = bp_displayed_user_id();
        } else {
            $current_user_id = bp_get_member_user_id();
        }
        
        // Check
        $options = get_option('reviews_options');
        $allow_for_values = array();
        $reviews_allowed = 0;
        if(is_array($options['allow_for'])) {
            $allow_for_values = $options['allow_for'];
        }
        $displayed_user = get_userdata( $current_user_id );
        if($displayed_user) {
            $reviews_allowed = count(array_intersect($displayed_user->roles,$allow_for_values));
        }

        if($reviews_allowed > 0 || ($reviews_allowed == 0 && !isset($options['hide_reviews']))) {
            if (current_user_can('administrator')):
                if ($checkitem == 1) {
                    $check_content_loop = $wpdb->get_results("SELECT AVG(star) AS Average FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and (is_activated is null or is_activated=1)");
                    $check_content_loop_count = $wpdb->get_col("SELECT star FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "'  and (is_activated is null or is_activated=1)");
                } else {
                    $check_content_loop = $wpdb->get_results("SELECT AVG(star) AS Average FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and (is_activated is null or is_activated=1)");
                    $check_content_loop_count = $wpdb->get_col("SELECT star FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "'  and (is_activated is null or is_activated=1)");
                }
                $check_content_loop_count_2 = $wpdb->get_col("SELECT star FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and (is_activated=0)");
                $unconfirmed = ', <span class="unconfirmed">' . count($check_content_loop_count_2) . ' unconfirmed</span>)</span>';

            else:
                $check_content_loop = $wpdb->get_results("SELECT AVG(star) AS Average FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and (is_activated is null or is_activated=1)");
                $check_content_loop_count = $wpdb->get_col("SELECT star FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and (is_activated is null or is_activated=1)");
            endif;
            if ($check_content_loop[0]->Average != "") {
                $check_show_star_loop = $check_content_loop[0]->Average;
                $demss = 0;
                echo '<span class="rating-top"> ';
                for ($dem = 1; $dem < 6; $dem++) {
                    if ($dem <= $check_show_star_loop) {
                        echo '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                    } else {
                        $demss++;
                        if (ceil($check_show_star_loop) - $check_show_star_loop > 0 and $demss == 1) {
                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star_half.png">';
                        } else {
                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                        }
                    }
                }
                echo ' (' . __('Based on', 'bpreviews') . ' ' . count($check_content_loop_count) . ' ' . __('reviews', 'bpreviews') . $unconfirmed;
            } else {
                echo '<span class="rating-top" style="font-weight:bold">' . __('No Reviews', 'bpreviews') . '</span>';
            }
        }
        // End check
    }

    function prorevs_member_header() {
        if ($GLOBALS['bp']->current_component == "reviews") {
            return false;
        } else {
            $options = get_option('reviews_options');
            $allow_for_values = array();
            $reviews_allowed = 0;
            if(is_array($options['allow_for'])) {
                $allow_for_values = $options['allow_for'];
            }
            $current_user_id =  bp_displayed_user_id();
            $displayed_user = get_userdata( $current_user_id );
            if($displayed_user) {
                $reviews_allowed = count(array_intersect($displayed_user->roles,$allow_for_values));
            }

            if($reviews_allowed > 0 || ($reviews_allowed == 0 && !$options['hide_reviews'])) {
            ?>
            <div class="generic-button">
                <a title="<?= __('Add reviews for this user', 'bpreviews') ?>"
                   href="<?php echo bp_get_displayed_user_link() ?>reviews/"><?= __('Add Review', 'bpreviews') ?></a>
            </div>
            <?php
            }
        }
    }

    add_action('bp_member_header_actions', 'prorevs_member_header');

//
// Add the "reviews" tab
//


    function prorevs_profile_nav() {
    
        $options = get_option('reviews_options');
        $allow_for_values = array();
        $reviews_allowed = 0;
        if(is_array($options['allow_for'])) {
            $allow_for_values = $options['allow_for'];
        }
        $current_user_id =  bp_displayed_user_id();
        $displayed_user = get_userdata( $current_user_id );
        if($displayed_user) {
            $reviews_allowed = count(array_intersect($displayed_user->roles,$allow_for_values));
        }

        if($reviews_allowed > 0 || ($reviews_allowed == 0 && !isset($options['hide_reviews']))) {
            function prorevs_reviews_tab() {

                function prorevs_reviews_tab_title() {
                    echo 'Reviews';
                }

                function prorevs_reviews_tab_content() {
                    require(PROREVS_ROOT . '/css/customstylemembertwo.php');
                    require(PROREVS_ROOT . '/includes/postreviewform.php');
                }

                add_action('bp_template_title', 'prorevs_reviews_tab_title');
                add_action('bp_template_content', 'prorevs_reviews_tab_content');
                bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
            }

            bp_core_new_nav_item(array(
                'name' => 'Reviews',
                'slug' => 'reviews',
                'screen_function' => 'prorevs_reviews_tab',
                'position' => 40,
                'default_subnav_slug' => 'reviews',
                'item_css_id' => 'prorevs-reviews-tab'
            ));
        }
    }
    
    add_action('bp_setup_nav', 'prorevs_profile_nav');


//
// Process and add a review
//

    function prorevs_review_limit_exceeded(&$options, $user_id, $usercheck) {
        global $wpdb;

        if ($options['limit'] == 0)
            return false;
        $wpdb->show_errors();
        $n_reviews = $wpdb->get_var(
                $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}bp_activity WHERE user_id = %d AND usercheck = %d", array($user_id, $usercheck))
        );

        return $n_reviews >= $options['limit'];
    }

    function check_email($email) {
        global $wpdb;
        echo 'SELECT id FROM' . $wpdb->prefix . 'bp_activity_meta' . ' WHERE meta_key = "user_email" and meta_value="' . $email . '"';
        $result = $wpdb->get_var('SELECT id FROM ' . $wpdb->prefix . 'bp_activity_meta' . ' WHERE meta_key = "user_email" and meta_value="' . $email . '"');
        return $result;
    }

    if (isset($_POST['review_member_submit'])) {
        if (!is_user_logged_in()) {
            $captcha_correct = false;
            $email_exist = false;

            if (isset($_SESSION['phrase']) && ($_SESSION['phrase'] == $_POST['captcha_code'])) {
                $captcha_correct = true;
            }
            if ($captcha_correct && ($email_exist === false)):
                $current_user = get_blog_option($thisblog, 'admin_email');
                $user_reviewd = $_POST['rating_member_id'];
                $user_reviewd_name = $_POST['rating_member_name'];
                $avartar_reviewd = "";
                $link_set = get_bloginfo('home') . "/members/" . $current_user->user_login;
                if (!prorevs_review_limit_exceeded($options, $current_user->ID, $user_reviewd)) {
                    if (isset($_POST['review_member_content']) and $_POST['review_member_content'] != "") {
                        $rating_member = $_POST['rating_member'];
                        $contentss .= '<span class="ratingtop">';
                        for ($dem = 1; $dem < 6; $dem++) {
                            if ($dem <= $rating_member) {
                                $contentss .= '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                            } else {
                                $contentss .= '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                            }
                        }
                        $contentss.='</span>';
                        $user_id = $current_user->ID;
                        $component = "Members";
                        $type = "Member_review";
                        $action = "<a href='" . $link_set . "' title='" . $current_user->user_login . "'>" . $current_user->user_login . "</a> " . __('posted an Review ', 'bpreviews') . $avartar_reviewd . " <a href='" . get_bloginfo('home') . "/members/" . $user_reviewd_name . "'>" . $user_reviewd_name . "</a>";
                        $content = $contentss . htmlspecialchars($_POST['review_member_content']);
                        $primary_link = $link_set;
                        $item_id = "";
                        $secondary_item_id = "";
                        $date_recorded = date('Y-m-d H:i:s ');
                        $hide_sitewide = 0;
                        $mptt_left = 0;
                        $mptt_right = 0;
                        $star = $rating_member;
                        $usercheck = $user_reviewd;
                        $anonymous = 1;

                        $activityID = prorevs_add_review(1, $component, $type, $action, $content, $primary_link, $item_id, $secondary_item_id, $date_recorded, $hide_sitewide, $mptt_left, $mptt_right, $star, $usercheck, $anonymous, 1, 0);
                        save_activity_meta($activityID, 'user_name', $_POST['user_name']);
                        save_activity_meta($activityID, 'user_email', $_POST['user_email']);
                        save_activity_meta($activityID, 'reviewedUserUrl', get_bloginfo('home') . "/members/" . $user_reviewd_name . '/reviews/');
                        $setcheckoption = $user_id . "-" . $usercheck;
                        $checkfirst = get_option($setcheckoption);
                        if ($checkfirst) {
                            update_option($setcheckoption, $checkfirst + 1);
                        } else {
                            add_option($setcheckoption, 1, '', 'yes');
                        }
                        if (isset($options['admin_approval']) && ($options['admin_approval'] == '1')) {
                            add_action('template_notices', 'prorevs_add_title_here_success_loggedout');
                            $sitename = strtolower($_SERVER['SERVER_NAME']);
                            $adminEmail = "wordpress@$sitename";
                            $blogName = get_bloginfo();
                            $confirmationLink = get_site_url() . '?cn_source=email&reviewID=' . $activityID . '&chash=' . base64_encode(md5('reviewConfirmation' . $activityID));
                            $confirmationLink = '<a style="width:220px;margin:0px auto;background:#007fb8;text-align:center;background:linear-gradient(to bottom,#007fb8 1%,#6ebad5 3%,#007fb8 7%,#007fb8 100%);border:#004b91 solid 1px;padding:8px 0 6px;text-decoration:none;border-radius:2px;display:block" href="' . $confirmationLink . '" target="_blank">
                                        <span style="color:#fff">'.__('CONFIRM REVIEW ', 'bpreviews').'</span>
                                    </a>';
                            $userString1 = __('Thank you for submitting a review on','bpreviews');
                            $userString2 = __('Before your review is posted and approved, you must click the link below to confirm this review','bpreviews');
                            $userString3 = __('Thank you','bpreviews');
                            $userMessage = <<<EOF
                                    <font style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:300;line-height:20px;text-align:left;font-size:14px;color:#333;padding:13px 0 0 0">{$_POST['user_name']}</font>,<br/><br/>
                                    <font style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:300;line-height:20px;text-align:left;font-size:14px;color:#333;padding:13px 0 0 0">{$userString1} {$blogName}. $userString2</font><br/><br/>
                                    $confirmationLink<br/><br/>
                                    {$userString3},<br/>
                                    {$blogName}   
EOF;

                            $headers = "From:  < $adminEmail >" . "\r\n";
                            $subject = __('Review confirmation mail from ', 'bpreviews') . $blogName . "\r\n";
                            add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
                            wp_mail($_POST['user_email'], $subject, $userMessage, $headers);
                        } else {
                            add_action('template_notices', 'prorevs_add_title_here_success');
                            $result = $wpdb->query("UPDATE {$wpdb->prefix}bp_activity SET `is_activated`=1 WHERE `id`=" . esc_sql($activityID));
                        }
                    } else {
                        add_action('template_notices', 'prorevs_add_title_here_error_no_content');
                    }
                } else {
                    add_action('template_notices', 'prorevs_add_title_here_error_limit');
                }
            else:
                if ($email_exist === true)
                    add_action('template_notices', 'prorevs_email_exist');

                if ($captcha_correct === false)
                    add_action('template_notices', 'prorevs_incorrect_captcha');
            endif;
        }else {
            $current_user = wp_get_current_user();
            $user_reviewd = $_POST['rating_member_id'];
            $user_reviewd_name = $_POST['rating_member_name'];
            $avartar_reviewd = "";
            $link_set = get_bloginfo('home') . "/members/" . $current_user->user_login;

            if (!prorevs_review_limit_exceeded($options, $current_user->ID, $user_reviewd)) {
                if (isset($_POST['review_member_content']) and $_POST['review_member_content'] != "") {
                    add_action('template_notices', 'prorevs_add_title_here_success');
                    $rating_member = $_POST['rating_member'];
                    $contentss .= '<span class="ratingtop">';
                    for ($dem = 1; $dem < 6; $dem++) {
                        if ($dem <= $rating_member) {
                            $contentss .= '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                        } else {
                            $contentss .= '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                        }
                    }
                    $contentss.='</span>';
                    $user_id = $current_user->ID;
                    $component = "Members";
                    $type = "Member_review";
                    $action = "<a href='" . $link_set . "' title='" . $current_user->user_login . "'>" . $current_user->user_login . "</a> posted an Review " . $avartar_reviewd . " <a href='" . get_bloginfo('home') . "/members/" . $user_reviewd_name . "'>" . $user_reviewd_name . "</a>";
                    $content = $contentss . htmlspecialchars($_POST['review_member_content']);
                    $primary_link = $link_set;
                    $item_id = "";
                    $secondary_item_id = "";
                    $date_recorded = date('Y-m-d H:i:s ');
                    $hide_sitewide = 0;
                    $mptt_left = 0;
                    $mptt_right = 0;
                    $star = $rating_member;
                    $usercheck = $user_reviewd;
                    $anonymous = ($options['anonymous'] && isset($_POST['anonymous']) && $_POST['anonymous'] ? 1 : 0);

                    prorevs_add_review($user_id, $component, $type, $action, $content, $primary_link, $item_id, $secondary_item_id, $date_recorded, $hide_sitewide, $mptt_left, $mptt_right, $star, $usercheck, $anonymous, 0, 1);

                    $setcheckoption = $user_id . "-" . $usercheck;
                    $checkfirst = get_option($setcheckoption);
                    if ($checkfirst) {
                        update_option($setcheckoption, $checkfirst + 1);
                    } else {
                        add_option($setcheckoption, 1, '', 'yes');
                    }
                } else {
                    add_action('template_notices', 'prorevs_add_title_here_error_no_content');
                }
            } else {
                add_action('template_notices', 'prorevs_add_title_here_error_limit');
            }
        }
    }

    function set_html_content_type() {

        return 'text/html';
    }

    function prorevs_add_title_here_success() {
        echo '
            <div id="message" class="updated">
                <p>'. __('Your review was posted successfully!', 'bpreviews').'</p>
            </div>
        ';
    }

    function prorevs_add_title_here_success_loggedout() {
        echo '
            <div id="message" class="updated">
                <p>'. __('Your review was posted successfully. Please confirm your review by email.', 'bpreviews').'</p>
            </div>
        ';
    }

    function prorevs_add_title_here_error_no_content() {
        echo '
            <div id="message" class="error" style="display: block;">
                <p>'. __('Please enter some content to post.', 'bpreviews').'</p>
            </div>
        ';
    }

    function prorevs_add_title_here_error_limit() {
        $options = get_option('reviews_options');
        echo '
            <div id="message" class="error" style="display: block;">
                <p>'. __('You can\'t post more than ', 'bpreviews'). $options['limit'] .  __(' review(s) for a single user. ', 'bpreviews').'</p>
            </div>
        ';
    }

    function prorevs_incorrect_captcha() {
        $options = get_option('reviews_options');
        echo '
            <div id="message" class="error" style="display: block;">
                <p>'.__('Please enter correct capcha', 'bpreviews').'</p>
            </div>
        ';
    }

    function prorevs_email_exist() {
        $options = get_option('reviews_options');
        echo '
            <div id="message" class="error" style="display: block;">
                <p>'.__('Please use another email address', 'bpreviews').'</p>
            </div>
        ';
    }

}

add_action('init', 'parseReviewConfirmation');

function parseReviewConfirmation() {
    global $wpdb, $blog_id;
    $options = get_option('reviews_options');
    if (isset($_GET['cn_source']) && isset($_GET['reviewID']) && isset($_GET['chash'])):
        $newHash = base64_encode(md5('reviewConfirmation' . $_GET['reviewID']));
        if ($_GET['chash'] == $newHash):
            $result = $wpdb->query("UPDATE {$wpdb->prefix}bp_activity SET `is_activated`=1 WHERE `id`=" . esc_sql($_GET['reviewID']));
            if ($result):
                $activityResult = $wpdb->get_results("SELECT meta_key,meta_value FROM {$wpdb->prefix}bp_activity_meta WHERE `activity_id`=" . esc_sql($_GET['reviewID']) . ' order by id', ARRAY_A);
                $moderateURL = $activityResult[2]['meta_value'];
                if (isset($options['approval_emails']) && (trim($options['approval_emails']) != '') && (($options['admin_approval'] == '1'))):
                    $siteURL = get_site_url();
                    $blogName = get_bloginfo();
                    $sitename = strtolower($_SERVER['SERVER_NAME']);
                    $adminEmail = "wordpress@$sitename";
                    $emailstring1 = __('A new review has been posted live on','bpreviews');
                    $emailstring2  = __('this review was confirmed by','bpreviews');
                    $emailstring3  = __('You can review it and moderate it at','bpreviews');
                    $adminMessage = <<<EOF
                                    <font style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:300;line-height:20px;text-align:left;font-size:14px;color:#333;padding:13px 0 0 0">{$emailstring1} {$siteURL}.($emailstring2} {$activityResult[0]['meta_value']}'s email {$activityResult[1]['meta_value']}</font><br/><br/>
                                    <font style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:300;line-height:20px;text-align:left;font-size:14px;color:#333;padding:13px 0 0 0">{$emailstring3}:</font><br/><br/>
                                    $moderateURL
EOF;
                    $headers = "From:  < $adminEmail >" . "\r\n";
                    $subject = __('New review submitted on ', 'bpreviews') . $blogName . "\r\n";
                    add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
                    wp_mail(explode(',', $options['approval_emails']), $subject, $adminMessage, $headers);
                    $_SESSION['review_status'] = 'confirmed';
                endif;
                ?>
                <script type="text/javascript">
                    window.location = "<?= $moderateURL; ?>";
                </script>
                <?php
            endif;
        endif;
    endif;
}

add_action('init', 'approveReview');

function approveReview() {
    global $wpdb;
    if (isset($_GET['approveID']) && ($_GET['approveID'] != '')):
        $approveID = base64_decode(str_rot13($_GET['approveID']));
        $result = $wpdb->query("UPDATE {$wpdb->prefix}bp_activity SET `is_activated`=1 WHERE `id`=" . esc_sql($approveID));
        if ($result):
            $activityResult = $wpdb->get_results("SELECT meta_key,meta_value FROM {$wpdb->prefix}bp_activity_meta WHERE `activity_id`=" . esc_sql($approveID), ARRAY_A);
            $moderateURL = $activityResult[2]['meta_value'];
            $_SESSION['review_status'] = 'activated';
            ?>
            <script type="text/javascript">
                window.location = "<?= $moderateURL; ?>";
            </script>
            <?php
        endif;
    endif;
}

