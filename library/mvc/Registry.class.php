<?php
class Registry {

	/*
	* @the vars array
	* @access private
	*/
	private $vars = array();
	private static $instance;

	public static function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance = new Registry;
		}
		return self::$instance;
	}

	/**
	*
	* @set undefined vars
	*
	* @param string $index
	*
	* @param mixed $value
	*
	* @return void
	*
	*/

	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	/**
	*
	* @get variables
	*
	* @param mixed $index
	*
	* @return mixed
	*
	*/

	public function __get($index) {
		return $this->vars[$index];
	}
}