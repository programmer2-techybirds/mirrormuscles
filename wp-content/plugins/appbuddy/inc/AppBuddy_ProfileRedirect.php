<?php


class AppBuddy_ProfileRedirect {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		if( appp_get_setting('ap3_bp_me_path') )
			add_action( 'wp', array( $this, 'appp_redirect' ) );
	}

	/**
	 * Swaps the placehold path /me/ with a /username/ to 
	 * BP pages and redirects to that page.
	 * 
	 * If not logged in, redirects to the homepage displaying
	 * the #loginModal and sets the redirect_to parameter.
	 * 
	 * @since 3.1.0
	 */
	public function appp_redirect() {

		global $wp;
		$current_url = trailingslashit( home_url( $wp->request ) );

		$me_path = apply_filters( 'ap3_bp_me_url', '/me/' );
		
		// See if the 'me' path is part of the current URL
		$me = stripos( $current_url, $me_path );

		if( $me !== false ) {

			$bp_user_id = bp_loggedin_user_id();

			// Is user logged in
			if( $bp_user_id ) {
				$bp_user_name = bp_core_get_username( $bp_user_id );

				// Using user_nicename or user_login
				if ( bp_is_username_compatibility_mode() ) {
					// using user_nicename
					$bp_user_name = rawurlencode( $bp_user_name );
				}

				// Replace the 'me' path with the real username
				$bp_user_url = str_replace($me_path, '/'.$bp_user_name.'/', $current_url);

				wp_redirect( $bp_user_url );
				exit();

			} else {

				// Not logged in. Redirect to a URL and show login modal

				$redirect = $current_url;
				$url = trailingslashit( home_url() );
				$url = add_query_arg('appp', 3, $url);
				$url = add_query_arg('redirect_to', urlencode($redirect), $url);
				$url .= '#loginModal';

				// Be sure that your URL has a trailing slash when using a hash; otherwise, Safari will ignore the #loginModal
				$url = apply_filters( 'ap3_loginmodal_url', $url, $redirect, $bp_user_id );

				wp_redirect( $url  );
				exit;
			}

		}
	}
	
}

new AppBuddy_ProfileRedirect();