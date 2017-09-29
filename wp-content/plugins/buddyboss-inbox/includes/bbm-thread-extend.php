<?php
/**
 * Description of BP_Messages_Thread_Extends
 *
 * @author BuddyBoss
 */
class BP_Messages_Thread_Extend extends BP_Messages_Thread {

    /**
     * Get current message threads for a user.
     *
     * @since BuddyBoss Inbox (1.0.0)
     *
     * @param array $args {
     *     Array of arguments.
     *     @type int    $user_id      The user ID.
     *     @type string $box          The type of mailbox to get. Either 'inbox' or 'sentbox'.
     *                                Defaults to 'inbox'.
     *     @type string $type         The type of messages to get. Either 'all' or 'unread'
     *                                or 'read'. Defaults to 'all'.
     *     @type int    $limit        The number of messages to get. Defaults to null.
     *     @type int    $page         The page number to get. Defaults to null.
     *     @type string $search_terms The search term to use. Defaults to ''.
     *     @type array  $meta_query   Meta query arguments. See WP_Meta_Query for more details.
     * }
     * @return array|bool Array on success. Boolean false on failure.
     */
    public static function get_current_threads_for_user($args = array()) {
        global $wpdb, $bp;
        
        // Backward compatibility with old method of passing arguments
	if ( ! is_array( $args ) || func_num_args() > 1 ) {
		$old_args_keys = array(
			0 => 'user_id',
			1 => 'box',
			2 => 'type',
			3 => 'limit',
			4 => 'page',
			5 => 'search_terms',
		);
		$func_args = func_get_args();
               
		$args      = bp_core_parse_args_array( $old_args_keys, $func_args );
         
	}

	$defaults = array(
		'user_id'      => false,
		'box'          => 'inbox',
		'type'         => 'all',
		'limit'        => null,
		'page'         => null,
		'search_terms' => '',
		'meta_query'   => array()
	    );
        $r = wp_parse_args( $args, $defaults );
        
        extract($r);
        
        $user_id_sql = $pag_sql = $type_sql = $search_sql = '';

        if ($limit && $page) {
            $pag_sql = $wpdb->prepare(" LIMIT %d, %d", intval(( $page - 1 ) * $limit), intval($limit));
        }

        if ($type == 'unread') {
            $type_sql = " AND r.unread_count != 0 ";
        } elseif ($type == 'read') {
            $type_sql = " AND r.unread_count = 0 ";
        }

        if (!empty($search_terms)) {
            $search_terms_like = '%' . bp_esc_like($search_terms) . '%';
            $search_sql = $wpdb->prepare("AND ( subject LIKE %s OR message LIKE %s )", $search_terms_like, $search_terms_like);
        }

        if ('sentbox' == $box) {
            $user_id_sql = $wpdb->prepare('m.sender_id = %d', $user_id);
            $thread_ids = $wpdb->get_results("SELECT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND m.sender_id = r.user_id AND {$user_id_sql} AND r.is_deleted = 0 {$search_sql} GROUP BY m.thread_id ORDER BY date_sent DESC {$pag_sql}");
            $total_threads = $wpdb->get_var("SELECT COUNT( DISTINCT m.thread_id ) FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND m.sender_id = r.user_id AND {$user_id_sql} AND r.is_deleted = 0 {$search_sql} ");

        } elseif('inbox' == $box) {
            $user_id_sql = $wpdb->prepare('r.user_id = %d', $user_id);
            $thread_ids = $wpdb->get_results("SELECT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND r.is_deleted = 0 AND {$user_id_sql} AND r.sender_only = 0 {$type_sql} {$search_sql} GROUP BY m.thread_id ORDER BY date_sent DESC {$pag_sql}");
            $total_threads = $wpdb->get_var("SELECT COUNT( DISTINCT m.thread_id ) FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND r.is_deleted = 0 AND {$user_id_sql} AND r.sender_only = 0 {$type_sql} {$search_sql}");

        } elseif('drafts' == $box){
            
            $user_id_sql = $wpdb->prepare('r.user_id = %d', $user_id);
            $thread_ids_inbox = $wpdb->get_results("SELECT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND r.is_deleted = 0 AND {$user_id_sql} AND r.sender_only = 0 {$type_sql} {$search_sql} GROUP BY m.thread_id ORDER BY date_sent DESC {$pag_sql}");
            $thread_ids_sentbox = $wpdb->get_results("SELECT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND m.sender_id = r.user_id AND {$user_id_sql} AND r.is_deleted = 0 {$search_sql} GROUP BY m.thread_id ORDER BY date_sent DESC {$pag_sql}");

            $thread_ids = array_merge((array) $thread_ids_inbox,(array) $thread_ids_sentbox);
            $draft_ids = bbm_get_user_draft_ids();
            $total_drafts = 0;
            if( isset($thread_ids) && !empty($thread_ids) && is_array($draft_ids) ){
                foreach($thread_ids as $key => $val){
                    if( !in_array($val->thread_id, $draft_ids) ){
                        unset($thread_ids[$key]);
                    }
                }
                $total_drafts = count($draft_ids);
            }
            $thread_ids = array_values($thread_ids);
            $total_threads = intval($total_drafts);
        }

        // if label feature enabled & need filtered by label_id
        $label_feature = buddyboss_messages()->option('label_feature');
        $label_id  = filter_input(INPUT_GET, 'label_id', FILTER_SANITIZE_STRING);
        if( $label_feature && isset($label_id) && !empty($label_id) ){
            $label_threads = array();
            $label_thread_ids = bbm_get_messages_by_label_id($label_id);

            if(!empty($label_thread_ids)){
                foreach($label_thread_ids as $single){
                    $label_threads[] = $single->thread_id;
                }
            }

            //Get threads id by label
            $thread_ids_sql = "SELECT * FROM ( (SELECT DISTINCT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND r.is_deleted = 0 AND {$user_id_sql} AND r.sender_only = 0 {$type_sql} {$search_sql} AND m.thread_id IN (".implode(",",$label_threads).") GROUP BY m.thread_id)
                                      UNION (SELECT m.thread_id, MAX(m.date_sent) AS date_sent FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND m.sender_id = r.user_id AND {$user_id_sql} AND r.is_deleted = 0 {$search_sql} AND m.thread_id IN (".implode(",",$label_threads).") GROUP BY m.thread_id)
                                       ) s GROUP BY s.thread_id ORDER BY s.date_sent DESC {$pag_sql}";

            //echo $thread_ids_sql;

            $thread_ids = $wpdb->get_results( $thread_ids_sql );

            //Count total threads by label
            $thread_ids_cnt_sql = "SELECT COUNT(DISTINCT c.thread_id) FROM ( (SELECT m.thread_id FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND r.is_deleted = 0 AND {$user_id_sql} AND r.sender_only = 0 {$type_sql} {$search_sql} AND m.thread_id IN (".implode(",",$label_threads).") GROUP BY m.thread_id)
                                      UNION (SELECT m.thread_id FROM {$bp->messages->table_name_recipients} r, {$bp->messages->table_name_messages} m WHERE m.thread_id = r.thread_id AND m.sender_id = r.user_id AND {$user_id_sql} AND r.is_deleted = 0 {$search_sql} AND m.thread_id IN (".implode(",",$label_threads).") GROUP BY m.thread_id) ) c";

            $total_threads = $wpdb->get_var( $thread_ids_cnt_sql );

/*
            if( isset($thread_ids) && !empty($thread_ids) ){
                foreach($thread_ids as $key => $val){
		
                    if( !in_array($val->thread_id, $label_threads) ){
                        unset($thread_ids[$key]);
                    }
                }
            }
  */
            $thread_ids = array_values($thread_ids);

        }


        if (empty($thread_ids)) {
            return false;
        }

        // Sort threads by date_sent
        foreach ((array) $thread_ids as $thread) {
            $sorted_threads[$thread->thread_id] = strtotime($thread->date_sent);
        }

        arsort($sorted_threads);

        $threads = false;
        foreach ((array) $sorted_threads as $thread_id => $date_sent) {
            $threads[] = new BP_Messages_Thread($thread_id);
        }

        return array('threads' => &$threads, 'total' => (int) $total_threads);
    }

}