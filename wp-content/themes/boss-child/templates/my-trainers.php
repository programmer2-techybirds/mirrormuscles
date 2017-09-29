<?php
/*
Template Name: My Trainers
*/
get_header();?>

<?php
	if(isset($_GET['nid']) && !empty($_GET['nid']))
		if( check_admin_referer( $current_user->ID ) );
			bp_notifications_mark_notification( $_GET['nid'], 0 );

	$uid = $current_user->ID;
	$member_type = bp_get_member_type($uid);
	$pending_connections = pending_connection_requests('pt');
	$accepted_connections = accepted_connection_requests('pt');
	$has_not_connected = get_not_connected_friends($uid,'pt');
	$pending_parq = get_pending_client_parq($uid,'pt');
	$pending_sharing = get_client_pending_sharing_requests($uid);

	$mm_spamer_options = get_option("mm_spamer_options");
    $mm_spamer_threshold = intval($mm_spamer_options["mm_spamer_threshold"]);
?>

<?php if($member_type=='standard'): ?>
<div id="buddypress">
<div id="primary">
<div class="template-my-trainers">
    <div class="site-content">

		<h3 class="template-title">My Trainers</h3>
		
		<div class="col-md-12 text-right">
			<button type="button" class="btn inverse request_friend_connection">Connect Friends as Trainers&nbsp;<i class="fa fa-lg fa-user-plus"></i></button>
			<p><small><i class="fa fa-info-circle"></i> The maximum number of requests to an individual is <?php echo $mm_spamer_threshold;?>, if each one is rejected you will be blocked. Trainer can be connected only, after you fill in trainer's PAR-Q.</small></p>
		</div>
		<div class="clear"></div>

		<div class="mm-search-container">
			<div class="search-wrap">
	        	<input id="search" type="text" placeholder="Live search...">
	        	<button type="button" id="searchsubmit" disabled><i class="fa fa-search"></i></button>
        	</div>
		</div>

		<div id="connection_tabs" class="mm-tabs">
	        <ul>
	            <li><a href="#connected">Connected <span class="count accepted_count"><?php echo count($accepted_connections);?></span></a></li>
	            <li><a href="#requested">Requests <span class="count pending_count"><?php echo count($pending_connections);?></span></a></li>
	            <li><a href="#pending_parq">Pending PAR-Q <span class="count pending_count"><?php echo count($pending_parq);?></span></a></li>
	            <li><a href="#sharing_requests">Sharing Requests <span class="count pending_count"><?php echo count($pending_sharing);?></span></a></li>
	        </ul>
	        <div id="connected">
				<?php if(!empty($accepted_connections)) :?>
					<?php if ( bp_has_members( 'per_page=10&include='.implode(',',$accepted_connections) ) ) : ?>
						<ul id="accepted-connections-list" class="item-list members-list" role="main">
							<?php while ( bp_members() ) : bp_the_member(); ?>
								<li>
									<div class="item-avatar col-md-2 col-xs-12 text-center">
										<a href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php bp_member_avatar('type=full&width=256&height=256'); ?></a>
									</div>

									<div class="item col-md-5 col-xs-12 text-center">
										<div class="item-title">
											<a class="fullname" href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php echo bp_get_profile_field_data('field=1&user_id='.bp_get_member_user_id()).' '.bp_get_profile_field_data('field=2&user_id='.bp_get_member_user_id());?></a>
											<p><small><?php echo implode(', ', bp_get_profile_field_data('field=12&user_id='.bp_get_member_user_id()));?></small></p>
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
				<?php else: ?>
					<div id="message" class="info"><p>Sorry, no connected Trainers were found.</p></div>
				<?php endif; ?>
	        </div><!--#clients-connected-->
	        <div id="requested">
	        	<?php if(!empty($pending_connections)) :?>
	        		<?php if ( bp_has_members( 'per_page=10&include='.implode(',',$pending_connections) ) ) : ?>
						<ul id="pending-connections-list" class="item-list members-list" role="main">
	            			<?php while ( bp_members() ) : bp_the_member(); ?>
								<li>
									<div class="item-avatar col-md-2 col-xs-12 text-center">
										<a href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php bp_member_avatar('type=full&width=256&height=256'); ?></a>
									</div>

									<div class="item col-md-5 col-xs-12 text-center">
										<div class="item-title">
											<a class="fullname" href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php echo bp_get_profile_field_data('field=1&user_id='.bp_get_member_user_id()).' '.bp_get_profile_field_data('field=2&user_id='.bp_get_member_user_id());?></a>
											<p><small><?php echo implode(', ', bp_get_profile_field_data('field=12&user_id='.bp_get_member_user_id()));?></small></p>
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
				<?php else: ?>
					<div id="message" class="info"><p>Sorry, no requested connections were found.</p></div>
				<?php endif;?>
	        </div><!--#requested-->
	        <div id="pending_parq">
	        	<ul id="pending-parq-connections-list" class="item-list members-list" role="main">
				<?php if(!empty($pending_parq)) :?>
					<?php foreach($pending_parq as $key=>$parq) :?>
						<?php
							$connection_user_id = ($uid == $parq->client_id) ? $parq->trainer_id : $parq->client_id;
						?>
						<li>
							<div class="item-avatar col-md-2 col-xs-12 text-center">
								<a href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php bp_member_avatar('type=full&width=256&height=256'); ?></a>
							</div>

							<div class="item col-md-5 col-xs-12 text-center">
								<div class="item-title">
									<a class="fullname" href="<?php echo bp_core_get_user_domain(bp_get_member_user_id());?>"><?php echo get_fullname($connection_user_id);?></a>
									<p><small><?php echo implode(', ', bp_get_profile_field_data('field=12&user_id='.$connection_user_id));?></small></p>
								</div>
								<?php
					                $showing = null;
					                //if bp-followers activated then show it.
					                if(function_exists("bp_follow_add_follow_button")) {
					                    $showing = "follows";
					                    $followers  = bp_follow_total_follow_counts(array("user_id"=>$connection_user_id));
					                } elseif (function_exists("bp_add_friend_button")) {
					                    $showing = "friends";
					                }
				                ?>

								<div class="item-meta">
									<div class="activity">
										<?php bp_initiator_member_last_active( $connection_user_id ) ?>
									</div>
									
									<?php if($showing == "friends"): ?>
				                    <span class="count"><?php echo friends_get_total_friend_count($connection_user_id); ?></span>
				                    	<?php if ( friends_get_total_friend_count($connection_user_id) > 1 ) { ?>
				                    		<span><?php _e("Friends","boss"); ?></span>
				                        <?php } else { ?>
				                        	<span><?php _e("Friend","boss"); ?></span>
				                        <?php } ?>
				                    <?php endif; ?>

				                    <?php if($showing == "follows"): ?>
				                    <span class="count"><?php $followers = bp_follow_total_follow_counts(array("user_id"=>$connection_user_id)); echo $followers["followers"]; ?></span><span><?php _e("Followers","boss"); ?></span>
				                    <?php endif; ?>
								</div>
							</div>

							<div class="action col-md-5 col-xs-12 text-center">
				                <hr>
				                <a href="/parq/?parq_id=<?php echo $parq->id;?>">Please fill in the PAR-Q.</a>
								<hr>
							</div>
							<div class="clear"></div>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<div id="message" class="info"><p>Sorry, no pending PARQ were found.</p></div>
				<?php endif;?>
				</ul>
			</div>
			<div id="sharing_requests">
				<ul id="pending-parq-connections-list" class="item-list members-list" role="main">
				<?php if(!empty($pending_sharing)) :?>
					<?php foreach($pending_sharing as $key=>$request) :?>
						<?php
							$result = base64_encode(str_rot13($request->id));
				        	$hash = md5(NONCE_SALT.$request->id);
				        	$accept_link = WP_PLUGIN_URL.'/mirror-muscles/handler.php?action=accept_share_result&result='.$result.'&hash='.$hash;
				        	$cancel_link = WP_PLUGIN_URL.'/mirror-muscles/handler.php?action=cancel_share_result&result='.$result.'&hash='.$hash;
						?>
						<li>
							<div class="item-avatar col-md-2 col-xs-12 text-center">
								<a href="<?php echo bp_core_get_user_domain($request->trainer_id);?>"><?php  echo get_avatar($request->trainer_id,256);?></a>
							</div>

							<div class="item col-md-5 col-xs-12 text-center">
								<div class="item-title">
									<a class="fullname" href="<?php echo bp_core_get_user_domain($request->trainer_id);?>"><?php echo bp_get_profile_field_data('field=1&user_id='.$request->trainer_id).' '.bp_get_profile_field_data('field=2&user_id='.$request->trainer_id);?></a>
									<p><small><?php echo implode(', ', bp_get_profile_field_data('field=12&user_id='.$connection_user_id));?></small></p>
								</div>
								<?php
					                $showing = null;
					                //if bp-followers activated then show it.
					                if(function_exists("bp_follow_add_follow_button")) {
					                    $showing = "follows";
					                    $followers  = bp_follow_total_follow_counts(array("user_id"=>$request->trainer_id));
					                } elseif (function_exists("bp_add_friend_button")) {
					                    $showing = "friends";
					                }
				                ?>

								<div class="item-meta">
									<div class="activity">
										<?php bp_initiator_member_last_active( $request->trainer_id ) ?>
									</div>
									
									<?php if($showing == "friends"): ?>
				                    <span class="count"><?php echo friends_get_total_friend_count($request->trainer_id); ?></span>
				                    	<?php if ( friends_get_total_friend_count($request->trainer_id) > 1 ) { ?>
				                    		<span><?php _e("Friends","boss"); ?></span>
				                        <?php } else { ?>
				                        	<span><?php _e("Friend","boss"); ?></span>
				                        <?php } ?>
				                    <?php endif; ?>

				                    <?php if($showing == "follows"): ?>
				                    <span class="count"><?php $followers = bp_follow_total_follow_counts(array("user_id"=>$request->trainer_id)); echo $followers["followers"]; ?></span><span><?php _e("Followers","boss"); ?></span>
				                    <?php endif; ?>
								</div>
							</div>

							<div class="action col-md-5 col-xs-12 text-center">
				                <a class="btn success accept_sharing_request" href="<?php echo $accept_link;?>">Accept Request</a>    
								<a class="btn danger cancel_sharing_request" href="<?php echo $cancel_link;?>">Cancel Request</a>
							</div>
							<div class="clear"></div>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<div id="message" class="info"><p>Sorry, no pending sharing requests were found.</p></div>
				<?php endif;?>
				</ul>
			</div><!--#sharing_requests-->
	    </div><!--#connection_tabs-->
    </div><!--.template...-->                
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
							All your friends are connected with you(or has pending connection) as Personal Trainers.<br>
							Please add new users to your friendship list.
						</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>	
</div><!-- #request_friend_connection -->

<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>