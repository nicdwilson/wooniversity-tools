<?php
/*
Plugin name: Wooniversity Tools
Plugin URI: wooniversity
Description: Break your own website (and learn to fix it again)
Author: nicw
Version: Beta
Author URI:
*/

namespace Wooni;

use Wooni\Wooniversity_Tools;

/**
 * Class Menu
 * @package Wooni
 */
class Menu {

	protected static $instance = null;

	public static function init(): ?Menu {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/* when the class is constructed. */
	public function __construct() {

		$this->add_menu_page();
		$this->add_shipping_submenu_page();
		$tools = Wooniversity_Tools::init();
		$tools->register_tools( 'wooniversity-tools-shipping' );


	}

	/**
	 * Add the main Wooniversity Tools page
	 */
	public function add_menu_page() {

		add_menu_page(
			'Wooniversity Tools',
			'Wooniversity Tools',
			'manage_options',
			'wooniversity-tools.php',
			array(
				$this,
				'render_menu_page',
			),
			'dashicons-admin-tools',
			1
		);
	}

	/**
	 * Add the page to the tools menu
	 */
	public function add_shipping_submenu_page() {

		add_submenu_page(
			'wooniversity-tools.php',
			'Shipping',
			'Shipping',
			'manage_options',
			'wooniversity-tools-shipping',
			array(
				$this,
				'render_shipping_page',
			)
		);

	}

	public function render_menu_page() {
	}

	public function render_shipping_page() {

		$template       = dirname( plugin_dir_path( __FILE__ ) ) . '/templates/shipping-menu.php';
		$scenario_menus = apply_filters( 'add_scenario_menus', array() );

		ob_start();
		include( $template );

		echo ob_get_clean();

	}

}
