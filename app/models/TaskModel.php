<?php

namespace Models;

class TaskModel extends \Core\Model {
	public function __construct(){
		parent::__construct('task');
		$this->setRequiredField('creator_id', 'performer_id', 'name', 'due_date', 'priority', 'description');
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
		$where = ['id' => $taskId, 'project_id' => $projectId];
		$where = array_merge($where, $extraResearch);
		$task = $this->find($where);

		if ($this->taskEmptyOrCurrentUserIsNotRelatedTask($task)) {
			Alert::displayAlert('task', 'TASK_NOT_FOUND', false);
			redirectToPage(generateLink('task', 'listTasks', [$projectId]));
		}

		return $task[0];
	}

	private function taskEmptyOrCurrentUserIsNotRelatedTask($task) {
		return (empty($task) 
					||	($task[0]['creator_id'] != $_SESSION['user']['id'] && $task[0]['performer_id'] != $_SESSION['user']['id'])
				);
	}

	public function edit($taskId) {
		$dataTask = filterArrayData($_POST['task']);

		if (!isset($dataTask['completed']))
			$dataTask['completed'] = '0';

		$this->validateRequiredFields($this->requiredFields, $dataTask);
		$this->update($dataTask, $taskId);
	}

	public function delete($taskId, $projectId) {
		$this->loadTask($taskId, $projectId, ['creator_id' => $_SESSION['user']['id']]);
		$this->update(['active' => '0'], $taskId);
	}
}

?>