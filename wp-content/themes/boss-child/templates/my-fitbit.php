<?php
/*
Template Name: My Fitbit
*/
get_header();?>

<?php

$type = bp_get_member_type($current_user->ID);

if( $type == 'standard')
    $is_shared = get_user_meta($bp->loggedin_user->id,'fitbit_shared');

require WP_PLUGIN_DIR.'/mirror-muscles/fitbit/fitbitphp.php';

$mm_fitbit_options = get_option("mm_fitbit_options");
$mm_fitbit_client_id = $mm_fitbit_options["mm_fitbit_client_id"];
$mm_fitbit_consumer_key = $mm_fitbit_options["mm_fitbit_consumer_key"];
$mm_fitbit_consumer_secret = $mm_fitbit_options["mm_fitbit_consumer_secret"];


$connect_url = 'https://www.fitbit.com/oauth2/authorize?response_type=code&client_id='.$mm_fitbit_client_id.'&redirect_uri=';
$connect_url .= rawurlencode(WP_PLUGIN_URL.'/mirror-muscles/fitbit.php');
$connect_url .= '&scope='.rawurlencode('activity profile sleep');

$limit_is_reached = false;

$fitbit = new FitBitPHP($mm_fitbit_consumer_key, $mm_fitbit_consumer_secret);

if($fitbit->sessionStatus() !=0 ){
    $fitbit->initSession(home_url().'/my-fitbit');
    $fitbit->setResponseFormat('json');

    try {
        
        $ratelimit = $fitbit->getRateLimit();
        
        if($ratelimit){

            $profile = $fitbit->getProfile();
            update_user_meta($bp->loggedin_user->id,'fitbit_user_id', $profile->user->encodedId);
   
        }

    } catch (Exception $e) {
        if($e->httpcode==429)
            $limit_is_reached = true;
    }

}

?>

<?php if($type == ('standard' || 'pt') ): ?>
<div id="buddypress">
<div id="primary">
<div class="template-my-fitbit">
    <div class="site-content">
        
        <h3 class="template-title">My Fitbit</h3>

        <div class="col-md-12 text-center">
            <?php if(is_user_logged_in() && wp_is_mobile()):?>
                <?php print_video_container();?>
            <?php endif;?>
        </div>

        <div class="col-md-12">
            <img style="max-width: 100%" src="<?php echo get_stylesheet_directory_uri();?>/images/FitbitLogo.png"> 
        </div>
        <?php if(!$limit_is_reached): ?>
            <div class="clear"></div>
            <?php if( $fitbit->sessionStatus() == 2 ): ?>
                <hr>
                <div class="col-md-12 text-center">
                    <p>Connected as:
                        <a class="fitbit-profile-link" href="<?php echo 'https://www.fitbit.com/user/'.$profile->user->encodedId;?>" target="_blank"><?php echo $profile->user->fullName;?>
                            <img src="<?php echo $profile->user->avatar?>">
                        </a>
                    </p>
                    <?php if( $type== 'standard' ): ?>
                        <div class="btn-group inverse">
                            <a class="btn" href="<?php echo WP_PLUGIN_URL.'/mirror-muscles/fitbit.php?reset_fitbit_session=1'?>">
                                <i class="fa fa-unlink"> Disconect</i></a>
                            <a class="btn <?php echo ($is_shared) ? 'unshare-fitbit-account' : 'share-fitbit-account';?>">
                                <i class="fa fa-share-alt"> <?php echo ($is_shared) ? 'Unshare link for Trainers/GYMs' : 'Share link for Trainers/GYMs';?></i>
                            </a>
                        </div>
                        <p><small><i class="fa fa-info-circle"></i> Once you have shared a link to your Fitbit results, please, go to the privacy settings in the personal <a href="https://www.fitbit.com/user/profile/privacy" target="_blank">Fitbit profile</a> and select parameters which you want to share</small></p>
                    <?php elseif( $type== 'enchanced' ):?>
                        <a class="btn inverse" href="<?php echo WP_PLUGIN_URL.'/mirror-muscles/fitbit.php?reset_fitbit_session=1'?>">
                            <i class="fa fa-unlink"> Disconect</i>
                        </a>
                    <?php endif;?>
                </div>
                <hr>
            <?php endif;?>
            <?php if( $fitbit->sessionStatus() == 0 ): ?>
                <hr>
                <div class="col-md-12 text-center">
                    <a href="<?php echo $connect_url;?>" class="btn inverse"><i class="fa fa-link"></i> Connect</a>
                </div>
                <hr>
            <?php endif;?>
            <div class="clear"></div>
            
            <?php if( $fitbit->sessionStatus() == 2 ): ?>
                <?php $types = array('calories','steps', 'sleep');?>
                <div class="rate-limit col-md-12 pull-right text-right">
                    <small><i class="fa fa-info-circle"></i> <span><?php echo (string)$ratelimit->viewer;?></span> 
                    <u rel="popover" data-content="You can make 150 requests per hour for each user that has authorized to access your data. All hourly limits are reset at the start of the new hour. Some request types are: request Fitbit profile(on page loading), request current Rate limit(on page loading), request for burned calories, request for minutes asleep, etc." data-html="true">requests remains</u> for current hour.</small>
                </div>
                <div class="clear"></div>

                <div id="fitbit-tabs" class="mm-tabs fitbit">
                    <ul>
                        <?php for($i=0; $i<count($types); $i++): ?>
                            <li><a href="#fitbit-<?php echo $types[$i];?>"><?php echo ucfirst($types[$i]);?></a></li>
                        <?php endfor;?>
                    </ul>
                    <?php for($i=0; $i<count($types); $i++): ?>
                        <div id="fitbit-<?php echo $types[$i];?>">
                            <div class="text-center">
                                <div class="form-group">
                                    <label>Choose date:</label><br>
                                    <input type="text" class="datepicker day-datepicker fitbit-datepicker" data-type="<?php echo $types[$i];?>"><i class="fa fa-spinner fa-spin fitbit-loading" data-type="<?php echo $types[$i];?>"></i>
                                </div>
                            </div>
                            <div id="fitbit-<?php echo $types[$i];?>-sharing-container" class="fitbit-sharing-container">
                                <div id="fitbit-<?php echo $types[$i];?>-print-container" class="fitbit-print-container">
                                    <div id="fitbit-<?php echo $types[$i];?>-results"></div>
                                    <div id="fitbit-<?php echo $types[$i];?>-plot" class="fitbit-plot"></div>
                                    <div id="fitbit-<?php echo $types[$i];?>-plot-legend" class="plot-legend"></div>
                                </div>
                                <div class="col-md-12 form-group text-center">
                                    <div class="btn-group inverse">
                                        <button class="btn fitbit-print"><i class="fa fa-lg fa-print"></i>Print</button>
                                        <button class="btn fitbit-share"><i class="fa fa-user-plus"></i>Share to Wall</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor;?>
                </div>
            <?php endif;?>
        <?php else: ?>
            <div class="clear"></div>
            <br>
            <div id="message" class="info"><p>Sorry, your Fitbit requests limit is reached. Try later.</p></div>
            <p class="text-center">
                <span>You can disconect and connect with another Fitbit account, if you have some. </span>
                <a class="btn inverse" href="<?php echo WP_PLUGIN_URL.'/mirror-muscles/fitbit.php?reset_fitbit_session=1'?>">
                    <i class="fa fa-unlink"> Disconect</i>
                </a>
            </p>
            
            
        <?php endif;?>
    </div>
</div>
</div>
</div>
                  
<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>