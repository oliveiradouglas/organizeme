<?php

namespace Controllers;

class ContactsController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('contacts');
		parent::__construct();
	} 

	public function index() {
		$linkListContacts = generateLink('contacts', 'listContacts');
		$this->view->redirectToPage($linkListContacts);
	}

	public function listContacts(){
		verifyUserIsLogged();

		try {
			$contacts = $this->model->searchMyContacts();
		} catch (\Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}

		$tableHeader = [
			'name' => 'Nome',
		];

		$this->view->assignVariable('tableHeader', $tableHeader);
		$this->view->assignVariable('contacts', $contacts);

		$this->view->createPage('Contacts', 'listContacts');
	}

	public function register() {
		verifyUserIsLogged();
		$this->view->createPage('Contacts', 'register');
	}

	public function saveRegister() {
		verifyUserIsLogged();
		validatePost('contacts', 'contacts');
	
		$user2 = $this->searchContact(['email' => $_POST['contacts']['email']]);

		$this->verifyExistingRequest($user2['id']);

		$dataContact['user1'] = $_SESSION['user']['id'];
		$dataContact['user2'] = $user2['id'];

		if (!validateRequiredFields($this->model->requiredFields, $dataContact)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('contacts', 'register'));
		}

		$returnSave = $this->model->save($dataContact);

		if (!$returnSave) {
			$this->alert->printAlert('contacts', "REGISTER", false);
		} else {
			$returnSendApproval = $this->sendFriendRequest($user2);
			$this->alert->printAlert('contacts', "WAITING_APPROVAL", $returnSendApproval);
		}
		
		$this->index();
	}

	private function verifyExistingRequest($user2Id) {

		if ($user2Id == $_SESSION['user']['id']) {
			$this->alert->printAlert('contacts', 'CURRENT_USER', false);
			$this->index();
		}

		$contact = $this->model->loadContact($user2Id);

		if (!empty($contact)) {
			if ($contact[0]['accepted']) {
				$this->alert->printAlert('contacts', 'EXISTING_CONTACT', false);
			} else {
				$this->alert->printAlert('contacts', 'WAITING_APPROVAL', false);
			}
			
			$this->index();
		}

		return true;
	}

	private function searchContact($where) {
		$userModel = new \Models\UserModel();

		$andWhere           = $where;
		$andWhere['active'] = 1;

		$user = $userModel->find($andWhere);

		if (empty($user)) {
			$this->alert->printAlert('user', 'NOT_FOUND', false);
			$this->view->redirectToPage(generateLink('contacts', 'register'));
		}

		return $user[0];
	}

	private function sendFriendRequest($user2) {
		$mail = new \Helpers\Email();

		$idsEncrypted  = base64_encode("{$user2['id']}/{$user2['email']}/{$user2['password']}/{$_SESSION['user']['id']}");
		$linkForAccept = generateLink('contacts', 'acceptContact', [$idsEncrypted]);

		$mail->assignVariable('nameUser1', $_SESSION['user']['name']);
		$mail->assignVariable('nameUser2', $user2['name']);
		$mail->assignVariable('linkForAccept', $linkForAccept);
		$mail->setTemplate('friend_request');

		return $mail->sendMail($user2, 'Solicitação de amizade');
	}

	public function acceptContact(array $url) {
		unset($_SESSION['user']);
		$parameters = $this->validateParametersAcceptContact($url);

		$andWhere = [
			'id'       => $parameters[0],
			'email'    => $parameters[1],
			'password' => $parameters[2],
		];

		$user = $this->searchContact($andWhere);

		$_SESSION['user']['id']   = $user['id'];
		$_SESSION['user']['name'] = $user['name'];

		$contact['accepted'] = 1;

		$andWhere = [
			'user1'  => $parameters[3],
			'user2'  => $user['id'],
			'active' => 1
		];

		$returnEdit = $this->model->update($contact, $andWhere);

		$this->alert->printAlert('contacts', "ACCEPT_CONTACT", $returnEdit);

		$this->index();
	}

	private function validateParametersAcceptContact(array $url) {
		$url2 = explode('/', base64_decode($url[2]));
		
		for ($i = 0; $i < 4; $i++) { 
			if (!isset($url2[$i]) || empty($url2[$i])) {
				$this->alert->printAlert('contacts', 'PARAMETERS_WRONG', false);
				$this->view->redirectToPage(generateLink('home', 'index'));
			}
		}

		return $url2;
	}

	public function delete(array $url) {
		verifyUserIsLogged();
		$this->validateFillTheId($url, 'contacts');

		$currentUserId = $_SESSION['user']['id'];

		$andWhere = [
			'id'     => $url[2],
			'active' => 1,
		];

		$orWhere = [
			'user1' => $currentUserId,
			'user2' => $currentUserId
		];

		$contact = $this->model->find($andWhere, $orWhere);

		if (empty($contact) || ($contact[0]['user1'] != $currentUserId && $contact[0]['user2'] != $currentUserId)) {
			$this->alert->printAlert('contacts', 'NOT_FOUND', false);
			$this->index();
		}

		$returnDelete = $this->model->update(['active' => '0'], $url[2]);

		$this->alert->printAlert('contacts', 'DELETE', $returnDelete);
		$this->index();
	}
}