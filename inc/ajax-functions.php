<?php
/**
 * Setup SMTP server
 *
 * @param object $phpmailer
 * @return void
 */
function mailtrap($phpmailer) {
	$phpmailer->isSMTP();
	$phpmailer->Host = 'smtp.mailtrap.io';
	$phpmailer->SMTPAuth = true;
	$phpmailer->Port = 25;
	//$phpmailer->Username = '4bd4b28780f625'; // just for test - artur
	//$phpmailer->Password = '3a48d9b888f834';
	$phpmailer->Username = 'd882e29ab672a9'; // sm
	$phpmailer->Password = '947888c60ae876';
}
add_action('phpmailer_init', 'mailtrap');

if ( ! function_exists('smexample_debug_wpmail') ) {
	/**
	 * Debug SMTP connection
	 *
	 * @param boolean $result
	 * @return void
	 */
	function smexample_debug_wpmail( $result = false ) {
		if ( $result ) return;

		global $ts_mail_errors, $phpmailer;

		if ( ! isset($ts_mail_errors) ) $ts_mail_errors = array();
		if ( isset($phpmailer) ) $ts_mail_errors[] = $phpmailer->ErrorInfo;

		print_r('<pre>');
		print_r($ts_mail_errors);
		print_r('</pre>');
	}
}

add_action('wp_ajax_nopriv_smexample_send', 'smexample_send');
add_action('wp_ajax_smexample_send', 'smexample_send');
if ( ! function_exists('smexample_send') ) {
	/**
	 * Send contact emails
	 *
	 * @return void
	 */
	function smexample_send() {
		if ( check_ajax_referer( 'smexample_send', 'nonce' ) ) {

			$name = sanitize_text_field( esc_attr($_POST['name']) );
			$message = wp_strip_all_tags($_POST['message']);
			$from_email = sanitize_email( esc_attr($_POST['email']) );
			$consent = sanitize_text_field( esc_attr($_POST['consent']) );

			$fieldErr = [];

			if( empty($name) ) $fieldErr[] = ( __( 'Podaj imię!', 'smexample' ) );
			if( empty($message) ) $fieldErr[] = ( __( 'Podaj wiadomość!', 'smexample' ) );
			if( empty($from_email) ) $fieldErr[] = ( __( 'Podaj adres email!', 'smexample' ) );
			if( empty($consent) ) $fieldErr[] = ( __( 'Zaznacz zgodę na przetwarzanie danych', 'smexample' ) );
			
			if( !empty($fieldErr) ) {
				$fieldErr = implode("<br>", $fieldErr);
				wp_send_json_error( $fieldErr );
			}

			$target_email = get_bloginfo('admin_email');

			$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . $name . ' <' . $from_email . '>');
			$subject = get_bloginfo('name') . ' - Contact Form';

			$send = wp_mail($target_email, $subject, $message, $headers);

			if ( $send ) {
				wp_send_json_success( __( 'Wiadomość została wysłana!', 'smexample' ) );
			} else {
				//smexample_debug_wpmail($send);
				wp_send_json_error( __( 'Nie udało się wysłać wiadomości!', 'smexample' ) );
			}
		} else {
			wp_send_json_error( __( 'Błąd! Token niepoprawny.', 'smexample' ) );
		}
	}
}

add_action('wp_ajax_nopriv_smexample_get_reports', 'smexample_get_reports');
add_action('wp_ajax_smexample_get_reports', 'smexample_get_reports');
if ( ! function_exists('smexample_get_reports') ) {
	/**
	 * Get reports set in theme options
	 *
	 * @return void
	 */
	function smexample_get_reports() {
		$target = ( !empty($_POST['target']) ) ? intval($_POST['target']) : 0;
		$reports = smexample_get_option('reports');
		if( !empty($reports) && !empty($reports[$target]) ) {
			$item = $reports[$target];
			$content = '';

			if( !empty($item['title']) ) $content .= '<div class="report__title">' . $item['title'] . '</div>';
			if( !empty($item['content']) ) $content .= '<div class="report__content">' . apply_filters('the_content', $item['content']) . '</div>';

			echo $content;
			die();
		}
	}
}