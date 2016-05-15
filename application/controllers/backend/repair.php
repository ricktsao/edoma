<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair extends Backend_Controller {
	
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
		$url = $this->config->item("api_server_url")."sync/getAppRepair";
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
			$repair_server_info = $this->it_model->listData("repair","server_sn='".$server_info["sn"]."'");
			if($repair_server_info["count"]==0)
			{
								
				$user_info = $this->it_model->listData("sys_user","app_id='".$server_info["app_id"]."'");
				if($user_info["count"]>0)
				{
					$user_info = $user_info["data"][0];
					
					$add_data = array(
					"comm_id" => $this->getCommId(),
					"server_sn" => $server_info["sn"],
					"user_sn" => $user_info["sn"],
					"user_name" => $user_info["name"],
					"app_id" => $user_info["app_id"], 
					"type" => $server_info["type"],
					"status" =>0,
					"content" => $server_info["content"],
					"updated" => date("Y-m-d H:i:s"),
					"created" => date("Y-m-d H:i:s")
					);
					$repair_sn = $this->it_model->addData( "repair" , $add_data );	
					if($repair_sn > 0)
					{
						$add_data["sn"] = $repair_sn;								
						$this->sync_item_to_server($add_data,"updateServerRepair","repair");
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
		
		$condition = "";
		if(isNull($status))
		{
			$status = "0";
		}
		$condition = "status = '".$status."'";

		$user_map = $this->it_model->listData("sys_user","");
		$user_map = $this->it_model->toMapValue($user_map["data"],"sn","name");
		
		$repair_list = $this->it_model->listData("repair",$condition, $this->per_page_rows , $this->page,array("created"=>"asc"));
		$data["list"] = $repair_list["data"];

		$data["pager"] = $this->getPager($repair_list["count"],$this->page,$this->per_page_rows,"contentList");	
		$data["user_map"] = $user_map;
		$data["status"] = $status;
		
		//計算數量
		//--------------------------------------------------------
		$repair_0_list = $this->it_model->listData("repair","status = 0");
		$repair_1_list = $this->it_model->listData("repair","status = 1");
		$repair_2_list = $this->it_model->listData("repair","status = 2");
		$repair_3_list = $this->it_model->listData("repair","status = 3");
		$repair_4_list = $this->it_model->listData("repair","status = 4");
		
		$data["status_0_cnt"] = $repair_0_list["count"];
		$data["status_1_cnt"] = $repair_1_list["count"];
		$data["status_2_cnt"] = $repair_2_list["count"];
		$data["status_3_cnt"] = $repair_3_list["count"];
		$data["status_4_cnt"] = $repair_4_list["count"];
		
		//--------------------------------------------------------
		
		//dprint($data["list"]);
		//$this->speed();
		$this->display("content_list_view",$data);
	}
	

	public function editContent()
	{
		$content_sn = $this->input->get('sn');
		
		
		
		$repair_info = $this->it_model->listData( "repair" , "sn =".$content_sn);
		if($repair_info["count"]>0)
		{				
			$repair_info = $repair_info["data"][0];		
			
			//若status = 0 更新為已讀
			//------------------------------------------------------------------
			if($repair_info["status"]==0)
			{
				$result = $this->it_model->updateData( "repair" , array("status"=>1,"is_sync" => 0,"updated"=>date("Y-m-d H:i:s")), "sn =".$content_sn );
								
				if($result)
				{
					$repair_info = $this->it_model->listData( "repair" ,"sn ='".$content_sn."'");
					if($repair_info["count"]>0)
					{
						$repair_info = $repair_info["data"][0];
						$this->sync_repair_to_server($repair_info);
					}
					
				}				
			}			
			//------------------------------------------------------------------
			
			$user_info = $this->it_model->listData( "sys_user" , "sn =".$repair_info["user_sn"]);
			if($user_info["count"]==0)
			{
				redirect(bUrl("contentList"));	
			}
			$repair_info["user_name"] = $user_info["data"][0]["name"];
			
			$data["repair_info"] = $repair_info;


			//reply list
			//------------------------------------------------------------------
			$reply_list = $this->it_model->listData("repair_reply","repair_sn = '".$repair_info["sn"]."'",NULL , NULL,array("created"=>"asc"));
			$data["reply_list"] = $reply_list["data"];
			//------------------------------------------------------------------
			
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
			//更新處理狀態
			//------------------------------------------------------------------			
			$result = $this->it_model->updateData( "repair" , array("status"=>tryGetData("status",$edit_data,1),"is_sync" => 0,"updated"=>date("Y-m-d H:i:s")), "sn ='".$edit_data["sn"]."'" );
			if($result)
			{
				$repair_info = $this->it_model->listData( "repair" ,"sn ='".$edit_data["sn"]."'");
				if($repair_info["count"]>0)
				{
					$repair_info = $repair_info["data"][0]; 									
					//dprint($repair_info);exit;
					$this->sync_repair_to_server($repair_info);
				}
				
			}		
			
			//------------------------------------------------------------------
			
			
			//更新回覆
			//------------------------------------------------------------------	
			if( isNotNull(tryGetData("reply",$edit_data)) )
			{
				$add_data = array(
				"repair_sn" => $edit_data["sn"],
				"repair_status" => tryGetData("status",$edit_data,1),
				"reply" => tryGetData("reply",$edit_data),
				"is_sync" => 0,
				"updated" => date( "Y-m-d H:i:s" ),
				"created" => date( "Y-m-d H:i:s" )
				);
				
				$content_sn = $this->it_model->addData( "repair_reply" , $add_data );
				if($content_sn > 0)
				{
					$add_data["sn"] = $content_sn;
					$this->sync_repair_reply_to_server($add_data);
				
					
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}	
			}
						
			
			
		}				
		
		
		redirect(bUrl("contentList"));	
        	
	}
	

	/**
	 * 同步至雲端server
	 */
	function sync_repair_to_server($post_data)
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/updateRepair";
		//dprint($post_data);exit;
		
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
		
		$this->it_model->updateData( "repair" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}
	
	
	function sync_repair_reply_to_server($post_data)
	{
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/updateRepairReply";
		//dprint($post_data);exit;
		
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
		
		$this->it_model->updateData( "repair_reply" , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
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