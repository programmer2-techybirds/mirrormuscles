<?php
/*
  Plugin Name: BP-WP Profile Reviews
  Version: 2.1.1
  Description: BP-WP Profile Reviews has two functions: (1) create a review section for member profiles in Buddypress (2) convert Wordpress comments on a post or page into reviews with star ratings.
  Author: Spoonjab
  Author URI: http://spoonjab.com
  Plugin URI: http://spoonjab.com/bp-wp-profile-reviews/
  Text Domain: bpreviews
  Domain Path: /languages/
  Copyright (C) 2015 Spoonjab

  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software") to use the Software. Permission is NOT granted to modify, merge, publish, distribute, sublicense, and/or sell copies of the Software. Persons to whom the Software is furnished, are subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

ob_start();

if (!defined('DEPROURL')) {
    define('DEPROURL', WP_PLUGIN_URL . '/BP-WP-Profile-Page-Post-Reviews/');
}

if (!defined('PROREVS_ROOT')) {
    define('PROREVS_ROOT', dirname(__FILE__));
}

add_action('wp_head', 'insert_js_depro', 1);

function insert_js_depro() {
    ?>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        window.document.onkeydown = function(e) {
            if (!e)
                e = event;
            if (e.keyCode == 116) {
                return false;
            }
        }

        jQuery(document).ready(function() {
            var jq = jQuery;

            // Make the Read More on the already-rated box have a unique class
            var arm = jq('.already-rated .activity-read-more');
            jq(arm).removeClass('activity-read-more').addClass('already-rated-read-more');

            jq('.star').mouseover(function() {
                var num = jq(this).attr('id').substr(4, jq(this).attr('id').length);
                for (var i = 1; i <= num; i++)
                    jq('#star' + i).attr('src', "<?php echo DEPROURL ?>images/star.png");
            });

            jq('div#review-rating').mouseout(function() {
                for (var i = 1; i <= 5; i++)
                    jq('#star' + i).attr('src', "<?php echo DEPROURL ?>images/star_off.png");
            });

            jq('.star').click(function() {
                var num = jq(this).attr('id').substr(4, jq(this).attr('id').length);
                for (var i = 1; i <= 5; i++)
                    jq('#star' + i).attr('src', "<?php echo DEPROURL ?>images/star_off.png");
                for (var i = 1; i <= num; i++)
                    jq('#star' + i).attr('src', "<?php echo DEPROURL ?>images/star.png");

                jq('.star').unbind('mouseover');
                jq('div#review-rating').unbind('mouseout');

                jq('input#rating').attr('value', num);
            });

            jq('.already-rated-read-more a').live('click', function(event) {
                var target = jq(event.target);

                var link_id = target.parent().attr('id').split('-');
                var a_id = link_id[3];

                var a_inner = '.already-rated blockquote p';

                jq(target).addClass('loading');

                jq.post(
                        ajaxurl,
                        {
                            action: 'get_single_activity_content',
                            'activity_id': a_id
                        },
                function(response) {
                    jq(a_inner).slideUp(300).html(response).slideDown(300);
                });

                return false;
            });

            jq('#whats-new-submit').click(function() {
                if (jq('input#rating').val() == 0) {
                    alert('<?php _e('Please choose a star rating!','bpreviews'); ?>'); //jquery text i18n
                    return false;
                }
            });

            jq('#submit').click(function() {
                if (jq('input#rating').val() == 0) {
                    alert('Please Rate for This page/post !!!');
                    return false;
                }
            });
        });
    </script>
    <?php
}

//
// Implements the options page
//

add_action('admin_menu', 'prorevs_admin_menu');

function prorevs_admin_menu() {
    add_menu_page(__('Reviews Settings', 'bpreviews'), __('Profile Reviews', 'bpreviews'), 'manage_options', 'profile-reviews', 'prorevs_options_page', DEPROURL . '/images/star.png');

    add_action('admin_init', 'prorevs_admin_init');

    function prorevs_admin_init() {
        register_setting('reviews_options_page', 'reviews_options');
    }

    function prorevs_options_page() {
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        ?>
        <div class="wrap">
            <h2><?= __('BP-WP Profile Reviews - Options', 'bpreviews'); ?></h2>
            <div class="postbox" style="width: 850px;">
                <h3 style="padding: 10px;"><?= __('About BP-WP Profile Reviews v.2.1.1', 'bpreviews'); ?></h3>
                <div style="padding:10px 10px; margin-top: -12px; background:#ffffff;">
        <?= __('This plugin is an adaptation of <a href="http://wordpress.org/extend/plugins/bp-group-reviews/" target="_blank">BP Group Reviews</a>. This plugin has two functions that can be used together or separately:', 'bpreviews'); ?><br>
                    <ol>
                        <li><strong><?= __('Buddypress Member Profile Reviews', 'bpreviews'); ?></strong> - <?= __('This option enables a Reviews tab and button for Buddypress Member Profiles. Members can leave text and star reviews on other members. The average star rating, number of reviews, and time is displayed. Reviews can only be deleted by the author or an administrator. The amount and average star ratings are displayed on the Member Directory. Reviews are fed into the activity stream. You can also reply and comment on reviews.', 'bpreviews'); ?></li>
                        <li><strong><?= __('Wordpress/Buddypress Post and Page Reviews', 'bpreviews'); ?></strong> - <?= __('Post and Page comments are globally transformed into reviews with star ratings. Only a name, comment text box, and star rating show (no other fields like website, email, etc...). If enabled, existing comments will show zero stars. This option can be used whether you have Buddypress installed or not.  The average star rating is shown at the top of the comments. This works for Buddpress and Wordpress posts and pages alike. You can enable or disable ratings at the bottom of the draft. Reviews are fed into the activity stream. Reviews can be deleted in the Comments section of the admin panel. Star rating is displayed in the admin Comments section.', 'bpreviews'); ?></li>
                    </ol>
                </div>
                <div style="padding:10px; background:#eaf2fa;">
        <?= __('Plugin Website', 'bpreviews'); ?>: <a href="http://spoonjab.com/bp-wp-profile-reviews/?utm_source=BP%2BPlugin&utm_medium=admin%2Bpanel&utm_campaign=PluginBackend" target="_blank">http://spoonjab.com/bp-wp-profile-reviews/</a><br>
        <?= __('Author Website', 'bpreviews'); ?>: <a href="http://spoonjab.com/?utm_source=BP%2BPlugin&utm_medium=admin%2Bpanel&utm_campaign=PluginBackend" target="_blank">http://spoonjab.com</a><br>
        <?= __('Support Email', 'bpreviews'); ?>: <a href="mailto:spoon@spoonjab.com">spoon@spoonjab.com</a><br>
                    <?= __('Support Forums', 'bpreviews'); ?>: <a href="http://support.spoonjab.com">support.spoonjab.com</a><br><br>
                    <div style="color:#BE5409;font-weight:bold;">
                    <?= __('I pay to have this plugin developed at my own expense; future requests and development can be accommodated through donations. If you want to make a request, email', 'bpreviews'); ?> <a href="mailto:spoon@spoonjab.com">spoon@spoonjab.com</a>
                    </div>
                </div>
            </div>
            <form action="options.php" method="post">
        <?php
        settings_fields('reviews_options_page');
        $options = get_option('reviews_options');
        $allow_for_values = array();
        $allow_by_values = array();
        if(is_array($options['allow_for'])) {
            $allow_for_values = $options['allow_for'];
        }
        if(is_array($options['allow_by'])) {
            $allow_by_values = $options['allow_by'];
        }
        ?>
                <div class="options" style="width: 850px">
                    <br>
                    <label style="font-size: 18px;"><strong><?= __('Enable Buddpress reviews on profile pages?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[profile]" <?php echo $options['profile'] == 'profile' ? 'checked' : '' ?> type="checkbox" id="textfield" value="profile" size="50" /></label><br>- <?= __('Adds a Reviews tab and button to all member profiles (Buddypress Activity Streams must be enabled)', 'bpreviews'); ?><br>
                    <div style="width: 750px; margin-left: 30px;">
                        <label style="float:none;"><strong> <?= __('Number of multiple Buddypress Profile reviews per user', 'bpreviews'); ?>:</strong>&nbsp;<input name="reviews_options[limit]" type="text" id="text_check" value="<?php echo isset($options['limit']) ? $options['limit'] : '1' ?>" size="1" /></label>
                        <ul style="padding-left: 30px; margin-top: 0px;">
                            <li><?= __('Allows for multiple reviews from one user on same member. 0 = unlimited', 'bpreviews'); ?></li>
                        </ul>
                        <label><strong><?= __('Prevent members from rating their own Buddypress Profile?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[Prevent]" <?php echo $options['Prevent'] == 'Prevent' ? 'checked' : '' ?> type="checkbox" id="textfield" value="Prevent"  /></label><br><br>
                        <label><strong><?= __('Allow members to publish their reviews as anonymous?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[anonymous]"  <?php echo $options['anonymous'] == '1' ? 'checked' : '' ?> type="checkbox" id="textfield" value="1" /></label><br><br>
                        <label><strong><?= __('Allow reviews from the public (non-members)?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[non_logged_approve]" <?php echo $options['non_logged_approve'] == '1' ? 'checked' : '' ?> type="checkbox" id="textfield" value="1" /></label><br>- <?= __('this option also enables Captcha to prevent spam', 'bpreviews'); ?><br>
                        <ul style="list-style: initial; padding-left: 30px;">
                            <li><strong><?= __('Require email address confirmation before public review is posted', 'bpreviews'); ?>:</strong>&nbsp;<input name="reviews_options[admin_approval]" type="checkbox" <?php echo $options['admin_approval'] == '1' ? 'checked' : '' ?> value="1"  size="50" /><br><?= __('Number of unconfirmed reviews visible to admin on member profile.', 'bpreviews'); ?></li>
                            <li><strong><?= __('Send verified new review email notices to', 'bpreviews'); ?>:</strong>&nbsp;<input name="reviews_options[approval_emails]" type="text" style="width: 300px;" value="<?php echo isset($options['approval_emails']) ? $options['approval_emails'] : get_settings('admin_email') ?>" size="50" /><br><?= __('Separate multiple email addresses using "," (comma) character', 'bpreviews'); ?></li>
                        </ul>
                        <label><strong><?= __('Allow users to report (flag) profile reviews?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[flag]" <?php echo $options['flag'] == '1' ? 'checked' : '' ?> type="checkbox" value="1" /></label><br>
                        <ul style="list-style: initial; padding-left: 30px;">
                            <li><strong><?= __('Send notices for flagged reviews to', 'bpreviews'); ?>:</strong>&nbsp;<input name="reviews_options[admin_emails]"  type="text" style="width: 300px;" value="<?php echo isset($options['admin_emails']) ? $options['admin_emails'] : get_settings('admin_email') ?>" size="50" /><br><?= __('Separate multiple email addresses using "," (comma) character', 'bpreviews'); ?></li>
                        </ul>
                        <label><strong><?= __('Membership Role Based Reviews', 'bpreviews'); ?>:</strong></label><br>
                        <div style="padding-left: 30px;">
                            <p style="margin-bottom:5px"><strong>Allow reviews only for the following membership roles:</strong></p>
                        <?php 
                            $roles_size = count($all_roles);
                            $i = 0;
                        ?>
                        <?php foreach($all_roles as $key => $value): ?>
                            <?php if($i == ceil($roles_size/2) || $i == 0): ?>
                            <ul style="list-style: none;width:40%;float:left;margin-top: 0;padding-left: 3%;margin-bottom: 5px;">
                            <?php endif; ?>
                                <li><input type="checkbox" name="reviews_options[allow_for][]" value="<?php echo $key ?>" <?php echo in_array($key,$allow_for_values) ? 'checked' : '' ?> />&nbsp;<?php echo $value['name'] ?></li>
                            <?php if($i == ceil($roles_size/2)-1 || $i == $roles_size): ?>
                            </ul>
                            <?php endif; ?>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                        </div>
                        <div style="padding-left: 30px;clear:both">
                            <p style="margin-bottom:5px;margin-top:0;"><input type="checkbox" name="reviews_options[hide_allow_for]" value="1" <?php echo $options['hide_allow_for'] == '1' ? 'checked' : '' ?> />&nbsp;Hide warning message on profiles that cannot receive reviews.</p>
                            <p style="margin-bottom:5px;margin-top:0;"><input type="checkbox" name="reviews_options[hide_reviews]" value="1" <?php echo $options['hide_reviews'] == '1' ? 'checked' : '' ?> />&nbsp;Remove all review functionality from displaying on Buddypress profiles if not allowed. (Profile review summary, Profile Reviews tab, Member Directory review summary, etc...)</p>
                        </div>
                        <div style="padding-left: 30px;">
                            <p style="margin-bottom:5px"><strong>Allow reviews to be <u>created by</u> the following membership roles:</strong></p>
                            <ul style="list-style: initial; padding-left: 30px;">
                                <li>This option will not prevent non-member reviews if you allow public reviews above.</li>
                            </ul>
                        <?php 
                            $roles_size = count($all_roles);
                            $i = 0;
                        ?>
                        <?php foreach($all_roles as $key => $value): ?>
                            <?php if($i == ceil($roles_size/2) || $i == 0): ?>
                            <ul style="list-style: none;width:40%;float:left;margin-top: 0;padding-left: 3%;margin-bottom: 5px;">
                            <?php endif; ?>
                                <li><input type="checkbox" name="reviews_options[allow_by][]" value="<?php echo $key ?>" <?php echo in_array($key,$allow_by_values) ? 'checked' : '' ?> />&nbsp;<?php echo $value['name'] ?></li>
                            <?php if($i == ceil($roles_size/2)-1 || $i == $roles_size): ?>
                            </ul>
                            <?php endif; ?>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                        </div>
                        <div style="padding-left: 30px;clear:both">
                            <p style="margin-bottom:5px;margin-top:0;"><input type="checkbox" name="reviews_options[hide_allow_by]" value="1" <?php echo $options['hide_allow_by'] == '1' ? 'checked' : '' ?> />&nbsp;Hide warning message on profiles when user does not have review permissions.</p>
                        </div>
                        <label><strong><?= __('Shortcodes', 'bpreviews'); ?>:</strong></label><br>
                        <ul style="list-style: initial; padding-left: 30px;">
                            <li><strong>[prorevs_users_by_rating limit=10]</strong> - <?= __('Displays list of members with the highest average star rating', 'bpreviews'); ?></li>
                            <li><strong>[prorevs_users_by_review_count limit=10]</strong> - <?= __('Displays list of members with the most total reviews', 'bpreviews'); ?></li>
                        </ul>

                    </div>
                    <br>
                    <hr align="left" style="width: 850px;"><br>
                    <label style="font-size: 18px;"><strong><?= __('Enable Wordpress reviews for pages/posts?', 'bpreviews'); ?></strong>&nbsp;<input name="reviews_options[postpage]" style="float: none;" <?php echo $options['postpage'] == 'postpage' ? 'checked' : '' ?> type="checkbox" id="textfield" value="postpage" size="50" /></label><br>- <?= __('Converts default Wordpress comments to include a star rating. Comments must enabled for posts/pages reviews to be visible', 'bpreviews'); ?><br><br>
                </div>
                <input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
            </form>
        </div>
        <?php
    }

;
}

;

$options = get_option('reviews_options');

if ($options['postpage'] == 'postpage') {
    add_action('add_meta_boxes', 'depro_myplugin_add_custom_box');
    add_action('save_post', 'depro_myplugin_save_postdata');

    function depro_myplugin_add_custom_box() {
        add_meta_box(
                'depro_myplugin_sectionid', __('Reviews', 'bpreviews'), 'depro_myplugin_inner_custom_box', 'post'
        );
        add_meta_box(
                'depro_myplugin_sectionid', __('Reviews', 'bpreviews'), 'depro_myplugin_inner_custom_box', 'page'
        );
    }

    function depro_myplugin_inner_custom_box($post) {
        global $post;
        $post_id = $post->ID;
        wp_nonce_field(plugin_basename(__FILE__), 'myplugin_noncename');
        echo '<label for="setreviews_onoroff">';
        _e("Show Review on this page ? ", 'bpreviews');
        echo '</label> ';
        $checkmetakey = get_post_meta($post_id, '_reviews', true);
        if ($checkmetakey == "onoff") {
            $checked = "checked";
        } else {
            $checked = "";
        }
        echo '<input type="checkbox" id="setreviews_onoroff" name="setreviews_onoroff" value="onoff" ' . $checked . ' />';
    }

    function depro_myplugin_save_postdata($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!wp_verify_nonce($_POST['myplugin_noncename'], plugin_basename(__FILE__)))
            return;
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return;
        }

        $mydata = $_POST['setreviews_onoroff'];

        add_post_meta($post_id, '_reviews', $mydata, true);
        update_post_meta($post_id, '_reviews', $mydata);
    }

    add_action('comments_template', 'prorevs_template_comment');

    function prorevs_template_comment() {
        ?>
        <script type="text/javascript">
            window.document.onkeydown = function(e)
            {
                if (!e)
                    e = event;
                if (e.keyCode == 27) {
                    var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

                    if (!temp || !respond)
                        return;
                    t.I('comment_parent').value = '0';
                    temp.parentNode.insertBefore(respond, temp);
                    temp.parentNode.removeChild(temp);
                    this.style.display = 'none';
                    this.onclick = null;

                    return false;
                }
            }

            jQuery(document).ready(function() {
                jQuery('.form-submit #submit').val('Post');
                var check = 0;
                jQuery('.comment-reply-link').click(function() {
                    check++;
                    jQuery('.form-submit #submit').val('Post');
                    if (check == 1) {
                        jQuery("#submit").after(" <p id='hidenow' style='font-size:10px;color:#a1a1a1;position:relative;left:-50px;top:5px'>or press ESC to cancel !</p>");
                    }
                })
            });
        </script>
        <style type="text/css">
            li.depth-1 .comment-meta, .comment-form-email, .comment-form-url {
                display: none !important;
            }

            ul.children .comment-meta {
                display: block !important;
            }

            ol.commentlist #review-rating {
                display: none !important;
            }

            #comments h3, #cancel-comment-reply-link {
                display: none !important;
            }

            #submit {
                float: right;
                margin-right: 80px;
                position: relative;
                z-index: 1000;
            }

            .commentlist #submit{
                float: left !important;
            }

            .commentlist #hidenow{
                display: block !important;
            }

            #respond #hidenow{
                display: none;
            }

            .comment-notes{
                display: none;
            }

            ol.commentlist li.comment{
                padding-top: 10px;
            }
        </style>
        <?php
        global $post;
        $post__id = $post->ID;
        global $wpdb;
        $rows = $wpdb->get_col("SELECT comment_ID FROM {$wpdb->prefix}comments WHERE comment_post_ID ='$post__id' and comment_parent = '0' AND comment_approved ='1'");
        $rowsss = 0;

        if (count($rows) > 0) {
            $dem = -1;

            foreach ($rows as $commentsid) {
                $dem++;
                $commentsid = $rows[$dem];
                $rowss = $wpdb->get_col("SELECT meta_value FROM {$wpdb->prefix}commentmeta WHERE comment_id ='$commentsid' and meta_key = 'star_of_comment'");
                $rowsss += $rowss[0];
            }

            $check_show_star = $rowsss / count($rows);
        } else {
            $check_show_star = 0;
        }
        ?>
        <div class="top-commentss">
            <span><?php
        $demss = 0;
        for ($dem = 1; $dem < 6; ++$dem) {
            if ($dem <= $check_show_star) {
                echo '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
            } else {
                $demss++;
                if (ceil($check_show_star) - $check_show_star > 0 and $demss == 1) {
                    echo '<img alt="1 star" src="' . DEPROURL . '/images/star_half.png">';
                } else {
                    echo '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                }
            }
        }
        ?></span> (Based on  <?php echo count($rows) ?> ratings )
            <h3>Reviews</h3>
        </div>
                <?php
            }

            add_filter('comment_text', 'edit_comment_depro');

            function edit_comment_depro($comment) {
                $check_star = get_comment_meta(get_comment_ID(), 'star_of_comment', true);
                $comment_check = get_comment(get_comment_ID());
                if ($comment_check->comment_parent == 0) {
                    echo '<div class="activity-header">
                <span class="rating"> ';
                    for ($dem = 1; $dem < 6; $dem++) {
                        if ($dem <= $check_star) {
                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
                        } else {
                            echo '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
                        }
                    }
                    $user_id = $comment_check->user_id;

                    if ($user_id == 0) {
                        $user_link = "#No-data-with-this-user";
                    } else {
                        $user_info = get_userdata($user_id);
                        $user_link = get_bloginfo('home') . "/members/" . $user_info->user_login;
                    }
                    global $wpdb;
                    $rows = $wpdb->get_col("SELECT comment_ID FROM " . $wpdb->prefix . "comments WHERE comment_parent ='" . get_comment_ID() . "' AND comment_approved='1'");
                    ?>
            </span> By <a title="<?php echo $comment_check->comment_author ?>" href="<?php echo $user_link ?>"><?php echo $comment_check->comment_author ?></a> ( <?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?> )
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#<?php echo "comment-" . get_comment_ID(); ?> .comment-reply-link').eq(0).html('comment (<?php echo count($rows); ?>)');
                });
            </script>
            <?php
        }
        echo '<br>' . $comment;
    }

    add_filter('comments_open', 'remove_comments_template_on_pages');

    function remove_comments_template_on_pages() {
        $options = get_option('reviews_options');
        global $post;
        $idpost = $post->ID;
        $metakey = get_post_meta($idpost, '_reviews', true);
        if ($options['postpage'] != "postpage" or $metakey != "onoff") {
            ?>
            <style type="text/css">
                .top-commentss,#comments{display:none}
            </style>
            <?php
            return false;
        }
        else
            return true;
    }

    add_action('comment_form_field_comment', 'checkdepro');

    function checkdepro() {
        ?>
        <style type="text/css">
            .form-allowed-tags{display:none}
        </style>
        <div id="whats-new-content">
            <div id="whats-new-textarea">
                <div>
                    <textarea value="" id="whats-new" name="comment"></textarea>
                </div>
            </div>
            <div id="review-rating">
                <br>Rate it: <img src="<?php echo DEPROURL; ?>/images/star_off.png" class="star" id="star1"><img src="<?php echo DEPROURL; ?>/images/star_off.png" class="star" id="star2"><img src="<?php echo DEPROURL; ?>/images/star_off.png" class="star" id="star3"><img src="<?php echo DEPROURL; ?>/images/star_off.png" class="star" id="star4"><img src="<?php echo DEPROURL; ?>/images/star_off.png" class="star" id="star5">
            </div><br>
        </div>
        <input type="hidden" value="0" id="rating" name="rating">
        <?php
    }

    add_action('comment_post', 'add_star');

    function add_star() {
        global $post;
        $post_id = $post->ID;
        $args = array(
            'number' => '1',
            'post_id' => $post_id,
            'order' => 'DESC',
        );
        $star_check = $_POST["rating"];
        $commentss = get_comments($args);
        foreach ($commentss as $comment) {
            $comment_id = $comment->comment_ID;
            $comment_parent = $comment->comment_parent;
        }
        if ($comment_parent == 0) {
            add_comment_meta($comment_id, 'star_of_comment', $star_check, true);
        }
    }

}

function prorevs_load() {
    require_once('includes/class_depro.php');
    require_once('includes/top_users.php');
}

add_action('plugins_loaded', 'prorevs_load');

function prorevs_scripts_and_styles() {
    wp_register_script('prorevs-script-common', plugins_url('/js/common.js', __FILE__), array('jquery'), '20121225-2');
    wp_localize_script('prorevs-script-common', 'proRevs', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('prorevs-ajax-nonce'),
        'loadingImg' => plugins_url('/images/loading.gif', __FILE__)
    ));
    wp_enqueue_script('prorevs-script-common');

    wp_register_style('prorevs-style-common', plugins_url('/css/style.css', __FILE__), array(), '20121225-2', 'all');
    wp_enqueue_style('prorevs-style-common');
}

add_action('wp_enqueue_scripts', 'prorevs_scripts_and_styles');

//
// Handle the ajax "Report a Review" action
//

if ($options['flag']) {

    function prorevs_report_review_action() {
        global $wpdb;
        $options = get_option('reviews_options');
        if (wp_verify_nonce($_POST['nonce'], 'prorevs-ajax-nonce')) {
            $rating = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bp_activity WHERE id = %d", (int) $_POST['id']));
            if ($rating !== null) {
                $current_user = wp_get_current_user();
                $rater = get_userdata($rating->user_id);
                $reviewed = get_userdata($rating->usercheck);
                wp_mail(
                        $options['admin_emails'], 'A review is flagged and needs your attention', "URL: " . get_bloginfo('home') . '/members/' . $reviewed->user_login . "/reviews/\n" .
                        "Reporter: {$current_user->user_login}\n" .
                        "Reason: {$_POST['reason']}\n" .
                        "Review by: {$rater->user_login}\n" .
                        "Review content: " . strip_tags($rating->content)
                );
                echo '""';
            } else {
                echo '"Can\\\'t find the given review."';
            }
        } else {
            echo '"Please reload the page and try again."';
        }

        exit;
    }

    add_action('wp_ajax_report-review', 'prorevs_report_review_action');
}

// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');

// Hide ratings from activities
function prorevs_activity_filter($a, $activities) {
    global $bp;

//    if (is_super_admin())
//        return $activities;

    if ($GLOBALS['bp']->current_component == "reviews")
        return $activities;

    foreach ($activities->activities as $key => $activity) {
        if ($activity->type == 'Member_review' && $activity->anonymous) {
            if (get_current_user_id() == $activities->activities[$key]->user_id) {
                $activities->activities[$key]->user_id = -1;
                $activities->activities[$key]->action
                        = preg_replace('#^<a.*?\>.*?</a>#', 'You (anonymously)', $activities->activities[$key]->action);
                $activities->activities[$key]->primary_link = '';
                $activities->activities[$key]->user_email = '';
                $activities->activities[$key]->user_nicename = '';
                $activities->activities[$key]->user_login = '';
                $activities->activities[$key]->display_name = '';
                $activities->activities[$key]->user_fullname = '';
            } else {
                --$activities->activity_count;
                unset($activities->activities[$key]);
            }
        }
    }

    $activities->activities = array_values($activities->activities);

    return $activities;
}

add_action('bp_has_activities', 'prorevs_activity_filter', 10, 2);

register_activation_hook(__FILE__, 'prorevs_activation_hook');

function prorevs_activation_hook() {
    global $wpdb;
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD star int(11)");
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD usercheck int(11)");
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD anonymous int(11)");
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD logged_out int(11)");
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD is_activated int(11)");
    $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_activity ADD INDEX usercheck (usercheck)");
}

add_action('init', 'bp_wp_session');

function bp_wp_session() {
    if (session_id() == '')
        session_start();
}

function load_bpreviews_plugin_textdomain() {
    load_plugin_textdomain('bpreviews', FALSE, basename(dirname(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'load_bpreviews_plugin_textdomain');