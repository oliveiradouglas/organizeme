<?php

namespace Core;

abstract class Controller {
	protected $model;
	protected $view;

	public function __construct(){
		$this->view  = new View();
	}

	protected function loadModel($modelName){
		$modelName   = ucfirst(strtolower(trim($modelName))) . 'Model';
		$modelName   = "\\Models\\{$modelName}";
		$this->model = new $modelName();
	}

	protected function validateFillTheId(array $url, $nameController, $url3 = false) {
		if (!isset($url[2]) || empty($url[2])) {
			Alert::displayAlert(strtolower($nameController), strtoupper($nameController) . '_ID', false);
			redirectToPage(DOMAIN);
		}

		if ($url3 !== false && (!isset($url[3]) || empty($url[3]))) {
			Alert::displayAlert(strtolower($url3), strtoupper($url3) . '_ID', false);
			redirectToPage(DOMAIN);
		}
	}

	protected function postExists($nameIndexPost){
		return (isset($_POST[$nameIndexPost]) ? true : false);
	}	
}

?>