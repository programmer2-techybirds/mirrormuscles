jQuery(document).ready(function(jQuery){
	
	jQuery('*').on('click',function(e){
		//console.log(e.target);
	});

	window.onload = function () {
	var cometchathead= document.getElementsByTagName("head");
	var link=document.createElement('link');
	link.href='/cometchat/cometchatcss.php?ts='+Math.random();
	link.rel='stylesheet';
	link.type= 'text/css';
	link.charset = 'utf-8';
	cometchathead[0].appendChild(link);
	var script1=document.createElement('script');
	script1.src='/cometchat/cometchatjs.php?ts='+Math.random();
	script1.type= 'text/javascript';
	script1.charset = 'utf-8';
	cometchathead[0].appendChild(script1);
	}
/*
	jQuery("html").niceScroll({
		'cursorcolor': "#4dcade",
		'cursorborder': '1px solid #4dcade',
		'cursorwidth': "8px",
		'zindex': 100000,
		'bouncescroll': true,
	});
*/
	
    //for password strength popover
	jQuery(document).on('change','#pass1',function(){
		jQuery('#pass-strength-result a').popover({ 
			html : true,
			trigger: "hover",
			placement: 'top',
	    });
	});

	mm_video = jQuery('#mm-video-container video').get(0);

	jQuery('#mm-video-container video').on('ended',function(){
     	jQuery('#mm-video-sound').hide();
     	jQuery('#mm-video-play').hide();
    });


	jQuery('#mm-video-sound').toggle(function() {
    	jQuery(this).removeClass('fa-volume-off').addClass('fa-volume-up');
    	jQuery("#mm-video-container video").prop("volume", 0.2).prop('muted', false);
    }, function() {
        jQuery(this).removeClass('fa-volume-up').addClass('fa-volume-off');
        jQuery("#mm-video-container video").prop('muted', true);
    });

    jQuery('#mm-video-play').click(function() {
        if(mm_video.paused){
        	mm_video.play()
        	jQuery(this).removeClass('fa-play-circle-o').addClass('fa-pause-circle-o');
        }else{
        	mm_video.pause()
        	jQuery(this).removeClass('fa-pause-circle-o').addClass('fa-play-circle-o');
        }
    });



    jQuery('.mm-tabs').responsiveTabs({
	    setHash: true,
	    animation: 'fade'
	});



	jQuery('.mm-accourdion').accordion({
		heightStyle: 'content',
		collapsible: true,
		active: false,
		activate: function(){
			jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');
			jQuery('.popover').remove();
		}
	});

	

	jQuery(function () {
		jQuery('.footable').footable();
	});
	
	
    jQuery('input, select').keypress(function(event) { return event.keyCode != 13; });
	
	//for mozilla			
    jQuery('[disabled="false"], [disabled=""]').removeAttr('disabled');








	//jQuery('.popover-label,.varified-mark').popover();

	jQuery('[data-toggle="tooltip"]').tooltip({
		placement: 'top',
		trigger: 'hover'
	});



	jQuery('input[name="field_10"], #field_10, #location').geocomplete({componentRestrictions: {country: ''}}).attr('placeholder','');

	


    

	jQuery(document).on('click','.edit-error',function(event){
		jQuery(this).css('border-color','#4dcade').removeClass('edit-error').attr('placeholder','');
		jQuery(this).next().next().remove();
	});



	jQuery(document).on('mouseup','#friend-list a.button.accept',function(event){
		var prev_count = parseInt(jQuery('a#requests .count').text());
		
		jQuery('a#requests .count').text(prev_count-1);
	});

	jQuery(document).on('mouseup','#friend-list a.button.reject',function(event){
		var prev_count = parseInt(jQuery('a#requests .count').text());
		
		jQuery('a#requests .count').text(prev_count-1);
	});





	jQuery('#custom-members-order-select').change( function() {
		
		var object = 'members';
		var scope = (jQuery('#search-order-filter-scope').length>0) ? jQuery('#search-order-filter-scope').val() : jQuery('#custom-membertype-tabs li.selected').attr('id').split('-')[1];
		var filter = jQuery('option:selected', this).val();
		var search_terms = false;

		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
		return false;
	});



	jQuery('#custom-membertype-tabs li').click( function(event) {
		var object = 'members';
		var scope = jQuery(this).attr('id').split('-')[1];
		var filter = jQuery('#custom-members-order-select option:selected').val();
		var search_terms = false;


		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
		return false;
	});




	//reviews
	//
	//
	///////////////////////////////////////////////////////

	// Make the Read More on the already-rated box have a unique class
            var arm = jQuery('.already-rated .activity-read-more');
            jQuery(arm).removeClass('activity-read-more').addClass('already-rated-read-more');

            jQuery('.post-rating-star').mouseover(function() {
                var num = jQuery(this).attr('id').substr(4, jQuery(this).attr('id').length);
                for (var i = 1; i <= num; i++)
                    jQuery('#star' + i).removeClass('fa-star-o').addClass('fa-star');
            });

            jQuery('div#review-rating').mouseout(function() {
                for (var i = 1; i <= 5; i++)
                    jQuery('#star' + i).removeClass('fa-star').addClass('fa-star-o');
            });

            jQuery('.post-rating-star').click(function() {
                var num = jQuery(this).attr('id').substr(4, jQuery(this).attr('id').length);
                for (var i = 1; i <= 5; i++)
                    jQuery('#star' + i).removeClass('fa-star').addClass('fa-star-o');
                for (var i = 1; i <= num; i++)
                    jQuery('#star' + i).removeClass('fa-star-o').addClass('fa-star');

                jQuery('.post-rating-star').unbind('mouseover');
                jQuery('div#review-rating').unbind('mouseout');

                jQuery('input#rating').attr('value', num);
            });

            jQuery('.already-rated-read-more a').live('click', function(event) {
                var target = jQuery(event.target);

                var link_id = target.parent().attr('id').split('-');
                var a_id = link_id[3];

                var a_inner = '.already-rated blockquote p';

                jQuery(target).addClass('loading');

                jQuery.post(
                        ajaxurl,
                        {
                            action: 'get_single_activity_content',
                            'activity_id': a_id
                        },
                function(response) {
                    jQuery(a_inner).slideUp(300).html(response).slideDown(300);
                });

                return false;
            });

            jQuery('#whats-new-submit').click(function() {
                if (jQuery('input#rating').val() == 0) {
                    alert('Please choose a star rating!'); //jQueryuery text i18n
                    return false;
                }
            });

            jQuery('#submit').click(function() {
                if (jQuery('input#rating').val() == 0) {
                    alert('Please Rate for This page/post !!!');
                    return false;
                }
            });








});//document.ready ends

