<?php

class AppPresser_Notifications_CPT {

	public static $notify_url_meta_key = 'apppush_notify_url';

	public function __construct() {

		$this->singular  = __( 'Notification', 'apppresser-push' );
		$this->plural    = __( 'Notifications', 'apppresser-push' );
		$this->post_type = AppPresser_Notifications::$cpt;

		$this->labels = array(
			'name'               => $this->plural,
			'singular_name'      => $this->singular,
			'add_new'            => sprintf( __( 'Add New %s' ), $this->singular ),
			'add_new_item'       => sprintf( __( 'Add New %s' ), $this->singular ),
			'edit_item'          => sprintf( __( 'Edit %s' ), $this->singular ),
			'new_item'           => sprintf( __( 'New %s' ), $this->singular ),
			'all_items'          => $this->plural,
			'view_item'          => sprintf( __( 'View %s' ), $this->singular ),
			'search_items'       => sprintf( __( 'Search %s' ), $this->plural ),
			'not_found'          => sprintf( __( 'No %s' ), $this->plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash' ), $this->plural ),
			'parent_item_colon'  => null,
			'menu_name'          => $this->plural,
		);

		$this->args = array(
			'labels'             => $this->labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => 'apppresser_settings',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'notification' ),
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'menu_position'      => 1,
			'supports'           => array( 'title', 'excerpt' ),
			'taxonomies'         => array( 'category' ),
		);

	}

