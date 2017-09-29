<?php
if (session_id() == '')
    session_start();
include(PROREVS_ROOT . '/captcha-master/CaptchaBuilderInterface.php');
include(PROREVS_ROOT . '/captcha-master/PhraseBuilderInterface.php');
include(PROREVS_ROOT . '/captcha-master/CaptchaBuilder.php');
include(PROREVS_ROOT . '/captcha-master/PhraseBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

require_once(ABSPATH . 'wp-admin/admin-functions.php');
global $wpdb;
$current_user = wp_get_current_user();

function curPageURLreviews() {
    $pageURL = 'http';
    if (false) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if (false) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

if (isset($_SESSION['review_status']) && ($_SESSION['review_status'] == 'confirmed')):
    add_action('wp_footer', 'review_confirmation_notice');
    unset($_SESSION['review_status']);
endif;
if (isset($_SESSION['review_status']) && ($_SESSION['review_status'] == 'activated')):
    add_action('wp_footer', 'review_activation_notice');
    unset($_SESSION['review_status']);
endif;
if (current_user_can('administrator')):

    $check_exit = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "'");

    $tmp = $wpdb->get_results("SELECT id, content, star FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "'");
else:
    $check_exit = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");

    $tmp = $wpdb->get_results("SELECT id, content, star FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");
endif;
$check_content = array();
$check_content_ids = array();
foreach ($tmp as $row) {
    $check_content_ids[] = $row->id;
    $check_content[$row->id] = $row;
}
if (current_user_can('administrator'))
    $check_content_loop = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE  type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "'");
else
    $check_content_loop = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE  type = 'Member_review' and  usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");
$options = get_option('reviews_options');
$can_post_review = true;
$allow_for_values = array();
if(is_array($options['allow_for'])) {
    $allow_for_values = $options['allow_for'];
}
$displayed_user = get_userdata( bp_displayed_user_id() );
// Check if reviews are allowed on this user
if(count(array_intersect($displayed_user->roles,$allow_for_values)) > 0) {
    if (is_user_logged_in() || ($options['non_logged_approve'] == '1')) {
        $check_total_set = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' ");
        
        if ($options['limit'] == 0) {
            $show_check = true;
        } else {
            $show_check = false;
        }
        $check_show = (bp_displayed_user_id() == $current_user->ID and $options['Prevent'] == "Prevent");

        if ($check_show) {
            echo "<p style='padding-bottom:5px'>".__('You can\'t review yourself','bpreviews')."</p>";
        } else if(is_user_logged_in() && count(array_intersect($current_user->roles,$options['allow_by'])) == 0) {
            $can_post_review = false;
        ?>
            <?php if(!$options['hide_allow_by']) { ?>
            <div id="message" class="info">
                <p><?php printf(__('Your membership role is not allowed to post reviews', 'bpreviews')); ?></p>
            </div>
            <?php } ?>
        <?php
        } else {
            ?>
            <form class="review-member-form whats-new-form-member" name="whats-new-form" id="whats-new-form " method="post" action="">
                <?php
                if (is_user_logged_in()):
                    ?>
                    <div id="whats-new-avatar">
                        <a href="<?php echo get_bloginfo('home') ?>/members/<?php echo $current_user->user_login ?>/">
                            <?php echo get_avatar($current_user->ID, 60, $default, $current_user->display_name) ?>
                        </a>
                    </div>
                    <?php
                endif;
                ?>
                <?php
                if ((count($check_exit) != 0) && (is_user_logged_in())) {
                    if ($options['limit'] == 0) {
                        $maxss = 10000;
                    } else {
                        $maxss = $options['limit'];
                    }

                    $args = array(
                        'user_id' => $check_exit[0]->user_id,
                        'action' => 'Member_review',
                        'in' => $check_content_ids,
                        'sort' => 'ASC',
                        'max' => $maxss
                    );
                    if (bp_has_activities($args)) {
                        while (bp_activities()) {
                            bp_the_activity();
                            $demsss = bp_get_activity_id();
                            ?>
                            <div class="already-rated">
                                <h5><?php printf(__("You rated %s ",'bpreviews'), bp_core_get_username(bp_displayed_user_id())); ?></h5>
                        <!--                            <style type="text/css">
                                    #activity-<?php // echo $check_exit[0]->id          ?> .hidencheck { display: none; }
                                </style>-->
                                <blockquote style="padding-bottom: 20px">
                                    <style type="text/css">.already-rated p .ratingtop { display: none; }</style>
                                    <p><?php echo $check_content[$demsss]->content ?></p>
                                    <div class="rest-stars">
                                        <span class="ratingtop" style="float:right">
                                            <?php
                                            for ($dem = 1; $dem < 6; $dem++) {
                                                if ($dem <= $check_content[$demsss]->star) {
                                                    echo '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                                                } else {
                                                    echo '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                                                }
                                            }
                                            ?>
                                        </span><br>
                                        <p style="float:right;margin-top:5px;"><?php bp_activity_delete_link(); ?></p>
                                    </div>
                                </blockquote>
                            </div>
                            <?php
                        }
                    }
                }
                $showForm = true;
                if ((count($check_exit) < $options['limit'] or $options['limit'] == 0)) {
                    ?>
                    <h5><?= __('What are your thoughts on','bpreviews') ?> <?php echo bp_core_get_username(bp_displayed_user_id()) ?>, <?php echo $current_user->display_name ?>?</h5>

                    <div id="whats-new-content">
                        <div id="whats-new-textarea">
                            <div>
                                <textarea value="" id="whats-new" name="review_member_content" style="display: inline-block; height: 50px;"></textarea>
                            </div>
                        </div>

                        <div id="review-rating" >
                            Rate it: <img src="<?php echo DEPROURL ?>/images/star_off.png" class="star" id="star1">
                            <img src="<?php echo DEPROURL ?>/images/star_off.png" class="star" id="star2">
                            <img src="<?php echo DEPROURL ?>/images/star_off.png" class="star" id="star3">
                            <img src="<?php echo DEPROURL ?>/images/star_off.png" class="star" id="star4">
                            <img src="<?php echo DEPROURL ?>/images/star_off.png" class="star" id="star5">
                        </div>
                        <div class="clear">&nbsp;</div>
                        <div class="clear">&nbsp;</div>
                        <?php
                        if (!is_user_logged_in()):
                            ?>
                            <div class="user-details">
                                <p><label><?= __('Name','bpreviews') ?>  <span class="required">*</span>:&nbsp;</label><input required type="text" name="user_name"></p>
                                <p><label><?= __('Email','bpreviews') ?>  <span class="required">*</span>:&nbsp;</label><input type="email" name="user_email" required></p>
                                <?php
                                $builder = new CaptchaBuilder();
                                $builder->build();
                                $capcha_img = time() . '.jpg';
                                if (!is_dir('temp'))
                                    @mkdir('temp');
                                else {
                                    $files = glob('temp/'. "*");
                                    $time = time();
                                    foreach ($files as $file)
                                        if (is_file($file))
                                            if ($time - filemtime($file) >= 60 * 60 * 24) // 2 days
                                                unlink($file);
                                }
                                $builder->save('temp/' . $capcha_img);
                                $_SESSION['phrase'] = $builder->getPhrase();
                                echo '<p style=""><img src="' . $builder->inline() . '"></p>';
                                echo '<p style="padding-right: 24px;"><label>'.__('Enter the code below','bpreviews').' <span class="required">*</span></label></p>';
                                echo '<input type="text" required name="captcha_code" value=""></p>';
                                ?>
                            </div>
                            <?php
                        endif;
                        ?>
                        <div id="whats-new-options" style="height: 40px;">
                            <div id="whats-new-submit">
                                <input type="hidden" value="0" id="rating" name="rating_member">
                                <input type="hidden" value="<?php echo bp_displayed_user_id() ?>" id="rating_id" name="rating_member_id">
                                <input type="hidden" value="<?php echo bp_core_get_username(bp_displayed_user_id()) ?>" id="rating_name" name="rating_member_name">
                                <span class="ajax-loader"></span>
                                <input type="submit" value="Post My Review" id="whats-new-submit" name="review_member_submit">
                            </div>
                            <?php
                            if ($options['anonymous'] && is_user_logged_in()) {
                                ?>
                                <div id="prorevs-anonymous">
                                    <label><?= __('Post as anonymous','bpreviews') ?>: <input type="checkbox" name="anonymous" value="1"></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
            <?php
        }
    } else if(!is_user_logged_in() && !$options['non_logged_approve']) {
            $can_post_review = false;
        ?>
            <div id="message" class="info">
                <p><?php printf(__('Please login to add review', 'bpreviews')); ?></p>
            </div>
    <?php }
    if (count($check_content_loop) > 0) {
        ?>
        <div class="pagination" id="pag-top" style="margin-bottom:20px">
            <div id="group-dir-count-top" class="pag-count">
                <?= __('Viewing reviews 1 to','bpreviews') ?> <?php echo count($check_content_loop) ?>  (of <?php echo bp_core_get_username(bp_displayed_user_id()) ?> )
            </div>
            <div id="group-dir-pag-top" class="pagination-links">
            </div>
        </div>
        <?php
    } else if(!$can_post_review) {
        ?>
        <div id="message" class="info">
            <p><?php printf(__("There are no reviews yet.", 'bpreviews')); ?></p>
        </div>
        <?php
    } else {
        ?>
        <div id="message" class="info">
            <p><?php printf(__("There are no reviews yet. <span class='bp-reviews-message'>Why not be the first to write one?</span>", 'bpreviews')); ?></p>
        </div>
        <?php
    }
    ?>
<?php
} else {
?>
    <?php if(!$options['hide_allow_for']) { ?>
    <div id="message" class="info">
        <p><?php printf(__("Reviews are not allowed for this membership role", 'bpreviews')); ?></p>
    </div>
    <?php } ?>
<?php
}
// End check if reviews are allowed on this user
?>
<div class="activity show_important">
    <ul id="activity-stream" class="activity-list item-list ">
        <?php
        foreach ($check_content_loop as $check_content_loopss) {
            $depro_idcheck = $check_content_loopss->id;
            $user_info_id = $check_content_loopss->user_id;
            $user_info_lastcheck = get_userdata($user_info_id);

            $args = array(
                'in' => array($check_content_loopss->id),
                'user_id' => $check_content_loopss->user_id
            );
            if (bp_has_activities($args)) {
                while (bp_activities()) {
                    bp_the_activity();
                    $demsss = bp_get_activity_id();
                    $is_confirmed = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}bp_activity_meta WHERE activity_id=" . $demsss . ' order by id', ARRAY_A);
                    ?>
                    <li id="activity-<?php echo $depro_idcheck ?>" class="member review">
                        <?php
                        if ($check_content_loopss->anonymous) {
                            ?>
                            <div class="activity-avatar">
                                <?php echo get_avatar(NULL, 48) ?>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="activity-avatar">
                                <a href="<?php echo get_bloginfo('home') ?>/members/<?php echo $user_info_lastcheck->user_login ?>/">
                                    <?php echo get_avatar($check_content_loopss->user_id, 48, $default, "") ?>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="activity-content">
                            <div class="activity-header">
                                <span class="ratingtop">
                                    <?php
                                    for ($dem = 1; $dem < 6; ++$dem) {
                                        if ($dem <= $check_content_loopss->star) {
                                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                                        } else {
                                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                                        }
                                    }
                                    ?>
                                </span> By
                                <?php
                                if ($check_content_loopss->anonymous) {
                                    if (isset($is_confirmed[0]['meta_value'])):
                                        echo $is_confirmed[0]['meta_value'] . ' ' . bp_core_time_since($check_content_loopss->date_recorded);
                                    else:
                                        echo __('Anonymous','bpreviews');
                                    endif;
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    <a href="<?php echo get_bloginfo('home') ?>/members/<?php echo $user_info_lastcheck->user_login ?>/"><?php echo $user_info_lastcheck->user_login ?></a> <?php echo bp_core_time_since($check_content_loopss->date_recorded) ?>
                                    <?php
                                }
                                ?>
                                <span class="hidencheck"><span style='color:red'><!-- only Administrator see this button --></span><?php
                    if (current_user_can('administrator')) {
                        $confirmationmsg = '';
                        if ($check_content_loopss->is_activated == '0'):
                            echo '<a href="' . curPageURLreviews() . '?approveID=' . str_rot13(base64_encode($demsss)) . '" class="button item-button" rel="nofollow">Approve</a>&nbsp;&nbsp;&nbsp;';
                            $confirmationmsg = 'This review is unconfirmed';
                        endif;

                        bp_activity_delete_link();
                        echo $confirmationmsg;
                    }
                                ?></span>
                            </div>
                            <div class="activity-inner delete_star">
                                <p><?php echo $check_content_loopss->content ?></p>
                            </div>
                            <style type="text/css">.delete_star .ratingtop { display: none; }</style>
                            <div class="activity-meta">
                                <?php
                                if (is_user_logged_in()) {
                                    if (bp_activity_can_comment()) {
                                        ?>
                                        <a href="<?php bp_activity_comment_link(); ?>" class="acomment-reply" id="acomment-comment-<?php echo $depro_idcheck ?>"><?php _e('Comment', 'bpreviews'); ?> (<span><?php bp_activity_comment_count(); ?></span>)</a>
                                        <?php
                                    }
                                    if ($options['flag']) {
                                        ?>
                                        <a href="#" class="prorevs-report-review" data-id="<?php echo $check_content_loopss->id ?>"><?= __('Report','bpreviews') ?></a>
                                        <span class="prorevs-report-form" data-id="<?php echo $check_content_loopss->id ?>" style="display: none">
                                            <?= __('Reason','bpreviews') ?>: <input type="text" id="prorevs-reason-<?php echo $check_content_loopss->id ?>">
                                            <a href="#" class="prorevs-report-send"><?= __('send','bpreviews') ?></a>
                                            <a href="#" class="prorevs-report-cancel"><?= __('cancel','bpreviews') ?></a>
                                        </span>
                                        <?php
                                    }
                                }
                                do_action('bp_activity_entry_meta');
                                ?>
                            </div>
                        </div>
                        <div class="activity-comments">
                            <?php
                            bp_activity_comments();
                            $options = get_option('reviews_options');
                            if (is_user_logged_in()) {
                                ?>
                                <form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php echo $depro_idcheck ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
                                    <div class="ac-reply-avatar"><?php bp_loggedin_user_avatar('width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT); ?></div>
                                    <div class="ac-reply-content">
                                        <div class="ac-textarea">
                                            <textarea id="ac-input-<?php echo $depro_idcheck ?>" class="ac-input" name="ac_input_<?php echo $depro_idcheck ?>"></textarea>
                                        </div>
                                        <input type="submit" name="ac_form_submit" value="<?php _e('Post', 'bpreviews'); ?>" /> &nbsp; <?php _e('or press esc to cancel.', 'bpreviews'); ?>
                                        <input type="hidden" name="comment_form_id" value="<?php echo $depro_idcheck ?>" />
                                    </div>
                                    <?php
                                    do_action('bp_activity_entry_comments');
                                    wp_nonce_field('new_activity_comment', '_wpnonce_new_activity_comment');
                                    ?>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                    </li>
                    <?php
                }
            }
        }
        ?>
    </ul>
</div>
<?php

function review_confirmation_notice() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#item-header').append('<div id="message" class="updated"><p>Your review confirmed successfully!</p>            </div>');
        });
    </script>
    <?php
}

function review_activation_notice() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#item-header').append('<div id="message" class="updated"><p>Review activated successfully!</p>            </div>');
        });
    </script>
    <?php
}
?>

<!-- Display "Why not be the first to write one" message if user is logged in and has permission leave reviews-->
<?php if ( is_user_logged_in() ) { ?>
<style  type="text/css" media="screen">
.bp-reviews-message { display: inline!important; }
</style>
<?php } ?>