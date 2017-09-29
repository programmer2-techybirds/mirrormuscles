<?php $member_type = get_query_var('member_type');?>

<p class="template-description-text"><?php echo nl2br(stripslashes(get_query_var('keto_desc')));?></p>

<form id="keto_form" action="" method="post" enctype="multipart/form-data">
    <div class="col-md-6">
        <div class="col-md-12 calculator-container" id="keto_calculator_container">
            
            <h4 class="text-center">Keto Calculator:</h4>
                        
            <div class="form-group form-inline">
                <label for="keto_gender" class="col-md-5 col-xs-12 control-label">Gender</label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                    <select <?php disabled($member_type, 'standard'); disabled($member_type, 'pt');?> name="keto_gender" id="keto_gender" class="form-control" required>
                        <option value="Male" <?php selected(get_query_var('gender'), 'Male',true);?>>Male</option>
                        <option value="Female" <?php selected(get_query_var('gender'), 'Female',true);?>>Female</option>
                    </select>                                       
                </div>
            </div>

            <div class="form-group">
                <label for="keto_age" class="col-md-5 col-xs-12 control-label">Age</label>
                <div class="col-md-7 input-group">
                    <input type="number" min="8" class="form-control" name="keto_age" id="keto_age" placeholder="Age" value="<?php echo get_query_var( 'age');?>" <?php disabled($member_type, 'standard'); disabled($member_type, 'pt');?> required >
                    <div class="input-group-addon">years</div>
                </div>
            </div>
            <div id="error-keto_age" class="col-md-12"></div>

            
            <div class="form-group">
                <label for="keto_weight" class="col-md-5 col-xs-12 control-label">Weight</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="30" max="10000" step="1" class="form-control" name="keto_weight" id="keto_weight" placeholder="Weight" required>
                </div>
                <div class="col-md-3 input-group pad0">
                 <select id="keto_units_weight" class="form-control" name="keto_units_weight" tabindex="-1" required>
                        <option value="kg" selected>kg</option>
                        <option value="lbs">lbs</option>
                        <option value="oz">oz</option>
                    </select>
                </div>
            </div>
            <div id="error-keto_weight" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="keto_height" class="col-md-5 col-xs-12 control-label">Height</label>
                <div class="col-md-4 col-xs-12 input-group" style="float:left; padding-right: 10px;">
                    <input type="number" min="10" max="300" step="1" class="form-control" name="keto_height" id="keto_height" placeholder="Height" required>
                </div>
                <div class="col-md-3 input-group pad0">
                    <select id="keto_units_height" class="form-control" name="keto_units_height" tabindex="-1" required>
                        <option value="cm" selected>cm</option>
                        <option value="in">inches</option>
                    </select>
                </div>
            </div>
            <div id="error-keto_height" class="col-md-12"></div>

            <div class="form-group">
                <label for="keto_bodyfat" class="col-md-5 col-xs-12 control-label">Bodyfat <br><small><a style="font-weight: 300; text-decoration: none;" href="/bfc" target="_blank" tabindex="-1"><i class="fa fa-calculator"></i> Calculate your body fat</a></small></label>
                <div class="col-md-7 col-xs-12 input-group">
                    <input type="number" min="1" step="any" class="form-control" name="keto_bodyfat" id="keto_bodyfat" required>
                    <div class="input-group-addon">%</div>
                </div>
            </div>
            <div id="error-keto_bodyfat" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="keto_factor" class="col-md-5 col-xs-12 control-label">Activity Factor <i class="fa fa-info-circle" rel="popover"
                data-content="<small><b>Sedentary</b> - Little or no Exercise/desk job</small><br>
                            <small><b>Lightly active</b> - Light exercise/sports 1–3 days/week</small><br>
                            <small><b>Moderately active</b> - Moderate Exercise, sports 3–5 days/ week</small><br>
                            <small><b>Very active</b> - Heavy Exercise/sports 6–7 days/week</small><br>
                            <small><b>Extremely active</b> - Very heavy exercise/physical job/training 2x/day</small><br>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group pad0">
                    <select class="form-control" name="keto_factor" id="keto_factor" required>
                        <option value="">-</option>
                        <option value="1.2">Sedentary</option>
                        <option value="1.375">Lightly active</option>
                        <option value="1.55">Moderately active</option>
                        <option value="1.725">Very active</option>
                        <option value="1.9">Extremely active</option>
                    </select>
                </div>
            </div>
            <div id="error-keto_factor" class="col-md-12"></div>
            
            <div class="form-group">
                <label for="keto_netcarbs" class="col-md-5 col-xs-12 control-label">Your daily net carbs <i class="fa fa-info-circle" rel="popover"
                data-content="<small>Below 50g of net carbs each day is sufficient for most people to stay in ketosis. Make sure to get your carbs from vegetables (10-15g), nuts and seeds (5-10g), and fruits (5-10g). Keep in mind that in Europe food labels generally show net carbs, while America shows total carbs. Calculate net carbs by subtracting fiber from total carbs</small>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group">
                    <input type="number" min="1" step="1" max="100" class="form-control" name="keto_netcarbs" id="keto_netcarbs" required>
                    <div class="input-group-addon">g</div>
                </div>
            </div>
            <div id="error-keto_netcarbs" class="col-md-12"></div>

            <div class="form-group">
                <label for="keto_netcarbs" class="col-md-5 col-xs-12 control-label">Your daily norm of protein <i class="fa fa-info-circle" rel="popover"
                data-content="<small>Set value in range 1.3 ... 2.2 g/kg and than we calculate your <b>Averege Protein Norm</b></small>" data-html="true"></i></label>
                <div class="col-md-7 col-xs-12 input-group">
                    <input type="number" min="1.3" max="2.2" class="form-control" name="keto_protein" id="keto_protein" required>
                    <div id="keto-protein-units" data-unut="kg" class="input-group-addon">g/kg</div>
                </div>
            </div>
            <div id="error-keto_protein" class="col-md-12"></div>

            <div class="text-center">
                <button type="button" class="btn" id="keto_calculate"><i class="fa fa-calculator"></i>Calculate</button>
            </div>

            
        </div>
    </div>

    <div class="col-md-6 col-xs-12">
        <div id="keto_results_container" class="col-md-12 calculator-container">
            <div class="calculator-print-container keto-print-container">
                <h4>keto CALCULATION RESULTS:</h4>
                <div class="text-center">
                    <div id="keto-presets-slider">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="text-center" colspan="2"><label>Calories Deficit or Surplus Percentage</label></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <div id="keto-calories-presets" class="btn-group inverse">
                                            <button type="button" class="btn" data-bmr="" data-tdee="" data-carbs-weight="" data-protein-weight="" data-preset="loss" disabled="disabled">Weight loss</button>
                                            <button type="button" class="btn active" data-bmr="" data-tdee="" data-carbs-weight="" data-protein-weight="" data-preset="maintenance" disabled="disabled">Maintenance</button>
                                            <button type="button" class="btn keto-slider-preset" data-bmr="" data-tdee="" data-carbs-weight="" data-protein-weight="" data-preset="0" disabled="disabled" style="display:none;">Custom</button>
                                            <button type="button" class="btn" data-bmr="" data-tdee="" data-carbs-weight="" data-protein-weight="" data-preset="gain" disabled="disabled">Weight gain</button>
                                        </div><!--/#keto-calories-presets-->
                                    </td>
                                </tr>
                                <tr>
                                    <td width="160"><label>Manual calories adjustment (<span id="keto-calories-slider-value">0</span>%)</label></td>
                                    <td>
                                        <div id="keto-calories-slider">
                                            <div id="keto-colorized-slider" style="width: 100%">
                                                <div class="min"><span></span></div>
                                                <div class="max"><span></span></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!--/#macronutrient_presets-slider-->
                </div>
                <div id="keto-results-share" class="calculator-results-share">
                    <p class="calculator-results-title">Calculated nutritional macros:</p>
                    <table id="keto-results-table">
                        <tr>
                            <td width="50%">
                                <p id="keto_results_bmr" class="calculator-results-title"><small>BMR:</small></p>
                                <p id="keto_results_tdee" class="calculator-results-title"><small>TDEE:</small></p>
                                <p id="keto_results_intake_fat" class="calculator-results-title"><small>Fat intake should be:</small></p>
                                <p id="keto_results_average_protein" class="calculator-results-title"><small>Average protein norm:</small></p>
                            </td>
                            <td width="50%">
                                <p id="keto_results_bodyfat" class="calculator-results-title"><small>Body fat:</small></p>
                                <p id="keto_results_leanmass" class="calculator-results-title"><small>Lean body mass:</small></p>
                                <p id="keto_results_essential_bodyfat" class="calculator-results-title"><small>Essential body fat:</small></p>
                            </td>
                        </tr>
                    </table>
                    <table id="keto_per_day_table" class="calculator-results-table">
                        <tbody>
                            <tr>
                                <td style="width: 33%;" class="protein">Protein</td>
                                <td style="width: 33%;" class="fat">Fat</td>
                                <td style="width: 33%;" class="carbs">Net Carbs</td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="calculator-results-title">Percentage ratio:</p>
                    <div id="keto-results-pie" class="calculator-results-pie"></div>
                    <div id="keto-results-pie-hover" class="col-md-12 text-center calculator-results-pie-hover"></div>
                    <div id="keto-results-pie-legend" class="col-md-12 calculator-results-pie-legend"></div>

                </div><!--#share-->
            </div><!--.print-->

            <div class="col-md-12 form-group text-center">
                <div class="btn-group inverse">
                    <button type="button" class="btn" id="keto-print" disabled="disabled"><i class="fa fa-lg fa-print"></i>Print</button>
                    <button type="button" class="btn" id="keto-share" disabled="disabled"><i class="fa fa-user-plus"></i>Share to Wall</button>
                </div>
            </div>
        </div>
    </div>
</form>