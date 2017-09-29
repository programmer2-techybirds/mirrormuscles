<?php
	$pending_connections = pending_connection_requests();
	$accepted_connections = accepted_connection_requests();
	$uid = get_current_user_id();
?>
		<div class="tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul class="btn-group inverse user-progress-tab ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist"> 
                <li class="btn ui-corner-top ui-state-default" role="tab" tabindex="-1" aria-controls="accepted-connections" aria-labelledby="ui-id-13" style="width: 50%;">
                    <a href="#acepted-connections" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-13">Accepted Connections<span class="count accepted_count"><?php echo count($accepted_connections);?></span></a>
                </li>

                <li class="btn ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="pending-connections" aria-labelledby="ui-id-12" style="width: 50%">
                    <a href="#pending-connections" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-12">Pending Connection Requests<span class="count pending_count"><?php echo count($pending_connections);?></span></a>
                </li>
            </ul>
           
            <div id="acepted-connections" aria-labelledby="ui-id-13" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;">
                <ul id="accepted-connections-list" class="item-list" role="main">
					<?php if(!empty($accepted_connections)) :?>
						<?php foreach($accepted_connections as $key=>$connection) :?>
							<?php
								$connection_user_id = ($uid == $connection->initiator_user_id) ? $connection->friend_user_id : $connection->initiator_user_id;
							?>
							<li>
								<div class="item-avatar col-md-2">
									<a href="<?php echo bp_core_get_user_domain($connection_user_id);?>"><?php echo get_avatar($connection_user_id,70); ?></a>
								</div>

								<div class="item col-md-4">
									<div class="item-title">
										<a class="fullname" href="<?php echo bp_core_get_user_domain($connection_user_id);?>"><?php echo bp_get_profile_field_data('field=1&user_id='.$connection_user_id).' '.bp_get_profile_field_data('field=8&user_id='.$connection_user_id);?></a>
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

								<div class="action col-md-6">
									<div class="col-md-12 relations-block-inputs form-group">
										<p><strong>Connection Request Text:&nbsp;</strong><span><?php _e($connection->request_text); ?></span></p>
										<p><strong>Accepted:&nbsp;</strong><span><?php _e($connection->added); ?></span></p>
									</div>
					                <div class="col-md-12 action-wrap text-center">
					                	<button id="refuse_connection_request_<?php _e($connection->id); ?>" type="button" class="refuse_connection">Refuse Connection</button>
									</div>
								</div>

								<div class="clear"></div>
							</li>
						<?php endforeach; ?>
					<?php else: ?>
						<div id="message" class="info">
							<p><?php _e( "Sorry, no accepted connection requests.", 'boss' ); ?></p>
						</div>
					<?php endif; ?>
                </ul>
            </div>

             <div id="pending-connections" aria-labelledby="ui-id-12" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel">
                <ul id="pending-connections-list" class="item-list" role="main">
	                <?php if(!empty($pending_connections)) :?>
						<?php foreach($pending_connections as $key=>$connection) :?>
							<?php
								$connection_user_id = ($uid == $connection->initiator_user_id) ? $connection->friend_user_id : $connection->initiator_user_id;
							?>
							<li>
								<div class="item-avatar col-md-2">
									<a href="<?php echo bp_core_get_user_domain($connection_user_id);?>"><?php  echo get_avatar($connection_user_id,70); ?></a>
								</div>

								<div class="item col-md-4">
									<div class="item-title">
										<a class="fullname" href="<?php echo bp_core_get_user_domain($connection_user_id);?>"><?php echo bp_get_profile_field_data('field=1&user_id='.$connection_user_id).' '.bp_get_profile_field_data('field=8&user_id='.$connection_user_id);?></a>
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

								<div class="action col-md-6">
									<div class="col-md-12 relations-block-inputs form-group text-center">
										<p><strong>Connection Request Text: </strong><span><?php _e($connection->request_text); ?></span></p>
									</div>
									<div class="col-md-12 action-wrap text-center">
						                <?php if( $connection->initiator_user_id != $uid): ?>
						                	<button id="accept_connection_request_<?php _e($connection->id); ?>" type="button" class="accept_connection">Accept Request</button>
										    <button id="reject_connection_request_<?php _e($connection->id); ?>" type="button" class="reject_connection">Reject Request</button>
										<?php else: ?>
											<button id="reject_connection_request_<?php _e($connection->id); ?>" type="button" class="reject_connection">Cancel Request</button>
										<?php endif; ?>
									</div>
								</div>

								<div class="clear"></div>
							</li>
						<?php endforeach; ?>
					<?php else: ?>
						<div id="message" class="info">
							<p><?php _e( "Sorry, no pending connection requests.", 'boss' ); ?></p>
						</div>
					<?php endif; ?>
				</ul>
            </div>
        </div>

