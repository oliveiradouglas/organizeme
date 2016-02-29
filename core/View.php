<?php

namespace Core;

class View {
	private $assignVariables = [];

	public function createPage($controllerName, $functionName){
		extract($this->assignVariables);
		$controllerName = ucfirst($controllerName);

		require_once(PATH_ROOT . "app/views/Container/header.php");
		require_once(PATH_ROOT . "app/views/{$controllerName}/{$functionName}/{$functionName}.php");	
		require_once(PATH_ROOT . "app/views/Container/footer.php");
	}

	public function assignVariable($variableName, $value){
		$this->assignVariables[$variableName] = $value;
	}

	public function redirectToPage($link){
		header("Location: {$link}");
		exit();
	}
}

?>