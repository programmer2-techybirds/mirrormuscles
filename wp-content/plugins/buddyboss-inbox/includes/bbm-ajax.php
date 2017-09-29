<?php
/**
 * All ajax callbacks
 */
function bbm_attachment_ajax_callback(){

    if ( isset($_POST['task']) && $_POST['task'] == 'remove_attachment' ) {
        $attachment_data = isset($_POST['attachment_id']) && !empty($_POST['attachment_id']) ? $_POST['attachment_id'] : '';
        $bbm_draft_id = isset($_POST['bbm_draft_id']) && !empty($_POST['bbm_draft_id']) ? $_POST['bbm_draft_id'] : '';
        if ( isset( $attachment_data ) && file_exists( $attachment_data ) ) {
            $deleted = unlink( $attachment_data);
            unlink( dirname($attachment_data)."/thumb-".basename($attachment_data) ); //delete thumb
        }

        if($deleted){
            bbm_update_draft_col('draft_attachment', '', 'bbm_draft_id', $bbm_draft_id);
            _e( 'Attachment deleted successfully!', 'buddyboss-inbox' );
        }

        exit;
    }

}

// upload attachment
function bb_buddyboss_message_attachment_ajax() {
    if ( !is_user_logged_in() ) {
        echo '-1';
        return false;
    }

    $attachment = new BB_Messages_Attachment();

    /**
     * Everything is in place to upload the file
     * @see Custom_Attachment->__construct()
     *
     * - custom errors > eg : only upload file containing custom in their name,
     * - max upload file > eg: 512000,
     * - location in /wp-content/uploads > eg: '/wp-content/uploads/custom',
     * - allowed mime types > eg: array( 'png', 'jpg' )
     */
    $_POST['action'] = 'bbm_attachment_upload';
    $file = $attachment->upload( $_FILES );
    

    // Display the error and do not send the message
    if ( ! empty( $file['error'] ) ) {
        bp_core_add_message( $file['error'], 'error' );

        // The file was successfully uploaded!!
    } else {
        //generate the thumbnail
        
        $thumb = $attachment->generate_thumbnails($file);
        
        $file['name'] = basename($file['url']);
        $file['thumb'] = $thumb;
        
        echo htmlspecialchars( json_encode( $file ), ENT_NOQUOTES );
    }


    exit(0);
}

