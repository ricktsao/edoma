<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_log extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
	}
	


	/**
	 * sys_news list page
	 */
	public function contentList()
	{			
		$condition = "";
		$comm_list = $this->it_model->listData("community","status = 1");
		
		foreach ($comm_list["data"] as $key => $comm_info) 
		{
			
			$query = "SELECT SQL_CALC_FOUND_ROWS *	FROM sys_user WHERE comm_id = '5tgb4rfv' and role = 'I' and app_id is not null and app_id != '' and launch = 1";
	
			$user_list = $this->it_model->runSql( $query);

			
		}
		
		
		//$list = $this->c_model->GetList( "sys_news" , $condition ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		//dprint($list);
		//img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $comm_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{
		
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");	
		
		$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
		$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
		
		$this->addCss("css/bootstrap-fonts.css");
				
		
		$content_sn = $this->input->get('sn');
		
			
		//社區
		$community_list = $this->it_model->listData("community","status =1",NULL,NULL,array("name"=>"asc"));
		$data["community_list"] = $community_list["data"];	
		
		
		
		
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "sys_news",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			$sys_news_info = $this->c_model->GetList( "sys_news" , "sn =".$content_sn);
			
			if(count($sys_news_info["data"])>0)
			{
				img_show_list($sys_news_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $sys_news_info["data"][0];			

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
		
		$comm_id_ary = tryGetData("comms", $_POST,array());
		$edit_data["comm_id"] = implode(",", $comm_id_ary);
		
		//dprint($edit_data["comm_id"]);exit;
		
		if ( ! $this->_validateContent())
		{
			$this->addCss("css/chosen.css");
			$this->addJs("js/chosen.jquery.min.js");	
			
			$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
			$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
			
			$this->addCss("css/bootstrap-fonts.css");
			
			//社區
			$community_list = $this->it_model->listData("community","status =1",NULL,NULL,array("name"=>"asc"));
			$data["community_list"] = $community_list["data"];	
			
			
			
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			
			
			
			if(isNotNull($edit_data["sn"]))
			{				
				if($this->it_model->updateData( "edoma_content" , $edit_data, "sn =".$edit_data["sn"] ))
				{					
					$img_filename = $this->uploadImage($edit_data["sn"]);					
					$edit_data["img_filename"] = $img_filename;
					
					//$this->sync_to_server($edit_data);
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
				
				$content_sn = $this->it_model->addData( "edoma_content" , $edit_data );
				if($content_sn > 0)
				{
					$img_filename =$this->uploadImage($content_sn);
					$edit_data["img_filename"] = $img_filename;
					
					$edit_data["sn"] = $content_sn;
					//$this->sync_to_server($edit_data);
				
					
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}	
			}
			
			$sync_data = array(
			
			);			
			
			
			redirect(bUrl("contentList"));	
        }	
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
			//$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
			//move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
			
			$img_filename = resize_img($_FILES['img_filename']['tmp_name'],$img_config['resize_setting']);					
			//echo 	$img_filename;exit;
			
			if (!is_dir( $this->config->item('edoma_folder_path') ))
			{
				mkdir($this->config->item('edoma_folder_path'),0777);
			}  
			
			if (!is_dir( $this->config->item('edoma_folder_path').$folder_name ))
			{
				mkdir($this->config->item('edoma_folder_path').$folder_name,0777);
			}
			
			//將檔案複製到commapi folder 下
			copy(set_realpath("upload/website").$folder_name.'/'.$img_filename , $this->config->item('edoma_folder_path').$folder_name.'/'.$img_filename);
			
			

			$this->it_model->updateData( "edoma_content" , array("img_filename"=> $img_filename), "sn = '".$content_sn."'" );
			
			$orig_img_filename = $this->input->post('orig_img_filename');
			
			@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
			@unlink($this->config->item('edoma_folder_path').$folder_name.'/'.$orig_img_filename);	
			
			//檔案同步至server
			//$this->sync_file($folder_name);
			
			
		}
		return $img_filename;
	}
	
	
	
	/**
	 * 驗證sys_newsedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules( 'content', '內容', 'required' );	
		$this->form_validation->set_rules( 'sort', '排序', 'trim|required|numeric|min_length[1]');			
		$this->form_validation->set_rules( 'comms', '發佈社區', 'required' );	
		
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
			$result = $this->it_model->updateData( "edoma_content" , array("del"=>1,"update_date"=>date("Y-m-d H:i:s")), "sn ='".$content_sn."'" );
			if($result)
			{
				array_push($sync_sn_ary,$content_sn);
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