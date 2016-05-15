<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newscat extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	/**
	 * news list page
	 */
	public function contentList()
	{				
		
		$list = $this->c_model->GetList( "newscat" , "" ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"desc") );
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		//dprint($data["pager"]);
		
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{
		
		
		$content_sn = $this->input->get('sn');
			
		$this->sub_title = "news edit";
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "newscat",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$news_info = $this->c_model->GetList( "newscat" , "sn =".$content_sn);
			
			if(count($news_info["data"])>0)
			{
				img_show_list($news_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $news_info["data"][0];			

				$this->display("content_form_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}
		}
	}
	
	
	public function updateContent()
	{	
		$edit_data = $this->dealPost();
						
		if ( ! $this->_validateContent())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			
			deal_img($edit_data ,"img_filename",$this->router->fetch_class());			
			
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "web_menu_content" , $edit_data, "sn =".$edit_data["sn"] ))
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
									
				$edit_data["create_date"] =   date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "web_menu_content" , $edit_data );
				if($content_sn > 0)
				{				
					$edit_data["sn"] = $content_sn;
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
	
			}
			
			redirect(bUrl("contentList"));	
        }	
	}
	
	/**
	 * 驗證newsedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteContent()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "web_menu_content",NULL,$del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("contentList", FALSE));	
	}


	public function launchContent()
	{		
		$this->ajaxChangeStatus("web_menu_content","launch",$this->input->post("content_sn", TRUE));
	}


	
	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */