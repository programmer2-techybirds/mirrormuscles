<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 *
 * Handle all mails activities via sendgrid 
 **/


 
 if ( ! class_exists( 'BuddyBoss_rbe_sendgrid' ) ):
 
class BuddyBoss_rbe_sendgrid {
    
    public function __construct() {
        //nothing to do here.
    }
    
    /**
    * Instance
    *
    * @since BuddyBoss RBE (1.0.0)
    */
   public static function instance() {
           static $instance = null;
           
           if ( null === $instance ) {
                $instance = new BuddyBoss_rbe_sendgrid;
                $instance->actions();
           }
   
           return $instance;
   }
   
   
   function actions() {
    
            add_action( 'wp_ajax_sendgrid_incoming_service', array($this,"catch_mail") );
            add_action( 'wp_ajax_nopriv_sendgrid_incoming_service', array($this,"catch_mail") );
			add_action( "buddyboss_rbe_option_sendgrid_email_alias", array($this,"buddyboss_rbe_option_sendgrid_email_alias"));
    
   }
   
   /*
    * Return the email to use over site.  
    **/
   public function get_email() {
	return buddyboss_rbe()->option("sendgrid_email_alias")."@".buddyboss_rbe()->option("sendgrid_configured_domain");
   }
   
   function catch_mail() {
        
        error_reporting(0);
        
        $server_email = $this->get_email();
        $Headers = $_POST["headers"];
		
		//Extract References
		$References = "";
		$Ref = explode("References:",$Headers);
		if(isset($Ref[1])) {
			$Ref  = explode("\n",$Ref[1]);
			if(isset($Ref[1])) {
				$Ref = $Ref[0];
				$References = trim($Ref);
			}
		}
		unset($Ref);
		
		//Correct From
		$from = $_POST["from"];
		if(strpos($from,"<") !== false) {
			$from = explode("<",$from);
			if(isset($from[1])) {
				$from = explode(">",$from[1]);
				$from = trim($from[0]);
			}
		}
		
		//Correct To
		$to = $_POST["to"];
		if(strpos($to,"<") !== false) {
			$to = explode("<",$to);
			if(isset($to[1])) {
				$to = explode(">",$to[1]);
				$to = trim($to[0]);
			}
		}
				
        $subject = $_POST['subject'];
        $html = $_POST['html'];
		
		if(empty($html)) {
			$html = $_POST["text"];
		}
        
		$real_to = $to;
		if(strpos($to,"+") !== false) {
			$real_to = explode("+",$real_to);
			$real_to[1] = explode("@",$real_to[1]);
			$real_to = $real_to[0]."@".$real_to[1][1];
		}
		          
        if ($real_to == $server_email){
            
            header("HTTP/1.1 200 OK");
          
			if(strpos($to,"+bbrbe") !== false) {
				
				$identity = str_replace(array("<",">"),"",$to);
								
				$identity = explode("+",$to);
				$identity = explode("@",$identity[1]);
				$identity = explode("-",$identity[0]);
			
			} else {
			
				$identity = str_replace(array("<",">"),"",$References);
								
				$identity = explode("@",$identity);
				$identity = explode("-",$identity[0]);
				
			} 
            
            
            $process = new BuddyBoss_rbe_Processor();
            $process->setFrom($from);
            $process->setBody($html);
            $process->setIdentitiy($identity);
            
            if($process->process()) {
                
                if($process->isTest()) { //we don't need to do any additional action on test
                    buddyboss_rbe()->log( sprintf(__( 'Received test mail from %1$s.',"bb-reply-by-email" ),$from));
                    return true;
                }
                
                $replies = array();
                $replies[] = $process->getReply();
                
                if(!is_array($replies)) { $replies = array(); }
		
				foreach($replies as $r) {
					
					do_action("bbrbe_new_reply",$r["type"],$r["user"],$r["content"],$r["values"]);	
				}
                                
                echo __( "Incoming Received","bb-reply-by-email");
                
            } else {
                $error = $process->_error();
                echo $error;
                buddyboss_rbe()->log( sprintf(__( 'Failed incoming mail error: (%1$s)',"bb-reply-by-email" ),$error));
            }
          
        }else{
          header("HTTP/1.0 200 OK"); //return success even fail as its fail due to security verification.
          echo __( "Ooops.","bb-reply-by-email");
        }
        
        exit;
    
    
   }
   
   
	/*
	 * When the sendgrid_email_alias option is empty return default.
	 **/
	function buddyboss_rbe_option_sendgrid_email_alias($option) {
		if(empty($option) || !is_numeric($option)) {
			return 'reply';
		}
		return $option;
	}
    
}

endif;
