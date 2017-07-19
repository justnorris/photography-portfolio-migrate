<?php

namespace Phormig\Admin;


use Phormig\Migrate\Migrate;
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
	 * @var Migrate
	 */
	private $migrate;


	/**
	 * Start up
	 */
	public function __construct( Migration_Requirements $requirements, Migrate $migrate ) {

		add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );


		$this->requirements = $requirements;


		$this->migrate = $migrate;
	}


	/**
	 * Add options page
	 */
	public function add_plugin_page() {

		// This page will be under "Settings"
		$submenu = add_submenu_page(
			'tools.php',
			'Migrate Easy Photography Portfolio',
			'Migrate Portfolio',
			'manage_options',
			'photography-portfolio-migrate',
			[ $this, 'create_admin_page' ]
		);

		add_action( 'admin_head-' . $submenu, [ $this, 'admin_style' ] );
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
			<div class="eppmig-success eppmig-panel">
				<h1>Success!</h1>
				<p>
					<b>Migration was successful!</b><br>
				</p>

				<h2>Final Steps</h2>
				<p>
					Now that the portfolio data has been migrated. Here are a few next steps
				</p>
				<ol>
					<li> Disable and Delete <b><?php echo apply_filters( 'eppmig_plugin_name', 'the old portfolio' ) ?></b> plugin
					<li> Disable and Delete <b>Easy Photography Portfolio: Migrate</b> plugin
					<li> Reset your permalinks. Go to <a href="<?php echo admin_url( 'options-permalink.php' ) ?>">Settings &rarr; Permalinks</a>
						and
						change the URL structure, save changes, and then change the URL structure back to where it was before
				</ol>

				<b>That's it! Enjoy the new Easy Photography Portfolio plugin!</b>
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

			<div class="eppmig-migration-instructions eppmig-panel">

				<h2>⚠️ Backup your database!</h2>
				<p>
					Before you start the migration, it's highly recommended that you <b>backup your database first!</b><br>
					Don't worry, it's highly unlikely that something will go wrong, but it's better to be safe than sorry!<br>
				</p>
				<p>
					Install a plugin like <a href="https://wordpress.org/plugins/backwpup/">BackWPup</a> and backup your database.
					You can watch a complete <a href="http://go.colormelon.com/database-backup-tutorial">video tutorial
						guide on YouTube</a> how to use the plugin.
				</p>

				<p>
					Note, that you <b>don't have to backup files</b> - this migration isn't going to alter the files (images) in any way, it's
					only going to the database entries to Easy Photography Portfolio format.
				</p>

			</div>

			<div class="eppmig-requirements eppmig-panel">

				<h2>Checking migration requirements...</h2>
				<?php $this->requirements->show_requirements(); ?>


				<?php if ( $this->requirements->all_requirements_met() ): ?>

					<div class="eppmig-requirements__ready">
						<p>
							Looks like everything is OK! Click the button below to begin migrating your posts!
						</p>
					</div>

					<form id="eppmig-migrate" method="post" action="tools.php?page=photography-portfolio-migrate">
						<?php wp_nonce_field( PHORMIG_NONCE_KEY ); ?>
						<?php submit_button( 'Migrate to Easy Photography Portfolio', 'button-primary button-hero' ); ?>
					</form>

					<script>
                        document.getElementById( 'eppmig-migrate' ).addEventListener( 'submit', function () {
                            var button = this.querySelector( '.button' )
                            button.setAttribute( 'disabled', true )
                            button.setAttribute( 'value', 'Migrating! Wait until the migration is complete...' )
                        } )
					</script>
				<?php else: ?>
					<a class="button-primary button-hero button-large disabled">Migrate to Easy Photography Portfolio</a>
				<?php endif; ?>
			</div> <!-- .eppmig-requirements -->

		</div> <!-- wrap -->
		<?php
	}


	function admin_style() {

		?>
		<style>
			.eppmig-panel {
				background-color: white;
				padding: 2rem;
				margin-bottom: 2rem;
				margin-top: 2rem;
			}

			.eppmig-panel li {
				margin-bottom: .5rem;
			}

			.phormig-requirement {
				padding: 1rem;
				margin-bottom: .75rem;
			}

			.phormig-requirement--failed {
				background-color: #f7f7f7;
				padding-bottom: 0;
			}

			.phormig-requirement__title {
				font-size: 1.05rem;
			}

			.phormig-condition-unmet {
				padding: 1rem;
				margin-left: .95rem;
			}

			.phormig-condition-unmet ol {
				margin: 0;
			}


			.phormig-requirement .dashicons {
				font-size: 1.75rem;
				line-height: 1.2rem;
				margin-right: .5rem;
			}
			.phormig-requirement .dashicons-yes {
				color: green;
			}

			.phormig-requirement .dashicons-no {
				color: red;
			}


		</style><?php
	}
}
