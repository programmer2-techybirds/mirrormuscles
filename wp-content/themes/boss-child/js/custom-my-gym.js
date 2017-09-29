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


	
});