<?php

    global $current_user;

    $member_type = get_query_var('member_type');

    $is_my_client = get_query_var('is_my_client');

    $adv_workout_logs = get_query_var('adv_workout_logs');

?>



<?php if($workout_logs):?>

    <div class="mm-accourdion">

    <?php foreach($adv_workout_logs as $uniq_id=>$log):?>  

        

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

            <div class="saved-wl-container text-center advanced" data-uniqid="<?php echo $uniq_id;?>">

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

						.imagediv5 tr td{

						cursor:move;

						}

						</style>

						<script>

						jQuery(document).ready(function(){

								jQuery(".imagediv5").sortable({ tolerance: 'pointer' });            

						});

						function submitsaveformadv(id){

							jQuery.post(ajaxurl, jQuery('#'+id).serialize(),function(data){

								 jQuery('#successmessage'+id).show( "slow" );

								 setTimeout(function() {

									jQuery('#successmessage'+id).hide( "fast" );

								 }, 7000);

							});

						}

						</script>

                        <div class="workout-log-share-container">

                        	<p id="successmessage<?php echo $uniq_id; ?>" style="display:none; color:#003300;">Order saved successfully!</p>

                            <form name="saveorderformadv" id="<?php echo $uniq_id; ?>" method="post" action="">

                            <input type="hidden" name="action" value="saveorderformadv" />

                            <input type="button" name="savesubmit" value="Save Order" onclick="submitsaveformadv('<?php echo $uniq_id;?>');" style="float:right; margin-bottom:20px;" />

                            <table class="workout-table-exercises table toggle-default footable" data-uniqid="<?php echo $uniq_id;?>">

                                <thead>

                                    <tr>

                                        <th>Order</th>

                                        <th>Week</th>

                                        <th>Exercise</th>

                                        <th data-hide="phone">Tempo</th>

                                        <th data-hide="phone">Repeats</th>

                                        <th data-hide="phone">Load</th>

                                        <th data-hide="phone">Rest</th>

                                        <?php echo (!$is_my_client && $log[0]->client_id != $current_user->ID) ? '<th data-hide="phone" class="actions">Actions</th>' : '';?>

                                    </tr>

                                </thead>

                                <tbody class="imagediv5">

                                    <?php foreach ($log as $key => $value): ?>

                                        <tr class="me3" data-id="<?php echo $value->id;?>">

                                        	<input type="hidden" name="logid[]" value="<?php echo $value->id;?>" />

                                            <td><?php echo $value->exercise_order;?></td>

                                            <td><?php echo $value->week;?></td>

                                            <td><?php echo stripslashes_deep($value->exercise_name);?></td>

                                            <td><?php echo $value->tempo;?></td>

                                            <td><?php echo implode('<br>',json_decode($value->repeats));?></td>

                                            <td><?php echo implode('<br>',json_decode($value->loads));?></td>

                                            <td><?php echo implode('<br>',json_decode($value->rest));?></td>

                                            <?php echo (!$is_my_client && $log[0]->client_id != $current_user->ID) 

                                                    ? '<td class="actions">

                                                        <span class="hidden-exercise-name">'.$value->exercise_name.'</span>

                                                        <i class="fa fa-lg fa-edit edit-awl-exercise" data-id="'.$value->id.'" data-client-id="'.$value->client_id.'" data-week="'.$value->week.'" data-exercise-order="'.$value->exercise_order.'" data-exercise-id="'.$value->exercise_id.'" data-tempo="'.$value->tempo.'" data-repeats="'.implode(',',json_decode($value->repeats)).'" data-loads="'.implode(',',json_decode($value->loads)).'" data-rest="'.implode(',',json_decode($value->rest)).'"></i>

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

                            <h3 class="template-subtitle">Change <?php echo stripslashes_deep($log[0]->name);?></h3>

                            <form class="edit-wl-form select-exercise-container wl-form" data-uniqid="<?php echo $uniq_id;?>" onkeypress="return event.keyCode != 13;">



                                <div class="form-group text-center">

                                    <label>Select week: </label><br>

                                    <div class="btn-group adv exerciseweeknums">

                                        <button data-week="1" type="button" class="btn active">Week 1</button>

                                        <button data-week="2" type="button" class="btn">Week 2</button>

                                        <button data-week="3" type="button" class="btn">Week 3</button>

                                        <button data-week="4" type="button" class="btn">Week 4</button>

                                    </div>

                                </div>



                                <div class="text-center">

                                    <label>Select exercise: </label>

                                    <div class="form-group dsfdsfsd">

                                        <?php echo workout_exercise_selectbox( 'add-sawl-exercisename-'.$log[0]->uniq_id, 'add-sawl-exercisename-'.$log[0]->uniq_id, 'exercisename form-group', true);?>

                                    </div>

                                    <div id="error-add-sawl-exercisename-<?php echo $log[0]->uniq_id;?>"></div>

                                    

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

                                    <p id="add-swl-show-exercise-details-<?php echo $log[0]->uniq_id;?>" class="show-exercise-details"><small>Show/Hide exercise details <i class="fa fa-eye"></i></small></p>

                                    <hr>



                                    <small><label style="font-weight: 400"><input type="checkbox" id="add-sawl-exercisename-custom-radio-<?php echo $log[0]->uniq_id;?>" class="add-wl-exercisename-custom-radio"> or set custom Exercise name</label></small>

                                    <div class="form-group">

                                      <input id="add-sawl-exercisename-custom-<?php echo $log[0]->uniq_id;?>" name="add-sawl-exercisename-custom-<?php echo $log[0]->uniq_id;?>" type="text" class="exercisename-custom form-control" placeholder="Custom exercise name..." disabled="true" required>

                                    </div>

                                    <div id="error-add-sawl-exercisename-custom-<?php echo $log[0]->uniq_id;?>"></div>

                                    

                                    

                                    <div class="form-group form-inline col-md-6 text-right">

                                        <label>Exercise Order:</label>

                                        <select id="add-awl-exercise-order" class="exerciseorder" required>

                                            <?php foreach (range('A','Z') as $key => $letter):?>

                                                <?php for($n=1;$n<5;$n++):?>

                                                    <option value="<?php echo $letter.''.$n?>"><?php echo $letter.''.$n?></option>

                                                <?php endfor;?>

                                            <?php endforeach;?>

                                        </select>

                                    </div>

                                    <div id="error-add-awl-exercise-order"></div>



                                    <div class="form-group form-inline col-md-6 text-left">

                                        <label>Exercise Tempo:</label>

                                        <input id="add-awl-exercise-tempo" name="add-awl-exercise-tempo" type="number" class="exercisetempo" min="1" max="9999" step="1" value="1" required>

                                    </div>

                                    <div id="error-add-awl-exercise-tempo"></div>



                                    <div class="form-group form-inline">

                                        <label>Set exercise sets:</label>

                                        <select class="adv-exercisesets">';

                                            <?php for ($n = 1; $n < 11; ++ $n): ?>

                                                <option value="<?php echo $n;?>"><?php echo $n;?></option>

                                            <?php endfor;?>

                                        </select>

                                    </div>



                                    <table id="add-awl-repeats-weight-table" class="repeats-weight-table">

                                        <thead>

                                            <th width="25%">Set</th>

                                            <th width="25%">Repeats</th>

                                            <th width="25%">Load</th>

                                            <th width="25%">Rest Period</th>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td>Set 1</td>

                                                <td><input class="exerciserepeats" type="number" min="1" max="9999" step="1" value="1" required></td>

                                                <td><input class="exerciseload" type="number" min="1" max="9999" step="1" value="1" required></td>

                                                <td><input class="exerciserest" type="number" min="1" max="600" step="1" value="1" required></td>

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

                        <div id="message" class="info"><p>Sorry, you can't see Advanced Workout logs added by another Personal Trainers.</p></div>

                    <?php else: ?>

                        <div id="message" class="info"><p>Sorry, this Advanced Workout log dosen`t shared by Client.</p></div>

                    <?php endif;?>

                <?php endif;?>

            </div><!--.saved-workout-container--> 

        </div>

    <?php endforeach;?>

    </div>

<?php else: ?>

    <div id="message" class="info"><p>Sorry, no workout logs were found.</p></div>

<?php endif;?>