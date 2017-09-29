<?php
/**
 * Updates or creates a new post with notifications post meta.
 * @since  1.0.0
 */
class AppPresser_Notifications_Update {

	/**
	 * Pushwoosh API url
	 * @var string
	 */
	public $api_url = 'https://cp.pushwoosh.com/json/1.3/';
	public  static $is_using_segments = null;
	public  static $notification_count = 0;
	private static $send_meta_key = 'send_push_notification';
	private static $bypass_meta_key = 'bypass_segments_send_push';

	/**
	 * Our WordPress hooks
	 * @since  1.0.0
	 */
	public function hooks() {

		// send push notifications from any cpt
		$post_types = appp_get_setting( 'notifications_post_types' );

		if( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		$post_types[] = 'apppush';

		if ( is_array( $post_types ) ) {

			add_action( 'all_admin_notices', array( $this, 'send_failed_no_subscribers' ) );
			add_action( 'all_admin_notices', array( $this, 'add_notice_device_count' ) );
			add_action( 'all_admin_notices', array( $this, 'add_notice_sent_to_all' ) );
			add_action( 'all_admin_notices', array( $this, 'add_notice_missing_myapp_settings' ) );

			// loop through our post types from our settings
			foreach ( $post_types as $type ) {

				// save our notification and segment preferences on each post
				add_action( "save_$type", array( $this, 'set_postmeta_send' ), 11, 3 );

				// And hook in our metabox and publish methods
				add_action( "add_meta_boxes_$type", array( $this, 'add_meta_box' ) );
				add_action( "publish_$type", array( $this, 'save' ), 10, 2 );
				add_action( "publish_future_$type", array( $this, 'send_notify_on_publish' ) );

			}

		}

	}

	/**
	 * Check if we want to output debug form data to the content
	 * @since  1.0.0
	 * @return boolean True if debug mode is on
	 */
	public function is_debug() {
		return ( isset( $_POST['app_debug'] ) || defined( 'APPPRESSER_DEBUG' ) && APPPRESSER_DEBUG );
	}

	public function send_post_request( $action, $data ) {
		// Build url
		$url  = $this->api_url . $action;

		// Encode our data
		$data = json_encode( array( 'request' => $data ) );

		// Define the args for the API
		$args['body'] = $data;
		$args['headers']['Content-Type'] = 'Content-Type: application/json';

		// call the API via the WP HTTP API
		$response = wp_remote_post( esc_url_raw( $url ), $args );
		$response = wp_remote_retrieve_body( $response );

		if ( ! $response )
			return false;

		return $response;

	}

	public function get_devices_by_user_id( $user_ids = array() ){

		$ap3_key = appp_get_setting( 'notifications_ap3_key' );
		$site_slug = appp_get_setting( 'ap3_site_slug' );
		$app_id = appp_get_setting( 'ap3_app_id' );

		// If using AP3, get arns instead of device ids
		if( isset( $ap3_key ) && isset( $site_slug ) && isset( $app_id ) ) {
			$meta_key = "ap3_endpoint_arns";
		} else {
			$meta_key = "appp_push_device_id";
		}

		$device_ids = null;

		$_user_ids = array();
		
		foreach ( $user_ids as $user ) {
			if( is_object( $user ) ) {
				$_user_ids[] = $user->user_id;
			} else {
				$_user_ids[] = $user;
			}
		}

		if( !empty( $_user_ids ) ) {
			global $wpdb;

			// only accept INT in sql
			$_user_ids = array_map('absint', $_user_ids);
			$_user_ids = implode(',', $_user_ids);
			$sql = "SELECT `meta_value` FROM $wpdb->usermeta WHERE `meta_key` = '$meta_key' AND `user_id` IN ($_user_ids)";
			$results = $wpdb->get_results( $sql, ARRAY_N );

			if( $results ) {

				$device_ids = array();
				// make sure we don't have any multidimensional arrays
				foreach ($results as $key => $value) {
					
					if( is_array( $value ) ) {

						$value = $value[0];
						
						if( is_serialized( $value ) ) {
							$value = unserialize( $value );
							$device_ids = array_merge($device_ids, $value);
						} else {
							array_push($device_ids, $value);
						}						
					} else {
						array_push($device_ids, $value);
					}
				}
			}
		}

		return $device_ids;
	}
	
	/**
	 * Compile the arguments for the send_post_request function
	 * 
	 * @param $send_date string 'now'
	 * @param $content string
	 * @param $badges int The number of badges to show
	 * @param $devices array An array of device ids
	 * @param $data array Custom data
	 * @param $custom_url string
	 * @param $custom_page string
	 * @param $segment string AWS topic arn
	 * @param $target string _blank, _self, _system
	 * 
	 **/
	public function notification_send( $send_date, $content, $badges, $devices = array(), $data = array(), $custom_url = '', $custom_page = '', $segment = '', $target = '' ) {

		global $post;

		$ap3_key = appp_get_setting( 'notifications_ap3_key' );
		$site_slug = appp_get_setting( 'ap3_site_slug' );
		$app_id = appp_get_setting( 'ap3_app_id' );

		// send push via AP3 API
		if( $ap3_key ) {

			$site_slug = str_replace('/','',$site_slug);

			$api_domain = (defined('MYAPPPRESSER_DEV_DOMAIN')) ? MYAPPPRESSER_DEV_DOMAIN : 'https://myapppresser.com/';

			$api = $api_domain . $site_slug . '/wp-json/ap3/v1/';
			
			$send_endpoint = $api . 'send';

			if( isset( $_POST['notify_url'] ) ) {

				if( strpos( $_POST['notify_url'], 'http') === 0 ) {
					$custom_url = $_POST['notify_url'];
					$blog_url = get_bloginfo( 'url' );
					$target = ( strpos($custom_url, $blog_url) === 0 ) ? '_self' : '';
				} else {
					$custom_page = $_POST['notify_url'];
				}
			}

			$title = ( isset( $_POST['post_title'] ) ) ? $_POST['post_title'] : '';

			// Encode our data
			$data = array( 
				'id' => $app_id,
				'key' => $ap3_key,
				'message' => $content,
				'page' => $custom_page,
				'title' => $title,
				'url'  => $custom_url,
				'device_arns' => $devices,
				'segment' => $segment,
				'target' => $target
			);

			$data = apply_filters( 'ap3_send_push_data', $data );

			// Define the args for the API
			$args['body'] = $data;
			// $args['headers']['Content-Type'] = 'Content-Type: application/json';

			// call the API via the WP HTTP API
			$response = wp_remote_post( esc_url_raw( $send_endpoint ), $args );
			$response = wp_remote_retrieve_body( $response );

			// bail here so we don't send through Pushwoosh also
			return $response;
		}

		$pw_auth = appp_get_setting( 'notifications_pushwoosh_api_key' );

		if ( $pw_auth ) {

			$pw_application = appp_get_setting( 'notifications_pushwoosh_app_key' );

			// free == 'on', paid == false
			$pw_free_account = appp_get_setting( 'notifications_pushwoosh_account_type' );
			
			$notifications = array(
				'send_date'  => $send_date, // now
				'content'    => $content,
				'ios_badges' => absint( $badges ),
				//'data'       => json_encode(array( 'custom' => 'json data' )),
			);

			if( !empty( $custom_url ) &&  ! $pw_free_account ) {

				// this domain, load with ajax
				$parsed_url = parse_url( $custom_url, PHP_URL_HOST );
				$site_url   = parse_url( get_site_url(), PHP_URL_HOST );
				if( $parsed_url && $site_url && $parsed_url == $site_url ) {
					$notifications['data'] = '{"custom":{"page_ajax_url":"'.$custom_url.'"}}';
				} else {
					$notifications['data'] = '{"custom":{"page_noajax_url":"'.$custom_url.'"}}';
				}
			}

			// check if devices to send notification only to these devices 
			if( $devices ){
				if( $devices[0] == '0' )
					wp_die();
				$notifications = array_merge( $notifications, array( 'devices' => $devices) );
			}
			

			$arr_message = array(
				'application'   => $pw_application,
				'auth'          => $pw_auth,
				'notifications' => array( $notifications ) 
			);

			/*print_r($arr_message);
			exit();*/

			$response = $this->send_post_request( 'createMessage', $arr_message );

			if ( $this->is_debug() ) {

				wp_die( '<xmp>$response: '. print_r( $response, true ) .'</xmp>' );

			}

		}

	}

	public static function is_using_segments() {

		if( self::$is_using_segments === null ) {
			self::$is_using_segments = (appp_get_setting( 'notifications_allow_segments' ) == "1");
		}

		return self::$is_using_segments;
	}

	public function send_push_notification( $post_id, $post ) {

		$message = $post->post_title;
		$custom_url = '';
		$custom_page = null;
		$target = '';

		// Add excerpt to message if it exists
		if ( ! empty( $post->post_excerpt ) )
			$message .= ' - ' . $post->post_excerpt;
		
		if( $post->post_type == AppPresser_Notifications::$cpt) {
			if( isset($_POST['notify_url']) && !empty($_POST['notify_url'])) {
				
				if( strpos( $_POST['notify_url'], 'http') === 0 ) {
					$custom_url = $_POST['notify_url'];
				} else {
					$custom_page = $_POST['notify_url'];
				}

			}

		} else {

			$send_push = get_post_meta( $post_id, self::$send_meta_key, true );

			if( $send_push == "1" ) {
				$custom_url = get_permalink( $post_id );
				$target = '_self';
			} else {
				// Don't send a notification if the checkbox wasn't checked
				return;
			}
		}

		// Allow message filtering
		$message = apply_filters( 'send_push_post_content', $message, $post_id, $post );
		$custom_url = apply_filters( 'send_push_custom_url', $custom_url, $post_id, $post );
		$target = apply_filters( 'send_push_custom_url_target', $target, $post_id, $post, $custom_url );

		$segment = ( !empty( $_POST['segments'] ) ? $_POST['segments'] : get_post_meta( $post_id, '_appp_segment', true ) );

		$device_ids = $this->get_device_ids( $post );

		$notifications_ap3_key = appp_get_setting('notifications_ap3_key');

		// if using AP3, just send push, skip all segmenting checks below
		if( !empty( $notifications_ap3_key ) ) {
			$this->notification_send( 'now', $message, 1, $device_ids, null, $custom_url, $custom_page, $segment, $target );
			return;
		}

		// if using segments and no subscriptions found show an admin notice
		if( $device_ids === false ) {
			// a long workaround to display the admin notice
			add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
		} else {
			$this->notification_send( 'now', $message, 1, $device_ids, null, $custom_url, '', $segment, $target );

			if( !empty( $device_ids ) ) {
				self::$notification_count = count($device_ids);
				add_filter( 'redirect_post_location', array( $this, 'add_notice_device_count_query_var' ), 99 );
			} else {
				add_filter( 'redirect_post_location', array( $this, 'add_notice_send_to_all_query_var' ), 99 );
			}
		}
	}

	public function send_failed_no_subscribers() {
		if ( ! isset( $_GET['send_push_failed'] ) ) {
			return;
		}
		echo '<div id="message" class="update-nag notice"><p>'. sprintf( __( 'Since you have <a href="%s" target="_blank">segmented notifications enabled</a>, the notification did not send because there were no subscribers to any of the selected categories.  You must either select a category to which users subscribe or select the "Send this push to everyone" checkbox before sending.  To try again, you can set this post status to draft, press update, modify your settings and publish again.', 'apppresser-push' ), admin_url('admin.php?page=apppresser_settings&tab=tab-appp-notifications') ).'</p></div>';
	}

	public function add_notice_device_count() {
		if ( ! isset( $_GET['add_notice_device_count'] ) || ! is_numeric( $_GET['add_notice_device_count'] ) ) {
			return;
		}
		echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'. sprintf( _n( 'Since you have <a href="%s" target="_blank">segmented notifications enabled</a>, the notification was sent to %s device.', 'Since you have <a href="%s" target="_blank">segmented notifications enabled</a>, the notification was sent to %s devices.', (int)$_GET['add_notice_device_count'], 'apppresser-push' ), admin_url('admin.php?page=apppresser_settings&tab=tab-appp-notifications'), $_GET['add_notice_device_count'] ).'</p></div>';
	}

	public function add_notice_sent_to_all() {
		if ( ! isset( $_GET['sent_to_all'] ) ) {
			return;
		}
		echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'. __( 'The notification was sent to all devices.', 'apppresser-push' ).'</p></div>';
	}

	/**
	 * If user has entered a AppPresser Notification Key,
	 * let them know they need to add the site slug and app id too.
	 */
	public function add_notice_missing_myapp_settings() {
			if( appp_get_setting( 'notifications_ap3_key' ) && ! $this->verify_myapppresser_settings() ) {
				echo '<div id="message" class="updated error notice-success is-dismissible"><p>'. sprintf( __( '<b>AppBuddy:</b> You have added an <b>AppPresser Notifications Key</b>, so you must also enter your <b>Site slug</b> and <b>App ID</b> to send push notifications through your myapppresser.com account. %sAppPresser Settings%s', 'apppresser-push' ), '<a href="' .admin_url('admin.php?page=apppresser_settings') . '">', '</a>').'</p></div>';
			}
	}

	public function verify_myapppresser_settings() {
		$ap3_key = appp_get_setting( 'notifications_ap3_key' );
		$site_slug = appp_get_setting( 'ap3_site_slug' );
		$app_id = appp_get_setting( 'ap3_app_id' );

		$auto_load = true;

		if ( $ap3_key && $site_slug && $app_id ) {
			if( ! get_option( 'verify_ap3_push_settings' ) )
				update_option( 'verify_ap3_push_settings', true, $auto_load );
			return true;
		} else {
			update_option( 'verify_ap3_push_settings', false, $auto_load );
			return false;
		}
	}

	public function add_notice_query_var( $location ) {
		remove_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
		return add_query_arg( array( 'send_push_failed' => '1' ), $location );
	}

	public function add_notice_device_count_query_var( $location ) {
		remove_filter( 'redirect_post_location', array( $this, 'add_notice_device_count_query_var' ), 99 );
		return add_query_arg( array( 'add_notice_device_count' => self::$notification_count ), $location );
	}

	public function add_notice_send_to_all_query_var( $location ) {
		remove_filter( 'redirect_post_location', array( $this, 'add_notice_send_to_all_query_var' ), 99 );
		return add_query_arg( array( 'sent_to_all' => '1' ), $location );
	}

	/**
	 * Gets the device id based on whether the admin chooses to use segments and based
	 * user's preferences
	 * 
	 * @param $post object A post
	 * 
	 * @return array|boolean Either an empty array, an array of strings of device ids or false
	 */
	public function get_device_ids( $post ) {
		if( ! is_object( $post ) || ! $this->is_using_segments() || $this->is_send_to_all( $post ) ) {
			// not using segments so we don't need to worry about device ids
			return array();
		} else {
			$device_ids = AppPresser_Notifications_Segments::get_device_ids_by_post( $post );
			if( empty( $device_ids ) ) {
				// an empty array && using segments so return false so we don't send a notification
				return false;
			} else {
				return $device_ids;
			}
		}
	}

	public function is_send_to_all( $post ) {
		$checked = get_post_meta( $post->ID, self::$bypass_meta_key, true );

		return ($checked === '1');
	}

	public function add_meta_box( $post ) {

		// Don't show metabox once post has been published
		if ( 'publish' == $post->post_status )
			return;

		add_meta_box(
			'appnotifications',
			__( 'AppPush', 'apppresser-push' ),
			array( $this, 'notification_metabox' ),
			$post->post_type,
			'side',
			'high'
		);

	}

	public function notification_metabox( $post ) {

		$post_type = get_post_type_object( $post->post_type );

		$checked = get_post_meta( $post->ID, self::$send_meta_key, true );

		$notifications_ap3_key = appp_get_setting('notifications_ap3_key');

		if( $post->post_type != 'apppush' || self::is_using_segments() ) {
			echo '<table id="segments-new-post-options" class="widefat">';

			if( $post->post_type != 'apppush' ) {
				echo '<tr><td><input type="checkbox" name="'.self::$send_meta_key.'" value="1" '.checked( $checked, "1", false ).'></td><td>'. sprintf( __( 'Send a push notification when this %s is published.', 'apppresser-push' ), $post_type->labels->singular_name );
				echo wp_nonce_field( 'push_text_description', 'apppush_nonce' ) . '</td></tr>';
			}

			// v2
			if( empty( $notifications_ap3_key ) && self::is_using_segments() ) {
				$checked = get_post_meta( $post->ID, self::$bypass_meta_key, true );
				echo '<tr><td><input type="checkbox" name="'.self::$bypass_meta_key.'" value="1" '.checked( $checked, "1", false ).'></td><td>'. sprintf( __( 'Send this push to everyone', 'apppresser-push' ), $post_type->labels->singular_name ) . '</td></tr>';
			}

			// v3
			if( $notifications_ap3_key ) {
				$segments = AppPresser_Notifications_CPT::get_segments();
				if( $segments ) {
					echo '<tr><td colspan="2">';
						$segment_cat = get_post_meta( $post->ID, '_appp_segment', true );
						AppPresser_Notifications_CPT::get_segments_dropdown( $segments, $segment_cat );
					echo '</td></tr>';
				}
			}

			echo '</table>';
		} else if( !empty( $notifications_ap3_key ) ) {
			echo '<tr><td><input type="hidden" name="'.self::$send_meta_key.'" value="1"></td><td>'. sprintf( __( 'Publishing this %s will send a push notification.', 'apppresser-push' ), $post_type->labels->singular_name ) . '</td></tr>';
		}


	}

	public function set_postmeta_send( $post_id, $post, $update = null ) {

	    // Check if our nonce is set.
		if ( ! isset( $_POST['apppush_nonce'] ) )
			return;

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['apppush_nonce'], 'push_text_description' ) )
			die('nonce invalid');

