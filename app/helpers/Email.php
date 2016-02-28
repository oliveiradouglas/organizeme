<?php

namespace Helpers;

class Email extends \PHPMailer {
	private $assignVariables = [];
	private $template;

	public function __construct() {
		$this->setLanguage('pt', PATH_ROOT . 'vendor/phpmailer/phpmailer/language/');
		$this->isSMTP();
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
		if (is_array($for['email'])) {
			foreach ($for['email'] as $to) {
				$this->addAddress($to);
			}
		} else {
			$this->addAddress($for['email'], $for['name']);
		}
	}


}

?>