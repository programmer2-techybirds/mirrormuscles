/* global jQuery, Ink */
(function( $, Ink ) {
    $( window ).load( function() { 
        var formInstance = Ink.Common_1.getInstance( '.wcv-form' )[0];

        if ( typeof formInstance === 'undefined' ) {
            return;
        }

        var oldHandler = formInstance._options.onError;

        /**
         * Custom validation error handler. Scrolls the erroring field
         * into view.
         *
         * @param FormValidator.FormElement[] errors
         */
        formInstance._options.onError = function( errors ) {
            if ( errors.length < 1 ) {
                return;
            }

            /* Get first element with errors */
            var $element = $( errors[0].getElement() );

            /* If the element is being displayed in a tab pane, focus that tab */
            var $pane = $element.closest( '.tabs-content' );

            if ( $pane && ! $pane.hasClass( 'active' ) ) {
                var tabsInstance = Ink.Common_1.getInstance( '.wcv-tabs' )[0];

                if ( typeof tabsInstance !== 'undefined' ) {
                    tabsInstance.changeTab( '#' + $pane.attr( 'id' ) );
                }
            }
            
            /* Scroll element into view */
            var $group = $element.closest( '.control-group' );

            $( 'html, body' ).animate( {
                scrollTop: $group.offset().top,
            }, {
                duration: 500,
            } );

            /* Call original error handler, if any */
            if ( typeof oldHandler !== 'undefined' ) {
                oldHandler( errors );
            }
        }
    } );
})( jQuery, Ink.UI );