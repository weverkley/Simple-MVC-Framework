<?php
class homeController Extends Controller {
	protected function init(){}

	public function index() {
		/* template variables */
		$this->template->title = 'Home - Simple-MVC-Framework';

		$this->template->loadTemplate('home');
	}

	public function about(){
		$this->template->title = 'About - Simple-MVC-Framework';

		$this->template->loadTemplate('home');
	}
}
