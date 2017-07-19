<?php


namespace Phormig\Migrate\Requirements;


class Old_Portfolio_Plugin_Is_Active extends Requirement {
	/**
	 * @var
	 */
	private $settings;


	/**
	 * EPP_Active constructor.
	 *
	 * @param $settings
	 */
	public function __construct( $settings ) {

		$this->settings = $settings;
		parent::__construct();
	}


	public function instructions() {

		return "
		<div class='phormig-condition-unmet'>
			Plugin <b>{$this->settings['plugin_name']}</b> must be active to migrate posts
		</div>
		";


	}


	public function title() {


		return "<b>{$this->settings['plugin_name']}</b> plugin is active";
	}


	public function check() {

		return $this->bool(
			(
				empty( $this->settings['plugin'] )
				||
				is_plugin_active( $this->settings['plugin'] )
			)
		);
	}

}