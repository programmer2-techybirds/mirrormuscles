<?php
/*
Template Name: My Progress
*/
get_header();?>
<?php 
    if (isset($_POST) && !empty($_POST) && isset($_POST['show_client_progress']) && !empty($_POST['show_client_progress']) ) {
        $trainer_id = $current_user->id;
        $client_id = $_POST['client_id'];
        $is_my_client = user_is_connected($client_id);

        $orm_results = get_onerepmax_results($client_id);
    }else{
        $client_id = $current_user->id;
        $is_my_client = false;
    }

    $results = get_bfc_results($client_id);
    if(!empty($results)){
        $weightList = $leanmassList = $bodyfatList = $a ='';
        foreach($results as $key=>$result){
            switch ($result->units) {
                case 'kg':
                    $weight = $result->weight;
                    $leanmass = $result->leanmass;
                    break;
                case 'lbs':
                    $weight = $result->weight*0.45359237;
                    $leanmass = $result->leanmass*0.45359237;
                    break;
                case 'oz':
                    $weight = $result->weight*0.0283495231;
                    $leanmass = $result->leanmass*0.0283495231;
                    break;
                default:
                    $weight = $result->weight;
                    $leanmass = $result->leanmass;
            }

            if( !next( $results ) ) {
                $weightList .= '['.(strtotime($result->added)*1000).', '.$weight.']';
                $leanmassList .= '['.(strtotime($result->added)*1000).', '.$leanmass.']';
                $bodyfatList .= '['.(strtotime($result->added)*1000).', '.$result->bodyfat.']';
                
            }
            else{
                $weightList .= '['.(strtotime($result->added)*1000).', '.$weight.'],';
                $leanmassList .= '['.(strtotime($result->added)*1000).', '.$leanmass.'],';
                $bodyfatList .= '['.(strtotime($result->added)*1000).', '.$result->bodyfat.'],';
            }
        }
    }
                    
                        

?>

<?php if(bp_get_member_type($client_id) == 'standard' || (isset($trainer_id) && $is_my_client) ): ?>

