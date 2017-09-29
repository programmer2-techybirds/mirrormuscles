<?php
/*
Template Name: PAR-Q
*/
get_header();?>

<?php
	if(isset($_GET['nid']) && !empty($_GET['nid']))
		if( check_admin_referer( $current_user->ID ) );
			bp_notifications_mark_notification( $_GET['nid'], 0 );

	$userid = $current_user->ID;
	$type = bp_get_member_type($userid);
	$fullname = get_fullname($userid);
	$dob = bp_get_profile_field_data('field=5&user_id='.$userid);
	$address = bp_get_profile_field_data('field=10&user_id='.$userid);
	$mobile = bp_get_profile_field_data('field=11&user_id='.$userid);
	$user_info = get_userdata($userid);
	$email = $user_info->user_email;
	$parq_id = ( isset($_GET['parq_id']) && !empty($_GET['parq_id']) ) ? $_GET['parq_id'] : '';
	$parq = get_parq($parq_id);
	$answers = json_decode($parq->client_answers);

	$has_new_pending = get_pending_parq_from_trainer($parq->client_id,$parq->trainer_id);
?>

<?php
	if( $parq->client_id == $userid && $parq->status == 'complete' && $has_new_pending ) :
		wp_redirect('/parq/?parq_id=' . $has_new_pending->id);
?>

<?php  elseif( $parq->client_id == $userid && $parq->status == 'complete' && !$has_new_pending ): ?>
	<div class="template-parq">
	    <div class="site-content">
	    	<div id="buddypress" class="container print_container">
	    		<h2 class="parq_title text-center">Physical Activity Readiness Questionnaire (PAR-Q)</h2>
				<div id="message" class="info col-md-12"><p>Sorry, you allready filled tis PAR-Q.</p></div>
			</div>
		</div>
	</div>
