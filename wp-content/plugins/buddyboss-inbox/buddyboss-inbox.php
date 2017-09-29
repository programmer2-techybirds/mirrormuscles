<?php
/**
 * Plugin Name: BuddyBoss Inbox
 * Plugin URI:  http://buddyboss.com/product/buddyboss-inbox/
 * Description: Turn BuddyPress Messaging into a state of the art inbox
 * Author:      BuddyBoss
 * Author URI:  http://buddyboss.com
 * Version:     1.1.1
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
  exit;

/**
 * ========================================================================
 * CONSTANTS
 * ========================================================================
 */
// Codebase version
if (!defined( 'BUDDYBOSS_INBOX_PLUGIN_VERSION' ) ) {
  define( 'BUDDYBOSS_INBOX_PLUGIN_VERSION', '1.1.1' );
}

// Database version
if (!defined( 'BUDDYBOSS_INBOX_PLUGIN_DB_VERSION' ) ) {
  define( 'BUDDYBOSS_INBOX_PLUGIN_DB_VERSION', '1' );
}

// Directory
if (!defined( 'BUDDYBOSS_INBOX_PLUGIN_DIR' ) ) {
  define( 'BUDDYBOSS_INBOX_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Url
if (!defined( 'BUDDYBOSS_INBOX_PLUGIN_URL' ) ) {
  $plugin_url = plugin_dir_url( __FILE__ );

  // If we're using https, update the protocol. Workaround for WP13941, WP15928, WP19037.
  if ( is_ssl() )
    $plugin_url = str_replace( 'http://', 'https://', $plugin_url );

  define( 'BUDDYBOSS_INBOX_PLUGIN_URL', $plugin_url );
}

// File
if (!defined( 'BUDDYBOSS_INBOX_PLUGIN_FILE' ) ) {
  define( 'BUDDYBOSS_INBOX_PLUGIN_FILE', __FILE__ );
}

/**
 * ========================================================================
 * MAIN FUNCTIONS
 * ========================================================================
 */

/**
 * Main
 *
 * @return void
 */
function BUDDYBOSS_INBOX_init()
{
  global $bp, $BUDDYBOSS_INBOX;

	if ( !$bp ) {
		add_action('admin_notices','bbm_bp_admin_notice');
		return;
	}

  $main_include  = BUDDYBOSS_INBOX_PLUGIN_DIR  . 'includes/main-class.php';

  try
  {
    if ( file_exists( $main_include ) )
    {
      require( $main_include );
    }
    else{
      $msg = sprintf( __( "Couldn't load main class at:<br/>%s", 'buddyboss-inbox' ), $main_include );
      throw new Exception( $msg, 404 );
    }
  }
  catch( Exception $e )
  {
    $msg = sprintf( __( "<h1>Fatal error:</h1><hr/><pre>%s</pre>", 'buddyboss-inbox' ), $e->getMessage() );
    echo $msg;
  }

  $BUDDYBOSS_INBOX = BuddyBoss_Inbox_Plugin::instance();

}
add_action( 'plugins_loaded', 'BUDDYBOSS_INBOX_init' );

/**
 * Must be called after hook 'plugins_loaded'
 * @return BuddyBoss Inbox Plugin main controller object
 */
function buddyboss_messages()
{
  global $BUDDYBOSS_INBOX,$bp;

  if ( $bp ) {
    if($BUDDYBOSS_INBOX->option( 'label_feature' ) == "on") {
	 if(class_exists("BuddyBoss_Inbox_Labels") AND empty($BUDDYBOSS_INBOX->bp_inbox_labels)){
	    $BUDDYBOSS_INBOX->bp_inbox_labels = BuddyBoss_Inbox_Labels::instance();
	  }
    }
  }

  return $BUDDYBOSS_INBOX;
}

register_activation_hook( __FILE__, 'buddyboss_messages_setup_db_tables' );
/**
 * Setup database table for for label functionality and checks for BP.
 * Runs on plugin activation.
 */
function buddyboss_messages_setup_db_tables( $network_wide=false ) {

	global $bp;
	if ( !$bp ) {
		wp_die( __( 'Please first download and activate BuddyPress plugin', 'buddyboss-inbox' ) );
	}

    buddyboss_messages_create_relationship_table();
    buddyboss_messages_create_labels_table();
    buddyboss_messages_create_drafts_table();
    update_option( 'buddypress_message_db_version', BUDDYBOSS_INBOX_PLUGIN_DB_VERSION );
}

/**
 * Create database table for bb messages label relationship.
 */
function buddyboss_messages_create_relationship_table(){
    global $wpdb;
    $table_name = bp_core_get_table_prefix(). 'bp_messages_label_message';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE " . $table_name . " (
    bbm_label_msg_id bigint(20) NOT NULL AUTO_INCREMENT,
    thread_id bigint(20) NOT NULL,
    user_id bigint(20) NOT NULL,
    label_id bigint(20) NOT NULL,
    PRIMARY KEY  (bbm_label_msg_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create database table for bb messages labels.
 */
function buddyboss_messages_create_labels_table(){
    global $wpdb;
    $table_name = bp_core_get_table_prefix(). 'bp_messages_labels';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE " . $table_name . " (
    bbm_label_id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    label_name varchar(255) DEFAULT NULL,
    label_class varchar(255) DEFAULT NULL,
    PRIMARY KEY  (bbm_label_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create database table for bb messages draft.
 */
function buddyboss_messages_create_drafts_table(){
    global $wpdb;
    $table_name = bp_core_get_table_prefix(). 'bp_messages_drafts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE " . $table_name . " (
    bbm_draft_id bigint(20) NOT NULL AUTO_INCREMENT,
    thread_id bigint(20) NULL,
    user_id bigint(20) NOT NULL,
    recipients varchar(200) NULL,
    draft_subject varchar(200) NULL,
    draft_content longtext NULL,
    draft_attachment longtext,
    draft_date datetime NOT NULL,
    draft_uniqid varchar(200) NULL,
    PRIMARY KEY  (bbm_draft_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function bbm_bp_admin_notice() {
	echo "<div class='error'><p>BuddyBoss Inbox needs BuddyPress activated</p></div>";
}

/**
 * Register BuddyBoss Menu Page
 */
if ( !function_exists( 'register_buddyboss_menu_page' ) ) {

	function register_buddyboss_menu_page() {
		// Set position with odd number to avoid confict with other plugin/theme.
		add_menu_page( 'BuddyBoss', 'BuddyBoss', 'manage_options', 'buddyboss-settings', '', buddyboss_messages()->assets_url . '/images/logo.svg', 61.000129 );

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
//new buddyboss_updater_plugin( 'http://update.buddyboss.com/plugin', plugin_basename(__FILE__), 102);
