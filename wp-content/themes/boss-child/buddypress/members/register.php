<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHKB9Y1_R9tuSqYJMSqaAIFsFGw9qMhm8&libraries=places"></script>


<script>   
function initialize(){

   autocomplete = new google.maps.places.Autocomplete((document.getElementById('field_10')), {types: ['(regions)']});
   google.maps.event.addListener(autocomplete, 'place_changed', function() {});
 
}
             
google.maps.event.addDomListener(window, 'load', initialize);
	  
</script>

<?php $mm_regpage_options = get_option('mm_regpage_options'); ?>
<div id="buddypress" class="buddypress-reg" style="background: url( <?php echo $mm_regpage_options['regpage_image']?> ) no-repeat center center fixed; background-size: cover;">

	<?php do_action( 'bp_before_register_page' ); ?>
	 
	<div class="page" id="register-page" >
	   <form action="" name="signup_form" id="signup_form"  class="standard-form" method="post" enctype="multipart/form-data">
		<div id="signup_form_block" class="row">
			<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<?php do_action( 'template_notices' ); ?>
				<?php do_action( 'bp_before_registration_disabled' ); ?>
					<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>
				<?php do_action( 'bp_after_registration_disabled' ); ?>
			<?php endif; // registration-disabled signup step ?>

		<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
			
			<div class="col-md-12 col-xs-12"><?php do_action( 'template_notices' ); ?></div>
			<div class="col-md-12 col-xs-12"><?php do_action( 'bp_before_account_details_fields' );?></div>
			<div class="col-md-12 col-xs-12">
				<div class="regpage-account-details">
				
					<div class="col-md-12 col-xm-12 col-xs-12 text-center regblock_header">
						LOGIN DETAILS
					</div>

					<label for="signup_username"><?php _e( 'Username', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_username_errors' ); ?>
					<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php bp_form_field_attributes( 'username' ); ?>/>
					<div id="error-signup_username"></div>

					<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_email_errors' ); ?>
					<input type="email" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php bp_form_field_attributes( 'email' ); ?>/>
					<div id="error-signup_email"></div>

					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_password_errors' ); ?>
					<input type="password" name="signup_password" id="signup_password" value="" class="password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
					<div id="error-signup_password"></div>

					<div id="pass-strength-result">Password strength meter / Weak Passwords are accepted</div>
                    
					<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
					<?php do_action( 'bp_signup_password_confirm_errors' ); ?>
					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" class="password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>
					<div id="error-signup_password_confirm"></div>

					<?php do_action( 'bp_account_details_fields' ); ?>
					<?php do_action( 'bp_after_account_details_fields' ); ?>

				</div><!-- .regpage-account-details-->
			</div>

			<div class="col-md-12 col-xs-12">
							
				<div class="regpage-profile-details">
					
					<div class="col-md-12 col-xs-12 text-center regblock_header">
						PROFILE DETAILS
					</div>		
					
					<?php do_action( 'bp_before_signup_profile_fields' ); ?>

					<div class="col-md-4 col-xs-12" >
						<!-- User type - field_4 -->
						<div class="radio-container">
							<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
								<?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
									<?php if(bp_get_the_profile_field_name()=='User Type'):?>
										<?php
											$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
											$field_type->edit_field_html();
										?>
										<div id="error-field_4"></div>
									<?php endif;?>
								<?php endwhile;?>
							<?php endwhile; endif; endif; ?>
						</div>

						<!-- Time Zone - field_100 -->
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
							<?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
								<?php if(bp_get_the_profile_field_name()=='Time Zone'):?>
									<?php
										$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
										$field_type->edit_field_html();
									?>
									<div id="error-field_100"></div>
								<?php endif;?>
							<?php endwhile;?>
						<?php endwhile; endif; endif; ?>

						<!-- First Name - field_1 & Last Name - field_2 & GYM Name - field -->
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
							<?php $aaa=bp_the_profile_field();
							while (bp_profile_fields()) : bp_the_profile_field(); ?>
								<?php if(bp_get_the_profile_field_name()=='First Name'
											|| bp_get_the_profile_field_name()=='Last Name' 
											|| bp_get_the_profile_field_name()=='GYM Name'):?>
									<?php
										$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
										$field_type->edit_field_html();
									?>
									<?php if(bp_get_the_profile_field_input_name()=='field_1'):?>
	                                    <div id="error-field_1"></div>
	                                <?php elseif(bp_get_the_profile_field_input_name()=='field_2'):?>
	                                    <div id="error-field_2"></div>
	                                <?php elseif(bp_get_the_profile_field_input_name()=='field_3'):?>
	                                    <div id="error-field_3"></div>
	                                <?php endif;?>
								<?php endif;?>
							<?php endwhile; $bbb=bp_the_profile_field();?>
						<?php endwhile; endif; endif; ?>
					</div>

					<div class="col-md-4 col-xs-12">
						<!-- Specialization - field_12 -->
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
							<?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
								<?php if(bp_get_the_profile_field_name()=='Specialization'):?>
									<?php
										$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
										$field_type->edit_field_html();
									?>
									<div id="error-field_12"></div>
								<?php endif;?>
							<?php endwhile; ?>
						<?php endwhile; endif; endif; ?>

						<!-- Birthday - field_5, Gender - field_7, Location - field_10 -->
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
							<?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
								<?php if(bp_get_the_profile_field_name()=='Birthday'||
										bp_get_the_profile_field_name()=='Gender'||
										bp_get_the_profile_field_name()=='Location'):?>

									<?php if(bp_get_the_profile_field_input_name()=='field_7'):?>
										<div class="radio-container" style="margin-top: 0; margin-bottom: 2px;">
									<?php endif;?>

										<?php
											$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
											$field_type->edit_field_html();
										?>
										<input type="hidden" id="hidden_birthday_year" value="<?php echo $_POST['field_5_year']?>">
										<input type="hidden" id="hidden_birthday_month" value="<?php echo $_POST['field_5_month']?>">
										<input type="hidden" id="hidden_birthday_day" value="<?php echo $_POST['field_5_day']?>">
                                        

									<?php if(bp_get_the_profile_field_input_name()=='field_7'):?>
										</div>
									<?php endif;?>

									<?php if(bp_get_the_profile_field_input_name()=='field_5'):?>
	                                    <div id="error-field_5_day"></div>
	                                    <div id="error-field_5_month"></div>
	                                    <div id="error-field_5_year"></div>
	                                <?php elseif(bp_get_the_profile_field_input_name()=='field_7'):?>
	                                    <div id="error-field_7"></div>
	                                <?php elseif(bp_get_the_profile_field_input_name()=='field_10'):?>
	                                    <div id="error-field_10"></div>
	                                <?php endif;?>
								<?php endif;?>
							<?php endwhile; ?>
						<?php endwhile; endif; endif; ?>
					</div>

					<div class="col-md-4 col-xs-12">
						<!-- Phone - field_11 -->
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
							<?php $ccc=bp_the_profile_group();
							while (bp_profile_fields()) : bp_the_profile_field(); ?>
								<?php if(bp_get_the_profile_field_name()=='Phone'):?>
									<?php
										$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
										$field_type->edit_field_html();
									?>
	                                <div id="error-field_11"></div>
								<?php endif;?>
							<?php endwhile; ?>
						<?php endwhile; endif; endif; ?>

						<?php do_action( 'bp_signup_profile_fields' ); ?>
						<?php do_action( 'bp_after_signup_profile_fields' ); ?>

						<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_field_ids(); ?>" />
						<div>
							<?php do_action( 'bp_before_registration_submit_buttons' ); ?>
							<div id="error-g-recaptcha-response"></div>
						</div>
                        
                        <div id="for_tandc">
                            <label for="terms_condition">
                                <input name="terms_condition" id="terms_condition" value="terms" type="checkbox" disabled="disabled"><strong><a href="https://www.mirrormuscles.com/vendor-terms-conditions/" style="color:#fff;margin-left: 10px">Terms and Conditions</a> </strong>
                            </label>
                        </div>
						
						<input class="btn" type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Complete Sign Up', 'buddypress' ); ?>" style="text-transform:uppercase;"/>
						<?php do_action( 'bp_after_registration_submit_buttons' ); ?>	
					</div>
				</div>
			</div>

			<?php wp_nonce_field( 'bp_new_signup' ); ?>

		<?php endif; // request-details signup step ?>

		<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' ); ?>
			<?php

			/**
			 * Fires before the display of the registration confirmed messages.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_before_registration_confirmed' ); ?>

			<?php if ( bp_registration_needs_activation() ) : ?>
				<p class="regpage-account-details">
					<?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address. ', 'buddypress' ); ?>
					<a href="<?php echo wp_login_url(); ?>" class="please-login">Login</a>
				</p>
			<?php else : ?>
				<p class="regpage-account-details">
					<?php _e( 'You have successfully created your account! Please log in using the username and password you have just created. ', 'buddypress' ); ?>
					<a href="<?php echo wp_login_url(); ?>" class="please-login">Login</a>
				</p>
			<?php endif; ?>

			<?php

			/**
			 * Fires after the display of the registration confirmed messages.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_after_registration_confirmed' ); ?>

		<?php endif; // completed-confirmation signup step ?>

		<?php

		/**
		 * Fires and displays any custom signup steps.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		do_action( 'bp_custom_signup_steps' ); ?>
		</div><!-- .row -->
		</form>

	</div><!-- #page -->

	<?php

	/**
	 * Fires at the bottom of the BuddyPress member registration page template.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_register_page' ); ?>

</div><!-- #buddypresrs -->
<script type="text/javascript">

	jQuery(document).ready(function(){
		
		//set password strength meter popover
		jQuery('body').popover({ 
			selector: '[rel=popover]',
		    html : true,
		    trigger: "hover",
		    placement: 'bottom'
		});

		//set Location geocomplete dropdown
		jQuery('#field_10').geocomplete({componentRestrictions: {country: ''}}).attr('placeholder','');
		
		//remove birthdate xprofile initial required attribute
		jQuery('select[id^=field_5]').removeAttr('required');
		
		//set birthdate values after form posting
		jQuery('#field_5_year').val(jQuery('#hidden_birthday_year').val());
		jQuery('#field_5_month').val(jQuery('#hidden_birthday_month').val());
		jQuery('#field_5_day').val(jQuery('#hidden_birthday_day').val());

		var telInput = jQuery("#field_11");

		telInput.intlTelInput({
			initialCountry: "auto",
			geoIpLookup: function(callback) {
			  	jQuery.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			    	var countryCode = (resp && resp.country) ? resp.country : "";
			    	callback(countryCode);
			  	});
			}
		});
		

		if(navigator.userAgent.match(/AppleWebKit/) && !navigator.userAgent.match(/Chrome/)){
		   //alert('this is safari brower and only safari brower')
				var ua = navigator.userAgent.toLowerCase(),
				selectorX = jQuery('#hourlyOffrWrper ul#myOffrAdd'); 
				if (ua.indexOf('safari') != -1) { 
					if (ua.indexOf('chrome') > -1) {
						if (telInput.val().length>0)
							telInput.val(telInput.intlTelInput("getNumber"));
				
						telInput.on("keyup change", function() {
							var intlNumber = telInput.intlTelInput("getNumber");
							  if (intlNumber)
								telInput.val(intlNumber);
						});
					} else {
						//alert("safari") // Safari
						
						jQuery(".styled > strong").css("left","2px !important");
						jQuery("#signup_form.standard-form div.radio label").css("text-align","center");
						
						jQuery("#signup_form.standard-form #field_7 label[for='option_8']").css("width","85px !important");
						jQuery("#signup_form.standard-form #field_7 label[for='option_9']").css("width","98px !important");
						jQuery("#signup_form.standard-form #field_7 label[for='option_8']").css("text-align","center");
						jQuery("#signup_form.standard-form #field_7 label[for='option_9']").css("text-align","center");
						jQuery("#signup_form.standard-form #field_7 label[for='option_8']").css("top","5px");
						jQuery("#signup_form.standard-form #field_7 label[for='option_9']").css("top","5px");
					}
				}
		}

/*
		if (telInput.val().length>0)
			telInput.val(telInput.intlTelInput("getNumber"));

		telInput.on("keyup change", function() {
            var intlNumber = telInput.intlTelInput("getNumber");
              if (intlNumber)
                telInput.val(intlNumber);
        });
*/

		jQuery('#field_100 option').each(function(i,e){
			var val = jQuery(this).val();
			/*
			var offset = val.split('(')
			  .filter(function(v){ return v.indexOf(')') > -1})
			  .map( function(value) { 
			     var gmt = value.split(')')[0];
			     return gmt.split('GMT')[1];
			     //returns -11:00...+14:00;
			  });*/

			var offset = val.split('(')
			  .filter(function(v){ return v.indexOf(')') > -1})
			  .map( function(value) { 
			     return value.split(')')[1].trim();
			     //returns Pacific/Wallis, Europe/Kiev...
			  });
			
			if(navigator.userAgent.match(/AppleWebKit/) && !navigator.userAgent.match(/Chrome/)){
			   //alert('this is safari brower and only safari brower')
					var ua = navigator.userAgent.toLowerCase(),
					selectorX = jQuery('#hourlyOffrWrper ul#myOffrAdd'); 
					if (ua.indexOf('safari') != -1) { 
						if (ua.indexOf('chrome') > -1) {
							if(offset.toString() === Intl.DateTimeFormat().resolvedOptions().timeZone)
								jQuery(this).attr('selected','selected');
						} else {
							//alert("safari") // Safari
						//selectorX.css('max-width', 1200);
							jQuery("#signup_form.standard-form div.radio label[for='option_pt']").css("width","145px !important");
							jQuery("#signup_form.standard-form div.radio label[for='option_gym']").css("width","110px !important");
							jQuery("#signup_form.standard-form div.radio label[for='option_standard']").css("width","135px !important");
							jQuery("#signup_form.standard-form div.radio label:first-child").css("width","265px !important");
							jQuery("#signup_form.standard-form div.radio label:first-child").css("text-align","left !important");
						}
					}
			}
/*			
			if(offset.toString() === Intl.DateTimeFormat().resolvedOptions().timeZone)
				jQuery(this).attr('selected','selected');
*/				
		});


		var switch_usertype = function(type){
			jQuery('div[id^=error]').empty();
			//remove 'reqired' from field labels
			jQuery('input[id^="field_"], select[id^="field_"], input[name="field_7"]').attr('disabled','disabled')
			jQuery('label[for^="field_"]:not(label[for="field_4"])').text(function(i, val) {
				return val.replace(/\(required\)/g,'');
			});
			jQuery('div#field_7').prev('legend').text(function(i, val) {
				return val.replace(/ \(required\)/g,'');
			});

			jQuery('select[id^=field_]').attr('disabled','disabled')
			jQuery('select[id^=field_] option').attr('selected',false);
			jQuery('input[name^=field_]:not([name="field_4"])').attr('selected',false).attr('checked',false).attr('disabled','disabled');
			
			switch(type){
				case 'standard':
					jQuery('label[for="field_1"],label[for="field_2"], label[for^="field_5"],legend,label[for="field_10"],label[for="field_11"],label[for="field_100"]').text(function(i, val) {
						return val+' (required)';
					});
					jQuery('#field_1,#field_2,#field_100,select[id^="field_5"],input[name=field_7],#field_10,#field_11').attr('disabled',false);
					jQuery('input[name=terms_condition]').attr('disabled',true);
					jQuery('input[name=terms_condition]').attr('checked', false);
				break;
				case 'pt':
					jQuery('label[for="field_1"],label[for="field_2"], label[for^="field_5"],label[for="field_100"],legend,label[for="field_10"],label[for="field_11"],label[for^="field_12"],label[for="field_100"]').text(function(i, val) {
						return val+' (required)';
					});
					jQuery('#field_1,#field_2,#field_100,select[id^="field_5"],input[name=field_7],input[name=terms_condition],#field_10,#field_11,select[name^=field_12]').attr('disabled',false);
				break;
				case 'gym':
					jQuery('label[for="field_3"],label[for="field_10"],label[for="field_11"],label[for="field_100"],label[for^="field_12"]').text(function(i, val) {
						return val+' (required)';
					});
					jQuery('#field_3,#field_100,input[name=terms_condition],#field_10,#field_11,select[name^=field_12]').attr('disabled',false);
				break;
			}	
		}
		
		var type = jQuery('input[name="field_4"]:checked').val();
		switch_usertype(type);

		jQuery(document).on('change','input[name="field_4"]',function(){
			switch_usertype(jQuery(this).val());
		});


		jQuery.validator.addMethod('username', function (value) { 
		    return /^([a-zA-Z0-9.-@]+)$/.test(value); 
		}, 'Usernames can contain only letters, numbers, ., -, and @');

		jQuery.validator.addMethod("nowhitespace", function(value, element)
        { return jQuery.trim(value) && value != ""; }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("isvalidphone", function(value, element)
        { return telInput.intlTelInput("isValidNumber"); }, "Mobile phone number is invalid.");

        jQuery("#signup_form").validate({
            ignore: "",
            rules: {
            	signup_username:{
            		required: true,
            		nowhitespace: true,
            		username: true,
            		minlength: 4,
            		remote: {
				        //url: mirrorMuscles.ajaxPath,
						url: "https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php",
				        type: "POST",
				        cache: false,
				        dataType: "json",
				        data: {
				            signup_email: function() { return jQuery("#signup_username").val(); },
				            action: "check-username-exist"
				        }
				    }
            	},
            	signup_email:{
            		required: true,
            		nowhitespace: true,
            		remote: {
				        //url: mirrorMuscles.ajaxPath,
						url: "https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php",
				        type: "POST",
				        cache: false,
				        dataType: "json",
				        data: {
				            signup_email: function() { return jQuery("#signup_email").val(); },
				            action: "check-user-email-exist"
				        }
				    }
            	},
            	signup_password: {
		            required: true,
		            minlength: 1,
		            nowhitespace: true,
		        },
		        signup_password_confirm: {
		            required: true,
		            minlength: 1,
		            equalTo: "#signup_password",
		            nowhitespace: true
		        },
                field_1: {
                  required: true,
                  nowhitespace: true
                },
                field_2: {
                  required: true,
                  nowhitespace: true
                },
                field_3: {
                  required: true,
                  nowhitespace: true
                },
                field_4: {
                    required: true
                },
                field_5_day:{
                    required: true,
                    minlength: 1
                },
                field_5_month:{
                    required: true,
                    minlength: 1
                },
                field_5_year:{
                    required: true,
                    minlength: 1
                },
                field_7:{
                    required: true,
                    minlength: 1
                },
                field_10: {
                  required: true,
                  nowhitespace: true
                },
                field_11: {
                  required: true,
                  isvalidphone: true,
                  nowhitespace: true,
                },
                field_12: {
                  required: true
                },
                field_100: {
                  required: true
                },
                'g-recaptcha-response': {
		           required: function() {
		               if(grecaptcha.getResponse() == '') {
		                   return true;
		               } else {
		                   return false;
		               }
		           }
		       }
            },
            messages: {
            	signup_username: {
            		required: 'Enter your account Username',
            		minlength: 'Username minlength is 4',
            		remote: 'Sorry, that username already exists!'
            	},
            	signup_email: {
            		required: 'Enter your account Email',
            		email: 'Invald email address. Your email address must be in the format of name@domain.com',
            		remote: 'Sorry, that email already exists!'
            	},
            	signup_password: 'Enter account Password',
            	signup_password_confirm: 'Enter account Password confirmation',
                field_1: "Enter your First Name",
                field_2: "Enter your Last Name",
                field_3: "Enter your GYM Name",
                field_4: "Choose your User Type",
                field_5_day: "Select your Birthday day",
                field_5_month: "Select your Birthday month",
                field_5_year: "Select your Birthday year",
                field_7: "Select your Gender",
                field_10: "Enter your Location",
                field_11: "Mobile phone number is invalid",
                field_12: "Enter your Specialization",
                field_100: "Select your Time Zone",
                'g-recaptcha-response': "You must prove you are human."
            },
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
	});
</script>