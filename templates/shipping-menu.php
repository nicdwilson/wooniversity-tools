<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variabkles
 *
 * $scenario_menus - contains required menu data for scenarios
 */
var_dump( $scenario_menus );

?>

<div class="wrap">

    <div id="icon-themes" class="icon32"></div>
    <h2>Shipping scenarios</h2>

	<?php $active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'shipping'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=wooniversity-tools-shipping&tab=shipping"  class="nav-tab <?php echo 'shipping' === $active_tab ? 'nav-tab-active' : ''; ?>">
            General shipping scenarios
        </a>

        <?php foreach ($scenario_menus as $scenario ): ?>
        <a href="?page=<?php echo $scenario['parent-slug']; ?>&tab=<?php echo $scenario['tab-slug']; ?>" class="nav-tab <?php echo $scenario['tab-slug'] === $active_tab ? 'nav-tab-active' : ''; ?>">
            <?php echo $scenario['tab-title']; ?>
        </a>
        <?php endforeach; ?>
    </h2>

        <form method="post" action="options.php">

            <?php if ( 'usps' === $active_tab ) : ?>

		        <?php settings_fields( 'woocommerce_shipping_usps' ); ?>
		        <?php do_settings_sections( 'woocommerce-shipping-usps' ); ?>

	        <?php endif; ?>

			<?php if ( 'shipping' === $active_tab ) : ?>

				<?php settings_fields( 'woocommerce_shipping' ); ?>
				<?php do_settings_sections( 'woocommerce-shipping' ); ?>

			<?php endif; ?>

			<?php submit_button(); ?>

    </form>

</div>
