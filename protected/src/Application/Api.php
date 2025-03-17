<?php
namespace Project\Application;

use Sy\Bootstrap\Lib\Str;

class Api extends \Sy\Bootstrap\Application\Api {

	/**
	 * Add "foo/bar" to this white list if you want to remove security check for endpoint "/api/foo/bar"
	 *
	 * @var array
	 */
	private $whiteList = [];

	public function security() {
		// White list
		$action = $this->action;
		$method = $this->method;
		if (in_array("$action/$method", $this->whiteList)) return true;
		return parent::security();
	}

	/**
	 * Example: create a new endpoint "/api/foo"
	 *
	 * public function fooAction() {
	 *     $this->ok();
	 * }
	 */

}