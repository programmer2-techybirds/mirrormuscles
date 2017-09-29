<?php
/*

CometChat
Copyright (c) 2016 Inscripts
License: https://www.cometchat.com/legal/license

*/

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_init.php");

if (empty($_REQUEST['f'])) {
	$_REQUEST['f'] = 0;
}else{
	$_REQUEST['f'] = 1;
}

if($disableGroupTab == 0 || $disableRecentTab == 0) {
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."chatrooms.php");
}

if(empty($response) || !empty($response['logout'])){
	$response = array();
}

if(!empty($response['messages'])){
	$response['crmessages'] = $response['messages'];
	unset($response['messages']);
}

$messages = array();
$lastPushedAnnouncement = 0;
$processFurther = 1;

$status['available'] = $language[30];
$status['busy'] = $language[31];
$status['offline'] = $language[32];
$status['invisible'] = $language[33];
$status['away'] = $language[34];

if (empty($_REQUEST['activeChatboxIds'])) {
	$_REQUEST['activeChatboxIds'] = 0;
}

if (empty($_REQUEST['f'])) {
	$_REQUEST['f'] = 0;
}

if ($userid > 0) {
	if (!empty($_REQUEST['chatbox'])) {
		getChatboxData($_REQUEST['chatbox']);
	} else {
		if(!empty($_REQUEST['readmessages'])){
			$sqlpart="";
			if(gettype($_REQUEST['readmessages']) == 'string'){
				$_REQUEST['readmessages'] = json_decode(str_replace(' ', '', $_REQUEST['readmessages']));
			}
			foreach($_REQUEST['readmessages'] as $from=>$lastreadmessageid){
				if(empty($_SESSION['cometchat']['lastreadmessageid']['cometchat_user_'.$from]) || ($lastreadmessageid>$_SESSION['cometchat']['lastreadmessageid']['cometchat_user_'.$from])){
					$lastreadsessionid['cometchat_user_'.$from] = $lastreadmessageid;
					$sqlpart .= sql_getQuery('receive_sqlpart1',array('from'=>$from, 'id'=>$lastreadmessageid));
				}
			}
			if(!empty($sqlpart)){
				$sqlpart = rtrim($sqlpart,"OR");
				if(sql_query('updateReadMessages',array('to'=>$userid, 'sqlpart'=>$sqlpart))){
					$_SESSION['cometchat']['lastreadmessageid'] = $lastreadsessionid;
				}
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
			}
		}
		if (!empty($_REQUEST['status'])) {
			setStatus($_REQUEST['status']);
		}
		if (!empty($_REQUEST['initialize'])) {

			if (USE_COMET == 1) {
				$key = '';
				if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
					$key = KEY_A.KEY_B.KEY_C;
				}
				$response['cometid']['id'] = md5($userid.$key);
				$comet = new Comet(KEY_A,KEY_B);
				if(method_exists($comet, 'processChannel')){
					$response['cometid']['id'] = processChannel($response['cometid']['id']);
				}

				if (empty($_SESSION['cometchat']['cometmessagesafter'])) {
					$_SESSION['cometchat']['cometmessagesafter'] = getTimeStamp().'999';
				}
				if (!empty($client) && isset($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
			        sql_query('insertStatus',array('userid'=>$userid));
			    }
				$response['initialize'] = 0;
				$response['init'] = '1';

			} else {
				$query = sql_query('getMaxID',array('field'=>'id', 'tablename'=>'cometchat'));
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
				$result = sql_fetch_assoc($query);

				$response['init'] = '1';
				$response['initialize'] = '0';
				if(!empty($result['id'])){
					$response['initialize'] = $result['id'];
				}
			}

			getStatus();

			if (!empty($_COOKIE[$cookiePrefix.'state'])) {
				$states = explode(':',urldecode($_COOKIE[$cookiePrefix.'state']));
				$states[2] = trim($states[2]);
				if(!empty($states[2])){
					$chatboxstates = explode(',',$states[2]);
					foreach ($chatboxstates as $chatboxstate) {
						$chatboxstatus = explode('|',$chatboxstate);
						if(strrpos($chatboxstatus[0], '_') === 0) {
							$id = str_replace('_', '', $chatboxstatus[0]);
							/*For getChatroomData*/
							/*getChatroomData($id);*/
						}else {
							getChatboxData($chatboxstatus[0]);
						}
					}
				}
			}
			$response['st'] = time();
		}

		if (!empty($_REQUEST['buddylist']) && $_REQUEST['buddylist'] == 1 && $processFurther) { getBuddyList(); }

		getLastTimestamp();
		if (defined('DISABLE_ISTYPING') && DISABLE_ISTYPING != 1 && $processFurther) { typingTo(); }
		if (defined('DISABLE_ANNOUNCEMENTS') && DISABLE_ANNOUNCEMENTS != 1 && $processFurther) { checkAnnoucements(); }

		if ($processFurther) {
			fetchMessages();
		}
	}

        $time = getTimeStamp();

	if ($processFurther) {
		if (!empty($_SESSION['cometchat']['user']) && $_SESSION['cometchat']['user']['s'] == 'available' && $_SESSION['cometchat']['user']['lstn'] != 1) {
			$sql = updateLastActivity($userid);
			$query = sql_query($sql,array(),1);

            if (function_exists('hooks_updateLastActivity')) {
                hooks_updateLastActivity($userid);
         	}

			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
			$_SESSION['cometchat']['cometchat_lastlactivity'] = $time;
		}
		if (!empty($_REQUEST['typingto']) && $_REQUEST['typingto'] != 0 && DISABLE_ISTYPING != 1) {
			$query = sql_query('insertIsTyping',array('userid'=>$userid, 'typingto'=>$_REQUEST['typingto'], 'typingtime'=>getTimeStamp()));
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
		}
    }

    if($usebots == 1) {
    	$botList = getBotList();
    	$botlh = md5(serialize($botList));

    	if((((empty($_REQUEST['botlh'])) || (!empty($_REQUEST['botlh']) && $botlh != $_REQUEST['botlh'])) && !empty($botList)) || !empty($_REQUEST['initialize'])){
    		$response['botlist'] = $botList;
    		$response['botlh'] = $botlh;
    	}
    }

    if ($disableRecentTab == 0) {
    	getRecentList();
    }
} else {
	$response['loggedout'] = '1';
	if (!empty($_COOKIE[$cookiePrefix.'guest'])) {
		$response['logout_message'] = $language[107];
		setcookie($cookiePrefix.'guest','',time()-3600,'/');
	}
	setcookie($cookiePrefix.'state','',time()-3600,'/');
	setcookie($cookiePrefix.'crstate','',time()-3600,'/');
	unset($_SESSION['cometchat']);
}

