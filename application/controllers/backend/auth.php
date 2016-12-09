<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();

	}
		
	

	public function admin()
	{
		$condition = "";

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);			
		}	

		$query = "SELECT * FROM edoma_user";

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");

		$this->display("admin_list_view",$data);
	}




	/**
	 * 設定住戶車位
	 */
	public function setParking()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$user_sn = $this->input->get("sn", TRUE);
		$user_id = $this->input->get("id", TRUE);

		//既有車位list
		//---------------------------------------------------------------------------------------------------------------
		$exist_parking_list = $this->it_model->listData( "parking p left join user_parking up on p.sn = up.parking_sn" 
												, "user_sn = ".$user_sn , NULL , NULL , array("p.parking_id"=>"asc","sn"=>"desc"));

		$data["exist_parking_array"] = count($exist_parking_list["data"]) > 0 ? $exist_parking_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------

		$sys_user_group = array();		
		
		$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn." and role='I' ");
		
		if (count($admin_info["data"]) > 0) {
			$edit_data =$admin_info["data"][0];
			
			$data['user_data'] = $edit_data;
			
			$this->display("parking_setting_view",$data);
		}
		else
		{
			redirect(bUrl("admin"));	
		}
	}


	/**
	 * 搜尋還沒有住戶登錄的車位
	 */
	public function ajaxGetParking()
	{
		$keyword = $this->input->get('keyword', true);

		if (mb_strlen($keyword) == 0) {
		
		} else {
		
			echo '<ul id="parking_list" style="margin:0px">';
			if (mb_strlen($keyword) > 1) {
				$parking_result = $this->it_model->listData( "parking" , 'parking_id like "'.$keyword.'%" and sn not in (select distinct parking_sn from user_parking) ');
				//dprint($parking_result);
				if (count($parking_result["data"]) > 0) {

					$i = 0;
					$cust = array();
					foreach ($parking_result["data"] as $parking) {
						$parking_sn = $parking['sn'];
						$parking_id = $parking['parking_id'];
						$location = $parking['location'];
						echo '<li onclick="selectParking(\''.$parking_sn .'\',\''. $parking_id .'\',\''. $location .'\');">'
							.$parking_id.'　位置：'.$location
							."</li>";
						$i++;
					}
				} else {
						echo '<li style="font-weight:normal; color: #c8c8c8">查無車位資料，請確認車位ID輸入無誤</li>';
				}
			} else {
				echo '<li style="font-weight:normal; color: #c8c8c8">查無車位資料，請確認車位ID輸入無誤</li>';
			}
			// echo json_encode($return);
			echo '</ul>';
		}
	}


	/**
	 * 設定住戶車位
	 */
	public function addUserParking()
	{
		$edit_data = array();
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		if ( isNotNull(tryGetData('parking_sn', $edit_data, NULL)) 
			&& isNotNull(tryGetData('user_sn', $edit_data, NULL)) 
			&& isNotNull(tryGetData('user_id', $edit_data, NULL)) ) {

			$arr_data = array('parking_sn'	=>	tryGetData('parking_sn', $edit_data)
							, 'user_sn'	=>	tryGetData('user_sn', $edit_data)
							, 'person_sn'	=>	0
							, 'user_id'	=>	tryGetData('user_id', $edit_data)
							, 'car_number'	=>	tryGetData('car_number', $edit_data)
							, 'updated'	=>	date('Y-m-d H:i:s')
							, 'updated_by'	=>	$this->session->userdata('user_name')
							, 
							);
			
			$query = 'INSERT INTO `user_parking` '
					.'       (`parking_sn`, `user_sn`, `person_sn` '
					.'        , `user_id`, `car_number`, `updated`, `updated_by`) '
					.'VALUES (?, ?, ? '
					.'        , ?, ?, ?, ? ) '
					.'    ON DUPLICATE KEY UPDATE  '
					.'       `car_number` = VALUES(`car_number`) '
					.'       , `updated` = VALUES(`updated`) '
					.'       , `updated_by` = VALUES(`updated_by`) '
					;


			$this->db->query($query, $arr_data);
			if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') {
				$this->showSuccessMessage('車位設定成功');
			} else {
				$this->showFailMessage('車位設定失敗');
			}
		} else {
			$this->showFailMessage('車位設定失敗，請確認資料確實輸入');
		}

		redirect(bUrl("setParking"));
	}

	/**
	 * 刪除住戶車位
	 */
	function deleteUserParking()
	{
		$del_array = $this->input->post("del",TRUE);
		
		foreach( $del_array as $item ) {
			$tmp = explode('!@', $item);
			$parking_sn = $tmp[0];
			$user_sn = $tmp[1];
			$user_id = $tmp[2];

			$this->it_model->deleteData('user_parking',  array('parking_sn' => $parking_sn, 'user_sn' => $user_sn, 'user_id' => $user_id));
		}

		$this->showSuccessMessage('住戶車位刪除成功');

		redirect(bUrl("setParking"));
	}




	public function editAdmin()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$admin_sn = $this->input->get("sn", TRUE);
		
		//---------------------------------------------------------------------------------------------------------------
		$sys_user_group = array();		
						
		if($admin_sn == "")
		{
			$data["edit_data"] = array
			(			
				'start_date' => date( "Y-m-d" ),
				'forever' => 1,
				'launch' => 1
			);
			
			
			
			$this->display("admin_edit_view",$data);
		}
		else 
		{
			$admin_info = $this->it_model->listData( "edoma_user" , "sn =".$admin_sn);
			
			if (count($admin_info["data"]) > 0) {			
				$edit_data =$admin_info["data"][0];
				
				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["end_date"] ) );
			
				$data['edit_data'] = $edit_data;
			
				$this->display("admin_edit_view",$data);
			}
			else
			{
				redirect(bUrl("admin"));	
			}
		}
	}
	

	public function updateAdmin()
	{
		//$this->load->library('encrypt');
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		if ( ! $this->_validateAdmin())
		{
			$data["edit_data"] = $edit_data;
			
			//dprint($edit_data);
			$this->display("admin_edit_view",$data);
		}
        else 
        {	
        	$arr_data = array(				
        		//"email" =>$edit_data["email"]
				  "name"		=>	tryGetData("name", $edit_data)				
				, "forever"		=>	tryGetData("forever", $edit_data, 1)
				, "launch"		=>	tryGetData("launch", $edit_data, 1)
				, "updated" =>  date( "Y-m-d H:i:s" )
			);        	
			
			if($edit_data["sn"] != FALSE)
			{
				dprint($edit_data);
				//echo $arr_data['password'];
				if($edit_data['password']!=''){
					$arr_data['password'] =  prepPassword($edit_data["password"]);
				}				

				$arr_return = $this->it_model->updateDB( "edoma_user" , $arr_data, "sn =".$edit_data["sn"] );
				//dprint($this->db->last_query());
				if($arr_return['success'])			
				{					
				//	$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();					
				}
				else 
				{
					//$this->output->enable_profiler(TRUE);
					$this->showFailMessage();
				}
				
				redirect(bUrl("admin",TRUE,array("sn")));		
			}
			else 
			{
				$arr_data["account"] = $edit_data["account"];
				$arr_data["password"] = prepPassword($edit_data["password"]);
				$arr_data['created'] = 	 date( "Y-m-d H:i:s" );
				$arr_data['created_by'] = "";
				
				$sys_user_sn = $this->it_model->addData( "edoma_user" , $arr_data );
				//$this->logData("新增人員[".$arr_data["id"]."]");
				if($sys_user_sn > 0)
				{				
					$edit_data["sn"] = $sys_user_sn;
				//	$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();
				}
				else 
				{
					$this->showFailMessage();
				}
				
				redirect(bUrl("admin",TRUE,array("sn")));
			}
        }
	}


	/**
	 * 更新權限群組
	 */
	function _updateWebAdminGroup(&$edit_data)
	{					
		$group_sn_ary = tryGetData("group_sn", $edit_data,array());				
		$old_group_sn_ary = tryGetData("old_group_sn", $edit_data,array());	

		foreach ($group_sn_ary as $key => $group_sn) 
		{
				
			$arr_data = array
			(				
				"launch" => 1,				
				"update_date" => date( "Y-m-d H:i:s" )
			);			
			
			//與原先的群組相同-->不動做
			if(in_array($group_sn, $old_group_sn_ary))
			{
				
				//$result = $this->it_model->updateData( "sys_user_belong_group" , array('launch'=>1,'update_date'=>date( "Y-m-d H:i:s" ) ),"sys_user_sn ='".$sys_user_sn."' and sys_user_group_sn ='".$group_sn."'" );				
				//$condition = "customer_sn ='".tryGetData("customer_sn", $edit_data)."' AND user_sn='".$this->session->userdata('user_sn')."' AND relationship_cat_sn='".$relationship_cat_sn."' AND relationship_sn='".$relationship_sn."' AND relationship_people = '".$relationship_people."' ";
				//$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );
			}
			else //新的群組-->新增
			{
				$arr_data["sys_user_group_sn"] = $group_sn;		
				$arr_data["sys_user_sn"] = $edit_data["sn"];	
				$result_sn = $this->it_model->addData( "sys_user_belong_group" , $arr_data );
			}
		}
		
					
		//需要刪除的群組(將launch設為0)
		$del_land_ary = array_diff($old_group_sn_ary,$group_sn_ary);		
		foreach ($del_land_ary as $key => $group_sn) 
		{			
			
			$arr_data = array
			(				
				"launch" => 0,				
				"update_date" => date( "Y-m-d H:i:s" )
			);		
			
			$condition = "sys_user_group_sn ='".$group_sn."' AND sys_user_sn='".$edit_data["sn"]."' ";
			$result = $this->it_model->updateData( "sys_user_belong_group" , $arr_data, $condition );
		}
	}	


	function _validateAdmin()
	{
		$sn = tryGetValue($this->input->post('sn',TRUE),0);	


		$this->form_validation->set_message('checkAdminAccountExist', '帳號重複');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
		
		if($sn==0)
		{
			$this->form_validation->set_rules('account', $this->lang->line("field_account"), 'trim|required|checkAdminAccountExist' );
			$this->form_validation->set_rules('password', $this->lang->line("field_password"), 'trim|required|min_length[4]|max_length[10]' );
		}
		
		/*	
		if ($role == 'I') {
		$forever = tryGetValue($this->input->post('forever',TRUE),0);
			if($forever!=1)
			{
				$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );	
			}
			$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );
		}
		*/
		$this->form_validation->set_rules( 'name', $this->lang->line("field_name"), 'required|max_length[30]' );
		
		

		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteAdmin()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "sys_user",NULL,$del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("admin", FALSE));	
	}

	public function delAdmin(){
		$sn = $this->input->get('sn',TRUE);
		$query ="DELETE FROM edoma_user WHERE sn={$sn}";
		echo $query;

		$this->it_model->runSqlCmd($query);
		redirect(bUrl("auth", FALSE));	

	}

	public function launchAdmin()
	{		
		$this->ajaxChangeStatus("sys_user","launch",$this->input->post("user_sn", TRUE));
	}

	
	
	
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("admin","editAdmin","updateAdmin"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */