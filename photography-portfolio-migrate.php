<?php
/**
 * @package           Photography portfolio: Migration
 * @link              http://colormelon.com
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Photography Portfolio: Migration
 * Plugin URI:        http://colormelon.com/plugins/photography-portfolio
 * Description:       Migrate your portfolio posts to Easy Photography Portfolio
 * Version:           1.0.0
 * Author:            Colormelon
 * Author URI:        http://colormelon.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       phort-migrate
 */
/**
 * This file should work without errors on PHP 5.2.17
 * Use this instead of __DIR__
 */
$__DIR = dirname( __FILE__ );


/**
 * Define Constants
 */
define( 'PHORMIG_ABSPATH', $__DIR . '/' );
define( 'PHORMIG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PHORMIG_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );


function phormig_initialize() {

	if ( ! class_exists( 'Colormelon_Photography_Portfolio' ) ) {

		include( ABSPATH . "wp-includes/pluggable.php" );

		function phortmig_auto_deactivate() {

			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		if ( current_user_can( 'activate_plugins' ) ) {
			add_action( 'admin_init', 'phortmig_auto_deactivate' );
			add_action(
				'admin_notices',
				function () {

					echo '<div class="error">';
					echo wp_kses_post(
						__(
							'<p>You have to activate "Easy Photography Portfolio" before activating "Easy Photography Portfolio: Migrate" plugin. Please try again.</p>'
							,
							'photography-portfolio-migrate'
						)
					);
					echo '</div>';
				}
			);

		}
	}


	else {
		require_once PHORMIG_ABSPATH . 'core.php';
	}
}


add_action( 'after_setup_theme', 'phormig_initialize', 250 );