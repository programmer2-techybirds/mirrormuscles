<?php 
	$userid = bp_loggedin_user_id();
	$type = bp_get_member_type($userid);
	$fullname = get_fullname($userid);
	$dob = bp_get_profile_field_data('field=5&user_id='.$userid);
	$address = bp_get_profile_field_data('field=10&user_id='.$userid);
	$mobile = bp_get_profile_field_data('field=11&user_id='.$userid);
	$user_info = get_userdata($userid);
	$email = $user_info->user_email;
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Please fill in the attached questionnaire before you send the request</h3>
			<hr>
			<h2 class="parq_title">Physical Activity Readiness Questionnaire (PAR-Q)</h2>
			<p class="parq_notation">If you are between the ages of 15 and 69, the PAR-Q will tell you if you should check with your doctor before you
			significantly change your physical activity patterns. If you are over 69 years of age and are not used to being very
			active, check with your doctor. Common sense is your best guide when answering these questions. Please read
			carefully and answer each one honestly: check YES or NO.</p>
			
			<form></form>
			<form action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="POST" id="parq-modal-form">
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

					<div class="parq_agreement_container yes_for_answers_agreement">
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
							<button type="submit" class="bnt success" name="save_request_and_parq" value="1">Submit and send Connection Request</button>
						</div>
						
					</div>

					<div class="parq_agreement_container no_for_answers_agreement">
						<p>It is reasonably safe for you to participate in physical activity, gradually building up from your current ability level. A fitness appraisal can help determine your ability levels.</p>
						
						<div class="checkbox"><div id="error-agreement_no"></div>
						    <label>
						    	<input class="parq_question" name="agreement_no" type="checkbox" > I have read, understood and accurately completed this questionnaire. I confirm that I am voluntarily engaging in an acceptable level of exercise, and my participation involves a risk of injury.
						    </label>
						</div>

						<div class="clearfix"></div>
						
						<div class="text-center">
							<button type="submit" class="bnt success" name="save_request_and_parq" value="1">Submit and send Connection Request</button>
						</div>
					</div>

				</div>
				<div class="clear"></div>

				<div class="col-md-12">
					<small><strong>Note:</strong> This physical activity clearance is valid for a maximum of 12 months from the date it is completed and becomes invalid if your condition changes so that you would answer YES to any of the 7 questions.</small>
				</div>
				<div class="clear"></div><br>
			</form>
		</div>
	</div>
</div>
    		
			
			
			

    	</div><!--.container-->
    </div>
</div>