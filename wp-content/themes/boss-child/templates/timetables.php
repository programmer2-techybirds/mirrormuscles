<?php

/*

Template Name: Timetables

*/

get_header();

?>

<?php

	if (isset($_GET) && !empty($_GET) && isset($_GET['show_gym_timetables']) ) {

        $member_id = $current_user->id;

        $gym_id = $_GET['gym_id'];

        $is_gym_member = user_is_connected($gym_id);

    }else{
    	$member_id = $current_user->ID;

        $gym_id = $current_user->id;

        $is_gym_member = false;
    }

	$member_type = bp_get_member_type($member_id);
	$timetables = get_timetables($gym_id,strtotime('midnight',time()));

?>

<?php if($member_type == ('standard'||'pt'||'gym')): ?>

<div id="buddypress">

	<div id="primary">

		<div class="template-gym-time-tables">

    		<div class="site-content">

        		<h3 class="template-title">Class Timetables</h3>

                <script>

				jQuery(document).ready(function(){

					jQuery( "#result-timetables-date2" ).datepicker({

						showOn: "both",

						buttonText: "<i class='fa fa-lg fa-calendar'></i>",

						dateFormat: 'MM d, yy',

						minDate: new Date(),
						
						onSelect: function(dateText, inst) {

							var gym_id = jQuery('#gym_id').val();

							get_date_timetables2(dateText,gym_id);

						}

					}).datepicker("setDate", new Date());

					jQuery( "#timetables-date2" ).datepicker({

						showOn: "both",

						buttonText: "<i class='fa fa-lg fa-calendar'></i>",

						dateFormat: 'MM d, yy',

						minDate: new Date(),

						onSelect: function(dateText, inst) {

							//var clean_uri = location.protocol + "//" + location.host + location.pathname;

							//window.history.replaceState({}, document.title, clean_uri);

						}

					}).datepicker("setDate", new Date());

				});
			

				function get_date_timetables2(dateText,gym_id){

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
									  
									  '<td class="classsize" data-size="'+e.classsize+'">'+e.classsize+'</td>'+

									  '<td class="spec">'+e.spec+'</td>'+

									  '<td class="trainer" data-trainer="'+e.trainer_id+'">'+e.trainer_name+'</td>'+

									  '<td class="duration" data-duration="'+e.duration+'">'+e.duration_+'</td>';

							  if(e.action)

								tbody += '<td class="action">'+e.action+'</td>';

							  tbody += '</tr>';

							});

				

							

						  }

						  jQuery('#mytbody').html(tbody);

						 // jQuery('.footable').trigger('footable_initialize').trigger('footable_redraw');

						}

					});

				}

				</script>

				<?php if( $member_type == 'gym' ): ?>

					<form class="form-horizontal" name="new-timetables" id="new-timetables" action="https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php" method="post" enctype="multipart/form-data">

		            	

		            	<h3 class="template-subtitle">Add Class Timetables</h3>

						<div class="col-md-6 col-xs-12">

			                <div class="form-group">

			                    <label for="timetables-classname" class="col-md-3 control-label">Class Name</label>

			                    <div class="col-md-9 input-group">

			                    	<input type="text" class="custom_class" name="timetables-classname" id="timetables-classname" placeholder="Class Name" required>

			               		</div>

			               	</div>

			               	<div id="error-timetables-classname" class="col-md-12"></div>



			               	<div class="form-group">

			                    <label for="timetables-classsize" class="col-md-3 control-label">Class Size</label>

			                    <div class="col-md-9 input-group">

			                    	<input type="number" min="1" class="custom_class" name="timetables-classsize" id="timetables-classsize" placeholder="Class Size" required>

			                	</div>

			               	</div>

							<div id="error-timetables-classsize" class="col-md-12"></div>

							

							<div class="form-group spec-container">

								<label for="timetables-spec" class="col-md-3 control-label">Specialization</label>

			                    <div class="col-md-9 input-group">

			                        <select name="timetables-spec" id="timetables-spec" class="custom_class" required>

										<?php foreach (bp_get_profile_field_data('field=12&user_id='.$gym_id) as $key => $value): ?>

											<option value="<?php echo $value;?>"><?php echo $value;?></option>

										<?php endforeach; ?>

										<option value="custom">Custom spec</option>

			                        </select>

			                    </div>

			                </div>

						  	<div class="form-group custom-spec-container">

						    	<label for="timetables-custom-spec" class="col-md-3 control-label">Custom Specialization</label>

						    	<div class="col-md-9 input-group">

						      		<input type="text" class="form-control" name="timetables-custom-spec" id="timetables-custom-spec" placeholder="Custom spec">

						      		<button type="button" class="btn danger cancel-custom-spec">Cancel</button>

						    	</div>

						  	</div>

							<div id="error-timetables-spec" class="col-md-12"></div>

							<div id="error-timetables-custom-spec" class="col-md-12"></div>



							<div class="form-group">

			                    <label for="timetables-trainer" class="col-md-3 control-label">Trainer</label>

			                    <div class="col-md-9 input-group">

			                        <select name="timetables-trainer" id="timetables-trainer" class="custom_class" required>

										<?php foreach (accepted_connection_requests('pt',$gym_id) as $key => $value): ?>

											<option value="<?php echo $value;?>"><?php echo get_fullname($value);?></option>

										<?php endforeach; ?>

			                        </select>

			                    </div>

			                </div>

			                <div id="error-timetables-trainer" class="col-md-12"></div>

			            </div>

			            <div class="col-md-6 col-xs-12">
                        
                        	<div class="form-group">

			                    <label for="day" class="col-md-3 control-label">Weekly / Daily</label>

			                    <div class="col-md-9 input-group">

			                        <select name="day[]" id="day" class="custom_class" multiple="multiple">
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                	</select>
                                        

			                    </div>

			                </div>   
                            
                            <div id="error-timetables-date" class="col-md-12"></div>	

			            	<div class="form-group">

			                    <label for="timetables-date" class="col-md-3 control-label">End Date</label>

			                    <div class="col-md-9 input-group">

			                        <input type="text" name="timetables-date" id="timetables-date2" class="custom_class" required readonly="readonly">

			                    </div>

			                </div>

			                <div id="error-timetables-date" class="col-md-12"></div>

							<div class="form-group">

			                    <label for="timetables-time" class="col-md-3 control-label">Time</label>

			                    <div class="col-md-9 input-group">

			                        <select name="timetables-time" id="timetables-time" class="custom_class" required>

										<?php for($hours=6; $hours<22; $hours++):?>

											<?php for($mins=0; $mins<60; $mins+=15): ?>

										    <?php $time = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT); ?>

										        <option value="<?php echo strtotime($time);?>"><?php echo $time;?></option>

										    <?php endfor;?>                   

										<?php endfor;?>

			                        </select>

			                    </div>

			                </div>

			                <div id="error-timetables-time" class="col-md-12"></div>



							<div class="form-group">

			                    <label for="timetables-time" class="col-md-3 control-label">Duration</label>

			                    <div class="col-md-9 input-group">

			                        <select name="timetables-duration" id="timetables-duration" class="custom_class" required>

										<?php for($hours=0; $hours<3; $hours++):?>

											<?php for($mins=0; $mins<60; $mins+=15): ?>

												<?php if( $hours == 0 && $mins == 0 ): ?>

												<?php else:?>

													<?php $time = str_pad($hours,1,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT); ?>

										        	<option value="<?php echo strtotime($time);?>"><?php echo $time;?></option>

										    	<?php endif;?>

										    <?php endfor;?>                   

										<?php endfor;?>

			                        </select>

			                    </div>

			                </div>

			                <div id="error-timetables-duration" class="col-md-12"></div>

		               	</div>

						<div class="col-md-12 text-center">

							<input type="hidden" name="save-timetables" value=""/>

							<button type="button" class="btn danger" id="clear-timetables-edit" style="display:none;">Cancel</button>

							<button type="submit" class="btn" id="save-timetables">Save</button>

			            </div>

			        </form>
					
					<div class="clearfix"></div>

			        <hr>

				<?php endif;?>

				<h3 class="template-subtitle timetables-subtitle">Timetables on <input type="text" id="result-timetables-date2" name="resultsdate" /></h3>

		        <input type="hidden" id="gym_id" value="<?php echo $gym_id; ?>">
                <input type="hidden" id="current_user" value="<?php echo $member_id; ?>">
                
		        <div class="clearfix"></div>

		        <div class="mm-search-container">

					<div class="search-wrap">

			        	<input id="search" type="text" placeholder="Filter by Specialization...">

			        	<button type="button" id="searchsubmit" disabled><i class="fa fa-search"></i></button>

		        	</div>

				</div>

				<div class="clearfix"></div> 
				<div class="booking-div">	
                	<div class="booking-loader">
                    	<div class="loader-body">
                        	<p>Processing.....</p>
                        </div>
                    </div>
		        	<table id="timetables-table" class="table footable" data-filter="#search" data-filter-text-only="true">

                    <thead>

	                    <tr>

	                        <th data-sort-ignore="true" data-filter-ignore="true">Date</th>

	                        <th data-type="numeric" data-filter-ignore="true">Time</th>

	                        <th data-hide="phone,tablet" data-filter-ignore="true">Class Name</th>
                            
                            <th data-hide="phone,tablet" data-filter-ignore="true">Spaces Available</th>

	                        <th data-hide="phone">Specialization</th>

	                        <th data-hide="phone, tablet" data-filter-ignore="true">Trainer</th>

	                        <th data-type="numeric" data-hide="phone, tablet" data-filter-ignore="true">Duration</th>

	                        <?php if( $member_type != 'pt' ):?>

	                        	<th data-sort-ignore="true" data-hide="phone, tablet" data-filter-ignore="true">

	                        		<?php echo ( $member_type == 'gym' ) ? 'Actions' : 'Book';?>

	                        	</th>

	                    	<?php endif;?>

	                    </tr>

                    </thead>

                    <tbody id="mytbody" class="list">

                    	<?php for( $i = 0; $i < count( $timetables ); $i++ ) : ?>

					        <tr data-id="<?php echo $timetables[$i]->id;?>">

					        		<td class="date" data-value="<?php echo $timetables[$i]->date;?>">

					                	<?php echo date('F d, Y',$timetables[$i]->date);?>

					                </td>

					                <td class="time" data-time="<?php echo $timetables[$i]->time;?>">

					                	<?php echo date('G:i',$timetables[$i]->time);?>

					                </td>

					                <td class="classname" data-size="<?php echo $timetables[$i]->classsize;?>"><?php echo stripslashes_deep($timetables[$i]->classname);?></td>
                                    
                                    <td class="classsize" data-size="<?php echo $timetables[$i]->classsize;?>"><?php echo stripslashes_deep($timetables[$i]->classsize);?></td>

					                <td class="spec"><?php echo stripslashes_deep($timetables[$i]->specialization);?></td>

					                <td class="trainer" data-trainer="<?php echo $timetables[$i]->trainer_id;?>"><?php echo get_fullname($timetables[$i]->trainer_id);?></td>

					        		<td class="duration" data-duration="<?php echo $timetables[$i]->duration;?>"><?php echo date('G:i',$timetables[$i]->duration);?></td>

					        		<?php if( $member_type != 'pt' ):?>
										<?php $claasname = $timetables[$i]->id; ?>
						        		<td class="action">

						        			<?php $button_class = ($timetables[$i]->classsize!=0) ? "":"disabled='disabled'";
											echo ( $member_type == 'gym' ) 
						        							? '<i data-id="'.$timetables[$i]->id.'" class="fa fa-trash fa-lg delete-timetables"></i>' 
		: ( (is_booked_table($member_id,$claasname)) ? 'You have Book this Gym Time' 
		
		: ( (user_is_connected($gym_id)) ? (($timetables[$i]->classsize == 0 && date('y-m-d',$timetables[$i]->date) < date('y-m-d') ) ? '<button data-id="'.$timetables[$i]->id.'" type="button" class="btn inverse book-taining" '.$button_class.'>Book</button>' : '<button data-id="'.$timetables[$i]->id.'" type="button" class="btn inverse book-taining" '.$button_class.'>Book</button> <span class="addtocalendar atc-style-blue">
		
        <var class="atc_event">
			<var class="atc_date_start">20'.date('y-m-d').' '.date('h:m:s').'</var>
            <var class="atc_date_end">20'.date('y-m-d',$timetables[$i]->date).' '.date('h',$timetables[$i]->time).':00:00</var>
            <var class="atc_timezone">Europe/London</var>
            <var class="atc_title">'.$timetables[$i]->classname.'</var>
            <var class="atc_description">You have Book this Gym Time for Gym Training.</var>
            <var class="atc_organizer">Mirrormuscles Gym</var>
            <var class="atc_organizer_email">info@mirrormuscles.com</var>
			<var class="atc_location">Tatooine</var>
        </var>
    </span>') : '<small>Please become a GYM member to book a training</small>' ) );?>

						        		</td>
								<?php endif; ?>
					        </tr>

						<?php endfor; ?>
                         
                    </tbody>

	            </table>
                </div>

        	</div>

        </div>

    </div>

</div>

<style>
.custom_class{
	width:50%;
}
#new-timetables .input-group #day{
	width: 202px;
	height: 60px;
}
@media (max-size:720px){
	.custom_class{
		width:100%;
	}
}

.booking-div {
	position: relative;
	display: block;	
}
.booking-loader {
	background: rgba(255, 255, 255, 0.9);
	display: none;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	position: absolute;
	z-index: 999999;
}
.loader-body {
	text-align:center;	
}
.loader-body p {
	color: #000;
	font-size: 26px;
	margin: 16% 0;
	font-weight: 600;
}
</style>

<script type="text/javascript">

	jQuery(document).ready(function(){
		
	/*	var myDate = new Date();
		var res = myDate.toTimeString();
		var result = res.split(" ");
		result[2] + ' ' + result[3] + ' ' + result[4];	*/
	
		jQuery(".inverse.book-taining").click(function(){
			jQuery(".booking-loader").css("display","block");
			var timetable = jQuery(this).data("id");
			var gymid = jQuery("#gym_id").val();
			var user = 	jQuery("#current_user").val();
			var data = {
					'action'	: 'custome_booking_table', 
					'timetable' : timetable, 
					'gymid' 	: gymid, 
					'user' 		: user,	 
				};
			jQuery.ajax({ 
					url: ajaxurl,
					type: "POST",
					data: data,
					success: function(val) {
						//console.log(val);
						location.reload();
					},
				}); 
		});
	});
	
	jQuery(".fa-lg.delete-timetables").click(function(){
		if (confirm('Are you sure you want to delete this?')) {
			var timetable_id = jQuery(this).data("id");
			var data =  {
							'action' : 'delete-custom-timetables', 
							'id' : timetable_id,
						};
			jQuery.ajax({ 
					url: "https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php",
					type: "POST",
					data: data,
					success: function(val) {
						//console.log(val);
						location.reload();
					},
			});
		}
	});

</script>

<script>
	jQuery(document).ready(function(){
    	var n = jQuery(".classsize").size();
   		//alert(n);
  })
</script>

<?php else: wp_redirect(home_url()); endif;?>

<?php get_footer(); ?>