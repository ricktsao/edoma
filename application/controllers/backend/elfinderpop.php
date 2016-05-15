<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ElfinderPop extends Backend_Controller {


	public function index()
	{		
		
		$this->load->view("backend/elfinder_view");
		
		
	}
	
	

	public function generateTopMenu()
	{
	}
	
}