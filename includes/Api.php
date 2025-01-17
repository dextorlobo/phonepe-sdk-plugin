<?php

namespace Imarun\PhonePaySdkPlugin;
use WP_Error;
use WP_REST_Response;
use PhonePe\payments\v1\PhonePePaymentClient;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;
use PhonePe\common\PhonePeClient;
use PhonePe\common\exceptions\PhonePeException;
use PhonePe\Env;

class Api {

	public function __construct() {
		$options                   = get_option( 'wp_pps_general_options', false );
		$this->MERCHANTID          = ( isset( $options['wp_pps_general_merchant_id'] ) ) ? $options['wp_pps_general_merchant_id'] : '';
		$this->SALTKEY             = ( isset( $options['wp_pps_general_salt_key'] ) ) ? $options['wp_pps_general_salt_key'] : '';
		$this->SALTINDEX           = ( isset( $options['wp_pps_general_salt_index'] ) ) ? $options['wp_pps_general_salt_index'] : '';
		$this->ENV                 = ( isset( $options['wp_pps_general_environment'] ) ) ? $options['wp_pps_general_environment'] : ''; // 'Env.PRODUCTION' or 'Env.UAT';
		$this->SHOULDPUBLISHEVENTS = true;
	}

	public function createPaymentPage( $data ) {
		try {
			$phonePePaymentsClient = new PhonePePaymentClient( $this->MERCHANTID, $this->SALTKEY, $this->SALTINDEX, $this->ENV, $this->SHOULDPUBLISHEVENTS );
			$request = PgPayRequestBuilder::builder()
				->mobileNumber( $data['phone'] )
				->callbackUrl( site_url( '/wp-json/phonepe/v1/payment-callback' ) )
				->merchantId( $this->MERCHANTID )
				->merchantUserId( $data['email'] )
				->amount( $data['amount'] * 100 )
				->merchantTransactionId( $data['wp_order_id'] )
				->redirectUrl( site_url( '/order-confirmation?token='.base64_encode( $data['wp_order_id'] ) ) )
				->redirectMode("REDIRECT")
				->paymentInstrument( InstrumentBuilder::buildPayPageInstrument() )
				->build();

			$response = $phonePePaymentsClient->pay( $request );
			$url      = $response->getInstrumentResponse()->getRedirectInfo()->getUrl();

			return new WP_REST_Response( $url, 200 );
		} catch ( PhonePeException $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	public function verifyPayment( $payload, $xVerify ) {
		try {
			$phonepeClient = new PhonePeClient( $this->MERCHANTID, $this->SALTKEY, $this->SALTINDEX, $this->ENV );
			$isValid       = $phonepeClient->verifyCallback( $payload, $xVerify );
			if ( ! $isValid ) {
				return new WP_Error( '400', 'Invalid request.' );
			}

			return new WP_REST_Response( $isValid, 200 );
		} catch ( PhonePeException $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}
}
