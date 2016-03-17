<?php

namespace Controllers;
use \Models\UserModel;

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
			$where    = ['user_id' => $_SESSION['user']['id']];
			$projects = $this->model->find($where);
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
			$this->view->createPage('Project', 'register');
			exit();
		}

		try {
			$this->model->create();
			$registred = true;
		} catch (\Exception $e) {
			$registred = false;
		}

		Alert::displayAlert('project', "REGISTER", $registred);
		$this->index();
	}

	public function edit(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');
		$project = $this->model->loadProject($url[2]);

		if (!$this->postExists('project')) {
			$this->view->assignVariable('project', $project);
			$this->view->createPage('Project', 'edit');
			exit();
		}

		try {
			$this->model->edit($project['id']);
			$edited = true;
		} catch (\Exception $e) {
			$edited = false;
		}

		Alert::displayAlert('project', 'EDIT', $edited);
		$this->index();
	}

	public function delete(array $url) {
		UserModel::verifyUserIsLogged();
		$this->validateFillTheId($url, 'project');

		try {
			$this->model->loadProject($url[2]);
			$this->model->update(['active' => '0'], $url[2]);
			$deleted = true;
		} catch (\Exception $e) {
			$deleted = false;
		}

		Alert::displayAlert('project', 'DELETE', $deleted);
		$this->index();
	}
}