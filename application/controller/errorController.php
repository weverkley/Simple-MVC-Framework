<?php
class errorController Extends Controller {
	protected function init(){}

	public function error404(){
	    $this->template->title = 'Page not found';

		$this->template->loadTemplate('home');
	}
}
