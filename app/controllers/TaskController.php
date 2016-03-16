<?php

namespace Controllers;
use Models\UserModel;

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
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		try {
			$tasks = $this->model->find(['project_id' => $url[2]]);
			array_filter($tasks, [$this->model, 'translateConclusion']);
			
			$this->view->assignVariable('tableHeader', $this->model->getHeaderOfListing());
			$this->view->assignVariable('projectId', $url[2]);
			$this->view->assignVariable('tasks', $tasks);
			$this->view->createPage('Task', 'listTasks');
		} catch (\Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}
	}

	/**
	* @param $url[2] = id do projeto
	*/

	public function register(array $url) {
		UserModel::verifyUserIsLogged();
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
		} catch (\Exception $e) {
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
		} catch (\Exception $e) {
			$this->alert->printAlert('contacts', 'LOAD_CONTACTS', false);
			$this->index($projectId);
		}
	}

	/**
	* @param $url[3] = id da tarefa
	* @param $url[2] = id do projeto
	*/

	public function edit(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		$task = $this->model->loadTask($url[3], $url[2]);
		if ($this->postExists('task')) 
			$this->saveEdit($url[3], $url[2]);

		$this->view->assignVariable('task', $task);
		$this->loadAndAssignPerformers($url[2]);
		$this->view->createPage('Task', 'edit');
	}

	private function saveEdit($taskId, $projectId) {
		try {
			$this->model->edit($taskId);
			$registred = true;
		} catch (\Exception $e) {
			$registred = false;
		}
		
		$this->alert->printAlert('task', 'EDIT', $registred);
		$this->index($projectId);
	}

	public function visualize(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		$task = $this->model->loadTask($url[3], $url[2]);

		$this->view->assignVariable('task', $task);
		$this->view->createPage('Task', 'visualize');
	}

	public function delete(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		try {
			$this->model->delete($url[3], $url[2]);
			$deleted = true;
		} catch (\Exception $e) {
			$deleted = false;
		}

		$this->alert->printAlert('task', 'DELETE', $deleted);
		$this->view->redirectToPage(generateLink('task', 'listTasks', [$url[2]]));
	}
}