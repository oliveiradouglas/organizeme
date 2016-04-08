<?php

namespace Models;

class FileModel extends \Core\Model {
	public function __construct(){
		parent::__construct('file');
		$this->setRequiredField('name', 'task_id');
	}

	public function create($taskId) {
		$file['name']   = $this->loadPostFile();
		$file['taskId'] = $taskId;

		$this->validateRequiredFields($this->requiredFields);
 		return $this->save();
	}

	public function loadPostFile() {
		$file = $_FILES['file'];
		$this->validateFile($file, false);
		return sha1(time()) . "-{$file['name']}";
	}

	public function validateFile($file, $internalCall) {
		if ($file['error'] != 0) {
			\Helpers\Alert::displayAlert('file', 'ERROR_UPLOAD_FILE', false);
			if ($internalCall) return false;
			redirectToPage($_SESSION['HTTP_REFERER']);
		}

		$validExtensions = ['jpg', 'png', 'gif', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'odt', 'ppt', 'pptx'];
		$extension       = strtolower(end(explode('.', $file['name'])));

		if (!in_array($extension, $validExtensions)) {
		  	\Helpers\Alert::displayAlert('file', 'A extensão do arquivo é invalida, utilize as seguintes extensões (' . implode(', ', $validExtensions) . ')!', false);
		  	if ($internalCall) return false;
		  	redirectToPage($_SESSION['HTTP_REFERER']);
		}

		$maxUploadSize = (1024 * 1024 * 5); // 5 MB
		if ($file['size'] > $maxUploadSize) {
			\Helpers\Alert::displayAlert('file', 'EXCEEDED_MAX_SIZE', false);
			if ($internalCall) return false;
		  	redirectToPage($_SESSION['HTTP_REFERER']);
		}

		return true;
	}

	public function edit($fileId) {
		$this->validateRequiredFields($this->requiredFields);
		$this->update($fileId);
	}
}

?>