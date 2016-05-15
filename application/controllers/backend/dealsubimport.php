<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dealsubimport extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	



	public function abc()
	{

		## 將 bx_land_ownership 的 customer_sn 更新 - by Claire
		//$this->land_model->updateCustomerSnOfLandOwnershipNew();


		
		$city_code = 'h';

		$query = 'SELECT sn, owner_name, owner_id, owner_address '
				.'  FROM b'.$city_code.'_land_ownership '
				.' WHERE customer_sn IS NULL '
				.'   AND land_sn = 2862642 '
				;
		$result = $this->it_model->runSql($query);

		foreach($result['data'] as $item) {
			$ownership_sn = tryGetData('sn', $item);
			$owner_name = tryGetData('owner_name', $item, NULL);
			$owner_id = tryGetData('owner_id', $item, NULL);
			$owner_address = tryGetData('owner_address', $item, NULL);

			$cust_result = $this->person_model->getCustomerInfoAdv($owner_name, $owner_id, $owner_address);

			if ( $cust_result['count'] > 0 ) {
				dprint($cust_result);
				/*
				if () {
				
				} else {
				
				}*/

			} else {
				// 找步道此人
			}

			//dprint(" $owner_name , $owner_id , $owner_address   ===> ");
			//dprint($cust_info);//die;
			echo '<hr>';
		}


	}

	/**
	 * 
	 */
	public function chkDealLandBooks()
	{
		header("Content-Type:text/html; charset=utf-8");
		echo '***成交日期、契約書編號、單位';
		// 單位別
		$unit_name_array = array(4=>'台北',12=>'新北',11=>'新莊',5=>'悅陽',13=>'南崁',14=>'聯陽',15=>'桃園',28=>'大園',6=>'總管理處');

		$this->db->query( 'SET SESSION group_concat_max_len = 1000000' );

		$query = '
SELECT SQL_CALC_FOUND_ROWS d.deal_date, d.contract_no,  

GROUP_CONCAT(DISTINCT ds.unit_sn SEPARATOR "#@") as units_info, 

GROUP_CONCAT(DISTINCT CASE ds.agent_role WHEN "S" THEN "賣方-" ELSE "買方-" END,ds.user_name ORDER BY ds.agent_role SEPARATOR "<BR>") as sales_info, 

GROUP_CONCAT(DISTINCT CASE dc.customer_role WHEN "S" THEN "賣方-" ELSE "<b>買方</b>-" END,dc.customer_name ORDER BY dc.customer_role SEPARATOR "<BR>") as customer_info,
GROUP_CONCAT(DISTINCT ds.unit_deal_no SEPARATOR "<BR>") as deal_no_info
, d.sub_source 
, GROUP_CONCAT(DISTINCT dl.city_code, " ", dl.land_sn," ", dl.section," ", dl.land_no SEPARATOR "#@") as land_detail_info 
FROM deal d
LEFT JOIN deal_sales ds ON d.sn = ds.deal_sn
LEFT JOIN deal_lands dl ON d.sn = dl.deal_sn
LEFT JOIN deal_customers dc ON d.sn = dc.deal_sn
where contract_no not like "X000%" and ds.unit_deal_no is not null and ds.unit_sn = 13
GROUP BY d.sn
order by deal_date desc ';
		$result = $this->it_model->runSql($query);

		echo '<style type="text/css">
			* {font-family: "微軟正黑體";}
			td {text-align: center;}
			b {color: #f00}
		</style>';
		echo '<table border=1 cell-padding=6>';
		echo '<tr><th>成交日期</th><th>契約書編號</th><th>業務單位</th><th>單位成交編號</th><th style="width: 10%;">業務人員</th><th style="width: 10%;">客戶</th><th style="width: 40%;">地段號</th><th>土地筆數</th></tr>';
		foreach($result['data'] as $data) {

			$units_info = explode('#@', $data['units_info']);
			
			$unit_detail = '';
			foreach ($units_info as $unit) {
				$unit_detail .= $unit_name_array[$unit].'<br>';
			}

			$land_detail_info = explode('#@', $data['land_detail_info']);
			$sizeof_land_detail_info = sizeof($land_detail_info);
			
			$details = '';
			foreach ($land_detail_info as $k=>$item) {
				$land = explode(' ', $item);
				//dprint($land);
				$city_code = tryGetData(0, $land, null);
				$land_sn = tryGetData(1, $land, null);

				if ( isNotNull($city_code) && isNotNull($land_sn) && $land_sn > 0 ) {
					$tmp = $this->land_model->getLandLastestBook2($city_code, $land_sn);
					//dprint($tmp);
					if ( isNotNull(tryGetData('print_date', $tmp)) ) {
						$owner_array = $tmp['owner_customer_sn_array'];
//						$owner_list = implode(',', $owner_array);
						$owner_list = '';
						foreach ($owner_array as $v) {
							$t = explode(' ', $v);

							$owner_list .= $t[1].', ';
						}
						$owner_list = substr($owner_list, 0, -2);
						$print_date = showDateFormat($tmp['print_date'], 'Y年m月d日');
//						$print_date = $tmp['print_date'];
						///$diff = (strtotime($data['deal_date']) - strtotime($tmp['print_date']) );
						///if ( $diff > 0 ) //86400*7
						///{
							$print_date = '<span style="color:#c00"> '.$print_date.'</span> <Br><span style="color:#369; font-size:14px">　　(所有權部：'.$owner_list.' )</span>' ;
						///}
					} else {
						$print_date = '<span style="color:#c00">.........查無謄本</span>';
					}

					if (isNotNull(tryGetData('city_name', $tmp)) )
						$details .= '('.++$k.') '.$tmp['city_name'].$tmp['town_name'].''.$tmp['section_name'].' '.$tmp['land_no_main'].'-'.$tmp['land_no_sub'].' &raquo; '.$print_date.'<br>';
					else
						$details .= '('.++$k.') '.$land[2].' '. $land[3].' &raquo; '.$print_date.'<br>';
				} else {
					if ( !isset($land[2])  ) {
					//	dprint('@@@@@@@@@@@@');
					dprint($data['land_detail_info']);die;
					}
					if ( !isset($land[3])  ) {
					//	dprint('############');
					dprint($data['land_detail_info']);die;
					}
					$details .= '('.++$k.') '.$land[2].' '. $land[3].' &raquo; <span style="color:#c00">.........查無此地號</span><br>';
				}
			}
			
//die;


			echo sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td style="text-align: left;">%s</td><td>%d 筆</td></tr>'
						, showDateFormat($data['deal_date'], 'Y年m月d日')//.'<BR><BR>'.strtotime($data['deal_date']).' - '.strtotime($tmp['print_date']). ' = '. $diff
						, $data['contract_no']
						, $unit_detail
						, $data['deal_no_info']
						, $data['sales_info']
						, $data['customer_info']
						//, implode('<br>',$land_detail_info)
						, $details
						, $sizeof_land_detail_info
						);
		}
		echo '</table>';

		/*
Array
(
    [deal_date] => 2015-12-30
    [contract_no] => C00332
    [units_info] => 14
#5
    [sales_info] => 買方-趙賢彬
賣方-曾苡棠
    [customer_info] => 買方-莊永和
賣方-吳寶珠
    [deal_no_info] => 20042
10065
    [sub_source] => 10412成交列管資料-TO會計室(合約書編號OK).xlsx
    [land_detail_info] => 桃園市蘆竹區南崁下段 572-42#@桃園市蘆竹區南崁下段 572-11#@桃園市蘆竹區南崁下段 568-19#@桃園市蘆竹區南崁下段 572-58#@桃園市蘆竹區南崁下段 572-51
    [land_info] => H 1497221#@H 1497190#@H 1497135#@H 1497236#@H 1497230
)
		*/
	}

	/**
	 * Ａ７配地街廓一覽表數據
	 */
	public function listA7()
	{	
					
		$edit_data[] = array();

		$a7_list = $this->it_model->listData("a7_setting" );
		$org_map = array();
		foreach ($a7_list["data"] as $item) 
		{
//			dprint($item);
			$list[] = $item;
		}

		$data["list"] = $list;


		$this->display("list_a7_setting_view",$data);



	}

	/**
	 * 匯入Ａ７配地街廓一覽表數據
	 */
	public function importA7()
	{	
					
		$edit_data[] = array();
		$data["edit_data"] = $edit_data;
		$this->display("import_a7_view",$data);
	}
	
	

	/**
	 * 匯入Ａ７配地街廓一覽表數據
	 */
	public function updateA7Import()
	{
		set_time_limit(2000);//執行時間
		$edit_data = array();
											
		$config['upload_path'] = getcwd().'./upload/tmp/';
		$config['allowed_types'] = 'xlsx';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);
		
		
		
		if ( ! $this->upload->do_upload("xlsfile"))
		{
			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;				
			 
			
			$this->display("import_form_view",$data);
		}
		else
		{
			$file_info = $this->upload->data();
			
			//dprint($file_info);
			//exit;
			//echo $file_info["full_path"];
		
			$this->load->library('excel');
			
			
			
			//讀取excel資料
			//--------------------------------------------------------------------------------
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load(iconv("UTF-8", "big5",$file_info["full_path"]) );
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) 
			{
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();	
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();


				// $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				// 儲存格若為日期時間格式，須轉出日期
				$given_cell = $objPHPExcel->getActiveSheet()->getCell($cell);

				if (is_object($given_cell->getValue())) {
					$data_value= $given_cell->getValue()->getPlainText();

				} else {
					$data_value= $given_cell->getValue();

				}


				if (PHPExcel_Shared_Date::isDateTime($given_cell)) {
					$format = 'Y-m-d';
					$data_value = date($format, PHPExcel_Shared_Date::ExcelToPHP($data_value)); 
				}
				
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {			    	    	
			        $header[$row][$column] = $data_value;
			    } else {
			        $arr_data[$row][$column] = $data_value;
			    }
			}
			
			//send the data in an array format
			$xls_data['header'] = $header;
			$xls_data['values'] = $arr_data;
			
			//dprint($xls_data['values']);

			$mapping = array(
				'no' => '項次'
				, 'street_id' => '街廓編號'
				, 'street_total_m2' => '街廓總面積'
				, 'street_total_value' => '街廓總地價'
				, 'first_m2' => '第一宗　面積'
				, 'first_value' => '第一宗　評定地價'
				, 'first_right' => '第一宗　所需權利價值'
				, 'gen_m2' => '一般土地　總面積'
				, 'gen_value' => '一般土地　評定地價'
				, 'gen_total_right' => '一般土地　總地價'
				, 'gen_min_width' => '一般土地　最小分配寬度'
				, 'gen_min_m2' => '一般土地　最小分配面積'
				, 'gen_min_right' => '一般土地　最低所需權利價值'
				, 'gen_deep_m2' => '一般土地　街廓較深處　參考面積'
				, 'gen_deep_right' => '一般土地　街廓較深處　參考權值'
				, 'last_m2' => '最後宗土地　面積'
				, 'last_value' => '最後宗土地　評定地價'
				, 'last_right' => '最後宗土地　所需權利價值'
				, 'deep_01' => '深度　起'
				, 'deep_02' => '深度　末'
				, 'memo' => '備註'
				, 'flag' => '限制'
				);
			$tmp = array();
			$parsed_array = array();
			$i = 0;
			foreach ($xls_data['values'] as $key => $item) 
			{
				$i++;
				if ($i > 2) {
//			echo $key;	
//dprint($item);
if (isNull(tryGetData('C', $item, NULL)) ) continue;

				$tmp['no'] = tryGetData('A', $item);
				$tmp['street_id'] = tryGetData('C', $item);
				$tmp['street_total_m2'] = tryGetData('D', $item);
				$tmp['street_total_value'] = tryGetData('F', $item);
				$tmp['first_m2'] = tryGetData('G', $item);
				$tmp['first_value'] = tryGetData('H', $item);
				$tmp['first_right'] = tryGetData('I', $item);
				$tmp['gen_m2'] = tryGetData('J', $item); 
				$tmp['gen_value'] = tryGetData('L', $item);
				$tmp['gen_total_right'] = tryGetData('M', $item);
				$tmp['gen_min_width'] = tryGetData('P', $item);
				$tmp['gen_min_m2'] = tryGetData('R', $item);
				$tmp['gen_min_right'] = tryGetData('U', $item);
				$tmp['gen_deep_m2'] = tryGetData('V', $item); 
				$tmp['gen_deep_right'] = tryGetData('W', $item);
				$tmp['last_m2'] = tryGetData('X', $item); 
				$tmp['last_value'] = tryGetData('Y', $item);
				$tmp['last_right'] = tryGetData('Z', $item);
				$tmp['deep_01'] = tryGetData('AA', $item);
				$tmp['deep_02'] = tryGetData('AB', $item);
				$tmp['memo'] = tryGetData('AH', $item);
				$parsed_array[] = $tmp;
				}
				//$parsed_array['flag'] = tryGetData('B', $item);

				/*
				$no = tryGetData('A', $item);
				$street_id = tryGetData('C', $item);
				$street_total_m2 = tryGetData('D', $item);
				$street_total_value = tryGetData('F', $item);
				$first_m2 = tryGetData('G', $item);
				$first_value = tryGetData('H', $item);
				$first_right = tryGetData('I', $item);
				$gen_m2 = tryGetData('J', $item); 
				$gen_value = tryGetData('L', $item);
				$gen_total_right = tryGetData('M', $item);
				$gen_min_width = tryGetData('P', $item);
				$gen_min_m2 = tryGetData('R', $item);
				$gen_min_right = tryGetData('U', $item);
				$gen_deep_m2 = tryGetData('V', $item); 
				$gen_deep_right = tryGetData('W', $item);
				$last_m2 = tryGetData('X', $item); 
				$last_value = tryGetData('Y', $item);
				$last_right = tryGetData('Z', $item);
				$deep_01 = tryGetData('AA', $item);
				$deep_02 = tryGetData('AB', $item);
				$memo = tryGetData('AH', $item);
				$all_flag = tryGetData('B', $item);
				*/
			}

			$sql = array();
			$insert = '';
			foreach ($parsed_array as $v) 
			{
				$insert = 'Insert into a7_setting SET ';
				foreach ($v as $k => $vv) 
				{	
					if ($k == 'no')		echo '<p>';
					if (isset($mapping[$k])) 
						echo $mapping[$k].' = ';
					else
						echo $k.' ?= ';
					
					echo $vv .'('.gettype($vv). ')<br>';

					if (is_string($vv) && $k != 'memo' && $k != 'street_id' ) {
//						$vv = NULL;
						$insert .= $k.' = NULL, ';
					} else {
						if ($k == 'memo' || $k == 'street_id') {
							$insert .= $k.' = "'.$vv.'", ';
						} else {
							$insert .= $k.' = '.$vv.', ';
						}
					}
					//$vv = (float) $vv;
//					$insert .= $k.' = "'.$vv.'", ';
					//$k = ;
				}
				$insert = substr($insert, 0, -2);
				$insert .= ';';
				$sql[] = $insert;
			}
