<?php

/*
 *
 * todo: each plugin scenarios should load it's own requirements
 *
* Plugin scenarios
*


todo load settings
todo load settings fields
todo assign to shipping
 */

namespace Wooni;

/**
 * Class Menu
 * @package Wooni
 */
class USPS {


	private $usps_scenarios = array();

	protected static $instance = null;

	public static function init(): ?USPS {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		$this->settings = $this->get_usps_settings();
		$this->add_usps_scenarios();
		$this->add_scenarios();
		$this->add_usps_actions();

		//add_action( 'wp_ajax_usps_remove_origin_code', array( $this, 'usps_remove_origin_code' ) );

	}

	public function add_usps_actions() {

		foreach ( $this->usps_scenarios as $scenario ) {
			foreach ( $this->usps_scenarios as $scenario ) {
				add_action( 'wp_ajax_' . $scenario['scenario-action'], array( $this, $scenario['scenario-action'] ) );
			}
		}
	}


	/**
	 * Add the scenario menu data. This drives the menu page.
	 * Required: Each plugin has scenarios that belong to a specific parent page
	 * Make sure you match the parent page slug correctly.
	 *
	 * @param array $scenario_menus
	 *
	 * @return array|mixed
	 */
	public function add_scenarios( $scenario_menus = array() ) {

		$scenario_data = array(
			'plugin-folder'    => 'woocommerce-shipping-usps',
			'plugin-file'      => 'woocommerce-shipping-usps.php',
			'plugin-message'   => 'To complete the USPS Shipping troubleshooting section, activate the USPS Shipping extension.',
			'menu-parent-slug' => 'wooniversity-tools-shipping',
			'menu-tab-slug'    => 'usps',
			'menu-tab-title'   => 'USPS Shipping scenarios',
			'scenarios'        => $this->usps_scenarios,
		);

		$tools = Wooniversity_Tools::init();
		$tools->register_scenarios( $scenario_data );

	}

	public function add_usps_scenarios() {

		$usps_scenarios = array(
			array(
				'scenario-id'            => 'usps-scenario-one',
				'scenario-title'         => 'Scenario 1',
				'scenario-prompt'        => 'I am not getting USPS rates at all, even though I have entered my ID, setup my zones, and added weights and dimensions to my products. Please help.',
				'scenario-action' => 'usps_remove_origin_code',
				'scenario-hint'          => 'Here is your hint - the answer is definitely in the shipping debug response from USPS.',
				'scenario-solution'      => 'Each USPS Shipping method in each shipping zone must have an origin postal code set. Otherwise the debug message displays "MissingOriginPostalCode"'
			),
		);

		$this->usps_scenarios = $usps_scenarios;
	}

	public function usps_remove_origin_code() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wooniversity_tools' ) ) {
			exit( 'Cheatin\' huh?' );
		}

		$settings = $this->get_usps_settings();

		if ( count( $settings ) > 1 ) {
			//todo there is more than one zone set up for USPS
		}

		foreach ( $settings as $key => $value ) {
			$settings[ $key ]['origin'] = '';
			update_option( $key, $settings[ $key ] );
		}

		( new Wooniversity_Tools )->register_current_action( 'usps_remove_origin_code' );

		echo json_encode( array( 'type' => 'success' ) );
		exit;
	}

	private function get_usps_settings() {

		$instance_ids = $this->get_usps_instance_ids();

		foreach ( $instance_ids as $instance_id ) {
			$settings[ 'woocommerce_usps_' . $instance_id->instance_id . '_settings' ] = get_option( 'woocommerce_usps_' . $instance_id->instance_id . '_settings' );
		}

		return $settings;
	}

	private function get_usps_instance_ids() {

		global $wpdb;

		$table        = $wpdb->prefix . 'woocommerce_shipping_zone_methods';
		$sql          = "SELECT instance_id FROM $table WHERE method_id = 'usps'";
		$instance_ids = $wpdb->get_results( $sql );

		if ( is_wp_error( $instance_ids ) || empty( $instance_ids ) ) {
			$instance_ids = array();
		}

		return $instance_ids;
	}

}


add_action( 'admin_init', array( 'Wooni\USPS', 'init' ) );