	    if( isset( $_POST[self::$send_meta_key] ) && $_POST[self::$send_meta_key] == '1' ) {
			update_post_meta( $post_id, self::$send_meta_key, '1' );
		} else {
			delete_post_meta( $post_id, self::$send_meta_key );
		}

		if( isset( $_POST[self::$bypass_meta_key] ) && $_POST[self::$bypass_meta_key] == '1' ) {
			update_post_meta( $post_id, self::$bypass_meta_key, '1' );
		} else {
			delete_post_meta( $post_id, self::$bypass_meta_key );
		}

		if( isset( $_POST['segments'] ) ) {
			update_post_meta( $post_id, '_appp_segment', $_POST['segments'] );
		} else {
			delete_post_meta( $post_id, '_appp_segment' );
		}
	}

	public function send_notify_on_publish( $post_id, $post = null ) {

		if( $post === null ) {
			$post = get_post( $post_id );
		}

		$send_push_notification = get_post_meta( $post_id, self::$send_meta_key, true );

		if( $post->post_status == 'publish' && ( $send_push_notification == '1' || $post->post_type == AppPresser_Notifications::$cpt ) ) {
			// send push notification
			$this->send_push_notification( $post_id, $post );
		}
	}

	public function save( $post_id, $post ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['apppush_nonce'] ) )
			return;

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['apppush_nonce'], 'push_text_description' ) ) 
			return;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if( isset( $_POST[self::$send_meta_key] ) && $_POST[self::$send_meta_key] == '1' ) {
			update_post_meta( $post_id, self::$send_meta_key, '1' );
		}

		if( isset( $_POST[self::$bypass_meta_key] ) && $_POST[self::$bypass_meta_key] == '1' ) {
			update_post_meta( $post_id, self::$bypass_meta_key, '1' );
		}

		if( isset( $_POST['segments'] ) ) {
			update_post_meta( $post_id, '_appp_segment', $_POST['segments'] );
		}

		// send push notification
		$this->send_push_notification( $post_id, $post );

	}

}


/**
 * apppush_send_notification function.
 * 
 * @access public
 * @param mixed $content
 * @return void
 */
function apppush_send_notification( $content ) {

	if( empty( $content ) ) return;
		
	$appp_push = new AppPresser_Notifications_Update();
	$appp_push->notification_send( 'now', $content, 1 );
	
	do_action( 'apppush_send_notification', $content );
}