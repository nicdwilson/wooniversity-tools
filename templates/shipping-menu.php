<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Variables
 *
 * $scenario_menus - contains required menu data for scenarios
 */


?>

<div class="wrap wooni-shipping-tools">

    <div id="icon-themes" class="icon32"></div>
    <h2>Shipping scenarios</h2>

	<?php $active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'shipping'; ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=wooniversity-tools-shipping&tab=shipping"
           class="nav-tab <?php echo 'shipping' === $active_tab ? 'nav-tab-active' : ''; ?>">
            General shipping scenarios
        </a>

		<?php foreach ( $scenario_menus as $scenario_plugin ): ?>

            <a href="?page=<?php echo $scenario_plugin['menu-parent-slug']; ?>&tab=<?php echo $scenario_plugin['menu-tab-slug']; ?>"
               class="nav-tab <?php echo $scenario_plugin['menu-tab-slug'] === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php echo $scenario_plugin['menu-tab-title']; ?>
            </a>

		<?php endforeach; ?>
    </h2>

    <form method="post" action="options.php">

		<?php foreach ( $scenario_menus as $scenario ): ?>

		<?php if ( $scenario['menu-tab-slug'] === $active_tab ) : ?>

		<?php foreach ( $scenario['scenarios'] as $item ): ?>

        <div class="wrap">
            <h3><?php echo $item['scenario-title']; ?></h3>

            <div id="<?php echo $item['scenario-id']; ?>-prompt" class="wooni-scenario-prompt" style="display: none;">
                <p>
					<?php echo $item['scenario-prompt']; ?>
                </p>
            </div>

            <div id="<?php echo $item['scenario-id']; ?>-hint" class="wooni-scenario-hint" style="display: none;">
                <p>
					<?php echo $item['scenario-hint']; ?>
                </p>
            </div>

            <div id="<?php echo $item['scenario-id']; ?>-solution" class="wooni-scenario-solution" style="display: none;">
                <p>
					<?php echo $item['scenario-solution']; ?>
                </p>
            </div>

            <button id="<?php echo $item['scenario-id']; ?>-button"
                    data-wooni-id="<?php echo $item['scenario-id']; ?>"
                    data-wooni-action="<?php echo $item['scenario-action']; ?>"
                    href="" class="wooni-scenario-action">Do scenario
            </button>

            <button id="<?php echo $item['scenario-id']; ?>-hint-button"
                    data-wooni-id="<?php echo $item['scenario-id']; ?>"
                    data-wooni-action=""
                    href="" class="wooni-scenario-hint">Gimme a hint
            </button>

            <button id="<?php echo $item['scenario-id']; ?>-solution-button"
                    data-wooni-id="<?php echo $item['scenario-id']; ?>"
                    data-wooni-action=""
                    href="" class="wooni-scenario-solution">Show solution
            </button>

			<?php endforeach; ?>

			<?php endif;
			endforeach;

			if ( 'shipping' === $active_tab ) : ?>

				<?php do_settings_sections( 'wooniversity-tools-shipping' ); ?>

			<?php endif; ?>

			<?php submit_button(); ?>

    </form>

</div>
