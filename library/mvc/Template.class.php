<?php
class Template {
	/*
	 * @Variables array
	 * @access private
	 */
	private $vars = array();
	private $controller;
	private $action;

	/**
	 *
	 * @constructor
	 *
	 * @access public
	 *
	 * @return void
	 *
	 */
	public function __construct($controller, $action){
		$this->controller = $controller;
		$this->action = $action;
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
	*
	* @param string $shared_folder (Folder containing header and footer for each action)
	*
	* @return void
	*
	*/
	public function render($shared_folder = null) {
		extract($this->vars);
		$path = ROOT.DS.'application'.DS.'view'.DS;
		$header = (file_exists($path.$this->controller.DS.'header.php'))? $path.$this->controller.DS.'header.php' : $path.$this->controller.DS.'header.html';
		$file = (file_exists($path.$this->controller.DS.$this->action.'.php'))? $path.$this->controller.DS.$this->action.'.php' : $path.$this->controller.DS.$this->action.'.html';

		if ($this->action == 'error404') 
			$file = (file_exists($path.'error'.DS.$this->action.'.php'))? $path.'error'.DS.$this->action.'.php' : $path.'error'.DS.$this->action.'.html';

 		if (!file_exists($file)) 
 			$file = (file_exists($path.$this->controller.DS.'index.php'))? $path.$this->controller.DS.'index.php' : $path.$this->controller.DS.'index.html';

		$footer = (file_exists($path.$this->controller.DS.'footer.php'))? $path.$this->controller.DS.'footer.php' : $path.$this->controller.DS.'footer.html';
        if (file_exists($header)) 
            require_once ($header);
        else
            require_once (file_exists($path.$shared_folder.DS.'header.php'))? $path.$shared_folder.DS.'header.php' : $path.$shared_folder.DS.'header.html';

        require_once ($file);       
             
        if (file_exists($footer))
            require_once ($footer);
        else
            require_once (file_exists($path.$shared_folder.DS.'footer.php'))? $path.$shared_folder.DS.'footer.php' : $path.$shared_folder.DS.'footer.hmtl';
    }

	public function show($name) {
		$path = (file_exists(ROOT.DS.'application'.DS.'view'.DS.$name.'.php'))? ROOT.DS.'application'.DS.'view'.DS.$name.'.php' : ROOT.DS.'application'.DS.'view'.DS.$name.'.html';

		if (!file_exists($path)) {
			throw new Exception('Template not found in '. $path);
			return false;
		}

		// Load variables
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		require_once $path;               
	}
}