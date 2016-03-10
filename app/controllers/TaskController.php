<?php

namespace Controllers;

class TaskController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('task');
		parent::__construct();
	} 

	public function listTasks(array $url){
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');
		$tasks = [];

		try {
			$andWhere = [
				'project_id' => $url[2],
				'active'     => 1,
			];

			$tasks = $this->model->find($andWhere);
		} catch (Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}

		$tableHeader = [
			'name'      => 'Nome',
			'due_date'  => 'Data de vencimento',
			'completed' => 'Concluída'
		];

		array_filter($tasks, [$this->model, 'translateConclusion']);

		$this->view->assignVariable('tableHeader', $tableHeader);
		$this->view->assignVariable('project_id', $url[2]);
		$this->view->assignVariable('tasks', $tasks);

		$this->view->createPage('Task', 'listTasks');
	}

	public function register(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		$contactsModel = new \Models\ContactsModel();
		$performers    = $contactsModel->searchMyContacts();
		$this->view->assignVariable('performers', $performers);

		$this->view->assignVariable('project_id', $url[2]);
		$this->view->createPage('Task', 'register');
	}

	public function saveRegister() {
		verifyUserIsLogged();
		validatePost('task', 'task');
	
		$dataTask               = filterArrayData($_POST['task']);
		$dataTask['creator_id'] = $_SESSION['user']['id'];

		if (!validateRequiredFields($this->model->requiredFields, $dataTask)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('project', 'listProjects'));
		}

		$project_id = $dataTask['project_id'];

		$returnSave = $this->model->save($dataTask);
		
		$this->alert->printAlert('task', "REGISTER", $returnSave);
		$this->view->redirectToPage(generateLink('task', 'listTasks', [$project_id]));
	}

	public function edit(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		$task = $this->loadTask($url[3], $url[2]);
		$this->view->assignVariable('task', $task[0]);

		$contactsModel = new \Models\ContactsModel();
		$performers    = $contactsModel->searchMyContacts();
		$this->view->assignVariable('performers', $performers);
		
		$this->view->createPage('Task', 'edit');
	}

	private function loadTask($task_id, $project_id, array $extraResearch = []) {
		$where = [
			'id'         => $task_id,
			'project_id' => $project_id,
			'active'     => 1,
		];

		if (!empty($extraResearch)) $where = array_merge($where, $extraResearch);

		$task = $this->model->find($where);

		if (empty($task) ||	
			($task[0]['creator_id'] != $_SESSION['user']['id'] && $task[0]['performer_id'] != $_SESSION['user']['id'])) {

			if (empty($extraResearch)) {
				$this->alert->printAlert('task', 'TASK_NOT_FOUND', false);
			} else {
				$this->alert->printAlert('system', 'ERROR_OPERATION', false);
			}

			$this->view->redirectToPage(generateLink('task', 'listTasks', [$project_id]));
		}

		return $task;
	}

	public function saveEdit(array $url) {
		verifyUserIsLogged();
		validatePost('task', 'task');
		$this->validateFillTheId($url, 'project', 'task');

		$this->loadTask($url[3], $url[2]);

		$dataTask = filterArrayData($_POST['task']);

		if (!isset($dataTask['completed'])) {
			$dataTask['completed'] = '0';
		}

		if (!validateRequiredFields($this->model->requiredFields, $dataTask)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('task', 'register', [$url[2]]));
		}

		$returnEdit = $this->model->update($dataTask, $url[3]);

		$this->alert->printAlert('task', 'EDIT', $returnEdit);
		$this->view->redirectToPage(generateLink('task', 'listTasks', [$url[2]]));
	}

	public function visualize(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		$task = $this->loadTask($url[3], $url[2]);

		$this->view->assignVariable('task', $task[0]);
		$this->view->createPage('Task', 'visualize');
	}

	public function delete(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		$this->loadTask($url[3], $url[2], ['creator_id' => $_SESSION['user']['id']]);

		$returnDelete = $this->model->update(['active' => '0'], $url[3]);

		$this->alert->printAlert('task', 'DELETE', $returnDelete);
		$this->view->redirectToPage(generateLink('task', 'listTasks', [$url[2]]));	
	}
}