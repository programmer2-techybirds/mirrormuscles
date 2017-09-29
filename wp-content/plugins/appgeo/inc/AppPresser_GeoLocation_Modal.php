<?php

/**
 * AppBuddy_Modal_Buttons class.
 */
class AppGeo_Modal {


	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
	}


	/**
	 * hooks function.
	 *
	 * @access public
	 * @return void
	 */
	public function hooks() {
		// Do these actions after init hook otherwise it's too early to check the user's login status
		add_action('init', array( $this, 'after_init_hooks' ) );
	}

	public function after_init_hooks() {

		$this->is_loggedin = is_user_logged_in();
			
		if( AppPresser::is_app() && isset( $this->is_loggedin ) ) {	

			add_action( 'geo_modal_button_action', array( $this, 'add_modal_button' ) );
			add_action( 'wp_head', array( $this, 'load_checkin_modal' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

			if( defined( 'REMOVE_GEO_TOOLBAR' ) ) return;

			// Don't do this stuff if REMOVE_GEO_TOOLBAR is defined
			add_action( 'appp_header_right', array( $this, 'modal_button' ) );

			add_action( 'wp_footer', array( $this, 'geo_checkin_modal_template' ) );
		}
	}

	/**
	 * scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function scripts() {

		wp_enqueue_script( 'jquery' );

		$translation_array = array(
			'error' => __( '<div class="center">Oops, location failed, click here to try again!</div>', 'appgeo' ),
			'pin_error' => __( 'Oops, you need to select a pin.', 'appgeo' ),
			'checkin_success' => __( 'Awesome, we got you checked in!', 'appgeo' ),
			'checkin_error' => __( 'oops, checkin failed, please try again.' ),
			'login_error' => __( 'Oops, you need to be logged in to checkin.', 'appgeo' ),
			'alert_pop_title' => __( 'Checked In', 'appgeo' ),
			'geolocation_options' => array(
				'timeout' => 5000,
				'maximumAge' => 3000,
				'enableHighAccuracy' => true,
			),
		);

		$translation_array = apply_filters( 'geo_string_filter', $translation_array );

		wp_localize_script( 'jquery', 'appgeo', $translation_array );


	}


	/**
	 * add_modal_button function.
	 *
	 * Adds buttons to right toolbar button hook
	 *
	 * @access public
	 * @return array
	 */
	public function add_modal_button() {

		$args = '';

		if ( $this->is_loggedin  ) {

			$args = array(
				'button_class' => 'nav-right-btn io-modal-open',
				'icon_class'   => 'fa fa-lg fa-map-marker',
				'button_text'  => '',
				'url' => '#geo-checkin-form'
			);

		}

		echo $this->get_modal_button($args);

	}


	/**
	 * modal_button function.
	 *
	 * Adds button right toolbar button hook.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function modal_button() {
		do_action('geo_modal_button_action');
	}

	public function attach_image_input() {
	?>
		<input type="hidden" id="attach-image" name="attach-image" value="">
	<?php
	}

	/**
	 * get_modal_button function.
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return void
	 */
	public function get_modal_button( $args = array() ) {

		// need defaults here
		$this->args = wp_parse_args( $args, array(
				'button_class' => '',
				'icon_class'   => '',
				'button_text'  => '',
				'url'          => '',
				'post_in'      => ''
			) );

		wp_enqueue_script( 'appgeo' );
		//wp_enqueue_script( 'heartbeat' );

		if ( is_user_logged_in() ) {
			$button = apply_filters( 'geo_modal_button', sprintf( '<nav id="top-menu4" class="top-menu pull-right" role="navigation"><a class="%s" href="%s" data-post="%s" onclick="AppGeo_getLoc();"><i class="%s"></i> %s</a></nav>', $this->args['button_class'], $this->args['url'], $this->args['post_in'], $this->args['icon_class'], $this->args['button_text'] ) );

		} else {
			$button = apply_filters( 'geo_modal_button', sprintf( '<nav id="top-menu4" class="top-menu pull-right" role="navigation"><a class="%s" href="%s" data-post="%s" onclick="AppGeo_login();"><i class="%s"></i> %s</a></nav>', $this->args['button_class'], $this->args['url'], $this->args['post_in'], $this->args['icon_class'], $this->args['button_text'] ) );
		}

		return $button;
	}

	function geo_checkin_modal_template() {
		?>
		<aside class="io-modal" id="geo-checkin-form" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="toolbar site-header">
				<i class="fa fa-location-arrow fa-lg left" onclick="AppGeo_center_Marker();"></i>
				<i class="io-modal-close fa fa-times fa-lg"></i>
			</div>
			<div class="io-modal-content">
			<?php if ( is_user_logged_in() ) : ?>
				<?php $user_ID = get_current_user_id(); ?>
				<div id="map-canvas"></div>
				<a href="#" class="btn btn-success btn-large btn-wide noajax btn-checkin" onclick="AppGeo_checkin( <?php echo $user_ID; ?> );"><?php _e('Check In', 'appgeo') ; ?></a>
				<?php wp_nonce_field( 'ajax-geo-nonce', 'security' ); ?>
			<?php endif; ?>
			</div>
		</aside>
			<?php
	}


	function load_checkin_modal() {
		?>
			<style>
				#map-canvas {
					height: 200px;
					height: 65vh;
					margin: -10px -10px 10px -10px;
					padding: 0;
					text-align: center;
				}
				.btn-checkin {
					display: none;
				}
			</style>
			<script>

				var map;
				var marker;
				var latitude;
				var longitude;
				var $title;
				var $place;
				var $address;
				var $place_id;
				var $pin;
				//var error = '';
				var UpClasses   = 'slide-in-up-add ng-animate slide-in-up slide-in-up-add-active';
				var downClasses = 'slide-in-up-remove slide-in-up-remove-active';

				// get location; attached to a button click
				function AppGeo_getLoc() {
					$pin = true;
					latitude = null;
					longitude = null;
					$place = null;
					$address = null;
					$title = null;
					$place_id = null;

					<?php

						//  In case GPS is disabled, defaults to Apple HQ or use the appgeo_default_position filter
						$default_position = apply_filters( 'appgeo_default_position', array('latitude'=>'37.3319492', 'longitude' => '-122.0297625') );

						if( is_array($default_position) && isset( $default_position['latitude'], $default_position['longitude'] ) ) {
							echo 'AppGeo_onSuccessGeoPost( {coords:{latitude:' . $default_position['latitude'] . ',longitude:' . $default_position['longitude'] . '}} );';
						}

					?>

					<?php if( method_exists( 'AppPresser', 'is_min_ver' ) && AppPresser::is_min_ver( 2 ) ) : // v2 or higher ?>

						parent.postMessage( 'get_current_position', '*');
					
					<?php else : ?>
					
					setTimeout(function() {
						navigator.geolocation.getCurrentPosition( AppGeo_onSuccessGeoPost, AppGeo_onErrorGeo );
					}, 500);
						
					AppGeo_clearmap();

					if(!map){
						jQuery('.ajax-spinner').show();
					}
					<?php endif; ?>
				
				}

				// if location is successful initialize
				function AppGeo_onSuccessGeoPost( position ) {
					latitude = position.coords[ 'latitude' ];
					longitude = position.coords[ 'longitude' ];
					AppGeo_initialize( latitude, longitude );
					jQuery('.ajax-spinner').hide();
				}

				// data structure is different with AP3, only this function changes
				function ap3_onSuccessGeoPost( position ) {
					console.log( position );
					latitude = position.lat;
					longitude = position.long;
					AppGeo_initialize( latitude, longitude );
					jQuery('.ajax-spinner').hide();
				}


				function AppGeo_initialize( latitude, longitude ) {

				    var myLatlng = new google.maps.LatLng( latitude, longitude );
				    var myOptions = {
				        zoom: 15,
				        center: myLatlng,
				        mapTypeId: google.maps.MapTypeId.ROADMAP
				    }
				    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
				    AppGeo_TestMarker( latitude, longitude );
				}

				// Function for adding a marker to the page.
				function AppGeo_addMarker(location) {
					setTimeout(function(){
						jQuery('.btn-checkin').css({'display': 'block'});
					    marker = new google.maps.Marker({
					        position: location,
					        map: map,
					        animation: google.maps.Animation.DROP,
					        draggable: true
					    });
					}, 500);
				}

				// Testing the addMarker function
				function AppGeo_TestMarker( latitude, longitude ) {
				       loc = new google.maps.LatLng( latitude, longitude );
				       AppGeo_addMarker( loc );
				}

				// center location
				function AppGeo_center_Marker() {
					var latLng = marker.getPosition();
					map.setCenter(latLng);
				}

				// error message if location is not found
				function AppGeo_onErrorGeo( position ) {
					jQuery('#map-canvas').html( appgeo.error );
					jQuery('.ajax-spinner').hide();
				}

				// check user in saves cordinates to post meta
				function AppGeo_checkin( user_id ) {

					if( $pin === null || typeof $pin === 'undefined') {
						alert( appgeo.pin_error );
						return;
					};

					var nonce = document.getElementById('security').value;

					jQuery('.ajax-spinner').show();

					data = {
						'id': user_id,
						'latitude': latitude,
						'longitude': longitude,
						'title': $title,
						'place': $place,
						'address': $address,
						'place_id': $place_id,
						'nonce': nonce,
						'action': 'appgeo_checkin',
					}

					jQuery.ajax({
			            type: 'POST',
			            url: apppCore.ajaxurl,
			            data: data,
			            success: function( data ){
							jQuery('.ajax-spinner').hide();
							setTimeout(function() {

							<?php if( method_exists('AppPresser', 'is_min_ver') && AppPresser::is_min_ver(2) ) : ?>
								parent.postMessage( 'checkin_success', '*');
								if( typeof appgeo_markers !== 'undefined' && typeof appgeo_markers.geolocations_json !== 'undefined') {
									appgeo_markers.geolocations_json();
								}
							<?php else : ?>
								alert( appgeo.checkin_success );
							<?php endif; ?>
								jQuery('#geo-checkin-form').css('display', 'none').addClass(downClasses).removeClass(UpClasses);
							}, 500);

			            },
			            error: function( data ) {
				            alert( appgeo.checkin_error );
			            }
			        });

				}

				function AppGeo_clearmap() {
					jQuery('#map-canvas').html( '' );
				}


				// add marker for single address
				function AppGeo_findAddress( address ) {

					AppGeo_clearmap();

					if(!map){
						jQuery('.ajax-spinner').show();
					}

			        var geocoder = new google.maps.Geocoder();


			        var addresses = address.split('|');

			        for (i = 0; i < addresses.length; i++) {
					    var newaddress = addresses[i];

				        geocoder.geocode({ 'address': newaddress }, function (results, status) {
				            if (status == google.maps.GeocoderStatus.OK) {
				                latitude = results[0].geometry.location.lat();
				                longitude = results[0].geometry.location.lng();
				                var posLatlng = new google.maps.LatLng(latitude, longitude);


						        var myOptions = {
							        zoom: 15,
							        center: posLatlng,
							        mapTypeId: google.maps.MapTypeId.ROADMAP
							    }

						        map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

				                setTimeout(function(){
									marker = new google.maps.Marker({
									  position: posLatlng,
									  map: map,
									  title: "Location",
									  animation: google.maps.Animation.DROP,
									  draggable: true
									});
									jQuery('.ajax-spinner').hide();
									jQuery('.btn-checkin').css({'display': 'block'});
				                }, 500);
				            }
				        });

				    } // end for loop
			    }

				// Loop through store locations
				function placesCallback(results, status) {

					if (status == google.maps.places.PlacesServiceStatus.OK) {
						for (var i = 0; i < results.length; i++) {
						  var place = results[i];
						  createMarker(results[i]);
						}
					}
				}

				// Add a marker to the map
				function createMarker(place) {

					marker = new google.maps.Marker({
						map: map,
						position: place.geometry.location,
						animation: google.maps.Animation.DROP,
						draggable: true
					});

					jQuery('.ajax-spinner').hide();
					jQuery('.btn-checkin').css({'display': 'block'});

					google.maps.event.addListener( marker, 'click', function() {
						$pin = true;
						var infowindow = new google.maps.InfoWindow();

						latitude = place.geometry.location.lat();
						longitude = place.geometry.location.lng();
						$place = place.name;
						$place_id = place.id;
						$address = place.vicinity;

						infowindow.setContent('<p>' + place.name + '</p><p>' + place.vicinity + '</p>');
						infowindow.open(map, this);
						setTimeout( function () { infowindow.close(); }, 3500);
					});
				}


				function AppGeo_setPlaceLat( pos ) {

					var posLatlng = new google.maps.LatLng(pos.coords['latitude'], pos.coords['longitude']);

					var myOptions = {
				        zoom: 12,
				        center: posLatlng,
				        mapTypeId: google.maps.MapTypeId.ROADMAP
				    }


			        map = new google.maps.Map( document.getElementById("map-canvas"), myOptions );


					var request = {
						location: posLatlng,
						radius: '10000',
						keyword: $place
						//types: ['store']
					};

					service = new google.maps.places.PlacesService(map);
					service.nearbySearch(request, placesCallback);
				}

				/**
				 * toggle the checkin form
				 *
				 * @param string action: none|block
				 */
				function toggleCheckinForm(action) {
					jQuery('#geo-checkin-form').css('display', action).addClass(UpClasses).removeClass(downClasses);
				}

				<?php if( ! method_exists( 'AppPresser', 'is_min_ver' ) || ! AppPresser::is_min_ver( 2 ) ) : ?>

				// App v1 only
				function openCheckinModal() {
					var checkinBtn = document.getElementById('checkin-here-btn');
					var title = null;
					var place = null;
					var addr  = null;

					if( checkinBtn ) {

						// Shortcode button was clicked

						title = checkinBtn.getAttribute('data-title');
						place = checkinBtn.getAttribute('data-place');
						addr  = checkinBtn.getAttribute('data-address');
					} else {
						// Header marker icon was clicked
					}

					$title = title;
					$place = place;
					$address = addr;
					$pin = null;
					$place_id = null;

					toggleCheckinForm('block');

					if( addr === '' || addr === null ) {
						AppGeo_findPlace( place );
					} else {
						$pin = true;
						AppGeo_findAddress( addr );
					}
				}

				// App v1 only
				function AppGeo_findPlace() {
					document.getElementById('map-canvas').innerHTML = '';
					toggleAjaxSpinner('show');

					navigator.geolocation.getCurrentPosition( AppGeo_setPlaceLat );

				}
				<?php endif; ?>

				/**
				 * toggle the ajax spinner gif
				 *
				 * @param string action: show|hide
				 */
				function toggleAjaxSpinner(action) {
					if( action == 'show' ) {
						jQuery('.ajax-spinner').show();	
					} else {
						jQuery('.ajax-spinner').hide();
					}
						
				}

				function AppGeo_login() {
					jQuery('#loginModal').css('display', 'block').addClass(UpClasses).removeClass(downClasses);

					jQuery('#error-message').html(appgeo.login_error);
				}

			</script>
		<?php
	}


}