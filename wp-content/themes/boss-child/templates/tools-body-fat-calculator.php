<?php
/*
Template Name: Tools Body Fat Calculator
*/

get_header();

$member_type = bp_get_member_type($current_user->id);
$birthday = bp_get_profile_field_data('field=5&user_id='.$current_user->id);
$gender = bp_get_profile_field_data('field=7&user_id='.$current_user->id);
$age = ($member_type != 'gym') ? (date('Y') - date('Y',strtotime($birthday))) : '';

?>
<div id="buddypress" class="template-my-progress container">
    <div class="site-content">
        <h3 class="template-title">Body Fat Calculator</h3>
		<div id="body-fat-calculator">
		    <form id="bfc-form" action="" method="post" enctype="multipart/form-data">
		        <input type="hidden" name="current_user" value="<?php echo $current_user->id;?>"/>
		        <div class="col-md-6 col-xs-12">
		            <div class="col-md-12 bfc-left">
		                <h4>Body Fat Calculator:</h4>
		                <div class="form-group">
		                    <label for="gender" class="col-md-6 control-label">Gender</label>
		                    <div class="col-md-6 input-group"  style="padding: 0;" >
		                        <select name="gender" id="gender" class="form-control" required>
				                    <option value="Male" <?php selected($gender, 'Male',true);?>>Male</option>
				                    <option value="Female" <?php selected($gender, 'Female',true);?>>Female</option>
				                </select>
		                    </div>
		                </div>
		                

		                <div class="form-group">
		                    <label for="age" class="col-md-6 control-label">Age</label>
		                    <div class="col-md-6 input-group">
		                        <input type="number"  min="8" class="form-control" name="age" id="age" placeholder="Age" value="<?php echo $age;?>" required >
                    			<div class="input-group-addon">years</div>
		                    </div>
		                </div>
		                <div id="error-age" class="col-md-12"></div>

		                <div class="form-group">
		                    <label for="weight" class="col-md-6 control-label">Weight</label>
		                    <div class="col-md-3 input-group" style="float:left; padding-right: 10px;">
		                        <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight" required>
		                    </div>
		                    <div class="col-md-2 input-group"  style="padding: 0;" >
		                     <select id="units" class="form-control" name="units" tabindex="-1" required>
		                            <option value="kg" selected>kg</option>
		                            <option value="lbs">lbs</option>
		                            <option value="oz">oz</option>
		                        </select>
		                    </div>
		                </div>
		                <div id="error-weight" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="chest" class="col-md-6 control-label">Chest</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="chest" id="chest" placeholder="Chest" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-chest" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="axilla" class="col-md-6 control-label">Axilla</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="axilla" id="axilla" placeholder="Axilla" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-axilla" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="triceps" class="col-md-6 control-label">Triceps</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="triceps" id="triceps" placeholder="Triceps" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
						<div id="error-triceps" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="subscapular" class="col-md-6 control-label">Subscapular</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="subscapular" id="subscapular" placeholder="Subscapular" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-subscapular" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="Abdominal" class="col-md-6 control-label">Abdominal</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="abdominal" id="abdominal" placeholder="Abdominal" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-abdominal" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="suprailiac" class="col-md-6 control-label">Suprailiac</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="suprailiac" id="suprailiac" placeholder="Suprailiac" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-suprailiac" class="col-md-12"></div>
		                
		                <div class="form-group">
		                    <label for="thigh" class="col-md-6 control-label">Thigh</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="thigh" id="thigh" placeholder="Thigh" required>
		                        <div class="input-group-addon">mm</div>
		                    </div>
		                </div>
		                <div id="error-thigh" class="col-md-12"></div>
		                
		                <div class="form-group text-center">
		                    <!--<small><a href="/how-to-measure-your-bodyfat" target="_blank" tabindex="-1"><i class="fa fa-info-circle"></i> How to measure your body fat</a></small>-->
		                    <small><?php echo '<i class="fa fa-info-circle"></i>  '. do_shortcode('[video_lightbox_youtube video_id="hQWoq8D9xnE&rel=false" width="640" height="480" anchor="How to measure your body fat"]'); ?></small>
		                </div>
		            </div>
		        </div>

		        <div class="col-md-6 col-xs-12">
		            <div class="col-md-12 bfc-right">
		                <h4 class="text-center">Body Fat Calculator Results:</h4>
		                <div id="error-bodyfat" class="col-md-12"></div>
		                <div class="form-group">
		                    <label for="bodyfat" class="col-md-6 control-label">Body Fat</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="bodyfat" id="bodyfat" placeholder="Body Fat" readonly>
		                        <div class="input-group-addon">%</div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="fatmass" class="col-md-6 control-label">Fat Mass</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="fatmass" id="fatmass" placeholder="Fat Mass" readonly>
		                        <div class="input-group-addon">kg</div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="leanmass" class="col-md-6 control-label">Lean Mass</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="leanmass" id="leanmass" placeholder="Lean Mass" readonly>
		                        <div class="input-group-addon">kg</div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label for="mmcategory" class="col-md-6 control-label">Mirror Muscles Category</label>
		                    <div class="col-md-6 input-group">
		                        <input type="text" class="form-control" name="mmcategory" id="mmcategory" placeholder="Mirror Muscles Category" readonly>
		                    </div>
		                </div>
		                
		                <hr>
		                <div class="text-center">
		                	<div class="btn-group inverse">
		                    	<button type="button" class="btn" id="bfc-calculate"><i class="fa fa-calculator"></i> Calculate</button>
		                    	<button type="button" class="btn" id="bfc-copy"><i class="fa fa-copy"></i> Copy Body Fat to clipboard</button>
		                    </div>
		                </div>
		            </div>
		        </div>
		</form>
		</div><!--#body-fat-calculator-->
	</div><!--/.site-content-->
</div><!--/#buddypress-->
<script type="text/javascript">
	
	jQuery(document).ready(function(){

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


		jQuery(document).on('click', '#bfc-calculate', function(){

			if(jQuery("#bfc-form").valid()){
				
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
					
				}
			}
		});

		jQuery(document).on('click', '#bfc-copy', function(){
			var temp = jQuery("<input>");
			jQuery("body").append(temp);
			temp.val(jQuery('#bodyfat').val()).select();
			document.execCommand("copy");
			temp.remove();
		});





	});



</script>
<?php get_footer(); ?>