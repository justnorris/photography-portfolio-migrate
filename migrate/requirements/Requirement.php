<?php


namespace Phormig\Migrate\Requirements;


abstract class Requirement {

	public $requirement_is_met = false;


	/**
	 * Requirement constructor.
	 */
	public function __construct() {

		$this->requirement_is_met = $this->check();
	}


	abstract public function check();


	abstract public function title();


}