<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authgroup extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();		
	}
		
	
	public function contentList()
	{
		$list = $this->it_model->listData( "sys_user_group" , NULL , $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"desc") );		
			
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		//dprint($data["pager"]);
		
		
		$this->display("content_list_view",$data);
	}
	
	
	public function editContent()
	{			
		$content_sn = $this->input->get('sn');
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,					
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{
			$info = $this->it_model->listData( "sys_user_group" , "sn =".$this->db->escape($content_sn) , $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"desc") );				
					
			if(count($info["data"])>0)
			{
				//img_show_list($news_info["data"],'img_filename',$this->router->fetch_class());			
				
				$data["edit_data"] = $info["data"][0];			

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
		$this->load->library('encrypt');
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
						
		if ( ! $this->_validateGroup())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			
			//deal_img($edit_data ,"img_filename",$this->router->fetch_class());			
			
			$arr_data = array(				
				  "title" => tryGetData("title", $edit_data)   
				, "id" => tryGetData("id", $edit_data)     		
				, "launch" => tryGetData("launch", $edit_data)
				, "sort" => tryGetData("sort", $edit_data)				
				, "update_date" =>  date( "Y-m-d H:i:s" ) 				
			);        			
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "sys_user_group" , $arr_data, "sn =".$edit_data["sn"] ))
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
									
				$edit_data["create_date"] = date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "sys_user_group" , $arr_data );
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
	 * 驗證 欄位是否正確
	 */
	function _validateGroup()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules( 'id', 'id', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}
	
	
	public function launchGroup()
	{		
		$this->ajaxChangeStatus("sys_user_group","launch",$this->input->post("content_sn", TRUE));
	}
	

		

	public function editBackendAuth()
	{
		$this->addCss('css/jstree.css');
		$this->addJs('js/jstree.min.js');
		
		$content_sn = $this->input->get('sn');
				
		
		$auth_list = $this->it_model->listData( "sys_user_group_b_auth" , "sys_user_group_sn =".$this->db->escape($content_sn)." and launch = 1" , NULL , NULL , array("sn"=>"asc") );				
		
		$auth_ary = array();
		foreach ($auth_list["data"] as $item) 
		{
			array_push($auth_ary,$item["module_sn"]);
		}
				
		//$data["auth_list"] = $auth_list["data"];
		$data["auth_ary"] = $auth_ary;
		$data["admin_group_sn"] = $content_sn;
		
		//dprint($auth_ary);
		//exit;
		$this->display("b_auth_form_view",$data);

	}
	
	
	public function editFrontendAuth()
	{
		$this->addCss('css/jstree.css');
		$this->addJs('js/jstree.min.js');
		
		$content_sn = $this->input->get('sn');
				
		
		$auth_list = $this->it_model->listData( "sys_user_group_f_auth" , "sys_user_group_sn =".$this->db->escape($content_sn)." and launch = 1" , NULL , NULL , array("sn"=>"asc") );				
		
		$auth_ary = array();
		foreach ($auth_list["data"] as $item) 
		{
			array_push($auth_ary,$item["web_menu_sn"]);
		}
				
		//$data["auth_list"] = $auth_list["data"];
		$data["auth_ary"] = $auth_ary;
		$data["admin_group_sn"] = $content_sn;
		
		
		$this->_getFrontendMenu($data);		
		
		//前端特殊權限list
		//------------------------------------------------------
		$f_func_list = $this->it_model->listData("sys_function");		
		$f_func_list = $f_func_list["data"];
		$data["f_func_list"] = $f_func_list;
		//------------------------------------------------------
		
		//群組所傭有的前端特殊權限
		//------------------------------------------------------
		$f_auth_ary = array();
		$f_auth_list = $this->it_model->listData( "sys_user_func_auth" , "sys_user_group_sn =".$this->db->escape($content_sn)." and launch = 1");
		$f_auth_list = $f_auth_list["data"];
		foreach ($f_auth_list as $key => $item) 
		{
			array_push($f_auth_ary,$item["sys_function_sn"]);
		}
		$data["f_auth_ary"] = $f_auth_ary;
		//------------------------------------------------------
		
		//dprint($f_func_list);
		
		$this->display("f_auth_form_view",$data);

	}
	
	
	private function _getFrontendMenu(&$data =array())
	{
		//$condition = " launch = 1";
		$condition = "";
		
		$sort = array
		(
			"sort" => "asc" 
		);
		

		$f_menu_list = $this->it_model->listData("web_menu"," level=1",NULL,NULL,$sort);	
		

		//$f_menu_list = $this->_adjustFMenu($f_menu_list["data"]);
		
		
		$l2_list = $this->it_model->listData("web_menu","level=2",NULL,NULL,$sort);	
		//$l2_list = $this->_adjustLeftMenu($l2_list["data"]);
		//$module_item_list = $this->it_model->listData("sys_module_item",$condition,NULL,NULL,$sort);	
		//$module_item_list = $this->_adjustLeftMenu($module_item_list["data"]);
		
		$web_menu_map = array();
		foreach ($l2_list["data"] as $item) 
		{			
			$web_menu_map[$item["parent_sn"]]["item_list"][]=$item;
		}
				
		$data['f_menu_list'] = $f_menu_list["data"];
		$data['web_menu_map'] = $web_menu_map;
				
	}
	

	
	
	public function updateFrontendAuth()
	{	
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
				
		$auths = explode(",", $edit_data["auths"]);
		$select_values = explode(",", $edit_data["select_values"]);
		
		//dprint($auths);
		//echo ($select_values[0]=="true"?"1":"0");
		//exit;
		
		$parent_queue = array();//父層模組統一最後更新
		
		for ($i=0; $i < count($auths) ; $i++) 
		{
			$tmp_sn_ary = explode("-", $auths[$i]); 
			if(count($tmp_sn_ary)==1)//無父層模組時
			{
				$arr_data = array(							
					"launch" => ($select_values[$i]=="true"?"1":"0")				
					, "update_date" =>  date( "Y-m-d H:i:s" ) 				
				);        
			
				$result = $this->it_model->updateData( "sys_user_group_f_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and web_menu_sn = '".$auths[$i]."'" );
				if($result === FALSE)
				{
					$this->_addFAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$auths[$i]);
				}	
			}
			else if(count($tmp_sn_ary)>1)//有父層模組時
			{
				
				$arr_data = array(							
					"launch" => ($select_values[$i]=="true"?"1":"0")				
					, "update_date" =>  date( "Y-m-d H:i:s" ) 				
				);      
				
				//父層模組queue
				//--------------------------------------------------------
				if(!array_key_exists($tmp_sn_ary[0], $parent_queue))
				{
					$parent_queue[$tmp_sn_ary[0]] = array();
				}				
				array_push($parent_queue[$tmp_sn_ary[0]],($select_values[$i]=="true"?"1":"0"));
				//--------------------------------------------------------					
				
				//子模組更新
				if(isNotNull($tmp_sn_ary[1]))
				{
					$result = $this->it_model->updateData( "sys_user_group_f_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and web_menu_sn = '".$tmp_sn_ary[1]."'" );
					if($result === FALSE)
					{
						$this->_addFAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$tmp_sn_ary[1]);
					}
				}
					
			}			
		}

		
		//父層模組更新
		//-----------------------------------------------------------------
		$arr_data["update_date"] = date( "Y-m-d H:i:s" );
		//dprint($parent_queue);
		//exit;
		foreach ($parent_queue as $web_menu_sn => $launch_ary) 
		{
			if(in_array('1', $launch_ary)) //判斷此子模組是否有勾選(只要勾一個父模組就launch就需更改為1)
			{
				$arr_data["launch"] = '1';
			}
			else 
			{
				$arr_data["launch"] = '0';
			}
			
			$result = $this->it_model->updateData( "sys_user_group_f_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and web_menu_sn = '".$web_menu_sn."'" );
			if($result === FALSE)
			{
				$this->_addFAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$web_menu_sn);
			}	
		}
		//-----------------------------------------------------------------

		// 更新特殊權限
		$this->_updateSysFunc($edit_data);
		

		$this->showSuccessMessage();
			
		redirect(bUrl("contentList"));	

	}
	



	/**
	 * 更新特殊權限
	 */
	function _updateSysFunc(&$edit_data)
	{
		if(isNull(tryGetData("admin_group_sn", $edit_data)))
		{
			return;
		}
		
		
		$func_sn_ary = tryGetData("func_sn", $edit_data,array());				
		$old_func_sn_ary = tryGetData("old_func_sn", $edit_data,array());	
		
		
		
		
		foreach ($func_sn_ary as $key => $func_sn) 
		{
			
			$arr_data = array
			(				
				"launch" => 1,				
				"is_frontend" => tryGetData("is_frontend", $edit_data,1),
				"update_date" => date( "Y-m-d H:i:s" )
			);			
			
			
			//與原先組相同-->不動做
			if(in_array($func_sn, $old_func_sn_ary))
			{				
				//dprint("-->update");
				//exit;
			}
			else //新的function-->新增
			{
				
				$arr_data["sys_user_group_sn"] = tryGetData("admin_group_sn", $edit_data);	
				$arr_data["sys_function_sn"] = $func_sn;	
				$result_sn = $this->it_model->addData( "sys_user_func_auth" , $arr_data );
			}
			
		}
		
					
		//需要刪除的function(將launch設為0)
		$del_ary = array_diff($old_func_sn_ary,$func_sn_ary);		
		foreach ($del_ary as $key => $func_sn) 
		{			
			
			$arr_data = array
			(				
				"launch" => 0,				
				"update_date" => date( "Y-m-d H:i:s" )
			);		
			
			$condition = "sys_function_sn ='".$func_sn."' AND sys_user_group_sn='".tryGetData("admin_group_sn", $edit_data)."' ";
			$result = $this->it_model->updateData( "sys_user_func_auth" , $arr_data, $condition );
		}
	}




	public function updateBackendAuth()
	{	
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
				
		$auths = explode(",", $edit_data["auths"]);
		$select_values = explode(",", $edit_data["select_values"]);
		
		//dprint($auths);
		//echo ($select_values[0]=="true"?"1":"0");
		//exit;
		
		$parent_queue = array();//父層模組統一最後更新
		
		for ($i=0; $i < count($auths) ; $i++) 
		{
			$tmp_sn_ary = explode("-", $auths[$i]); 
			if(count($tmp_sn_ary)==1)//無父層模組時
			{
				$arr_data = array(							
					"launch" => ($select_values[$i]=="true"?"1":"0")				
					, "update_date" =>  date( "Y-m-d H:i:s" ) 				
				);        
			
				$result = $this->it_model->updateData( "sys_user_group_b_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and module_sn = '".$auths[$i]."'" );
				if($result === FALSE)
				{
					$this->_addBAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$auths[$i]);
				}	
			}
			else if(count($tmp_sn_ary)>1)//有父層模組時
			{
				
				$arr_data = array(							
					"launch" => ($select_values[$i]=="true"?"1":"0")				
					, "update_date" =>  date( "Y-m-d H:i:s" ) 				
				);      
				
				//父層模組queue
				//--------------------------------------------------------
				if(!array_key_exists($tmp_sn_ary[0], $parent_queue))
				{
					$parent_queue[$tmp_sn_ary[0]] = array();
				}				
				array_push($parent_queue[$tmp_sn_ary[0]],($select_values[$i]=="true"?"1":"0"));
				//--------------------------------------------------------					
				
				//子模組更新
				if(isNotNull($tmp_sn_ary[1]))
				{
					$result = $this->it_model->updateData( "sys_user_group_b_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and module_sn = '".$tmp_sn_ary[1]."'" );
					if($result === FALSE)
					{
						$this->_addBAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$tmp_sn_ary[1]);
					}
				}
				
					
			}			
		}

		
		//父層模組更新
		//-----------------------------------------------------------------
		$arr_data["update_date"] = date( "Y-m-d H:i:s" );
		//dprint($parent_queue);
		//exit;
		foreach ($parent_queue as $module_sn => $launch_ary) 
		{
			if(in_array('1', $launch_ary)) //判斷此子模組是否有勾選(只要勾一個父模組就launch就需更改為1)
			{
				$arr_data["launch"] = '1';
			}
			else 
			{
				$arr_data["launch"] = '0';
			}
			
			$result = $this->it_model->updateData( "sys_user_group_b_auth" , $arr_data,"sys_user_group_sn ='".tryGetData("admin_group_sn", $edit_data)."' and module_sn = '".$module_sn."'" );
			if($result === FALSE)
			{
				$this->_addBAuth($arr_data,tryGetData("admin_group_sn", $edit_data),$module_sn);
			}	
		}
		//-----------------------------------------------------------------


		$this->showSuccessMessage();
			
		redirect(bUrl("contentList"));	

	}

	public function editGroupUser()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$group_sn = $this->input->get('sn');	
		
		
		
		$group_info = $this->it_model->listData("sys_user_group","sn='".$group_sn."'");
		if($group_info["count"]>0)
		{
			$group_info = $group_info["data"][0];
			$data["group_info"] = $group_info;
		}
		else 
		{
			redirect(bUrl("contentList"));
		}
		
		
		$user_group_list = $this->it_model->listData("sys_user_belong_group","sys_user_group_sn = '".$group_sn."'  AND launch = 1");		
		$user_group_list = $user_group_list["data"];
		$user_sn_ary = datatoArray($user_group_list,"sys_user_sn");		
		
		
		if(count($user_sn_ary)>0)
		{
			$condition = "AND sn in (".implode(",", $user_sn_ary).")";
			$query = "select SQL_CALC_FOUND_ROWS * "
						."    from sys_user"						
						."   where 1 ".$condition
						."   order by sn asc, launch "
						;
	
			$user_list = $this->it_model->runSql( $query);
			//dprint($user_list);
			$user_list = $user_list["data"];
			
		}
		else
		{
			$user_list = array();
		}
		
		
		
		

		
		
		
		$data["user_list"] = $user_list;
		$data["group_sn"] = $group_sn;
		
		
		$this->display("group_user_edit_view",$data);
	}



	//取得user list
	public function ajaxGetUserList()
	{		
		
		$ajax_ary = array();		
		
		$group_sn = $this->input->get('sn');	
		
		//已加入的名單
		//-----------------------------------------------------------------------------------------------------------------------------
		$user_group_list = $this->it_model->listData("sys_user_belong_group","sys_user_group_sn = '".$group_sn."'  AND launch = 1");		
		$user_group_list = $user_group_list["data"];
		$user_sn_ary = datatoArray($user_group_list,"sys_user_sn");	
		//-----------------------------------------------------------------------------------------------------------------------------

		$condition = "";
		if(count($user_sn_ary)>0)
		{
			$condition = "AND sn not in (".implode(",", $user_sn_ary).") AND launch=1";			
		}
		
		
		$query = "select SQL_CALC_FOUND_ROWS * "
						."    from sys_user "					
						."   where 1 ".$condition
						."   order by sn asc, launch "
						;

		$user_list = $this->it_model->runSql( $query);
		
		$user_list = $user_list["data"];

		
		foreach ($user_list as $item) 
		{
			
			$tmp_data = array
			(
				"sn" => $item["sn"],
				"id" => $item["id"],
				"name" => $item["name"],
				"title" => $item["title"],
				"phone" => $item["phone"],
				"email" => $item["email"],
            	"eff_date" => showEffectiveDate($item["start_date"], $item["end_date"], $item["forever"])            	
			);
		
			array_push($ajax_ary,$tmp_data);
		}
		
		
		$output_ary = array();
		$output_ary["data"] = $ajax_ary;

		echo json_encode($output_ary, JSON_UNESCAPED_UNICODE);
		
	}

	
	
	/**
	 * 更新權限群組
	 */
	function updateGroupUser()
	{		
		
		$group_sn = $this->input->post("group_sn",TRUE);
		if($group_sn == FALSE)
		{
			$this->showFailMessage();				
			redirect(bUrl("contentList"));			
		}
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		
		
							
		$user_sn_ary = tryGetData("user_sn", $edit_data,array());
		$old_user_sn_ary = tryGetData("old_user_sn", $edit_data,array());	

		foreach ($user_sn_ary as $key => $user_sn) 
		{
				
			$arr_data = array
			(				
				"launch" => 1,				
				"update_date" => date( "Y-m-d H:i:s" )
			);			
			
			
			//與原先的群組相同-->不動做
			if(in_array($user_sn, $old_user_sn_ary))
			{

			}
			else //新的群組-->新增
			{
				$condition = "sys_user_group_sn ='".$group_sn."' AND sys_user_sn='".$user_sn."' ";
				$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );	
				
				if($result == FALSE)
				{
					$arr_data["sys_user_group_sn"] = $group_sn;		
					$arr_data["sys_user_sn"] = $user_sn;	
					$result_sn = $this->it_model->addData( "sys_user_belong_group" , $arr_data );
				}
			}
			
		}
		
		$this->showSuccessMessage();
			
		redirect(bUrl("contentList"));	
				
		
	}
	
	/**
	 * 刪除的群組內的user(將launch設為0)
	 */
	function deleteGroupUser()
	{
		$group_sn = $this->input->post("group_sn",TRUE);
		if($group_sn == FALSE)
		{
			$this->showFailMessage();				
			redirect(bUrl("contentList"));			
		}
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}		
		
		
							
		$user_sn_ary = tryGetData("del", $edit_data,array());
		
		//dprint($user_sn_ary);
		//exit;
		
		foreach ($user_sn_ary as $key => $user_sn) 
		{
				
			$arr_data = array
			(				
				"launch" => 0,				
				"update_date" => date( "Y-m-d H:i:s" )
			);			
			
			$condition = "sys_user_group_sn ='".$group_sn."' AND sys_user_sn='".$user_sn."' ";
			$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );	
			
		}
		
		$this->showSuccessMessage();
			
		redirect(bUrl("contentList"));
	}




	private function _addFAuth($arr_data,$admin_group_sn,$web_menu_sn)
	{
		$arr_data["sys_user_group_sn"] = $admin_group_sn;
		$arr_data["web_menu_sn"] = $web_menu_sn;
		$this->it_model->addData( "sys_user_group_f_auth" , $arr_data );
	}


	private function _addBAuth($arr_data,$admin_group_sn,$module_sn)
	{
		$arr_data["sys_user_group_sn"] = $admin_group_sn;
		$arr_data["module_sn"] = $module_sn;
		$this->it_model->addData( "sys_user_group_b_auth" , $arr_data );
	}
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("contentList","editAdmin","updateAdmin"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */