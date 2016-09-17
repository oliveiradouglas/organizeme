<?php

namespace Models;

class UserModel extends \Core\Model {
	public function __construct(){
		parent::__construct('user');
		$this->setRequiredField('name');
	}

	public static function isLogged(){
		return (isset($_SESSION['user']['id']) ? true : false);
	}

	public function login(){
		$accessData           = filterArrayData($_POST['user']);
		$accessData['active'] = 1;

		$user = $this->find($accessData);

		if (empty($user)) {
			\Helpers\Alert::displayAlert('user', "INVALID_LOGIN", false);
			return false;
		} 
	
		$this->createUserSession($user[0]);
	}

	private function createUserSession($user) {
		$_SESSION['user']['id']   = $user['id'];
		$_SESSION['user']['name'] = $user['name'];
	}

	public static function verifyUserIsLogged() {
		if (!self::isLogged()) {
		    \Helpers\Alert::displayAlert('user', 'USER_NOT_LOGGED', false);
		    redirectToPage(DOMAIN);
		}
	}

	public function create() {
		$dataUser = filterArrayData($_POST['user']);
		$this->setRequiredField('password', 'email');
		$this->validateRequiredFields($dataUser);
		$this->save($dataUser);
	}

	public function edit($userId) {
		$dataUser = filterArrayData($_POST['user']);
		$this->validateRequiredFields($dataUser);

		if (empty($dataUser['password'])) unset($dataUser['password']);

		$this->update($dataUser, $userId);
		$_SESSION['user']['name'] = $dataUser['name'];
	}

	public function passwordRecovery() {
		$user = $this->find(['email' => $_POST['recovery_email']]);
		if (empty($user)) {
			throw new \DomainException("Usuário não encontrado!");
		}

		$newPassword = md5(mt_rand());

		$user             = $user[0];
		$user['password'] = encryptPassword($newPassword);
		$this->update($user, $user['id']);

		return $this->sendEmailPasswordRecovery($user, $newPassword);
	}

	private function sendEmailPasswordRecovery($user, $newPassword) {
		$mail = new \Helpers\Email();

		$mail->assignVariable('nameUser', $user['name']);
		$mail->assignVariable('newPassword', $newPassword);
		$mail->setTemplate('password_recovery');

		return $mail->sendMail($user, 'Recuperação de senha');
	}	
}

?>