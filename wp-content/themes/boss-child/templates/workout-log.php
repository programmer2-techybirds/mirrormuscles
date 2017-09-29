<?php

/*

Template Name: Workout log

*/

get_header();

?>



<?php



    $member_type = bp_get_member_type($current_user->ID);

    set_query_var( 'member_type', $member_type);



    //if trainer viewing shared logs of clie

    if (isset($_GET['show-workout-logs']) && !empty($_GET['u']) && $member_type == 'pt' ) {

        $trainer_id = $current_user->ID;

        $client_id = $_GET['u'];

        $is_my_client = user_is_connected($client_id);

    }else{

        $client_id = $current_user->ID;

        $is_my_client = false;

    }



    $workout_logs = get_workout_logs($client_id);

	$workout_logs_sample = get_workout_logs_sample($client_id);

    $adv_workout_logs = get_adv_workout_logs($client_id);

    set_query_var( 'is_my_client', $is_my_client);

	set_query_var( 'workout_logs_sample', $workout_logs_sample);

    set_query_var( 'workout_logs', $workout_logs);

    set_query_var( 'adv_workout_logs', $adv_workout_logs);



    $muscles = get_workout_muscles();



?>

    

<?php if($member_type == 'standard' || $member_type == 'pt' || $member_type == 'gym' ): ?>

<div id="buddypress">

<div id="primary">

<div class="template-workout-log">

	<h3 class="template-title"><?php echo($is_my_client) ? get_fullname($client_id) : ''?> Workout log</h3>

	
	<div id="workout-log-tabs" class="mm-tabs">

        <ul>

        	<?php if($current_user->ID != 1){ ?>

            

            <?php if(!$is_my_client):?>

                <li><a href="#add-workout-log">Add <br>workout log</a></li>

            <?php endif;?>

            

            <li><a href="#saved-workout-logs"><?php echo($is_my_client) ? get_fullname($client_id) : 'Saved'?><br> workout logs</a></li>

            

            <?php if(!$is_my_client):?>

                <li><a href="#add-advanced-workout-log">Add advanced <br>workout log</a></li>

            <?php endif;?>



            <li><a href="#saved-advanced-workout-logs"><?php echo($is_my_client) ? get_fullname($client_id) : 'Saved'?> advanced <br>workout logs</a></a></li>

            

            <?php } ?>

            

            <?php if($current_user->ID == 1){ ?>

            <li><a href="#add-sample-workout-logs">Add sample <br>workout log</a></li>

            <?php } ?>

            

            <li><a href="#sample-workout-logs"> Sample <br>workout logs</a></li>

            

        </ul>





		<?php if($current_user->ID != 1){ ?>

        <?php if(!$is_my_client):?>

            <div id="add-workout-log">

                <?php get_template_part('templates/workout-log', 'add');?>

            </div><!--#add-workout-form-->

        <?php endif;?>



        <div id="saved-workout-logs">

            <?php get_template_part('templates/workout-log', 'saved');?>

        </div><!--./#saved-workout-logs-->



        <?php if(!$is_my_client):?>

            <div id="add-advanced-workout-log">

                <?php get_template_part('templates/workout-log', 'add-advanced');?>

            </div>

        <?php endif;?>



        <div id="saved-advanced-workout-logs">

            <?php get_template_part('templates/workout-log', 'saved-advanced');?>

        </div>

        

        <?php } ?>

        

        <?php if($current_user->ID == 1){ ?>

        <div id="add-sample-workout-logs">

            <?php get_template_part('templates/workout-log', 'add-sample');?>

        </div>

        <?php } ?>

        

        <div id="sample-workout-logs">

            <?php get_template_part('templates/workout-log', 'sample');?>

        </div>

        

    </div>

    <div class="clear"></div>

    <hr>

    <div class="clear"></div>


    <h3 class="template-subtitle">Muscles overview</h3>

    <div class="col-md-6 col-xs-12 muscles-overview text-center">

        <div class="muscle-front-background"></div>

        <div class="muscle-pickers front">

        <ul class="text-center">
 

            <?php foreach ($muscles as $key => $muscle):?>

                <?php if($muscle['is_front']==1):?>

                    <li class="muscle-picker front" data-muscle="<?php echo $muscle['id']?>"><?php echo $muscle['name'];?></li>

                <?php endif;?>

            <?php endforeach;?>

        </ul>

        </div>

    </div>

    <div class="col-md-6 col-xs-12 muscles-overview">

        <div class="muscle-back-background"></div>

        <div class="muscle-pickers back">

        <ul class="text-center">

           <?php foreach ($muscles as $key => $muscle):?>

                <?php if($muscle['is_front']==0):?>

                    <li class="muscle-picker back" data-muscle="<?php echo $muscle['id']?>"><?php echo $muscle['name'];?></li>

                <?php endif;?>

            <?php endforeach;?>
        </ul>

        </div>

    </div>

</div>

</div>                         

</div>

<?php else: //wp_redirect(home_url()); 

endif;?>



<?php get_footer(); ?>