<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IT_Controller extends CI_Controller 
{
	
	
	function __construct() 
	{
		parent::__construct();
		
		/*
		if($_SERVER['HTTP_HOST'] == 'web.chupei.com.tw' || $_SERVER['HTTP_HOST'] == '118.163.146.74')
		{
			echo '';
			exit;
		}
		*/
		

	}	
	
	
	public function sysLogout()
	{
		$this->session->unset_userdata('user_sn');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('user_email');
		$this->session->unset_userdata('supper_admin');
		$this->session->unset_userdata('user_login_time');
		$this->session->unset_userdata('user_auth');
		$this->redirectHome();
	}	
	


}

require('Backend_Controller.php');
require('Frontend_Controller.php');