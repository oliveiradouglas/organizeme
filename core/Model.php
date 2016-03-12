<?php

namespace Core;

abstract class Model {
	protected $table;
	protected $requiredFields;

	public function __construct($table) {
		$this->table = $table;
	}	

	public function findAll(){
		$query = $this->prepareBasicQuery(true);

		$returnQuery  = $this->executeQuery($query);			
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}

	public function find($andWhere = [], array $orWhere = []){
		$query = "{$this->prepareBasicQuery(false)} WHERE ";

		if (!empty($andWhere) && !is_array($andWhere)) 	$andWhere = ['id' => $andWhere];
		if (!isset($andWhere['active'])) $andWhere['active'] = 1;
		
		$andWhere = $this->mountStringMap($andWhere, ' AND ');
		$orWhere  = (empty($andWhere) && !empty($orWhere) || empty($orWhere) ? '' : ' OR ') . $this->mountStringMap($orWhere, ' OR ');

		$query       .= "{$andWhere} {$orWhere};";
		$returnQuery  = $this->executeQuery($query);
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}

	protected function prepareBasicQuery($closeQuery){
		$basicQuery = "SELECT * FROM {$this->table}" . ($closeQuery ? ';' : ' ');
		return $basicQuery;
	}

	protected function mountStringMap(array $data, $link){
		$stringMap = '';

		while (list($column, $value) = each($data)) {
			$stringMap .= (!empty($stringMap) ? $link : '') . "{$column} = \"{$value}\"";
		}

		return $stringMap;
	}

	protected function executeQuery($query){
		$mysqli      = Connection::openConnection();
		$returnQuery = $mysqli->query($query);
		Connection::closeConnection($mysqli);

		return $returnQuery;
	}

	protected function createArrayRecords($returnQuery){
		$records = [];

		if (!empty($returnQuery->num_rows)) {
			while ($record = $returnQuery->fetch_assoc()) {
				$records[] = $record;
			}
		}

		return $records;
	}

	public function save(array $data){
		$columns = implode(array_keys($data), ', ');
		$values  = implode(array_filter($data, [$this, 'escape']), ', ');

		$query  = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values});";
		$insert = $this->executeQuery($query);

		if (!$insert) throw new \Exception("Ocorreu algum erro ao cadastrar!");
	}

	private function escape(&$string){
		$string = "\"{$string}\"";
		return $string;
	}

	public function update(array $data, $where){
		if (empty($data) || empty($where)) {
			throw new \Exception("Os parametros passados para função estão incorretos");
		}

		$this->prepareClauseWhere($where);

		$data  = $this->mountStringMap($data, ', ');
		$query = "UPDATE {$this->table} SET {$data} WHERE {$where};";

		$update = $this->executeQuery($query);

		if (!$update) throw new \Exception("Ocorreu algum erro ao editar!");
	}

	private function prepareClauseWhere(&$where) {
		if (!is_array($where)) {
			$where = "id = \"{$where}\"";
		} else {
			$where = $this->mountStringMap($where, ' AND ');
		}
	}

	public function setRequiredField() {
		$nameFields = func_get_args();

		foreach ($nameFields as $nameField) {
			if (!is_string($nameField)) 
				throw new \Exception("O nome do campo deve ser uma string!");
			
			$this->requiredFields[] = $nameField;
		}
	}
	
	protected function validateRequiredFields($requiredFields, $data){
	    foreach ($requiredFields as $requiredField) {
	        if (!isset($data[$requiredField]) || empty($data[$requiredField])) {
	            throw new \Exception("Os campos obrigatórios não foram preenchidos!");
	        }
	    }
	}
}

?>