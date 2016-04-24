<?php

namespace Models;

class TaskModel extends \Core\Model {
	public function __construct(){
		parent::__construct('task');
		$this->setRequiredField('creator_id', 'performer_id', 'name', 'due_date', 'priority');
	}

	public function loadTasksToRemember() { 
		$today = date('Y-m-d');
		$query = "SELECT task.id, task.name, task.description, task.priority, task.due_date, task.days_to_remember, creator.name as creator_name, creator.email as creator_email, performer.name as performer_name, performer.email as performer_email
					FROM {$this->table} task
					INNER JOIN user creator
					ON task.creator_id = creator.id
					INNER JOIN user performer
					ON task.performer_id = performer.id
					WHERE task.days_to_remember > 0
					AND task.due_date > {$today}
					AND task.completed = 0
					AND task.active = 1";

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
		$this->validateRequiredFields($dataTask);
		return $this->save($dataTask);
	}

	public function loadtask($taskId, $projectId, array $extraResearch = []) {
		$where = ['id' => $taskId, 'project_id' => $projectId];
		$where = array_merge($where, $extraResearch);
		$task = $this->find($where);

		$projectUsersModel = new ProjectUsersModel();
		if (empty($task) || ($this->currentUserIsNotRelatedTask($task) && !$projectUsersModel->currentUserRelatedToTheProject($projectId))) {
			\Helpers\Alert::displayAlert('task', 'TASK_NOT_ALLOWED_EDIT', false);
			redirectToPage(generateLink('task', 'listTasks', [$projectId]));
		}

		return $task[0];
	}

	private function currentUserIsNotRelatedTask($task) {
		return ($task[0]['creator_id'] != $_SESSION['user']['id']) && ($task[0]['performer_id'] != $_SESSION['user']['id']);
	}

	public function edit($taskId) {
		$dataTask = filterArrayData($_POST['task']);

		if (!isset($dataTask['completed']))
			$dataTask['completed'] = '0';

		$this->validateRequiredFields($dataTask);
		$this->update($dataTask, $taskId);
	}

	public function delete($taskId, $projectId) {
		$this->loadTask($taskId, $projectId, ['creator_id' => $_SESSION['user']['id']]);
		$this->update(['active' => '0'], $taskId);
	}
}

?>