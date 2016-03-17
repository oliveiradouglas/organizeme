<?php

namespace Models;

class ContactsModel extends \Core\Model {
	public function __construct(){
		parent::__construct('contacts');
		$this->setRequiredField('user1', 'user2');
	}

	public function searchMyContacts($loadWaitingApproval = false, $loadCurrentUser = true) {
		$query = "SELECT c.id, u.id as user_id, u.name 
			FROM contacts c 
			INNER JOIN user u ON c.user1 = u.id OR c.user2 = u.id
			WHERE (c.user1 = {$_SESSION['user']['id']}
			OR c.user2  = {$_SESSION['user']['id']})
			AND c.active = 1";

		$accepted = ($loadWaitingApproval ? "" : " AND c.accepted = 1") . ";";
		$query   .= $accepted;

		$returnQuery  = $this->executeQuery($query);
		$arrayRecords = $this->createArrayRecords($returnQuery);

		if ($loadCurrentUser) 
			$this->prepareArrayCurrentUser($arrayRecords);
		else
			$this->removeCurrentUser($arrayRecords);

		return $arrayRecords;
	}

	private function prepareArrayCurrentUser(&$arrayRecords) {
		$dataCurrentUser = [
			['id' => $_SESSION['user']['id'], 'user_id' => $_SESSION['user']['id'], 'name' => $_SESSION['user']['name'] . " (Eu)"]
		];
		
		$this->removeCurrentUser($arrayRecords);
		$arrayRecords = array_merge($dataCurrentUser, $arrayRecords);
	}

	private function removeCurrentUser(&$arrayRecords) {
		$currentUserId = $_SESSION['user']['id'];
		$ids           = array_column($arrayRecords, 'user_id');
		
		$indexCurrentUser = array_search($currentUserId, $ids);
		while($indexCurrentUser !== false) {
			unset($ids[$indexCurrentUser], $arrayRecords[$indexCurrentUser]);
			$indexCurrentUser = array_search($currentUserId, $ids);
		}
	}
	
	public function create($user2Id) {
		$dataContact['user1'] = $_SESSION['user']['id'];
		$dataContact['user2'] = $user2Id;

		$this->validateRequiredFields($this->requiredFields, $dataContact);
		$this->save($dataContact);
	}

	public function verifyExistingRequest($user2Id) {
		if ($user2Id == $_SESSION['user']['id']) {
			\Helpers\Alert::displayAlert('contacts', 'CURRENT_USER', false);
			redirectToPage(generateLink('contacts', 'listContacts'));
		}

		$contact = $this->loadContact($user2Id);
		debug($contact);
		if (empty($contact)) return;
		
		$indexAlert = (($contact[0]['accepted']) ? 'EXISTING_CONTACT' : 'WAITING_APPROVAL');
		\Helpers\Alert::displayAlert('contacts', $indexAlert, false);
		redirectToPage(generateLink('contacts', 'listContacts'));
	}

	public function loadContact($user2Id) {
		$andWhere = [];
		$orWhere  = [['user1' => $_SESSION['user']['id'], 'user2' => $user2Id], ['user1' => $user2Id, 'user2' => $_SESSION['user']['id']]];

		return $this->find($andWhere, $orWhere);
	}
}

?>