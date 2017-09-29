<?php

    global $current_user;

    $member_type = get_query_var('member_type');

    $is_my_client = get_query_var('is_my_client');

    $workout_logs = get_query_var('workout_logs');

?>



<?php if($workout_logs):?>

    <div class="mm-accourdion">

    <?php foreach($workout_logs as $uniq_id=>$log):?>  

        

        <?php if($member_type == 'standard'):?>

            <h3 class="<?php echo ($log[0]->client_id == $current_user->ID) ? 'trainer-saved-workout' : 'myself-saved-workout';?>">

                <span class="pull-left"><?php echo stripslashes_deep($log[0]->name);?></span>

                <span class="pull-right"><?php echo ($log[0]->client_id == $current_user->ID) ? 'created by '.get_fullname($log[0]->user_id) : '';?></span>

            </h3>

        <?php else:?>



            <h3 class="<?php echo ($log[0]->client_id != 0) ? 'trainer-saved-workout' : 'myself-saved-workout';?>">

                <span class="pull-left"><?php echo stripslashes_deep($log[0]->name);?></span>

                <?php if($is_my_client):?>

                    <span class="pull-right"><?php echo ($log[0]->client_id != 0) ? 'created by '.get_fullname($log[0]->user_id) : '';?></span>

                <?php else: ?>

                    <span class="pull-right"><?php echo ($log[0]->client_id != 0) ? 'created for '.get_fullname($log[0]->client_id) : '';?></span>

                <?php endif; ?>

            </h3>

        <?php endif;?>

        

        <div>

            <div class="saved-wl-container text-center" data-uniqid="<?php echo $uniq_id;?>">

                <?php if( !$is_my_client || ($is_my_client && $log[0]->shared == 1) || ($is_my_client && $log[0]->user_id == $current_user->ID) ):?>

                    <div class="saved-wl-container-inner">

                        <h3 class="text-center diary-plan-title"><?php echo stripslashes_deep($log[0]->name);?></h3>

                        <p class="text-center">created: <?php echo date('F j, Y G:i:s',strtotime($log[0]->added));?></p>

                        <div class="btn-group inverse">

                            <!-- if this page viewing not from ClientProgress page-->

                            <?php if(!$is_my_client) :?>

                                

                                <?php if($member_type == 'standard' && $log[0]->client_id != $current_user->ID):?>

                                    <a class="btn small <?php echo ($log[0]->shared == 1) ? 'unshare-workout-log' : 'share-workout-log';?>"><i class="fa fa-share-alt"> <?php echo ($log[0]->shared == 1) ? 'Unshare for Trainers/GYMs' : 'Share for Trainers/GYMs';?></i></a>

                                <?php endif;?>

                                

                                <a class="btn small to-wall-workout-log"><i class="fa fa-user-plus"> to Wall</i></a>

                                <a class="btn small print-workout-log"><i class="fa fa-print"> Print</i></a>



                                <?php if($log[0]->client_id != $current_user->ID):?>

                                    <a class="btn small delete-workout-log"><i class="fa fa-trash"> Delete</i></a>

                                <?php endif;?>



                            <?php else: ?>

                                <!-- else if current user is 'pt' and he comes from ClientProgress page-->

                                <a class="btn small to-wall-workout-log"><i class="fa fa-user-plus"> to Wall</i></a>

                                <a class="btn small print-workout-log"><i class="fa fa-print"> Print</i></a>

                            <?php endif; ?>

                        </div>

                        <div class="clear"></div>

                        <style>

						.imagediv3 tr td{

						cursor:move;

						}

						</style>

						<script>

						jQuery(document).ready(function(){

								jQuery(".imagediv3").sortable({ tolerance: 'pointer' });            

						});

						function submitsaveform(id){

							jQuery.post(ajaxurl, jQuery('#'+id).serialize(),function(data){

								 jQuery('#successmessage2'+id).show( "slow" );

								 setTimeout(function() {

									jQuery('#successmessage2'+id).hide( "fast" );

								 }, 7000);

							});

						}

						</script>

                        <div class="workout-log-share-container">

                            <p id="successmessage2<?php echo $uniq_id; ?>" style="display:none; color:#003300;">Order saved successfully!</p>

                            <form name="saveorderform" id="<?php echo $uniq_id;?>" method="post" action="">

                            <input type="hidden" name="action" value="saveorderform" />

                            <input type="button" name="savesubmit" value="Save Order" onclick="submitsaveform('<?php echo $uniq_id;?>');" style="float:right; margin-bottom:20px;" />

                            <table class="workout-table-exercises table toggle-default footable" data-uniqid="<?php echo $uniq_id;?>">

                                <thead>

                                    <tr>

                                    <th>Day</th>
                                    
                                    <th>Exercise</th>

                                    <th>Repeats (Weights)</th>

                                    <?php echo (!$is_my_client && $log[0]->client_id != $current_user->ID) ? '<th  class="actions">Actions</th>' : '';?>

                                    </tr>

                                </thead>

                               

                                <tbody class="imagediv3">

                                	

                                    <?php foreach ($log as $key => $value): ?>

                                        <tr class="me" data-id="<?php echo $value->id;?>">

                                        	<input type="hidden" name="logid2[]" value="<?php echo $value->id;?>" />
                                            
                                            <td><?php echo date('l', strtotime("Sunday +{$value->day} days"));?></td>
                                            
                                            <td><?php echo stripslashes_deep($value->exercise_name);?></td>

                                            <td>

                                                <?php

                                                    $repeats = json_decode($value->repeats);

                                                    $weights = json_decode($value->weights);

                                                    

                                                    $str = [];

                                                        foreach ($repeats as $k => $v)

                                                            array_push($str,$v.'('.$weights[$k].')');

                                                    echo implode(' - ',$str);

                                                ?>

                                            </td>

                                            <?php echo (!$is_my_client && $log[0]->client_id != $current_user->ID) 

                                                    ? '<td class="actions">

                                                        <span class="hidden-exercise-name">'.$value->exercise_name.'</span>

                                                        <i class="fa fa-lg fa-edit edit-wl-exercise" data-id="'.$value->id.'" data-client-id="'.$value->client_id.'" data-day="'.$value->day.'" data-exercise-id="'.$value->exercise_id.'" data-repeats="'.implode(',',json_decode($value->repeats)).'" data-weights="'.implode(',',json_decode($value->weights)).'"></i>

                                                        &nbsp;<i class="fa fa-lg fa-trash delete-workout-log-exercise" data-id="'.$value->id.'"></i></td>' : '';?>

                                        </tr> 

                                    <?php endforeach;?>

                                    

                                </tbody>

                                

                            </table>

                            </form>

                        </div>

                    </div><!--.saved-workout-container-inner-->

                    <?php if(!$is_my_client && $log[0]->client_id != $current_user->ID) :?>

                        <div class="saved-wl-container-edit" data-uniqid="<?php echo $uniq_id;?>" data-name="<?php echo $log[0]->name;?>" data-shared="<?php echo $log[0]->shared;?>">

                            

                            <h3 class="template-subtitle">Edit <?php echo stripslashes_deep($log[0]->name);?></h3>

                            

                            <form class="edit-wl-form select-exercise-container wl-form" data-uniqid="<?php echo $uniq_id;?>" onkeypress="return event.keyCode != 13;">



                                <div class="form-group text-center">

                                    <label>Select weekday: </label><br>

                                    <div class="btn-group exerciseweekdays">

                                        <button data-daynum="1" data-dayname="Monday" type="button" class="btn active">MON</button>

                                        <button data-daynum="2" data-dayname="Thuesday" type="button" class="btn">TUE</button>

                                        <button data-daynum="3" data-dayname="Wednesday" type="button" class="btn">WED</button>

                                        <button data-daynum="4" data-dayname="Thursday" type="button" class="btn">THU</button>

                                        <button data-daynum="5" data-dayname="Friday" type="button" class="btn">FRI</button>

                                        <button data-daynum="6" data-dayname="Saturday" type="button" class="btn">SAT</button>

                                        <button data-daynum="7" data-dayname="Sunday"type="button" class="btn">SUN</button>

                                    </div>

                                </div>



                                <div class="text-center">

                                    <label>Select exercise: </label>

                                    <div class="form-group">

                                        <?php echo workout_exercise_selectbox( 'add-swl-exercisename-'.$log[0]->uniq_id, 'add-swl-exercisename'.$log[0]->uniq_id, 'exercisename form-group', true);?>

                                    </div>

                                    <div id="error-add-swl-exercisename-"<?php echo $log[0]->uniq_id;?>></div>

                                    

                                    <div class="exercise-details-container">

                                        <table class="exercise-details">

                                            <thead>

                                                <th width="70%" colspan="2">Details</th>

                                                <th>Images</th>

                                            </thead>

                                            <tbody>

                                                <tr>

                                                    <td><label>Name:</label></td>

                                                    <td class="exercise-name"></td>

                                                    <td class="exercise-images" rowspan="6"></td>

                                                </tr>

                                                <tr>

                                                    <td><label>Description:</label></td>

                                                    <td class="exercise-description"></td>

                                                </tr>

                                                <tr>

                                                    <td><label>Muscles:</label></td>

                                                    <td class="exercise-muscles"></td>

                                                </tr>

                                                <tr>

                                                    <td><label>Secondary Muscles:</label></td>

                                                    <td class="exercise-muscles-secondary"></td>

                                                </tr>

                                                <tr>

                                                    <td><label>Equipment:</label></td>

                                                    <td class="exercise-equipment"></td>

                                                </tr>

                                                <tr>

                                                    <td><label>Comments:</label></td>

                                                    <td class="exercise-comment"></td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                    <div class="clear"></div>

                                    <p id="add-swl-show-exercise-details-<?php echo $log[0]->uniq_id ?>" class="show-exercise-details"><small>Show/Hide exercise details <i class="fa fa-eye"></i></small></p>

                                    <hr>



                                    <small><label style="font-weight: 400"><input type="checkbox" id="add-swl-exercisename-custom-radio-<?php echo $log[0]->uniq_id ?>" class="add-wl-exercisename-custom-radio"> or set custom Exercise name</label></small>

                                    <div class="form-group">

                                      <input id="add-swl-exercisename-custom-<?php echo $log[0]->uniq_id ?>" name="add-swl-exercisename-custom-<?php echo $log[0]->uniq_id ?>" type="text" class="exercisename-custom form-control" placeholder="Custom exercise name..." disabled="true" required>

                                    </div>

                                    <div id="error-add-swl-exercisename-custom-<?php echo $log[0]->uniq_id ?>"></div>

                                    

                                    

                                    <div class="form-group form-inline">

                                        <label>Set exercise sets:</label>

                                        <select class="exercisesets">';

                                            <?php for ($n = 1; $n < 11; ++ $n): ?>

                                                <option value="<?php echo $n;?>"><?php echo $n;?></option>

                                            <?php endfor;?>

                                        </select>

                                    </div>



                                    <table id="add-swl-repeats-weight-table" class="repeats-weight-table">

                                        <thead>

                                            <th width="30%">Set</th>

                                            <th width="35%">Repeats</th>

                                            <th width="35%">Weight<br>(optional)</th>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td>Set 1</td>

                                                <td><input class="exerciserepeats" type="number" min="1" max="99" step="1" value="1" required></td>

                                                <td><input class="exerciseweight" type="number" min="0" max="1000" step="any" value="0" required></td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <div class="clear"></div>

                                <hr>

                                <div class="col-md-12 text-center">

                                    <a class="btn inverse edit-wl-exercise-insert" data-uniqid="<?php echo $uniq_id;?>">Insert new exercise</a>

                                    <div class="btn-group inverse">

                                        <a class="btn edit-wl-exercise-cancel disabled" data-uniqid="<?php echo $uniq_id;?>">Cancel edit</a>

                                        <a class="btn edit-wl-exercise-update disabled" data-uniqid="<?php echo $uniq_id;?>">Update exercise</a>

                                    </div>

                                </div>

                            </form>

                        </div>

                    <?php endif;?>

                <?php else: ?>

                    <?php if($is_my_client && $log[0]->client_id != 0 && $log[0]->user_id !== $current_user->ID):?>

                        <div id="message" class="info"><p>Sorry, you can't see Workout log added by another Personal Trainers.</p></div>

                    <?php else: ?>

                        <div id="message" class="info"><p>Sorry, this Workout log dosen`t shared by Client.</p></div>

                    <?php endif;?>

                <?php endif;?>

            </div><!--.saved-workout-container--> 

        </div>

    <?php endforeach;?>

    </div>

<?php else: ?>

    <div id="message" class="info"><p>Sorry, no workout logs were found.</p></div>

<?php endif;?>