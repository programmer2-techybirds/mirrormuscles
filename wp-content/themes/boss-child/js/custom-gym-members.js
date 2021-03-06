jQuery(document).ready(function(){
	
	jQuery('input#search').quicksearch('.members-list li',{
    	 selector: '.fullname'
    });

    //send connection request
    jQuery('.connection-request').on('click',function(){
        var _this = jQuery(this);
        var reciver = _this.data('reciver');
        jQuery(this).addClass('loading').attr('disabled','disabled');

        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: mirrorMuscles.ajaxPath,
            data: {action: 'connection-request', reciver:reciver},
            success: function(callback) {
                window.location.reload();
            }
        });
    });

    jQuery('.connection-cancel, .connection-reject, .connection-accept, .connection-disconnect').on('click',function(){
        var _this = jQuery(this);
        var connection = _this.data('connection');
        jQuery(this).addClass('loading').attr('disabled','disabled');

        if(_this.hasClass('connection-cancel'))
            var action = 'connection-cancel'
        else if(_this.hasClass('connection-reject'))
            var action = 'connection-reject'
        else if(_this.hasClass('connection-accept'))
            var action = 'connection-accept'
        else if(_this.hasClass('connection-disconnect'))
            var action = 'connection-disconnect'
        
        jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: mirrorMuscles.ajaxPath,
            data: {action: action, connection:connection},
            success: function(callback) {
                window.location.reload();
            }
        });
    });

	
    jQuery(document).on('click',".request_friend_connection",function (){

		jQuery.fancybox({
		    'padding':  0,
		    'width':    800,
		    'height':   610,
		    'type':     'iframe',
		    'autoSize': false,
			'width': 320,
			'height': 600,
		    'content':   jQuery('#request_friend_connection').html(),
		    helpers: {
                overlay: {
                    locked: false
                }
            },
            onComplete: function(){
            	jQuery("#fancybox-inner").scrollTop(0)
            },
		     afterShow: function() {

	     		jQuery('input#search-for-connection').quicksearch('#members-list-for-connection li',{
			    	 selector: '.fullname'
			    });
			    
			    //send connection request
			    jQuery('.connection-request').on('click',function(){
			        var _this = jQuery(this);
			        var reciver = _this.data('reciver');
			        jQuery(this).addClass('loading').attr('disabled','disabled');

			        jQuery.ajax({
			            type: "POST",
			            dataType: "JSON",
			            url: mirrorMuscles.ajaxPath,
			            data: {action: 'connection-request', reciver:reciver},
			            success: function(callback) {
		                    window.location.reload();
			            }
			        });
			    });

			    jQuery('.connection-cancel, .connection-reject, .connection-accept, .connection-disconnect').on('click',function(){
			        var _this = jQuery(this);
			        var connection = _this.data('connection');
			        jQuery(this).addClass('loading').attr('disabled','disabled');

			        if(_this.hasClass('connection-cancel'))
			            var action = 'connection-cancel'
			        else if(_this.hasClass('connection-reject'))
			            var action = 'connection-reject'
			        else if(_this.hasClass('connection-accept'))
			            var action = 'connection-accept'
			        else if(_this.hasClass('connection-disconnect'))
			            var action = 'connection-disconnect'
			        
			        jQuery.ajax({
			            type: "POST",
			            dataType: "JSON",
			            url: mirrorMuscles.ajaxPath,
			            data: {action: action, connection:connection},
			            success: function(callback) {
		                    window.location.reload();
			            }
			        });
			    });                           
            }
		});

	});


	jQuery(document).on('click','.resend_parq',function(){

		var client_id = jQuery(this).data('client');
		var _this = jQuery(this);
		jQuery(this).addClass('loading disabled').attr('disabled',true);

		jQuery.ajax({
	        type: "POST",
	        url: mirrorMuscles.ajaxPath,
	        data: {action:'resend_parq',client_id:client_id},
	        success: function(data) {
	        	
	        	var data = jQuery.parseJSON(data);
	        	_this.removeClass('loading disabled').attr('disabled',true);
                
                if(data.error){
                   jQuery('#resend_parq_error').html('<div id="message" class="info"><p>'+data.error+'.</p></div>');                   
                   setTimeout(function(){
                   		jQuery('#resend_parq_error').empty();
	        			_this.attr('disabled',false);
                   },2500);
                }
			}
		});
    });


	
});