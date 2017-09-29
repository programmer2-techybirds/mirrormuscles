<?php
/*
* Provide reply by email support for plugin Subscribe to Comments
* Plugin URL: https://wordpress.org/plugins/subscribe-to-comments/
* This class has dependance on post-comments
* last update: 21-Nov-2015
**/
class buddyboss_plugin_support_subscribe_to_comments

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
			$instance = new buddyboss_plugin_support_subscribe_to_comments();
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
	
		if(buddyboss_rbe()->option("post_comments_support") == "1" AND class_exists("CWS_STC")) {	
		
                    add_filter("comment_post", array($this,"register") , 2, 9);
                    add_filter("wp_set_comment_status", array($this,"register") , 2, 2);
                    
                    add_filter("comment_post_redirect", array($this,"unregister"),12);
                    add_filter("wp_set_comment_status", array($this,"unregister"),12);
                    add_action("bbrbe_new_reply", array($this,"receiver") , 10, 4);
	
		}
	
		
	}
	/*
	* Register the identitiy
	**/
	function register($comment_id)
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
	
	
}
$buddyboss_plugin_support_subscribe_to_comments = new buddyboss_plugin_support_subscribe_to_comments();
$buddyboss_plugin_support_subscribe_to_comments->instance();