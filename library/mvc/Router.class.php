<?php
class Router {
    public static $c = 0;
	private $controller;
	private $action;
	private $params = array();

	public function __construct(){
        /* check how many time this object was instantiated */
        self::$c++;

        $default = DEFAULT_CONTROLLER;
        $url = isset($_GET['route'])? $_GET['route'] : $default;
        $url = explode('/', $url);
        $url = array_filter($url);

        $this->controller = strtolower(array_shift($url));
        $this->action = strtolower(array_shift($url));  
        $this->params = $url;

        $this->action = (empty($this->action))? 'index' : $this->action;
	}

	public function dispatch(){
        $controller = self::getController().'Controller';
        $route = ROOT.DS.'application'.DS.'controller'.DS.$controller.'.php';
        $action = self::getAction();
        $params = self::getParams();

        if(is_readable($route)){
            $controller = new $controller(self::getController(), $action, $params);
            
            if(!is_callable(array($controller, $action))){
                $this->action = 'index';
            }

            if(isset($params)){
                call_user_func_array(array($controller, self::getAction()), self::getParams());
            }
            else{
                call_user_func(array($controller, self::getAction()));
            }
        }
        else {
            $controller = new errorController('error', 'error404', array());
            $controller->error404();
        }
	}

    public function getController(){
        return $this->controller;
    }
    
    public function getAction(){
        return $this->action;
    }
    
    public function getParams(){
        return $this->params;
    }
}