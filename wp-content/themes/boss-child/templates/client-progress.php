<?php
/*
Template Name: Client Progress
*/
get_header();
?>

<?php
	if(isset($_GET['nid']) && !empty($_GET['nid']))
		if( check_admin_referer( $current_user->ID ) );
			bp_notifications_mark_notification( $_GET['nid'], 0 );
			
	$uid = $current_user->ID;
	$pending_connections = pending_connection_requests('standard');
	$accepted_connections = accepted_connection_requests('standard');
	$has_not_connected = get_not_connected_friends($uid,'standard');
	
	$user_id = $current_user->ID;
	$member_type = bp_get_member_type($user_id);
	$clients = accepted_connection_requests('standard');
	$transformation_winner = get_transformation_winner();
	
	$mm_spamer_options = get_option("mm_spamer_options");
    $mm_spamer_threshold = intval($mm_spamer_options["mm_spamer_threshold"]);
?>
<?php if( $member_type== ('pt' || 'gym')  ): ?>
<div id="buddypress">
	<div id="primary">
		<div class="template-client-progress">
    		<div class="site-content">
            	<?php if( $member_type== ('gym')  ){ ?>
        		<h3 class="template-title">GYM Members</h3>
                <?php }else{ ?>
                <h3 class="template-title">My Clients</h3>
                <?php } ?>

		        <?php if(!empty($transformation_winner)): ?>
			    	<div id="clients-bigest-transformation" class="text-center" style="display:none;">
			        	<h3 class="template-subtitle">The biggest transformation - Leader</h3>
					 	<div class="avatar">
							<a href="<?php echo bp_core_get_user_domain($transformation_winner['user_id']);?>">
								<?php  echo get_avatar($transformation_winner['user_id'],256); ?>
							</a>
						</div>
						<p>
							<a href="<?php bp_member_permalink($transformation_winner['user_id']); ?>" class="result-title">
								<?php echo $transformation_winner['firstname'].' '.$transformation_winner['lastname']; ?>
							</a>
						</p>
						<p><strong>Weight: </strong><?php echo $transformation_winner['last_weight']?> kg</p>
						<p><strong>Bodyfat: </strong><?php echo $transformation_winner['last_bodyfat']?>% - <?php echo ($transformation_winner['last_bodyfat'] < $transformation_winner['first_bodyfat']) ? 'Down' : 'Up'; ?> from <?php echo $transformation_winner['first_bodyfat']?>%</p>
						<p><strong>Fat mass: </strong><?php echo $transformation_winner['last_fatmass']?> kg</p>
						<p><strong>Lean mass: </strong><?php echo $transformation_winner['last_leanmass']?> kg</p>
						<p><strong>Mirror Muscles Category: </strong><?php echo $transformation_winner['last_category']?></p>			
			        </div>
		    	<?php endif;?>
				<br>
				<div class="col-md-12 text-right">
                    <button type="button" class="btn inverse request_friend_connection">Connect Friends as Clients&nbsp;<i class="fa fa-lg fa-user-plus"></i></button>
                    <p><small><i class="fa fa-info-circle"></i> The maximum number of requests to an individual is <?php echo $mm_spamer_threshold;?>, if each one is rejected you will be blocked</small></p>
                </div>
                <div class="clear"></div>
		        <?php if($clients):?>
			        
			        <div class="mm-search-container">
						<div class="search-wrap">
				        	<input id="search" type="text" placeholder="Live search...">
				        	<button type="button" id="searchsubmit" disabled><i class="fa fa-search"></i></button>
			        	</div>
			        	<div id="message" class="info no_results" style="display:none;"><p>Sorry, no clients found.</p></div>
					</div>

					<p class="text-center">
						<small>
							<i class="fa fa-info-circle"></i> To share the latest client results, you need to send "Sharing Request" to client.
						</small>
					</p>
					<hr>

					<?php if ( bp_has_members( 'per_page=10&include='.implode(',',$clients) ) ) : ?>
						<ul id="accepted-connections-list" class="item-list members-list" role="main">
							<?php while ( bp_members() ) : bp_the_member(); ?>
								<?php 
					        		$client_result = get_client_last_bfc_result(bp_get_member_user_id());
					        		$client_result_sharing = get_client_last_shared_bfc_result(bp_get_member_user_id());
					        		$client_fitbit_shared = get_user_meta(bp_get_member_user_id(),'fitbit_shared');
					        		$client_fitbit_account = get_user_meta(bp_get_member_user_id(),'fitbit_user_id');
					        	?>
								<li>
									<div class="item-avatar col-md-2 col-xs-12 text-center">
										<a href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php bp_member_avatar('type=full&width=256&height=256'); ?></a>
									</div>

									<div class="item col-md-5 col-xs-12 text-center">
										<div class="item-title">
											<a class="fullname" href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php echo get_fullname(bp_get_member_user_id());?></a>
										</div>

						                <?php
							                $showing = null;
							                //if bp-followers activated then show it.
							                if(function_exists("bp_follow_add_follow_button")) {
							                    $showing = "follows";
							                    $followers  = bp_follow_total_follow_counts(array("user_id"=>bp_get_member_user_id()));
							                } elseif (function_exists("bp_add_friend_button")) {
							                    $showing = "friends";
							                }
						                ?>

										<div class="item-meta">
											<div class="activity">
												<?php bp_initiator_member_last_active(bp_get_member_user_id() ) ?>
											</div>
											
											<?php if($showing == "friends"): ?>
						                    <span class="count"><?php echo friends_get_total_friend_count(bp_get_member_user_id()); ?></span>
						                    	<?php if ( friends_get_total_friend_count(bp_get_member_user_id()) > 1 ) { ?>
						                    		<span><?php _e("Friends","boss"); ?></span>
						                        <?php } else { ?>
						                        	<span><?php _e("Friend","boss"); ?></span>
						                        <?php } ?>
						                    <?php endif; ?>

						                    <?php if($showing == "follows"): ?>
						                    <span class="count"><?php $followers = bp_follow_total_follow_counts(array("user_id"=>bp_get_member_user_id())); echo $followers["followers"]; ?></span><span><?php _e("Followers","boss"); ?></span>
						                    <?php endif; ?>
										</div>
                                        <hr>
										<?php print_connection_status();?>
	                					<?php print_members_rejected_count(); ?>
                                        <hr />
                                        <?php
											$parqs = get_complete_client_parq(bp_get_member_user_id());
											$parqs_ = array();
											if($parqs)
												foreach( $parqs as $key=>$parq)
													$parqs_[] = '<a href="/parq/?parq_id='.$parq->id.'">PAR-Q '.date('Y-m-d',strtotime($parq->updated)).'</a>';
											echo implode('<br>', $parqs_);
										?>
										<div class="clear"></div>
										
										<button type="button" class="btn resend_parq" data-client="<?php echo bp_get_member_user_id();?>" <?php echo (get_pending_parq_from_trainer(bp_get_member_user_id(),$current_user->ID)) ? 'disabled' : '';?>>Resend PAR-Q request</button>
										<div id="resend_parq_error"></div>
									</div>
	                				
	                				<div class="col-md-5 col-xs-12 text-center inline-forms">
										<!--MY PROGRESS link-->
										<form id="client-progress-<?php echo bp_get_member_user_id();?>" action="<?php echo "/my-progress/";?>" method="post" enctype="multipart/form-data">
											<input type="hidden" name="client_id" value="<?php echo bp_get_member_user_id();?>">
											<input type="hidden" name="show_client_progress" value="1"?>
											<button class="btn" type="submit">Photo, BFC and ONE-REP MAX progress</button>
										</form>

										<!--WORKOUT LOG link-->
										<form id="workout-logs-<?php echo bp_get_member_user_id();?>" action="/workout-log" method="get" enctype="multipart/form-data">
											<input type="hidden" name="u" value="<?php echo bp_get_member_user_id();?>">
											<input type="hidden" name="show-workout-logs" value="1"?>
											<button class="btn" type="submit">Workout logs</button>
										</form>

										<!--FOOD DIARIES link-->
										<form id="client-diaries-<?php echo bp_get_member_user_id();?>" action="<?php echo "/food-supplement-diary/";?>" method="post" enctype="multipart/form-data">
											<input type="hidden" name="client_id" value="<?php echo bp_get_member_user_id();?>">
											<input type="hidden" name="show_client_diaries" value="1"?>
											<button class="btn" type="submit">Supplement Diaries</button>
										</form>
                                        
                                        <!--SUPPLEMENT DIARIES link-->
                                        <form id="client-diaries-<?php echo bp_get_member_user_id();?>" action="<?php echo "/nutrition-diary/";?>" method="post" enctype="multipart/form-data">
											<input type="hidden" name="client_id" value="<?php echo bp_get_member_user_id();?>">
											<input type="hidden" name="show_client_diaries" value="1"?>
											<button class="btn" type="submit">Nutrition Diaries</button>
										</form>
										
										<!--FITBIT link-->
										<form id="fitbit-account-<?php echo bp_get_member_user_id();?>" class="inline-forms">
											<a href="<?php echo ($client_fitbit_shared && $client_fitbit_account) ? 'https://www.fitbit.com/user/'.$client_fitbit_account[0] : ''?>" target="_blank" class="btn <?php echo (!$client_fitbit_shared) ? 'disabled' : '' ?>">Fitbit account</a>
										</form>

										<!--SHARE BFC SHARED RESULTS-->
										<form id="client-progress-share-<?php echo bp_get_member_user_id();?>" class="inline-forms" >
											<?php if(!$client_result): ?>
												<button class="btn" type="button" disabled>No BFC Results shared</button>
											<?php elseif($client_result->id == $client_result_sharing->result_id && $client_result_sharing->status == 'pending'): ?>
												<button class="btn" type="button" disabled>Pending Request</button>
											<?php elseif($client_result->id == $client_result_sharing->result_id && $client_result_sharing->status == 'accepted'): ?>
												<div class="progress-sharing-container">
													<p style="margin:0; color:#30455C;">Share client BFC results on <?php echo date('Y-m-d',strtotime($client_result_sharing->added))?>:</p>
													<a class="share-facebook popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Facebook"><i class="fa fa-2x fa-facebook-square"></i></a>
							                        <a id="share_google_<?php echo bp_get_member_user_id();?>" class="share-google  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Google+"><i class="fa fa-2x fa-google-plus-square"></i></a>
							                     	<a class="share-twitter  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Twitter"><i class="fa fa-2x fa-twitter-square"></i></a>
							                        <a class="share-email  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share with Email" target="_top"><i class="fa fa-2x fa-envelope-square"></i></a>
							                        <a class="share-wall  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to My Wall"><i class="fa fa-2x fa-user-plus"></i></a>
							                        <input type="hidden" class="share_text" value="<?php echo $client_result_sharing->share_text;?>">
													<div id="success-share"></div>
													<div class="share_with_email" style="display:none;">
														<input type="email" class="share_with_email_email form-group" required placeholder="Share to email" val="sss">
														<textarea readonly style="width:100%;" rows="5"><?php echo $client_result_sharing->share_text;?></textarea>
														<button class="btn share_with_email_send">Send</button>
													</div>
												</div>
											<?php elseif($client_result->id != $client_result_sharing->result_id && $client_result_sharing->status == 'accepted'): ?>
												<button class="sharing-request btn" data-client="<?php echo bp_get_member_user_id(); ?>" type="button">BFC Sharing Request</button>
												<p class="new_sharing_results"><small>New results avaliable!</small></p>
												<div class="progress_sharing_container">
													<p style="margin:10px 0 0; color:#30455C;">Share client BFC results on <?php echo date('Y-m-d',strtotime($client_result_sharing->added))?>:</p>
													<a class="share-facebook popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Facebook"><i class="fa fa-2x fa-facebook-square"></i></a>
							                        <a id="share_google_<?php echo bp_get_member_user_id();?>" class="share-google  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Google+"><i class="fa fa-2x fa-google-plus-square"></i></a>
							                     	<a class="share-twitter  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to Twitter"><i class="fa fa-2x fa-twitter-square"></i></a>
							                        <a class="share-email  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share with Email" target="_top"><i class="fa fa-2x fa-envelope-square"></i></a>
							                        <a class="share-wall  popover-label" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Share to My Wall"><i class="fa fa-2x fa-user-plus"></i></a>
							                        <input type="hidden" class="share_text" value="<?php echo $client_result_sharing->share_text;?>">
													<div id="success-share"></div>
													<div class="share_with_email" style="display:none;">
														<input type="email" class="share_with_email_email form-group" required placeholder="Share to email">
														<textarea readonly style="width:100%" rows="5"><?php echo $client_result_sharing->share_text;?></textarea>
														<button class="share_with_email_send">Send</button>
													</div>
												</div>
											<?php else: ?>
												<button class="sharing-request btn" data-client="<?php echo bp_get_member_user_id(); ?>" type="button">BFC Sharing Request</button>
											<?php endif;?>	
										</form>

									</div>

									<div class="clear"></div>
								</li>
							<?php endwhile; ?>
						</ul>
						<div id="pag-bottom" class="pagination">
							<div class="pag-count" id="member-dir-count-bottom">
								<?php bp_members_pagination_count(); ?>
							</div>
							<div class="pagination-links" id="member-dir-pag-bottom">
								<?php bp_members_pagination_links(); ?>
							</div>
						</div>
					<?php endif;?>
				<?php else:?>
					<div id="message" class="info"><p>Sorry, no clients progress found.</p></div>
				<?php endif;?>
    </div>                   
