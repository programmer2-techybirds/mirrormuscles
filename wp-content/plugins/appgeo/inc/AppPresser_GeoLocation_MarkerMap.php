<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppGeo_Ajax class.
 *
 * creates ajax endpoints for checking in a user
 */
class AppGeo_MarkerMap {

	public static $geolocations = array();

	public function __construct() {

	}

	private static $_instance = null;

	public static function getInstance () {
		if (self::$_instance === null) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public static function get_users_geo() {
		global $wpdb;

		$sql = "SELECT user_id, meta_key, meta_value FROM $wpdb->usermeta WHERE `meta_key` IN ('appp_latitude', 'appp_longitude')";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $row ) {

			if( ! array_key_exists( 'user_id_'.$row->user_id, self::$geolocations ) ) {
				self::$geolocations['user_id_'.$row->user_id] = new AppGeo_Geolocation( $row->user_id );
			}

			if( $row->meta_key == 'appp_latitude') {
				self::$geolocations['user_id_'.$row->user_id]->add_latitude( $row->meta_value );
			} else if( $row->meta_key == 'appp_longitude') {
				self::$geolocations['user_id_'.$row->user_id]->add_longitude( $row->meta_value );
			}
			
		}
	}

	/**
	 * Get lats and longs
	 * 
	 * @param string $_post_type A comma separated list of post types
	 * 
	 * @return array|boolean return false if param is empty
	 */
	public static function get_geo_by_post_type( $_post_types ) {
		global $wpdb;

		if( empty( $_post_types ) || !is_string( $_post_types ) ) {
			return false;
		}

		$post_type = explode(',', $_post_types);

		// SELECT post_id, meta_key, meta_value, post_type FROM wp_postmeta 
		// 	JOIN wp_posts on ID = post_id
		// WHERE (
		// 	   ( `meta_key` IN ('appp_latitude', 'appp_longitude') AND post_type IN ('post','page') )
		// 	OR ( `meta_key` IN ('latitude', 'longitude') AND post_type IN ('checkin') )
		// 	) AND post_status = 'publish'

		/**
		 * It's a real posibility that the meta_keys latitude and longitude are used
		 * or added by other plugins so we take extra effort here to query them
		 * only if they have the post_type of checkin
		 */

		$where = array(
			'posts' => '',
			'checkins' => '',
			'operator' => '',
		);

		if( !in_array( 'checkin', $post_type ) || count($post_type) >= 2 ) {

			$_in = array();

			// Don't include the checkin post_type
			foreach ($post_type as $key => $value) {
				if( $value != 'checkin' ) {
					$_in[] = $value;
				}
			}

			$where['posts'] = "( `meta_key` IN ('appp_latitude', 'appp_longitude') AND post_type IN ('" . implode("','", $_in) . "') )";
		}

		// Only query lats and longs if 
		if( in_array('checkin', $post_type) ) {
			$where['checkins'] =  "( `meta_key` IN ('latitude', 'longitude') AND post_type IN ('checkin') )";
		}

		if( $where['posts'] && $where['checkins'] ) {
			$where['operator'] = 'OR';
		}

		$sql = "SELECT post_id, meta_key, meta_value, post_type FROM $wpdb->postmeta 
					JOIN $wpdb->posts on ID = post_id
				WHERE (
					{$where['posts']}
					{$where['operator']}
					{$where['checkins']}
					) AND post_status = 'publish'";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $row ) {

			if( ! array_key_exists( 'post_id_'.$row->post_id, self::$geolocations ) ) {
				self::$geolocations['post_id_'.$row->post_id] = new AppGeo_Geolocation( $row->post_id );
			}

			switch ( $row->meta_key ) {
				case 'latitude':
				case 'appp_latitude':
					self::$geolocations['post_id_'.$row->post_id]->add_latitude( $row->meta_value );
					break;

				case 'longitude':
				case 'appp_longitude':
					self::$geolocations['post_id_'.$row->post_id]->add_longitude( $row->meta_value );
					break;

				case 'place':
					self::$geolocations['post_id_'.$row->post_id]->add_place( $row->meta_value );
					break;
				
				case 'place_id':
					self::$geolocations['post_id_'.$row->post_id]->add_place_id( $row->meta_value );
					break;

				case 'address':
					self::$geolocations['post_id_'.$row->post_id]->add_address( $row->meta_value );
					break;
			}
			
		}

