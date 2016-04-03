<?php

namespace Controllers;
use \Models\UserModel;
use \Helpers\Alert;

class ContactsController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('contacts');
		parent::__construct();
	} 

	public function index() {
		$linkListContacts = generateLink('contacts', 'listContacts');
		redirectToPage($linkListContacts);
	}

	public function listContacts(){
		UserModel::verifyUserIsLogged();

		try {
			$contacts = $this->model->searchMyContacts(true, false);
			$this->view->assignVariable('contacts', $contacts);
			$this->view->assignVariable('tableHeader', ['name' => 'Nome', 'status' => 'Status']);
		} catch (\Exception $e) {
			Alert::displayAlert('system', "QUERY_ERROR", false);
		}

		$this->view->createPage('Contacts', 'listContacts');
	}

	public function register() {
		UserModel::verifyUserIsLogged();

		try {
			if ($this->postExists('contacts')) 
				$this->saveRegister();
			
			$this->view->createPage('Contacts', 'register');
		} catch (\Exception $e) {
			$this->index();
		}
	}

	private function saveRegister() {
		try {
			$user2 = $this->searchUser(['email' => $_POST['contacts']['email']]);
			$this->model->verifyExistingRequest($user2['id']);
			$this->model->create($user2['id']);

			Alert::displayAlert('contacts', "WAITING_APPROVAL", true);
		} catch (\Exception $e) {
			Alert::displayAlert('contacts', "REGISTER", false);
		}
		
		$this->index();
	}

	private function searchUser($where) {
		$userModel = new UserModel();
		$user      = $userModel->find($where, [], 'id');

		if (empty($user)) {
			Alert::displayAlert('user', 'NOT_FOUND', false);
			redirectToPage(generateLink('contacts', 'register'));
		}

		return $user[0];
	}

	/**
	* @param $url[3] operação / 1 = aceitar 0 = reprovar
	* @param $url[2] id do contato
	*/
	public function acceptContact(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'contact');

		try {
			if (!isset($url[3]) || ($url[3] != 1) && $url[3] != 0) {
				Alert::displayAlert('system', "OPERATION_UNKNOW", $accepted);
				$this->index();
			}

			$this->model->update(['accepted' => $url[3], 'active' => $url[3]], $url[2]);
			$success = true;
		} catch (\Exception $e) {
			$success = false;
		}

		$operation = ($url[3] ? "ACCEPT" : "REJECT") . "_CONTACT";
		Alert::displayAlert('contacts', $operation, $success);
		$this->index();
	}

	public function delete(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'contacts');

		try {
			$currentUserId = $_SESSION['user']['id'];
			$andWhere = ['id' => $url[2]];
			$orWhere  = ['user1' => $currentUserId, 'user2' => $currentUserId];
			$contact  = $this->model->find($andWhere, $orWhere);

			if (empty($contact) || ($contact[0]['user1'] != $currentUserId && $contact[0]['user2'] != $currentUserId)) {
				Alert::displayAlert('contacts', 'NOT_FOUND', false);
				$this->index();
			}

			$this->model->update(['active' => '0'], $url[2]);
			$deleted = true;
		} catch (\Exception $e) {
			$deleted = false;
		}

		Alert::displayAlert('contacts', 'DELETE', $deleted);
		$this->index();
	}
}