<?php
/**
 * Plugin Name: BuddyBoss Reply by Email
 * Plugin URI:  http://www.buddyboss.com/product/buddyboss-reply-by-email/
 * Description: Reply to BuddyPress notifications by email, from messages, activity posts, bbPress forums, and WordPress comments.
 * Author:      BuddyBoss
 * Author URI:  http://buddyboss.com
 * Version:     1.0.3
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * ========================================================================
 * CONSTANTS
 * ========================================================================
 */
// Codebase version
if ( ! defined( 'BUDDYBOSS_RBE_PLUGIN_VERSION' ) ) {
	define( 'BUDDYBOSS_RBE_PLUGIN_VERSION', '1.0.3' );
}

// Database version
if ( ! defined( 'BUDDYBOSS_RBE_PLUGIN_DB_VERSION' ) ) {
	define( 'BUDDYBOSS_RBE_PLUGIN_DB_VERSION', 1 );
}

// Directory
if ( ! defined( 'BUDDYBOSS_RBE_PLUGIN_DIR' ) ) {
	define( 'BUDDYBOSS_RBE_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Url
if ( ! defined( 'BUDDYBOSS_RBE_PLUGIN_URL' ) ) {
	$plugin_url = plugin_dir_url( __FILE__ );

	// If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
	if ( is_ssl() )
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );

	define( 'BUDDYBOSS_RBE_PLUGIN_URL', $plugin_url );
}

// File
if ( ! defined( 'BUDDYBOSS_RBE_PLUGIN_FILE' ) ) {
	define( 'BUDDYBOSS_RBE_PLUGIN_FILE', __FILE__ );
}

/**
 * ========================================================================
 * MAIN FUNCTIONS
 * ========================================================================
 */

$main_include = BUDDYBOSS_RBE_PLUGIN_DIR . 'includes/main-class.php';
try {
		if ( file_exists( $main_include ) ) {
			require( $main_include );
		} else {
			$msg = sprintf( __( "Couldn't load main class at:<br/>%s", 'bb-reply-by-email' ), $main_include );
			throw new Exception( $msg, 404 );
		}
} catch ( Exception $e ) {
		$msg = sprintf( __( "<h1>Fatal error:</h1><hr/><pre>%s</pre>", 'bb-reply-by-email' ), $e->getMessage() );
		echo $msg;
}

add_filter( 'cron_schedules', array("BuddyBoss_RBE_Plugin","cron_schedules") );  
register_activation_hook( __FILE__, array( 'BuddyBoss_RBE_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BuddyBoss_RBE_Plugin', 'deactivate' ) );
 
/**
 * Main
 *
 * @return void
 */

add_action( 'plugins_loaded', 'BUDDYBOSS_RBE_init' );

function BUDDYBOSS_RBE_init() {
	global $BUDDYBOSS_RBE;

	$BUDDYBOSS_RBE = BuddyBoss_RBE_Plugin::instance();

}

/**
 * Must be called after hook 'plugins_loaded'
 * @return BuddyBoss rbe Plugin main controller object
 */
function buddyboss_rbe() {
	
	global $BUDDYBOSS_RBE;
	return $BUDDYBOSS_RBE;
	
}

/**
 * Register BuddyBoss Menu Page
 */
if ( !function_exists( 'register_buddyboss_menu_page' ) ) {

	function register_buddyboss_menu_page() {
		// Set position with odd number to avoid confict with other plugin/theme.
		add_menu_page( 'BuddyBoss', 'BuddyBoss', 'manage_options', 'buddyboss-settings', '', BUDDYBOSS_RBE_PLUGIN_URL . '/assets/images/logo.svg', 61.000129 );

		// To remove empty parent menu item.
		add_submenu_page( 'buddyboss-settings', 'BuddyBoss', 'BuddyBoss', 'manage_options', 'buddyboss-settings' );
		remove_submenu_page( 'buddyboss-settings', 'buddyboss-settings' );
	}

	add_action( 'admin_menu', 'register_buddyboss_menu_page' );
}

/**
 * Allow automatic updates via the WordPress dashboard
 */
require_once('includes/buddyboss-plugin-updater.php');
new buddyboss_updater_plugin( 'http://update.buddyboss.com/plugin', plugin_basename(__FILE__), 148);
