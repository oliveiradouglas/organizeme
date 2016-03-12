<?php

namespace Core;

class Connection {
	public static function openConnection(){
		$mysqli = new \MySQLi(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);
		
		if ($mysqli->connect_errno) {
			echo "Erro ao conectar-se ao banco de dados.";
			die();
		}

		return $mysqli;
	}

	public static function closeConnection($connection){
		$close = $connection->close();

		if(!$close){
			echo "Erro ao fechar a conexão";
		}
	}
}

?>