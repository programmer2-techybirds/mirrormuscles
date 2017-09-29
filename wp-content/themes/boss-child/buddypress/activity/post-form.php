<?php

/**
 * BuddyPress - Activity Post Form
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary">

	<?php do_action( 'bp_before_activity_post_form' ); ?>

	<p class="activity-greeting_">
		<?php if ( bp_is_group() )
				printf( __( "What's new in %s, %s?", 'boss' ), bp_get_group_name(),  get_fullname(  bp_loggedin_user_id() ));
			else
				if(is_home()){
					printf( __( "What's new, %s?", 'boss' ), get_fullname( bp_loggedin_user_id() ));
				}else{
					global $wp, $wpdb;
					$current_url = home_url(add_query_arg(array(),$wp->request));
					$url = explode('/',$current_url);
					$prefix = $wpdb->prefix;
					$myrows = $wpdb->get_results("SELECT display_name FROM {$prefix}users WHERE user_nicename = '".$url[4]."'" );
					if($myrows[0] != ""){
						printf( __( "What's new, ".$myrows[0]->display_name."?", 'boss' ), get_fullname( bp_loggedin_user_id() ));
					}else{
						printf( __( "What's new, %s?", 'boss' ), get_fullname( bp_loggedin_user_id() ));
					}
				}
				
		?>
	</p>

	<div id="whats-new-content">
		<div id="whats-new-textarea">
			<textarea class="bp-suggestions" name="whats-new" id="whats-new" cols="50" rows="10" 
				<?php if ( bp_is_group() ) : ?>data-suggestions-group-id="<?php echo esc_attr( (int) bp_get_current_group_id() ); ?>" <?php endif; ?>
			><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_textarea( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</div>

		<div id="whats-new-options">

            <div id="whats-new-additional">
            
			<?php if ( bp_is_active( 'groups' ) && !bp_is_my_profile() && !bp_is_group() ) : ?>

				<div id="whats-new-post-in-box">

					<select id="whats-new-post-in" name="whats-new-post-in">
						<option selected="selected" value="0"><?php _e( 'My Profile', 'boss' ); ?></option>

						<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
							while ( bp_groups() ) : bp_the_group(); ?>

								<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

							<?php endwhile;
						endif; ?>

					</select>
				</div>
				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />

			<?php elseif ( bp_is_group_home() ) : ?>

				<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
				<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />

			<?php endif; ?>

			<?php do_action( 'bp_activity_post_form_options' ); ?>
            </div><!-- #whats-new-additional -->
            
            <div id="whats-new-submit">
				<input type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit" value="<?php esc_attr_e( 'Post Update', 'boss' ); ?>" />
			</div>

		</div><!-- #whats-new-options -->
	</div><!-- #whats-new-content -->

	<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
	<?php do_action( 'bp_after_activity_post_form' ); ?>
    
    <script>
		jQuery(document).ready(function(){
			jQuery('.buddystream_share_button.mylocation').css("display","none");
			jQuery('#whats-new-options select').css("height","40px");
			jQuery('#buddyboss-media-preview').css("margin","0px !important");
			
		});
	</script>
    <style>
	#buddyboss-media-preview{
		margin:0px !important;	
	}
	</style>
</form><!-- #whats-new-form -->