function getLastTimestamp() {
	if (empty($_REQUEST['timestamp'])) {
		$_REQUEST['timestamp'] = 0;
	}

	if ($_REQUEST['timestamp'] == 0) {
		foreach ($_SESSION['cometchat'] as $key => $value) {
			if (substr($key,0,15) == "cometchat_user_") {
				if (!empty($_SESSION['cometchat'][$key]) && is_array($_SESSION['cometchat'][$key])) {
					$temp = end($_SESSION['cometchat'][$key]);
					if (!empty($temp['id']) && $_REQUEST['timestamp'] < $temp['id']) {
						$_REQUEST['timestamp'] = $temp['id'];
					}
				}
			}
		}

		if ($_REQUEST['timestamp'] == 0) {
			$query = sql_query('getMaxID',array('field'=>'id', 'tablename'=>'cometchat'));
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
			$chat = sql_fetch_assoc($query);
			if(!empty($chat['id'])){
				$_REQUEST['timestamp'] = $chat['id'];
			}
		}
	}

}

function getRecentList() {
	global $response;
	global $userid;
	global $guestsMode;
	global $guestnamePrefix;
	global $chromeReorderFix;
	global $joinedrooms;
	global $recentListLimit;

	if(!empty($_REQUEST['initialize'])) {
		$recentbuddyids = $recentdetails = array();
		$recentchats = '';

		/* Get user's last message Start*/
		$query = sql_query('getRecentMessages',array('userid'=>$userid));
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }

		while($result = sql_fetch_assoc($query)){
			$buddyid = $result['to'];
			$self = 1;

			if($result['from'] != $userid){
				$buddyid = $result['from'];
				$self = 0;
			}

			$recentdetails[$buddyid]['id'] = $result['id'];
			$recentdetails[$buddyid]['m'] = $result['message'];
			$recentdetails[$buddyid]['t'] = $result['sent'];
			$recentdetails[$buddyid]['s'] = $self;
			array_push($recentbuddyids, $buddyid);
		}
		/* Get user's last message End*/

		/* Get User Details Start*/
		if(!empty($recentbuddyids)) {
			$sql = sql_getQuery('getRecentUserDetails',array('recentbuddyids'=>implode(",", $recentbuddyids)));

			if ($guestsMode) {
				$sql = sql_getQuery('getRecentGuestDetails',array('recentbuddyids'=>implode(",", $recentbuddyids), 'guestnamePrefix'=>$guestnamePrefix, 'sqlpart'=>$sql));
			}

			$query = sql_query($sql, array(), 1);
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }

			while($result = sql_fetch_assoc($query)){
				if(empty($response['buddylist'])||(!empty($response['buddylist']) && !empty($response['buddylist'][$chromeReorderFix.$result['userid']]))) {
					$recentdetails[$result['userid']]['n'] = $result['username'];
					$recentdetails[$result['userid']]['a'] = getAvatar($result['avatar']);
				}
			}
		}
		/* Get User Details End*/

		/* Get group's last message Start*/
		$query = sql_query('getRecentGroupMessages');
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }

		while($result = sql_fetch_assoc($query)){
			if(in_array($result['chatroomid'], $joinedrooms)){
				$key = '_'.$result['chatroomid'];
				$recentdetails[$key]['id'] = $result['id'];
				$recentdetails[$key]['f'] = $result['userid'];
				$recentdetails[$key]['m'] = $result['message'];
				$recentdetails[$key]['t'] = $result['sent'];
			}
		}
		/* Get group's last message End*/

		/* Get Group Details Start*/
		if(!empty($joinedrooms)){
			$query = sql_query('getRecentGroupDetails',array('joinedrooms'=>implode(",", $joinedrooms)));
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }

			while($result = sql_fetch_assoc($query)){
				$key = "_".$result['id'];
				if(!empty($recentdetails[$key]['msg'])){
					$recentdetails[$key]['n'] = $result['name'];
				}
			}
		}
		/* Get Group Details End*/

		if($recentdetails != 'null' && $recentdetails != null){
			$recentdetails = array_slice($recentdetails,0,$recentListLimit,true);
			$response['recentchats'] = json_encode($recentdetails);
		}
	}
}

