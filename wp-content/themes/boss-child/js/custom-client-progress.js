   jQuery(document).ready(function(){
   	
	   	jQuery('input#search').quicksearch('#members-list li',{
	    	 selector: '.fullname',
	    	 'loader': 'span.loading',
	    	 'noResults': '.no_results',
	    });

	    jQuery(document).on('click','.sharing-request',function(){
	    	_this = jQuery(this);
	    	_this.addClass('loading disabled').attr('disabled',true);
	    	var client_id = _this.attr('data-client');
	    	jQuery.ajax({
                type: "POST",
                url: mirrorMuscles.ajaxPath,
                data: {action: 'sharing_request_to_client', client_id: client_id},
                success: function(data) {
                		//var callback = jQuery.parseJSON(data);
                		_this.text('Sharing Request (pending)').removeClass('loading sharing-request');
                   	}
                });
	    });

	    jQuery(document).on('click','.share-twitter', function(event){
	    	
	    	jQuery('#success-share').empty();
	    	jQuery(this).closest('div').find('.share_with_email').hide();
			
			var loc = document.location.origin;
			var text = jQuery(this).closest('div').find('input.share_text').val();
 			window.open('http://twitter.com/share?url=' + loc + '&text=' + encodeURIComponent(text) + '&', 'twitterwindow', 'height=450, width=550, top='+(jQuery(window).height()/2 - 225) +', left='+jQuery(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');	
		});
		

		jQuery(document).on('click','.share-wall', function(event){
        	
        	jQuery('#success-share').empty();
        	jQuery(this).closest('div').find('.share_with_email').hide();

            var text = jQuery(this).closest('div').find('input.share_text').val();
            jQuery.ajax({
        	    type: "POST",
            	url: mirrorMuscles.ajaxPath,
            	data: {bfc_share_wall: 1, text: text},
            	success: function(data) {
                    jQuery('#success-share').append('<div id="message" class="updated text-center"><p>Results successfully shared to the Wall.</p></div>')
                }
            });  
    	});



		jQuery('.share-email').on('click', function(event){
			jQuery('#success-share').empty();
			jQuery(this).closest('div').find('.share_with_email').show();
		});

		function validateEmail(sEmail) {
			var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)jQuery/;
			if (filter.test(sEmail)){
			    return true;
			}
			else{
			    return false;
			}
		}

		jQuery(document).on('click','.share_with_email_send', function(e){
			
			jQuery('#success-share').empty();
			var _this = jQuery(this)
			_this.addClass('loading disabled').attr('disabled',true);
			var text = jQuery(this).closest('div').find('textarea').val();
			text += ' '+mirrorMuscles.currentFullname+' from <a href="'+mirrorMuscles.homeUrl+'"><small>Mirror Muscles.</small></a>'
			var email = jQuery(this).closest('div').find('.share_with_email_email').val();
			var subject = 'My Client Bodyfat Calculator Result';
        
	        if (jQuery.trim(email).length == 0 || !validateEmail(email)) {
	            jQuery('#success-share').append('<label id="bfc-email-share-error" class="error" style="text-align: center !important">Invalid email address.</label>');
	            e.preventDefault();
	        }else{
				jQuery.ajax({
				    type: "POST",
					url: mirrorMuscles.ajaxPath,
					data: {bfc_share_email: 1, email: email, subject: subject, text: text},
					success: function(data) {
						_this.removeClass('loading disabled').attr('disabled',false);
				        jQuery('#success-share').append('<div id="message" class="updated text-center"><p>Results successfully shared with email.</p></div>')
				    	jQuery('.share_with_email').hide();
				    }
				});
	        }

		});


		createFBShareLink = function(description) {
	        var url = 'http://www.facebook.com/dialog/feed?app_id=' + mirrorMuscles.fbAppId +
	            '&link=' + document.location.origin +'/my-progress' +
	            '&picture='+mirrorMuscles.themeDir+'/images/mm_sharing_logo.jpg";?>'+
	            '&name=Mirror Muscle: My Client Results' + 
	            '&caption=<a href="'+document.location.origin+'">' + document.location.origin + 
	            '&description=' + description + 
	            '&redirect_uri='+document.location.origin+'/wp-content/plugins/mirror-muscles/PopupClose.html' + 
	            '&display=popup'; 

	        return url; 
	    }

    	jQuery(document).on('click','.share-facebook', function(event){
			jQuery('#success-share').empty();
	    	jQuery(this).closest('div').find('.share_with_email').hide();

	    	var text = jQuery(this).closest('div').find('textarea').val();

			var openFBWindow = function() {
			  	var url = createFBShareLink(text);
			  	window.open(url, 
			              	'feedDialog', 
			              	'toolbar=0,status=0,width=626,height=436'
			  ); 
			}		
				openFBWindow();
				
		});



    	createGoogleShareOptions = function(prefilltext) {
	        var options = {
	                contenturl: mirrorMuscles.homeUrl+'<?php echo "?".rand();?>',
	                clientid: mirrorMuscles.googleAppId,
	                cookiepolicy: 'single_host_origin',
	                prefilltext: prefilltext,
	                calltoactionlabel: 'OPEN',
	                calltoactionurl: mirrorMuscles.homeUrl
	        }; 

	        return options; 
	    }
	    
	    jQuery('.share-google').each(function(){
	    	var text = jQuery(this).closest('div').find('textarea').val();
	    	var id = jQuery(this).attr('id');
			var options = createGoogleShareOptions(text);
			gapi.interactivepost.render(id,options);
	    });


    	

});