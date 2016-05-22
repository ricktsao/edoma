<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

	 function mask( $str, $start = 0, $length = null ) {
		$mask = preg_replace ( "/\S/", "*", $str );
		if( is_null ( $length )) {
			$mask = substr ( $mask, $start );
			$str = substr_replace ( $str, $mask, $start );
		}else{
			$mask = substr ( $mask, $start, $length );
			$str = substr_replace ( $str, $mask, $start, $length );
		}
		return $str;
	}


	## 將戶別代號轉成文字
	function building_id_to_text($building_id, $separate=false)
	{
		// Get the CodeIgniter super object
		$CI =& get_instance();
		$building_id_to_text = '';
		if ( isNotNull($building_id) ) {
			$building_id_parts = explode('_', $building_id);

			if ($separate == false) {
				$building_id_to_text = $CI->building_part_01.' '.tryGetData($building_id_parts[0], $CI->building_part_01_array);
				$building_id_to_text .= '&nbsp;&nbsp;'.$CI->building_part_02.' '.tryGetData($building_id_parts[1], $CI->building_part_02_array);
				$building_id_to_text .= '&nbsp;&nbsp;'.$CI->building_part_03.' '.$building_id_parts[2];
		
				return $building_id_to_text;

			} else {
				$parts = array(tryGetData($building_id_parts[0], $CI->building_part_01_array)
								, tryGetData($building_id_parts[1], $CI->building_part_02_array)
								, $building_id_parts[2] );
				return $parts;
			}
		}
		return false;
	}

	## 將車位代號轉成文字
	function parking_id_to_text($parking_id, $separate=false)
	{
		// Get the CodeIgniter super object
		$CI =& get_instance();
		$parking_id_to_text = '';
		if ( isNotNull($parking_id) ) {
			$parking_id_parts = explode('_', $parking_id);

			if ($separate == false) {
				$parking_id_to_text = $CI->parking_part_01.' '.tryGetData($parking_id_parts[0], $CI->parking_part_01_array);
				$parking_id_to_text .= '&nbsp;&nbsp;'.$CI->parking_part_02.' '.tryGetData($parking_id_parts[1], $CI->parking_part_02_array);
				$parking_id_to_text .= '&nbsp;&nbsp;'.$CI->parking_part_03.' '.$parking_id_parts[2];
				
				return $parking_id_to_text;
			} else {

				$parts = array(tryGetData($parking_id_parts[0], $CI->parking_part_01_array)
								, tryGetData($parking_id_parts[1], $CI->parking_part_02_array)
								, $parking_id_parts[2] );
				return $parts;
			}
		}
		return false;
	}


	function image_thumb($folder_name, $image_name, $width, $height)
	{
		// Get the CodeIgniter super object
		$CI =& get_instance();
	 
		// Path to image thumbnail
		//$image_thumb = dirname('upload/' . $folder_name . '/' . $image_name) . '/' . $image_name . '_' . $width . '_' . $height . strrchr($image_name, '.');
		$image_thumb = dirname($folder_name . '/' . $image_name);
		 
		if( ! file_exists($image_thumb))
		{
			// LOAD LIBRARY
			$CI->load->library('image_lib');
	 
			// CONFIGURE IMAGE LIBRARY
			$config['image_library']    = 'gd2';
			$config['source_image']     = $folder_name. '/' . $image_name;
			$config['new_image']        = $image_thumb;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio']   = TRUE;
			$config['height']           = $height;
			$config['width']            = $width;
			$CI->image_lib->initialize($config);
		//	$CI->image_lib->resize();
			if (!$CI->image_lib->resize()) {
				echo '@111 '.$image_thumb;
				echo '@222 '.$folder_name. '/' . $image_name;
				echo '@333 '.$CI->image_lib->display_errors();die;
			}
			$CI->image_lib->clear();echo 'thumb okkkkkkkkkkkkkkk';

		}
	 
		// return '<img src="' . dirname($_SERVER['SCRIPT_NAME']) . '/' . $image_thumb . '" />';
	}
	
	/*
	 * 紀錄log
	 * $desc:log描述
	 */
	function logData($desc="",$action = 1)
	{
		/*
		$table_name = tryAddLogTable("frontend");
			
		$date = new DateTime();

		$CI =& get_instance();

		$module_id = $CI->uri->segment(1);
		if ($module_id == 'backend') {
			$module_id = $CI->uri->segment(2);
		}

		$arr_data = array(				
			 "user_id" => $CI->session->userdata("user_id")
			, "session_id"=> $CI->session->userdata('session_id')
			, "ip"=> $CI->input->ip_address()
			, "module_id" =>  $module_id
			, "desc" => $desc
			, "action" => $action
			, "active_time" =>  $date->getTimestamp()	
			, "create_date" =>  date( "Y-m-d H:i:s" )
		);
		$sys_user_sn = $CI->db->insert( $table_name , $arr_data );
		*/
	}


	function tryAddLogTable($comefrom="backend")
	{
		$table_name = "sys_".$comefrom."_log_".date( "Y" );

		$CI =& get_instance();

		if( ! $CI->db->table_exists($table_name))
		{
			$sql = "CREATE TABLE `".$table_name."` (
					`sn` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
					`session_id` VARCHAR(40) NULL DEFAULT NULL,
					`user_id` VARCHAR(6) NULL DEFAULT NULL,
					`ip` VARCHAR(50) NOT NULL,
					`module_id` VARCHAR(50) NOT NULL,
					`desc` VARCHAR(500) NOT NULL,
					`action` TINYINT(1) NULL DEFAULT '0' COMMENT '0:模組停留狀況,1:動作',
					`stay_time` SMALLINT(6) NULL DEFAULT '0',
					`active_time` INT(11) NOT NULL,
					`create_date` DATETIME NOT NULL,
					PRIMARY KEY (`sn`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=MyISAM
				;
				";
				$CI->db->query($sql);
		} 
		
		
		return $table_name;
		
		
	}
	
	function current_full_url()
	{
		$CI =& get_instance();

		$url = $CI->config->site_url($CI->uri->uri_string());
		return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
	}


	/**
	 * 發送email
	 */
    function send_email($to='', $subject, $content, $sender_name=null, $sender_mail=null) 
    {
        if(preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/', $to) == FALSE)
        {
            return;
        }
        
        $CI =& get_instance();
		logData('寄信：'.$subject.' 給 '.$to);

        //清除上次寄件者
        $CI->phpmailer->ClearAddresses();
        
        //設定使用SMTP發送
        $CI->phpmailer->IsSMTP();

        //指定SMTP的服務器位址
        $CI->phpmailer->Host = $CI->config->item('host','mail');
        //設定SMTP服務的POST
        $CI->phpmailer->Port = $CI->config->item('port','mail');


        //寄件人名稱
		if (isNull($sender_name)) {
			$sender_name = $CI->config->item('sender_name','mail');
		}
        $CI->phpmailer->FromName = $sender_name;

        //寄件人Email
		if (isNull($sender_mail)) {
			$sender_mail = $CI->config->item('sender_mail','mail');
		}
        $CI->phpmailer->From = $sender_mail;

        //收件人Email
        $CI->phpmailer->AddAddress($to);


        //設定信件字元編碼
        $CI->phpmailer->CharSet = $CI->config->item('charset','mail');
        //設定信件編碼，大部分郵件工具都支援此編碼方式
        $CI->phpmailer->Encoding = $CI->config->item('encoding','mail');
        //設置郵件格式為HTML
        $CI->phpmailer->IsHTML($CI->config->item('is_html','mail'));
        //每50自斷行
        $CI->phpmailer->WordWrap = $CI->config->item('word_wrap','mail');


        //郵件標題
        $CI->phpmailer->Subject = $subject;

        //郵件內容
        $CI->phpmailer->Body = $content;

        //寄送郵件
        if(!$CI->phpmailer->Send())
        {
            echo "郵件無法順利寄出!";
            echo "Mailer Error: " . $CI->phpmailer->ErrorInfo;
            //exit;
			die;
        }
    }

    
    /**
	 * 發送多筆email
	 */
    function send_muti_email($to_mails=array(), $subject, $content) 
    {
        
        $CI =& get_instance();        
        
		logData('寄信：'.$subject.' To: '.implode(',',$to_mails));

        //清除上次寄件者
        $CI->phpmailer->ClearAddresses();
        
        //設定使用SMTP發送
        $CI->phpmailer->IsSMTP();

        //指定SMTP的服務器位址
        $CI->phpmailer->Host = $CI->config->item('host','mail');
        //設定SMTP服務的POST
        $CI->phpmailer->Port = $CI->config->item('port','mail');


        //寄件人Email
        $CI->phpmailer->From = $CI->config->item('sender_mail','mail');
        //寄件人名稱
        $CI->phpmailer->FromName = $CI->config->item('sender_name','mail');

        //收件人Email
        foreach ($to_mails as $mail) 
        {
            if(preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/', $mail) == TRUE)
            {
               $CI->phpmailer->AddAddress($mail);
            }
        }


        //設定信件字元編碼
        $CI->phpmailer->CharSet = $CI->config->item('charset','mail');
        //設定信件編碼，大部分郵件工具都支援此編碼方式
        $CI->phpmailer->Encoding = $CI->config->item('encoding','mail');
        //設置郵件格式為HTML
        $CI->phpmailer->IsHTML($CI->config->item('is_html','mail'));
        //每50自斷行
        $CI->phpmailer->WordWrap = $CI->config->item('word_wrap','mail');


        //郵件標題
        $CI->phpmailer->Subject = $subject;
        //郵件內容
        $CI->phpmailer->Body = $content;

        //寄送郵件
        if(!$CI->phpmailer->Send())
        {
            echo "郵件無法順利寄出!";
            echo "Mailer Error: " . $CI->phpmailer->ErrorInfo;
            //exit;
        }
    }
    
    
    

    /**
     * 產生隨機密碼
     */
    function randomPassword($len = 8) 
    {

        $ranges = array        
        (        
                1 => array(97, 122), // a-z (lowercase)
                2 => array(48, 57) // 0-9 (numeral)        
                //3 => array(65, 90), // A-Z (uppercase)
                
        );

        $password = "";

        for ($i=0; $i<$len; $i++)
        {
                $r = mt_rand(1,count($ranges));
                $password .= chr(mt_rand($ranges[$r][0], $ranges[$r][1]));
        }

        return $password;
    }
    
    
    function sort_by_study_level($a, $b)
    {
        if($a['study_level'] == $b['study_level']) return 0;
        return ($a['study_level'] > $b['study_level']) ? 1 : -1;
    }

	
    function is_between($given_timestamp=5, $begin_timestamp=0, $end_timestamp=100)
    {
		if ($given_timestamp >= $begin_timestamp
			&& $given_timestamp <= $end_timestamp) {
			return true;
		}
		return false;
    }
	
	
	
	//混淆字串
	function confuseString($str_word)
	{
		
		if(strlen($str_word)>5)
		{
			$str_word = substr($str_word, 0, 4)."****";
		}
		
		return $str_word;
	}
	
	
	
	/**
	 * ASCII 字元自動全形/半形轉換 (字碼補位法)
	 *
	 * @param string $char 欲轉換的 ASCII 字元
	 * @param string $width 字形模式 half|full|auto (半形|全形|自動)
	 * @return string 轉換後的對應字元
	 */
	function shiftSpace($char=null, $width='auto') {
	
	    //取得當前字元的16進位值
	    $charHex = hexdec(bin2hex($char));
	
	    //判斷當前字元為半形或全形
	    $charWidth = ($char == '　' or ($charHex >= hexdec(bin2hex('！')) and $charHex <= hexdec(bin2hex ('～')))) ? 'full' : 'half';
	
	    //如果字元字形與指定字形一樣，就直接回傳
	    if($charWidth == $width) {
	        return $char;
	    }
	
	    //如果是空白字元就直接比對轉換回傳
	    if($char === '　' ) {
	        return ' ';
	    } elseif($char === ' ') {
	        return '　';
	    }
	
	    //計算 ASCII 字元16進位的unicode差值
	    $diff = abs(hexdec(bin2hex ('！')) - hexdec(bin2hex ('!')));
	
	    //計算字元"_"之後的半形字元修正值(192)
	    $fix = abs(hexdec(bin2hex ('＿')) - hexdec(bin2hex ('｀'))) - 1;
	
	    //全形/半形轉換
	    if($charWidth == 'full'){
	        $charHex = $charHex - (($charHex > hexdec(bin2hex('＿'))) ? $diff + $fix : $diff); 
	    } else {
	        $charHex = $charHex + (($charHex > hexdec(bin2hex('_'))) ? $diff + $fix : $diff); 
	    }
	
	    return hex2bin(dechex($charHex));
	}


	function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = null)
	{
		if (!$encoding) {
			$diff = strlen($input) - mb_strlen($input);
		}
		else {
			$diff = strlen($input) - mb_strlen($input, $encoding);
		}
		return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
	}





function mb_str_exist($str, $ex_str)
{
    $str_arr = mb_str_split($str);
    $ex_str_arr = mb_str_split($ex_str);
    $first_pos = 0; //存取第一個字元的位置
    $have_one = false; //用以判斷第一個字元自否存在，若存在，再接下去搜尋剩下的
    for ($i = 0; $i < count($ex_str_arr); $i++) {
        if ($i == 0) {
            for ($j = $first_pos; $j < count($str_arr); $j++) {
                if ($str_arr[$j] == $ex_str_arr[0]) {
                    $first_pos = $j;
                    $have_one = true;
                    break;
                }
            }

            if ($have_one == false) {
                return false;
            }
        }
        else
        if ($i !== 0 and $have_one == true) {
            if (isset($str_arr[$j + 1]) && isset($ex_str_arr[$i]) 
				&& $str_arr[$j + 1] == $ex_str_arr[$i]) {
                $j++;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    return $first_pos;
}

// Let string to char array
function mb_str_split($str, $length = 1)
{
    if ($length < 1) return FALSE;
    $result = array();
    for ($i = 0; $i < mb_strlen($str); $i+= $length) {
        $result[] = mb_substr($str, $i, $length);
    }

    return $result;
}


	function number_format_clean($number, $precision=0, $dec_point='.', $thousands_sep=',')
    {
		return rtrim(rtrim(number_format($number, $precision, $dec_point ,$thousands_sep), '0'), $dec_point);
    }


	 /**
     * 時間加減處理
     * $strDate：需要處理的時間字符串
     * $days：   加减天数
     **/
    function changeDate($strDate,$days)
    {
          $time = time();
          if(isset($strDate) && !empty($strDate))
          {
              $time = strtotime($strDate);
          }

         return date('Y-m-d H:i:s',strtotime("$days day",$time));
    }
	
	/**
     *  獲取當前周、月的頭尾日期
     *
     *  $dateArr['W1']:周一
     *  $dateArr['W7']:周末
     *  $dateArr['M1']:月頭
     *  $dateArr['M2']:月尾
     **/
    function GetCurrentDateInfo($calc_date = '')
    {
    	if(isNull($calc_date))
		{
			$calc_date = date('Y-m-d');			
		}
		else 
		{
			$calc_date = showDateFormat($calc_date,'Y-m-d');
		}
		
		
       $dayTimes = 24*60*60;
       $dateArr = array();
       $temp = '';

       /* 0:周末 1-6:周一 至 周六 */
       $weekIndex = (int)date('w', strtotime($calc_date));

       switch($weekIndex)
       {
            case 0:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-6);
                $dateArr['W7'] = $calc_date." 23:59:59";
                break;
            case 1:
                $dateArr['W1'] = $calc_date." 00:00:00";
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),6);
                break;
            case 2:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-1);
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),5);
                break;
            case 3:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-2);
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),4);
                break;
            case 4:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-3);
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),+3);
                break;
            case 5:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-4);
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),+2);
                break;
            case 6:
                $dateArr['W1'] = changeDate(date($calc_date." 23:59:59"),-5);
                $dateArr['W7'] = changeDate(date($calc_date." 23:59:59"),+1);
                break;
       }


       //1-12：一月 至 十二月
            
       //$monthIndex = (int)date('m');
       $monthIndex = (int)date('m', strtotime($calc_date));
       switch($monthIndex){
           case 1:
               $temp = date('Y-02-01 00:00:00');
               $dateArr['M1'] = date('Y-01-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 2:
               $temp = date('Y-03-01 00:00:00');
               $dateArr['M1'] = date('Y-02-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 3:
               $temp = date('Y-04-01 00:00:00');
               $dateArr['M1'] = date('Y-03-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 4:
               $temp = date('Y-05-01 00:00:00');
               $dateArr['M1'] = date('Y-04-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 5:
               $temp = date('Y-06-01 00:00:00');
               $dateArr['M1'] = date('Y-05-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 6:
               $temp = date('Y-07-01 00:00:00');
               $dateArr['M1'] = date('Y-06-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 7:
               $temp = date('Y-08-01 00:00:00');
               $dateArr['M1'] = date('Y-07-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 8:
               $temp = date('Y-09-01 00:00:00');
               $dateArr['M1'] = date('Y-08-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 9:
               $temp = date('Y-10-01 00:00:00');
               $dateArr['M1'] = date('Y-09-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 10:
               $temp = date('Y-11-01 00:00:00');
               $dateArr['M1'] = date('Y-10-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 11:
               $temp = date('Y-12-01 00:00:00');
               $dateArr['M1'] = date('Y-11-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
           case 12:
               $temp = date((date('Y')+1)."-01-01 00:00:00");
               $dateArr['M1'] = date('Y-12-01 00:00:00');
               $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);
               break;
       }


       return $dateArr;
    }

    /**
	 * 計算兩日期的差距(天數 or 小時)
	 * $start_date : 起始時間 
	 * $end_date : 結束時間
	 * $type : DAY(回傳天數),HOUR(回傳小時)
	 */
    function calcDiffDate($start_date,$end_date,$type = "DAY")
    {    	
		$startymd = date('Y-m-d',strtotime($start_date));
		$starthms = date('H-i-s',strtotime($start_date));
		$start_array_ymd = explode('-', $startymd ); 
		$start_array_hms = explode('-', $starthms);
		$startdate = mktime($start_array_hms[0],$start_array_hms[1],$start_array_hms[2],$start_array_ymd[1],$start_array_ymd[2],$start_array_ymd[0]);
		
		
		$endymd = date('Y-m-d',strtotime($end_date)); 
		$endhms = date('H-i-s',strtotime($end_date)); 
		$end_array_ymd = explode('-', $endymd ); 
		$end_array_hms = explode('-', $endhms); 
		$enddate = mktime($end_array_hms[0],$end_array_hms[1],$end_array_hms[2],$end_array_ymd[1],$end_array_ymd[2],$end_array_ymd[0]);
		
		if($type == "DAY")
		{
			$diff_date = (($enddate-$startdate)/3600)/24;			
		}
		else if($type == "HOUR")
		{
			$diff_date =(($enddate-$startdate)/3600);
		}
		
		 
		return round($diff_date); 		
    }
	
	
	/**
	 * 將DBquery的Data轉成map
	 * 
	 */
	 function datatoMap($input_ary=array(), $key="",$value_key="")
	 {
	 	$ouput_map = array();
		foreach ($input_ary as $item) 
		{
			$ouput_map[$item[$key]] = $item[$value_key];
		}
	 	return $ouput_map;
	 }
	
	/**
	 * 將DBquery的Data轉成array
	 * 
	 */
	 function datatoArray($input_ary=array(), $key="")
	 {
	 	$ouput_map = array();
		foreach ($input_ary as $item) 
		{
			array_push($ouput_map,$item[$key]);
		}
	 	return $ouput_map;
	 }
	
	 function getHardword()
	 {
	 	$hardword_ary = array(
	 		'㈯' => '土',
			'㆞' => '地',
			'㆓' => '二',
			'㊞' => '印',
			'㈪' => '月',
			'㈰' => '日',
			'㈾' => '資',
			'㆒' => '一',
			'㆕' => '四',
			'㈤' => '五',
			'㈵' => '特',
			'㆙' => '甲',
			'㆖' => '上',
			'㈲' => '有',
			'㊠' => '項',
			'㉂' => '自',
			'㆟' => '人',
			'㊟' => '注',
			'㉃' => '至',
			'㆗' => '中',
			'㈴' => '名',
			'㈮' => '金',
			'㈬' => '水',
			'㆔' => '三',
			'㈩' => '十',
			'㆘' => '下',
			'㆚' => '乙',
			'恒' => '恒'
		);
		
		
		return $hardword_ary;
	 }
	 
	 
	 function getCityTownConvert()
	 {
	 	$hardword_ary = array(
	 		"臺北縣蘆洲市" => "新北市蘆洲區",
			"臺北縣中和市" => "新北市中和區",
			"臺北縣永和市" => "新北市永和區",
			"臺北縣三重市" => "新北市三重區",
			"臺北縣淡水鎮" => "新北市淡水區",
			"臺北縣新莊市" => "新北市新莊區",
			"臺北縣林口鄉" => "新北市林口區",
			"臺北縣三峽鎮" => "新北市三峽區",
			"臺北縣板橋市" => "新北市板橋區",
			"臺北縣泰山鄉" => "新北市泰山區",
			"臺北縣土城市" => "新北市土城區",
			"臺北縣樹林市" => "新北市樹林區",
			"臺北縣樹林鎮" => "新北市樹林區",
			"臺北縣鶯歌鎮" => "新北市鶯歌區",
			"臺北縣五股鄉" => "新北市五股區",
			"臺北縣金山鄉" => "新北市金山區",
			"臺北縣八里鄉" => "新北市八里區",
			"臺北縣萬里鄉" => "新北市萬里區",
			"臺北縣石門鄉" => "新北市石門區",
			"臺北縣三芝鄉" => "新北市三芝區",
			"臺北縣瑞芳鎮" => "新北市瑞芳區",
			"臺北縣汐止市" => "新北市汐止區",
			"臺北縣平溪鄉" => "新北市平溪區",
			"臺北縣貢寮鄉" => "新北市貢寮區",
			"臺北縣雙溪鄉" => "新北市雙溪區",
			"臺北縣深坑鄉" => "新北市深坑區",
			"臺北縣石碇鄉" => "新北市石碇區",
			"臺北縣新店市" => "新北市新店區",
			"臺北縣坪林鄉" => "新北市坪林區",
			"臺北縣烏來鄉" => "新北市烏來區",
			"台北縣蘆洲市" => "新北市蘆洲區",
			"台北縣中和市" => "新北市中和區",
			"台北縣永和市" => "新北市永和區",
			"台北縣三重市" => "新北市三重區",
			"台北縣淡水鎮" => "新北市淡水區",
			"台北縣新莊市" => "新北市新莊區",
			"台北縣林口鄉" => "新北市林口區",
			"台北縣三峽鎮" => "新北市三峽區",
			"台北縣板橋市" => "新北市板橋區",
			"台北縣泰山鄉" => "新北市泰山區",
			"台北縣土城市" => "新北市土城區",
			"台北縣樹林市" => "新北市樹林區",
			"台北縣樹林鎮" => "新北市樹林區",
			"台北縣鶯歌鎮" => "新北市鶯歌區",
			"台北縣五股鄉" => "新北市五股區",
			"台北縣金山鄉" => "新北市金山區",
			"台北縣八里鄉" => "新北市八里區",
			"台北縣萬里鄉" => "新北市萬里區",
			"台北縣石門鄉" => "新北市石門區",
			"台北縣三芝鄉" => "新北市三芝區",
			"台北縣瑞芳鎮" => "新北市瑞芳區",
			"台北縣汐止市" => "新北市汐止區",
			"台北縣平溪鄉" => "新北市平溪區",
			"台北縣貢寮鄉" => "新北市貢寮區",
			"台北縣雙溪鄉" => "新北市雙溪區",
			"台北縣深坑鄉" => "新北市深坑區",
			"台北縣石碇鄉" => "新北市石碇區",
			"台北縣新店市" => "新北市新店區",
			"台北縣坪林鄉" => "新北市坪林區",
			"台北縣烏來鄉" => "新北市烏來區",
			"蘆洲市" => "蘆洲區",
			"中和市" => "中和區",
			"永和市" => "永和區",
			"三重市" => "三重區",
			"淡水鎮" => "淡水區",
			"新莊市" => "新莊區",
			"林口鄉" => "林口區",
			"三峽鎮" => "三峽區",
			"板橋市" => "板橋區",
			"泰山鄉" => "泰山區",
			"土城市" => "土城區",
			"樹林市" => "樹林區",
			"樹林鎮" => "樹林區",
			"鶯歌鎮" => "鶯歌區",
			"五股鄉" => "五股區",
			"金山鄉" => "金山區",
			"八里鄉" => "八里區",
			"萬里鄉" => "萬里區",
			"石門鄉" => "石門區",
			"三芝鄉" => "三芝區",
			"瑞芳鎮" => "瑞芳區",
			"汐止市" => "汐止區",
			"平溪鄉" => "平溪區",
			"貢寮鄉" => "貢寮區",
			"雙溪鄉" => "雙溪區",
			"深坑鄉" => "深坑區",
			"石碇鄉" => "石碇區",
			"新店市" => "新店區",
			"坪林鄉" => "坪林區",
			"烏來鄉" => "烏來區",
			"臺北縣" => "新北市",
			"台北縣" => "新北市",
			"台北縣" => "新北市",
			"臺北市" => "台北市",
			"臺中" => "台中",
			"臺中" => "台中",
			"臺南" => "台南",
			"臺南" => "台南",
			"臺東" => "台東",
			"臺東" => "台東"
		);
		return $hardword_ary;
	 }
	
	/**
	 * 文字替代
	 * $char_ary 取代的文字陣列
	 * $query_string 檢查的字串
	 */
	function replaceCharAry($char_ary,$query_string)
	{
		foreach($char_ary as $key => $val)
		{			
			if(substr_count($query_string,$key)>0)
			{
				$query_string=str_replace($key,$val,$query_string);			
			}
		}		
		return $query_string;
	}
	
	
	// 將utf-8編碼的中文字　轉成big-5 再轉回 utf-8 (讓 ’福’ 跟 ’福’ 可以變成同樣的 ’福’ 字)
	function big5_for_utf8($given_string=null)
	{

		if (is_null($given_string)) return false;
		if ( in_array($given_string, array('胡維珉', '凃翔騰', '凃于淵', '槺榔段', '槺榔段下槺榔小段', '槺榔段上槺榔小段', '番婆坟段', '桃園市新屋區番婆坟段')) ) return $given_string;
		
		mb_internal_encoding("UTF-8");

		// 將地址裡的樓層數字轉成中文數字 （1樓變一樓）
		$given_string = str_replace('樓', '樓', $given_string);
		$given_string = str_replace('号', '號', $given_string);
		
		$match = array();
		preg_match("/號([0-9]+)樓/", $given_string, $match);
		if ( sizeof($match) == 0) {
			preg_match("/號([０-９]+)樓/", $given_string, $match);
			$full_flag = true;
		}

		//if ( tryGetData(1, $match, 0) > 0 ) {
		if ( sizeof($match) > 0 ) {
			$level_num = $match[1];
			
			$full_width_number = array('０', '１', '２', '３', '４', '５', '６', '７', '８', '９');
			if ( in_array($level_num, $full_width_number) === true) {
				$full_width_level_num = $level_num;
				$key = array_search($level_num, $full_width_number);
				$level_num = (string) $key;

				$level_num_chinese = number_to_string($level_num);

				if ($level_num < 20 and $level_num > 10) {
					$level_num_chinese = mb_substr($level_num_chinese, 1);
				}
				$tail_string_before = $match[0];
				$tail_string_after = str_replace($full_width_level_num, $level_num_chinese, $match[0]);
				$given_string = str_replace($tail_string_before, $tail_string_after, $given_string);

			} elseif ($level_num > 0) {
			
				$level_num_chinese = number_to_string($level_num);

				if ($level_num < 20 and $level_num > 10) {
					$level_num_chinese = mb_substr($level_num_chinese, 1);
				}
				$tail_string_before = $match[0];
				$tail_string_after = str_replace($level_num, $level_num_chinese, $match[0]);
				$given_string = str_replace($tail_string_before, $tail_string_after, $given_string);
			}
				
			
		}

		$spec_word_array = array('臺' => '台'			,'北' => '北'			,'巿' => '市'			,'六' => '六'		,'祥' => '祥'
								,'樓' => '樓'			,'路' => '路'			,'里' => '里'			,'林' => '林'		,'𡍼' => '塗'
								,'黄' => '黃'			,'寿' => '壽'			,'啓' => '啟'			,'隆' => '隆'		,'麗' => '麗'
								,'禇' => '褚'			,'楮' => '褚'			,'温' => '溫'			,'敍' => '敘'		,'㈧' => '八'
								,'逹' => '達'			,'鳯' => '鳳'			,'頴' => '穎'			,'晋' => '晉'		,'恒' => '恆'
								,'勲' => '勳'			,'嫺' => '嫻'			,'嫏' => '鄉'			,'靓' => '靚'		,'号' => '號'
								,'国' => '國'			,'号' => '號'			,'～' => '之'			,'鋭' => '銳'		,'鷄' => '雞'
								,'増' => '增'			,'会' => '會'			,'窝' => '窠'			,'行' => '行'
								,'眞' => '真'			,'蘆' => '蘆'			,'蘆' => '蘆'			,'爕' => '燮'
								,'年' => '年'			,'更' => '更'			,'崙' => '崙'			,'㈳' => '社'
								,'1' => '１'			,'4' => '４'			,'7' => '７'			,'9' => '９'
								,'2' => '２'			,'5' => '５'			,'8' => '８'			,'0' => '０'
								,'3' => '３'			,'6' => '６'			,'_' => ''		,'＿' => ''		,'－' => '之'		,'-' => '之'
								,'台北縣' => '新北市'	,'台中縣' => '台中市'	,'台南縣' => '台南市'	,'高雄縣' => '高雄市'
								,'桃園縣桃園市' => '桃園市桃園區'		,'桃園市平區' => '桃園市平鎮區'
								,'桃園縣桃園縣' => '桃園市桃園區'		,'桃園市中壢市' => '桃園市中壢區'   ,'桃園縣蘆竹市' => '桃園市蘆竹區'	,'桃園縣蘆竹鄉' => '桃園市蘆竹區'
								,'桃園鄉蘆竹鄉' => '桃園市蘆竹區'		,'桃園區蘆竹鄉' => '桃園市蘆竹區'	,'桃園市蘆竹鄉' => '桃園市蘆竹區'	,'桃園縣桃園巾' => '桃園市桃園區' 
								,'桃園縣龜山鄉' => '桃園市龜山區'		,'桃園縣' => '桃園市'				,'龜山鄉' => '龜山區'		,'大園鄉' => '大園區'
								,'蘆竹市' => '蘆竹區'	,'蘆竹鄉' => '蘆竹區',	'中壢市' => '中壢區',	'八德市' => '八德區'
								,'東區公園裡' => '東區公園里'		,'東區科園裡' => '東區科園里'			,'東區東園裡' => '東區東園里'
								,'萬里鄉' => '萬里區', '金山鄉' => '金山區', '板橋市' => '板橋區', '汐止市' => '汐止區', '深坑鄉' => '深坑區'
								,'石碇鄉' => '石碇區', '瑞芳鎮' => '瑞芳區', '平溪鄉' => '平溪區', '雙溪鄉' => '雙溪區', '貢寮鄉' => '貢寮區'
								,'新店市' => '新店區', '坪林鄉' => '坪林區', '烏來鄉' => '烏來區', '永和市' => '永和區', '中和市' => '中和區'
								,'土城市' => '土城區', '三峽鎮' => '三峽區', '樹林市' => '樹林區', '鶯歌鎮' => '鶯歌區', '三重市' => '三重區'
								,'新莊市' => '新莊區', '泰山鄉' => '泰山區', '林口鄉' => '林口區', '蘆洲市' => '蘆洲區', '五股鄉' => '五股區'
								,'八里鄉' => '八里區', '淡水鎮' => '淡水區', '三芝鄉' => '三芝區', '石門鄉' => '石門區'
								);

		$spec_word_array2 = array('新北市中和區灰里'	=> '新北市中和區灰磘里'
								, '新北市中和區灰摇里'	=> '新北市中和區灰磘里'
								, '新北市中和區灰搖里'	=> '新北市中和區灰磘里'
								, '新北市中和區灰窯里'	=> '新北市中和區灰磘里'

								, '灰子段' => '灰磘子段'		, '灰段' => '灰磘段'
								, '瓦厝段' => '瓦磘厝段'		, '瓦子段' => '瓦磘子段'
								, '新磁段' => '新磁磘段'		, '東瓦厝段' => '東瓦磘厝段'		
								, '磚段' => '磚磘段'			, '磚子段' => '磚子磘段'

								, '灰摇子段' => '灰磘子段'		, '灰摇段' => '灰磘段'
								, '瓦摇厝段' => '瓦磘厝段'		, '瓦摇子段' => '瓦磘子段'
								, '新磁摇段' => '新磁磘段'		, '東瓦摇厝段' => '東瓦磘厝段'
								, '磚摇段' => '磚磘段'			, '磚摇子段' => '磚子磘段'

								, '灰搖子段' => '灰磘子段'		, '灰搖段' => '灰磘段'
								, '瓦搖厝段' => '瓦磘厝段'		, '瓦搖子段' => '瓦磘子段'
								, '新磁搖段' => '新磁磘段'		, '東瓦搖厝段' => '東瓦磘厝段'
								, '磚搖段' => '磚磘段'			, '磚搖子段' => '磚子磘段'

								, '灰窯子段' => '灰磘子段'		, '灰窯段' => '灰磘段'
								, '瓦窯厝段' => '瓦磘厝段'		, '瓦窯子段' => '瓦磘子段'
								, '新磁窯段' => '新磁磘段'		, '東瓦窯厝段' => '東瓦磘厝段'
								, '磚窯子段' => '磚子磘段'
								, '_' => ''						, '＿' => ''						, '－' => ''			, '-' => ''
								, '大園區林村'=>'大園區菓林里'		,'大園區鄉林村'=>'大園區菓林里'
								, '桃園縣大園鄉林村' => '桃園市大園區菓林里'		,'桃園市大園區林里' => '桃園市大園區菓林里'
								);
								//, '磚窯段' => '磚磘段'
		$spec_word_array3 = getHardword();
		$tmp_string = $given_string;
		
		$tmp_string = replaceCharAry($spec_word_array3,$tmp_string);
		
		//// echo '<p>[Before] '.$tmp_string;
		foreach ($spec_word_array as $k=>$v) {
			//echo $tmp_string.' : '.$k.' --> '.$v.' ==> '.$tmp_string.'<p>';
			$tmp_string = str_replace($k, $v, $tmp_string);
		}
		//// echo '<p>[After] '.$tmp_string;

		//if (mb_str_exist($tmp_string, "新北市") !== false || mb_str_exist($tmp_string, "台北縣") !== false) {
		if ( in_array(mb_substr($tmp_string, 0, 3), array('台北市','新北市','桃園市','新竹市','台中市','台南市','高雄市') ) ) {
			if ( in_array(mb_substr($tmp_string, 5, 1), array('鄉','鎮','市','區') ) ) {

				$tmp_string = str_replace('村００', '里', $tmp_string);
				$tmp_string = str_replace('村０', '里', $tmp_string);
				$tmp_string = str_replace('里００', '里', $tmp_string);
				$tmp_string = str_replace('里０', '里', $tmp_string);

				$tmp_string = preg_replace("/".mb_substr($tmp_string, 0, 3)."([\x7f-\xff]+)".mb_substr($tmp_string, 5, 1)."/", mb_substr($tmp_string, 0, 3)."$1區", $tmp_string);
				$tmp_string = preg_replace("/".mb_substr($tmp_string, 0, 3)."([\x7f-\xff]+)".mb_substr($tmp_string, 5, 1)."([\x7f-\xff]+)村/", mb_substr($tmp_string, 0, 3)."$1區$2里", $tmp_string);
			}
		}

		if ( mb_substr($tmp_string, 5, 1) == '市' ) {

				$tmp_string = str_replace('村００', '里', $tmp_string);
				$tmp_string = str_replace('村０', '里', $tmp_string);
				$tmp_string = str_replace('里００', '里', $tmp_string);
				$tmp_string = str_replace('里０', '里', $tmp_string);

				$tmp_string = preg_replace("/".mb_substr($tmp_string, 5, 1)."([\x7f-\xff]+)村/", mb_substr($tmp_string, 0, 3)."$1里", $tmp_string);
		}

		## iconv 參數用法參考自　http://yoonow.pixnet.net/blog/post/11141558-使用-ignore及translit-忽略-iconv-轉碼錯誤或取得
		$big5_string = iconv("utf-8", "big5//TRANSLIT//IGNORE",  $tmp_string);
		$utf8_string = iconv("big5",  "utf-8//TRANSLIT//IGNORE", $big5_string);

		//// echo '<p>[Iconv] '.$utf8_string;

		//// echo '<p>[Iconv Before] '.$tmp_string;
		foreach ($spec_word_array2 as $k=>$v) {
			//echo $tmp_string.' : '.$k.' --> '.$v.' ==> '.$tmp_string.'<p>';
			$utf8_string = str_replace($k, $v, $utf8_string);
		}
		//// echo '<p>[Iconv After] '.$utf8_string;
		// 若轉好的字串長度不符合原本的字串長度，則返回原字串  ex黃秀峯
		if ( mb_strlen($given_string, 'utf-8') != mb_strlen($utf8_string, 'utf-8') ) {

			//echo '<p>文字轉碼錯誤 (Helper big5_for_utf8函式) : '.$given_string.' → '.$utf8_string;
			foreach ($spec_word_array2 as $k=>$v) {
				//echo $tmp_string.' : '.$k.' --> '.$v.' ==> '.$tmp_string.'<p>';
				$given_string = str_replace($k, $v, $given_string);
			}
			//echo ' → '.$given_string;

			return $tmp_string;
		}

		return $utf8_string;
	}
	

	function number_to_string($num) {
		$string = '';
		//$numc ="零,壹,貳,參,肆,伍,陸,柒,捌,玖";
		$numc ="零,一,二,三,四,五,六,七,八,九";
		$unic =",十,百,千";
		$unic1 =",萬,億,兆,京";
		$numc_arr = explode("," , $numc);
		$unic_arr = explode("," , $unic);
		$unic1_arr = explode("," , $unic1);
		$i = str_replace(',','',$num);
		$c0 = 0;
		$str=array();
		do{
			$aa = 0;
			$c1 = 0;
			$s = "";
			$lan=(strlen($i)>=4)?4:strlen($i);
			$j = substr($i, -$lan);
			while($j>0){
				$k = $j % 10;
				if($k > 0){
					$aa = 1;
					$s = $numc_arr[$k].$unic_arr[$c1].$s;
				}elseif ($k == 0){
					if($aa == 1)    $s = "0" . $s;
				}
				$j = intval($j / 10);
				$c1 += 1;
			}
			$str[$c0]=($s=='')?'':$s.$unic1_arr[$c0];
			$count_len=strlen($i) - 4;
			$i=($count_len > 0 )?substr($i, 0, $count_len):'';
			$c0 += 1;
		}while($i!='');
		foreach($str as $v) $string .= array_pop($str);
		$string = preg_replace('/0+/','零',$string);
		return $string;
	}

	function person_id_check($pid)
	{   
		$iPidLen = strlen($pid);

		if ($iPidLen == 10) {
			if (!preg_match("/^[A-Za-z][1-2][0-9]{8}$/",$pid) || strpos('-', $pid) === true || $pid=='無') {
				return FALSE;
			}

		} elseif ($iPidLen == 9) {
			if (!preg_match("/^[A-Za-z]{2}[0-9]{7}$/",$pid)) {
				return FALSE;
			} else {
				return TRUE;
			}
		} elseif ($iPidLen == 8) {
			if (!preg_match("/^[0-9]{8}$/",$pid)) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}

		/*if(!preg_match("/^[A-Za-z][1-2][0-9]{8}$/",$pid) ||  $iPidLen != 10 || strpos('-', $pid) === true || $pid=='無' )
		{
			if ( in_array(substr($pid, 0, 2), array('TK','MT')) && $iPidLen == 9) {

			return true;
			} else {
			return FALSE;
			}
		}*/

		$head = array("A"=>1,"B"=>0,"C"=>9,"D"=>8,"E"=>7,"F"=>6,"G"=>5,"H"=>4,"I"=>9,"J"=>3,"K"=>2,"M"=>1,"N"=>0,"O"=>8,"P"=>9,"Q"=>8,"T"=>5,"U"=>4,"V"=>3,"W"=>1,"X"=>3,"Z"=>0,"L"=>2,"R"=>7,"S"=>6,"Y"=>2);
		$pid  = strtoupper($pid);
		$iSum  = 0;
		for($i=0; $i< $iPidLen; $i++)
		{
			$sIndex = substr($pid,$i,1);

				//$iSum   += (empty($i) || $i==0) ? $head[$sIndex] : intval($sIndex) * abs( 9 - base_convert($i,10,9) );
				if (empty($i) || $i==0) {
					if (!isset($head[$sIndex])) echo $pid.' --->   '.$sIndex;
					else {
					//echo $pid.' --> -> ->   '.$sIndex;
					//echo $head[$sIndex];
					$iSum   += $head[$sIndex];
					}
				} else {
					$iSum   += intval($sIndex) * abs( 9 - base_convert($i,10,9) );
				}
		}
		return ( $iSum  % 10 == 0 ) ? TRUE:FALSE;
	}

	/* 計算入庫所佔用的記憶體資源 2016.01.30 vincent */
	function memory_use_now()
	{
	    $level = array('Bytes', 'KB', 'MB', 'GB');
		$n = memory_get_usage();
		for ($i=0, $max=count($level); $i<$max; $i++)
		{
			if ($n < 1024) 
			{
			    $n = round($n, 2);
			    return "{$n} {$level[$i]}";
			}
		    $n /= 1024;
	    }
	}

	/* 清除字串內的空白 2016.01.30 vincent */
	function trimSpace($val)
	{
		$val = trim($val); //清除字串前後空白
		$val = preg_replace('/\s(?=)/', '', $val); //清除字串間空白
		$val = preg_replace('/[\n\r\t]/', ' ', $val); //清除非space的空白，用一個空格代替
		return $val;
	}

	/* 清除特殊符號 2016.01.30 vincent */
	function strFilter($str=null)
	{
    	if( isNotNull($str) ) {
		  $str = str_replace("'", '', $str);
		  $str = str_replace('"', '', $str);
		  /*
		  $str = str_replace('`', '', $str);
		  $str = str_replace('·', '', $str);
		  $str = str_replace('~', '', $str);
		  $str = str_replace('!', '', $str);
		  $str = str_replace('！', '', $str);
		  $str = str_replace('@', '', $str);
		  $str = str_replace('#', '', $str);
		  $str = str_replace('$', '', $str);
		  $str = str_replace('￥', '', $str);
		  $str = str_replace('%', '', $str);
		  $str = str_replace('^', '', $str);
		  $str = str_replace('……', '', $str);
		  $str = str_replace('&', '', $str);
		  $str = str_replace('*', '', $str);
		  $str = str_replace('(', '', $str);
		  $str = str_replace(')', '', $str);
		  $str = str_replace('（', '', $str);
		  $str = str_replace('）', '', $str);
		  $str = str_replace('-', '', $str);
		  $str = str_replace('_', '', $str);
		  $str = str_replace('——', '', $str);
		  $str = str_replace('+', '', $str);
		  $str = str_replace('=', '', $str);
		  $str = str_replace('|', '', $str);
		  $str = str_replace('\\', '', $str);
		  $str = str_replace('[', '', $str);
		  $str = str_replace(']', '', $str);
		  $str = str_replace('【', '', $str);
		  $str = str_replace('】', '', $str);
		  $str = str_replace('{', '', $str);
		  $str = str_replace('}', '', $str);
		  $str = str_replace(';', '', $str);
		  $str = str_replace('；', '', $str);
		  $str = str_replace(':', '', $str);
		  $str = str_replace('：', '', $str);
		  $str = str_replace('\'', '', $str);
		  $str = str_replace('"', '', $str);
		  $str = str_replace('「', '', $str);
		  $str = str_replace('」', '', $str);
		  $str = str_replace(',', '', $str);
		  $str = str_replace('，', '', $str);
		  $str = str_replace('<', '', $str);
		  $str = str_replace('>', '', $str);
		  $str = str_replace('《', '', $str);
		  $str = str_replace('》', '', $str);
		  $str = str_replace('.', '', $str);
		  $str = str_replace('。', '', $str);
		  $str = str_replace('/', '', $str);
		  $str = str_replace('、', '', $str);
		  $str = str_replace('?', '', $str);
		  $str = str_replace('？', '', $str);
		  */
      		return trim($str);
		}
    	return $str;
	}


	/**
	 * 經緯度轉twd97
	 * $lon(x) 經度  ex:121.309564
	 * $lat(y) 緯度  ex:25.008422
	 */
	function lonlat_to_twd97($lon ,$lat)
	{
		$a = 6378137.0;
    	$b = 6356752.314245;
    	$lon0 = 121 * pi() / 180;
    	$k0 = 0.9999;
    	$dx = 250000;
		
		$twd97_ary = array("x"=>0,"y"=>0);
		//$TWD97 = '';
		
		$lon = ($lon/180) * pi();
        $lat = ($lat/180) * pi();
		
		$e = pow((1 - pow($b,2) / pow($a,2)), 0.5);
		$e2 = pow($e,2)/(1-pow($e,2));
 		$n = ( $a - $b ) / ( $a + $b );
		$nu = $a / pow((1-(pow($e,2)) * (pow(sin($lat), 2) ) ) , 0.5);
		$p = $lon - $lon0;
		
		$A = $a * (1 - $n + (5/4) * (pow($n,2) - pow($n,3)) + (81/64) * (pow($n, 4)  - pow($n, 5)));
		$B = (3 * $a * $n/2.0) * (1 - $n + (7/8.0)*(pow($n,2) - pow($n,3)) + (55/64.0)*(pow($n,4) - pow($n,5)));		
        $C = (15 * $a * (pow($n,2))/16.0)*(1 - $n + (3/4.0)*(pow($n,2) - pow($n,3)));		
        $D = (35 * $a * (pow($n,3))/48.0)*(1 - $n + (11/16.0)*(pow($n,2) - pow($n,3)));		
        $E = (315 * $a * (pow($n,4))/51.0)*(1 - $n);		
		$S = $A * $lat - $B * sin(2 * $lat) + $C * sin(4 * $lat) - $D * sin(6 * $lat) + $E * sin(8 * $lat);
		
		
		 //計算Y值
        $K1 = $S*$k0;
        $K2 = $k0*$nu*sin(2*$lat)/4.0;		
        $K3 = ($k0*$nu*sin($lat)*(pow(cos($lat),3))/24.0) * (5 - pow(tan($lat),2) + 9*$e2*pow((cos($lat)),2) + 4*(pow($e2,2))*(pow(cos($lat),4)));        
        $y = $K1 + $K2*(pow($p,2)) + $K3*(pow($p,4));
		
		
		//計算X值
        $K4 = $k0*$nu*cos($lat);		
        $K5 = ($k0*$nu*(pow(cos($lat),3))/6.0) * (1 - pow(tan($lat),2) + $e2*(pow(cos($lat),2)));		
        $x = $K4 * $p + $K5 * (pow($p, 3)) + $dx;
		
		$twd97_ary["x"] = $x;
		$twd97_ary["y"] = $y;
		//$TWD97 = $x.",".$y;
        return $twd97_ary;
		
	}  
	


