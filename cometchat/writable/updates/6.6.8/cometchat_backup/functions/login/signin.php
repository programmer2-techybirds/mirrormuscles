<?php
include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'cometchat_init.php');
/**
 * Check if any error exists
 */
$error = "";
if (!empty($_GET["error"])) {
    $error = trim( strip_tags(  $_GET["error"] ) );
}

if( !empty( $_GET["network"] ) ) {
    $config = dirname(__FILE__) .DIRECTORY_SEPARATOR. 'config.php';
    require_once( dirname(__FILE__) .DIRECTORY_SEPARATOR. 'Social/Auth.php' );

    try{
        $socialAuth = new Social_Auth( $config );

        $network = trim( strip_tags( $_GET["network"] ) );
        $adapter = $socialAuth->authenticate( $network );

    } catch( Exception $e ) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>CometChat Social Authentication</title>
    <!-- Custom Css Styles Start-->
    <style>
        .hidden {
            display: none;
        }
    </style>
    <!-- Custom Css Styles End -->
</head>

<body style="padding: 40px 15px;">
<script>
var window_opener = window.opener;
try {
    if(typeof(window_opener.jqcc) == 'undefined') {
        window_opener = window_opener.window.opener;
        window_opener.close();
    }
} catch(e){
}
<?php if( isset( $_GET["network"] ) && $_GET["network"] ) {
    $network = trim( strip_tags( $_GET["network"] ) );
    if(!$socialAuth->isNetworkConnected($network)) {
?>
        window_opener.postMessage("alert^<?php echo ucfirst($network); ?> has not been configured correctly.",'*');
<?php
} else if(!empty( $_GET["caller"] ) && $_GET["caller"] == 'mobilewebapp' ){
?>
    window_opener.postMessage('ccmobilewebapp_reinitializeauth','*');
<?php
    } else {
?>
        window_opener.postMessage('cc_reinitializeauth','*');

<?php }
?>
    window.close();
<?php }
?>
</script>
</body>
</html>
