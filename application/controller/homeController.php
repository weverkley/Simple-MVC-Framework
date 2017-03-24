<?php
class homeController Extends Controller {
	protected function init(){}

	public function index() {
		/* template variables */
		$data['title'] = 'Home - Simple-MVC-Framework';

		/*$data['user'] = $this->model->getUser();*/

	    /* parse variables as an array to template*/
		$this->template->data = $data;
		/* 
		* if you dont want to render header and footer from another folder
		* jus leave blank to render header and footer in the same folder
		* Ex: $this->template->render();
		* haeader.php and footer.php must be at the same view folder.
		*/
		$this->template->render('shared');
	}

	public function about(){
		/* template variables */
		$data['title'] = 'About - Simple-MVC-Framework';

	    /* parse variables as an array to template*/
		$this->template->data = $data;
		$this->template->render('shared');
	}
}