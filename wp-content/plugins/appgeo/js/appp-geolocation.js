window.geo_object = window.geo_object || { ajax_url : apppCore.ajaxurl };

(function(window, document, $, undefined) {

	window.AppGeolocation       = {};
	var shortcode_fired 		= false;
	AppGeolocation.ajaxloaded   = false;
	AppGeolocation.get          = navigator.geolocation;
	var getUserInvterval;
	var getLocationInvterval;

	var geo_form = false;

	if ( null !== document.getElementById('appp_longitude') ) {
		geo_form = true;
	}

	AppGeolocation.geoLocate_post = function() {

		if ( !geo_form && shortcode_fired === true )
			return;

		AppGeolocation.get.getCurrentPosition( AppGeolocation.onSuccessGeoPost, AppGeolocation.onErrorGeo );

		getLocationInvterval = getCurrentPositionInterval();

		shortcode_fired   = true;
		AppGeolocation.ajaxloaded = false;
	};

	AppGeolocation.onSuccessGeoPost = function( position ) {
		// Make sure our geolocation form is available
		if ( document.getElementById('appp_longitude') === null || ! position )
			return;

		AppGeolocation.position = position;

		apppresser.log('onSuccessGeoPost position',position);

		var element = null;
		// Coordinate parameters to map to the dom elements
		var elements = [
			'longitude',
			'latitude',
			'altitude',
			'accuracy',
			'altitudeAccuracy',
			'heading',
			'speed',
			'timestamp'
		];
		var count = elements.length - 1;
		// Loop through the parameters and add the values to the corresponding dom element
		while ( count >= 0 ) {
			element = document.getElementById( 'appp_' + elements[count].toLowerCase() );
			// Make sure the element exists
			if ( element ) {
				// Set its value
				if( elements[count] == 'timestamp' ) {
					element.value = position.timestamp;
				} else {
				element.value = position.coords[ elements[count] ];
			}
			}
			count--;
		}

		var $map = $('#appp_map_preview_img');

		// if map preview
		if ( position && $map.length ){
			var key = ( geo_object.gmap_api ) ? '&key=' + geo_object.gmap_api : '';
			$map.attr( 'src', 'http://maps.googleapis.com/maps/api/staticmap?zoom=17&size=600x300&maptype=roadmap&markers=color:red%7Ccolor:red%7Clabel:%7C' + position.coords.latitude + ',' + position.coords.longitude + '&sensor=false' + key );
		}

	};

	AppGeolocation.onErrorGeo = function(error) {
		if ( typeof apppCore.log === 'function' )
			apppCore.log( 'code: '+ error.code +'\n'+'message: '+ error.message +'\n' );
	};

	// store location data for user
	AppGeolocation.geoLocate_user = function() {
		navigator.geolocation.getCurrentPosition( AppGeolocation.onSuccessGeoUser, AppGeolocation.onErrorGeo );
	};

	AppGeolocation.onSuccessGeoUser = function(position) {

		// Make sure our geolocation form is available
		if ( !geo_form && shortcode_fired === true )
			return;

		var url = geo_object.ajax_url;
		var cookie = apppCore.ReadCookie( 'Appp_Geolocation' );

		if ( !url && !cookie )
			return;

		$.ajax({
			type: 'POST',
			dataType: "json",
			url: url,
			data: {
				'action': 'appp_geo_user',
				'longitude': position.coords.longitude,
				'latitude': position.coords.latitude
			},
			success: function( response ) {
				apppresser.log('onSuccessGeoUser response',response);
				console.log(response.data);
			},
			error: function() {
				console.log('geo location error');
			}
		});
	};

	AppGeolocation.init = function() {

		if ( document.getElementById('appp_longitude') ) {
			geo_form = true;
		} else {
			return;
		}

		if ( null !== document.getElementById('app-geolocation-geolocate-post-trigger') ) {
			AppGeolocation.geoLocate_post();
		}

		if ( null !== document.getElementById('app-geolocation-geolocate-user-trigger') ) {
			AppGeolocation.geoLocate_user();
			// check every minute for new location
			getUserInvterval = geoLocate_userInterval();

		}

		// ver 1 only
		if( typeof apppCore.ver == 'undefined' || apppCore.ver == 1) {
			jQuery('.onclick-appgeo-getloc').on('click',  openCheckinModal );
		}

	};

	function geoLocate_userInterval() {
		// check every 60 seconds
		return window.setInterval( function(){ AppGeolocation.geoLocate_user(); }, 60000 );
	}

	function getCurrentPositionInterval() {
		// check every 15 seconds
		return window.setInterval( function(){
			AppGeolocation.get.getCurrentPosition( AppGeolocation.onSuccessGeoPost, AppGeolocation.onErrorGeo );
			console.log('geo');
		},15000);
	}


	$(document).ready( AppGeolocation.init ).bind( 'load_ajax_content_done', function() {

			window.clearInterval(getUserInvterval);
			window.clearInterval(getLocationInvterval);

			AppGeolocation.ajaxloaded = true;
			AppGeolocation.init();

	});

})(window, document, jQuery);
