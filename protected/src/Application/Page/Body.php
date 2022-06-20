<?php
namespace Project\Application\Page;

use Sy\Bootstrap\Lib\Url;

class Body extends \Sy\Bootstrap\Application\Page\Body {

	/**
	 * User settings page
	 */
	public function user_account() {
		$service = \Sy\Bootstrap\Service\Container::getInstance();
		$user = $service->user->getCurrentUser();
		if (!$user->isConnected() or $user->hasRole('blacklisted')) $this->redirect(WEB_ROOT . '/');
		$sections = [
			'index'  => $this->_('Account informations'),
			'change' => $this->_('Change password'),
			'delete' => $this->_('Delete account')
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

	/**
	 * Return navigation menu, can return null
	 *
	 * @return \Sy\Bootstrap\Component\Nav\Menu
	 */
	protected function _menu() {
		return new \Project\Component\Nav\Navbar();
	}

}