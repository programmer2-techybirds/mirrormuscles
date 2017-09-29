<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss Inbox
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('BuddyBoss_Inbox_Drafts')):

    /**
     * BuddyBoss_Inbox_Drafts
     * ********************
     */
    class BuddyBoss_Inbox_Drafts {

        private $actions = array(
            'bp_setup_nav',
        );
        private $filters = array(
        );

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
         * @uses BuddyBoss_Inbox_Drafts::setup() Init admin class
         *
         * @return object Admin class
         */
        public static function instance() {
            static $instance = null;

            if (null === $instance) {
                $instance = new BuddyBoss_Inbox_Drafts();
                $instance->setup();
                $instance->do_action();
                $instance->do_filter();
            }

            return $instance;
        }

        public function do_action() {
            foreach ($this->actions as $action) {
                add_action($action, array($this, $action));
            }
        }

        public function do_filter() {
            foreach ($this->filters as $function => $filter) {
                if (is_numeric($function)) {
                    $function = $filter;
                }
                add_filter($filter, array($this, $function));
            }
        }

        public function setup() {
            add_action( 'bp_after_messages_compose_content', array($this, 'add_draft_button') );
            add_action( 'bp_after_message_reply_box', array($this, 'add_draft_button') );
            add_filter( 'bp_has_message_threads', array($this, 'bp_has_message_threads'), 2, 3 );
            add_filter( 'bp_get_messages_content_value', array($this, 'display_draft_content'), 10, 1 );
            add_action( 'messages_message_after_save', array($this, 'delete_this_thread_draft') );
            add_action( 'messages_delete_thread', array( $this, 'messages_delete_thread_draft' ), 10, 1 );

            //Delete(migrate) draft from wp_bp_messages_drafts for deleted threads
            add_action( 'admin_init', array( $this, 'messages_delete_old_thread_draft' ), 10, 1 );
            add_action( 'admin_notices', array( $this, 'admin_migration_notice' ) );

            // all actions
            add_action('bp_actions', array($this, 'bbm_delete_draft') );
            // ajax operations
            add_action('wp_ajax_bbm_draft_ajax', 'bbm_draft_ajax_callback');
            add_action('wp_ajax_nopriv_bbm_draft_ajax', 'bbm_draft_ajax_callback');
        }

        public function bbm_delete_draft(){
            if (bp_displayed_user_id() == bp_loggedin_user_id()) {
                $messages_component = bp_is_current_component('messages');
                $drafts_action = bp_is_current_action('drafts');
                if( $messages_component && $drafts_action && isset($_GET['draft_delete']) && !empty($_GET['draft_delete']) ){
                    bbm_delete_draft_by_draft_id($_GET['draft_delete']);
                    bp_core_redirect( trailingslashit( bp_displayed_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ) );
                }
            }
        }

        public function delete_this_thread_draft($msg){
            if( isset( $_POST['send-notice'] ) ){
                return;
            }
            $thread_ID = (int) $_REQUEST['thread_id'];
            $draft_id = ( isset($_POST['compose_draft_id']) && !empty($_POST['compose_draft_id']) ) ? $_POST['compose_draft_id'] : '';
            if( $thread_ID == 0 && !empty($draft_id) ){
                bbm_delete_draft_by_draft_id($draft_id);
            }else{
                bbm_delete_draft_by_thread_id($thread_ID);
            }
        }

        //Delete draft on message thread delete
        public function messages_delete_thread_draft( $thread_id ) {
            bbm_delete_draft_by_thread_id( $thread_id );
        }

        public function display_draft_content($content){
            global $thread_template;
            $thread_id = isset( $thread_template->thread->thread_id ) && !empty( $thread_template->thread->thread_id ) ? $thread_template->thread->thread_id : '';
            if( !empty($thread_id) ){
                $content = bbm_draft_col_by_thread_id($thread_id, 'draft_content');
            }
            return $content;
        }

        public function add_draft_button(){
            echo '<input type="button" name="save_as_draft" id="save_as_draft" value="'.__("Save Draft","buddyboss-inbox").'" />';
        }

        public function bp_setup_nav() {
            global $bp;
            // get drafts count
            $drafts_count = 0;
            $user_drafts = bbm_get_user_draft_ids();
            if( isset($user_drafts) && is_array($user_drafts) ){
                $drafts_count = count($user_drafts);
            }
			if ( $drafts_count == '0' ) {
				$draft_name = sprintf(__('Drafts', 'buddyboss-inbox'));
			} else {
				if ( function_exists( 'boss' ) ) {
					$draft_name = sprintf(__('Drafts <span> (%s) </span>', 'buddyboss-inbox'), $drafts_count);
				} else {
					$draft_name = sprintf(__('Drafts <span>%s</span>', 'buddyboss-inbox'), $drafts_count);
				}
			}
			
            bp_core_new_subnav_item(
                array(
                    'name' => $draft_name,
                    'slug' => 'drafts',
                    'parent_url' => trailingslashit(bp_displayed_user_domain() . $bp->messages->slug),
                    'parent_slug' => 'messages',
                    'screen_function' => array($this, 'groups_screen_group_members'),
                    'position' => 60,
                    'user_has_access' => true,
                    'item_css_id' => 'drafts'
                )
            );
        }

        function groups_screen_group_members() {
            add_action('bp_template_content', array($this, 'bp_template_content'));
            bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
        }

        function bp_template_content() {
            ob_start();

            bbm_message_load_template("drafts");

            $content = ob_get_contents();

            ob_end_clean();

            echo $content;            
        }

        function bp_has_message_threads($has_threads, $messages_not_use, $args) {
            global $messages_template;

            if (!isset($args['drafts'])) {
                return $has_threads;
            }

            // The default box the user is looking at
            if (bp_is_current_action('sentbox')) {
                $default_box = 'sentbox';
            } elseif (bp_is_current_action('notices')) {
                $default_box = 'notices';
            } elseif (bp_is_current_action('drafts')) {
                $default_box = 'drafts';
            } else {
                $default_box = 'inbox';
            }

            // Parse the arguments
            $r = bp_parse_args($args, array(
                'user_id' => bp_loggedin_user_id(),
                'box' => $default_box,
                'per_page' => 10,
                'max' => false,
                'type' => 'all',
                'search_terms' => isset($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : '',
                'page_arg' => 'mpage',
            ), 'has_message_threads');

            // If trying to access notices without capabilities, redirect to root domain
            if (bp_is_current_action('notices') && !bp_current_user_can('bp_moderate')) {
                bp_core_redirect(bp_displayed_user_domain());
            }

            // Load the messages loop global up with messages
            $messages_template = new BP_Messages_Box_Template_Extend(
                $r['user_id'], 'drafts', $r['per_page'], $r['max'], $r['type'], $r['search_terms'], $r['page_arg']
            );

            remove_filter('bp_has_message_threads', array($this, 'bp_has_message_threads'), 2);

            return apply_filters('bp_has_message_threads', $messages_template->has_threads(), $messages_template, $r);
        }

        //Process deletion(migration) of drafts for deleted threads
        function messages_delete_old_thread_draft() {
            global $wpdb;

            if ( ! isset( $_REQUEST['bbm-drafts-migrate'] ) ) return;

            $bbm_draft_migrated = get_option( '_bbm_db_draft_delete_migrate' );

            if ( empty( $bbm_draft_migrated ) ) {

                //Select all draft id of deleted thread
                $draft_ids = $wpdb->get_col("SELECT bbm_draft_id FROM {$wpdb->base_prefix}bp_messages_drafts d WHERE d.thread_id != 0 AND d.thread_id IN (SELECT m.thread_id FROM {$wpdb->base_prefix}bp_messages_recipients m WHERE m.user_id = d.user_id AND is_deleted = 1 )");

                if( ! empty( $draft_ids ) ) {

                    //Delete draft for deleted thread
                    $draft_ids__in = implode( ',', $draft_ids );
                    $retval = $wpdb->query("DELETE FROM {$wpdb->base_prefix}bp_messages_drafts WHERE bbm_draft_id IN ({$draft_ids__in});");
                }

                //Set _bbm_db_draft_delete_migrate to true after migration goes successfully
                update_option( '_bbm_db_draft_delete_migrate', true );
            }
        }


        //Drafts migration admin notices
        function admin_migration_notice(){
            $status = get_option('_bbm_db_draft_delete_migrate');

            //Show admin notice for delete(migrate) draft for deleted threads
            if ( current_user_can( 'manage_options' ) && empty( $status ) ) {
                echo ( '<div class="notice notice-error"><p><strong>BuddyBoss Inbox</strong>: ' . __( 'Please Migrate your Database', 'buddyboss-inbox' ) . ' <a href="' . add_query_arg( array( 'bbm-drafts-migrate' => 'true' ) ) . '">' . __( 'Click Here', 'buddyboss-inbox' ) . '</a> </p></div>' );
            }

            //Show admin notice after drafts delete migration done
            if ( isset( $_REQUEST['bbm-drafts-migrate'] )  ) {
                echo ( '<div class="notice notice-success is-dismissible"><p><strong>BuddyBoss Inbox:</strong> '. __( 'Migration done successfully!', 'buddyboss-inbox' ) .'</div>' );
            }

        }

    }

    endif;