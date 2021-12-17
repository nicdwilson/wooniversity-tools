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

	/**
	 * Folder, file and messaging variables for this plugin
	 *
	 * @var string[]
	 */
	private $plugin = array(
			'folder' => 'woocommerce-shipping-usps',
			'file' => 'woocommerce-shipping-usps.php',
			'message' => 'To complete the USPS Shipping troubleshooting section, activate the USPS Shipping extension.',
		);

	protected static $instance = null;

	public static function init(): ?USPS {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		add_filter('add_wooni_plugins', array( $this, 'add_plugin' ) );
		add_filter('add_shipping_scenario_menus', array( $this, 'add_scenario_menus' ) );
	}

	/**
	 * Add the plugin setup notice. If the plugin is missing, this let's the user know they'll need
	 * to add and configure the plugin before running the scenarios for this plugin
	 *
	 * @param array $wooni_plugins
	 *
	 * @return array|mixed
	 */
	public function add_plugin( $wooni_plugins = array() ){

		$wooni_plugins[] = $this->plugin;

		return $wooni_plugins;

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
	public function add_scenario_menus( $scenario_menus = array() ){

		$scenario_menus[] = array(
			'parent-slug' => 'wooniversity-tools-shipping',
			'tab-slug' => 'usps',
			'tab-title' => 'USPS Shipping scenarios',
		);

		return $scenario_menus;

	}
}

add_action( 'admin_menu', array( 'Wooni\USPS', 'init' ) );

