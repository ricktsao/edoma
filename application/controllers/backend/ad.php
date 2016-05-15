<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	


	public function contentList()
	{						
		$condition = "";
		
		$list = $this->c_model->GetList2( "ad" , $condition ,FALSE, $this->per_page_rows , $this->page , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{		
		
		$content_sn = $this->input->get('sn');
			
		$cat_list = $this->c_model->GetList( "ad" , "" ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		$data["cat_list"] = $cat_list["data"];
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "ad",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$ad_info = $this->c_model->GetList( "ad" , "sn =".$content_sn);
			
			if(count($ad_info["data"])>0)
			{
				img_show_list($ad_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $ad_info["data"][0];			

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
						
						
		if(isNotNull($edit_data["sn"]))
		{				
			if($this->it_model->updateData( "web_menu_content" , $edit_data, "sn =".$edit_data["sn"] ))
			{					
				$img_filename = $this->uploadImage($edit_data["sn"]);					
				$edit_data["img_filename"] = $img_filename;
				
				$this->sync_to_server($edit_data);
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
				$img_filename =$this->uploadImage($content_sn);
				$edit_data["img_filename"] = $img_filename;
				
				$edit_data["sn"] = $content_sn;
				$this->sync_to_server($edit_data);
			
				
				$this->showSuccessMessage();							
			}
			else 
			{
				$this->showFailMessage();					
			}	
		
		
		}
			redirect(bUrl("contentList"));	
        	
	}
	
	
	
	//圖片處理
	private function uploadImage($content_sn)
	{
		$img_filename = "";
		if(isNull($content_sn))
		{
			return;
		}
		//dprint($_FILES);exit;
		if(isNotNull($_FILES['img_filename']['name']))
		{
			$folder_name = $this->router->fetch_class();
			
			//圖片處理 img_filename				
			$img_config['resize_setting'] =array($folder_name=>array(1024,1024));					
			$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
			move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
			
			$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
				
			//社區同步資料夾
			$img_config['resize_setting'] =array($folder_name=>array(500,500));
			resize_img($uploadedUrl,$img_config['resize_setting'],$this->getCommId(),$img_filename);
			
			@unlink($uploadedUrl);	

			$this->it_model->updateData( "web_menu_content" , array("img_filename"=> $img_filename), "sn = '".$content_sn."'" );
			
			$orig_img_filename = $this->input->post('orig_img_filename');
			
			@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
			@unlink(set_realpath("upload/".$this->getCommId()."/".$folder_name).$orig_img_filename);	
			
			//檔案同步至server
			$this->sync_file($folder_name);
		}
		return $img_filename;
	}
	
	
	
	/**
	 * 驗證bulletinedit 欄位是否正確
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
		
		$del_ary = tryGetData("del",$_POST,array());				

		//社區主機刪除
		//----------------------------------------------------------------------------------------------------
		$sync_sn_ary = array();//待同步至雲端主機 array
		foreach ($del_ary as  $content_sn) 
		{
			$result = $this->it_model->updateData( "web_menu_content" , array("del"=>1,"is_sync"=>0,"update_date"=>date("Y-m-d H:i:s")), "sn ='".$content_sn."'" );
			if($result)
			{
				array_push($sync_sn_ary,$content_sn);
			}						
		}
		//----------------------------------------------------------------------------------------------------
				
		//社區主機同步
		//----------------------------------------------------------------------------------------------------
		foreach ($sync_sn_ary as  $content_sn) 
		{			
			$query = "SELECT SQL_CALC_FOUND_ROWS * from web_menu_content where sn =	'".$content_sn."'";			
			$content_info = $this->it_model->runSql($query);
			if($content_info["count"] > 0)
			{
				$content_info = $content_info["data"][0]; 
				
				
				$this->sync_to_server($content_info);
				
				//dprint($content_info);exit;
								
			}			
		}		
		//----------------------------------------------------------------------------------------------------

		
		$this->showSuccessMessage();
		
		redirect(bUrl("contentList", FALSE));	
	}

	public function launchContent()
	{		
		$this->ajaxlaunchContent($this->input->post("content_sn", TRUE));
	}
	

	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */