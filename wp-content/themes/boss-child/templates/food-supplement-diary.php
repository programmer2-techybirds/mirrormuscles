<?php
/*
Template Name: Food-Supplement Diary
*/
get_header();?>
<div id="diaries_print_container" class="print_container" style="display: none;"></div>
<?php

    if (isset($_POST) && !empty($_POST) && isset($_POST['show_client_diaries']) && !empty($_POST['show_client_diaries']) ) {
        $trainer_id = $current_user->ID;
        $client_id = $_POST['client_id'];
        $is_my_client = user_is_connected($client_id);
    }else{
        $client_id = $current_user->ID;
        $is_my_client = false;
    }

    $mm_foodsupp_diary_options = get_option("mm_foodsupp_diary_options");
    $food_diary_image = $mm_foodsupp_diary_options["food_diary_image"];
    $supplement_diary_image = $mm_foodsupp_diary_options["supplement_diary_image"];
    $food_diary_desc = $mm_foodsupp_diary_options["food_diary_desc"];
    $supplement_diary_desc = $mm_foodsupp_diary_options["supplement_diary_desc"];

    $food_diary_plans = get_food_diary_plans($client_id);
    $supplement_diary_plans = get_supplements_diary_plans($client_id);

?>
    
<?php if(bp_get_member_type($client_id) == 'standard' || (isset($trainer_id) && $is_my_client) ): ?>
<div id="buddypress">
<div id="primary">
<div class="template-food-supplement-diary">
    <div class="site-content">
        <h3 class="template-title">
            <?php echo($is_my_client) ? get_fullname($client_id) : ''?> Nutrition Diary
        </h3>

        <div class="col-md-12 text-center">
            <?php if(is_user_logged_in() && wp_is_mobile()):?>
                <?php print_video_container();?>
            <?php endif;?>
        </div>
        
        <div id="food-diary">
        
            <div class="col-md-12 diary-logo-block">
                <img src="<?php echo $food_diary_image;?>">
                <?php echo ($is_my_client) ? '<span>Nutrition diaries</span>' : '<span>Create your nutrituion diary</span>';?>
            </div>
        
            <div class="col-md-12 diary-desc-block">
                <p class="text-center"><?php echo nl2br($food_diary_desc);?></p>
            </div>

            <div class="col-md-12 pull-right text-right">
                <p><small><strong><i class="fa fa-info-circle"></i> Measures: </strong>Calories - kcal., Protein, Fat, Carbs - gram</small></p>
            </div>
            <div class="clear"></div>

            <div id="food-diary-tabs" class="mm-tabs">
                <?php if(!$is_my_client):?>
                    <ul id="food-diary-tabs">
                        <li><a href="#new-food-plan">New nutrition plan</span></a></li>
                        <li><a href="#saved-food-plans">My nutrition plans</a></li>
                        <li><a href="#add-custom-ingredient">Add custom foods</a></li>
                    </ul>
                <?php else:?>
                    <h4 class="template-subtitle"><?php echo get_fullname($client_id).'nutrition plans';?></h4>
                <?php endif;?>

                <?php if (!$is_my_client): ?>
                    <div id="new-food-plan">
                        <form id="food-diary-form" class="clear" onkeypress="return event.keyCode != 13;" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="food_diary_name" class="col-md-6 control-label dairy-label">What would you like to call this nutrition plan?</label>
                                <div class="col-xs-12 col-md-2m col-md-3 input-group">
                                    <input type="text" class="form-control" name="food_diary_name" id="food_diary_name" placeholder="Nutrition plan name" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meals_qty" class="col-md-6 control-label dairy-label">Number of meals per day?</label>
                                <div class="col-md-2 input-group"  style="padding: 0;" >
                                    <input type="number" min="1" max="10" step="1" value="1" id="meals-qty" class="form-control" name="meals_qty" required>
                                </div>
                            </div>
                            <div id="meals_tbls" class="col-md-12">
                                <div id="meals-tbls-container" class="meals-tbls-container table-container">
                                    <table id="meal-table-1" data-meal="1" class="meal-table table toggle-default footable">
                                        <thead>
                                            <tr class="meal-title">
                                                <th colspan="6" class="text-center">Meal 1</th>
                                            </tr>
                                            <tr class="meal-header">
                                                <th style="width: 35%;">Ingredients</th>
                                                <th data-hide="phone" style="width: 15%;" class="calories">Calories</th>
                                                <th data-hide="phone" style="width: 15%;" class="protein">Protein</th>
                                                <th data-hide="phone" style="width: 15%;" class="fat">Fat</th>
                                                <th data-hide="phone" style="width: 15%;" class="carbs">Carbs</th>
                                                <th data-hide="phone"><small>Action</small></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="meal-add-ingredient">
                                                <td class="text-center">
                                                    <p>Add ingredient:</p>
                                                    <p><input type="text" class="search-ingredient-input" placeholder="Search ingredient..."></p>
                                                    <small><label style="font-weight: 400"><input type="checkbox" class="search_in_custom"> Search in Mirror Muscles <a class="popover-custom" data-toggle="popover" data-content="Search for ingredients that was added to the Custom Ingredients Database by Mirror Muscles members." style="cursor:pointer">Custom Ingredients</a></label></small>
                                                    <p><button type="button" class="btn search-ingredient-btn">Search</button></p>
                                                    <p><small>or <a class="jump-to-add-custom-foods">Add custom foods</a></small></p>
                                                </td>
                                                <td colspan="5" class="search-ingredient-results">
                                                    
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="meal-total">
                                                <td class="text-right">Total:</td>
                                                <td class="calories">0</td>
                                                <td class="protein">0</td>
                                                <td class="fat">0</td>
                                                <td class="carbs">0</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" id="save_food_diary" name="save_food_diary" class="btn">Save diary plan</button>
                            </div>
                        </form>
                    </div><!--#new-food-plans-->
                <?php endif; ?>

                <div id="saved-food-plans">
                    <?php if($food_diary_plans): ?>
                        <?php foreach($food_diary_plans as $diary_uniq_id=>$plan):?>
                            <?php
                                $first_meal = reset($plan);
                                $first_ingredient = reset($first_meal);
                                $in_total_calories = $in_total_protein = $in_total_fats = $in_total_carbs = 0;
                            ?>
                            
                            <div class="food-plan-container" data-uniqid="<?php echo $diary_uniq_id;?>">
                                <h3 class="text-center diary-plan-title"><?php echo $first_ingredient->diary_name;?></h3>
                                <?php if( !$is_my_client || ($is_my_client && $first_ingredient->shared == 1) ):?>
                                    <p class="text-center">created: <?php echo date('d.m.Y G:i:s',strtotime($first_ingredient->updated));?></p>
                                    <div class="btn-group inverse pull-right">
                                        <?php if(!$is_my_client) :?>
                                            <a class="btn <?php echo ($first_ingredient->shared == 1) ? 'unshare-food-plan' : 'share-food-plan';?>"><i class="fa fa-share-alt"> <?php echo ($first_ingredient->shared == 1) ? 'Unshare for Trainers/GYMs' : 'Share for Trainers/GYMs';?></i></a>
                                            <a class="btn to-wall-food-plan"><i class="fa fa-user-plus"> to Wall</i></a>
                                            <a class="btn print-food-plan"><i class="fa fa-print"> Print</i></a>
                                            <a class="btn delete-food-plan"><i class="fa fa-trash"> Delete</i></a>
                                        <?php else: ?>
                                            <a class="btn print-food-plan"><i class="fa fa-print"> Print</i></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="food-plan-share-container">    
                                        <?php foreach($plan as $m=>$meal):?>
                                            <?php $meal_calories = $meal_protein = $meal_fats = $meal_carbs = 0; ?>
                                            <table class="meal-table-saved table toggle-default footable">
                                                <thead>
                                                    <tr class="meal-title">
                                                        <td colspan="5" class="text-center">Meal <?php echo ($m+1);?></td>
                                                    </tr>
                                                    <tr class="meal-header">
                                                        <th style="width: 35%;">Ingredients</th>
                                                        <th data-hide="phone" style="width: 15%;" class="calories">Calories</th>
                                                        <th data-hide="phone" style="width: 15%;" class="protein">Protein</th>
                                                        <th data-hide="phone" style="width: 15%;" class="fat">Fat</th>
                                                        <th data-hide="phone" style="width: 15%;" class="carbs">Carbs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($meal as $i=>$ingredient):?>
                                                        <tr class="ingredient-row">
                                                            <td><?php echo $ingredient->ingredient_name;?></td>
                                                            <td><?php echo $ingredient->ingredient_calories;?></td>
                                                            <td><?php echo $ingredient->ingredient_protein;?></td>
                                                            <td><?php echo $ingredient->ingredient_fats;?></td>
                                                            <td><?php echo $ingredient->ingredient_carbs;?></td>
                                                        </tr>
                                                        <?php
                                                            $meal_calories += number_format($ingredient->ingredient_calories,2,'.','');
                                                            $meal_protein += number_format($ingredient->ingredient_protein, 2,'.','');
                                                            $meal_fats += number_format($ingredient->ingredient_fats, 2,'.','');
                                                            $meal_carbs += number_format($ingredient->ingredient_carbs, 2,'.','');
                                                        ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="meal-total">
                                                        <td class="text-right">Total:</td>
                                                        <td class="calories"><?php echo $meal_calories;?></td>
                                                        <td class="protein"><?php echo $meal_protein;?></td>
                                                        <td class="fat"><?php echo $meal_fats;?></td>
                                                        <td class="carbs"><?php echo $meal_carbs;?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php
                                                $in_total_calories += $meal_calories;
                                                $in_total_protein += $meal_protein;
                                                $in_total_fats += $meal_fats;
                                                $in_total_carbs += $meal_carbs;
                                            ?>
                                        <?php endforeach; ?>
                                        <hr style="border-color: #30455c;">
                                        <table class="food-plan-in-total">
                                            <tbody>
                                                <tr class="meal-total">
                                                    <td style="width: 35%" class="text-right">Daily total:</td>
                                                    <td style="width: 15%;" class="calories"><?php echo $in_total_calories;?></td>
                                                    <td style="width: 15%;" class="protein"><?php echo $in_total_protein;?></td>
                                                    <td style="width: 15%;" class="fat"><?php echo $in_total_fats;?></td>
                                                    <td style="width: 15%;" class="carbs"><?php echo $in_total_carbs;?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div id="message" class="info"><p>Sorry, this nutrition plan dosen`t shared by Client.</p></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?> 
                    <?php else: ?>
                        <div id="message" class="info"><p>Sorry, no nutrition plans were found.</p></div>
                    <?php endif; ?>                   
                </div><!--#saved-food-plans-->
                <?php if(!$is_my_client):?>
                    <div id="add-custom-ingredient">
                            <form id="add_own_ingredient" name="add_own_ingredient" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
                                <h4 class="template-subtitle">New Mirror Muscles Ingredient:</h4>
                                <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0"> 
                                    <table class="new_ingredient_tbl">
                                        <tbody>
                                            <tr>
                                                <td colspan="1">
                                                    <label>Ingredient name:</label>
                                                </td>
                                                <td colspan="3">
                                                    <input type="text" name="ingredient_name" id="ingredient_name" required>
                                                    <div id="error-ingredient_name"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>Serving number:</label>
                                                </td>
                                                <td>
                                                    <input type="number" min="1" step="any" name="ingredient_number_of_units" id="ingredient_number_of_units" required>
                                                    <div id="error-ingredient_number_of_units"></div>
                                                </td>
                                                <td>
                                                    <label>Serving description:</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="ingredient_measurement_description" id="ingredient_measurement_description" required>
                                                    <div id="error-ingredient_measurement_description"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="1">
                                                    <label>Calories:</label>
                                                </td>
                                                <td colspan="3">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="1" step="any" class="form-control" name="ingredient_calories" id="ingredient_calories" required>
                                                        <div class="input-group-addon">kcal</div>
                                                    </div>
                                                    <div id="error-ingredient_calories"></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <div class="col-md-6 col-sm-12"> 

                                    <table class="new_ingredient_tbl">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <label>Total fat:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_fat" id="ingredient_fat" required>
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                    <div id="error-ingredient_fats"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <span>Saturated fat:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_saturated_fat" id="ingredient_saturated_fat">
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <span>Polyunsaturated fat:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_polyunsaturated_fat" id="ingredient_polyunsaturated_fat">
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <span>Monounsaturated fat:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_monounsaturated_fat" id="ingredient_monounsaturated_fat">
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <span>Trans fat:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_trans_fat" id="ingredient_trans_fat">
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <label>Cholesterol:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_cholesterol" id="ingredient_cholesterol" required>
                                                        <div class="input-group-addon">mg</div>
                                                    </div>
                                                    <div id="error-ingredient_cholesterol"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <label>Sodium:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_sodium" id="ingredient_sodium" required>
                                                        <div class="input-group-addon">mg</div>
                                                    </div>
                                                    <div id="error-ingredient_sodium"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span>Potassium:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_potassium" id="ingredient_potassium">
                                                        <div class="input-group-addon">mg</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6 col-sm-12"> 
                                    <table class="new_ingredient_tbl">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <label>Total carbohydrate:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_carbohydrate" id="ingredient_carbohydrate" required>
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                    <div id="error-ingredient_carbohydrate"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <label>Dietary fiber:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_fiber" id="ingredient_fiber" required>
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                    <div id="error-ingredient_fiber"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <label>Sugars:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_sugar" id="ingredient_sugar" required>
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                    <div id="error-ingredient_sugar"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <label>Protein:</label>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_protein" id="ingredient_protein" required>
                                                        <div class="input-group-addon">g</div>
                                                    </div>
                                                    <div id="error-ingredient_protein"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span>Vitamin A:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_vitamin_a" id="ingredient_vitamin_a">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span>Vitamin C:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_vitamin_c" id="ingredient_vitamin_c">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span>Calcium:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_calcium" id="ingredient_calcium">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span>Iron:</span>
                                                </td>
                                                <td colspan="2">
                                                    <div class="input-group form-group">
                                                        <input type="number" min="0" step="any" class="form-control" name="ingredient_iron" id="ingredient_iron">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12 form-group text-center">
                                    <p><small><strong><i class="fa fa-info-circle"></i></strong> Your ingredient will be added to the Mirror Muscles Custom Products Database. It can be edited by the site administrator.</small></p>
                                    <input type="hidden" name="save_new_ingredient" value="1">
                                    <button type="submit" class="btn" id="save_new_ingredient">Save</button>
                                </div>
                            </div><!---->
                        </form>
                    </div>
                <?php endif;?>
            </div><!--#food-diary-tabs-->
        </div><!-- #food-diary-->

        <div class="clear"></div>
        
        <hr>

        
        <h3 class="template-title"><?php echo($is_my_client) ? get_fullname($client_id) : ''?> Supplement Diary</h3>
       
        <div id="supplement-diary">
            
            <div class="col-md-12 diary-logo-block">
                <img src="<?php echo $supplement_diary_image;?>">
                <?php echo ($is_my_client) ? '<span>Supplement diaries</span>' : '<span>Create your supplement diary</span>';?>
            </div>
            
            <div class="col-md-12 diary-desc-block">
                <p class="text-center"><?php echo nl2br($supplement_diary_desc);?></p>
            </div>
                
            <div class="clear"></div>

            <div id="supplement-diary-tabs" class="mm-tabs">
                <?php if(!$is_my_client) :?>
                    <ul>
                        <li><a href="#new-supplement-plan">New supplement plan</span></a></li>
                        <li><a href="#saved-supplement-plans">My supplement plans</a></li>
                    </ul>
                <?php else:?>
                    <h4 class="template-subtitle"><?php echo get_fullname($client_id).'supplement plans';?></h4>
                <?php endif;?>


                <?php if(!$is_my_client) :?>
                    <div id="new-supplement-plan">
                        <form id="supplement-diary-form" onkeypress="return event.keyCode != 13;" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="supplements_diary_name" class="col-md-6 control-label dairy-label">What would you like to call this supplement plan?</label>
                                <div class="col-xs-12 col-md-2 col-md-3 input-group">
                                    <input type="text" class="form-control" name="supplements_diary_name" id="supplements_diary_name" placeholder="Supplement plan name" value="" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="supplements_qty" class="col-md-6 control-label dairy-label">How many supplements do you take each day?</label>
                                <div class="col-md-2 input-group"  style="padding: 0;" >
                                 <select id="supplements_qty" class="form-control" name="supplements_qty" required>
                                            <option value="">-</option>
                                        <?php for($i=1; $i<=99;  $i++): ?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div>
                            <div id="supplements_tbls" class="col-md-12">
                                <!-- Tables Here-->                    
                            </div>
                            
                            <div class="col-md-12 text-center">
                                <button id="save_supplement_diary" name="save_supplement_diary" class="btn">Save supplement plan</button>
                            </div>
                        </form>
                    </div><!--#new-supplement-plan-->
                <?php endif; ?>
                
                <div id="saved-supplement-plans">
                    <?php if($supplement_diary_plans): ?>
                        <?php foreach($supplement_diary_plans as $diary_uniq_id=>$supplements):?>
                            
                            <?php $first_supplement = reset($supplements);?>
                            
                            <div class="supplement-plan-container" data-uniqid="<?php echo $diary_uniq_id;?>">
                                <h3 class="text-center diary-plan-title"><?php echo $first_supplement->diary_name;?></h3>
                                <?php if(!$is_my_client || ($is_my_client && $first_supplement->shared == 1)):?>
                                    <p class="text-center">created: <?php echo date('d.m.Y G:i:s',strtotime($first_supplement->updated));?></p>
                                    <div class="btn-group inverse pull-right">
                                        <?php if(!$is_my_client) :?>
                                            <a class="btn <?php echo ($first_supplement->shared == 1) ? 'unshare-supplement-plan' : 'share-supplement-plan';?>"><i class="fa fa-share-alt"> <?php echo ($first_supplement->shared == 1) ? 'Unshare for Trainers/GYMs' : 'Share for Trainers/GYMs';?></i></a>
                                            <a class="btn print-supplement-plan"><i class="fa fa-print"> Print</i></a>
                                            <a class="btn delete-supplement-plan"><i class="fa fa-trash"> Delete</i></a>
                                        <?php else: ?>
                                            <a class="btn print-supplement-plan"><i class="fa fa-print"> Print</i></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clear"></div>
                                    <table class="supplements-table-saved table toggle-default footable">
                                        <thead>
                                            <tr>
                                                <th>Supplement name</th>
                                                <th data-hide="phone" style="width: 25%;">Units</th>
                                                <th data-hide="phone" style="width: 25%;"h>Amount</th>
                                                <th data-hide="phone" style="width: 25%;">Times Taken Per Day</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($supplements as $s=>$supplement):?> 
                                                <tr id="sdp_supplement_options_<?php echo $supplement->id;?>" class="sdp_supplement_options">
                                                    <td data-field="supplement_name"><?php echo $supplement->supplement_name;?></td>
                                                    <td data-field="supplement_unit"><?php echo $supplement->supplement_unit;?></td>
                                                    <td data-field="supplement_amount"><?php echo $supplement->supplement_amount;?></td>
                                                    <td data-field="supplement_per_day"><?php echo $supplement->supplement_per_day;?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div id="message" class="info"><p>Sorry, this supplement plan dosen`t shared by Client.</p></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div id="message" class="info"><p>Sorry, no nutrition plans were found.</p></div>
                    <?php endif; ?>  
                </div><!--#saved-supplement-plans-->
            </div><!--#supplement-diary-tabs-->
        </div><!--#supplement-diary-->
    </div>
</div>
</div>
</div>
                  
<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>