<?php  elseif( $parq->client_id == $userid && $parq->status == 'pending' ): ?>
<div class="template-parq">
    <div class="site-content">
    	<div id="buddypress" class="container print_container">
    		
    		<h2 class="parq_title text-center">Physical Activity Readiness Questionnaire (PAR-Q)</h2>
			
			<p class="parq_notation">If you are between the ages of 15 and 69, the PAR-Q will tell you if you should check with your doctor before you
			significantly change your physical activity patterns. If you are over 69 years of age and are not used to being very
			active, check with your doctor. Common sense is your best guide when answering these questions. Please read
			carefully and answer each one honestly: check YES or NO.</p>
			<form action="https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php" method="POST" id="parq_form">
				<div class="parq_user_fields col-sm-12">
					<div class="parq_field col-md-6 col-xs-12">
						<label for="parq_name">Name: </label>
		    			<input type="text" name="parq_name" value="<?php echo $fullname;?>" required>
		    			<div id="error-parq_name"></div>
		    		</div> 
		    		<div class="parq_field col-md-6 col-xs-12">
		    			<label for="parq_dob">DOB: </label>
		    			<input type="text" name="parq_dob" value="<?php echo $dob;?>" required>
		    			<div id="error-parq_dob"></div>
		    		</div>
		    		<div class="parq_field col-md-6 col-xs-12">
		    			<label for="parq_address">Address: </label>
						<input type="text" name="parq_address" value="<?php echo $address;?>" required>
						<div id="error-parq_address"></div>
		    		</div>
		    		<div class="parq_field col-md-6 col-xs-12">
		    			<label for="parq_postcode">Postcode: </label>
		    			<input type="text" name="parq_postcode" value="" required>
		    			<div id="error-parq_postcode"></div>
		    		</div>
		    		<div class="parq_field col-md-6 col-xs-12">
		    			<label for="parq_email">Email: </label>
		    			<input type="email" name="parq_email" value="<?php echo $email;?>" required>
		    			<div id="error-parq_email"></div>
		    		</div>
		    		<div class="parq_field col-md-6 col-xs-12">
		    			<label for="parq_mobile">Mobile: </label>
		    			<input type="text" name="parq_mobile" id="parq-mobile" value="<?php echo $mobile;?>" required>
		    			<div id="error-parq_mobile"></div>
		    		</div>
		    	</div>

				<div class="clear"></div>
				<input type="hidden" name="parq_id" value="<?php echo $parq_id;?>">
				<input type="hidden"  name="parq_hash" value="<?php echo md5(NONCE_SALT.$parq->client_id.$parq->trainer_id);?>" >
				
				<div class="parq_question_container col-md-12">
					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								1. Has your doctor ever said you have a heart condition and that you should only do physical activity recommended by a doctor?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_1" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_1" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								2. Do you feel pain in your chest when you do physical activity?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_2" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_2" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								3. In the past month, have you had a chest pain when you were not doing physical activity?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_3" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_3" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								4. Do you lose you balance because of dizziness or do you ever lose conciousness?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_4" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_4" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								5. Do you have a bone or joint problem (for example, back, knee, or hip) that could be made worse by a change in your physical activity?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_5" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_5" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								6. Is your doctor currently prescribing medication for your blood pressure or heart condition?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_6" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_6" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9 parq_question">
							<span>
								7. Do you know of any other reason why you should not do physical activity?
							</span>
						</div>
						<div class="col-md-3 text-right">
							<div class="checkbox">
							    <label>
							    	<input class="parq_question" name="question_7" type="radio" value="yes"> Yes
							    </label>
							    <label>
							    	<input class="parq_question" name="question_7" type="radio" value="no"> No
							    </label>
						  	</div>
						</div>
						<div class="col-md-12 yes_for_7" style="display:none">
							<label>Please comment answer for #7:</label>
							<div id="error-question_7_other"></div>
							<textarea class="form-control" class="parq_question" name="question_7_other" rows="5"></textarea>
						</div>
					</div>

				</div><!--.parq_questions_container-->
				
				<div class="clear"></div>

				<div class="col-md-12 pad0">

					<div class="parq_agreement_container yes_for_answers_agreement"><!--<div class="parq_agreement_container yes_for_answers_agreement">-->
						<p>You should consult with your doctor to clarify that it is safe for you to become physically active at this current time and in your current state of health.</p>
						<div class="checkbox"><div id="error-agreement_yes"></div>
						    <label>
						    	<input class="parq_question" name="agreement_yes" type="checkbox" > I have read, understood and accurately completed this questionnaire. I confirm that I am voluntarily engaging in an acceptable level of exercise, and my participation involves a risk of injury.
						    	
						    </label>
						</div>
						<div class="checkbox"><div id="error-agreement_yes_2"></div>
						    <label>
						    	<input class="parq_question" name="agreement_yes_2" type="checkbox" > I have sought medical advice and my GP has agreed that I may exercise.
						    </label>
						</div>

						<div class="clearfix"></div>

						<div class="text-center">
							<button type="submit" class="bnt success" name="save_parq" value="1">Submit</button>
						</div>
						
					</div>

					<div class="parq_agreement_container no_for_answers_agreement"><!--<div class="parq_agreement_container no_for_answers_agreement">-->
						<p>It is reasonably safe for you to participate in physical activity, gradually building up from your current ability level. A fitness appraisal can help determine your ability levels.</p>
						
						<div class="checkbox"><div id="error-agreement_no"></div>
							    <label>
							    	<input class="parq_question" name="agreement_no" type="checkbox" > I have read, understood and accurately completed this questionnaire. I confirm that I am voluntarily engaging in an acceptable level of exercise, and my participation involves a risk of injury.
							    	
							    </label>
						</div>

						<div class="clearfix"></div>
						
						<div class="text-center">
							<button type="submit" class="bnt success" name="save_parq" value="1">Submit</button>
						</div>
					</div>

				</div>
				<div class="clear"></div>

				<div class="col-md-12">
					<small><strong>Note:</strong> This physical activity clearance is valid for a maximum of 12 months from the date it is completed and becomes invalid if your condition changes so that you would answer YES to any of the 7 questions.</small>
				</div>
				<div class="clear"></div><br>
			</form>
    	</div><!--.container-->
    </div>
</div>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.css" />
<script type="text/javascript">
	$(document).ready(function(){

		jQuery('[name="parq_address"]').geocomplete({componentRestrictions: {country: ''}});


		jQuery('[name="parq_dob"]').datepicker({
      		changeYear: true,
      		dateFormat: 'MM d, yy'
		});

		$("#parq-mobile").intlTelInput({
			initialCountry: "auto",
			geoIpLookup: function(callback) {
			  	$.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			    	var countryCode = (resp && resp.country) ? resp.country : "";
			    	callback(countryCode);
			  	});
			}
		});

		$.validator.addMethod("nowhitespace", function(value, element)
        { return $.trim(value) && value != ""; }, "No space please and don't leave it empty");

		$("#parq_form").validate({
			ignore: "",
		  	errorPlacement: function(error, element){
					var name = element.attr("name");
	        		$('#error-' + name).append(error);
				},
			invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top-100
                }, 600);
            }
		});


		$(document).on('change','[name="question_7"]',function(){

			if( $(this).val() == 'yes' )
				$('.yes_for_7').show();
			else
				$('.yes_for_7').hide();

		});


		$(document).on('change','.parq_question',function(){

			var checked = $('.parq_question:checked').length;
			if( checked == 7 ){

				var no_answers = 0;

				$('.parq_question:checked').each(function(i,e){
					
					if( $(e).val() == 'no' )
						no_answers++
				});

				if( no_answers < 7 ){
					$('.no_for_answers_agreement').hide();
					$('[name="agreement_no"]').attr('required',false);
					$('.yes_for_answers_agreement').show();
					$('[name="agreement_yes"]').attr('required',true);
					$('[name="agreement_yes_2"]').attr('required',true);
				}
				else{
					$('.yes_for_answers_agreement').hide();
					$('[name="agreement_yes"]').attr('required',false);	
					$('[name="agreement_yes_2"]').attr('required',false);	
					$('.no_for_answers_agreement').show();
					$('[name="agreement_no"]').attr('required',true);	
				}
			}
			
		});
	});
