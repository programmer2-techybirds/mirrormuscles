<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the member activation page.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_activation_page' ); ?>

	<div class="page" id="activate-page" 
		<?php 
        	$mm_regpage_options = get_option("mm_regpage_options");
        	echo 'style="background:url('.$mm_regpage_options['regpage_image'].') no-repeat center center fixed; background-size: cover; min-height: 736px;"';
        ?>
	>

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
		do_action( 'template_notices' ); ?>

		<?php

		/**
		 * Fires before the display of the member activation page content.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		do_action( 'bp_before_activate_content' ); ?>

		<?php if ( bp_account_was_activated() ) : ?>

			<?php if ( isset( $_GET['e'] ) ) : ?>
				<p class="regpage-account-details text-center">
					<?php _e( 'Your account was activated successfully! Your account details have been sent to you in a separate email.', 'buddypress' ); ?>
				</p>
			<?php else : ?>
				<p class="regpage-account-details text-center">
					<?php _e( 'Your account was activated successfully! You can now log in with the username and password you provided when you signed up.', 'buddypress' ); ?>
					<a href="<?php echo wp_login_url(); ?>" class="please-login">Login</a>
				</p>
			<?php endif; ?>

		<?php else : ?>

			<p class="regpage-account-details text-center"><?php _e( 'Please provide a valid activation key.', 'buddypress' ); ?></p>

			<form action="" method="get" class="standard-form" id="activation-form">

				<label for="key" style="color:#fff;"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
				<input type="text" name="key" id="key" value="" style="color: white; border: 2px solid #4dcadd; border-radius: 4px; background-color: transparent !important; margin-bottom: 10px;"/>

				<p class="submit">
					<input type="submit" name="submit" class="button" value="<?php esc_attr_e( 'Activate', 'buddypress' ); ?>" />
				</p>

			</form>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of the member activation page content.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		//do_action( 'bp_after_activate_content' ); ?>

	</div><!-- .page -->

	<?php

	/**
	 * Fires after the display of the member activation page.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_activation_page' ); ?>

</div><!-- #buddypress -->
