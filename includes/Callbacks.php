<?php
/**
 * Callback Functions.
 *
 * @package img-ecm-wp
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\PhonePaySdkPlugin;

use WP_REST_Request;
use WP_REST_Response;

class Callbacks {

	/**
	 * Callbacks constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {}

	public function handle_payment_callback( WP_REST_Request $request ) {
		if ( ! $request->is_json_content_type() ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'You have provided invalid content_type to perform this request.',
					'data'    => [],
				),
				400
			);
		}

		$payload = $request->get_body();
		$headers = $request->get_headers();
		$xVerify = isset( $headers['x_verify'] ) ? $headers['x_verify'][0] : '';
		error_log( json_encode( $xVerify ) );
		error_log( $payload );

		if ( empty( $xVerify ) || empty( $payload ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Please provide x-verify or payload.',
					'data'    => [],
				),
				400
			);
		}

		$payload_array = json_decode( $payload, true );

		if ( ! isset( $payload_array['response'] ) || empty( $payload_array['response'] ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Please provide response.',
					'data'    => [],
				),
				400
			);
		}

		$isValid = get_phonepe_api_instance()->verifyPayment( $payload, $xVerify );
		$payload = json_decode( base64_decode( $payload_array['response'] ), true );

		//if ( ! is_wp_error( $isValid ) ) {
			error_log('success');
			if ( $payload['success'] === true ) {
				do_action( 'thl_payment_success_callback', $payload, $headers );
			} else {
				error_log('failed');
				do_action( 'thl_payment_failed_callback', $payload, $headers );
			}

			return new WP_REST_Response(
				array(
					'success' => true,
					'message' => 'Payment updated successfully.',
					'data'    => $payload,
				),
				200
			);
		/* } else {
			error_log('validation failed');
			do_action( 'thl_payment_varification_failed_callback', $payload, $headers );

			return new WP_REST_Response(
				array(
					'success' => false,
					'message' => 'Payment verification failed.',
					'data'    => $payload,
				),
				400
			);
		} */
	}
}

		