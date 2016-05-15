<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IT_Model extends CI_Model
{
	protected $sql_cmd = NULL;
	protected $row_count = 0;
	
	 function __construct() 
	 {
		parent::__construct();	  
	 }

	
	/*
	執行SQL(讀取)
	輸入：無
	輸出：無
	開發日期：2010-05-14
	開發者：IT Team
	*/
	function readQuery( $sql_cmd )
	{	
		$result = $this->db->query($sql_cmd);

		//echo sql_cmd;
		if ($result->num_rows() > 0) 
		{					
			$temp_sql = "select FOUND_ROWS() as RecordCount";
			$total_result = $this->db->query($temp_sql);

			if ($total_result->num_rows() > 0)
			{
				$row = $total_result->row_array(); 
				$this->row_count = $row["RecordCount"];
			}
			return $result->result_array();
		}
		else 
		{	

			$this->row_count = $result->num_rows();
			return array();
		}
	}
	
	
	/*
	串排序SQL(order by)
	輸入：$arr_sort：排序(order by)依據
	輸出：order by field_1 [asc|desc] , field_1 [asc|desc] , .........
	開發日期：2009-05-11
	開發者：IT Team
	*/
	public function getSortSQL( $arr_sort )
	{
		$sql = "";

		if( is_array( $arr_sort ) && sizeof( $arr_sort ) > 0 )
		{
			$arr_sort_key = array_keys( $arr_sort );
			
			for( $i = 0 ; $i < sizeof( $arr_sort_key ) ; $i++ )
			{
				if( $i == 0 )
					$sql .= " order by ";
				else
					$sql .= " , ";
					
				$sql .= $arr_sort_key[$i]." ".$arr_sort[$arr_sort_key[$i]];
			}
		}
		
		return $sql;
	}
	
	
	/*
	串資料範圍SQL(LIMIT)
	輸入：
		1.$int_rows：取出幾列
		2.$int_page:取出第幾頁的資料(傳入1即為第1頁)
	輸出：[LIMIT 1|LIMIT 0 , 10]
	開發日期：2009-05-21
	開發者：IT Team
	*/
	function getLimitSQL( $int_rows , $int_page )
	{
		$sql = "";
	
		if( preg_match( "/^[0-9]{1,}$/" , $int_rows ) && $int_rows > 0 )
		{
			if( preg_match( "/^[0-9]{1,}$/" , $int_page ) && $int_page > 0 )
			{
				$sql .= " LIMIT ".( ( $int_page - 1 ) * $int_rows )." , ".$int_rows." ";
			}
			else
			{
				$sql .= " LIMIT ".$int_rows." ";
			}
		}
		
		return $sql;
	}
	
	/*
	取得上一段SELECT加上SQL_CALC_FOUND_ROWS的敘述，去除LIMIT的總筆數
	輸入：無
	輸出：總筆數
	開發日期：2009-02-06
	開發者：IT Team
	*/
	function getRowsCount()
	{	
		return $this->row_count;
	}
	
	/*
	串Where SQL(同一欄位多個值)
	輸入：
		1.$str_field_name：欄位名稱(必要時用table_name.field_name)
		2.$mix_sn:單一個值或值的陣列
	輸出：field = 'value_1' or field = 'value_2' or ............................
	開發日期：2009-05-21
	開發者：IT Team
	*/
	function getCombiSQL( $str_field_name , $mix_sn )
	{
		$sql = "";
	
		if( is_array( $mix_sn ) && sizeof( $mix_sn ) > 0 )
		{
			for( $i = 0 ; $i < sizeof( $mix_sn ) ; $i++ )
			{
				if( $i > 0 )
					$sql .= " or ";
					
				$sql .= " ".$str_field_name." = '".$mix_sn[$i]."' ";
			}
		}
		elseif( ereg( "^[0-9]{1,}$" , $mix_sn ) )
		{
			$sql .= " ".$str_field_name." = '".$mix_sn."' ";
		}
		else
		{
			$sql .= "1";
		}
		
		return $sql;
	}


	/*
	串Where SQL(有效時間及啟用/停用)
	輸入：
		1.$str_table_name：資料表名稱
		2.$str_prefix:欄位前置詞
	輸出：		start_date < NOW()
			and ( end_date > NOW() or forever = '1' )
			and launch = '1' ";
	開發日期：2009-05-21
	開發者：IT Team
	*/
	function eSQL( $str_table_name  )
	{
		$sql = "";

		$sql .= "		".$str_table_name.".start_date <= NOW()
					and ( ".$str_table_name.".end_date > NOW() or ".$str_table_name.".forever = '1' )
					and ".$str_table_name.".launch = '1' ";

		return $sql;
	}
	
	function getEffectedSQL( $str_table_name  )
	{
		$sql = "";

		$sql .= "		".$str_table_name.".start_date <= NOW()
					and ( ".$str_table_name.".end_date > NOW() or ".$str_table_name.".forever = '1' )
					and ".$str_table_name.".launch = '1' ";

		return $sql;
	}
	
	/*
	串群組SQL(group by)
	輸入：
		$arr_group：排序(group by)欄位
	輸出：group by field_1 [asc|desc] , field_1 [asc|desc] , .........
	開發日期：2009-05-21
	開發者：IT Team
	*/
	function getGroupSQL( $arr_group )
	{
		$sql = "";

		if( is_array( $arr_group ) && sizeof( $arr_group ) > 0 )
		{
			$arr_group_key = array_keys( $arr_group );
			
			for( $i = 0 ; $i < sizeof( $arr_group_key ) ; $i++ )
			{
				if( $i == 0 )
					$sql .= " group by ";
				else
					$sql .= " , ";
					
				$sql .= $arr_group[$i];
			}
		}
		
		return $sql;
	}
	
	/*
	列表資料(Select)
	輸入：	1.$str_table_name:表格名稱
			2.$condition:條件式
			3.$int_rows:每頁幾筆(或限制僅讀幾筆)
			4.$int_page:取得某頁資料
			5.$arr_sort:排序
					$arr_sort
					array
					(
						table_name.field_name_1 => "asc|desc" , 
						table_name.field_name_2 => "asc|desc"
					)
	輸出：最後一筆的自動編號欄位值
	開發日期：2010-02-13
	開發者：IT Team
	*/
	function listData( $str_table_name , $str_conditions = NULL , $int_rows = NULL , $int_page = NULL , $arr_sort = NULL )
	{
		$sql = "	SELECT SQL_CALC_FOUND_ROWS *
					FROM ".$str_table_name."
					WHERE ( 1 = 1 ) ";

		if( $str_conditions != NULL )
			$sql .= " AND ( ".$str_conditions." ) ";

		$sql .= $this->getSortSQL( $arr_sort );
			
		$sql .= $this->getLimitSQL( $int_rows , $int_page );
		
		$res = $this->readQuery( $sql );
		
		$arr_data = array
		(
			"sql" => $sql ,
			"data" => $res ,
			"count" => $this->getRowsCount()
		);		
		
		return $arr_data;
	}
	
	
	
	
	function runSql( $sql = "" , $int_rows = NULL , $int_page = NULL , $arr_sort = NULL )
	{
		
		$sql .= $this->getSortSQL( $arr_sort );
			
		$sql .= $this->getLimitSQL( $int_rows , $int_page );
		
		
		$res = $this->readQuery( $sql );
		
		$arr_data = array
		(
			"sql" => $sql ,
			"data" => $res,
			"count" => $this->getRowsCount()
		);		
		
		return $arr_data;
	}
	
	
	function runSqlCmd($sql = "")
	{
		$result = $this->db->query($sql);
		return $result;
	}
	

	/*
	新增資料(Insert)
	輸入：	1.$str_table_name:表格名稱
			2.$arr_data:陣列
				array:
					[欄位名稱]:值
					[欄位名稱]:值
					[欄位名稱]:值
					......
	輸出：最後一筆的自動編號欄位值

	*/
	function addData( $str_table_name , $arr_data )
	{		
		$this->db->insert($str_table_name,$arr_data);

		return $this->db->insert_id();
	}
	
	/*
	更新資料(Update)
	輸入：	1.$str_table_name:表格名稱
			2.$arr_data:陣列
				[欄位名稱]:值
				[欄位名稱]:值
				[欄位名稱]:值
				......
			3.$str_conditions:特定條件
	輸出：被更新的資料筆數
	*/
	function updateData( $str_table_name , $arr_data , $str_conditions )
	{
		$this->db->update($str_table_name, $arr_data, $str_conditions);

		if($this->db->affected_rows() != FALSE && $this->db->affected_rows() > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}		
	}
	
	
	function tryAddLogTable($comefrom="backend")
	{
		$table_name = "sys_".$comefrom."_log_".date( "Y" );
		
		if( ! $this->db->table_exists($table_name))
		{
		   $sql = "CREATE TABLE `".$table_name."` (
					`sn` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
					`user_id` VARCHAR(6) NULL DEFAULT NULL,
					`ip` VARCHAR(50) NOT NULL,
					`module_id` VARCHAR(50) NOT NULL,
					`desc` VARCHAR(500) NOT NULL,
					`action` TINYINT(1) NULL DEFAULT '0' COMMENT '0:使用狀況,1:動作',
					`start_date` DATETIME NULL DEFAULT NULL,
					`end_date` DATETIME NULL DEFAULT NULL,
					`active_date` DATETIME NOT NULL,
					PRIMARY KEY (`sn`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=MyISAM
				;
				";
				$this->db->query($sql);
		} 
		
		
		return $table_name;
		
		
	}
	
	
	
	/*
	 * 2012.10.19  
	 * 輸出sql;
	更新資料(Update)
	輸入：	1.$str_table_name:表格名稱
			2.$arr_data:陣列
				[欄位名稱]:值
				[欄位名稱]:值
				[欄位名稱]:值
				......
			3.$str_conditions:特定條件
	輸出：被更新的資料筆數
	*/
	function updateDB( $str_table_name , $arr_data , $str_conditions )
	{
		$this->db->update($str_table_name, $arr_data, $str_conditions);	
		$arr_return=array();
		$arr_return['sql']=$this->db->last_query();
		if($this->db->affected_rows() != FALSE && $this->db->affected_rows() > 0 )
		{
			$arr_return['success']=TRUE;
		}
		else
		{
			$arr_return['success']=FALSE;
		}
		
		return 	$arr_return;
	}
	
	
	function deleteData( $str_table_name , $ary_conditions )
	{
		if( $ary_conditions != NULL && is_array($ary_conditions))
		{
			//$sql = "delete from ".$str_table_name." where ".$str_conditions;			

			$this->db->delete($str_table_name,$ary_conditions);
			
			//echo $sql;
			
			if($this->db->affected_rows() != FALSE && $this->db->affected_rows() > 0 )
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}

		}
		else
		{
			return FALSE;
		}
	}
	
	
	/*刪除檔案*/
	/*20120928  更新
	/*增加一個$ary_where_in參數
	/*array("欄位"=>"參數Array")
	*/
	 
	function deleteDB( $str_table_name , $ary_conditions=NULL ,$ary_where_in=NULL)
	{		
				
		if(($ary_conditions != NULL && is_array($ary_conditions))||($ary_where_in != NULL && is_array($ary_where_in)))
		{
			
			if($ary_conditions != NULL){
				$this->db->where($ary_conditions);			
			}

			if($ary_where_in != NULL){
				foreach($ary_where_in as $key=>$value){
					$this->db->where_in($key,$value);	
				}					
			}
			
			$this->db->delete($str_table_name);			
			
			
					
			if($this->db->affected_rows() != FALSE && $this->db->affected_rows() > 0 )
			{
				$success=TRUE;
			}
			else
			{
				$success=FALSE;
			}
		}
		else
		{
			$success=FALSE;
		}
		
		$arr_return=array("success"=>$success
						,"sql"=>$this->db->last_query());
						
		return $arr_return;
		
	}
	
	
	/*
	 *引用日期：2012-08-06
	 *引用者：
	 *解說：取得欄位
	 *
	 * */
	function GetFields($obj_field)
	{
		if(is_array($obj_field) && sizeof($obj_field) > 0)
		{
			$sql="";
			
			foreach($obj_field as $field=>$alias)
			{
				$sql.=$field." AS ".$alias.",";
			}
			
			$sql=substr($sql,0,-1);
			
			return $sql;			
		}
		elseif( !empty($obj_field) )
		{
			return $obj_field;
		}
		else
		{
			return "*";
		}
	}
	
	
	
	
	/*
	//------開發記錄------
	(1)
	開發者:IT Team
	開發時間:2010-05-
	輸入:
		1.$ADORecordSet：資料集合物件
	用途：將ADORecordSet轉成Array
	//------開發記錄------
	*/
	function convertArrayToKeyValuePair( $recordset , $key_field , $value_field )
	{
		$arr = array();		
		if( count($recordset) > 0 )
		{			
			for( $i = 0 ; $i < count($recordset) ; $i++ )
			{				
				$arr[$recordset[$i][$key_field]] = $recordset[$i][$value_field];
			}
		}		
		return $arr;
	}
	
	function toMapValue( $recordset , $key_field , $value_field )
	{
		$arr = array();		
		if( count($recordset) > 0 )
		{			
			for( $i = 0 ; $i < count($recordset) ; $i++ )
			{				
				$arr[$recordset[$i][$key_field]] = $recordset[$i][$value_field];
			}
		}		
		return $arr;
	}
	
	
	
	//-------------------------------------------------------------------------------- 
	function convertArrayToValueArray( $recordset , $value_field )
	{
		$arr = array();
		
		if( count($recordset) > 0 )
		{			
			for( $i = 0 ; $i < count($recordset) ; $i++ )
			{
				array_push( $arr , $recordset[$i][$value_field] );	
			}
		}
		
		return $arr;
	}
	
	function convertArrayToKeyArray( $recordset , $key_field )
	{
	
		$arr = array();		
		if( count($recordset) > 0 )
		{			
			for( $i = 0 ; $i < count($recordset) ; $i++ )
			{
				$arr[$recordset[$i][$key_field]] = $recordset[$i];
			}
		}		
		return $arr;

	}
	

	
}