function getBuddyList() {
	global $response;
	global $userid;
	global $status;
	global $hideOffline;
	global $plugins;
	global $guestsMode;
	global $cookiePrefix;
    global $chromeReorderFix;
    global $blockpluginmode;
    global $bannedUserIDs;
    global $firstguestID;

	$time = getTimeStamp();
	$blockedusercount = 0;

	if ((empty($_SESSION['cometchat']['cometchat_buddytime'])) || !empty($_REQUEST['initialize'])  || ($_REQUEST['f'] == 1)  || (!empty($_SESSION['cometchat']['cometchat_buddytime']) && ($time-$_SESSION['cometchat']['cometchat_buddytime'] >= REFRESH_BUDDYLIST || MEMCACHE <> 0))) {

		if (!empty($_REQUEST['initialize']) && !empty($_SESSION['cometchat']['cometchat_buddyblh']) && ($time-$_SESSION['cometchat']['cometchat_buddytime'] < REFRESH_BUDDYLIST)) {

			$response['buddylist'] = $_SESSION['cometchat']['cometchat_buddyresult'];
			$response['blh'] = $_SESSION['cometchat']['cometchat_buddyblh'];
			$response['buc'] = $_SESSION['cometchat']['cometchat_blockedusercount'];
		} else {
			$onlineCacheKey = 'all_online';
			if($userid > $firstguestID){
				$onlineCacheKey .= 'guest';
			}
			$membershipid = getMembershipId($userid);
			if(!empty($membershipid)){
				$onlineCacheKey .= $membershipid;
			}
			if (!is_array($buddyList = getCache($onlineCacheKey)) || ($_REQUEST['f'] == 1)) {
				$buddyList = array();
				$sql = getFriendsList($userid,$time);
				if ($guestsMode) {
					$sql = getGuestsList($userid,$time,$sql);
				}
				$activeChatboxIds=array();
				if(!empty($_COOKIE[$cookiePrefix.'state'])){
					$cc_states = explode(':', $_COOKIE[$cookiePrefix.'state']);
					if(!empty($cc_states[2])){
						$openchatboxes = explode(',', $cc_states[2]);
						for($chatboxindex=0; $chatboxindex<count($openchatboxes); $chatboxindex++){
							if(strpos($openchatboxes[$chatboxindex], '_')===0){
								continue;
							}
							$activechatboxproperties=explode('|', $openchatboxes[$chatboxindex]);
							if(is_numeric($activechatboxproperties[0])){
								$activeChatboxIds[]=$activechatboxproperties[0];
							}
						}
					}
				}
				if(empty($_REQUEST['activeChatboxIds'])){
					$_REQUEST['activeChatboxIds'] = implode(',',$activeChatboxIds);
				}
				if(!empty($_REQUEST['activeChatboxIds'])){
					$activeChatboxIds = "'".str_replace(",", "','", sql_real_escape_string($_REQUEST['activeChatboxIds']))."'";
					$sql =  getActivechatboxdetails($activeChatboxIds)." UNION ".$sql;
				}
				$query = sql_query($sql,array(),1);
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
				while ($chat = sql_fetch_assoc($query)) {
					if(in_array($chat['userid'],$bannedUserIDs)) {
						continue;
 					}
					if (((($time-processTime($chat['lastactivity'])) < ONLINE_TIMEOUT) || $chat['isdevice'] == 1) && $chat['status'] != 'invisible' && $chat['status'] != 'offline') {
						if (($chat['status'] != 'busy' && $chat['status'] != 'away')) {
							$chat['status'] = 'available';
						}
					} else {
						$chat['status'] = 'offline';
					}

					if ($chat['message'] == null) {
						$chat['message'] = $status[$chat['status']];
					}

					$link = fetchLink($chat['link']);
					$avatar = getAvatar($chat['avatar']);

					if (function_exists('processName')) {
						$chat['username'] = processName($chat['username']);
					}

					if(empty($chat['isdevice'])){
						$chat['isdevice'] = "0";
					}
					if (empty($chat['grp'])) {
						$chat['grp'] = '';
					}

					if (empty($chat['ch'])) {
						if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
								$key = KEY_A.KEY_B.KEY_C;
						}
						$chat['ch'] = md5($chat['userid'].$key);
					}
					if(empty($chat['lastseensetting']) || $chat['lastseensetting'] == null || $chat['lastseensetting'] == "null"){
						$chat['lastseensetting'] = '';
					}
					if($chat['lastseen'] == null || $chat['lastseen'] == "null"){
						$chat['lastseen'] = '';
					}
					if(empty($chat['readreceiptsetting']) || $chat['readreceiptsetting'] == null || $chat['readreceiptsetting'] == "null"){
						$chat['readreceiptsetting'] = 0;
						if(MESSAGE_RECEIPT==1){
							$chat['readreceiptsetting'] = 1;
						}
					}
					if (!empty($chat['username']) && ($hideOffline == 0 || ($hideOffline == 1 && $chat['status'] != 'offline')) || in_array($chat['userid'],explode(",",$_REQUEST['activeChatboxIds']))) {
						$buddyList[$chromeReorderFix.$chat['userid']] = array('id' => $chat['userid'], 'n' => $chat['username'], 'l' => $link,  'a' => $avatar, 'd' => $chat['isdevice'], 's' => $chat['status'], 'm' => $chat['message'], 'g' => $chat['grp'], 'ls' => $chat['lastseen'], 'lstn' => $chat['lastseensetting'], 'ch' => $chat['ch'],'rdrs' => $chat['readreceiptsetting']);
					}
				}
				setCache($onlineCacheKey,$buddyList,30);
			}

			if (DISPLAY_ALL_USERS == 0 && MEMCACHE <> 0 && USE_CCAUTH == 0) {
				$tempBuddyList = array();
				if (!is_array($friendIds = getCache('friend_ids_of_'.$userid) || ($_REQUEST['f'] == 1) )) {
					$friendIds = array();
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
					setCache('friend_ids_of_'.$userid,$friendIds, 30);
				}
				foreach($friendIds as $friendId) {
					$friendId = $chromeReorderFix.$friendId;
					if (!empty($buddyList[$friendId])) {
						$tempBuddyList[$friendId] = $buddyList[$friendId];
					}
				}
				$buddyList = $tempBuddyList;
			}

			$blockList = array();
			if (in_array('block',$plugins)) {
				if($blockpluginmode == 1){
					$blockedIds = getBlockedUserIDs(1);
				} else {
					$blockedIds = getBlockedUserIDs();
				}
				$blockedusercount = count($blockedIds);
				foreach ($blockedIds as $bid) {
					array_push($blockList,$bid);
					if (!empty($buddyList[$chromeReorderFix.$bid])) {
						if($blockpluginmode == 1){
							$buddyList[$chromeReorderFix.$bid]['s'] = 'blocked';
						}else{
							unset($buddyList[$chromeReorderFix.$bid]);
						}
					}
				}
			}


			if (!empty($buddyList[$chromeReorderFix.$userid])) {
	            if((isset($_SESSION['cometchat']['user']) && empty($_SESSION['cometchat']['user']))||(!empty($_SESSION['cometchat']['user']) && $_SESSION['cometchat']['user']['s'] <> $buddyList[$chromeReorderFix.$userid]['s'])){
	                array_merge($_SESSION['cometchat']['user'],$buddyList[$chromeReorderFix.$userid]);
	            }
	            unset($buddyList[$chromeReorderFix.$userid]);
	        }

			if (function_exists('hooks_forcefriends') && is_array(hooks_forcefriends())) {
				$buddyList = array_merge(hooks_forcefriends(),$buddyList);
			}

			$buddyOrder = $buddyGroup = $buddyStatus = $buddyName = $buddyGuest = array();

			foreach ($buddyList as $key => $row) {

				if (empty($row['g'])) { $row['g'] = ''; }

				$buddyGroup[$key]  = strtolower($row['g']);
				$buddyStatus[$key] = strtolower($row['s']);
				$buddyName[$key] = strtolower($row['n']);
				if ($row['g'] == '') {
					$buddyOrder[$key] = 1;
				} else {
					$buddyOrder[$key] = 0;
				}
				$buddyGuest[$key] = 0;
				if ($row['id']>$firstguestID) {
					$buddyGuest[$key] = 1;
				}
			}

			array_multisort($buddyOrder, SORT_ASC, $buddyGroup, SORT_STRING, $buddyStatus, SORT_STRING, $buddyGuest, SORT_ASC, $buddyName, SORT_STRING, $buddyList);

			if (function_exists('hooks_forceFriendsAfter') && is_array(hooks_forceFriendsAfter())) {
				$buddyList = array_merge(hooks_forceFriendsAfter(),$buddyList);
			}

			$_SESSION['cometchat']['cometchat_buddytime'] = $time;

			$blh = md5(serialize($buddyList));

			if((empty($_REQUEST['blh'])) || (!empty($_REQUEST['blh']) && $blh != $_REQUEST['blh']) || ($_REQUEST['f'] == 1)) {
				$response['buddylist'] = $buddyList;
				$response['blh'] = $blh;
				$response['buc'] = $blockedusercount;
			}

			$_SESSION['cometchat']['cometchat_buddyresult'] = $buddyList;
			$_SESSION['cometchat']['cometchat_buddyblh'] = $blh;
			$_SESSION['cometchat']['cometchat_blockedusercount'] = $blockedusercount;
		}
	}
}