<?php $mpi_options = get_option("mpi_options"); ?>
<div id="buddypress" class="template-my-progress container">
    <div class="site-content">
        <h3 class="template-title">
            <?php echo($is_my_client) ? get_fullname($client_id) : 'My'?> Progress
        </h3>
        <div class="col-md-12 text-center">
            <?php if(is_user_logged_in() && wp_is_mobile()):?>
                <?php print_video_container();?>
            <?php endif;?>
        </div>
        <p class="template-description-text"><?php echo nl2br($mpi_options['mpi_header_text']);?></p>
        <div id="my-progress-tabs" class="mm-tabs">
            <ul> 
                
                <?php if(!$is_my_client):?><li><a href="#bfc-tab">Body Fat Calculator</a></li><?php endif;?>
                <li><a href="#previous-results-tab">Previous BFC Results</a></li>
                <li><a href="#photo-progress-tab1">Photo Progress</a></li>
                <?php if($is_my_client):?><li><a href="#one-rm-previous-results">One-Rep Max Results</a></li><?php endif;?>
            </ul>
           

            <?php if(!$is_my_client):?>
                <div id="bfc-tab">
                    <div id="body-fat-calculator">
                        <form id="bfc-form" action="https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="current_user" value="<?php echo $current_user->id;?>"/>
                            <div class="col-md-6 col-xs-12">
                                <div class="col-md-12 bfc-left">
                                    <?php
                                        $birthday = bp_get_profile_field_data('field=5&user_id='.$client_id);
                                        $gender = bp_get_profile_field_data('field=7&user_id='.$client_id);
                                        $bdate = new DateTime($birthday);
                                        $now = new DateTime();
                                        $interval = $now->diff($bdate);
                                        $age = $interval->y;
                                    ?>
                                    <h4>Calculator:</h4>
                                    <div class="form-group">
                                        <label for="gender" class="col-md-6 control-label">Gender</label>
                                        <div class="col-md-6 input-group"  style="padding: 0;" >
                                            <select class="form-control" disabled>
                                                <option value="M" <?php if($gender == 'Male') echo "selected";?>>Male</option>
                                                <option value="F" <?php if($gender == 'Female') echo "selected";?>>Female</option>
                                            </select>
                                            <input type="hidden" id="gender" class="form-control" name="gender" value="<?php echo $gender;?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="age" class="col-md-6 control-label">Age</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="age" id="age" placeholder="Age" value="<?php echo $age;?>" required readonly>
                                            <div class="input-group-addon">years</div>
                                        </div>
                                    </div>
                                    <div id="error-age" class="col-md-12"></div>

                                    <div id="error-weight" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="weight" class="col-md-6 control-label">Weight</label>
                                        <div class="col-md-3 input-group" style="float:left; padding-right: 10px;">
                                            <input type="text" class="form-control" name="weight" id="weight" placeholder="Weight" required>
                                        </div>
                                        <div class="col-md-2 input-group"  style="padding: 0;" >
                                         <select id="units" class="form-control" name="units" tabindex="-1" required>
                                                <option value="kg" selected>kg</option>
                                                <option value="lbs">lbs</option>
                                                <option value="oz">oz</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="error-chest" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="chest" class="col-md-6 control-label">Chest</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="chest" id="chest" placeholder="Chest" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    
                                    <div id="error-axilla" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="axilla" class="col-md-6 control-label">Axilla</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="axilla" id="axilla" placeholder="Axilla" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    
                                    <div id="error-triceps" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="triceps" class="col-md-6 control-label">Triceps</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="triceps" id="triceps" placeholder="Triceps" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>

                                    <div id="error-subscapular" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="subscapular" class="col-md-6 control-label">Subscapular</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="subscapular" id="subscapular" placeholder="Subscapular" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    
                                    <div id="error-abdominal" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="Abdominal" class="col-md-6 control-label">Abdominal</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="abdominal" id="abdominal" placeholder="Abdominal" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    
                                    <div id="error-suprailiac" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="suprailiac" class="col-md-6 control-label">Suprailiac</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="suprailiac" id="suprailiac" placeholder="Suprailiac" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    
                                    <div id="error-thigh" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="thigh" class="col-md-6 control-label">Thigh</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="thigh" id="thigh" placeholder="Thigh" required>
                                            <div class="input-group-addon">mm</div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center"> 
                                        <!--<small><a href="/how-to-measure-your-bodyfat" target="_blank" tabindex="-1"><i class="fa fa-info-circle"></i> How to measure your body fat</a></small>
