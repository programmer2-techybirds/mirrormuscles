<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$videoPluginType = setConfigValue('videoPluginType','0');
$selfhostedwebrtc = setConfigValue('selfhostedwebrtc','');

/* SETTINGS END */

$vidWidth = '220';
$vidHeight = '165';
$maxP = '10';
$quality = '90';
$winWidth = '650';
$winHeight = '365';
$connectUrl = '';
$camWidth = '440';
$camHeight = '330';
$fps = '30';
$soundQuality = '7';
$email = 'email';

/* videoPluginType Codes
0. CometChat Servers (WebRTC)
1. Self-hosted WebRTC
*/

$webRTCServer = 'r.chatforyoursite.com';
$webRTCPHPServer = 's.chatforyoursite.com';
if ($videoPluginType == '1') {
	$webRTCServer = $selfhostedwebrtc;
	$webRTCPHPServer = BASE_URL."transports/cometservice-selfhosted";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////