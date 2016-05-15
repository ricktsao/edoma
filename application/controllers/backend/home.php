<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Backend_Controller{

	public function index()
	{
		// Rick
		$this->check_offline_sync();//web_menu_content 離線同步
		$this->check_mailbox_offline_sync();//mailbox 離線同步		
		$this->check_repair_offline_sync();//報修	
		$this->check_suggestion_offline_sync();//社區意見箱
		$this->check_gas_offline_sync();//gas 離線同步
		
		// Claire
		$this->check_user_sync();	//user 離線同步	 
		$this->check_house_to_rent_sync();	// 租屋離線同步	 
		$this->check_house_to_sale_sync();	// 售屋離線同步	

		$this->display("index_view");
	}

	public function testpdf()
	{	
		$time = time();
		$pdfFilePath = "./upload/tmp/testpdf_".$time .".pdf";

		$html = "<h1>富網通社區測試</h1>";
		$html .= "<table border=1><tr><td>表格＆圖檔</td><td><img width='100' src='".base_url('template\backend\images\img_logo.png')."'></td></tr></table>";

		$this->load->library('pdf');
		$mpdf = new Pdf();
		$mpdf = $this->pdf->load();
		$mpdf->useAdobeCJK = true;
		$mpdf->autoScriptToLang = true;

		//$mpdf->SetWatermarkText('富網通社區測試',0.1);
		//$mpdf->showWatermarkText = true; 
		//$mpdf->watermark_font = 'PMingLiU';		
		
		//$mpdf->SetWatermarkImage(base_url('template\backend\images\img_logo.png'));
		//$mpdf->watermarkImageAlpha = 0.081;
		//$mpdf->showWatermarkImage = true;
		
		$mpdf->SetWatermarkText('富網通社區測試');
		$mpdf->watermarkTextAlpha = 0.081;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->showWatermarkText = true;
		 
		//$mpdf=new \mPDF('+aCJK','A4','','',32,25,27,25,16,13); 		
		$mpdf->WriteHTML($html);
		$mpdf->Output();
	}
 	


	public function query_land()
	{
		$city = $this->input->get('ct', true);
		$land_sn = $this->input->get('num', true);
		
		if ( isNotNull($city) && isNotNull($land_sn) ) {
			$table = 'b'.$city.'_land_view' ;
			$condition = 'sn = '.$land_sn ;
			$result = $this->it_model->listData($table, $condition);
			
			if ($result['count'] > 0 ) {
				$land = $result['data'][0];
				echo '<meta charset="UTF-8">';

				$land_desc = tryGetData('city_name', $land).' ';
				$land_desc .= tryGetData('town_name', $land).' ';
				$land_desc .= tryGetData('section_name', $land).' ';
				$land_desc .= tryGetData('land_no_main', $land).'-';
				$land_desc .= tryGetData('land_no_sub', $land);
				$url = sprintf( frontendUrl('land', 'index/?type=2&pc=%s&town=%s&section=%s&land_no=%s-%s') 
								, $city 
								, '' 
								, tryGetData('section_sn', $land)
								, tryGetData('land_no_main', $land)
								, tryGetData('land_no_sub', $land)
								);
				echo $city .' & '. $land_sn.' ==> <a href="'.$url.'" target="land" style="text-decoration: none">'.$land_desc.'</a>';
			}
		}

	}

	public function query_report_stat()
	{
		//$this->profiler();
		echo '<meta charset="UTF-8">';
		
		$start_date = $this->input->get('s', true);
		$end_date = $this->input->get('e', true);

		$unit_array = array(19=>'聯陽業務一組'
							, 18=>'南崁業務一組'
							, 17=>'新北業務一組'
							, 16=>'新莊業務一組'
							, 25=>'台北業務一組'
							, 5=>'悅陽建設'
							, 20=>'桃園業務一組'
							, 21=>'桃園業務二組'
							, 23=>'桃園業務三組'
							, 24=>'桃園業務四組'
							, 27=>'桃園業務四組_開發');
		echo '<style type="text/css">
			th {
				font-size:16px; font-family: "微軟正黑體"; background-color: #e7e7e7
			}
			td {
				font-size:16px; font-family: "微軟正黑體"; 
			}
			a {text-decoration: none;}
		</style>';
		echo '<h2>單位批閱狀況統計<h2>';
		echo '<h3>日期區間：'.$start_date.' ~ '.$end_date.'<h3>';
		echo '<table>';
		echo '<tr><th>單位名稱</th><th>未批閱筆數</th><th>已批閱筆數</th><tr>';

			$i = 1;
		foreach ($unit_array as $k=>$v) {

			/* 未批閱*/
			$query_no_comment = 'SELECT SQL_CALC_FOUND_ROWS *
								FROM sales_report r
								LEFT JOIN customer c ON r.customer_sn = c.sn
								WHERE r.status=1 AND (COMMENT IS  NULL) 
								AND UPPER(user_id) IN (
									  SELECT id
								 FROM sys_user s LEFT JOIN unit u ON s.unit_sn = u.sn  
								 WHERE   s.launch =1 and (u.sn='.$k.' OR u.parent_sn='.$k.')
								)
								and visit_date between "'.$start_date.'" and "'.$end_date.'" '
								;

			$list_no_comment = $this->it_model->runSql($query_no_comment);


			/* 已批閱*/
			$query_comment = 'SELECT SQL_CALC_FOUND_ROWS *
								FROM sales_report r
								LEFT JOIN customer c ON r.customer_sn = c.sn
								WHERE r.status=1 AND (COMMENT IS NOT NULL) 
								AND UPPER(user_id) IN (
									  SELECT id
								 FROM sys_user s LEFT JOIN unit u ON s.unit_sn = u.sn  
								 WHERE   s.launch =1 and (u.sn='.$k.' OR u.parent_sn='.$k.')
								)
								and visit_date between "'.$start_date.'" and "'.$end_date.'" '
								;
			$list_comment = $this->it_model->runSql($query_comment);

				if ($i % 2 == 0) 
					$bgcolor = '#f6f6f6';
				else
					$bgcolor = '#fff';
			echo '<tr style="background-color: '.$bgcolor.'"><td><a href="'.bUrl('query_report_detail/?unit_sn='.$k.'&unit_name='.$v.'&s='.$start_date.'&e='.$end_date, false).'">'.$v. "</a></td><td style='text-align:center'>".$list_no_comment['count']. "</td><td style='text-align:center'>".$list_comment['count'].'</td></tr>';

		}
		echo '</table>';


	}






	public function query_report_detail()
	{		
		//$this->profiler();
		$unit_sn = $this->input->get('unit_sn');
		$unit_name = $this->input->get('unit_name');
		echo '<meta charset="UTF-8">';
		
		$start_date = $this->input->get('s', true);
		$end_date = $this->input->get('e', true);
		echo '<style type="text/css">
			th {
				font-size:14px; font-family: "微軟正黑體"; background-color: #e7e7e7
			}
			td {
				font-size:14px; font-family: "微軟正黑體";
			}
		</style>';
		echo '<h2>單位批閱狀況統計<h2>';
		echo '<h3>業務單位：'.$unit_name.'<h3>';
		echo '<h3>日期區間：'.$start_date.' ~ '.$end_date.'<h3>';

		echo '<table style="width: 70%">';
		echo '<tr><th></th><th style="width: 6%">業務</th><th style="width: 12%">拜訪日期</th><th style="width: 6%">客戶</th><th style="width: 45%">洽談內容</th><th style="width: 30%">主管批閱</th><tr>';


			/* 未批閱*/
			$query_no_comment = 'SELECT SQL_CALC_FOUND_ROWS *
								FROM sales_report r
								LEFT JOIN customer c ON r.customer_sn = c.sn
								WHERE r.status=1 
								AND UPPER(user_id) IN (
									  SELECT id
								 FROM sys_user s LEFT JOIN unit u ON s.unit_sn = u.sn  
								 WHERE   s.launch =1 and (u.sn='.$unit_sn.' OR u.parent_sn='.$unit_sn.')
								)
								and visit_date between "'.$start_date.'" and "'.$end_date.'" '
								;

			$list_no_comment = $this->it_model->runSql($query_no_comment);
			$i = 1;
			foreach ($list_no_comment['data'] as $item) {
				//dprint($item);
				$user_info = $this->person_model->getUserInfobyUserID($item['user_id']);
				
					// 主管批示
					$comment_text = '';
					if (isNotNull(tryGetData('comment', $item, Null)) ) {
						$comment = tryGetData('comment', $item);
						$comment_array = json_decode($comment, true);
						$comment_text .= '<div style="color: #c00;">';
						foreach ($comment_array as $cmt) {
							$comment_text .=  $cmt.'<br>';
						}
						$comment_text .=  '</div>';
					}
				if ($i % 2 == 0) 
					$bgcolor = '#f6f6f6';
				else
					$bgcolor = '#fff';
				echo sprintf('<tr style="background-color: %s"><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>'
							, $bgcolor
							, $i
							, $user_info['name']
							, '<span  style="line-height:150%">'.showDateFormat(tryGetData('visit_date', $item), 'Y年m月d日').'<br>'.showDateFormat(tryGetData('visit_date', $item), 'H時i分').'</span>'
							, $item['customer_name']
							, $item['visit_note']
							, $comment_text
							);
				$i++;
			}

		echo '</table>';


	}





























	
	
	function utf16urlencode($str)
	{
	    $str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
	    $out = '';
	    for ($i = 0; $i < mb_strlen($str, 'UTF-16'); $i++)
	    {
	        $out .= '%u'.bin2hex(mb_substr($str, $i, 1, 'UTF-16'));
	    }
	    return $out;
	}
	
	public function generateTopMenu()
	{		
		//$this->addTopMenu("群組管理 ","");
		//$this->addTopMenu("帳號管理 ","");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */