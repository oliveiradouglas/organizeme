<?php

namespace Models;

class ContactsModel extends \Core\Model {
	public function __construct(){
		$this->table = 'contacts';
		parent::__construct();
	}

	public $requiredFields = ['user1', 'user2'];

	public function searchMyContacts() {
		$query = "SELECT c.id, u.id as user_id, u.name 
					FROM contacts c 
					INNER JOIN user u ON c.user1 = u.id OR c.user2 = u.id
					WHERE (c.user1 = {$_SESSION['user']['id']}
					OR c.user2  = {$_SESSION['user']['id']})
					AND c.active = 1
					AND c.accepted = 1;";

		$returnQuery  = $this->executeQuery($query);

		$dataCurrentUser = [
			[
				'user_id' => $_SESSION['user']['id'],
				'name'    => $_SESSION['user']['name'] . " (Eu)"
			]
		];

		$arrayRecords = $this->createArrayRecords($returnQuery);
		$this->removeCurrentUser($arrayRecords);

		$arrayRecords = array_merge($dataCurrentUser, $arrayRecords);

		return $arrayRecords;
	}

	private function removeCurrentUser(&$arrayRecords) {
		$currentUserId = $_SESSION['user']['id'];
		$ids           = array_column($arrayRecords, 'user_id');
		
		while(in_array($currentUserId, $ids)) {
			$key = array_search($currentUserId, $ids);
			unset($ids[$key], $arrayRecords[$key]);
		}
	}

	public function loadContact($user2Id) {
		$query = "SELECT *
					FROM contacts
					WHERE (user1 = {$_SESSION['user']['id']}
					AND user2 = {$user2Id} 
					OR user1 = {$user2Id}
					AND user2 = {$_SESSION['user']['id']})
					AND active = 1";

		$returnQuery  = $this->executeQuery($query);			
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}
}

?>