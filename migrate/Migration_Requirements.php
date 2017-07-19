<?php


namespace Phormig\Migrate;


class Migration_Requirements {
	/**
	 * @var array
	 */
	private $requirements;


	/**
	 * Migration_Requirements constructor.
	 */
	public function __construct( array $requirements ) {


		$this->requirements = $requirements;
	}


	public function show_requirements() {


		foreach ( $this->requirements as $requirement ) {

			$requirement_passes  = $requirement->requirement_is_met;
			$icon                = $this->icon( $requirement_passes );
			$requirement_classes = 'phormig-requirement';

			if ( ! $requirement_passes ) {
				$requirement_classes .= ' phormig-requirement--failed';
			}

			echo "<div class='{$requirement_classes}'>";

			echo "<div class='phormig-requirement__title'>$icon {$requirement->title()}</div>";

			if ( ! $requirement_passes ) {
				echo $requirement->instructions();
			}

			echo "</div>";
		}

	}


	public function icon( $bool ) {

		if ( $bool ) {
			return '<span class="dashicons dashicons-yes"></span>';
		}

		return '<span class="dashicons dashicons-no"></span>';

	}


	public function all_requirements_met() {


		foreach ( $this->requirements as $requirement ) {
			if ( $requirement->requirement_is_met !== true ) {
				return false;
			}
		}

		return true;

	}
}