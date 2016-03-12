<?php

namespace Models;

class TaskModel extends \Core\Model {
	public function __construct(){
		parent::__construct('task');
		$this->setRequiredField('creator_id', 'performer_id', 'name', 'due_date');
	}

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

	public function getHeaderOfListing() {
		return [
			'name'      => 'Nome',
			'due_date'  => 'Data de vencimento',
			'completed' => 'Concluída'
		];
	}

	public function create() {
		$dataTask               = filterArrayData($_POST['task']);
		$dataTask['creator_id'] = $_SESSION['user']['id'];
		$this->validateRequiredFields($this->requiredFields, $dataTask);
		$this->save($dataTask);
	}

	public function loadtask($taskId, $projectId, array $extraResearch = []) {
		$where = [
			'id'         => $taskId,
			'project_id' => $projectId,
		];

		$where = array_merge($where, $extraResearch);
		$task = $this->find($where);

		if ($this->taskEmptyOrCurrentUserIsNotRelatedTask($task)) {
			$alert = new \Helpers\Alert();
			$alert->printAlert('task', 'TASK_NOT_FOUND', false);

			$view = new \Core\View();
			$view->redirectToPage(generateLink('task', 'listTasks', [$projectId]));
		}

		return $task;
	}

	private function taskEmptyOrCurrentUserIsNotRelatedTask($task) {
		return (empty($task) 
					||	($task[0]['creator_id'] != $_SESSION['user']['id'] && $task[0]['performer_id'] != $_SESSION['user']['id'])
				);
	}

	public function edit() {
		
	}
}

?>