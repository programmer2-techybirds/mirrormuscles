<?php

/**
 * AppBuddy_Notifications class.
 */
class AppBuddy_Notifications {


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		@$this->is_loggedin = is_user_logged_in();
		$this->hooks();
	}


	/**
	 * hooks function.
	 *
	 * @access public
	 * @return void
	 */
	public function hooks() {
		
		if ( isset( $this->is_loggedin) && class_exists( 'AppPresser_Notifications' ) && class_exists( 'AppPresser_Notifications_Update' ) && appp_get_setting( 'apppush_appbuddy' ) ) {
			add_action( 'messages_message_sent', array( $this, 'send_notification_message' ),999 ,1 );
			add_action( 'bp_friends_sent_request_email', array( $this, 'send_friend_request' ),999 ,5 );
			add_action( 'bp_activity_sent_mention_email', array( $this, 'send_mention' ),1 ,5 );
			add_filter( 'appp_friend_request_message', array( $this, 'filter_friend_request_message' ) );
			
		}

	}


	/**
	 * send_notification_message function.
	 *
	 * Sends a push notification for any email sent via messages
	 *
	 * @todo needs title
	 * @access public
	 * @return array
	 */
	public function send_notification_message( $message ) {
		$push = new AppPresser_Notifications_Update();
		$devices = $push->get_devices_by_user_id( $message->recipients );

		if( empty($devices) )
			return 'No registered devices, cannot send message.';
		
		$push->notification_send( 'now', $message->subject, 1, $devices );
	}

	/**
	 * send_friend_request function.
	 *
	 * Sends a push notification for any friend request
	 *
	 * @todo turn this into toolbar button api
	 * @access public
	 * @return array
	 */
	public function send_friend_request( $friend_id, $subject, $message, $friendship_id, $initiator_id ) {
		$push = new AppPresser_Notifications_Update;
		$devices = $push->get_devices_by_user_id( array( $friend_id ) );

		if( empty($devices) )
			return 'No registered devices, cannot send message.';

		$message = apply_filters( 'appp_friend_request_message', $subject );

		$custom_url = ''; // @TODO
		$push->notification_send( 'now', $message, 1, $devices, array(), $custom_url );
	}

	/**
	 * Replace placeholders for {{{site.name}}} and {{initiator.name}}
	 * 
	 * @access public
	 * @return string
	 */
	public function filter_friend_request_message( $msg ) {
		$msg = str_replace('{{{site.name}}}', get_bloginfo('name').":", $msg);
		$user = get_user_by('ID', $initiator_id);
		$msg = str_replace('{{initiator.name}}', "@".$user->user_login, $msg);

		return $msg;
	}

	/**
	 * send_mention function.
	 *
	 * Sends a push notification for any @mention
	 *
	 * @todo turn this into toolbar button api
	 * @access public
	 * @return array
	 */
	public function send_mention( $activity, $subject, $message, $content, $receiver_user_id ) {

		$sender_user_id = $activity->user_id;
		$user = get_user_by( 'ID', $sender_user_id );
		$message = "@" . $user->user_login . " " . __( "just mentioned you in a post", 'appbuddy' );

		$message = apply_filters( 'app_send_mention_message', $message, $sender_user_id, $receiver_user_id );

		$push = new AppPresser_Notifications_Update;
		$devices = $push->get_devices_by_user_id( array( $receiver_user_id ) );

		if( empty($devices) )
			return 'No registered devices, cannot send message.';

		$custom_url = (isset( $activity->primary_link)) ? $activity->primary_link : '';
		$push->notification_send( 'now', $message, 1, $devices, array(), $custom_url );
	}

}
$AppBuddy_Notifications = new AppBuddy_Notifications();