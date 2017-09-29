<?php

/*

CometChat
Copyright (c) 2016 Inscripts
License: https://www.cometchat.com/legal/license

*/
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

if(BAR_DISABLED==1 && empty($_REQUEST['admin'])){
	exit();
}

if(get_magic_quotes_runtime()){
	set_magic_quotes_runtime(false);
}

$mtime = explode(" ",microtime());
$starttime = $mtime[1]+$mtime[0];

$HTTP_USER_AGENT = '';
$useragent = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

if(empty($theme)){
	$theme = 'docked';
}

if(empty($color)){
	$color = 'color1';
}

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
}

ob_start();

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."colors".DIRECTORY_SEPARATOR.'color.php');

$left = 'left';
$right = 'right';
$dir = 'ltr';
$cbfn = '';

if($rtl==1){
	$left = 'right';
	$right = 'left';
	$dir = 'rtl';
}

if(!empty($_REQUEST['callbackfn'])){
	$cbfn = $_REQUEST['callbackfn'];
}
if(!empty($_REQUEST['admin'])){
	$admincss=dirname(__FILE__).DIRECTORY_SEPARATOR.'writable'.DIRECTORY_SEPARATOR.$writable.DIRECTORY_SEPARATOR.'admin.css';
	if(file_exists($admincss)&&DEV_MODE!=1){
		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($admincss) ||
			@trim($_SERVER['HTTP_IF_NONE_MATCH']) == md5_file($admincss)) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
		readfile($admincss);
		$css = ob_get_clean();
	}else{
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."admin.css");
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."admin2.css");
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."jquery-ui.css");
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."colorpicker.css");

		$css = minify(ob_get_clean());

		$fp = @fopen($admincss,'w');
		@fwrite($fp,$css);
		@fclose($fp);
	}
	$lastModified = filemtime($admincss);
	$etag = md5_file($admincss);
}else{
	$type = 'core';
	$name = 'default';
	$js = '';

	if(!empty($_REQUEST['type'])&&!empty($_REQUEST['name'])){
		$type = cleanInput($_REQUEST['type']);
		$name = cleanInput($_REQUEST['name']);
	}

	$subtype = '';
	if(!empty($_REQUEST['subtype'])){
		$subtype = cleanInput($_REQUEST['subtype']);
	}

	$cbfn = '';
	if(!empty($_REQUEST['callbackfn'])){
		$cbfn = cleanInput($_REQUEST['callbackfn']);
	}

	$cssfile = dirname(__FILE__).DIRECTORY_SEPARATOR.'writable'.DIRECTORY_SEPARATOR.$writable.DIRECTORY_SEPARATOR.$cbfn.$type.$name.$theme.$color.$lang.'.css';

	if(file_exists($cssfile)&&DEV_MODE!=1){
		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($cssfile) ||
			@trim($_SERVER['HTTP_IF_NONE_MATCH']) == md5_file($cssfile)) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
		readfile($cssfile);
		$css = ob_get_clean();
	}else{
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'docked'.DIRECTORY_SEPARATOR.'config.php');
		if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'config.php')){
			include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'config.php');
		}
		if($type!='core'||$name!='default'){
			if(!empty($name)&&$cbfn!='desktop'){
				if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$name.".css")){
					include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$name.".css");
				}elseif(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."docked".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$name.".css")){
					include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."docked".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$name.".css");
				}
			}else{
				include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
			}
			if(!empty($subtype)){
				if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$type.'s'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$subtype.'.css')){
					include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.$type.'s'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$subtype.'.css');
				}
			}
		}else{
			if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css")){
				include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
			}else{
				include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."docked".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
			}
			if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.'mobile'.DIRECTORY_SEPARATOR.'config.php')){
				include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.'mobile'.DIRECTORY_SEPARATOR.'config.php');
				if($enableMobileTab&&file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.'mobile'.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css")){
					include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.'mobile'.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
				}
			}
		}
		if(!empty($settings['enable_customcss']) && $settings['enable_customcss']['value'] == 1 && !empty($settings['customcss']) && empty($cbfn)){
			echo $settings['customcss']['value'];
		}
		$css = minify(ob_get_clean());
		$fp = @fopen($cssfile,'w');
		@fwrite($fp,$css);
		@fclose($fp);
	}
	$lastModified = filemtime($cssfile);
	$etag = md5_file($cssfile);
}

if(phpversion()>='4.0.4pl1'&&(strstr($useragent,'compatible')||strstr($useragent,'Gecko'))){
	if(extension_loaded('zlib')&&GZIP_ENABLED==1){
		ob_start('ob_gzhandler');
	}else{
		ob_start();
	}
}else{
	ob_start();
}

header('Content-type: text/css;charset=utf-8');
header("Last-Modified: ".gmdate("D, d M Y H:i:s",$lastModified)." GMT");
header('Expires: '.gmdate("D, d M Y H:i:s",time()+3600*24*365).' GMT');
header("Etag: ".$etag);
echo $css;

$mtime = explode(" ",microtime());
$endtime = $mtime[1]+$mtime[0];

echo "\n\n/* Execution time: ".($endtime-$starttime)." seconds */";
function cleanInput($input){
	$input = preg_replace("/[^+A-Za-z0-9\_]/","",trim($input));
	return strtolower($input);
}
function minify($css){
	$css = preg_replace('#\s+#',' ',$css);
	$css = preg_replace('#/\*.*?\*/#s','',$css);
	$css = str_replace('; ',';',$css);
	$css = str_replace(': ',':',$css);
	$css = str_replace(' {','{',$css);
	$css = str_replace('{ ','{',$css);
	$css = str_replace(', ',',',$css);
	$css = str_replace('} ','}',$css);
	$css = str_replace(';}','}',$css);
	return trim($css);
}