<?php
namespace Project\Service;

/**
 * Service container.
 *
 * @method static Container getInstance()
 */
class Container extends \Sy\Bootstrap\Service\Container {

	public function __construct() {
		parent::__construct();

		/**
		 * Declare your services here:
		 *
		 * $this->myService = function () {
		 *     return new MyService();
		 * }
		 */
	}

}