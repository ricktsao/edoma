<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailreg extends Backend_Controller 
{
	
	function __construct() 
	{
		parent::__construct();

	}

	public function user()
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



	public function editContent()
	{
		$user_sn = $this->input->get('user_sn');
		$user_info = $this->it_model->listData("sys_user","sn='".$user_sn."'");	
		if($user_info["count"]==0)
		{
			redirect(bUrl("user"));	
		}
		$user_info = $user_info["data"][0];
		$data["user_info"] = $user_info;
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;

		$this->display("content_form_view",$data);		
	}

	public function updateContent()
	{	
				
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		
		
		
		$update_data = array(
		"comm_id" => $this->getCommId(),
		"type" => tryGetData("type",$edit_data),
		"desc" => tryGetData("desc",$edit_data),
		"booked" => date( "Y-m-d H:i:s" ),
		"booker" => $this->session->userdata("user_sn"),
		"booker_id" => $this->session->userdata("user_sn"),
		"user_name" => tryGetData("user_name",$edit_data),
		"updated" => date( "Y-m-d H:i:s" )
		);
		
		$update_data["type_str"] = tryGetData(tryGetData("type",$edit_data), $mail_box_type_ary);
		
		
		
		$user_info = $this->it_model->listData("sys_user","sn='".tryGetData("user_sn",$edit_data)."'");
		if($user_info["count"]>0)
		{
			$user_info = $user_info["data"][0];
			$update_data["user_sn"] = $user_info["sn"];
			$update_data["user_app_id"] = $user_info["app_id"];
			$update_data["user_building_id"] = $user_info["building_id"];
		}
		
		
		
		$content_sn = $this->it_model->addData( "mailbox" , $update_data );
		if($content_sn > 0)
		{				
			//設定　代收編號　日期＋流水後號３碼
			//--------------------------------------------------
			$mail_no = str_pad($content_sn,10,'0',STR_PAD_LEFT);
			$mail_no = date("Ymd").substr($mail_no,7,3);
			$this->it_model->updateData( "mailbox" , array("no"=>$mail_no,"updated" => date( "Y-m-d H:i:s" )),"sn = ".$content_sn );					
			//--------------------------------------------------
			
			$update_data["sn"] = $content_sn;
			$update_data["no"] = $mail_no;
			
			$this->sync_item_to_server($update_data,"updateMailbox","mailbox");
			
			
			$this->showSuccessMessage();							
		}
		else 
		{
			$this->showFailMessage();					
		}
	
			
			
		redirect(bUrl("user"));	
        
	}
	
	
	
	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  
		$this->addTopMenu(array("user","editContent","updateUser"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */