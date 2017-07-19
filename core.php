<?php


use Phormig\Admin\Settings_Page;
use Phormig\Migrate\Migrate;
use Phormig\Migrate\Migration_Requirements;
use Phormig\Migrate\Requirements\Categories_To_migrate;
use Phormig\Migrate\Requirements\EPP_Active;
use Phormig\Migrate\Requirements\Old_Portfolio_Plugin_Is_Active;
use Phormig\Migrate\Requirements\Posts_To_Migrate;


define( 'PHORMIG_NONCE_KEY', 'migrate_epp' );

$settings = apply_filters(
	'phormig_settings',
	[

		'post_type'   => 'portfolio',
		'taxonomy'    => 'portfolio_category',
		'plugin'      => 'village-portfolio-post-type/village-portfolio-post-type.php',
		'gallery_key' => 'village_gallery',
		'plugin_name' => 'Village Portfolio Post Type',

	]
);


require_once PHORMIG_ABSPATH . 'admin/admin.php';


// Include Requirements
require_once PHORMIG_ABSPATH . 'migrate/Migrate.php';
require_once PHORMIG_ABSPATH . 'migrate/Migration_Requirements.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Requirement.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/EPP_Active.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Old_Portfolio_Plugin_Is_Active.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Posts_To_Migrate.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Categories_To_Migrate.php';

/**
 * Check the requirements
 */
$requirements = new Migration_Requirements(
	[
		new EPP_Active(),
		new Old_Portfolio_Plugin_Is_Active( $settings ),
		new Posts_To_Migrate( $settings['post_type'] ),
		new Categories_To_migrate( $settings['taxonomy'] ),

	]
);

/**
 * Create migration instance
 */
$migration = new Migrate( $settings );


/*
 * Check for $_POST Requests
 */
if (
	$requirements->all_requirements_met()
	&&
	$migration->valid_post_request()
) {
	$migration->migrate();
}


/**
 * Create a settings page
 */
$settings_page = new Settings_Page( $requirements, $migration );
