var get_day_calendar = function(date,userid){

    

    var member_type = jQuery('#member-type').val();

    jQuery('.training-workout-input, .trainer-select, .client-select').val('').prop('disabled',false);

    jQuery('p.pending-invitation, p.accepted-invitation, p.refusing-training').hide().remove();



    jQuery.ajax({

        type: "POST",

        dataType: "JSON",

        url: mirrorMuscles.ajaxPath,

        data: {action: 'get-day-calendar', user_id: userid, calendar_date: date},

        success: function(callback) {



                if(!callback.error){

                    

                    if(callback.success['shared'] == 0){

                        jQuery('.sharing-button').attr('id','share-calendar').html('<i class="fa fa-share-alt"> Share for Trainers</i>');

                    }else if(callback.success['shared']==1){

                        jQuery('.sharing-button').attr('id','unshare-calendar').html('<i class="fa fa-share-alt"> Unshare for Trainers</i>');

                    }



                    jQuery.each(callback.success['day'],function(i,e){

                        jQuery('input.training-workout-input[data-row="'+i+'"]').val(e.workout).attr('data-row',i);

                        if(member_type == 'standard')

                            jQuery('select.trainer-select[data-row="'+i+'"]').val(e.person_id).attr('data-row',i);

                        else

                            jQuery('select.client-select[data-row="'+i+'"]').val(e.person_id).attr('data-row',i);



                        if(e.status == 'pending'){

                            if( member_type == 'standard' )

                                jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="pending-invitation"><small>Pending invitation</small><br><button data-row="'+i+'" type="button" class="btn success accept-invitation">Accept</button><button data-row="'+i+'" type="button" class="btn danger reject-invitation">Reject</button></p>');

                            else

                                jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="pending-invitation"><small>Pending invitation</small></p>');

                            

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select').prop("disabled",true);

                        }

                        if(e.status == 'accepted' && e.person_id != -1 ){

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="accepted-invitation"><small>Accepted invitation</small><br><button data-row="'+i+'" type="button" class="btn danger refuse-invitation">Refuse</button></p>');

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select').prop("disabled",true);

                        }else if( e.status == 'accepted' && e.person_id == -1 ){

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="accepted-invitation"><small>Accepted invitation</small></p>');

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select').prop("disabled",true);

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select option:last').attr('selected','selected');

                        }

                        if(e.status == 'refusing'){

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="refusing-training"><small>Pending refuse</small><br><button data-row="'+i+'" type="button" class="btn success accept-refuse">Accept</button><button data-row="'+i+'" type="button" class="btn danger reject-refuse">Reject</button></p>');

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select').prop("disabled",true);

                        }

                        if(e.status == 'refusing-initiator'){

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-time').append('<p class="refusing-training"><small>Pending refuse</small></p>');

                            jQuery('.calendar-table tr[data-row="'+i+'"] td.training-person select').prop("disabled",true);

                        }

                    }); 

                }



                var now = jQuery.datepicker.formatDate('yy-mm-dd', new Date());

                if(date<now){

                    jQuery('.training-workout-input').prop('disabled',true);

                    jQuery('.trainer-select, .client-select').each(function(){

                        jQuery(this).prop('disabled',true);

                    });

                    jQuery('.pending-invitation button, .accepted-invitation button, .refusing-training button').remove();               

                }



            }

        });



}





function validateEmail(sEmail) {

    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)jQuery/;

    if (filter.test(sEmail)){

        return true;

    }

    else{

        return false;

    }

}

