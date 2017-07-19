<?php


namespace Phormig\Migrate\Requirements;


class EPP_Active extends Requirement {
	/**
	 * @var
	 */
	private $post_type;


	public function instructions() {

		return "
		<div class='phormig-condition-unmet'>
			You must have <b>Easy Photography Portfolio</b> installed and active
		</div>
		";


	}


	public function title() {


		return "<b>Easy Photography Portfolio</b> plugin is active";
	}


	public function check() {


		return (
			post_type_exists( 'phort_post' )
			&&
			class_exists( 'Colormelon_Photography_Portfolio' )
			&&
			is_plugin_active( 'photography-portfolio/photography-portfolio.php' )
		);
	}

}