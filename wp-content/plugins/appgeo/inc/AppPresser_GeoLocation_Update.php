<?php
/**
 * Updates or creates a new post with geolocation post meta
 * @since  1.0.0
 */
class AppPresser_GeoLocation_Update {

	/**
	 * Our WordPress hooks
	 * @since  1.0.1
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'update_post' ), 999 );
		if ( $this->is_debug() ) {
			add_filter( 'the_content', array( $this, 'submitted_data' ), 999 );
		}
	}

	/**
	 * Check if we want to output debug form data to the content
	 * @since  1.0.2
	 * @return boolean True if debug mode is on
	 */
	public function is_debug() {
		return ( isset( $_POST['app_debug'] ) || defined( 'APPPRESSER_DEBUG' ) && APPPRESSER_DEBUG );
	}

	/**
	 * Handles the creation or updating of the geolocation post
	 * @since  1.0.0
	 */
	public function update_post() {
		global $_POST, $user_ID;

		// only run this if geolocation submit button is clicked
		if ( !isset( $_POST['submit_geolocation_post'] ) )
			return;

		// Verify nonce for security
		wp_verify_nonce( 'apppgeolocation-nonce', 'apppgeolocation-post' );
		if ( ! defined( 'APPP_IMPORTING' ) ) {
			define( 'APPP_IMPORTING', true );
		}

		if ( $this->is_debug() ) {
			$this->post_data = $_POST;
		}

		// get post fields and values
		// $form_fields = isset( $_POST['form_fields'] ) ? $_POST['form_fields'] : NULL;
		// $form_fields = str_replace( array( '[\"', '\"]' ), '', $form_fields );
		// $form_fields = explode( '\",\"', $form_fields );

		// $form_values = isset( $_POST['form_values'] ) ? $_POST['form_values'] : NULL;
		// $form_values = str_replace( array( '[\"', '\"]' ), '', $form_values );
		// $form_values = explode( '\",\"', $form_values );

		// // set post values
		// foreach ( $form_fields as $key => $value ) {
		// 	$_POST[$value] = $form_values[$key];
		// }

		$post_id = isset( $_POST['appp_geo_post_id'] ) ? $_POST['appp_geo_post_id'] : NULL;
		// check appp_action
		$appp_action = isset( $_POST['appp_action'] ) ? $_POST['appp_action'] : NULL;
		// check if should be attached to the current post
		if ( $appp_action == 'this' && isset( $_POST['appp_geo_post_id'] ) ) {
			$post_id = $_POST['appp_geo_post_id'];

			// create a new post to attach image to
		} elseif ( $appp_action == 'new' && isset( $_POST['appp_post_type'] ) ) {
			$post_type = $_POST['appp_post_type'];
			$post_status = isset( $_POST['appp_post_status'] ) ? $_POST['appp_post_status'] : 'publish';
			$post_title = isset( $_POST['appp_geo_post_title'] ) ? $_POST['appp_geo_post_title'] : date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
			$post_content = isset( $_POST['appp_geo_post_content'] ) ? $_POST['appp_geo_post_content'] : '';
			$post_id = wp_insert_post( array(
					'post_type' => sanitize_text_field( $post_type ),
					'post_status' => sanitize_text_field( $post_status ),
					'post_author' => absint( $user_ID ),
					'post_title' => sanitize_text_field( $post_title ),
					'post_content' => sanitize_text_field( $post_content )
				) );
		}

		// Hook for other stuff
		do_action( 'appp_after_process_geolocation', $post_id );

	}

	/**
	 * Filters the_content and adds _POST debug data output to the content
	 * @since  1.0.2
	 * @param  string  $content Existing content
	 * @return string           (Maybe) modified content
	 */
	public function submitted_data( $content ) {
		if ( isset( $this->post_data ) ) {
			$content .= '<xmp style="background:white;padding:20px;">'. print_r( $this->post_data, true ) .'</xmp>';
		}
		return $content;
	}

}
