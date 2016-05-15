<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parking extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	/**
	 * faq list page
	 */
	public function index()
	{
		$query_key = array();
		foreach( $_GET as $key => $value ) {
			$query_key[$key] = $this->input->get($key,TRUE);
		}

		$p_part_01 = tryGetData('p_part_01', $query_key, NULL);
		$p_part_02 = tryGetData('p_part_02', $query_key, NULL);
		$p_part_03 = tryGetData('p_part_03', $query_key, NULL);

		$condition = '';
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
		}

		$query = 'SELECT SQL_CALC_FOUND_ROWS p.*, up.car_number, u.building_id, u.name, u.tel, u.phone, u.role
					FROM parking p left join user_parking up on p.sn = up.parking_sn
					left join sys_user u on up.user_sn = u.sn
					WHERE ( 1 = 1 ) '.$condition
					;
		$exist_parking_list = $this->it_model->runSql( $query ,  $this->per_page_rows , $this->page , array("p.sn"=>"asc"));

		$user_parking_list = array();
		//$i = 0; 
		//$j = 0; 
		if (count($exist_parking_list["data"]) > 0) {
			foreach ($exist_parking_list["data"] as $item) {
				//$i++;
				//if (isNotNull(tryGetData('user_sn', $item, NULL))) {
				//	$j++;
				//}
				$user_parking_list[] = $item;
			}
		}

		$data["user_parking_list"] = $user_parking_list;
		//$data["i"] = $i;
		//$data["j"] = $j;

		
		//取得分頁
		$data["pager"] = $this->getPager($exist_parking_list["count"],$this->page,$this->per_page_rows,"index");
		//---------------------------------------------------------------------------------------------------------------


		$data['p_part_01'] = $p_part_01;
		$data['p_part_02'] = $p_part_02;
		$data['p_part_03'] = $p_part_03;

		// 車位別相關參數
		$data['parking_part_01'] = $this->parking_part_01;
		$data['parking_part_02'] = $this->parking_part_02;
		$data['parking_part_03'] = $this->parking_part_03;
		$data['parking_part_01_array'] = $this->parking_part_01_array;
		$data['parking_part_02_array'] = $this->parking_part_02_array;


		$this->display("index_view",$data);
	}



	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("contentList", "updateLandSummary"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */