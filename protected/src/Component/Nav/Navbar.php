<?php
namespace Project\Component\Nav;

use Sy\Bootstrap\Lib\Url;
use Sy\Bootstrap\Component\Icon;

class Navbar extends \Sy\Component\WebComponent {

	public function __construct() {
		parent::__construct();
		$this->mount(function () {
			$this->init();
		});
	}

	private function init() {
		$this->addTranslator(LANG_DIR);
		$this->setTemplateFile(TPL_DIR . '/Component/Nav/Navbar.html');
		$this->setVars([
			'WEB_ROOT' => WEB_ROOT,
			'PROJECT'  => PROJECT,
			'USER_CONNECT_URL' => Url::build('page', 'user-connection'),
		]);
		$this->setComponent('NAV', $this->menu());
		$this->setComponent('NAV_RIGHT', $this->menuRight());
	}

	private function menu() {
		$data = [
			'Home'     => ['icon' => new Icon('home'), 'page' => 'home'],
			'About' => [
				'icon' => new Icon('question-circle'),
				'menu' => [
					'About us' => ['icon' => new Icon('users'), 'page' => 'about-us'],
					'Conditions of use' => ['icon' => new Icon('info-circle'), 'page' => 'use'],
					'Privacy policy' => ['icon' => new Icon('shield'), 'page' => 'privacy'],
				],
			],
		];
		$menu = new \Sy\Bootstrap\Component\Nav\Menu($data);
		$menu->setAttribute('class', "me-auto");
		return $menu;
	}

	private function menuRight() {
		// Connection / User menu
		$service = \Sy\Bootstrap\Service\Container::getInstance();
		$user    = $service->user->getCurrentUser();
		if ($user->isConnected()) {
			$m = [
				'Account settings' => ['icon' => new Icon('gear'), 'page' => 'user-account'],
				'-',
				'Sign Out' => ['icon' => new Icon('power'), 'url' => Url::build('user', 'signOut')],
			];

			$name = htmlentities(trim($user->firstname . ' ' . $user->lastname), ENT_QUOTES, 'UTF-8');
			$data = [
				'<img class="rounded-circle" src="' . Url::avatar($user->email) . '" alt="' . $name . '" /> ' . $name => [
					'page' => 'user',
					'param' => ['id' => $user->id],
					'class' => 'dropdown-menu-end',
					'menu' => $m,
				],
			];
		} else {
			$data = ['Sign In' => ['icon' => new Icon('power'), 'page' => 'user-connection']];
		}

		$menu = new \Sy\Bootstrap\Component\Nav\Menu($data);
		return $menu;
	}

}