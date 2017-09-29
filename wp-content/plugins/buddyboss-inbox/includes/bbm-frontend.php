<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss Inbox
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'BuddyBoss_Inbox_Frontend' ) ) {

	/**
	 * BuddyBoss_Inbox_Frontend
	 * ********************
	 */
	class BuddyBoss_Inbox_Frontend {

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
		 * @uses BuddyBoss_Inbox_Frontend::setup() Init admin class
		 *
		 * @return object Admin class
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new BuddyBoss_Inbox_Frontend;
				$instance->setup();
				$instance->do_action();
				$instance->do_filter();
			}

			return $instance;
		}

		public function do_action() {
			foreach ( $this->actions as $function => $action ) {
				if ( is_numeric( $function ) ) {
					$function = $action;
				} else {
					if ( is_array( $action ) ) {
						foreach ( $action as $act ) {
							add_action( $act, array( $this, $function ) );
						}
						continue;
					}
				}
				add_action( $action, array( $this, $function ) );
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
			$attachment_feature	 = buddyboss_messages()->option( 'attachment_feature' );
			$attachment_allowed	 = bbm_plupload_file_formats();
			$label_feature		 = buddyboss_messages()->option( 'label_feature' );
			$draft_feature		 = buddyboss_messages()->option( 'draft_feature' );
			$editor_feature		 = buddyboss_messages()->option( 'editor_feature' );

			if ( $editor_feature || $draft_feature || $label_feature) {
				
				add_action( 'init', array($this,'remove_all_wp_editor_buttons'), 99 );
				add_action( 'wp_head', array($this,'remove_all_wp_editor_buttons'),99 );
				
				add_action( 'bp_init', 'buddyboss_messages_template_stack' );
				if ( bp_is_user() ) {
					add_filter( 'bp_get_template_part', 'buddyboss_messages_replace_template', 10, 3 );
				}
			}

			if ( $editor_feature ) {
				add_filter( 'tiny_mce_before_init', 'bbm_tiny_mce_before_init' );
				//Stop removing html tags
				remove_filter( 'messages_message_content_before_save', 'wp_filter_kses', 1 );
				add_filter( 'messages_message_content_before_save', array($this,'bbm_kses'));
				remove_filter( 'bp_get_messages_content_value', 'wp_filter_kses', 1 );
				add_filter( 'bp_get_messages_content_value', array($this,'bbm_kses'));
				remove_filter( 'bp_get_the_thread_message_content', 'wp_filter_kses', 1 );
				add_filter( 'bp_get_the_thread_message_content', array($this,'bbm_kses'));
			}

			if ( $attachment_feature && !empty( $attachment_allowed ) ) {

				add_filter( 'upload_mimes', array( $this, 'buddyboss_message_myme_types' ), 3, 1 );
				add_action( 'bp_after_messages_compose_content', array( $this, 'attachment' ) );
				add_action( 'bp_after_message_reply_box', array( $this, 'attachment' ) );
				add_action( 'bp_after_message_content', array( $this, 'bbm_file_for_messages_display' ) );

				add_action( 'wp_ajax_bb_buddyboss_message_attachment', 'bb_buddyboss_message_attachment_ajax' );
				add_action( 'wp_ajax_nopriv_bb_buddyboss_message_attachment', 'bb_buddyboss_message_attachment_ajax' );
				add_action( 'wp_ajax_bbm_attachment_ajax', 'bbm_attachment_ajax_callback' );
				add_action( 'wp_ajax_nopriv_bbm_attachment_ajax', 'bbm_attachment_ajax_callback' );

				// Hook to 'messages_message_sent' to add a new message meta containing the file data
				add_action( 'messages_message_sent', array( $this, 'bbm_file_for_messages_attach_file' ), 10, 1 );
				add_action( 'messages_message_after_save', array( $this, 'bb_buddyboss_messages_send_reply' ), 10, 1 );
				// Hook to 'messages_screen_compose' in case the message was not sent to delete the file
				add_action( 'messages_screen_compose', array( $this, 'bbm_file_for_messages_delete_file' ) );
			}

			// all filters
			//add_filter( 'bp_get_options_nav_inbox', array( $this, 'add_message_count' ) );
		}

		/**
		 * Simply output the link of the attached file if any.
		 */
		public function bbm_file_for_messages_display() {
			global $bbm_message_attachments;
			$message_id = (int) bp_get_the_thread_message_id();

			$files = bp_messages_get_meta( $message_id, '_bbm_file_attached_file' );

			
			if ( !empty( $files ) AND is_array($files) ) {
							
				ob_start();
				
				$bbm_message_attachments = $files;
				
				bbm_message_load_template("attachments");
				
				$content = ob_get_contents();
				
				ob_end_clean();
				
				echo str_replace("\n","",$content);
			}
		}

		public function add_message_count( $li ) {
			$msg_count = bp_get_total_unread_messages_count();
			return str_replace( '</a>', ' (' . $msg_count . ') </a>', $li );
		}

		public function attachment() {
			$value			 = buddyboss_messages()->option( 'attachment_feature' );
			$draft_feature	 = buddyboss_messages()->option( 'draft_feature' );
			if ( !$value ) {
				return;
			}

			// for draft
			if ( $draft_feature ) {
				global $thread_template;
				$thread_id				 = isset( $thread_template->thread->thread_id ) && !empty( $thread_template->thread->thread_id ) ? $thread_template->thread->thread_id : '';
				$bbm_draft_id			 = '';
				$draft_attachment_raw	 = '';
				$draft_attachment_data	 = array();
				$draft_attachment_name	 = '';
				$draft_attachment_url	 = '';
				$draft_attachment_file	 = '';
				if ( !empty( $thread_id ) ) {
					$bbm_draft_id			 = bbm_draft_col_by_thread_id( $thread_id, 'bbm_draft_id' );
					$draft_attachment_raw	 = bbm_draft_col_by_thread_id( $thread_id, 'draft_attachment' );
					$draft_attachment_data	 = !empty( $draft_attachment_raw ) ? unserialize( $draft_attachment_raw ) : array();
				}
				if ( isset( $_GET[ 'draft_id' ] ) && !empty( $_GET[ 'draft_id' ] ) && is_numeric( $_GET[ 'draft_id' ] ) ) {
					$bbm_draft_id			 = $_GET[ 'draft_id' ];
					$draft_detail			 = bbm_draft_row_by_draft_id( $_GET[ 'draft_id' ] );
					$draft_attachment_raw	 = ( isset( $draft_detail->draft_attachment ) && !empty( $draft_detail->draft_attachment ) ) ? $draft_detail->draft_attachment : '';
					$draft_attachment_data	 = !empty( $draft_attachment_raw ) ? unserialize( $draft_attachment_raw ) : array();
					$uri_data = array();
					if(is_array($draft_attachment_data)) {
						foreach($draft_attachment_data as $adata) {
							$json = stripslashes( json_encode( $adata ) );
							
							if(substr($json,0,1) == "[") {
								$json = substr($json,1,-1);
							}
							$uri_data[] = $json;
						}
					}
				}
			}
			
			?>
			<div class="bbm-attachment-wrapper">
				<a id="bb-buddyboss-attachment" name="bb-buddyboss-attachment" data-another-txt="<?php _e( 'Attach Another File', 'buddyboss-inbox' ); ?>" data-txt="<?php _e( 'Attach File', 'buddyboss-inbox' ); ?>"><?php echo (count($draft_attachment_data) == '0')?__( 'Attach File', 'buddyboss-inbox' ):__( 'Attach Another File', 'buddyboss-inbox' ); ?> <i class="fa fa-paperclip"></i></a>
				
				<?php if ( $draft_feature && !empty( $draft_attachment_data ) ):
					foreach($draft_attachment_data as $adata):
					
				?>
					<span class="bbm-uploaded-file">
					<?php echo $adata["name"]; ?>
					<a href="#" data-attachment_id="<?php echo $adata["file"]; ?>" class="remove-uploaded-file" title="Remove">x</a>
					</span>
				
				<?php
					endforeach;
				      endif; ?>
				<input type="hidden" name="bbm-attachment-uri" id="bbm-attachment-uri" value='<?php echo!empty( $uri_data ) ? implode("||||||||",$uri_data) : ''; ?>' />
				<input type="hidden" name="bbm-draft-id" id="bbm_draft_id" value="<?php echo $bbm_draft_id; ?>" />
			</div>
			<?php
		}

		public function bb_buddyboss_messages_send_reply( $message = null ) {
			if ( isset( $_POST[ 'send-notice' ] ) ) {
				return;
			}

			if ( isset( $_POST[ 'attachment_uri' ] ) && !empty( $_POST[ 'attachment_uri' ] ) ) {
				
				
				$_POST['attachment_uri'] = explode("||||||||",$_POST['attachment_uri']);
				
				foreach($_POST['attachment_uri']  as $key => $val) {
				    $val = trim($val);
				    if(empty($val)) {
					unset($_POST['attachment_uri'][$key]);
				    }
				}
				
				$attachment_data = array();
				
				foreach($_POST['attachment_uri'] as $attch) {
				    $file = json_decode(stripslashes($attch), true);
				    if ( isset( $file["file"] ) && file_exists( $file["file"] ) ) {
					$attachment_data[] = $file;
				    }
				}
				
				// Save a new message meta
				bp_messages_update_meta( $message->id, '_bbm_file_attached_file', $attachment_data );
				
			}

		}

		/**
		 * Don't keep the uploaded file if sending the message failed
		 */
		function bbm_file_for_messages_delete_file() {
			if ( isset( $_POST[ 'bbm-attachment-uri' ] ) && !empty( $_POST[ 'bbm-attachment-uri' ] ) ) {
				
				$_POST['bbm-attachment-uri'] = explode("||||||||",$_POST['bbm-attachment-uri']);
            
				foreach($_POST['bbm-attachment-uri']  as $key => $val) {
				    $val = trim($val);
				    if(empty($val)) {
					unset($_POST['bbm-attachment-uri'][$key]);
				    }
				}
				
				$attachment_data = false;
				
				foreach($_POST['bbm-attachment-uri'] as $attch) {  
				    $file = json_decode(stripslashes($attch), true);
				    if ( isset( $file["file"] ) && file_exists( $file["file"] ) ) {
					unlink( $file[ 'file' ] );
					unlink( dirname($file[ 'file' ])."/thumb-".basename($file[ 'file' ]) ); //delete thumb
				    }
				}
				
			
			}
		}

		/**
		 * If the message was successfully sent, save the attached file data in a messages meta.
		 */
		function bbm_file_for_messages_attach_file( $message = null ) {
		
			if ( isset( $_POST[ 'bbm-attachment-uri' ] ) && !empty( $_POST[ 'bbm-attachment-uri' ] ) ) {
				
				$_POST['bbm-attachment-uri'] = explode("||||||||",$_POST['bbm-attachment-uri']);
            
				foreach($_POST['bbm-attachment-uri']  as $key => $val) {
				    $val = trim($val);
				    if(empty($val)) {
					unset($_POST['bbm-attachment-uri'][$key]);
				    }
				}
				
				$attachment_data = false;
				
				foreach($_POST['bbm-attachment-uri'] as $attch) {
				    $file = json_decode(stripslashes($attch), true);
				    if ( isset( $file["file"] ) && file_exists( $file["file"] ) ) {
					$attachment_data[] = $file;
				    }
				}
				
				
				bp_messages_update_meta( $message->id, '_bbm_file_attached_file', $attachment_data );
			
			}
		}

		/**
		 * Additional mime types added
		 * @param $mime_types
		 * @return array
		 */
		function buddyboss_message_myme_types( $mime_types ) {

            $bp_messages_slug =  bp_get_messages_slug();

            //Make sure we are on the buddypress message page
            if ( bp_is_current_component( $bp_messages_slug ) )
			    $mime_types = bbm_chosen_file_formats();

			return $mime_types;
		}
		
		
		/*
		 * bbm kses custom filter
		 * @param $content
		 * @return String
		 **/
		function bbm_kses($content) {
			$allowedtags = array(
				'address' => array(),
				'a' => array(
					'href' => true,
					'rel' => true,
					'rev' => true,
					'name' => true,
					'target' => true,
				),
				'abbr' => array(),
				'acronym' => array(),
				'area' => array(
					'alt' => true,
					'coords' => true,
					'href' => true,
					'nohref' => true,
					'shape' => true,
					'target' => true,
				),/*
				'article' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*//*
				'aside' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*//*
				'audio' => array(
					'autoplay' => true,
					'controls' => true,
					'loop' => true,
					'muted' => true,
					'preload' => true,
					'src' => true,
				),*/
				'b' => array(),
				'big' => array(),
				'blockquote' => array(
					'cite' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'br' => array(),
				'button' => array(
					'disabled' => true,
					'name' => true,
					'type' => true,
					'value' => true,
				),
				'caption' => array(
					'align' => true,
				),
				'cite' => array(
					'dir' => true,
					'lang' => true,
				),
				'code' => array(),
				'col' => array(
					'align' => true,
					'char' => true,
					'charoff' => true,
					'span' => true,
					'dir' => true,
					'valign' => true,
					'width' => true,
				),
				'colgroup' => array(
					'align' => true,
					'char' => true,
					'charoff' => true,
					'span' => true,
					'valign' => true,
					'width' => true,
				),
				'del' => array(
					'datetime' => true,
				),
				'dd' => array(),
				'dfn' => array(),
				'details' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'open' => true,
					'xml:lang' => true,
				),
				'div' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'fieldset' => array(),
				'figure' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'figcaption' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'font' => array(
					'color' => true,
					'face' => true,
					'size' => true,
				),/*
				'footer' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*//*
				'form' => array(
					'action' => true,
					'accept' => true,
					'accept-charset' => true,
					'enctype' => true,
					'method' => true,
					'name' => true,
					'target' => true,
				),*/
				'h1' => array(
					'align' => true,
				),
				'h2' => array(
					'align' => true,
				),
				'h3' => array(
					'align' => true,
				),
				'h4' => array(
					'align' => true,
				),
				'h5' => array(
					'align' => true,
				),
				'h6' => array(
					'align' => true,
				),/*
				'header' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'hgroup' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*/
				'hr' => array(
					'align' => true,
					'noshade' => true,
					'size' => true,
					'width' => true,
				),
				'i' => array(),
				'img' => array(
					'alt' => true,
					'align' => true,
					'border' => true,
					'height' => true,
					'hspace' => true,
					'longdesc' => true,
					'vspace' => true,
					'src' => true,
					'usemap' => true,
					'width' => true,
					'class' => true
				),
				'ins' => array(
					'datetime' => true,
					'cite' => true,
				),
				'kbd' => array(),
				'label' => array(
					'for' => true,
				),
				'legend' => array(
					'align' => true,
				),
				'li' => array(
					'align' => true,
					'value' => true,
				),
				'map' => array(
					'name' => true,
				),
				'mark' => array(),
				/*'menu' => array(
					'type' => true,
				),*//*
				'nav' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*/
				'p' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'pre' => array(
					'width' => true,
				),
				'q' => array(
					'cite' => true,
				),
				's' => array(),
				'samp' => array(),
				'span' => array(
					'dir' => true,
					'align' => true,
					'lang' => true,
					'style' => true,
					'xml:lang' => true,
				),/*
				'section' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),*/
				'small' => array(),
				'strike' => array(),
				'strong' => array(),
				'sub' => array(),
				'summary' => array(
					'align' => true,
					'dir' => true,
					'lang' => true,
					'xml:lang' => true,
				),
				'sup' => array(),
				'table' => array(
					'align' => true,
					'bgcolor' => true,
					'border' => true,
					'cellpadding' => true,
					'cellspacing' => true,
					'dir' => true,
					'rules' => true,
					'summary' => true,
					'width' => true,
				),
				'tbody' => array(
					'align' => true,
					'char' => true,
					'charoff' => true,
					'valign' => true,
				),
				'td' => array(
					'abbr' => true,
					'align' => true,
					'axis' => true,
					'bgcolor' => true,
					'char' => true,
					'charoff' => true,
					'colspan' => true,
					'dir' => true,
					'headers' => true,
					'height' => true,
					'nowrap' => true,
					'rowspan' => true,
					'scope' => true,
					'valign' => true,
					'width' => true,
				),
				/*'textarea' => array(
					'cols' => true,
					'rows' => true,
					'disabled' => true,
					'name' => true,
					'readonly' => true,
				),*/
				'tfoot' => array(
					'align' => true,
					'char' => true,
					'charoff' => true,
					'valign' => true,
				),
				'th' => array(
					'abbr' => true,
					'align' => true,
					'axis' => true,
					'bgcolor' => true,
					'char' => true,
					'charoff' => true,
					'colspan' => true,
					'headers' => true,
					'height' => true,
					'nowrap' => true,
					'rowspan' => true,
					'scope' => true,
					'valign' => true,
					'width' => true,
				),
				'thead' => array(
					'align' => true,
					'char' => true,
					'charoff' => true,
					'valign' => true,
				),
				'title' => array(),
				'tr' => array(
					'align' => true,
					'bgcolor' => true,
					'char' => true,
					'charoff' => true,
					'valign' => true,
				),
				'track' => array(
					'default' => true,
					'kind' => true,
					'label' => true,
					'src' => true,
					'srclang' => true,
				),
				'tt' => array(),
				'u' => array(),
				'ul' => array(
					'type' => true,
				),
				'ol' => array(
					'start' => true,
					'type' => true,
				),
				'var' => array(),
				/*'video' => array(
					'autoplay' => true,
					'controls' => true,
					'height' => true,
					'loop' => true,
					'muted' => true,
					'poster' => true,
					'preload' => true,
					'src' => true,
					'width' => true,
				),*/
				);
			
			return wp_kses($content,$allowedtags);
			
		}
		
		function remove_all_wp_editor_buttons() {
			global $bp, $wp_filter;
			if(is_admin()) { return; }
			
			$current_action = bp_current_action();
			if($bp->current_component == 'messages' && $current_action == 'compose'){
			   unset($wp_filter['mce_buttons']);
			   unset($wp_filter['media_buttons']);
			}
			if($bp->current_component == 'messages' && $current_action == 'view'){
			   unset($wp_filter['mce_buttons']);
			   unset($wp_filter['media_buttons']);
			}		
			
		}

	}

}