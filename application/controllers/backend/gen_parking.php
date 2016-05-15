<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gen_parking extends Backend_Controller {
	
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
		foreach( $_POST as $key => $value ) {
			$query_key[$key] = $this->input->post($key,TRUE);
		}

		$comm_id = tryGetData('comm_id', $query_key, NULL);
		$p_part_01 = tryGetData('p_part_01', $query_key, NULL);
		$p_part_02 = tryGetData('p_part_02', $query_key, NULL);
		$start = (int) tryGetData('start', $query_key, 0);
		$end = (int) tryGetData('end', $query_key, 0);

		if ( isNotNull($comm_id) && $start > 0 && $end > 0 && $end > $start ) {
		
			$prefix = NULL;
			if (isNotNull($p_part_01) && $p_part_01 > 0) {
				$prefix = $p_part_01.'_';
			}
			if (isNotNull($p_part_01) && isNotNull($p_part_02) && $p_part_01 > 0 && $p_part_02 > 0) {
				$prefix .= $p_part_02.'_';
			}
			
			if (isNotNull($prefix)) {
				$now = date('Y-m-d H:i:s');
				$k = $end - $start + 1;
				$j = 0;
				for($i=$start; $i<=$end; $i++) {

					$parking_id = $prefix.$i;
					$park_data = array('comm_id' => $comm_id
									,  'parking_id' => $parking_id 
									,  'status' => 1
									,  'created' => $now);

					$query = 'INSERT IGNORE INTO `parking` '
							.'       (`sn`, `comm_id`, `parking_id`, `status`, `created`) '
							.'VALUES (NULL, ?, ?, ?, ?)';

					$this->db->query($query, $park_data);
					$parking_sn = $this->db->insert_id();
					//dprint($parking_sn);
					if ( $parking_sn > 0 ) $j++;
				}

				if ($j > 0) {
				
					$this->showSuccessMessage('車位產出完成，應產出 '.$k.' 個車位編號，實際產出 '.$j.' 個車位編號');

				} else {
				
					$this->showFailMessage('查無此車位，請重新確認');
				}
			} else {
				
				$this->showFailMessage('車位產出失敗，請重新確認');
			}
			redirect(bUrl('index'));
		}


		$data['comm_id'] = $this->getCommId();

		$data['p_part_01'] = $p_part_01;
		$data['p_part_02'] = $p_part_02;
		$data['start'] = $start;
		$data['end'] = $end;

		// 車位別相關參數
		$data['parking_part_01'] = $this->parking_part_01;
		$data['parking_part_02'] = $this->parking_part_02;
		//$data['parking_part_03'] = $this->parking_part_03;
		$data['parking_part_01_array'] = $this->parking_part_01_array;
		$data['parking_part_02_array'] = $this->parking_part_02_array;


		$this->display("generate_view",$data);
	}



	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("generate", "result"));
	}



	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */