<?php

class AppPresser_GeoLocation_Settings {

	public function hooks() {

		// Add setting rows to Apppresser settings
		add_action( 'apppresser_add_settings', array( $this, 'geolocation_settings' ), 30 );
		add_filter( 'apppresser_sanitize_setting', array( $this, 'delete_cookie' ), 10, 2 );
		add_action( 'appp_admin_settings_head', array( $this, 'enqueue_admin' ) );
		add_filter( 'apppresser_field_markup_geolocation_time_x', array( $this, 'geolocation_time_x' ), 10, 4 );
		add_filter( 'apppresser_setting_default', array( $this, 'set_defaults' ), 10, 3 );
	}

	// Geolocation settings Settings
	public function geolocation_settings( $appp ) {

		$appp->add_setting_tab( __( 'Geolocation', 'apppresser-geolocation' ), 'appp-geo' );

		$appp->add_setting( AppPresser_Geolocation::APPP_KEY, __( 'App-Geolocation License Key', 'appgeo' ), array( 'type' => 'license_key', 'tab' => 'appp-geo', 'helptext' => __( 'Adding a license key enables automatic updates.', 'appgeo' ) ) );

		$appp->add_setting( 'geolocation_map', __( 'Show map on posts with geolocation data', 'appgeo' ), array( 'type' => 'checkbox', 'tab' => 'appp-geo' ) );
		$appp->add_setting( 'geolocation_track_users', __( 'Save user geolocation data', 'appgeo' ), array( 'type' => 'checkbox', 'tab' => 'appp-geo' ) );
		$appp->add_setting( 'geolocation_time_x', __( 'Frequency to save a user\'s geolocation data.', 'appgeo' ), array(
			'type' => 'geolocation_time_x',
			'tab' => 'appp-geo',
			'helptext' => __( 'Note: If you change this value, the individual cookies per user will not be set to the new value until their existing cookies expire.', 'appgeo' ),
			// 'description' => __( 'Sets the cookie expiration value', 'appgeo' ),
		) );

		$appp->add_setting( 'googlemap_api_key', __( 'Google Map API Key', 'appgeo'), array( 'type' => 'text', 'tab' => 'appp-geo', 'description' => sprintf( __( 'Need a <a href="%s" target="_blank">Google Map API key</a>?', 'appgeo'), 'http://docs.apppresser.com/article/124-appgeolocation' ) ) );

	}

	public function geolocation_time_x( $field, $key, $value, $args ) {
		$field = sprintf( '<input class="regular-text small-text" type="number" placeholder="#" id="apppresser--%1$s" name="appp_settings[%2$s]" value="%3$s" />'."\n", $key, $key, $value );

		$options = array(
			'minutes' => MINUTE_IN_SECONDS,
			'hours'   => HOUR_IN_SECONDS,
			'days'    => DAY_IN_SECONDS,
			'weeks'   => WEEK_IN_SECONDS,
			'years'   => YEAR_IN_SECONDS,
		);
		$current = absint( AppPresser_Admin_Settings::settings( 'geolocation_time_incr', HOUR_IN_SECONDS ) );

		$field .= '
		<select id="apppresser--geolocation_time_incr" name="appp_settings[geolocation_time_incr]" >'."\n";

		$opts = array();
		foreach ( $options as $name => $value ) {
			$opts[ $name ] = '<option value="'. $value .'" '. selected( $value, $current, false ) .'>'. esc_html( $name ) .'</option>'."\n";
		}
		$field .= implode( "\n", $opts );
		$field .= '</select>'."\n";
		$field .= '<p class="description">'. $args['description'] .'</p>'."\n";

		return $field;

	}

	public function set_defaults( $value, $key ) {
		switch ( $key ) {
			case 'geolocation_time_x':
				return $value ? absint( $value ) : 1;
			// case 'geolocation_time_x':
			// 	return $value ? absint( $value ) : 1;
		}

		return $value;
	}

	public function delete_cookie( $value, $key ) {

		// Delete my cookie
		if ( $key === 'geolocation_time_x' ) {
			setcookie( 'Appp_Geolocation', 'true', time() - DAY_IN_SECONDS );
		}

		return $value;
	}

	public function enqueue_admin() {
		wp_enqueue_script( 'app-geo-admin', AppPresser_Geolocation::$plugin_url .'js/appp-geolocation-admin.js', array( 'appp-admin' ), AppPresser_Geolocation::VERSION );
	}

}
