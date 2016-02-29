<?php

namespace Models;

class UserModel extends \Core\Model {
	public function __construct(){
		$this->table = 'user';
		parent::__construct();
	}

	private $requiredFields = ['name', 'email'];

	public static function isLogged(){
		return (isset($_SESSION['user']['id']) ? true : false);
	}

	public function login(){
		$accessData           = filterArrayData($_POST['user']);
		$accessData['active'] = 1;

		$user = $this->find($accessData);

		if (empty($user)) {
			$this->alert->printAlert('user', "INVALID_LOGIN", false);
			return false;
		} 
	
		$this->createUserSession($user[0]);
	}

	private function createUserSession($user) {
		$_SESSION['user']['id']   = $user['id'];
		$_SESSION['user']['name'] = $user['name'];
	}

	public function verifyUserIsLogged() {
		if (!self::isLogged()) {
		    $alert = new \Helpers\Alert();
		    $alert->printAlert('user', 'USER_NOT_LOGGED', false);
		    
		    $view = new View();
		    $view->redirectToPage(DOMAIN);
		}
	}

	public function create() {
		$dataUser = filterArrayData($_POST['user']);
		$this->setRequiredField('password');
		$this->validateRequiredFields($this->requiredFields, $dataUser);
		$this->save($dataUser);
	}

	public function setRequiredField($nameField) {
		$this->requiredFields[] = $nameField;
	}

	public function edit($userId) {
		$dataUser = filterArrayData($_POST['user']);
		$this->validateRequiredFields($this->requiredFields, $dataUser);
		$this->update($dataUser, $userId);
	}

	public function passwordRecovery() {
		$user = $this->find(['email' => $_POST['recovery_email']]);

		if (empty($user)) {
			throw new \Exception("Usuário não encontrado para recuperar a senha!");
		}

		$user             = $user[0];
		$user['password'] = encryptPassword(md5(mt_rand()));
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