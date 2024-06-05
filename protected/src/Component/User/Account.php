<?php
namespace Project\Component\User;

class Account extends \Sy\Bootstrap\Component\Form {

	public function init() {
		$f = $this->addFieldset();

		$service = \Project\Service\Container::getInstance();
		$user = $service->user->getCurrentUser();

		$this->addCsrfField();

		// E-mail
		$this->addTextInput([
			'name'      => 'email',
			'value'     => $user->email,
			'required'  => 'required',
			'maxlength' => 64,
			'readonly'  => 'readonly',
		], [
			'label' => 'E-mail',
		], $f);

		// Firstname
		$this->addTextInput([
			'name'      => 'firstname',
			'value'     => $user->firstname,
			'required'  => 'required',
			'maxlength' => 64,
		], [
			'label' => 'Firstname',
		], $f);

		// Lastname
		$this->addTextInput([
			'name'      => 'lastname',
			'value'     => $user->lastname,
			'maxlength' => 64,
		], [
			'label' => 'Lastname',
		], $f);

		// Description
		$this->addTextarea([
			'name'      => 'description',
			'maxlength' => 500,
		], [
			'label' => 'Description',
		], $f)->addText($user->description);

		// Language
		$this->addSelect([
			'name'     => 'language',
			'required' => 'required',
		], [
			'label'    => 'Language',
			'options'  => LANGS,
			'selected' => $user->language,
			'validator' => [
				function($value) {
					$res = in_array($value, array_keys(LANGS));
					if (!$res) {
						$this->setError($this->_('Language error'));
					}
					return $res;
				},
			],
		], $f);

		$this->addButton('Save', ['type' => 'submit'], ['color' => 'primary', 'icon' => 'save']);
	}

	public function submitAction() {
		try {
			$this->validatePost();
			$service = \Project\Service\Container::getInstance();
			$user = $service->user->getCurrentUser();
			$service->user->update(['id' => $user->id], [
				'firstname'   => $this->post('firstname'),
				'lastname'    => $this->post('lastname'),
				'description' => $this->post('description'),
				'language'    => $this->post('language'),
			]);
			$service->lang->setLang($this->post('lang'));
			return $this->jsonSuccess('Change saved');
		} catch (\Sy\Component\Html\Form\Exception $e) {
			$this->logWarning($e);
			return $this->jsonError($this->getOption('error') ?? 'Please fill the form correctly');
		} catch (\Sy\Db\MySql\DuplicateEntryException $e) {
			$this->logWarning($e->getMessage());
			return $this->jsonError('User already exists');
		} catch (\Sy\Db\MySql\Exception $e) {
			$this->logWarning($e->getMessage());
			return $this->jsonError('An error occured');
		}
	}

}