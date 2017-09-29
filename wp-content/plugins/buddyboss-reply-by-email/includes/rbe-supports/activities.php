<?php
/*
* Provide reply by email support for activities
**/
class buddyboss_rbe_activity_support

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
			$instance = new buddyboss_rbe_activity_support();
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
		if(function_exists( 'bp_is_active' ) AND bp_is_active( 'activity' )) {
	
			if(buddyboss_rbe()->option("activities_support") == "1") {	
		
				add_action("bp_activity_after_save", array($this,"register") , 1);
				add_action("bp_activity_sent_mention_email", array($this,"unregister"));
				add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
		
			}
			
		}
		
	
		add_action("bbrbe_screen_settings",array($this,"setting_screen"));
		add_filter("bbrbe_general_settings_before_save",array($this,"setting_save"));
		
	}
	/*
	* Register the identitiy
	**/
	function register($activity)
	{
		$id = $activity->id;
        try {
            // register
            buddyboss_rbe()->core->register_unique_identity("activities", array(
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
        if($type != "activities"){ #Type check
            return false;
        }
        
        global $wpdb, $bp;
        
	$content = strip_tags($content); //ensure it has no html.
	
	$activity_id = (int) $values[0];
	$get_activity = $wpdb->get_row("SELECT *FROM {$bp->activity->table_name} WHERE id='{$activity_id}'");
        if(!empty($get_activity)) {
            $activity_id = bp_activity_new_comment( array(
                  'activity_id'=> $activity_id,
                  'content' => $content,
                  'user_id' => $userdata->ID 
            ) );    
        }
        
		return true;
	}
	
	/*
	 * Output the screen on general settings
	 * @param array $settings
	 **/
	function setting_screen($settings) {
		$enable = @$settings["activities_support"];
		?>
		<tr>
				<th scope="row"><label for="enabled"><?php _e( 'Activity posts', 'bb-reply-by-email');?></label></th>
				<td>
					<input name="activities_support" type="checkbox" id="activities_support" <?php checked($enable,'1'); ?> value="1"> <?php _e( 'Allow replying to Activity post notifications.', 'bb-reply-by-email');?>
				
					<p class="description">
					<?php
					
					if(!function_exists( 'bp_is_active' ) OR !bp_is_active( 'activity' )) {
						echo sprintf(__( 'Requires <a href="%1$s" target="_blank">BuddyPress</a> plugin & BuddyPress <a href="%2$s" target="_blank">Activity component</a> to enable Activity posts notifications.', 'bb-reply-by-email'),'https://wordpress.org/plugins/buddypress/',admin_url("options-general.php?page=bp-components"));
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
		$_POST["activities_support"] = (isset($_POST["activities_support"]) AND $_POST["activities_support"] == "1")?"1":"0";
		$settings["activities_support"] = $_POST["activities_support"];
		return $settings;
	}
}
$buddyboss_rbe_activity_support = new buddyboss_rbe_activity_support();
$buddyboss_rbe_activity_support->instance();