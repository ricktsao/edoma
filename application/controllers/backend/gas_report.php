<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gas_report extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	

	/**
	 * list page
	 */
	public function contentList()
	{		
		$this->getAppData();//至server查詢有無app新增資料,並同步
	
	
		$year = $this->input->get("year",TRUE);		
		$month = $this->input->get("month",TRUE);
		
		$building_list = array();
		if(isNotNull($year) && isNotNull($month))
		{
			$this->getBuildData();
			$building_query ="
			select addr,building_id,owner_addr from sys_user where role='I' group by addr 	
			order by addr
			";
			$building_list = $this->it_model->runSql($building_query);
			//dprint(building_list);exit;
			$building_list = $building_list["data"];
			foreach ($building_list as $key => $building_info) 
			{
				$building_list[$key]["year"] = $year;
				$building_list[$key]["month"] = $month;
				$building_list[$key]["degress"] = 0;			
				
				$user_build_text = building_id_to_text($building_info["building_id"]);
				$build_text_ary = explode('&nbsp;&nbsp;',$user_build_text);
				$build_addr = tryGetData(0,$build_text_ary).'&nbsp;&nbsp;'.tryGetData(1,$build_text_ary).'&nbsp;&nbsp;門號'.$building_info["addr"];
				$building_list[$key]["build_addr"] = $build_addr;			
				
				
				$query = "
				SELECT SQL_CALC_FOUND_ROWS * from gas 
				where year = '".$year."' and month = '".$month."'
				and building_id = '".$building_info["addr"]."'";	
				
				$gas_info = $this->it_model->runSql($query);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];
					$degress = tryGetData("degress",$gas_info,"-");
					if($degress > 0 )
					{
						$degress = $degress."度";
					}
					$building_list[$key]["degress"] = $degress ;					
				}			
				
			}
		}
		
		
		$this_year = date("Y");
		$year_list = array();
		array_push($year_list,$this_year);
		array_push($year_list,$this_year-1);
		array_push($year_list,$this_year-2);
		$data["year_list"] = $year_list;
		
		$data["q_year"] = $year;	
		$data["q_month"] = $month;		
		$data["building_list"] = $building_list;
		//echo count($building_list);
		$this->display("content_list_view",$data);
	}
	

	/**
	 * pdf下載頁面
	 */
	public function showPdf()
	{
		$year = $this->input->get("year",TRUE);		
		$month = $this->input->get("month",TRUE);
		
		$building_list = array();
		if(isNotNull($year) && isNotNull($month))
		{
			$this->getBuildData();
			
			
			$building_query ="
			select addr,building_id,owner_addr from sys_user where role='I' group by addr 	
			order by addr
			";
			
			$building_list = $this->it_model->runSql($building_query);
			//dprint(building_list);exit;
			$building_list = $building_list["data"];
			foreach ($building_list as $key => $building_info) 
			{
				$building_list[$key]["year"] = $year;
				$building_list[$key]["month"] = $month;
				$building_list[$key]["degress"] = 0;			
				
				$user_build_text = building_id_to_text($building_info["building_id"]);
				$build_text_ary = explode('&nbsp;&nbsp;',$user_build_text);
				$build_addr = tryGetData(0,$build_text_ary).'&nbsp;&nbsp;'.tryGetData(1,$build_text_ary).'&nbsp;&nbsp;門號'.$building_info["addr"];
				$building_list[$key]["build_addr"] = $build_addr;	
				
				$query = "
				SELECT SQL_CALC_FOUND_ROWS * from gas 
				where year = '".$year."' and month = '".$month."'
				and building_id = '".$building_info["addr"]."'";
				
				$gas_info = $this->it_model->runSql($query);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];
					$degress = tryGetData("degress",$gas_info,"-");
					if($degress > 0 )
					{
						$degress = $degress."度";
					}
					$building_list[$key]["degress"] = $degress ;
				}			
				
			}
		}
					
		if(count($building_list)>0)
		{
			$content_str = '
			<tr style="color:#FFF">
				<td style="background: #036EB8;padding:10px;color:#FFF">住戶地址</td>
                <td style="background: #036EB8;padding:10px;text-align: center;color:#FFF">年份</td>
                <td style="background: #036EB8;padding:10px;text-align: center;color:#FFF">月份</td>
                <td style="background: #036EB8;padding:10px;text-align: center;color:#FFF">度數</td>
			</tr>
			';
			foreach ($building_list as $key => $gas_info) 
			{
				$content_str .= '
				<tr>
					<td style="padding: 10px;">'.$gas_info["build_addr"].'</td>
					<td style="padding: 10px;text-align: center">'.$gas_info["year"].'</td>										
					<td style="padding: 10px;text-align: center">'.$gas_info["month"].'</td>
					<td style="padding: 10px;text-align: center">'.($gas_info["degress"]==0?"-":$gas_info["degress"]).'</td>
				</tr>
				';
			}	
			
	
			$html = "<h1 style='text-align:center'>".$year."年  ".$month."月 - 瓦斯報表</h1>";
			$html .= '<table style="width: 90%;">'.$content_str.'</table>';
			
	
			$this->load->library('pdf');
			$mpdf = new Pdf();
			$mpdf = $this->pdf->load();
			$mpdf->useAdobeCJK = true;
			$mpdf->autoScriptToLang = true;
			
			
			
			$water_info = $this->c_model->GetList( "watermark");			
			if(count($water_info["data"])>0)
			{
				img_show_list($water_info["data"],'img_filename',"watermark");
				$water_info = $water_info["data"][0];			
		
				$mpdf->SetWatermarkImage($water_info["img_filename"]);
				$mpdf->watermarkImageAlpha = 0.081;
				$mpdf->showWatermarkImage = true;				
			}
			
			$mpdf->WriteHTML($html);		

			$time = time();
			$pdfFilePath = "瓦斯報表_".$time .".pdf";
			$mpdf->Output($pdfFilePath,'I');
		}
		else
		{
			$this->closebrowser();
		}
	}
	
	
	
	/**
	 * 查詢server上有無app新增的資料
	 **/
	public function getAppData()
	{
		$this->getBuildData();
		
		$post_data["comm_id"] = $this->getCommId();
		$url = $this->config->item("api_server_url")."sync/getAppGas";		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$json_data = curl_exec($ch);
		curl_close ($ch);
		
		$app_data_ary =  json_decode($json_data, true);
		if( ! is_array($app_data_ary))
		{
			$app_data_ary = array();
		}
		
		
		foreach( $app_data_ary as $key => $server_info ) 
		{			
		
		
			$update_data = array(			
			"server_sn" => $server_info["sn"],			
			"degress" => $server_info["degress"],							
			"updated" => date( "Y-m-d H:i:s" ),
			"is_sync" => 0
			);
		
			$condition = "building_id = '".$server_info["building_id"]."' AND year = '".$server_info["year"]."' AND month = '".$server_info["month"]."' ";
			$result = $this->it_model->updateData( "gas" , $update_data,$condition );					
			
			if($result === FALSE)
			{
				$update_data["comm_id"] = $this->getCommId();
				$update_data["building_id"] =  $server_info["building_id"];
				$update_data["building_text"] = building_id_to_text($server_info["building_id"]);
				$update_data["year"] = $server_info["year"];
				$update_data["month"] = $server_info["month"];
				$update_data["created"] = date( "Y-m-d H:i:s" );
				
				$content_sn = $this->it_model->addData( "gas" , $update_data );
				
				
				if($content_sn > 0)
				{				
					$update_data["sn"] = $content_sn;								
											
					$this->sync_item_to_server($update_data,"updateServerGas","gas");				
				}
				else 
				{
					//$this->showFailMessage();					
				}				
			}
			else
			{
				$condition = "building_id = '".$this->session->userdata('f_building_id')."' AND year = '".$year."' AND month = '".$month."' ";
				$gas_info = $this->it_model->listData("gas",$condition);
				if($gas_info["count"]>0)
				{
					$gas_info = $gas_info["data"][0];		
					$this->sync_item_to_server($gas_info,"updateServerGas","gas");
				}					
			}
		
								
		}
		
		//echo '<meta charset="UTF-8">';
		//dprint($app_data_ary);
		
	}
	
	
	function getBuildData()
	{
		// 取得戶別相關參數
		$this->load->model('auth_model');
		$this->building_part_01 = $this->auth_model->getWebSetting('building_part_01');
		$building_part_01_value = $this->auth_model->getWebSetting('building_part_01_value');
		$this->building_part_02 = $this->auth_model->getWebSetting('building_part_02');
		$building_part_02_value = $this->auth_model->getWebSetting('building_part_02_value');
		$this->building_part_03 = $this->auth_model->getWebSetting('building_part_03');

		if (isNotNull($building_part_01_value)) {
			$this->building_part_01_array = array_merge(array(0=>' -- '), explode(',', $building_part_01_value));
		}

		if (isNotNull($building_part_02_value)) {
			$this->building_part_02_array = array_merge(array(0=>' -- '), explode(',', $building_part_02_value));
		}
	}
	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */