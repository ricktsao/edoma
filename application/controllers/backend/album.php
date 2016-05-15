<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends Backend_Controller {
	
	public $level_limit=2;
	public $path="upload/product";
	
	function __construct() 
	{
		parent::__construct();	
		$this->load->Model("album_model");			
	}

	
	/**
	 * album list page
	 */
	public function contentList()
	{
		$this->sub_title = "相簿";	
	
		//---------------------------------------------------------------------
	
		
		$list = $this->it_model->listData( "album" , "is_del=0" , $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"desc") );
		
		$data["list"] = $list["data"];
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"contentList");	
		$this->display("album_list_view",$data);
	}
	
	/**
	 * album edit page
	 */
	public function editContent($album_sn="")
	{	
		
				
		$album_sn =  tryGetArrayValue("sn",$_GET,"");
				
			//dprint($cat_list);	
		if($album_sn == "")
		{
			$data["edit_data"] = array
			(
				'sort' =>500,
				'start_date' => date( "Y-m-d" ),
				'forever' => 1,
				'launch' =>1,
				'title'=>null
			);
			$this->display("album_form_view",$data);
		}
		else 
		{
			$album_info = $this->it_model->listData("album","sn =".$album_sn);				
		
			if(count($album_info["data"])>0)
			{
				//img_show_list($album_info["data"],'img_filename','album');	
			
				$data["edit_data"] = $album_info["data"][0];			
				$this->display("album_form_view",$data);
			}
			else
			{
				redirect(bUrl("albumList"));	
			}
		}
	}
	
	/**
	 * 更新album
	 */
	public function updateContent()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["description"] = $this->input->post("description");	
						
		if ( ! $this->_validateContent())
		{
			//$cat_list = $this->it_model->listData( "album_category" , NULL , NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
			//$data["cat_list"] = $cat_list["data"];	
			$data["edit_data"] = $edit_data;		
			$this->display("album_form_view",$data);
		}
        else 
        {		
        	$arr_data = array
        	(				
        		"title" => tryGetValue($edit_data["title"])  				
				, "description" => tryGetArrayValue("description",$edit_data)	
        		, "sort" => tryGetArrayValue("sort",$edit_data,500)	
				, "launch" => tryGetArrayValue("launch",$edit_data,0)	
				, "start_date" => tryGetArrayValue("start_date",$edit_data)					
				, "update_date" =>  date( "Y-m-d H:i:s" )
			);        	
			
			
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "album" , $arr_data, "sn =".$edit_data["sn"] ))
				{	
					$sync_result = $this->album_model->sync_to_server($edit_data,"sync_album/updateContent");
					$this->it_model->updateData( "album" , array("is_sync"=>$sync_result), "sn =".$edit_data["sn"] );			
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
			}
			else 
			{
									
				$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
				
				$album_sn = $this->it_model->addData( "album" , $arr_data );
				if($album_sn > 0)
				{				
					$edit_data["sn"] = $album_sn;
					$sync_result = $this->album_model->sync_to_server($edit_data,"sync_album/updateContent");
					$this->it_model->updateData( "album" , array("is_sync"=>$sync_result), "sn =".$edit_data["sn"] );
					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}
	
			}
			
			//圖片刪除 img_filename
		//	del_img($edit_data,"img_filename","album");
			
			redirect(bUrl("ContentList"));	
        }	
	}
	

	/**
	 * 驗證album edit 欄位是否正確
	 */
	function _validateContent()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');	
			
		$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		//$this->form_validation->set_rules( 'album_category_sn', '作品賞析分類', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}

	/**
	 * delete album
	 */
	function deleteContent()
	{
		
		$del = $this->input->post('del',TRUE);		
		$del = implode(",",$del);
	
		if($del!= FALSE )
		{
			//$this->it_model->deleteDB( "voting",NULL,$del_ary );
			$this->it_model->updateData( "album" , array("is_del"=>1,"is_sync"=>0), "sn in (".$del.")" );			
			$re_sync = $this->album_model->sync_to_server(array("sn"=>$del),"sync_album/removeAlbum");
			if($re_sync=="1"){
				$this->it_model->updateData( "album" , array("is_sync"=>1), "sn in (".$del.")" );
			}
		
		}
		$this->showSuccessMessage();
		redirect(bUrl("contentList", FALSE));	

	}

	
	
	/**
	 * item list page
	 */
	public function itemList()
	{	

		array_push($this->style_js, "js/jquery.liteuploader.min.js");

		//album list
		//---------------------------------------------------------------------
		$album_sn = $this->input->get("album_sn",TRUE);	
		$album_list = $this->it_model->listData( "album" , NULL, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );

		
		//dprint($album_list["data"]);
		
		$data["album_list"] = $album_list["data"];
		if($album_sn == FALSE && $album_list["count"] > 0)
		{
			$album_sn = $album_list["data"][0]["sn"];
		}
		$data["album_sn"] = $album_sn;
		//---------------------------------------------------------------------
	
		//album info
		//---------------------------------------------------------------------			
		$album_info = $this->it_model->listData("album","sn =".$album_sn);
		if(count($album_info["data"])>0)
		{
			img_show_list($album_info["data"],'img_filename','album');				
			$album_info = $album_info["data"][0];
		}
		else
		{
			redirect(bUrl("albumList"));	
		}
		//---------------------------------------------------------------------
		
		
		$data["album_sn"] = $album_sn;
		
		$this->sub_title = "作品賞析[".$album_info["title"]."] -> 相片列表";	
		
		$list = $this->it_model->listData( "album_item" , "album_sn ='".$album_sn."' and is_del = 0" , $this->per_page_rows , $this->page , array("sort"=>"asc","sn"=>"desc") );
		
		if($this->page > 1 && $list["count"] == 0)
		{						
			$page = $this->page - 1;
			redirect(bUrl("itemList?cat_sn=".$cat_sn."&album_sn=".$album_sn."&page=".$page, FALSE));
		}
		
		img_show_list($list["data"],'img_filename','album','s_');
		$data["list"] = $list["data"];		
		
		
		//dprint($data);
		//取得分頁
		$data["pager"] = $this->getPager($list["count"],$this->page,$this->per_page_rows,"itemList");	
		$this->display("item_list_view",$data);
	}
	
	
	public function uploadAlbumItem()
	{
		$urls = array();
		$message = 'fail';
	
		if (isset($_POST['liteUploader_id']) && $_POST['liteUploader_id'] == 'fileUpload2')
		{
		//echo '<br>-->2';
			$folder_name = $this->router->fetch_class();

			foreach ($_FILES['fileUpload2']['error'] as $key => $error)
			{
				if ($error == UPLOAD_ERR_OK)
				{					
					
					$arr_data = array
					(				
						"title" => ''  
						,"album_sn" => $this->input->get("album_sn",TRUE) 						
						, "sort" => 500	
						, "launch" => 1
						, "update_date" =>  date( "Y-m-d H:i:s" )
					);    
					
					//dprint($arr_data);
					
					//圖片處理 img_filename				
					$img_config['resize_setting'] =array($folder_name=>array(1024,1024));					
					$uploadedUrl = './upload/tmp/' . $_FILES['fileUpload2']['name'][$key];
					move_uploaded_file( $_FILES['fileUpload2']['tmp_name'][$key], $uploadedUrl);
					
					$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
					$arr_data['img_filename'] = $img_filename;	
					//社區同步資料夾
					$img_config['resize_setting'] =array($folder_name=>array(500,500));
					resize_img($uploadedUrl,$img_config['resize_setting'],$this->getCommId(),$img_filename);
					
					@unlink($uploadedUrl);	

					$gallery_sn = $this->it_model->addData( "album_item" , $arr_data );
					$arr_data['sn'] = $gallery_sn;
					$sync_result = $this->album_model->sync_to_server($arr_data,"sync_album/updatePhoto");
					$this->it_model->updateData( "album_item" , array("is_sync"=>$sync_result), "sn = ".$gallery_sn);
					$this->sync_file($folder_name);
				}
			}

			$message = 'Successfully Uploaded File(s) From Second Upload Input';
		}

		echo json_encode(
			array(
				'message' => $message
				//,'urls' => $urls,
			)
		);
		exit;
	
	}
	
	
	/**
	 * item edit page
	 */
	public function editItem()
	{			
	
		//album info
		//---------------------------------------------------------------------

		
		$album_sn = $this->input->get("album_sn",TRUE);		
		$album_info = $this->it_model->listData("album","sn =".$album_sn);				
			
		if(count($album_info["data"])>0)
		{
			img_show_list($album_info["data"],'img_filename','album');				
			$album_info = $album_info["data"][0];
		}
		else
		{
			redirect(bUrl("albumList"));	
		}
		
		//---------------------------------------------------------------------
		
		$this->sub_title = "作品賞析[".$album_info["title"]."] -> 圖片編輯";		
		$item_sn = $this->input->get("sn",TRUE);
		$data["album_sn"] = $album_sn;		
				
		
		if(isNull($item_sn))
		{

			$data["edit_data"] = array
			(
				'sort' =>500,
				'title' => NULL
			);
			$this->display("item_form_view",$data);
		}
		else 
		{
			$album_info = $this->it_model->listData("album_item","sn =".$item_sn);
			if(count($album_info["data"])>0)
			{
				$data["edit_data"] = $album_info["data"][0];
				$data["edit_data"]["orig_img_filename"] = $data["edit_data"]["img_filename"];
				$data["edit_data"]["img_filename"] = isNotNull($data["edit_data"]["img_filename"])?base_url()."upload/website/album/".$data["edit_data"]["img_filename"]:"";	



				$this->display("item_form_view",$data);
			}
			else
			{
				redirect(bUrl("itemList"));	
			}
		}
	}
	
	/**
	 * 更新album
	 */
	public function updateItem()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
				
		if ( ! $this->_validateItem())
		{
			$data["edit_data"] = $edit_data;		
			$this->display("item_form_view",$data);
		}
        else 
        {		
        	$arr_data = array
        	(				
        		"title" => tryGetValue($edit_data["title"])    		
        		, "album_sn" => tryGetArrayValue("album_sn",$edit_data)	
        		, "sort" => tryGetArrayValue("sort",$edit_data,500)
			
			);        	
			
			if(isNotNull(tryGetData("del_img_filename",$edit_data)) && tryGetData("del_img_filename",$edit_data) == "1")
			{
				$arr_data["img_filename"] = "";
			}
			
			
		
			if(isNotNull($_FILES['img_filename']['name']))
			{
				

					$folder_name = $this->router->fetch_class();

					//圖片處理 img_filename				
					$img_config['resize_setting'] =array($folder_name=>array(1024,1024));					
					$uploadedUrl = './upload/tmp/' . $_FILES['img_filename']['name'];
					move_uploaded_file( $_FILES['img_filename']['tmp_name'], $uploadedUrl);
					
					$img_filename = resize_img($uploadedUrl,$img_config['resize_setting']);					
					$arr_data['img_filename'] = $img_filename;	
					//社區同步資料夾
					$img_config['resize_setting'] =array($folder_name=>array(500,500));
					resize_img($uploadedUrl,$img_config['resize_setting'],$this->getCommId(),$img_filename);
					
					@unlink($uploadedUrl);	
					$this->sync_file($folder_name);
					
			
			}
			
			if(isNotNull($edit_data["sn"]))
			{
				if($this->it_model->updateData( "album_item" , $arr_data, "sn =".$edit_data["sn"] ))
				{	
					$arr_data['sn'] = $edit_data["sn"];
					$sync_result = $this->album_model->sync_to_server($arr_data,"sync_album/updatePhoto");
					$this->it_model->updateData( "album_item" , array("is_sync"=>$sync_result), "sn =".$arr_data["sn"] );	
					
					$this->showSuccessMessage();					
				}
				else 
				{
					$this->showFailMessage();
				}
				
			}
			else 
			{									
				//$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
				
				$item_sn = $this->it_model->addData( "album_item" , $arr_data );
				if($item_sn > 0)
				{				
					$arr_data["sn"] = $item_sn;
					$sync_result = $this->album_model->sync_to_server($arr_data,"sync_album/updatePhoto");
					$this->it_model->updateData( "album_item" , array("is_sync"=>$sync_result), "sn =".$arr_data["sn"] );	

					$this->showSuccessMessage();							
				}
				else 
				{
					$this->showFailMessage();					
				}	
			}
			
			
			//圖片刪除 img_filename
			//del_img($edit_data,"img_filename","album");

			
			redirect(bUrl("itemList"));	
        }	
	}
	

	/**
	 * 驗證album edit 欄位是否正確
	 */
	function _validateItem()
	{
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			
		//$this->form_validation->set_rules( 'title', '名稱', 'required' );	
		$this->form_validation->set_rules('sort', '排序', 'trim|required|numeric|min_length[1]');			
		
		return ($this->form_validation->run() == FALSE) ? FALSE : TRUE;
	}

	/**
	 * delete album
	 */
	function delItem()
	{

		$del = $this->input->post('del',TRUE);		
		$del = implode(",",$del);
		$album_sn = $this->input->post("album_sn",TRUE);
		
		if($del!= FALSE )
		{
			//$this->it_model->deleteDB( "voting",NULL,$del_ary );
			$this->it_model->updateData( "album_item" , array("is_del"=>1,"is_sync"=>0), "sn in (".$del.")" );			
			$re_sync = $this->album_model->sync_to_server(array("sn"=>$del),"sync_album/removeAlbumItem");
			if($re_sync=="1"){
				$this->it_model->updateData( "album_item" , array("is_sync"=>1), "sn in (".$del.")" );
			}
		
		}
		$this->showSuccessMessage();
		redirect(bUrl("itemList?&album_sn=".$album_sn, FALSE));		
	}

	/**
	 * launch album
	 */
	function launchItem()
	{
		$album_sn = $this->input->post("album_sn",TRUE);
		$this->launchItem("album_item","itemList?album_sn=".$album_sn);
	}
	
	
	
	public function GenerateTopMenu()
	{		
	
		$this->addTopMenu(array("contentList","editContent","updateContent"));
		//$this->addTopMenu("相片列表",array("itemList","editItem","updateItem"));				
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */