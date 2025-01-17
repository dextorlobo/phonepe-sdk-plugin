<?php
/**
 * Main plugin class.
 *
 * @package img-pps-wp
 * @since 1.0.0
 */

declare( strict_types = 1 );

namespace Imarun\PhonePaySdkPlugin;
use Imarun\PhonePaySdkPlugin\Admin\Settings;
use Imarun\PhonePaySdkPlugin\Callbacks;

/**
 * The core plugin class.
 *
 * @since   1.0.0
 * @package img-pps-wp
 */
class Plugin {
	const API_NAMESPACE = 'phonepe/v1';
	public function init() {
		$this->register_routes();
		/**
		 * Load hooks after setup theme.
		 */
		add_action( 'after_setup_theme', array( $this, 'pps_fire_after_setup_theme_methods' ) );
	}

	/**
	 * Register plugin routes.
	 */
	private function register_routes() {
		add_action(
			'rest_api_init',
			function () {
				foreach ( $this->routes() as $route => $args ) {
					register_rest_route( self::API_NAMESPACE, $route, $args );
				}
			}
		);
	}

	/**
	 * Get the plugin routes.
	 *
	 * @return array
	 */
	private function routes(): array {
		return array(
			'/payment-callback' => array(
				'methods' => 'POST',
				'callback' => array( new Callbacks, 'handle_payment_callback' ),
				'permission_callback' => '__return_true',
			),
		);
	}

	/**
	 * Check access.
	 */
	public function check_access( \WP_REST_Request $request ) {
		$key = $request->get_header( 'x-api-key' );
		if ( $key == $this->api_key ) {
			return true;
		}

		return new \WP_Error(
			'error',
			__( 'Sorry, you are not allowed to do that.' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	/**
	 * Register hooks and posts after setup theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function pps_fire_after_setup_theme_methods() {
		( new Settings )->init();
	}
}
