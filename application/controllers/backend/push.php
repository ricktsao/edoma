<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Push extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	


	/**
	 * course list page
	 */
	public function contentList()
	{
		
		
		$list = $this->it_model->listData( "app_push" , "launch=1" , $this->per_page_rows , $this->page , array("push_time"=>"desc") );		
			
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
		$comm_map = $this->it_model->listData("community");
		$comm_map = $this->it_model->toMapValue($comm_map["data"],"id","name");
		
		$condition = "content_type = 'feedback' and  del != 1 ";		
		$list = $this->it_model->listData( "web_menu_content" , $condition , NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );		
			
		
		if($list["count"]>0)
		{
			$list = $list["data"];	
			$html = "<h1 style='text-align:center'>富網通意見箱</h1>";
				
			
			$tables = 
			'<tr>										
				<th style="width:60px">序號</th>
				<th>主旨</th>
				<th>內容</th>								
				<th>狀態</th>					
			</tr>';
			
			
			
			/*
			 echo '<hr>回覆:<br>';
												echo '<span style="color:red;">	';												
												echo nl2br($list[$i]["brief2"]);
												echo '<br>['.$list[$i]["update_date"].']';
												echo '</span>'; 
			 * */
			
			
			for($i=0;$i<sizeof($list);$i++)
			{
				
				$comment = $list[$i]["content"];
				if(isNotNull($list[$i]["brief2"]))
				{
					$comment .= '<hr>回覆:<br>';
					$comment .= '<span style="color:red;">	';												
					$comment .= nl2br($list[$i]["brief2"]);
					$comment .= '<br>['.$list[$i]["update_date"].']';
					$comment .= '</span>'; 
				}
												
				
				
				$tables .= 
				'<tr>
					<td>'.($i+1).'</td>
					<td>'.$list[$i]["title"].'</td>
					<td>'.$comment.'</td>
					<td>'.($list[$i]["target"]==1?"<span style='color:blue'>已回覆</span>":"<span style='color:red'>未回覆</span>").'</td>						
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
			$pdfFilePath = "富網通意見箱_".$time .".pdf";
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
		
		$this->addCss("css/bootstrap-fonts.css");
				
		
		$content_sn = $this->input->get('sn');

				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'date' => date( "Y-m-d" )
			);
			$this->display("content_form_view",$data);
		}
		else 
		{
			
			$condition = " sn =".$content_sn;		
			$course_info = $this->it_model->listData( "app_push" , $condition);		
			


			if(count($course_info["data"])>0)
			{		

				$currentData =  $course_info["data"][0];

				$date = explode(" ",$currentData['push_time']);

				$time = explode(":",$date[1]);

				$currentData['date'] = $date[0];
				$currentData['hh'] = $time[0];
				$currentData['mm'] = $time[1];
				
				$data["edit_data"] =$currentData;			

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
		$arr_data = array();
		foreach ($_POST as $key => $value) {
			$arr_data[$key] = $value;
		}
		
		if ( ! $this->_validateContent())
		{
			$this->addCss("css/chosen.css");
			$this->addJs("js/chosen.jquery.min.js");			
		
			
			$this->addCss("css/bootstrap-fonts.css");
			
								
			$data["edit_data"] = $arr_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			$content_sn = tryGetData("sn", $arr_data);
			
			$edit_data = array(
				"push_time"=> "{$arr_data['date']} {$arr_data['hh']}:{$arr_data['mm']}:00",
				"message"=>$arr_data['message']
			);
			
			
			if(isNotNull($content_sn))
			{				
				if($this->it_model->updateData( "app_push" , $edit_data, "sn =".$content_sn ))
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
				$edit_data['created_by'] = $this->session->userdata('user_name');
				$this->it_model->addData("app_push",$edit_data);
				$this->showSuccessMessage();
			}
			
			redirect(bUrl("contentList"));	
        }	
	}	
	

	public function del(){
		$sn=$_GET['sn'];
		if(!$sn){
			redirect(bUrl("contentList"));	
		}
		$edit_data = array("launch"=>0);
		$this->it_model->updateData( "app_push" , $edit_data, "sn =".$sn );
		$this->showSuccessMessage();
		redirect(bUrl("contentList"));	

	}
	
	
	/**
	 * 驗證courseedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'message', '訊息', 'required' );			
		//$this->form_validation->set_rules( 'comms', '發佈社區', 'required' );	
		
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
						$update_data["client_sync"] = 0;
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