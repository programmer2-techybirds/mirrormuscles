<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 *
 * Do the Process work for RBE
 **/


 if ( ! class_exists( 'BuddyBoss_rbe_Processor' ) ):

class BuddyBoss_rbe_Processor {

    protected $identity;
    protected $body;
    protected $from;
    protected $isTest = false;
    protected $reply = false;
    protected $_errorCode = false;
    protected $_error = false;


    public function __construct() {
        //nothing to do here.
    }

    /**
    * @since BuddyBoss RBE (1.0.0)
    */
   public static function instance() {
           static $instance = null;

           if ( null === $instance ) {
                $instance = new BuddyBoss_rbe_Processor;
           }

           return $instance;
   }


   function setIdentitiy($identity) {
        $this->identity = $identity;
   }

   function setBody($body) {
        $this->body = $body;
   }

   function setFrom($from) {
        $this->from = $from;
   }

   function _error() {
        return $this->_error;
   }

   function _errorCode() {
        return $this->_errorCode;
   }

   function isTest() {
        return $this->isTest;
   }

   function getReply() {
        return $this->reply;
   }

   /*
    * Error codes
    * 1 > Not a valid header.
    * 2 > When content is empty
    * 3 > Security token is expired (mostly happen when security token doesnt match or expired)
    * 4 > User didn't matched or found.
    *
    *
    **/

   function process() {
            global $wpdb;

            $identity = $this->identity;
            $body = $this->body;
            $from_email = $this->from;

            if($identity[0] != "bbrbe") {
                $this->_error = __("Does not have a valid Reply by Email header.","bb-reply-by-email");
                $this->_errorCode = '1';
                return false;
            }

            $type = $identity[1];
            $token = $identity[2];

            if(empty($type) || empty($token)) {
                 $this->_error = __("Does not have a valid RBE header.","bb-reply-by-email");
                 $this->_errorCode = '1';
                 return false;
            }
                        update_option("rbe_body", $body);

            $body = stripslashes($body);


            /* remove <head> tag if exists */
            $body = preg_replace('#<head(.*?)>(.*?)</head>#is', '', $body);
            /* remove style tag if exists */
            $body = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $body);
            /* remove script tag if exists */
            $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);

            /* filter correct content */
            $body = str_replace(array("<br>","<br/>","<br />"),"\n<br>",$body);

            //Remove Block Section
            $body = explode("\n",$body);
            foreach($body as $k => $v)
            {
                if(substr(strip_tags($v),0,1)==">" || substr(strip_tags($v),0,4)=="&gt;") {
                    unset($body[$k]);
                }
            }

            $body = implode("\n",$body);


            //striping possible signatures.
            $body = preg_replace('/\s*(.+)\s*[\r\n]--\s+.*/s', '$1', $body); // standard rules signature.

            //update_option("rbe_body",$body);

            //strip gmail signatures.
            $body = preg_replace("/\.*<div class=\"gmail_signature\">+.*<\/div>/s", "$1", $body);

            //strip for yahoo and others.
            $body = preg_replace('/\.*<div class=\"signature\"+.*<\/div>/s', '$1', $body);

            //changing div and p into br
            $body = preg_replace("/<p[^>]*?>/", "", $body);
            $body = str_replace("</p>", "\n<br>", $body);
            $body = preg_replace("/<div[^>]*?>/", "", $body);
            $body = str_replace("</div>", "\n<br>", $body);

            $body = apply_filters("rbe_processor_body",$body,$type);

            $body = explode("\n",$body);
            $ncontent = array();
            $i = 0;

            foreach($body as $c) {
                $cc = trim($c); $cc = strip_tags($cc,'<a>'); //Strip for checking unqiue line
                if(strpos($cc, BuddyBoss_rbe_core::mail_body_before_identifire() ) !== false) {
                    $ncontent[count($ncontent)-1] = NULL;
                    if(count($ncontent)-2 != '0') { // assuming that on index 0 there always a content.
                        $ncontent[count($ncontent)-2] = NULL;
                    }
                    break;
                }
                $ncontent[] = $c;
                $i++;
            }


            $content = implode("\n",$ncontent);

            // Now Strip Tags
            $content = strip_tags($content);

            //regonize if its web outlook standard.
            if(strpos($content,"_______________________________________") !== false) {
                $content = explode("_______________________________________",$content);
                $content = $content[0];
            }

            // outlook 2007 standard if found from in string split from it.
            // [mailto: should nt be on first line.
            if(strpos($content,"[mailto:") !== false AND strpos($content,"[mailto:") != 0) {
                $content = explode("[mailto:",$content);
                $content = trim($content[0]);
                //remove last line
                $content = explode("\n",$content);
                unset($content[count($content)-1]);
                $content = implode("\n",$content);
            }


            if(empty($content)) {
                 BuddyBoss_rbe_core::auto_fail_reply($from_email,$content);
                 $this->_error = __("Content is empty.","bb-reply-by-email");
                 $this->_errorCode = '2';
                 return false;
            }
            /* end filter correct content */

            $token_verification = apply_filters("rbe_processor_token_verification",true,$identity,$from_email,$content);

            if($token_verification) {

                //verify the token.
                $get_token = $wpdb->get_row(
                    $wpdb->prepare("SELECT *FROM {$wpdb->base_prefix}bbrbe_token WHERE token_id=%s AND type=%s",$token,$type)
                );

                if(empty($get_token)) {
                     BuddyBoss_rbe_core::auto_fail_reply($from_email,$content);
                     $this->_error = __("Security token is expired.","bb-reply-by-email");
                     $this->_errorCode = '3';
                     return false;
                }

                if($get_token->token_id != $token) {
                     BuddyBoss_rbe_core::auto_fail_reply($from_email,$content);
                     $this->_error = __("Security token not matched.","bb-reply-by-email");
                     $this->_errorCode = '3';
                     return false;
                }

                //verify the email security.
                $allowed_emails = explode(",",$get_token->allow_emails);
                if(!in_array($from_email,$allowed_emails)) {
                     BuddyBoss_rbe_core::auto_fail_reply($from_email,$content);
                     $this->_error = __("From source doesnt have permission with this token.","bb-reply-by-email");
                     $this->_errorCode = '3';
                     return false;
                }

            }


            //catch test one.
            if($type == "test") {
                    $testemail = get_option("buddyboss_rbe_test_email");
                    $incoming_email = $identity[3];

                    $md5 = md5($testemail);
                    $md5 = substr($md5,10,10);

                    if($incoming_email == $md5) {
                        update_option("buddyboss_rbe_test_replied_message",$content);
                    }

                    $this->_isTest = true;
                    return true;
            }

            //verify if user is valid.
            $user = get_user_by( "email", $from_email );

            // Unknown user so skip it.
            if(empty($user)) { //skip and mark it as read.
                $this->_error = __("User didn't matched or found.","bb-reply-by-email");
                $this->_errorCode = '4';
                return false;
            }

            $values = array();
            $identity2 = $identity;
            //strip unwanted index
            unset($identity2[0]); //plug indentify
            unset($identity2[1]); //type identify
            unset($identity2[2]); //token identify
            foreach($identity2 as $identity2) {  $values[] = $identity2;  }
            unset($identity2);

            $reply = array(
                               "content"    => $content,
                               "type"       => $type,
                               "values"     => $values,
                               "user"       => $user
                               );

            $this->reply = $reply;

            return true;
   }


}

endif;
