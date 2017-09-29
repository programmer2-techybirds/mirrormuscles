<?php 
add_filter( 'bp_email_use_wp_mail', '__return_true' );
remove_filter( 'messages_message_content_before_save', 'wp_filter_kses', 1);
?>