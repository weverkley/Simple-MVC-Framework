<?php

class adminController Extends Controller {
	protected function init(){}

	public function index() {
		$this->template->title = 'Home - Simple-MVC-Framework';
		$this->template->web = 'Home - Simple-MVC-Framework';


		$this->template->loadTemplate('admin');
	}

}
