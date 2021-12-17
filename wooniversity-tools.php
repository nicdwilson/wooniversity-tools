<?php
/*
Plugin name: Wooniversity Tools
Plugin URI: https://wwwocommerce.com
Description: Break your WooCommerce - and learn to fix it
Author: nicw
Version: 1.1
Author URI:
*/

namespace Wooni;


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-menu.php';
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-options.php';
}

/**
 * Load scenarios
 * todo autoload new scenarios
 */
if ( is_admin() ) {
    /*
     * $dir = plugin_dir_path( __FILE__ ) . 'scenarios';
     * $scenarios = list_files( $dir, 1, array('index.php') );
     * etc for autoload
     */
	require_once plugin_dir_path( __FILE__ ) . '/scenarios/woocommerce-shipping-usps/class-usps.php';
}

/**
 * Class Wooniversity_Tools
 * @package Wooni
 */
class Wooniversity_Tools {

	/*
	 * Version is a habit
	 */
	public $version = '1';

	/**
     * todo move to invidual scenarios
	 * Plugin scenarios
	 */
    private $wooni_plugins = array();

	protected static $instance = null;

	/**
	 * @return Wooniversity_Tools|null
	 */
	public static function init(): ?Wooniversity_Tools {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {

		add_action( 'admin_menu', array( 'Wooni\Menu', 'init' ) );
		add_action( 'admin_notices', array( $this, 'check_plugins' ) );

	}

	/**
	 * On plugin activation
	 */
	public static function activated() {
	}

	/**
	 * On plugin deactivation we remove all the options
	 */
	public static function deactivated() {
	}

	/**
	 * Add an admin warning if Facebook for WooCommerce is active
	 */
	public function check_plugins() {

		$wooni_plugins = apply_filters('add_wooni_plugins', $this->wooni_plugins );

		?>

		<div class="notice notice-error error-alt">
			<p>Your are running Wooniversity Tools. Remember to deactivate when you are finished</p>
		</div>

        <?php foreach( $wooni_plugins as $plugin ): ?>

			<?php if ( ! is_plugin_active( $plugin['folder'] . '/' . $plugin['file'] ) ): ?>

                <div class="notice notice-error error-alt">
                    <p>Wooniversity Tools: <?php echo $plugin['message']; ?></p>
                </div>

			<?php endif;
        endforeach;

	}

}

add_action( 'init', array( 'Wooni\Wooniversity_Tools', 'init' ) );
register_deactivation_hook( __FILE__, array( 'Wooni\Wooniversity_Tools', 'deactivated' ) );
register_activation_hook( __FILE__, array( 'Wooni\Wooniversity_Tools', 'activated' ) );