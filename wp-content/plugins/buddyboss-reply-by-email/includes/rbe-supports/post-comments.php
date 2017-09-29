<?php
/*
* Provide reply by email support for post comments
**/
class buddyboss_rbe_post_comment_support

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
			$instance = new buddyboss_rbe_post_comment_support();
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
	
		if(buddyboss_rbe()->option("post_comments_support") == "1") {	
		
		add_filter("comment_notification_text", array($this,"register") , 1, 2);
		add_filter("comment_post_redirect", array($this,"unregister"));
		add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
	
		}
	
		add_action("bbrbe_screen_settings",array($this,"setting_screen"));
		add_filter("bbrbe_general_settings_before_save",array($this,"setting_save"));
		
	}
	/*
	* Register the identitiy
	**/
	function register($notify_message, $comment_id)
	{
		$id = $comment_id;
        try {
            // register
            buddyboss_rbe()->core->register_unique_identity("postcmt", array(
                $id
            ));
        } catch(Exception $e) { return $notify_message; }
	
	return $notify_message;

	}
	/*
	* unregister the identitiy
	**/
	function unregister($l)
	{
		try {
            // simple unregister
            buddyboss_rbe()->core->unregister_unique_identity();
        } catch(Exception $e) { return $l; }
		
		return $l;
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
        if($type != "postcmt"){ #Type check
            return false;
        }
        
        global $wpdb, $bp;
        $comment_id = (int) $values[0];
	$comment = get_comment( $comment_id );
	
	
	if(empty($comment)) {
		return;
	}
	
	$commentdata = array(
	'comment_post_ID' => $comment->comment_post_ID, // to which post the comment will show up
	'comment_author' => $userdata->display_name, //fixed value - can be dynamic 
	'comment_author_email' => $userdata->user_email, //fixed value - can be dynamic 
	'comment_content' => $content, //fixed value - can be dynamic 
	'comment_type' => '', //empty for regular comments, 'pingback' for pingbacks, 'trackback' for trackbacks
	'comment_parent' => $comment_id, //0 if it's not a reply to another comment; if it's a reply, mention the parent comment ID here
	'user_id' => $userdata->ID, //passing current user ID or any predefined as per the demand
	);
	$comment_id = wp_new_comment( $commentdata );
        
	
		return true;
	}
	
	/*
	 * Output the screen on general settings
	 * @param array $settings
	 **/
	function setting_screen($settings) {
		$enable = @$settings["post_comments_support"];
		?>
		<tr>
				<th scope="row"><label for="enabled"><?php _e( 'WordPress Post comments', 'bb-reply-by-email');?></label></th>
				<td>
					<input name="post_comments_support" type="checkbox" id="post_comments_support" <?php checked($enable,'1'); ?> value="1"> <?php _e( 'Allow replying to WordPress post comment notifications.', 'bb-reply-by-email');?>
					<p class="description">
					<?php
					
					if(!class_exists("CWS_STC")) {
						echo sprintf(__( 'Requires <a href="%1$s" target="_blank">Subscribe to Comments</a> plugin to enable comment notifications for best experience.', 'bb-reply-by-email'),'https://wordpress.org/plugins/subscribe-to-comments/');
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
		$_POST["post_comments_support"] = (isset($_POST["post_comments_support"]) AND $_POST["post_comments_support"] == "1")?"1":"0";
		$settings["post_comments_support"] = $_POST["post_comments_support"];
		return $settings;
	}
}
$buddyboss_rbe_post_comment_support = new buddyboss_rbe_post_comment_support();
$buddyboss_rbe_post_comment_support->instance();