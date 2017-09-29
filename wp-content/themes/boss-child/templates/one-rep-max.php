<?php
/*
Template Name: 1 Rep max
*/
get_header();
$user_id = $current_user->id;
$member_type = bp_get_member_type($user_id);
$mm_onerepmax_options = get_option("mm_onerepmax_options");
$onerepmax_intro_text = stripslashes_deep($mm_onerepmax_options["onerepmax_intro_text"]);
$onerepmax_outro_text = stripslashes_deep($mm_onerepmax_options["onerepmax_outro_text"]);
$orm_results = get_onerepmax_results($user_id);
?>

<script>

function showhidecustomex(){

	  if(jQuery("#orm_exercise_custom_checkbox").is(':checked'))
	  {	
		  jQuery('#customdiv').append('<div id="error-orm_exercise_custom" class="text-center"></div><input type="text" class="form-control text-center" name="orm_exercise_custom" id="orm_exercise_custom" placeholder="Custom exercise" required>');

	  }else{
		  jQuery('#customdiv').html('');  
	  }
		 
}

</script>

<?php if( $member_type=='standard' || $member_type=='pt' || $member_type=='gym' ): ?>
	<div id="buddypress">
	<div id="primary">
	<div class="template-my-clients">
	    <div class="site-content">
	        <h3 class="template-title">One-Rep Max Calculator</h3>
	        
	        <div class="col-md-12 text-center">
				<?php if(is_user_logged_in() && wp_is_mobile()):?>
					<?php print_video_container();?>
				<?php endif;?>
	        </div>
	        <div class="clearfix"></div>

	        <div class="col-md-12 text-center">
				<?php echo nl2br($onerepmax_intro_text);?>
	        </div>
	        <div class="clearfix"></div>

	        <div id="my-progress-tabs" class="mm-tabs">
            <ul> 
                <li><a href="#one-rm-calculator">One-Rep Max Calculator</a></li>
                <li><a href="#one-rm-previous-results">One-Rep Max Previous Results</a></li>
            </ul>

            <div id="one-rm-calculator">
				<div class="col-md-6 col-sm-12">
					<div class="col-md-12" id="onerepmax_calculator">
						<form id="orm_form" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
			        		
			        		<h4 class="text-center">ONE-REP MAX (ONE-RM) Calculator:</h4>
	                		
	                		<div class="form-group text-center">
			                    <label for="orm_exercise" class="control-label">CHOOSE EXERCISE:</label>
			               		<div id="error-orm_exercise" class="text-center"></div>
			                    <select id="orm_exercise" class="form-control" name="orm_exercise" required>
			                        	<option value="Deadlift">Deadlift</option>
			                        	<option value="Squat">Squat</option>
			                        	<option value="Over head squat">Over head squat</option>
			                        	<option value="Bench Press">Bench Press</option>
			                        	<option value="Pull Up">Pull Up</option>
			                        	<option value="Overhead Press">Overhead Press</option>
			                        	<option value="Dip">Dip</option>
			                        	<option value="Snatch">Snatch</option>
			                        	<option value="Clean & Jerk">Clean & Jerk</option>
			                        	<option value="Power Clean">Power Clean</option>
			                    </select>
			                </div>
			                <div class="form-group text-center">
			                	<small>
			                		<label style="font-weight: 400">
			                			<input type="checkbox" name="orm_exercise_custom_checkbox" id="orm_exercise_custom_checkbox" onclick="showhidecustomex();" class="styled">
			                			or type CUSTOM exercise name
			                		</label>
			                	</small>
			                </div>
			                <div class="form-group text-center" id="customdiv">
							</div>

						    <div class="form-group text-center">
			                    <label for="weight" class="control-label">WEIGHT LIFTED:</label>
			                    <div id="error-orm_weight" class="text-center"></div>
			                    <input type="number" step="1" min="10" max="20000" class="form-control" name="orm_weight" id="orm_weight" placeholder="Weight" required>
							</div>
							
							<div class="form-group text-center">
			                    <label for="orm_reteaps" class="control-label">REPEATS:</label>
			               		<div id="error-orm_repeats" class="text-center"></div>
			                    <select id="orm_repeats" class="form-control" name="orm_repeats" required>
			                        <?php for($i=1; $i<=12; $i++): ?>
			                        	<option value="<?php echo $i;?>"><?php echo $i;?></option>
			                        <?php endfor;?>
			                    </select>
			                </div>
			                
			                <div class="form-group text-center">
			                	<button type="submit" class="btn" id="orm_calculate"><i class="fa fa-calculator"></i>Calculate</button>
			                </div>
			            </form>
			        </div>
		        </div>
				
				<div class="col-md-6 col-sm-12">
					<div class="col-md-12" id="onerepmax_results">
						<form id="orm_results_form" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
							
							<h4>ONE-RM calculation result:</h4>
							
							<div class="print_container">
							<div id="onerepmax_results_share">
								<h3 class="onerepmax_maximum text-center">YOUR ONE-REP MAX: <span></span></h3>
								<div class="col-md-6 col-sm-12">
									<div class="input-group form-group">
					                    <div class="input-group-addon">95% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_95" id="onerepmax_95" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">90% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_90" id="onerepmax_90" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">85% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_85" id="onerepmax_85" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">80% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_80" id="onerepmax_80" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">75% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_75" id="onerepmax_75" placeholder="?" readonly>
					                </div>
					            </div>
					            <div class="col-md-6 col-sm-12">
									<div class="input-group form-group">
					                    <div class="input-group-addon">70% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_70" id="onerepmax_70" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">65% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_65" id="onerepmax_65" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">60% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_60" id="onerepmax_60" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">55% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_55" id="onerepmax_55" placeholder="?" readonly>
					                </div>
					                <div class="input-group form-group">
					                    <div class="input-group-addon">50% ONE-RM</div>
					                    <input type="text" class="form-control" name="onerepmax_50" id="onerepmax_50" placeholder="?" readonly>
					                </div>
					            </div>
					        </div><!--#share_container-->
				            </div><!--.print_container-->
				            <div class="col-md-12 form-group text-center bfc-buttons">
			                	<div class="btn-group">
			                		<button class="btn" id="orm_save" disabled="disabled"><i class="fa fa-lg fa-save"></i>Save</button>
			                        <button class="btn" id="orm_print" disabled="disabled"><i class="fa fa-lg fa-print"></i>Print</button>
			                        <button class="btn" id="orm_share" disabled="disabled"><i class="fa fa-user-plus"></i>Share to Wall</button>
			                    </div>
			                </div>
		                </form>
		            </div>
				</div>
				<div class="clear"></div>
            </div>
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
                                            echo $result->added.'&nbsp;<i data-result="'.$result->id.'" class="fa fa-lg fa-trash delete-prev-result"></i>';
                                        ?>
                                    </td>
                                    <td><?php echo $result->exercise;?></td>
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

			

			<hr>
	        <div class="col-md-12 text-center">
	        	<h4 class="text-center">Instructions:</h4>
				<?php echo nl2br($onerepmax_outro_text);?>
	        </div>
	        
	        <div class="col-md-12 text-center">
	        	<table class="onerepmax_table">
	        		<tbody>
	        			<tr>
	        				<th>Reps</th><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td>
	        			</tr>
	        			<tr>
	        				<th>%1RM</th><td>100</td><td>95</td><td>93</td><td>90</td><td>87</td><td>85</td><td>83</td><td>80</td><td>77</td><td>75</td><td>73</td><td>70</td>
	        			</tr>
	        		</tbody>
	        		
	        	</table>
	        </div>

	    </div>                   
	</div>
	</div>                         
	</div>

<?php else: wp_redirect(home_url()); endif;?>

<?php get_footer(); ?>