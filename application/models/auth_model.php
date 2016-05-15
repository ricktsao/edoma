<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Model extends IT_Model
{
	
	function __construct() 
	{
		parent::__construct();	  
	}


	// 取得還有效的車位 SN
	public function getFreeParkingSn( $parking_id )
	{
		if (isNotNull($parking_id)) {
			$query = 'SELECT p.*, up.user_sn '
					.'  FROM parking p  '
					.'  LEFT JOIN user_parking up ON p.sn = up.parking_sn '
					.' WHERE p.status = 1 AND up.user_sn IS NULL AND p.`parking_id`="'.$parking_id.'"'
					;
			$result = $this->it_model->runSql( $query );

			if ( $result['count'] > 0) {
				$data = $result['data'][0];
				return tryGetData('sn', $data, NULL);
			}
		}
		return false;
	}
	
	public function getWebSetting( $key )
	{
		if (isNotNull($key)) {
			$result = $this->it_model->listData('web_setting', '`key`="'.$key.'"');
			$data = $result['data'][0];

			return tryGetData('value', $data, NULL);
		}
		return false;
	}
	
	public function GetWebAdminList( $condition = NULL , $rows = NULL , $page = NULL , $sort = array() )
	{
		echo $condition;
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							sys_admin_group.*
					FROM 	sys_admin_group					
					WHERE ( 1 )
					";

		if( $condition != NULL )
		{
			$sql .= " AND ( ".$condition." ) ";
		}

		$sql .= $this->getSortSQL( $sort );
			
		$sql .= $this->getLimitSQL( $rows , $page );

		$data = array
		(
			"sql" => $sql ,
			"data" => $this->readQuery( $sql ) ,
			"count" => $this->getRowsCount()
		);		

		return $data;
	}
	
	
	public function GetGroupAuthorityList( $condition = NULL , $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	select sys_user_group_b_auth.*, sys_module.id from sys_user_group_b_auth
					left join sys_module on sys_user_group_b_auth.module_sn = sys_module.sn										
					WHERE ( 1 )
					";

		if( $condition != NULL )
		{
			$sql .= " AND ( ".$condition." ) ";
		}
		
		$sql .= "group by sys_module.id"; 
		

		$sql .= $this->getSortSQL( $sort );
			
		$sql .= $this->getLimitSQL( $rows , $page );

		$data = array
		(
			"sql" => $sql ,
			"data" => $this->readQuery( $sql ) ,
			"count" => $this->getRowsCount()
		);		

		return $data;
	}
	
	
	
	

}