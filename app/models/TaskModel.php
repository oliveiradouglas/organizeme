<?php

namespace Models;

class TaskModel extends \Core\Model {
	public function __construct(){
		$this->table = 'task';
		parent::__construct();
	}

	public $requiredFields = ['creator_id', 'performer_id', 'name', 'due_date'];

	public function loadTasksToRemember() { 
		$today = date('Y-m-d');
		$query = "SELECT t.id, t.name, t.description, t.priority, t.due_date, t.days_to_remember, u.name as user_name, u.email as user_email
					FROM {$this->table} t
					INNER JOIN user u
					ON t.creator_id = u.id
					WHERE t.days_to_remember > 0
					AND t.due_date > {$today}
					AND t.completed = 0
					AND t.active = 1";

		$returnQuery  = $this->executeQuery($query);
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}

	public function translateConclusion(&$task) {
	    $task['completed'] = ($task['completed'] ? 'Sim' : 'Não');
	}	
}

?>