<?php
/**
 * All custom functions
 */
function bbm_draft_delete_link($draft_id){
    return bp_loggedin_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() . '?draft_delete=' . $draft_id;
}

function bbm_get_time_bp_format($raw_time){
    return bp_format_time( strtotime( $raw_time ) );
}

function bbm_drafts_get_recipient_tabs($usernames) {
    $recipients = explode( ' ', $usernames );

    foreach ( $recipients as $recipient ) {

        $user_id = bp_is_username_compatibility_mode()
            ? bp_core_get_userid( $recipient )
            : bp_core_get_userid_from_nicename( $recipient );

        if ( ! empty( $user_id ) ) : ?>

            <li id="un-<?php echo esc_attr( $recipient ); ?>" class="friend-tab">
				<span><?php
                    echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 15, 'height' => 15 ) );
                    echo bp_core_get_userlink( $user_id );
                    ?></span>
            </li>

        <?php endif;
    }
}

function bbm_drafts_check_recipients($usernames) {
    $recipients = explode( ' ', $usernames );
    $recipient_ids = array();
    foreach ( $recipients as $recipient ) {

        $user_id = bp_is_username_compatibility_mode()
            ? bp_core_get_userid( $recipient )
            : bp_core_get_userid_from_nicename( $recipient );

        if ( ! empty( $user_id ) ) {
            $recipient_ids[] = $user_id;
        }
    }
    return $recipient_ids;
}

function bbm_get_user_drafts(){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id, OBJECT );
    return $results;
}

function bbm_get_user_draft_ids(){
    $draft_ids = array();
    $all_drfats = bbm_get_user_drafts();
    if( isset($all_drfats ) && !empty($all_drfats ) ){
        foreach($all_drfats as $single){
            $draft_ids[] = $single->thread_id;
        }
    }
    return $draft_ids;
}

function bbm_draft_check_exists($thread_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_var( 'SELECT COUNT(*) FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id.' AND thread_id = '.$thread_id );
    return $results;
}

function bbm_create_new_draft($draft_content, $attachment_data='', $thread_id='', $recipients='', $draft_subject='', $draft_uniqid=''){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $new_values = array(
        'thread_id' => $thread_id,
        'user_id' => $user_id,
        'recipients' => $recipients,
        'draft_subject' => $draft_subject,
        'draft_content' => $draft_content,
        'draft_attachment' => $attachment_data,
        'draft_date' => bp_core_current_time(),
        'draft_uniqid' => $draft_uniqid
    );
    $format = array('%d','%d','%s','%s','%s','%s','%s','%s');
    $wpdb->insert(bp_core_get_table_prefix()."bp_messages_drafts", $new_values, $format);
}

function bbm_update_draft_content($bbm_draft_id, $draft_content, $attachment_data='', $recipients='', $draft_subject='', $draft_uniqid=''){
    global $wpdb;
    $wpdb->update(
        bp_core_get_table_prefix().'bp_messages_drafts',
        array(
            'draft_content' => $draft_content,
            'draft_attachment' => $attachment_data,
            'recipients' => $recipients,
            'draft_subject' => $draft_subject,
            'draft_date' => bp_core_current_time(),
            'draft_uniqid' => $draft_uniqid
        ),
        array( 'bbm_draft_id' => $bbm_draft_id ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        ),
        array( '%d' )
    );
}

function bbm_update_draft_col($col_name, $col_val, $where_col_name, $where_col_val){
    global $wpdb;
    $wpdb->query(
        "
	UPDATE ".bp_core_get_table_prefix()."bp_messages_drafts
	SET ".$col_name." = '".$col_val."'
	WHERE ".$where_col_name." = ".$where_col_val."
	"
    );
}

function bbm_draft_user_compose_drafts(){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id.' AND thread_id = 0', OBJECT );
    return $results;
}

function bbm_draft_row_by_draft_id($bbm_draft_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_row( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id.' AND bbm_draft_id = '.$bbm_draft_id, OBJECT );
    return $results;
}

