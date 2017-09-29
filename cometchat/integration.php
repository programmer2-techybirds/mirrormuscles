<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */
$cms = "buddypress";
$dbms = "mysql";
define('SET_SESSION_NAME','');          // Session name
define('SWITCH_ENABLED','1');
define('INCLUDE_JQUERY','1');
define('FORCE_MAGIC_QUOTES','1');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */
if($dbms == "mssql" && file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'sqlsrv_func.php')){
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'sqlsrv_func.php');
}

$dbconfigfile = dirname(__FILE__).DIRECTORY_SEPARATOR.'writable'.DIRECTORY_SEPARATOR.'dbconfig.php';
$cmsconfigfile = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-config.php';
$configurationSettings = array(
	'constant'=>array(
		'DB_HOST',
		'DB_USER',
		'DB_NAME',
		'DB_PASSWORD',
		'LOGGED_IN_KEY',
		'LOGGED_IN_SALT',
		'LOGGED_IN_COOKIE'
	),
	'variable'=>array(
		'table_prefix'
	)
);

if (file_exists($dbconfigfile)) {
	include_once($dbconfigfile);
}
else {
	if (file_exists($cmsconfigfile)) {
		include_once($cmsconfigfile);
	}
	$dbconfigtoWrite = "<?php\n\t";
	foreach($configurationSettings as $configurationSetting => $configurationVal){
		if($configurationSetting == 'constant'){
			for($constantIndex=0; $constantIndex<count($configurationVal); $constantIndex++){
				$dbconfigtoWrite .= "\n\tdefine('".$configurationVal[$constantIndex]."','".constant($configurationVal[$constantIndex])."');\n";
			}
		}
		if($configurationSetting == 'variable'){
			for($constantIndex=0; $constantIndex<count($configurationVal); $constantIndex++){
				$dbconfigtoWrite .= "\n\t\$".$configurationVal[$constantIndex]."\t=\t".var_export(${$configurationVal[$constantIndex]},true).";\n";
			}
		}
		unset($constantIndex);
	}
	file_put_contents($dbconfigfile,$dbconfigtoWrite);
}

// DO NOT EDIT DATABASE VALUES BELOW

define('DB_SERVER',                         DB_HOST									);
define('DB_PORT',                           "3306"									);
define('DB_USERNAME',                       DB_USER									);
$table_prefix = $table_prefix;									// Table prefix(if any)
$db_usertable = 'users';							// Users or members information table name
$db_usertable_userid = 'ID';						// UserID field in the users or members table
$db_usertable_name = 'display_name';					// Name containing field in the users or members table
$db_avatartable = " left join ".$table_prefix."usermeta on ".$table_prefix.$db_usertable.".".$db_usertable_userid."=".$table_prefix."usermeta.user_id and ".$table_prefix."usermeta.meta_key = 'wsl_current_user_image' ";
$db_avatarfield = " coalesce(concat(".$table_prefix.$db_usertable.".".$db_usertable_userid.",'|',".$table_prefix.$db_usertable.".user_email,'|',".$table_prefix."usermeta.meta_value),concat(".$table_prefix.$db_usertable.".".$db_usertable_userid.",'|',".$table_prefix.$db_usertable.".user_email))";
$db_linkfield = ' '.$table_prefix.$db_usertable.'.user_nicename ';

/*COMETCHAT'S INTEGRATION CLASS USED FOR SITE AUTHENTICATION */

class Integration{

	function __construct(){
		if(!defined('TABLE_PREFIX')){
			$this->defineFromGlobal('table_prefix');
			$this->defineFromGlobal('db_usertable');
			$this->defineFromGlobal('db_usertable_userid');
			$this->defineFromGlobal('db_usertable_name');
			$this->defineFromGlobal('db_avatartable');
			$this->defineFromGlobal('db_avatarfield');
			$this->defineFromGlobal('db_linkfield');
		}
	}