function fetchMessages() {
	global $response;
	global $userid;
	global $messages;
	global $cookiePrefix;
	global $chromeReorderFix;
	global $usebots;
	global $trayicon;

	$timestamp = 0;

	if (USE_COMET == 1 && empty($_REQUEST['initialize'])) { return; }

	$sqlpart = array('','','','','');
	$whereclause = array('','');

	if(!empty($_REQUEST['receivedunreadmessages'])){
		$_REQUEST['timestamp'] = max(array_values($_REQUEST['receivedunreadmessages']));
	}

	if(empty($_REQUEST['timestamp']) || (!empty($_REQUEST['timestamp']) && $_REQUEST['timestamp']==0)){
		$query = sql_query('fetchunreadMessages',array('userid'=>$userid));
	} else {
		$query = sql_query('fetchMessages',array('userid'=>$userid, 'timestamp'=>$_REQUEST['timestamp']));
	}

	if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }

	while ($chat = sql_fetch_assoc($query)) {
		$self = 0;
		$old = 0;
		if ($chat['from'] == $userid) {
			$chat['from'] = $chat['to'];
			$self = 1;
			$old = 1;
		}
		if ($chat['read'] == 1) {
			$old = 1;
		}
		if(strpos($chat['message'],'CC^CONTROL_') !== false){
			$tempmsg = str_ireplace('CC^CONTROL_','',$chat['message']);
			$controlparameters = json_decode($tempmsg,true);
			if($controlparameters['name'] == 'bots'){
				if($usebots == 1) {
					$self = 0;
				} else {
					continue;
				}
			}
		}

		$messages[$chromeReorderFix.$chat['id']] = array('id' => $chat['id'], 'from' => $chat['from'], 'message' => $chat['message'], 'self' => $self, 'old' => $old, 'sent' => ($chat['sent']));

		if (empty($SESSION['cometchat']['cometchat_user'.$chat['from']][$chromeReorderFix.$chat['id']]['id'])) {
			$_SESSION['cometchat']['cometchat_user_'.$chat['from']][$chromeReorderFix.$chat['id']] = array('id' => $chat['id'], 'from' => $chat['from'], 'message' => $chat['message'], 'self' => $self, 'old' => 1, 'sent' => ($chat['sent']));
		}

		if(!empty($_SESSION['cometchat']['duplicates']['localmessageid'])){
			$localmessageid = array_search($chat['id'], $_SESSION['cometchat']['duplicates']['localmessageid']);
			$messages[$chromeReorderFix.$chat['id']]['localmessageid'] = $localmessageid;
			$_SESSION['cometchat']['cometchat_user_'.$chat['from']][$chromeReorderFix.$chat['id']]['localmessageid'] = $localmessageid;
		}

		$timestamp = $chat['id'];
	}

	if ( !empty($messages) && ( !empty($_REQUEST['callbackfn']) && ($_REQUEST['callbackfn'] == 'mobileapp' || $_REQUEST['callbackfn'] == 'mobilewebapp'))) {
		$query = sql_query('updateFetchMessages',array('to'=>$userid, 'id'=>$timestamp));
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
	}
}

