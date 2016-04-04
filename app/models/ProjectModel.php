<?php

namespace Models;

class ProjectModel extends \Core\Model {
	public function __construct(){
		parent::__construct('project');
		$this->setRequiredField('name', 'user_id');
	}

	public function searchProjects($projectId = null) {
		$query = "SELECT DISTINCT(p.id) as id, p.name as name, p.description as description, p.user_id as user_id
			FROM project p
			INNER JOIN project_users pu ON pu.project_id = p.id AND pu.active = 1
			WHERE (p.user_id = \"{$_SESSION['user']['id']}\" OR pu.user_id = \"{$_SESSION['user']['id']}\")
			AND p.active = 1";

		$query .= (!empty($projectId) ? " AND p.id = \"{$projectId}\"" : '') . ';';

		$returnQuery  = $this->executeQuery($query);
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;			
	}

	public function loadProject($projectId) {
		$project = $this->searchProjects($projectId);

		if (empty($project)) {
			\Helpers\Alert::displayAlert('project', 'PROJECT_NOT_FOUND', false);
			redirectToPage(generateLink('project', 'listProjects'));
		}

		return $project[0];
	}

	public function create() {
		$dataProject = $this->loadPostProject();
		$this->validateRequiredFields($this->requiredFields, $dataProject);
 		return $this->save($dataProject);
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