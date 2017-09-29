<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 *
 * Handle all core functionality 
 **/

 if ( ! class_exists( 'BuddyBoss_rbe_core' ) ):
 
class BuddyBoss_rbe_core {
                
		
		
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
	     * Singleton
	     *
	     * @since BuddyBoss RBE (1.0.0)
	     *
	     * @uses BuddyBoss_rbe_core::setup() Init admin class
	     *
	     * @return object core class
	     */
	public static function instance() { 
		static $instance = null;
     
		if ( null === $instance ) {
			$instance = new BuddyBoss_rbe_core;
			$instance->setup();
		}
     
		return $instance;
	}
			
			
			/**
	     * Setup core class
	     *
	     * @since BuddyBoss RBE (1.0.0)
	     */
	public function setup() {
		$this->hooks();
	}
	
	
	/**
	     * Contain action and filters
	     * @since BuddyBoss RBE (1.0.0)
	     **/
	public function hooks() {
		add_action( 'admin_notices', array($this,'admin_notices') );
		add_filter( 'wp_mail_from', array($this,"mail_from"),99 );
		add_filter( "wp_mail", array($this,"wp_mail"),99 );  
		add_filter( "bp_email_set_template", array($this,"bp_email_set_template"),99 );  
		add_action( "phpmailer_init", array($this,"mail_identity_integration"),999 );  
		add_action( "bp_email_validate", array($this,"bp_email_validate"),999,2 );  
		add_action( "bbrbe_schedule", array($this,"remove_expire_tokens") ); 
		add_action( "template_redirect", array($this,"init") );
		add_action( "buddyboss_rbe_option_mail_service", array($this,"buddyboss_rbe_option_mail_service"));
		add_filter(	"bp_send_email_delivery_class",array($this,"bp_send_email_delivery_class"));
		//add_action("admin_init",array($this,"init"));
	}
	
	function init() {
		
	}
	
	/*
	 * Set the from email
	 * @uses BuddyBoss RBE 1.0.0
	 **/
	public function mail_from($original_email_address) {
	    $new_email_address = buddyboss_rbe()->option("server_email");
	    if(empty($new_email_address)) {
		return $original_email_address; //return orignal is not confrigure.
	    }
	    return buddyboss_rbe()->option("server_email"); 
	}
	
	/*
	 * Api helper to register unqiue identity
	 * @uses buddyboss_rbe()
	 * @param string type
	 * @param array $data data for the identity
	 **/ 
	
	public function register_unique_identity($type,$data,$allow_emails=array()) {
	    if(empty($type)) {  return false; }
	    if(empty($data)) {  return false; }
	    
	    $id = $this->generate_unique_idenity_string($type,$data);
	    
	    buddyboss_rbe()->__set("unique_identity",$id);
	    
	    return true;
	}
	
	/*
	 * Api helper to unregister unique identity
	 * @uses buddyboss_rbe()
	 **/
	public function unregister_unique_identity() {
	    if(empty($type)) { return false; }
		
	    buddyboss_rbe()->__set("unique_identity",'');
	    buddyboss_rbe()->__set("current_type",'');
	    buddyboss_rbe()->__set("current_token_id",'');
	    buddyboss_rbe()->__set("current_token_allow_emails",array());
	    
	    return false;
	}
    
	/**
	 * Generated the unqiue idenitiy on based of data given
	 * @param string $type type of identity group
	 * @param array $data data for the identity
	 **/
	
	public function generate_unique_idenity_string($type,$data) {
	    global $wpdb;
	    
	    if(empty($type)) {  return false; }
	    if(empty($data)) {  return false; }
	    
	    $token = $this->generate_token();
	    
	    $now = gmdate('Y-m-d H:i:s');
	    
	    $wpdb->insert($wpdb->base_prefix.'bbrbe_token',array(
								'token_id'	=>	$token,
								'date'		=> 	$now,
								'type'		=>	$type
							    ));
	    
	    $identity[] = "bbrbe";
	    $identity[] = $type;
	    $identity[] = $token;
	    
	    foreach($data as $d) {
		if(!empty($d)) {
		    if(is_string($d) || is_numeric($d)) {
			$identity[] = $d;
		    }
		}                        
	    }
	    
	    $id = implode("-",$identity);
	    
	    buddyboss_rbe()->__set("current_token_id",$token); 
	    buddyboss_rbe()->__set("current_type",$type); 
	    
	    return apply_filters("rbe_generate_unique_idenity_string",$id,$identity);
	    
	}
	
