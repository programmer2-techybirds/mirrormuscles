<?php

global $wpdb;
$current_user = wp_get_current_user();

    $check_exit = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");
    $tmp = $wpdb->get_results("SELECT id, content, star FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' AND usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");

    $check_content = array();
    $check_content_ids = array();

    foreach ($tmp as $row) {
        $check_content_ids[] = $row->id;
        $check_content[$row->id] = $row;
    }

    $check_content_loop = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_activity WHERE  type = 'Member_review' and  usercheck='" . bp_displayed_user_id() . "' and (is_activated is null or is_activated=1)");

    $options = get_option('reviews_options');

// Check if reviews are allowed on this user
    if (is_user_logged_in()): ?>
        
        <?php 
            
            $check_total_set = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}bp_activity WHERE user_id ='$current_user->ID' AND type = 'Member_review' ");
        
            if (bp_displayed_user_id() == $current_user->ID) :
            
                echo '<div id="message" class="info"><p>You can\'t review yourself</p</div>';
            
            else : ?>

                <form class="review-member-form whats-new-form-member" name="whats-new-form" id="whats-new-form " method="post" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>">
                    <?php if (count($check_exit)!=0): ?>
                        <?php 
                            $maxss = ($options['limit'] == 0) ? 10000 : $options['limit'];
                        
                            $args = array(
                                'user_id' => $check_exit[0]->user_id,
                                'action' => 'Member_review',
                                'in' => $check_content_ids,
                                'sort' => 'ASC',
                                'max' => $maxss
                            );

                            if (bp_has_activities($args)) : printf(__('<h3 class="template-subtitle">Your reviews and ratings for %s </h3>','bpreviews'), get_fullname(bp_displayed_user_id())); while (bp_activities()) : bp_the_activity(); $demsss = bp_get_activity_id(); ?>
                                <div class="already-rated">
                                    <div class="col-md-6 col-md-offset-3 col-sm-8 col-offset-2 col-xs-12 col-xs-offset-0">
                                        <p class="already-rated-content"><?php echo $check_content[$demsss]->content; ?></p>
                                        <p class="text-right already-rated-actions"><?php bp_activity_delete_link(); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; endif;?>
                    <?php endif;?>
                    
                    <?php if ((count($check_exit) < $options['limit'] || $options['limit'] == 0)): ?>
                        <h5><?php echo 'What are your thoughts on '.get_fullname(bp_displayed_user_id()).'?';?></h5>

                        <div id="whats-new-content">
                            <div id="whats-new-textarea">
                                <div>
                                    <textarea value="" id="whats-new" name="review_member_content" style="display: inline-block; height: 50px;"></textarea>
                                </div>
                            </div>

                            <div id="review-rating" >
                                Rate it: <i id="star1" class="fa fa-star-o post-rating-star"
                                ></i><i id="star2" class="fa fa-star-o post-rating-star"
                                ></i><i id="star3" class="fa fa-star-o post-rating-star"
                                ></i><i id="star4" class="fa fa-star-o post-rating-star"
                                ></i><i id="star5" class="fa fa-star-o post-rating-star"></i>
                            </div>
                            <div class="clearfix"></div>
                            <div id="whats-new-options">
                                <div id="whats-new-submit">
                                    <input type="hidden" value="0" id="rating" name="rating_member">
                                    <input type="hidden" value="<?php echo bp_displayed_user_id() ?>" id="rating_id" name="rating_member_id">
                                    <input type="hidden" value="<?php echo get_fullname(bp_displayed_user_id()) ?>" id="rating_name" name="rating_member_name">
                                    <span class="ajax-loader"></span>
                                    <input type="submit" value="Post My Review" id="whats-new-submit" name="review_member_submit_custom">
                                </div>
                                <?php if ($options['anonymous']):?>
                                    <div id="prorevs-anonymous">
                                        <label>Post as anonymous: <input type="checkbox" name="anonymous" value="1"></label>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif;?>
                </form>
            <?php endif;//prevent rate own account?>
    
    <?php else : wp_redirect(home_url()); endif;?>
        



    <?php if (count($check_content_loop) > 0) : ?>
        <h3 class="template-subtitle"><?php printf(__("All reviews and ratings for %s ",'bpreviews'), get_fullname(bp_displayed_user_id())); ?></h3>
        <div class="pagination" id="pag-top" style="margin-bottom:20px">
            <div id="group-dir-count-top" class="pag-count">
                <?= __('Viewing reviews 1 to','bpreviews') ?> <?php echo count($check_content_loop) ?>  (of <?php echo bp_core_get_username(bp_displayed_user_id()) ?> )
            </div>
            <div id="group-dir-pag-top" class="pagination-links">
            </div>
        </div>
    <?php elseif(!$can_post_review): ?>
        <div id="message" class="info">
            <p><?php printf(__("There are no reviews yet.", 'bpreviews')); ?></p>
        </div>
    <?php else: ?>
        <div id="message" class="info">
            <p><?php printf(__("There are no reviews yet. <span class='bp-reviews-message'>Why not be the first to write one?</span>", 'bpreviews')); ?></p>
        </div>
    <?php endif;?>







<div class="activity show_important">
    <ul id="activity-stream" class="activity-list item-list ">
        <?php
        foreach ($check_content_loop as $check_content_loopss):

            $depro_idcheck = $check_content_loopss->id;
            $user_info_id = $check_content_loopss->user_id;
            $user_info_lastcheck = get_userdata($user_info_id);

            $args = array(
                'in' => array($check_content_loopss->id),
                'user_id' => $check_content_loopss->user_id
            );
            if (bp_has_activities($args)) : while (bp_activities()) : bp_the_activity(); $demsss = bp_get_activity_id();?>

                <li id="activity-<?php echo $depro_idcheck ?>" class="member review">
                    <div class="activity-avatar">
                        <a href="<?php echo bp_core_get_user_domain($user_info_lastcheck->user_id) ?>">
                            <?php echo bp_get_activity_avatar(array('user_id'=>$check_content_loopss->user_id,'type'=>'full','width'=>48,'height'=>48)) ?>
                        </a>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                             By <a href="<?php echo bp_core_get_user_domain($user_info_lastcheck->user_id)?>"><?php echo get_fullname($check_content_loopss->user_id); ?></a> <?php echo bp_core_time_since($check_content_loopss->date_recorded) ?>
                        </div>
                        <div class="activity-inner delete_star">
                            <p><?php echo $check_content_loopss->content ?></p>
                        </div>
                    </div>
                </li>
            <?php endwhile; endif; endforeach;?>
    </ul>
</div>

