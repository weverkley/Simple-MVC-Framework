<?php
class homeModel extends Model{
	protected function init(){
		$this->db = Registry::getInstance('Database');
	}

	public function getUser(){
		$result = $this->db->select('*', 'user');
		$result = $this->db->numRows($result);
		return $result;
	}
}