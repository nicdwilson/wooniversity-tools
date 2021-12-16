<?php
/*
Plugin name: Wooniversity Tools
Plugin URI: wooniversity
Description: Break you own website (and learn to fix it again)
Author: nicw
Version: Beta
Author URI:
*/

namespace Wooni;

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
	}

	/**
	 * Add the main Wooniversity Tools page
	 */
	public function add_menu_page(){

		add_submenu_page(
			'options-general.php',
			'Wooniversity Tools',
			'Wooniversity Tools',
			'manage_options',
			'wooniversity-tools',
			array(
				$this,
				'render_page',
				array(
					'wooniversity-tools'
				)
			)
		);
	}

	/*
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
				'render_page',
				array(
					'wooniversity-tools-shipping'
				)
			)
		);

	}

}