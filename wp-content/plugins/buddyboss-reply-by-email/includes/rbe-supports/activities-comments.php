<?php
/*
* Provide reply by email support for activities comments
**/
class buddyboss_rbe_activity_comment_support

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
			$instance = new buddyboss_rbe_activity_comment_support();
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
		if ( function_exists( 'bp_is_active' ) AND bp_is_active( 'activity' ) ) { 
	
			if(buddyboss_rbe()->option("activities_comments_support") == "1") {	
		
				add_action("bp_activity_comment_posted", array($this,"register") , 1);
				add_action("bp_activity_comment_posted", array($this,"unregister"),15);
				add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
		
			}
			
		} 
	
		add_action("bbrbe_screen_settings",array($this,"setting_screen"));
		add_filter("bbrbe_general_settings_before_save",array($this,"setting_save"));
		
	}
	/*
	* Register the identitiy
	**/
	function register($comment_id)
	{
		$id = $comment_id;
        try {
            // register
            buddyboss_rbe()->core->register_unique_identity("activitiesc", array(
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
        if($type != "activitiesc"){ #Type check
            return false;
        }
         
        global $wpdb, $bp;
	
	$content = strip_tags($content); //ensure it has no html.
	
        $comment_id = (int) $values[0];
	$get_activity_comment = $wpdb->get_row("SELECT *FROM {$bp->activity->table_name} WHERE id='{$comment_id}' AND type='activity_comment'");
	$get_activity = $wpdb->get_row("SELECT *FROM {$bp->activity->table_name} WHERE id='{$get_activity_comment->item_id}'");
	
	$parent_id = $comment_id;
//	$thread_comments_depth = get_option("thread_comments_depth"); @todo have to check depth and stop.
	
        if(!empty($get_activity)) {
            $activity_id = bp_activity_new_comment( array(
                  'activity_id'=> $get_activity->id,
                  'content' => $content,
                  'user_id' => $userdata->ID,
		  'parent_id'	=> $parent_id
            ) );    
        }
        
		return true;
	} 
	
	/*
	 * Output the screen on general settings
	 * @param array $settings
	 **/
	function setting_screen($settings) {
		$enable = @$settings["activities_comments_support"];
		
		?>
		<tr>
				<th scope="row"><label for="enabled"><?php _e( 'Activity comments', 'bb-reply-by-email');?></label></th>
				<td>
					<input name="activities_comments_support" type="checkbox" id="activities_comments_support" <?php checked($enable,'1'); ?> value="1"> <?php _e( 'Allow replying to Activity comment notifications.', 'bb-reply-by-email');?> 
				
					<p class="description">
					<?php
					
					if(!function_exists( 'bp_is_active' ) OR !bp_is_active( 'activity' )) {
						echo sprintf(__( 'Requires <a href="%1$s" target="_blank">BuddyPress</a> plugin & BuddyPress <a href="%2$s" target="_blank">Activity component</a> to enable Activity comments notifications.', 'bb-reply-by-email'),'https://wordpress.org/plugins/buddypress/',admin_url("options-general.php?page=bp-components"));
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
		$_POST["activities_comments_support"] = (isset($_POST["activities_comments_support"]) AND $_POST["activities_comments_support"] == "1")?"1":"0";
		$settings["activities_comments_support"] = $_POST["activities_comments_support"];
		return $settings;
	}
}
$buddyboss_rbe_activity_comment_support = new buddyboss_rbe_activity_comment_support();
$buddyboss_rbe_activity_comment_support->instance();