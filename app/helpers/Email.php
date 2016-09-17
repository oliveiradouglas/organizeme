<?php

namespace Helpers;

class Email extends \PHPMailer {
	private $assignVariables = [];
	private $template;

	public function __construct() {
		$this->setLanguage('pt', PATH_ROOT . 'vendor/phpmailer/phpmailer/language/');
		$this->isSMTP();

		$this->CharSet    = 'UTF-8';
		$this->FromName   = 'OrganizeME';
		$this->Host       = 'smtp.gmail.com';
		$this->Port       = 587;
		$this->SMTPSecure = 'tls';
		$this->SMTPAuth   = true;
	}

	public function setTemplate($templateName){
		$this->template = $this->mountTemplateEmail($templateName);
	}

	public function mountTemplateEmail($templateName){
		extract($this->assignVariables);
		
		ob_start();
		include_once(PATH_ROOT . '/app/helpers/templates/' . $templateName . '.php');
		$body = ob_get_contents();
		ob_end_clean();

		return $body;
	}

	public function assignVariable($variableName, $value){
		$this->assignVariables[$variableName] = $value;
	}

	public function sendMail(array $for, $subject){
		$this->fillRecipients($for);
		$this->isHTML(true);

		$this->Subject = $subject;
		$this->Body    = $this->template;

		return $this->Send();
	}

	public function fillRecipients($for) {
		if (isset($for[0])) {
			foreach ($for as $to) {
				$this->addAddress($to['email'], $to['name']);
			}
		} else {
			$this->addAddress($for['email'], $for['name']);
		}
	}


}

?>