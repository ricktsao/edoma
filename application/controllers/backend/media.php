<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends Backend_Controller{
	
	function __construct() 
	{
		parent::__construct();		
	}
	

	public function index()
	{
		//$this->display("index_view");
		//$this->load->view($this->config->item('backend_name').'/media/media_view');
		
		//return $this->load->view($this->config->item('backend_name').'/template_index_view', $data);
		
		$this->display("index_view");
	}
	

	public function filemanager()
	{
		//$this->display("index_view");
		//$this->load->view($this->config->item('backend_name').'/media/media_view');
		
		//return $this->load->view($this->config->item('backend_name').'/template_index_view', $data);
		$this->load->view('backend/media/media_view');
		//$this->display("media_view");
	}
	
	function test()
	{
	  $this->load->helper('path');
	  $opts = array(
	    // 'debug' => true, 
	    'roots' => array(
	      array( 
	        'driver' => 'LocalFileSystem', 
	        'path'          => set_realpath('upload')."media",     // path to files (REQUIRED)
			'URL'           => site_url('upload').'/media/', // URL to files (REQUIRED)
	        // more elFinder options here
	      ) 
	    )
	  );
	  $this->load->library('elfinderlib', $opts);
	}
	
	
	public function generateTopMenu()
	{		
		$this->addTopMenu("媒體庫 ",array("index"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */