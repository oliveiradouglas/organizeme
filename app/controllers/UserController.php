<?php

namespace Controllers;

class UserController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('user');
		parent::__construct();
	}

	public function login(){
		if ($this->postExists('user')) {
			$this->model->login();
		} else {
			$alert->printAlert('user', "USER_POST_EMPTY", false);
		}

		$this->view->redirectToPage(DOMAIN);
	}

	public function logout(){
		unset($_SESSION['user']);
		$this->alert->printAlert('user', "LOGOUT", true);
		$this->view->redirectToPage(DOMAIN);
	}

	public function register(){
		if (!$this->postExists('user')) {
			$this->view->createPage('User', 'register');
			return true;
		}

		try {
			$this->model->create();
			$this->alert->printAlert('user', "REGISTER", true);
			$this->login();
		} catch (Exception $e) {
			$this->alert->printAlert('user', "ERROR_REGISTER", false);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);			
		}
	}

	public function edit(array $url){
		$this->model->verifyUserIsLogged();
		$this->validateFillTheId($url, 'user');

		$userId = $url[2];
		$this->verifyIdUserEditIsLoggedUser($userId);

		if ($this->postExists('user')) {
			try {
				$this->model->edit($userId);
				$edit = true;
			} catch (Exception $e) {
				$edit = false;
			}
			
			$this->alert->printAlert('user', "EDIT", $edit);
			$this->view->redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$this->view->assignVariable('user', $this->loadUser($userId));
		$this->view->createPage('User', 'edit');
	}

	private function loadUser($userId) {
		$user = $this->model->find(['id' => $userId, 'active' => 1]);
		
		if (empty($user)) {
			$this->alert->printAlert('user', 'NOT_FOUND', false);
			$this->view->redirectToPage(DOMAIN);
		}

		return $user[0];
	}

	private function verifyIdUserEditIsLoggedUser($idUserEdit) {
		if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $idUserEdit) {
			$this->alert->printAlert('system', 'ERROR_OPERATION', false);
			$this->view->redirectToPage(DOMAIN);
		}
	}

	public function validateEmail(){
		if (!$this->postExists('email')) {
			echo 0;	exit();
		}

		$user = $this->model->find(array('email' => $_POST['email']));

		if (!empty($user)) {
			echo 0; exit();
		}

		echo 1;
	}

	public function passwordRecovery(){
		if (!$this->postExists('recovery_email')) {
			$this->view->createPage('User', 'passwordRecovery');
			return true;
		}
		
		try {
			$passwordRecovery = $this->model->passwordRecovery();
		} catch (Exception $e) {
			$passwordRecovery = false;
		}
		
		$this->alert->printAlert('user', "PASSWORD_RECOVERY", $passwordRecovery);
		$this->view->redirectToPage(DOMAIN);
	}
}