	public function hooks() {

		add_action( 'init', array( $this, 'register_cpt' ) );
		add_action( 'admin_head', array( $this, 'conditional_hooks' ) );
		add_filter( 'post_updated_messages', array( $this, 'messages' ) );
		add_filter( 'manage_edit-'. $this->post_type .'_columns', array( $this, 'columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_display' ) );
		add_action( 'add_meta_boxes_' . $this->post_type, array( $this, 'metabox_replace' ) );
		add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 2 );

	}

	/**
	 * Conditional Hooks for this CPT
	 * @since  1.0.0
	 */
	public function conditional_hooks() {
		$screen = get_current_screen();
		if ( isset( $screen->post_type ) && $screen->post_type == $this->post_type ) {
			add_filter( 'enter_title_here', array( $this, 'title' ) );
			add_filter( 'gettext', array( $this, 'modify_text' ) );
			$this->excerpt_css();
		}
	}

	/**
	 * Register notifications Custom Post Type
	 * @since  1.0.0
	 */
	public function register_cpt() {
		register_post_type( $this->post_type, apply_filters( 'appp_push_cpt_args', $this->args ) );
	}

	/**
	 * Modies CPT based messages to include our CPT labels
	 * @since  0.1.0
	 * @param  array  $messages Array of messages
	 * @return array            Modied messages array
	 */
	public function messages( $messages ) {
		global $post, $post_ID;

		$messages[$this->singular] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( '%1$s updated. <a href="%2$s">View %1$s</a>' ), $this->singular, esc_url( get_permalink( $post_ID ) ) ),
			2 => __( 'Custom field updated.' ),
			3 => __( 'Custom field deleted.' ),
			4 => sprintf( __( '%1$s updated.' ), $this->singular ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( '%1$s restored to revision from %2$s' ), $this->singular , wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( '%1$s published. <a href="%2$s">View %1$s</a>' ), $this->singular, esc_url( get_permalink( $post_ID ) ) ),
			7 => sprintf( __( '%1$s saved.' ), $this->singular ),
			8 => sprintf( __( '%1$s submitted. <a target="_blank" href="%2$s">Preview %1$s</a>' ), $this->singular, esc_url( add_query_arg( 'preview', 'true', esc_url( get_permalink( $post_ID ) ) ) ) ),
			9 => sprintf( __( '%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %1$s</a>' ), $this->singular,
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
			10 => sprintf( __( '%1$s draft updated. <a target="_blank" href="%2$s">Preview %1$s</a>' ), $this->singular, esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		);
		return $messages;

	}

	/**
	 * Filter CPT title entry placeholder text
	 * @since  1.0.0
	 * @param  string $title Original placeholder text
	 * @return string        Modifed placeholder text
	 */
	public function title( $title ){
		return sprintf( __( '%s Title' ), $this->singular );
	}

	/**
	 * Change text for certain customizer strings four our custom version.
	 * @since  1.0.7
	 * @param  string  $translated_text Input
	 * @return string                   Maybe modified text
	 */
	public function modify_text( $translated_text ) {
		switch ( $translated_text ) {
			case 'Excerpt':
				return sprintf( __( '%s Text' ), $this->singular );
		}
		return $translated_text;
	}

	/**
	 * Registers admin columns to display.
	 * @since  0.1.0
	 * @param  array  $columns Array of registered column names/labels
	 * @return array           Modified array
	 */
	public function columns( $columns ) {

		$date = $columns['date'];
		unset( $columns['date'] );
		$columns[ $this->post_type .'_excerpt' ] = __( 'Excerpt' );
		$columns['date'] = $date;

		return $columns;
	}

	/**
	 * Handles admin column excerpt display.
	 * @since  0.1.0
	 * @param  array  $column Array of registered column names
	 */
	public function columns_display( $column ) {
		global $post;

		if ( $this->post_type .'_excerpt' === $column && ! empty( $post->post_excerpt ) ) {
			echo wpautop( $post->post_excerpt );
		}
	}

	/**
	 * Make excerpt column wider
	 * @since  1.0.0
	 */
	public function excerpt_css() {
		?>
		<style type="text/css"> .column-<?php echo $this->post_type; ?>_excerpt { width: 65%; } </style>
		<?php
	}

	/**
	 * Replace excerpt metabox
	 * @since  1.0.0
	 * @param  object  $post Post object
	 */
	public function metabox_replace() {
		remove_meta_box( 'postexcerpt', $this->post_type, 'normal' );
		add_meta_box( 'notificationexcerpt', sprintf( __( '%s Text' ), $this->singular ), array( $this, 'post_excerpt_meta_box' ), $this->post_type, 'normal', 'core' );
	}

	/**
	 * Display post excerpt textarea, w/o helper text
	 * @since 1.0.0
	 * @param object $post
	 */
	function post_excerpt_meta_box( $post ) {

		$nofify_url_meta = get_post_meta( $post->ID, self::$notify_url_meta_key, true );

		?>
		<div class="inside">
			<p><label class="screen-reader-text" for="excerpt"><?php printf( __( '%s Text' ), $this->singular ) ?></label><textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea></p>
			<p><label for="notify_url">Custom URL:</label><br><input type="text" name="notify_url" id="notify_url" class="code" value="<?php echo $nofify_url_meta ?>"></p> 
			<p><small>Optional: external urls open in browser, app url opens in app.</small></p>
			<?php self::get_segments_dropdown() ?>
			<?php wp_nonce_field( 'push_text_description', 'apppush_nonce' ); ?>
		</div>
		<?php
	}

	public static function get_segments_dropdown( $segments = array(), $selected = '' ) {

		if( empty( $segments ) ) {
			$segments = self::get_segments();
		}

		if( !empty( $segments ) ) {
			echo '<label>Send to segment: </label><br/>';
			echo '<select name="segments">';
			echo '<option value="">All subscribers</option>';
			foreach ($segments as $key => $value) {
				echo '<option value="' . $segments[$key]["arn"] .'"'. selected( $selected, $segments[$key]["arn"], false ).'>' . $segments[$key]["name"] . '</option>';
			}
			echo '</select>';
		}
	}

	/**
	 * Get AP3 segments from API, if they exist
	 */
	public static function get_segments() {

		$segments = [];

		$notifications_ap3_key = appp_get_setting('notifications_ap3_key');

		// Get AP3 segments from API, if they exist
		if( !empty( $notifications_ap3_key ) ) {

			$site_slug = appp_get_setting( 'ap3_site_slug' );
			$app_id = appp_get_setting( 'ap3_app_id' );

			$my_server = ( defined('MYAPPPRESSER_DEV_DOMAIN') ) ? MYAPPPRESSER_DEV_DOMAIN : 'https://myapppresser.com/';

			$api = $my_server . $site_slug . '/wp-json/ap3/v1/app/' . $app_id;

			$response = wp_remote_get( esc_url_raw( $api ) );
			$api_response = json_decode( wp_remote_retrieve_body( $response ), true );

			$segments = $api_response['segments'];

		}

		return $segments;
	}

	function save_post_meta( $post_id, $post ) {
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['apppush_nonce'] ) || !wp_verify_nonce( $_POST['apppush_nonce'], 'push_text_description' ) )
			return $post_id;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['notify_url'] ) ) ? $_POST['notify_url']: '';

		// If not APv3+, esc_url
		if( ! appp_get_setting( 'notifications_ap3_key' ) ) {
			$new_meta_value = esc_url( $new_meta_value );
		}

		/* Get the meta key. */
		$meta_key = self::$notify_url_meta_key;

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && $meta_value == '' )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( $new_meta_value == '' && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}

}