</div>
</div>                         
</div>



<div id="request_friend_connection" style="display: none;">
	<div class="col-md-12" style="padding: 10px;">
		<div class="col-md-12" style=" padding: 20px 0px;">
			<?php if($has_not_connected): ?>
				<div class="search-wrap col-md-12">
		    		<input id="search-for-connection" type="text">
		    		<button type="button" id="searchsubmit" disabled><i class="fa fa-search"></i></button>
				</div>
				<div class="clear"></div>
				<?php if ( bp_has_members( 'user_id='.$uid.'&include='.implode(',',$has_not_connected) ) ) : ?>
					
					<ul id="members-list-for-connection" class="item-list" role="main">
						<ul id="members-list-for-connection" class="item-list" role="main">
						<?php while ( bp_members() ) : bp_the_member(); ?>
							<li>	
								<div class="item-avatar col-md-5 text-center">
									<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=48&height=48'); ?></a>
									<div class="item-title">
										<a class="fullname" href="<?php bp_member_permalink(); ?>"><?php echo bp_get_profile_field_data('field=1&user_id='.bp_get_member_user_id()).' '.bp_get_profile_field_data('field=2&user_id='.bp_get_member_user_id()) ?></a>
									</div>
								</div>
								<div class="action col-md-7 text-center">
									<?php print_connection_status();?>
                					<?php print_members_rejected_count(); ?>
								</div>
								<div class="clear"></div>
							</li>
						<?php endwhile; ?>
					</ul>
					</ul>
				<?php endif;?>
			<?php else: ?>
				<div id="buddypress">
					<div id="message" class="info text-center">
						<p>
							All your friends are connected with you(or has pending connection) as Clients.<br>
							Please add new users to your friendship list.
						</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>	
</div><!-- #request_friend_connection -->

<script src="https://apis.google.com/js/plus.js?onload=init"></script>
<?php endif;?>
<?php get_footer(); ?>