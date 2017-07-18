<?php

namespace Phormig\Admin;


use Phormig\Migrate\Migration_Requirements;

class Settings_Page {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	/**
	 * @var Migration_Requirements
	 */
	private $requirements;


	/**
	 * Start up
	 */
	public function __construct( Migration_Requirements $requirements ) {

		add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
		$this->requirements = $requirements;

		if ( ! empty( $_POST ) && check_admin_referer( 'migrate_epp' ) ) {
			echo "<h1>Yeehah, do migration...</h1>";
		}
	}


	/**
	 * Add options page
	 */
	public function add_plugin_page() {

		// This page will be under "Settings"
		add_submenu_page(
			'tools.php',
			'Migrate Easy Photography Portfolio',
			'Migrate Portfolio',
			'manage_options',
			'photography-portfolio-migrate',
			[ $this, 'create_admin_page' ]
		);
	}


	/**
	 * Options page callback
	 */
	public function create_admin_page() {

		?>
		<div class="wrap">
			<h1>Easy Photography Portfolio: Migrate</h1>

			<br>

			<div class="eppmig-requirements">
				<h2>Requirements</h2>

				<?php $this->requirements->show_requirements(); ?>


				<?php if ( $this->requirements->all_requirements_met() ): ?>
					<form method="post" action="tools.php?page=photography-portfolio-migrate">
						<?php wp_nonce_field( 'migrate_epp' ); ?>
						<?php submit_button( 'Migrate' ); ?>
					</form>
				<?php else: ?>
					<a class="button-primary disabled">Migrate</a>
				<?php endif; ?>
			</div> <!-- .eppmig-requirements -->

		</div> <!-- wrap -->
		<?php
	}


}
