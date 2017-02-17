<?php
class Registry {

	/*
	* @the vars array
	* @access private
	*/
	private $vars = array();

	/*
	* @the instance array
	* @access private static
	*/
	private static $instance = array();

	/**
    * Check if the class is already an instance
    * 
    * @param object $class
    */
	public static function getInstance($class){
        if(isset(self::$instance[$class]))
            return self::$instance[$class];
        else
        { 
            self::$instance[$class] = new $class();
            return self::$instance[$class];
        }   
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