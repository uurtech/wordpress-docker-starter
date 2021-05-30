<?php

namespace WPMailSMTP\Providers\Sendgrid;

use WPMailSMTP\MailCatcherInterface;
use WPMailSMTP\Providers\MailerAbstract;
use WPMailSMTP\WP;

/**
 * Class Mailer.
 *
 * @since 1.0.0
 */
class Mailer extends MailerAbstract {

	/**
	 * Which response code from HTTP provider is considered to be successful?
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $email_sent_code = 202;

	/**
	 * URL to make an API request to.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $url = 'https://api.sendgrid.com/v3/mail/send';

	/**
	 * Mailer constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param MailCatcherInterface $phpmailer The MailCatcher object.
	 */
	public function __construct( $phpmailer ) {

		// We want to prefill everything from MailCatcher class, which extends PHPMailer.
		parent::__construct( $phpmailer );

		$this->set_header( 'Authorization', 'Bearer ' . $this->options->get( $this->mailer, 'api_key' ) );
		$this->set_header( 'content-type', 'application/json' );
	}

	/**
	 * Redefine the way email body is returned.
	 * By default we are sending an array of data.
	 * SendGrid requires a JSON, so we encode the body.
	 *
	 * @since 1.0.0
	 */
	public function get_body() {

		$body = parent::get_body();

		return wp_json_encode( $body );
	}

	/**
	 * @inheritdoc
	 */
	public function set_from( $email, $name = '' ) {

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return;
		}

		$from['email'] = $email;

		if ( ! empty( $name ) ) {
			$from['name'] = $name;
		}