<small><a href="https://www.youtube.com/embed/hQWoq8D9xnE" rel="wp-videolightbox" title="" tabindex="-1"><i class="fa fa-info-circle"></i> How to measure your body fat</a></small>-->
<small><?php echo '<i class="fa fa-info-circle"></i>  '. do_shortcode('[video_lightbox_youtube video_id="hQWoq8D9xnE&rel=false" width="640" height="480" anchor="How to measure your body fat"]'); ?></small>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="col-md-12 bfc-right">
                                    <h4 class="text-center">Results:</h4>
                                    
                                    <div class="form-group">
                                        <small>
                                            <label style="font-weight: 700">
                                                <input type="checkbox" id="add-custom-body-fat-radio" class="add-custom-body-fat-radio styled"><strong></strong> or set custom Body Fat Result
                                            </label>
                                        </small>
                                    </div>
                                    <div id="error-bodyfat" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="bodyfat" class="col-md-6 control-label">Body Fat</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="bodyfat" id="bodyfat" placeholder="Body Fat" readonly>
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </div>
                                    <div id="error-fatmass" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="fatmass" class="col-md-6 control-label">Fat Mass</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="fatmass" id="fatmass" placeholder="Fat Mass" readonly>
                                            <div class="input-group-addon">kg</div>
                                        </div>
                                    </div>
                                    <div id="error-leanmass" class="col-md-12"></div>
                                    <div class="form-group">
                                        <label for="leanmass" class="col-md-6 control-label">Lean Mass</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="leanmass" id="leanmass" placeholder="Lean Mass" readonly>
                                            <div class="input-group-addon">kg</div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="mmcategory" class="col-md-6 control-label">Mirror Muscles Category</label>
                                        <div class="col-md-6 input-group">
                                            <input type="text" class="form-control" name="mmcategory" id="mmcategory" placeholder="Mirror Muscles Category" readonly>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    <div class="text-center bfc-buttons">
                                        <div class="btn-group">
                                            <a class="btn" id="bfc-calculate"><i class="fa fa-calculator"></i>Calculate</a>
                                            <a class="btn" id="bfc-save"><i class="fa fa-lg fa-save"></i>Save</a>
                                            <a id="bfc-share" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-share-alt"></i>Share <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                   <li><a id="bfc-share-facebook"><i class="fa fa-lg fa-facebook-square"></i> Facebook</a></li>
                                                    <li><a id="bfc-share-google"><i class="fa fa-lg fa-google-plus-square"></i> Google+</a>
                                                    <button id="bfc-share-google-btn" type="button" style="position: absolute; top: -99999999px; width: 1px: height:1px;"></button>
                                                    </li>
                                                    <li><a id="bfc-share-twitter"><i class="fa fa-lg fa-twitter-square"></i> Twitter</a></li>
                                                    <li><a id="bfc-share-email" target="_top"><i class="fa fa-lg fa-envelope-square"></i> Email</a></li>
                                                    <li><a id="bfc-share-wall"><i class="fa fa-lg fa-user-plus"></i> My Wall</a></li>
                                                </ul>
                                            </a>
                                        </div>
                                        
                                    </div>
                                    <div id="error-save" class="col-md-12"></div>
                                    <div id="success-save" class="col-md-12"></div>
                                    <div class="share_with_email" style="display:none;">
                                        <input type="email" class="share_with_email_email form-group" required placeholder="Share to email">
                                        <textarea class="share_with_email_text" readonly style="width:100%" rows="5"></textarea>
                                        <button type="button" class="share_with_email_send">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <?php if(!empty($results)): ?>
                        <div class="col-md-12">
                            <div id="chart-container-outer-1">
                                <h4 class="text-center">Body Fat % vs Body Weight</h4>
                                <p class="text-right">
                                    <small><i class="fa fa-info-circle"></i> Double click on chart for zoom in or use mousewheel.</small>
                                </p>
                                <div id="chart-container-controls-1">
                                    <div id="chart_arrows-1"></div>
                                </div>
                                <div id="chart-container-inner-1" style="width: 100%; height: 300px;"> </div>
                                <div id="chart-container-legend-1" class="plot-legend"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    
                </div><!--#bfc-tab-->
            <?php endif;?>
            
             <div id="photo-progress-tab1">
                <?php

                    $args = array(
                            'order'             => 'DESC',
                            'author'            =>  $client_id,
                            'orderby'           => 'post_date',
                            'post_type'         => 'user-progress-image',
                            'post_status'       => 'publish',
                            'posts_per_page'    => 365// -1 is infinite
                        );
                        
                        $progreses = get_posts( $args );
                        $now_date = new DateTime(date("Y-m-d H:i:s"));
                        $lastpost_date = new DateTime($progreses[0]->post_date);
                        $difference = $now_date->diff($lastpost_date);
                    
                ?>

                <?php if(!empty($progreses)): ?> 
                    <p>      
                        <div class="container-slider row">
                            <div class="slider-for-container">
                                <div class="slider slider-for">
                                <?php $i=1; foreach($progreses as $key=>$progress): ?>
                                    <div data-src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($progress->ID), 'full' )[0]?>" style="background-image: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($progress->ID), 'full' )[0]?>); background-repeat: no-repeat;left: 33px;" data-div="<?php echo $i; ?>">
                                        <?php //echo //get_the_post_thumbnail( $progress->ID, 'large' ); ?>
                                        <h4 class="slider-for-caption"><?php echo date('F d, Y', strtotime($progress->post_date));?></h4>
                                        <?php if (!$is_my_client): ?>
                                            <ul data-photo-id="<?php echo $progress->ID?>" class="share_photo_progress">
                                                <li><a class="photo_share_facebook" data-published="<?php echo date('F d, Y', strtotime($progress->post_date));?>" title="Share to Facebook"><i class="fa fa-lg fa-facebook-square"></i></a></li>
                                                <li>
    	                                            <a class="g-interactivepost"
    													data-contenturl="<?php echo $progress->guid;?>"
    													data-clientid="<?php $mmshare_options = get_option("mmshare_options"); echo $mmshare_options["mmshare_google_app"];?>"
    													data-cookiepolicy="single_host_origin"
    													data-prefilltext="<?php //$mpi_options = get_option("mpi_options"); echo $mpi_options["mpi_share_photo_desc"];?>"
    													data-calltoactionlabel="OPEN"
    													data-calltoactionurl="<?php echo $progress->guid;?>" 
    													title="Share to Google+">
    													<i class="fa fa-lg fa-google-plus-square"></i>
    												</a>
      											</li>
                                                <li><a class="photo_share_twitter" data-published="<?php echo date('F d, Y', strtotime($progress->post_date));?>" data-guid="<?php echo $progress->guid;?>" title="Share to Twitter"><i class="fa fa-lg fa-twitter-square"></i></a></li>
                                                <li><a class="photo_share_wall" data-published="<?php echo date('F d, Y', strtotime($progress->post_date));?>" title="Share to Wall"><i class="fa fa-lg fa-user-plus"></i></a></li>
                                                <li><a class="delete-progress-image"><i class="fa fa-lg fa-trash"></i></a></li>
                                            </ul>
                                        <?php $i++; endif;?>
                                    </div>
                                <?php endforeach;?>
                                </div>
                                <div id="slider-for-arrows"></div>     
                            </div>
                            
                            <div class="slider slider-nav">
                            
								<?php /*?><?php foreach($progreses as $key=>$progress): ?>
                                    <div style="background-image: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($progress->ID), 'full' )[0]?>); background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                                <?php endforeach;?><?php */?>
                               
                                                        
                             <?php $j=1; foreach($progreses as $key=>$progress): ?>
                                <div id="cdiv_<?php echo $j; ?>" style="background-image: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id($progress->ID), 'full' )[0]?>); background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                            <?php $j++; endforeach; ?>
                            </div>
                        </div>
                    </p>
                <?php else: ?>
                    <div id="message" class="info"><p>Sorry, no progress found.</p></div>
                <?php endif; ?>

                <?php if (!$is_my_client):?>
                    <div class="progress_image_form_container">
                        <form class="form-horizontal" name="progress_img_form" id="progress_img_form" action="https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php" method="post" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div id="error-progress_image" class="col-md-12 text-right">
                                        <?php 
                                            //if last uploded is older than uploading interval
                                            if(!empty($progreses) && $difference->d < $mpi_options["mpi_upload_days"]){
                                                $days_remain = $mpi_options["mpi_upload_days"] - $difference->d;
                                                echo '<p class="text-center"><i class="fa fa-info-circle"></i> You can upload your next progress image only on '.$days_remain.' days remain.</p>';
                                            }
                                        ?>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-file">
                                                Browse&hellip; <input type="file" name="progress_image" id="progress_image" required <?php if(!empty($progreses) && $difference->d < $mpi_options["mpi_upload_days"]) echo "class='btn inverse' disabled='disabled'";?> />
                                            </span>
                                        </span>
                                        <input id="filename-display" name="filename-display" type="text" class="form-control" readonly />
                                    </div>
                                    
                                    <p class="text-right">
                                        <small><i class="fa fa-info-circle"></i> <?php echo $mpi_options["mpi_help_text"];?></small>
                                    </p>
                                </div>
                                <div class="form-group text-right">
                                    <input type="hidden" name="upload_progress_image" value="" />
                                    <input type="submit" class="upload-image" id="upload_progress_image"  <?php if(!empty($progreses) && $difference->d < $mpi_options["mpi_upload_days"]) echo "class='btn inverse' disabled"; ?> value="Upload new photo" />   
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endif;?>
            </div><!--#photo-progress-tabb-->


            <div id="previous-results-tab">
                <div id="update-results"></div>
                <?php if(!empty($results)): ?>
                    <table class="table toggle-default footable" data-page-navigation=".pagination" data-page-size="10">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Body Fat</th>
                                <th data-hide="phone,tablet">Fat mass</th>
                                <th data-hide="phone,tablet">Lean mass</th>
                                <th data-hide="phone,tablet">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($results as $key=>$result) :?>
                                <tr>
                                    <td>
                                        <?php 
                                            echo $result->added;
                                            
                                            $last_result = reset($results); 
                                            $count = count($results);
                                            
                                            if (empty($is_my_client)){

                                                echo (date('Y-m-d') == date('Y-m-d', strtotime($result->added)) || $result == reset($results)) ? '<i data-result="'.$result->id.'" class="fa fa-lg fa-trash delete-prev-result"></i>' : '';
                                                echo (date('Y-m-d') == date('Y-m-d', strtotime($last_result->added))
                                                    && $result == $results[1]) ? ' <i data-result="'.$result->id.'" class="fa fa-lg fa-trash delete-prev-result"></i>' : '';
                                            }

                                        ?>
                                    </td>
                                    <td><?php echo $result->bodyfat;?>, %</td>
                                    <td><?php echo $result->fatmass.' '.$result->units;?></td>
                                    <td><?php echo $result->leanmass.' '.$result->units;?></td>
                                    <td><?php echo $result->category;?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <div class="pagination pagination-centered"></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div id="chart-container-outer">
                        <h4 class="text-center">Body Fat % vs Body Weight</h4>
                        <p class="text-right">
                            <small><i class="fa fa-info-circle"></i> Click on chart for zoom in.</small>
                        </p>
                        <div id="chart-container-controls">
                            <div id="chart_arrows"></div>
                        </div>
                        <div id="chart-container-inner" style="width: 100%; height: 300px;"> </div>
                        <div id="chart-container-legend" class="plot-legend"></div>
                    </div>
                <?php else: ?>
                    <div id="message" class="info"><p>Sorry, no saved results found.</p></div>
                <?php endif; ?>
            </div><!--#previous-results-tab-->

            <?php if($is_my_client):?>

                <div id="one-rm-previous-results">
                    <?php if(!empty($orm_results)): ?>
                        <table class="table toggle-default footable" data-page-navigation=".pagination" data-page-size="10">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th data-hide="phone,tablet">Exercise</th>
                                    <th data-hide="phone,tablet">Weight Lifted</th>
                                    <th data-hide="phone,tablet">Repeats</th>
                                    <th>ONE-REP MAX</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orm_results as $key=>$result) :?>
                                    <tr>
                                        <td>
                                            <?php 
                                                echo $result->added;
                                            ?>
                                        </td>
                                        <td><?php echo stripslashes_deep($result->exercise);?></td>
                                        <td><?php echo $result->weight;?></td>
                                        <td><?php echo $result->repeats;?></td>
                                        <td><?php echo round( (100*$result->weight)/(101.3 - 2.67123*$result->repeats) );?></td>
                                    </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <div class="pagination pagination-centered"></div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <div id="message" class="info"><p>Sorry, no saved results found.</p></div>
                    <?php endif; ?>
                </div>
            <?php endif;?>
        </div>
    </div>
