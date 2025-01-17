<?php
/**
 * The plugin bootstrap file.
 *
 * @since 1.0.0
 * @package img-pps-wp
 *
 * @wordpress-plugin
 * Plugin Name:       PhonePe Plugin
 * Description:       PhonePe SDK plugin.
 * Version:           1.0.6
 * Author:            Arun Sharma
 * Author URI:        https://www.imarun.me/
 * Text Domain:       img-pps-wp
 */

declare( strict_types = 1 );

use Imarun\PhonePaySdkPlugin\Plugin;
use Imarun\PhonePaySdkPlugin\Api;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * API and Plugin version constants.
 */
define( 'IMG_PPS_PLUGIN_VERSION', '1.0.6' );
define( 'IMG_PPS_PLUGIN_PATH', __FILE__ );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	include_once __DIR__ . '/vendor/autoload.php';
} else {
	throw new \Exception( 'Missing vendor/autoload.php. Please run composer install.' );
}

( new Plugin() )->init();

function get_phonepe_api_instance() {
	return new Api();
}
