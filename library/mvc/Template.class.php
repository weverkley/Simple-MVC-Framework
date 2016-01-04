<?php
class Template {
	private $vars = array();
	private $controller;
	private $action;

	public function __construct($controller, $action){
		$this->controller = $controller;
		$this->action = $action;
	}

	public function __set($index, $value) {
	    $this->vars[$index] = $value;
	}

	public function render() {
		extract($this->vars);
		$path = ROOT.DS.'application'.DS.'view'.DS;
		$file = $path.$this->controller.DS.$this->action.'.php';

		if ($this->action == 'error404') 
			$file = $path.'error'.DS.$this->action.'.php';

 		if (!file_exists($file)) 
 			$file = $path.$this->controller.DS.'index.php';

		require_once $path.'shared'.DS.'header.php';

        require_once $file;       
             
        require_once $path.'shared'.DS.'footer.php';
    }
}