(function(window, document, $, undefined) {

	'use strict';

	var appgeo_markers = {};

	appgeo_markers.init = function() {

	};

	appgeo_markers.geolocations_json = function() {

		if( typeof appgeo_markers.source === 'undefined' ) {
			return;
		}

		$.ajax({
			type: 'POST',
			dataType: "json",
			url: appgeo_markers.ajax_url,
			data: {
				'action': 'appgeo_geolocations_json',
				'source': appgeo_markers.source,
			},
			success: function( response ) {
				console.log(response);
				appgeo_markers.add_map( response );
			},
			error: function() {
				console.log('geo location error');
			}
		});
	};

	appgeo_markers.add_map = function(data) {

		var bounds = new google.maps.LatLngBounds();

		var options = {
			mapTypeId: google.maps.MapTypeId.ROADMAP,
		};

		if(appgeo_markers.zoom !== 'auto') {
			// zoom is a number
			options.zoom = appgeo_markers.zoom;
			options.center = new google.maps.LatLng(appgeo_markers.center.latitude, appgeo_markers.center.longitude);
		}

		if( document.getElementById(appgeo_markers.selector) === null ) {
			console.warn(appgeo_markers.selector, 'selector not found for the marker map');
			return;
		}

		var map = new google.maps.Map(document.getElementById(appgeo_markers.selector), options);

		var markers = [];
		for (var i = 0; i < data.geolocations.length; i++) {
			var appgeos = data.geolocations[i];
			var latLng = new google.maps.LatLng(appgeos.latitude, appgeos.longitude);
			var marker = new google.maps.Marker({
				position: latLng
			});
			markers.push(marker);
			bounds.extend(latLng);
		}
		if(appgeo_markers.zoom === 'auto') {
			var latlngbounds = new google.maps.LatLngBounds();
			map.fitBounds(bounds);
			map.panToBounds(bounds);
		}
		var markerCluster = new MarkerClusterer(map, markers, {imagePath: appgeo_markers.map_images});
	};

	$(document).on('ready load_ajax_content_done', appgeo_markers.init);

	window.appgeo_markers = appgeo_markers;

})(window, document, jQuery);
