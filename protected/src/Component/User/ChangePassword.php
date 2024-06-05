<?php
namespace Project\Component\User;

class ChangePassword extends \Sy\Bootstrap\Component\Form {

	public function init() {
		$this->addCsrfField();

		$f = $this->addFieldset();

		$this->addPassword(
			[
				'name'         => 'old_password',
				'required'     => 'required',
				'autocomplete' => 'current-password',
			],
			[
				'label'     => 'Old password',
				'validator' => function($value, $element) {
					$service = \Project\Service\Container::getInstance();
					$user = $service->user->getCurrentUser();
					if ($service->user->passwordVerify($value, $user->password)) {
						return true;
					}
					$element->setError('Password error');
					return false;
				},
			],
			$f
		);

		$this->addPassword(
			[
				'name'         => 'new_password',
				'required'     => 'required',
				'autocomplete' => 'new-password',
				'minlength'    => 8,
				'maxlength'    => 128,
			],
			[
				'label'     => 'New password',
			],
			$f
		);

		$this->addPassword(
			[
				'name'         => 'new_password_bis',
				'required'     => 'required',
				'autocomplete' => 'new-password',
				'minlength'    => 8,
				'maxlength'    => 128,
			],
			[
				'label' => 'Confirm new password',
				'validator' => function($value, $element) {
					$password = $this->post('new_password');
					if (!empty($value) and $value === $password) {
						return true;
					}
					$element->setError('Password error');
					return false;
				},
			]
		);

		$this->addButton('Save', ['type' => 'submit'], ['color' => 'primary', 'icon' => 'save']);
	}

	public function submitAction() {
		try {
			$this->validatePost();
			$service = \Project\Service\Container::getInstance();
			$user = $service->user->getCurrentUser();
			$service->user->update(['id' => $user->id], ['password' => password_hash($this->post('new_password'), PASSWORD_DEFAULT)]);
			$service->user->signIn($user->email, $this->post('new_password'));
			return $this->jsonSuccess('Password changed successfully');
		} catch (\Sy\Component\Html\Form\Exception $e) {
			$this->logWarning($e);
			return $this->jsonError($this->getOption('error') ?? 'Please fill the form correctly');
		} catch (\Sy\Bootstrap\Service\User\Exception $e) {
			$this->logWarning($e->getMessage());
			return $this->jsonError('An error occured');
		}
	}

}