</script>
<?php elseif( $parq->trainer_id == $userid && $parq->status == 'complete' ): ?>
	<div class="template-parq">
	    <div class="site-content">
	    	<div id="buddypress" class="container print_container">
	    		<h2 class="parq_title text-center">Physical Activity Readiness Questionnaire (PAR-Q)</h2>
	    		<p class="text-right"><strong>Filled: <?php echo date('F d, Y',strtotime($parq->updated));?></strong></p>
					<div class="parq_user_fields col-sm-12">
						<div class="parq_field col-md-6 col-xs-12">
							<label for="parq_name">Name: </label>
			    			<input type="text" name="parq_name" value="<?php echo $parq->client_name;?>" readonly>
			    		</div> 
			    		<div class="parq_field col-md-6 col-xs-12">
			    			<label for="parq_dob">DOB: </label>
			    			<input type="text" name="parq_dob" value="<?php echo $parq->client_dob;?>" readonly>
			    		</div>
			    		<div class="parq_field col-md-6 col-xs-12">
			    			<label for="parq_address">Address: </label>
							<input type="text" name="parq_address" value="<?php echo $parq->client_address;?>" readonly>
			    		</div>
			    		<div class="parq_field col-md-6 col-xs-12">
			    			<label for="parq_postcode">Postcode: </label>
			    			<input type="text" name="parq_postcode" value="<?php echo $parq->client_postcode;?>" readonly>
			    		</div>
			    		<div class="parq_field col-md-6 col-xs-12">
			    			<label for="parq_email">Email: </label>
			    			<input type="email" name="parq_email" value="<?php echo $parq->client_email;?>" readonly>
			    		</div>
			    		<div class="parq_field col-md-6 col-xs-12">
			    			<label for="parq_mobile">Mobile: </label>
			    			<input type="text" name="parq_mobile" value="<?php echo $parq->client_mobile;?>" readonly>
			    		</div>
			    	</div>

					<div class="clear"></div>

					<div class="parq_question_container col-md-12">
						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									1. Has your doctor ever said you have a heart condition and that you should only do physical activity recommended by a doctor? - <strong><?php echo ucfirst($answers->{1});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									2. Do you feel pain in your chest when you do physical activity? - <strong><?php echo ucfirst($answers->{2});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									3. In the past month, have you had a chest pain when you were not doing physical activity? - <strong><?php echo ucfirst($answers->{3});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									4. Do you lose you balance because of dizziness or do you ever lose conciousness? - <strong><?php echo ucfirst($answers->{4});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									5. Do you have a bone or joint problem (for example, back, knee, or hip) that could be made worse by a change in your physical activity? - <strong><?php echo ucfirst($answers->{5});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									6. Is your doctor currently prescribing medication for your blood pressure or heart condition? - <strong><?php echo ucfirst($answers->{6});?></strong>
								</span>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 parq_question">
								<span>
									7. Do you know of any other reason why you should not do physical activity? - <strong><?php echo ucfirst($answers->{7});?></strong>
								</span>
							</div>
							<?php if($answers->{'7_other'}): ?>
							<div class="col-md-12 yes_for_7">
								<label>Comment for answer #7:</label><br>
								<p><?php echo $answers->{'7_other'};?></p>
							</div>
							<?php endif;?>
						</div>

					</div><!--.parq_questions_container-->
					
					<div class="clear"></div>

	    	</div><!--.container-->
	    	<hr>
	    	<div class="col-md-12 text-center" style="margin-bottom: 50px;">
	    		<button type="button" class="print_parq">Print <i class="fa fa-print"></i></button>
	    	</div>
	    </div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.print_parq', function(){
				$('.print_container').printElement({pageTitle:'My Nutrition Plans'});	
			});

		});
	</script>
<?php else: 
	
	//wp_redirect(home_url());

endif; ?>

<?php get_footer();?>