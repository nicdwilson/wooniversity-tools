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
		apply_filters('add_wooni_plugins', $this->add_plugin );
	}

	public function add_plugin( $wooni_plugins = array() ){

		$wooni_plugins[] = $this->plugin;
		return $wooni_plugins;
	}
}

