<?php
/*
Template Name: Dashboard
*/
?>

<?php get_header();?>


<?php
$uid = bp_loggedin_user_id();
$member_type = bp_get_member_type($uid);

$next_training = get_next_training_session($uid);

$mm_frontpage_options = get_option("mm_frontpage_options");
$next_training_image = $mm_frontpage_options["next_training_image"];
$std_track_progress_desc = $mm_frontpage_options["std_track_progress_desc"];
$enc_track_progress_desc = $mm_frontpage_options["enc_track_progress_desc"];
$std_activity_desc = $mm_frontpage_options["std_activity_desc"];
$std_getdiet_desc = $mm_frontpage_options["std_getdiet_desc"];
$std_getdiet_image = $mm_frontpage_options["std_getdiet_image"];
$enc_getplans_desc = $mm_frontpage_options["enc_getplans_desc"];
$enc_getplans_image = $mm_frontpage_options["enc_getplans_image"];
$std_techniques_desc = $mm_frontpage_options["std_techniques_desc"];
$std_techniques_image = $mm_frontpage_options["std_techniques_image"];
$std_transformation_desc = $mm_frontpage_options["std_transformation_desc"];
$enc_engage_desc = $mm_frontpage_options["enc_engage_desc"];
$enc_manage_desc = $mm_frontpage_options["enc_manage_desc"];

$transformation_winner = get_transformation_winner();

?>

<style>
.activity-shortcode-title{
display:none;
}
</style>

<div class="next-training-session frontpage_block" style="background: url(<?php echo $next_training_image;?>) no-repeat right top; background-color: #B3B2B8;">
    <div class="next-training-session-text">
        <span>Next training session</span>
        <br>
        <?php if(!empty($next_training)): ?>
            <span><?php echo $next_training;?></span>
        <?php else:?>
            <span>---</span>
        <?php endif;?>
    </div>
</div><!--/.next-trining-session-->