	/*
	 * Generate the unique token 
	 * @since BuddyBoss RBE 1.0.0
	 **/
	function generate_token() {
		
		$unique = uniqid();
		$md5 = substr(md5(get_bloginfo("url")),rand(5, 10),rand(15, 30));
		$unique2 = substr(uniqid(mt_rand(), true),0,5);
		
		$token = substr($unique.$md5.$unique2,0,30);
		
		return apply_filters("rbe_generate_token",$token);
		
	}
	
	/*
	 * Helper for updating allowed emails into the token
	 * @since BuddyBoss RBE 1.0.2
	 * @param $allow_emails (Array having one and more emails)
	 **/
	function update_token_allow_emails($allow_emails=array()) {
		global $wpdb;
		
		//do this to share one token with other mail sent in same time period
		$old_allow_emails = (array) buddyboss_rbe()->__get("current_token_allow_emails");
		$allow_emails = array_merge($old_allow_emails,$allow_emails);
		$allow_emails = array_unique($allow_emails);		
		buddyboss_rbe()->__set("current_token_allow_emails",$allow_emails);		
		
		$token =  buddyboss_rbe()->__get("current_token_id"); 
		$type = buddyboss_rbe()->__get("current_type"); 
		
		$wpdb->update($wpdb->base_prefix.'bbrbe_token',array(
								"allow_emails"=>implode(",",$allow_emails)
								),
							  array(
								'token_id'=>$token,
								'type'=>$type
								));
		
	}
	
	/*
	 * Remove expire tokens
	 * @since BuddyBoss RBE 1.0.0
	 * This function will delete all expire tokens for security reason.
	 **/
	
