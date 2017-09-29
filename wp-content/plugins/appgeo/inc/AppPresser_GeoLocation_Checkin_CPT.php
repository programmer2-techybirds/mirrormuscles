<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppBuddy_Ajax class.
 */
class AppGeo_CPT {

	public $longitude = null;
	public $latitude = null;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->setup_actions();
	}


	/**
	 * setup_actions function.
	 *
	 * @access private
	 * @return void
	 */
	private function setup_actions() {
		// Hook into the 'init' action
		add_action( 'init', array( $this, 'checkin_post_type'), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_checkin_metaboxes' ) );
		add_action('admin_menu', array( $this, 'remove_submenus' ) );
		add_action('admin_head', array( $this, 'hide_add_new_button' ) );

	}


	/**
	 * checkin_post_type function.
	 *
	 * @access public
	 * @return void
	 */
	public function checkin_post_type() {

		$labels = array(
			'name'                => _x( 'Checkins', 'Checkins', 'appgeo' ),
			'singular_name'       => _x( 'Checkin', 'Checkin', 'appgeo' ),
			'menu_name'           => __( 'Checkins', 'appgeo' ),
			'name_admin_bar'      => __( 'Checkin', 'appgeo' ),
			'parent_item_colon'   => __( 'Parent Checkin:', 'appgeo' ),
			'all_items'           => __( 'All Checkins', 'appgeo' ),
			'add_new_item'        => __( 'Add Checkin', 'appgeo' ),
			'add_new'             => __( 'Add New', 'appgeo' ),
			'new_item'            => __( 'New Checkin', 'appgeo' ),
			'edit_item'           => __( 'Edit Checkin', 'appgeo' ),
			'update_item'         => __( 'Update Checkin', 'appgeo' ),
			'view_item'           => __( 'View Checkin', 'appgeo' ),
			'search_items'        => __( 'Search Checkin', 'appgeo' ),
			'not_found'           => __( 'Not found', 'appgeo' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'appgeo' ),
		);
		$args = array(
			'label'               => __( 'checkin', 'appgeo' ),
			'description'         => __( 'User Checkins', 'appgeo' ),
			'labels'              => $labels,
			'supports'            => array(''),
			'taxonomies'          => array(''),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-location',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'checkin', $args );

	}


	/**
	 * add_checkin_metaboxes function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_checkin_metaboxes() {
		add_meta_box('checkin_user', 'Checkin Data', array( $this, 'checkin_data'), 'checkin', 'normal', 'default');
    	add_meta_box('checkin_location', 'Checkin Map', array( $this, 'checkin_map'), 'checkin', 'normal', 'default');
	}


	/**
	 * checkin_user function.
	 * 
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	public function checkin_data( $post ) {

		if( ! $post ) return;

		$this->longitude = get_post_meta( $post->ID, 'longitude', 1 );
		$this->latitude = get_post_meta( $post->ID, 'latitude', 1 );
		$place = get_post_meta( $post->ID, 'place', 1 );
		$address = get_post_meta( $post->ID, 'address', 1 );
		$place_id = get_post_meta( $post->ID, 'place_id', 1 );

		$user = get_userdata( $post->post_author );
		echo '<strong>' . $user->user_login . '</strong> - ';
		echo '  Latitude: ' . $this->latitude . '  Longitude: ';
		echo $this->longitude;
		if( $place ) {
			echo '<br>Place: ' . $place;
		}
		if( $address ) {
			echo '<br>Address: ' . $address;
		}
		if( $place_id ) {
			echo '<br>Places ID: ' . $place_id;
		}
	}


	/**
	 * checkin_location function.
	 *
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	public function checkin_map( $post ) {

		if( ! $post ) {
			_e( 'Sorry, no Location was found.', 'appgeo' );
			return;
		}

		$api_key = AppPresser_Admin_Settings::settings( 'googlemap_api_key', '' );
		$api_key = ( $api_key ) ? '&key='.$api_key : '';

		if ( $this->longitude && $this->latitude ) {
			echo '<img id="appp_map_preview_img" src="http://maps.googleapis.com/maps/api/staticmap?zoom=17&size=600x300&maptype=roadmap&markers=color:red%7Ccolor:red%7Clabel:%7C'. $this->latitude .','. $this->longitude . $api_key .'" style="max-width:100%">';
		}
	}


	/**
	 * remove_submenus function.
	 *
	 * @access public
	 * @return void
	 */
	function remove_submenus() {
		global $submenu;
		unset($submenu['edit.php?post_type=checkin'][10]); // Removes 'Add New'.
	}


	/**
	 * hide_add_new_button function.
	 *
	 * @access public
	 * @return void
	 */
	function hide_add_new_button() {

    	if( 'checkin' == get_post_type() ) {
		  echo '<style type="text/css">
		    	#favorite-actions {display:none;}
				.add-new-h2{display:none;}
				.tablenav{display:none;}
		    </style>';
		 }
	}




}