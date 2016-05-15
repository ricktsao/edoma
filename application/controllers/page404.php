<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page404 extends CI_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
			
		$this->load->view('frontend/page404/page_view', array());
	}
	

	
		
}

