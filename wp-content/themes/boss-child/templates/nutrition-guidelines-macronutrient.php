<?php $member_type = get_query_var('member_type');?>

<p class="template-description-text"><?php echo nl2br(stripslashes(get_query_var('macronutrient_desc')));?></p>

<form id="macronutrient_form"name="macronutrient_form" action="" method="post" enctype="multipart/form-data">
	<div class="col-md-6">
        <div class="col-md-12 calculator-container" id="macronutrient_calculator_container">
            
            <h4 class="text-center">Macronutrient Calculator:</h4>
            
            <div class="form-group form-inline">
                <label for="macronutrient_daily_calories" class="col-md-5 col-xs-12 control-label">Daily Calories</label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                    <input type="number" min="1200" max="20000" name="macronutrient_daily_calories" id="macronutrient_daily_calories" class="form-control" required>
                </div>
            </div>
            <div id="error-macronutrient_daily_calories" class="col-md-12"></div>
            <div class="form-group form-inline text-center">
                <small><label for="macronutrient_radio" class="control-label">
                <input type="checkbox" name="macronutrient_radio" id="macronutrient_radio" value="1">
                or fill in required fields below</label></small>
            </div>
            <hr style="border-color: #4dcade;">

            <div class="form-group form-inline">
                <label for="macronutrient_gender" class="col-md-5 col-xs-12 control-label">Gender</label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                    <select data-member-type="<?php echo $member_type;?>" name="macronutrient_gender" id="macronutrient_gender" class="form-control" disabled="true" required>
                        <option value="Male" <?php selected(get_query_var('gender'), 'Male',true);?>>Male</option>
                        <option value="Female" <?php selected(get_query_var('gender'), 'Female',true);?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="macronutrient_age" class="col-md-5 col-xs-12 control-label">Age</label>
                <div class="col-md-7 input-group">
                    <input data-member-type="<?php echo $member_type;?>" type="number" min="8" class="form-control" name="macronutrient_age" id="macronutrient_age" placeholder="Age" value="<?php echo get_query_var( 'age');?>" disabled="true" required>
                    <div class="input-group-addon">years</div>
                </div>
            </div>
            <div id="error-macronutrient_age" class="col-md-12"></div>

            
            <div class="form-group">
                <label for="macronutrient_weight" class="col-md-5 col-xs-12 control-label">Weight</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="30" max="10000" step="1" class="form-control" name="macronutrient_weight" id="macronutrient_weight" placeholder="Weight" disabled="true" required>
                </div>
                <div class="col-md-3 input-group pad0">
                 <select id="macronutrient_units_weight" class="form-control" name="macronutrient_units_weight" tabindex="-1" disabled="true" required>
                        <option value="kg" selected>kg</option>
                        <option value="lbs">lbs</option>
                        <option value="oz">oz</option>
                    </select>
                </div>
            </div>
			<div id="error-macronutrient_weight" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="macronutrient_height" class="col-md-5 col-xs-12 control-label">Height</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="10" max="300" step="1" class="form-control" name="macronutrient_height" id="macronutrient_height" placeholder="Height" disabled="true" required>
                </div>
                <div class="col-md-3 input-group pad0">
                 	<select id="macronutrient_units_height" class="form-control" name="macronutrient_units_height" tabindex="-1" disabled="true" required>
                        <option value="cm" selected>cm</option>
                        <option value="in">inches</option>
                    </select>
                </div>
            </div>
			<div id="error-macronutrient_height" class="col-md-12"></div>
			
            <div class="form-group">
                <label for="macronutrient_factor" class="col-md-5 col-xs-12 control-label">Activity Factor <i class="fa fa-info-circle" rel="popover"
                data-content="<small><b>Sedentary</b> - You don’t move much. No exercise, desk job, lots of TV</small><br>
                			<small><b>Lightly active</b> - Active a few days a week, exercise 1-3 days</small><br>
                			<small><b>Moderately active</b> - Where I would assume most people are at. Train 4-5 days a week and active lifestyle</small><br>
                			<small><b>Very active</b> - Training hard for a specific sport or purpose 5-6 hours a week. Typically one with a hard labor job as well</small><br>
                			<small><b>Extremely active</b> - Endurance training or hard charging athlete who spends 10 or more hours training a week and/or lots of activity outside of training</small><br>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                 	<select class="form-control" name="macronutrient_factor" id="macronutrient_factor" disabled="true" required>
						<option value="">-</option>
						<option value="1.2">Sedentary</option>
						<option value="1.375">Lightly active</option>
						<option value="1.55">Moderately active</option>
						<option value="1.725">Very active</option>
						<option value="1.9">Extremely active</option>
					</select>
                </div>
            </div>
			<div id="error-macronutrient_factor" class="col-md-12"></div>

			<hr style="border-color: #4dcade;">
			<div class="form-group">
                <label for="macronutrient_meals" class="col-md-5 col-xs-12 control-label">Meals</label>
                <div class="col-md-7 col-xs-12 input-group">
                    <input type="number" min="2" max="8" step="1" class="form-control" name="macronutrient_meals" id="macronutrient_meals"  placeholder="Meal per day min 2 and max 8 times" required>
                </div>
            </div>
			<div id="error-macronutrient_meals" class="col-md-12"></div>
                           
            
            <div class="text-center">
            	<button type="button" class="btn" id="macronutrient_calculate"><i class="fa fa-calculator"></i>Calculate</button>
            </div>

            
        </div>
    </div>

	<div class="col-md-6 col-xs-12">
        <div id="macronutrient_results_container" class="col-md-12 calculator-container">
	        
	        <div class="calculator-print-container macronutrient-print-container">
				<h4>Macronutrient CALCULATION RESULTS:</h4>
				<div class="text-center">
					<div id="macronutrient-presets-slider">
					    <table>
					    	<tbody>
					    		<tr>
					    			<td class="text-center" colspan="2"><label>Select percentage preset: Protein/Fat/Carbohydrate</label></td>
					    		</tr>
					    		<tr>
					    			<td width="160"><label>Auto Presets</label></td>
					    			<td>
					    				<div id="macronutrient-presets" class="btn-group inverse">
									        <button type="button" class="btn" data-preset="25-15-60" data-calories="" data-meals="" disabled="disabled">25/15/60<br>&nbsp;</button>
									        <button type="button" class="btn macronutrient-slider-preset" data-preset="0-0-0" data-calories="" data-meals="" disabled="disabled" style="display:none;">Custom</button>
									        <button type="button" class="btn" data-preset="30-20-50" data-calories="" data-meals="" disabled="disabled">30/20/50<br>&nbsp;</button>
									        <button type="button" class="btn" data-preset="30-30-40" data-calories="" data-meals="" disabled="disabled">30/30/40<br>Zone Diet</button>
									        <button type="button" class="btn" data-preset="45-30-25" data-calories="" data-meals="" disabled="disabled">45/30/25<br>&nbsp;</button>
									    </div><!--/#macronutrient_presets-->

					    			</td>
					    		</tr>
					    		<tr>
					    			<td width="160"><label>Manual Protein</label></td>
					    			<td><div id="protein-slider"></div></td>
					    		</tr>
					    		<tr>
					    			<td width="160"><label>Manual Fat</label></td>
					    			<td><div id="fat-slider"></div></td>
					    		</tr>
					    		<tr>
					    			<td width="160"><label>Manual Carbohydrate</label></td>
					    			<td><div id="carbs-slider"></div></td>
					    		</tr>
					    	</tbody>
					    </table>
					</div><!--/#macronutrient_presets-slider-->
	        	</div>
				<div id="macronutrient_results_share" class="calculator-results-share">
					<p class="macronutrient_calories_per_day calculator-results-title">Calories per day:</p>
					<table id="macronutrient_per_day_table" class="calculator-results-table">
						<tbody>
							<tr>
								<td style="width: 33%;" class="protein">Protein</td>
								<td style="width: 33%;" class="fat">Fat</td>
								<td style="width: 33%;" class="carbs">Carbohydrate</td>
							</tr>
						</tbody>
					</table>

					<p class="macronutrient_calories_per_meal calculator-results-title">Calories per meal:</p>
					<table id="macronutrient_per_meal_table" class="calculator-results-table">
						<tbody>
							<tr>
								<td style="width: 33%;" class="protein">Protein</td>
								<td style="width: 33%;" class="fat">Fat</td>
								<td style="width: 33%;" class="carbs">Carbohydrate</td>
							</tr>
						</tbody>
					</table>
				</div><!--#share-->
			</div><!--.print-->

			<div class="col-md-12 form-group text-center">
            	<div class="btn-group inverse">
                    <button type="button" class="btn" id="macronutrient-print" disabled="disabled"><i class="fa fa-lg fa-print"></i>Print</button>
                    <button type="button" class="btn" id="macronutrient-share" disabled="disabled"><i class="fa fa-user-plus"></i>Share to Wall</button>
                </div>
            </div>
		</div>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function(){
	$("#macronutrient_radio").on('change', function(){	
		if ($("#macronutrient_radio").is(':checked')){
			$("#macronutrient_gender").attr('disabled', false);
		}else{
			$("#macronutrient_gender").attr('disabled', true);	
		}
	});
	
});
</script>