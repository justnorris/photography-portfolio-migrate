<?php


namespace Phormig\Migrate\Requirements;


abstract class Requirement {

	public $requirement_status = 'fail';


	/**
	 * Requirement constructor.
	 */
	public function __construct() {

		$this->requirement_status = $this->check();
	}


	abstract public function check();


	abstract public function title();


	public function bool( $val ) {

		if ( $val ) {
			return $this->pass();
		}

		return $this->fail();

	}


	public function pass() {

		return 'pass';
	}


	public function fail() {

		return 'fail';
	}


	public function warn() {

		return 'warn';
	}


	public function ignore() {

		return 'ignore';
	}
}