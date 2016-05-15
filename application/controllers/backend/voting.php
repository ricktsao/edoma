<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voting extends Backend_Controller {
	
	private $voting_model;

	function __construct() 
	{
		parent::__construct();	

		$this->load->model('Voting_model');	
		
	}
	


	/**
	 * bulletin list page
	 */
	public function contentList()
	{	


		$list = $this->it_model->listData( "voting","is_del=0",$this->per_page_rows , $this->page );
		$data["list"] = $list['data'];
		$list["count"] =  $list['count'];

		$today = date('Y-m-d');
		for($i=0;$i<count($data['list']);$i++){
			$data['list'][$i]['active']=FALSE;
			$start_date = date("Y-m-d",strtotime($data['list'][$i]['start_date']));

			if($today >=$start_date){
				$data['list'][$i]['active'] = TRUE;
			}

		}


		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows  ,"contentList");	
		$this->display("content_list_view",$data);
		//dprint($data["pager"]);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{
		
		$content_sn = $this->input->get('sn');		
				
		if($content_sn == "")
		{
			$data["edit_data"] = array
			(
				'subject' =>null,
				'start_date' => date('Y-m-d',strtotime(date( "Y-m-d" )."+1 days")),			
				'description' => 0,
				'allow_anony' => 0,
				'is_multiple' => 0,
				'voting_option'=>array()
			);
			$this->display("content_form_view",$data);
		}
		else 
		{			

			$list = $this->it_model->listData( "voting","sn =".$content_sn );

			$list= $list['data'];
			
			if(count($list)>0)
			{
						
				$data["edit_data"] = $list[0];

				//get option
				$option =  $this->it_model->listData( "voting_option","voting_sn =".$content_sn." AND is_del=0" );				
				$data['edit_data']['voting_option'] = $option['data'];

				$this->display("content_form_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}
		}
	}
	
	
	public function updateContent()
	{	
		$edit_data = [];

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		$edit_data["allow_anony"] = tryGetArrayValue("allow_anony",$edit_data,0);
		$edit_data["is_multiple"] = tryGetArrayValue("is_multiple",$edit_data,0);	
		

					
		if ( ! $this->_validateContent())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {
			
        	if(isset($edit_data["voting_option"])){
        		$voting_option = $edit_data["voting_option"];
        		unset($edit_data["voting_option"]);
        	}
			
			if(isNotNull($edit_data["sn"]))
			{
				$this->Voting_model->change_option($edit_data["sn"],$voting_option);
					
				if($this->it_model->updateData( "voting" , $edit_data, "sn =".$edit_data["sn"] ))
				{
					$sync_result = $this->Voting_model->sync_to_server($edit_data,"sync_voting/updateContent");
					$this->it_model->updateData("voting",array("is_sync"=>$sync_result),"sn = ".$edit_data["sn"]);
					$this->Voting_model->change_option($edit_data["sn"],$voting_option);
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
			}
			else 
			{			
				$edit_data['user_sn'] = $this->session->userdata('user_sn');
				$content_sn = $this->it_model->addData( "voting" , $edit_data );
				if($content_sn > 0)
				{				
					$edit_data["sn"] = $content_sn;
					$sync_result = $this->Voting_model->sync_to_server($edit_data,"sync_voting/updateContent");

					$this->it_model->updateData("voting",array("is_sync"=>$sync_result),"sn = ".$content_sn);

					//echo $re;die();
					$this->Voting_model->change_option($edit_data["sn"],$voting_option);
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
	
			}
			
			redirect(bUrl("contentList"));	
        }	
	}
	
	/**
	 * 驗證bulletinedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');		
		
		$this->form_validation->set_rules( 'subject', '投票主題', 'required' );	
		
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}




	public function deleteContent()
	{
		$del = $this->input->post('del',TRUE);		
		$del = implode(",",$del);
	
		if($del!= FALSE )
		{
			//$this->it_model->deleteDB( "voting",NULL,$del_ary );
			$this->it_model->updateData( "voting" , array("is_del"=>1,"is_sync"=>0), "sn in (".$del.")" );			
			$re_sync = $this->Voting_model->sync_to_server(array("sn"=>$del),"sync_voting/removeVoting");
			if($re_sync=="1"){
				$this->it_model->updateData( "voting" , array("is_sync"=>1), "sn in (".$del.")" );
			}
		
		}
		$this->showSuccessMessage();
		redirect(bUrl("contentList", FALSE));	
	}


	public function launchContent()
	{		
		$this->ajaxChangeStatus("web_menu_content","launch",$this->input->post("content_sn", TRUE));
	}

	public function votingRecord()
	{	

		$sn = $this->input->get('sn');

		//check is manager

		$this->session->userdata('user_sn');

		$sql="SELECT IF(is_manager=1,TRUE,FALSE) as is_manager FROM sys_user WHERE sn=".$this->session->userdata('user_sn');

		$is_manager = $this->it_model->runSql($sql);

		$is_manager = $is_manager['data'][0]['is_manager'];
		

		$data = [];

		$data['list'] = $this->Voting_model->votingRecord($sn,$is_manager);

		
		
		
		$this->display("voting_record_view",$data);
		
	}

	public function showPdf()
	{
		$sn = $this->input->get('sn');
		//$item_info = $this->c_model->GetList( "bulletin" , "sn =".$content_sn);
		$list = $this->Voting_model->votingRecord($sn);

	//	dprint($list);
	//	die();	
		if($list)
		{		
						
	
			$html = "<h1 style='text-align:center'>社區議題投票</h1>";
			$html .= "<h2>".$list["subject"]."</h2>";
			$html .= "<h3>".$list["description"]."</h3>";
			if($list['create_user']!=''){
				$html .= "<h3>發起人:".$list["create_user"]."</h3>";
			}
			$html .= "<table border=1 style='width:100%;'>
						<thead>
							<tr>
								<td style='text-align:center'>項次</td>
								<td style='text-align:center'>選項</td>
								<td style='text-align:center'>得票</td>
							</tr>
						</thead>
						<tbody>";

			for($i=0;$i<count($list['options']);$i++){

				$html.= "<tr>
							<td style='text-align:center'>".($i+1).".</td>
							<td>".$list['options'][$i]['option_text']."</td>
							<td style='text-align:center'>".$list['options'][$i]['voting_count']."</td>
						</tr>";


			}

			$html .= "<tbody></table>";
	
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
			$pdfFilePath = "社區議題投票_".$time .".pdf";
			$mpdf->Output($pdfFilePath,'I');
		}
		else
		{
			$this->closebrowser();
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