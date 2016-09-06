<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pager Setting
|--------------------------------------------------------------------------
|
| Set the default pager 
|
*/



$CI	=& get_instance();

$language_value = "zh-tw";

date_default_timezone_set('Asia/Taipei');



$config['admin_folder'] = '/backend';

$CI->lang->load('common', $language_value);

$config['pager']['per_page_rows'] = 10; //每頁筆數

$config['enable_box_cache'] = TRUE; //開啟box cache




$config['max_size'] = "200";


//前後台路徑
$config['backend_name'] = "backend";
$config['frontend_name'] = "frontend";
$config['template_backend_path']="template/backend/";
$config['template_frontend_path']="template/frontend/";


//upload image設定
//------------------------------------------------------
$config['image']['upload_tmp_path'] = './upload/tmp';
$config['image']['allowed_types'] = 'gif|jpg|png';
$config['image']['upload_max_size'] = '204800';

//------------------------------------------------------

//郵件設定
//------------------------------------------------------
$config['mail']['host'] = 'abcde.com.tw'; 
$config['mail']['port'] = '25';
$config['mail']['sender_mail'] = '';
$config['mail']['sender_name'] = '富網通 <it@abcde.com.tw>';
$config['mail']['charset'] = 'utf-8';
$config['mail']['encoding'] = 'base64';
$config['mail']['is_html'] = TRUE;
$config['mail']['word_wrap'] = 50;//每50自斷行
$config['mail']['template'] = 
"<!doctype html>
<html lang='en'>
	<head>
	<meta charset='UTF-8'>
	<title>信函</title>
	<style type='text/css'>
		* {font-family: '微軟正黑體', Verdana; font-size:16px; line-height:180%%}
		#main {min-height: 260px }
		#header {height: 120px }
		#content {min-height: 300px }
		#footer {height: 160px }
		hr.style-one {
		border: 0;
		height: 1px;
		background: #333;
		background-image: linear-gradient(to right, #ccc, #333, #ccc);
		}
		.strong {font-weight: bold; font-size:20px; color:#00c}
	</style>
	</head>
<body>
<div id='main'>
	<div id='header'>
	<img src='http://web.chupei.com.tw/template/frontend/images/img_logo.png' width='280'>
	</div>
	<div id='content'>
	%s
	</div>
	<div id='footer'>
	<hr class='style-one'>
	<span style='color: #b0b0b0'>此信件是由系統發出，請勿直接回信；若有任何問題，可洽詢各單位秘書或資訊室人員，感謝您。</span>
	</div>
</div>
</body>
</html>";

//------------------------------------------------------



//外網權限功能(1:開啟,2:關閉)
$config['web_access_enable'] = 0;

// 角色
$config['role_array'] = array(  'I' => '住　戶'
								, 'M' => '物業人員'
								//, 'G' => '警衛'
								//, 'S' => '秘書'
								, 'F' => '富網通'
								);
// 性別
$config['gender_array'] = array(  1 => '先生'
								, 2 => '女士'
								);

// 性別
$config['yes_no_array'] = array( 0=>'否', 1=>'是');
// 性別
$config['yes_no_array2'] = array( 0=>'沒有', 1=>'有');
// 性別
$config['gender_array2'] = array('m'=>'男性','f'=>'女性', 'a'=>'男女不拘');
// 車位
$config['parking_array'] = array( 0=>'沒有', 1=>'有-平面式', 2=>'有-機械式');

// 家具
$config['furniture_array'] = array( array('value'=>'a', 'title'=>'床')
								, array('value'=>'b', 'title'=>'衣櫃')
								, array('value'=>'c', 'title'=>'沙發')
								, array('value'=>'d', 'title'=>'桌子')
								, array('value'=>'e', 'title'=>'椅子')
								);
// 家電
$config['electric_array'] = array( array('value'=>'a', 'title'=>'電視')
								, array('value'=>'b', 'title'=>'冰箱')
								, array('value'=>'c', 'title'=>'第四台')
								, array('value'=>'d', 'title'=>'網路')
								, array('value'=>'e', 'title'=>'洗衣機')
								, array('value'=>'f', 'title'=>'熱水器')
								, array('value'=>'g', 'title'=>'天然瓦斯')
								);

// 物件類型
$config['house_type_array'] = array( 'a'=>'電梯大樓'
								, 'b'=>'透天厝'
								, 'c'=>'華廈'
								, 'd'=>'公寓'
								, 'e'=>'別墅'
								, 'f'=>'車位'
								);
// 型態
$config['rent_sale_type_array'] = array( 'a'=>'整層住家'
								, 'b'=>'獨立套房'
								, 'c'=>'分租套房'
								, 'd'=>'雅房'
								, 'e'=>'店面'
								, 'f'=>'辦公'
								);

// 型態
$config['house_direction_array'] = array( 'a'=>'坐東朝西'
								, 'b'=>'坐南朝北'
								, 'c'=>'坐西朝東'
								, 'd'=>'坐北朝南'
								, 'e'=>'坐東南朝西北'
								, 'f'=>'坐西南朝東北'
								, 'g'=>'坐西北朝東南'
								, 'h'=>'坐東北朝西南'
								);
											
//檔案類型
$config['mail_box_type'] = array(
	"A" => "掛號信",
	"B" => "包裹",
	"C" => "代收包裹",
	"D" => "送洗衣物"			
);


// api server url
$config['api_server_url'] = 'http://27.147.4.239/commapi/';


//維修範圍
$config['repair_type'] = array(
	"1" => "公共區域",
	"2" => "住家內部"		
);

//維修處理進度  0:已報修,1:已讀 /2:勘驗/3:估價/4:完工
$config['repair_status'] = array(
	"0" => "報修中",
	"1" => "已讀",	
	"2" => "勘驗",
	"3" => "估價",
	"4" => "完工"
);


//意見箱收件者  a:管委收,s:總幹事收
$config['suggestion_to_role'] = array(
	"a" => "管委收",
	"s" => "總幹事收"
);


// edoma folder path
$config['edoma_folder_path'] = 'E:/htdocs/commapi/upload/edoma/';


// claire
// $config['edoma_folder_path'] = 'C:/wamp/www/edoma/upload/edoma/';
