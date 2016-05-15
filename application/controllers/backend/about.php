<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	public function editContent()
	{
		
		$about_info = $this->c_model->GetList( "about" );
		if($about_info["count"]>0)
		{				
			img_show_list($about_info["data"],'img_filename',"about");
			$data["edit_data"] = $about_info["data"][0];			

			
		}
		else
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "about",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
		}
		$this->display("content_form_view",$data);		
		
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
						
			if(isNotNull($edit_data["sn"]))
			{				
				if($this->it_model->updateData( "web_menu_content" , $edit_data, "sn =".$edit_data["sn"] ))
				{					
					$img_filename = $this->uploadImage($edit_data["sn"]);					
					$edit_data["img_filename"] = $img_filename;					
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
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}	
			}
			
			$sync_data = array(
			
			);			
			
			
			redirect(bUrl("editContent"));	
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
			//$this->sync_file($folder_name);
		}
		return $img_filename;
	}
	
	
	/**
	 * 驗證daily_goodedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'content', '內容', 'required' );	
		//$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}






	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */