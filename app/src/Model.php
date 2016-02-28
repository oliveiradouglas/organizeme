<?php

namespace Core;

abstract class Model {
	protected $table;
	protected $connection;

	public function __construct(){
		$this->connection = new \Core\Connection();
	}

	public function findAll(){
		$query = $this->prepareBasicQuery(true);

		$returnQuery  = $this->executeQuery($query);			
		$arrayRecords = $this->createArrayRecords($returnQuery);

		return $arrayRecords;
	}

	public function find($andWhere = [], array $orWhere = []){
		$query = "{$this->prepareBasicQuery(false)} WHERE ";

		if (!empty($andWhere) && !is_array($andWhere)) {
			$andWhere = ['id' => $andWhere];
		}
		
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
		$mysqli      = $this->connection->openConnection();
		$returnQuery = $mysqli->query($query);
		$this->connection->closeConnection($mysqli);

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
		$values  = implode(array_filter($data, array($this, 'escape')), ', ');

		$query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values});";

		$insert = $this->executeQuery($query);

		if (!$insert) return false;

		return true;
	}

	private function escape(&$string){
		$string = "\"{$string}\"";
		return $string;
	}

	public function update(array $data, $where){

		if (empty($data) || empty($where)) {
			return false;
		}

		$this->prepareClauseWhere($where);

		$data = $this->mountStringMap($data, ', ');

		$query = "UPDATE {$this->table} SET {$data} WHERE {$where};";

		$returnUpdate = $this->executeQuery($query);

		return $returnUpdate;
	}

	private function prepareClauseWhere(&$where) {
		if (!is_array($where)) {
			$where = "id = \"{$where}\"";
		} else {
			$where = $this->mountStringMap($where, ' AND ');
		}
	}
}

?>