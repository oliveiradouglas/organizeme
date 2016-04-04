<?php

namespace Controllers;
use Models\UserModel;
use Helpers\Alert;

class TaskController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('task');
		parent::__construct();
	} 

	private function index($projectId) {
		$listTasks = generateLink('task', 'listTasks', [$projectId]);
		redirectToPage($listTasks);
	}

	public function listTasks(array $url){
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		try {
			$projectUsersModel = new \Models\ProjectUsersModel();
			$projectUsersModel->verifyCurrentUserRelatedToTheProject($url[2]);

			$tasks = $this->model->find(['project_id' => $url[2]]);
			array_filter($tasks, [$this->model, 'translateConclusion']);
			
			$this->view->assignVariable('tableHeader', $this->model->getHeaderOfListing());
			$this->view->assignVariable('projectId', $url[2]);
			$this->view->assignVariable('tasks', $tasks);
		} catch (\Exception $e){
			Alert::displayAlert('system', "QUERY_ERROR", false);
		}
		
		$this->view->createPage('Task', 'listTasks');
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

		Alert::displayAlert('task', 'REGISTER', $registred);
		$this->index($_POST['task']['project_id']);
	}

	private function loadAndAssignPerformers($projectId) {
		try {
			$projectUsersModel = new \Models\ProjectUsersModel();
			$performers        = $projectUsersModel->loadProjectUsers($projectId);
			$performers[]      = ['user_id' => $_SESSION['user']['id'], 'user_name' => $_SESSION['user']['name'] . ' (Eu)'];
			$performers        = uniqueMultidimArray(array_reverse($performers), 'user_id');

			$this->view->assignVariable('performers', $performers);
		} catch (\Exception $e) {
			Alert::displayAlert('contacts', 'LOAD_CONTACTS', false);
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

		$task = $this->model->loadTask($url[3], $url[2], ['creator_id' => $_SESSION['user']['id']]);
		if ($this->postExists('task')) {
			if (empty($task)) {
				Alert::displayAlert('task', 'TASK_NOT_ALLOWED_EDIT', false);
				$this->index($url[2]);
			}

			$this->saveEdit($url[3], $url[2]);
		}

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
		
		Alert::displayAlert('task', 'EDIT', $registred);
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

		Alert::displayAlert('task', 'DELETE', $deleted);
		$this->index($url[2]);
	}

	public function completeTask(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project', 'task');

		try {
			$andWhere = ['id' => $url[3], 'project_id' => $url[2]];
			$orWhere  = ['creator_id' => $_SESSION['user']['id'], 'performer_id' => $_SESSION['user']['id']];
			$task     = $this->model->find($andWhere, $orWhere);

			if (empty($task)) {
				Alert::displayAlert('task', 'USER_UNRELATED_TO_TASK', false);
				$this->index($url[2]);
			}

			$completeTask = ['completed' => 1, 'conclusion_date' => date('Y-m-d')];
			$this->model->update($completeTask, $url[3]);
			
			$completed = true;
		} catch (\Exception $e) {
			$completed = false;
		}

		Alert::displayAlert('task', 'TASK_COMPLETE', $completed);
		$this->index($url[2]);
	}	
}