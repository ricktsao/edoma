<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parkuser extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->getAppData();//同步app登入資料
		
		$condition = ' AND role = "P"';

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		/*$p_part_01 = tryGetData('p_part_01', $query_key, NULL);
		$p_part_02 = tryGetData('p_part_02', $query_key, NULL);
		$p_part_03 = tryGetData('p_part_03', $query_key, NULL);

		$parking_id = NULL;
		if (isNotNull($p_part_01) && $p_part_01 > 0) {
			$parking_id = $p_part_01.'_';
		}
		if (isNotNull($p_part_01) && isNotNull($p_part_02) && $p_part_01 > 0 && $p_part_02 > 0) {
			$parking_id .= $p_part_02.'_';
		}
		if (isNotNull($p_part_01) && isNotNull($p_part_02) && isNotNull($p_part_03) && $p_part_01 > 0 && $p_part_02 > 0 && $p_part_03 > 0) {
			$parking_id .= $p_part_03;
		}
		if (isNotNull($parking_id)) {
			$condition .= ' AND parking_id like "'.$parking_id.'%"' ;
		}*/

		// 指定客戶姓名
		$keyword = tryGetData('keyword', $query_key, NULL);	
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND ( `id` like '%".$keyword."%' "
						."      OR `name` like '%".$keyword."%' "
						."      OR `tel` like '".$keyword."%' " 
						."      OR `phone` like '".$keyword."%'  ) "
						;
		}


		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by s.building_id, s.name "
						;

		$result = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
