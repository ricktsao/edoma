<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msgcenter extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	public function contentList()
	{				
		$msg_list = $this->it_model->listData("user_message_assign","",$this->per_page_rows,$this->page,array("created" => "desc"));		
		$data["msg_list"] = $msg_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($msg_list["count"],$this->page,$this->per_page_rows,"contentList");			
		$this->display("content_list_view",$data);
	}
	
	


	public function editContent()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");	
		
		$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
		$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
		
		$this->addCss("css/bootstrap-fonts.css");
				
		$data = array();		
		
		//罐頭訊息		
		$can_msg_list = $this->c_model->GetList( "can_msg" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );		
		$data["can_msg_list"] = $can_msg_list["data"];	

		
		//住戶
		$user_list = $this->it_model->listData("sys_user","launch =1 and role = 'I' ",NULL,NULL,array("name"=>"asc"));
		$data["user_list"] = $user_list["data"];	
		
		$user_id = $this->session->userdata("user_id");
		
		
		
		//$this->_initUnitData($data);
		
		$data["edit_data"] = array
		(			
			'meeting_date' => date( "Y-m-d 09:00" ),			
			'target' => 1,			
		);
		$this->display("content_form_view",$data);
	
	}
	
	
	public function updateContent()
	{			
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		//dprint($edit_data);exit;
		
		//$to_user_sn_ary = tryGetData("users", $edit_data,array());
						
		if ( ! $this->_validateContent())
		{
			$this->addCss("css/chosen.css");
			$this->addJs("js/chosen.jquery.min.js");	
			
			$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
			$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
			
			$this->addCss("css/bootstrap-fonts.css");
			
			//罐頭訊息		
			$can_msg_list = $this->c_model->GetList( "can_msg" , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );		
			$data["can_msg_list"] = $can_msg_list["data"];	

		
			//住戶
			$user_list = $this->it_model->listData("sys_user","launch =1 and role = 'I' ",NULL,NULL,array("name"=>"asc"));
			$data["user_list"] = $user_list["data"];	
			
			$data["edit_data"] = $edit_data;								
			$this->display("content_form_view",$data);
		}
        else 
        {		
        	$error_user_ary = array();
			$to_user_sn_ary = tryGetData("users", $edit_data,array());
			$to_user_name_ary = array(); 
			
			$msg_count = 0;
			foreach ($to_user_sn_ary as $key => $to_user_sn) 
			{
				$user_info = $this->it_model->listData("sys_user","sn='".$to_user_sn."'");
				if($user_info["count"]==0)
				{
					continue;
				}
				$user_info = $user_info["data"][0];
				
				array_push($to_user_name_ary,$user_info["name"]);
				
				
				$arr_data = array
				(      
					  "edit_user_sn" => $this->session->userdata('user_sn')
					, "to_user_sn" => $to_user_sn
					, "to_user_app_id" => $user_info["app_id"]
					, "to_user_name" => mb_substr($user_info["name"],0,1).tryGetData($user_info["gender"],$this->config->item("gender_array"),"君") 
					, "title" => tryGetData("title", $edit_data)
					, "msg_content" => tryGetData("msg_content", $edit_data)
					, "updated" => date( "Y-m-d H:i:s" )
					, "created" => date( "Y-m-d H:i:s" )
				);	
								
				$content_sn = $this->it_model->addData( "user_message" , $arr_data );
				
				if($content_sn > 0)
				{
					$arr_data["sn"] = $content_sn;
					$arr_data["comm_id"] = $this->getCommId();					
					$this->sync_message_to_server($arr_data);
					
					$msg_count++;
				}
				else
				{
					array_push($error_user_ary,$user_info["name"]);
				}
			}
			
			if($msg_count == count($to_user_sn_ary))
			{					
				$this->showSuccessMessage();							
			}
			else 
			{
				$msg = "下列用戶請重新發送:<br>".implode("<br>", $error_user_ary);
				$this->showMessage($msg);
			}

			
			$arr_data = array
			(      
				  "edit_user_sn" => $this->session->userdata('user_sn')
				, "to_user_sn" => implode(",", $to_user_sn_ary)					
				, "to_user_name" => implode(",", $to_user_name_ary) 
				, "to_user_count" => count($to_user_sn_ary)
				, "title" => tryGetData("title", $edit_data)
				, "msg_content" => tryGetData("msg_content", $edit_data)
				, "updated" => date( "Y-m-d H:i:s" )
				, "created" => date( "Y-m-d H:i:s" )
			);	
							
			$content_sn = $this->it_model->addData( "user_message_assign" , $arr_data );
			
			
			redirect(bUrl("contentList"));	
        }	
	}

		 

	/**
	 * 更新指派記錄
	 */
	function updateMessageAssign($edit_data = array(),$sales_list = array(),$error_user_ary = array(),$assign_sn = 0)
	{
		if(count($sales_list)==0)
		{
			return $assign_sn;
		}
		
		
		$to_user_sn_ary = array();
		$to_user_id_ary = array();
		
		foreach ($sales_list as $key => $item) 
		{
			array_push($to_user_sn_ary,$item["sn"]);
			array_push($to_user_id_ary,$item["id"]."-".$item["name"]);
		}		
		
	
		if($assign_sn == 0)
		{
			$arr_data = array
			(      
				  "from_unit_sn" => $this->session->userdata('unit_sn')
				, "from_unit_name" => $this->session->userdata('unit_name')
				, "from_user_sn" => $this->session->userdata('user_sn')
				, "to_user_sn" => implode(",", $to_user_sn_ary)
				, "to_user_id" => implode(",", $to_user_id_ary)
				, "category_id" => tryGetData("category_id", $edit_data)
				, "title" => tryGetData("title", $edit_data)					
				, "msg_content" => tryGetData("msg_content", $edit_data)			
				, "updated" => date( "Y-m-d H:i:s" )
				, "created" => date( "Y-m-d H:i:s" )
			);			
			
			if(tryGetData("category_id", $edit_data) == "meeting")
			{
				$arr_data["meeting_date"] = tryGetData("meeting_date", $edit_data,NULL);
				
			}
			
			$assign_sn = $this->it_model->addData( "sys_message_assign" , $arr_data );
		}
		else 
		{
			$arr_data = array
			(				
				  "fail_user_id" =>	implode(",", $error_user_ary)		
				, "updated" => date( "Y-m-d H:i:s" )
			);		
			
			$condition = "sn ='".$assign_sn."'";
			$result = $this->it_model->updateData( "sys_message_assign" , $arr_data, $condition );
		}
		
		return $assign_sn;
		
	}





	
	/**
	 * 驗證faqedit 欄位是否正確
	 */
	function _validateContent()
	{
	
		
		$this->form_validation->set_rules( 'title', '標題', 'required' );	
		$this->form_validation->set_rules( 'msg_content', '訊息內容', 'required' );
		$this->form_validation->set_rules( 'users', '發佈對象', 'required' );				
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	/**
	 * 同步至雲端server
	 */
	function sync_message_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync/updateUserMessage";
		//dprint($post_data);
		//exit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		$this->it_model->updateData( "user_message" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
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
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */