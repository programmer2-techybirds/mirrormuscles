<?php

    $member_type = get_query_var('member_type');

    $is_my_client = get_query_var('is_my_client');

?>

<style>

#imagediv77 tr td{

cursor:move;

}

</style>

<script>

jQuery(document).ready(function(){

        jQuery("#imagediv77").sortable({ tolerance: 'pointer' });

        jQuery('#imagediv77 tr').css("cursor","move");              

  });

</script>



<div id="add-wl-container">

    <form name="add-swl-form" id="add-swl-form" class="select-exercise-container wl-form" action="https://staging-uat.mirrormuscles.org/wp-content/plugins/mirror-muscles/handler.php" method="post" enctype="multipart/form-data">

        

        <div class="form-group-lg text-center">

            <label>Workout Log Name:</label>

            <input type="text" class="form-control workout-log-name" name="add-swl-name" id="add-swl-name" placeholder="Workout Log Name" required>

            <p><small><i class="fa fa-info-circle"></i> Usually a description about what parts are trained, like "Arms" or "Pull Day"</small></p>

            <div id="error-add-swl-name"></div>

        </div>



        <div class="form-group text-center">

            <label>Select weekday:</label><br>

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

            <label>Select exercise:</label>

            <div class="form-group">

                <?php echo workout_exercise_selectbox( 'add-wl-exercisename', 'add-wl-exercisename', 'exercisename form-group', true);?>

            </div>

            <div id="error-add-swl-exercisename"></div>

            

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

            <p id="add-swl-show-exercise-details" class="show-exercise-details"><small>Show/Hide exercise details <i class="fa fa-eye"></i></small></p>

            <hr>





            <small><label style="font-weight: 400"><input type="checkbox" id="add-swl-exercisename-custom-radio" class="add-wl-exercisename-custom-radio"> or set custom Exercise name</label></small>

            <div class="form-group">

              <input id="add-swl-exercisename-custom" name="add-swl-exercisename-custom" type="text" class="exercisename-custom form-control" placeholder="Custom exercise name..." disabled="true" required>

            </div>

            <div id="error-add-swl-exercisename-custom"></div>

            

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

                    <th width="35%">Reps</th>

                    <th width="35%">Weight<br>(optional)</th>

                </thead>

                <tbody>

                    <tr>

                        <td>Set 1</td>

                        <td><input class="exerciserepeats" type="number" min="1" max="99" step="1" value="1" required></td>

                        <td><input class="exerciseweight" type="number" min="0" max="1000" step="1" value="0" required></td>

                    </tr>

                </tbody>

            </table>

        </div>

        <div class="clear"></div>

        <hr>



        <div class="col-md-12 text-center">

            <button type="button" class="add-day-exercise btn inverse">Add exercise</button>

        </div>

        <div class="clear"></div>

        <hr>



        <div class="col-md-12 text-center">

            <table id="new-workout-table-exercises" class="workout-table-exercises table toggle-default footable">

                <thead>

                <tr>

                    <th data-hide="phone">Day</th>

                    <th data-toggle="true">Exercise</th>

                    <th data-hide="phone">Reps</th>

                    <th >Action</th>

                </tr>

                </thead>

                <tbody id="imagediv77"></tbody>

            </table>

        </div>

        <div class="col-md-12 text-center">

            <button type="button" class="btn" id="save-new-swl" name="save-new-swl">Save Workout Log</button>

            <p id="successmessage77" style="display:none; color:#003300;">Workout log saved successfully!</p>

        </div>

    </form><!--./#add-wl-form-->

</div><!--#add-wl-container-->