<?php
/**
 *
 *
 * @package cometchat
 */
$site_url = get_site_url();
$license = ABSPATH.'/cometchat/license.php';
					if(file_exists($license)){
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ).'ccstyle.css';?>" >
</head>
<body>
	<div class="updated">
        <p><?php _e( 'CometChat has been successfully installed!'); ?></p>
    </div>

    <table class="adminform">
	    <thead>
		    <tr>
		        <th>You can now access the admin panel directly from the navigation menu. Your default login is:</th>
		    </tr>
	    </thead>
	    <tfoot>
	    	<tr>
	    	<th>
	    	The CometChat bar is now active on your site!
	    	</th></tr>
	    </tfoot>
	    <tr>

	        <td><b>Username:</b> cometchat</td>
	    </tr>
	    <tr>
	        <td><b>Password:</b> cometchat</td>
	    </tr>
	    <tr>
	       <td style="padding: 10px;">
				<a class="button-primary" href="<?php echo $site_url;?>" target="_blank">View Website</a>
				<a class="button-primary" href="" target="_self" style="margin-left: 10px;">Access CometChat Admin Panel</a>
			</td>
	    </tr>
	</table>
</body>
</html>
<?php
}?>

