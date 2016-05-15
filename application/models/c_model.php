<?php
Class C_model extends IT_Model
{
	public function GetList( $content_type = "", $condition = NULL , $is_frontend = FALSE, $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							edoma_content.*
					FROM 	edoma_content				
					WHERE ( del = 0 ) 
					";
		
		if( isNotNull($content_type) )
		{
			$sql .= " AND  edoma_content.content_type='".$content_type."'  ";
		}
		
		if( isNotNull($condition) )
		{
			$sql .= " AND ( ".$condition." ) ";
		}
		
		if( $is_frontend )
		{
			$sql .= " AND  ".$this->eSQL("edoma_content")."  ";
		}

		$sql .= $this->getSortSQL( $sort );
			
		$sql .= $this->getLimitSQL( $rows , $page );

		$res = $this->readQuery( $sql );	
		
		//page info
		//------------------------------------------
		$page_count=0;
		$pre_page=NULL;
		$next_page=NULL;
		//算出總頁數
		if(isNotNull($page))
		{
			$page_count=floor($this->getRowsCount()/$rows);
			if($this->getRowsCount()%$rows>0)
			{
				$page_count+=1;
			}
			
			if($page>1)
			{
				$pre_page=$page-1;
			}
			
			if($page<$page_count)
			{
				$next_page=$page+1;
			}			
		}
		//------------------------------------------
		
		$data = array
		(
			"sql" => $sql ,
			"data" => $res ,
			"count" => $this->getRowsCount(),
			"pageInfo"=>array("pageCount"=> $page_count,
								"pre_page"=>$pre_page,
								"next_page"=>$next_page)	
		);		

		return $data;
	}
	
	
	public function GetList2( $content_type = "", $condition = NULL , $is_frontend = FALSE, $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							edoma_content.*,parent.title as parent_title,parent.id as parent_id
					FROM 	edoma_content		
					LEFT JOIN edoma_content as parent on edoma_content.parent_sn = parent.sn		
					WHERE ( edoma_content.del = 0 or parent.del = 0 ) 
					";			
		
		if( isNotNull($content_type) )
		{
			$sql .= " AND  edoma_content.content_type='".$content_type."'  ";
		}
		
		
	

		if( $condition != NULL )
		{
			$sql .= " AND ( ".$condition." ) ";
		}

		if( $is_frontend )
		{
			$sql .= " AND  ".$this->eSQL("edoma_content")."  AND (edoma_content.parent_sn IS NULL OR ".$this->eSQL("parent").")  ";
		}


		$sql .= $this->getSortSQL( $sort );
			
		$sql .= $this->getLimitSQL( $rows , $page );


		$res = $this->readQuery( $sql );	
		
		//page info
		//------------------------------------------
		$page_count=0;
		$pre_page=NULL;
		$next_page=NULL;
		//算出總頁數
		if(isNotNull($page))
		{
			$page_count=floor($this->getRowsCount()/$rows);
			if($this->getRowsCount()%$rows>0)
			{
				$page_count+=1;
			}
			
			if($page>1)
			{
				$pre_page=$page-1;
			}
			
			if($page<$page_count)
			{
				$next_page=$page+1;
			}			
		}
		//------------------------------------------
		


		$data = array
		(
			"sql" => $sql ,
			"data" => $res ,
			"count" => $this->getRowsCount(),
			"pageInfo"=>array("pageCount"=> $page_count,
								"pre_page"=>$pre_page,
								"next_page"=>$next_page)	
		);		


		return $data;
	}
	
	
	
	
	
	
	public function keyword($keyword = "")
	{
		$keyword_string = "";
		if(isNotNull($keyword))
		{
			$keyword_string = " edoma_content.title like '%".$keyword."%' AND edoma_content.content like '%".$keyword."%' ";
		}		
		
		return $keyword_string;
	}
	
	
	
	public function GetGalleryList( $condition = NULL , $is_frontend = FALSE, $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							gallery.*,gallery_category.title as category_title
					FROM 	gallery
					LEFT JOIN gallery_category on gallery.gallery_category_sn = gallery_category.sn
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
	
}