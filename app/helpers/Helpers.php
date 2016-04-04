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

function redirectToPage($link){
    header("Location: {$link}");
    exit();
}

function showErrorPage() {
    $view = new \Core\View();
    $view->createPage('error', 'pageNotFound');
    exit();
}

function debug($variable, $continue = false) {
    if (is_array($variable)) {
        echo "<pre>";
        print_r($variable);
    } else {
        var_dump($variable);
    }
    
    if (!$continue) exit();
}

function uniqueMultidimArray($array, $key) { 
    $temp_array = []; 
    $key_array  = []; 
    $i = 0; 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
} 

?>