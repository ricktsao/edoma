<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filetree extends Backend_Controller {	
	
	function __construct() 
	{
		parent::__construct();		
	}
	
	//關於
	public function index()
	{		
		foreach( $_GET as $key => $value )
		{
			$edit_data[$key] = $this->input->get($key,TRUE);			
		}		
		$data["edit_data"] = $edit_data;
		
		$this->load->view($this->config->item('backend_name').'/box/tools/filetree_view',$data);
	}	
	
	public function GenerateTopMenu()
	{
		
	
		
		//$this->AddTopMenu("帳號管理 ",array("admin"));	
	}
	
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */