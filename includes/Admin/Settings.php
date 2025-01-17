<?php
/**
 * Settings class.
 *
 * @package img-pps-wp
 * @since 0.1.0
 */

declare( strict_types = 1 );

namespace Imarun\PhonePaySdkPlugin\Admin;

class Settings {

	/**
	 * Settings constructor.
	 *
	 * @since   0.1.0
	 */
	public function init() {
		/**
		 * Register our wp_pps_general_settings_init to the admin_init action hook.
		 */
		add_action( 'admin_init', array( $this, 'wp_pps_general_settings_init' ) );

		/**
		 * Register our wp_pps_general_options_page to the admin_menu action hook.
		 */
		add_action( 'admin_menu', array( $this, 'wp_pps_general_options_page' ) );

		$plugin = 'phonepe-sdk-plugin/phonepe-sdk-plugin.php';
		add_filter( "plugin_action_links_$plugin", array( $this, 'wp_pps_settings_link' ) );
	}
	
	/**
	 * custom option and settings
	 */
	public function wp_pps_general_settings_init() {
		// Register a new setting for "wp_pps_general" page.
		register_setting( 'wp_pps_general', 'wp_pps_general_options' );
	
		// Register a new section in the "wp_pps_general" page.
		add_settings_section(
			'wp_pps_general_section_developers',
			__( '', 'wp_pps_general' ), array( $this, 'wp_pps_general_section_developers_callback' ),
			'wp_pps_general'
		);

		// Register a new field in the "wp_pps_general_section_developers" section, inside the "wp_pps_general" page.
		add_settings_field(
			'wp_pps_general_merchant_id', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Merchant ID', 'wp_pps_general' ),
			array( $this, 'wp_pps_general_merchant_id_cb' ),
			'wp_pps_general',
			'wp_pps_general_section_developers',
			array(
				'label_for' => 'wp_pps_general_merchant_id',
				'class'     => 'wp_pps_general_row regular-text',
			)
		);

		// Register a new field in the "wp_pps_general_section_developers" section, inside the "wp_pps_general" page.
		add_settings_field(
			'wp_pps_general_salt_key', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Salt Key', 'wp_pps_general' ),
			array( $this, 'wp_pps_general_salt_key_cb' ),
			'wp_pps_general',
			'wp_pps_general_section_developers',
			array(
				'label_for' => 'wp_pps_general_salt_key',
				'class'     => 'wp_pps_general_row regular-text',
			)
		);

		// Register a new field in the "wp_pps_general_section_developers" section, inside the "wp_pps_general" page.
		add_settings_field(
			'wp_pps_general_salt_index', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Salt Index', 'wp_pps_general' ),
			array( $this, 'wp_pps_general_salt_index_cb' ),
			'wp_pps_general',
			'wp_pps_general_section_developers',
			array(
				'label_for' => 'wp_pps_general_salt_index',
				'class'     => 'wp_pps_general_row regular-text',
			)
		);

		// Register a new field in the "wp_pps_general_section_developers" section, inside the "wp_pps_general" page.
		add_settings_field(
			'wp_pps_general_environment', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Environment', 'wp_pps_general' ),
			array( $this, 'wp_pps_general_environment_cb' ),
			'wp_pps_general',
			'wp_pps_general_section_developers',
			array(
				'label_for' => 'wp_pps_general_environment',
				'class'     => 'wp_pps_general_row regular-text',
			)
		);
	}

	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function wp_pps_general_section_developers_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'PhonePe API Credentials.', 'wp_pps_general' ); ?></p>
		<?php
	}

	/**
	 * merchant id callback function.
	 *
	 * @param array $args
	 */
	public function wp_pps_general_merchant_id_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_pps_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_pps_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Merchant ID.</p>
		<?php
	}

	/**
	 * Salt key callback function.
	 *
	 * @param array $args
	 */
	public function wp_pps_general_salt_key_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_pps_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_pps_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Salt Key.</p>
		<?php
	}

	/**
	 * Salt Index callback function.
	 *
	 * @param array $args
	 */
	public function wp_pps_general_salt_index_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_pps_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="wp_pps_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo $options[ $args['label_for'] ] ?>">
		<p class="description" id="tagline-description">Enter Salt Index.</p>
		<?php
	}

	/**
	 * Environment callback function.
	 *
	 * @param array $args
	 */
	public function wp_pps_general_environment_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$options = get_option( 'wp_pps_general_options' );
		$options[ $args['label_for'] ] = $options[ $args['label_for'] ] ?? "";
		$envs = [
			'PRODUCTION' => 'Production',
			'UAT' => 'Development',
		]
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" name="wp_pps_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
			<option value="" <?php selected( $options[ $args['label_for'] ], '' ); ?>>Select Environment</option>
			<?php foreach ( $envs as $key => $value ) { 
				if ( isset( $value ) && is_array( $options ) && isset( $options[ $args['label_for'] ] ) ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $options[ $args['label_for'] ], $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php 
				} else {
					echo '<option disabled>Invalid Environment</option>';
				} 
			} ?>
		</select>
		<p class="description" id="tagline-description">Selete Environment.</p>
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	public function wp_pps_general_options_page() {
		add_submenu_page(
			'options-general.php',
			'PhonePe Settings',
			'PhonePe Settings',
			'manage_options',
			'wp_pps_general',
			array( $this, 'wp_pps_general_options_page_html' )
		);
	}

	/**
	 * Top level menu callback function
	 */
	public function wp_pps_general_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	
		// add error/update messages
	
		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		/* if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wp_pps_general_messages', 'wp_pps_general_message', __( 'Settings Saved', 'wp_pps_general' ), 'updated' );
		} */
	
		// show error/update messages
		settings_errors( 'wp_pps_general_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "wp_pps_general"
				settings_fields( 'wp_pps_general' );
				// output setting sections and their fields
				// (sections are registered for "wp_pps_general", each field is registered to a specific section)
				do_settings_sections( 'wp_pps_general' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function wp_pps_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=wp_pps_general">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}
}