	function defineFromGlobal($key){
		if(isset($GLOBALS[$key])){
			define(strtoupper($key), $GLOBALS[$key]);
			unset($GLOBALS[$key]);
		}
	}

	function getUserID() {
		$userid = 0;
		if (!empty($_SESSION['basedata']) && $_SESSION['basedata'] != 'null') {
			$_REQUEST['basedata'] = $_SESSION['basedata'];
		}
		if (!empty($_REQUEST['basedata'])) {
			if (function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1') {
				$key = "";
				if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
					$key = KEY_A.KEY_B.KEY_C;
				}
				$uid = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(rawurldecode($_REQUEST['basedata'])), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
				if (intval($uid) > 0) {
					$userid = $uid;
				}
			} else {
				$userid = $_REQUEST['basedata'];
			}
		}

		if (isset($_COOKIE[LOGGED_IN_COOKIE]) && (empty($userid) || $userid == "null")) {
            $username = explode("|", $_COOKIE[LOGGED_IN_COOKIE]);
            $sql = ("SELECT ID FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE user_login = '".sql_real_escape_string($username[0])."'");
            $result = sql_query($sql, array(), 1);
            $row = sql_fetch_assoc($result);
            $userid = $row['ID'];
        }

		if (!isset($_SESSION['cometchat']['cookieval'])) {
			$sql = ("SELECT option_value FROM ".TABLE_PREFIX."options WHERE option_name = 'siteurl'");
			$result = sql_query($sql, array(), 1);
			$row = sql_fetch_assoc($result);
			$_SESSION['cometchat']['cookieval'] = 'wordpress_logged_in_'.md5($row['option_value']);
		}

