<?php

namespace Controllers;

class GroupController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('group');
		parent::__construct();
	} 

	public function listGroups(){
		verifyUserIsLogged();
		$groups = [];

		try {
			$where = [
				'user_id' => $_SESSION['user']['id'],
				'active'  => 1,
			];

			$groups = $this->model->find($where);
		} catch (Exception $e){
			$this->alert->printAlert('system', "QUERY_ERROR", false);
		}

		$tableHeader = [
			'name'     => 'Nome',
		];

		$this->view->assignVariable('tableHeader', $tableHeader);
		$this->view->assignVariable('groups', $groups);

		$this->view->createPage('Group', 'listGroups');
	}

	public function register() {
		verifyUserIsLogged();
		$this->view->createPage('Group', 'register');
	}

	public function index() {
		$this->view->redirectToPage(generateLink('group', 'listGroups'));
	}

	public function saveRegister() {
		verifyUserIsLogged();
		validatePost('group', 'group');
	
		$dataGroup            = $_POST['group'];
		$dataGroup['user_id'] = $_SESSION['user']['id'];

		if (!validateRequiredFields($this->model->requiredFields, $dataGroup)) {
			$this->alert->printAlert('system', "FILL_REQUIRED_FIELDS", false);
			$this->view->redirectToPage(generateLink('group', 'register'));
		}

		$returnSave = $this->model->save($dataGroup);
		
		$this->alert->printAlert('project', "REGISTER", $returnSave);
		
		$this->index();
	}

	public function edit(array $url) {
		verifyUserIsLogged();
		$this->validateFillTheId($url, 'group');

		$group = $this->loadGroup($url[2]);

		$this->view->assignVariable('group', $group[0]);
		$this->view->createPage('Group', 'edit');
	}

	private function loadGroup($group_id) {
		$where = [
			'id'      => $group_id,
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
		$this->validateFillTheId($url, 'project');

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
		$this->validateFillTheId($url, 'project');

		$this->loadProject($url[2]);

		$returnDelete = $this->model->update(['active' => '0'], $url[2]);

		$this->alert->printAlert('project', 'DELETE', $returnDelete);
		$this->view->redirectToPage(generateLink('project', 'listProjects'));	
	}
}