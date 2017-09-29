<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss Inbox
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'BuddyBoss_Inbox_Labels' ) ) {

	/**
	 * BuddyBoss_Inbox_Labels
	 * ********************
	 */
	class BuddyBoss_Inbox_Labels {

		private $actions = array();
		private $filters = array();

		/**
		 * Empty constructor function to ensure a single instance
		 */
		public function __construct() {
			// ... leave empty, see Singleton below
		}

		/* Singleton
		 * ===================================================================
		 */

		/**
		 * Admin singleton
		 *
		 * @since BuddyBoss Inbox (1.0.0)
		 *
		 * @param  array  $options [description]
		 *
		 * @uses BuddyBoss_Inbox_Labels::setup() Init admin class
		 *
		 * @return object Admin class
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new BuddyBoss_Inbox_Labels();
				$instance->setup();
				$instance->do_action();
				$instance->do_filter();
			}

			return $instance;
		}

		public function do_action() {
			foreach ( $this->actions as $action ) {
				add_action( $action, array( $this, $action ) );
			}
		}

		public function do_filter() {
			foreach ( $this->filters as $function => $filter ) {
				if ( is_numeric( $function ) ) {
					$function = $filter;
				}
				add_filter( $filter, array( $this, $function ) );
			}
		}

		public function setup() {
			add_action( 'bbm_message_recipient', array( $this, 'bp_message_labels' ) );
			add_action( 'bp_messages_inbox_list_header', array( $this, 'bp_message_inbox_labels_header' ) );
			add_action( 'bp_messages_inbox_list_item', array( $this, 'bp_message_inbox_labels' ) );
			add_action( 'bp_get_the_thread_subject', array( $this, 'bp_get_the_thread_subject_add_label' ), 99 );
			add_filter( 'bp_has_message_threads', array( $this, 'bp_has_message_threads' ), 2, 3 );
			// ajax operations
			add_action( 'wp_ajax_bbm_label_ajax', 'bbm_label_ajax_callback' );
			add_action( 'wp_ajax_nopriv_bbm_label_ajax', 'bbm_label_ajax_callback' );
			add_action( 'wp_ajax_bbm_delete_label_ajax', 'bbm_delete_label_ajax_callback' );
			add_action( 'wp_ajax_nopriv_bbm_delete_label_ajax', 'bbm_delete_label_ajax_callback' );
		}

		private function thread_row_id( $thread ) {
			if ( 'sentbox' == bp_current_action() ) {
				$id = 's-' . $thread->messages[ 0 ]->id;
			} else {
				$id = 'r-' . $thread->recipients[ get_current_user_id() ]->id;
			}
			return $id;
		}

		public function bp_message_inbox_labels_header() {

			//Don't show label column in draft list
			if ( ! bp_is_current_action( 'drafts' ) ) {
				echo  '<th scope="col" class="thread-info">'.__('Label','buddyboss-inbox').'</th>';
			}
		}

		public function bp_message_inbox_labels() {

			//Don't show label column in draft list
			if (  bp_is_current_action( 'drafts' ) ) {
				return;
			}

			global $messages_template;
			$message_inbox_link	 = bp_displayed_user_domain() . bp_get_messages_slug() . '/inbox/';
			$all_labels			 = array();
			$thread_id			 = isset( $messages_template->thread->thread_id ) && !empty( $messages_template->thread->thread_id ) ? $messages_template->thread->thread_id : '';
			if ( !empty( $thread_id ) ) {
				$get_thread_label = bbm_get_message_labels( $thread_id );
			}

			if ( isset( $get_thread_label ) && !empty( $get_thread_label ) ) {
				foreach ( $get_thread_label as $single ) {
					$single_label_id = isset( $single->label_id ) && !empty( $single->label_id ) ? $single->label_id : '';
					$all_labels[]	 = array(
						'id'	 => $single_label_id,
						'name'	 => bbm_get_label_by_id( $single_label_id ),
					);
				}
			}

			if ( !empty( $all_labels ) ) {
				$label_names = array();
				$label		 = "<td class='thread-labels'>";
				foreach ( $all_labels as $single ) {
					$class = bbm_get_label_class_by_id( $single[ 'id' ] );
					if ( empty( $single[ 'name' ] ) ) {
						continue;
					} //skip empties
					$label_names[] = "<span class='bbm-label " . $class . "'><a href='" . $message_inbox_link . "?label_id=" . $single[ 'id' ] . "'>" . $single[ 'name' ] . "</a></span>";
				}
				$label .= implode( ' ', $label_names ) . "</td>";
				echo apply_filters( "buddyboss_inbox_message_label", $label );
			} else {

				if ( 1 ) {
					$label = "<td class='thread-labels empty'></td>";
					echo $label;
				}
			}
		}

		public function bp_get_the_thread_subject_add_label( $subject ) {
			global $thread_template;
			$message_inbox_link		 = bp_displayed_user_domain() . bp_get_messages_slug() . '/inbox/';
			$all_labels				 = array();
			$get_thread_label_class	 = array();
			$thread_id				 = $thread_template->thread->thread_id;
			$get_thread_label		 = bbm_get_message_labels( $thread_id );

			if ( isset( $get_thread_label ) && !empty( $get_thread_label ) ) {
				foreach ( $get_thread_label as $single ) {
					$all_labels[]				 = array(
						'id'	 => $single->label_id,
						'name'	 => bbm_get_label_by_id( $single->label_id ),
					);
					$get_thread_label_class[]	 = bbm_get_label_class_by_id( $single->label_id );
				}
			}

			if ( !empty( $all_labels ) ) {
				$label_names = '';
				foreach ( $all_labels as $key => $single ) {
					$class = $get_thread_label_class[ $key ];
					if ( empty( $single[ 'name' ] ) ) {
						continue;
					} //skip empties
					$label_names .= "<span class='" . strtolower( $class ) . "'><a href='" . $message_inbox_link . "?label_id=" . $single[ 'id' ] . "'>" . $single[ 'name' ] . "</a></span>";
				}
				return $subject . '<span class="thread-subject-label">' . $label_names . '</span>';
			} else {
				return $subject;
			}
		}

		function bp_has_message_threads( $has_threads, $messages_not_use, $args ) {
			global $messages_template;

			if ( isset( $args[ 'drafts' ] ) ) {
				return $has_threads;
			}

			if ( 'starred' == $args[ 'box' ] ) {
				return array( $has_threads, $messages_not_use, $args );
			}

			// The default box the user is looking at
			if ( bp_is_current_action( 'sentbox' ) ) {
				$default_box = 'sentbox';
			} elseif ( bp_is_current_action( 'notices' ) ) {
				$default_box = 'notices';
			} else {
				$default_box = 'inbox';
			}

			// Parse the arguments
			$r = bp_parse_args( $args, array(
				'user_id'		 => bp_loggedin_user_id(),
				'box'			 => $default_box,
				'per_page'		 => 10,
				'max'			 => false,
				'type'			 => 'all',
				'search_terms'	 => isset( $_REQUEST[ 's' ] ) ? stripslashes( $_REQUEST[ 's' ] ) : '',
				'page_arg'		 => 'mpage', // See https://buddypress.trac.wordpress.org/ticket/3679
				'meta_query'	 => array()
			), 'has_message_threads' );

			// If trying to access notices without capabilities, redirect to root domain
			if ( bp_is_current_action( 'notices' ) && !bp_current_user_can( 'bp_moderate' ) ) {
				bp_core_redirect( bp_displayed_user_domain() );
			}

			// Load the messages loop global up with messages
			$messages_template = new BP_Messages_Box_Template_Extend(
			$r[ 'user_id' ], $r[ 'box' ], $r[ 'per_page' ], $r[ 'max' ], $r[ 'type' ], $r[ 'search_terms' ], $r[ 'page_arg' ]
			);

			//remove_filter( 'bp_has_message_threads', array( $this, 'bp_has_message_threads' ), 2 );

			return apply_filters( 'bp_has_message_threads_bb_inbox', $messages_template->has_threads(), $messages_template, $r );
		}

		/**
		 * messgae label dropdown add
		 */
		public function bp_message_labels() {
			global $thread_template;
			$thread_id	 = $thread_template->thread->thread_id;
			$all_labels	 = bbm_get_user_labels();
			?>
			<div class=bbm_label_wrapper>
				<form name="bbm_label_form" action="" method="post">
					<dl class="bbm_label_dropdown">
						<dt>
						<?php echo $this->bbm_label_button(); ?>
						</dt>
						<dd>
							<div class="multiSelect">
								<ul>
									<li class="bbm_label_title"><?php _e( "Label as:", "buddyboss-inbox" ); ?></li>
									<?php
									if ( isset( $all_labels ) && !empty( $all_labels ) ) {
										foreach ( $all_labels as $single ) {
											$check_exists	 = bbm_label_check_exists( $thread_id, $single->bbm_label_id, bp_loggedin_user_id() );
											$checked		 = $check_exists > 0 ? 'checked' : '';
											?>
											<li>
												<span class="bbm-label-input"><input name="bbm_label_id_<?php echo $single->bbm_label_id ?>" type="checkbox" <?php echo $checked; ?> value="<?php echo $single->bbm_label_id ?>" /></span>
												<div class="bbm-label-wrap"><?php echo $single->label_name ?><i class="fa fa-times-circle bbm_label_delete"></i></div>
											</li>
											<?php
										}
									} else {
										?>
										<li><?php _e( "No labels available.", "buddyboss-inbox" ); ?></li>
										<?php
									}
									?>
									<li><a class="bbm-modal" href="#bbm_add_label_popup"><?php _e( "Create new", "buddyboss-inbox" ); ?></a> </li>
								</ul>
							</div>
						</dd>
					</dl>
				</form>

				<!-- Create new label popup -->
				<div id="bbm_add_label_popup" class="white-popup-block mfp-hide bbm-add-label">
					<h3><?php _e( 'Add Label', 'buddyboss-inbox' ); ?></h3>
					<form class="add-bbm-label-details standard-form"  method="post" action="">
						<div class="bbm-li-no-margin bbm-distance-bottom">
							<ul class="bbm-li-left">
								<li class="field-label-add-title">
									<div>
										<input type="hidden" name="bbm_current_thread_id" id="bbm_current_thread_id" value="<?php echo $thread_id; ?>" />
										<input type="hidden" name="current_user_id" id="current_user_id" value="<?php echo bp_loggedin_user_id(); ?>" />
										<input id="bbm_label_name" name="bbm_label_name" class="bbm-label-name" type="text" value="" placeholder="<?php _e( "Label Name", "buddyboss-inbox" ); ?>"/>
									</div>
								</li>
								<li>
									<input id="label_add" class="label-add" type="button" name="label_add" value="<?php _e( 'Save', 'buddyboss-inbox' ); ?>" />
									<div class="popup_error_message"></div>
								</li>
							</ul>
						</div>
					</form>
				</div>
			</div>
			<?php
		}

		public function bbm_label_button() {
			$html = '<a class="button" title="Add/Create Label" href="javascript:void(0)">';
			$html .= '<span class="hida">' . __( "Label", "buddyboss-inbox" ) . '</span>';
			$html .= '<p class="multiSel"></p></a>';
			return apply_filters( 'bbm_label_button_html', $html );
		}

	}

}
