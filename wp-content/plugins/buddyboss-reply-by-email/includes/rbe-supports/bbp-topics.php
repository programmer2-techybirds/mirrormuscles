<?php
/*
* Provide reply by email support for bbpress topics
**/
class buddyboss_rbe_bbp_topic_support

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
			$instance = new buddyboss_rbe_bbp_topic_support();
			$instance->setup();
		}
		return $instance;
	}
	protected function setup()
	{
		$this->hooks();
	}
	/*
	* Contain filters and actions
	* @since BuddyBoss RBE (1.0.0)
	* @uses add_action(),add_filter()
	**/
	function hooks()
	{
	
		if(function_exists("bbpress")) { //feature only available when bbpress plugin is activated.
			
			if(buddyboss_rbe()->option("bbp_topic_support") == "1") {	
			
				add_action("bbp_pre_notify_subscribers", array($this,"register") , 1, 3);
				add_action("bbp_post_notify_subscribers", array($this,"unregister"));
				add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
		
			}
		
		}
	
		add_action("bbrbe_screen_settings",array($this,"setting_screen"));
		add_filter("bbrbe_general_settings_before_save",array($this,"setting_save"));
		
	}
	/*
	* Register the identitiy
	**/
	function register($reply_id, $topic_id, $user_ids)
	{
		$id = $topic_id;
		try {
		    // register
		    buddyboss_rbe()->core->register_unique_identity("bbptopic", array(
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
        if($type != "bbptopic"){ #Type check
            return false;
        }
        
        global $wpdb, $bp;
		
		$topic_id = (int) $values[0];
		
        $reply_data = array(
			'post_parent'    => $topic_id, // topic ID
			'post_status'    => bbp_get_public_status_id(),
			'post_type'      => bbp_get_reply_post_type(),
			'post_author'    => $userdata->ID,
			'post_password'  => '',
			'post_content'   => $content,
			'post_title'     => '',
			'menu_order'     => 0,
			'comment_status' => 'closed'
		);
		
		$reply = bbp_insert_reply( $reply_data );
		$topic = get_post($topic_id);

        // update other data
        bbp_update_reply($reply, $topic_id);

        add_filter("bbp_get_reply_url",array($this,"bbp_get_reply_url"),2,2);
		
		bbp_notify_topic_subscribers($reply, $topic_id , $topic->post_parent ); //send notification
		
		remove_filter("bbp_get_reply_url",array($this,"bbp_get_reply_url"),2,2);

		return true;
		
	}
	
	/*
	 * Helper for tricking the virtual bbpost reply url.
	 **/
	function bbp_get_reply_url($url,$reply_id) {
		$reply = get_post($reply_id);
		return get_permalink($reply->post_parent);
	}
	
	/*
	 * Output the screen on general settings
	 * @param array $settings
	 **/
	function setting_screen($settings) {
		$enable = @$settings["bbp_topic_support"];
		?>
		<tr>
				<th scope="row"><label for="enabled"><?php _e( 'bbPress topics', 'bb-reply-by-email');?></label></th>
				<td>
					<input name="bbp_topic_support" type="checkbox" id="bbp_topic_support" <?php checked($enable,'1'); ?> value="1"> <?php _e( 'Allow replying to bbPress forum notifications.', 'bb-reply-by-email');?>
					<p class="description">
					<?php
					
					if(!function_exists("bbpress")) {
						echo sprintf(__( 'Requires <a href="%1$s" target="_blank">bbPress</a> plugin to enable bbPress topics notifications.', 'bb-reply-by-email'),'https://wordpress.org/plugins/bbpress/');
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
		$_POST["bbp_topic_support"] = (isset($_POST["bbp_topic_support"]) AND $_POST["bbp_topic_support"] == "1")?"1":"0";
		$settings["bbp_topic_support"] = $_POST["bbp_topic_support"];
		return $settings;
	}
}
$buddyboss_rbe_bbp_topic_support = new buddyboss_rbe_bbp_topic_support();
$buddyboss_rbe_bbp_topic_support->instance();