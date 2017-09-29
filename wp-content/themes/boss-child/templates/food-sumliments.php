<?php
/*
Template Name: Supplement Diary
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
                            <div class="form-group ">
                                <label for="supplements_qty" class="col-md-6 control-label dairy-label">How many supplements do you take each day?</label>
                                <div class="col-xs-12 col-md-2 col-md-2 input-group"  style="padding: 0;" >
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