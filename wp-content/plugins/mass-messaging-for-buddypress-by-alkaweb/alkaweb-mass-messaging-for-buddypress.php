<?php

/**
 * Plugin Name: Mass Messaging for Buddypress - by Alkaweb
 * Description: Allows to send mass messages to all members, all members of specific groups, all memebrs with specific roles.
 * Version: 1.1.2
 * Author: Alkaweb
 * Author URI: https://woffice.io/
 * License: GPL2 or later
 * Text Domain: alkaweb-bmm
 * Domain Path: /languages
 *
 * @author Alkaweb
 * @author Webbaku
 * @package Mass Messaging for BuddyPress - by Alkaweb
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) )
	die('Forbidden');

class BuddypressMassMessagingByAlkaweb {

	/**
	 * The only instance of the class.
	 *
	 * @var BuddypressMassMessagingByAlkaweb
	 */
	private static $instance = null;


	private function __construct()
	{
		//I18n
		add_action( 'admin_init', array($this, 'load_textdomain') );
		add_action( 'init', array($this, 'load_textdomain') );

		//Styles and Javascript Files
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );

		//Backend pages and actions
		add_action( 'admin_menu', array($this, 'render_menu') );
		add_action('admin_init', array($this, 'handle_backend_messages_sending') );

		//Frontend sections and functions
		add_action('bp_after_messages_compose_content', array($this, 'add_frontend_fields') );
		add_filter('bp_messages_recipients', array($this, 'handle_frontend_messages_sending'));
	}

	/**
	 * Returns the main instance, saved statically
	 *
	 * @return BuddypressMassMessagingByAlkaweb
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	public function load_textdomain() {

		//var_dump('I am triggered');
		//die();

		load_plugin_textdomain( 'alkaweb-bmm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	public function enqueue_admin_scripts() {

		//basic css plugin
		wp_enqueue_style( 'alkaweb_bmm_style', plugins_url('/css/style.css', __FILE__) );

	}

	public function render_menu() {
		//add an item to the menu
		add_menu_page(
			'Mass Messaging for BuddyPress',
			'Mass Messaging for BuddyPress',
			'administrator',
			'alkaweb-buddypress-mass-messaging',
			array($this, 'render_compose_page'),
			'dashicons-email-alt'
		);

		/*add_submenu_page(
			'alkaweb-buddypress-mass-messaging',
			__('Settings', 'alkaweb-bmm'),
			__('Settings', 'alkaweb-bmm'),
			'administrator',
			'alkaweb-buddypress-mass-messaging-setings',
			'alkaweb_bmm_render_settings_page'

		);*/
	}

	/**
	 * Render the options page
	 */
	public function render_compose_page() {
		?>

		<?php if( bp_is_active ( 'messages' ) ) : ?>
			<div class="wrap">
				<form action="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=alkaweb-buddypress-mass-messaging" method="post" id="alkaweb-bbp-mass-messaging">
					<h2>BuddyPress Mass Messaging - by Alkaweb</h2>
					<table class="form-table alkaweb-bmm-table">
						<tbody>
						<tr><th><?php esc_html_e('Subject', 'alkaweb-bmm'); ?></th><td colspan="3"><input name="alkaweb_bmm_subject" type="text" value="" size="45" /></td></tr>
						<tr><th><?php esc_html_e('Content', 'alkaweb-bmm'); ?></th><td colspan="3"><textarea name="alkaweb_bmm_content" cols="45" rows="10"></textarea></tr>
						<tr>
							<th><?php esc_html_e('Receivers', 'alkaweb-bmm'); ?></th>
							<td class="alkaweb-bmm-receivers">
								<h4><?php esc_html_e('All', 'alkaweb-bmm'); ?></h4>
								<p><label><input type="checkbox" name="alkaweb_bmm_receiver_all" value="all"> <?php esc_html_e('All', 'alkaweb-bmm'); ?></label></p>
							</td>
							<td class="alkaweb-bmm-receivers">
								<h4><?php esc_html_e('Groups', 'alkaweb-bmm'); ?></h4>
								<?php
								if(bp_is_active('groups')) {
									$groups = BP_Groups_Group::get(array(
										'type'=>'alphabetical',
										'per_page'=>999
									));
									foreach($groups['groups'] as $group) {
										echo '<p><label><input type="checkbox" name="alkaweb_bmm_receiver_groups[]" value="'.$group->id.'">'.$group->name.'</label></p>';
									}
								} else {
									esc_html_e("Groups component is not active.", 'alkaweb-bmm');
								}
								?>
							</td>
							<td class="alkaweb-bmm-receivers">
								<h4><?php esc_html_e('Roles', 'alkaweb-bmm'); ?></h4>
								<?php
								foreach (get_editable_roles() as $role_name => $role_info){

									echo '<p><label><input type="checkbox" name="alkaweb_bmm_receiver_roles[]" value="'.$role_name.'">'.$role_info['name'].'</label></p>';
								}
								?>
							</td>
						</tr>
						</tbody>
					</table>
					<br />


					<?php wp_nonce_field( 'alkaweb_bmm' ); ?>

					<input name="alkaweb_bmm_submit" type="submit" class="button button-primary" value="<?php esc_html_e('Send Messages', 'alkaweb-bmm'); ?>"/></form>

				<!-- TODO include this in a private method -->
				<div class="alkaweb-bmm-signature">
					<hr>
					<?php
					$theme = wp_get_theme(); // gets the current theme
					if ('woffice' != $theme->__get('stylesheet') && 'woffice' != $theme->__get('stylesheet')): ?>
						<p><a href="http://bit.ly/2eQh7QQ" target="_blank"><img style="border: 1px solid #d0d0d0" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/add-728x90.jpg'; ?>"></a></p>
					<?php endif; ?>

					<p><?php echo sprintf(__('Did you find this plugin useful? Please let us know <a href="%s" target="_blank">here</a>.', 'alkaweb-bmm'), 'https://wordpress.org/support/plugin/mass-messaging-for-buddypress-by-alkaweb/reviews/')?></p>
				</div>

			</div>
		<?php else: ?>

			<div id="message" class="error fade below-h2"><p><?php printf( __('Please activate the <a href="%s">BuddyPress private messaging component</a> in order to use this plugin', 'alkaweb-bmm'), admin_url('admin.php?page=bp-component-setup') ); ?></p></div>

		<?php endif; ?>

		<?php
	}

	/**
	 * Send the messages composed by backend
	 */
	public function handle_backend_messages_sending() {

		if(
			isset($_GET['page'])
			&& $_GET['page'] == 'alkaweb-buddypress-mass-messaging'
			&& isset($_POST['alkaweb_bmm_submit'])
		) {
			check_admin_referer( 'alkaweb_bmm' );

			$subject = ( isset( $_POST['alkaweb_bmm_subject'] ) ) ? sanitize_text_field($_POST['alkaweb_bmm_subject']) : '';
			$content = ( isset( $_POST['alkaweb_bmm_content'] ) ) ? sanitize_text_field($_POST['alkaweb_bmm_content']) : '';

			if(empty($subject) || empty($content)) {
				echo "<script type='text/javascript'> alert('" . esc_html__( 'Please fill Subject and Content fields', 'alkaweb-bmm' ) . "')</script>";
				return;
			}

			$all    = ( isset( $_POST['alkaweb_bmm_receiver_all'] ) && $_POST['alkaweb_bmm_receiver_all'] == 'all' );
			$groups = ( isset( $_POST['alkaweb_bmm_receiver_groups'] ) ) ? $_POST['alkaweb_bmm_receiver_groups'] : array();
			$roles  = ( isset( $_POST['alkaweb_bmm_receiver_roles'] ) ) ? $_POST['alkaweb_bmm_receiver_roles'] : array();

			if($all) {
				//If the message have to be sent to all members
				$users = get_users();

				$messages_sent = $this->send_message($users, $subject, $content);

			} else {
				$receivers = array();

				//Get receivers from groups
				foreach($groups as $group) {
					$group_members = groups_get_group_members(array('group_id' => $group, 'exclude_admins_mods'=> 0));

					if(array_key_exists('members', $group_members)) {
						foreach ( $group_members['members'] as $member ) {
							if ( ! in_array( $member, $receivers ) ) {
								array_push( $receivers, $member );
							}
						}
					}
				}

				//Get receivers from roles
				$all_users = get_users();

				foreach($all_users as $user) {
					$the_user_role = (array) $user->roles;
					$role_intersect = array_intersect( $the_user_role, $roles );

					if($role_intersect && !in_array($user, $receivers) )
						array_push($receivers, $user);

				}


				$messages_sent = $this->send_message($receivers, $subject, $content);

			}

			//Internationalizing
			if($messages_sent >= 1){
				echo "<script type='text/javascript'> alert('". sprintf(_n( '%s message sent succesfully', '%s messages sent succesfully', $messages_sent, 'alkaweb-bmm' ), $messages_sent)."')</script>";
			} else {
				echo "<script type='text/javascript'> alert('".esc_html__('0 messages sent, maybe you just selected empty roles or groups. If you are sure this is not your case, please contact our support.', 'alkaweb-bmm')."')</script>";
			}
		}

	}

	/**
	 * Add additional fields in the Compose view
	 */
	public function add_frontend_fields() {

		//If the user has the right permissions
		if(!current_user_can('manage_options'))
			return;

		echo '<p><label for="send-global"><input type="checkbox" id="send-global" name="send-global" value="1" /> '. __( "Send as a private message to all users.", 'alkaweb-bmm' ) . '</label></p>';
	}

	public function handle_frontend_messages_sending($recipients) {

		//If the user has the right permissions
		if(!current_user_can('manage_options'))
			return $recipients;

		//If the message has to be sent to all users
		if( !array_key_exists('send-global', $_POST) || $_POST['send-global'] != 1)
			return $recipients;


		$users = get_users();
		$first_user = null;

		//At moment, there is no way to send a message using buddypress standard process, withouth recivient and at the
		// same time without throw errors, so we send a standard message start to the first user of the list and send
		// manually all other messages
		if(count($users) > 1) {
			$first_user = array_shift($users);

			//Avoid to send a message to himself
			if($first_user->ID == get_current_user_id())
				$first_user = array_shift($users);


			$this->send_message($users, $_POST['subject'], $_POST['content']);

		}

		$recipients = $first_user->ID;

		//Return a single user as recipient, in order to send a standard message and don't throws errors
		return $recipients;
	}

	/**
	 * Send the messages to all receivers passed by parameter and returnthe number of messages sent
	 *
	 * @param $receivers
	 *
	 * @return int
	 */
	private function send_message($receivers, $subject, $content) {
		$c = 0;


		if($receivers instanceof WP_User)
			$receivers = array($receivers);

		foreach($receivers as $user) {
			if($user->ID == get_current_user_id())
				continue;

			if( messages_new_message( array('sender_id' => get_current_user_id(), 'subject' => $subject, 'content' => $content, 'recipients' => $user->ID) ) )
				$c++;
		}

		return $c;
	}
}

if(!function_exists('buddypress_mass_messaging_by_alkaweb')) {
	function buddypress_mass_messaging_by_alkaweb(){
		return BuddypressMassMessagingByAlkaweb::instance();
	}
}

//add_action('bp_include', 'buddypress_mass_messaging_by_alkaweb');
buddypress_mass_messaging_by_alkaweb();