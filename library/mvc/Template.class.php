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
	public function render($shared_folder) {
		extract($this->vars);
		$path = ROOT.DS.'application'.DS.'view'.DS;
		$header = $path.$this->controller.DS.'header.php';
		$file = $path.$this->controller.DS.$this->action . '.php';

		if ($this->action == 'error404') 
			$file = $path.'error'.DS.$this->action . '.php';

 		if (!file_exists($file)) 
 			$file = $path.$this->controller.DS.'index.php';

		$footer = $path.$this->controller.DS.'footer.php';
        if (file_exists($header)) 
            require_once ($header);
        else
            require_once $path.$shared_folder.DS.'header.php';

        require_once ($file);       
             
        if (file_exists($footer))
            require_once ($footer);
        else
            require_once $path.$shared_folder.DS.'footer.php';
    }

	public function show($name) {
		$path = ROOT.DS.'application'.DS.'view'.DS.$name.'.php';

		if (!file_exists($path))
		{
			throw new Exception('Template not found in '. $path);
			return false;
		}

		// Load variables
		foreach ($this->vars as $key => $value)
		{
			$$key = $value;
		}

		require_once $path;               
	}
}