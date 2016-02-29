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
	    }

	    return false;
	}

	private function prepareClassNameController($controllerName){
		$controllerName      = ucfirst(strtolower($controllerName));
	    $classNameController = "\\Controllers\\{$controllerName}Controller";
	    return $classNameController;
	}

	private function instantiateControllerObject($controllerName){
	    $controller = $this->prepareClassNameController($controllerName);
	    return new $controller();
	}

	private function mountCallToErrorPage(){
		return ['Error', 'pageNotFound'];
	}

	private function mountCallToHomePage(){
		if (\Models\UserModel::isLogged()) {
			$url = ['project','listProjects'];
		} else {
			$url = ['home', 'index'];
		}

		return $url;
	}
	
}

?>