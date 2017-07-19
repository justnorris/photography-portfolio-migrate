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

			$status              = $requirement->requirement_status;
			$icon                = $this->icon( $status );
			$requirement_classes = 'phormig-requirement';

			if ( $status === 'ignore' ) {
				return;
			}

			if ( $status != 'pass' ) {
				$requirement_classes .= ' phormig-requirement--' . $status;
			}

			echo "<div class='{$requirement_classes}'>";

			echo "<div class='phormig-requirement__title'>$icon {$requirement->title()}</div>";

			if ( $status != 'pass' ) {
				echo $requirement->instructions();
			}

			echo "</div>";
		}

	}


	public function icon( $status ) {

		switch ( $status ) {
			case 'pass':
				$icon = 'yes';
				break;

			case 'fail':
				$icon = 'no';
				break;

			case 'warn':
				$icon = 'warning';
				break;

			default:
				$icon = 'fail';
		}

		return "<span class='dashicons dashicons-{$icon}'></span>";

	}


	public function all_requirements_met() {


		foreach ( $this->requirements as $requirement ) {
			if ( $requirement->requirement_status == 'fail' ) {
				return false;
			}
		}

		return true;

	}
}