<div>
</div>


<!--script src="https://apis.google.com/js/plus.js?onload=init"></script-->

<script type="text/javascript">

<?php if(!empty($results)): ?> 

jQuery(document).ready(function(){
	
	var weightList = [<?php echo $weightList;?>];
    var bodyfatList = [<?php echo $bodyfatList;?>];
    var leanmassList = [<?php echo $leanmassList;?>];

    jQuery(function () {        
        var plot = jQuery.plot(jQuery("#chart-container-inner"),
            [
                {
                    data: weightList,
                    label: "Body Weight, kg",
                    points: { show: true },
                    lines: { show: true},
                    color: '#FD6703',
                    hoverable: true
                },
                {
                    data: leanmassList,
                    label: "Lean Mass, kg",
                    points: { show: true },
                    lines: { show: true, lineWidth: 2},
                    color: '#30455c',
                    hoverable: true
                },
                {
                    data: bodyfatList,
                    label: "Body Fat, %",
                    points: { show: true },
                    lines: { show: true},
                    hoverable: true,
                    yaxis: 2,
                    color: '#4DCADE'
                }
            ],
            {   canvas: true,
                series: {
                   points: { radius: 2 }
                }, 
                grid: {
                    hoverable: true,
                    backgroundColor: null,
                    minBorderMargin: 20,
                    borderWidth: 2,
                    margin: {
                        top: 5,
                        left: 5,
                        bottom: 5,
                        right: 5,
                    },
                    borderColor: {
                        top: '#fff',
                        left: '#fff',
                        bottom: '#fff',
                        right: '#fff'
                    }
                },
                
                legend:{
                    show: true,
                    container: '#chart-container-legend',
                    noColumns: 3,
                    labelBoxBorderColor: 'transparent',
                    labelFormatter: function(label, series) {
                    return '<span style="color:#30455C;">' + label + '</span>';
                    }
                },
                xaxis: {
                    show: true,
                    color: 'rgba(0,0,0, 0.1)',
                    mode: "time",
                    timeformat: "%b %e, %Y",
                    
                },
                yaxes: [
                    {
                        axisLabel: 'Weight, kg & Lean Mass, kg',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 12,
                        axisLabelColour: '#30455c',
                        color: 'rgba(255,255,255, 0.1)',
                        font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455c"
                        },
                        zoomRange: [weightList[0],new Date().getTime()],
                        panRange: [weightList[0],new Date().getTime()]
                    },
                    {
                        axisLabel: 'Body Fat, %',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 12,
                        axisLabelColour: '#30455c',
                        position: "right",
                        color: 'rgba(255,255,255, 0.1)',
                        font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455c"
                        },
                        zoomRange: [weightList[0],new Date().getTime()],
                        panRange: [weightList[0],new Date().getTime()]
                    }
                ],
                zoom: {
                    interactive: true,
                    amount: 1.2
                },
                pan: {
                    interactive: true
                }

            }
        );

        jQuery('<a id="zoomout"><i class="fa fa-search-minus"> zoom out</a>')
            .prependTo(jQuery("#chart-container-controls"))
            .click(function (event) {
                event.preventDefault();
                plot.zoomOut();
            });

        

        function addArrow(dir, offset) {
            jQuery("<span class='fa fa-arrow-"+dir+" chart_arrow_"+dir+"'>")
                .appendTo(jQuery("#chart-container-controls #chart_arrows"))
                .click(function (e) {
                    e.preventDefault();
                    plot.pan(offset);
                });
        }

        addArrow("left",{ left: -100 });
        addArrow("right",{ left: 100 });
        addArrow("up",{ top: -100 });
        addArrow("down",{ top: 100 });
        
    });

    jQuery(function () {        
        var plot = jQuery.plot(jQuery("#chart-container-inner-1"),
            [
                {
                    data: weightList,
                    label: "Body Weight, kg",
                    points: { show: true },
                    lines: { show: true},
                    color: '#FD6703',
                    hoverable: true
                },
                {
                    data: leanmassList,
                    label: "Lean Mass, kg",
                    points: { show: true },
                    lines: { show: true, lineWidth: 2},
                    color: '#30455c',
                    hoverable: true
                },
                {
                    data: bodyfatList,
                    label: "Body Fat, %",
                    points: { show: true },
                    lines: { show: true},
                    hoverable: true,
                    yaxis: 2,
                    color: '#4DCADE'
                }
            ],
            {   canvas: true,
                series: {
                   points: { radius: 2 }
                }, 
                grid: {
                    hoverable: true,
                    backgroundColor: null,
                    minBorderMargin: 20,
                    borderWidth: 2,
                    margin: {
                        top: 5,
                        left: 5,
                        bottom: 5,
                        right: 5,
                    },
                    borderColor: {
                        top: '#fff',
                        left: '#fff',
                        bottom: '#fff',
                        right: '#fff'
                    }
                },
                
                legend:{
                    show: true,
                    container: '#chart-container-legend-1',
                    noColumns: 3,
                    labelBoxBorderColor: 'transparent',
                    labelFormatter: function(label, series) {
                    return '<span style="color:#30455C;">' + label + '</span>';
                    }
                },
                xaxis: {
                    show: true,
                    color: 'rgba(0,0,0, 0.1)',
                    mode: "time",
                    timeformat: "%b %e, %Y",
                    
                },
                yaxes: [
                    {
                        axisLabel: 'Weight, kg & Lean Mass, kg',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 12,
                        axisLabelColour: '#30455c',
                        color: 'rgba(255,255,255, 0.1)',
                        font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455c"
                        },
                        zoomRange: [weightList[0],new Date().getTime()],
                        panRange: [weightList[0],new Date().getTime()]
                    },
                    {
                        axisLabel: 'Body Fat, %',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 12,
                        axisLabelColour: '#30455c',
                        position: "right",
                        color: 'rgba(255,255,255, 0.1)',
                        font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455c"
                        },
                        zoomRange: [weightList[0],new Date().getTime()],
                        panRange: [weightList[0],new Date().getTime()]
                    }
                ],
                zoom: {
                    interactive: true,
                    amount: 1.2
                },
                pan: {
                    interactive: true
                }

            }
        );

        jQuery('<a id="zoomout-1"><i class="fa fa-search-minus"> zoom out</a>')
            .prependTo(jQuery("#chart-container-controls-1"))
            .click(function (event) {
                event.preventDefault();
                plot.zoomOut();
            });

        

        function addArrow(dir, offset) {
            jQuery("<span class='fa fa-arrow-"+dir+" chart_arrow_"+dir+"'>")
                .appendTo(jQuery("#chart-container-controls-1 #chart_arrows-1"))
                .click(function (e) {
                    e.preventDefault();
                    plot.pan(offset);
                });
        }

        addArrow("left",{ left: -100 });
        addArrow("right",{ left: 100 });
        addArrow("up",{ top: -100 });
        addArrow("down",{ top: 100 });
        
    });




    jQuery("#chart-container-inner").bind("plothover", function (event, pos, item) {
        if (item) {
            var label = item.series.label;
            var date = new Date(item.datapoint[0]);
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var content_date = monthNames[(date.getMonth())]+' '+date.getDate() +', '+date.getFullYear();
            jQuery("#flot-chart-tooltip").remove();
            var content = (label == 'Body Weight, kg') 
                            ? 'Saved: <strong>'+content_date+'</strong><br> Weight: <strong>'+item.datapoint[1]+' kg</strong>' 
                            : ((label == 'Lean Mass, kg') ? 'Saved: <strong>'+content_date+'</strong><br> Lean Mass: <strong>'+item.datapoint[1]+' kg</strong>' 
                            : 'Saved: <strong>'+content_date+'</strong><br> Body Fat: <strong>'+item.datapoint[1]+' %</strong>');
            jQuery('body').append('<div id="flot-chart-tooltip" style="top:'+(item.pageY + 5)+'px; left: '+(item.pageX + 5)+'px">' + content + '</div>');
        }else
            jQuery("#flot-chart-tooltip").remove();
    });

    jQuery("#chart-container-inner-1").bind("plothover", function (event, pos, item) {
        if (item) {
            var label = item.series.label;
            var date = new Date(item.datapoint[0]);
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var content_date = monthNames[(date.getMonth())]+' '+date.getDate() +', '+date.getFullYear();
            jQuery("#flot-chart-tooltip-1").remove();
            var content = (label == 'Body Weight, kg') 
                            ? 'Saved: <strong>'+content_date+'</strong><br> Weight: <strong>'+item.datapoint[1]+' kg</strong>' 
                            : ((label == 'Lean Mass, kg') ? 'Saved: <strong>'+content_date+'</strong><br> Lean Mass: <strong>'+item.datapoint[1]+' kg</strong>' 
                            : 'Saved: <strong>'+content_date+'</strong><br> Body Fat: <strong>'+item.datapoint[1]+' %</strong>');
            jQuery('body').append('<div id="flot-chart-tooltip-1" style="top:'+(item.pageY + 5)+'px; left: '+(item.pageX + 5)+'px">' + content + '</div>');
        }else
            jQuery("#flot-chart-tooltip-1").remove();
    });

});

