<?php $member_type = get_query_var('member_type');?>

<p class="template-description-text"><?php echo nl2br(stripslashes(get_query_var('iifym_desc')));?></p>

<form id="iifym_form" action="" method="post" enctype="multipart/form-data">
	<div class="col-md-6">
        <div class="col-md-12 calculator-container" id="iifym_calculator_container">
            
            <h4 class="text-center">IIFYM Diet Macro Calculator:</h4>
            
            <p class="text-center"><small>Enter your stats to see your macronutrient breakdown per meal for cutting or bulking.</small></p>
            
            <div class="form-group form-inline">
                <label for="iifym_gender" class="col-md-5 col-xs-12 control-label">Gender</label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                    <select <?php disabled($member_type, 'standard'); disabled($member_type, 'pt');?> name="iifym_gender" id="iifym_gender" class="form-control" required>
                        <option value="Male" <?php selected(get_query_var('gender'), 'Male',true);?>>Male</option>
                        <option value="Female" <?php selected(get_query_var('gender'), 'Female',true);?>>Female</option>
                    </select>				                        
                </div>
            </div>

            <div class="form-group">
                <label for="iifym_age" class="col-md-5 col-xs-12 control-label">Age</label>
                <div class="col-md-7 input-group">
                    <input type="number"  min="8" class="form-control" name="iifym_age" id="iifym_age" placeholder="Age" value="<?php echo get_query_var( 'age');?>" <?php disabled($member_type, 'standard'); disabled($member_type, 'pt');?> required >
                    <div class="input-group-addon">years</div>
                </div>
            </div>
            <div id="error-iifym_age" class="col-md-12"></div>

            
            <div class="form-group">
                <label for="iifym_weight" class="col-md-5 col-xs-12 control-label">Weight</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="30" max="10000" step="1" class="form-control" name="iifym_weight" id="iifym_weight" placeholder="Weight" required>
                </div>
                <div class="col-md-3 input-group pad0">
                 <select id="iifym_units_weight" class="form-control" name="iifym_units_weight" tabindex="-1" required>
                        <option value="kg" selected>kg</option>
                        <option value="lbs">lbs</option>
                        <option value="oz">oz</option>
                    </select>
                </div>
            </div>
			<div id="error-iifym_weight" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="iifym_height" class="col-md-5 col-xs-12 control-label">Height</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="10" max="300" step="1" class="form-control" name="iifym_height" id="iifym_height" placeholder="Height" required>
                </div>
                <div class="col-md-3 input-group pad0">
                 	<select id="iifym_units_height" class="form-control" name="iifym_units_height" tabindex="-1" required>
                        <option value="cm" selected>cm</option>
                        <option value="in">inches</option>
                    </select>
                </div>
            </div>
			<div id="error-iifym_height" class="col-md-12"></div>
			
            <div class="form-group">
                <label for="iifym_factor" class="col-md-5 col-xs-12 control-label">Activity Factor <i class="fa fa-info-circle" rel="popover"
                data-content="<small><b>Sedentary</b> - Little or no Exercise/desk job</small><br>
                			<small><b>Lightly active</b> - Light exercise/sports 1–3 days/week</small><br>
                			<small><b>Moderately active</b> - Moderate Exercise, sports 3–5 days/ week</small><br>
                			<small><b>Very active</b> - Heavy Exercise/sports 6–7 days/week</small><br>
                			<small><b>Extremely active</b> - Very heavy exercise/physical job/training 2x/day</small><br>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                 	<select class="form-control" name="iifym_factor" id="iifym_factor" required>
						<option value="">-</option>
						<option value="1.2">Sedentary</option>
						<option value="1.375">Lightly active</option>
						<option value="1.55">Moderately active</option>
						<option value="1.725">Very active</option>
						<option value="1.9">Extremely active</option>
					</select>
                </div>

            </div>
			<div id="error-iifym_factor" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="iifym_goal" class="col-md-5 col-xs-12 control-label">Goal <i class="fa fa-info-circle" rel="popover"
                data-content="<small><b>Shred Recommended</b> - Decrease 10%</small><br>
                			<small><b>Shred Moderate</b> - Decrease 15%</small><br>
                			<small><b>Shred Aggressive</b> - Decrease 20%</small><br>
                			<small><b>Bulk Recommended</b> - Increase 10%</small><br>
                			<small><b>Bulk Moderate</b> - Increase 15%</small><br>
                			<small><b>Bulk Aggressive</b> - Increase 20%</small><br>
                			<small><b>Maintain</b> - no change</small><br>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                 	<select class="form-control" name="iifym_goal" id="iifym_goal" required>
						<option value="">-</option>
						<option value="-10">Shred Recommended</option>
						<option value="-15">Shred Moderate</option>
						<option value="-20">Shred Aggressive</option>
						<option value="10">Bulk Recommended</option>
						<option value="15">Bulk Moderate</option>
						<option value="20">Bulk Aggressive</option>
						<option value="0">Maintain</option>
					</select>
                </div>
            </div>
			<div id="error-iifym_goal" class="col-md-12"></div>
           
            <div class="form-group">
                <label for="iifym_age" class="col-md-5 col-xs-12 control-label">Meals</label>
                <div class="col-md-7 col-xs-12 input-group">
                    <input type="number" min="2" max="8" step="1" class="form-control" name="iifym_meals" id="iifym_meals"  placeholder="Meal per day min 2 and max 8 times" required>
                </div>
            </div>
			<div id="error-iifym_meals" class="col-md-12"></div>
            
            <div class="text-center">
            	<button type="button" class="btn" id="iifym_calculate"><i class="fa fa-calculator"></i>Calculate</button>
            </div>

            
        </div>
    </div>

	<div class="col-md-6 col-xs-12">
        <div id="iifym_results_container" class="col-md-12 calculator-container">
        	<div class="calculator-print-container iifym-print-container">
				<h4>IIFYM CALCULATION RESULTS:</h4>
    			<div id="iifym_results_share" class="calculator-results-share">
    				<p class="iifym_calories_per_day calculator-results-title">Calories per day:</p>
    				<p id="iifym_results_tdee" class="text-center"><small></small></p>
    				<table id="iifym_per_day_table" class="calculator-results-table">
    					<tbody>
    						<tr>
    							<td style="width: 33%;" class="protein">Protein</td>
    							<td style="width: 33%;" class="fat">Fat</td>
    							<td style="width: 33%;" class="carbs">Carbohydrate</td>
    						</tr>
    					</tbody>
    				</table>

    				<p class="iifym_calories_per_meal calculator-results-title">Calories per meal:</p>
    				<table id="iifym_per_meal_table" class="calculator-results-table">
    					<tbody>
    						<tr>
    							<td style="width: 33%;" class="protein">Protein</td>
    							<td style="width: 33%;" class="fat">Fat</td>
    							<td style="width: 33%;" class="carbs">Carbohydrate</td>
    						</tr>
    					</tbody>
    				</table>

    				<p class="iifym_percenage_ratio calculator-results-title">Percentage ratio:</p>
    				<div id="iifym_results_pie" class="calculator-results-pie"></div>
    				<div id="iifym_results_pie_hover" class="col-md-12 text-center calculator-results-pie-hover"></div>
    				<div id="iifym_results_pie_legend" class="col-md-12 calculator-results-pie-legend"></div>

    			</div><!--#share-->
			</div><!--.print-->

			<div class="col-md-12 form-group text-center">
            	<div class="btn-group inverse">
                    <button type="button" class="btn" id="iifym-print" disabled="disabled"><i class="fa fa-lg fa-print"></i>Print</button>
                    <button type="button" class="btn" id="iifym-share" disabled="disabled"><i class="fa fa-user-plus"></i>Share to Wall</button>
                </div>
            </div>
		</div>
	</div>
</form>