function bbm_draft_row_by_uniqid($uniqid){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_row( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id.' AND draft_uniqid = "'.$uniqid.'" ', OBJECT );
    return $results;
}

function bbm_draft_col_by_thread_id($thread_id, $col_name){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_row( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_drafts WHERE user_id = '.$user_id.' AND thread_id = '.$thread_id, OBJECT );
    return !isset($results->$col_name) ? '' : $results->$col_name;
}

function bbm_delete_draft_by_thread_id($thread_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $wpdb->query(
        $wpdb->prepare(
        "
         DELETE FROM ".bp_core_get_table_prefix()."bp_messages_drafts
		 WHERE thread_id = %d
		 AND user_id = %d
		",
            $thread_id, $user_id
        )
    );
}

function bbm_delete_draft_by_draft_id($bbm_draft_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $wpdb->query(
        $wpdb->prepare(
        "
         DELETE FROM ".bp_core_get_table_prefix()."bp_messages_drafts
		 WHERE bbm_draft_id = %d
		 AND user_id = %d
		",
            $bbm_draft_id, $user_id
        )
    );
}

function bbm_get_user_labels(){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_labels WHERE user_id = '.$user_id, OBJECT );
    $counts = $wpdb->get_results( "SELECT label.label_id,count(label.label_id) as total FROM `".bp_core_get_table_prefix()."bp_messages_label_message` as label WHERE label.user_id='{$user_id}' AND label.thread_id IN (SELECT messages.thread_id as thread_id FROM `".bp_core_get_table_prefix()."bp_messages_recipients` as messages where messages.thread_id = label.thread_id AND messages.user_id = label.user_id AND messages.is_deleted='0')  GROUP BY label.label_id", OBJECT );

    foreach((array)$counts as $key => $val) {
	$counts[$val->label_id] = $val;
	//unset($counts[$key]);
    }
    //merge count in results
    foreach((array)$results as $key => $val) {
	
	if(isset($counts[$val->bbm_label_id])) {
	    $results[$key]->total = $counts[$val->bbm_label_id]->total;
	} else {
	    $results[$key]->total = 0;
	}
	
    }
    return $results;
}

function bbm_create_new_label($label_name, $user_id){
    global $wpdb;
	$label_class_count = get_option('bb-messages-label-count');
	if ( empty( $label_class_count )  || '21' == $label_class_count ) {
		$label_class_count = '1'; 
	}
	$label_class = 'label-color-'.$label_class_count; 
	
    $new_values = array(
        'user_id' => $user_id,
        'label_name' => $label_name,
		'label_class' => $label_class,
    );
    $format = array('%d','%s','%s');
    $wpdb->insert(bp_core_get_table_prefix()."bp_messages_labels", $new_values, $format);
	
	update_option('bb-messages-label-count', (int)$label_class_count + 1 );
    return isset( $wpdb->insert_id ) && !empty( $wpdb->insert_id ) ? $wpdb->insert_id : '';
}

function bbm_get_label_by_id($label_id){
    global $wpdb;
    $result = $wpdb->get_row( 'SELECT label_name FROM '.bp_core_get_table_prefix().'bp_messages_labels WHERE bbm_label_id = '.$label_id );
    return isset( $result->label_name ) && !empty( $result->label_name ) ? $result->label_name : '';
}

function bbm_get_label_class_by_id($label_id){
    global $wpdb;
    $result = $wpdb->get_row( 'SELECT label_class FROM '.bp_core_get_table_prefix().'bp_messages_labels WHERE bbm_label_id = '.$label_id );
    return isset( $result->label_class ) && !empty( $result->label_class ) ? $result->label_class : '';
}

function bbm_get_message_labels($thread_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE user_id = '.$user_id.' AND thread_id = '.$thread_id, OBJECT );
    return $results;
}

function bbm_get_messages_by_label_id($label_id){
    global $wpdb;
    $user_id = bp_loggedin_user_id();
    $results = $wpdb->get_results( 'SELECT * FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE label_id = '.$label_id, OBJECT );
    return $results;
}

function bbm_label_check_exists($thread_id, $label_id, $user_id){
    global $wpdb;
    $results = $wpdb->get_var( 'SELECT COUNT(*) FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE user_id = '.$user_id.' AND label_id = '.$label_id.' AND thread_id = '.$thread_id );
    return $results;
}

function bbm_label_remove_exists($thread_id, $user_id){
    global $wpdb;
    $wpdb->query('DELETE FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE user_id = '.$user_id.' AND thread_id = '.$thread_id );
}

function bbm_label_remove_label($thread_id, $label_id, $user_id){
    global $wpdb;
    $wpdb->query('DELETE FROM '.bp_core_get_table_prefix().'bp_messages_label_message WHERE label_id = '.$label_id.' AND user_id = '.$user_id.' AND thread_id = '.$thread_id );
}

function bbm_label_add_label($thread_id, $label_id, $user_id){
    global $wpdb;
    $new_values = array(
        'thread_id' => $thread_id,
        'user_id' => $user_id,
        'label_id' => $label_id,
    );
    $format = array('%d','%d','%d');
    $wpdb->insert(bp_core_get_table_prefix()."bp_messages_label_message", $new_values, $format);
}

/**
 * Text to be cloned to textarea
 * @param $initArray
 * @return mixed
 */
function bbm_tiny_mce_before_init( $initArray ){
    $draft_feature = buddyboss_messages()->option('draft_feature');
    $draft_autosave = buddyboss_messages()->option('draft_autosave');
    $current_action = bp_current_action();

    if($draft_feature && $draft_autosave && $current_action == 'compose') {

        $initArray['setup'] = <<<JS
[function(ed) {
    ed.onKeyUp.add(window.bbm_draft_leavetype);
    ed.onKeyDown.add(window.bbm_draft_entertype);

}][0]
JS;
    } elseif($draft_feature && $draft_autosave && $current_action == 'view') {

        $initArray['setup'] = <<<JS
[function(ed) {
    ed.onKeyUp.add(window.bbm_draft_leavetype);
    ed.onKeyDown.add(window.bbm_draft_entertype);
    ed.onChange.add(function(ed, l) {
        var reply_val = tinyMCE.get('message_content').getContent();
        jQuery('#send-reply #message_content').val(reply_val);
    });

}][0]
JS;
    } else {

    $initArray['setup'] = <<<JS
[function(ed) {
    ed.onChange.add(function(ed, l) {
        var reply_val = tinyMCE.get('message_content').getContent();
        jQuery('#send-reply #message_content').val(reply_val);
    });

}][0]
JS;
    }

    return $initArray;
}

/**
 * Output the markup for the message type dropdown.
 */
function bbm_messages_options() {
    ?>

    <label for="message-type-select" class="bp-screen-reader-text">
        <?php _e( 'Select:', 'buddyboss-inbox' ) ?>
    </label>

    <select name="message-type-select" id="message-type-select">
        <option value=""><?php _e( 'None', 'buddyboss-inbox' ); ?></option>
        <option value="all"><?php _ex('All', 'Message dropdown filter', 'buddyboss-inbox') ?></option>
    </select> 

    <button id="delete_<?php echo bp_current_action(); ?>_messages"><?php _e( 'Delete Selected', 'buddyboss-inbox' ); ?></button>

<?php
}

/**
 * Prepare chosen formats
 * @param $chosen_formats
 */
function bbm_chosen_file_formats(){

    $file_formats = array();
    $chosen_format = buddyboss_messages()->option('attach_file_formats');

    $image = array(
        'jpg|jpeg|jpe'                 => 'image/jpeg',
        'gif'                          => 'image/gif',
        'png'                          => 'image/png',
        'bmp'                          => 'image/bmp',
        'tif|tiff'                     => 'image/tiff',
        'ico'                          => 'image/x-icon',
    );

    $video = array(
        'asf|asx'                      => 'video/x-ms-asf',
        'wmv'                          => 'video/x-ms-wmv',
        'wmx'                          => 'video/x-ms-wmx',
        'wm'                           => 'video/x-ms-wm',
        'avi'                          => 'video/avi',
        'divx'                         => 'video/divx',
        'flv'                          => 'video/x-flv',
        'mov|qt'                       => 'video/quicktime',
        'mpeg|mpg|mpe'                 => 'video/mpeg',
        'mp4|m4v'                      => 'video/mp4',
        'ogv'                          => 'video/ogg',
        'webm'                         => 'video/webm',
        'mkv'                          => 'video/x-matroska',
		'3gp'						   => 'video/3gpp',
		'3g2'						   => 'video/3gpp2'
    );

    $text = array(
        'txt|asc|c|cc|h'               => 'text/plain',
        'csv'                          => 'text/csv',
        'tsv'                          => 'text/tab-separated-values',
        'ics'                          => 'text/calendar',
        'rtx'                          => 'text/richtext',
        'css'                          => 'text/css',
        'htm|html'                     => 'text/html',
    );

    $audio = array(
        'mp3|m4a|m4b'                  => 'audio/mpeg',
        'ra|ram'                       => 'audio/x-realaudio',
        'wav'                          => 'audio/wav',
        'ogg|oga'                      => 'audio/ogg',
        'mid|midi'                     => 'audio/midi',
        'wma'                          => 'audio/x-ms-wma',
        'wax'                          => 'audio/x-ms-wax',
        'mka'                          => 'audio/x-matroska',
		'3gp'						   => 'audio/3gpp',
		'3g2'						   => 'audio/3gpp2'
    );

    $compressed = array(
        'rtf'                          => 'application/rtf',
        'js'                           => 'application/javascript',
        'pdf'                          => 'application/pdf',
        'swf'                          => 'application/x-shockwave-flash',
        'class'                        => 'application/java',
        'tar'                          => 'application/x-tar',
        'zip'                          => 'application/zip',
        'gz|gzip'                      => 'application/x-gzip',
        'rar'                          => 'application/rar',
        '7z'                           => 'application/x-7z-compressed',
        'exe'                          => 'application/x-msdownload',
    );

    $documents = array(
        'doc'                          => 'application/msword',
        'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
        'wri'                          => 'application/vnd.ms-write',
        'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
        'mdb'                          => 'application/vnd.ms-access',
        'mpp'                          => 'application/vnd.ms-project',
        'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
        'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
        'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',

        // OpenOffice formats
        'odt'                          => 'application/vnd.oasis.opendocument.text',
        'odp'                          => 'application/vnd.oasis.opendocument.presentation',
        'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
        'o dg'                          => 'application/vnd.oasis.opendocument.graphics',
        'odc'                          => 'application/vnd.oasis.opendocument.chart',
        'odb'                          => 'application/vnd.oasis.opendocument.database',
        'odf'                          => 'application/vnd.oasis.opendocument.formula',

        // WordPerfect formats
        'wp|wpd'                       => 'application/wordperfect',

        // iWork formats
        'key'                          => 'application/vnd.apple.keynote',
        'numbers'                      => 'application/vnd.apple.numbers',
        'pages'                        => 'application/vnd.apple.pages',
    );
	
	if ( empty( $chosen_format ) ) {
		return false;
	}

    if( in_array('image', $chosen_format) && is_array( $image ) )
        $file_formats = array_merge($file_formats, $image);

    if( in_array('video', $chosen_format) && is_array( $video ) )
        $file_formats = array_merge($file_formats, $video);

    if( in_array('text', $chosen_format) && is_array( $text ) )
        $file_formats = array_merge($file_formats, $text);

    if( in_array('audio', $chosen_format) && is_array( $audio ) )
        $file_formats = array_merge($file_formats, $audio);

    if( in_array('compressed', $chosen_format) && is_array( $compressed ) )
        $file_formats = array_merge($file_formats, $compressed);

    if( in_array('documents', $chosen_format) && is_array( $documents ) )
        $file_formats = array_merge($file_formats, $documents);

    return $file_formats;
}

function bbm_plupload_file_formats(){
    $file_formats = array();
    $get_file_formats = bbm_chosen_file_formats();
    if( isset( $get_file_formats ) && is_array( $get_file_formats ) ){
        $file_formats = array_keys($get_file_formats);
    }
    if( isset( $file_formats ) && !empty( $file_formats ) ){
        foreach($file_formats as $key => $val){
            $file_formats[$key] = str_replace('|', ',', $val);
        }
    }
    return $file_formats;
}

function bbm_inbox_pagination() {
		?>
		
		<div class="pagination no-ajax" id="user-pag">

			<div class="pag-count" id="messages-dir-count">
				<?php bp_messages_pagination_count(); ?>
			</div>

			<div class="pagination-links" id="messages-dir-pag">
				<?php bp_messages_pagination(); ?>
			</div>

		</div><!-- .pagination -->

		<?php do_action( 'bp_after_member_messages_pagination' ); ?>
	<?php
}

function bbm_format_size_units() {
    $attach_file_size = buddyboss_messages()->option('attach_file_size');
    // convert MB to KB
    $attach_file_size = $attach_file_size * 1024;
    // convert KB to bytes
    $attach_file_size = $attach_file_size * 1024;
    // get only integer val
    $attach_file_size = intval($attach_file_size);
    // return
    return $attach_file_size.'b';
}


/**
 * Apply stylesheet to the visual editor.
 *
 * @uses add_editor_style() Links a stylesheet to visual editor
 */

function bbm_tinymce_style( $content ) {
	
	if ( 'messages' != bp_current_component() ) {
		return $content;
	}
	
	add_editor_style( buddyboss_messages()->assets_url . '/css/bb-inbox-editor-style.css' );

	// This is for front-end tinymce customization
	if ( ! is_admin() ) {
		global $editor_styles,$stylesheet;
		$editor_styles = ( array ) $editor_styles;
		$stylesheet = ( array ) $stylesheet;

		$stylesheet[] = buddyboss_messages()->assets_url . '/css/bb-inbox-editor-style.css';

		$editor_styles = array_merge( $editor_styles, $stylesheet );
	}
	return $content;
}

add_filter( 'the_editor_content', 'bbm_tinymce_style' );

function bp_messages_inbox_labels_list() {
	global $messages_template;
	$message_inbox_link = bp_displayed_user_domain() . bp_get_messages_slug() . '/inbox/';
	$all_labels = array();
	$thread_id = isset( $messages_template->thread->thread_id ) && ! empty( $messages_template->thread->thread_id ) ? $messages_template->thread->thread_id : '';
	if ( ! empty( $thread_id ) ) {
		$get_thread_label = bbm_get_message_labels( $thread_id );
	}

	if ( isset( $get_thread_label ) && ! empty( $get_thread_label ) ) {
		foreach ( $get_thread_label as $single ) {
			$single_label_id = isset( $single->label_id ) && ! empty( $single->label_id ) ? $single->label_id : '';
			$all_labels[] = array(
				'id' => $single_label_id,
				'name' => bbm_get_label_by_id( $single_label_id ),
			);
		}
	}

	if ( ! empty( $all_labels ) ) {
		$label_names = array();
		$label = "";
		foreach ( $all_labels as $single ) {
			$class = bbm_get_label_class_by_id( $single[ 'id' ] );
			if ( empty( $single[ 'name' ] ) ) {
				continue;
			} //skip empties
			$label_names[] = "<span class='bbm-label " . $class . "'><a href='" . $message_inbox_link . "?label_id=" . $single[ 'id' ] . "'>" . $single[ 'name' ] . "</a></span>";
		}
		$label .= implode( ' ', $label_names );
		echo apply_filters( "buddyboss_inbox_message_label", $label );
	}
}

//Admin style in a function to avoid adding css file for one line
add_action('admin_head', 'bbm_settings_style');

function bbm_settings_style() { ?>
	<style>
		.settings_page_buddyboss-inbox-includes-admin .form-table th { width: 0; }
	</style><?php
}

function bbm_get_message_attachments() {
    global $bbm_message_attachments;
    return $bbm_message_attachments;
}
