<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailbox extends Backend_Controller {
	
	function __construct() 
	{
		parent::__construct();		
		
	}
	
	
	
	public function keycode()
	{
				
		$this->display("keycode_view",array());
	}
	
	

	/**
	 * list page
	 */
	public function contentList()
	{
		$keycode_id = $this->input->post('keycode');
		
		$user_info = $this->it_model->listData("sys_user","id ='".$keycode_id."'");
		if($user_info["count"]==0)
		{
			$this->showMessage("磁卡錯誤");	
			redirect(bUrl("keycode"));
		}
		
		//領收人
		$user_info = $user_info["data"][0];
		$data["user_info"] = $user_info;
	
		//郵件類型
		$mail_box_type = $this->auth_model->getWebSetting('mail_box_type');
		$mail_box_type_ary = explode(",",$mail_box_type);
		$data["mail_box_type_ary"] = $mail_box_type_ary;
		
		$build_id_ary = explode("_", tryGetData("building_id", $user_info));
		$build_id_str = "";
		if(count($build_id_ary)==3)
		{
			$build_id_str = $build_id_ary[0]."_".$build_id_ary[1]."_%";
		}
				
		$mailbox_list = $this->it_model->listData("mailbox","is_receive = 0 and user_building_id like '".$build_id_str."' ", NULL , NULL, array("booked"=>'desc'));				
		//dprint($mailbox_list);
		
		$data["mailbox_list"] = $mailbox_list["data"];
		
		$this->display("content_list_view",$data);
	}
	



	public function updateMailbox()
	{			
		$receive_sn_ary = $this->input->post('is_receive',TRUE);		
		$mailbox_sn_ary = $this->input->post('mailbox_sn',TRUE);


		$receive_user_name = $this->input->post('receive_user_name',TRUE);
		$receive_user_sn = $this->input->post('receive_user_sn',TRUE);
		
		for ($i=0; $i < count($mailbox_sn_ary) ; $i++) 
		{
			if(in_array($mailbox_sn_ary[$i], $receive_sn_ary))
			{

				
				$update_data = array(
					"is_receive" => 1,
					"receive_user_name" => $receive_user_name,
					"receive_user_sn" => $receive_user_sn,
					"is_sync" => 0,
					"received" => date("Y-m-d H:i:s"),
					"updated" => date("Y-m-d H:i:s")
				);
				
				$result = $this->it_model->updateData( "mailbox" , $update_data,"sn ='".$mailbox_sn_ary[$i]."'" );
				if($result)
				{
					$mail_info = $this->it_model->listData("mailbox","sn ='".$mailbox_sn_ary[$i]."'");
					if($mail_info["count"]>0)
					{
						$mail_info =$mail_info["data"][0];
						$this->sync_item_to_server($mail_info,"updateMailbox","mailbox");
					}
										
				}
				
				
			}			
		}
		
		$this->showSuccessMessage();
		redirect(bUrl("keycode", TRUE));	
				
	}


	public function ajaxGetPeople()
	{
		$keyword = $this->input->get('keyword', true);
		
		$user_list = $this->it_model->listData("sys_user","name like '%".$keyword."%'");
		
		
		$return_string = '';
		foreach( $user_list["data"] as $key => $user )
		{
			
			$return_string .= '
			<ul id="names_list">
				<li onclick="selectCountry(\''.$user["sn"].'\',\''.$user["name"].'\');">'.$user["name"].'　地址：'.$user["owner_addr"].'</li>
			</ul>
			';	
		}
		echo $return_string;
	}

	


	
	public function GenerateTopMenu()
	{
		//addTopMenu 參數1:子項目名稱 ,參數2:相關action  

		$this->addTopMenu(array("keycode","contentList","editContent","updateContent"));
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */