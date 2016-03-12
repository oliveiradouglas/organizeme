<?php

namespace Models;

class ProjectModel extends \Core\Model {
	public function __construct(){
		parent::__construct('project');
		$this->setRequiredField('name', 'user_id');
	}

	public function searchProjects() {
		$query = "SELECT *
					FROM project p
					INNER JOIN user u
					ON p.user_id = u.id
					WHERE p.user_id = {$_SESSION['user']['id']}
					AND p.active = 1";
	}

	public function loadProject($projectId) {
		$where = [
			'id'      => $projectId,
			'user_id' => $_SESSION['user']['id'],
		];

		$project = $this->find($where);

		if (empty($project)) {
			$alert = new \Helpers\Alert();
			$alert->printAlert('project', 'PROJECT_NOT_FOUND', false);

			$view = new \Core\View();
			$view->redirectToPage(generateLink('project', 'listProjects'));
		}

		return $project[0];
	}

	public function create() {
		$dataProject = $this->loadPostProject();
		$this->validateRequiredFields($this->requiredFields, $dataProject);
		$this->save($dataProject);
	}

	public function edit($projectId) {
		$dataProject = $this->loadPostProject();
		$this->validateRequiredFields($this->requiredFields, $dataProject);
		$this->update($dataProject, $projectId);
	}

	private function loadPostProject() {
		$dataProject            = $_POST['project'];
		$dataProject['user_id'] = $_SESSION['user']['id'];

		return $dataProject;
	}
}

?>