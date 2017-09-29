<?php

/**

 * BuddyPress - Activity Loop

 *

 * @package BuddyPress

 * @subpackage bp-legacy

 */



/**

 * Fires before the start of the activity loop.

 *

 * @since 1.2.0

 */

do_action( 'bp_before_activity_loop' ); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="activity-list item-list 1">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php bp_get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="<?php bp_activity_load_more_link() ?>"><?php _e( 'Load More', 'buddypress' ); ?></a>
		</li>

	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

		<script>

			jQuery(document).ready(function(){
				jQuery('.activity-content .activity-meta a').click(function() {
					var res = jQuery(this).attr('id');
					var result = res.substring(text.indexOf('-') +9);
				});
			});
		
		</script>
        <style type="text/css">
			#buddypress span.bp-verified img{
				height: 15px !important;
			}
			.ac-reply-content .buddyboss-comment-media-add-photo-button{
				height: 40px !important;
			}
		</style>

	<?php endif; ?>



<?php else : ?>



	<div id="message" class="info">

		<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>

	</div>



<?php endif; ?>



<?php



/**

 * Fires after the finish of the activity loop.

 *

 * @since 1.2.0

 */

do_action( 'bp_after_activity_loop' ); ?>



<?php if ( empty( $_POST['page'] ) ) : ?>



	<form action="" name="activity-loop-form" id="activity-loop-form" method="post">



		<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>



	</form>



<?php endif; ?>