function bbm_draft_ajax_callback(){
    global $wpdb;

    // Bulk delete drafts
    if ( isset($_POST['task']) && $_POST['task'] == 'bulk_delete_drafts' ) {

        $draft_ids = array();
        $get_draft_ids = !isset($_POST['draft_ids']) ? '' : $_POST['draft_ids'];
        if( !empty($get_draft_ids) ){
            $draft_ids = explode(',', $get_draft_ids);
        }

        if( !empty($draft_ids) ){
            foreach($draft_ids as $single){
                bbm_delete_draft_by_draft_id($single);
            }
        }

        exit;
    }

    // for reply screen
    if ( isset($_POST['task']) && $_POST['task'] == 'save_as_draft' ) {

        $thread_id = !isset($_POST['thread_id']) ? '' : $_POST['thread_id'];
        $draft_content = !isset($_POST['draft_content']) ? '' : $_POST['draft_content'];
        
        if( isset($_POST['bbm_attachment']) && !empty($_POST['bbm_attachment']) ){
            
            $_POST['bbm-attachment-uri'] = explode("||||||||",$_POST['bbm-attachment-uri']);
            
            foreach($_POST['bbm-attachment-uri']  as $key => $val) {
                $val = trim($val);
                if(empty($val)) {
                    unset($_POST['bbm-attachment-uri'][$key]);
                }
            }
            
            $attachment_data  = array();
            
            foreach($_POST['bbm-attachment-uri'] as $attch) {
                $file = json_decode(stripslashes($attch), true);
                if ( isset( $file["file"] ) && file_exists( $file["file"] ) ) {
                    $attachment_data[] = $file;
                }
            }
            
        }
        
        $attachment_data = isset($attachment_data) && !empty($attachment_data) ? serialize($attachment_data) : '';
        $user_id = bp_loggedin_user_id();

        if(!empty($thread_id) &&
            !empty($user_id) &&
            (
                !empty($draft_content) ||
                !empty($attachment_data)
            )
        ){
            $bbm_draft_id = bbm_draft_col_by_thread_id($thread_id, 'bbm_draft_id');
            if( !empty($bbm_draft_id) ){
                bbm_update_draft_content($bbm_draft_id, $draft_content, $attachment_data);
                _e( 'All changes saved.', 'buddyboss-inbox' );
            }else{
                bbm_create_new_draft($draft_content, $attachment_data, $thread_id);
                _e( 'All changes saved.', 'buddyboss-inbox' );
            }
        }

        exit;
    }

    // for compose screen
    if ( isset($_POST['task']) && $_POST['task'] == 'compose_save_as_draft' ) {
        $form_data = array();
        $user_id = bp_loggedin_user_id();
        parse_str($_POST['form_data'], $form_data);
        $draft_subject = $form_data['subject'];
        $draft_content = ( isset($_POST['draft_content']) && !empty($_POST['draft_content']) ) ? $_POST['draft_content'] : '';
        $recipients = ( isset($_POST['recipients']) && !empty($_POST['recipients']) ) ? $_POST['recipients'] : $form_data['send-to-input'];
        $bbm_draft_id = ( isset($_POST['draft_id']) && !empty($_POST['draft_id']) ) ? $_POST['draft_id'] : '';

        if( isset($form_data['bbm-attachment-uri']) && !empty($form_data['bbm-attachment-uri']) ){
            
            $form_data['bbm-attachment-uri'] = explode("||||||||",$form_data['bbm-attachment-uri']);
            
            foreach($form_data['bbm-attachment-uri']  as $key => $val) {
                $val = trim($val);
                if(empty($val)) {
                    unset($form_data['bbm-attachment-uri'][$key]);
                }
            }
            
            foreach($form_data['bbm-attachment-uri'] as $attch) {
                $file = json_decode(stripslashes($attch), true);
                if ( isset( $file["file"] ) && file_exists( $file["file"] ) ) {
                    $attachment_data[] = $file;
                }
            }
           
            
        }
        $attachment_data = isset($attachment_data) && !empty($attachment_data) ? serialize($attachment_data) : '';

        $draft_uniqid = ( isset($_POST['draft_uniqid']) && !empty($_POST['draft_uniqid']) ) ? $_POST['draft_uniqid'] : '';
        if(!empty($draft_uniqid) && empty($bbm_draft_id)){
            $get_draft_detail = bbm_draft_row_by_uniqid($_POST['draft_uniqid']);
            $bbm_draft_id = isset( $get_draft_detail->bbm_draft_id ) && !empty( $get_draft_detail->bbm_draft_id ) ? $get_draft_detail->bbm_draft_id : '';
        }

        if( !empty($user_id) &&
            (
                !empty($recipients) ||
                !empty($draft_subject) ||
                !empty($draft_content) ||
                !empty($attachment_data)
            )
        ){
            if(!empty($bbm_draft_id)){
                bbm_update_draft_content($bbm_draft_id, $draft_content, $attachment_data, $recipients, $draft_subject, $draft_uniqid);
                _e( 'Draft updated successfully!', 'buddyboss-inbox' );
            }else{
                bbm_create_new_draft($draft_content, $attachment_data, '', $recipients, $draft_subject, $draft_uniqid);
                _e( 'Draft saved successfully!', 'buddyboss-inbox' );
            }
        }

        exit;
    }

}

