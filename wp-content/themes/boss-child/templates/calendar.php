<?php

/*

Template Name: Calendars

*/



get_header();?>



<?php

    if(bp_get_member_type(get_current_user_id())=='pt' 

        && isset($_GET['action']) && $_GET['action'] == 'show_shared_schedule' 

        && isset($_GET['date']) && !empty($_GET['date'])

        && isset($_GET['student']) && !empty($_GET['student'])

        && isset($_GET['hash']) && !empty($_GET['hash'])

    )

    {

        $show_shared_schedule = false;

        

        $trainer_id = get_current_user_id();

        $hash = $_GET['hash'];

        $calendar_date  = $_GET['date'];

        $student_id = base64_decode(str_rot13(str_rot13($_GET['student'])));

        $hashed = md5(NONCE_SALT.$calendar_date.$student_id);

        

        $is_realy_trainer = user_is_connected($student_id);



        if($hash == $hashed && $is_realy_trainer){

            $today = get_today_calendar($student_id,$calendar_date);

            if( $today['shared'] == 1 )

                $show_shared_schedule = true;

        }else{

            wp_redirect(home_url());

            exit;

        }

        

    }



?>



<?php if($show_shared_schedule): ?>



<?php include_once('shared-calendar.php'); ?>



<?php else: ?>



<?php 

$userid = get_current_user_id();

$member_type = bp_get_member_type($userid);

?>

<?php if( $member_type == ('standard' || 'pt') ): ?>

<?php



        $members = ($member_type == 'standard') ? accepted_connection_requests('pt') : accepted_connection_requests('standard');

        $today = get_today_calendar($userid);

?>

<script>

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



