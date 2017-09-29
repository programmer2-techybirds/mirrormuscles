<?php
/*
* Provide reply by email support for messages
**/
class buddyboss_rbe_message_support

{
	public function __construct()

	{
		// nothing to do
	}
	/**
	 * singleton
	 *
	 * @since BuddyBoss RBE (1.0.0)
	 */
	public static function instance()

	{
		static $instance = null;
		if (null === $instance) {
			$instance = new buddyboss_rbe_message_support();
			$instance->setup();
		}
		return $instance;
	}
	protected function setup()
	{
		if ( ! function_exists( 'bp_is_active' ) ) { return false; }
		$this->hooks();
	}
	/*
	* Contain filters and actions
	* @since BuddyBoss RBE (1.0.0)
	* @uses add_action(),add_filter()
	**/
	function hooks()
	{
		if(function_exists( 'bp_is_active' ) AND bp_is_active( 'messages' )) {
			if(buddyboss_rbe()->option("messages_support") == "1") {	
			
				add_action("messages_message_sent", array($this,"register") , 1);
				add_action("bp_messages_sent_notification_email", array($this,"unregister"));
				add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
		
			}
		}
	
		add_action("bbrbe_screen_settings",array($this,"setting_screen"));
		add_filter("bbrbe_general_settings_before_save",array($this,"setting_save"));
		
	}
	/*
	* Register the identitiy
	**/
	function register(&$message)
	{
		$id = $message->thread_id;
        try {
            // register
            buddyboss_rbe()->core->register_unique_identity("messages", array(
                $id
            ));
        } catch(Exception $e) { return false; }
	}
	/*
	* unregister the identitiy
	**/
	function unregister()
	{
		try {
			// simple unregister
			buddyboss_rbe()->core->unregister_unique_identity();
		    } catch(Exception $e) { return false; }
	}
	/*
	* Icoming function.
	* This function will call when new reply is avaible.
	* @param string $type - return the type of reply.
	* @param object $userdata - return the userdata of the user relates to this reply.
	* @param string $content - return the content of reply.
	* @param array $values - return the extra values of the reply.
	**/
	function receiver($type, $userdata, $content, $values)
	{
        if($type != "messages"){ #Type check
            return false;
        }
        
        global $wpdb, $bp;
        $thread_id = (int) $values[0];
		$get_recipients = $wpdb->get_row("SELECT *FROM {$bp->messages->table_name_recipients} WHERE user_id='{$userdata->ID}' AND thread_id='{$thread_id}' AND is_deleted='0'");
		if (empty($get_recipients)) { 
			return false;
		}
		
        messages_new_message(array(
			'thread_id' => $thread_id,
			'sender_id' => $userdata->ID,
			'content' => $content
		));
        
		return true;
	}
	
	/*
	 * Output the screen on general settings
	 * @param array $settings
	 **/
	function setting_screen($settings) {
		$enable = @$settings["messages_support"];
		?>
		<tr>
				<th scope="row"><label for="enabled"><?php _e( 'Messages', 'bb-reply-by-email');?></label></th>
				<td>
					<input name="messages_support" type="checkbox" id="messages_support" <?php checked($enable,'1'); ?> value="1"> <?php _e( 'Allow replying to Message notifications.', 'bb-reply-by-email');?>
				
					<p class="description">
					<?php
					
					if(!function_exists( 'bp_is_active' ) OR !bp_is_active( 'messages' )) {
						echo sprintf(__( 'Requires <a href="%1$s" target="_blank">BuddyPress</a> plugin & BuddyPress <a href="%2$s" target="_blank">Private Messaging</a> to enable Messages notifications.', 'bb-reply-by-email'),'https://wordpress.org/plugins/buddypress/',admin_url("options-general.php?page=bp-components"));
					}
					
					?>
					</p>
				
				</td>
		</tr>
		<?php 
	}
	
	/*
	 * Save the settings
	 * @param array $settings
	 **/
	function setting_save($settings) {
		$_POST["messages_support"] = (isset($_POST["messages_support"]) AND $_POST["messages_support"] == "1")?"1":"0";
		$settings["messages_support"] = $_POST["messages_support"];
		return $settings;
	}
}
$buddyboss_rbe_message_support = new buddyboss_rbe_message_support();
$buddyboss_rbe_message_support->instance();