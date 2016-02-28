<?php

namespace Models;

class GroupModel extends \Core\Model {
	public function __construct(){
		$this->table = 'group';
		parent::__construct();
	}

	public $requiredFields = ['name'];
}

?>