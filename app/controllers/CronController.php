<?php

namespace Controllers;

class CronController extends \Core\Controller {

	public function rememberExpirationOfTask(){
		$this->loadModel('task');
		$tasks = $this->model->loadTasksToRemember();

		if (!empty($tasks)) {
			$mail = new \Helpers\Email();

			foreach ($tasks as $task) {
				if ($this->verifyDateToSendEmail($task['days_to_remember'], $task['due_date'])) {
					$mail->assignVariable('task', $task);
					$mail->setTemplate('remember_task');
					$creator = $this->fillUserData($task);
					$mail->sendMail($creator, 'Vencimento de tarefa');
					$mail->clearAddresses();
				}
			}
		}

		echo "Fim";
	}

	private function verifyDateToSendEmail($daysToRemove, $baseDate) {
		$dateToSendEmail = strtotime("{$baseDate} -{$daysToRemove} days");
		$today           = strtotime(date('Y-m-d'));

		return (($dateToSendEmail == $today) ? true : false);
	}

	private function fillUserData($data) {
		$user = [
			'name'  => $data['user_name'],
			'email' => $data['user_email']
		];

		return $user;
	}
}

?>