<?php

  /**
  * Plugin Name: cometchat
  * Description: Enable audio/video/text chat on your WordPress site in minutes and increase user activity exponentially!
  * Version: 1.0.0
  * Author: CometChat
  * Author URI: http://www.cometchat.com/
  */
  $set_iframe_size = "<script type=\"text/javascript\">
    function cc_iFrameSize(){
      var cc_frame = document.getElementById(\"admin_iframe\");
      var scroll_offset = window.scrollY;
      cc_frame.style.height = '';
      cc_frame.style.height = cc_frame.contentDocument.body.scrollHeight+\"px\";
      cc_frame.style.width = cc_frame.contentDocument.body.scrollWidth+\"px\";
      document.body.scrollTop = scroll_offset;
    }
    </script>";

include_once(ABSPATH.'wp-admin/includes/plugin.php');
function add_menu_item() {
    add_menu_page( 'CometChat', 'CometChat', 'manage_options', 'cometchat/cometchatinstall.php', '', plugins_url( 'cometchat/newcometchat.png' ), '2.24' );
}

function cometchat_friend_ajax() {
    if(isset($_POST['usergroups'])){
      global $wp_roles;
      $roles = array_keys($wp_roles->get_names());
      $usergroups = $_POST['usergroups'];
      print_r($usergroups);

      $disable_cometchat = array_intersect($roles, $usergroups);
      $enable_cometchat = array_diff($roles, $usergroups);
      //Disable CometChat for selected users
      if(!empty($disable_cometchat)){
        foreach ($disable_cometchat as $key => $value) {
          $role = get_role($value);
          $role->add_cap( 'enable_cometchat',false );
          update_option( $value , 'true', '', 'no');
        }
      }
      //Disable CometChat for selected users
      if(!empty($enable_cometchat)){
        foreach ($enable_cometchat as $key => $value) {
          $role = get_role($value);
          $role->add_cap( 'enable_cometchat',true );
          update_option( $value , 'false', '', 'no');
        }
      }
    }else{
      global $wp_roles;
      $roles = array_keys($wp_roles->get_names());
      foreach ($roles as $value) {
         $role = get_role($value);
         $role->add_cap( 'enable_cometchat',true );
         update_option( $value , 'false', '', 'no');
      }
    }
    if(isset($_POST['hide_bar'])){
        if($_POST['hide_bar'] == 'true'){
            update_option( 'hide_bar' , 'true', '', 'no');
        }else{
            update_option( 'hide_bar', 'false', '', 'no');
        }
    }
    if(isset($_POST['inbox_sync'])){
        if($_POST['inbox_sync'] == 'inbox_sync'){
            update_option( $_POST['inbox_sync'] , 'true', '', 'no');
        }else{
            update_option( 'inbox_sync', 'false', '', 'no');
        }
        unlink(dirname(dirname(dirname(dirname(__FILE__))))."/cometchat/cache/cache.storage.".$_SERVER['HTTP_HOST']."/aW/aW5ib3hfc3luY2NjX18.txt");
    }
    die();
}

  function add_ccbar(){
    if(file_exists(ABSPATH.'cometchat/install.php')){
        if(get_option('hide_bar') == 'true'){
            return;
        }
        if(current_user_can('enable_cometchat')){
            $site_url = get_site_url();
            echo "<link type=\"text/css\" href=\"".$site_url."/cometchat/cometchatcss.php\" rel=\"stylesheet\" charset=\"utf-8\" />
            <script type=\"text/javascript\" src= \"".$site_url."/cometchat/cometchatjs.php\" charset=\"utf-8\"></script>";
        }
    }
  }

  function add_customjs(){
    global $set_iframe_size;
    echo $set_iframe_size;
  }

  function remove_cometchat_database() {
    global $wpdb;

    global $wp_roles;
    $roles = array_keys($wp_roles->get_names());
    foreach ($roles as $key => $value) {
        delete_option($value);
    }
    delete_option('inbox_sync');
    delete_option('hide_bar');
    $path = ABSPATH.'cometchat/';
    function rrmdir_recursive($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        rrmdir_recursive($dir."/".$object);
                    }else{
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
        /*reset($objects);
        rmdir($dir);
      }*/
    }
    rrmdir_recursive($path);
    $sql = $wpdb->get_results("SELECT CONCAT( 'DROP TABLE IF EXISTS ', GROUP_CONCAT(table_name) , '' ) AS statement FROM information_schema.tables WHERE table_schema = '$wpdb->dbname' and table_name like 'cometchat%'");
    $wpdb->query($sql[0]->statement);
}

function register_settings() {
    global $wp_roles;
    $roles = array_keys($wp_roles->get_names());
    foreach ($roles as $key => $value) {
        $role = get_role($value);
        $role->add_cap( 'enable_cometchat',true );
    }
    add_option('inbox_sync','true','','no');
}

function insert_buddypress_message_first( BP_Messages_Message $message){
    if(get_option('inbox_sync') == 'true'){
        global $wpdb;
        $from = bp_loggedin_user_id();
        $msg = $_POST['content'];
        $sent = time();
        $url = get_site_url()."/cometchat/cometchat_send.php";
        if(!isset($_SESSION['random'])){
            $rand1 = rand(10000000000000000000,99999999999999999999);
            $rand2 = rand(1000000000000,9999999999999);
            $callback = 'jqcc'.$rand1.'_'.$rand2;
            $_SESSION['random'] = $callback;
        }else{
            $callback = $_SESSION['random'];
        }
        $prefix = $wpdb->prefix;
        $sql = ("SELECT option_value from ".$prefix."options where option_name = 'hash_value'");
        $result = $wpdb->get_row($sql);
        $hash = $result->option_value;
        $hashval = md5($hash.$from);
        $recipients = $message->recipients;
        foreach ($recipients as $key => $value) {
            if($recipients[$key]->user_id != $from){
                $to = $recipients[$key]->user_id;
                if (in_array ('curl', get_loaded_extensions())){
                    $fields_string = array('cc_social_userid'=>$from,'to'=>$to,'message'=>$msg,'callback'=>$callback,'hash_val'=>$hashval,'deny_sanitize'=>'true','deny_hooks_message'=>'true','cc_direction'=>0);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPGET, 1);
                    curl_setopt($ch, CURLOPT_URL, $url );
                    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
                    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                    $result = curl_exec($ch);
                    curl_close($ch);
                }else{
                    $sql = ("INSERT INTO cometchat(`from`,`to`,`message`,`sent`,`read`,`direction`) VALUES ('".$from."','".$to."','".$msg."','".$sent."',1,0)");
                    $wpdb->query($sql);
                }
            }
        }
    }
}

register_activation_hook( __FILE__, 'register_settings');
add_action('admin_menu', 'add_menu_item');

add_action('wp_head', 'add_ccbar');
add_action('admin_enqueue_scripts','add_customjs');
add_action( 'wp_ajax_cometchat_friend_ajax', 'cometchat_friend_ajax');
register_uninstall_hook( __FILE__, 'remove_cometchat_database' );
add_action( 'messages_message_after_save', 'insert_buddypress_message_first');
?>