<?php

namespace Models;

class UserModel extends \Core\Model {
	public function __construct(){
		$this->table = 'user';
		parent::__construct();
	}

	public $requiredFields = ['name', 'email', 'password'];
}

?>