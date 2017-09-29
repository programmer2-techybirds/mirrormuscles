jQuery(document).ready(function(){



    jQuery('[rel=popover]').popover({ 

      html : true,

      trigger: "hover",

      placement: 'right',

      container: 'body'

      

      });





	//jQuery.validator.setDefaults({ ignore: ":hidden:not(select)" })





	function recalcMacronutrient(){



		var btn = jQuery('#macronutrient-presets .btn.active');

		var calories = btn.data('calories');

		var meals = btn.data('meals');

		var preset = btn.data('preset').split('-');



		var protein_calories = Math.round(calories * ( preset[0] / 100 ));

		var fat_calories = Math.round(calories * ( preset[1] / 100 ));

		var carbs_calories = Math.round(calories * ( preset[2] / 100 ));



		var protein_weight = Math.round(protein_calories/4);

		var fat_weight = Math.round(fat_calories/9);

		var carbs_weight = Math.round(carbs_calories/4);



		jQuery('.macronutrient_calories_per_day').html('Calories per day: <b>'+Math.round(calories)+' (100%)</b>');

		jQuery('#macronutrient_per_day_table td.protein').html('<h3>'+preset[0]+' %<br>'+protein_calories+' kcal<br>'+protein_weight+' g</h3>Protein');

		jQuery('#macronutrient_per_day_table td.fat').html('<h3>'+preset[1]+' %<br>'+fat_calories+' kcal<br>'+fat_weight+' g</h3>Fat');

		jQuery('#macronutrient_per_day_table td.carbs')

		

		jQuery('.macronutrient_calories_per_meal').html('Calories per meal('+meals+'): <b>'+Math.round(calories/meals)+'</b>');

		jQuery('#macronutrient_per_meal_table td.protein').html('<h3>'+Math.round(protein_calories/meals)+' kcal<br>'+Math.round(protein_weight/meals)+' g</h3>Protein');

		jQuery('#macronutrient_per_meal_table td.fat').html('<h3>'+Math.round(fat_calories/meals)+' kcal<br>'+Math.round(fat_weight/meals)+' g</h3>Fat');

		jQuery('#macronutrient_per_meal_table td.carbs').html('<h3>'+Math.round(carbs_calories/meals)+' kcal<br>'+Math.round(carbs_weight/meals)+' g</h3>Carbohydrate');



		jQuery( "#protein-slider" ).slider( "value", preset[0] ).slider({ disabled: false });

	    jQuery( "#fat-slider" ).slider( "value", preset[1] ).slider({ disabled: false });

	    jQuery( "#carbs-slider" ).slider( "value", preset[2] ).slider({ disabled: false });

	    

	}





	function recalcSliderMacronutrient(){

		var protein = jQuery( "#protein-slider" ).slider( "value" );

        var fat = jQuery( "#fat-slider" ).slider( "value" );

        var carbs = jQuery( "#carbs-slider" ).slider( "value" );

        

        

        var btn = jQuery('#macronutrient-presets .btn.macronutrient-slider-preset');

        btn.attr('data-preset', protein+'-'+fat+'-'+carbs);



		var calories = btn.attr('data-calories');

		var meals = btn.attr('data-meals');



		var protein_calories = Math.round(calories * ( protein / 100 ));

		var fat_calories = Math.round(calories * ( fat / 100 ));

		var carbs_calories = Math.round(calories * ( carbs / 100 ));



		var protein_weight = Math.round(protein_calories/4);

		var fat_weight = Math.round(fat_calories/9);

		var carbs_weight = Math.round(carbs_calories/4);



		var percentage = Math.round(protein+fat+carbs);



		jQuery('.macronutrient_calories_per_day').html('Calories per day: <b>'+Math.round(calories*(percentage/100))+' ('+percentage+'%)</b>');

		jQuery('#macronutrient_per_day_table td.protein').html('<h3>'+protein+' %<br>'+protein_calories+' kcal<br>'+protein_weight+' g</h3>Protein');

		jQuery('#macronutrient_per_day_table td.fat').html('<h3>'+fat+' %<br>'+fat_calories+' kcal<br>'+fat_weight+' g</h3>Fat');

		jQuery('#macronutrient_per_day_table td.carbs').html('<h3>'+carbs+' %<br>'+carbs_calories+' kcal<br>'+carbs_weight+' g</h3>Carbohydrate');

		

		jQuery('.macronutrient_calories_per_meal').html('Calculated calories per meal('+meals+'): <b>'+Math.round((calories/meals)*(percentage/100))+'</b>');

		jQuery('#macronutrient_per_meal_table td.protein').html('<h3>'+Math.round(protein_calories/meals)+' kcal<br>'+Math.round(protein_weight/meals)+' g</h3>Protein');

		jQuery('#macronutrient_per_meal_table td.fat').html('<h3>'+Math.round(fat_calories/meals)+' kcal<br>'+Math.round(fat_weight/meals)+' g</h3>Fat');

		jQuery('#macronutrient_per_meal_table td.carbs').html('<h3>'+Math.round(carbs_calories/meals)+' kcal<br>'+Math.round(carbs_weight/meals)+' g</h3>Carbohydrate');



	}









	var macronutrient_validator = jQuery("#macronutrient_form").validate({

		ignore: '',

        errorPlacement: function(error, element){

            var name = element.attr("name");

            jQuery('#error-' + name).append(error);

        },

        invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())

                    return;

                jQuery('html, body').animate({

                    scrollTop: jQuery(validator.errorList[0].element).offset().top-100

                }, 600);

            }

    });





    jQuery(document).on('click','#macronutrient_radio',function(event){

    	//hide all error notices

    	if(!this.checked)

    		macronutrient_validator.resetForm();



    	jQuery("#macronutrient_form input:not([type='checkbox'], #macronutrient_meals), #macronutrient_form select, #macronutrient_gender ").attr('disabled', !this.checked);

    	jQuery('#macronutrient_daily_calories').attr('disabled',this.checked);
		

    	if(this.checked){

    		//check user member type

    		var member_type = jQuery('#macronutrient_gender').data('member-type');

    		//if this is standard or pt user, than disable age and gender fields

    		if(member_type != 'gym')

    			jQuery('#macronutrient_gender, #macronutrient_age').attr('disabled', true);

    	}



    });



    jQuery(document).on('click','#macronutrient_calculate',function(event){



        event.preventDefault();



       	if( jQuery("#macronutrient_form").valid() ){

       		if( jQuery('#macronutrient_radio').is(':checked') ){

       			var gender = jQuery('#macronutrient_gender option:selected').val();

	            var age = parseInt(jQuery('#macronutrient_age').val());

	            var weight = parseFloat(jQuery('#macronutrient_weight').val()).toFixed(2);

	            var weight_units = jQuery('#macronutrient_units_weight option:selected').val();

	            var height = parseFloat(jQuery('#macronutrient_height').val()).toFixed(2);

	            var height_units = jQuery('#macronutrient_units_height option:selected').val();

	            var factor = parseFloat(jQuery('#macronutrient_factor option:selected').val()).toFixed(3);

	            //conver weight and height to value in kg and cm

	            var weight_ = (weight_units == 'kg') ? weight : ((weight_units == 'lbs') ? weight*0.45359237 : weight*0.0283495231 );

	            var height_ = (height_units == 'cm' ) ? height : height*2.54;



	            if(gender == 'Male')

	            	var bmr = 66.5 + (13.7 * weight_) + (5 * height_) - (6.76 * age);

	            else if(gender == 'Female')

	            	var bmr = 655 + (9.56 * weight_) + (1.8 * height_) - (4.68 * age);



				bmr *= factor;

       		}

       		else

       			var bmr = jQuery('#macronutrient_daily_calories').val();

       		

       		var meals = parseInt(jQuery('#macronutrient_meals').val());



       		jQuery('#macronutrient-presets .btn').removeAttr('disabled').removeClass('active').attr('data-calories',bmr).attr('data-meals',meals);

       		jQuery('#macronutrient-print, #macronutrient-share').attr('disabled',false);

       		jQuery('#macronutrient-presets .btn:first').addClass('active');

       		recalcMacronutrient();

       	}



    });//click #macronutrient_calculate



    jQuery('#macronutrient-presets .btn').click(function(){

    	jQuery('#macronutrient-presets .btn').removeClass('active');

    	jQuery(this).addClass('active');

    	recalcMacronutrient();

    });







    jQuery( "#protein-slider, #fat-slider, #carbs-slider" ).slider({

	    orientation: "horizontal",

      	range: "min",

      	max: 100,

      	value: 0,

      	slide: recalcSliderMacronutrient,

      	change: recalcSliderMacronutrient,

      	disabled: true

    });







    jQuery(document).on('click', '#macronutrient-print', function(event){

		event.preventDefault();

		jQuery('.macronutrient-print-container').printElement({pageTitle:'Macronutrient calculation results'});

	});





	jQuery(document).on('click', '#macronutrient-share', function(event){

		var _this = jQuery(this);

		event.preventDefault();

		scrollPos = document.body.scrollTop;

		

		html2canvas(jQuery("#macronutrient_results_share"), { 

	        background:'#fff',

	        onrendered: function(canvas) {

	        	

	        	jQuery(window).scrollTo(0,scrollPos);

	        	_this.addClass('loading').attr('disabled','disabled');

	            var imgData = canvas.toDataURL('image/jpeg');

		        jQuery.ajax({ 

		            type: "POST", 

		            url: mirrorMuscles.ajaxPath,

		            dataType: 'text',

		            data: {canvas : imgData, action : 'to-wall-macronutrient'},

		            success: function(data){

		            	window.location.reload();

		            }

		        }); 

	        }



	    }); //End html2canvas

	});









	/***********************************************

	/ 	KETO

	/**********************************************/



		function recalcKeto(slider) {

			

			var btn = (slider) ? jQuery('#keto-calories-presets .btn.keto-slider-preset') : jQuery('#keto-calories-presets .btn.active');



			var preset = btn.attr('data-preset');

			var bmr = btn.attr('data-bmr');

			var tdee = parseInt(btn.attr('data-tdee'));

			

			if(!slider){

				var coefficient = (preset == 'loss') ? -20 : ( (preset == 'gain') ? 20 : 0);

				tdee = (preset == 'loss') ? Math.round(tdee - (tdee*0.2)) : ((preset == 'gain') ? Math.round(tdee + (tdee*0.2)) : tdee)

			}else{

				tdee = Math.round(tdee + (tdee*preset/100));

			}

			

       		var carbs_weight = btn.attr('data-carbs-weight');

       		var protein_weight = btn.attr('data-protein-weight');

       		var carbs_calories = Math.round(carbs_weight*4);

			var protein_calories = Math.round(protein_weight * 4);

			var fat_calories = Math.round(tdee - carbs_calories - protein_calories);

			var fat_weight = Math.round(fat_calories/9);

			

			if(!slider)

       			jQuery( "#keto-calories-slider" ).slider( "value", coefficient ).slider({ disabled: false });



       		

       		jQuery('#keto_results_tdee').html('<small>TDEE: <b>'+tdee+'kcal</b></small>');



       		jQuery('#keto_per_day_table td.protein').html('<h3>'+protein_calories+' kcal<br>'+protein_weight+' g</h3>Protein');

			jQuery('#keto_per_day_table td.fat').html('<h3>'+fat_calories+' kcal<br>'+fat_weight+' g</h3>Fat');

			jQuery('#keto_per_day_table td.carbs').html('<h3>'+carbs_calories+' kcal<br>'+carbs_weight+' g</h3>Carbohydrate');



       		jQuery("#keto-results-pie").css('height',170);



       		var data = [

			    { label: "Protein",  data: protein_calories, color: "#BC5F54"},

			    { label: "Fat",  data: fat_calories, color: "#E4B101"},

			    { label: "Net Carbs",  data: carbs_calories, color: "#4BB89A"}

			];



			var keto_plot = jQuery.plot(jQuery("#keto-results-pie"), data,

				{

			        series: {

			            pie: { 

			                show: true,

			                radius: 1,

				            label: {

				                show: true,

				                radius: 0.8,

				                formatter: function(label, series) {

				                    return '<div style="font-size:12px ;text-align:center; color:white; padding: 3px;">'+Math.round(series.percent)+'%</div>';

				                },

				                background: {

				                	opacity: 0.6,

				                	color: '#30455c'

				                }

				            }

			            }

			        },

			        grid: {

			            hoverable: true

			        },

			        legend:{

	                    show: true,

	                    container: '#keto-results-pie-legend',

	                    noColumns: 3,

	                    labelBoxBorderColor: 'transparent',

	                    labelFormatter: function(label, series) {

	                    return '<span style="color:#30455C;">' + label + '</span>';

	                    }

	                },

			});



			var canvas = keto_plot.getCanvas();

			var img = canvas.toDataURL("image/png");

			jQuery('#keto-results-pie').append('<img class="canvas-print keto-canvas-print" src="' + img + '"/>');



		}









		function recalcSliderKeto() {

			var btn = jQuery('#keto-calories-presets .btn.keto-slider-preset');

        	var preset = jQuery( "#keto-calories-slider" ).slider( "value");

        	btn.attr('data-preset', preset);

        	recalcKeto(true);

		}







		function colorizeKetoSlider(ui) {

			var value = Math.abs(ui.value);

		    	var percentage = (value/50)*100;



		    	jQuery('#keto-calories-slider-value').text(ui.value);

		        

		        if(ui.value>0){

		        	jQuery('#keto-colorized-slider .min span').css('width',percentage+'%');

		        	jQuery('#keto-colorized-slider .max span').css('width','0%');

		        }



		        if(ui.value<0){

		        	jQuery('#keto-colorized-slider .max span').css('width',percentage+'%');

		        	jQuery('#keto-colorized-slider .min span').css('width','0%');

		        }  

		        if(ui.value==0){

					jQuery('#keto-colorized-slider .max span').css('width','0%');

					jQuery('#keto-colorized-slider .min span').css('width','0%');

		        }

		}





		var keto_validator = jQuery("#keto_form").validate({

			ignore: '',

	        errorPlacement: function(error, element){

	            var name = element.attr("name");

	            jQuery('#error-' + name).append(error);

	        },

	        invalidHandler: function(form, validator) {

	                if (!validator.numberOfInvalids())

	                    return;

	                jQuery('html, body').animate({

	                    scrollTop: jQuery(validator.errorList[0].element).offset().top-100

	                }, 600);

	            }

	    });







		jQuery( "#keto-calories-slider" ).slider({

		    orientation: "horizontal",

	      	range: "min",

	      	min: -50,

	      	max: 50,

	      	value: 0,

	      	slide: function (event, ui) {

		    		colorizeKetoSlider(ui); 

		           recalcSliderKeto(); 

		    },

	      	change: function(event, ui) {

	      		colorizeKetoSlider(ui);

	      		recalcSliderKeto();

	      	},

	      	disabled: true

	    });







	    jQuery(document).on('click','#keto_calculate',function(event){



	        event.preventDefault();



	       	if( jQuery("#keto_form").valid() ){



	   			var gender = jQuery('#keto_gender option:selected').val();

	            var age = parseInt(jQuery('#keto_age').val());

	            var weight = parseFloat(jQuery('#keto_weight').val()).toFixed(2);

	            var weight_units = jQuery('#keto_units_weight option:selected').val();

	            var height = parseFloat(jQuery('#keto_height').val()).toFixed(2);

	            var height_units = jQuery('#keto_units_height option:selected').val();

	            var factor = parseFloat(jQuery('#keto_factor option:selected').val()).toFixed(3);

	            //convert weight and height to value in kg and cm

	            var weight_ = (weight_units == 'kg') ? weight : ((weight_units == 'lbs') ? weight*0.45359237 : weight*0.0283495231 );

	            var height_ = (height_units == 'cm' ) ? height : height*2.54;



	            if(gender == 'Male')

	            	var bmr = 66.5 + (13.7 * weight_) + (5 * height_) - (6.76 * age);

	            else if(gender == 'Female')

	            	var bmr = 655 + (9.56 * weight_) + (1.8 * height_) - (4.68 * age);



				var tdee = Math.round(bmr * factor);			

				var carbs_weight = parseInt(jQuery('#keto_netcarbs').val());

				var protein_weight = parseInt(jQuery('#keto_protein').val()) * weight_;

				var average_protein = Math.round( ( (1.3*weight_) + (2.2*weight_) ) / 2 );

				var carbs_calories = Math.round(carbs_weight*4);

				var protein_calories = Math.round(protein_weight * 4);

				var bodyfat = parseFloat(jQuery('#keto_bodyfat').val()).toFixed(2);

				var fatmass =	Math.round(bodyfat*weight_/100);

				var leanmass = weight_-fatmass;

				var essential_fat = (gender == 'Male') ? Math.round(weight_*0.025) : Math.round(weight_*0.115);

				var fat_calories = Math.round(tdee - carbs_calories - protein_calories);

				var fat_weight = Math.round(fat_calories/9);



				jQuery('#keto_results_bmr').html('<small>BMR: <b>'+Math.round(bmr)+'kcal</b></small>');

				jQuery('#keto_results_tdee').html('<small>TDEE: <b>'+tdee+'kcal</b></small>');

				jQuery('#keto_results_intake_fat').html('<small>Fat intake should be: <b>'+fat_weight+'g</b></small>');

				jQuery('#keto_results_average_protein').html('<small>Average protein norm: <b>'+average_protein+'g</b></small>');

				jQuery('#keto_results_bodyfat').html('<small>Body fat: <b>'+fatmass+'kg</b></small>');

				jQuery('#keto_results_leanmass').html('<small>Lean mass: <b>'+leanmass+'kg</b></small>');

				jQuery('#keto_results_essential_bodyfat').html('<small>Essential body fat: <b>'+essential_fat+'kg</b></small>');



	       		jQuery('#keto-print, #keto-share').attr('disabled',false);

	       		jQuery('#keto-calories-presets .btn')

	       		.attr('data-bmr', bmr)

	       		.attr('data-tdee', tdee)

	       		.attr('data-carbs-weight', carbs_weight)

	       		.attr('data-protein-weight', protein_weight)

	       		.attr('disabled',false);

	       		

	       		recalcKeto(false);

	       		

	       	}



	    });//click #keto_calculate



	    jQuery("#keto-results-pie").bind("plothover", pieHoverKeto);

		function pieHoverKeto(event, pos, obj) {

		    if (!obj)

		        return;

		    percent = parseFloat(obj.series.percent).toFixed(2);

		    jQuery("#keto-results-pie-hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+Math.round(percent)+'%)</span>');

		}





		jQuery('#keto-calories-presets .btn').click(function(){

			jQuery('#keto-calories-presets .btn').removeClass('active');

			jQuery(this).addClass('active');

			recalcKeto(false);

		});







		jQuery(document).on('click', '#keto-print', function(event){

			event.preventDefault();

			jQuery('.keto-canvas-print').show();

			jQuery('#keto-results-pie-hover').empty();

			jQuery('.keto-print-container').printElement({pageTitle:'keto calculation results'});

		});





		jQuery(document).on('click', '#keto-share', function(event){

			var _this = jQuery(this);

			jQuery('.keto-canvas-print').hide();

			jQuery('#keto-results-pie-hover').empty();

			

			event.preventDefault();



			scrollPos = document.body.scrollTop;

			

			html2canvas(jQuery("#keto-results-share"), { 

		        background:'#fff',

		        onrendered: function(canvas) {

		        	

		        	jQuery(window).scrollTo(0,scrollPos);

		        	_this.addClass('loading').attr('disabled','disabled');



		            var imgData = canvas.toDataURL('image/jpeg');

			        jQuery.ajax({ 

			            type: "POST", 

			            url: mirrorMuscles.ajaxPath,

			            dataType: 'text',

			            data: {canvas : imgData, action : 'to-wall-keto'},

			            success: function(data){

			            	window.location.reload();

			            }

			        }); 

		        }



		    }); //End html2canvas

		});









	/***********************************************

	/	IIFYM

	/**********************************************/







    jQuery("#iifym_form").validate({

        errorPlacement: function(error, element){

            var name = element.attr("name");

            jQuery('#error-' + name).append(error);

        },

        invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())

                    return;

                jQuery('html, body').animate({

                    scrollTop: jQuery(validator.errorList[0].element).offset().top-100

                }, 600);

            }

    });







    jQuery(document).on('click','#iifym_calculate',function(event){



        event.preventDefault();



       	if( jQuery("#iifym_form").valid() ){

            var gender = jQuery('#iifym_gender option:selected').val();

            var age = parseInt(jQuery('#iifym_age').val());

            var weight = parseFloat(jQuery('#iifym_weight').val()).toFixed(2);

            var weight_units = jQuery('#iifym_units_weight option:selected').val();

            var height = parseFloat(jQuery('#iifym_height').val()).toFixed(2);

            var height_units = jQuery('#iifym_units_height option:selected').val();

            var factor = parseFloat(jQuery('#iifym_factor option:selected').val()).toFixed(3);

            var goal = jQuery('#iifym_goal option:selected').val();

            var meals = parseInt(jQuery('#iifym_meals').val());



            var weight_ = (weight_units == 'kg') ? weight : ((weight_units == 'lbs') ? weight*0.45359237 : weight*0.0283495231 );

            var height_ = (height_units == 'cm' ) ? height : height*2.54;

            var protein_weight = (weight_units == 'kg') ? Math.round((weight*2.20462) * 0.825) : ((weight_units == 'lbs') ?  Math.round(weight * 0.825) : Math.round((weight*0.0625) * 0.825) );

            



			var ree;

			switch(gender) {

			    case 'Male':

			        ree = Math.round((10 * weight_)) + Math.round((6.25 * height_)) - Math.round((5 * age)) + 5 ;

			    break;



			    case 'Female':

			        ree = Math.round((10 * weight_)) + Math.round((6.25 * height_)) - Math.round((5 * age)) - 161 ;

			    break;

			}

			var tdee = Math.round(Math.round(ree) * factor);



			var tdee_;

			switch(goal) {

			    case '-10':

			        tdee_ = tdee - (tdee * 0.1);

			    break;



			    case '-15':

			        tdee_ = tdee - (tdee * 0.15);

			    break;



			    case '-20':

			        tdee_ = tdee - (tdee * 0.2);

			    break;



			    case '10':

			        tdee_ = tdee + (tdee * 0.1);

			    break;



			    case '15':

			        tdee_ = tdee + (tdee * 0.15);

			    break;



			    case '20':

			       	tdee_ = tdee + (tdee * 0.2);

			    break;



			    case '0':

			        tdee_ = tdee;

			    break;

			}



			jQuery('.iifym_calories_per_day').html('Calories per day: <b>'+Math.round(tdee_)+'</b>');

			jQuery('.iifym_calories_per_meal').html('Calories per meal('+meals+'): <b>'+Math.round(tdee_/meals)+'</b>');





			var protein_calories = Math.round(protein_weight * 4);

			var fat_calories = Math.round(tdee_*0.25);

			var fat_weight = Math.round(fat_calories/9);

			var carbs_calories = Math.round(tdee_ - protein_calories - fat_calories);

			var carbs_weight = Math.round(carbs_calories/4);



			jQuery('#iifym_per_day_table td.protein').html('<h3>'+protein_weight+'g</h3>Protein');

			jQuery('#iifym_per_day_table td.fat').html('<h3>'+fat_weight+'g</h3>Fat');

			jQuery('#iifym_per_day_table td.carbs').html('<h3>'+carbs_weight+'g</h3>Carbohydrate');



			jQuery('#iifym_per_meal_table td.protein').html('<h3>'+Math.round(protein_weight/meals)+'g</h3>Protein');

			jQuery('#iifym_per_meal_table td.fat').html('<h3>'+Math.round(fat_weight/meals)+'g</h3>Fat');

			jQuery('#iifym_per_meal_table td.carbs').html('<h3>'+Math.round(carbs_weight/meals)+'g</h3>Carbohydrate');



			if(goal != '0')

				jQuery('#iifym_results_tdee small').html('Your TDEE is <b>'+Math.round(tdee)+'</b> calories/per day, but based on your goal, your daily target is <b>'+Math.round(tdee_)+'</b> calories/per day.')

			



			jQuery("#iifym_results_pie").css('height',170);



			var data = [

			    { label: "Protein",  data: protein_weight, color: "#BC5F54"},

			    { label: "Fat",  data: fat_weight, color: "#E4B101"},

			    { label: "Carbohydrate",  data: carbs_weight, color: "#4BB89A"}

			];



			var iifym_plot = jQuery.plot(jQuery("#iifym_results_pie"), data,

				{

			        series: {

			            pie: { 

			                show: true,

			                radius: 1,

				            label: {

				                show: true,

				                radius: 0.8,

				                formatter: function(label, series) {

				                    return '<div style="font-size:12px ;text-align:center; color:white;padding:3px;">'+Math.round(series.percent)+'%</div>';

				                },

				                background: {

				                	opacity: 0.6,

				                	color: '#30455c'

				                }

				            }

			            }

			        },

			        grid: {

			            hoverable: true

			        },

			        legend:{

	                    show: true,

	                    container: '#iifym_results_pie_legend',

	                    noColumns: 3,

	                    labelBoxBorderColor: 'transparent',

	                    labelFormatter: function(label, series) {

	                    return '<span style="color:#30455C;">' + label + '</span>';

	                    }

	                },

			});



			var canvas = iifym_plot.getCanvas();

			var img = canvas.toDataURL("image/png");

			jQuery('#iifym_results_pie').append('<img class="canvas-print iifym-canvas-print" src="' + img + '"/>');

							



			jQuery('#iifym-print, #iifym-share').attr('disabled',false);

			



		}

    });



	jQuery("#iifym_results_pie").bind("plothover", pieHoverIifym);

	function pieHoverIifym(event, pos, obj) {

	    if (!obj)

	        return;

	    percent = parseFloat(obj.series.percent).toFixed(2);

	    jQuery("#iifym_results_pie_hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+Math.round(percent)+'%)</span>');

	}





	jQuery(document).on('click', '#iifym-print', function(event){

		event.preventDefault();

		jQuery('.iifym-canvas-print').show();

		jQuery('#iifym_results_pie_hover').empty();

		jQuery('.iifym-print-container').printElement({pageTitle:'iifym calculation results'});

	});



	jQuery(document).on('click', '#iifym-share', function(event){

		var _this = jQuery(this);

		jQuery('.iifym-canvas-print').hide();

		jQuery('#iifym_results_pie_hover').empty();

		event.preventDefault();





		scrollPos = document.body.scrollTop;

		

		html2canvas(jQuery("#iifym_results_share"), { 

	        background:'#fff',

	        onrendered: function(canvas) {

	        	

	        	jQuery(window).scrollTo(0,scrollPos);

	        	_this.addClass('loading').attr('disabled','disabled');



	            var imgData = canvas.toDataURL('image/jpeg');

		        jQuery.ajax({ 

		            type: "POST", 

		            url: mirrorMuscles.ajaxPath,

		            dataType: 'text',

		            data: {canvas : imgData, action : 'to-wall-iifym'},

		            success: function(data){

		            	window.location.reload();

		            }

		        }); 

	        }



	    }); //End html2canvas

	});











});