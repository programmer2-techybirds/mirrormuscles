<?php
/*
Plugin Name: AppPush
Plugin URI: http://apppresser.com
Description: Push Notifications for AppPresser
Text Domain: apppresser-push
Domain Path: /languages
Version: 3.2.0
Author: AppPresser Team
Author URI: http://apppresser.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class AppPresser_Notifications {

	// A single instance of this class.
	public static $instance    = null;
	public static $this_plugin = null;
	public static $plugin_url  = null;
	public static $plugin_path = null;
	public static $sc_done     = false;
	public static $cpt         = 'apppush';
	const APPP_KEY             = 'apppush_key';
	const PLUGIN               = 'AppPush';
	const VERSION              = '3.2.0';

	/**
	 * Creates or returns an instance of this class.
	 * @since  0.1.0
	 * @return AppPresser_Notification A single instance of this class.
	 */
	public static function go() {
		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;
	}

	public function __construct() {

		self::$this_plugin = plugin_basename( __FILE__ );
		self::$plugin_url  = trailingslashit( plugins_url( '' , __FILE__ )  );
		self::$plugin_path = plugin_dir_path( __FILE__ );

		// is main plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'apppresser/apppresser.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'apppresser_required' ) );
			return;
		}

		// Load translations
		load_plugin_textdomain( 'apppresser-push', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		add_action( 'plugins_loaded', array( $this, 'update_db_check' ), 11 );

		// Add cordova notifications plugins  - probably don't need this, notifications scripts are all local to the extensions
		// add_filter( 'apppresser_phonegap_plugins_include', array( $this, 'phonegap_notifications' ), 10, 2 );

		// Register notifications js
		add_action( 'wp_enqueue_scripts', array( $this, 'push_scripts_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_script_styles' ) );

 		// Save registered device id
		add_action( 'wp_ajax_appp_push_device_id', array( $this, 'appp_push_device_id' ) );
		add_action( 'wp_ajax_nopriv_appp_push_device_id', array( $this, 'appp_push_device_id' ) );

		// Save registered device id (AP3)
		add_action( 'wp_ajax_ap3_add_device_id', array( $this, 'ap3_add_device_id' ) );
		add_action( 'wp_ajax_nopriv_ap3_add_device_id', array( $this, 'ap3_add_device_id' ) );

	}

	/* Add device for AP2
	*/
	public function appp_push_device_id(){

		global $user_ID;
		if ( $user_ID ) {
			if ( isset( $_POST['device_id'] ) && self::set_user_device_id( $_POST['device_id'] ) ) {
				wp_send_json_success( $_POST['device_id'] );
			} else {
				wp_send_json_error( 'failed to save device id' );
			}
		}
	}

	/* Add device for AP3
	*/
	public function ap3_add_device_id(){

		global $user_ID;
		if ( $user_ID ) {
			if ( isset( $_GET['endpoint'] ) ) {
				update_user_meta( $user_ID, 'ap3_endpoint_arns', $_GET['endpoint'] );
				wp_send_json_success( $_GET['endpoint'] );
			} else {
				wp_send_json_error( 'Failed to save device id.' );
			}
		}

	}

	/**
	 * Adds device id to any existing ids to the user meta
	 * 
	 * @since 2.2.0
	 * 
	 * @return array|boolean returns an array of device ids or false
	 */
	public static function set_user_device_id( $device_id ) {

		global $user_ID;

		if( $user_ID && !empty( $device_id ) ) {
			$device_ids = self::get_user_device_ids();

			// make sure it's not already there
			if( ! in_array($device_id, $device_ids ) ) {
				array_push( $device_ids, sanitize_text_field( $device_id ) );
				update_user_meta( $user_ID, 'appp_push_device_id', $device_ids );
			}

			return $device_ids;
		}

		return false;
	}

	/**
	 * Gets the device ids from the usermeta
	 * 
	 * @param int $user_id
	 * 
	 * @since 2.2.0
	 * 
	 * @return array May be an empty array if usermeta doesn't exist
	 */
	public static function get_user_device_ids( $user_id = null ) {

		if( $user_id === null ) {
			global $user_ID;
			$user_id = $user_ID;
		}

		if( $user_id ) {
			$device_ids = get_user_meta( $user_id, 'appp_push_device_id', true );
			if( $device_ids ) {
				$device_ids = maybe_unserialize( $device_ids );
				if( is_array( $device_ids ) ) {
					return $device_ids;
				} else {
					// maybe a string if < v2.2.0 when only once device id per user was saved
					return array( $device_ids );
				}
			}
		}

		return array();
	}

	public function includes() {

		appp_updater_add( __FILE__, self::APPP_KEY, array(
			'item_name' => self::PLUGIN,
			'version'   => self::VERSION,
		) );

		// Push notification settings
		$this->classy( 'push_settings', 'Settings', $this->api_ready() );

		// CPT registration
		$this->classy( 'push_cpt', 'CPT' );
		// Push notification
		$this->classy( 'push_update', 'Update' );

		if ( $this->api_ready() ) {
			// Segment signup filter
			$this->classy( 'push_segment', 'Segments' );
		}

	}

	/**
	 * Include and initiate class files
	 * @since  1.0.0
	 */
	public function classy( $var, $class, $config = null ) {

		$class = "AppPresser_Notifications_{$class}";
		require_once( self::$plugin_path ."inc/{$class}.php" );
		$this->$var = new $class( $config );
		$this->$var->hooks();

	}

	/**
	 * Checks if pushwoosh api key is saved
	 * @since  1.0.0
	 * @return bool  True if api key is saved
	 */
	public function api_ready() {
		// return !! appp_get_setting( 'notifications_pushwoosh_api_key' );
		return ( !! appp_get_setting( 'notifications_pushwoosh_api_key' ) ) || ( !! appp_get_setting( 'notifications_ap3_key' ) );
	}

	/**
	 * Notice if AppPresser Core is not installed (and deactivate)
	 * @since  1.0.0
	 */
	public function apppresser_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the AppPresser Core plugin to be installed/activated. %1$s has been deactivated.', 'apppresser-push' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}

	/**
	 * Set the notifications text when activating the plugin (if not set)
	 * @since  1.0.0
	 */
	public function activate() {

	}

	/**
	 * Include Phonegap notifications plugins
	 * @since  1.0.0
	 */
	public function phonegap_notifications( $plugins, $os ) {
		// Probably don't need this, notifications scripts are all local to the extensions
		// @todo conditionally include these only when needed
		$plugins[] = 'org.apache.cordova.notifications.Notifications';

		return $plugins;
	}

	/**
	 * Register notifications script
	 * @since  1.0.0
	 */
	public function push_scripts_styles() {
		// Only use minified files if SCRIPT_DEBUG is off
		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		$depends = array( 'jquery' );
		// If cordova is around, make sure it gets listed as a dependency
		if ( wp_script_is( 'cordova-core', 'registered' ) || wp_script_is( 'cordova-core', 'enqueued' ) ) {
			$depends[] = 'cordova-core';
		}

		$pushwoosh_app_code = appp_get_setting( 'notifications_pushwoosh_app_key' );
		$pushwoosh_gcm_id = appp_get_setting( 'notifications_gcm_sender' );

		if ( $pushwoosh_app_code || $pushwoosh_gcm_id ) {

			// This if statement is not necessary since both files are exactly the same, but it is useful code for future use
			if ( appp_is_ios() ) {
				wp_enqueue_script( 'appp_cordova_push', self::$plugin_url ."js/appp-cordova-push$min.js", array( 'cordova-core' ), self::VERSION );
			} elseif ( appp_is_android() ) {
				wp_enqueue_script( 'appp_cordova_push', self::$plugin_url ."js/appp-cordova-push$min.js", array( 'cordova-core' ), self::VERSION );
			}

			wp_enqueue_script( 'appp_pushwoosh', self::$plugin_url ."js/pushwoosh$min.js", array( 'jquery', 'cordova-core', 'appp_cordova_push' ), self::VERSION, true );
			wp_enqueue_script( 'appp_push_init', self::$plugin_url ."js/appp-push-init$min.js", array( 'appp_cordova_push', 'appp_pushwoosh' ), self::VERSION, true );
			wp_enqueue_script( 'appp_segment_push', self::$plugin_url ."js/appp-segment-push$min.js", array( 'jquery' ), self::VERSION );

			// Pass our PHP variables to our JS so we can use them in Javascript
			wp_localize_script( 'jquery', 'apppPushVars', array(
				'app_code' => $pushwoosh_app_code,
				'gcm_id'   => $pushwoosh_gcm_id,
				'notifications_title' => appp_get_setting('notifications_title')
			) );
		}
	}

	/**
	 * Register admin scripts and styles
	 * @since x.x.x
	 */
	public function admin_script_styles() {
		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'appp_segment_push', self::$plugin_url ."js/appp-segment-push$min.js", array( 'jquery' ), self::VERSION );

		wp_register_style( 'apppush_admin', self::$plugin_url."css/appp-push-admin.css", array(), self::VERSION );
		wp_enqueue_style( 'apppush_admin' );
	}

	public function update_db_check() {
		if ( get_site_option( 'apppush_db_version' ) != AppPresser_Notifications::VERSION ) {
			require_once( self::$plugin_path ."inc/AppPresser_Notifications_Install.php" );
			AppPresser_Notifications_Install::install();
		}
	}
}
AppPresser_Notifications::go();