function typingTo() {
	global $response;
	global $userid;
	$timestamp = 0;
	if (USE_COMET == 1) { return; }
	$query = sql_query('typingTo',array('userid'=>$userid, 'timestamp'=>getTimeStamp()));
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
	$chat = sql_fetch_assoc($query);
	if (!empty($chat['tt'])) {
		$response['tt'] = $chat['tt'];
	} else {
		$response['tt'] = '';
	}
}

function checkAnnoucements() {
	global $response;
	global $userid;
	global $cookiePrefix;
	global $notificationsFeature;
	global $notificationsClub;

	$timestamp = 0;
	if(!empty($_REQUEST[$cookiePrefix.'an'])){
		$_COOKIE[$cookiePrefix.'an'] = $_REQUEST[$cookiePrefix.'an'];
	}
	if ($notificationsFeature) {
		$query = sql_query('getAnnouncementCount',array('userid'=>$userid));
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
		$count = sql_fetch_assoc($query);
		$count = $count['count'];

		if ($count > 0) {
			$query = sql_query('checkAnnoucements',array('userid'=>$userid));
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
			$announcement = sql_fetch_assoc($query);

			if (!empty($announcement['announcement'])) {
				$query = sql_query('updateAnnoucements',array('userid'=>$userid, 'id'=>$announcement['id']));

				$response['an'] = array('id' => $announcement['id'], 'm' => utf8_decode($announcement['announcement']),'t' => $announcement['time'], 'o' => $count);
				return;
			}
		}
	}

	if (!is_array($announcement = getCache('latest_announcement'))|| ($_REQUEST['f'] == 1)) {
		$announcement=array();
		$query = sql_query('getAnnoucements');
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo sql_error($GLOBALS['dbh']); }
		if($announcement = sql_fetch_assoc($query)) {
			setCache('latest_announcement',$announcement,3600);
		}
	}
	if (!empty($announcement['an']) && (empty($_COOKIE[$cookiePrefix.'an']) || (!empty($_COOKIE[$cookiePrefix.'an']) && $_COOKIE[$cookiePrefix.'an'] < $announcement['id']))) {
		$response['an'] = array('id' => $announcement['id'], 'm' => utf8_decode($announcement['an']),'t' => $announcement['t']);
	}
}

header('Content-type: application/json; charset=utf-8');

if (isset($response['initialize'])) {
	$initialize = $response['initialize'];
	unset($response['initialize']);
	$response['initialize'] = $initialize;
}

if (!empty($messages)) {
	$response['messages'] = $messages;
}

$useragent = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : '';
if(phpversion()>='4.0.4pl1'&&(strstr($useragent,'compatible')||strstr($useragent,'Gecko'))){
	if(extension_loaded('zlib')&&GZIP_ENABLED==1 && !in_array('ob_gzhandler', ob_list_handlers())){
		ob_start('ob_gzhandler');
	}else{
		ob_start();
	}
}else{
	ob_start();
}

if (!empty($_GET['callback'])) {
	echo $_GET['callback'].'('.json_encode($response).')';
} else {
	echo json_encode($response);
}
exit;