jQuery(document).ready(function(){

        jQuery( "#calendar-date" ).datepicker({

            showOn: "both",

            buttonText: "<i class='fa fa-lg fa-calendar'></i>",

            dateFormat: 'yy-mm-dd',

            onSelect: function(dateText, inst) {

                var userid = jQuery('#current-user').val();

                get_day_calendar(dateText,userid);

                var clean_uri = location.protocol + "//" + location.host + location.pathname;

                window.history.replaceState({}, document.title, clean_uri);

            }

        }).datepicker("setDate", new Date());



        /*/if this is shared

        if(jQuery('#training-schedule-date').val().length == 10){

            var userid = jQuery('#current-user').val();

            training_schedule_date = jQuery('#training-schedule-date').val();

            get_day_calendar(training_schedule_date,userid);

            jQuery( "#calendar-date" ).val(training_schedule_date);

        }*/



        jQuery('#next-day, #prev-day').on("click", function () {

            var date = jQuery('#calendar-date').datepicker('getDate');

            

            if( jQuery(this).attr('id') == 'next-day' )

                date.setTime(date.getTime() + (1000*60*60*24))

            else

                date.setTime(date.getTime() - (1000*60*60*24))



            jQuery('#calendar-date').datepicker("setDate", date);

            jQuery('.ui-datepicker-current-day').click();

            jQuery(window).scrollTop(0);

            var clean_uri = location.protocol + "//" + location.host + location.pathname;

            window.history.replaceState({}, document.title, clean_uri);

        });


        jQuery(document).on('change','.trainer-select',function(event){

            var _this = jQuery(this);

            var date = jQuery('#calendar-date').val();

            var user_id = jQuery('#current-user').val();

            var row = _this.data('row');

            var person = jQuery("option:selected", this).val();


            jQuery.ajax({

                type: "POST",

                dataType: "JSON",

                url: mirrorMuscles.ajaxPath,

                data: {action: 'set-calendar-person', calendar_date: date, row: row, person: person},

                success: function(data) {

                    if(data.pending){

                        jQuery.fancybox({

                            'padding':  20,

                            'width':    320,

                            'height':   240,

                            'type':     'iframe',

                            'content':   '<div id="message" class="info"><p>Sorry, '+data.pending+'.</p></div>'

                        });

                        var dateText = jQuery('#calendar-date').val();

                        get_day_calendar(dateText,user_id); 

                        jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');   

                    }else{

                        var dateText = jQuery('#calendar-date').val();

                        get_day_calendar(dateText,user_id); 

                        jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');

                    }

                }

            });

        });


        jQuery(document).on('change','.client-select',function(){
			
			var _this = jQuery(this);

            var date = jQuery('#calendar-date').val();

            var user_id = jQuery('#current-user').val();

            var row = _this.data('row');

            var person = jQuery("option:selected", this).val();

            var fullname = jQuery("option:selected", this).text();

            jQuery('.invitation-client').html(fullname);

            if(person.length>1){

                if(person == 'other'){

                    jQuery('.invitation-client').html('<br><input type="text" id="other-name" placeholder="Enter Fullname"><br><input type="email" id="other-email" placeholder="Enter Email">');

                    jQuery.fancybox({

                        'padding':  0,

                        'width':    320,

                        'height':   610,

                        'type':     'iframe',

                        'modal': true, 

                        'content':   jQuery('div#send-invitation').html(),

                         afterShow: function() {



                            jQuery('.cancel-send-invitation').on('click', function(){

                                var dateText = jQuery('#calendar-date').val();

                                get_day_calendar(dateText,user_id);

                                jQuery.fancybox.close();

                            });



                            jQuery('.accept-send-invitation').on('click', function(){

                                var other_name = jQuery('.fancybox-wrap input#other-name').val();

                                var other_email = jQuery('.fancybox-wrap input#other-email').val();

                                if(other_name.length>0&&validateEmail(other_email)){

                                    __this = jQuery(this);

                                    __this.addClass('loading disabled').attr('disabled',true);

                                    jQuery.ajax({

                                        type: "POST",

                                        url: mirrorMuscles.ajaxPath,

                                        data: {action:'send_training-invitation_other', calendar_date: date, row: row, other_name: other_name, other_email: other_email},

                                        success: function(data){

                                            __this.removeClass('loading disabled').attr('disabled',false);

                                            var dateText = jQuery('#calendar-date').val();

                                            get_day_calendar(dateText,user_id);

                                            jQuery.fancybox.close();

                                        }

                                    });

                                }else{



                                    jQuery('.invitation-client').prepend('<div id="buddypress"><div id="message" class="error"><p>Name, Email is empty or invalid.</p></div></div>');

                                    setTimeout(function(){

                                        jQuery('.fancybox-wrap #buddypress').remove();

                                    },2500)
                                }
                            });
                        }
                    });

                }else{

                    jQuery.fancybox({

                        'padding':  0,

                        'width':    320,

                        'height':   610,

                        'type':     'iframe',

                        'modal': true, 

                        'content':   jQuery('div#send-invitation').html(),

                         afterShow: function() {

                            jQuery('.cancel-send-invitation').on('click', function(){

                                jQuery.ajax({

                                    type: "POST",

                                    url: mirrorMuscles.ajaxPath,

                                    data: {action: 'set-calendar-person', calendar_date: date, row: row, person: person},

                                    success: function(data) {

                                            jQuery.fancybox.close();

                                        }
                                });
                            });


                            jQuery('.accept-send-invitation').on('click', function(){

                                __this = jQuery(this);

                                __this.addClass('loading disabled').attr('disabled',true);

                                jQuery.ajax({

                                    type: "POST",

                                    dataType: "JSON",

                                    url: mirrorMuscles.ajaxPath,

                                    data: {action: 'send-training-invitation', calendar_date: date, row: row, person: person},

                                    success: function(data) {

                                        if(!data.busy){

                                            __this.removeClass('loading disabled').attr('disabled', false);

                                            var dateText = jQuery('#calendar-date').val();

                                            get_day_calendar(dateText,user_id);

                                            jQuery.fancybox.close();

                                        }

                                        else{

                                            __this.removeClass('loading disabled').attr('disabled', false);

                                            var dateText = jQuery('#calendar-date').val();

                                            get_day_calendar(dateText,user_id);

                                            jQuery.fancybox.close();

                                            jQuery.fancybox({

                                                'padding':  20,

                                                'width':    320,

                                                'height':   240,

                                                'type':     'iframe',

                                                'content':   '<div id="buddypress"><div id="message" class="info"><p>Sorry, '+data.busy+'.</p></div></div>'

                                            });
                                        }    
                                    }
                                });
                            });
                        }
                    });
                }             

            }else{

                jQuery.ajax({

                    type: "POST",

                    url: mirrorMuscles.ajaxPath,

                    data: {action:'set-calendar-person', calendar_date: date, row: row, person: person},

                    success: function(data) {
						
                    }
                });
            }
        });

        jQuery(document).on('click','.workout-save',function(){

            var now = jQuery.datepicker.formatDate('yy-mm-dd', new Date());

            var date = jQuery('#calendar-date').val();

            if(date<now){

                return false;          

            }

            var _this = jQuery(this);

            var date = jQuery('#calendar-date').val();

            var person = jQuery('#current-user').val();

            var row = _this.prev('input').data('row');

            var workout = jQuery(this).closest('div').find('input').val();

            if(jQuery.trim(workout).length >= 0){

                jQuery.ajax({

                    type: "POST",

                    url: mirrorMuscles.ajaxPath,

                    data: {action:'set-calendar-workout', calendar_date: date, row: row, workout: workout},

                    success: function(data) {

                        _this.prev('.workout_unedit').removeClass('workout_unedit').addClass('workout_edit').html('<i class="fa fa-lg fa-edit"></i>');

                    }

                });

            }else{

                _this.closest('div').find('input').val(''); 

                get_day_calendar(date,person); 

            }

        });







        jQuery(document).on('click','.accept-invitation, .reject-invitation, .refuse-invitation, .accept-refuse, .reject-refuse' ,function(){

            var _this = jQuery(this);

            var date = jQuery('#calendar-date').val();

            var person = jQuery('#current-user').val();

            var row = _this.data('row');



            if(jQuery(this).hasClass('accept-invitation'))

                var action = 'accept-training-invitation';

            if(jQuery(this).hasClass('reject-invitation'))

                var action = 'reject-training-invitation';

            if(jQuery(this).hasClass('refuse-invitation'))

                var action = 'refuse-training-invitation';

            if(jQuery(this).hasClass('accept-refuse'))

                var action = 'accept-training-refuse';

            if(jQuery(this).hasClass('reject-refuse'))

                var action = 'reject-training-refuse';



            jQuery.ajax({

                type: "POST",

                url: mirrorMuscles.ajaxPath,

                data: {action: action, calendar_date: date, row: row},

                success: function(data) {

                    var dateText = jQuery('#calendar-date').val();

                    get_day_calendar(dateText,person);

                }

            });



        });


        jQuery(document).on('click','#share-calendar, #unshare-calendar',function(event){

            var action = jQuery(this).attr('id');

            var date = jQuery('#calendar-date').val();

            var _this = jQuery(this);

            _this.addClass('loading disabled').attr('disabled',true);

            jQuery.ajax({

                type: "POST",

                dataType: "JSON",

                url: mirrorMuscles.ajaxPath,

                data: {action: action, calendar_date:date},

                success: function(data) {

                    _this.removeClass('loading disabled').attr('disabled',false);

                    if(data.success){

                        var html = ( action == 'share-calendar' ) ? '<i class="fa fa-share-alt"> Unshare for Trainers</i>' : '<i class="fa fa-share-alt"> Share for Trainers</i>';

                        _this.attr('id','unshare-calendar').html(html);    

                    }else{

                        jQuery.fancybox.close();

                        jQuery.fancybox({

                            'padding':  20,

                            'width':    320,

                            'height':   240,

                            'type':     'iframe',

                            'content':   '<div id="buddypress"><div id="message" class="error"><p>Sorry, '+data.error+'</p></div></div>'

                        });

                    }

                }

            }); 

        });


        jQuery(document).on('click', '#watch-week',function(){

            var date = jQuery('#calendar-date').val();

            jQuery.ajax({

                type: "POST",

                dataType: 'JSON',

                url: mirrorMuscles.ajaxPath,

                data: {action:'get-week-calendar', calendar_date: date},

                success: function(data) {

                        var rows = '';

                        for(i=0; i<16; i++){

                            if(i<6){

                                time = parseInt(6+i)+':00 a.m.';

                            }else if(i==6){

                                time = '12:00 p.m.';

                            }else{

                                time = parseInt(i-6)+':00 p.m.';

                            }

                            rows += '<tr class="week-persons">'

                                +'<td>Private Trainer on '+time+'</td>'

                                +'<td>'+fill_week_table(data,'Monday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Tuesday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Wednesday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Thursday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Friday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Saturday', parseInt(i+1), 'person')+'</td>'

                                +'<td>'+fill_week_table(data,'Sunday', parseInt(i+1), 'person')+'</td>'

                            +'</tr>'

                            +'<tr class="week-workouts">'

                                +'<td>Workout on '+time+'</td>'

                                +'<td>'+fill_week_table(data,'Monday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Tuesday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Wednesday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Thursday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Friday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Saturday', parseInt(i+1), 'workout')+'</td>'

                                +'<td>'+fill_week_table(data,'Sunday', parseInt(i+1), 'workout')+'</td>'

                           +'</tr>';

                        }

                        var week_table = '<table id="week-table" class="week-table table toggle-default footable">'

                        +'<thead>'

                            +'<tr>'

                                +'<th>Time</th>'

                                +'<th data-hide="phone,tablet">Monday</th>'

                                +'<th data-hide="phone,tablet">Tuesday</th>'

                                +'<th data-hide="phone,tablet">Wednesday</th>'

                                +'<th data-hide="phone,tablet">Thursday</th>'

                                +'<th data-hide="phone,tablet">Friday</th>'

                                +'<th data-hide="phone,tablet">Saturday</th>'

                                +'<th data-hide="phone,tablet">Sunday</th>'

                            +'</tr>'

                        +'</thead>'

                        +'<tbody>'+rows+'</tbody>'

                        +'</table>';

						jQuery('#login_for_review').html(week_table).modal('show');//now its working



                    }

                });

        });

        

        var fill_week_table = function(obj,day,time,field){

            if(obj.hasOwnProperty(day)){

                if(obj[day].hasOwnProperty(time)){

                     return obj[day][time][field]; 

                }

                else

                    return '-';

            }

            else

                return '-';

        }



        

});//document.ready ends

    

    