		return self::$geolocations;
	}

	public static function get_geolocations( $data = '' ) {

		if( has_filter( 'appp_custom_get_geolocations' ) ) {
			return apply_filters( 'appp_custom_get_geolocations', $data );
		}

		switch ( $data ) {
			case 'checkin':
				self::get_geo_by_post_type('checkin');
				break;

			case 'user':
				self::get_users_geo();
				break;

			case 'all':
				self::get_users_geo();
				self::get_geo_by_post_type('checkin,post,page');
				break;
			
			default: // include any custom post type
				if( !empty($data) && strpos('ckeckin', $data) !== false ) {
					self::get_users_geo();
				}
				if( !empty($data) )
					self::get_geo_by_post_type($data);
				break;
		}

		return self::$geolocations;
	}

	public static function get_map( $data ) {

		$err = self::validate_map_data($data);

		if( is_string( $err ) ) {
			return $err;
		}

	    $css = '
	    <style>
	      #'.$data['selector'].' {
	        padding: 0;
	        margin: -10px -10px 10px -10px;
	        border-width: 1px;
	        border-style: solid;
	        border-color: #ccc #ccc #999 #ccc;
	        -webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	        -moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	        box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
	        width: auto;
	        height: '.((is_numeric($data['height']))?$data['height'].'px':$data['height']).';
	      }
	    </style>';

	    if( $data['zoom'] === 'auto' ) {
			// add ticks because it will be a string in javascript
			$data['zoom'] = '\'auto\'';
		}

		$_js = array(
			"appgeo_markers.ajax_url = '".admin_url('admin-ajax.php')."';",
	    	"appgeo_markers.map_images = '".apply_filters('appgeo_map_images', AppPresser_Geolocation::$plugin_url."images/m")."';",
	    	"appgeo_markers.selector = '".$data['selector']."';",
			"appgeo_markers.zoom = ".$data['zoom'].";",
			"appgeo_markers.source = '".$data['source']."';",
			"appgeo_markers.geolocations_json();",
	    );

	    if(!empty($data['center_lat']) && !empty($data['center_long'])) {
	    	$_js[] = "appgeo_markers.center = {latitude:".$data['center_lat'].",longitude:".$data['center_long']."};";
	    }

		$js = "
		<script>
			". implode("\n			", $_js)."
		</script>";

		return $css.$js;
	}

	public static function validate_map_data($data) {

		if( is_numeric($data['zoom']) && !((int)$data['zoom'] >= 0 && (int)$data['zoom'] <= 20 )) {
			return __('The "zoom" parameter of the [appp-map] needs to be a number between 0 (outerspace) and 20 (street level)', 'appgeo');
		} else if( empty($data['selector'] ) ) {
			return __( 'The "selector" parameter for the [appp-map] shortcode cannot be empty.', 'appgeo' );
		} else if( empty($data['height'] ) ) {
			return __( 'The "height" parameter for the [appp-map] shortcode cannot be empty.', 'appgeo' );
		} else if( !empty($data['zoom']) && $data['zoom'] === "auto" && ( !empty($data['center_lat']) || !empty($data['center_long']) ) ) {
			return __( 'If you are using zoom="auto" you cannot use center_lat="" or center_lat="", because auto zoom will also auto center.');
		} else if( (!empty($data['zoom']) && $data['zoom'] !== "auto") ) {
			if( !empty($data['center_lat']) && empty($data['center_long']) ) {
				return __('If you are using center_lat, you must also use center_long.');
			} else if( !empty($data['center_long']) && empty($data['center_lat']) ) {
				return __('If you are using center_long, you must also use center_lat.');
			} else if( (empty($data['center_lat']) || empty($data['center_long'])) || !is_numeric($data['center_lat']) || !is_numeric($data['center_long']) ) {
				return __('The "center_lat" and "center_long" parameters for the [appp-map] must be coordinates', 'appgeo');
			}
		}

		$test_height = str_replace(array('px', 'em', '%'), '', $data['height']);
		if( ! is_numeric( $test_height ) ) {
			return __( 'The "height" parameter for the [appp-map] shortcode must be a number in pixels, ems or %.', 'appgeo' );
		}

		return true;
	}
}