//dprint($sql);
				foreach ($sql as $query) 
				{
					echo $query.'<br>';
				}
			die;

			$error = array();
			$i = 0;
			$j = 0;
			foreach ($xls_data['values'] as $key => $item) 
			{
				$name = tryGetData('A', $item);
				$name = big5_for_utf8($name);
				$addr = tryGetData('B', $item);
				$addr = big5_for_utf8($addr);
				$sales = tryGetData('F', $item);
				$sales = big5_for_utf8($sales);
				$unit = tryGetData('D', $item);
				$unit = big5_for_utf8($unit);
				$deal_date = tryGetData('G', $item);
				
				// 先查詢業務序號
				$result = $this->person_model->getSalesList('name="'.$sales.'" and u.unit_name like "'.$unit.'%"');
				if ($result['count'] < 1 ) {

					$error['wrong_sales'][] = $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

				} else {

					$u_sn = $result['data'][0]['sn'];
					$u_id = $result['data'][0]['id'];
					
					$item['u_sn'] = $u_sn;
					$item['u_id'] = $u_id;

					// 查詢客戶序號
					$cust_sn = $this->person_model->getCustomerSnbyNamenAddr($name, $addr);

					if ($cust_sn == false) {

						$error['wrong_cust'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

					} else {
					
						$item['c_sn'] = $cust_sn;
/*
						$arr_data = array('user_sn' => tryGetData('u_sn', $item)
										, 'user_id' => tryGetData('u_id', $item)
										, 'tmp_user_name' => tryGetData('F', $item)
										, 'source' => 'uploaded'
										, 'customer_sn' => tryGetData('c_sn', $item)
										, 'customer_name' => tryGetData('A', $item)
										, 'tmp_address' => tryGetData('B', $item)
										, 'restricted' => 1
										, 'restrict_forever' => 1
										, 'status' => 1
										, 'created' => date('Y-m-d H:i:59')
										, 'updated' => date('Y-m-d H:i:59')
										, 'updated_user_id' => $this->session->userdata('user_id')
										);
*/
						$arr_data = array(tryGetData('u_sn', $item)
										, tryGetData('u_id', $item)
										, tryGetData('F', $item)
										, 'uploaded'
										, tryGetData('c_sn', $item)
										, tryGetData('A', $item)
										, tryGetData('B', $item)
										, 1
										, 1
										, 1
										, date('Y-m-d H:i:s')
										, date('Y-m-d H:i:s')
										, $this->session->userdata('user_id')
										, $this->session->userdata('user_name').'上傳列管名單 '.date('Y-m-d H:i:s')
										);
						$query = 'INSERT INTO `sales_customer` '
								.'       (`user_sn`, `user_id`, `tmp_user_name`, `source`, `customer_sn`, `customer_name`, `tmp_address` '
								.'        , `restricted`, `restrict_forever`, `status`, `created`, `updated`, `updated_user_id`, `memo`) '
								.'VALUES (?, ?, ?, ?, ?, ?, ? '
								.'        , ?, ?, ?, ?, ?, ?, ?) '
								.'    ON DUPLICATE KEY UPDATE  '
								.'       `source` = VALUES(`source`) '
								.'       , `customer_name`=VALUES(`customer_name`) '
								.'       , `restricted`=VALUES(`restricted`) '
								.'       , `restrict_forever` = VALUES(`restrict_forever`) '
								.'       , `updated` = VALUES(`updated`) '
								.'       , `updated_user_id` = VALUES(`updated_user_id`) '
								.'       , `memo` = VALUES(`memo`) '
								;


						$this->db->query($query, $arr_data);
						if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') {
							$j++;
							$error['ok'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';
						} else {
							//$error['wrong_db'] = ''
							$error['wrong_db'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.') <br>錯誤訊息：'.$this->db->_error_message().'<br> 語法； '.$this->db->last_query();
						}
					}
				}
				$i++;
			}
			


			$wrong_sales_count = count(tryGetData('wrong_sales', $error, array()));
			$wrong_cust_count = count(tryGetData('wrong_cust', $error, array()));
			$wrong_db_count = count(tryGetData('wrong_db', $error, array()));
			$error_count = $wrong_sales_count + $wrong_cust_count + $wrong_db_count;

			$message = "<p>檔案【".$file_info["file_name"] .'】'
			.'共 <strong>'.$i.'</strong> 組列管名單，'
			.'成功配對 <strong>'.$j.'</strong> 組，失敗 <strong>'.$error_count.'</strong> 組';

			/*
			$ok_count = count(tryGetData('ok', $error, array()));
			if ( $ok_count > 0 ) {
				$message .= '<p>成功配對的記錄如下：<br>';
				foreach ($error['ok'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}
			$message = '- - - - - - - - - - - - - - - - - - - - - - - - ';
			*/

			if ( $wrong_sales_count > 0 ) {
				$message .= '<p>因 查無業務 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_sales'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}
				
			if ( $wrong_cust_count > 0 ) {
				$message .= '<p>因 查無客戶 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_cust'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}

			if ( $wrong_db_count > 0 ) {
				$message .= '<p>因 未知原因 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_db'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}


			logData("列管名單上傳 - ".$file_info["file_name"], 1);
			

			$template = $this->config->item('template','mail');
			$content = '<p>'.$this->session->userdata('user_name').' 於 '.date('Y-m-d H:i:s').' 上傳列管名單檔案<Br>'
						.'處理狀況如下：</p>'
						.$message
						;
			
			$content = sprintf($template, $content);
			send_email($this->session->userdata('user_email'),'【竹北置地】列管名單上傳通知信函', $content);
			send_email('claire.huang@chupei.com.tw','【竹北置地】列管名單上傳通知信函', $content);

			if ($error_count > 0) {
				$message .= '<br><br>請確實檢視您上傳的檔案資料；若無法解決，請洽詢資訊室程式組人員，謝謝。';
			}


			//刪除暫存檔
			//unlink( iconv("UTF-8", "big5",$file_info["full_path"]) );
			
			//$this->session->set_flashdata('backend_message', $message);
			//redirect(bUrl("editContent"));	

			$data['message'] = $message;
			$this->display("import_a7_result_view", $data);
		}
	}
	





	/**
	 * category edit page
	 */
	public function importContent()
	{
		$content_sn = $this->input->get('sn');

		//echo big5_for_utf8('槺榔段下槺榔小段');

		$edit_data[] = array();
		$data["edit_data"] = $edit_data;
		$this->display("import_form_view",$data);
	}
	
	
	public function updateImport()
	{
		set_time_limit(2000);//執行時間

		$edit_data = array();
		
		$config['upload_path'] = getcwd().'./upload/deal/sub/';
		$config['allowed_types'] = 'xlsx';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);


		$parsed_result = array();

		// 檢查上傳的檔案是否為 xls
		if ( ! $this->upload->do_upload("xlsfile")) {

			$message = '檔案上傳失敗，請重新上傳，或請洽詢資訊室　（'.$this->upload->display_errors().'）';

			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;

		} else {

			$message = '主機忙碌中，請重新上傳...';

			$file_info = $this->upload->data();

			$this->load->library('excel');

			## 讀取excel資料 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load(iconv("UTF-8", "big5",$file_info["full_path"]) );
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			$arr_data = array();
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) 
			{
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();	
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();


				// $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				// 儲存格若為日期時間格式，須轉出日期
				$given_cell = $objPHPExcel->getActiveSheet()->getCell($cell);
				$data_value= $given_cell->getValue();

				if (PHPExcel_Shared_Date::isDateTime($given_cell)) {
					$format = 'Y-m-d';
					$data_value = date($format, PHPExcel_Shared_Date::ExcelToPHP($data_value)); 
				}
				
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {			    	    	
			        $header[$row][$column] = $data_value;
			    } else {
			    			    	
			        $arr_data[$row][$column] = $data_value;
			    }
			}

			if ( sizeof($arr_data) == 0 ) {

				header("Content-Type:text/html; charset=utf-8");
				dprint('您上傳的檔案內容有問題...　請將以下訊息提供給資訊室程式組，感謝');
				dprint($_FILES);
				dprint($file_info);
				die;
			} 
			//send the data in an array format
			$xls_data['header'] = $header;
			$xls_data['values'] = $arr_data;
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			

			$city_array = array('A' => '台北市', 'F' => '新北市', 'H' => '桃園市', 'O' => '新竹市', 'J' => '新竹縣');
			## 查詢行政區的地段
			$whole_section = $this->land_model->getWholeSection();

			$error = array();
			$i = 0;
			$j = 0;			

			$unit_sales = array();
			$error_section = array();
			$error_land = array();
			$error_contracts = array();
			$alert_contracts = array();
			$error_customer = array();
			$error_contract = array();
			$errorr_msg  = '';

			mb_internal_encoding("UTF-8");

			foreach ($xls_data['values'] as $key => $item) 
			{
				if (sizeof($item) < 8 ) {
					break;
				}
				$i++;
				$handle = true;

				$contract_no = tryGetData('A', $item, null);
				$unit_deal_no = tryGetData('C', $item, null);
				$deal_date = tryGetData('D', $item, null);
				$customer_name = tryGetData('I', $item, null);				// 客戶資料
				$customer_id = tryGetData('J', $item, null);
				$customer_birth = tryGetData('K', $item, null);
				$customer_phone = tryGetData('L', $item, null);
				$ori_customer_addr = tryGetData('M', $item, null);
				$memo = tryGetData('N', $item, null);
				$customer_sn = null;



				// $unit_sn = $this->person_model->getMainUnitSnbyName($unit_name);

				// 單位別
				$unit_name_array = array(4=>'台北',12=>'新北',11=>'新莊',5=>'悅陽',13=>'南崁',14=>'聯陽',15=>'桃園',28=>'大園',6=>'總管理處');
				$unit_name = trim(tryGetData('B', $item));
				if ( $unit_name == '上茂') $unit_name = '桃園';
				if ( $unit_name == '總處') $unit_name = '總管理處';

				if ( in_array($unit_name, $unit_name_array) ) {
					$unit_sn = array_search($unit_name, $unit_name_array);

				} else {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',［單位］欄位填寫的 "'.$unit_name.'" 不被接受，系統只接受 台北,新北,新莊,悅陽,南崁,聯陽,桃園,大園 或 總管理處，敬請確認';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}


				// 檢查若沒有［契約書編號］，必須要有［成交編號］ (for 桃園公司)
				if (isNull($contract_no) || $contract_no=='0' || mb_strlen($contract_no) < 4 ) {
					if (isNull($unit_deal_no) && $contract_no!='0' && $unit_sn != 15 ) {
						$errorr_msg .= '第'.$i.'列 查無［契約書編號］及［成交編號］';//: '.$contract_no.' &'.$unit_deal_no;
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						//var_dump($unit_deal_no);
						//dprint('查無［契約書編號］及［成交編號］'.$i.' : '.$contract_no.' &'.$unit_deal_no);dprint($item);die;
					    continue;
					} else {
						$contract_no = 'Z'.$unit_deal_no;
					}
				}

				// 契約書編號　從１＋３碼改為１＋５碼
				if (mb_strlen($contract_no) == 4 ) {
					$prefix = substr($contract_no,0,1);
					$tail = substr($contract_no,1,3);
					$tail = str_pad($tail, 5, "0", STR_PAD_LEFT);
					$contract_no = $prefix.$tail;
				}

				if ( strtotime($deal_date) > strtotime('today')) {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',請確認［成交日期 '.$deal_date.'］是否正確';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}

				if ( strtotime($deal_date) > strtotime('today')) {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',請確認［成交日期 '.$deal_date.'］是否正確';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}

				// 檢查其他單位是否有資料
				if ( isNull($unit_name) || isNull($unit_deal_no) || isNull($customer_name) || isNull($customer_id) || isNull($ori_customer_addr) ) {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',第'.$i.'列 請確認"單位別"、"單位成交編號"、"客戶姓名"、"客戶身份證號"、"客戶地址" 及 "業務人員"..等欄位資訊，是否確實填寫';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}


				// 交易類型   1=>'買賣', 2=>'租賃'
				$deal_type = NULL;
				$deal_type_array = array(1=>'買賣', 2=>'租賃');
				$deal_type = trim(tryGetData('E', $item, '買賣'));
				if ( in_array($deal_type, $deal_type_array) ) {
					$deal_type = array_search($deal_type, $deal_type_array);

				} else {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',［交易類型］欄位填寫的 "'.$deal_type.'" 不被接受，系統只接受 買賣 或 租賃，敬請確認';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}

				// 標的物類型    1=>'土地', 2=>'建物', 3=>'土地+建物'
				$target_type = NULL;
				$target_type_array = array(1=>'土地', 2=>'建物', 3=>'土地+建物');
				$target_type = trim(tryGetData('F', $item, '土地'));
				if ( in_array($target_type, $target_type_array) ) {
					$target_type = array_search($target_type, $target_type_array);

				} else {
					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',［標的物類型］欄位填寫的 "'.$target_type.'" 不被接受，系統只接受 土地、建物 或 土地+建物，敬請確認';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}

				// 此筆資料是屬於賣方 或 買方 或 名義登記人
				if (tryGetData('H', $item) == '買方') {
					$role = "B";
					$role_text = '買方';

				} elseif (tryGetData('H', $item) == '賣方') {
					$role = "S";
					$role_text = '賣方';

				} elseif (tryGetData('H', $item) == '名義登記人') {
					$role = "N";
					$role_text = '名義登記人';

				} else {

					$handle = false;
					$errorr_msg .= ''.$unit_deal_no.',［客戶類型］欄位填寫的 "'.tryGetData('H', $item).'" 不被接受，系統只接受以下３種身份：買方、賣方 或 名義登記人，敬請確認';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;
				}

				$customer_id = strtoupper(trim($customer_id));
				$customer_name = big5_for_utf8($customer_name);

				$ori_customer_addr = str_replace('F', '樓', $ori_customer_addr);
				$ori_customer_addr = str_replace(' ', '', $ori_customer_addr);
				$customer_addr = big5_for_utf8($ori_customer_addr);

				// 身分證號驗證
				if (strpos($customer_name,'公司')===false && strpos($customer_name,'祭祀公業')===false && person_id_check($customer_id) === false) {
					if (isNotNull($customer_name) && isNotNull($ori_customer_addr)) {
						$customer_sn = $this->person_model->getCustomerSnAdv($customer_name, null, $customer_addr);
						//echo "<p> $customer_name , null , $customer_addr ==>>>>>>>>> ".$customer_sn;
					}
					if (isNull($customer_sn) || $customer_sn === false) {
						$handle = false;
						$errorr_msg .= ''.$unit_deal_no.',客戶［'.$role_text.' '.$customer_name.'］身分證號［'.$customer_id.'］有誤，請確認是否填寫正確';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						continue;
					}
				} else {

					$customer_sn = $this->person_model->getCustomerSnAdv($customer_name, $customer_id, $customer_addr);
				}


				if ( isNull($customer_sn) || $customer_sn === false ) {

					if (mb_strlen($customer_id) == 8 || (mb_strlen($customer_id) ==10 && mb_strpos($customer_addr, '鄰') !== false 
						&&  ( mb_strpos($customer_addr, '村') !== false || mb_strpos($customer_addr, '里') !== false) ) || mb_substr($customer_addr, 0, 2) == '日本') {

					} else {
						$error_customer[] = $contract_no.' => ['.$customer_name.'-'.$customer_id.'-'.$customer_addr.']';
						$handle = false;
						//$error_addr[] = $contract_no;
						$errorr_msg .= ''.$unit_deal_no.',查無此位客戶［'.$role_text.' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］，請確認是否與身分證／謄本上登載的地址相符（地址需含有 "里、鄰" 或者 "村、鄰"）';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$customer_sn = null;
						continue;
					}
				}

				$section_land_text = tryGetData('G', $item);
				$section_land_text = trim($section_land_text);


				$target_info = array();
				$land_sn_list = array();
				if ($section_land_text =='違約') {
					/*
					$handle = false;
					$error_contract[] = $contract_no;
					$errorr_msg .= ''.$unit_deal_no.',違約';
					$error_contracts[$contract_no][] = $errorr_msg;
					$errorr_msg  = '';
					continue;						// 違約 ????? ask emma
					*/
				} else {

					$section_land_array = explode("\n", $section_land_text);
					foreach ($section_land_array as $section_land) {


						$city_name = mb_substr($section_land, 0,3);
						$city_code = array_search($city_name, $city_array);

						$pos = mb_strrpos($section_land, '段');
						$section = mb_substr($section_land, 0, $pos+1);
						$section = trim($section);
						$section = big5_for_utf8($section);

						$land_no = mb_substr($section_land, $pos+1);
						if ($section == '桃園市新屋區榔段下榔小段') {
							$section_sn = 6096;
						} else {
							$section_sn = array_search($section, $whole_section);
						}
						
						if ($section_sn === false) {
							$handle = false;
							$error_section[] = $contract_no.' => ['.$section_land.']';
							$errorr_msg .= ''.$unit_deal_no.',查無此地段［'.$section.'］';
							$error_contracts[$contract_no][] = $errorr_msg;
							$errorr_msg  = '';
							//continue;
						}

						// 查最近一份謄本的標示部跟所有權人
						$book_info = $this->land_model->getLandLastestBookBySectionLandNo($city_code, $section_sn, $land_no);

						if ($book_info !== false || isNotNull(tryGetData('print_date', $book_info, NULL)) ) {
							$land_sn = $book_info['land_sn'];
							$land_sn_list[] = $land_sn;
							$land_desc_sn = $book_info['land_desc_sn'];
							$current_owner_array = $book_info['owner_customer_sn_array'];
							$print_date = $book_info['print_date'];

							// 若此筆資料是買方，必須check 列印日期大於等於成交日期，並且客戶在所有權部~
							if ( sizeof($current_owner_array) > 0) {

								if (in_array($customer_sn , $current_owner_array) ) {

									$handle = true;
									$target_info[] = array(  'land_sn' => $land_sn
															,'customer_sn' => $customer_sn
															,'city_code' => $city_code
															,'section' => $section
															,'section_sn' => $section_sn
															,'land_no' => $land_no
															//,'section_land' => $section_land
															,'land_desc_sn' => $land_desc_sn
															,'print_date' => $print_date
															,'msg' => ''
															);
								
								} else {
									if ($customer_sn > 0) {
										///if (in_array($role, array('B', 'N'))) {
											$handle = true;
											$target_info[] = array(  'land_sn' => $land_sn
																	,'customer_sn' => $customer_sn
																	,'city_code' => $city_code
																	,'section' => $section
																	,'section_sn' => $section_sn
																	,'land_no' => $land_no
																//	,'section_land' => $section_land
																	,'land_desc_sn' => $land_desc_sn
																	,'print_date' => $print_date
																	,'msg' => ''.$unit_deal_no.',經查［'.$section_land.'］謄本現有的所有權人，並無此位客戶［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］'
																	);
											$alert_contracts[$contract_no][] = '［'.$section_land.'］謄本現有的所有權人，並無此位客戶［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］';
											$errorr_msg = '';
										///}

									} else {
									
										if (mb_strlen($customer_id) ==10 && (mb_strpos($customer_addr, '鄰') === false && (mb_strpos($customer_addr, '里') === false || mb_strpos($customer_addr, '村') === false)) ) {
											$target_info[] = array(  'land_sn' => $land_sn
																	,'customer_sn' => null
																	,'city_code' => $city_code
																	,'section' => $section
																	,'section_sn' => $section_sn
																	,'land_no' => $land_no
																//	,'section_land' => $section_land
																	,'land_desc_sn' => $land_desc_sn
																	,'print_date' => $print_date
																	,'msg' => '查無此客戶資訊［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］（地址需含有 "里、鄰" 或者 "村、鄰"）'
																	);
											$alert_contracts[$contract_no][] = '查無此客戶資訊［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］ 資料，請確認是否與身分證／謄本上登載的地址相符（地址需含有 "里、鄰" 或者 "村、鄰"）';
											$errorr_msg = '';
										} else {
											$handle = true;
										
											$target_info[] = array(  'land_sn' => $land_sn
																	,'customer_sn' => null
																	,'city_code' => $city_code
																	,'section' => $section
																	,'section_sn' => $section_sn
																	,'land_no' => $land_no
																//	,'section_land' => $section_land
																	,'land_desc_sn' => $land_desc_sn
																	,'print_date' => $print_date
																	,'msg' => ''.$unit_deal_no.',查無此客戶［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］ 資料'
																	);
											$alert_contracts[$contract_no][] = '查無此客戶資訊［'.tryGetData('H', $item).' '.$customer_name.' '.$customer_id.' '.$ori_customer_addr.'］';
											$errorr_msg = '';
										}
									}
								}

							} else {
							
								$handle = true;
								$target_info[] = array(  'land_sn' => $land_sn
														,'customer_sn' => $customer_sn
														,'city_code' => $city_code
														,'section' => $section
														,'section_sn' => $section_sn
														,'land_no' => $land_no
														//,'section_land' => $section_land
														,'land_desc_sn' => $land_desc_sn
														,'print_date' => $print_date
														,'msg' => ''.$unit_deal_no.',查無［'.$section_land.'］此筆土地謄本資料'
														);
								$alert_contracts[$contract_no][] = '查無此筆［'.$section_land.'］土地謄本資料';
								$errorr_msg = '';
							} 
						} else {
							$land_sn_list[] = $city_code.'!@'.$section_sn.'!@'.$land_no;


									$handle = true;
								$target_info[] = array(  'land_sn' => 0
														,'customer_sn' => $customer_sn
														,'city_code' => $city_code
														,'section' => $section
														,'section_sn' => $section_sn
														,'land_no' => $land_no
														//,'section_land' => $section_land
														,'land_desc_sn' => NULL
														,'print_date' => NULL
														,'msg' => ''.$unit_deal_no.',查無此筆土地地號［'.$section_land.'］'
														);

							//$handle = false;
							// 查無此筆土地地號資料
							$error_land[] = $contract_no.' => '.$section_land;
							$errorr_msg .= '查無此筆土地地號［'.$section_land.'］';
							$alert_contracts[$contract_no][] = $errorr_msg;
							$errorr_msg = '';
								//continue; 
								/*
								$target_info[] = array(  'land_sn' => 0
														,'customer_sn' => $customer_sn
														,'section_land' => $section_land
														,'land_desc_sn' => null
														,'print_date' => null
														,'msg' => '查無《'.$section_land.'》此筆土地地號資料!'
														);
								*/
						}
						// $book_info['deal_date'] = $deal_date;
						// dprint($book_info);
						/*
							## 查最近一份謄本的列印日期
							$land_desc_list = $this->it_model->listData( "b".$city_code."_land_desc" , "land_sn = ".$land_sn , NULL , NULL , array("print_date"=>"desc"));

							if ($land_desc_list["count"] > 0 ) {
								if ($land_desc_list["data"][0]["source"] == 'TK') {
									$print_date = '查無謄本(TK)';
								} else {
									$print_date = $land_desc_list["data"][0]["print_date"];
								}
								$area_m2 = number_format($land_desc_list["data"][0]["area_m2"], 2);
								$area_ping = $land_desc_list["data"][0]["area_ping"];
								$kind = $land_desc_list["data"][0]["kind"];

							} else {

								$print_date = '查無謄本';
							}
						*/
					}
				}
				/////////}





				## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				## 業務姓名、佣金比例、業務序號
				$unit_sn = $this->person_model->getMainUnitSnbyName($unit_name);
				$sales = array();
				$sales_error = false;

				$sales_01_name = trim(tryGetData('O', $item));
				$sales_01_name = big5_for_utf8($sales_01_name);
				//$sales_01_ratio = (float) tryGetData('P', $item, 0);
				//$sales_01_sn = $this->person_model->getUserSnbyUserName($sales_01_name);
				$sales_01_ratio = 0 ;
				$sales_01_sn = 0 ;
				if ( isNotNull($sales_01_name)) {
					$sales_01_ratio = (float) tryGetData('P', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_01_name);
					$sales_01_sn = tryGetData('sn', $sales_info, NULL);
					$sales_01_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_01_sn) || $sales_01_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務1［'.$sales_01_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_01_sn
										, 'user_id' => $sales_01_id
										, 'user_name'	=>  $sales_01_name
										, 'ratio'	=> $sales_01_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_02_name = trim(tryGetData('Q', $item, null));
				$sales_02_name = big5_for_utf8($sales_02_name);
				$sales_02_ratio = 0 ;
				$sales_02_sn = 0 ;
				if ( isNotNull($sales_02_name)) {
					$sales_02_ratio = (float) tryGetData('R', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_02_name);
					$sales_02_sn = tryGetData('sn', $sales_info, NULL);
					$sales_02_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_02_sn) || $sales_02_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務2［'.$sales_02_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_02_sn
										, 'user_id' => $sales_02_id
										, 'user_name'	=>  $sales_02_name
										, 'ratio'	=> $sales_02_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_03_name = trim(tryGetData('S', $item, null));
				$sales_03_name = big5_for_utf8($sales_03_name);
				$sales_03_ratio = 0 ;
					$sales_03_sn = 0 ;
				if ( isNotNull($sales_03_name)) {
					$sales_03_ratio = (float) tryGetData('T', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_03_name);
					$sales_03_sn = tryGetData('sn', $sales_info, NULL);
					$sales_03_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_03_sn) || $sales_03_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務3［'.$sales_03_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_03_sn
										, 'user_id' => $sales_03_id
										, 'user_name'	=>  $sales_03_name
										, 'ratio'	=> $sales_03_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_04_name = trim(tryGetData('U', $item, null));
				$sales_04_name = big5_for_utf8($sales_04_name);
				$sales_04_ratio = 0 ;
				$sales_04_sn = 0 ;
				if ( isNotNull($sales_04_name)) {
					$sales_04_ratio = (float) tryGetData('V', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_04_name);
					$sales_04_sn = tryGetData('sn', $sales_info, NULL);
					$sales_04_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_04_sn) || $sales_04_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務4［'.$sales_04_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_04_sn
										, 'user_id' => $sales_04_id
										, 'user_name'	=>  $sales_04_name
										, 'ratio'	=> $sales_04_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_05_name = trim(tryGetData('W', $item, null));
				$sales_05_name = big5_for_utf8($sales_05_name);
				$sales_05_ratio = 0 ;
				$sales_05_sn = 0 ;
				if ( isNotNull($sales_05_name)) {
					$sales_05_ratio = (float) tryGetData('X', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_05_name);
					$sales_05_sn = tryGetData('sn', $sales_info, NULL);
					$sales_05_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_05_sn) || $sales_05_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務5［'.$sales_05_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_05_sn
										, 'user_id' => $sales_05_id
										, 'user_name'	=>  $sales_05_name
										, 'ratio'	=> $sales_05_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_06_name = trim(tryGetData('Y', $item, null));
				$sales_06_name = big5_for_utf8($sales_06_name);
				$sales_06_ratio = 0 ;
				$sales_06_sn = 0 ;
				if ( isNotNull($sales_06_name)) {
					$sales_06_ratio = (float) tryGetData('Z', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_06_name);
					$sales_06_sn = tryGetData('sn', $sales_info, NULL);
					$sales_06_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_06_sn) || $sales_06_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務6［'.$sales_06_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_06_sn
										, 'user_id' => $sales_06_id
										, 'user_name'	=>  $sales_06_name
										, 'ratio'	=> $sales_06_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}


				$sales_07_name = trim(tryGetData('AA', $item, null));
				$sales_07_name = big5_for_utf8($sales_07_name);
				$sales_07_ratio = 0 ;
				$sales_07_sn = 0 ;
				if ( isNotNull($sales_07_name)) {
					$sales_07_ratio = (float) tryGetData('AB', $item, 0);
					$sales_info = $this->person_model->getUserInfobyUserName($sales_07_name);
					$sales_07_sn = tryGetData('sn', $sales_info, NULL);
					$sales_07_id = tryGetData('id', $sales_info, NULL);
					if ( isNull($sales_07_sn) || $sales_07_sn === false) {
						$errorr_msg .= ''.$unit_deal_no.',經查並無業務7［'.$sales_07_name.'］資訊';
						$error_contracts[$contract_no][] = $errorr_msg;
						$errorr_msg  = '';
						$sales_error = true;
						//continue;
					} else {
						
						$unit_sales[$contract_no][$role][] = array( 'agent_role' => $role
										, 'unit_sn' => $unit_sn
										, 'unit_name' => $unit_name
										, 'unit_deal_no' => $unit_deal_no
										, 'user_sn' => $sales_07_sn
										, 'user_id' => $sales_07_id
										, 'user_name'	=>  $sales_07_name
										, 'ratio'	=> $sales_07_ratio
										/////, 'target_info'	=> $target_info
										);
					}
				}



				if ($sales_error == true) continue;
				//   $parsed_result[$contract_no]['unit_sales'][$role][] = $sales;
				//$unit_sales[$contract_no][$role][] = $sales;
				## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -



				if ($handle == true) {
//echo '<br>'.$contract_no.' -> '.$customer_sn.' -> '.$customer_name.' -> '.$role;
					## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					## 主表
					$parsed_result[$contract_no]['contract_no'] = $contract_no;
					$parsed_result[$contract_no]['unit_deal_no_array'][] = $unit_deal_no;
					$parsed_result[$contract_no]['deal_type'] = $deal_type;					// 交易類型   1=>'買賣', 2=>'租賃'
					$parsed_result[$contract_no]['target_type'] = $target_type;				// 標的物類型    1=>'土地', 2=>'建物', 3=>'土地+建物'
					$parsed_result[$contract_no]['deal_date'] = $deal_date;
					$parsed_result[$contract_no]['source'] = $file_info['file_name'];
					$parsed_result[$contract_no]['created_user_id'] = $this->session->userdata('user_id');

					## 副表１ - 土地
					$parsed_result[$contract_no]['deal_lands'] = $target_info;
					natsort($land_sn_list);
					## 副表２ - 客戶
//					if (isNull($customer_sn)) $customer_sn = rand();
					$parsed_result[$contract_no]['deal_customers'][$role][$customer_id] = array( 'customer_sn' => $customer_sn
																			, 'customer_name' => $customer_name
																			, 'customer_id' => $customer_id
																			, 'customer_birth' => $customer_birth
																			, 'customer_phone' => $customer_phone
																			, 'customer_addr' => $customer_addr
																			, 'memo' => $memo
																			, 'land_sn_list' => $land_sn_list
																			);

					## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					
				} else {
					//$error_contracts[$contract_no][] = $errorr_msg;
					//$errorr_msg  = '';
				}
				//$i++;

			}

			/*
			echo '<p>●以下找不到地號: <br>';
			dprint(array_unique($error_land));
			echo '<p>●以下找不到土地段別: <br>';
			dprint(array_unique($error_section));
			echo '<p>●以下為違約: <br>';
			dprint(array_unique($error_contract));
			echo '<p>●以下為找不到客戶: <br>';
			dprint(array_unique($error_customer));
			*/

			## check 買方與賣方的交易標的是否相符合  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$msg = array();
			foreach ($parsed_result as $cno=>$contract) {
				$last_land_array = array();
				$current_land_array = array();

				foreach ($contract['deal_customers'] as $role=>$custs) {
					$ok = true;

					foreach ($custs as $cust) {
						sort($cust['land_sn_list']);
						if ( sizeof($last_land_array) == 0) {
							$last_land_array = $cust['land_sn_list'];
							continue;
						} else {
							$current_land_array = $cust['land_sn_list'];

							if ( array_diff($current_land_array, $last_land_array) != array_diff($last_land_array, $current_land_array) ) {
								$msg[] = $cno.'! ' . implode('_',$current_land_array) . ' != '.implode('_',$last_land_array) ;

								$error_contracts[$cno][] = '買方與賣方的交易標的不符，請確認交易標的是否確實輸入';
								$ok = false;
							}
						}
					}
				}
			}
			//echo '<p>●以下契約編號，買方與賣方的交易標的不符';
			//dprint($msg);
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


			//echo '<h3 style="color:#c00">無法處理之契約編號及原因如下: </h3>';
			foreach($error_contracts as $cont_no=>$reasons){
				//echo '<p style="color:#c00; font-size:12px">'.$cont_no.'<br>';
				unset($parsed_result[$cont_no]);
				//$unique_reasons = array_unique($reasons);
				//foreach ($unique_reasons as $reason){
				//	echo $reason.'<br>';
				//}
			}

			## 將 parsed 過程中須注意的事項一併記錄於主表 (最後會存於 deal.system_alert 欄位)
			/////echo '<h3 style="color:#369">須注意之契約編號及原因如下: </h3>';
			foreach($alert_contracts as $cont_no=>$reasons){
				/////echo '<p style="color:#369; font-size:12px">'.$cont_no.'<br>';
				//unset($parsed_result[$cont_no]);
				$unique_reasons = array_unique($reasons);
				foreach ($unique_reasons as $reason){
					/////echo $reason.'<br>';
					if (isNotNull(tryGetData($cont_no, $parsed_result, NULL)) ) {
						$parsed_result[$cont_no]['system_alert'][] = $reason;
					}
				}
			}


			## 副表３ - 業務 - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			foreach($unit_sales as $cont_no=>$role_sales){
