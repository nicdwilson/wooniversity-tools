<?php
/**
 * Plugin Name: Wooniversity Tools
 * Plugin URI: https://wooniversity.wordpress.com/
 * Description: Proof of concept training tool
 * Version: 1.0.0
 * Author: nicw
 * Author URI: https://woocommerce.com
 * Text Domain: wooniversity
 * Requires at least: 5.6
 * Requires PHP: 7.0
 *
 * @package WooCommerce
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

		$this->load_assets();

		add_action( 'admin_menu', array( 'Wooni\Menu', 'init' ) );
		add_action( 'admin_notices', array( $this, 'check_plugins' ) );
		add_filter( 'add_wooni_plugins', array( $this, 'add_plugins' ) );
		add_filter( 'add_scenario_menus', array( $this, 'add_scenario_menus' ) );

	}

	/**
	 * Load scripts and CSS
	 */
	public function load_assets() {

		wp_register_script( 'wooni-tools',
			plugin_dir_url( __FILE__ ) . 'assets/wooni-tools.js',
			array( 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/wooni-tools.js' ),
			true
		);

		wp_localize_script(
			'wooni-tools',
			'wooniAjax',
			array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( "wooniversity_tools" )
			)
		);

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wooni-tools' );
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

		$wooni_plugins  = apply_filters( 'add_wooni_plugins', $this->wooni_plugins );
		$current_action = $this->get_current_action();

		?>

		<?php if ( $current_action ): ?>

            <div class="notice notice-error error-alt">
                <p>Your are running a Wooniversity Tools Scenario. Something on your site is broken until you fix it
                    (todo current action title:,<?php echo $current_action; ?>)</p>
            </div>


		<?php else: ?>

            <div class="notice notice-error error-alt">
                <p>Your are running Wooniversity Tools. No scenarios are in play. Remember to deactivate when you are
                    finished</p>
            </div>

		<?php endif; ?>


		<?php foreach ( $wooni_plugins as $plugin ): ?>

			<?php if ( ! is_plugin_active( $plugin['folder'] . '/' . $plugin['file'] ) ): ?>

                <div class="notice notice-error error-alt">
                    <p>Wooniversity Tools: <?php echo $plugin['message']; ?></p>
                </div>

			<?php endif;
		endforeach;

	}

	/**
	 * Register which action is currently running
	 *
	 * @param $action
	 */
	public function register_current_action( $action ) {
		update_option( 'woonitools_running_action', $action );
	}

	/**
	 * Check if a Wooni Tools action is currently running
	 * todo grab current action data
	 *
	 * @return false|mixed|void
	 */
	public function get_current_action() {

		$current_action = get_option( 'woonitools_running_action', false );

		return $current_action;
	}

	public function register_tools( $tool_slug = '' ) {

		if ( $tool_slug === '' ) {
			return false;
		}

		$tools = $this->get_tools();

		$tools['slugs'][] = $tool_slug;
		$tools['slugs']   = array_unique( $tools['slugs'] );

		return update_option( 'woonitools', $tools );
	}

	public function register_scenarios( $scenario_data = array() ) {

		if ( $scenario_data === array() ) {
			return false;
		}

		$tool_data = $this->get_scenario_data();

		$tool_data[ $scenario_data['plugin-folder'] ] = $scenario_data;

		update_option( 'woonitool_data', $tool_data, false );
	}

	/**
	 * Add the plugin setup notices. If the plugin is missing, this lets the user know they'll need
	 * to add and configure the plugin before running the scenarios for this plugin
	 *
	 * @param array $wooni_plugins
	 *
	 * @return array
	 */
	public function add_plugins( $wooni_plugins ) {

		$tool_data = $this->get_scenario_data();

		foreach ( $tool_data as $plugin ) {

			$wooni_plugins[] = array(
				'folder'  => $plugin['plugin-folder'],
				'file'    => $plugin['plugin-file'],
				'message' => $plugin['plugin-message']
			);
		}

		return $wooni_plugins;

	}

	public function add_scenario_menus( $menu_data ){

		$tool_data = $this->get_scenario_data();

		$menu_data = array();

		foreach ( $tool_data as $tool_set => $scenarios ) {

			foreach( $scenarios['scenarios'] as $scenario ){
			    $menu_data[$tool_set][] = $scenario;
			}
		}

		return $tool_data;

	}

	private function get_scenario_data() {

		$tool_data = get_option( 'woonitool_data', array() );

		if ( ! is_array( $tool_data ) ) {
			$tool_data = array();
		}

		return $tool_data;
	}

	private function get_tools() {

		$tools = get_option( 'woonitools', array() );

		if ( ! is_array( $tools ) ) {
			$tools = array();
		}

		return $tools;
	}

	private function set_tools( $data = array() ) {
		update_option( 'woonitools', $data );
	}


}

add_action( 'init', array( 'Wooni\Wooniversity_Tools', 'init' ) );
register_deactivation_hook( __FILE__, array( 'Wooni\Wooniversity_Tools', 'deactivated' ) );
register_activation_hook( __FILE__, array( 'Wooni\Wooniversity_Tools', 'activated' ) );