<?php
/*
Template Name: Nutrition Guidelines
*/
get_header();

$member_type = bp_get_member_type($current_user->id);
$birthday = bp_get_profile_field_data('field=5&user_id='.$current_user->id);
$gender = bp_get_profile_field_data('field=7&user_id='.$current_user->id);
$age = ($member_type != 'gym') ? (date('Y') - date('Y',strtotime($birthday))) : '';

$mm_nutrition_guidelines_options = get_option("mm_nutrition_guidelines_options");
$mm_nutritions_macronutrient = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_macronutrient_desc"]);
$mm_nutritions_keto = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_keto_desc"]);
$mm_nutritions_iifym = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_iifym_desc"]);
$mm_nutritions_zonediet = stripslashes_deep($mm_nutrition_guidelines_options["mm_nutritions_zonediet_desc"]);

//set variable for template parts
set_query_var( 'age', $age );
set_query_var( 'gender', $gender );
set_query_var( 'member_type', $member_type);

set_query_var( 'macronutrient_desc', $mm_nutritions_macronutrient);
set_query_var( 'keto_desc', $mm_nutritions_keto);
set_query_var( 'iifym_desc', $mm_nutritions_iifym);
set_query_var( 'zonediet_desc', $mm_nutritions_zonediet);

?>
<?php if(is_user_logged_in()): ?>

	<?php 
		
	 ?>
	<div id="buddypress">
		<div id="primary">
			<div class="template-nutrition-plans">
	    		<div class="site-content">
			        <h3 class="template-title">Nutrition Guidelines</h3>
			        <p class="template-description-text">You can select type of Nutrition Guidelines / Calculator you would like to use</p>

			        <div class="mm-tabs">
				        <ul>
				            <li><a href="#macronutrient">Macronutrient Calculator</a></li>
				            <li><a href="#keto">Keto</a></li>
				            <li><a href="#iifym">IIFYM</a></li>
				        </ul>

				        <div id="macronutrient">
				        	<?php get_template_part('templates/nutrition-guidelines', 'macronutrient');?>
				        </div><!--/#macronutrient-->

				        <div id="keto">
				        	<?php get_template_part('templates/nutrition-guidelines', 'keto');?>
				        </div><!--/#keto-->

				        <div id="iifym">
				        	<?php get_template_part('templates/nutrition-guidelines', 'iifym');?>
				        </div><!--/#iifym-->
	        		</div><!--/.mm-tabs-->
	    		</div><!--.site-content-->                            
			</div><!--.template...-->
		</div><!--#primary-->
	</div><!--#buddypress-->
<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>