//dprint( $result["sql"]);
		$data["list"] = $result["data"];
		
		//取得分頁
		$data["pager"] = $this->getPager($result["count"],$this->page,$this->per_page_rows,"admin");

		/*
		$data['p_part_01'] = $p_part_01;
		$data['p_part_02'] = $p_part_02;
		$data['p_part_03'] = $p_part_03;

		// 車位別相關參數
		$data['parking_part_01'] = $this->parking_part_01;
		$data['parking_part_02'] = $this->parking_part_02;
		$data['parking_part_03'] = $this->parking_part_03;
		$data['parking_part_01_array'] = $this->parking_part_01_array;
		$data['parking_part_02_array'] = $this->parking_part_02_array;
		*/

		$this->display("user_list_view",$data);
	}





	/**
	*   匯出 excel
	*/

	public function exportExcel()
	{

		$condition = ' AND role = "I"';

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$b_part_01 = tryGetData('b_part_01', $query_key, NULL);
		$b_part_02 = tryGetData('b_part_02', $query_key, NULL);
		$b_part_03 = tryGetData('b_part_03', $query_key, NULL);
		
		// 搜尋戶別
		$building_id = NULL;
		if (isNotNull($b_part_01) && $b_part_01 > 0) {
			$building_id = $b_part_01.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && $b_part_01 > 0 && $b_part_02 > 0) {
			$building_id .= $b_part_02.'_';
		}
		if (isNotNull($b_part_01) && isNotNull($b_part_02) && isNotNull($b_part_03) && $b_part_01 > 0 && $b_part_02 > 0 && $b_part_03 > 0) {
			$building_id .= $b_part_03;
		}
		if (isNotNull($building_id)) {
			$condition .= ' AND building_id like "'.$building_id.'%"' ;
		}

		// 指定客戶姓名
		$keyword = tryGetData('keyword', $query_key, NULL);	
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND ( `id` like '%".$keyword."%' "
						."      OR `name` like '%".$keyword."%' "
						."      OR `tel` like '".$keyword."%' " 
						."      OR `phone` like '".$keyword."%'  ) "
						;
		}

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by s.building_id, s.name "
						;

		$admin_list = $this->it_model->runSql( $query,  NULL, NULL );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];
		
		//取得分頁
		//$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$this->load->view($this->config->item('admin_folder').'/user/excel_user_list_view', $data);
	}




	/**
	 * 設定住戶車位
	 */
	public function setParking()
	{
		$this->getAppData();//同步app登入資料
		
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		$data = array();


		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$p_part_01 = tryGetData('p_part_01', $query_key, NULL);
		$p_part_02 = tryGetData('p_part_02', $query_key, NULL);
		$p_part_03 = tryGetData('p_part_03', $query_key, NULL);

		$data['p_part_01'] = $p_part_01;
		$data['p_part_02'] = $p_part_02;
		$data['p_part_03'] = $p_part_03;


		// 車位別相關參數
		$data['parking_part_01'] = $this->parking_part_01;
		$data['parking_part_02'] = $this->parking_part_02;
		$data['parking_part_03'] = $this->parking_part_03;
		$data['parking_part_01_array'] = $this->parking_part_01_array;
		$data['parking_part_02_array'] = $this->parking_part_02_array;

		$user_sn = $this->input->get("sn", TRUE);
		$user_id = $this->input->get("id", TRUE);

		//既有車位list
		//---------------------------------------------------------------------------------------------------------------
		//$exist_parking_list = $this->it_model->listData( "parking p left join user_parking up on p.sn = up.parking_sn" 
		//										, "user_sn = ".$user_sn , NULL , NULL , array("p.parking_id"=>"asc","sn"=>"desc"));
		$exist_parking_list = $this->it_model->listData( "parking p left join user_parking up on p.sn = up.parking_sn " 
												, "user_sn = ".$user_sn , NULL , NULL , array("p.parking_id"=>"asc" ));

		$data["exist_parking_array"] = count($exist_parking_list["data"]) > 0 ? $exist_parking_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------

		$sys_user_group = array();		
		
		$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn." and role='P' ");
		
		if (count($admin_info["data"]) > 0) {
			$edit_data =$admin_info["data"][0];
			
			$data['user_data'] = $edit_data;
			
			$this->display("parking_setting_view",$data);
		}
		else
		{
			redirect(bUrl("index"));	
		}
	}


	/**
	 * 搜尋還沒有住戶登錄的車位
	 */
	public function xxxxxx_ajaxGetParking()
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
		$now = date('Y-m-d H:i:s');
		$edit_data = array();
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		if ( isNotNull(tryGetData('p_part_01', $edit_data, NULL)) 
			&& isNotNull(tryGetData('p_part_02', $edit_data, NULL)) 
			&& isNotNull(tryGetData('p_part_03', $edit_data, NULL)) 
			&& isNotNull(tryGetData('user_sn', $edit_data, NULL)) ) {

			$p_part_01 = tryGetData('p_part_01', $edit_data);
			$p_part_02 = tryGetData('p_part_02', $edit_data);
			$p_part_03 = tryGetData('p_part_03', $edit_data);
			
			$parking_id = $p_part_01.'_'.$p_part_02.'_'.$p_part_03; 
			$parking_sn = $this->auth_model->getFreeParkingSn($parking_id);
			if ($parking_sn > 0 ) {
				$arr_data = array('comm_id' => $this->getCommId() 
								, 'parking_sn'	=>	$parking_sn
								, 'user_sn'	=>	tryGetData('user_sn', $edit_data)
								, 'person_sn'	=>	0
								, 'user_id'	=>	tryGetData('user_id', $edit_data)
								, 'car_number'	=>	tryGetData('car_number', $edit_data)
								, 'updated'	=>	$now
								, 'updated_by'	=>	$this->session->userdata('user_name')
								, 
								);
				
				$query = 'INSERT INTO `user_parking` '
						.'       (`comm_id`, `parking_sn`, `user_sn`, `person_sn` '
						.'        , `user_id`, `car_number`, `updated`, `updated_by`) '
						.'VALUES (?, ?, ?, ? '
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
					$this->showFailMessage('【'.parking_id_to_text($parking_id).'】車位已被使用 或 無此車位，請重新確認');
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




	public function editUser()
	{
		$this->getAppData();//同步app登入資料
		
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$user_sn = $this->input->get("sn", TRUE);
		$role = 'P';
		
						
		if ($user_sn == "") {
			$data["edit_data"] = array( 'role' => $role,
										'comm_id' => $this->getCommID(),
										'id' => NULL,
										'app_id' => NULL,
										'gender' => 1,
										'start_date' => date( "Y-m-d" ),
										'forever' => 1,
										'launch' => 1
										);
			
			$this->display("user_edit_view",$data);

		} else {

			$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn);
			
			if (count($admin_info["data"]) > 0) {			
				$edit_data =$admin_info["data"][0];
				
				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["end_date"] ) );
				

				$data['edit_data'] = $edit_data;
				$this->display("user_edit_view",$data);
			}
			else
			{
				redirect(bUrl("index"));	
			}
		}
	}
	

	public function updateUser()
	{
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		

		if ( ! $this->_validateUser())
		{

			$role = 'P';

			$data["edit_data"] = $edit_data;
			$data['role'] = tryGetData('role', $edit_data, $role);
			
			$this->display("user_edit_view",$data);
		}
        else 
        {
        	$arr_data = array(
				 "comm_id"		=>	tryGetData("comm_id", $edit_data)
				, "role"		=>	'P'
				, "id"			=>	tryGetData("id", $edit_data, NULL)
				, "app_id"		=>	tryGetData("app_id", $edit_data, NULL)
				, "name"		=>	tryGetData("name", $edit_data)
				, "tel"			=>	tryGetData("tel", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "addr"		=>	tryGetData("addr", $edit_data)
				, "gender"		=>	tryGetData("gender", $edit_data)

				, "start_date"	=>	tryGetData("start_date", $edit_data, NULL)
				, "end_date"	=>	tryGetData("end_date", $edit_data, NULL)
				, "forever"		=>	tryGetData("forever", $edit_data, 0)
				, "launch"		=>	tryGetData("launch", $edit_data, 0)
				, "updated" =>  date( "Y-m-d H:i:s" )
				, "is_sync" =>  0
			);
			
			if($edit_data["sn"] != FALSE)
			{
				//dprint($arr_data);
				$arr_return = $this->it_model->updateDB( "sys_user" , $arr_data, "sn =".$edit_data["sn"] );

				if($arr_return['success'])			
				{
					$this->showSuccessMessage();
					
				}
				else 
				{
					//$this->output->enable_profiler(TRUE);
					$this->showFailMessage();
				}
				
				redirect(bUrl("index",TRUE,array("sn")));		
			}
			else 
			{
				$arr_data["created"] = date( "Y-m-d H:i:s" ); 	
				$sys_user_sn = $this->it_model->addData( "sys_user" , $arr_data );

				if($sys_user_sn > 0) {				
					$edit_data["sn"] = $sys_user_sn;
					$this->showSuccessMessage();

				} else {
					$this->showFailMessage();
				}
				
				redirect(bUrl("index",TRUE,array("sn")));
			}
        }
	}


	function _validateUser()
	{
		$end_date = tryGetValue($this->input->post('end_date',TRUE), 0);
		$forever = tryGetValue($this->input->post('forever',TRUE), 0);


		$this->form_validation->set_message('checkAdminAccountExist', 'Error Message');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
		
		
		$forever = tryGetValue($this->input->post('forever',TRUE),0);
		if($forever!=1)
		{
				$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );	
		}
			$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );

		$this->form_validation->set_rules( 'name', $this->lang->line("field_name"), 'required|max_length[30]' );
		$this->form_validation->set_rules( 'tel', '電話', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'phone', '手機號碼', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'addr', '住址', 'required|max_length[20]' );



		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteUser()
	{
		$del_ary =array('role'=>'P', 'sn'=> $this->input->post('del',TRUE));		
		
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "sys_user", NULL, $del_ary );				
		}
		$this->showSuccessMessage();
		redirect(bUrl("admin", FALSE));	
	}

	public function launchUser()
	{		
		$this->ajaxChangeStatus("sys_user","launch",$this->input->post("user_sn", TRUE));
	}

	
	
	
	/**
	 * 查詢server user 登入app資料
	 **/
	public function getAppData()
	{		
		
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppUser";		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		$app_data_ary =  json_decode($json_data, true);
		
		//dprint($app_data_ary );exit;
		
		if( ! is_array($app_data_ary))
		{
			$app_data_ary = array();
		}
		
		
		foreach( $app_data_ary as $key => $s_user_info ) 
		{		
		
		
			$update_data = array(			
			"app_id" => $s_user_info["app_id"],			
			"app_last_login_ip" => $s_user_info["app_last_login_ip"],			
			"app_last_login_time" => $s_user_info["app_last_login_time"],
			"app_login_time" => $s_user_info["app_login_time"],
			"app_use_cnt" => $s_user_info["app_use_cnt"],							
			"updated" => date( "Y-m-d H:i:s" )
			);
			
			$condition = "sn = '".$s_user_info["client_sn"]."' ";
			$result = $this->it_model->updateData( "sys_user" , $update_data,$condition );
		}		
		
	}
	
	
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("user","editUser","updateUser"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */