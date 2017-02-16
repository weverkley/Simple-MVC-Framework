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
		if(class_exists($model)) 
			$this->model = new $model($this->db);

		$this->controller = $controller;
		$this->params = $params;
		$this->template = new Template($controller, $this->model, $method);
		$this->init();
	}

	/**
    * Initialize the required classes and variables
    */
    protected function init(){
     /* Put your code here*/
    }

    public function __destruct() {}
}