<?php
class errorController Extends Controller {
	protected function init(){}

	public function error404(){
		/* template variables */
		$data['title'] = 'Page not found';

	    /* parse variables as an array to template*/
		$this->template->data = $data;
		/* rendering header and footer from error view folder */
		$this->template->render();
	}
}