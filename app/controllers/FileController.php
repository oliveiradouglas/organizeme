<?php

namespace Controllers;

class FileController extends \Core\Controller {
	public function __construct(){
		$this->loadModel('file');
		parent::__construct();
	}

	/**
	 * $url[2] = id do arquivo no bd 
	 * $url[3] = nome do arquivo no bd
	 */
	public function download($url) {
		if (empty($url[2]) || empty($url[3])) 
			$this->processDownloadError();

		$file = $this->model->find(['id' => $url[2], 'name' => $url[3]]);
		if (empty($file)) 
			$this->processDownloadError();

		$fileOnServer = PATH_UPLOAD . $file[0]['name'];
		if (!file_exists($fileOnServer)) 
			$this->processDownloadError();

		$realFileName = explode('-', $file[0]['name'])[1];
		$this->setHeadersToDownload($realFileName, $fileOnServer);
		readfile($fileOnServer);
	}

	private function processDownloadError() {
		\Helpers\Alert::displayAlert('file', 'ERROR_DOWNLOAD', false);
		$linkRedirect = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : generateLink('project', 'listProjects'));
		redirectToPage($linkRedirect);
	}

	private function setHeadersToDownload($realFileName, $fileOnServer) {
		header('Content-Description: File Transfer');
		header("Content-Disposition: attachment; filename='{$realFileName}'");
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($fileOnServer));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Expires: 0');
	}
}

?>