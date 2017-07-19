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

function phortmig_auto_deactivate() {

	include( ABSPATH . "wp-includes/pluggable.php" );

	if ( current_user_can( 'activate_plugins' ) ) {

		// Deactivate Plugin
		add_action(
			'admin_init',
			function () {

				deactivate_plugins( plugin_basename( __FILE__ ) );
			}
		);

		// Show message
		add_action(
			'admin_notices',
			function () {

				echo '<div class="error">';
				echo '<p>You have to activate "Easy Photography Portfolio" before activating "Easy Photography Portfolio: Migrate" plugin. Please try again.</p>';
				echo '</div>';
			}
		);

	}


}

function phormig_initialize() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! class_exists( 'Colormelon_Photography_Portfolio' ) ) {
		phortmig_auto_deactivate();

		return;
	}


	// Initialize Core
	require_once PHORMIG_ABSPATH . 'core.php';

}


add_action( 'init', 'phormig_initialize' );