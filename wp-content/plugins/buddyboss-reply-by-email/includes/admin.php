<?php
/**
 * @package WordPress
 * @subpackage BuddyBoss RBE
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'BuddyBoss_RBE_Admin' ) ):

	/**
	 *
	 * BuddyBoss RBE Admin
	 * ********************
	 *
	 *
	 */
	class BuddyBoss_RBE_Admin {
		/* Options/Load
		 * ===================================================================
		 */

		/**
		 * Plugin options
		 *
		 * @var array
		 */
		public	$options = array();
		private $network_activated 		= false,
				$plugin_slug 		= 'bb-buddyboss-rbe',
				$menu_hook		= 'admin_menu',
				$settings_page 		= 'buddyboss-settings',
				$capability 		= 'manage_options',
				$messages		= array();

		/** 
		 * Empty constructor function to ensure a single instance
		 */
		public function __construct() {
			// ... leave empty, see Singleton below
		}

		/* Singleton
		 * ===================================================================
		 */

		/**
		 * Admin singleton
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @param  array  $options [description]
		 *
		 * @uses BuddyBoss_RBE_Admin::setup() Init admin class
		 *
		 * @return object Admin class
		 */
		public static function instance() {
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new BuddyBoss_RBE_Admin;
				$instance->setup();
			}

			return $instance;
		}

		/* Utility functions
		 * ===================================================================
		 */

		/**
		 * Get option
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @param  string $key Option key
		 *
		 * @uses BuddyBoss_RBE_Plugin::option() Get option
		 *
		 * @return mixed      Option value
		 */
		public function option( $key ) {
			$value = buddyboss_rbe()->option( $key );
			return $value;
		}

		/* Actions/Init
		 * ===================================================================
		 */

		/**
		 * Setup admin class
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @uses buddyboss_rbe() Get options from main BuddyBoss_RBE_Plugin class
		 * @uses is_admin() Ensures we're in the admin area
		 * @uses curent_user_can() Checks for permissions
		 * @uses add_action() Add hooks
		 */
		public function setup() {
			if ( ( ! is_admin() && ! is_network_admin() ) || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$this->plugin_settings_url = admin_url( 'admin.php?page=' . $this->plugin_slug );

			$this->network_activated = $this->is_network_activated();
			
			$this->messages = array(
						1	=>	'<div class="message error"><p>'.__("Server port can only contain numbers.","bb-reply-by-email").'</p></div>',
						2	=>	'<div class="message error"><p>'.__("Please select the valid server protocol.","bb-reply-by-email").'</p></div>',
						3	=>	'<div class="message updated"><p>'.__("Setting saved.","bb-reply-by-email").'</p></div>',
						4	=>	'<div class="message error"><p>'.__("Entered email is not valid.","bb-reply-by-email").'</p></div>',
						5	=>	'<div class="message error"><p>'.__("Please enter a valid email address.","bb-reply-by-email").'</p></div>',
						6	=>	'<div class="message updated"><p>'.__("Test email has been sent.","bb-reply-by-email").'</p></div>',
						7	=>	'<div class="message error"><p>'.__("Entered domain is not configured with SendGrid.","bb-reply-by-email").'</p></div>',
			);

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( $this->menu_hook, array( $this, 'admin_menu' ) );
			
			$plugin = plugin_basename( BUDDYBOSS_RBE_PLUGIN_FILE );
			add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ) );

		}

		/**
		 * Check if the plugin is activated network wide(in multisite).
		 * 
		 * @return boolean
		 */
		private function is_network_activated() {
			$network_activated = false;
			if ( is_multisite() ) {
				if ( ! function_exists( 'is_plugin_active_for_network' ) )
					require_once( ARBEATH . '/wp-admin/includes/plugin.php' );

				if ( is_plugin_active_for_network( basename( constant( 'BUDDYBOSS_RBE_PLUGIN_DIR' ) ) . '/buddyboss-rbe.php' ) ) {
					$network_activated = true;
				}
			}
			return $network_activated;
		}

		/**
		 * Register admin settings
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @uses register_setting() Register plugin options
		 * @uses add_settings_section() Add settings page option sections
		 * @uses add_settings_field() Add settings page option
		 */
		public function admin_init() {
			if(!current_user_can( 'manage_options' )) {
				return false;	
			}
			
			if(@$_GET["page"] != $this->plugin_slug) {
				return false;
			}
			
			$this->save_settings();
			
		}

		/**
		 * Add plugin settings page
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @uses add_options_page() Add plugin settings page
		 */
		public function admin_menu() {
			add_submenu_page(
					$this->settings_page, __('BuddyBoss Reply by Email','bb-reply-by-email'), __('Reply by Email','bb-reply-by-email'), $this->capability, $this->plugin_slug, array( $this, 'options_page' )
			);
		}
		
		// Add settings link on plugin page
        function plugin_settings_link($links) {
            $links[] = '<a href="'.admin_url("admin.php?page=bb-buddyboss-rbe").'">'.__("Settings","bb-reply-by-email").'</a>';
            return $links;
        }
		
		/* Settings Page + Sections 
		 * ===================================================================
		 */

		/**
		 * Render settings page
		 *
		 * @since BuddyBoss RBE (1.0.0)
		 *
		 * @uses do_settings_sections() Render settings sections
		 * @uses settings_fields() Render settings fields
		 * @uses esc_attr_e() Escape and localize text
		 */
		public function options_page() {
			
			$_GET["view"] = (isset($_GET["view"]))?$_GET["view"]:"";			
			
			?>
			<div class="wrap">
				<h2><?php _e( 'BuddyBoss Reply by Email', 'bb-reply-by-email' ); ?></h2>
				
				<h2 class="nav-tab-wrapper">
					<a id="nav-main" href="<?php echo $this->plugin_settings_url; ?>" class="nav-tab <?php echo ($_GET["view"]=="")?"nav-tab-active":""; ?>">
					<?php _e("Components","bb-reply-by-email"); ?>
					</a>
					
					<a id="nav-main" href="<?php echo $this->plugin_settings_url."&view=server"; ?>" class="nav-tab <?php echo ($_GET["view"]=="server")?"nav-tab-active":""; ?>">
					<?php _e("Email Setup","bb-reply-by-email"); ?>
					</a>
					
					<a id="nav-main" href="<?php echo $this->plugin_settings_url."&view=test"; ?>" class="nav-tab <?php echo ($_GET["view"]=="test")?"nav-tab-active":""; ?>">
					<?php _e("Send Test","bb-reply-by-email"); ?>
					</a>
			
					<a id="nav-main" href="<?php echo $this->plugin_settings_url."&view=logs"; ?>" class="nav-tab <?php echo ($_GET["view"]=="logs")?"nav-tab-active":""; ?>">
					<?php _e("Logs","bb-reply-by-email"); ?>
					</a>
					<a id="nav-main" href="<?php echo $this->plugin_settings_url."&view=support"; ?>" class="nav-tab <?php echo ($_GET["view"]=="support")?"nav-tab-active":""; ?>">
					<?php _e("Support","bb-reply-by-email"); ?>
					</a>
				</h2>

				<br />
				
				<?php
				
				if(isset($_GET["msg"]) AND $this->messages[$_GET["msg"]]) {
					echo $this->messages[$_GET["msg"]];
				}
				
				?>
				
				
				<?php
				
				if($_GET["view"] == "") {
					$this->general_settings_tab();
				}
				if($_GET["view"] == "server") {
					$this->server_settings_tab();
				}
				if($_GET["view"] == "logs") {
					$this->server_logs_tab();
				}
				if($_GET["view"] == "test") {
					$this->server_test_tab();
				}
				if($_GET["view"] == "support") {
					$this->support_tab();
				}
				
				?>
			</div>

			<?php
		}
		
		function general_settings_tab() {
			$settings = get_option("buddyboss_rbe_plugin_options");
			?>
			<form action="<?php echo $this->plugin_settings_url; ?>" method="post">
				<?php
						wp_nonce_field("buddyboss_rbe_admin_general_nonce","admin_general_setting_nonce");
					?>
					
					<table class="form-table">
					<tbody>
						
						<?php
							do_action("bbrbe_screen_settings",$settings);
						?>
					</tbody>
					</table>
					
					<p class="submit">
						<input name="bboss_g_s_settings_submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes',"bb-reply-by-email" ); ?>" />
					</p>
			
			</form>
			<?php
		}
		
		function server_test_tab() {
			$testmail = get_option("buddyboss_rbe_test_email");
			$replied_message = get_option("buddyboss_rbe_test_replied_message");
			$check_reply = get_option("buddyboss_rbe_test_check_reply_step");
			
			$count_max = 10;
			
			if($this->option("mail_service") == "personal") {
				$count_max = buddyboss_rbe()->option("server_update_interval");
			}
			
			if(!buddyboss_rbe()->core->is_mail_server_configured()):
			?>
			<p class="description"><?php _e('You need to first Email Setup to test reply by email.','bb-reply-by-email'); ?></p>
			<?php
			return;
			endif;
			
			?>
			<form action="<?php echo $this->plugin_settings_url; ?>" method="post">
				<?php
						wp_nonce_field("buddyboss_rbe_admin_test_nonce","admin_test_nonce");
					?>
					
					<?php if(empty($testmail)): ?>
						<input type="hidden" name="action" value="email" />
					
						<table class="form-table">
							
						<tbody>
							
							<tr>
								<th scope="row"><label for="test_email"><?php _e("Email Address","bb-reply-by-email"); ?></label></th>
								<td>
									<input name="test_email" type="text" id="test_email" value="<?php echo $this->option("test_email"); ?>" class="regular-text"> <input name="bboss_g_s_settings_submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Send Test' ,"bb-reply-by-email"); ?>" />
									<p class="description"><?php _e('Enter any email address you have access to, and then click "Send Test".<br /> Then log into that email and reply to the test message. If it works your reply will display on this page after 5-10 seconds.','bb-reply-by-email'); ?></p>
								</td>
							</tr>
							
						</tbody>
						</table>
						
					<?php endif; ?>
					
					<?php if(!empty($testmail)): ?>
					
						<?php if(empty($check_reply)): ?>
						
						<input type="hidden" name="action" value="viewreply" />
						
						<p>
							<?php _e('Please check your inbox and reply to the email. Then click the "Read Reply" button below to confirm.','bb-reply-by-email'); ?>
						</p>
						
						<p class="submit">
						<input name="bboss_g_s_settings_submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Read Reply' ,"bb-reply-by-email"); ?>" />
						</p>
												
						<?php else: ?>
					
						<input type="hidden" name="action" value="check" />
							
						<table class="form-table">
						<tbody>
							
							<tr>
								<th scope="row"><label for="server_host"><?php _e("Replied Message","bb-reply-by-email"); ?></label></th>
								<td>
									<?php
										if(!empty($replied_message)) {
											echo "<p>{$replied_message}</p>";
										} else {
											echo '<p id="testcounter">'.__("Please wait for <span class='count'></span> seconds.","bb-reply-by-email").'</p>';
										}
									?>
								</td>
							</tr>
							
						</tbody>
						</table>
						
						<a href="<?php echo $this->plugin_settings_url."&view=test"; ?>" class="recheckbtn button-primary"><?php echo __("Re-Check","bb-reply-by-email"); ?></a>
						
						<a href="<?php echo $this->plugin_settings_url."&view=test&new=1"; ?>" class="button-primary"><?php echo __("New Test","bb-reply-by-email"); ?></a>
					
					
						<script>
								
							var countmax = <?php echo $count_max; ?>;
							
							
							jQuery(document).ready(function() {
								
								setInterval(function(){
									if (countmax == 0) {
										return false;
									}
									jQuery("#testcounter .count").text(countmax);
									countmax--;
									if (countmax < 1) {
										window.location = "<?php echo $this->plugin_settings_url."&view=test"; ?>";
										jQuery(".recheckbtn").click();
									}
								},1000);
								
							
									
							});
							
						</script>
						
					
						<?php endif; ?>
					<?php endif; ?>
					
					
			
			</form>
			
			
			
			<?php
		}
		
		function server_settings_tab() {
			
			$mail_service =  $this->option("mail_service");
			if(empty($mail_service)) { $mail_service = "cloudmailin"; }
			
			?>
			
			<form action="<?php echo $this->plugin_settings_url; ?>" method="post"  id="settingform">
					
					<table class="form-table">
					<tbody>
					
					<?php
						wp_nonce_field("buddyboss_rbe_mail_setting_nonce","mail_setting_nonce");
					?>	
					<tr>
								<th scope="row"><label for="mail_service"><?php _e("Mail Service","bb-reply-by-email"); ?></label></th>
								<td>
									<select name="mail_service" id="mail_service">
										<option value="none"><?php _e("Disabled","bb-reply-by-email"); ?></option>
										<?php foreach(buddyboss_rbe()->mail_services as $key => $val): ?>
											<option <?php selected($key,$mail_service); ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
										<?php endforeach; ?>
									</select>
									<p class="description"><?php _e('Select the mail service you want to use for inbound emails.','bb-reply-by-email'); ?></p>
								</td>
						</tr>
					
					
					</tbody>
					</table>
					
					<hr style="border: 0px;border-top: 4px solid rgba(0, 0, 0, 0.07);" />
					
					<?php
					
					if(buddyboss_rbe()->option("mail_service") == "cloudmailin") {
						$this->cloudmailin_settings();
					}
					if(buddyboss_rbe()->option("mail_service") == "sendgrid") {
						$this->sendgrid_settings();
					}
					if(buddyboss_rbe()->option("mail_service") == "none") {
						?>
						<center><p class="description"><?php _e('Reply by Email will not work until a Mail Service is selected and configured.','bb-reply-by-email'); ?></p></center>
						<?php
					}
					
					?>
					
					
					<p class="submit">
						<input name="bboss_g_s_settings_submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', "bb-reply-by-email" ); ?>" />
					</p>
			</form>
			
			<?php
			
		}
		
	
				
		function sendgrid_settings() {
			?>
			
			<?php
				wp_nonce_field("buddyboss_rbe_mail_setting_nonce","mail_setting_nonce");
			?>
			
			<h2><?php _e("SendGrid Setup","bb-reply-by-email"); ?></h2>
			<p><?php _e('We integrate with <a href="https://sendgrid.com/" target="_blank">SendGrid</a>, a scalable service for incoming email. Sendgrid is more complex to configure than Cloudmailin, but is also more affordable at higher volumes. Please open a <a href="https://sendgrid.com/pricing.html" target="_blank"> free plan</a> and <a href="https://app.sendgrid.com/settings/whitelabel/domains" target="_blank">whitelabel</a> your domain, and then return here when finished.','bb-reply-by-email'); ?> <em><?php echo sprintf(__('Go to the <a href="%s">Support tab</a> for video tutorials.','bb-reply-by-email'), $this->plugin_settings_url."&view=support" ); ?></em></p>

			<!-- Step 1 -->
				
			<div class="r3rdparty_incoming_url sendgrid">		
				<h2><?php _e("Step 1: Domain Whitelabel","bb-reply-by-email"); ?></h2>
				<div class="r3rd_endpoint">
					<label><?php _e("Domain added at SendGrid","bb-reply-by-email"); ?></label>
					<input name="sendgrid_configured_domain" type="text" id="sendgrid_configured_domain" class="r3rdparty_incoming_url" value="<?php echo $this->option("sendgrid_configured_domain"); ?>" class="regular-text" placeholder="<?php _e('eg. reply.domain.com','bb-reply-by-email'); ?>">
					<p class="description"><?php echo sprintf(__('Enter the domain or subdomain which you <a href="%s" target="_blank">whitelabeled</a> in your SendGrid account, at Settings &gt; Whitelabels &gt; Domains.','bb-reply-by-email'),"https://app.sendgrid.com/settings/whitelabel/domains"); ?></p>
				</div>

				<?php $v = $this->option("sendgrid_configured_domain"); if(!empty($v)): ?>
					<div class="r3rd_endpoint">
						<label><?php _e("Email Reply","bb-reply-by-email"); ?></label>
						<br />
						<input name="sendgrid_email_alias" type="text" id="sendgrid_email_alias" value="<?php echo $this->option("sendgrid_email_alias"); ?>" class="regular-text"><span class="sendgrid_email_alias">@<?php echo $this->option("sendgrid_configured_domain"); ?></span>
						<p class="description"><?php echo sprintf(__('Enter an email name to be used for all inbound replies. Default value is "reply".','bb-reply-by-email')); ?></p>
					</div>
				<?php endif; ?>	
			</div>

			<!-- Step 2 -->
			
			<?php $v = $this->option("sendgrid_configured_domain"); if(!empty($v)): ?>
				<div class="r3rdparty_incoming_url sendgrid">
					<h2><?php _e("Step 2: Inbound Parse","bb-reply-by-email"); ?></h2>
					<p class="description"><?php _e('Once your domain is verified as whitelabeled at SendGrid, you will need to add the following <b>HOSTNAME &amp; URL</b> values to the SendGrid <a href="https://app.sendgrid.com/settings/parse" target="_blank">Inbound Parse</a> settings, at Settings &gt; Inbound Parse.', 'bb-reply-by-email'); ?></p>
					<div class="r3rd_endpoint">
						<label><?php _e("HOSTNAME","bb-reply-by-email"); ?></label>
						<input type="text" readonly name="incoming_url" class="r3rdparty_incoming_url selectall" value="<?php echo buddyboss_rbe()->option("sendgrid_configured_domain"); ?>" />
					</div>
					<div class="r3rd_endpoint">
						<label><?php _e("URL","bb-reply-by-email"); ?></label>
						<input type="text" readonly name="incoming_url" class="r3rdparty_incoming_url selectall" value="<?php echo admin_url('admin-ajax.php'); ?>?action=sendgrid_incoming_service" />
					</div>
					
					<div class="r3rd_endpoint">
						<p class="description">
							<?php _e("Leave <b>SPAM CHECK</b> and <b>SEND RAW</b> as un-checked.","bb-reply-by-email"); ?>
						</p>			
					</div>
				</div>
				
				<script>
					jQuery(document).ready(function(){
							jQuery("input.selectall").click(function(){
								jQuery(this).select();	
							});
					});
				</script>
				
			<?php endif; ?>
		
			<?php
			
		}
		
		function cloudmailin_settings() {
			
			?>
			
			<?php
				wp_nonce_field("buddyboss_rbe_mail_setting_nonce","mail_setting_nonce");
			?>
			
			<h2><?php _e("CloudMailin Setup","bb-reply-by-email"); ?></h2>
			<p><?php _e('We integrate with <a href="https://www.cloudmailin.com/"" target="_blank">CloudMailin</a>, an easy to configure service for incoming email. Please <a href="https://www.cloudmailin.com/user/sign_up" target="_blank">create a free account</a> using the settings below, and then return here once you have an email assigned.','bb-reply-by-email'); ?></p>
				<table class="form-table">
					
				<tbody>
					
					<tr>
						<th scope="row"><label for="incoming_email"><?php _e("Incoming Email Address","bb-reply-by-email"); ?></label></th>
						<td>
							<input name="cloudmailin_incoming_email" type="text" id="cloudmailin_incoming_email" value="<?php echo $this->option("cloudmailin_incoming_email"); ?>" class="regular-text">
							
							
							<p class="description"><?php _e('Enter the email address given to you during CloudMailin signup.','bb-reply-by-email'); ?></p>
						</td>
					</tr>
					
				</tbody>
				</table>
				
			<div class="r3rdparty_incoming_url">
				<h2><?php _e("Settings for CloudMailin","bb-reply-by-email"); ?></h2>
				<p><?php _e('Enter the below settings at CloudMailin when it asks <em>"Where shall we send your email?"</em>', 'bb-reply-by-email'); ?></p>
				<div class="r3rd_endpoint">
					<label><?php _e("URL of your server (HTTP Endpoint)","bb-reply-by-email"); ?></label>
					<input type="text" readonly name="incoming_url" class="r3rdparty_incoming_url" value="<?php echo admin_url('admin-ajax.php'); ?>?action=cloudmailin_incoming_service" />
				</div>
				<div class="postformat">
					<label><?php _e("POST Format","bb-reply-by-email"); ?></label>
					<p class="postformat"><?php _e("Multipart (recommended)","bb-reply-by-email"); ?></p>
				</div>
			</div>
			
			<script>
				jQuery(document).ready(function(){
						jQuery("input.r3rdparty_incoming_url").click(function(){
							jQuery(this).select();	
						});
				});
			</script>
			
			<?php
			
		}
		
		/*
		 * Shows the logs
		 *
		 **/
		function server_logs_tab() {
			
			$logs = get_option("buddyboss_rbe_logs");
			@krsort($logs,SORT_NUMERIC);
			
			$settings = get_option("buddyboss_rbe_plugin_options");
			$settings["Error_Raised"] = "0";
			update_option("buddyboss_rbe_plugin_options",$settings);
			
			
			$log_limit = 200;
			$i = 0;
			$new_log = array(); 
			foreach($logs as $key => $val) {
				$new_log[$key] = $val;
				if($i  == $log_limit) { break; }
				$i++;
			}			
			update_option("buddyboss_rbe_logs",$new_log);
			?>
			
			<?php if(empty($new_log)) {  ?>
				<div class="message error"><p><?php _e("No logs found.","bb-reply-by-email"); ?></p></div>
			<?php } else { ?>
			
			<br />		
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
				<tr>
					<th scope="col" class="manage-column"><?php _e("Datetime","bb-reply-by-email"); ?></th>
					<th scope="col" class="manage-column"><?php _e("Message","bb-reply-by-email"); ?></th>
				</tr>
				
				<?php foreach($new_log as $t => $m) { ?>
					<tr>
						<th scope="col" class="manage-column"><?php echo date("r",$t); ?></th>
						<th scope="col" class="manage-column"><?php echo $m; ?></th>
					</tr>
				<?php } ?>
			</table>
			
			<?php } ?>			
			<?php
			
		}
		
		function support_tab() {
			if ( file_exists( dirname( __FILE__ ) . '/help-support.php' ) ) {
				require_once( dirname( __FILE__ ) . '/help-support.php' );
			}
		}
		
		/*
		 * Saving the admin setting 
		 **/
		function save_settings() {
			
			
			if(@$_GET["view"] == "test" AND @$_GET["new"] == "1") {
				update_option("buddyboss_rbe_test_email","");
				update_option("buddyboss_rbe_test_replied_message","");
				update_option("buddyboss_rbe_test_check_reply_step","");
				wp_redirect($this->plugin_settings_url."&view=test");
				exit;
			}
			
			/*
			 * CloudMailin Settings.
			 **/
			
			if(isset($_POST["mail_setting_nonce"]) AND wp_verify_nonce($_POST["mail_setting_nonce"],"buddyboss_rbe_mail_setting_nonce")) {
				
				$settings = get_option("buddyboss_rbe_plugin_options");
			
			
				if($settings["mail_service"] != $_POST["mail_service"]){
					$settings["mail_service"] = $_POST["mail_service"];
					update_option("buddyboss_rbe_plugin_options",$settings);
					wp_redirect($this->plugin_settings_url."&msg=3&view=server");
					exit;
				}
			
				/**
				 * Cloudmailin Settings Update
				 **/
				if(buddyboss_rbe()->option("mail_service") == "cloudmailin") {
					
					if(!filter_var($_POST["cloudmailin_incoming_email"], FILTER_VALIDATE_EMAIL)) {
						wp_redirect($this->plugin_settings_url."&msg=5&view=server");
						exit;
					} else {
						$settings["cloudmailin_incoming_email"] = $_POST["cloudmailin_incoming_email"];
					}
					
				}
				
				
				/**
				 * SendGrid Settings Update
				 **/
				if(buddyboss_rbe()->option("mail_service") == "sendgrid") {
					
					$domain = $_POST["sendgrid_configured_domain"];
					$domain = str_replace(array("http://","https://","www."),"",$domain);
					$domain = explode("/",$domain);
					$domain = $domain[0];
				
					// Verify Domain if configured correctly.
					$result = dns_get_record($domain,DNS_CNAME);
					$found = false;
					foreach($result as $result) {
						if(strpos($result["target"],"sendgrid") !== false) {
							$found = true;
						}
					}
					
					if(!$found) {
						wp_redirect($this->plugin_settings_url."&msg=7&view=server");
						$settings["sendgrid_configured_domain"] = "";
						update_option("buddyboss_rbe_plugin_options",$settings);
						exit;
					}
					
					$settings["sendgrid_configured_domain"] = $domain;
					
				}
				
								
				update_option("buddyboss_rbe_plugin_options",$settings);
				
				wp_redirect($this->plugin_settings_url."&msg=3&view=server");
				exit;
			
			}
			
			if(isset($_POST["admin_general_setting_nonce"]) AND wp_verify_nonce($_POST["admin_general_setting_nonce"],"buddyboss_rbe_admin_general_nonce")) {
				
				$settings = get_option("buddyboss_rbe_plugin_options");
								
				$settings = apply_filters("bbrbe_general_settings_before_save",$settings);
				
				update_option("buddyboss_rbe_plugin_options",$settings);
				
				wp_redirect($this->plugin_settings_url."&msg=3");
				exit;
			
			}
			
			if(isset($_POST["admin_test_nonce"]) AND wp_verify_nonce($_POST["admin_test_nonce"],"buddyboss_rbe_admin_test_nonce")) {
				
				if($_POST["action"] == "viewreply") {
					update_option("buddyboss_rbe_test_check_reply_step","1");
					wp_redirect($this->plugin_settings_url."&view=test");
					exit;
				}
				
				if($_POST["action"] == "email") {
					if(!is_email($_POST["test_email"])) {
						wp_redirect($this->plugin_settings_url."&msg=5&view=test");
						exit;
					}
					
					$md5  = md5($_POST["test_email"]);
					$md5 = substr($md5,10,10); //short to save space.
					buddyboss_rbe()->core->register_unique_identity("test", array(
						$md5
		                        ));
					
					wp_mail( $_POST["test_email"], __("BuddyBoss Reply by Email Test","bb-reply-by-email"), __('Send a reply to this email. Then return to the Reply by Email "Send Test" tab to confirm it worked.','bb-reply-by-email') );
					
					buddyboss_rbe()->core->unregister_unique_identity();
					
					update_option("buddyboss_rbe_test_email",$_POST["test_email"]);
					
					wp_redirect($this->plugin_settings_url."&msg=6&view=test");
					exit;
					
				}
				
				
			}
		}
		
	} //End of BuddyBoss_RBE_Admin class
	

endif;

