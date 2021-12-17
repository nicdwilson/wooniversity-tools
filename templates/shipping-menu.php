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

	        <?php foreach ($scenario_menus as $scenario ): ?>

            <?php if ( $scenario['tab-slug'] === $active_tab ) : ?>

		        <?php foreach( $scenario['scenarios'] as $item ): ?>

                    <div class="wrap">
                        <h3><?php echo $item['title']; ?></h3>

                    <div id="<?php echo $item['id']; ?>-prompt" style="display: none;">
                        <p>
		                    <?php echo $item['prompt']; ?>
                        </p>
                    </div>

                    <a id="<?php echo $item['id']; ?>" data-wooni-action="<?php echo $item['action']; ?>" href="" class="wooni-scenario-action">Do scenario</a>

                <?php endforeach; ?>

	        <?php endif;
            endforeach;

			if ( 'shipping' === $active_tab ) : ?>

				<?php do_settings_sections( 'wooniversity-tools-shipping' ); ?>

			<?php endif; ?>

			<?php submit_button(); ?>

    </form>

</div>
