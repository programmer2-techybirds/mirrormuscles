<?php

/*

CometChat
Copyright (c) 2016 Inscripts
License: https://www.cometchat.com/legal/license

*/
include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php");

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang.php")) {
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang.php");
}

$basedata = $action = null;

if(!empty($_REQUEST['basedata'])) {
	$basedata = sql_real_escape_string($_REQUEST['basedata']);
}

if(!empty($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}

if($action == 'sendSticker') {
	$to = $_REQUEST['to'];
	$key = $_REQUEST['key'];
	$chatroommode = $_REQUEST['chatroommode'];
	$category = $_REQUEST['category'];
	$caller = $_REQUEST['caller'];
	if (!empty($chatroommode)) {
		$controlparameters = array('type' => 'plugins', 'name' => 'stickers', 'method' => 'sendSticker', 'params' => array('to' => $to, 'key' => $key, 'chatroommode' => $chatroommode, 'category' => $category, 'caller' => $caller));
		$controlparameters = json_encode($controlparameters);
		$response = sendChatroomMessage($to, 'CC^CONTROL_'.$controlparameters);
	} else {
		$controlparameters = array('type' => 'plugins', 'name' => 'stickers', 'method' => 'sendSticker', 'params' => array('to' => $to, 'key' => $key, 'chatroommode' => $chatroommode, 'category' => $category, 'caller' => $caller));
		$controlparameters = json_encode($controlparameters);
		$response = sendMessage($to,'CC^CONTROL_'.$controlparameters);
		pushMobileNotification($to,$response['id'],$_SESSION['cometchat']['user']['n'].":".$stickers_language[2]);
	}
	if (!empty($_REQUEST['callback'])) {
		echo $_REQUEST['callback'].'('.json_encode($response).')';
	} else if($response !=null && !empty($response)) {
		echo json_encode($response);
	}
} else {
	$id = $_GET['id'];
	$text = '';
	$categories = array();
	$category = scandir(dirname(__FILE__).DIRECTORY_SEPARATOR."images");
	foreach($category as $c){
	    if($c != '.' && $c != '..'){
	        array_push($categories, $c);
	    }
	}

	$tab = '';
	$body_content = '';
	$used = array();

	$chatroommode = 0;
	$broadcastmode = 0;
	$caller = '';
	if (!empty($_GET['chatroommode'])) {
		$chatroommode = 1;
	}
	if (!empty($_GET['broadcastmode'])) {
		$broadcastmode = 1;
	}
	if (!empty($_GET['caller'])) {
		$caller = $_GET['caller'];
	}
	$embed = '';
	$embedcss = '';
	$close = "setTimeout('window.close()',2000);";
	$before = 'window.opener';

	if (!empty($_GET['embed']) && $_GET['embed'] == 'web') {
		$embed = 'web';
		$embedcss = 'embed';
		$close = "";
		$before = 'parent';

		if ($chatroommode == 1) {
			$before = "$('#cometchat_trayicon_chatrooms_iframe,#cometchat_container_chatrooms .cometchat_iframe,.cometchat_embed_chatrooms',parent.document)[0].contentWindow";
		}
		if ($broadcastmode == 1) {
			$before = "$('#cometchat_trayicon_chatrooms_iframe,#cometchat_container_chatrooms .cometchat_iframe,.cometchat_embed_chatrooms',parent.document)[0].contentWindow";
		}
	}

	if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') {
		$embed = 'desktop';
		$embedcss = 'embed';
		$close = "";
		$before = 'parentSandboxBridge';
	}

	$hideadditional = '';
	$showusidstickers = false;

	$style4thjuly2017 = "";
	$ts4thjuly2017 = strtotime('2017-07-04 00:00:00' );
	/*$ts4thjuly2017 = strtotime('today UTC'); //uncomment to test*/
	if(time()>$ts4thjuly2017-1*60*60 && in_array('independencedayus', $categories)){
		$showusidstickers = true;
	}
	if(in_array('independencedayus', $categories)){
		array_splice($categories, array_search('independencedayus', $categories ), 1);
	}
	if($showusidstickers){
		array_unshift($categories, 'independencedayus');
	}
	foreach ($categories as $key=>$value) {
		$selected = '';
		$sticker_selected = '';
		$content = '';
		if($key==0){
			$selected = 'sticker_tab_selected';
			$sticker_selected = 'sticker_selected';
		}
		$tab .= '<div id="'.$value.'" class="tab '.$value.' '.$selected.' '.$sticker_selected.'" ></div>';
		if($value=='independencedayus'){
			$style4thjuly2017 = '.'.$value.'{ background: url("'.BASE_URL.'plugins/stickers/images/'.$value.'/independencedayus_1.png") no-repeat center}';
		}
		$images = scandir(dirname(__FILE__).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.$value);
		foreach ($images as $key=>$val) {
			if($val != '.' && $val != '..'){
				$class = explode('.png',$val);
				$content .= '<span class="cometchat_sticker_image '.$class[0].'" category="'.$value.'" chatroommode="'.$chatroommode.'" caller = "'.$caller.'"></span>';
				if($value == 'independencedayus'){
					$style4thjuly2017 .= '.'.$class[0].'{ background-image: url("'.BASE_URL.'plugins/stickers/images/'.$value.'/'.$val.'")}';
				}
			}
		}
		$body_content .= '<div class="'.$value.' stickers '.$sticker_selected.'">'.$content.'</div>';
	}

	if(!empty($style4thjuly2017)){
		$style4thjuly2017 = '<style>'.$style4thjuly2017.'</style>';
	}

	$extrajs = "";
	$scrollcss = "overflow-y:scroll;overflow-x:hidden;position:absolute;top:26px;";
	if ($sleekScroller == 1) {
		$extrajs = '<script src="../../js.php?type=core&name=scroll"></script>';
		$scrollcss = "";
	}

	$cc_theme = '';
	if(!empty($_REQUEST['cc_theme'])){
		$cc_theme = $_REQUEST['cc_theme'];
	}
	if($cc_theme == 'embedded'){
		$scrollcss = "height:160px !important;";
	}
echo <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<title>{$stickers_language[0]}</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=stickers&subtype=stickers&cc_theme={$cc_theme}" />
		<script src="../../js.php?type=core&name=jquery"></script>
		<script src="../../js.php?type=core&name=slick"></script>
		<script src="../../js.php?type=plugin&name=stickers" type="text/javascript"></script>
		{$style4thjuly2017}
		<script>
			$ = jQuery = jqcc;
			var theme = '{$cc_theme}';
		</script>
		{$extrajs}
		<style>
			.container_body {
				{$scrollcss};
			}
			.container_body.embed {
				{$scrollcss};
			}
		</style>
		<script type="text/javascript">
	    	$(function(){
	    		$('.tab').click(function(){
	    			$('.tab').removeClass('selected');
	    			$(this).addClass('selected');
	    			$('.tab').removeClass('sticker_tab_selected');
	    			$('.stickers').removeClass('sticker_selected');
	    			if(theme == 'embedded'){
	    				$('.tab').removeClass('sticker_tab_selected');
	    				$(this).addClass('sticker_tab_selected');
	    			} else {
	    				$('.tab').removeClass('sticker_selected');
	    				$(this).addClass('sticker_selected');
	    			}
	    			$('.'+$(this).attr('id')).addClass('sticker_selected');
	    		});
			$('.cometchat_sticker_image').click(function(){
				var key = $(this).attr('class').split(' ')[1];
				var category = $(this).attr('category');
				var chatroommode = $(this).attr('chatroommode');
				var caller = $(this).attr('caller');
				var controlparameters = {"type":"plugins", "name":"ccstickers", "method":"sendStickerMessage", "params":{"to":{$id}, "key":key, "chatroommode":chatroommode, "category":category, "caller":caller}};
				controlparameters = JSON.stringify(controlparameters);
				if(typeof(parent) != 'undefined' && parent != null && parent != self){
					parent.postMessage('CC^CONTROL_'+controlparameters,'*');
				} else {
					window.opener.postMessage('CC^CONTROL_'+controlparameters,'*');
				}
			});
			var mobileDevice = navigator.userAgent.match(/ipad|ipod|iphone|android|windows ce|Windows Phone|blackberry|palm|symbian/i);
				if(mobileDevice){
					$(".container_body").css({'overflow-y': 'auto'});
					$("#tabs_container").css({'float': 'none'});
				}else if (jQuery().slimScroll) {
				$(".container_body").slimScroll({ width: '100%'});
				$(".container_body").slimScroll({ height: '160px'});
			}
			if (jQuery().slick) {
				jqcc('#tabs_container').slick({ infinite: false, slidesToShow: 4, slidesToScroll: 1 });
			}
		});
	    </script>
	</head>
	<body>
		<div class="cometchat_wrapper">
			<div id="tabs_container">
				{$tab}
		    </div>
			<div class="container_body {$embedcss}">
				{$body_content}
			</div>
		</div>
	</body>
</html>
EOD;
}