<div id="buddypress">
    <div id="primary">
        <div class="template-dashboard">
            <div class="site-content">

                <div class="row">
                    <div class="col-md-6 pad-r-0">
                        <div class="frontpage_block frontpage_block_g" >
                            <h3 class="frontpage_headers"><?php echo ($member_type == 'standard') ? 'Track Your Progress' : 'Client Progress Updates';?></h3>
                            <p class="frontpage_desc">
                                <?php echo ($member_type == 'standard') ? nl2br($std_track_progress_desc) : nl2br($enc_track_progress_desc);?>
                            </p>
                            <a href="<?php echo ($member_type == 'standard')? '/my-progress' : '/client-progress';?>" class="btn frontpage_btn_link">
                                See more
                            </a>
                        </div>
                    </div>
                    
                    <style>
					#zoombtn{
					cursor: pointer;
					color: #fff;
					padding: 0;
					text-align: center;
					width: 85px;
					margin-top: 35px;
					}
					#zoombtn:hover{
					color:#FE9753;
					}
					</style>

                    <?php $results = ($member_type == 'standard') ? get_bfc_results($uid) : get_bfc_results(get_last_updated_client());?>
                    <?php if($results): ?>
                        
                        <div class="col-md-6 pad-l-0">
                            <div id="<?php echo ($member_type == 'standard') ? 'std' : 'enc';?>_progress_block" class="frontpage_block">
                                
                                <?php if($member_type != 'standard'):?>
                                    <h3 class=lastprogress-name>Last updated by <?php echo get_fullname(get_last_updated_client());?></h3>
                                <?php endif;?>
                                <div id="zoombtn" class="button"><i class="fa fa-search-minus"></i>&nbsp;zoom out</div>
                                
                                <div id="<?php echo ($member_type == 'standard') ? 'myprogress' : 'lastprogress';?>_container" style="width:100%; height:320px;">
                                </div>
                                <div id="<?php echo ($member_type == 'standard') ? 'std' : 'enc';?>_legend" class="plot-legend"></div>
                            </div>
                            <?php 
                                if($member_type == 'standard') 
                                    get_bfc_plot_script($results,'myprogress');
                                else 
                                    get_bfc_plot_script($results,'lastprogress');
                            ?>
                        </div>
                    
                    <?php else: ?>
                        <div class="col-md-6 pad-r-0 frontpage_block">
                            <h3 class="template-title"><?php echo ($member_type == 'standard') ? 'My Previous Results' : 'Last updated Client';?></h3>
                            <div id="message" class="info" style="margin: 0 auto;">
                                <?php if($member_type == 'standard'): ?>
                                    <p>Sorry, no results found. You can add your results <a href="/my-progress/#bfc">here</a></p>
                                <?php else: ?>
                                    <p>Last updated Client</p>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div><!--.row-->

                <div class="row">
                    <div class="col-md-6 pad-r-0">
                        <div id="<?php echo ($member_type == 'standard') ? 'std' : 'enc';?>_activity_block" class="frontpage_block">
                            <h3 class="template-title"><?php echo ($member_type == 'standard') ? 'My trainers activity' : 'My clients activity';?></h3>
                            <?php $activities = ($member_type == 'standard') ? accepted_connection_requests('pt') : accepted_connection_requests('standard');?>
                            <?php if($activities): ?>
                                <?php echo do_shortcode('[activity-stream max=5 pagination=0 display_comments=0 user_id='.implode(',',$activities).']'); ?>
                            <?php else:?>
                                <div id="message" class="info" style="margin: 0 auto;"><p>Sorry, there is no activity streams yet.</p></div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="col-md-6 pad-l-0">
                        <div class="frontpage_block frontpage_block_g">
                            <h3 class="frontpage_headers">News Feed</h3>
                            <p class="frontpage_desc">
                            	<?php 
								$friendidlist = '';
								$friendids = friends_get_friend_user_ids($uid); 
								foreach($friendids as $friendid){
									$friendidlist .= $friendid.',';
								}
								$friendidlist = substr($friendidlist, 0, -1); 
								?>
                                <?php echo do_shortcode('[activity-stream max=5 pagination=0 display_comments=0 user_id='.$friendidlist.']'); ?>        
                            </p>
                            <a href="/members/<?php echo $bp->loggedin_user->userdata->user_login;?>/friends/" class="btn frontpage_btn_link">See more</a>
                        </div>
                    </div>
                </div><!--.row-->

                <div class="row">
                    <div class="col-md-6 pad-r-0">
                        <div class="frontpage_block frontpage_block_g">
                            <h3 class="frontpage_headers">
                                <?php echo ($member_type == 'standard') 
                                ? 'Get a diet that works for your goals' 
                                : 'Get nutrition and training plans to help your clients';?>
                            </h3>
                            <p class="frontpage_desc"><?php echo ($member_type == 'standard') ? nl2br($std_getdiet_desc) : nl2br($enc_getplans_desc);?></p>
                            <a href="<?php echo ($member_type == 'standard') ? '/food-supplement-diary' : '/nutrition-guidelines'?>" class="btn frontpage_btn_link">See more</a>
                        </div>
                    </div>
                    <div class="col-md-6 pad-l-0" >
                        <div id="std_getdiet_block" class="frontpage_block" style="background: url(<?php echo ($member_type == 'standard') ? $std_getdiet_image : $enc_getplans_image;?>) no-repeat scroll top; background-size: cover;">
                        </div>
                    </div>
                </div><!--.row-->

                <div class="row">
                    <?php if($member_type == 'standard'): ?>
                        <div class="col-md-6 pad-r-0">
                            <div id="std_techniques_block" class="frontpage_block" style="background: url(<?php echo $std_techniques_image;?>) no-repeat scroll top; background-size: cover;"></div>
                        </div>
                    <?php else: ?>

                        <div class="col-md-6 pad-r-0">
                        	
                            <?php $today = get_today_calendar($uid);?>
                            <div id="enc_engage_block" class="frontpage_block">
                            	<h3 class="frontpage_headers" style="padding-left:20px;">Workout Log
                            	</h3>
                                <table class="calendar-table table toggle-default footable">
                                    <thead>
                                        <tr>
                                            <th data-toggle="true" width="33%" id="today_date"></th>
                                            <th width="33%">Client</th>
                                            <th data-hide="phone,tablet" width="33%">Workouts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0; $i<16; $i++):?>
                                        <tr>
                                            <td class="training_time">
                                                <?php
                                                    if($i<6){
                                                        $hour = intval(6+$i);
                                                        echo $hour.':00 a.m.';
                                                    }else if($i==6){
                                                        echo '12:00 p.m.';
                                                    }else{
                                                        echo ($i-6).':00 p.m.';
                                                    }
                                                ?>
                                            </td>
                                            <td class="training_person">
                                                <?php if(isset($today[$i+1])):?>
                                                    <?php echo get_fullname($today[$i+1]->person_id);?>
                                                <?php endif;?>
                                            </td>
                                            <td class="training_workout">
                                                <?php if(isset($today[$i+1])):?>
                                                    <?php echo $today[$i+1]->workout;?>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                       <?php endfor;?>
                                    </tbody>
                                </table>
                                <a href="workout-log/" style="margin-left:20px; position:relative;" class="btn frontpage_btn_link">See more</a>
                            </div>
                        </div>
                    <?php endif;?>
                    
                    <div class="col-md-6 pad-l-0">
                        <div class="frontpage_block frontpage_block_g">
                            <h3 class="frontpage_headers">
                                <?php echo ($member_type == 'standard') ? 'Learn new training techniques' : 'Engage with your clients';?>
                            </h3>
                            <p class="frontpage_desc">
                                <?php echo ($member_type == 'standard') ? nl2br($std_techniques_desc) : nl2br($enc_engage_desc);?>
                            </p>
                            <?php if($member_type == 'standard'): ?>
                                <a href="/training-plans" class="btn frontpage_btn_link">See more</a>
                            <?php elseif($member_type == 'pt'): ?>
                                <a href="/trainer-clients" class="btn frontpage_btn_link">See more</a>
                            <?php elseif($member_type == 'gym'): ?>
                                <a href="/gym-members" class="btn frontpage_btn_link">See more</a>
                            <?php endif;?>
                        </div>
                    </div>
                </div><!--/.row-->

                <div class="row">
                    <div class="col-md-6 pad-r-0">
                        <div class="frontpage_block frontpage_block_g">
                            <h3 class="frontpage_headers">
                                <?php echo ($member_type == 'standard') ? 'The Biggest Transformation' :'Manage your clients'?>
                            </h3>
                            <p class="frontpage_desc">
                                <?php echo ($member_type == 'standard') ? nl2br($std_transformation_desc) : nl2br($enc_manage_desc);?>
                            </p>
                            <a href="<?php echo ($member_type == 'standard') ? '/members/#standard' : '/client-progress'?>" class="btn frontpage_btn_link">See more</a>
                        </div>
                    </div>
                    <?php if($member_type == 'standard'): ?>
                        <div class="col-md-6 pad-l-0">
                            <div id="std_transformation_block" class="frontpage_block">
                                <?php if($transformation_winner):?>
                                    <div id="clients-bigest-transformation" class="text-center">
                                        <h3 class="template-subtitle">The biggest transformation - Leader</h3>
                                        <p>
                                            <a href="<?php bp_member_permalink($transformation_winner['user_id']); ?>" class="result-title">
                                                <?php  echo get_avatar($transformation_winner['user_id'],256); ?> <?php echo $transformation_winner['firstname'].' '.$transformation_winner['lastname']; ?>
                                            </a>
                                        </p>
                                        <p><strong>Weight: </strong><?php echo $transformation_winner['last_weight']?> kg</p>
                                        <p><strong>Bodyfat: </strong><?php echo $transformation_winner['last_bodyfat']?>% - <?php echo ($transformation_winner['last_bodyfat'] < $transformation_winner['first_bodyfat']) ? 'Down' : 'Up'; ?> from <?php echo $transformation_winner['first_bodyfat']?>%</p>
                                        <p><strong>Fat mass: </strong><?php echo $transformation_winner['last_fatmass']?> kg</p>
                                        <p><strong>Lean Mass: </strong><?php echo $transformation_winner['last_leanmass']?> kg</p>
                                        <p><strong>Mirror Muscles Category: </strong><?php echo $transformation_winner['last_category']?></p>           
                                    </div>
                                <?php else: ?>
                                    <div id="message" class="info" style="margin: 0 auto;"><p>Sorry, there is no leader yet.</p></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>              
                        <div class="col-md-6 pad-l-0">
                            <?php
                                $clients = get_top5_clients_by_bfc();
                                $counter = 1;
                            ?>
                            <div id="enc_manage_block" class="frontpage_block">
                                <?php if($clients): ?>
                                    <h3 class="template-title">My Clients Best BFC Results</h3>
                                    <table class="clients_table">
                                        <tbody>
                                            <?php foreach($clients as $key=>$client): ?>
                                                <tr>
                                                    <td rowspan="3" class="my_clients_counter text-center"><a href="<?php  echo bp_core_get_user_domain($client['user_id']);?>"><?php echo $counter;?>.</a></td>
                                                    <td rowspan="3" class="text-center my_clients_avatar">
                                                        <a href="<?php  echo bp_core_get_user_domain($client['user_id']);?>"><?php  echo get_avatar($client['user_id'],256); ?></a>
                                                    </td>
                                                    <td colspan="3" class="text-left my_clients_link">
                                                        <a href="<?php echo bp_core_get_user_domain($client['user_id']);?>"><?php echo get_fullname($client['user_id']); ?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><p><strong>Weight: </strong><?php echo $client['last_weight'].$client['last_units']; ?></p></td>
                                                    <td><p><strong>Fatmass: </strong><?php echo $client['last_fatmass'].$client['last_units'];?></p></td>
                                                </tr>
                                                <tr>
                                                    <td><p><strong>Leanmass: </strong><?php echo $client['last_leanmass'].$client['last_units'];?></p></td>
                                                    <td><p><strong>Bodyfat: </strong>
                                                        <?php if($client['last_bodyfat'] == $client['first_bodyfat']):?>
                                                            <?php echo $client['last_bodyfat'].'%(only one result)';?>
                                                        <?php else:?>
                                                            <?php echo $client['last_bodyfat'].'% - '. ( ($client['last_bodyfat'] < $client['first_bodyfat']) ? 'Down' : 'Up' ).' from '.$client['first_bodyfat'].'%';?>
                                                        <?php endif;?>
                                                    </p></td>
                                                </tr>
                                            <?php ++$counter;?>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                <?php else:?>
                                    <h3 class="template-title">My Clients Best BFC Results</h3>
                                    <div id="message" class="info" style="margin: 0 auto;"><p>Sorry, no results found.</p></div>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div><!--/.row-->

            <?php if($member_type == 'standard'):?>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery("#std_activity_block").niceScroll({
                            'cursorcolor': "#4dcade",
                            'cursorborder': '1px solid #4dcade',
                            'cursorwidth': "8px",
                            'zindex': 4,
                            'bouncescroll': true,
                            railalign: 'right'
                        });
                    });
                </script>
            <?php else:?>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        var d = new Date();
                        var today = ('0' + d.getDate()).slice(-2) + '.' + ('0' + (d.getMonth()+1)).slice(-2) + '.' + d.getFullYear();
                        jQuery('#today_date').text(today);

                        jQuery("#enc_activity_block, #enc_engage_block").niceScroll({
                            'cursorcolor': "#4dcade",
                            'cursorborder': '1px solid #4dcade',
                            'cursorwidth': "8px",
                            'zindex': 4,
                            'bouncescroll': true,
                            railalign: 'right'
                        });
                    });
                </script>
            <?php endif;?>
            </div><!--/.site-content-->
        </div><!--/.template-dashboard-->
    </div>
</div>



<?php get_footer(); ?>