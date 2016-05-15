<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuthEdit extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();
		$this->load->Model("auth_model","main_model");
	}
	
	public function index()
	{
		$admin_sn=$this->session->userdata('admin_sn');
		$data['url']=array("action"=>bUrl('update'));
		
		$this->sub_title = $this->lang->line("admin_form");				
		
		$admin_info = $this->main_model->listData("sys_user", NULL ,"sn =".$admin_sn);
		$data['edit_data']=array();
		if(count($admin_info["data"])==0)
		{			
			redirect(backendControllerUrl());	
		}else{
			$this->display("admin_edit_view",$data);	
		}
		
	}
	
	public function update()
	{
		$admin_sn=$this->session->userdata('user_sn');
		$this->load->library('encrypt');
		$data['url']=array("action"=>bUrl('update'));
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		
		
		if ( ! $this->_validate())
		{				
			$data["edit_data"] = $edit_data;			
			$this->display("admin_edit_view",$data);
		}			
        else 
        {			
        	$arr_data["password"] = prepPassword($edit_data["password"]);
        	$arr_data["updated"] = date("Y-m-d H:i:s");
			$arr_data["is_chang_pwd"] = 1;
      	 	$arr_return=$this->main_model->updateDB( "sys_user" , $arr_data, "sn =".$admin_sn );
			if($arr_return['success']){
				$this->showSuccessMessage();
			}else{
				$this->showFailMessage();	
			}
			
			//redirect(bUrl("index"));		
			redirect(backendUrl());
        }	
	}
	
	
		
	function _validate()
	{				
		$this->form_validation->set_rules('password', $this->lang->line("field_password"), 'trim|required|min_length[4]|max_length[10]' );	
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}

	
	
	
	public function generateTopMenu()
	{
		
		
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu("更改密碼",array("index","update"));
		
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */