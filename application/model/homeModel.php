<?php
class blogModel extends Model{
	protected function init(){

	}

	public function getUser(){
		$result = $this->db->select('*', 'user');
		$result = $this->db->numRows($result);
		return $result;
	}
}