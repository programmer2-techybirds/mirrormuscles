<form action="<?php bp_messages_form_action('compose' ); ?>" method="post" id="send_message_form" class="standard-form" role="main" enctype="multipart/form-data">

	<?php do_action( 'bp_before_messages_compose_content' ); ?>

	<label for="send-to-input" class="item item-input item-stacked-label">

		<span class="input-label"><?php _e("Send To (Username or Friend's Name)", 'appbuddy' ); ?></span>

		<ul class="first acfb-holder">
			<li>
				<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" />
					<?php bp_message_get_recipient_tabs(); ?>
			</li>
		</ul>
	</label>

	<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<input type="checkbox" id="send-notice" name="send-notice" value="1" /> <?php _e( "This is a notice to all users.", "appbuddy" ); ?>
	<?php endif; ?>

	<label for="subject"><?php _e( 'Subject', 'appbuddy' ); ?></label>
	<input type="text" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />

	<label for="content"><?php _e( 'Message', 'appbuddy' ); ?></label>
	<textarea name="content" id="message_content" rows="15" cols="40"><?php bp_messages_content_value(); ?></textarea>

	<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />

	<?php do_action( 'bp_after_messages_compose_content' ); ?>

	<div class="submit">
		<input type="submit" value="<?php esc_attr_e( "Send Message", 'appbuddy' ); ?>" name="send" id="send" />
	</div>

	<?php wp_nonce_field( 'messages_send_message' ); ?>
</form>

<script type="text/javascript">
	if( /iP(ad|hone|od)/.test(navigator.userAgent) ) {
		// current iOS keyboard bug doesn't like .focus()
	} else {
		jQuery("#send-to-input").focus();
	}
</script>

