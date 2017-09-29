<?php

include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'config.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$videoPluginType = setConfigValue('videoPluginType','0');
$selfhostedwebrtc = setConfigValue('selfhostedwebrtc','');

/* SETTINGS END */

$vidWidth = '350';
$vidHeight = '262';
$maxP = '10';
$quality = '90';
$connectUrl = '';
$camWidth = '450';
$camHeight = '335';
$fps = '30';
$soundQuality = '7';

/* videoPluginType Codes
0. CometChat Servers (WebRTC)
1. Self-hosted WebRTC
*/

$webRTCServer = 'r.chatforyoursite.com';
$webRTCPHPServer = 's.chatforyoursite.com';
if ($videoPluginType == '0') {
	$camWidth = '450';
	$camHeight = '335';
}

if ($videoPluginType == '1') {
	$webRTCServer = $selfhostedwebrtc;
	$webRTCPHPServer = BASE_URL."transports/cometservice-selfhosted";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////