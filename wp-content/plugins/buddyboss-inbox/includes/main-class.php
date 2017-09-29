<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'BuddyBoss_Inbox_Plugin' ) ) {

	/**
	 *
	 * BuddyBoss Inbox Plugin Main Controller
	 * **************************************
	 *
	 *
	 */
	class BuddyBoss_Inbox_Plugin {
		/* Includes
		 * ===================================================================
		 */

		/**
		 * Most WordPress/BuddyPress plugin have the includes in the function
		 * method that loads them, we like to keep them up here for easier
		 * access.
		 * @var array
		 */
		private $main_includes = array(
			'bbm-frontend',
			'bbm-attachment',
			'bbm-template-extend',
			'bbm-thread-extend',
			'bbm-functions',
			'bbm-ajax',
			'bbm-labels',
			'bbm-drafts',
			'bbm-template'
		);

		/**
		 * Admin includes
		 * @var array
		 */
		private $admin_includes = array(
			'admin'
		);

		/* Plugin Options
		 * ===================================================================
		 */

		/**
		 * Default options for the plugin, the strings are
		 * run through localization functions during instantiation,
		 * and after the user saves options the first time they
		 * are loaded from the DB.
		 *
		 * @var array
		 */
		private $default_options = array(
			'enabled' => true
		);

		/**
		 * This options array is setup during class instantiation, holds
		 * default and saved options for the plugin.
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Is BuddyPress installed and activated?
		 * @var boolean
		 */
		public $bp_enabled = false;


		/* Version
		 * ===================================================================
		 */

		/**
		 * Plugin codebase version
		 * @var string
		 */
		public $version = '0.0.0';

		/**
		 * Plugin database version
		 * @var string
		 */
		public $db_version = '0.0.0';

		/* Paths
		 * ===================================================================
		 */
		public $file		 = '';
		public $basename	 = '';
		public $plugin_dir	 = '';
		public $plugin_url	 = '';
		public $includes_dir = '';
		public $includes_url = '';
		public $lang_dir	 = '';
		public $assets_dir	 = '';
		public $assets_url	 = '';

		/* Component State
		 * ===================================================================
		 */
		public $current_type	 = '';
		public $current_item	 = '';
		public $current_action	 = '';
		public $is_single_item	 = false;


		/* Magic
		 * ===================================================================
		 */

		/**
		 * BuddyBoss Inbox uses many variables, most of which can be filtered to
		 * customize the way that it works. To prevent unauthorized access,
		 * these variables are stored in a private array that is magically
		 * updated using PHP 5.2+ methods. This is to prevent third party
		 * plugins from tampering with essential information indirectly, which
		 * would cause issues later.
		 *
		 * @see BuddyBoss_Inbox_Plugin::setup_globals()
		 * @var array
		 */
		private $data;

		/* Singleton
		 * ===================================================================
		 */

		/**
		 * Main BuddyBoss Inbox Instance.
		 *
		 * BuddyBoss Inbox is great
		 * Please load it only one time
		 * For this, we thank you
		 *
		 * Insures that only one instance of BuddyBoss Inbox exists in memory at any
		 * one time. Also prevents needing to define globals all over the place.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @static object $instance
		 * @uses BuddyBoss_Inbox_Plugin::setup_globals() Setup the globals needed.
		 * @uses BuddyBoss_Inbox_Plugin::setup_actions() Setup the hooks and actions.
		 * @uses BuddyBoss_Inbox_Plugin::setup_textdomain() Setup the plugin's language file.
		 * @see buddyboss_messages()
		 *
		 * @return BuddyBoss Inbox The one true BuddyBoss.
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if ( null === $instance ) {
				$instance = new BuddyBoss_Inbox_Plugin();
				$instance->setup();
				$instance->setup_globals();
				$instance->setup_actions();
				$instance->setup_textdomain();
			}

			// Always return the instance
			return $instance;
		}

		/* Magic Methods
		 * ===================================================================
		 */

		/**
		 * A dummy constructor to prevent BuddyBoss Inbox from being loaded more than once.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 * @see BuddyBoss_Inbox_Plugin::instance()
		 * @see buddypress()
		 */
		private function __construct() { /* nothing here */
		}

		/**
		 * A dummy magic method to prevent BuddyBoss Inbox from being cloned.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'buddyboss-inbox' ), '1.7' );
		}

		/**
		 * A dummy magic method to prevent BuddyBoss Inbox from being unserialized.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'buddyboss-inbox' ), '1.7' );
		}

		/**
		 * Magic method for checking the existence of a certain custom field.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __isset( $key ) {
			return isset( $this->data[ $key ] );
		}

		/**
		 * Magic method for getting BuddyBoss Inbox varibles.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __get( $key ) {
			return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
		}

		/**
		 * Magic method for setting BuddyBoss Inbox varibles.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __set( $key, $value ) {
			$this->data[ $key ] = $value;
		}

		/**
		 * Magic method for unsetting BuddyBoss Inbox variables.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __unset( $key ) {
			if ( isset( $this->data[ $key ] ) )
				unset( $this->data[ $key ] );
		}

		/**
		 * Magic method to prevent notices and errors from invalid method calls.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 */
		public function __call( $name = '', $args = array() ) {
			unset( $name, $args );
			return null;
		}

		/* Plugin Specific, Setup Globals, Actions, Includes
		 * ===================================================================
		 */

		private function setup() {
			global $wpdb;

			$db_version = get_option( 'bb-message-db' );

			if ( $db_version !== '1.0' ) {
				add_option( 'bb-message-db', '1.0' );
			}
		}

		/**
		 * Setup BuddyBoss Inbox plugin global variables.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @uses plugin_dir_path() To generate BuddyBoss Inbox plugin path.
		 * @uses plugin_dir_url() To generate BuddyBoss Inbox plugin url.
		 * @uses apply_filters() Calls various filters.
		 */
		private function setup_globals() {

			global $BUDDYBOSS_INBOX;

			$saved_options	 = get_option( 'buddyboss_messages_plugin_options' );
			$saved_options	 = maybe_unserialize( $saved_options );

			$this->options = wp_parse_args( $saved_options, $this->default_options );

			// Normalize legacy uppercase keys
			foreach ( $this->options as $key => $option ) {
				// Delete old entry
				unset( $this->options[ $key ] );

				// Override w/ lowercase key
				$this->options[ strtolower( $key ) ] = $option;
			}

			/** Versions ************************************************* */
			$this->version		 = BUDDYBOSS_INBOX_PLUGIN_VERSION;
			$this->db_version	 = BUDDYBOSS_INBOX_PLUGIN_DB_VERSION;

			/** Paths ***************************************************** */
			// BuddyBoss Inbox root directory
			$this->file			 = BUDDYBOSS_INBOX_PLUGIN_FILE;
			$this->basename		 = plugin_basename( $this->file );
			$this->plugin_dir	 = BUDDYBOSS_INBOX_PLUGIN_DIR;
			$this->plugin_url	 = BUDDYBOSS_INBOX_PLUGIN_URL;

			// Languages
			$this->lang_dir = dirname( $this->basename ) . '/languages/';

			// Includes
			$this->includes_dir	 = $this->plugin_dir . 'includes';
			$this->includes_url	 = $this->plugin_url . 'includes';

			// Templates
			$this->templates_dir = $this->plugin_dir . 'templates';
			$this->templates_url = $this->plugin_url . 'templates';

			// Assets
			$this->assets_dir	 = $this->plugin_dir . 'assets';
			$this->assets_url	 = $this->plugin_url . 'assets';
		}

		/**
		 * Setup BuddyBoss Inbox main actions
		 *
		 * @since  BuddyBoss Inbox 1.0
		 */
		private function setup_actions() {

			add_action( 'wp_before_admin_bar_render', array( $this, 'update_wp_menus' ), 99 );

			// Admin
			add_action( 'init', array( $this, 'setup_admin_settings' ) );

			if ( !$this->is_enabled() )
				return;

			// Hook into BuddyPress init
			add_action( 'bp_init', array( $this, 'bp_loaded' ), 1 );

			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

			add_action( 'bp_setup_nav', array( $this, 'bbg_rename_inbox_subnav' ), 99 );

			/**
			 *
			 * NOTE:
			 * Commit Reverted #d536c396be59f5c292aa6a4146ce3e19ba5f8c53 - "duplicated pagination"
			 *
			 * Not sure why following action was commented out on the first place.
			 * It was breaking pagination on Sendbox/Inbox page
			 *
			 * @todo: Make sure to test for duplicated pagination with all possible theme
			 */
			add_action( 'bp_before_member_messages_threads', 'bbm_inbox_pagination', 99 ); //Sendbox/Inbox pagination
            
            add_filter( 'body_class', array( $this, 'bbm_body_class' ) );
		}
        
        public function bbm_body_class($classes) {
            $classes[] = 'bb-inbox';
            return $classes;
        }

		public function setup_admin_settings() {
			if ( !bp_is_active( 'messages' ) )
				return;
			if ( ( is_admin() || is_network_admin() ) && current_user_can( 'manage_options' ) ) {
				$this->load_admin();
			}
		}

		/**
		 * Load plugin text domain
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses sprintf() Format .mo file
		 * @uses get_locale() Get language
		 * @uses file_exists() Check for language file(filename)
		 * @uses load_textdomain() Load language file
		 */
		public function setup_textdomain() {

			$domain = 'buddyboss-inbox';

			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			//first try to load from wp-content/languages/plugins/ directory
			load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $domain . '-' . $locale . '.mo' );

			//if not found, then load from buddyboss-inbox/languages/ directory
			load_plugin_textdomain( $domain, false, $this->lang_dir );
		}

		/**
		 * We require BuddyPress to run the main components, so we attach
		 * to the 'bp_loaded' action which BuddyPress calls after it's started
		 * up. This ensures any BuddyPress related code is only loaded
		 * when BuddyPress is active.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 * @access public
		 *
		 * @return void
		 */
		public function bp_loaded() {
			global $bp;

			if ( !bp_is_active( 'messages' ) )
				return;

			$this->bp_enabled = true;

			$this->load_main();
		}

		/* Load
		 * ===================================================================
		 */

		/**
		 * Include required admin files.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 * @access private
		 *
		 * @uses $this->do_includes() Loads array of files in the include folder
		 */
		public function load_admin() {
			$this->do_includes( $this->admin_includes );

			$this->admin = BuddyBoss_Inbox_Admin::instance();
		}

		/**
		 * Include required files.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 * @access private
		 *
		 * @uses BuddyBoss_Inbox_Plugin::do_includes() Loads array of files in the include folder
		 */
		private function load_main() {
			$this->do_includes( $this->main_includes );

			BuddyBoss_Inbox_Frontend::instance();

			// if draft feature enabled
			$draft_feature = buddyboss_messages()->option( 'draft_feature' );
			if ( $draft_feature ) {
				BuddyBoss_Inbox_Drafts::instance();
			}

			// if label feature enabled
			$label_feature = buddyboss_messages()->option( 'label_feature' );
			if ( $label_feature == "on" ) {
				BuddyBoss_Inbox_Labels::instance();
			}
		}

		/* Activate/Deactivation/Uninstall callbacks
		 * ===================================================================
		 */

		/**
		 * Fires when plugin is activated
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses current_user_can() Checks for user permissions
		 * @uses check_admin_referer() Verifies session
		 */
		public function activate() {
			if ( !current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$plugin = isset( $_REQUEST[ 'plugin' ] ) ? $_REQUEST[ 'plugin' ] : '';

			check_admin_referer( "activate-plugin_{$plugin}" );
		}

		/**
		 * Fires when plugin is de-activated
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses current_user_can() Checks for user permissions
		 * @uses check_admin_referer() Verifies session
		 */
		public function deactivate() {
			if ( !current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$plugin = isset( $_REQUEST[ 'plugin' ] ) ? $_REQUEST[ 'plugin' ] : '';

			check_admin_referer( "deactivate-plugin_{$plugin}" );
		}

		/**
		 * Fires when plugin is uninstalled
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses current_user_can() Checks for user permissions
		 * @uses check_admin_referer() Verifies session
		 */
		public function uninstall() {
			if ( !current_user_can( 'activate_plugins' ) ) {
				return;
			}

			check_admin_referer( 'bulk-plugins' );

			// Important: Check if the file is the one
			// that was registered during the uninstall hook.
			if ( $this->file != WP_UNINSTALL_PLUGIN ) {
				return;
			}
		}

		/* Utility functions
		 * ===================================================================
		 */

		/**
		 * Include required array of files in the includes directory
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses require_once() Loads include file
		 */
		public function do_includes( $includes = array() ) {
			foreach ( (array) $includes as $include ) {
				require_once( $this->includes_dir . '/' . $include . '.php' );
			}
		}

		/**
		 * Check if the plugin is active and enabled in the plugin's admin options.
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @uses BuddyBoss_Media_Plugin::option() Get plugin option
		 *
		 * @return boolean True when the plugin is active
		 */
		public function is_enabled() {
			$is_enabled = $this->option( 'enabled' ) === true || $this->option( 'enabled' ) === 'on';

			return $is_enabled;
		}

		/**
		 * Convenience function to access plugin options, returns false by default
		 *
		 * @since  BuddyBoss Inbox (1.0.0)
		 *
		 * @param  string $key Option key

		 * @uses apply_filters() Filters option values with 'buddyboss_messages_option' &
		 *                       'buddyboss_messages_option_{$option_name}'
		 * @uses sprintf() Sanitizes option specific filter
		 *
		 * @return mixed Option value (false if none/default)
		 *
		 */
		public function option( $key ) {
			$key	 = strtolower( $key );
			$option	 = isset( $this->options[ $key ] ) ? $this->options[ $key ] : null;

			// Apply filters on options as they're called for maximum
			// flexibility. Options are are also run through a filter on
			// class instatiation/load.
			// ------------------------
			// This filter is run for every option
			$option = apply_filters( 'buddyboss_messages_option', $option );

			// Option specific filter name is converted to lowercase
			$filter_name = sprintf( 'buddyboss_messages_option_%s', strtolower( $key ) );
			$option		 = apply_filters( $filter_name, $option );

			return $option;
		}

		/**
		 * Localizing message js
		 */
		public function js_object() {
			$attach_file_size = bbm_format_size_units();

			$js_object = array(
				'uploader'				 => array(
					'max_file_size'			 => $attach_file_size,
					'multiselect'			 => false,
					'nonce'					 => wp_create_nonce( 'bbm_attachment_upload' ),
					'flash_swf_url'			 => includes_url( 'js/plupload/plupload.flash.swf' ),
					'silverlight_xap_url'	 => includes_url( 'js/plupload/plupload.silverlight.xap' ),
					'filters'				 => array(
						array(
							'title'			 => __( 'Allowed Files', 'buddyboss-inbox' ),
							'extensions'	 => implode( ',', bbm_plupload_file_formats() ),
							'max_file_size'	 => (int) buddyboss_messages()->option( 'attach_file_size' ) . 'mb',
						)
					),
				),
				'selectors'				 => array(
					'form_message'	 => '#send_message_form',
					'form_reply'	 => '#send-reply',
				),
				'lang'					 => array(
					'upload_error'	 => array(
						'file_size'	 => sprintf( __( 'Uploaded file must not be more than %s mb', 'buddyboss-inbox' ), buddyboss_messages()->option( 'attach_file_size' ) ),
						'file_type'	 => sprintf( __( 'Selected file not allowed to be uploaded. It must be one of the following: %s', 'buddyboss-inbox' ), implode( ', ', bbm_plupload_file_formats() ) ),
						'generic'	 => __( 'Error! File could not be uploaded.', 'buddyboss-inbox' ),
					),
					'remove'		 => __( 'Remove', 'buddyboss-inbox' ),
					'uploading'		 => __( 'Uploading...', 'buddyboss-inbox' ),
					'auto_draft_saving'      => __( 'Saving as draft...', 'buddyboss-inbox' ),
				),
				'current_action'		 => bp_current_action(),
				'download_attach'		 => __( 'Download Attachment', 'buddyboss-inbox' ),
				'attachment_feature'	 => buddyboss_messages()->option( 'attachment_feature' ),
				'draft_feature'			 => buddyboss_messages()->option( 'draft_feature' ),
				'draft_autosave'		 => buddyboss_messages()->option( 'draft_autosave' ),
				'editor_feature'		 => buddyboss_messages()->option( 'editor_feature' ),
				'draft_autosave_idle'	 => '60000'
			);

			return apply_filters( 'bb_buddyboss_message_js_object', $js_object );
		}

		/**
		 * Load css/js files
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function assets() {
			if ( !bp_is_active( 'messages' ) ) {
				return;
			}

			// FontAwesome icon fonts. If browsing on a secure connection, use HTTPS.
            // We will only load if our is latest.
            $recent_fwver = (isset(wp_styles()->registered["fontawesome"]))?wp_styles()->registered["fontawesome"]->ver:"0";
            $current_fwver = "4.5.0";
            if(version_compare($current_fwver, $recent_fwver , '>')) {
                wp_deregister_style( 'fontawesome' );
                wp_register_style( 'fontawesome', "//maxcdn.bootstrapcdn.com/font-awesome/{$current_fwver}/css/font-awesome.min.css", false, $current_fwver);
                wp_enqueue_style( 'fontawesome' );
            }

                        

			wp_enqueue_style( 'buddyboss-inbox-main', buddyboss_messages()->assets_url . '/css/buddyboss-inbox.min.css', array(), BUDDYBOSS_INBOX_PLUGIN_VERSION, 'all' );
			//wp_enqueue_style( 'buddyboss-inbox-main', buddyboss_messages()->assets_url . '/css/buddyboss-inbox.css', array(), '1.0.2', 'all' );
                        
			wp_enqueue_style( 'magnific-popup', buddyboss_messages()->assets_url . '/css/magnific-popup.css', array(), '1.0.0', 'all' );
            wp_enqueue_script( 'magnific-popup', buddyboss_messages()->assets_url . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
			
                        $get_js_object = $this->js_object();
			wp_enqueue_script( 'bb-inbox-js', buddyboss_messages()->assets_url . '/js/buddyboss-inbox.min.js', array( 'jquery', 'plupload-all' ), BUDDYBOSS_INBOX_PLUGIN_VERSION, true );
			//wp_enqueue_script( 'bb-inbox-js', buddyboss_messages()->assets_url . '/js/buddyboss-inbox.js', array( 'jquery', 'plupload-all' ), '1.0.2', true );
			wp_localize_script( 'bb-inbox-js', 'bbm_object', $get_js_object );
		}

		public function admin_assets() {
			wp_enqueue_style( 'buddyboss-inbox-admin-css', buddyboss_messages()->assets_url . '/css/bbm-admin.css', array(), BUDDYBOSS_INBOX_PLUGIN_VERSION, 'all' );
		}
		
		/**
		 * Rename Inbox Tab from Messages
		 */
		public function bbg_rename_inbox_subnav() {
			if ( !bp_is_active( 'messages' ) ) {
				return;
			}

			$bp		 = buddypress();
			$count	 = bp_get_total_unread_messages_count();

			if ( $count == '0' ) {
				$count = '';
                $name = 'Inbox <strong>' . $count . '</strong>';
                $this->_edit_bp_options_nav( array( 'name' => $name ) );
			} else {

				if ( function_exists( 'boss' ) ) {
                    $name = 'Inbox <span> (' . $count . ') </span>';
                    $this->_edit_bp_options_nav( array( 'name' => $name ) );
				} else {
                    $name = 'Inbox <span>' . $count . '</span>';
                    $this->_edit_bp_options_nav( array( 'name' => $name ) );
				}
			}
		}
        
        /**
         * A back-compat wrapper for editing subnavs/bp_options_nav
         * @param array $args
         */
        protected function _edit_bp_options_nav( $args, $nav = 'messages', $subnav = 'inbox' ){
            $bp = buddypress();
            $version_compare = version_compare( BP_VERSION, '2.6', '<' );
            if ( $version_compare ){
                foreach( $args as $k => $v ){
                    $bp->bp_options_nav[ $nav ][ $subnav ][ $k ] = $v;
                }
            } else {
                $bp->members->nav->edit_nav( $args, $subnav, $nav );
            }
        }

        public function update_wp_menus() {
			global $wp_admin_bar, $bp;

			if ( !bp_is_active( 'messages' ) ) {
				return;
			}

			$domain = $bp->loggedin_user->domain;

			$draft_feature = buddyboss_messages()->option( 'draft_feature' );

			// ADD DRAFT ITEMS
			if ( is_user_logged_in() && $draft_feature ) {
				$wp_admin_bar->remove_menu( 'my-account-messages-notices' );

				$wp_admin_bar->add_menu( array(
					'parent' => 'my-account-messages',
					'id'	 => 'my-account-messages-drafts',
					'title'	 => __( 'Drafts', 'buddyboss-inbox' ),
					'href'	 => $domain . 'messages/drafts/'
				) );

				$wp_admin_bar->add_menu( array(
					'parent' => 'my-account-messages',
					'id'	 => 'my-account-messages-notices',
					'title'	 => __( 'All Member Notices', 'buddyboss-inbox' ),
					'href'	 => $domain . 'messages/notices/'
				) );
			}
		}

	}

// End class BuddyBoss_Inbox_Plugin
}