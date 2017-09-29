<?php
/**
 * BuddyPress - Home
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
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

<div id="buddypress">
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' ); ?>
   
                        
	<div class="activity no-ajax">
		<?php if ( bp_has_activities( 'display_comments=threaded&show_hidden=true&include=' . bp_current_action() ) ) : ?>

			<ul id="activity-stream" class="activity-list item-list">
			<?php while ( bp_activities() ) : bp_the_activity(); ?>

				<?php bp_get_template_part( 'activity/entry' ); ?>

			<?php endwhile; ?>
			</ul>

		<?php endif; ?>
	</div>
</div>
