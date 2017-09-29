<?php

/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'BuddyBoss_RBE' ) ):

	/**
	 *
	 * BuddyBoss RBE BuddyPress Component
	 * ***********************************
	 */
	class BuddyBoss_RBE {

		/**
		 * INITIALIZE CLASS
		 *
		 * @since BuddyBoss RBE 1.0
		 */
		public function __construct() {
			
		}
		/**
		 * Instance
		 *
		 * @since BuddyBoss RBE 1.0
		 */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if ( null === $instance ) {
				$instance = new BuddyBoss_RBE();
				$instance->setup_actions();
			}

			// Always return the instance
			return $instance;
		}
		

		/**
		 * Convenince method for getting main plugin options.
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 */
		public function option( $key ) {
			return buddyboss_rbe()->option( $key );
		}

		/**
		 * SETUP ACTIONS
		 *
		 * @since  BuddyBoss RBE 1.0
		 */
		public function setup_actions() {
			// Add body class
			add_filter( 'body_class', array( $this, 'body_class' ) );
			
			// Back End Assets
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
			
		}

		/**
		 * Add active RBE class
		 *
		 * @since BuddyBoss RBE (0.1.1)
		 */
		public function body_class( $classes ) {
			$classes[] = apply_filters( 'buddyboss_rbe_body_class', 'bb-reply-by-email' );
			return $classes;
		}

		
		/**
		 * Load Admin Script
		 * @return void 
		 */
		public function admin_assets() {
			wp_enqueue_style( 'buddyboss-rbe-main', buddyboss_rbe()->assets_url . '/css/buddyboss-rbe-admin.css', array(), BUDDYBOSS_RBE_PLUGIN_VERSION, 'all' );
			//wp_enqueue_style( 'buddyboss-rbe-main-admin', buddyboss_rbe()->assets_url . '/css/buddyboss-rbe-admin.min.css', array(), '1.0.0', 'all' );
			wp_enqueue_script( 'jquery-steps', buddyboss_rbe()->assets_url . '/js/jquery.steps.min.js', array( 'jquery' ), '1.0.0', true );
		}

	}

	 //End of class BuddyBoss_RBE_BP_Component
	

endif;

