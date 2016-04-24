<?php

namespace Controllers;
use \Helpers\Alert;

class UserController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('user');
		parent::__construct();
	}

	public function login(){
		if ($this->postExists('user')) {
			$this->model->login();
		} else {
			Alert::displayAlert('user', "USER_POST_EMPTY", false);
		}

		redirectToPage(DOMAIN);
	}

	public function logout(){
		unset($_SESSION['user']);
		Alert::displayAlert('user', "LOGOUT", true);
		redirectToPage(DOMAIN);
	}

	public function register(){
		if (!$this->postExists('user')) {
			$this->view->createPage('User', 'register');
			exit();
		}

		try {
			$this->model->create();
			Alert::displayAlert('user', "REGISTER", true);
			$this->login();
		} catch (\Exception $e) {
			Alert::displayAlert('user', "ERROR_REGISTER", false);
			redirectToPage($_SERVER['HTTP_REFERER']);			
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
			} catch (\Exception $e) {
				$edit = false;
			}
			
			Alert::displayAlert('user', "EDIT", $edit);
		}

		$this->view->assignVariable('user', $this->loadUser($userId));
		$this->view->createPage('User', 'edit');
	}

	private function loadUser($userId) {
		$user = $this->model->find(['id' => $userId, 'active' => 1]);
		
		if (empty($user)) {
			Alert::displayAlert('user', 'NOT_FOUND', false);
			redirectToPage(DOMAIN);
		}

		return $user[0];
	}

	private function verifyIdUserEditIsLoggedUser($idUserEdit) {
		if (!isset($_SESSION['user']) || $_SESSION['user']['id'] != $idUserEdit) {
			Alert::displayAlert('system', 'ERROR_OPERATION', false);
			redirectToPage(DOMAIN);
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
		} catch (\Exception $e) {
			$passwordRecovery = false;
		}
		
		Alert::displayAlert('user', "PASSWORD_RECOVERY", $passwordRecovery);
		redirectToPage((isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : DOMAIN));
	}
}