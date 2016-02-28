<?php

namespace Controllers;

class ProjectController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('project');
		parent::__construct();
	} 

	public function listProjects(){
		verifyUserIsLogged();
		$projects = [];

		try {
			$where = [
				'user_id' => $_SESSION['user']['id'],
				'active'  => 1,
			];

			$projects = $this->model->find($where);
		} catch (Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}

		$tableHeader = [
			'name'     => 'Nome',
		];

		$this->view->assignVariable('tableHeader', $tableHeader);
		$this->view->assignVariable('projects', $projects);

		$this->view->createPage('Project', 'listProjects');
	}

	public function register() {
		verifyUserIsLogged();
		$this->view->createPage('Project', 'register');
	}

	public function saveRegister() {
		verifyUserIsLogged();
		validatePost('project', 'project');
	
		$dataProject            = $_POST['project'];
		$dataProject['user_id'] = $_SESSION['user']['id'];

		if (!validateRequiredFields($this->model->requiredFields, $dataProject)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('project', 'register'));
		}

		$returnSave = $this->model->save($dataProject);
		
		$this->alert->printAlert('project', "REGISTER", $returnSave);
		
		$this->view->redirectToPage(generateLink('project', 'listProjects'));
	}

	public function edit(array $url) {
		verifyUserIsLogged();
		$this->validateId($url, 'project');

		$project = $this->loadProject($url[2]);

		$this->view->assignVariable('project', $project[0]);
		$this->view->createPage('Project', 'edit');
	}

	private function loadProject($project_id) {
		$where = [
			'id'      => $project_id,
			'user_id' => $_SESSION['user']['id'],
			'active'  => 1,
		];

		$project = $this->model->find($where);

		if (empty($project)) {
			$this->alert->printAlert('project', 'PROJECT_NOT_FOUND', false);
			$this->view->redirectToPage(generateLink('project', 'listProjects'));
		}

		return $project;
	}

	public function saveEdit(array $url) {
		verifyUserIsLogged();
		validatePost('project', 'project');
		$this->validateId($url, 'project');

		$this->loadProject($url[2]);

		if (!validateRequiredFields($this->model->requiredFields, $_POST['project'])) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('project', 'register'));
		}

		$returnEdit = $this->model->update($_POST['project'], $url[2]);

		$this->alert->printAlert('project', 'EDIT', $returnEdit);
		$this->view->redirectToPage(generateLink('project', 'listProjects'));
	}

	public function delete(array $url) {
		verifyUserIsLogged();
		$this->validateId($url, 'project');

		$this->loadProject($url[2]);

		$returnDelete = $this->model->update(['active' => '0'], $url[2]);

		$this->alert->printAlert('project', 'DELETE', $returnDelete);
		$this->view->redirectToPage(generateLink('project', 'listProjects'));	
	}
}