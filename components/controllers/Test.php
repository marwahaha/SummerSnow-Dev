<?php

class Test extends Controller {

	public function index() {
		//$this->load->helper("Test");
		//go6o();
		
		//$this->load->model("Test_model");
		
		print_r($this);
		
		//$this->test_model->a();
		
		//echo $this->route->segment(3);
		//echo isset($this->route->params['view']) ? "da" : "ne";
		
		//show_404();
	}
	
	public function index2() {
		global $_DATA;
		print_r($_DATA);
		echo "Hello world2!";
	}

}