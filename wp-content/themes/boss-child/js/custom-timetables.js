jQuery(document).ready(function(){

    jQuery('#timetables-spec').on('change',function(){
    	if(jQuery(this).val()=='custom'){
    		jQuery(this).removeAttr('required');
    		jQuery('#timetables-custom-spec').attr('required',true);
    		jQuery('.spec-container').slideUp();
    		jQuery('.custom-spec-container').slideDown();
    	}
    });

     jQuery('#timetables-table').footable({
       filter: {
            filterFunction: function (index) {
                var jQueryt = jQuery(this),
                    jQuerytable = jQueryt.parents('table:first'),
                    filter = jQuerytable.data('current-filter').toUpperCase(),
                    tableFilterTextOnly = jQuerytable.data('filter-text-only');

                var text;
                jQueryt.find('td.spec').each(function () {
                    var jQuerytd = jQuery(this);
                    var jQueryth = jQuerytable.find('th').eq(jQuerytd.index());

                    if (!jQueryth.data('filter-ignore')) {
                        text += jQuerytd.text();

                        if (!tableFilterTextOnly) {
                            if (!jQueryth.data('filter-text-only')) {
                                text += jQuerytd.data('value');
                            }
                        }
                    }
                });

                return text.toUpperCase().indexOf(filter) >= 0;
            }
        }
    });



    jQuery('.cancel-custom-spec').on('click',function(){
		jQuery('#timetables-custom-spec').removeAttr('required');
		jQuery('#timetables-spec').attr('required',true);
		jQuery('#timetables-spec option:first').attr('selected','selected');
		jQuery('.custom-spec-container').slideUp();
		jQuery('.spec-container').slideDown();
    });

    jQuery("#new-timetables").validate({
	  	errorPlacement: function(error, element){
				var name = element.attr("name");
        		jQuery('#error-' + name).append(error);
			},
		invalidHandler: function(form, validator) {
	        jQuery('#save-timetables').removeClass('loading').prop('disabled',false);
    	}

	});

	jQuery('#new-timetables').submit(function() {
		if(jQuery(this).valid()){
  			jQuery('#save-timetables').addClass('loading').prop('disabled',true);
  			return true;
  		}
	});


	jQuery(document).on('click','.delete-timetables',function(event){
        event.preventDefault();
        var _this = jQuery(this);
        _this.removeClass('fa-trash delete-timetables').addClass('fa-spin fa-spinner');
        var id = jQuery(this).data('id');
        var footable = _this.parents('table:first').data('footable');
        jQuery.ajax({
            type: "POST",
            url: mirrorMuscles.ajaxPath,
            data: {action: 'delete-timetables', id:id},
            success: function(data){
      				_this.closest('tr').fadeOut(600,function(){
      					var row = _this.parents('tr:first');
      					footable.removeRow(row);
      				});
      			}
        });
    });


    jQuery(document).on('click','.edit-timetables',function(){
    	
    	jQuery('#new-timetables')[0].reset();
        jQuery('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-pencil');
        var id = jQuery(this).data('id');
        _this = jQuery(this);
        _this.removeClass('fa-pencil').addClass('fa-spinner fa-spin');
        var date = _this.closest('tr').find('td.date').text().trim();
        var time = _this.closest('tr').find('td.time').data('time');
        var classname = _this.closest('tr').find('td.classname').text().trim();
        var classsize = _this.closest('tr').find('td.classname').data('size');
        var spec = _this.closest('tr').find('td.spec').text().trim();
        var trainer = _this.closest('tr').find('td.trainer').data('trainer');
        var duration = _this.closest('tr').find('td.duration').data('duration');

        jQuery('#timetables-classname').val(classname);
        jQuery('#timetables-classsize').val(classsize);
       	jQuery('#timetables-trainer').val(trainer);
       	jQuery('#timetables-date').val(date);
       	jQuery('#timetables-time').val(time);
       	jQuery('#timetables-duration').val(duration);

       	if(jQuery('#timetables-spec option[value="'+spec+'"]').length < 1){
       	
       		jQuery('#timetables-custom-spec').val(spec);
       		jQuery('#timetables-custom-spec').attr('required',true);
       		jQuery('#timetables-spec').removeAttr('required');
    		jQuery('.spec-container').slideUp();
    		jQuery('.custom-spec-container').slideDown();
       	
       	}else{

       		jQuery('#timetables-spec').val(spec);
       		jQuery('#timetables-custom-spec').removeAttr('required');
			jQuery('#timetables-spec').attr('required',true);
			jQuery('#timetables-spec option:first').attr('selected','selected');
			jQuery('.custom-spec-container').slideUp();
			jQuery('.spec-container').slideDown();
       	}


        jQuery("[name='save-timetables']").val(id);
        jQuery('#clear-timetables-edit').show();
        jQuery('html, body').animate({
	        scrollTop: jQuery('#new-timetables').position().top
	    }, 1000);
        
    });

    jQuery('#clear-timetables-edit').click(function(){
        jQuery('#new-timetables')[0].reset();
        jQuery("[name='save-timetables']").val('');
        jQuery('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-pencil');
        jQuery(this).hide();
  	});


	function get_date_timetables(dateText,gym_id){
    jQuery.ajax({
        type: "POST",
        dataType: 'JSON',
        url: mirrorMuscles.ajaxPath,
        data: {action: 'get-timetables', gym_id:gym_id, date: dateText},
        success: function(callback){
          var tbody = '';
          
          if(!callback.error){
            
            jQuery.each(callback.success,function(i,e){
              
              tbody += '<tr data-id="'+e.id+'">'+
                      '<td class="date" data-value="'+e.date+'">'+e.date_+'</td>'+
                      '<td class="time" data-time="'+e.time+'">'+e.time_+'</td>'+
                      '<td class="classname" data-size="'+e.classsize+'">'+e.classname+'</td>'+
                      '<td class="spec">'+e.spec+'</td>'+
                      '<td class="trainer" data-trainer="'+e.trainer_id+'">'+e.trainer_name+'</td>'+
                      '<td class="duration" data-duration="'+e.duration+'">'+e.duration_+'</td>';
              if(e.action)
                tbody += '<td class="action">'+e.action+'</td>';
              tbody += '</tr>';
            });

            
          }
          jQuery('#timetables_table tbody').html(tbody);
          jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');
        }
    });
	}


});