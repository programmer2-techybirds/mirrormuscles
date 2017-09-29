<?php

/**
 *
 *
 * @package cometchat
 */
	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

	$cometchat_dir = ABSPATH.'cometchat';
	if(isset($_POST['install-plugin-update'])){
		$cometchat_old_dir = $cometchat_dir.'_'.time();
		shell_exec("cp -r $cometchat_dir $cometchat_old_dir");
		if(fileUpload()){
				$dir = plugin_dir_path( __FILE__ ).'adminform.php';
				require_once($dir);
		}
	}
	if(is_dir($cometchat_dir) && file_exists($cometchat_dir. DS .'license.php') && is_dir($cometchat_dir. DS .'admin') && !isset($_POST['install-plugin-update'])) {
		$dir = plugin_dir_path( __FILE__ ).'menu.php';
		require_once($dir);
	}
	else{
		if(isset($_POST['install-plugin-submit'])){
			if(fileUpload()){
				$dir = plugin_dir_path( __FILE__ ).'adminform.php';
				require_once($dir);
			}
		}
		else{
			if(!isset($_POST['install-plugin-update'])){
			?>

			<table class="upload-plugin" style="display:block;">
				<th>
					<p class="install-help"><?php _e('You are one step away from having CometChat on your site! Please select "cometchat.zip" which you have downloaded from our site and click "Install" to proceed.'); ?></p>
				</th>
				<tr>
					<td>
						<form method="post" enctype="multipart/form-data" class="wp-upload-form" action = "">
							<label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
							<input type="file" id="pluginzip" name="pluginzip" />
							<?php submit_button('Install Now' , 'button', 'install-plugin-submit', false ); ?>
						</form>
					</td>
				</tr>
			    <tr>
			    	<td>
			    		<p><?php _e('You can download the latest version of '); ?><i><?php _e('cometchat.zip');?></i><?php _e(' from ');?><a href="http://www.cometchat.com" target="_blank">our site</a><?php _e('. You will need to purchase a CometChat license if you haven\'t already. Feel free to email us at '); ?><a href="mailto:sales@cometchat.com" target="_blank">sales@cometchat.com</a><?php _e(' if you have any questions.'); ?>
			    		</p>
			    	</td>
			    	<td>
			    		<input type="hidden" name="upload" value="1" />
			    	</td>
			    </tr>
			</table>
			<?php
			}
		}
	}

	function fileUpload(){
		if(isset($_FILES['pluginzip'])) {
			WP_Filesystem();
	        $filename = $_FILES['pluginzip']['name'];
	        $source = $_FILES['pluginzip']['tmp_name'];
	        $type = $_FILES['pluginzip']['type'];
	        $name = explode('.', $filename);
	        $target = ABSPATH;
	        $install = get_site_url().'/cometchat/install.php';

	       	if($_FILES['pluginzip']['error'] == '0'){
	        	if($name[0] == 'cometchat'){
	        	unzip_file($source,$target);
	        	$hash = md5(time().$_SERVER['SERVER_NAME']);
	    	    $comet = <<<EOD
    			<iframe src="$install" style="width:1px;height:1px;border:0;"></iframe>
EOD;
					$license = ABSPATH.'/cometchat/license.php';
					if(file_exists($license)){
						echo $comet;
						global $wpdb;
						$table_name = $wpdb->prefix . 'options';
						$wpdb->insert(
							$table_name,
							array(
								'option_name' => 'hash_value',
								'option_value' => $hash
								),
							array(
								'%s',
								'%s'
								)
							);
					}else{
						  ?><div class="error"><p><?php _e('Oops! looks like we don\'t have necessary file permissions to upload \'cometchat\' folder into your wordpress directory. Please unzip the \'cometchat.zip\' that has been provided in the package. Now place the \'cometchat\' folder in your wordpress root directory and execute \'cometchat/install.php\' from \'http://&lt;PATH_TO_WORDPRESS&gt;/cometchat/install.php\' file.'); ?></p></div>
					<?php
					}
		        }
		        else{
		            ?>
		            <div class="error"><p><?php _e('Please select the file named cometchat.zip'); ?></p></div>
		            <?php
		            return false;
		        }
	        } else{
	        	if($_FILES['pluginzip']['error'] == '1'){
		        	?>
		        	<div class="error"><p><?php _e('The uploaded file exceeds the upload_max_filesize directive in php.ini. Please ask your website administrator to set a higher value.'); ?></p></div>
			        <?php
		    	}else{
			    	?>
		        	<div class="error"><p><?php _e('File Upload error. Error Code:'.$_FILES['pluginzip']['error']); ?></p></div>
			        <?php
		    	}
		        return false;
	        }
	    }
	    return true;
    }

?>