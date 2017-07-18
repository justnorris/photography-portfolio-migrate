<?php


use Phormig\Admin\Settings_Page;
use Phormig\Migrate\Migration_Requirements;
use Phormig\Migrate\Requirements\Posts_To_Migrate;

require_once PHORMIG_ABSPATH . 'admin/admin.php';


// Include Requirements
require_once PHORMIG_ABSPATH . 'migrate/Migration_Requirements.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Requirement.php';
require_once PHORMIG_ABSPATH . 'migrate/requirements/Posts_To_Migrate.php';


$settings = [

	'post_type' => 'portfolio',


];

/**
 * Boot
 */
$requirements = new Migration_Requirements(
	[
		new Posts_To_Migrate( $settings['post_type'] ),
	]
);


if ( is_admin() ) {
	$settings_page = new Settings_Page( $requirements );
}
