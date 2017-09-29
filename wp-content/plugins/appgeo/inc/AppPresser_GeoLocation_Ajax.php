<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppGeo_Ajax class.
 *
 * creates ajax endpoints for checking in a user
 */
class AppGeo_Ajax {


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
		add_action('wp_ajax_appgeo_checkin', array( $this, 'appgeo_checkin') );
		add_action('wp_ajax_appgeo_geolocations_json', array( $this, 'get_gelocations_json'));
		add_action('wp_ajax_nopriv_appgeo_geolocations_json', array( $this, 'get_gelocations_json'));
	}

	/**
	 * appgeo_checkin function.
	 *
	 * function to validate a user and check them in by adding a check in cpt with coordinate meta
	 *
	 * @access public
	 * @return JSON
	 */
	public function appgeo_checkin() {

		// check_ajax_referer( 'ajax-geo-nonce', 'nonce' );

		if ( ! is_user_logged_in() ) return;

		$user = wp_get_current_user();
		$id = (int) $_POST['id'];
		$latitude = sanitize_text_field( $_POST['latitude'] );
		$longitude = sanitize_text_field( $_POST['longitude'] );
		$place = sanitize_text_field( $_POST['place'] );
		$address = sanitize_text_field( $_POST['address'] );
		$place_id = sanitize_text_field( $_POST['place_id'] );

		if ( $id !== $user->ID ) return;
		
		$title = $user->user_login . __(' checked in ', 'appgeo' );
		
		if( ! empty( $place ) ) 
			$title = $user->user_login . __(' checked into ', 'appgeo' ) . $place;
		
		
		// Create post object
		$checkin = array(
		  'post_title'    => $title,
		  'post_content'  => ' ',
		  'post_status'   => 'publish',
		  'post_author'   => $user->ID,
		  'post_type'	  => 'checkin',
		);

		$checkin = apply_filters( 'appgeo_checkin_post_filter', $checkin );

		// Insert the post into the database
		$post = wp_insert_post( $checkin );

		if( $post ) {

			$checkin_meta = array(
				'latitude'    => $latitude,
				'longitude'  => $longitude,
				'address'   => $address,
				'place'   => $place,
				'place_id' => $place_id,
				'message' => __('checked in.', 'appgeo'),
				'user' => $id,
				'post_id' => $post,
			);

			$checkin_meta = apply_filters( 'appgeo_checkin_meta_filter', $checkin_meta );

			add_post_meta( $post, 'latitude', $checkin_meta['latitude'], true ) || update_post_meta( $post, 'latitude', $checkin_meta['latitude'] );
			add_post_meta( $post, 'longitude', $checkin_meta['longitude'], true ) || update_post_meta( $post, 'longitude', $checkin_meta['longitude'] );
			add_post_meta( $post, 'place', $checkin_meta['place'], true ) || update_post_meta( $post, 'place', $checkin_meta['place'] );
			add_post_meta( $post, 'address', $checkin_meta['address'], true ) || update_post_meta( $post, 'address', $checkin_meta['address'] );
			add_post_meta( $post, 'place_id', $checkin_meta['place_id'], true ) || update_post_meta( $post, 'place_id', $checkin_meta['place_id'] );

			wp_send_json_success( $checkin_meta );

		} else {
			$return = array( 'success' => false );
			wp_send_json_error( $return );
		}


	}

	/**
	 * Gets the geolocations in a Json format
	 * 
	 * @param string $source 'checkin', 'user', or 'all'
	 * 
	 */
	public static function get_gelocations_json( $source = '', $echo = false ) {

		// check_ajax_referer( 'ajax-geo-nonce', 'nonce' );

		if( isset( $_POST['source'] ) ) {
			$source = $_POST['source'];
			$echo = true;
		}

		$geolocations = AppGeo_MarkerMap::get_geolocations( $source );

		$json = array();

		foreach ($geolocations as $geo) {

			if( empty($geo->longitude) && empty($geo->latitude) ) {
				continue;
			}

			$json[] = '{"id":'.$geo->id.','.
					   '"place":"'.$geo->place.'",'.
					   '"place_id":"'.$geo->place_id.'",'.
					   '"address":"'.$geo->address.'",'.
					   '"longitude":'.$geo->longitude.',"'.
					   'latitude":'.$geo->latitude.
					 '}';
		}

		$_json = '{"geolocations": [' . implode(',', $json) . ']}';

		if( $echo ) {
			echo $_json;
			die();
		} else {
			return $_json;
		}
	}

}