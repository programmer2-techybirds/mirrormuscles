<div class="bboss_ajax_search_item bboss_ajax_search_member">
	<a href="<?php echo add_query_arg( array( 'no_frame' => '1' ), bp_get_member_permalink() ); ?>">
		<div class="item-avatar">
			<?php bp_member_avatar( 'type=thumb&width=60&height=60' ); ?>
		</div>

		<div class="item">
			<div class="item-title">
				<?php
					$member_id = bp_get_member_user_id();
					$search_member_type = bp_get_member_type($member_id);
					$user_data = get_userdata($member_id);
					echo $user_data->display_name;
				?>
				<!--p style="margin:0;color:#4dcadd;"><small><?php echo $user_data->user_email;?></small></p-->
				<p style="margin:0;"><small><strong><?php echo bp_get_profile_field_data('field=User Type&user_id='.$member_id);?></strong></small></p>
				<p style="margin:0;"><small><?php if($search_member_type =='enchanced') echo bp_get_profile_field_data('field=Specialization for Trainer/GYM&user_id='.$member_id);?></small></p>
				<p style="margin:0;"><small><i><?php echo bp_get_profile_field_data('field=Location&user_id='.$member_id);?><i></small></p>
				
			</div>
		</div>
	</a>
</div>