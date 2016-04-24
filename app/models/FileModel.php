<?php

namespace Models;

class FileModel extends \Core\Model {
	public function __construct(){
		parent::__construct('file');
		$this->setRequiredField('name', 'task_id');
	}

	public function saveAndUploadFile($taskId) {
		$file['name'] = $this->loadPostFile();

		if (empty($file['name']))
			return;

		$file['task_id'] = $taskId;
		$this->validateRequiredFields($file);
 		$this->save($file);

 		move_uploaded_file($_FILES['file']['tmp_name'], PATH_UPLOAD . str_replace("\"", '', $file['name']));
	}

	public function loadPostFile() {
		$file = $_FILES['file'];
		
		if (empty($file['name'])) 
			return;

		$this->validateFile($file, false);
		return sha1(time()) . "-{$file['name']}";
	}

	public function validateFile($file) {
		if ($file['error'] != 0) {
			\Helpers\Alert::displayAlert('file', 'ERROR_UPLOAD_FILE', false);
			redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$validExtensions = ['jpg', 'png', 'gif', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'odt', 'ppt', 'pptx', 'txt'];
		$fileNameInArray = explode('.', $file['name']);
		$extension       = strtolower(end($fileNameInArray));

		if (!in_array($extension, $validExtensions)) {
		  	\Helpers\Alert::displayAlert('file', 'A extensão do arquivo é invalida, utilize as seguintes extensões (' . implode(', ', $validExtensions) . ')!', false);
		  	redirectToPage($_SERVER['HTTP_REFERER']);
		}

		$maxUploadSize = (1024 * 1024 * 5); // 5 MB
		if ($file['size'] > $maxUploadSize) {
			\Helpers\Alert::displayAlert('file', 'EXCEEDED_MAX_SIZE', false);
		  	redirectToPage($_SERVER['HTTP_REFERER']);
		}

		return true;
	}

	public function edit($fileId) {
		$this->validateRequiredFields();
		$this->update($fileId);
	}
}

?>