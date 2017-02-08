<?php
class Model {
	public $db;

	public function __construct($db) {
		$this->db = $db;
		$this->init();
	}

	/**
    * Initialize the required classes and variables
    */
    protected function init(){
     /* Put your code here*/
    }
}