	function remove_expire_tokens() {
		global $wpdb;
		
		$wpdb->query("DELETE FROM {$wpdb->base_prefix}bbrbe_token WHERE date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
	}
	
	
	/**
	 * Filters and Modify the wp_mail default params
	 * @since BuddyBoss RBE 1.0.0
	 * @param compact details
	 **/
	
	public function wp_mail($data) {
		global $wpdb;
		
		$id = buddyboss_rbe()->__get("unique_identity");
	   
		if(empty($id)) {   return $data;   } //only run below code when needed.
		
		$headers = $data["headers"];
		
		//slit reply to from custom header if there any.
		if ( !is_array( $headers ) ) {
				$data["headers"] = str_replace(array("Reply-To:","Reply-To :"),"",$headers); //remove it so this header info become invalid.
		}
		
		if(!is_array($data["headers"])) {
			$data["headers"] = array();
			
			if(!empty($headers)) { /* add if there was any string single header was there. */
				$data["headers"][] = $headers;
			}
		}
		
		/*=== Add allow mail from to ID to token ===*/
	
		
		$allow_emails = buddyboss_rbe()->__get("current_token_allow_emails");
		$to = $data["to"];
		if(!is_array($to)) { $to = explode(",",$to); }
		foreach($to as $t) {
			$allow_emails[] = $t;
		}
		
		$header = $data["headers"];
		foreach((array)$header as $h) {
			$pos = strpos($h, "Bcc:");
			if($pos !== false) {
				$hh = str_replace("Bcc:","",$h);
				$hh = trim($hh);
				$allow_emails[] = $hh;
			} 
		}
		
		$this->update_token_allow_emails($allow_emails);
		
		/*=== End add allow mail from to ID to token ===*/
		
		
		/*=== First Good Attempt of Changing the Header Info ===*/
		$eid = $this->markup_email_id($id);		
		$data["headers"][] = "References: <$eid>";
		$reply_to = $this->rbe_reply_to();
		$reply_to_name =  get_bloginfo( 'name' );
		$reply_to_name =  apply_filters( 'wp_mail_from_name', $reply_to_name );

		$data["headers"][] = 'Reply-To: "'.$reply_to_name.'" <'.$reply_to.'>';
		
		//prepend identifire on body
	    $content_type = 'text/plain';
	    $content_type = apply_filters( 'wp_mail_content_type', $content_type );
	    $prependcontent = $this->mail_body_before_identifire()."\n\n";
		
	    if( 'text/html' == $content_type ) {
		    $prependcontent = "<p>$prependcontent</p><br /><br />";
	    }
	    $data["message"] = $prependcontent.$data["message"];
		
		return $data;
	}
	
	/*
	 * Collect some information from bp new mail system
	 * @since BuddyBoss Rbe 1.0.2
	 * 
	 **/
	
	function bp_email_validate($return_val,$BP_Email) {
		
		$id = buddyboss_rbe()->__get("unique_identity");
	   
		if(empty($id)) {   return $return_val;   } //only run below code when needed.
		
		if(!is_wp_error($return_val)) {
		
			/*=== Add allow mail from to ID to token ===*/
			
			$recipients = $BP_Email->get_to();
			
			$allow_emails = array();
			
			foreach ( $recipients as $recipient ) {
				try {
					$allow_emails[] = $recipient->get_address();
				} catch ( phpmailerException $e ) {
				}
			}
			
			$this->update_token_allow_emails($allow_emails);
			
			/*=== End allow mail from to ID to token ===*/
			
		}
		
		return $return_val;
	}
	
	/*
	 * Appends & modify the content of buddypress mail template.
	 * @since BuddyBoss RBE 1.0.2
	 **/
	
	function bp_email_set_template($template) {
		
		$id = buddyboss_rbe()->__get("unique_identity");
		if(empty($id)) {   return $template;   } //only run below code when needed.
		
		$prependcontent = $this->mail_body_before_identifire()."\n\n";
		$prependcontent = "<p>$prependcontent</p><br /><br />";
		
		return $prependcontent.$template;
	}
	
	/**
	 * Intregate the indentity to the mail.
	 * @since BuddyBoss RBE 1.0.0
	 * @param object &$phpmailer an object of phpmailier
	 **/
	
	public function mail_identity_integration( &$phpmailer) {
	    
	    if(!$this->is_mail_server_configured()) { //do nothing when server is not confrigured
		return;
	    }
	    
	    $id = buddyboss_rbe()->__get("unique_identity");
	   
	    if(empty($id)) {
		return;
	    }
		    
		$eid = $this->markup_email_id($id);
		
		//force reply to
		$phpmailer->clearReplyTos();
		
		$custom_headers = $phpmailer->createHeader();
		
		
		
		
		$reply_to = $this->rbe_reply_to(); 
		$reply_to_name =  get_bloginfo( 'name' );
		$reply_to_name =  apply_filters( 'wp_mail_from_name', $reply_to_name );
		
		$check = explode("Reply-To:",$custom_headers);
		
		if(@strpos($check[1],$reply_to) === false){
		
			$phpmailer->addCustomHeader("Reply-To", '"'.$reply_to_name.'" <'.$reply_to.'>');			
		}
					   
		$phpmailer->MessageID = $eid;
		
		$check = explode("References:",$custom_headers);
		
		if(@strpos($check[1],"<$eid>") === false) {
			$phpmailer->addCustomHeader("References",  "<$eid>");
		}
	    	    
	}
	
	/*
	 * Return reply mail to address.
	 * In cases can return unique id as identifire.
	 * $unique :- Should return email unique ID or Standard normal
	 **/
	function rbe_reply_to($unique=true) {
		$reply_to = "";
		if(buddyboss_rbe()->option("mail_service") == "cloudmailin") {		
			$reply_to = buddyboss_rbe()->option("cloudmailin_incoming_email");
		}
	
		if(buddyboss_rbe()->option("mail_service") == "sendgrid") {
			$reply_to = buddyboss_rbe()->mail_handler->get_email();
		}
		/*
		if(buddyboss_rbe()->option("mail_service") == "personal") {
			$reply_to = buddyboss_rbe()->option("server_email");
		}
		*/
		
		if($unique) {
			/* intredate the id into the reply email */
			$id = buddyboss_rbe()->__get("unique_identity");
			$email = explode("@",$reply_to);
			$reply_to = $email[0]."+".$id."@".$email[1];
		}
		
		return $reply_to;
	}
	
	function markup_email_id($id) {
		//convert id to email look alike.
	    $siteurl = site_url();
	    $host = parse_url($siteurl);
	    $host = $host["host"];
		$domain = str_replace(array("http://","www."),"",$host);
	    $eid = "$id@$domain";
	    $eid = apply_filters("rbe_mail_identity_integration_email_like_id",$eid,$id);
		return $eid;
	}
	
	/*
	 * Return mail body before identifire string
	 * *** Warning ***
	 * Don't change this before indentifire text on production live
	 * site as it can cause previous sent mail notification not
	 * usefull for users.
	 **/
	public static function mail_body_before_identifire() {
		return apply_filters("bbrbe_body_before_identifire","--- Write ABOVE THIS LINE to post a reply ---");
	}
	
	/*
	 * Check if the setting is configured 
	 **/
	
	public function is_mail_server_configured() {
		
		$mail_service = buddyboss_rbe()->option("mail_service");
		
		if($mail_service == "cloudmailin") {
			
			$server_email = buddyboss_rbe()->option("cloudmailin_incoming_email");
			if(!empty($server_email)) {	return true; }
			
		}
		
		
		if($mail_service == "sendgrid") {
			
			$alias = buddyboss_rbe()->option("sendgrid_email_alias");
			$domain = buddyboss_rbe()->option("sendgrid_configured_domain");
			if(!empty($alias) AND !empty($domain)) {	return true; }
			
		}
		
		
		return false;
		
	}
	
	
	/*
	 * Will display to notice when required setting is not configured
	 **/
	public function admin_notices() {
		
		if(buddyboss_rbe()->option("mail_service") == "none") {
			echo '<div class="message error"><p>'.sprintf(__("Please select <a href='%s'>Mail service</a> in order to use <b>Reply by Email</b> functionality.","bb-reply-by-email"),buddyboss_rbe()->admin->plugin_settings_url.'&view=server').'</p></div>';
		}
		
		if(!$this->is_mail_server_configured() AND buddyboss_rbe()->option("mail_service") != "none") {
			echo '<div class="message error"><p>'.sprintf(__("Please complete the <a href='%s'>email setup</a> in order to use <b>Reply by Email</b> functionality.","bb-reply-by-email"),buddyboss_rbe()->admin->plugin_settings_url.'&view=server').'</p></div>';
		}
		
		if(buddyboss_rbe()->option("Error_Raised") == "1") {
			echo '<div class="message error"><p>'.sprintf(__("<b>BuddyBoss Reply by Email</b> is not working correctly check <a href='%s'>logs</a> for details.","bb-reply-by-email"),buddyboss_rbe()->admin->plugin_settings_url.'&view=logs').'</p></div>';
		}
		
	}
	
    /*
     * Function will reply back when message post fails.
     **/
    function auto_fail_reply($to,$message) {
        
	$message = trim($message);
	
        if(!empty($message)) {
            $message = sprintf(__('Hi,
Message reply you sent "%1$s" failed to post as the message has expired.
Note: Don\'t reply to this email as it is auto generated.
',"bb-reply-by-email"),$message);
        } else {
            
             $message = __("Hi,
Your previous reply failed. Please type some text and reply.","bb-reply-by-email");
            
        }
        
        wp_mail( $to, __("Reply message failed","bb-reply-by-email"), $message );
         
    }
	
	/*
	 * When the mail service option is empty return default from old version.
	 **/
	function buddyboss_rbe_option_mail_service($option) {
		if(empty($option)) {
			return "cloudmailin";
		}
		return $option;
	}
	
	function bp_send_email_delivery_class($return) {
		
		require_once(dirname(__FILE__)."/classes/rbe-bp-mailier.php");
		
		return "RBE_BP_PHPMailer";
		
	}
	
	
}

endif;
