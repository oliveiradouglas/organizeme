<?php

namespace Controllers;

class TaskController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('task');
		parent::__construct();
	} 

	private function index($projectId) {
		$listTasks = generateLink('task', 'listTasks', [$projectId]);
		$this->view->redirectToPage($listTasks);
	}

	public function listTasks(array $url){
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		try {
			$tasks = $this->model->find(['project_id' => $url[2]]);
			array_filter($tasks, [$this->model, 'translateConclusion']);
			
			$this->view->assignVariable('tableHeader', $this->model->getHeaderOfListing());
			$this->view->assignVariable('projectId', $url[2]);
			$this->view->assignVariable('tasks', $tasks);
			$this->view->createPage('Task', 'listTasks');
		} catch (Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}
	}

	/**
	* @param $url[2] = id do projeto
	*/

	public function register(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		if ($this->postExists('task')) 
			$this->saveRecord();
		
		$this->loadAndAssignPerformers($url[2]);
		$this->view->assignVariable('projectId', $url[2]);
		$this->view->createPage('Task', 'register');	
	}
		
	private function saveRecord() {
		try {
			$this->model->create();
			$registred = true;
		} catch (Exception $e) {
			$registred = false;
		}

		$this->alert->printAlert('task', 'REGISTER', $registred);
		$this->index($_POST['task']['project_id']);
	}

	private function loadAndAssignPerformers($projectId) {
		try {
			$contactsModel = new \Models\ContactsModel();
			$performers    = $contactsModel->searchMyContacts();
			$this->view->assignVariable('performers', $performers);
		} catch (Exception $e) {
			$this->alert->printAlert('contacts', 'LOAD_CONTACTS', false);
			$this->index($projectId);
		}
	}

	/**
	* @param $url[3] = id da tarefa
	* @param $url[2] = id do projeto
	*/

	public function edit(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		if ($this->postExists('task')) {
			$this->saveEdit();
		}

		$task = $this->model->loadTask($url[3], $url[2]);
		$this->view->assignVariable('task', $task[0]);
		$this->loadAndAssignPerformers($url[2]);
		$this->view->createPage('Task', 'edit');
	}

	private function saveEdit() {
		try {
			$this->model->edit();
			$registred = true;
		} catch (Exception $e) {
			$registred = false;
		}
		
		$this->alert->printAlert('task', 'REGISTER', $registred);
		$this->index($_POST['task']['project_id']);
	}

	public function saveEdit(array $url) {
		\Models\UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');
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