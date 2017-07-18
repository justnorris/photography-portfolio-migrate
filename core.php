<?php


use Phormig\Admin\Settings_Page;
use Phormig\Migrate\Migrate;
use Phormig\Migrate\Migration_Requirements;
use Phormig\Migrate\Requirements\Categories_To_migrate;
use Phormig\Migrate\Requirements\Posts_To_Migrate;

require_once PHORMIG_ABSPATH . 'admin/admin.php';


// Include Requirements
require_once PHORMIG_ABSPATH . 'migrate/Migrate.php';
require_once PHORMIG_ABSPATH . 'migrate/Migration_Requirements.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Requirement.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Posts_To_Migrate.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Categories_To_Migrate.php';


$settings = [

	'post_type'   => 'portfolio',
	'taxonomy'    => 'portfolio_category',
	'plugin'      => 'village-portfolio-post-type/village-portfolio-post-type.php',
	'gallery_key' => 'village_gallery',

];

/**
 * Boot
 */
$requirements = new Migration_Requirements(
	[
		new Posts_To_Migrate( $settings['post_type'] ),
		new Categories_To_migrate( $settings['taxonomy'] ),

	]
);


if ( is_admin() ) {
	$migration = new Migrate( $settings );

	if ( // Post request is set
		! empty( $_POST )

		// Has the correct admin referer nonce
		&& check_admin_referer( 'migrate_epp' )

		// Not doing ajax
		&& ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )

		// All the configuration requirements are met
		&& $requirements->all_requirements_met()
	) {
		$migration->migrate();
	}

	$settings_page = new Settings_Page( $requirements, $migration );
}
