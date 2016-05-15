<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend_Controller {


	function __construct() 
	{
		parent::__construct();

	}

	public function index()
	{
		$this->addCss("css/index.css");
		
		$this->addJs("js/jquery-2.2.3.min.js");
		$this->addJs("js/jquery.cycle2.min.js");
		$this->addJs("js/jquery.cycle2.carousel.min.js");
		$this->addJs("js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js");
		$this->addJs("js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5");		
		
		
		$data = array();
		
		//日行一善 
		//----------------------------------------------------------------------------
		$daily_good_list = $this->c_model->GetList2( "daily_good" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$data["daily_good_list"] = $daily_good_list["data"];
		//----------------------------------------------------------------------------
		
		//社區公告
		//----------------------------------------------------------------------------
		$news_list = $this->c_model->GetList2( "news" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		$data["news_list"] = $news_list["data"];
		
		//dprint($news_list);
		//----------------------------------------------------------------------------
		
		//課程訊息 
		//--------------------------------------------------------------------
		$course_list = $this->c_model->GetList2( "course" , "" ,TRUE, 3 , 1 , array("web_menu_content.hot"=>'desc',"sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		
		$data["course_list"] = $course_list["data"];
		//--------------------------------------------------------------------	
		
		//相簿
		//-----------------------------------------
		$this->load->Model("album_model");			
		$data["album_list"] = $this->album_model->GetHomeAlbumList();
		//-----------------------------------------

		//廣告
		//-----------------------------------------
		$ad_list = $this->c_model->GetList( "ad" , "" ,TRUE, 10 , 1 , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc"));
		img_show_list($ad_list["data"],'img_filename',"ad");
		$data["ad_list"] = $ad_list["data"];
		//-----------------------------------------
		
		$houses = array();
		$condition = ' ';
		$condition = ' '. $this->it_model->getEffectedSQL('house_to_rent') ;
		$result = $this->it_model->listData('house_to_rent', $condition, 3, 0, array('sn'=>'desc'));
		
		// Check if the rents data store contains rents (in case the database result returns NULL)
		if ($result['count'] > 0) {

			$config_rent_sale_type_array = config_item('rent_sale_type_array');
			$config_house_type_array = config_item('house_type_array');
			foreach ($result['data'] as $item) {

				// 型態
				$rent_type = tryGetData('rent_type', $item, NULL);
				$item['rent_type'] = tryGetData($rent_type, $config_rent_sale_type_array, NULL);

				// 物件類型
				$house_type = tryGetData('house_type', $item, NULL);
				$item['house_type'] = tryGetData($house_type, $config_house_type_array, NULL);

				// 照片
				//$condition = 'comm_id="'.$comm_id.'" AND house_to_rent_sn='.$item['sn'];
				$condition = 'house_to_rent_sn='.$item['sn'];
				$phoresult = $this->it_model->listData('house_to_rent_photo', $condition);
				$photos = array();
				foreach ($phoresult['data'] as $photo) {
					$img = base_url('upload/website/house_to_rent/'.$item['comm_id'].'/'.$item['sn'].'/'.$photo['filename']);
					$photos[] = array('photo' => $img
									, 'title' => $photo['title'] );
				}
				$item['photos'] = $photos;
				$houses[] = $item;
			}
		}
		$data["houses"] = $houses;

		
		$this->display("homepage_view",$data);
	}		
}

