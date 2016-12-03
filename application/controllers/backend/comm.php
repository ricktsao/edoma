<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comm extends Backend_Controller
{

	function __construct()
	{
		parent::__construct();

	}

	public function index()
	{

		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$condition = '';

		// 指定客戶姓名
		$keyword = tryGetData('keyword', $query_key, NULL);
		$data['given_keyword'] = '';
		if(isNotNull($keyword)) {
			$data['given_keyword'] = $keyword;
			$condition .= " AND `name` like '%".$keyword."%' "
						;
		}


		$query = "select SQL_CALC_FOUND_ROWS * "
						."    FROM community  " //left join unit u on s.unit_sn = u.sn
						."   where 1 ".$condition
						;

		$result = $this->it_model->runSql( $query,  $this->per_page_rows , $this->page , array('status'=>'desc', 'name'=>'asc'));

		$data["list"] = $result["data"];

		//取得分頁
		$data["pager"] = $this->getPager($result["count"],$this->page,$this->per_page_rows,"index");


		$this->display("list_view",$data);
	}


	public function editComm()
	{
		$this->addCss("css/chosen.css");
		$this->addJs("js/chosen.jquery.min.js");

		$comm_sn = $this->input->get("sn", TRUE);


		if ($comm_sn == "") {
			$data["edit_data"] = array( 'name' => null
						, 'status' => 1 );

			$this->display("edit_view",$data);

		} else {

			$admin_info = $this->it_model->listData( "community" , "sn =".$comm_sn);

			if (count($admin_info["data"]) > 0) {
				$edit_data =$admin_info["data"][0];

				//$edit_data["start_date"] = $edit_data["start_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["start_date"] ) );
				//$edit_data["end_date"] = $edit_data["end_date"]==NULL?"": date( "Y-m-d" , strtotime( $edit_data["end_date"] ) );


				$data['edit_data'] = $edit_data;
				$this->display("edit_view",$data);
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


		if ( ! $this->_validate()) {

			$data["edit_data"] = $edit_data;

			$this->display("edit_view",$data);
		} else {
        			$arr_data = array(
				 "name"		=>	tryGetData("name", $edit_data)
				, "tel"			=>	tryGetData("tel", $edit_data)
				, "phone"		=>	tryGetData("phone", $edit_data)
				, "addr"		=>	tryGetData("addr", $edit_data)

				//, "start_date"	=>	tryGetData("start_date", $edit_data, NULL)
				//, "end_date"	=>	tryGetData("end_date", $edit_data, NULL)
				//, "forever"		=>	tryGetData("forever", $edit_data, 0)
				, "status"		=>	tryGetData("status", $edit_data, 1)
				, "updated" =>  date( "Y-m-d H:i:s" )
			);

			if ($edit_data["sn"] != FALSE) {
				//dprint($arr_data);
				$arr_return = $this->it_model->updateDB( "community" , $arr_data, "sn =".$edit_data["sn"] );

				if ($arr_return['success']) {
					$this->showSuccessMessage();

				} else {
					//$this->output->enable_profiler(TRUE);
					$this->showFailMessage();
				}
				redirect(bUrl("index",TRUE,array("sn")));
			} else {
				$arr_data["id"] = random_string('alpha', 8);
				$arr_data["created"] = date( "Y-m-d H:i:s" );
				$sys_user_sn = $this->it_model->addData( "community" , $arr_data );

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


	function _validate()
	{

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


		//	$end_date = tryGetValue($this->input->post('end_date',TRUE), 0);
		//	$forever = tryGetValue($this->input->post('forever',TRUE), 0);
		//	$forever = tryGetValue($this->input->post('forever',TRUE),0);
		//	if($forever!=1)
		//	{
		//			$this->form_validation->set_rules( 'end_date', $this->lang->line("field_end_date"), 'required' );
		//	}
		//	$this->form_validation->set_rules( 'start_date', $this->lang->line("field_start_date"), 'required' );

		$this->form_validation->set_rules( 'name', '社區名稱', 'required|max_length[30]' );
		$this->form_validation->set_rules( 'tel', '電話', 'min_length[8]|max_length[20]' );
		$this->form_validation->set_rules( 'phone', '行動電話', 'min_length[8]|max_length[20]' );
		$this->form_validation->set_rules( 'addr', '社區地址', 'min_length[10]|max_length[100]' );

		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}


	// 產生社區SQL檔案，提供社區安裝使用
	public function generateSql()
	{
		$comm_sn = $this->input->get("sn", TRUE);

		if ( $comm_sn > 0 ) {
			$comm_info = $this->it_model->listData( "community" , "sn =".$comm_sn);

			if (count($comm_info["data"]) > 0) {
				$edit_data = $comm_info["data"][0];

				$comm_id = tryGetData('id', $edit_data, NULL);

				if ( isNotNull($comm_id) ) {

					// 讀取安裝SQL的原始檔，寫入 comm_id 之後另存新檔
					$sql_content = read_file('./upload/initial.sql');
					$generate_content = sprintf($sql_content, $comm_id, $comm_id);

					$filename = prepPassword($comm_id);
					$filename = $filename.'.sql';

					if ( ! write_file('./upload/comm_sql/'.$filename, $generate_content) ) {
						$this->showFailMessage();
					} else {
						$this->showSuccessMessage();
					}
				}
			}
		}

		redirect(bUrl("index", FALSE));
	}

	/*
	public function deleteComm()
	{
		$del_ary =array('sn'=> $this->input->post('del',TRUE));

		if($del_ary!= FALSE && count($del_ary)>0)
		{
			$this->it_model->deleteDB( "comm", NULL, $del_ary );
		}
		$this->showSuccessMessage();
		redirect(bUrl("index", FALSE));
	}
	*/


	public function launchComm()
	{
		$this->ajaxChangeStatus("comm","status",$this->input->post("sn", TRUE));
	}


	public function generateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action
		$this->addTopMenu(array("user","editUser","updateUser"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */