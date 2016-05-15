<?php
Class Album_model extends IT_Model
{
	private $path;


	function __construct() 
	{
		parent::__construct();

		$this->path =  base_url()."upload/website/album/";

	}

	public function GetAlbumList( $condition = NULL , $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							album.*,album_category.title as category_title
					FROM 	album
					LEFT JOIN album_category on album.album_category_sn = album_category.sn
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

	public function GetHomeAlbumList(){
		//$path = base_url()."upload/website/album/";
		$sql = "SELECT title,sn,start_date FROM album  ORDER BY start_date DESC  LIMIT 0,3";
		$data =  $this->readQuery( $sql );
		
		for($i=0;$i<count($data);$i++){

			$itemSql = "SELECT 
			CONCAT('".$this->path ."',img_filename) as img_filename,
			title FROM album_item WHERE album_sn =".$data[$i]['sn']." and img_filename <>''  ORDER BY sort DESC   LIMIT 0,3";

			$item_result = $this->readQuery( $itemSql );

			$data[$i]['imgs']=$item_result;

		}

	 	return $data;
	}

	public function GetPhoto($sn){
		$itemSql = "SELECT 
		CONCAT('".$this->path ."',img_filename) as img_filename,
		title FROM album_item WHERE album_sn =".$sn." and img_filename <>'' and is_del=0  ORDER BY sort DESC";

		$item_result = $this->readQuery( $itemSql );

		return $item_result;
	}

	public	function sync_to_server($post_data =null,$page_name){
		//$url = "http://localhost/commapi/sync/updateContent";
		$url = $this->config->item("api_server_url").$page_name;
		//$url = "http://localhost:8080/commapi/".$page_name;
		
		$post_data['comm_id'] =  $this->session->userdata("comm_id");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		return $is_sync;
		//------------------------------------------------------------------------------
	}
	
	
}
