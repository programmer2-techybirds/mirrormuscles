jQuery(document).ready(function(){
	jQuery("html,body").scrollTop(0);

	function showExerciseRepeats(container,n){
		var trs = '';
		for(i=1; i<=n; i++)
			trs += '<tr>\
						<td>Set '+i+'</td>\
						<td><input class="exerciserepeats" type="number" min="1" max="99" step="1" value="1" required></td>\
						<td><input class="exerciseweight" type="number" min="0" max="1000" step="1" value="0" required></td>\
					</tr>';
        jQuery('.repeats-weight-table tbody',container).html(trs);
        jQuery('.exercisesets',container).val(n).trigger('chosen:updated');
	}

	
	function showExerciseRepeatsLoad(container,n){
		var trs = '';
		for(i=1; i<=n; i++)
			trs += '<tr>\
						<td>Set '+i+'</td>\
						<td><input class="exerciserepeats" type="number" min="1" max="9999" step="1" value="1" required></td>\
                        <td><input class="exerciseload" type="number" min="0" max="9999" step="1" value="1" required></td>\
                        <td><input class="exerciserest" type="number" min="0" max="600" step="1" value="1" required></td>\
					</tr>';
        jQuery('.repeats-weight-table tbody',container).html(trs);
        jQuery('.adv-exercisesets',container).val(n).trigger('chosen:updated');
	}


	

	jQuery('.exercisename').chosen({width: '280px','inherit_select_classes':true});
	jQuery('.workout-client-id').chosen({width: '280px'});
	jQuery('.exercisesets').chosen({width: '100px', disable_search: true});
	jQuery('.exerciseorder').chosen({width: '100px', disable_search: true});

	//fllback validation rule for choosen select
	jQuery.validator.setDefaults({ ignore: ":hidden:not(select)" });

	jQuery.validator.addMethod("customMaxLength", function(value, element, param) {
		return jQuery.validator.methods.maxlength.call(this, value, element, param[1]);
	}, '{0}');

	jQuery.validator.addMethod("customMaxVal", function(value, element, param) {
		return jQuery.validator.methods.max.call(this, value, element, param[1]);
	}, '{0}');

	jQuery.validator.addMethod("customMinVal", function(value, element, param) {
		return jQuery.validator.methods.min.call(this, value, element, param[1]);
	}, '{0}');


	jQuery.validator.addClassRules({
		'exerciseweight': {
			customMaxVal: [ 'Exercise Weight max value is 9999.', 9999 ],
		},
		'exercisetempo': {
			customMaxLength: [ 'Exercise Tempo max value is 9999.', 9999 ]
		},
		'exerciseload': {
			customMaxVal: [ 'Exercise Load max value is 9999.', 9999 ],
			customMinVal: [ 'Exercise Load min value is 1.', 1 ]
		},
		'exerciserest': {
			customMaxVal: [ 'Exercise Rest max value is 600 seconds.', 600 ],
			customMinVal: [ 'Exercise Rest min value is 1 second.', 1 ]
		}

	});
	
	jQuery('form.wl-form').each(function(key, form) {
	    jQuery(form).validate({
			errorPlacement: function(error, element){
	                var name = element.attr("name");
	                if(typeof name !== 'undefined')
	                	jQuery('#error-' + name).append(error);
	                else{
	                	element.closest('table').after(error);
	                	jQuery(element).val(1);
	                }
	            },
		    invalidHandler: function(form, validator) {

		        if (!validator.numberOfInvalids())
		            return;

		        jQuery('html, body').animate({
		            scrollTop: jQuery(validator.errorList[0].element).offset().top-100
		        }, 600);

		    }
		});
	});
	
	jQuery(document).on('click','.r-tabs-tab',function(){
		jQuery('.popover').remove();
	});

    jQuery(document).on('change','.exercisename',function(){

    	jQuery('.popover').remove();

		var container = jQuery(this).closest('.select-exercise-container');
		var name = jQuery('option:selected',this).data('name');
		var description = jQuery('option:selected',this).data('description');
		var video = jQuery('option:selected',this).data('video');
		
		var muscles = jQuery('option:selected',this).data('muscles');
		var muscles_ids = jQuery('option:selected',this).data('muscles-ids').toString().split(', ');
		var muscles_is_front = jQuery('option:selected',this).data('muscles-is-front').toString().split(', ');
		var muscle_popover_ = muscles_popover = '';
        
        jQuery.each(muscles_ids,function(k,v){
        	var side = ( muscles_is_front[k] == 1 ) ? 'muscle-front-background ' : 'muscle-back-background ';
        	var class_ = side+'muscle-'+v+' ';
        	muscle_popover_ += '<div class=\''+side+class_+'\'></div> ';
    	});

    	if(muscle_popover_.length>0)
    		muscles_popover = '<a type="button" class="btn small inverse" rel="popover" data-dismiss="popover" data-content="'+muscle_popover_+'" data-html="true">Show muscles</a>';
		
		var muscles_secondary = jQuery('option:selected',this).data('muscles-secondary');
		var muscles_secondary_ids = jQuery('option:selected',this).data('muscles-secondary-ids').toString().split(', ');
		var muscles_secondary_is_front = jQuery('option:selected',this).data('muscles-secondary-is-front').toString().split(', ');
		var muscle_secondary_popover_ = muscles_secondary_popover = '';
        
        jQuery.each(muscles_secondary_ids,function(k,v){
        	var side = ( muscles_secondary_is_front[k] == 1 ) ? 'muscle-front-background ' : 'muscle-back-background ';
        	var class_ = side+'muscle-'+v+' ';
        	muscle_secondary_popover_ += '<div class=\''+side+class_+'\'></div> ';
    	});

    	if(muscle_secondary_popover_.length>0)
    		muscles_secondary_popover = '<a type="button" class="btn small inverse" rel="popover" data-dismiss="popover" data-content="'+muscle_secondary_popover_+'" data-html="true">Show muscles</a>';
		

		var equipment = jQuery('option:selected',this).data('equipment');
		var comment = jQuery('option:selected',this).data('comment');
		var images = jQuery('option:selected',this).data('images');
		var images_ = '';
		
		if(typeof images !== 'undefined')
			jQuery.each(images.split('&&&'),function(k,v){
			    images_ += '<img class="exerciseimage" src="'+v+'">';
			});

		jQuery('table.exercise-details td.exercise-name',container).html(name);
		jQuery('table.exercise-details td.exercise-description',container).html(description);
		jQuery('table.exercise-details td.exercise-video',container).html(video);
		jQuery('table.exercise-details td.exercise-muscles',container).html(muscles+' '+muscles_popover);
		jQuery('table.exercise-details td.exercise-muscles-secondary',container).html(muscles_secondary+' '+muscles_secondary_popover);
		jQuery('table.exercise-details td.exercise-equipment',container).html(equipment);
		jQuery('table.exercise-details td.exercise-comment',container).html(comment);
		jQuery('table.exercise-details td.exercise-images',container).html(images_);

		if(jQuery('option:selected',this).val() == 0)
			jQuery('table.exercise-details td.exercise-name,\
				table.exercise-details td.exercise-description,\
				table.exercise-details td.exercise-video,\
				table.exercise-details td.exercise-muscles,\
				table.exercise-details td.exercise-muscles-secondary,\
				table.exercise-details td.exercise-equipment,\
				table.exercise-details td.exercise-comment,\
				table.exercise-details td.exercise-images',container).html('');			
		
		jQuery('[rel=popover]').popover({ 
	        html : true,
	        trigger: 'click',
	        placement: 'bottom',
	        container: 'body'
	    });
        
    });


    //disable|enable custom exercise name
	jQuery('.add-wl-exercisename-custom-radio').on('click',function(){
		var container = jQuery(this).closest('.select-exercise-container');
		
		jQuery('.exercisename',container).attr('disabled',this.checked).trigger('chosen:updated');
		jQuery('.exercisename-custom',container).attr('disabled',!this.checked);
		jQuery('label.error',container).remove();

	});


	//show|hide exercise details
	jQuery('.show-exercise-details').on('click',function(){
		jQuery('.popover').remove();
		var container = jQuery(this).closest('.select-exercise-container');
		jQuery('.exercise-details-container',container).toggle();
	});



	jQuery('.exerciseweekdays .btn,.exerciseweeknums .btn').on('click', function(){
		var container = jQuery(this).closest('div');
		jQuery('.btn',container).removeClass('active');
		jQuery(this).addClass('active');
	});


	//call `draw tr` function on `sets select` changes
	jQuery(document).on('change','.exercisesets, .adv-exercisesets',function(){
    	var container = jQuery(this).closest('.select-exercise-container');
	    var sets = parseInt(jQuery('option:selected',this).val());
	    if(jQuery(this).hasClass('adv-exercisesets'))
	    	showExerciseRepeatsLoad(container,sets);
		else
	    	showExerciseRepeats(container,sets);
    });

   	

   	 jQuery(document).on('click','.add-day-exercise',function(){
		var container = jQuery(this).closest('.select-exercise-container');
		jQuery('.error-no-exercise',container).remove();
		var daynum = jQuery('.exerciseweekdays .btn.active').data('daynum');
		var dayname = jQuery('.exerciseweekdays .btn.active').data('dayname');
    	
    	if(container.valid()){
    		if(jQuery('.add-wl-exercisename-custom-radio',container).is(":checked")){
    			var exercise_id = 0;
    			var exercise_name = jQuery('.exercisename-custom',container).val();
    		}
    		else{
    			var exercise_id = jQuery('.exercisename option:selected',container).val();
    			var exercise_name = jQuery('.exercisename option:selected',container).text();
    		}
    	
    		var repeats = []
	    	jQuery('.exerciserepeats',container).each(function(i,e){
	    		repeats.push(parseInt(jQuery(this).val()));
	    	});
		    
		    var weights = []
		    jQuery('.exerciseweight',container).each(function(i,e){
	    		weights.push(parseInt(jQuery(this).val()));
	    	});

		    repeats_td = [];
		    jQuery.each(repeats,function(i,e){
		    	var str = e;
		    	var weight = (weights[i]) ? '('+weights[i]+')' : '(0)';
		    	str += weight;
		    	repeats_td.push(str);
		    });

	    	var tr = '<tr data-daynum="'+daynum+'" data-exercise-id="'+exercise_id+'" data-repeats="'+repeats.join(', ')+'" data-weights="'+weights.join(', ')+'">\
	    				<td>'+dayname+'</td><td>'+exercise_name+'</td><td>'+repeats_td.join(' - ')+'</td><td><i class="fa fa-trash fa-lg remove-workout-exercise"></i><span class="hidden-exercise-name">'+exercise_name+'</tr></td>\
	    			</tr>';

	    	jQuery('.workout-table-exercises tbody',container).append(tr);
	    	
	    	jQuery('html, body').animate({
		        scrollTop: jQuery(".workout-table-exercises",container).offset().top
	    	}, 600);

	    	jQuery('.footable').trigger('footable_redraw');
	    	return false;
    	}	
    });


   	jQuery(document).on('click','.add-week-exercise',function(){
		var container = jQuery(this).closest('.select-exercise-container');
		var weeknum = jQuery('.exerciseweeknums .btn.active',container).data('week');
    	
    	if(container.valid()){
    		
    		var exercise_order = jQuery('.exerciseorder option:selected',container).val();
    		var exercise_tempo = jQuery('.exercisetempo',container).val();

    		if(jQuery('.add-wl-exercisename-custom-radio',container).is(":checked")){
    			var exercise_id = 0;
    			var exercise_name = jQuery('.exercisename-custom',container).val();
    		}
    		else{
    			var exercise_id = jQuery('.exercisename option:selected',container).val();
    			var exercise_name = jQuery('.exercisename option:selected',container).text();
    		}
    	
    		var repeats = []
	    	jQuery('.exerciserepeats',container).each(function(i,e){
	    		repeats.push(parseInt(jQuery(this).val()));
	    	});
		    
		    var loads = []
		    jQuery('.exerciseload',container).each(function(i,e){
	    		loads.push(parseInt(jQuery(this).val()));
	    	});

	    	var rest = []
		    jQuery('.exerciserest',container).each(function(i,e){
	    		rest.push(parseInt(jQuery(this).val()));
	    	});
		    
	    	var tr = '<tr data-order="'+exercise_order+'" data-tempo="'+exercise_tempo+'" data-weeknum="'+weeknum+'" data-exercise-id="'+exercise_id+'" data-repeats="'+repeats.join(', ')+'" data-loads="'+loads.join(', ')+'" data-rest="'+rest.join(', ')+'">\
	    				<td>'+exercise_order+'</td><td>'+weeknum+'</td><td>'+exercise_name+'</td><td>'+exercise_tempo+'</td><td>'+repeats.join('<br>')+'</td><td>'+loads.join('<br>')+'</td><td>'+rest.join('<br>')+'</td><td><i class="fa fa-trash fa-lg remove-workout-exercise"></i><span class="hidden-exercise-name">'+exercise_name+'</tr></td>\
	    			</tr>';

	    	jQuery('.workout-table-exercises tbody',container).append(tr);
	    	
	    	jQuery('html, body').animate({
		        scrollTop: jQuery(".workout-table-exercises",container).offset().top
	    	}, 600);

	    	jQuery('.footable').trigger('footable_redraw');
	    	return false;
    	}	
    });


   

    jQuery(document).on('click', '.remove-workout-exercise', function(){
		var _this  = jQuery(this);
		var footable = jQuery(this).parents('table:first').data('footable');
		jQuery(this).closest('tr').fadeOut(600,function(){
			var row = _this.parents('tr:first');
        	footable.removeRow(row);
		});
	});

	jQuery(document).on('click', '#save-new-wl', function(event){

		var container = jQuery(this).closest('.select-exercise-container');
		var _this = jQuery(this);
		
		if(container.valid()){
			if(jQuery(".workout-table-exercises tbody tr",container).length>0){
				var workout = [];
				jQuery(".workout-table-exercises tbody tr",container).each(function(i,e){
					var daynum = jQuery(e).data('daynum');
					var exercise_id = jQuery(e).data('exercise-id');
					var exercise_name = jQuery(e).find('.hidden-exercise-name').text().trim();
					var repeats = jQuery(e).data('repeats');
					var weights = jQuery(e).data('weights');
					workout.push({'day':daynum,'exercise_id':exercise_id,'exercise_name':exercise_name,'repeats':repeats,'weights':weights});
				});

				var name = jQuery('.workout-log-name',container).val();
				var client_id = jQuery('.workout-client-id option:selected',container).val();

				_this.addClass('loading').attr('disabled',true);

				jQuery.ajax({
	                type: "POST",
	                url: mirrorMuscles.ajaxPath,
	                data: {action: 'save-new-workout-log', workout: workout, name: name, client_id: client_id},
	                success: function(data) {
							jQuery('#successmessage22').show( "slow" );
							setTimeout(function() {
								jQuery('#successmessage22').hide( "fast" );
								_this.removeClass('loading');
	                        	window.location.reload();
							}, 5000);
	                        
	                }
	            });
			}else
				_this.before('<div id="error-no-exercise" class="error-no-exercise"><label class="error">Please add at least 1 exercise.</label></div>');
		}
	});
	
	
	jQuery(document).on('click', '#save-new-swl', function(event){

		var container = jQuery(this).closest('.select-exercise-container');
		var _this = jQuery(this);
		
		if(container.valid()){
			if(jQuery(".workout-table-exercises tbody tr",container).length>0){
				var workout = [];
				jQuery(".workout-table-exercises tbody tr",container).each(function(i,e){
					var daynum = jQuery(e).data('daynum');
					var exercise_id = jQuery(e).data('exercise-id');
					var exercise_name = jQuery(e).find('.hidden-exercise-name').text().trim();
					var repeats = jQuery(e).data('repeats');
					var weights = jQuery(e).data('weights');
					workout.push({'day':daynum,'exercise_id':exercise_id,'exercise_name':exercise_name,'repeats':repeats,'weights':weights});
				});

				var name = jQuery('.workout-log-name',container).val();
				var client_id = jQuery('.workout-client-id option:selected',container).val();

				_this.addClass('loading').attr('disabled',true);

				jQuery.ajax({
	                type: "POST",
	                url: mirrorMuscles.ajaxPath,
	                data: {action: 'save-new-workout-log-sample', workout: workout, name: name, client_id: client_id},
	                success: function(data) {
							jQuery('#successmessage77').show( "slow" );
							setTimeout(function() {
								jQuery('#successmessage77').hide( "fast" );
								_this.removeClass('loading');
	                        	window.location.reload();
							}, 5000);
	                        
	                }
	            });
			}else
				_this.before('<div id="error-no-exercise" class="error-no-exercise"><label class="error">Please add at least 1 exercise.</label></div>');
		}
	});


	jQuery(document).on('click', '#save-new-awl', function(event){

		var container = jQuery(this).closest('.select-exercise-container');
		var _this = jQuery(this);
		
		if(container.valid()){
			if(jQuery(".workout-table-exercises tbody tr",container).length>0){
				var workout = [];
				
				jQuery(".workout-table-exercises tbody tr",container).each(function(i,e){
					var exercise_order = jQuery(e).data('order');
					var tempo = jQuery(e).data('tempo');
					var week = jQuery(e).data('weeknum');
					var exercise_id = jQuery(e).data('exercise-id');
					var exercise_name = jQuery(e).find('.hidden-exercise-name').text().trim();
					var repeats = jQuery(e).data('repeats');
					var loads = jQuery(e).data('loads');
					var rest = jQuery(e).data('rest');
					workout.push({'exercise_order':exercise_order, 'week':week, 'tempo':tempo, 'exercise_id':exercise_id,'exercise_name':exercise_name,'repeats':repeats,'loads':loads,'rest':rest});
				});

				var name = jQuery('.workout-log-name',container).val();
				var client_id = jQuery('.workout-client-id option:selected',container).val();

				_this.addClass('loading').attr('disabled',true);

				jQuery.ajax({
	                type: "POST",
	                url: mirrorMuscles.ajaxPath,
	                data: {action: 'save-new-adv-workout-log', workout: workout, name: name, client_id: client_id},
	                success: function(data) {
							jQuery('#successmessage33').show( "slow" );
							setTimeout(function() {
								jQuery('#successmessage33').hide( "fast" );
								_this.removeClass('loading');
	                        	window.location.reload();
							}, 5000);
	                }
	            });
            }else
				_this.before('<div id="error-no-exercise" class="error-no-exercise"><label class="error">Please add at least 1 exercise.</label></div>');

		}
	});



	jQuery(document).on('click','.delete-workout-log',function(event){
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');
        var uniq_id = container.data('uniqid');
        var action = (container.hasClass('advanced')) ? 'delete-adv-workout-log' : 'delete-workout-log';
       	_this.addClass('loading').attr('disabled',true);

        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action, uniq_id:uniq_id},
            success: function(data){
                if(!data.error){
                    _this.removeClass('loading');
                    container.closest('.ui-accordion-content').prev('.ui-accordion-header').remove();
                    container.closest('.ui-accordion-content').remove();
                }
                else
                   window.location.reload();
            }
        });
    });
	
	jQuery(document).on('click','.delete-workout-log-sample',function(event){
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');
        var uniq_id = container.data('uniqid');
        var action = 'delete-workout-log-sample';
       	_this.addClass('loading').attr('disabled',true);

        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action, uniq_id:uniq_id},
            success: function(data){
                if(!data.error){
                    _this.removeClass('loading');
                    container.closest('.ui-accordion-content').prev('.ui-accordion-header').remove();
                    container.closest('.ui-accordion-content').remove();
                }
                else
                   window.location.reload();
            }
        });
    });


    jQuery(document).on('click','.delete-workout-log-exercise',function(event){
        var _this = jQuery(this);
        var id = _this.data('id');
       	var container = jQuery(this).closest('.saved-wl-container');
       	var action = (container.hasClass('advanced')) ? 'delete-adv-workout-log-exercise' : 'delete-workout-log-exercise';
		var footable = jQuery(this).parents('table:first').data('footable');
        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action, id:id},
            success: function(data){
                if(!data.error){
                	if(_this.parents('table tbody').find('tr').length>1){
                    	_this.closest('tr').fadeOut(600, function() {
                    		var row = _this.parents('tr:first');
        					footable.removeRow(row);
                    		jQuery(this).remove();
                    	});
                    }
                    else{
                    	container.closest('.ui-accordion-content').prev('.ui-accordion-header').remove();
                    	container.closest('.ui-accordion-content').remove();
                    }
                    
                }
                else
                   window.location.reload();
            }
        });
    });
	
	 jQuery(document).on('click','.delete-workout-log-exercise-sample',function(event){
        var _this = jQuery(this);
        var id = _this.data('id');
       	var container = jQuery(this).closest('.saved-wl-container');
       	var action = 'delete-workout-log-exercise-sample';
		var footable = jQuery(this).parents('table:first').data('footable');
        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action, id:id},
            success: function(data){
                if(!data.error){
                	if(_this.parents('table tbody').find('tr').length>1){
                    	_this.closest('tr').fadeOut(600, function() {
                    		var row = _this.parents('tr:first');
        					footable.removeRow(row);
                    		jQuery(this).remove();
                    	});
                    }else{
                    	container.closest('.ui-accordion-content').prev('.ui-accordion-header').remove();
                    	container.closest('.ui-accordion-content').remove();
                    }
                    
                }
               // else
                  // window.location.reload();
            }
        });
    });

    jQuery(document).on('click','.share-workout-log, .unshare-workout-log',function(event){
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');
        var logtype = (container.hasClass('advanced')) ? 'advanced' : 'normal';
        var action = (_this.hasClass('share-workout-log')) ? 'share' : 'unshare';
        var uniq_id = jQuery(this).closest('.saved-wl-container').data('uniqid');
        _this.addClass('loading');
        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action+'-workout-log', logtype: logtype, uniq_id: uniq_id},
            success: function(data) {
                if(!data.error){
                    _this.removeClass('loading');
                    if(action == 'share')
                        _this.removeClass('share-workout-log').addClass('unshare-workout-log').html('<i class="fa fa-share-alt"> Unshare for Trainers/GYMs</i>');   
                    else
                       _this.removeClass('unshare-workout-log').addClass('share-workout-log').html('<i class="fa fa-share-alt"> Share for Trainers/GYMs</i>');
                	
                	if(action == 'share')
                		 jQuery('.saved-wl-container-edit[data-uniqid="'+uniq_id+'"]').attr('data-shared',1);
                	else
                		 jQuery('.saved-wl-container-edit[data-uniqid="'+uniq_id+'"]').attr('data-shared',0);

                }
                else
                   window.location.reload();
            }
        }); 
    });


	jQuery(document).on('click', '.to-wall-workout-log', function(event){
		
		var _this = jQuery(this);
		var container = jQuery(this).closest('.saved-wl-container');
		var logtype = (container.hasClass('advanced')) ? 'advanced' : 'normal';
        var log_selector = container.find('.workout-log-share-container');
        var uniq_id = container.data('uniqid');
        var logtype = (container.hasClass('advanced')) ? 'advanced' : 'normal';
        
    	//hide actions cells
        if(jQuery('.footable',container).hasClass('default'))
        	log_selector.find('table thead th:last-child, table tbody tr td:last-child').hide();
        else{
        	var rows = jQuery('.footable tbody tr',log_selector);
	        	jQuery.each(rows,function(i,row){
	    		jQuery(row).trigger('footable_toggle_row');
	    		jQuery(row).next('tr').find('.footable-row-detail-row:last-child').hide();
	    	});
        }

        scrollPos = document.body.scrollTop;
        html2canvas(log_selector, { 
            background: '#E7FCFF',
            onrendered: function(canvas) {
                jQuery(window).scrollTo(0,scrollPos);
                _this.addClass('loading').attr('disabled','disabled');
                
               var imgData = canvas.toDataURL('image/jpeg');   

                jQuery.ajax({ 
                    type: "POST", 
                    url: mirrorMuscles.ajaxPath,
                    dataType: 'text',
                    data: {action: 'to-wall-workout-log', uniq_id : uniq_id, canvas : imgData, logtype: logtype},
                    success: function(data){
                    	_this.removeClass('loading').attr('disabled',false);
                       	window.location.reload();
                    }
                });
            }

        }); //End html2canvas
    });


	jQuery(document).on('click', '.print-workout-log', function(event){
        event.preventDefault();
        jQuery(this).closest('.saved-wl-container').printElement({pageTitle:'My Workout log'});
    });




	jQuery(document).on('click','.edit-wl-exercise:not(.under-edit)',function(event){
		jQuery('.popover').remove();
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');

        jQuery('.edit-wl-exercise-cancel',container).click();

        jQuery('.edit-wl-exercise.under-edit').removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
        
        _this.removeClass('fa-edit').addClass('fa-spin fa-spinner under-edit');
        
        var uniqid = container.data('uniqid');
        var id = _this.data('id');
        var day = _this.data('day') 
        var exercise_id = _this.data('exercise-id');
        var exercise_name = _this.prev('.hidden-exercise-name').text().trim();
        var repeats = _this.data('repeats').toString().split(',');
        var weights = _this.data('weights').toString().split(',');

        jQuery('.edit-wl-exercise-insert',container).addClass('disabled');
       	jQuery('.edit-wl-exercise-cancel, .edit-wl-exercise-update', container).removeClass('disabled').attr('data-id',id);
        
        jQuery('.exerciseweekdays .btn',container).removeClass('active');
        jQuery('.exerciseweekdays .btn[data-daynum="'+day+'"]',container).addClass('active');

        if(exercise_id == 0){
        	jQuery('.add-wl-exercisename-custom-radio',container).click();
        	jQuery('.exercisename',container).attr('disabled','disabled').trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled',false).val(exercise_name);
        }else{
        	jQuery('.add-wl-exercisename-custom-radio',container).attr('checked',false);
        	jQuery('.exercisename',container).attr('disabled',false).val(exercise_id).trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled','disabled').val('');
        }

        showExerciseRepeats(container,repeats.length);

        for(n=0; n<repeats.length; n++){
        	jQuery('.exerciserepeats:eq('+n+')',container).val(repeats[n]);
        	jQuery('.exerciseweight:eq('+n+')',container).val(weights[n]);
        }
        
    });
	
	jQuery(document).on('click','.edit-wl-exercise-sample:not(.under-edit)',function(event){
		jQuery('.popover').remove();
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');

        jQuery('.edit-wl-exercise-cancel-sample',container).click();

        jQuery('.edit-wl-exercise-sample.under-edit').removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
        
        _this.removeClass('fa-edit').addClass('fa-spin fa-spinner under-edit');
        
        var uniqid = container.data('uniqid');
        var id = _this.data('id');
        var day = _this.data('day') 
        var exercise_id = _this.data('exercise-id');
        var exercise_name = _this.prev('.hidden-exercise-name').text().trim();
        var repeats = _this.data('repeats').toString().split(',');
        var weights = _this.data('weights').toString().split(',');

        jQuery('.edit-wl-exercise-insert-sample',container).addClass('disabled');
       	jQuery('.edit-wl-exercise-cancel-sample, .edit-wl-exercise-update-sample', container).removeClass('disabled').attr('data-id',id);
        
        jQuery('.exerciseweekdays .btn',container).removeClass('active');
        jQuery('.exerciseweekdays .btn[data-daynum="'+day+'"]',container).addClass('active');

        if(exercise_id == 0){
        	jQuery('.add-wl-exercisename-custom-radio',container).click();
        	jQuery('.exercisename',container).attr('disabled','disabled').trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled',false).val(exercise_name);
        }else{
        	jQuery('.add-wl-exercisename-custom-radio',container).attr('checked',false);
        	jQuery('.exercisename',container).attr('disabled',false).val(exercise_id).trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled','disabled').val('');
        }

        showExerciseRepeats(container,repeats.length);

        for(n=0; n<repeats.length; n++){
        	jQuery('.exerciserepeats:eq('+n+')',container).val(repeats[n]);
        	jQuery('.exerciseweight:eq('+n+')',container).val(weights[n]);
        }
        
    });

    jQuery(document).on('click','.edit-awl-exercise:not(.under-edit)',function(event){
        var _this = jQuery(this);
        var container = jQuery(this).closest('.saved-wl-container');

        jQuery('.edit-wl-exercise-cancel',container).click();

        jQuery('.edit-awl-exercise.under-edit').removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
        
        _this.removeClass('fa-edit').addClass('fa-spin fa-spinner under-edit');

        var uniqid = container.data('uniqid');
        var id = _this.data('id');
        var week = _this.data('week');
        var exercise_order = _this.data('exercise-order').toString();
        var exercise_id = _this.data('exercise-id');
        var exercise_name = _this.prev('.hidden-exercise-name').text().trim();
        var tempo = _this.data('tempo');
        var repeats = _this.data('repeats').toString().split(',');
        var loads = _this.data('loads').toString().split(',');
        var rest = _this.data('rest').toString().split(',');

        jQuery('.edit-wl-exercise-insert',container).addClass('disabled');
       	jQuery('.edit-wl-exercise-cancel, .edit-wl-exercise-update', container).removeClass('disabled').attr('data-id',id);
        
        jQuery('.exerciseweeknums .btn',container).removeClass('active');
        jQuery('.exerciseweeknums .btn[data-week="'+week+'"]',container).addClass('active');

        if(exercise_id == 0){
        	jQuery('.add-wl-exercisename-custom-radio',container).click();
        	jQuery('.exercisename',container).attr('disabled','disabled').trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled',false).val(exercise_name);
        }else{
        	jQuery('.add-wl-exercisename-custom-radio',container).attr('checked',false);
        	jQuery('.exercisename',container).attr('disabled',false).val(exercise_id).trigger('chosen:updated');
        	jQuery('.exercisename-custom',container).attr('disabled','disabled').val('');
        }

        jQuery('.exerciseorder',container).val(exercise_order).trigger('chosen:updated');
        jQuery('.exercisetempo',container).val(tempo);

        showExerciseRepeatsLoad(container,repeats.length);

        for(n=0; n<repeats.length; n++){
        	jQuery('.exerciserepeats:eq('+n+')',container).val(repeats[n]);
        	jQuery('.exerciseload:eq('+n+')',container).val(loads[n]);
        	jQuery('.exerciserest:eq('+n+')',container).val(rest[n]);
        }
        
    });


    jQuery(document).on('click','.edit-wl-exercise-cancel',function(event){
        var container = jQuery(this).closest('.saved-wl-container');
        if(container.hasClass('advanced')){
        	jQuery('.edit-awl-exercise.under-edit',container).removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
        	jQuery('.exerciseweeknums .btn',container).removeClass('active');
        	jQuery('.exerciseweeknums .btn[data-week="1"]',container).addClass('active');
        	jQuery('.exerciseorder',container).val('A1').trigger('chosen:updated');
        	jQuery('.exercisetempo').val(1);
        	showExerciseRepeatsLoad(container,1);
        }else{
        	jQuery('.edit-wl-exercise.under-edit',container).removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
        	jQuery('.exerciseweekdays .btn',container).removeClass('active');
        	jQuery('.exerciseweekdays .btn[data-daynum="1"]',container).addClass('active');
        	showExerciseRepeats(container,1);
        }
	    
        jQuery('.edit-wl-exercise-insert',container).removeClass('disabled');
       	jQuery('.edit-wl-exercise-cancel, .edit-wl-exercise-update',container).addClass('disabled');
        jQuery('.add-wl-exercisename-custom-radio',container).attr('checked',false);
        jQuery('.exercisename',container).attr('disabled',false).val(0).trigger('chosen:updated');
        jQuery('.exercisename-custom',container).val('').attr('disabled',true);
        jQuery('.exercise-details-container',container).hide();
        jQuery('.exercisesets',container).val('1').trigger('chosen:updated');
        
    });
	
	jQuery(document).on('click','.edit-wl-exercise-cancel-sample',function(event){
																		   
        var container = jQuery(this).closest('.saved-wl-container');
		jQuery('.edit-wl-exercise-sample.under-edit',container).removeClass('fa-spin fa-spinner under-edit').addClass('fa-edit');
		jQuery('.exerciseweekdays .btn',container).removeClass('active');
		jQuery('.exerciseweekdays .btn[data-daynum="1"]',container).addClass('active');
		showExerciseRepeats(container,1);
	    
        jQuery('.edit-wl-exercise-insert-sample',container).removeClass('disabled');
       	jQuery('.edit-wl-exercise-cancel-sample, .edit-wl-exercise-update-sample',container).addClass('disabled');
        jQuery('.add-wl-exercisename-custom-radio',container).attr('checked',false);
        jQuery('.exercisename',container).attr('disabled',false).val(0).trigger('chosen:updated');
        jQuery('.exercisename-custom',container).val('').attr('disabled',true);
        jQuery('.exercise-details-container',container).hide();
        jQuery('.exercisesets',container).val('1').trigger('chosen:updated');
        
    });


    jQuery(document).on('click','.edit-wl-exercise-insert:not(.disabled), .edit-wl-exercise-update:not(.disabled)',function(event){
        var _this = jQuery(this);
        var uniqid = jQuery(this).data('uniqid');

        if(_this.hasClass('edit-wl-exercise-update'))
        	var id = jQuery(this).attr('data-id');
        else
        	var id = '';

        if(jQuery('.edit-wl-form[data-uniqid="'+uniqid+'"]').valid()){
        	_this.addClass('loading disabled');

        	var container = jQuery(this).closest('.saved-wl-container');
        	var logtype = (container.hasClass('advanced')) ? 'advanced' : 'normal';

        	var repeats = []
	    	jQuery('.exerciserepeats',container).each(function(i,e){
	    		repeats.push(parseInt(jQuery(this).val()));
	    	});

        	
        	if(jQuery('.add-wl-exercisename-custom-radio',container).is(":checked")){
    			var exercise_id = 0;
    			var exercise_name = jQuery('.exercisename-custom',container).val();
    		}
    		else{
    			var exercise_id = jQuery('.exercisename option:selected',container).val();
    			var exercise_name = jQuery('.exercisename option:selected',container).text();
    		}

        	if(_this.hasClass('edit-wl-exercise-update'))
        		action = 'update-workout-exercise';
        	else
        		action = 'insert-workout-exercise';


        	if(logtype=='advanced'){
        		var week = jQuery('.exerciseweeknums .btn.active',container).data('week');
        		var exercise_order = jQuery('.exerciseorder option:selected',container).val();
        		var tempo = jQuery('.exercisetempo',container).val();

				var loads = []
			    jQuery('.exerciseload',container).each(function(i,e){
		    		loads.push(parseInt(jQuery(this).val()));
		    	});
		    	
		    	var rest = []
			    jQuery('.exerciserest',container).each(function(i,e){
		    		rest.push(parseInt(jQuery(this).val()));
		    	});

		    	data = {action: action, logtype: logtype, id: id, uniq_id: uniqid, week: week, repeats: repeats, loads: loads, rest: rest, tempo:tempo, exercise_order: exercise_order, exercise_id: exercise_id, 'exercise_name': exercise_name};

        	}else{
        		var daynum = jQuery('.exerciseweekdays .btn.active',container).data('daynum');
				var dayname = jQuery('.exerciseweekdays .btn.active',container).data('dayname');

				var weights = []
			    jQuery('.exerciseweight',container).each(function(i,e){
		    		weights.push(parseInt(jQuery(this).val()));
		    	});

		    	var	repeats_td = [];
			    jQuery.each(repeats,function(i,e){
			    	var str = e;
			    	var weight = (weights[i]) ? '('+weights[i]+')' : '(0)';
			    	str += weight;
			    	repeats_td.push(str);
			    });
        		data = {action: action, logtype: logtype, id: id, uniq_id: uniqid, day: daynum, repeats: repeats, weights: weights, exercise_id: exercise_id, 'exercise_name': exercise_name};
        	}


        	jQuery.ajax({
	            type: "POST",
	            dataType: 'JSON',
	            url: mirrorMuscles.ajaxPath,
	            data: data,
	            success: function(data) {
	            	if(data.success){
	            		
	            		_this.removeClass('loading disabled');
	            		
	            		if(logtype=='advanced')
	            			var tr = '<tr data-id="'+data.success+'">\
	                   			<td>'+exercise_order+'</td><td>'+week+'</td><td>'+exercise_name+'</td>\
					    		<td>'+tempo+'</td><td>'+repeats.join('<br>')+'</td><td>'+loads.join('<br>')+'</td><td>'+rest.join('<br>')+'</td>\
					    		<td>\
					    			<span class="hidden-exercise-name">'+exercise_name+'</span>\
					    			<i class="fa fa-lg fa-edit edit-wl-exercise" data-id="'+data.success+'" data-week="'+daynum+'" data-exercise-id="'+exercise_order+'" data-exercise-id="'+exercise_id+'" data-tempo="'+tempo+'" data-repeats="'+repeats.join(',')+'" data-repeats="'+loads.join(',')+'" data-weights="'+rest.join(',')+'"></i>\
					    			&nbsp;\
					    			<i class="fa fa-lg fa-trash delete-workout-log-exercise" data-id="'+data.success+'"></i>\
					    		</td>\
					    		</tr>';
					    else
					    	var tr = '<tr data-id="'+data.success+'">\
	                   			<td>'+dayname+'</td><td>'+exercise_name+'</td>\
					    		<td>'+repeats_td.join(' - ')+'</td>\
					    		<td>\
					    			<span class="hidden-exercise-name">'+exercise_name+'</span>\
					    			<i class="fa fa-lg fa-edit edit-wl-exercise" data-id="'+data.success+'" data-day="'+daynum+'" data-exercise-id="'+exercise_id+'" data-repeats="'+repeats.join(',')+'" data-weights="'+weights.join(',')+'"></i>\
					    			&nbsp;\
					    			<i class="fa fa-lg fa-trash delete-workout-log-exercise" data-id="'+data.success+'"></i>\
					    		</td>\
					    		</tr>';
	                   	
	                   	if(_this.hasClass('edit-wl-exercise-update'))
	                   		jQuery('.workout-table-exercises tbody tr[data-id="'+data.success+'"]',container).replaceWith(tr);
	                   	else
	                   		jQuery('.workout-table-exercises tbody',container).append(tr);

	                   	jQuery('.edit-wl-exercise-cancel',container).click();
	                   	
	                   	jQuery('html, body').animate({
					        scrollTop: jQuery(container).offset().top
				    	}, 600);
	                   //
	            	}
	            	else{
            			window.location.reload();
	            	}

	            	jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');
            	}	
        	}); 
    	}
    });



	jQuery(document).on('click','.edit-wl-exercise-insert-sample:not(.disabled), .edit-wl-exercise-update-sample:not(.disabled)',function(event){
        var _this = jQuery(this);
        var uniqid = jQuery(this).data('uniqid');

        if(_this.hasClass('edit-wl-exercise-update-sample'))
        	var id = jQuery(this).attr('data-id');
        else
        	var id = '';

        if(jQuery('.edit-wl-form[data-uniqid="'+uniqid+'"]').valid()){
        	_this.addClass('loading disabled');

        	var container = jQuery(this).closest('.saved-wl-container');

        	var repeats = []
	    	jQuery('.exerciserepeats',container).each(function(i,e){
	    		repeats.push(parseInt(jQuery(this).val()));
	    	});

        	
        	if(jQuery('.add-wl-exercisename-custom-radio',container).is(":checked")){
    			var exercise_id = 0;
    			var exercise_name = jQuery('.exercisename-custom',container).val();
    		}
    		else{
    			var exercise_id = jQuery('.exercisename option:selected',container).val();
    			var exercise_name = jQuery('.exercisename option:selected',container).text();
    		}

        	if(_this.hasClass('edit-wl-exercise-update-sample'))
        		action = 'update-workout-exercise-sample';
        	else
        		action = 'insert-workout-exercise-sample';

			var daynum = jQuery('.exerciseweekdays .btn.active',container).data('daynum');
			var dayname = jQuery('.exerciseweekdays .btn.active',container).data('dayname');

			var weights = []
			jQuery('.exerciseweight',container).each(function(i,e){
				weights.push(parseInt(jQuery(this).val()));
			});

			var	repeats_td = [];
			jQuery.each(repeats,function(i,e){
				var str = e;
				var weight = (weights[i]) ? '('+weights[i]+')' : '(0)';
				str += weight;
				repeats_td.push(str);
			});
			data = {action: action, id: id, uniq_id: uniqid, day: daynum, repeats: repeats, weights: weights, exercise_id: exercise_id, 'exercise_name': exercise_name};


        	jQuery.ajax({
	            type: "POST",
	            dataType: 'JSON',
	            url: mirrorMuscles.ajaxPath,
	            data: data,
	            success: function(data) {
	            	if(data.success){
	            		
	            		_this.removeClass('loading disabled');
	            		
						var tr = '<tr data-id="'+data.success+'">\
							<td>'+dayname+'</td><td>'+exercise_name+'</td>\
							<td>'+repeats_td.join(' - ')+'</td>\
							<td>\
								<span class="hidden-exercise-name">'+exercise_name+'</span>\
								<i class="fa fa-lg fa-edit edit-wl-exercise-sample" data-id="'+data.success+'" data-day="'+daynum+'" data-exercise-id="'+exercise_id+'" data-repeats="'+repeats.join(',')+'" data-weights="'+weights.join(',')+'"></i>\
								&nbsp;\
								<i class="fa fa-lg fa-trash delete-workout-log-exercise-sample" data-id="'+data.success+'"></i>\
							</td>\
							</tr>';
	                   	
	                   	if(_this.hasClass('edit-wl-exercise-update-sample'))
	                   		jQuery('.workout-table-exercises tbody tr[data-id="'+data.success+'"]',container).replaceWith(tr);
	                   	else
	                   		jQuery('.workout-table-exercises tbody',container).append(tr);

	                   	jQuery('.edit-wl-exercise-cancel-sample',container).click();
	                   	
	                   	jQuery('html, body').animate({
					        scrollTop: jQuery(container).offset().top
				    	}, 600);
	                   //
	            	}
	            	else{
            			window.location.reload();
	            	}

	            	jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');
            	}	
        	}); 
    	}
    });





	jQuery(document).on('hover','.muscle-picker',function(){
		var muscle_id  = jQuery(this).data('muscle');
		if(jQuery(this).hasClass('front')){
			jQuery('.muscle-picker.front').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.muscle-front-background').removeClass('muscle-1 muscle-2 muscle-3 muscle-4 muscle-6 muscle-10 muscle-13 muscle-14');
			jQuery('.muscle-front-background').addClass('muscle-'+muscle_id);
		}else if(jQuery(this).hasClass('back')){
			jQuery('.muscle-picker.back').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.muscle-back-background').removeClass('muscle-5 muscle-7 muscle-8 muscle-9 muscle-11 muscle-12 muscle-15');
			jQuery('.muscle-back-background').addClass('muscle-'+muscle_id);
		}
		
	});

	jQuery(document).on('mouseleave','.muscle-pickers',function(){
		var muscle_id  = jQuery(this).data('muscle');
		if(jQuery(this).hasClass('front')){
			jQuery('.muscle-front-background').removeClass('muscle-1 muscle-2 muscle-3 muscle-4 muscle-6 muscle-10 muscle-13 muscle-14');
			jQuery('.muscle-picker.front').removeClass('active');
		}else if(jQuery(this).hasClass('back')){
			jQuery('.muscle-back-background').removeClass('muscle-5 muscle-7 muscle-8 muscle-9 muscle-11 muscle-12 muscle-15');
			jQuery('.muscle-picker.back').removeClass('active');
		}
		
	});




});