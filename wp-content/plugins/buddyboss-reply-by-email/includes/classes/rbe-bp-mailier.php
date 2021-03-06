<?php
/**
 * Core component classes.
 *
 * @package BuddyPress
 * @subpackage Core
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Email delivery implementation using PHPMailer.
 *
 * @since 2.5.0
 */

if(interface_exists("BP_Email_Delivery")) {

class RBE_BP_PHPMailer implements BP_Email_Delivery {

	/**
	 * Send email(s).
	 *
	 * @since 2.5.0
	 *
	 * @param BP_Email $email Email to send.
	 * @return bool|WP_Error Returns true if email send, else a descriptive WP_Error.
	 */
	public function bp_email( BP_Email $email ) {
		static $phpmailer = null;

		if ( $phpmailer === null ) {
			if ( ! class_exists( 'PHPMailer' ) ) {
				require_once ABSPATH . WPINC . '/class-phpmailer.php';
				require_once ABSPATH . WPINC . '/class-smtp.php';
			}

			$phpmailer = new PHPMailer( true );
		}


		/*
		 * Resets.
		 */

		$phpmailer->clearAllRecipients();
		$phpmailer->clearAttachments();
		$phpmailer->clearCustomHeaders();
		$phpmailer->clearReplyTos();
		$phpmailer->Sender = '';


		/*
		 * Set up.
		 */

		$phpmailer->IsMail();
		$phpmailer->CharSet  = bp_get_option( 'blog_charset' );
		$phpmailer->Hostname = self::get_hostname();


		/*
		 * Content.
		 */

		$phpmailer->Subject = $email->get_subject( 'replace-tokens' );
		$content_plaintext  = PHPMailer::normalizeBreaks( $email->get_content_plaintext( 'replace-tokens' ) );

		if ( $email->get( 'content_type' ) === 'html' ) {
			$phpmailer->msgHTML( $email->get_template( 'add-content' ) );
			$phpmailer->AltBody = $content_plaintext;

		} else {
			$phpmailer->IsHTML( false );
			$phpmailer->Body = $content_plaintext;
		}

		$recipient = $email->get_from();
		try {
			$phpmailer->SetFrom( $recipient->get_address(), $recipient->get_name(), false );
		} catch ( phpmailerException $e ) {
		}

		$recipient = $email->get_reply_to();
		try {
			$phpmailer->addReplyTo( $recipient->get_address(), $recipient->get_name() );
		} catch ( phpmailerException $e ) {
		}

		$recipients = $email->get_to();
		foreach ( $recipients as $recipient ) {
			try {
				$phpmailer->AddAddress( $recipient->get_address(), $recipient->get_name() );
			} catch ( phpmailerException $e ) {
			}
		}

		$recipients = $email->get_cc();
		foreach ( $recipients as $recipient ) {
			try {
				$phpmailer->AddCc( $recipient->get_address(), $recipient->get_name() );
			} catch ( phpmailerException $e ) {
			}
		}

		$recipients = $email->get_bcc();
		foreach ( $recipients as $recipient ) {
			try {
				$phpmailer->AddBcc( $recipient->get_address(), $recipient->get_name() );
			} catch ( phpmailerException $e ) {
			}
		}

		$headers = $email->get_headers();
		foreach ( $headers as $name => $content ) {
			$phpmailer->AddCustomHeader( $name, $content );
		}


		/**
		 * Fires after PHPMailer is initialised.
		 *
		 * @since 2.5.0
		 *
		 * @param PHPMailer $phpmailer The PHPMailer instance.
		 */
		do_action( 'bp_phpmailer_init', $phpmailer );

		/** This filter is documented in wp-includes/pluggable.php */
		do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );

		try {
			return $phpmailer->Send();
		} catch ( phpmailerException $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage(), $email );
		}
	}


	/*
	 * Utility/helper functions.
	 */

	/**
	 * Get an appropriate hostname for the email. Varies depending on site configuration.
	 *
	 * @since 2.5.0
	 *
	 * @return string
	 */
	public static function get_hostname() {
		if ( is_multisite() ) {
			return get_current_site()->domain;  // From fix_phpmailer_messageid()
		}

		$home = bp_get_option( 'home' );
		return parse_url($home,PHP_URL_HOST);
	}
}

}
