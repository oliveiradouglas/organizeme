<?php

namespace Models;

class ProjectModel extends \Core\Model {
	public function __construct(){
		$this->table = 'project';
		parent::__construct();
	}

	public $requiredFields = ['name', 'user_id'];

	public function searchProjects() {
		$query = "SELECT *
					FROM project p
					INNER JOIN user u
					ON p.user_id = u.id
					WHERE p.user_id = {$_SESSION['user']['id']}
					AND p.active = 1";
	}
}

?>