jQuery(document).on('change', '.btn-file :file', function() {

  	var input = jQuery(this),

    numFiles = input.get(0).files ? input.get(0).files.length : 1,

    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

	

	input.trigger('fileselect', [numFiles, label]);



});





jQuery(document).ready(function(){

	

	/*************

	/	Photo progress

	/

	**************/

    jQuery('#progress_image').on('fileselect', function(event, numFiles, label) {

        

        //var input = jQuery(this).parents('.input-group').find(':text'),

        //    log = numFiles > 1 ? numFiles + ' files selected' : label;



        jQuery('#filename-display').attr('readonly',false);

		jQuery('#filename-display').val(label)

		jQuery('#filename-display').attr('readonly',true);

        

    });	



    jQuery('#progress_image').on('change', function() {

		jQuery('#error-progress_image').empty();

		if(this.files[0].size > 2097152){

			jQuery('#error-progress_image').append('<label id="progress-image-error" class="error" for="progress-image">File size more then 2Mb.</label>');

			jQuery('#progress_image').replaceWith(jQuery('#progress_image').clone());

			jQuery('#filename-display').attr('readonly',false);

			jQuery('#filename-display').val('')

			jQuery('#filename-display').attr('readonly',true);

		}

	});



	jQuery.validator.addMethod('filesize', function (value, element, param) {

	    return this.optional(element) || (element.files[0].size <= param)

	}, 'File size must be less than 2Mb');



	jQuery("#progress_img_form").validate({

		rules: {

			progress_image: {

				required: true,

				extension:'jpe?g,png',

				filesize: 2097152

			}

		},

	  	errorPlacement: function(error, element){

				var name = element.attr("name");

        		jQuery('#error-' + name).append(error);

			},

		invalidHandler: function(form, validator) {

	        jQuery('#upload_progress_image').removeClass('loading').prop('disabled',false);

    	}



	});





	jQuery('#progress_img_form').submit(function() {

		if(jQuery(this).valid()){

  			jQuery('#upload_progress_image').addClass('loading').prop('disabled',true);

  			return true;

  		}

	});



	jQuery(document).on('click','.delete-progress-image',function(){

        var post_id = jQuery(this).closest('ul').data('photo-id');

        var _this = jQuery(this);

        jQuery.ajax({

            type: "POST",

            url: mirrorMuscles.ajaxPath,

            data: {action:'delete-progress-image', post_id: post_id},

            success: function(data) {

                _this.html('<i class="fa fa-2x fa-spinner fa-spin"></i>');

                location.reload();

            }

        });

    });

    

    /*************

	/	Photo progress slider

	/

	**************/

	jQuery('.slider-for').slick({

		lazyLoad: 'ondemand',

		slidesToShow: 1,

		slidesToScroll: 1,

		arrows: true,

		asNavFor: '.slider-nav',

		autoplay: false,

		autoplaySpeed: 8000,

		fade: true,

		appendArrows: jQuery('#slider-for-arrows'),

		prevArrow: '<span class="prev"><i class="fa fa-4x fa-angle-left"></i></span>',

        nextArrow: '<span class="next"><i class="fa fa-4x fa-angle-right"></i></span>',

		

	});



	jQuery('.slider-nav').slick({

		lazyLoad: 'ondemand',

		slidesToShow: 6,

		slidesToScroll: 1,

		asNavFor: '.slider-for',

		dots: false,

		centerMode: false,

		focusOnSelect: true,

		autoplay: false,

		autoplaySpeed: 8000,

		arrows: false,

		infinite: true,

		responsive: [

		    {

		      breakpoint: 1024,

		      settings: {

		        slidesToShow: 3,

		        slidesToScroll: 3,

		        infinite: true,

		        dots: true

		      }

		    },

		    {

		      breakpoint: 600,

		      settings: {

		        slidesToShow: 2,

		        slidesToScroll: 2

		      }

		    }

		]

	});







	/*************

	/	Photo progress sharing

	/

	**************/

	jQuery(document).on('click','.photo_share_wall',function(){

        var post_id = jQuery(this).closest('ul').data('photo-id');

        var published = jQuery(this).data('published');

        var _this = jQuery(this);

        _this.html('<i class="fa fa-lg fa-spinner fa-spin" style="color:#f1b64e;"></i>');

        jQuery.ajax({

            type: "POST",

            url: mirrorMuscles.ajaxPath,

            data: {photo_share_wall: 1, post_id: post_id, published: published},

            success: function(data) {

                _this.html('<i class="fa fa-lg fa-save" style="color:#6cdaa5;"></i>');

                setTimeout(function(){

                    _this.html('<i class="fa fa-lg fa-user-plus"></i>');

                },2500);

            }

        });

    });



    jQuery(document).on('click','.photo_share_facebook',function(){

        var _this = jQuery(this);

        var published = jQuery(this).data('published');

        var FBVars = {

            fbAppId: mirrorMuscles.fbAppId,

            fbShareUrl: mirrorMuscles.homeUrl,

            fbShareImg: _this.closest('div').data('src'),

            fbShareCaption: 'Progress on the '+published,

            fbShareName: "Mirror Muscles",

            fbShareDesc: mirrorMuscles.sharePhotoDesc+' Progress on the '+published,

            baseURL: mirrorMuscles.pluginPath//path to mirror-muscle plugin

        };



        createFBShareLink = function(FBVars) {

            var url = 'http://www.facebook.com/dialog/feed?app_id=' + FBVars.fbAppId +

                '&link=' + FBVars.fbShareUrl +

                '&picture=' + FBVars.fbShareImg +

                '&name=' + encodeURIComponent(FBVars.fbShareName) + 

                '&caption=' + encodeURIComponent(FBVars.fbShareCaption) + 

                '&description=' + encodeURIComponent(FBVars.fbShareDesc) + 

                '&redirect_uri=' + FBVars.baseURL + 'PopupClose.html' + 

                '&display=popup'; 

            return url; 

        }



        var openWindow = function() {

            var url = createFBShareLink(FBVars);

            window.open(url, 

                  'feedDialog', 

                  'toolbar=0,status=0,width=626,height=436'

            ); 

        }

        openWindow();

    

    });



	jQuery('.photo_share_twitter').on('click', function(event){

		var guid = jQuery(this).data('guid');

        var published = jQuery(this).data('published');

		window.open('http://twitter.com/share?url='+guid, 'twitterwindow', 'height=450, width=550, top='+(jQuery(window).height()/2 - 225) +', left='+jQuery(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');	

	});









	/*************

	/	BFC

	/

	**************/

	jQuery(document).on('change','#units',function(){

		var val = jQuery(this).val();

		jQuery('#weight, #chest, #axilla, #triceps, #subscapular, #abdominal, #suprailiac, #thigh, #bodyfat, #fatmass, #leanmass, #mmcategory').val('');

		jQuery('#fatmass,#leanmass').next('div').text(val);

	});



	jQuery("#bfc-form").validate({

		rules: {

		    age: { required: true, min: {param: 1} },

		    weight: { required: true, pattern: /^(?=.+)(?:[1-9]\d*)?(?:\.\d+)?$/ },

		    chest: { required: true, number: true, min: {param: 1} },

		    axilla: { required: true, number: true, min: {param: 1} },

		    triceps: { required: true, number: true, min: {param: 1} },

		    subscapular: { required: true, number: true, min: {param: 1} },

		    abdominal: { required: true, number: true, min: {param: 1} },

		    suprailiac: { required: true, number: true, min: {param: 1} },

		    thigh: { required: true, number: true, min: {param: 1} },

			},

		errorPlacement: function(error, element){

			var name = element.attr("name");

    		jQuery('#error-' + name).append(error);

		},

		focusInvalid: false,

	    invalidHandler: function(form, validator) {



	        if (!validator.numberOfInvalids())

	            return;



	        jQuery('html, body').animate({

	            scrollTop: jQuery(validator.errorList[0].element).offset().top-100

	        }, 600);



	    }

	});



	var calculated = false;

	jQuery(document).on('click', '#bfc-calculate', function(){



		jQuery('#error-bodyfat').empty();

		jQuery('#error-save').empty();

		jQuery('#success-save').empty();

		jQuery('#bodyfat').val('');



		if(jQuery("#bfc-form").valid()){

			

			calculated = true;

			var gender = jQuery('#gender').val();

			var units = jQuery('#units').val();

			var age = parseInt(jQuery('#age').val());



			var chest = parseInt(jQuery('#chest').val());

			var axilla = parseInt(jQuery('#axilla').val());

			var triceps = parseInt(jQuery('#triceps').val());

			var subscapular = parseInt(jQuery('#subscapular').val());

			var abdominal = parseInt(jQuery('#abdominal').val());

			var suprailiac = parseInt(jQuery('#suprailiac').val());

			var thigh = parseInt(jQuery('#thigh').val());



			var skinfolds = chest+axilla+triceps+subscapular+abdominal+suprailiac+thigh;



			var bodydensity = ( gender == 'Male' ) ? 1.112-(0.00043499*skinfolds)+(0.00000055*skinfolds*skinfolds)-(0.00028826*age) : 1.097-(0.00046971*skinfolds)+(0.00000056*skinfolds*skinfolds)-(0.00012828*age);

			var bodyfat =  [(4.95/bodydensity) - 4.5]*100;

			

			if(bodyfat>0){



				var weight = parseFloat(jQuery('#weight').val());



				var fatmass = parseFloat(bodyfat*weight/100);

				var leanmass = weight-fatmass;



				var bodyfat_g = Math.round(bodyfat);



				if( gender=='Male' )

					{

						if(bodyfat_g <= 4 )

							var mmcategory = 'Competitor';

						else if (bodyfat_g>4&&bodyfat_g<=14)

							var mmcategory = 'Athletes';

						else if(bodyfat_g>14&&bodyfat_g<=18)

							var mmcategory = 'Fitness';

						else if(bodyfat_g>18&&bodyfat_g<=26)

							var mmcategory = 'Acceptable';

						else if(bodyfat_g>26)

							var mmcategory = 'Obese';

					}

					else

					{

						if(bodyfat_g <= 12 )

							var mmcategory = 'Competitor';

						else if (bodyfat_g>12&&bodyfat_g<=21)

							var mmcategory = 'Athletes';

						else if(bodyfat_g>21&&bodyfat_g<=25)

							var mmcategory = 'Fitness';

						else if(bodyfat_g>25&&bodyfat_g<=32)

							var mmcategory = 'Acceptable';

						else if(bodyfat_g>32)

							var mmcategory = 'Obese';

					}

				

				jQuery('#bodyfat').val(bodyfat.toFixed(2));

				jQuery('#fatmass').val(fatmass.toFixed(1));

				jQuery('#leanmass').val(leanmass.toFixed(1));

				jQuery('#mmcategory').val(mmcategory);

				

				

				var share_text = 'My Bodyfat Calculator Results are: Bodyfat - '+bodyfat.toFixed(2)+'%, Fatmass - '+fatmass.toFixed(1)+'kg, Leanmass - '+leanmass.toFixed(1)+'kg, Category - '+mmcategory+'.';



				jQuery('#bfc-share-wall').attr('data-wall',share_text);

				jQuery('#bfc-share-twitter').attr('data-tweet', encodeURIComponent(share_text));

				jQuery('#bfc-share-facebook').attr('data-description', share_text);

				var options = createGoogleShareOptions(share_text);

				gapi.interactivepost.render('bfc-share-google',options);

				jQuery('.share_with_email_text').prop('readonly',false);

				jQuery('.share_with_email_text').val(share_text);

				jQuery('.share_with_email_text').prop('readonly',true);





				



			}

			else{

				calculated = false;

				jQuery('#bodyfat').val(bodyfat.toFixed(2));

				jQuery('#error-bodyfat').html('<label for="boddyfat" class="error" id="bodyfat-error">Please, enter new measurements</label>')

				jQuery('#weight, #chest, #axilla, #triceps, #subscapular, #abdominal, #suprailiac, #thigh').val('');

			}

		}

	});





	jQuery(document).on('click','#bfc-save:not(.disabled)', function(event){

		

		jQuery('#error-save').empty();

		jQuery('#success-save').empty();

		jQuery('#update-results').empty();

		

		if(jQuery("#bfc-form").valid()){

	

			jQuery('#bfc-save').addClass('loading disabled');

	

			if(calculated){

				var data = jQuery("#bfc-form").serialize();

                jQuery.ajax({

                type: "POST",

                url: mirrorMuscles.ajaxPath,

                data: {save_bfc: 1, data: data},

                success: function(data) {

                	var callback = jQuery.parseJSON(data);

                	

                		if(callback.success){

                			//jQuery('#bfc-save').removeClass('loading');

                        	//jQuery('#update-results').append('<div id="message" class="info text-center"><p>There is new saved results. Please <a href="javascript:history.go(0)">Reload</a> to see them.</p></div>')

                        	//jQuery('#success-save').append('<div id="message" class="updated text-center"><p>Results successfully saved.</p></div>');

                			window.location.reload();

                		}else if(callback.error){

                			jQuery('#bfc-save').removeClass('loading disabled');

                        	jQuery('#success-save').append('<label class="error" style="text-align: center !important">There is allready saved result for today.</label>');

                		}else{

                			jQuery('#bfc-save').removeClass('loading disabled');

                        	jQuery('#success-save').append('<label class="error" style="text-align: center !important">An error occured. Please try later.</label>');

                		}

                   	}

                }); 

			}

			else{

				jQuery('#bfc-save').removeClass('loading disabled');

				jQuery('#error-save').append('<label class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>');

			}			

		}

	});





	jQuery(document).on('click','#bfc-share-email', function(event){

		jQuery('#error-save').empty();

        jQuery('#success-save').empty();

        console.log('ddd');

		if(jQuery("#bfc-form").valid()){

			if(calculated)

				jQuery('.share_with_email').show();

			else

				jQuery('#error-save').append('<label id="bfc-email-share-error" class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>')			

		}

	});





	function validateEmail(sEmail) {

		var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

		if (filter.test(sEmail)){

		    return true;

		}

		else{

		    return false;

		}

	}



	jQuery('.share_with_email_send').on('click', function(event){

		event.preventDefault();

		jQuery('#success-save').empty();



		var text = jQuery('.share_with_email_text').val();

		var email = jQuery('.share_with_email_email').val();

		var subject = 'My Bodyfat Calculator Result';

    

        if (jQuery.trim(email).length == 0 || !validateEmail(email)) {

            jQuery('#error-save').append('<label id="bfc-email-share-error" class="error" style="text-align: center !important">Invalid email address.</label>');

            event.preventDefault();

        }else{

			jQuery.ajax({

			    type: "POST",

				url: mirrorMuscles.ajaxPath,

				data: {bfc_share_email: 1, email: email, subject: subject, text: text},

				success: function(data) {

			        jQuery('#success-save').append('<div id="message" class="updated text-center"><p>Results successfully shared with email.</p></div>')

			    	jQuery('.share_with_email').hide();

			    }

			});

        }



	});



	jQuery('#bfc-share-wall').on('click', function(event){

        jQuery('#error-save').empty();

        jQuery('#success-save').empty();

        if(jQuery("#bfc-form").valid()){

            if(calculated){

                var text = jQuery(this).data('wall');

                jQuery('#bfc-share').addClass('loading');

                jQuery.ajax({

                type: "POST",

                url: mirrorMuscles.ajaxPath,

                data: {bfc_share_wall: 1, text: text},

                success: function(data) {

                        jQuery('#bfc-share').removeClass('loading');

                        jQuery('#success-save').append('<div id="message" class="updated text-center"><p>Results successfully shared to the Wall.</p></div>')

                    }

                }); 

            }

            else{

                jQuery('#bfc-share').removeClass('loading');

                jQuery('#error-save').append('<label class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>')            

            }

        }

    });





	createFBShareLink = function() {

        var url = 'http://www.facebook.com/dialog/feed?app_id=' + mirrorMuscles.fbAppId +

            '&link=' + document.location.origin +'/my-progress' +

            '&picture='+mirrorMuscles.themeDir+'/images/mm_sharing_logo.jpg";?>'+

            '&name=Mirror Muscles: My Results' + 

            '&caption=<a href="'+document.location.origin+'">' + document.location.origin + 

            '&description=' + jQuery('#bfc-share-facebook').data('description') + //creates on calculated press

            '&redirect_uri='+document.location.origin+'/wp-content/plugins/mirror-muscles/PopupClose.html' + 

            '&display=popup'; 



        return url; 

    }



    jQuery('#bfc-share-facebook').on('click', function(event){

		jQuery('#error-save').empty();

		if(jQuery("#bfc-form").valid()){

			if(calculated){	

				var openFBWindow = function() {

				  	var url = createFBShareLink();

				  	window.open(url, 

				              	'feedDialog', 

				              	'toolbar=0,status=0,width=626,height=436'

				  ); 

				}		

 				openFBWindow();

			}

			else

				jQuery('#error-save').append('<label class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>');		

		}

	});



    createGoogleShareOptions = function(prefilltext) {

        var options = {

                contenturl: mirrorMuscles.homeUrl+'<?php echo "?".rand();?>',

                clientid: mirrorMuscles.googleAppId,

                cookiepolicy: 'single_host_origin',

                prefilltext: prefilltext,

                calltoactionlabel: 'CREATE',

                calltoactionurl: mirrorMuscles.homeUrl

        }; 



        return options; 

    }



    jQuery('#bfc-share-google').on('mousedown', function(event){

		event.preventDefault();

		jQuery('#error-save').empty();

		if(jQuery("#bfc-form").valid()){

			if(calculated){

				jQuery('#bfc-share-google').click();

			}			

			else

				jQuery('#error-save').append('<label class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>');

		}

	});



	jQuery('#bfc-share-twitter').on('click', function(event){

		jQuery('#error-save').empty();

		if(jQuery("#bfc-form").valid()){

			if(calculated){

				var loc = document.location.origin;

				var text = jQuery(this).data('tweet');

 				window.open('http://twitter.com/share?url=' + loc + '&text=' + text + '&', 'twitterwindow', 'height=450, width=550, top='+(jQuery(window).height()/2 - 225) +', left='+jQuery(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');	

			}

			else

				jQuery('#error-save').append('<label class="error" style="text-align: center !important">You must calculate results at first and then Share them.</label>');

		}

	});







	/*************

	/	PREVIOUS RESULTS

	/

	**************/

	jQuery(document).on('click','.delete-prev-result',function(){

        var result_id = jQuery(this).attr('data-result');

        _this = jQuery(this);

        _this.removeClass('fa-trash').addClass('fa-spinner fa-spin');

        var footable = jQuery(this).parents('table:first').data('footable');

        jQuery.ajax({

            type: "POST",

            url: mirrorMuscles.ajaxPath,

            data: {action: 'delete-prev-bfc-result', result_id: result_id},

            success: function(data) {

                _this.closest('tr').fadeOut(600,function(){

					var row = _this.parents('tr:first');

		        	footable.removeRow(row);

				});

            }

        });   

    });







});