<?php
/*
 * Author: AppPresser
 * Author URI: http://appresser.com
 * License: GPLv2
 */


/**
 * AppBuddy_Admin_Settings class.
 *
 * @extends AppBuddy
 */
class AppBuddy_Admin_Settings extends AppBuddy {

	public static $instance = null;


	/**
	 * run function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function run() {
		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;
	}


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'apppresser_add_settings', array( $this, 'appbuddy_settings' ) );
	}


	/**
	 * appbuddy_settings function.
	 *
	 * @access public
	 * @param mixed $appp
	 * @return void
	 */
	public function appbuddy_settings( $appp ) {

		$appp->add_setting( 'paragraph', '',
			array(
				'type' => 'h3',
				'description' => 'AppBuddy enhances BuddyPress in AppPresser apps. Get <a href="http://apppresser.com/extensions/appcamera/" target="_blank">AppCamera</a> for your app and allow your site users to upload images.',
				'tab' => 'appbuddy'
			)
		);

		$appp->add_setting_tab( __( 'AppBuddy', 'appbuddy' ), 'appbuddy' );

		$appp->add_setting( self::APPP_KEY, __( 'AppBuddy License Key', 'appbuddy' ),
			array( 'type' => 'license_key',
			'tab' => 'appbuddy',
			'helptext' => __( 'Adding a license key enables automatic updates.', 'appbuddy' )
			)
		);

		$appp->add_setting( 'appcam_appbuddy', __( 'AppCam', 'appbuddy' ),
			array( 'type' => 'checkbox',
			'tab' => 'appbuddy',
			'helptext' => __( 'Allow users to attach images to status updates.', 'appbuddy' )
			)
		);

		$appp->add_setting( 'appbuddy_disable_activation', __( 'Disable BuddyPress Registration Activation', 'appbuddy' ),
			array( 'type' => 'checkbox',
			'tab' => 'appbuddy',
			'helptext' => __( 'Allow users to skip the activation step where an email confirmation is required when anyone registers.', 'appbuddy' )
			)
		);

		/**
		 * @TODO remove inline-styles when we update AppPresser core
		 */
		$appp->add_setting( 'ap3_bp_me_path', __( 'Use /me/', 'appbuddy' ), 
			array( 'type' => 'checkbox',
			'tab' => 'appbuddy',
			'description' => sprintf( __('%sUse a /me/ URL path part to redirect to BuddyPress pages %sDocumentation%s(AppPresser 3 only)%s', 'appbuddy'), '<ul class="checkbox-description"><li>', '<a href="http://v3docs.apppresser.com/article/327-appbuddy-setup" target="_blank">', '</a></li><li><b>', '</b></li></ul>'),
			'helptext' => __( 'Replace the /me/ part of a URL with the logged-in user\'s username. Requires AppPresser 3.', 'appbuddy' ),
			)
		);

		// check if app push for these options
		if ( class_exists( 'AppPresser_Notifications' ) ) {

			$appp->add_setting( 'apppush_appbuddy', __( 'AppPush', 'appbuddy' ),
				array( 'type' => 'checkbox',
				'tab' => 'appbuddy',
				'helptext' => __( 'Allow BuddyPress friend requests, private and public messages to send push notifications.', 'appbuddy' )
				)
			);

		}

		$override_msg = ( defined('APPP_REMOVE_LOGIN') && APPP_REMOVE_LOGIN === true ) ? '<br><span class="dashicons dashicons-warning"></span><b>'. sprintf( __( 'This setting will be ignored since you have the %s setting in your configuration.', 'appbuddy' ), 'APPP_REMOVE_LOGIN' ) . '</b>' : '';

		$appp->add_setting( 'appbuddy_force_login', __( 'Force login', 'appbuddy' ),
			array(
				'type' => 'radio',
				'tab'  => 'appbuddy',
				'description' =>  __('Force users to login before continuing into your site.', 'appbuddy') . $override_msg,
				'options' => array(
					'on'  => __('On','appbuddy'),
					'off' => __('Off','appbuddy'),
				),
			)
		);
	}

}
AppBuddy_Admin_Settings::run();
