<?php
namespace Project\Application;

class Sitemap extends \Sy\Bootstrap\Application\Sitemap {

	public function __construct() {
		parent::__construct();
		$this->addProvider(new \Sy\Bootstrap\Application\Sitemap\Page());
	}

}