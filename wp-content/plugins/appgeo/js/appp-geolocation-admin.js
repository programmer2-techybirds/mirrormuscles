(function(window, document, $, undefined){

	var $checkDisable = $('#apppresser--geolocation_track_users');
	var $toDisableI = $('#apppresser--geolocation_time_x');
	var $toDisableS = $('#apppresser--geolocation_time_incr');
	var $form = $('.apppresser_settings form');

	$form.on( 'submit', function() {
		// remove disabled on save so that setting doesn't get overwritten
		$toDisableS.prop('disabled', false);
	});

	$checkDisable.on( 'change', toggle );
	toggle();

	function toggle() {
		if ( $checkDisable.prop( 'checked' ) ) {
			enable();
		} else {
			disable();
		}
	}
	function enable() {
		$toDisableI.prop('readonly', false);
		$toDisableS.prop('disabled', false);
	}
	// disables show promotion dropdown with message
	function disable( text ) {
		$toDisableI.prop('readonly', true);
		$toDisableS.prop('disabled', true);
	}

})(window, document, jQuery);