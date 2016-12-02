<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rent_House extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	/**
	 * faq list page
	 */
	public function index()
	{
		$condition = ' AND del=0 ';

		// 指定客戶姓名
		$keyword = $this->input->get('keyword', true);
		$given_keyword = '';
		if(isNotNull($keyword)) {
			$given_keyword = $keyword;
			$condition .= " AND ( `title` like '%".$keyword."%' "
						."      OR `addr` like '%".$keyword."%' "
						."      OR `living` like '%".$keyword."%' "
						."      OR `traffic` like '%".$keyword."%' "
						."      OR `desc` like '%".$keyword."%' "
						."      OR `rent_price` = '".$keyword."'  ) "
						;
		}

		$room = $this->input->get('room', true);
		$given_room = '';
		if(isNotNull($room)) {
			$given_room = $room;
			$condition .= " AND `room` = '".$room."' ";
		}
		$livingroom = $this->input->get('livingroom', true);
		$given_livingroom = '';
		if(isNotNull($livingroom)) {
			$given_livingroom = $livingroom;
			$condition .= " AND `livingroom` = '".$livingroom."' ";
		}
		$bathroom = $this->input->get('bathroom', true);
		$given_bathroom = '';
		if(isNotNull($bathroom)) {
			$given_bathroom = $bathroom;
			$condition .= " AND `bathroom` = '".$bathroom."' ";
		}
		$balcony = $this->input->get('balcony', true);
		$given_balcony = '';
		if(isNotNull($balcony)) {
			$given_balcony = $balcony;
			$condition .= " AND balcony = '".$balcony."' ";
		}


		$query = 'SELECT SQL_CALC_FOUND_ROWS *
					FROM edoma_house_to_rent
					WHERE ( 1 = 1 ) '.$condition
				;
		$dataset = $this->it_model->runSql( $query , NULL , NULL , array("sn"=>"desc","rent_price"=>"asc"));

		$data["dataset"] = count($dataset["data"]) > 0 ? $dataset["data"] : array();
		//---------------------------------------------------------------------------------------------------------------
		$data['given_keyword'] = $given_keyword;
		$data['given_room'] = $given_room;
		$data['given_livingroom'] = $given_livingroom;
		$data['given_bathroom'] = $given_bathroom;
		$data['given_balcony'] = $given_balcony;

		$this->display("index_view",$data);
	}


	public function edit()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$sn = $this->input->get("sn", TRUE);
		$role = $this->input->get("role", TRUE);

		$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
		$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
		
		$this->addCss("css/bootstrap-fonts.css");		
			
		//社區
		$community_list = $this->it_model->listData("community","status =1",NULL,NULL,array("name"=>"asc"));
		$data["community_list"] = $community_list["data"];


		//權組list
		//---------------------------------------------------------------------------------------------------------------
		if ( $role == 'I') {
			$condi = ' AND title IN ("住戶", "管委會") AND title != "富網通" ';
		} else {
			$condi = ' AND title NOT IN ("住戶", "管委會") AND title != "富網通" ';
		}

		$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1 ".$condi , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));

		$data["group_list"] = count($group_list["data"]) > 0 ? $group_list["data"] : array();
		//---------------------------------------------------------------------------------------------------------------

		$sys_user_group = array();		
						
		if($sn == "")
		{
			$data["edit_data"] = array
			(
				'start_date' => date( "Y-m-d" ),
				'end_date' => date( "Y-m-d", strtotime("+1 month") ),
				'forever' => 1,
				'launch' => 1,
				//'rent_type' => array(),
				//'house_type' => array(),
				'furniture' => '',
				'electric' => ''
			);
			
			$data["sys_user_group"] = $sys_user_group;
			$this->display("edit_view",$data);
		}
		else 
		{
			$result = $this->it_model->listData( "edoma_house_to_rent" , "sn =".$sn);
			
			if (count($result["data"]) > 0) {			
				$edit_data = $result["data"][0];
				
				$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( tryGetData('end_date',$edit_data, '+1 month' ) ) );
				
				
				$data['edit_data'] = $edit_data;
				$this->display("edit_view",$data);
			}
			else
			{
				redirect(bUrl("index"));	
			}
		}
	}



	public function update()
	{
		$this->load->library('encrypt');
		
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}

		if ( ! $this->_validateData() ) {
			//權組list
			//---------------------------------------------------------------------------------------------------------------		
			//$group_list = $this->it_model->listData( "sys_user_group" , "launch = 1" , NULL , NULL , array("sort"=>"asc","sn"=>"desc"));		
			//$data["group_list"] = count($group_list["data"])>0?$group_list["data"]:array();
			//---------------------------------------------------------------------------------------------------------------
			
			$this->addCss("css/duallistbox/bootstrap-duallistbox.min.css");
			$this->addJs("js/duallistbox/jquery.bootstrap-duallistbox.min.js");
			
			$this->addCss("css/bootstrap-fonts.css");		
				
			//社區
			$community_list = $this->it_model->listData("community","status =1",NULL,NULL,array("name"=>"asc"));
			$data["community_list"] = $community_list["data"];

			//$edit_data['rent_type'] = implode(',', tryGetData('rent_type', $edit_data, array()));
			//$edit_data['house_type'] = implode(',', tryGetData('house_type', $edit_data, array()));
			$edit_data['furniture'] = implode(',', tryGetData('furniture', $edit_data, array()));
			$edit_data['electric'] = implode(',', tryGetData('electric', $edit_data, array()));
			$data["edit_data"] = $edit_data;
			
			$data["sys_user_group"] = array();
			
			$this->display("edit_view",$data);
		}
        else 
        {

			$comm_id_list = NULL;
			if ( isNotNull(tryGetData("comms", $edit_data, NULL)) ) {
				$comm_id_array = $edit_data['comms'];
				$comm_id_list = implode(",", $comm_id_array);
			}


        	$arr_data = array(
				 "sn"				=>	tryGetData("sn", $edit_data, NULL)
				, 'comm_id'			=>  $comm_id_list
				, "rent_type"		=>	tryGetData("rent_type", $edit_data)
				, "house_type"		=>	tryGetData("house_type", $edit_data)
				, "furniture"		=>	implode(',', tryGetData("furniture", $edit_data, array()))
				, "electric"		=>	implode(',', tryGetData("electric", $edit_data, array()))
				, "title"		=>	tryGetData("title", $edit_data)
				, "name"		=>	tryGetData("name", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "room"		=>	tryGetData("room", $edit_data)
				, "livingroom"		=>	tryGetData("livingroom", $edit_data)
				, "bathroom"		=>	tryGetData("bathroom", $edit_data)
				, "balcony"		=>	tryGetData("balcony", $edit_data)
				, "locate_level"	=>	tryGetData("locate_level", $edit_data)
				, "total_level"		=>	tryGetData("total_level", $edit_data)
				, "area_ping"		=>	tryGetData("area_ping", $edit_data)
				, "rent_price"		=>	tryGetData("rent_price", $edit_data)
				, "deposit"			=>	tryGetData("deposit", $edit_data)
				, "addr"		=>	tryGetData("addr", $edit_data)
				, "move_in"		=>	tryGetData("move_in", $edit_data)
				, "rent_term"		=>	tryGetData("rent_term", $edit_data)
				, "current"		=>	tryGetData("current", $edit_data)
				, "usage"		=>	tryGetData("usage", $edit_data)
				, "meterial"		=>	tryGetData("meterial", $edit_data)

				, "flag_parking"		=>	tryGetData("flag_parking", $edit_data, 0)
				, "flag_cooking"		=>	tryGetData("flag_cooking", $edit_data, 0)
				, "flag_pet"		=>	tryGetData("flag_pet", $edit_data, 0)
				, "gender_term"		=>	tryGetData("gender_term", $edit_data)
				, "tenant_term"		=>	tryGetData("tenant_term", $edit_data)
				, "living"		=>	tryGetData("living", $edit_data)
				, "traffic"		=>	tryGetData("traffic", $edit_data)
				, "desc"		=>	tryGetData("desc", $edit_data)
				, "start_date"		=>	tryGetData("start_date", $edit_data)
				, "end_date"		=>	tryGetData("end_date", $edit_data)
				, "forever"		=>	tryGetData("forever", $edit_data, 0)
				, "launch"		=>	tryGetData("launch", $edit_data, 0)
				, "created" =>  date( "Y-m-d H:i:s" )
				, "updated" =>  date( "Y-m-d H:i:s" )
			);        	
			
			if($edit_data["sn"] != FALSE)
			{
				$arr_return = $this->it_model->updateDB( "edoma_house_to_rent" , $arr_data, "sn =".$edit_data["sn"] );
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
				
				$rent_sn = $this->it_model->addData( "edoma_house_to_rent" , $arr_data );
				//$this->logData("新增人員[".$arr_data["id"]."]");

				if($rent_sn > 0) {
					$arr_data["sn"] = $rent_sn;
					$this->showSuccessMessage();

						/* 同步 同步 同步 同步 同步 */
						//$arr_data["sn"] = $rent_sn;
						//$this->sync_item_to_server($arr_data, 'updateRentHouse', 'house_to_rent');
				}
				else 
				{
					$this->showFailMessage();
				}
				
			}












			/* 同步 同步 同步 同步 同步  ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ */
			$orig_comm_id_list = tryGetData("orig_comm_id", $edit_data); 
			$orig_comm_id_array = explode(",", $orig_comm_id_list);		
			
			
			//需要更新至web_menu_content的資料
			//----------------------------------------------------------------
			//dprint($comm_id_array);
			//dprint($orig_comm_id_array);
			foreach( $comm_id_array as $key => $comm_id )
			{
				if(isNull($comm_id))
				{
					continue;
				}
				$update_data = $arr_data;
				$update_data["comm_id"] = $comm_id;
				$update_data["del"] = 0;
				$this->updateCommRent($update_data);
			}
			//$comm_id_ary
			//----------------------------------------------------------------
			
			//web_menu_content需要刪除的檔案
			//----------------------------------------------------------------
			$del_comm_ary = array_diff($orig_comm_id_array,$comm_id_array);
			//dprint($del_comm_ary);
			foreach( $del_comm_ary as $key => $del_comm_id )
			{					
				if(isNull($del_comm_id))
				{
					continue;
				}
				$update_data = $arr_data;
				$update_data["comm_id"] = $del_comm_id;
				$update_data["del"] = 1;
				$this->updateCommRent($update_data);
			}
			//exit;
			//----------------------------------------------------------------
			/* 同步 同步 同步 同步 同步   ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑ */







			redirect(bUrl("index",TRUE,array("sn")));
        }
	}


	function _validateData()
	{
		$sn = tryGetValue($this->input->post('sn',TRUE),0);
		$is_manager = tryGetValue($this->input->post('is_manager',TRUE), 0);
		$end_date = tryGetValue($this->input->post('end_date',TRUE), 0);
		$forever = tryGetValue($this->input->post('forever',TRUE), 0);

		$this->form_validation->set_message('checkAdminAccountExist', 'Error Message');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
		
		
		$forever = tryGetValue($this->input->post('forever',TRUE),0);
		if($forever!=1) {
			$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );	
		}
		$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );
		
		$this->form_validation->set_rules( 'comms', '發佈社區', 'required' );

		$this->form_validation->set_rules( 'rent_price', '租金 ', 'required|less_than[300000]|greater_than[1000]' );
		$this->form_validation->set_rules( 'deposit', '押金', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'area_ping', '面積', 'required|less_than[1000]|greater_than[0]' );
		$this->form_validation->set_rules( 'room', '格局-房', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'livingroom', '格局-廳', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'bathroom', '格局-衛', 'required|less_than[10]|greater_than[0]' );
		$this->form_validation->set_rules( 'balcony', '格局-陽台', 'less_than[10]' );
		$this->form_validation->set_rules( 'locate_level', '位於幾樓', 'required|less_than[30]|greater_than[0]' );
		$this->form_validation->set_rules( 'total_level', '總樓層', 'required|less_than[30]|greater_than[0]' );


		$this->form_validation->set_rules( 'title', '租屋標題', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'name', '聯絡人', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'phone', '聯絡電話', 'required|max_length[50]' );
		$this->form_validation->set_rules( 'meterial', '隔間材質', 'max_length[50]' );
		$this->form_validation->set_rules( 'addr', '地址', 'required|max_length[100]' );
		$this->form_validation->set_rules( 'move_in', '可遷入日', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'rent_term', '最短租期', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'current', '現況', 'required|max_length[20]' );
		$this->form_validation->set_rules( 'desc', '特色說明', 'required|max_length[300]' );

		if ($is_manager == 1) {
			$this->form_validation->set_rules( 'manager_title', $this->lang->line("field_manager_title"), 'required|max_length[30]' );
			$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required');
			
		}

		//$this->form_validation->set_rules('email', $this->lang->line("field_email"), 'trim|required|valid_email|checkAdminEmailExist' );
		//$this->form_validation->set_rules( 'sys_user_group', $this->lang->line("field_admin_belong_group"), 'required' );
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}

	/**************************************************/
	/**************************************************/
	/**************************************************/

	/**
	 * 設定照片
	 */
	public function photoSetting()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");		
		
		$edoma_house_to_rent_sn = tryGetData('sn', $_GET, NULL);
		
		if ( isNotNull($edoma_house_to_rent_sn) ) {
			## 物件基本資料
			$admin_info = $this->it_model->listData( "edoma_house_to_rent" , "sn =".$edoma_house_to_rent_sn);
			
			if (count($admin_info["data"]) > 0) {
				$edit_data =$admin_info["data"][0];
				
				$data['house_data'] = $edit_data;

				## 既有照片list
				$exist_parking_list = $this->it_model->listData( "edoma_house_to_rent h LEFT JOIN edoma_house_to_rent_photo p ON h.sn = p.edoma_house_to_rent_sn" 
														, "h.del=0 and p.del=0 and edoma_house_to_rent_sn = ".$edoma_house_to_rent_sn 
														, NULL , NULL , array("p.sn"=>"asc"));

				$data["exist_photo_array"] = count($exist_parking_list["data"]) > 0 ? $exist_parking_list["data"] : array();
				
				$this->display("photo_setting_view",$data);
			}
			else
			{
				redirect(bUrl("index"));	
			}

		} else {

			redirect(bUrl("index"));	
		}
	}



	/**
	 * 設定照片
	 */
	public function updatePhoto()
	{
		$edit_data = array();
		foreach( $_POST as $key => $value ) {
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		
		$edoma_house_to_rent_sn = tryGetData('edoma_house_to_rent_sn', $edit_data, NULL);
		$comm_id = tryGetData('comm_id', $edit_data, NULL);
		$config['upload_path'] = './upload/website/house_to_rent/'.$edit_data['edoma_house_to_rent_sn'];
		$config['allowed_types'] = 'jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1200';
		$config['max_height']  = '1000';
		$config['overwrite']  = true;

		$filename = date( "YmdHis" )."_".rand( 100000 , 999999 );
		$config['file_name'] = $filename;

		$this->load->library('upload', $config);

		if (!is_dir('./upload/website/house_to_rent/'.$edit_data['edoma_house_to_rent_sn'])) {
				mkdir('./upload/website/house_to_rent/'.$edit_data['edoma_house_to_rent_sn'], 0777, true);
		}

		if ( isNull($edoma_house_to_rent_sn) || ! $this->upload->do_upload('filename'))
		{
			$error = array('error' => $this->upload->display_errors());

			$this->showFailMessage('物件照片上傳失敗，請稍後再試　' .$error['error'] );

		} else {

			$upload = $this->upload->data();
			$filename = tryGetData('file_name', $upload);

			// 製作縮圖
			// image_thumb('./upload/website/house_to_rent/'.$edit_data['house_to_rent_sn'], 'ddd_'.$filename, '120', '100');

			$arr_data = array('sn'					=>	tryGetData('sn', $edit_data, NULL)
							//, 'comm_id'				=>  tryGetData('comm_id', $edit_data)
							, 'edoma_house_to_rent_sn'	=>	tryGetData('edoma_house_to_rent_sn', $edit_data)
							, 'filename'			=>	$filename
							, 'title'				=>	tryGetData('title', $edit_data)
							, 'updated'				=>	date('Y-m-d H:i:s')
							, 'updated_by'			=>	$this->session->userdata('user_name')
							);

			$sn = $this->it_model->addData('edoma_house_to_rent_photo', $arr_data);
			if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') {

				$this->showSuccessMessage('物件照片上傳成功');

			
				//將檔案複製到commapi folder 下  - - - - - - - - - - - - - - - - - -
				if (!is_dir($this->config->item('edoma_folder_path').'house_to_rent/'.$edit_data['edoma_house_to_rent_sn'].'/')) {
						mkdir($this->config->item('edoma_folder_path').'house_to_rent/'.$edit_data['edoma_house_to_rent_sn'].'/', 0777, true);
						dprint('@'.__LINE__);
				}
				copy('./upload/website/house_to_rent/'.$edit_data['edoma_house_to_rent_sn'].'/'.$filename , $this->config->item('edoma_folder_path').'house_to_rent/'.$edit_data['edoma_house_to_rent_sn'].'/'.$filename);

				$update_data = $arr_data;
				$update_data["edoma_sn"] = $sn;
				$update_data["del"] = 0;
				$this->updateCommRentPhoto($update_data);
				// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


			} else {
				$this->showFailMessage('物件照片上傳失敗，請稍後再試');
			}
		}

		redirect(bUrl("photoSetting"));
	}



	public function deleteHouse()
	{
		
		$del_ary = tryGetData("del",$_POST,array());

		//社區主機刪除
		//----------------------------------------------------------------------------------------------------
		foreach ($del_ary as  $house_sn) 
		{
			//先同步社區 找出此售屋資訊有發佈到哪些社區
			$tmp = $this->it_model->listData("edoma_house_to_rent", "sn=".$house_sn);	
			if( $tmp["count"] == 0) {
				continue;
			} 
			$house_data = $tmp["data"][0];



			/* 同步 同步 同步 同步 同步  ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ ↓ */
			$comm_id_list = tryGetData("comm_id", $house_data); 
			$comm_id_array = explode(",", $comm_id_list);		
			
			//----------------------------------------------------------------
			
			//web_menu_content需要刪除的檔案
			//----------------------------------------------------------------
			foreach( $comm_id_array as $key => $del_comm_id )
			{					
				if(isNull($del_comm_id)) {
					continue;
				}
				$update_data = array();
				$update_data["sn"] = $house_sn;
				$update_data["comm_id"] = $del_comm_id;
				$update_data["del"] = 1;
				$this->updateCommRent($update_data);
			}
			//exit;
			//----------------------------------------------------------------
			/* 同步 同步 同步 同步 同步   ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑  ↑ */



			$result = $this->it_model->updateData( "edoma_house_to_rent" , array("del"=>1, "updated"=>date("Y-m-d H:i:s")), "sn ='".$house_sn."'" );
			
		}
		//----------------------------------------------------------------------------------------------------

		
		$this->showSuccessMessage();
		
		redirect(bUrl("index", FALSE));	
	}


	/**
	 * 刪除照片
	 */
	function deletePhoto()
	{
		$del_array = $this->input->post("del",TRUE);

		foreach( $del_array as $item ) {

			$tmp = explode('!@', $item);
			$sn = $tmp[0];
			$edoma_house_to_rent_sn = $tmp[1];
			$filename = $tmp[2];

			@unlink('./upload/website/house_to_rent/'.$edoma_house_to_rent_sn.'/'.$filename);
			//unlink('./upload/website/house_to_rent/'.$house_to_rent_sn.'/thumb_'.$filename);

			$del = $this->it_model->updateDB( "edoma_house_to_rent_photo" , array('del' => 1), "sn =".$sn." and edoma_house_to_rent_sn ='".$edoma_house_to_rent_sn."'" );

			if ($del) {
			
				/* 同步 同步 同步 同步 同步 */
				$update_data = $arr_data;
				$update_data["edoma_sn"] = $sn;
				$update_data["del"] = 1;
				$this->updateCommRentPhoto($update_data);
			}



		}

		// 檔案同步至server 檔案同步至server 檔案同步至server
		$this->sync_file('house_to_sale/'.$sn.'/');

		$this->showSuccessMessage('物件照片刪除成功');

		redirect(bUrl("photoSetting"));
	}



	/**************************************************/
	/**************************************************/
	/**************************************************/


	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */