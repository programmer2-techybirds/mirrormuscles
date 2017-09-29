<?php
    $member_type = get_query_var('member_type');
    $is_my_client = get_query_var('is_my_client');
?>
<style>
#imagediv2 tr td{
cursor:move;
}
</style>
<script>
jQuery(document).ready(function(){
        jQuery("#imagediv2").sortable({ tolerance: 'pointer' });
        jQuery('#imagediv2 tr').css("cursor","move");              
  });
</script>

<div id="add-wl-container">
    <form name="add-awl-form" id="add-awl-form" class="select-exercise-container wl-form" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
        
        <div class="form-group-lg text-center">
            <label>Advanced Workout Log Name:</label>
            <input type="text" class="form-control workout-log-name" name="add-awl-name" id="add-awl-name" placeholder="Advanced Workout Log Name" required>
            <p><small><i class="fa fa-info-circle"></i> Usually a description about what parts are trained, like "Arms" or "Pull Day"</small></p>
            <div id="error-add-awl-name"></div>
        </div>
        
        <?php if($member_type == 'pt'): ?>
            <div class="form-group text-center">
                <label>Advanced Workout Log for:</label><br>
                <?php $clients = accepted_connection_requests('standard');?>
                <select id="add-awl-client-id" class="workout-client-id form-control">
                    <option value="0">-</option>
                    <?php foreach ($clients as $key => $client_id):?>
                        <option value="<?php echo $client_id;?>"><?php echo get_fullname($client_id);?></option>
                    <?php endforeach;?>
                </select>
            </div>
        <?php endif;?>

        <div class="text-center">
            <label>Select exercise:</label>
            <div class="form-group">
                <?php echo workout_exercise_selectbox( 'add-awl-exercisename', 'add-awl-exercisename', 'exercisename form-group', true);?>
            </div>
            <div id="error-add-awl-exercisename"></div>
            
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
                            <td><label>Video:</label></td>
                            <td class="exercise-video"></td>
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
            <p id="add-awl-show-exercise-details" class="show-exercise-details"><small>Show/Hide exercise details <i class="fa fa-eye"></i></small></p>
            <hr>

            <small><label style="font-weight: 400"><input type="checkbox" id="add-awl-exercisename-custom-radio" class="add-wl-exercisename-custom-radio"> or set custom Exercise name</label></small>
            <div class="form-group">
              <input id="add-awl-exercisename-custom" name="add-awl-exercisename-custom" type="text" class="exercisename-custom form-control" placeholder="Custom exercise name..." disabled="true" required>
            </div>
            <div id="error-add-awl-exercisename-custom"></div>
            
            <div class="form-group form-inline col-md-6 text-right text-center-xs text-center-sm">
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

            <div class="form-group form-inline col-md-6 text-left text-center-xs text-center-sm">
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

        <div class="col-md-12 text-center">
            <button type="button" class="add-week-exercise btn inverse">Add exercise</button>
        </div>
        <div class="clear"></div>
        <hr>

        <div class="col-md-12 text-center">
            <table id="new-adv-workout-table-exercises" class="workout-table-exercises table toggle-default footable">
                <thead>
                <tr>
                    <th data-hide="phone">Order</th>
                    <th data-hide="phone">Week</th>
                    <th>Exercise</th>
                    <th data-hide="phone">Tempo</th>
                    <th data-hide="phone">Repeats</th>
                    <th data-hide="phone">Load</th>
                    <th data-hide="phone">Rest</th>
                    <th data-hide="phone">Action</th>
                </tr>
                </thead>
                <tbody id="imagediv2"></tbody>
            </table>
        </div>
        
        <div class="form-group text-center">
            <label>Select week:</label><br>
            <div class="btn-group exerciseweeknums">
                <button data-week="1" type="button" class="btn active">Week 1</button>
                <button data-week="2" type="button" class="btn">Week 2</button>
                <button data-week="3" type="button" class="btn">Week 3</button>
                <button data-week="4" type="button" class="btn">Week 4</button>
            </div>
        </div>
        
        <div class="col-md-12 text-center">
            <button type="button" class="btn" id="save-new-awl" name="save-new-awl">Save Workout Log</button>
            <p id="successmessage33" style="display:none; color:#003300;">Workout log saved successfully!</p>
        </div>
    </form><!--./#add-wl-form-->
</div><!--#add-wl-container-->