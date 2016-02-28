<?php

namespace Core;

class Request {
	function __construct(){
		$url = $this->prepareUrl();

		if (!$this->requestIsValid($url)) {
			$url = $this->mountCallToErrorPage();
		} else if (!isset($url[0]) || empty($url[0])) {
			$url = $this->mountCallToHomePage();
		}

		$controller = $this->instantiateControllerObject($url[0]);
		$controller->{$url[1]}($url);
	}

	private function prepareUrl(){
		$url = (isset($_GET['url']) ? $_GET['url'] : '');
	    $url = filter_var(trim($url), FILTER_SANITIZE_URL);
	    return explode('/', $url);
	}

	private function requestIsValid($url){
	    if((!isset($url[0]) || empty($url[0]))
	    	|| (class_exists($this->prepareClassNameController($url[0])) 
	    		&& method_exists($this->instantiateControllerObject($url[0]), $url[1]))
	    	) {
	        return true;
	    } else {
	    	return false;
	    }
	}

	private function mountCallToErrorPage(){
		$url = [
			0 => 'Error',
			1 => 'pageNotFound',
		];

		return $url;
	}

	private function mountCallToHomePage(){
		if (\Controllers\UserController::isLogged()) {
			$url = [
				0 => 'project',
				1 => 'listProjects'
			];
		} else {
			$url = [
				0 => 'home',
				1 => 'index'
			];
		}

		return $url;
	}
	
	private function instantiateControllerObject($controllerName){
	    $controller = $this->prepareClassNameController($controllerName);
	    return new $controller();
	}

	private function prepareClassNameController($controllerName){
		$controllerName      = ucfirst(strtolower($controllerName));
	    $classNameController = "\\Controllers\\{$controllerName}Controller";
	    return $classNameController;
	}
}

?>