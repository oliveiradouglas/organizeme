<?php

namespace Controllers;

class ErrorController extends \Core\Controller {

	public function pageNotFound(){
		$this->view->createPage('error', 'pageNotFound');
	}
}

?>