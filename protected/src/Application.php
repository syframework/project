<?php
namespace Project;

use Sy\Bootstrap\Lib\Url;
use Sy\Bootstrap\Lib\Icon\PolicyManager;
use Sy\Bootstrap\Lib\Icon\SvgPolicy;

class Application extends \Sy\Bootstrap\Application {

	public function __construct() {
		parent::__construct();

		// Icon configuration
		PolicyManager::addPolicy(new SvgPolicy(SVG_ICON_DIR));
	}

	protected function initUrlConverter() {
		Url\AliasManager::setAliasFile(__DIR__ . '/../conf/alias.php');
		Url::addConverter(new Url\AliasConverter());
		Url::addConverter(new Url\ControllerActionConverter());
	}

}