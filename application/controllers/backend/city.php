<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	


	/**
	 * sys_news list page
	 */
	public function contentList()
	{			

		$condition = "";
		$list = $this->it_model->listData("city");
		
		//img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//取得分頁
		//$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		$this->display("content_list_view",$data);
	}
	
	/**
	 * category edit page
	 */
	public function editContent()
	{		
		
		$content_sn = $this->input->get('id');	
				
		if($content_sn == "")
		{	
			redirect(bUrl("contentList"));
			die();
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'content_type' => "sys_news",
				'target' => 0,
				'forever' => 1,
				'launch' =>1
			);
			$this->display("content_form_view",$data);
		}
		else 
		{		
			

			$sys_news_info = $this->it_model->listData("city","id ='${content_sn}'");
			

			if(count($sys_news_info["data"])>0)
			{				
				
				$data["edit_data"] = $sys_news_info["data"][0];			

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
	
		
		//dprint($edit_data["comm_id"]);exit;

		$edit_data = array();

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $value;
		}


		
		
		if ( ! $this->_validateContent())
		{			
			$data["edit_data"] = $edit_data;		
			$this->display("content_form_view",$data);
		}
        else 
        {	
			if(isNotNull($edit_data["id"]))
			{	
			
				$re = $this->it_model->updateData( "city" , array('title'=>$edit_data['title']), "id ='".$edit_data['id']."'" );

				

						
				if($re)
				{	
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
	 * 驗證sys_newsedit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');				
		$this->form_validation->set_rules( 'title', '名稱', 'required' );		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	//----------------

	public function townList()
	{			

		$edit_data = array();

		foreach ($_GET as $key => $value) {
			$edit_data[$key] = $value;
		}

		
		$condition = "city_code='".$edit_data['id']."'";
		$list = $this->it_model->listData("town",$condition);
		
		//img_show_list($list["data"],'img_filename',$this->router->fetch_class());
		
		$data["list"] = $list["data"];
		
		//取得分頁
		//$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		
		$this->display("town_list_view",$data);
	}


	public function editTown()
	{			
		

		$edit_data = array();

		foreach ($_GET as $key => $value) {
			$edit_data[$key] = $value;
		}		
				
		if(!array_key_exists("sn",$edit_data))
		{	
			
			$data["edit_data"] = array
			(
				'city_code' =>$edit_data['id'],			
				'town_name' => null
			);
			$this->display("town_form_view",$data);
		}
		else 
		{	
			

			$sys_news_info = $this->it_model->listData("town","sn ='${edit_data['sn']}'");
			
			if(count($sys_news_info["data"])>0)
			{				
				
				$data["edit_data"] = $sys_news_info["data"][0];			

				$this->display("town_form_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}
		}
	}


	public function updateTown()
	{
		$edit_data = array();

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $value;
		}		
	

		if ( ! $this->_validateTown())
		{			
			$data["edit_data"] = $edit_data;		
			$this->display("town_form_view",$data);
		}
        else 
        {        
		
			if(isNotNull($edit_data["sn"]))
			{	
				
				$re = $this->it_model->updateData( "town" , array('town_name'=>$edit_data['town_name']), "sn ='${edit_data['sn']}'" );
						
				if($re)
				{	
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
								
			}else{


				$re = $this->it_model->addData("town",array("town_name"=>$edit_data["town_name"],
													"city_code"=>$edit_data["city_code"]));

				if($re)
				{	
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}

			}
			
			redirect(bUrl("townList",TRUE,array("sn")));	
        }	
	}	

	public function deleteTown(){
			
		if(array_key_exists("del",$_POST)){
			$this->it_model->deleteDB("town",NULL,array("sn"=>$_POST["del"]));
		}

		redirect(bUrl("townList",TRUE));
	}

	function _validateTown()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');				
		$this->form_validation->set_rules( 'town_name', '標題', 'required' );		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}



	public function villageList(){
		$edit_data = array();

		foreach ($_GET as $key => $value) {
			$edit_data[$key] = $value;
		}

		$condition = "town_sn='".$edit_data['sn']."'";
		$list = $this->it_model->listData("village",$condition);

		$data["list"] = $list["data"];

		$data["city_code"] = $edit_data["id"];

		$this->display("village_list_view",$data);
	}


		public function editVillage()
	{			
		

		$edit_data = array();

		foreach ($_GET as $key => $value) {
			$edit_data[$key] = $value;
		}		
				
		if(!array_key_exists("v_sn",$edit_data))
		{	
			
			$data["edit_data"] = array
			(
				'city_code' =>$edit_data['id'],			
				'village_name' => null,
				'sn'=>$edit_data['sn']
			);
			$this->display("village_form_view",$data);
		}
		else 
		{	
			

			$sys_news_info = $this->it_model->listData("village","sn ='${edit_data['v_sn']}'");
			
			if(count($sys_news_info["data"])>0)
			{				
				
				$data["edit_data"] = $sys_news_info["data"][0];		

				$data["edit_data"]['v_sn'] = $data["edit_data"]['sn'];	

				$this->display("village_form_view",$data);
			}
			else
			{
				redirect(bUrl("contentList"));	
			}
		}
	}

	public function updateVillage()
	{
		$edit_data = array();

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $value;
		}		
		


		if ( ! $this->_validateVillage())
		{			
			$data["edit_data"] = $edit_data;		
			$this->display("village_form_view",$data);
		}
        else 
        {     	

		
			if(isNotNull($edit_data["v_sn"]))
			{	
				
				

				$re = $this->it_model->updateData( "village" , array('village_name'=>$edit_data['village_name']), "sn ='${edit_data['v_sn']}'" );
						
				if($re)
				{	
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
								
			}else{
				
				$re = $this->it_model->addData("village",array("village_name"=>$edit_data["village_name"],
													"city_code"=>$edit_data["city_code"],
													"town_sn"=>$edit_data["sn"]));

				if($re)
				{	
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}

			}

		

			
			
			redirect(bUrl("villageList",TRUE,array("v_sn")));	
        }	
	}	


	function _validateVillage()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');				
		$this->form_validation->set_rules( 'village_name', '標題', 'required' );		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}


	public function deleteVillage(){
			
		if(array_key_exists("del",$_POST)){
			$this->it_model->deleteDB("village",NULL,array("sn"=>$_POST["del"]));
		}

		redirect(bUrl("villageList",TRUE));
	}


	public function deleteContent()
	{
		
		$del_ary = tryGetData("del",$_POST,array());				

		//社區主機刪除
		//----------------------------------------------------------------------------------------------------
		$sync_sn_ary = array();//待同步至雲端主機 array
		foreach ($del_ary as  $content_sn) 
		{
			$result = $this->it_model->updateData( "edoma_content" , array("del"=>1,"update_date"=>date("Y-m-d H:i:s")), "sn ='".$content_sn."'" );
			if($result)
			{
				array_push($sync_sn_ary,$content_sn);
			}						
		}
		//----------------------------------------------------------------------------------------------------
				
		
		$this->showSuccessMessage();
		
		redirect(bUrl("contentList", FALSE));	
	}

	public function launchContent()
	{		
		$this->ajaxlaunchContent($this->input->post("content_sn", TRUE));
	}
	

	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
