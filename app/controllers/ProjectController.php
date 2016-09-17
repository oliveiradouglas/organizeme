<?php

namespace Controllers;
use \Models\UserModel;
use \Helpers\Alert;

class ProjectController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('project');
		parent::__construct();
	} 

	private function index() {
		$listProjects = generateLink('project', 'listProjects');
		redirectToPage($listProjects);
	}

	public function listProjects(){
		UserModel::verifyUserIsLogged();
		$projects = [];

		try {
			$projects = $this->model->searchProjects();
		} catch (\Exception $e){
			Alert::displayAlert('system', "QUERY_ERROR", false);
		}

		$this->view->assignVariable('tableHeader', ['name' => 'Nome']);
		$this->view->assignVariable('projects', $projects);

		$this->view->createPage('Project', 'listProjects');
	}

	public function register() {
		UserModel::verifyUserIsLogged();

		if (!$this->postExists('project')) {
			$this->loadAndAssignUsers();
			$this->view->createPage('Project', 'register');
			exit();
		}

		try {
			$projectId = $this->model->create();
			
			$projectUsers = new \Models\ProjectUsersModel();
			$projectUsers->saveProjectUsers($projectId);

			$registred = true;
		} catch (\Exception $e) {
			$registred = false;
		}

		Alert::displayAlert('project', "REGISTER", $registred);
		$this->index();
	}

	private function loadAndAssignUsers($projectId = null) {
		try {
			$contactsModel = new \Models\ContactsModel();
			$users         = $contactsModel->searchMyContacts(false, false);
			$this->view->assignVariable('users', $users);

			if (!empty($projectId)) {
				$projectUsersModel = new \Models\ProjectUsersModel();
				$projectUsers      = $projectUsersModel->loadProjectUsers($projectId);
				$this->view->assignVariable('projectUsers', array_column($projectUsers, 'user_id'));
			}
		} catch (\Exception $e) {
			Alert::displayAlert('contacts', 'LOAD_CONTACTS', false);
		}
	}

	public function edit(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');
		$project = $this->model->loadProject($url[2]);

		if ($project['user_id'] != $_SESSION['user']['id']) {
			Alert::displayAlert('system', 'ERROR_OPERATION', false);
			$this->index();
		}

		if ($this->postExists('project')) {
			$edited = $this->saveEdit($project['id']);
			Alert::displayAlert('project', 'EDIT', $edited);
			$this->index();
		}

		$this->loadAndAssignUsers($url[2]);
		$this->view->assignVariable('project', $project);
		$this->view->createPage('Project', 'edit');
	}

	private function saveEdit($projectId) {
		try {
			$this->model->edit($projectId);

			$projectUsers = new \Models\ProjectUsersModel();
			$projectUsers->update(['active' => '0'], ['project_id' => $projectId]);
			$projectUsers->saveProjectUsers($projectId);
			
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function delete(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		try {
			$project = $this->model->loadProject($url[2]);

			if ($project['user_id'] != $_SESSION['user']['id']) {
				Alert::displayAlert('system', 'ERROR_OPERATION', false);
				$this->index();
			}

			$this->model->update(['active' => '0'], $url[2]);
			$deleted = true;
		} catch (\Exception $e) {
			$deleted = false;
		}

		Alert::displayAlert('project', 'DELETE', $deleted);
		$this->index();
	}

	public function visualize(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		$project = $this->model->loadProject($url[2]);
		$this->view->assignVariable('project', $project);

		$projectUsersModel = new \Models\ProjectUsersModel();
		$projectUsers      = $projectUsersModel->loadProjectUsers($url[2]);
		$this->view->assignVariable('projectUsers', $projectUsers);

		$this->view->createPage('Project', 'visualize');
	}	
}