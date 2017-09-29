<?php

/*

CometChat
Copyright (c) 2016 Inscripts
License: https://www.cometchat.com/legal/license

*/

include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php");
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang.php")) {
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang.php");
}

$embed =$target = '';
$close = "setTimeout('window.close()',2000);";

if(!empty($_REQUEST['embed'])){
	$embed = $_REQUEST['embed'];
}
$basedata=0;
if(!empty($_REQUEST['basedata'])){
	$basedata = $_REQUEST['basedata'];
}
$onsubmit='onSubmit= "return true;"';
if($embed=='desktop'){
	$close = "";
	$target = "target='invite'";
	$onsubmit='onSubmit="return inviteUsers();"';
}

function invite() {
	global $userid;
	global $audiochat_language;
	global $language;
	global $embed;
	global $embedcss;
	global $lightboxWindows;
	global $guestsMode;
	global $cookiePrefix;
    global $chromeReorderFix;
	global $hideOffline;
    global $target;
    global $basedata, $onsubmit;
    global $firstguestID;
	if($lightboxWindows == '1') {
		$embed = 'web';
		$embedcss = 'embed';

	}

	$status['available'] = $language[30];
	$status['busy'] = $language[31];
	$status['offline'] = $language[32];
	$status['invisible'] = $language[33];
	$status['away'] = $language[34];

	$id = $_GET['roomid'];

	if (empty($id)) { exit; }

	$time = getTimeStamp();
	$buddyList = array();

	$onlineCacheKey = 'all_online';
	if($userid > $firstguestID){
		$onlineCacheKey .= 'guest';
	}
	$membershipid = getMembershipId($userid);
	if(!empty($membershipid)){
		$onlineCacheKey .= $membershipid;
	}
	if ($onlineUsers = getCache($cookiePrefix.$onlineCacheKey, 30)) {
		$buddyList = unserialize($onlineUsers);
	} else {
		$sql = getFriendsList($userid,$time);
		if($guestsMode){
	    	$sql = getGuestsList($userid,$time,$sql);
		}
		$query = sql_query($sql, array(), 1);
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
		while ($chat = sql_fetch_assoc($query)) {
			if (((($time-processTime($chat['lastactivity'])) < ONLINE_TIMEOUT) && $chat['status'] != 'invisible' && $chat['status'] != 'offline') || $chat['isdevice'] == 1) {
				if ($chat['status'] != 'busy' && $chat['status'] != 'away') {
					$chat['status'] = 'available';
				}
			} else {
				$chat['status'] = 'offline';
			}

			$avatar = getAvatar($chat['avatar']);

			if (!empty($chat['username'])) {
				if (function_exists('processName')) {
					$chat['username'] = processName($chat['username']);
				}
				if ($chat['userid'] != $userid && ($hideOffline == 0||($hideOffline == 1 && $chat['status']!='offline'))) {
					$buddyList[$chromeReorderFix.$chat['userid']] = array('id' => $chat['userid'], 'n' => $chat['username'], 'a' => $avatar, 's' => $chat['status']);
				}
			}
		}
	}

	if (DISPLAY_ALL_USERS == 0 && MEMCACHE <> 0) {
		$tempBuddyList = array();
		if ($onlineFrnds = getCache($cookiePrefix.'friend_ids_of_'.$userid, 30)) {
			$friendIds = unserialize($onlineFrnds);
		} else {
			$sql = getFriendsIds($userid);
			$query = sql_query($sql, array(), 1);
			if(sql_num_rows($query) == 1 ){
				$buddy = sql_fetch_assoc($query);
				$friendIds = explode(',',$buddy['friendid']);
			}else {
				while($buddy = sql_fetch_assoc($query)){
					$friendIds[]=$buddy['friendid'];
				}
			}
			setCache($cookiePrefix.'friend_ids_of_'.$userid,serialize($friendIds), 30);
		}
		foreach($friendIds as $friendId) {
			$friendId = $chromeReorderFix.$friendId;
			if (isset($buddyList[$friendId])) {
				$tempBuddyList[$friendId] = $buddyList[$friendId];
			}
		}
		$buddyList = $tempBuddyList;
	}

	if (function_exists('hooks_forcefriends') && is_array(hooks_forcefriends())) {
		$buddyList = array_merge(hooks_forcefriends(),$buddyList);
	}

	$s['available'] = '';
	$s['away'] = '';
	$s['busy'] = '';
	$s['offline'] = '';

	foreach ($buddyList as $buddy) {
		if($buddy['id'] != $userid){
			$s[$buddy['s']] .= '<div class="invite_1"><div class="invite_2" onclick="javascript:document.getElementById(\'check_'.$buddy['id'].'\').checked = document.getElementById(\'check_'.$buddy['id'].'\').checked?false:true;"><img height=30 width=30 src="'.$buddy['a'].'"></div><div class="invite_3" onclick="javascript:document.getElementById(\'check_'.$buddy['id'].'\').checked = document.getElementById(\'check_'.$buddy['id'].'\').checked?false:true;"><span class="invite_name">'.$buddy['n'].'</span><br/><span class="invite_5">'.$status[$buddy['s']].'</span></div><input type="checkbox" name="invite[]" value="'.$buddy['id'].'" id="check_'.$buddy['id'].'" class="invite_4"></div>';
		}
	}

	$inviteContent = '';
	$invitehide = '';
	$inviteContent = $s['available']."".$s['away']."".$s['offline'];
	if(empty($inviteContent)) {
		$inviteContent = $audiochat_language[25];
		$invitehide = 'style="display:none;"';
	}

	echo <<<EOD
<!DOCTYPE html>
<html>
<head>
<title>{$audiochat_language[18]}</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=audiochat" />
<script src="../../js.php?type=core&name=jquery"></script>
<script>
  $ = jQuery = jqcc;
</script>
<script>
	function inviteUsers(){
		var invite=[];
		$(document).find(".invite_4:checked").each(function(){
			invite.push($(this).val());
		});
		var roomid = $(document).find("input[name=roomid]").val();
		var basedata = {$basedata};
		$.ajax({
			url: 'invite.php?action=inviteusers&embed={$embed}',
			data:{invite:invite,roomid:roomid,basedata:basedata},
			type:"POST",
			async: false,
			success:function(data){
				$(document).find("body").html('<div class="container_title {$embedcss}">{$audiochat_language[16]}</div><div class="container_body {$embedcss}">{$audiochat_language[12]}</span><div style="clear:both"></div></div>');
				setTimeout(function(){parentSandboxBridge.closeCCPopup()},2000);
			}
		});
	return false;
	}
	$(function(){
		$('.invite_1').click(function() {
		    var checked = $( "input:checked" ).length;
		    if(checked > 0){
		    	$('.invitebutton').attr("disabled", false);
		    }else{
		    	$('.invitebutton').attr("disabled", true);
		    }
		});
	});
</script>
</head>
<body>
<form method="post"  action="invite.php?action=inviteusers&embed={$embed}" {$onsubmit}>
<div class="cometchat_wrapper">
	<div class="container_title {$embedcss}">{$audiochat_language[16]}</div>
	<div class="container_body {$embedcss}">
		{$inviteContent}
		<div style="clear:both"></div>
	</div>
	<div class="container_sub" {$invitehide}>
		<input type="submit" value="{$audiochat_language[17]}" class="invitebutton" disabled>
	</div>
</div>
<input type="hidden" name="roomid" value="$id">
</form>
</body>
</html>
EOD;

}

function inviteusers() {
	global $audiochat_language;
	global $userid;
	global $close;
	global $embedcss;
	global $lightboxWindows;

	if($lightboxWindows == '1') {
		$embedcss = 'embed';

	}

	foreach ($_POST['invite'] as $user) {
		$response = sendMessage($user,"{$audiochat_language[14]}<a href=\"javascript:jqcc.ccaudiochat.accept_fid('{$userid}','{$_POST['roomid']}')\">{$audiochat_language[15]}</a>",1);
	}

	echo <<<EOD
<!DOCTYPE html>
<html>
<head>
<title>{$audiochat_language[18]}</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=audiochat" />
</head>
<body onload="{$close}">
<div class="cometchat_wrapper">
	<div class="container_title {$embedcss}">{$audiochat_language[16]}</div>
	<div class="container_body {$embedcss}">
		{$audiochat_language[12]}</span>
		<div style="clear:both"></div>
	</div>
</div>
</body>
</html>
EOD;

}

$allowedActions = array('invite','inviteusers');
$action = 'invite';

if (!empty($_GET['action']) && function_exists($_GET['action']) && in_array($_GET['action'],$allowedActions)) {
    $action = sql_real_escape_string($_GET['action']);
}
call_user_func($action);
