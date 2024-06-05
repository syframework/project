<?php
namespace Project\Component\User;

class DeleteAccount extends \Sy\Bootstrap\Component\Form\Crud\Delete {

	/**
	 * @var int
	 */
	private $userId;

	/**
	 * @param int $userId
	 */
	public function __construct($userId) {
		parent::__construct('user', ['id' => $userId]);
		$this->userId = $userId;
	}

	public function init() {
		$this->setOptions([
			'button-label' => 'Delete account',
			'confirm' => 'Delete your account?',
		]);
	}

	public function initInputs() {
		parent::initInputs();

		$this->addPassword(
			[
				'name'     => 'password',
				'required' => 'required',
				'autocomplete' => 'current-password',
			],
			[
				'label'     => 'Password',
				'validator' => function($value) {
					$service = \Project\Service\Container::getInstance();
					$user = $service->user->retrieve(['id' => $this->userId]);
					if (empty($user)) return false;
					return $service->user->passwordVerify($value, $user['password']);
				},
			]
		);
	}

	public function submitAction() {
		try {
			$this->validatePost();
			$service = \Project\Service\Container::getInstance();
			$service->user->delete(['id' => $this->userId]);
			$service->user->signOut();
			return $this->jsonSuccess('Account deleted', ['redirection' => PROJECT_URL]);
		} catch (\Sy\Component\Html\Form\Exception $e) {
			$this->logWarning($e);
			return $this->jsonError('An error occured');
		} catch (\Sy\Bootstrap\Service\User\Exception $e) {
			$this->logWarning($e->getMessage());
			return $this->jsonError('An error occured');
		}
	}

}