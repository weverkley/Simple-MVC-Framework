<?php
class Controller {
	public $db;
	public $controller;
	public $model;
	public $params;
	public $template;
	protected $registry;

	public function __construct($controller = null, $model = null, $params = array()){
		//$this->registry = Registry::getInstance();
		$method = $model;
		$model = $controller.'Model';
		$this->controller = $controller;
		$this->params = $params;
		$this->template = new Template($controller, $method);
		$this->init();
		if(class_exists($model)) 
			$this->model = new $model($this->db);
	}

	/**
    * Initialize the required classes and variables
    */
    protected function init(){
     /* Put your code here*/
    }

    public function __destruct() {}
}