<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppGeo_Ajax class.
 *
 * creates ajax endpoints for checking in a user
 */
class AppGeo_Shortcode {


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
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}


	/**
	 * register_shortcodes function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_shortcodes(){
		add_shortcode('checkin', array( $this, 'checkin') );
		add_shortcode('appp-map', array( $this, 'appp_map') );
	}


	/**
	 * checkin function.
	 *
	 * Gets shortcode params and then echos a button.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return void
	 */
	public function checkin( $atts ) {

		$default_btn_classes = 'btn btn-primary';

		$default_btn_classes = apply_filters( 'appgeo_button_class', $default_btn_classes );

		extract( $app_atts = shortcode_atts( array(
					'class'  => '', // button class for styling
					'title'	=> '',
					'address'	=> '',
					'place'	 => '',
					'button_text' =>  __( 'Check In', 'appgeo' ),
				), $atts ) );

		ob_start();
		?>
		<?php if ( AppPresser::is_app() ) { ?>

			<?php if ( is_user_logged_in() ) { ?>
				<button class="<?php echo $default_btn_classes; ?> onclick-appgeo-getloc <?php echo $app_atts['class']; ?>" id="checkin-here-btn" data-title="<?php echo $app_atts['title']; ?>" data-address="<?php echo $app_atts['address']; ?>" data-place="<?php echo $app_atts['place']; ?>">
					<?php echo $app_atts['button_text']; ?>
				</button>

				<?php if( AppPresser::is_min_ver( 3 ) && !empty($app_atts['address']) ) : ?>

						<script type="text/javascript">

							jQuery('.onclick-appgeo-getloc').on('click', function(event) {
								var address = jQuery(event.target).data('address');

								if(address) {
									event.preventDefault();

									$place = jQuery(event.target).data('place');
									$address = address;
									$pin = true;

									toggleCheckinForm('block');
									AppGeo_findAddress(address);
								} else {
									console.warn('address not found');
								}
							})
						</script>
				<?php elseif( AppPresser::is_min_ver( 3 ) && !empty($app_atts['place']) ) : ?>

						<script type="text/javascript">
							parent.postMessage( 'checkin_icon_show', '*');

							jQuery('.onclick-appgeo-getloc').on('click', function(event) {
								
								var place = jQuery(event.target).data('place');
								if(place) {
									event.preventDefault();

									$pin = null;
									$address = '';

									parent.postMessage( '{"geo_place":"'+place+'"}', '*');
								} else {
									console.warn('place not found');
								}

							})
						</script>

				<?php elseif( AppPresser::is_min_ver( 3 ) ) : ?>

						<script type="text/javascript">
							parent.postMessage( 'checkin_icon_show', '*');

							jQuery('.onclick-appgeo-getloc').on('click', function(event) {
								event.preventDefault();
								$pin = true;
								$address = '';
								$place = '';
								parent.postMessage( 'checkin_modal_show', '*');
							})
						</script>

				<?php endif; ?>
			<?php } else { ?>
				<button class="<?php echo $default_btn_classes. ' ' . $app_atts['class']; ?>"
					onclick="AppGeo_login()">
					<?php echo $app_atts['button_text']; ?>
				</button>
			<?php } ?>

		<?php } ?>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Returns the JavaScript to create a Google Map with markers from checkins
	 * 
	 * @param array $atts
	 * 
	 * @since 2.2.0
	 */
	public function appp_map( $atts ) {

		$default_coord = apply_filters('appgeo_map_coord', array('latitude'=>'37.4419', 'longitude'=>'-122.1419'));

		if( !is_array($default_coord) ||
			!isset($default_coord['latitude']) ||
			!isset($default_coord['longitude']) ||
			!is_numeric($default_coord['latitude']) ||
			!is_numeric($default_coord['longitude'])
		) {
			return __('The appgeo_map_coord filter did not return the correct data', 'appgeo');
		}

		$defaults = array(
			'source' => 'all',
			'zoom' => 'auto',
			'selector' => 'map-container',
			'height' => '600',
			'center_lat' => '',
			'center_long' => '',

		);

		// Only set default lat and long if not using zoom
		// and both lat and long must be set to be used
		if ( ( !empty($atts['zoom']) && $atts['zoom'] !== 'auto' ) &&
			 empty($atts['center_lat']) && empty($atts['center_long'])
		   ) {
			$defaults['center_lat']  = $default_coord['latitude'];
			$defaults['center_long'] = $default_coord['longitude'];
		}

		$app_atts = shortcode_atts( $defaults, $atts );

		wp_enqueue_script( 'appp-google-maps' );

		ob_start();
		?>
		<div id="<?php echo $app_atts['selector'] ?>"></div>
		<?php echo AppGeo_MarkerMap::get_map( $app_atts ) ?>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}


function checkinButton( $atts = '' ) {
	if ( AppPresser::is_app() ) {
		echo do_shortcode('[checkin '. $atts .']');
	}
}