function closeme(){

	jQuery('#msg').hide();

	jQuery('#msg .link-container').html('');

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

            

            jQuery('#msg .link-container').html(fullname);

			jQuery('#msg').show();



            if(person.length>1){

                

                if(person == 'other'){

                    jQuery('#msg .link-container').html('<div id="closeme" onclick="closeme();">Close</div><br><input type="text" id="other-name" placeholder="Enter Fullname"><br><input type="email" id="other-email" placeholder="Enter Email"><br /><br /><input type="button" class="cancel-send-invitation" value="Cancel" />&nbsp;&nbsp;<input type="button" class="accept-send-invitation" value="Send" />');



                            jQuery('.cancel-send-invitation').on('click', function(){

                                var dateText = jQuery('#calendar-date').val();

                                get_day_calendar(dateText,user_id);

                                jQuery('#msg').hide();

                            });



                            jQuery('.accept-send-invitation').on('click', function(){

                                var other_name = jQuery('input#other-name').val();

                                var other_email = jQuery('input#other-email').val();

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

                                            jQuery('#msg').hide();

                                        }

                                    });

                                }else{



                                    jQuery('#msg .link-container').append('<div id="buddypress"><div id="message" class="error"><p>Name, Email is empty or invalid.</p></div></div>');

                                    setTimeout(function(){

                                        jQuery('#msg .link-container #buddypress').remove();

                                    },2500)

                                }

                                



                            });

                

                }else{

							 jQuery('#msg .link-container').append('<div id="closeme" onclick="closeme();">Close</div><br /><br /><input type="button" class="cancel-send-invitation" value="Cancel" />&nbsp;&nbsp;<input type="button" class="accept-send-invitation" value="Send" />');



                            jQuery('.cancel-send-invitation').on('click', function(){

                                jQuery.ajax({

                                    type: "POST",

                                    url: mirrorMuscles.ajaxPath,

                                    data: {action: 'set-calendar-person', calendar_date: date, row: row, person: person},

                                    success: function(data) {

											jQuery('#msg').hide();

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

                                            jQuery('#msg').hide();

                                        }

                                        else{

                                            __this.removeClass('loading disabled').attr('disabled', false);

                                            var dateText = jQuery('#calendar-date').val();

                                            get_day_calendar(dateText,user_id);

                                            jQuery('#msg').hide();

											jQuery('#msg .link-container').html('<div id="closeme" onclick="closeme();">Close</div><div id="buddypress"><div id="message" class="info"><p>Sorry, '+data.busy+'.</p></div></div>');

											jQuery('#msg').show();

                                        }    



                                    }

                                });



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

						jQuery('#msg .link-container').html('<div id="closeme" onclick="closeme();">Close</div><div id="buddypress"><div id="message" class="error"><p>Sorry, '+data.error+'</p></div></div>');

						jQuery('#msg').show();

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

                            /*rows += '<tr class="week-persons">'

                                <td>'+time+'</td>\

                                <td>'+fill_week_table(data,'Monday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Tuesday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Wednesday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Thursday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Friday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Saturday', parseInt(i+1), 'person')+'</td>\

                                <td>'+fill_week_table(data,'Sunday', parseInt(i+1), 'person')+'</td>\

                            </tr>';

                        }

                        var week_table = '<div id="closeme" onclick="closeme();">Close</div><table id="week-table" class="week-table table toggle-default footable">\

                        <thead>\

                            <tr>\

                                <th>Time</th>\

                                <th data-hide="phone,tablet">Monday</th>\

                                <th data-hide="phone,tablet">Tuesday</th>\

                                <th data-hide="phone,tablet">Wednesday</th>\

                                <th data-hide="phone,tablet">Thursday</th>\

                                <th data-hide="phone,tablet">Friday</th>\

                                <th data-hide="phone,tablet">Saturday</th>\

                                <th data-hide="phone,tablet">Sunday</th>\

                            </tr>\

                        </thead>\

                        <tbody>'+rows+'</tbody>\

                        </table>'; */

						jQuery('#msg .link-container').html(week_table);

						jQuery('#msg').show();



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

    

   

</script>

<style>

.bg-back{

	background: none repeat scroll 0 0 rgba(0, 0, 0, 0.9);

	height: 100%;

	left: 0;

	position: fixed;

	top: 0;

	z-index: 90001;

	width: 100%;

	opacity:0.99;

	}

.link-container{

	background: none repeat scroll 0 0 #fff;

	border: 6px solid #3c474d;

	border-radius: 10px;

	box-shadow: 0 0 25px #3c474d;

	left: 18.5%;

	padding: 20px 20px;

	position: fixed;

	text-align: center;

	top: 20%;

	width: 80%;

	height:450px;

	overflow-y:scroll;

	z-index: 99999;

	}

	#closeme{

	text-align:right;

	color:#AA2D2A;

	font-size:20px;

	text-transform:uppercase;

	cursor:pointer;

	}

</style>

<div id="msg" class="bg-back" style="display:none;">

    <div class="link-container"></div>

</div>

<div class="template-calendar">

    <div class="site-content">

        <input type="hidden" id="current-user" value="<?php _e($userid);?>">

        <input type="hidden" id="member-type" value="<?php _e($member_type);?>">

        

            <h3 class="template-title"><?php echo ($member_type == 'standard') ? 'Training Schedule' : 'Trainer Calendar';?></h3>

        <div class="col-md-12 text-center">

            <?php if(is_user_logged_in() && wp_is_mobile()):?>

                <?php print_video_container();?>

            <?php endif;?>

        </div>

        <div class="col-md-12 text-right">

            <?php if($member_type == 'standard'): ?>

                <div class="btn-group inverse pull-right">

                    <button id="watch-week" class="btn inverse" type="button"><i class="fa fa-calendar"></i> Weekly schedule</button>

                    <button id="<?php echo ($today['shared'] == 1) ? 'unshare-calendar' : 'share-calendar' ?>" class="btn inverse sharing_button" type="button">

                        <i class="fa fa-share-alt"> <?php echo ($today['shared'] == 1)? 'Unshare fot Trainers' : 'Share for Trainers';?></i>

                    </button>

                </div>

            <?php elseif($member_type == 'pt'): ?>

                <button id="watch-week" class="btn inverse" type="button">Weekly schedule</button>

            <?php endif;?>

        </div>

        <div class="clear"></div>

        <form name="calendar-form" class="col-md-12"><!--for Firefox select boxes fix-->

            <table id="calendar-table" class="calendar-table table toggle-default footable">

                <thead>

                    <tr>

                        <th data-toggle="true" width="33%">

                            <input type="text" id="calendar-date" value="">

                            <?php if(isset($_GET['date']) && !empty($_GET['date'])):?>

                                <?php $training_schedule_date = base64_decode(str_rot13(str_rot13($_GET['date'])));?>

                            <?php endif;?>

                            <input type="hidden" id="training-schedule-date" value="<?php echo $training_schedule_date;?>">

                        </th>

                        <th data-hide="phone,tablet" width="33%">

                            <?php if($member_type == 'standard'): ?>

                                My Trainer

                            <?php elseif($member_type == 'pt'): ?>

                                Client

                            <?php endif;?>

                        <th data-hide="phone,tablet" width="33%">Workouts</th>

                    </tr>

                </thead>

                <tbody>

                <?php for($i=1; $i<=24; $i++):?>

                    <?php $time = ($i<12) ? ($i).' a.m.' : (($i==12) ? $time = '12 p.m.' :(($i==24) ? $time = '12 a.m.' :($i-12).' p.m.')); ?>

                        <tr data-row="<?php echo $i;?>">

                            <td class="training-time">

                                <?php echo $time;?>

                                <?php

                                    echo ($member_type == 'standard' && $today[$i]->status == 'pending') ? '<p class="pending-invitation"><small>Pending invitation</small><br><button data-row="'.$i.'" type="button" class="btn success accept-invitation">Accept</button><button data-row="'.$i.'" type="button" class="btn danger reject-invitation">Reject</button></p>' : '';

                                    echo ($member_type == 'pt' && $today[$i]->status == 'pending') ? '<p class="pending-invitation"><small>Pending invitation</small></p>' : '';

                                    echo ($today[$i]->status == 'accepted' && $today[$i]->person_id != -1) ? '<p class="accepted-invitation"><small>Accepted invitation</small><br><button data-row="'.$i.'" type="button" class="btn danger refuse-invitation">Refuse</button></p>' : '';

                                    echo ($today[$i]->status == 'accepted' && $today[$i]->person_id == -1) ? '<p class="accepted-invitation"><small>Accepted invitation</small></p>' : '';

                                    echo ($today[$i]->status == 'refusing') ? '<p class="refusing-training"><small>Pending refuse</small><br><button data-row="'.$i.'" type="button" class="btn success accept-refuse">Accept</button><button data-row="'.$i.'" type="button" class="btn danger reject-refuse">Reject</button></p>' : '';

                                    echo ($today[$i]->status == 'refusing-initiator') ? '<p class="refusing-training"><small>Pending refuse</small></p>' : '';

                                ?>

                            </td>

                            <td class="training-person">

                                <?php if($member_type == 'standard'): ?>

                                    <select data-row="<?php echo $i;?>" class="trainer-select" name="training-person" <?php echo ( $today[$i]->status == 'pending' || $today[$i]->status == 'accepted') ? 'disabled="disabled"' :'';?>>

                                        <option value="">-</option>

                                        <?php foreach($members as $key=>$trainer_id): ?>

                                            <option value="<?php echo $trainer_id;?>" <?php selected($today[$i]->person_id, $trainer_id, true);?>><span><?php echo get_fullname($trainer_id);?></span></option>

                                        <?php endforeach;?>

                                    </select>

                                <?php elseif($member_type == 'pt'): ?>

                                    <select data-row="<?php echo $i;?>" class="client-select" name="training-person" <?php echo ( $today[$i]->status == 'pending' || $today[$i]->status == 'accepted') ? 'disabled="disabled"' :'';?>>

                                        <?php if($today[$i]->person_id == -1): ?>

                                            <option value="">-</option>

                                            <option value="-1" selected="selected"><?php echo $today[$i]->other_name.'&nbsp;'.$today[$i]->other_email;?></option>

                                        <?php else: ?>

                                            <option value="">-</option>

                                            <option value="other">Other client</option>

                                            <?php foreach($members as $key=>$client_id): ?>

                                                <option value="<?php echo $client_id;?>" <?php selected($today[$i]->person_id, $client_id, true);?>><span><?php _e($student['firstname'].' '.$student['lastname'])?><?php echo get_fullname($client_id);?></span></option>

                                            <?php endforeach;?>

                                        <?php endif; ?>

                                    </select>

                                <?php endif;?>

                            </td>

                            <td class="training-workout">

                                <div class="training-workout-container">

                                    <input data-row="<?php echo $i;?>" type="text" class="training-workout-input" placeholder="-" value="<?php _e($today[$i]->workout)?>">

                                    <span class="workout-save"><i class="fa fa-lg fa-save"></i></span>

                                </div>

                            </td>

                        </tr>

                    <?php endfor;?>

                </tbody>

            </table>

            <div class="clear"></div>

            <div class="col-md-12 text-center">

                <button id="prev-day" class="btn" type="button">Prev. day</button>

                <button id="next-day" class="btn" type="button">Next day</button>

            </div>

        </form>

    </div>

</div>

<?php if($member_type == 'pt'): ?>

<div id="send-invitation" style="display:none;">

    <div class="col-md-12 text-center" style="padding:20px;">

        <h3 class="template-subtitle">Training Invitation</h3>

        <p class="invitation-text text-center">Send training invitation to <span class="invitation-client"></span></p>

        <button class="accept-send-invitation btn success">Yes</button>

        <button class="cancel-send-invitation btn danger">No</button>

    </div>

</div>

<?php endif;?>

<script type="text/javascript">

</script>

<?php endif;?><!--not shared template-->


<?php endif;?>


<?php get_footer(); ?>