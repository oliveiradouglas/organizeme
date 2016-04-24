<?php

namespace Controllers;

class CronController extends \Core\Controller {

	public function rememberExpirationOfTask(){
		$this->loadModel('task');
		$tasks = $this->model->loadTasksToRemember();

		$mail = new \Helpers\Email();
		foreach ($tasks as $task) {
			if ($this->verifyDateToSendEmail($task['days_to_remember'], $task['due_date'])) {
				$mail->assignVariable('task', $task);
				$mail->setTemplate('remember_task');

				$for = [
					$this->fillUserData($task, 'creator'),
					$this->fillUserData($task, 'performer')
				];

				$mail->sendMail($for, 'Vencimento de tarefa');
				$mail->clearAddresses();
			}
		}

		echo "Fim";
	}

	private function verifyDateToSendEmail($daysToRemove, $baseDate) {
		$dateToSendEmail = strtotime("{$baseDate} -{$daysToRemove} days");
		$today           = strtotime(date('Y-m-d'));

		return (($dateToSendEmail == $today) ? true : false);
	}

	private function fillUserData($data, $type) {
		$user = [
			'name'  => $data["{$type}_name"],
			'email' => $data["{$type}_email"]
		];

		return $user;
	}
}

?>