//if ( sizeof($role_sales) > 0) $role_unique_sales = array_unique($role_sales);

				foreach ($role_sales as $role=>$sales){
					//echo $sales.'<br>';
if ( sizeof($sales) > 0) $unique_sales = array_unique($sales, SORT_REGULAR );
					if (isNotNull(tryGetData($cont_no, $parsed_result, NULL)) ) {
						$parsed_result[$cont_no]['deal_sales'][$role] = $unique_sales;
					}
				}
			}
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


	//dprint($parsed_result['C295']['deal_customers']['B']);
			/*********** TO DB  ********************************************************/
			$restrict_array = array();
			$succeed_to_db = array();
			$faild_to_db = array();
			$dup_contract = array();

			if ( sizeof($parsed_result) > 0) {
				$this->load->model('deal_model');
	//dprint($parsed_result);die;
				## 將成交客資附表存入資料庫  - - - - - - - - - - - - - - - - - - - - - - - 
				foreach ($parsed_result as $c_no=>$deal) {
					// 先判斷契約書編號是否已存在 deal 主表
					$exists = $this->deal_model->contractNoExists($c_no);
					$deal_sn = null;
					if ($exists === true) {
						// 若契約書編號已存在，原則上是代表成交總表先上傳，成交客資尚未上傳，因此直接取得主表的序號
						$deal_sn = $this->deal_model->getDealSnbyContractNoForInsertSubDeal($c_no);
					}
					
					$unit_deal_no_array = array_unique($deal['unit_deal_no_array']);
					$unit_deal_no_list = implode(', ', $unit_deal_no_array);
					if ($exists === false || $deal_sn > 0  ) {
						$result = $this->deal_model->insertSubDealviaUploadDealCustomers($deal, $deal_sn);
						if ($result === false) {
							$faild_to_db[$c_no][] = $unit_deal_no_list;
						} else {
							$succeed_to_db[$c_no][] = $unit_deal_no_list;
						}
					} else {

						$dup_contract[$c_no][] = $unit_deal_no_list;
					}
				}

				// 將成交客資資料存入DB時，依據比對到的 customer_sn，將客戶電話更新到 customer table
				$update_phone =  ' UPDATE `deal_customers` dc , `customer` c '
								.'    SET c.phone = LEFT(dc.customer_phone,15) '
								.'  WHERE dc.customer_sn = c.sn '
								;
				$this->db->query($update_phone);

				## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

				## 針對有成功入庫的成交客資記錄進行業務與客戶的列管
				foreach ($parsed_result as $c_no=>$deal) {

					if ( array_key_exists($c_no, $succeed_to_db)) {
						
						## 買方  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
						$buyer_restrict = 0;
						$buyer_sales = tryGetData('B', $deal['deal_sales'], NULL);
						$buyer_customer = tryGetData('B', $deal['deal_customers'], NULL);
						if ( isNotNull($buyer_sales) && isNotNull($buyer_customer)) {
							
							foreach ($buyer_customer as $customer) {
								foreach ($buyer_sales as $sales) {

								$arr_data = array(tryGetData('user_sn', $sales)
												, tryGetData('user_id', $sales)
												, tryGetData('user_name', $sales)
												, '成交客資附表'.$file_info['file_name']
												, tryGetData('customer_sn', $customer)
												, tryGetData('customer_name', $customer)
												, tryGetData('customer_addr', $customer)
												, 1
												, 1
												, 1
												, date('Y-m-d H:i:s')
												, date('Y-m-d H:i:s')
												, $this->session->userdata('user_id')
												, '成交客資附表'.$file_info['file_name']
												);


									$buyer_restrict_result = $this->person_model->setRestrict($arr_data);
									if ($buyer_restrict_result) {
										$buyer_restrict++;
										//$key = tryGetData('user_sn', $sales).'_'.tryGetData('customer_sn', $customer);
										$user_sn = tryGetData('user_sn', $sales);
										$user_name = tryGetData('user_name', $sales);
										$customer_sn = tryGetData('customer_sn', $customer);
										$key = $user_sn.'!@'.$user_name;
										$restrict_array[$key][$customer_sn] = tryGetData('customer_name', $customer);	//'［'.tryGetData('user_name', $sales).'］成功列管［'.tryGetData('customer_name', $customer).'］';
									}
										

								}
							}

						}
						## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


						## 賣方  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
						$seller_restrict = 0;
						$seller_sales = tryGetData('S', $deal['deal_sales'], NULL);
						$seller_customer = tryGetData('S', $deal['deal_customers'], NULL);
						if ( isNotNull($seller_sales) && isNotNull($seller_customer)) {
							/*
							if ($c_no == 'C310') {
								echo '<hr>((賣方))';
								dprint( $seller_sales );
								dprint( $seller_customer);
							}*/
							foreach ($seller_customer as $customer) {
								foreach ($seller_sales as $sales) {

								$arr_data = array(tryGetData('user_sn', $sales)
												, tryGetData('user_id', $sales)
												, tryGetData('user_name', $sales)
												, '成交客資附表'.$file_info['file_name']
												, tryGetData('customer_sn', $customer)
												, tryGetData('customer_name', $customer)
												, tryGetData('customer_addr', $customer)
												, 1
												, 1
												, 1
												, date('Y-m-d H:i:s')
												, date('Y-m-d H:i:s')
												, $this->session->userdata('user_id')
												, '成交客資附表'.$file_info['file_name']
												);

									$seller_restrict_result = $this->person_model->setRestrict($arr_data);
									if ($seller_restrict_result) {
										$seller_restrict++;
										//$key = tryGetData('user_sn', $sales).'_'.tryGetData('customer_sn', $customer);
										$user_sn = tryGetData('user_sn', $sales);
										$user_name = tryGetData('user_name', $sales);
										$customer_sn = tryGetData('customer_sn', $customer);
										$key = $user_sn.'!@'.$user_name;
										$restrict_array[$key][$customer_sn] = tryGetData('customer_name', $customer);	//'［'.tryGetData('user_name', $sales).'］成功列管［'.tryGetData('customer_name', $customer).'］';
									}
								}
							}

						}
						## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


						## 名義登記人  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
						$register_restrict = 0;
						$register_sales = tryGetData('N', $deal['deal_sales'], NULL);
						$register_customer = tryGetData('N', $deal['deal_customers'], NULL);
						if ( isNotNull($register_sales) && isNotNull($register_customer)) {

							foreach ($register_customer as $customer) {
								foreach ($register_sales as $sales) {

								$arr_data = array(tryGetData('user_sn', $sales)
												, tryGetData('user_id', $sales)
												, tryGetData('user_name', $sales)
												, '成交客資附表'.$file_info['file_name']
												, tryGetData('customer_sn', $customer)
												, tryGetData('customer_name', $customer)
												, tryGetData('customer_addr', $customer)
												, 1
												, 1
												, 1
												, date('Y-m-d H:i:s')
												, date('Y-m-d H:i:s')
												, $this->session->userdata('user_id')
												, '成交客資附表'.$file_info['file_name']
												);


									$register_restrict_result = $this->person_model->setRestrict($arr_data);
									if ($register_restrict_result) {
										$register_restrict++;
										//$key = tryGetData('user_sn', $sales).'_'.tryGetData('customer_sn', $customer);
										$user_sn = tryGetData('user_sn', $sales);
										$user_name = tryGetData('user_name', $sales);
										$customer_sn = tryGetData('customer_sn', $customer);
										$key = $user_sn.'!@'.$user_name;
										$restrict_array[$key][$customer_sn] = tryGetData('customer_name', $customer);
									}
								}
							}
						}
						## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
					}
				}
			}

			## 將列管結果寄發站內訊息給業務人員  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$restrict_memo = NULL;
			foreach ($restrict_array as $key=>$customers) {
				list($user_sn, $user_name) = explode('!@', $key);
				if (is_array($customers)) {
					$customer_list = implode('、', $customers);

					$msg = $user_name.' 您好，<br>依據成交記錄，已成功為您列管以下客戶：<br>'.$customer_list;
					$restrict_memo .= $user_name.' 成功列管客戶：'.$customer_list.'<br>';
																						sendMsg('竹北置地『成交客戶列管』通知', $msg, array(25));
				}
			}
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


			/*******************************************************************/

			//地目對應表
			//$kind_map = $this->land_model->getLandKindMap();//取得地目代碼

			## 將處理結果匯集起來  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$message = '<p>成交客資附表【<span style="color:#c00; font-weight:bold;">'.$file_info['file_name'].'</span>】處理結果如下：</p>';

			if ( sizeof($succeed_to_db) > 0 ) {
				$message .= '<p>● 成功入庫的契約書編號為：</p>';
				$message .= '<div style="font-weight:bold; color: #c00">';//.implode('、', $succeed_to_db).'</span></p>';
				foreach ( $succeed_to_db as $cno=>$deal) {
					$message .= $cno.' <span style="color:#6a3500; font-size:12px; font-weight:normal;">成交編號：';
					foreach ( $deal as $dno) {
						$message .= $dno.',';
					}
					$message = mb_substr($message, 0, -1);
					$message .= ' </span><br>';
				}
				$message .= '</div>';
				$message .= '<hr>';
			}

			if ( sizeof($dup_contract) > 0 ) {
				$message .= '<p>● 資料庫已存在相同契約書編號的成交客資，因此不予入庫的：</p>';
				$message .= '<div style="font-weight:bold; color: #c00">';//.implode('、', $dup_contract).'</span></p>';
				foreach ( $dup_contract as $cno=>$deal) {
					$message .= $cno.' <span style="color:#6a3500; font-size:12px; font-weight:normal;"> 成交編號：';
					foreach ( $deal as $dno) {
						$message .= $dno.',';
					}
					$message = mb_substr($message, 0, -1);
					$message .= ' </span><br>';
				}
				$message .= '</div>';
				$message .= '<hr>';
			}

			if ( isNotNull($restrict_memo)) {
				$message .= '<p>● 列管記錄： </p><div style="font-size:12px; color: #6a3500">'.$restrict_memo.'</div>';
				$message .= '<hr>';
			}

			if ( sizeof($error_contracts) > 0) {
				$message .= '<p>● 無法處理之契約編號及原因如下：</p>';
				$message .=  '<div class="normal_coffee">';
				foreach($error_contracts as $cont_no=>$reasons){
					//$message .=  '<p>'.$cont_no.',';
					$unique_reasons = array_unique($reasons);
					foreach ($unique_reasons as $reason){
						$message .=  '<span class="blod_red">'.$cont_no.'</span>,'.$reason.'<br>';
					}
					$message .=  '</p>';
				}
				$message .=  '</div>';
				//$message .= '<hr>';
			}
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

			
			## 寄送Email通知程式組   - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			$content = '<p>上傳人員：'.$this->session->userdata('unit_name').' '.$this->session->userdata('user_name').'</p>';
			$content .= '<p>上傳時間：'.date('Y-m-d H:i:s').'</p>';
			$content .= '<div>'.$message.'</div>';
			send_email('claire.huang@chupei.com.tw','【竹北置地】成交客資表上傳通知 '.date('Y年m月d日H點i分'), $content);
			## - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		}

		$data['message'] = $message;


		//刪除暫存檔
		//unlink( iconv("UTF-8", "big5",$file_info["full_path"]) );
				
		$this->display("import_result_view", $data);
	}
	
	
	public function updateImport_________Ori()
	{
		set_time_limit(2000);//執行時間
		$edit_data = array();
											
		$config['upload_path'] = getcwd().'./upload/deal_restricted/';
		$config['allowed_types'] = 'xlsx';
        $config['max_size'] = '100000';

		$this->load->library('upload',$config);
		
		
		
		if ( ! $this->upload->do_upload("xlsfile"))
		{
			$edit_data["error"] = $this->upload->display_errors();
			$data["edit_data"] = $edit_data;				
			 
			
			$this->display("import_form_view",$data);
		}
		else
		{
			$file_info = $this->upload->data();
			
			//dprint($file_info);
			//exit;
			//echo $file_info["full_path"];
		
			$this->load->library('excel');
			
			
			
			//讀取excel資料
			//--------------------------------------------------------------------------------
			//read file from path
			$objPHPExcel = PHPExcel_IOFactory::load(iconv("UTF-8", "big5",$file_info["full_path"]) );
			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) 
			{
			    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();	
			    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();


				// $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				// 儲存格若為日期時間格式，須轉出日期
				$given_cell = $objPHPExcel->getActiveSheet()->getCell($cell);
				$data_value= $given_cell->getValue();

				if (PHPExcel_Shared_Date::isDateTime($given_cell)) {
					$format = 'Y-m-d';
					$data_value = date($format, PHPExcel_Shared_Date::ExcelToPHP($data_value)); 
				}
				
			    //header will/should be in row 1 only. of course this can be modified to suit your need.
			    if ($row == 1) {			    	    	
			        $header[$row][$column] = $data_value;
			    } else {
			    			    	
			        $arr_data[$row][$column] = $data_value;
			    }
			}
			
			//send the data in an array format
			$xls_data['header'] = $header;
			$xls_data['values'] = $arr_data;
			

			$error = array();
			$i = 0;
			$j = 0;

$a = $this->land_model->getRegionNamebyLandText('桃園市龜山區牛角坡段水尾小段22');
echo '#'.$a;

header("Content-Type:text/html; charset=utf-8");
				dprint($xls_data['values']);
				die;
/*
A契約書編號	B單位	C成交編號	D成交日期	E地號	F客戶類型	G姓名	H身份證字號	I生日	J電話	K戶籍地址	
L業務1	M業務1業績比例	N業務2	O業務2業績比例	P業務3	Q業務3業績比例	R業務4	S業務4業績比例	T業務5	U業務5業績比例
*/
			foreach ($xls_data['values'] as $key => $item) 
			{
				$name = tryGetData('A', $item);
				$name = big5_for_utf8($name);
				$addr = tryGetData('B', $item);
				$addr = big5_for_utf8($addr);
				$sales = tryGetData('F', $item);
				$sales = big5_for_utf8($sales);
				$unit = tryGetData('D', $item);
				$unit = big5_for_utf8($unit);
				$deal_date = tryGetData('G', $item);

				$name = tryGetData('A', $item);
				$name = big5_for_utf8($name);
				$addr = tryGetData('B', $item);
				$addr = big5_for_utf8($addr);
				$sales = tryGetData('F', $item);
				$sales = big5_for_utf8($sales);
				$unit = tryGetData('D', $item);
				$unit = big5_for_utf8($unit);
				$deal_date = tryGetData('G', $item);
				
				// 先查詢業務序號
				$result = $this->person_model->getSalesList('name="'.$sales.'" and u.unit_name like "'.$unit.'%"');
				if ($result['count'] < 1 ) {

					$error['wrong_sales'][] = $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

				} else {

					$u_sn = $result['data'][0]['sn'];
					$u_id = $result['data'][0]['id'];
					
					$item['u_sn'] = $u_sn;
					$item['u_id'] = $u_id;

					// 查詢客戶序號
					$cust_sn = $this->person_model->getCustomerSnbyNamenAddr($name, $addr);

					if ($cust_sn == false) {

						$error['wrong_cust'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';

					} else {
					
						$item['c_sn'] = $cust_sn;
/*
						$arr_data = array('user_sn' => tryGetData('u_sn', $item)
										, 'user_id' => tryGetData('u_id', $item)
										, 'tmp_user_name' => tryGetData('F', $item)
										, 'source' => 'uploaded'
										, 'customer_sn' => tryGetData('c_sn', $item)
										, 'customer_name' => tryGetData('A', $item)
										, 'tmp_address' => tryGetData('B', $item)
										, 'restricted' => 1
										, 'restrict_forever' => 1
										, 'status' => 1
										, 'created' => date('Y-m-d H:i:59')
										, 'updated' => date('Y-m-d H:i:59')
										, 'updated_user_id' => $this->session->userdata('user_id')
										);
*/
						$arr_data = array(tryGetData('u_sn', $item)
										, tryGetData('u_id', $item)
										, tryGetData('F', $item)
										, 'uploaded'
										, tryGetData('c_sn', $item)
										, tryGetData('A', $item)
										, tryGetData('B', $item)
										, 1
										, 1
										, 1
										, date('Y-m-d H:i:s')
										, date('Y-m-d H:i:s')
										, $this->session->userdata('user_id')
										, $this->session->userdata('user_name').'上傳列管名單 '.date('Y-m-d H:i:s')
										);
						$query = 'INSERT INTO `sales_customer` '
								.'       (`user_sn`, `user_id`, `tmp_user_name`, `source`, `customer_sn`, `customer_name`, `tmp_address` '
								.'        , `restricted`, `restrict_forever`, `status`, `created`, `updated`, `updated_user_id`, `memo`) '
								.'VALUES (?, ?, ?, ?, ?, ?, ? '
								.'        , ?, ?, ?, ?, ?, ?, ?) '
								.'    ON DUPLICATE KEY UPDATE  '
								.'       `source` = VALUES(`source`) '
								.'       , `customer_name`=VALUES(`customer_name`) '
								.'       , `restricted`=VALUES(`restricted`) '
								.'       , `restrict_forever` = VALUES(`restrict_forever`) '
								.'       , `updated` = VALUES(`updated`) '
								.'       , `updated_user_id` = VALUES(`updated_user_id`) '
								.'       , `memo` = VALUES(`memo`) '
								;


						$this->db->query($query, $arr_data);
						if ( $this->db->affected_rows() > 0 or $this->db->_error_message() == '') {
							$j++;
							$error['ok'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.')';
						} else {
							//$error['wrong_db'] = ''
							$error['wrong_db'][] =  $unit .' '.$sales.'  => '.$name .' (地址：'.$addr.') <br>錯誤訊息：'.$this->db->_error_message().'<br> 語法； '.$this->db->last_query();
						}
					}
				}
				$i++;
			}
			


			$wrong_sales_count = count(tryGetData('wrong_sales', $error, array()));
			$wrong_cust_count = count(tryGetData('wrong_cust', $error, array()));
			$wrong_db_count = count(tryGetData('wrong_db', $error, array()));
			$error_count = $wrong_sales_count + $wrong_cust_count + $wrong_db_count;

			$message = "<p>檔案【".$file_info["file_name"] .'】'
			.'共 <strong>'.$i.'</strong> 組列管名單，'
			.'成功配對 <strong>'.$j.'</strong> 組，失敗 <strong>'.$error_count.'</strong> 組';

			/*
			$ok_count = count(tryGetData('ok', $error, array()));
			if ( $ok_count > 0 ) {
				$message .= '<p>成功配對的記錄如下：<br>';
				foreach ($error['ok'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}
			$message = '- - - - - - - - - - - - - - - - - - - - - - - - ';
			*/

			if ( $wrong_sales_count > 0 ) {
				$message .= '<p>因 查無業務 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_sales'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}
				
			if ( $wrong_cust_count > 0 ) {
				$message .= '<p>因 查無客戶 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_cust'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}

			if ( $wrong_db_count > 0 ) {
				$message .= '<p>因 未知原因 而無法設定列管的記錄如下：<br>';
				foreach ($error['wrong_db'] as $perline){
					$message .= '　'.$perline.'<br>';
				}
			}


			logData("列管名單上傳 - ".$file_info["file_name"], 1);
			

			$template = $this->config->item('template','mail');
			$content = '<p>'.$this->session->userdata('user_name').' 於 '.date('Y-m-d H:i:s').' 上傳列管名單檔案<Br>'
						.'處理狀況如下：</p>'
						.$message
						;
			
			$content = sprintf($template, $content);
			send_email($this->session->userdata('user_email'),'【竹北置地】列管名單上傳通知信函', $content);
			send_email('claire.huang@chupei.com.tw','【竹北置地】列管名單上傳通知信函', $content);
			//send_email('it@chupei.com.tw','【竹北置地】列管名單上傳通知信函', $content);

			if ($error_count > 0) {
				$message .= '<br><br>請確實檢視您上傳的檔案資料；若無法解決，請洽詢資訊室程式組人員，謝謝。';
			}


			//刪除暫存檔
			//unlink( iconv("UTF-8", "big5",$file_info["full_path"]) );
			
			//$this->session->set_flashdata('backend_message', $message);
			//redirect(bUrl("editContent"));	

			$data['message'] = $message;
			$this->display("import_result_view", $data);
		}
	}

	public function GenerateTopMenu()
	{		
		$this->addTopMenu(array("importContent","updateImport"));
	}






	public function ccc()
	{
		//地目對應表
		//*****************************************************************
		$kind_map = $this->land_model->getLandKindMap();//取得地目代碼
		//*****************************************************************

		$city_code = 'f';
		$land = '新北市板橋區新雅段726';
		$sssss = $this->land_model->getRegionNamebyLandText($city_code, $land);
		dprint($sssss);
		if ( $sssss['region_name'] == 'Error') {
			echo $land.' => 查無此地號';
		} else {
			echo $land.' => '
			.$sssss['region_name'].' ; '
			.$sssss['land_sn'].' ; '
			.$sssss['print_date'].' ; '
			.$kind_map[$sssss['kind']].' ; '
			.$sssss['area_m2']
			.'m2 ('.$sssss['area_ping'].'坪)'.' ; '
			.$sssss['bulletin_date'].'年　'
			.$sssss['land_value'].'元';
		
		}
	}

	/**
	 * faq list page
	 */
	public function test()
	{
		$this->load->library('excel');
		
		$file = 'C:\Users\ch0082\Desktop\user2015_.xlsx';


		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		
		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
		    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    //header will/should be in row 1 only. of course this can be modified to suit your need.
		    if ($row == 1) {
		        $header[$row][$column] = $data_value;
		    } else {
		        $arr_data[$row][$column] = $data_value;
		    }
		}
		//send the data in an array format
		$data['header'] = $header;
		$data['values'] = $arr_data;
		
		//dprint($arr_data);
		
		
		$org_list = $this->it_model->listData("unit" );
		$org_map = array();
		foreach ($org_list["data"] as $item) 
		{
			$org_map[$item["unit_name"]] = $item["sn"]; 
		}
		
		echo '<meta charset="UTF-8">';
		//dprint($org_map);
		//return;
		foreach ($arr_data as $item) 
		{
			if( isNull(tryGetData("D", $item)) )
			{
				continue;
			}
			
			$user_id =  strtolower(tryGetData("D", $item)).str_pad(tryGetData("E", $item),4,'0',STR_PAD_LEFT);	
			$job_type = tryGetData("G", $item);
			
			$gender = tryGetData("I", $item)=="男"?1:0;
			
			
			$user_data = array(					 			
					 "name" =>  tryGetData("F", $item)
					, "job_type"=> $job_type
					, "job_title"=> tryGetData("H", $item)
					, "gender"=> $gender
					, "take_office_date" => tryGetData("J", $item,NULL)
					, "phone" => str_replace("-","",tryGetData("K", $item))
					, "email" => tryGetData("L", $item)
					, "start_date"=> date( "Y-m-d H:i:s" ) 
					, "update_date" =>  date( "Y-m-d H:i:s" ) 
				);
				
			if(array_key_exists(tryGetData("C", $item), $org_map))
			{
				$user_data["unit_sn"] = $org_map[tryGetData("C", $item)];				
			}
			
			
				
			$result = $this->it_model->updateData( "sys_user" , $user_data, "id ='".$user_id."'" );
			//dprint($user_data);
			//exit;
			
			if($result === FALSE)
			{
				$user_data["id"] = $user_id;
				$user_data["password"] =prepPassword($user_id);		
				$user_data["create_date"] = date( "Y-m-d H:i:s" );			
				//dprint($user_data);
				
			
				$this->it_model->addData("sys_user",$user_data );
	
			}
			
		}
		
		

	}

	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */