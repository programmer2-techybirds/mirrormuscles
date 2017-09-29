<?php do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

	<?php do_action( 'bp_before_member_messages_threads' ); ?>

	<form action="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ?>/bulk-manage/" method="post" id="messages-bulk-management">
		<div id="messages-table-wrap">
			<table id="message-threads" class="messages-notices">

				<thead>
					<tr>
						<th scope="col" class="thread-checkbox"><input id="select-all-messages" type="checkbox"><strong></strong></th>
						<th scope="col" class="thread-from"><?php _e( 'From', 'buddyboss-inbox' ); ?></th>
						<th scope="col" class="thread-info"><?php _e( 'Subject', 'buddyboss-inbox' ); ?></th>

						<?php
						/**
						 * Fires inside the messages box table header to add a new column.
						 *
						 * This is to primarily add a <th> cell to the messages box table header. Use
						 * the related 'bp_messages_inbox_list_item' hook to add a <td> cell.
						 *
						 * @since BuddyPress (2.3.0)
						 */
						do_action( 'bp_messages_inbox_list_header' );
						?>

						<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
							<th scope="col" class="thread-star"><span class="message-action-star"></span> <span class="screen-reader-text"><?php _e( 'Star', 'buddyboss-inbox' ); ?></span></span></th>
						<?php endif; ?>

						<th scope="col" class="thread-options"><?php _e( 'Actions', 'buddyboss-inbox' ); ?></th>
					</tr>
				</thead>

				<tbody>

					<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

						<tr id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?> unread<?php else: ?> read<?php endif; ?>">
							<td class="bulk-select-check">
								<input type="checkbox" name="message_ids[]" class="message-check" value="<?php bp_message_thread_id(); ?>" />
							</td>

							<td class="thread-from">
								<?php bp_message_thread_avatar( array( 'width' => 25, 'height' => 25 ) ); ?>

								<?php if ( 'sentbox' != bp_current_action() ) : ?>
									<span class="from"><?php _e( 'From:', 'buddyboss-inbox' ); ?></span> <?php bp_message_thread_from(); ?>
								<?php else: ?>
									<span class="to"><?php _e( 'To:', 'buddyboss-inbox' ); ?></span> <?php bp_message_thread_to(); ?>
								<?php endif; ?>

								<?php bp_message_thread_total_and_unread_count(); ?>
								<span class="activity"><?php bp_message_thread_last_post_date(); ?></span>
							</td>

							<td class="thread-info">
								<?php
								if ( function_exists( 'twentyfifteen_setup' ) ) {
									echo bp_messages_inbox_labels_list();
								}
								?>
								<p><a href="<?php bp_message_thread_view_link(); ?>" title="<?php esc_attr_e( "View Message", "buddypress" ); ?>"><?php bp_message_thread_subject(); ?></a></p>
								<p class="thread-excerpt"><?php bp_message_thread_excerpt(); ?></p>
							</td>

							<?php
							if ( !function_exists( 'twentyfifteen_setup' ) ) {
								do_action( 'bp_messages_inbox_list_item' );
							}
							?>

							<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
								<td class="thread-star">
									<?php bp_the_message_star_action_link( array( 'thread_id' => bp_get_message_thread_id() ) ); ?>
								</td>
							<?php endif; ?>

							<td class="thread-options">
								<?php if ( bp_message_thread_has_unread() ) : ?>
									<a class="read" href="<?php bp_the_message_thread_mark_read_url(); ?>"><?php _e( 'Read', 'buddyboss-inbox' ); ?></a>
								<?php else : ?>
									<a class="unread" href="<?php bp_the_message_thread_mark_unread_url(); ?>"><?php _e( 'Unread', 'buddyboss-inbox' ); ?></a>
								<?php endif; ?>
								|
								<a class="delete" href="<?php bp_message_thread_delete_link(); ?>"><?php _e( 'Delete', 'buddyboss-inbox' ); ?></a>
							</td>
						</tr>

					<?php endwhile; ?>

				</tbody>

			</table><!-- #message-threads -->
		</div>

		<div class="messages-options-nav">
			<?php bp_messages_bulk_management_dropdown(); ?>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>
	</form>

	<?php do_action( 'bp_after_member_messages_threads' ); ?>

	<?php do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found.', 'buddyboss-inbox' ); ?></p>
	</div>

<?php endif; ?>

<?php
do_action( 'bp_after_member_messages_loop' );
