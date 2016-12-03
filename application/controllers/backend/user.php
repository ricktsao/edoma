<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Backend_Controller
{

	function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		$this->getAppData();//同步app登入資料

		$condition = ' AND role = "I"';

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$manager_title_value = $this->auth_model->getWebSetting('manager_title');
		if (isNotNull($manager_title_value)) {
			$manager_title_array = array_merge(array(0=>' -- '), explode(',', $manager_title_value));
		}
		$data['manager_title_array'] = $manager_title_array;

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



		$headline = '所有住戶列表';
		if (isNotNull(tryGetData('qy', $query_key, NULL))) {
			if (tryGetData('qy', $query_key) == 'mgrs' ) {
				$condition .= ' AND is_manager = 1 ' ;
				$headline = '管委人員列表';
			}
			if (tryGetData('qy', $query_key) == 'cnts' ) {
				$condition .= ' AND is_contact = 1 ' ;
				$headline = '緊急聯絡人員列表';
			}
			if (tryGetData('qy', $query_key) == 'owns' ) {
				$condition .= ' AND is_owner = 1 ' ;
				$headline = '所有權人列表';
			}
		}
		$data['headline'] = $headline;

		$query = "select SQL_CALC_FOUND_ROWS s.* "
						."    FROM sys_user s " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						."   order by s.building_id, s.name "
						;

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];

		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$this->display("user_list_view",$data);
	}



	public function listOwns()
	{
		$headline = '所有權人列表';
		$data['headline'] = $headline;

		$condition = ' AND role = "I"';
		$condition .= ' AND is_owner = 1 ' ;

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

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];

		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$this->display("user_list_view",$data);
	}



	public function listMgrs()
	{
		$headline = '管委人員列表';
		$data['headline'] = $headline;

		$condition = ' AND role = "I"';
		$condition .= ' AND is_manager = 1 ' ;

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

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];

		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$this->display("user_list_view",$data);
	}



	public function listConts()
	{
		$headline = '緊急聯絡人員列表';
		$data['headline'] = $headline;

		$condition = ' AND role = "I"';
		$condition .= ' AND is_contact = 1 ' ;

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

		$admin_list = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page );
		//dprint( $admin_list["sql"]);
		$data["list"] = $admin_list["data"];

		//取得分頁
		$data["pager"] = $this->getPager($admin_list["count"],$this->page,$this->per_page_rows,"admin");


		$data['b_part_01'] = $b_part_01;
		$data['b_part_02'] = $b_part_02;
		$data['b_part_03'] = $b_part_03;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

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
	public function changeId()
	{
		$this->getAppData();//同步app登入資料

		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");
		$data = array();

		$user_sn = $this->input->get("sn", TRUE);
		$user_id = $this->input->get("id", TRUE);

		$sys_user_group = array();

		$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn." and comm_id='".$this->getCommId()."' and role='I' ");

		if (count($admin_info["data"]) > 0) {
			$edit_data =$admin_info["data"][0];

			$data['user_data'] = $edit_data;

			$this->display("change_id_view",$data);
		}
		else
		{
			redirect(bUrl("index"));
		}
	}





	public function updateId()
	{
		$admin_sn=$this->session->userdata('user_sn');

		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		if ( ! $this->_validate())
		{
			$admin_info = $this->it_model->listData( "sys_user" , "sn =".$edit_data['user_sn']." and comm_id='".$this->getCommId()."' and role='I' ");

			if (count($admin_info["data"]) > 0) {
				$user_data = $admin_info["data"][0];
			}

			$data["user_data"] = $user_data;
			$this->display("change_id_view",$data);
		}
        else
        {
        	$arr_data["id"] = $edit_data["new_id"];
        	$arr_data["updated"] = date("Y-m-d H:i:s");

      	 	$arr_return = $this->it_model->updateData( "sys_user" , $arr_data, "sn =".$edit_data['user_sn']." and comm_id='".$this->getCommId()."' and role='I' " );
			if ($arr_return){
				$this->showSuccessMessage();
			} else {
				$this->showFailMessage();
			}
			redirect(bUrl("index"));
        }
	}



	function _validate()
	{
		$this->form_validation->set_rules('new_id', "新ID", 'trim|required|min_length[4]|max_length[10]' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
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

		$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn." and role='I' ");

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
			&& isNotNull(tryGetData('user_sn', $edit_data, NULL))
			&& isNotNull(tryGetData('user_id', $edit_data, NULL)) ) {

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
					$this->showFailMessage('查無此【'.parking_id_to_text($parking_id).'】車位 或此車位已被使用，請重新確認');
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

		$manager_title_value = $this->auth_model->getWebSetting('manager_title');
		if (isNotNull($manager_title_value)) {
			$manager_title_array = array_merge(array(0=>' -- '), explode(',', $manager_title_value));
		}
		$data['manager_title_array'] = $manager_title_array;

		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		$user_sn = $this->input->get("sn", TRUE);
		$role = $this->input->get("role", TRUE);
		//權組list
		//---------------------------------------------------------------------------------------------------------------
		/*if ( $role == 'I') {
			$condi = ' AND title IN ("住戶", "管委會") AND title != "富網通" ';
		} else {
			$condi = ' AND title NOT IN ("住戶", "管委會") AND title != "富網通" ';
		}*/
			$condi = ' AND title IN ("住戶", "管委會") AND title != "富網通" ';

		$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1 ".$condi , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));

		$data["group_list"] = count($group_list["data"]) > 0 ? $group_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$sys_user_group = array();

		if ($user_sn == "") {
			$data["edit_data"] = array( 'role' => $role,
										'comm_id' => $this->getCommID(),
										'id' => NULL,
										'app_id' => NULL,
										'gender' => 1,
										'is_owner' => 1,
										'is_contact' => 1,
										'is_manager' => 0,
										'gas_right' => 0,
										'voting_right' => 0,
										'start_date' => date( "Y-m-d" ),
										'forever' => 1,
										'launch' => 1
										);

			$data["sys_user_group"] = $sys_user_group;
			$this->display("user_edit_view",$data);

		} else {

			$admin_info = $this->it_model->listData( "sys_user" , "sn =".$user_sn);

			if (count($admin_info["data"]) > 0) {
				$edit_data =$admin_info["data"][0];

				$building_id = explode('_', $edit_data["building_id"]);
				$edit_data['b_part_01'] = $building_id[0];
				$edit_data['b_part_02'] = $building_id[1];
				$edit_data['b_part_03'] = $building_id[2];

				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["end_date"] ) );


				$sys_user_belong_group = $this->it_model->listData("sys_user_belong_group","sys_user_sn = ".$edit_data["sn"]." and launch = 1" );
				foreach($sys_user_belong_group["data"] as $item)
				{
					array_push($sys_user_group,$item["sys_user_group_sn"]);
				}

				$data["sys_user_group"] = $sys_user_group;
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
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");

		$this->load->library('encrypt');

		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);
		}


		$manager_title_value = $this->auth_model->getWebSetting('manager_title');
		if (isNotNull($manager_title_value)) {
			$manager_title_array = array_merge(array(0=>' -- '), explode(',', $manager_title_value));
		}
		$data['manager_title_array'] = $manager_title_array;
		// 戶別相關參數
		$data['building_part_01'] = $this->building_part_01;
		$data['building_part_02'] = $this->building_part_02;
		$data['building_part_03'] = $this->building_part_03;
		$data['building_part_01_array'] = $this->building_part_01_array;
		$data['building_part_02_array'] = $this->building_part_02_array;

		if ( ! $this->_validateUser())
		{
			//權組list
			//---------------------------------------------------------------------------------------------------------------
			$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1" , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));
			$data["group_list"] = count($group_list["data"])>0?$group_list["data"]:array();
			//---------------------------------------------------------------------------------------------------------------

			$role = $this->input->get("role", TRUE);

			$data["edit_data"] = $edit_data;
			$data['role'] = tryGetData('role', $edit_data, $role);

			$data["sys_user_group"] = array();

			$this->display("user_edit_view",$data);
		}
        else
        {
        	$arr_data = array(
				 "comm_id"		=>	tryGetData("comm_id", $edit_data)
				, "id"			=>	tryGetData("id", $edit_data)
				, "app_id"		=>	tryGetData("app_id", $edit_data)
				, "role"		=>	'I'
				, "building_id"	=>	$edit_data['b_part_01'].'_'.$edit_data['b_part_02'].'_'.$edit_data['b_part_03']
				, "name"		=>	tryGetData("name", $edit_data)
				, "tel"			=>	tryGetData("tel", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "addr"		=>	tryGetData("addr", $edit_data)

				, "gender"		=>	tryGetData("gender", $edit_data)
				, "is_contact"		=>	tryGetData("is_contact", $edit_data)
				, "voting_right"	=>	tryGetData("voting_right", $edit_data)
				, "gas_right"		=>	tryGetData("gas_right", $edit_data)
				, "is_manager"		=>	tryGetData("is_manager", $edit_data)
				, "manager_title"	=>	tryGetData("manager_title", $edit_data)
				, "is_owner"		=>	tryGetData("is_owner", $edit_data)
				, "owner_addr"		=>	tryGetData("owner_addr", $edit_data)
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
				//dprint($this->db->last_query());
				if($arr_return['success'])
				{
					$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();

									echo $this->db->last_query();
						/* 同步 同步 同步 同步 同步 */
						$arr_data["sn"] = $edit_data['sn'];
						$this->sync_item_to_server($arr_data, 'updateUser', 'sys_user');
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
				if ( $edit_data["id"] == 'I') {			//住戶用 key code
					$arr_data["id"] = $edit_data["id"];

				} elseif ( in_array($edit_data["id"], array('G','M','S')) ) {
					$arr_data["account"] = $edit_data["account"];
					$arr_data["password"] = prepPassword($edit_data["password"]);
				}

				$arr_data["created"] = date( "Y-m-d H:i:s" );

				$sys_user_sn = $this->it_model->addData( "sys_user" , $arr_data );
				//$this->logData("新增人員[".$arr_data["id"]."]");
				if($sys_user_sn > 0)
				{
					$edit_data["sn"] = $sys_user_sn;
					$this->_updateWebAdminGroup($edit_data);
					$this->showSuccessMessage();

						/* 同步 同步 同步 同步 同步 */
						$arr_data["sn"] = $sys_user_sn;
						$this->sync_item_to_server($arr_data, 'updateUser', 'sys_user');
				}
				else
				{
					$this->showFailMessage();
				}

				redirect(bUrl("index",TRUE,array("sn")));
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


	function _validateUser()
	{
		$sn = tryGetValue($this->input->post('sn',TRUE),0);
		$role = tryGetValue($this->input->post('role',TRUE), 'M');
		$is_manager = tryGetValue($this->input->post('is_manager',TRUE), 0);
		$gas_right = tryGetValue($this->input->post('gas_right',TRUE), 0);
		$end_date = tryGetValue($this->input->post('end_date',TRUE), 0);
		$forever = tryGetValue($this->input->post('forever',TRUE), 0);


		$this->form_validation->set_message('checkAdminAccountExist', 'Error Message');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if($sn==0)
		{
			if ($role != 'I') {
				$this->form_validation->set_rules('account', $this->lang->line("field_account"), 'trim|required|checkAdminAccountExist' );
				$this->form_validation->set_rules('password', $this->lang->line("field_password"), 'trim|required|min_length[4]|max_length[10]' );
			}
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


		$this->form_validation->set_rules( 'b_part_01', $this->building_part_01, 'required|greater_than[0]' );
		$this->form_validation->set_rules( 'b_part_02', $this->building_part_02, 'required|greater_than[0]' );
		$this->form_validation->set_rules( 'b_part_03', $this->building_part_03, 'required|greater_than[0]' );

		$this->form_validation->set_rules( 'name', '姓名', 'required|max_length[30]' );
		$this->form_validation->set_rules( 'tel', '電話', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'phone', '行動電話', 'required|max_length[20]' );
		if ($role != 'I') {
			$this->form_validation->set_rules( 'title', $this->lang->line("field_title"), 'required|max_length[30]' );
		}

		if ($gas_right == 1) {
			$this->form_validation->set_rules( 'addr', '門牌號碼', 'required|min_length[1]' );
		}

		if ($is_manager == 1) {
			$this->form_validation->set_rules( 'manager_title', '管委職稱', 'required|greater_than[0]' );
			$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required');

			if ($forever != 1) {
				$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );
			}
		}

		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteUser()
	{
		$del_ary =array('role'=>'I', 'sn'=> $this->input->post('del',TRUE));

		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "sys_user",NULL,$del_ary );
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