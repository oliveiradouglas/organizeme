<?php

namespace Controllers;

class HomeController extends \Core\Controller {
	public function index(){
		$this->view->createPage('home', 'index');
	}
}

?>