<?php

/**
 * AppBuddy_Modal_Buttons class.
 */
class AppBuddy_Modal_Buttons {


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		@$this->is_loggedin = is_user_logged_in();
		$this->hooks();
	}


	/**
	 * hooks function.
	 *
	 * @access public
	 * @return void
	 */
	public function hooks() {
		
		if ( isset( $this->is_loggedin ) ) {
			add_action( 'appp_header_right', array( $this, 'status_modal_button' ) );
			add_action( 'toolbar_button_right', array( $this, 'add_status_modal_button' ) );
			add_action( 'appbuddy_after_activity_post_form', array( $this, 'attach_image_input' ) );
		}

	}


	/**
	 * add_status_modal_button function.
	 *
	 * Adds buttons to right toolbar button hook
	 *
	 * @todo turn this into toolbar button api
	 * @access public
	 * @return array
	 */
	public function add_status_modal_button() {
		
		// Allow other plugins such as AppWoo to remove this when needed
		if( apply_filters( 'appp_remove_status_modal_btn', false ) ) {
			return;
		}

		$args = '';

		if ( $this->is_loggedin && bp_is_current_component('activity') || $this->is_loggedin && bp_is_group_home() ) {

			$args = array(
				'button_class' => 'nav-right-btn io-modal-open',
				'icon_class'   => 'fa fa-lg fa-edit',
				'button_text'  => '',
				'url' => '#activity-post-form'
			);

		} else if ( !$this->is_loggedin ) {

			$args = array(
				'button_class' => 'nav-right-btn login io-modal-open',
				'icon_class'   => 'fa fa-lg fa-sign-in',
				'button_text'  => '',
				'post_in' => '',
				'url' => '#loginModal'
			);

		}

		echo $this->get_status_modal_button($args);

	}


	/**
	 * status_modal_button function.
	 *
	 * Adds button right toolbar button hook.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function status_modal_button() {

		do_action('toolbar_button_right');

	}

	public function attach_image_input() {
	?>
		<input type="hidden" id="attach-image" name="attach-image" value="">
	<?php
	}

	/**
	 * get_status_modal_button function.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function get_status_modal_button( $args = array() ) {

		// need defaults here
		$this->args = wp_parse_args( $args, array(
				'button_class' => '',
				'icon_class'   => '',
				'button_text'  => '',
				'url'          => '',
				'post_in'      => '',
			) );

		wp_enqueue_script( 'appbuddy' );
		//wp_enqueue_script( 'heartbeat' );

		$button = apply_filters( 'appbuddy_modal_button', sprintf( '<nav id="top-menu3" class="top-menu pull-right" role="navigation"><a class="%s" href="%s" data-post="%s"><i class="%s"></i> %s</a></nav>', $this->args['button_class'], $this->args['url'], $this->args['post_in'], $this->args['icon_class'], $this->args['button_text'] ) );

		return $button;
	}


}
$AppBuddy_Modal_Buttons = new AppBuddy_Modal_Buttons();