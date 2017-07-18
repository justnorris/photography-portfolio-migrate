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


	public function create_admin_page() {

		if ( ! empty( $_POST ) && check_admin_referer( 'migrate_epp' ) ) {
			$this->display_success_message();
		}
		else {
			$this->display_migration_form();
		}

	}


	public function display_success_message() {

		?>
		<div class="wrap">
			<div class="eppmig-success">
				<h1>Success!</h1>
				<p>
					<b>Migration was successful!</b><br>
				</p>

				<h2>Final Steps</h2>
				<p>
					Now that the portfolio data has been migrated, there are 2 tiny little steps:
				</p>
				<ol>
					<li> Delete the old portfolio plugin <?php do_action( 'eppmig_plugin_name' ) ?>
					<li> Reset your permalinks. Go to <a href="<?php echo admin_url( 'options-permalink.php' ) ?>">Settings &rarr; Permalinks</a>
						and
						change the URL structure, save changes, and then change the URL structure back to where it was before
				</ol>
			</div>

		</div>

		<?php

	}


	/**
	 * Options page callback
	 */
	public function display_migration_form() {

		?>
		<div class="wrap">
			<h1>Easy Photography Portfolio: Migrate</h1>

			<div class="eppmig-migration-instructions">

				<h2>Backup your database!</h2>
				<p>
					Before you start the migration, it's highly recommended that you <b>backup your database first!</b><br>
					Don't worry, it's highly unlikely that something will go wrong, but it's better to be safe than sorry!<br>

					<br>
					Install a plugin like <a href="https://wordpress.org/plugins/backwpup/">BackWPup</a> and backup your database.
					Here is a complete <a href="http://go.colormelon.com/database-backup-tutorial">video tutorial
						guide</a> how to use the plugin.

					<br>
					Note, that you <b>don't have to backup files</b> - this migration isn't going to alter the files in any way, it's
					going to convert only the database entries to Easy Photography Portfolio format.

				</p>

			</div>

			<div class="eppmig-requirements">

				<h2>Checking migration requirements...</h2>
				<?php $this->requirements->show_requirements(); ?>


				<?php if ( $this->requirements->all_requirements_met() ): ?>

					<div class="eppmig-requirements__ready">
						<p>
							Ready to migrate when you are. Click the button below to begin migrating your posts!
						</p>
					</div>

					<form id="eppmig-migrate" method="post" action="tools.php?page=photography-portfolio-migrate">
						<?php wp_nonce_field( 'migrate_epp' ); ?>
						<?php submit_button( 'Migrate to Easy Photography Portfolio', 'button-primary button-hero' ); ?>
					</form>

					<script>
                        document.getElementById( 'eppmig-migrate' ).addEventListener( 'submit', function () {
                            this.querySelector( '.button' ).setAttribute( 'disabled', true )
                        } )
					</script>
				<?php else: ?>
					<a class="button-primary button-hero button-large disabled">Migrate to Easy Photography Portfolio</a>
				<?php endif; ?>
			</div> <!-- .eppmig-requirements -->

		</div> <!-- wrap -->
		<?php
	}


}
