<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends Backend_Controller{
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper("file");
	}

	

	/**
	 * page list page
	 */
	public function pageList()
	{
		$this->sub_title = $this->lang->line("page_list");		
		
		$page_list = $this->it_model->listData( "html_page" , "page_id != 'index_addr_info' " , $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"asc") );
		$data["list"] = $page_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($page_list["count"],$this->page,$this->per_page_rows,"pages");

		$this->display("page_list_view",$data);
	}





	/**
	 * page edit page
	 */
	public function editPage()
	{
		
		$page_sn = $this->input->get('sn');
			
		$this->sub_title = $this->lang->line("page_form");	
				
		if($page_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'launch' =>1
			);
			$this->display("page_form_view",$data);
		}
		else 
		{
			$page_info = $this->it_model->listData("html_page","sn =".$page_sn);
			if(count($page_info["data"])>0)
			{
				$data["edit_data"] = $page_info["data"][0];				
				$this->display("page_form_view",$data);
			}
			else
			{
				redirect(bUrl("pages"));	
			}
		}
	}
	
	
	/**
	 * 更新page
	 */
	public function updatePage()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
				
		if ( ! $this->_validatepage())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("page_form_view",$data);
		}
        else 
        {
        				
        	$arr_data = array
        	(	
        		  "title" =>  tryGetData("title",$edit_data)     
				, "page_id" =>  tryGetData("page_id",$edit_data)     		
				, "start_date" => date( "Y-m-d" )
				, "end_date" => NULL
				, "forever" => 1	
				, "launch" => 1	
				, "sort" => tryGetData("sort",$edit_data,500)
				, "target" => tryGetData("target",$edit_data)
				, "content" => tryGetData("content",$edit_data)
				, "update_date" => date( "Y-m-d H:i:s" )
			);        	
			
					
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "html_page" , $arr_data, "sn =".$edit_data["sn"] ))
				{					
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}				
			}
			else 
			{			
				
				$page_sn = $this->it_model->addData( "html_page" , $arr_data );
				if($page_sn > 0)
				{				
					$edit_data["sn"] = $page_sn;
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}				
			}			
			
			redirect(bUrl("pageList"));		
        }	
	}
	

	/**
	 * 驗證page edit 欄位是否正確
	 */
	function _validatePage()
	{
	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		

		$this->form_validation->set_rules( 'page_id', "Page ID", 'required|alpha_dash' );	
		$this->form_validation->set_rules( 'title', "單元名稱", 'required' );			
				
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	
	/**
	 * delete page
	 */
	function deletePage()
	{
		$this->deleteItem("html_page", "pageList");
	}

	/**
	 * launch page
	 */
	public function launchItem()
	{		
		$this->ajaxChangeStatus("html_page","launch",$this->input->post("launch_sn", TRUE));
	}



	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu("page", array("pageList","editPage","updatePage"));		
	}
}

/* End of file page.php */