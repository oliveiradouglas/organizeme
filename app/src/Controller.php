<?php

namespace Core;

abstract class Controller {
	protected $model;
	protected $view;
	protected $alert;

	public function __construct(){
		$this->alert = new \Helpers\Alert();
		$this->view  = new View();
	}

	protected function loadModel($modelName){
		$modelName = ucfirst(strtolower(trim($modelName))) . 'Model';
		$modelName = "\\Models\\{$modelName}";
		$this->model = new $modelName();
	}

	protected function validateId(array $url, $nameController, $url3 = false) {
		if (!isset($url[2]) || empty($url[2])) {
			$this->alert->printAlert(strtolower($nameController), strtoupper($nameController) . '_ID', false);
			$this->view->redirectToPage(DOMAIN);
		}

		if ($url3 !== false && (!isset($url[3]) || empty($url[3]))) {
			$this->alert->printAlert(strtolower($url3), strtoupper($url3) . '_ID', false);
			$this->view->redirectToPage(DOMAIN);
		}
	}
}

?>