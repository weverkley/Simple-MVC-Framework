<?php
class errorController Extends Controller {
	public function error404(){
		/* template variables */
		$data['title'] = 'Page not found';

	    /* parse variables as an array to template*/
		$this->template->data = $data;
		$this->template->render();
	}
}