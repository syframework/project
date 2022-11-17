<?php
namespace Project\Application;

class Page extends \Sy\Bootstrap\Application\Page {

	/**
	 * User settings page
	 */
	public function userAccountAction() {
		$service = \Sy\Bootstrap\Service\Container::getInstance();
		$user = $service->user->getCurrentUser();
		if (!$user->isConnected() or $user->hasRole('blacklisted')) {
			$this->redirect(WEB_ROOT . '/');
		}
		$sections = [
			'index'  => $this->_('Account informations'),
			'change' => $this->_('Change password'),
			'delete' => $this->_('Delete account'),
		];
		$nav = new \Sy\Component\Html\Navigation();
		$nav->setAttribute('class', 'nav nav-pills flex-column');
		foreach ($sections as $id => $label) {
			$active = $id == $this->get('s', 'index') ? 'active' : '';
			$i = $nav->addItem($label, Url::build('page', 'user-account', array('s' => $id)), ['class' => "nav-link $active"]);
			$i->setAttribute('class', 'nav-item');
		}
		$this->setContentVars([
			'TITLE'   => $sections[$this->get('s', 'index')],
			'NAV'     => $nav,
			'CONTENT' => new \Project\Component\User\AccountPanel(),
		]);
	}

	protected function preInit() {
		// Head data
		$this->setMeta('viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no');
		$this->setMeta('mobile-web-app-capable', 'yes');
		$this->setMeta('apple-mobile-web-app-capable', 'yes');
		$this->setMeta('X-UA-Compatible', 'IE=edge', true);
		$this->setMeta('application-name', PROJECT);
		$this->setMeta('apple-mobile-web-app-title', PROJECT);

		// Favicon
		$this->addLink(['rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => WEB_ROOT . '/assets/img/icons/apple-touch-icon.png']);
		$this->addLink(['rel' => 'icon', 'sizes' => '32x32', 'href' => WEB_ROOT . '/assets/img/icons/favicon-32x32.png']);
		$this->addLink(['rel' => 'icon', 'sizes' => '16x16', 'href' => WEB_ROOT . '/assets/img/icons/favicon-16x16.png']);
		$this->addLink(['rel' => 'manifest', 'href' => WEB_ROOT . '/site.webmanifest']);
		$this->addLink(['rel' => 'mask-icon', 'href' => WEB_ROOT . '/assets/img/icons/safari-pinned-tab.svg', 'color' => '#3A5199']);
		$this->setFavicon(WEB_ROOT . '/assets/img/icons/favicon.ico');
		$this->setMeta('msapplication-TileColor', '#3A5199');
		$this->setMeta('msapplication-config', WEB_ROOT . '/browserconfig.xml');
		$this->setMeta('theme-color', '#3A5199');

		// Application css
		$this->addCssLink(WEB_ROOT . '/assets/css/app.css');

		// Application js
		$this->addJsLink(WEB_ROOT . '/assets/js/app.js');

		// Navbar menu
		$this->setLayoutVars(['_NAV' => new \Project\Component\Nav\Navbar()]);
	}

	protected function postInit() {
	}

}