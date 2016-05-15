<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * 檢查是否有登入
	 * 有 retrun TRUE
	 * 無 retrun FALSE
	 */
	function checkUserLogin()
	{
		$CI	=& get_instance();
		
		if(
			$CI->session->userdata("user_sn") !== FALSE 
			&& $CI->session->userdata("user_id") !== FALSE 			
			&& $CI->session->userdata("supper_admin") !== FALSE 
			&& $CI->session->userdata("user_login_time") !== FALSE 
			&& $CI->session->userdata("user_auth") !== FALSE 		
		)
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}

	}	
	
	
	/**
	 * 檢查是否有前台單元權限
	 * 有 retrun TRUE
	 * 無 retrun FALSE
	 */
	function checkFrontendAuth()
	{
		$CI	=& get_instance();
		
		$frontend_auth = $CI->session->userdata('frontend_auth');
		$menu_id = $CI->uri->segment(1);
		
		if($menu_id == "home" || $menu_id == "chpwd")
		{
			return TRUE;
		}		
		else if(in_array($menu_id, $frontend_auth))
		{
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}
	
	
	/**
	 * 取得管轄的業務人員列表
	 */
	function getMyOwnSalesList($user_id)
	{
		$CI	=& get_instance();	
		return $CI->person_model->getMyOwnSalesList($user_id );
		
	} 
	
	
	/**
	 * 查詢自己管轄的單位別
	 */
	function getMyUnitList($user_id)
	{		
		$CI	=& get_instance();	
		
		// 先判斷是否為特殊權限
		$is_super_admin = $CI->person_model->isSuperAdmin($user_id);

		if ($is_super_admin == false ) {
			// 查詢自己管轄的單位別
			$result = $CI->person_model->getUnitList('UPPER(u.manager_user_id)="'.strtoupper($user_id).'"' );

		} else {
			// 超級權限，可撈出所有單位
			$result = $CI->person_model->getUnitList('u.is_sales=1 and u.is_parent=1', NULL, NULL, array('u.sn'=>'asc', 'u.unit_name'=>'asc', 'u.level'=>'asc') );

		}		
		return $result;
	}
	
	
	/**
	 * $sub_unit_sn : user 單位代碼
	 * 取得分公司單位代碼
	 */
	function getMainUnitSn($sub_unit_sn)
	{
		$unit_ary = array(
			25 => 4,
			16 => 11,			
			17 => 12,			
			18 => 13,
			19 => 14,
			20 => 15,
			21 => 15,
			22 => 15,
			23 => 15,
			24 => 15,
			27 => 15,			
			29 => 28
		);
		
		if(array_key_exists($sub_unit_sn, $unit_ary))
		{
			return $unit_ary[$sub_unit_sn];
		}
		else 
		{
			return $sub_unit_sn;
		}
	}
	
	
	
	/**
	 * 傳送訊息
	 * $title : 標題
	 * $msg_content : 內容
	 * $publish_user_sn_ary : 發布的對像 user_sn,ex array(1,2,3)
	 */
	function sendMsg($title="",$msg_content="",$publish_user_sn_ary = array())
	{
		
		if(isNull($title) || isNull($msg_content))
		{
			return 0;
		}
		
		
		$CI	=& get_instance();
			
		$to_user_sn_ary	= array();
		
		$sales_list = $CI->it_model->listData("sys_user","launch = 1 and sn in (".implode(",", $publish_user_sn_ary).")");
		//dprint($sales_list);
		if($sales_list["count"]>0)
		{
			foreach ($sales_list["data"] as $key => $item) 
			{
				array_push($to_user_sn_ary,$item["sn"]);			
				
				$arr_data = array
				(      
					  "from_unit_sn" => $CI->session->userdata('unit_sn')
					, "from_unit_name" => $CI->session->userdata('unit_name')
					, "from_user_sn" => $CI->session->userdata('user_sn')
					, "to_user_sn" => $item["sn"]			
					, "category_id" => "notify"
					, "title" => $title					
					, "msg_content" => $msg_content		
					, "updated" => date( "Y-m-d H:i:s" )
					, "created" => date( "Y-m-d H:i:s" )
				);
				
				
				$msg_sn = $CI->it_model->addData( "sys_message" , $arr_data );
				
				//發送email
				if($msg_sn > 0)
				{
					if($_SERVER['HTTP_HOST'] == 'web-01' || $_SERVER['HTTP_HOST'] == 'web.chupei.com.tw' || $_SERVER['HTTP_HOST'] == '118.163.146.74')
					{
					
						$template = $CI->config->item('template','mail');
						$sender = $CI->session->userdata('unit_name').' '.$CI->session->userdata('user_name');
						
						$content = sprintf($template, $msg_content);
	
												
						send_email($item['email'],$title, $content);
					}
				}
				
				
			}
		}		
		
		

	}
	