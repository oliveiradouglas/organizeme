<?php

function filterArrayData($array) {

    if (!is_array($array)) return $array;

    foreach ($array as $index => $value) {
        if (substr($index, 0, 6) == 'date__') {
            $array[substr($index, 6)] = formatDateToDB($value);
            unset($array[$index]);
        } else if (substr($index, 0, 10) == 'password__') {
            $array[substr($index, 10)] = encryptPassword($value);
            unset($array[$index]);
        }
    }

    return $array;
}

function formatDateToDB($dateFormatBR){
	if (!empty($dateFormatBR)) {
		$dateFormatBR = implode('-', array_reverse(explode('/', $dateFormatBR)));
        return $dateFormatBR;
	}
}

function formatDateToBR($dateFormatDB){
	if (!empty($dateFormatDB)) {
        if ($dateFormatDB == '0000-00-00') return '';

		$dateFormatDB = implode('/', array_reverse(explode('-', $dateFormatDB)));
		return $dateFormatDB;
	}
}

function encryptPassword($password){
    if (!empty($password)) {
        $password = base64_encode(sha1($password));
        return $password;
    }
}

function generateLink($controller, $action, array $parameters = []){   
    return DOMAIN . "/{$controller}/{$action}/" . implode('/', $parameters);
}

function verifyUserIsLogged() {
    if (!isset($_SESSION['user'])) {
        $alert = new \Helpers\Alert();
        $alert->printAlert('user', 'USER_NOT_LOGGED', false);
        
        $view = new \Core\View();
        $view->redirectToPage(DOMAIN);
    }
}

?>
