<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ad extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	


	/**
	 * ad list page
	 */
	public function contentList()
	{			
		$condition = "";
		$list = $this->c_model->GetList( "ad" , $condition ,FALSE, $this->per_page_rows , $this->page , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		//dprint($list);
		img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * pdf list print
	 */
	public function showPdfList()
	{
		$condition = "";
		$ad_list = $this->c_model->GetList( "ad" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		img_show_list($ad_list["data"],'img_filename',$this->router->fetch_class());		
		
		
		if($ad_list["count"]>0)
		{
			$ad_list = $ad_list["data"];	
			$html = "<h1 style='text-align:center'>社區優惠</h1>";
				
			
			$tables = 
			'<tr>										
				<th style="width:60px">序號</th>
				<th>主旨</th>
				<th>廠商名稱</th>
				<th>廣告圖</th>								
				<th>有效日期</th>					
			</tr>';
			
			
			for($i=0;$i<sizeof($ad_list);$i++)
			{
				$tables .= 
				'<tr>
					<td>'.($i+1).'</td>
					<td>'.$ad_list[$i]["title"].'</td>
					<td>'.$ad_list[$i]["content"].'</td>
					<td><img border="0" style="height:150px" src="'.$ad_list[$i]["img_filename"].'"></td>
					<td>'.showEffectiveDate($ad_list[$i]["start_date"], $ad_list[$i]["end_date"], $ad_list[$i]["forever"]).'</td>						
				</tr>';	
			}
			
			$html .= '<table border="1" width="100%" >'.$tables.'</table>';
			
			$this->load->library('pdf');
			$mpdf = new Pdf();
			$mpdf = $this->pdf->load();
			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			
			
			$water_img = base_url('template/backend/images/watermark.png');
			$water_info = $this->c_model->GetList( "watermark");			
			if(count($water_info["data"])>0)
			{
				img_show_list($water_info["data"],'img_filename',"watermark");
				$water_info = $water_info["data"][0];			
				$water_img = $water_info["img_filename"];
						
			}
			$mpdf->SetWatermarkImage($water_img);
			$mpdf->watermarkImageAlpha = 0.081;
			$mpdf->showWatermarkImage = true;		
			
			$mpdf->WriteHTML($html);			
			
			$time = time();
			$pdfFilePath = "社區優惠_".$time .".pdf";
			$mpdf->Output($pdfFilePath,'I');
		}
		else
		{
			$this->closebrowser();
		}
		
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
		
		
		$this->_useAreaOption($data);
		
				
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
					
					if(isNull($img_filename))
					{
						 
						$img_filename = $this->input->post("orig_img_filename",TRUE);
					}
					
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
			
			$orig_comm_id = tryGetData("orig_comm_id", $_POST); 
			$orig_comm_id_ary = explode(",",$orig_comm_id);		
			
			
			//需要更新至web_menu_content的資料
			//----------------------------------------------------------------
			//dprint($comm_id_ary);
			//dprint($orig_comm_id_ary);
			foreach( $comm_id_ary as $key => $comm_id )
			{
				if(isNull($comm_id))
				{
					continue;
				}
				$update_data = $edit_data;
				$update_data["comm_id"] = $comm_id;
				$update_data["del"] = 0;
				$this->updateCommContent($update_data);
			}
			//$comm_id_ary
			//----------------------------------------------------------------
			
			//web_menu_content需要刪除的檔案
			//----------------------------------------------------------------
			$del_comm_ary = array_diff($orig_comm_id_ary,$comm_id_ary);
			//dprint($del_comm_ary);
			foreach( $del_comm_ary as $key => $del_comm_id )
			{					
				if(isNull($del_comm_id))
				{
					continue;
				}
				$update_data = $edit_data;
				$update_data["comm_id"] = $del_comm_id;
				$update_data["del"] = 1;
				$this->updateCommContent($update_data);
			}
			//exit;
			//----------------------------------------------------------------
					
			
			
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
			$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
			move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
			
			$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
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
			
			@unlink($uploadedUrl);
			@unlink(set_realpath("upload/website/".$folder_name).$orig_img_filename);	
			@unlink($this->config->item('edoma_folder_path').$folder_name.'/'.$orig_img_filename);	
			
			//檔案同步至server
			//$this->sync_file($folder_name);
			
			
		}
		return $img_filename;
	}
	
	
	
	/**
	 * 驗證adedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');				
		
		$this->form_validation->set_rules( 'title', '主旨', 'required' );	
		$this->form_validation->set_rules( 'sort', '排序', 'trim|required|numeric|min_length[1]');			
		$this->form_validation->set_rules( 'comms', '發佈社區', 'required' );	
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteContent()
	{
		
		$del_ary = tryGetData("del",$_POST,array());				

		//刪除
		//----------------------------------------------------------------------------------------------------		
		foreach ($del_ary as  $content_sn) 
		{
			$result = $this->it_model->updateData( "edoma_content" , array("del"=>1,"update_date"=>date("Y-m-d H:i:s")), "sn ='".$content_sn."'" );
			if($result)
			{
				$del_comm_info = $this->it_model->listData("edoma_content","sn ='".$content_sn."'");
				if($del_comm_info["count"]>0)
				{
					$del_comm_info = $del_comm_info["data"][0];
					$del_comm_ary = explode(",",$del_comm_info["comm_id"]);
					foreach( $del_comm_ary as $key => $del_comm_id )
					{					
						if(isNull($del_comm_id))
						{
							continue;
						}
						$update_data = $del_comm_info;
						$update_data["comm_id"] = $del_comm_id;
						$update_data["del"] = 1;
						$this->updateCommContent($update_data);
					}
					
				}				
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