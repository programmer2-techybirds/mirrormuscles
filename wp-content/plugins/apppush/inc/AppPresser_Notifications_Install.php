<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AppPresser_Notifications_Install {

	private static $db_updates = array();

	public static function install() {
		global $wpdb;

		if ( ! defined( 'APPPUSH_INSTALLING' ) ) {
			define( 'APPPUSH_INSTALLING', true );
		}

		self::create_tables();

		$current_db_version = get_option( 'apppush_db_version', null );

		if ( ! is_null( $current_db_version ) && version_compare( $current_db_version, AppPresser_Notifications::VERSION, '<' ) ) {
			// admin notice
		} else {
			self::update_db_version();
		}

		self::default_settings();

	}

	public function hooks() {

	}

	public static function default_settings() {
		
		$settings = get_option( 'appp_settings' );

		// Untouched apppush settings won't have the notifications_ap3_key
		if( ( $settings === false ) || ( $settings && !isset( $settings['notifications_ap3_key'] ) ) ) {
			// Add the posts as a chosen post_type
			$settings['notifications_post_types'] = array('post');
			update_option( 'appp_settings', $settings );
		}
	}

	private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );
	}

	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of WordPress 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 *
		 * This may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
		 * indexes first causes too much load on some servers/larger DB.
		 */
		$max_index_length = 191;

		$tables = "
CREATE TABLE {$wpdb->prefix}apppush_subscribe (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  post_type varchar(20) NOT NULL,
  taxonomy varchar(32) NOT NULL,
  term_id bigint(20) NOT NULL,
  PRIMARY KEY  (id)
) $collate;
		";

		return $tables;
	}

	/**
	 * Update DB version to current.
	 * @param string $version
	 */
	public static function update_db_version( $version = null ) {
		delete_option( 'apppush_db_version' );
		add_option( 'apppush_db_version', AppPresser_Notifications::VERSION );
	}
}