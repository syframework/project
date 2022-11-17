<?php
namespace Project\Db;

/**
 * Database layer services container.
 *
 * @method static Container getInstance()
 */
class Container extends \Sy\Bootstrap\Db\Container {

	public function __construct() {
		parent::__construct();

		/**
		 * Declare your database services here:
		 *
		 * $this->myTable = function () {
		 *     return new MyTable();
		 * }
		 */
	}

}