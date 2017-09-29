<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss Inbox
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('BuddyBoss_Inbox_Admin')):

    /**
     *
     * BuddyBoss_Inbox_Admin
     * ********************
     *
     *
     */
    class BuddyBoss_Inbox_Admin {
        /* Options/Load
         * ===================================================================
         */

        /**
         * Plugin options
         *
         * @var array
         */
        public $options = array();
		private $plugin_settings_tabs = array();

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
         * @uses BuddyBoss_Inbox_Admin::setup() Init admin class
         *
         * @return object Admin class
         */
        public static function instance() {
            static $instance = null;

            if (null === $instance) {
                $instance = new BuddyBoss_Inbox_Admin;
                $instance->setup();
            }

            return $instance;
        }

        /* Utility functions
         * ===================================================================
         */

        /**
         * Get option
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @param  string $key Option key
         *
         * @uses BuddyBoss_Inbox_Admin::option() Get option
         *
         * @return mixed      Option value
         */
        public function option($key) {
            $value = buddyboss_messages()->option($key);
            return $value;
        }

        /* Actions/Init
         * ===================================================================
         */

        /**
         * Setup admin class
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses buddyboss_messages() Get options from main BuddyBoss_Inbox_Admin class
         * @uses is_admin() Ensures we're in the admin area
         * @uses curent_user_can() Checks for permissions
         * @uses add_action() Add hooks
         */
        public function setup() {
            if ((!is_admin() && !is_network_admin() ) || !current_user_can('manage_options')) {
                return;
            }

            $actions = array(
                'admin_init',
                'admin_menu',
                'network_admin_menu'
            );

            if (isset($_GET['page']) && ( $_GET['page'] == 'buddyboss-inbox/includes/admin.php' )) {
                $actions[] = 'admin_enqueue_scripts';
            }

            foreach ($actions as $action) {
                add_action($action, array($this, $action));
            }
			add_action('admin_init', array($this, 'register_support_settings'));
			
            // add setting link
            $buddyboss = BuddyBoss_Inbox_Plugin::instance();
            $plugin = $buddyboss->basename;
            
            add_filter("plugin_action_links_$plugin", array($this, 'plugin_settings_link'));
        }

        /**
         * Register admin settings
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses register_setting() Register plugin options
         * @uses add_settings_section() Add settings page option sections
         * @uses add_settings_field() Add settings page option
         */
        public function admin_init() {
			
	    $this->plugin_settings_tabs['buddyboss_messages_plugin_options'] = __('General','buddyboss-inbox');
			
            register_setting( 'buddyboss_messages_plugin_options', 'buddyboss_messages_plugin_options', array( $this, 'plugin_options_validate' ));
            add_settings_section( 'general_section', __( 'General Settings', 'buddyboss-inbox' ), array( $this, 'section_general' ), __FILE__ );

            add_settings_field('attachment_feature', __('Attachments','buddyboss-inbox'), array( $this, 'attachment_feature_option' ), __FILE__ , 'general_section' );
            add_settings_field('label_feature', __('Labels','buddyboss-inbox'), array( $this, 'label_feature_option' ), __FILE__ , 'general_section' );
            add_settings_field('draft_feature', __('Drafts','buddyboss-inbox'), array( $this, 'draft_feature_option' ), __FILE__ , 'general_section' );
            add_settings_field('editor_feature', __('Visual Editor','buddyboss-inbox'), array( $this, 'editor_feature_option' ), __FILE__ , 'general_section' );
        }
            
        function register_support_settings() {
                $this->plugin_settings_tabs[ 'buddyboss_messages_support_options' ] = __('Support','buddyboss-inbox');

                register_setting( 'buddyboss_messages_support_options', 'buddyboss_messages_support_options' );
                add_settings_section( 'section_support', ' ', array( &$this, 'section_support_desc' ), 'buddyboss_messages_support_options' );
        }

        function section_support_desc() {
                if ( file_exists( dirname( __FILE__ ) . '/help-support.php' ) ) {
                        require_once( dirname( __FILE__ ) . '/help-support.php' );
                }
        }

		/**
         * Add plugin settings page
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses add_options_page() Add plugin settings page
         */
		
        public function admin_menu() {
			add_submenu_page( 'buddyboss-settings', __('BuddyBoss Inbox','buddyboss-inbox'), __('Inbox','buddyboss-inbox'), 'manage_options', __FILE__, array($this, 'options_page') );
        }

		/**
         * Add plugin settings page
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses BuddyBoss_Inbox_Admin::admin_menu() Add settings page option sections
         */
        public function network_admin_menu() {
            return $this->admin_menu();
        }

        // Add settings link on plugin page
        function plugin_settings_link($links) {
            $links[] = '<a href="'.admin_url("admin.php?page=".__FILE__).'">'.__("Settings","buddyboss-inbox").'</a>';
            return $links;
        }

        /**
         * Register admin scripts
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses wp_enqueue_script() Enqueue admin script
         * @uses wp_enqueue_style() Enqueue admin style
         * @uses buddyboss_messages()->assets_url Get plugin URL
         */
        public function admin_enqueue_scripts() {
            $js = buddyboss_messages()->assets_url . '/js/';
            $css = buddyboss_messages()->assets_url . '/css/';
        }

        /* Settings Page + Sections
         * ===================================================================
         */

        /**
         * Render settings page
         *
         * @since BuddyBoss Inbox (1.0.0)
         *
         * @uses do_settings_sections() Render settings sections
         * @uses settings_fields() Render settings fields
         * @uses esc_attr_e() Escape and localize text
         */
        
        public function options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : __FILE__;
		
		if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'  ) { ?>
			<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
			<p><strong><?php _e("Settings saved.","buddyboss-inbox"); ?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.","buddyboss-inbox"); ?></span></button></div><?php
		}
        ?>
            <div class="wrap">
                <h2><?php _e("BuddyBoss Inbox","buddyboss-inbox"); ?></h2>
				<?php $this->plugin_options_tabs(); ?>
                <form action="options.php" method="post" class="bb-inbox-settings-form">
                   <?php
					if ( 'buddyboss_messages_plugin_options' == $tab || empty($_GET['tab']) ) {
						settings_fields( 'buddyboss_messages_plugin_options' );
						do_settings_sections( __FILE__ ); ?>
						<p class="submit">
							<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e(__("Save Changes","buddyboss-inbox")); ?>" />
						</p><?php
					} else {
						settings_fields( $tab );
						do_settings_sections( $tab );
					} ?>
                    
                </form>
            </div>

            <?php
        }
        
        function plugin_options_tabs() {
                $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'buddyboss_messages_plugin_options';
                
                echo '<h2 class="nav-tab-wrapper">';
                foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
                        $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
                        echo '<a class="nav-tab ' . $active . '" href="?page=' . __FILE__ . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
                }
                echo '</h2>';
        }

	public function attachment_feature_option(){
            $value          =   buddyboss_messages()->option('attachment_feature');
            $file_format    =   buddyboss_messages()->option('attach_file_formats');
            $attachment_preview    =   buddyboss_messages()->option('attachment_preview');
            $file_format    =   (isset($file_format) AND is_array($file_format))?$file_format:array();
            $file_size      =   buddyboss_messages()->option('attach_file_size');
            $file_size      =   (isset($file_size))?$file_size:'';

            $checked = $value ? ' checked="checked" ' : '';

            echo "<input ".$checked." id='attachment_feature' name='buddyboss_messages_plugin_options[attachment_feature]' type='checkbox' />  ";

            _e( 'Attach files to your messages', 'buddyboss-inbox' );
            ?>

            <table class="form-table bb-attachment-submenu">
                
                <tr>
                    <td valign="top" style="vertical-align:top" scope="row" width="20%" ><?php esc_attr_e('Attachments Preview', 'buddyboss-inbox'); ?></td>
                    <td>
                        <input type='radio' <?php checked($attachment_preview,'thumbnail'); ?> name='buddyboss_messages_plugin_options[attachment_preview]' value="thumbnail" /> <?php _e( 'Thumbnail + File Name', 'buddyboss-inbox');?> <br /> <br />
                        <input type='radio' <?php checked($attachment_preview,'filename'); ?> name='buddyboss_messages_plugin_options[attachment_preview]' value="filename" /> <?php _e( 'File Name', 'buddyboss-inbox');?>
                    </td>
                   
                </tr>
                
                <tr>
                    <td scope="row" width="20%"><?php esc_attr_e('Allowed File Formats', 'buddyboss-inbox'); ?></td>
                    <td><input type='checkbox' <?php echo in_array('image', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="image" /> <?php _e( 'Image', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%"></td>
                    <td><input type='checkbox' <?php echo in_array('video', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="video" /> <?php _e( 'Video', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%"></td>
                    <td><input type='checkbox' <?php echo in_array('text', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="text" /> <?php _e( 'Text', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%"></td>
                    <td><input type='checkbox' <?php echo in_array('audio', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="audio" /> <?php _e( 'Audio', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%"></td>
                    <td><input type='checkbox' <?php echo in_array('compressed', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="compressed" /> <?php _e( 'Compressed (PDF, ZIP, Flash, etc.)', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%"></td>
                    <td><input type='checkbox' <?php echo in_array('documents', $file_format) ? 'checked' : '';?> name='buddyboss_messages_plugin_options[attach_file_formats][]' value="documents" /> <?php _e( 'Office Documents (Word, Pages, etc.)', 'buddyboss-inbox');?></td>
                </tr>
                <tr>
                    <td scope="row" width="20%" style="vertical-align: top"><?php esc_attr_e('Maximum File Size', 'buddyboss-inbox'); ?></td>
                    <td>
                        <input type='text' name='buddyboss_messages_plugin_options[attach_file_size]' value="<?php echo $file_size;?>" /> <?php _e( 'MB', 'buddyboss-inbox');?>
                        <p class="description">
                        <?php
                        $info = $this->file_upload_max_size();
                        $max_size_possible = $info['max_size_calculated'] / (1024 * 1024);
                        $max_size_possible = number_format($max_size_possible, 2);
                        printf(__("Based on your server's PHP configuration, maximum file size cannot exceed <strong>%s MB.</strong>", 'buddyboss-inbox'), $max_size_possible);
                        ?>
                        </p>
                    </td>
                </tr>
            </table>

        <?php
        }

        public function label_feature_option(){
            $value = buddyboss_messages()->option('label_feature');

            $checked = '';

            if ( $value ){
                $checked = ' checked="checked" ';
            }

            echo "<input ".$checked." id='label_feature' name='buddyboss_messages_plugin_options[label_feature]' type='checkbox' />  ";

            _e( 'Organize your messages into labelled categories', 'buddyboss-inbox' );
        }

        public function draft_feature_option(){
            $value = buddyboss_messages()->option('draft_feature');
            $autosave_value = buddyboss_messages()->option('draft_autosave');

            $checked = $value ? ' checked="checked" ' : '';
            $autosave_checked = $autosave_value ? ' checked="checked" ' : '';

            echo "<input ".$checked." id='draft_feature' name='buddyboss_messages_plugin_options[draft_feature]' type='checkbox' />  ";

            _e( 'Save unsent messages as drafts', 'buddyboss-inbox' );
        ?>

            <table class="form-table bb-drafts-submenu">
                <tr>
                    <td scope="row" width="20%"><?php esc_attr_e('Auto Save', 'buddyboss-inbox'); ?></td>
                    <td><input type='checkbox' <?php echo $autosave_checked;?> id='draft_autosave' name='buddyboss_messages_plugin_options[draft_autosave]' /> <?php esc_attr_e('Enable auto save', 'buddyboss-inbox'); ?></td>
                </tr>
            </table>

        <?php
        }

        public function editor_feature_option(){
            $value = buddyboss_messages()->option('editor_feature');

            $checked = '';

            if ( $value ){
                $checked = ' checked="checked" ';
            }

            echo "<input ".$checked." id='editor_feature' name='buddyboss_messages_plugin_options[editor_feature]' type='checkbox' />  ";

            _e( 'Add formatting to your messages', 'buddyboss-inbox' );
        }

        /**
         * General settings section
         *
         * @since BuddyBoss Wall (1.0.0)
         */
        public function section_general(){

        }

        /**
         * Validation for Maximum File Size
         * @param $input
         * @return mixed
         */
        public function plugin_options_validate( $input ) {
            $input['attach_file_size'] = ( float ) $input['attach_file_size'] ? ( float ) $input['attach_file_size'] : 2;

            /* check for maximum post size and upload size restriction */
            $info = $this->file_upload_max_size();
            if ( $info['max_size_calculated'] < ( $input['attach_file_size'] * 1024 * 1024 ) ) {
                $input['attach_file_size'] = $info['max_size_calculated'] / (1024 * 1024);
                $input['attach_file_size'] = number_format($input['attach_file_size'], 2);
            }

            if ( !isset($input['load-css']) || !$input['load-css'] ) {
                $input['load-css'] = 'no';
            }
            return $input; //no validations for now
        }

        /**
         * Calculate max file upload size in php.ini config
         * @return array
         */
        private function file_upload_max_size() {
            // Start with post_max_size.
            $max_size_calculated = $post_max_size = $this->return_bytes(ini_get('post_max_size'));

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->return_bytes(ini_get('upload_max_filesize'));
            if ( $upload_max > 0 && $upload_max < $post_max_size ) {
                $max_size_calculated = $upload_max;
            }

            return array(
                'post_max_size' => $post_max_size,
                'upload_max_filesize' => $upload_max,
                'max_size_calculated' => $max_size_calculated,
            );
        }

        private function return_bytes( $val ) {
            $val = trim($val);
            $last = strtolower($val[strlen($val) - 1]);
            switch ( $last ) {
                // The 'G' modifier is available since PHP 5.1.0
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
            return $val;
        }



    }

// End class BuddyBoss_Inbox_Admin
endif;