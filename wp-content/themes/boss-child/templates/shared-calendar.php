<div class="template-calendar">
    <div class="site-content">
        <?php 
            $fullname = get_fullname($student_id);
        ?>
        <h3 class="template-title"><?php echo $fullname;?> Training Schedule</h3>
        
        <div class="col-md-12 text-center">
            <?php if(is_user_logged_in() && wp_is_mobile()):?>
                <?php print_video_container();?>
            <?php endif;?>
        </div>

        <div class="col-md-12">
            <table id="calendar-table" class="calendar-table table toggle-default footable">
                <thead>
                    <tr>
                        <th data-toggle="true" width="33%">
                            <?php echo $_GET['date'];?>
                        </th>
                        <th data-hide="phone,tablet" width="33%">Trainers</th>
                        <th data-hide="phone,tablet" width="33%">Workouts</th>
                    </tr>
                </thead>
                
                <tbody>
                <?php for($i=1; $i<=24; $i++):?>
                    <?php $time = ($i<=11) ? ($i).' a.m.' : (($i==12) ? $time = '12 p.m.' : ($i-12).' p.m.'); ?>
                      <tr>
                        <td class="training-time">
                            <?php echo $time;?>
                            <?php
                                echo ($today[$i]->status == 'pending') ? '<p class="pending-invitation"><small>Pending invitation</small>' : '';
                                echo ($member_type == 'enchanced' && $today[$i]->status == 'pending') ? '<p class="pending-invitation"><small>Pending ivitation</small></p>' : '';
                                echo ($today[$i]->status == 'accepted' && $today[$i]->person_id != -1) ? '<p class="accepted-invitation"><small>Accepted invitation</small><br><button type="button" class="btn refuse-invitation">Refuse</button></p>' : '';
                                echo ($today[$i]->status == 'accepted' && $today[$i]->person_id == -1) ? '<p class="accepted-invitation"><small>Accepted invitation</small></p>' : '';
                            
                            ?>
                        </td>
                        <td class="training-person">
                            <?php echo get_fullname($today[$i]->person_id);?>
                        </td>
                        <td class="training-workout">
                            <?php echo $today[$i]->workout;?>
                        </td>
                    </tr>
                <?php endfor;?>
                </tbody>
            </table>
        </div><!--.calendar-container-->
    </div>
</div>