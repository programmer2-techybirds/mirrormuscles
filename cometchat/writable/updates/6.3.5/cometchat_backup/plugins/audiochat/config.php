<?php

include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$audioPluginType = setConfigValue('audioPluginType','0');
$selfhostedwebrtc = setConfigValue('selfhostedwebrtc','');

/* SETTINGS END */

$camWidth = '225';
$camHeight = '200';

/* audioPluginType Codes
0. CometChat Servers (WebRTC)
1. Self-hosted WebRTC
*/

$webRTCServer = 'r.chatforyoursite.com';
$webRTCPHPServer = 's.chatforyoursite.com';

if($audioPluginType == '1'){
	$webRTCServer = $selfhostedwebrtc;
	$webRTCPHPServer = BASE_URL."transports/cometservice-selfhosted";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////