		$this->set_body_param(
			array(
				'from' => $from,
			)
		);
	}

	/**
	 * @inheritdoc
	 */
	public function set_recipients( $recipients ) {

		if ( empty( $recipients ) ) {
			return;
		}

		// Allow for now only these recipient types.
		$default = array( 'to', 'cc', 'bcc' );
		$data    = array();

		foreach ( $recipients as $type => $emails ) {
			if (
				! in_array( $type, $default, true ) ||
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			$data[ $type ] = array();

			// Iterate over all emails for each type.
			// There might be multiple cc/to/bcc emails.
			foreach ( $emails as $email ) {
				$holder = array();
				$addr   = isset( $email[0] ) ? $email[0] : false;
				$name   = isset( $email[1] ) ? $email[1] : false;

				if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
					continue;
				}

				$holder['email'] = $addr;
				if ( ! empty( $name ) ) {
					$holder['name'] = $name;
				}

				array_push( $data[ $type ], $holder );
			}
		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'personalizations' => array( $data ),
				)
			);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function set_content( $content ) {

		if ( empty( $content ) ) {
			return;
		}

		if ( is_array( $content ) ) {

			$default = array( 'text', 'html' );
			$data    = array();

			foreach ( $content as $type => $body ) {
				if (
					! in_array( $type, $default, true ) ||
					empty( $body )
				) {
					continue;
				}

				$content_type  = 'text/plain';
				$content_value = $body;

				if ( $type === 'html' ) {
					$content_type = 'text/html';
				}

				$data[] = array(
					'type'  => $content_type,
					'value' => $content_value,
				);
			}

			$this->set_body_param(
				array(
					'content' => $data,
				)
			);
		} else {
			$data['type']  = 'text/html';
			$data['value'] = $content;

			if ( $this->phpmailer->ContentType === 'text/plain' ) {
				$data['type'] = 'text/plain';
			}

			$this->set_body_param(
				array(
					'content' => array( $data ),
				)
			);
		}
	}

	/**
	 * Redefine the way custom headers are processed for this mailer - they should be in body.
	 *
	 * @since 1.5.0
	 *
	 * @param array $headers
	 */
	public function set_headers( $headers ) {

		foreach ( $headers as $header ) {
			$name  = isset( $header[0] ) ? $header[0] : false;
			$value = isset( $header[1] ) ? $header[1] : false;

			$this->set_body_header( $name, $value );
		}

		// Add custom PHPMailer-specific header.
		$this->set_body_header( 'X-Mailer', 'WPMailSMTP/Mailer/' . $this->mailer . ' ' . WPMS_PLUGIN_VER );
	}

	/**
	 * This mailer supports email-related custom headers inside a body of the message.
	 *
	 * @since 1.5.0
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function set_body_header( $name, $value ) {

		$name = sanitize_text_field( $name );
		if ( empty( $name ) ) {
			return;
		}

		$headers = isset( $this->body['headers'] ) ? (array) $this->body['headers'] : array();

		$headers[ $name ] = WP::sanitize_value( $value );

		$this->set_body_param(
			array(
				'headers' => $headers,
			)
		);
	}

	/**
	 * SendGrid accepts an array of files content in body, so we will include all files and send.
	 * Doesn't handle exceeding the limits etc, as this is done and reported by SendGrid API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attachments
	 */
	public function set_attachments( $attachments ) {

		if ( empty( $attachments ) ) {
			return;
		}

		$data = array();

		foreach ( $attachments as $attachment ) {
			$file = false;

			/*
			 * We are not using WP_Filesystem API as we can't reliably work with it.
			 * It is not always available, same as credentials for FTP.
			 */
			try {
				if ( is_file( $attachment[0] ) && is_readable( $attachment[0] ) ) {
					$file = file_get_contents( $attachment[0] ); // phpcs:ignore
				}
			}
			catch ( \Exception $e ) {
				$file = false;
			}

			if ( $file === false ) {
				continue;
			}

			$filetype = str_replace( ';', '', trim( $attachment[4] ) );

			$data[] = array(
				'content'     => base64_encode( $file ), // string, 1 character.
				'type'        => $filetype, // string, no ;, no CRLF.
				'filename'    => empty( $attachment[2] ) ? 'file-' . wp_hash( microtime() ) . '.' . $filetype : trim( $attachment[2] ), // required string, no CRLF.
				'disposition' => in_array( $attachment[6], array( 'inline', 'attachment' ), true ) ? $attachment[6] : 'attachment', // either inline or attachment.
				'content_id'  => empty( $attachment[7] ) ? '' : trim( (string) $attachment[7] ), // string, no CRLF.
			);
		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'attachments' => $data,
				)
			);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function set_reply_to( $reply_to ) {

		if ( empty( $reply_to ) ) {
			return;
		}

		$data = array();

		foreach ( $reply_to as $key => $emails ) {
			if (
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			$addr = isset( $emails[0] ) ? $emails[0] : false;
			$name = isset( $emails[1] ) ? $emails[1] : false;

			if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
				continue;
			}

			$data['email'] = $addr;
			if ( ! empty( $name ) ) {
				$data['name'] = $name;
			}
		}

		if ( ! empty( $data ) ) {
			$this->set_body_param(
				array(
					'reply_to' => $data,
				)
			);
		}
	}

	/**
	 * SendGrid doesn't support sender or return_path params.
	 * So we do nothing.
	 *
	 * @since 1.0.0
	 *
	 * @param string $from_email
	 */
	public function set_return_path( $from_email ) {}

	/**
	 * Get a SendGrid-specific response with a helpful error.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function get_response_error() { // phpcs:ignore

		$body = (array) wp_remote_retrieve_body( $this->response );

		$error_text = array();

		if ( ! empty( $body['errors'] ) ) {
			foreach ( $body['errors'] as $error ) {
				if ( property_exists( $error, 'message' ) ) {
					// Prepare additional information from SendGrid API.
					$extra = '';
					if ( property_exists( $error, 'field' ) && ! empty( $error->field ) ) {
						$extra .= $error->field . '; ';
					}
					if ( property_exists( $error, 'help' ) && ! empty( $error->help ) ) {
						$extra .= $error->help;
					}

					// Assign both the main message and perhaps extra information, if exists.
					$error_text[] = $error->message . ( ! empty( $extra ) ? ' - ' . $extra : '' );
				}
			}
		} elseif ( ! empty( $this->error_message ) ) {
			$error_text[] = $this->error_message;
		}

		return implode( '<br>', array_map( 'esc_textarea', $error_text ) );
	}

	/**
	 * Get mailer debug information, that is helpful during support.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function get_debug_info() {

		$sendgrid_text[] = '<strong>Api Key:</strong> ' . ( $this->is_mailer_complete() ? 'Yes' : 'No' );

		return implode( '<br>', $sendgrid_text );
	}

	/**
	 * @inheritdoc
	 */
	public function is_mailer_complete() {

		$options = $this->options->get_group( $this->mailer );

		// API key is the only required option.
		if ( ! empty( $options['api_key'] ) ) {
			return true;
		}

		return false;
	}
}
