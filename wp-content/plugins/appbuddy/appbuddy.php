<?php
/*
Plugin Name: AppBuddy
Plugin URI: http://apppresser.com
Description: AppBuddy enhances BuddyPress in AppPresser apps.
Text Domain: appbuddy
Domain Path: /languages
Version: 3.2.1
Author: AppPresser Team
Author URI: http://apppresser.com
License: GPLv2
*/


/**
 * AppBuddy class.
 */
class AppBuddy {

	// A single instance of this class.
	public static $instance    = null;
	public static $this_plugin = null;
	const APPP_KEY             = 'appbuddy_key';
	const PLUGIN               = 'AppBuddy';
	const VERSION              = '3.2.1';


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() { $this->init(); }


	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init() {

		self::$this_plugin = plugin_basename( __FILE__ );

		// is main plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'apppresser/apppresser.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'apppresser_required' ) );
			return;
		}

		// is BuddyPress plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'buddypress/bp-loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'buddypress_required' ) );
			return;
		}

		// Load translations
		load_plugin_textdomain( 'appbuddy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// Define plugin constants
		$this->plugin['file'] = plugin_basename( __FILE__ );
		$this->plugin['url']  = trailingslashit( plugins_url( '' , __FILE__ ) );
		$this->plugin['dir']  = trailingslashit( plugin_dir_path( __FILE__ ) );

		// Enqueue scripts & styles
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		add_action( 'bp_include', array( $this, 'bp_includes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ) );

		add_action( 'bp_complete_signup', array( $this, 'redirect_after_registration' ) );
	}


	/**
	 * includes function.
	 *
	 * check if in app or admin and include needed files
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {

		include $this->plugin['dir'] . 'inc/AppBuddy_Customizer.php' ;

		if( is_admin() ) {
			include $this->plugin['dir'] . 'inc/admin/admin.php' ;
		}

		appp_updater_add( __FILE__, self::APPP_KEY, array(
			'item_name' => self::PLUGIN, // must match the extension name on the site
			'version'   => self::VERSION,
		) );

		if( ! class_exists( 'BP_Disable_Activation_Reloaded' ) ) {
			include $this->plugin['dir'] . 'inc/AppBuddy_Deactivate_Activation.php';
			AppBuddy_Deactivate_Activation::get_instance();
		}
	}


	/**
	 * bp_includes function.
	 *
	 * BuddyPress code needs to be loaded on bp_inlude hook
	 *
	 * @access public
	 * @return void
	 */
	public function bp_includes() {
		if( AppPresser::is_app() ) {
			include $this->plugin['dir'] . 'inc/AppBuddy_Modal_Buttons.php' ;
			if( AppPresser::get_apv( 1 ) ) { // only AppPresser v1
				include $this->plugin['dir'] . 'inc/AppBuddy_Blogs.php' ;
			}
			if( AppPresser::get_apv( 3 ) ) {
				include $this->plugin['dir'] . 'inc/AppBuddy_ProfileRedirect.php' ;
			}
			include $this->plugin['dir'] . 'inc/AppBuddy_Template_Stack.php' ;
			include $this->plugin['dir'] . 'inc/AppBuddy_Ajax.php' ;
		}
		include $this->plugin['dir'] . 'inc/AppBuddy_Notifications.php' ;

		include $this->plugin['dir'] . 'inc/AppBuddy_Login.php';

	}


	/**
	 * apppresser_required function.
	 *
	 * deactivate notice if AppPresser is not activated
	 *
	 * @access public
	 * @return void
	 */
	public function apppresser_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the AppPresser Core plugin to be installed/activated. %1$s has been deactivated.', 'appbuddy' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}


	/**
	 * buddypress_required function.
	 *
	 * deactivate notice if AppPresser is not activated
	 *
	 * @access public
	 * @return void
	 */
	public function buddypress_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the BuddyPress plugin to be installed/activated. %1$s has been deactivated.', 'appbuddy' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}


	/**
	 * register_script function.
	 *
	 * register scripts for AppBuddy
	 *
	 * @access public
	 * @return void
	 */
	public function register_script() {
		if( AppPresser::is_app() ) {
			wp_enqueue_script( 'appbuddy', $this->plugin['url'] .'inc/js/appbuddy.js', array( 'jquery' ), self::VERSION, true );

			$loggedin_user_url = ( is_user_logged_in() ) ? bp_loggedin_user_domain() : '';
			wp_localize_script( 'appbuddy', 'app_buddy',  array(
				'loggedin_user_url' => $loggedin_user_url,
				'l18n' => array(
					'login_process'    => __( 'Logging in....', 'appbuddy' ),
					'login_error'      => __( 'Error Logging in.', 'appbuddy' ),
				),
			));
		}
	}

	/**
	 * Redirect after successful registration. Fixes bug where app doesn't reload if using disable email activation plugin.
	 * Use appbuddy_registration_redirect filter to change redirect url
	 * @since 2.2.1
	 * 
	 * @access public
	 * @return void
	 */
	public function redirect_after_registration() {

		if ( has_filter( 'appbuddy_registration_redirect' ) && 'completed-confirmation' == bp_get_current_signup_step() ) {
			wp_redirect( apply_filters( 'appbuddy_registration_redirect', home_url() ) );
		exit;
	}

	}

	/**
	 * register scripts and styles
	 * @since 2.0.4
	 * 
	 * @access public
	 * @return void
	 */
	public function scripts_styles() {
		/* This stylesheet is required to display inline styles from buddypress for the cover image; because BuddyPress
		 * needs a style handle from this plugin. BuddyPress uses wp_add_inline_style( $params['theme_handle'], $inline_css );
		 * to display the styles for #header-cover-image.
		 */ 
		wp_enqueue_style( 'appbuddy-css', $this->plugin['url'] . 'inc/css/style.css', null, self::VERSION );
	}

}
$GLOBALS['AppBuddy'] = new AppBuddy();



function appbuddy_localize_gettext( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'Cancel Friendship Request' :
            $translated_text = __( 'Cancel Request', 'appbuddy' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'appbuddy_localize_gettext', 20, 3 );

// Register the Cover Image feature for Users profiles
function appbuddy_register_feature() {
    /**
     * You can choose to register it for Members and / or Groups by including (or not) 
     * the corresponding components in your feature's settings. In this example, we
     * chose to register it for both components.
     */
    $components = array( 'groups', 'xprofile' );

    // Define the feature's settings
    $cover_image_settings = array(
        'name'     => 'cover_image', // feature name
        'settings' => array(
            'components'   => $components,
            'width'        => 940,
            'height'       => 255,
            'callback'     => 'appbuddy_cover_image',
            'theme_handle' => 'appbuddy-css',
        ),
    );
 
    // Register the feature for your theme according to the defined settings.
    bp_set_theme_compat_feature( bp_get_theme_compat_id(), $cover_image_settings );
}
add_action( 'bp_after_setup_theme', 'appbuddy_register_feature' );


// Example of function to customize the display of the cover image
function appbuddy_cover_image( $params = array() ) {
    if ( empty( $params ) ) {
        return;
    }

    // The complete css rules are available here: https://gist.github.com/imath/7e936507857db56fa8da#file-bp-default-patch-L34
    return '
        /* Cover image */
        #header-cover-image {
            background-image: url(' . $params['cover_image'] . ');
            margin: -6px -10px -10px -10px;
            padding: 8px;
            background-size: cover;
            background-repeat: no-repeat;
        }
    ';
}