<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Frontend_Controller extends IT_Controller 
{
	public $title = "";	//標題	


	public $web_menu_list = array();
	public $web_menu_map = array();

	
	public $style_css = array();
	public $style_js = array();
	
	public $cdn_css = array();
	public $cdn_js = array();
	
	public $menu_id;
	public $menu_root_id;
	public $menu_info;
	public $web_menu_content_sn;
	public $web_menu_content_parent_sn;
	
	public $page_title_img;
	public $parent_title;
	
	
	public $page = 1;
	public $per_page_rows = 10;
	
	public $navi = array();
	public $navi_path = '';
	
	public $is_marguee = TRUE;
	public $show_header = TRUE;
	public $show_footer = TRUE;
	public $show_banner = TRUE;
	
	public $web_access = 0;
	
	
	function __construct() 
	{
		parent::__construct();
		
		/*
		//檢查是否登入		
		if(!checkUserLogin())
		{
			redirect(frontendUrl("login"));
		}		
		
		//檢查是否有前台單元權限
		if(!checkFrontendAuth())
		{
			redirect(frontendUrl("login"));
		}
		*/
		
		$this->initNavi();
		$this->initFrontend();
		$this->getParameter();
		
	}	
	
	function checkLogin()
	{		
		
		if(
			$this->session->userdata("f_user_name") !== FALSE 
			&& $this->session->userdata("f_user_sn") !== FALSE 	
			&& $this->session->userdata("f_user_id") !== FALSE
			&& $this->session->userdata("f_comm_id") !== FALSE 		
		)
		{
			
		}
		else 
		{
			
			$this->session->set_userdata('pre_login_url', base_url(uri_string()));
			
			
			//dprint($this->session->userdata);
			redirect(frontendUrl("login"));
		}
	}
	
	
	
	function initFrontend()
	{		
		$this->menu_info = $this->getMenuInfo();	
		$this->_getFrontendMenu();		
	}		
	
	
	function initNavi()
	{	
		$this->navi["首頁"] = frontendUrl();		
	}
	
	function addNavi($key,$url)
	{
		$this->navi[$key] = $url;	
	}
	
	function buildNavi()
	{
		$navi_size = count($this->navi);
		$navi_count = 0;
		foreach ($this->navi as $key => $value) 
		{
			$navi_count++;
			
			if($navi_size != $navi_count)
			{
				$this->navi_path .= '<a href="'.$value.'">'.$key.'</a>';
				$this->navi_path .= ' <div class="separator">&gt;</div> ';
				
				
			}
			else 
			{
				//$this->navi_path .= ' '.$key.'';
				$this->navi_path .= '<a class="last" >'.$key.'</a>';
			}

		}
		
		$this->navi_path = '<div id="breadcrumb">'.$this->navi_path.'</div>';
		
	}
	
	
	public function getParameter()
	{
		$this->page = $this->input->get('page',TRUE);
		//$this->per_page_rows =	$this->config->item('per_page_rows','pager');		
	}
	
	/**
	 * 回到Froentend 首頁
	 */	
	public function redirectHome()
	{
		header("Location:".base_url()."home");
	}
	
	/**
	 * 回到login頁
	 */	
	public function redirectLoginPage()
	{
		//取得預設語系		
		$condition = "is_default = 1";		
		$list = $this->language_model->GetList( $condition );		
		$list = $list["data"];	
		
		
		if( sizeof( $list ) > 0 )
		{
			
			header("Location:".getFrontendControllerUrl("member","login"));
			exit;
		}
		else
		{
			show_error('language not found');
		}
	}


	//設定跑馬燈
	public function setMarguee($is_marguee = TRUE)
	{
		$this->is_marguee = $is_marguee;
	}

	
	//設定Header區塊是否顯示
	public function displayHeader($show_header = TRUE)
	{
		$this->show_header = $show_header;
	}

	//設定Footer區塊是否顯示
	public function displayFooter($show_footer = TRUE)
	{
		$this->show_footer = $show_footer;
	}
	
	//設定banner區塊是否顯示
	public function displayBanner($show_banner = TRUE)
	{
		$this->show_banner = $show_banner;
	}

	
	protected function getMenuInfo()
	{	
		$this->menu_id = $this->uri->segment(1);				
		$menu_info = $this->it_model->listData("web_menu" , "id = '".$this->menu_id."' ");	
		
		//echo $this->uri->segment(2);
		//dprint($menu_info);
		if( sizeof($menu_info["data"])>0)
		{
			//parent info
			//-------------------------------------------------
			$parent_info = $this->it_model->listData("web_menu" , "sn = '".$menu_info["data"][0]["parent_sn"]."' ");
			
			//dprint($menu_info);
			if($parent_info["count"] > 0)
			{
				$this->page_title_img = $parent_info["data"][0]["img_filename"];	
				$this->parent_title = $parent_info["data"][0]["title"];
							
				$this->menu_root_id = $parent_info["data"][0]["id"]; 
				$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				
			}			
			//-------------------------------------------------			
			
				
			$this->module_sn = $menu_info["data"][0]["sn"];	
			
			$this->addNavi($menu_info["data"][0]["title"], fUrl("index"));
	
		  	return $menu_info["data"][0];
		}
		else 
		{
		
			
						
			return array("id"=>"","title"=>"");
		}
	}



	public function redirectPage()
	{
		$page_sn = 0;	
		$condition = "";
		
		$page_parent_list = $this->c_model->GetList( $this->router->fetch_class() , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		if($page_parent_list["count"]>0)
		{
			$condition = "parent_sn = '".$page_parent_list["data"][0]["sn"]."'";
		}
		
		$page_info = $this->c_model->GetList( $this->router->fetch_class()."_sub" , $condition ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
		//dprint($page_info);
		//exit;
		//$page_info = $this->c_model->GetList2( $this->router->fetch_class()."_sub" , "" ,FALSE, NULL , NULL , array("parent.sort"=>"asc", "web_menu_content.sort"=>"asc","sn"=>"web_menu_content.desc") );
		
		if($page_info["count"]>0)
		{
			$page_sn = $page_info["data"][0]["sn"];
		}
		else 
		{
			$page_info = $this->c_model->GetList( $this->router->fetch_class() , "" ,TRUE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
			if($page_info["count"]>0)
			{
				$page_sn = $page_info["data"][0]["sn"];
			}
			//dprint($page_info);
			//exit;
		}
		//echo $page_sn;
		header("Location:".frontendUrl($this->router->fetch_class(),"page/".$page_sn));
		exit;	
	}




	public function page($web_menu_content_sn = '')
	{	
		$data = array();
		$this->getWebMenuContentInfo($data,$web_menu_content_sn);
		
		$this->display("page_view", $data, "page");	
	}
		
	
	
	function addCss($css_value, $is_cdn = FALSE)
	{
		if($is_cdn)
		{
			array_push($this->cdn_css, $css_value);
		}
		else 
		{
			array_push($this->style_css, $css_value);
		}
				
	}
	
	function addJs($js_value, $is_cdn = FALSE)
	{
		if($is_cdn)
		{
			array_push($this->cdn_js, $js_value);
		}
		else 
		{
			array_push($this->style_js, $js_value);
		}
		
	}
	
	
	/**
	 * 組前端view所需css及js
	 */
	function _bulidJsCss(&$data = array())
	{
		$data['style_css'] = '';
		$data['style_js'] = '';
		foreach ($this->style_css as $value) 
		{
			$data['style_css'] .= '<link href="'.base_url().$this->config->item("template_frontend_path").$value.'" rel="stylesheet" type="text/css" />';    	
		}
		
		foreach ($this->cdn_css as $value) 
		{
			$data['style_css'] .= '<link href="'.$value.'" rel="stylesheet" type="text/css" />';    	
		}
		
		
		foreach ($this->style_js as $value) 
		{
			$data['style_js'] .= '<script type="text/javascript" src="'.base_url().$this->config->item("template_frontend_path").$value.'"></script>';
		}
		
		foreach ($this->cdn_js as $value) 
		{
			$data['style_js'] .= '<script  src="'.$value.'"></script>';
		}
	}
	
	
	public function getWebMenuContentInfo(&$data = array(),$web_menu_content_sn = '')
	{	
		
        $condition = "";
		if(isNotNull($web_menu_content_sn))
		{
			$condition = "sn='".$web_menu_content_sn."'";
		}
		
		$page_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );	
			
		if($page_info["count"]>0)
		{
			if(isNull($page_info["data"][0]["parent_sn"]))
			{
			
				$this->setSubTitle($page_info["data"][0]["title"]);
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
			}
			else 
			{
				
				$parent_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
				
				$parent_info = $this->it_model->listData("web_menu_content" , "sn = '".$page_info["data"][0]["parent_sn"]."' ");
				if($parent_info["count"]>0)
				{
					$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				}	
				
				
				$this->setSubTitle("《".$page_info["data"][0]["title"]."》");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
				$this->web_menu_content_parent_sn = $page_info["data"][0]["parent_sn"];
			}
			
			
			$data["html_page"] = $page_info["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}	

		return $data;		
	}
	
	
	public function getWebMenuContentInfoById(&$data = array(),$web_menu_content_id = '')
	{	
		
        $condition = "";
		if(isNotNull($web_menu_content_id))
		{
			$condition = "content_type='".$web_menu_content_id."'";
		}
		
		$page_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );	
			
		//dprint($page_info);
		//exit;
		
		if($page_info["count"]>0)
		{
			if(isNull($page_info["data"][0]["parent_sn"]))
			{
			
				$this->setSubTitle("[".$page_info["data"][0]["title"]."]");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
			}
			else 
			{
				
				$parent_info = $this->c_model->GetList( "" , $condition ,FALSE, NULL , NULL , array("sort"=>"asc","sn"=>"desc") );
				
				$parent_info = $this->it_model->listData("web_menu_content" , "sn = '".$page_info["data"][0]["parent_sn"]."' ");
				if($parent_info["count"]>0)
				{
					$this->addNavi($parent_info["data"][0]["title"], fUrl("index"));
				}	
				
				
				$this->setSubTitle("《".$page_info["data"][0]["title"]."》");
				$this->addNavi($page_info["data"][0]["title"], fUrl("index"));
				$this->web_menu_content_sn = $page_info["data"][0]["sn"];
				$this->web_menu_content_parent_sn = $page_info["data"][0]["parent_sn"];
			}
			
			
			$data["html_page"] = $page_info["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}	

		return $data;		
	}

	/**
	 * 取得Html page info
	 */
	public function getHtmlPageInfo(&$data = array(),$page_id = '')
	{	
		
        //$this->addJs("js/string.js");     
		
		$page_list = $this->it_model->listData( "html_page" , "page_id  = '".$page_id."' and ".$this->it_model->eSql('html_page'));
			

		if($page_list["count"]>0)
		{
			$data["html_page"] = $page_list["data"][0];
		}
		else 
		{
			header("Location:".frontendUrl());
			exit;
		}		
		return $data;		
	}
	
	
		
	
	private function _getFrontendMenu()
	{
			
		$sort = array
		(			
			"sort" => "asc" 
		);		
		
		$condition = "";
		if($this->web_access == 1 && $this->config->item("web_access_enable") == 1)
		{
			$condition = " AND allow_internet = 1";
		}
		
		$l1_list = $this->it_model->listData("web_menu","level=1 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);
		$this->web_menu_list = $l1_list["data"];
		
		$l2_list = $this->it_model->listData("web_menu","level=2 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);	
		//dprint($l2_list);
		foreach ($l2_list["data"] as $item) 
		{
			$this->web_menu_map[$item["parent_sn"]]["item_list"][]=$item;
		}
		
		$l3_list = $this->it_model->listData("web_menu","level=3 AND (launch=1 or launch=2 or launch=3) ".$condition,NULL,NULL,$sort);
		foreach ($l3_list["data"] as $item) 
		{
			$this->web_menu_map[$item["parent_sn"]]["item_list"][]=$item;
		}		
	}
	
		
	
	

	
	function loadWebSetting()
	{
		$setting_list = $this->it_model->listData("web_setting","launch = 1");
		
		$setting_info = array();
		foreach ($setting_list["data"] as $key => $item) {
			$setting_info[$item["key"]] = $item["value"];
		}
		
		return $setting_info;	
	}
	
	/**
	 * header區最新管委公告
	 */
	function _getLatestBulletin()
	{		
		$bulletin_info = $this->c_model->GetList2( "bulletin" , "" ,TRUE, 1 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		if($bulletin_info["count"]>0)
		{
			$bulletin_info = $bulletin_info["data"][0];
		}
		else 
		{
			$bulletin_info = array();
		}
		
		return $bulletin_info;
	}



	
	function _commonArea($view="",&$data = array())
	{
		//$data["top_p_cat_list"] = $this->_getTopProductCategory();

		## 暫時以判斷目前的 Class && Method 方式來決定左側選單 - by Claire
		$data['current_class'] = $this->router->fetch_class();
		$data['current_method'] = $this->router->fetch_method();
		
		
		$data['web_menu_list'] = $this->web_menu_list;
		$data['web_menu_map'] = $this->web_menu_map;

				
		//前台單元權限
		$data['frontend_auth'] = $this->session->userdata('frontend_auth');
				

		
		
		//dprint($data['frontend_auth']);
		
		$data['menu_info'] = $this->menu_info;
		$data['menu_id'] = $this->menu_id;
		$data['menu_root_id'] = $this->menu_root_id;
		if(!key_exists("web_menu_content_sn", $data))
		{
			$data['web_menu_content_sn'] = $this->web_menu_content_sn;	
		}
		
		$data['show_message'] =$this->session->flashdata('show_message');
		
		//dprint($data);
		
		$data['web_menu_content_parent_sn'] = $this->web_menu_content_parent_sn;
		$data['page_title_img'] = $this->page_title_img;
		$data['parent_title'] = $this->parent_title;
		
		
		
		$data['webSetting'] = $this->loadWebSetting();
		
		$data['templateUrl'] = $this->config->item("template_frontend_path");
		

		$data['latest_bulletin_info'] = $this->_getLatestBulletin();

		$data['show_header'] = $this->show_header;
		$data['show_footer'] = $this->show_footer;
		$data['show_banner'] = $this->show_banner;
		
		$data['header'] = $this->load->view('frontend/template_header_view', $data, TRUE);
		//$data['left_menu'] = $this->load->view('frontend/template_left_view', $data, TRUE);
		
		$data['content_js'] = '';
		if(file_exists(APPPATH.'views/'.$view.'_js.php'))
		{
			$data['content_js'] = $this->load->view($view.'_js', $data, TRUE);
		}		
		$data['footer'] = $this->load->view('frontend/template_footer_view', $data, TRUE);
		
		//麵包屑
		$this->buildNavi();
		$data['navi_path'] = $this->navi_path;
				
		
		
		//dprint($data['frontend_auth']);
		
		$this->_bulidJsCss($data);	
		return $data;	
	}
	

	/**
	 * output view
	 */
	function display($view, $data = array())
	{
		if(isNotNull(tryGetData("view_folder", $data)))
		{
			$view = "frontend/".tryGetData("view_folder", $data)."/".$view;
		}
		else 
		{
			$view = "frontend/".$this->router->fetch_class()."/".$view;	
		}
			
		$this->_commonArea($view,$data);

		$data['content'] = $this->load->view($view, $data, TRUE);
		
		//dprint($data);
		
		// 讓瀏覽器不快取
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
		$this->output->set_header('Pragma: no-cache');

		return $this->load->view('frontend/template_index_view', $data);
	}
	

	
	
	
	
	/**
	 * delete item
	 * @param	string : launch table
	 * @param	string : redirect action
	 * 
	 */
	public function deleteItem($launch_str_table,$redirect_action)
	{
		$del_ary = $this->input->post('del',TRUE);
		if($del_ary!= FALSE && count($del_ary)>0)
		{
			foreach ($del_ary as $item_sn)
			{
				$this->it_model->deleteData( $launch_str_table , array("sn"=>$item_sn) );	
			}
		}		
		$this->showSuccessMessage();
		redirect(getBackendUrl($redirect_action, FALSE));	
	}
	
	
	
	
	/**
	 * 分頁
	 */	
	public function getPager($total_count,$cur_page,$per_page,$redirect_action)
	{
		$config['total_rows'] = $total_count;
		$config['cur_page'] = $cur_page;
		$config['per_page'] = $per_page;		
		
		$this->pagination->initialize($config);
		$pager = $this->pagination->create_links();		
		$pager['action'] = $redirect_action;
		$pager['per_page_rows'] = $per_page;
		$pager['total_rows'] = $total_count;		
		//$offset = $this->pagination->offset;
		//$per_page = $this->pagination->per_page;
				
		return $pager;	
	} 
	
	

	
	
	public function showMessage($message = '')
	{
		$this->session->set_flashdata('show_message',$message);
	}


	
	
	
	
	function loadElfinder()
	{
	  $this->load->helper('path');
	  $opts = array(
	    'debug' => true, 
	    'roots' => array(
	      array( 
	        'driver' => 'LocalFileSystem', 
	        'path'   => set_realpath('upload'), 
	        'URL'    => site_url('upload').'/'
	        // more elFinder options here
	      ) 
	    )
	  );
	  $this->load->library('elfinderlib', $opts);
	}
	
	
	
	
		/**
	 * 登出
	 */
	public function logout()
	{

		$this->session->unset_userdata('f_user_name');
		$this->session->unset_userdata('f_user_sn');
		$this->session->unset_userdata('f_user_id');
		$this->session->unset_userdata('f_user_app_id');
		$this->session->unset_userdata('f_comm_id');
		$this->session->unset_userdata('f_building_id');
		
		$this->redirectHome();
	}	
	
	/**
	 * 取得社區id
	 */
	function getCommId()
	{
		$comm_id = $this->session->userdata("f_comm_id");
		return $comm_id;
	}
	
	function speed()
	{
		$this->output->enable_profiler(TRUE);	
	}
	
	
	
	/**
	 * 同步至雲端server
	 */
	function sync_item_to_server($post_data,$func_name,$table_name)
	{
		$url = $this->config->item("api_server_url")."sync/".$func_name;
		
		//dprint($post_data);
		//exit;
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
		
		$this->it_model->updateData( $table_name , array("is_sync"=>$is_sync,"updated"=>date("Y-m-d H:i:s")), "sn =".$post_data["sn"] );
		//------------------------------------------------------------------------------
	}

}
