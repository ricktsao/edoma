<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	
	/**
	 * 查詢server上有無app新增的資料
	 **/
	public function getAppData()
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppSuggestion";
		//dprint($post_data);exit;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		$app_data_ary =  json_decode($json_data, true);
		
		if( ! is_array($app_data_ary))
		{
			$app_data_ary = array();
		}
		
		
		foreach( $app_data_ary as $key => $server_info ) 
		{			
			$repair_server_info = $this->it_model->listData("suggestion","server_sn='".$server_info["sn"]."'");
			if($repair_server_info["count"]==0)
			{
								
				$user_info = $this->it_model->listData("sys_user","app_id='".$server_info["app_id"]."'");
				if($user_info["count"]>0)
				{
					$user_info = $user_info["data"][0];
					
					$add_data = array(
					"comm_id" => $this->getCommId(),
					"server_sn" => $server_info["sn"],
					"app_id" => $server_info["app_id"],
					"title" => $server_info["title"],
					"content" => $server_info["content"], 
					"user_sn" => $user_info["sn"],
					"to_role" => $server_info["to_role"], 					
					"updated" => date("Y-m-d H:i:s"),
					"created" => date("Y-m-d H:i:s")
					);
					$suggestion_sn = $this->it_model->addData( "suggestion" , $add_data );	
					if($suggestion_sn > 0)
					{
						$add_data["sn"] = $suggestion_sn;								
						$this->sync_item_to_server($add_data,"updateServerSuggestion","suggestion");
					}
				}				
				
			}
						
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}

	/**
	 * course list page
	 */
	public function contentList()
	{					
		$this->getAppData();//查詢server有無要同步的資料
		$status = $this->input->get('status');
		
		$user_map = $this->it_model->listData("sys_user","");
		$user_map = $this->it_model->toMapValue($user_map["data"],"sn","name");
		
		$condition = "";
		if(isNull($status))
		{
			$status = "0";
		}
		
		if($status == "0")
		{
			$condition = "reply is null ";	
		}
		else
		{
			$condition = "reply is not null ";
		}
		
		//to 管委or總幹事 判斷
		//-------------------------------------------------------
		$group_id_ary = array();
		$user_group_list = $this->it_model->listData("sys_user_group","sn in ( ".implode(',',$this->session->userdata("user_group"))." ) ");
		foreach ($user_group_list["data"] as $key => $group_info) 
		{
			array_push($group_id_ary,$group_info["id"]);
		}
		if(in_array("advuser", $group_id_ary)) 
		{
			$role_sql = "and to_role='a'";
		}
		else if(in_array("secretary", $group_id_ary))
		{
			$role_sql = "and to_role='s'";
		}
		else
		{
			$role_sql = "and to_role=''";
		}
		//-------------------------------------------------------
		
		
		$suggestion_list = $this->it_model->listData("suggestion",$condition.$role_sql, $this->per_page_rows , $this->page,array("created"=>"asc"));
		
		
		$data["list"] = $suggestion_list["data"];

		$data["pager"] = $this->getPager($suggestion_list["count"],$this->page,$this->per_page_rows,"contentList");	
		$data["user_map"] = $user_map;
		$data["status"] = $status;
		
		//計算數量
		//--------------------------------------------------------
		$suggestion_0_list = $this->it_model->listData("suggestion","reply is null ".$role_sql);
		$suggestion_1_list = $this->it_model->listData("suggestion","reply is not null ".$role_sql);
		
		
		$data["status_0_cnt"] = $suggestion_0_list["count"];
		$data["status_1_cnt"] = $suggestion_1_list["count"];
		//--------------------------------------------------------
		
		//dprint($data["list"]);
		$this->display("content_list_view",$data);
	}
	

	public function editContent()
	{
		
		
		
		$content_sn = $this->input->get('sn');
		
		$suggestion_info = $this->it_model->listData( "suggestion" , "sn =".$content_sn);
		if($suggestion_info["count"]>0)
		{				
			$suggestion_info = $suggestion_info["data"][0];		
			
			$user_info = $this->it_model->listData( "sys_user" , "sn =".$suggestion_info["user_sn"]);
			if($user_info["count"]==0)
			{
				redirect(bUrl("contentList"));	
			}
			$suggestion_info["user_name"] = $user_info["data"][0]["name"];
			
			$data["suggestion_info"] = $suggestion_info;			
			$this->display("content_form_view",$data);
		}
		else
		{
			redirect(bUrl("contentList"));	
		}		
	}
	
	
	public function updateContent()
	{	
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		//dprint($edit_data);
		//exit;
		if(isNull($edit_data["sn"]))
		{
			redirect(bUrl("contentList"));
		}
		else 
		{
			//更新回覆
			//------------------------------------------------------------------			
			$result = $this->it_model->updateData( "suggestion" , array("reply"=>tryGetData("reply",$edit_data,NULL),"is_sync"=>0,"updated"=>date("Y-m-d H:i:s")), "sn =".$edit_data["sn"] );
			if($result)
			{
				$suggestion_info = $this->it_model->listData( "suggestion" , "sn =".$edit_data["sn"]);
				if($suggestion_info["count"]>0)
				{	
					$suggestion_info = $suggestion_info["data"][0];		
					$this->sync_suggestion_to_server($suggestion_info);
				}
				
				
				
				$this->showSuccessMessage();							
			}
			else 
			{
				$this->showFailMessage();					
			}	
			
			//------------------------------------------------------------------
								
			
			
		}				
		
		
		redirect(bUrl("contentList",TRUE,array("all"=>"all"),array("status"=>1)));	
        	
	}
	

	/**
	 * 同步至雲端server
	 */
	function sync_suggestion_to_server($post_data)
	{
		$url = $this->config->item("api_server_url")."sync/updateSuggestion";
		
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
		
		$this->it_model->updateData( "suggestion" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
	
	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */