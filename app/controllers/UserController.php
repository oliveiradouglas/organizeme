<?php

namespace Controllers;

class UserController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('user');
		parent::__construct();
	} 

	public static function isLogged(){
		return (isset($_SESSION['user']['id']) ? true : false);
	}

	public function login(){
		validatePost('user', 'user');

		$accessData = filterArrayData($_POST['user']);
		$accessData['active'] = 1;

		$user = $this->model->find($accessData);

		if (empty($user)) {
			$this->alert->printAlert('user', "INVALID_LOGIN", false);
		} else {
			$_SESSION['user']['id']   = $user[0]['id'];
			$_SESSION['user']['name'] = $user[0]['name'];
		}
		
		$this->view->redirectToPage(DOMAIN);
	}

	public function logout(){
		unset($_SESSION['user']);
		$this->alert->printAlert('user', "LOGOUT", true);
		$this->view->redirectToPage(DOMAIN);
	}

	public function register(){
		$this->view->createPage('User', 'register');
	}

	public function saveRegister(){
		validatePost('user', 'user');

		$dataUser = filterArrayData($_POST['user']);

		if (!validateRequiredFields($this->model->requiredFields, $dataUser)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$returnSave = $this->model->save($dataUser);
		
		if (!$returnSave) {
			$this->alert->printAlert('user', "ERROR_REGISTER", false);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		}
		
		$this->login();
		$this->alert->printAlert('user', "ERROR_REGISTER", false);
	}

	public function edit(array $url){
		verifyUserIsLogged();
		$this->validateId($url, 'user');
		$this->verifyIdUserEdit($url[2]);

		$andWhere = [
			'id' => $url[2],
			'active' => 1
		];

		$user = $this->model->find($andWhere);

		if (empty($user)) {
			$this->alert->printAlert('user', 'NOT_FOUND', false);
			$this->view->redirectToPage(DOMAIN);
		}

		$this->view->assignVariable('user', $user[0]);
		$this->view->createPage('User', 'edit');
	}

	public function saveEdit(array $url){
		verifyUserIsLogged();
		$this->validateId($url, 'user');
		$this->verifyIdUserEdit($url[2]);

		$dataUser = filterArrayData($_POST['user']);

		if (!validateRequiredFields($this->model->requiredFields, $dataUser)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$returnEdit = $this->model->update($dataUser, $url[2]);

		$this->alert->printAlert('user', "EDIT", $returnEdit);

		$linkToRedirect = (($returnEdit) ? generateLink('project', 'listProjects') : generateLink('user', 'edit', [$url[2]]));

		$this->view->redirectToPage($linkToRedirect);
	}

	private function verifyIdUserEdit($idUserEdit) {
		if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $idUserEdit) {
			$this->alert->printAlert('system', 'ERROR_OPERATION', false);
			$this->view->redirectToPage(DOMAIN);
		}
	}

	public function validateEmail(){
		if (!isset($_POST['email'])) {
			echo 0;
			exit();
		}

		$user = $this->model->find(array('email' => $_POST['email']));

		if (!empty($user)) {
			echo 0;
			exit();
		}

		echo 1;
	}

	public function passwordRecovery(){
		$this->view->createPage('User', 'passwordRecovery');
	}

	public function requestPasswordRecovery(){
		validatePost('user', 'recovery_email');
		
		$user = $this->model->find(array('email' => $_POST['recovery_email'], 'active' => 1));

		if (empty($user)) {
			$this->alert->printAlert('user', "NOT_FOUND", false);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$user             = $user[0];
		$newPassword      = md5(mt_rand());
		$user['password'] = encryptPassword($newPassword);

		$returnEdit = $this->model->update($user, $user['id']);

		if (!$returnEdit) {
			$this->alert->printAlert('user', "PASSWORD_RECOVERY", $returnEdit);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		} 

		$returnSent = $this->sendEmailPasswordRecovery($user, $newPassword);

		$this->alert->printAlert('user', "PASSWORD_RECOVERY", $returnSent);
		$this->view->redirectToPage(generateLink('home', 'index'));
	}

	private function sendEmailPasswordRecovery($user, $newPassword) {
		$mail = new \Helpers\Email();

		$mail->assignVariable('nameUser', $user['name']);
		$mail->assignVariable('newPassword', $newPassword);
		$mail->setTemplate('password_recovery');

		return $mail->sendMail($user, 'Recuperação de senha');
	}
}