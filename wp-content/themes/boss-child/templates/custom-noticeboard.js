jQuery(document).on('change', '.btn-file :file', function() {
  	var input = jQuery(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  
  	input.trigger('fileselect', [numFiles, label]);

});


jQuery(document).ready(function(){

	jQuery('#noticeboard-image').on('fileselect', function(event, numFiles, label) {
        jQuery('#filename-display').attr('readonly',false);
		jQuery('#filename-display').val(label)
		jQuery('#filename-display').attr('readonly',true);
    });



	jQuery.validator.addMethod('filesize', function (value, element, param) {
	    return this.optional(element) || (element.files[0].size <= param)
	}, 'File size must be less than 2Mb');

    jQuery.validator.addMethod('short_url', function( value, element ) {
    // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
    return this.optional( element ) || /^(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\jQuery&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\jQuery&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\jQuery&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\jQuery&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\jQuery&'\(\)\*\+,;=]|:|@)|\/|\?)*)?jQuery/i.test( value );
}, 'Please enter a valid url without the "http");

    jQuery("#new-noticeboards-post").validate({
		rules: {
			'noticeboard-title': {
				required: true,
				maxlength: 150
			},
			'noticeboard-content': {
				required: true,
				maxlength: 500
			},
			'noticeboard-image': {
				required: true,
				extension:'jpg,jpeg,png',
				filesize: 2097152
			},
			'noticeboard-link': {
				short_url: true
			}
		},
	  	errorPlacement: function(error, element){
				var name = element.attr("name");
        		jQuery('#error-' + name).append(error);
			},
		invalidHandler: function(form, validator) {
	        jQuery('#save-noticeboard').removeClass('loading').prop('disabled',false);
    	}

	});


	jQuery('#new-noticeboards-post').submit(function() {
		if(jQuery(this).valid()){
  			jQuery('#save-noticeboards-post').addClass('loading').prop('disabled',true);
  			return true;
  		}
	});



	

    jQuery(document).on('click','.delete-noticeboards-post',function(){
        var post_id = jQuery(this).attr('data-post');
        _this = jQuery(this);
        _this.removeClass('fa-trash').addClass('fa-spinner fa-spin');
        jQuery.ajax({
            type: "POST",
            url: mirrorMuscles.ajaxPath,
            data: {action: 'delete-noticeboards-post', post_id: post_id},
            success: function(data) {
            	jQuery('#new-noticeboards-post')[0].reset();
    			jQuery('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-pencil');
                _this.closest('.notice-post-container').fadeOut(600,function(){
					jQuery(this).remove();
				});
            }
        });   
    });

    String.prototype.filename=function(extension){
	    var s= this.replace(/\\/g, '/');
	    s= s.substring(s.lastIndexOf('/')+ 1);
	    return extension? s.replace(/[?#].+jQuery/, ''): s.split('.')[0];
	}

    jQuery(document).on('click','.edit-noticeboards-post',function(){
    	
    	jQuery('#new-noticeboards-post')[0].reset();
        jQuery('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-pencil');
        var post_id = jQuery(this).attr('data-post');
        _this = jQuery(this);
        _this.removeClass('fa-pencil').addClass('fa-spinner fa-spin');
        var title = _this.closest('.notice-post-container').find('.notice-post-title').text();
        var content = _this.closest('.notice-post-container').find('.notice-post-content').text();
        //var image_filename = _this.closest('.notice-post-container').find('.notice-post-image img').attr('src').filename();
        var link = _this.closest('.notice-post-container').find('.notice-post-link').text();

        jQuery('#noticeboard-title').val(title);
       	jQuery('#noticeboard-content').val(content);
        //jQuery('#filename-display').attr('readonly',false);
        //jQuery('#filename-display').text(image_filename);
        //jQuery('#filename-display').attr('readonly',true);
		jQuery('#noticeboard-link').val(link);
        jQuery("[name='save-noticeboards-post']").val(post_id);
        jQuery('#clear-noticeboards-post-edit').show();

        jQuery('html, body').animate({
            scrollTop: jQuery('#new-noticeboards-post').position().top
        }, 1000);
    });

    jQuery('#clear-noticeboards-post-edit').click(function(){
        jQuery('#new-noticeboards-post')[0].reset();
        jQuery("[name='save-noticeboards-post']").val('');
        jQuery('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-pencil');
        jQuery(this).hide();
  	});

});