function bbm_label_ajax_callback(){

    if ( isset($_POST['task']) && $_POST['task'] == 'bbm_label_add_message' ) {

        $thread_id = !isset($_POST['thread_id']) ? '' : $_POST['thread_id'];
        $label_id = !isset($_POST['label_id']) ? '' : $_POST['label_id'];
        $user_id = bp_loggedin_user_id();
        $prepare_data = array();

        // add label for this thread
        if(!empty($thread_id) && !empty($label_id) && !empty($user_id)){
            bbm_label_add_label($thread_id, $label_id, $user_id);
            // prepare new label html
            $label_name = bbm_get_label_by_id($label_id);
            $message_inbox_link = bp_displayed_user_domain() . bp_get_messages_slug() . '/inbox/';
            $class = bbm_get_label_class_by_id($label_id);
            $prepare_data['label_html'] = '<span class="'.$class.'"><a href="'.$message_inbox_link.'?label_id='.$label_id.'">'.$label_name.'</a></span>';
            $prepare_data['message'] = __( 'Label added to conversation', 'buddyboss-inbox' );
        }
        echo json_encode($prepare_data);
        exit;
    }


    if ( isset($_POST['task']) && $_POST['task'] == 'bbm_label_remove_message' ) {

        $thread_id = !isset($_POST['thread_id']) ? '' : $_POST['thread_id'];
        $label_id = !isset($_POST['label_id']) ? '' : $_POST['label_id'];
        $user_id = bp_loggedin_user_id();
        $prepare_data = array();

        // remove label for this message
        if(!empty($thread_id) && !empty($label_id) && !empty($user_id)){
            bbm_label_remove_label($thread_id, $label_id, $user_id);
            // prepare response data
			$prepare_data['label_class'] = bbm_get_label_class_by_id($label_id);
            $prepare_data['message'] = __( 'Label removed from conversation', 'buddyboss-inbox' );
        }
        echo json_encode($prepare_data);
        exit;
    }


    if ( isset($_POST['task']) && $_POST['task'] == 'add_new_label' ) {

        $thread_id = !isset($_POST['thread_id']) ? '' : $_POST['thread_id'];
        $label_name = !isset($_POST['label_name']) ? '' : $_POST['label_name'];
        $user_id = bp_loggedin_user_id();
        $prepare_data = array();

        if(empty($label_name)) { //label can't be empty.
            $prepare_data['label_id'] = '';
            $prepare_data['message'] =__( 'Label cannot be empty!', 'buddyboss-inbox' );
            echo json_encode($prepare_data);
            exit;
        }
        
        // create label for this message
        if(!empty($label_name) && !empty($user_id)){
            $label_id = bbm_create_new_label($label_name, $user_id);
        }

        // add label for this thread
        if(!empty($thread_id) && !empty($label_id) && !empty($user_id)){
            bbm_label_add_label($thread_id, $label_id, $user_id);
            $prepare_data['label_id'] = $label_id;
            $prepare_data['message'] =__( 'Label created successfully!', 'buddyboss-inbox' );
        }
        echo json_encode($prepare_data);
        exit;
    }

}

function bbm_delete_label_ajax_callback() {
	
	if ( isset( $_POST[ 'label_id' ] ) ) {

		$label_id = $_POST[ 'label_id' ];
		$user_id = bp_loggedin_user_id();
		$prepare_data = array();

		// remove label for this message
		if ( !empty( $user_id ) ) {
			
			global $wpdb;
			//Removing labels from messages
			$wpdb->query('DELETE FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE label_id = '.$label_id.' AND user_id = '.$user_id );
			
			//Deleting the label
			$wpdb->query('DELETE FROM '.bp_core_get_table_prefix().'bp_messages_labels WHERE bbm_label_id = '.$label_id.' AND user_id = '.$user_id );
			
			// prepare response data
			$prepare_data[ 'message' ] = __( 'Label deleted successfully', 'buddyboss-inbox' );
		}
		echo json_encode( $prepare_data );
		exit;
	}
	
}
