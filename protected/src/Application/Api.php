<?php

namespace Project\Application;

class Api extends \Sy\Bootstrap\Application\Api {

	public function dispatch() {
		parent::dispatch();

		// If no action method found, check if an api class exists in Api folder
		$c = $this->request(ACTION_TRIGGER);
		if (is_null($c)) return;
		$class = 'Project\\Application\\Api\\' . ucfirst($c);
		if (class_exists($class)) new $class();
	}

}