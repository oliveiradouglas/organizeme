<?php

namespace Models;

class ProjectUsersModel extends \Core\Model {
	public function __construct(){
		parent::__construct('project_users');
		$this->setRequiredField('project_id', 'user_id');
	}

	public function saveProjectUsers($projectId) {
		if (!isset($_POST['project_users'])) {
			return;
		}

		foreach ($_POST['project_users'] as $userId) {
			if (empty($userId)) continue;
			
			$projectUser = [
				'project_id' => $projectId,
				'user_id'    => $userId,
			];

			$this->save($projectUser);
		}
	}

	public function loadProjectUsers($projectId) {
		$query = "SELECT pu.id, u.id as user_id, u.name as user_name 
			FROM {$this->table} pu 
			INNER JOIN user u ON pu.user_id = u.id
			WHERE pu.project_id = \"{$projectId}\"
			AND pu.active = 1
			AND u.active = 1;";

		$returnQuery  = $this->executeQuery($query);
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}

	public function currentUserRelatedToTheProject($projectId) {
		$where       = ['project_id' => $projectId, 'user_id' => $_SESSION['user']['id']];
		$userProject = $this->find($where);

		return !empty($userProject);
	}

	public function verifyCurrentUserRelatedToTheProject($projectId) {
		$projectModel = new ProjectModel();
		$project      = $projectModel->find(['id' => $projectId, 'user_id' => $_SESSION['user']['id']]);

		if (!$this->currentUserRelatedToTheProject($projectId) && empty($project)) {
			\Helpers\Alert::displayAlert('system', 'ERROR_OPERATION', false);
			redirectToPage(generateLink('project', 'listProjects'));
		}
	}
}

?>