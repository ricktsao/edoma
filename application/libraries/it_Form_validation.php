<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IT_Form_validation extends CI_Form_validation {


	function __construct() 
	{
		parent::__construct();
	}
	
	
	
	
	/**
	 * 檢查Point Rule id是否存在
	 *
	 * @access	public
	 * @return	bool
	 */
	function checkCategoryIdExist()
	{		
		$sn = $this->CI->input->post("sn",TRUE);		
		
		$category_id = $this->CI->input->post("id",TRUE);		
			
		$condition = "id = '".$point_id."'";		
		
		if($sn !== FALSE && $sn !== '')
		{
			$condition .= " AND sn != ".$sn;
		}
		
		
		$list = $this->CI->ak_model->listdata("product_category", $condition);
		
		
		if ($list["count"] > 0)
		{				
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}
	
	

	/**
	 * 檢查Admin帳號是否存在
	 *
	 * @access	public
	 * @return	bool
	 */
	function checkAdminAccountExist()
	{		
		
		$user_id = $this->CI->input->post("id",TRUE);		
			
		$condition = "id = '".$user_id."'";		
		
		$admin_list = $this->CI->it_model->listdata("sys_user", $condition);
		
		
		if ($admin_list["count"] > 0)
		{				
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}

	/**
	 * 檢查Admin email是否重覆
	 *
	 * @access	public
	 * @param	int 1:insert,2:update
	 * @return	bool
	 */
	function checkAdminEmailExist($type = 1)
	{
		$this->CI->load->Model("auth_model");
		
		$email = $this->CI->input->post("email",TRUE);		
		$sn = $this->CI->input->post("sn",TRUE);	
			
		$condition = "email = '".$email."'";	
		
		if(isNotNull($sn))
		{
			$condition .= " AND sn !=".$sn;
		}
			
		
		$admin_list = $this->CI->auth_model->listdata("web_admin", $condition);
		
		
		if ($admin_list["count"] > 0)
		{				
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}




	/**
	 * 檢查會員emial是否存在
	 *
	 * @access	public
	 * @return	bool
	 */
	function checkMemberEmailExist()
	{
		$this->CI->load->Model("Member_Model");
		
		$email = $this->CI->input->post("email",TRUE);
		//$email = $this->CI->input->post("sn",TRUE);
			
		$condition = "email = '".$email."'";
		
		$list = $this->CI->Member_Model->listdata("member", $condition);
		
		
		if ($list["count"] > 0)
		{				
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}



	function check_mobile($mobile)
	{
		
		//$check_result = ereg("09[0-9]{2}[0-9]{3}[0-9]{3}", $mobile);
		$check_result = preg_match("/09[0-9]{2}[0-9]{3}[0-9]{3}/", $mobile);
		if($check_result)
		{
			return TRUE;
			
		}
		else
		{
			$this->_error_messages['check_mobile'] = '手機號碼格式不正確!';	
			return FALSE;
		}

	}

    /**
     * Valid Date (ISO format)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function valid_date($str)
    {
		//echo ($str);
        if ( preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $str) ) 
        {
            $arr = explode("-", $str);    // splitting the array
            $yyyy = $arr[0];            // first element of the array is year
            $mm = $arr[1];              // second element is month
            $dd = $arr[2];              // third element is days
            return ( checkdate($mm, $dd, $yyyy) );
        } 
        else 
        {
            return FALSE;
        }
    }
	
	/**
	 * Ted add
	**/
	function email_update_conf($str, $sn='')
	{
		$this->CI->load->Model("Member_Model");
		$condition = "email = '".$str."' AND sn <> '".$sn."'";
		
		$list = $this->CI->Member_Model->listdata("member", $condition);
		
		
		if ($list["count"] > 0)
		{				
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}



/*======================================================================*\
                                  END
End of file MY_Validation.php
Location: ./application/libraries/MY_Validation.php
\*======================================================================*/
}