<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 *
 * Handle all mails activities via cloudmailin 
 **/


 
 if ( ! class_exists( 'BuddyBoss_rbe_cloudmailin' ) ):
 
class BuddyBoss_rbe_cloudmailin {
    
    public function __construct() {
        //nothing to do here.
    }
    
    /**
    * Singleton
    *
    * @since BuddyBoss RBE (1.0.0)
    */
   public static function instance() {
           static $instance = null;
           
           if ( null === $instance ) {
                $instance = new BuddyBoss_rbe_cloudmailin;
                $instance->actions();
           }
   
           return $instance;
   }
   
   
   function actions() {
    
            add_action( 'wp_ajax_cloudmailin_incoming_service', array($this,"catch_mail") );
            add_action( 'wp_ajax_nopriv_cloudmailin_incoming_service', array($this,"catch_mail") );
    
   }
   
   function catch_mail() {
        
        error_reporting(0);
        
        $server_email = buddyboss_rbe()->option("cloudmailin_incoming_email");
        
        $to = $_POST['envelope']['to'];
        $from = $_POST['envelope']['from'];
        $subject = $_POST['headers']['Subject'];
        $html = $_POST['html'];
        $References  =$_POST["headers"]["References"];
                        
		if(empty($html)) {
			$html = $_POST["plain"];
		}
		
		$real_to = $to;
		if(strpos($to,"+") !== false) {
			$real_to = explode("+",$real_to);
			$real_to[1] = explode("@",$real_to[1]);
			$real_to = $real_to[0]."@".$real_to[1][1];
		}
		
        if ($real_to == $server_email){
            
            header("HTTP/1.0 200 OK");
          
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
                    return false;
                }
                
                $replies = array();
                $replies[] = $process->getReply();
                
                if(!is_array($replies)) { $replies = array(); }
		
				foreach($replies as $r) {
					
					do_action("bbrbe_new_reply",$r["type"],$r["user"],$r["content"],$r["values"]);	
				}
					
                header("HTTP/1.0 200 OK");
                                
                echo __( "Incoming Received","bb-reply-by-email");
                
            } else {
                $error = $process->_error();
                echo $error;
                buddyboss_rbe()->log( sprintf(__( 'Failed incoming mail error: (%1$s)',"bb-reply-by-email" ),$error));
            }
          
        }else{
          header("HTTP/1.0 403 OK");
          echo __( "Ooops.","bb-reply-by-email");
        }
        
        exit;
    
    
   }
   
    
}

endif;
