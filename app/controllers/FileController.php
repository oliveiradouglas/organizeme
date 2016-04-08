<?php

namespace Controllers;
use Models\UserModel;
use Helpers\Alert;

class FileController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('file');
		parent::__construct();
	} 

	public function validateFile() {
		try {
			$return['status'] = true;

			$file      = $_FILES['file'];
			$fileValid = $this->model->validateFile($file, true);

			if (!$fileValid) 
				$return = $this->prepareReturnError();

			echo json_encode($return);
		} catch (\Exception $e) {
			echo json_encode($this->prepareReturnError());
		}
	}

	private function prepareReturnError() {
		$return['status']  = false;
		$reutrn['message'] = $_SESSION['alert'];
		return $return;
	}
}

?>