		if (isset($_COOKIE[$_SESSION['cometchat']['cookieval']]) && (empty($userid) || $userid == "null")) {
			$username = explode("|", $_COOKIE[$_SESSION['cometchat']['cookieval']]);
			$sql = ("SELECT ID FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE user_login = '".sql_real_escape_string($username[0])."'");
			$result = sql_query($sql, array(), 1);
			$row = sql_fetch_assoc($result);
			$userid = $row['ID'];
		}
		if(isset($_REQUEST['cc_social_userid']) && isset($_REQUEST['hash_val'])){
			$sql = ("SELECT option_value FROM ".TABLE_PREFIX."options WHERE option_name = 'hash_value'");
			$result = sql_query($sql, array(), 1);
			$row = sql_fetch_assoc($result);
			$hash = md5($row['option_value'].$_REQUEST['cc_social_userid']);
			if($hash == $_REQUEST['hash_val']){
				$userid = $_REQUEST['cc_social_userid'];
			}
		}
		$userid = intval($userid);
		return $userid;
	}

	function chatLogin($userName,$userPass) {
		$userid = 0;
		global $guestsMode;
		include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-includes'.DIRECTORY_SEPARATOR.'class-phpass.php');
		$hasher = new PasswordHash(8, false);
		if (filter_var($userName, FILTER_VALIDATE_EMAIL)) {
			$sql = ("SELECT * FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE user_email = '".sql_real_escape_string($userName)."'");
		} else {
			$sql = ("SELECT * FROM ".TABLE_PREFIX.DB_USERTABLE." WHERE user_login = '".sql_real_escape_string($userName)."'");
		}
		$result = sql_query($sql, array(), 1);
		$row = sql_fetch_assoc( $result );
		$check = $hasher->CheckPassword($userPass, $row['user_pass']);
		if ($check) {
			$userid = $row['ID'];
			$sql = ("SELECT option_value FROM ".TABLE_PREFIX."options WHERE option_name = '_transient_plugin_slugs'");
			$result = sql_query($sql, array(), 1);
			$row = sql_fetch_assoc($result);
			$option_value = $row['option_value'];
			$option_value = unserialize($option_value);
			$cc_plugin = 'cometchat/cometchat.php';
			if(in_array($cc_plugin, $option_value)){
				$sql = ("SELECT option_value FROM ".TABLE_PREFIX."options WHERE option_name = 'active_plugins'");
				$result = sql_query($sql, array(), 1);
				$row = sql_fetch_assoc($result);
				$active_plugins= $row['option_value'];
				$active_plugins = unserialize($active_plugins);
				$cc_plugin = 'cometchat/cometchat.php';
				if(in_array($cc_plugin, $active_plugins)){
					$sql = ("SELECT meta_value FROM ".TABLE_PREFIX."usermeta WHERE user_id = '".$userid."' AND meta_key = 'wp_capabilities'");
					$result = sql_query($sql, array(), 1);
					$row = sql_fetch_assoc($result);
					$usergroup = $row['meta_value'];
					$usergrp = unserialize($usergroup);
					$usergrp = array_keys($usergrp);
					$usergrp = $usergrp[0];
					$sql = ("SELECT option_value FROM ".TABLE_PREFIX."options WHERE option_name = '".$usergrp."'");
					$result = sql_query($sql, array(), 1);
					$row = sql_fetch_assoc($result);
					$opt_value = $row['option_value'];
					if($opt_value == 'true'){
						return 0;
					}
				}else{
					return 0;
				}
			}
		}
		if(!empty($userName) && !empty($_REQUEST['social_details'])) {
			$social_details = json_decode($_REQUEST['social_details']);
			$userid = socialLogin($social_details);
		}
		if(!empty($_REQUEST['guest_login']) && $userPass == "CC^CONTROL_GUEST" && $guestsMode == 1){
			$userid = getGuestID($userName);
		}
		if(!empty($userid) && isset($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp'){
			$sql = ("insert into cometchat_status (userid,isdevice) values ('".sql_real_escape_string($userid)."','1') on duplicate key update isdevice = '1'");
			sql_query($sql, array(), 1);
		}
		if($userid && function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1'){
			$key = "";
			if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
				$key = KEY_A.KEY_B.KEY_C;
			}
			$userid = rawurlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key)))));
		}

		return $userid;
	}

	function getFriendsList($userid,$time) {
		global $hideOffline;
		$offlinecondition = '';
		$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".DB_LINKFIELD." link , ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice, cometchat_status.readreceiptsetting readreceiptsetting from ".TABLE_PREFIX."bp_friends join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."bp_friends.friend_user_id = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ". DB_AVATARTABLE." left join (select ".TABLE_PREFIX."bp_friends.initiator_user_id friendid from ".TABLE_PREFIX."bp_friends where ".TABLE_PREFIX."bp_friends.friend_user_id = '".sql_real_escape_string($userid)."' and is_confirmed = 1 union select ".TABLE_PREFIX."bp_friends.friend_user_id friendid from ".TABLE_PREFIX."bp_friends where ".TABLE_PREFIX."bp_friends.initiator_user_id = '".sql_real_escape_string($userid)."' and is_confirmed = 1) friends on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = friends.friendid order by username asc");

		if ((defined('MEMCACHE') && MEMCACHE <> 0) || DISPLAY_ALL_USERS == 1) {
			if ($hideOffline) {
				$offlinecondition = "where ((cometchat_status.lastactivity > (".sql_real_escape_string($time)."-".((ONLINE_TIMEOUT)*2).")) OR cometchat_status.isdevice = 1) and (cometchat_status.status IS NULL OR cometchat_status.status <> 'invisible' OR cometchat_status.status <> 'offline')";
			}
			$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".DB_LINKFIELD." link , ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice, cometchat_status.readreceiptsetting readreceiptsetting from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." ".$offlinecondition." order by username asc");

		}

		return $sql;
	}

	function getFriendsIds($userid) {
		$sql = ("select ".TABLE_PREFIX."bp_friends.friend_user_id friendid from ".TABLE_PREFIX."bp_friends where ".TABLE_PREFIX."bp_friends.initiator_user_id = '".sql_real_escape_string($userid)."' and is_confirmed = 1 union select ".TABLE_PREFIX."bp_friends.initiator_user_id friendid from ".TABLE_PREFIX."bp_friends where ".TABLE_PREFIX."bp_friends.friend_user_id = '".sql_real_escape_string($userid)."' and is_confirmed = 1");

		return $sql;
	}

	function getUserDetails($userid) {
		$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".DB_LINKFIELD." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice, cometchat_status.readreceiptsetting readreceiptsetting from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ". DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".sql_real_escape_string($userid)."'");

		return $sql;
	}

	function getActivechatboxdetails($userids) {
		$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".DB_LINKFIELD." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice, cometchat_status.readreceiptsetting readreceiptsetting from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." IN (".$userids.")");

		return $sql;
	}

	function getUserStatus($userid) {
		$sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".sql_real_escape_string($userid)."'");
		return $sql;
	}

	function fetchLink($link) {
		$cc_url = (defined('CC_SITE_URL') ? CC_SITE_URL : BASE_URL);
		return $cc_url.'../members/'.$link;
	}

	function getAvatar($data) {
		if(!empty($data)) {
			$data = explode('|',$data);
			$id = $data[0];
			if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'avatars' .DIRECTORY_SEPARATOR. $id)) {
				$files = "";
				if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'avatars' .DIRECTORY_SEPARATOR. $id)) {
					while (false !== ($file = readdir($handle))) {
						if ($file != "." && $file != "..") {
							if(substr($file, -11, 7) == "bpthumb" ) {
								$files .= $file;
							}
						}
					}
					closedir($handle);
				}
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'avatars' .DIRECTORY_SEPARATOR. $id .DIRECTORY_SEPARATOR. $files)) {
					$cc_url = (defined('CC_SITE_URL') ? CC_SITE_URL : BASE_URL);
					return $cc_url.'../wp-content/uploads/avatars/'.$id.'/'.$files;
				}
			}else if(!empty($data[2])){
				return $data[2];
			}elseif(file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'wp-content'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'ultimatemember' .DIRECTORY_SEPARATOR. $id .DIRECTORY_SEPARATOR. 'profile_photo-80.jpg')){
				$cc_url = (defined('CC_SITE_URL') ? CC_SITE_URL : BASE_URL);
				return $cc_url.'../wp-content/uploads/ultimatemember/'.$id.'/profile_photo-80.jpg';
			}else{
				return '//www.gravatar.com/avatar/'.md5($data[1]).'?d=wavatar&s=80';
			}
		}
		else {
			return BASE_URL.'images/noavatar.png';
		}
	}

	function getTimeStamp() {
		return time();
	}

	function processTime($time) {
		return $time;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/* HOOKS */

	function hooks_message($userid,$to,$unsanitizedmessage,$dir) {
		global $language;
		if($dir == 2){
			return;
		}
		$sql = ("select option_value from `".TABLE_PREFIX."options` where option_name = 'inbox_sync'");
		$query = sql_query($sql, array(), 1);
		$inbox_sync = sql_fetch_assoc($query);
		if($inbox_sync['option_value'] == 'true'){
			$subject = $language['title'];
			$userid = sql_real_escape_string($userid);
			$to = sql_real_escape_string($to);
			$decoded_cc_message = decode_controlmessage($unsanitizedmessage);
			if(!empty($decoded_cc_message)){
				if($decoded_cc_message['name'] == 'stickers'){
					$unsanitizedmessage = 'has sent a sticker';
				}else{
					$unsanitizedmessage = '';
				}
			}
			if(!empty($unsanitizedmessage)){
				if(strpos($unsanitizedmessage,'^CONTROL_PLUGIN_AVCHAT_INITIATECALL') > -1){
					$unsanitizedmessage = 'has initiated a video chat request';
				}elseif(strpos($unsanitizedmessage,'^CONTROL_PLUGIN_AVCHAT_REJECTCALL') > -1){
					$unsanitizedmessage = 'The user is busy right now. Please try again later';
				}
				$time = date("Y-n-d H:i:s");
				$sql = ("select a.thread_id as id from `".TABLE_PREFIX."bp_messages_recipients` as a left join `wp_bp_messages_recipients` as b on a.thread_id = b.thread_id where a.user_id = '".$userid."' and b.user_id = '".$to."' and a.is_deleted!=1 and b.is_deleted!=1" );
				$query = sql_query($sql, array(), 1);
				$thread_id = sql_fetch_assoc($query);
				if(empty($thread_id['id'])) {
					$sql = ("select max(`thread_id`) as id from `".TABLE_PREFIX."bp_messages_messages`");
					$query = sql_query($sql, array(), 1);
					$thread_id = sql_fetch_assoc($query);
					if(empty($thread_id['id'])){
						$thread_id['id'] = 0;
					}
					$thread_id['id'] = intval($thread_id['id'])+1;
					$thread_id['id'] = sql_real_escape_string($thread_id['id']);
					$sql = ("insert into `".TABLE_PREFIX."bp_messages_messages`(`thread_id`,`sender_id`, `subject`, `message`, `date_sent`) values ('".$thread_id['id']."','".$userid."','".$subject."','".$unsanitizedmessage."','".$time."')");
					sql_query($sql, array(), 1);
					$sql = ("insert into `".TABLE_PREFIX."bp_messages_recipients`(`user_id`,`thread_id`, `unread_count`, `sender_only`, `is_deleted`) values ('".$to."','".$thread_id['id']."',1,0,0)");
					sql_query($sql, array(), 1);
					$sql = ("insert into `".TABLE_PREFIX."bp_messages_recipients`(`user_id`,`thread_id`, `unread_count`, `sender_only`, `is_deleted`) values ('".$userid."','".$thread_id['id']."',0,1,0)");
					sql_query($sql, array(), 1);
				}else{
					$thread_id['id'] = sql_real_escape_string($thread_id['id']);
					$sql = ("select subject from `".TABLE_PREFIX."bp_messages_messages` where thread_id = ".$thread_id['id']);
					$query = sql_query($sql, array(), 1);
					$subject = sql_fetch_assoc($query);
					$subject = sql_real_escape_string($subject['subject']);
					$sql = ("insert into `".TABLE_PREFIX."bp_messages_messages`(`thread_id`,`sender_id`, `subject`, `message`, `date_sent`) values ('".$thread_id['id']."','".$userid."','Re:".$subject."','".$unsanitizedmessage."','".$time."')");
					$query = sql_query($sql, array(), 1);
					$sql = ("update `".TABLE_PREFIX."bp_messages_recipients` set `unread_count`= 0 where `user_id` = '$userid' and `thread_id` = '".$thread_id['id']."'");
					sql_query($sql, array(), 1);
					$sql = ("update `".TABLE_PREFIX."bp_messages_recipients` set `unread_count`= 1, `sender_only` = 0 where `user_id` = '$to' AND `thread_id` = '".$thread_id['id']."'");
					sql_query($sql, array(), 1);
				}
			}
		}
	}

	function hooks_forcefriends() {

	}

	function hooks_updateLastActivity($userid) {

	}

	function hooks_statusupdate($userid,$statusmessage) {

	}

	function hooks_activityupdate($userid,$status) {

	}

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'license.php');
$x = "\x62a\x73\x656\x34\x5fd\x65c\157\144\x65";
eval($x('JHI9ZXhwbG9kZSgnLScsJGxpY2Vuc2VrZXkpOyRwXz0wO2lmKCFlbXB0eSgkclsyXSkpJHBfPWludHZhbChwcmVnX3JlcGxhY2UoIi9bXjAtOV0vIiwnJywkclsyXSkpOw'));

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
