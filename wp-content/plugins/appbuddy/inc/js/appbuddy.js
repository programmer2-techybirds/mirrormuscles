(function(window, document, $, undefined){
	'use strict';

	window.appbuddy = {

	  a: {},

	  selectors: {
	  	body: $('body')
	  },

	  init: function() {
	    appbuddy.a = appbuddy.selectors;
	    appbuddy.uiEvents();
	    appbuddy.avatarUI();
	    appbuddy.setEmbedIframe();
	    appbuddy.viewLargeAvatar.init();
	    appbuddy.modals.init();
	    appbuddy.l18n.init();
	  },

	  viewLargeAvatar: {
			_init: false,
			init: function() {
				if( ! this._init ) {
					$('#item-header-avatar a').on('click', appbuddy.viewLargeAvatar.open);
					this._init = true;
				}
			},
			open: function(e) {
				console.log( $(this).data('fullAvatar') );
			}
		},

	  setEmbedIframe: function() {
	  	
	  	var iframes, i, source, secret;

	  	iframes = document.querySelectorAll( 'iframe.wp-embedded-content' );

	  	for ( i = 0; i < iframes.length; i++ ) {
	  		source = iframes[ i ];

	  		secret = jQuery(source).data('secret');
	  		if ( secret && jQuery('[data-secret="'+secret+'"]').length ) {

	  			/* show the blockquote and remove the embeded iframe */

	  			jQuery('[data-secret="'+secret+'"]').show();
	  			jQuery(source).remove();
	  		} else {
	  			
	  			/* The blockquote is missing so adjust the iframe's width */

	  			source.setAttribute( 'width', jQuery(source).parent().width() );
	  		}

	  	}

	  },

	  // Utilize the Attachment API UI in BuddyPress 3.6
	  avatarUI: function() {

	  	// Set a flag to stop the maybeGoBack function
	  	apppCore.noGoBackFlags.push(function() {
	  		if(  window.location.href.indexOf( '/profile/change-avatar/' ) > 1 ) {
	  			window.apppCore.noGoBackFlag = 'changing-avatar';
	  		}
	  	});

	  	// We need BP_Uploader and bp.Avatar, but BP_Uploader not there in dynamic page loading.
	  	// Force a page reload when changing the avatar
	  	// in order to get the BP_Uploader object
	  	if(  window.location.href.indexOf( '/profile/change-avatar/' ) > 1 ) {
	  	    if( typeof BP_Confirm == 'object' &&
	  	      ( typeof bp === 'undefined' ||
	  	        typeof bp.Avatar === 'undefined' ||
	  	        typeof BP_Uploader === 'undefined' ) ) {
	  	    window.location.reload();
	  	    } else {
	  	        $('#avatar-upload input[type="submit"]').remove();
	  	        $('#avatar-upload input[type="file"]').remove();

	  	        if( window.bp && window.bp.Avatar ) {
	  	            bp.Avatar.start();
	  	        }
	  	    }
	  	} else if(  window.location.href.indexOf( '/profile/change-cover-image/' ) > 1 ||
	  			    window.location.href.indexOf( '/admin/group-cover-image/' ) > 1
	  	         ) {
	  	    if( typeof BP_Confirm == 'object' &&
	  	      ( typeof bp === 'undefined' ||
	  	        typeof bp.CoverImage === 'undefined' ||
	  	        typeof BP_Uploader === 'undefined' ) ) {
	  	    window.location.reload();
	  	    } else {

	  	        if( window.bp && window.bp.CoverImage ) {
	  	        	if( $('#bp-upload-ui').length === 0 ) {
	  	        		bp.CoverImage.start();
	  	        	}
	  	        }
	  	    }
	  	}
	  },

	  modals: {
	  	init: function() {
	  		$(document).on('create_dynamic_modals', appbuddy.modals.dynamic.create);
	  	},
	  	dynamic: {
	  		create: function() {
	  			$('.activity-image.dynamic-modal').apppmodal();
	  		}
	  	}
	  },

	  l18n: {
	  	defaults: {
		  	login_process: 'Logging in....',
			login_error: 'Error Logging in.',
	  	},
	  	init: function() {

	  		if( typeof app_buddy.l18n !== 'undefined' ) {
	  			appbuddy.l18n.t = app_buddy.l18n;
	  		} else {
	  			appbuddy.l18n.t = appbuddy.l18n.defaults;
	  		}
	  	}
	  },

	  ajax_html: function( event, data ) {

	  	// used for BuddyPress
		// html.find( 'script[type="text/html"]' ).clone().appendTo('body');

		appbuddy.updateCoverImage( data.html );
	  },

	  // add Cover Image CSS
	  updateCoverImage: function( html ) {
	  	$('#appbuddy-css-inline-css').remove();
		html.find( '#appbuddy-css-inline-css' ).clone().appendTo('body');
	  },

	  uiEvents: function() {

	    appbuddy.a.body.on("click", '.destructive', function(event) {
			$(event.target).parents('#attach-image-sheet').removeClass('active').addClass('hide');
			$('#cam-status').html('');
			$('#image-status').html('');
			$('#attach-image').val('');
	    });

		appbuddy.a.body.on( 'click', '#attach-photo', function( event ) {
			$('#attach-image-sheet').removeClass('hide').addClass('active');
		});

		if( typeof jQuery.fn.apppmodal === 'undefined' ) {

			// Deprecated in Ion 1.3.0

			appbuddy.a.body.on( 'click', '.activity-image', function( event ) {
				var image = event.target;
				$(this).clone()
						.appendTo('body')
						.wrap('<div class="image-pop"></div>')
						.removeClass('activity-image')
						.addClass('close-pop');

			});
		} else {
			$('.activity-inner img').addClass('dynamic-modal');
		}

		appbuddy.a.body.on( 'click', '.close-pop', function( event ) {
			$('.close-pop').parent().remove();
		});

		appbuddy.a.body.on( 'click', '.add-activity-image', function( event ) {
			event.preventDefault();
			$('#whats-new-form-in-modal').toggleClass( 'hide' );
			$('#activity_add_media').toggleClass( 'show' );
		});

		appbuddy.a.body.on( 'click', '.io-modal-open', function( event ) {
			// TODO: fix so (maybe fixed now) that the button can be disabled here
			// The element isn't on the page yet if forced to login
			if( $("#aw-whats-new-submit") )
				$("#aw-whats-new-submit").prop("disabled", false);
			
			var modal = $( event.target ).parent().attr('href');
			
			if( '#activity-post-form' === modal ) {

				if( typeof navigator.appVersion == 'string' && (/iphone|ipad/gi).test(navigator.appVersion) ) {
					// let the iOS user select the textarea.  keyboard bug
				} else {
					// focus on textarea needs delay for modal slide up
					setTimeout(function() {
						$('textarea#whats-new').focus();
						
					}, 1000);
				}
				
			}
			

		});

		appbuddy.a.body.on( 'click', '#activity-post-form .io-modal-close', function( event ) {
			event.preventDefault();
			$('#whats-new-form-in-modal').removeClass( 'hide' );
			$('#activity_add_media').removeClass( 'show' );
			// $("#aw-whats-new-submit").prop("disabled", true);
			$('.ajax-spinner').hide();
			$('#cam-status').html('');
			$('#image-status').html('');
			$('#attach-image').val('');
		});

		appbuddy.a.body.on( 'click', '#ab-submit-image', function( event ) {
			event.preventDefault();
			$('.ajax-spinner').show();

		});
	  }

	};
	$(document).ready( appbuddy.init ).on( 'load_ajax_content_done',  appbuddy.init );
	$(document).on( 'deviceready',  appbuddy.init );
	$('body').on('appp_ajax_html', appbuddy.ajax_html);


	// attach ajaxify class to specific bp elements
	var ajaxLink = function() {

		$('#main').on('click', '.activity-header a, .activity-inner a, .item-avatar a, .item-title a, .pagination-links a, .item-list-tabs a, #item-buttons a.group-button, #send-private-message a, .message-title a, #message-recipients a, table.notifications a, #member-list li h5 a, #members-list li h5 a, #admins-list li h5 a, .activity-meta a.view, #member-list a', function(event) {

			var $self = $(this);
			var $href = $(this).attr('href');

			if( $($self).attr('target') === '_blank' ) {
				event.preventDefault();
				window.open($href, '_blank');
			} else {

			  $self.addClass('ajaxify');
			}
		});

		$('#main').on('click', '#post-mention a', function(event) {
				event.preventDefault();

				var $self = $(this);
				var $href = $(this).attr('href');
				var $user = getURLParameter($href, 'r');

				var UpClasses   = 'slide-in-up-add ng-animate slide-in-up slide-in-up-add-active';
				var downClasses = 'slide-in-up-remove slide-in-up-remove-active';
				$('#whats-new').val( '@' + $user );
				$('#activity-post-form').css('display', 'block').removeClass(downClasses).addClass(UpClasses);

		});

		$('.ac-form').on('touchmove', function(e) {
			e.preventDefault();
		});

	};
	$(document).on( 'ready', ajaxLink ).bind( 'load_ajax_content_done', ajaxLink );

	function getURLParameter(url, name) {
	    return (new RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
	}


    // Perform AJAX login on form submit
    $('form#appbuddy-loginform').on('submit', function(e){
        $('form#appbuddy-loginform p.status').show().text(appbuddy.l18n.t.login_process);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'appbuddy_login', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#appbuddy-loginform #username').val(),
                'password': $('form#appbuddy-loginform #password').val(),
                'security': $('form#appbuddy-loginform #security').val() },
            success: function(data){
                $('form#login p.status').text(data);
                if (data.success === true){
                	var app_ver = ( apppCore.ver ) ? apppCore.ver : '1';
                	apppresser.sendLoginMsg( 1, data.data);
                    if( typeof appp_ajax_login.login_redirect !== 'undefined' ) {
                    	document.location.href = appp_ajax_login.login_redirect + '?appp=' + app_ver;
                    } else {
                    	document.location.href = '?appp=' + app_ver;
                    }
                } else {
	                $('form#appbuddy-loginform p.status').show().text(appbuddy.l18n.t.login_error);
                }
            }
        });
        e.preventDefault();
    });

})(window, document, jQuery);