<?php endif; ?> 
</script>
<script type="text/javascript">
	jQuery(document).on("click",".next",function(){
		var cdiv = jQuery(".slider.slider-for .slick-slide.slick-active").attr("data-div");
		//console.log(cdiv);
		jQuery(".slider.slider-nav .slick-slide").css("display","block");
		jQuery("#cdiv_"+cdiv).css("display","block");
		jQuery(".slider-nav .slick-slide.slick-active").removeClass("slick-active");
		jQuery("#cdiv_"+cdiv).addClass("slick-active");
		jQuery(".slider.slider-for.slick-initialized.slick-slider .slick-slide.slick-active").css("left", "0px");
		
	});
	
	jQuery(document).on("click",".prev",function(){
		var cdiv = jQuery(".slider.slider-for .slick-slide.slick-active").attr("data-div");
		//console.log(cdiv);
		jQuery(".slider.slider-nav .slick-slide").css("display","block");
		jQuery("#cdiv_"+cdiv).css("display","block");
		jQuery(".slider-nav .slick-slide.slick-active").removeClass("slick-active");
		jQuery("#cdiv_"+cdiv).addClass("slick-active");
	});
	
	jQuery(window).load(function(){
		jQuery(".slider.slider-nav .slick-slide").removeClass("slick-active");
		jQuery(".slider.slider-nav .slick-slide:first-child").addClass("slick-active");
		jQuery(".slider-nav .slick-active").css("background-size","80% !important");
		jQuery(".slider-nav .slick-slide").css("background-size","60% !important");
	});
	
</script> 

<script>

	$(document).ready(function(){
		jQuery('#add-custom-body-fat-radio').click(function(){
			var arr=jQuery(this).val();
			if (jQuery('#add-custom-body-fat-radio').is(":checked")){
				jQuery('#bodyfat').prop('readonly', false);
				jQuery('#fatmass').prop('readonly', false);
				jQuery('#leanmass').prop('readonly', false);
				jQuery('#bodyfat').prop('required', true);
				jQuery('#fatmass').prop('required', true);
				jQuery('#leanmass').prop('required', true);
				
			}
			else{
				jQuery('#bodyfat').prop('readonly', true);
				jQuery('#fatmass').prop('readonly', true);
				jQuery('#leanmass').prop('readonly', true);
				jQuery('#bodyfat').prop('required', false);
				jQuery('#fatmass').prop('required', false);
				jQuery('#leanmass').prop('required', false);
				
			}
		});
	});

</script> 

<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>