<?php

class AppBuddy_Login {

	public function __construct() {

		if( is_admin() ) {
			$this->check_legacy_install();
		} else if( $this->is_force_login() ) {
			add_filter('template_redirect', 'appbuddy_login_screen');
		}
	}

	/**
	 * Do our settings force the app user to login?
	 */
	public function is_force_login() {

		if( ! AppPresser::is_app() ) {
			return false;
		} else if( defined('APPP_REMOVE_LOGIN') && APPP_REMOVE_LOGIN === true ) {
			return false;
		} else if( appp_get_setting('appbuddy_force_login', 'on' ) == 'on' ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Checks to see if the new appbuddy_disable_force_login setting exists
	 */
	public function check_legacy_install() {

		$appp_settings = get_option( 'appp_settings' );

		if( is_array( $appp_settings ) && ! isset( $appp_settings['appbuddy_force_login'] ) ) {
			$this->add_setting_for_legacy_installs();
		}
	}

	/**
	 * If AppBuddy has already been installed we need to add the
	 * $app_setting['appbuddy_force_login'] = 'on' to keep
	 * the original behavior for that setting
	 */
	public function add_setting_for_legacy_installs() {
		$appp_settings = get_option( 'appp_settings' );
		$appp_settings['appbuddy_force_login'] = 'on';
		update_option( 'appp_settings', $appp_settings );
	}
}

new AppBuddy_Login();
