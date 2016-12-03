<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	/**
	 * 取得後台url
	 * @param string action 名稱
	 * @param bool  
	 * @return string
	 */
	function bUrl($action = '', $has_query_string = TRUE, $ignore_var = array(), $add_var = array())
	{
	
		$CI	=& get_instance();
		return backendUrl($CI->router->fetch_class(),$action, $has_query_string, $ignore_var, $add_var);
	}
	
	/**
	 * 取得後台url
	 * @param string  controller 名稱
	 * @param string  action 名稱
	 * @param bool 
	 * @return string
	 */
	function backendUrl($controller ='home', $action = 'index', $has_query_string = TRUE, $ignore_var = array(), $add_var = array())
	{
		$CI	=& get_instance();		
		$backend_name = $CI->config->item('backend_name');
		

		if(isset($CI->language_value) && empty($CI->language_value)==FALSE )
		{
			$lang_value = $CI->language_value;
		}
		
		$url = base_url().$backend_name.'/'.$controller.'/'.$action;	
		

		if($has_query_string)
		{		
			if(isNull($ignore_var))
			{
				$ignore_var = array();
			}
			
		
			$query_string = "";
			

			
				$query_string_ary = array();
				$tmp_ary = array();
				
				//將Query String做成陣列，在ignore_var中的變數，不儲存至$query_string中
				parse_str( $_SERVER['QUERY_STRING'] , $tmp_ary );
				foreach( $tmp_ary as $key => $value )
				{
					if( !in_array( $key , $ignore_var ) )
					{
						$query_string_ary[$key] = $value;
					}
				}
				
				

				
				foreach( $add_var as $key => $value )
				{
					$query_string_ary[$key] = $value;
				}


				
				foreach( $query_string_ary as $key => $value )
				{
					if( is_array( $value ) )
					{
						for( $i = 0 ; $i < sizeof( $value ) ; $i++ )
						{
							$query_string .= "&".$key."[]=".urlencode( $value[$i] );
						}
					}
					else
					{
						if($query_string == "")
						{
							$query_string .= "?".$key."=".$value;
						}
						else 
						{
							$query_string .= "&".$key."=".$value;
						}
						
					}
				}
				
				//return $str;

			
			$url .= $query_string;
		}
			
		return $url;
	}


	/**
	 * 取得前台url
	 * @param string action 名稱
	 * @param bool  
	 * @return string
	 */
	function fUrl($action = '', $has_query_string = FALSE, $ignore_var = array(), $add_var = array())
	{
	
		$CI	=& get_instance();
		return frontendUrl($CI->router->fetch_class(),$action, $has_query_string, $ignore_var, $add_var);
	}
	

	/**
	 * 取得前台url
	 * @param string  controller 名稱
	 * @param string  action 名稱
	 * @param bool 
	 * @return string
	 */
	function frontendUrl($controller ='home', $action = 'index', $has_query_string = FALSE, $ignore_var = array(), $add_var = array())
	{
		$CI	=& get_instance();		
		$frontend_name = $CI->config->item('frontend_name');
		
		$url = base_url().$controller.'/'.$action;	
		

		if($has_query_string)
		{		
			if(isNull($ignore_var))
			{
				$ignore_var = array();
			}		
			
			$query_string = "";
			
			$query_string_ary = array();
			$tmp_ary = array();
			
			
			//將Query String做成陣列，在ignore_var中的變數，不儲存至$query_string中
			if(!array_key_exists("all", $ignore_var) )
			{
				parse_str( $_SERVER['QUERY_STRING'] , $tmp_ary );
				foreach( $tmp_ary as $key => $value )
				{
					if( !in_array( $key , $ignore_var ) )
					{
						$query_string_ary[$key] = $value;
					}
				}
			}
			
			
								
			foreach( $add_var as $key => $value )
			{
				$query_string_ary[$key] = $value;
			}


				
			foreach( $query_string_ary as $key => $value )
			{
				if( is_array( $value ) )
				{
					for( $i = 0 ; $i < sizeof( $value ) ; $i++ )
					{
						$query_string .= "&".$key."[]=".urlencode( $value[$i] );
					}
				}
				else
				{
					if($query_string == "")
					{
						$query_string .= "?".$key."=".$value;
					}
					else 
					{
						$query_string .= "&".$key."=".$value;
					}
					
				}
			}
			
			//return $str;

			
			$url .= $query_string;
		}
			
		return $url;
	}
	

	function getHref($link, $open_new = 0) 
	{
		if (isNull($link))
		{
			return "";
		}		
	
	
		$link_pos = strrpos($link,"http");
		$output_result = "";
		$output_link = "";
		$target_string ="";
		
		if($open_new == 1)
		{
		  $target_string = " target='_blank' ";	
		}
			
		if (is_bool($link_pos) && !$link_pos) 
		{
		 $output_link = "http://".$link;
		}
		else
		{
		 $output_link = $link;
		}
		
		$output_result = " href='".$output_link."'".$target_string;
		return $output_result;
	}


	function isNotNull($value) 
	{
		if(!isset($value))
		{
			return FALSE;
		}
		if (is_array($value)) 
		{
			if (sizeof($value) > 0) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		} 
		else 
		{
			if ( (is_string($value) || is_int($value) || is_float($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
	}
	  
	function isNull($value) 
	{
		return !isNotNull($value);
	}
  

	function tryGetValue($value, $default_value='')
	{
		if(isset($value) && isNotNull($value))
		{
			return $value;
		}
		else
		{
			return $default_value;
		}
	}
  

	function tryGetArrayValue($arry_key,$check_ary , $default_value='')
	{
		if(isNotNull($check_ary) && array_key_exists($arry_key , $check_ary))
		{
			if($check_ary[$arry_key] == '')
			{
				return $default_value;
			}
			else 
			{
				return $check_ary[$arry_key];
			}
			
		}
		else
		{
			return $default_value;
		}  	
	}
  	
	function tryGetData($arry_key,$check_ary , $default_value='')
	{
		//echo '<br>#'.$arry_key;
		if(isNotNull($check_ary) && array_key_exists($arry_key , $check_ary))
		{
			if($check_ary[$arry_key] == '')
			{
				return $default_value;
			}
			else 
			{
				return $check_ary[$arry_key];
			}
			
		}
		else
		{
			return $default_value;
		}  	
	}
  
  
  
	function showTryGetData($arry_key, $check_ary , $default_value='', $prefix_value='', $tail_value='')
	{
		//echo '<br>#'.$arry_key;
		if ( isNull($default_value) ){
		
			if(isNotNull(tryGetData($arry_key, $check_ary , $default_value)))
			{
				return $prefix_value.$check_ary[$arry_key].$tail_value;
				
			//} else {
			//	return $default_value;
			}

		} else{
		
			if(tryGetData($arry_key, $check_ary , $default_value) != $default_value)
			{
				return $prefix_value.$check_ary[$arry_key].$tail_value;
				
			//} else {
			//	return $default_value;
			}

		}
		return $default_value;
	}
 
	function showMoreString($input_string,$max_length = 10 ,$display_words = '...',$link_string = '',$show_all = true)
	{
		$read_more = false;
		$input_string = strip_tags($input_string);
		$input_string = xml_convert($input_string);
		$input_string_lenght = mb_strlen($input_string,"UTF-8");
		$return_string = '';	
		if($input_string_lenght > $max_length)
		{
			$return_string.= mb_substr($input_string,0,$max_length,"UTF-8");
			if(isNotNull($link_string))
			{
				if($show_all)
				{
					$return_string.='<span style="cursor:hand" title="'.$input_string.'"><a href="'.$link_string.'">' .$display_words.'</a></span>';
				}
				else
				{
					$return_string.='<span style="cursor:hand" title="more"><a href="'.$link_string.'">' .$display_words.'</a></span>';
				}	
					
			}
			else
			{
				if($show_all)
				{
					$return_string.= '<span style="cursor:hand" title="'.$input_string.'">'.$display_words.'</span>';
				}else
				{
					$return_string.= $display_words;
				}	
			}				
		}
		else
		{
			$return_string.= $input_string;
		}
		return $return_string;
	}
  
  
	function showMoreStringSimple($input_string,$max_length = 35 ,$display_words = '...')
	{
		$input_string = strip_tags($input_string);
		$input_string_lenght = mb_strlen($input_string,"UTF-8");
		$return_string = '';	
		if($input_string_lenght > $max_length)
		{
			$return_string.= mb_substr($input_string,0,$max_length,"UTF-8").$display_words;
		}
		else
		{
			$return_string.= $input_string;
		}

		return $return_string;
	}
  
  
	function doLog($log_message,$log_level = 'error')
	{
		$CI	=& get_instance();
		$user_str = 'MembersID['.$CI->session->userdata('members_name').']';
		if($CI->session->userdata('admins_id')){
			$user_str = 'AdminsID['.$CI->session->userdata('admins_account').']';
		}
		log_message($log_level, $user_str .',IP[' .$CI->input->ip_address().'] ==>{'. $log_message.'}');
	}


	function showDateRange($dm_start_date  = '',$dm_end_date ='')
	{	
		
		if($dm_start_date == 'null' || $dm_start_date == '0000-00-00 00:00:00') 
		{
			$dm_start_date = '';
		}
		if($dm_end_date == 'null' || $dm_end_date == '0000-00-00 00:00:00')
		{
			$dm_end_date = '';
		}
		
		$return_string = "";
		if($dm_start_date != '' && $dm_end_date != ''){
			$return_string = substr($dm_start_date,0,10)." ~ ".substr($dm_end_date,0,10);
		}else if($dm_start_date != ''){
			$return_string = "從 ".substr($dm_start_date,0,10)." 開始";
		}else if($dm_end_date != ''){
			$return_string = "到 ".substr($dm_end_date,0,10)." 結束";
		}else{
			$return_string = "無限期";
		}
		return $return_string;
	}
	
	
	/**
	 * 
	 */
	function showDateFormat($date_string ='', $date_format  = 'Y-m-d')
	{
		if(isNull($date_string))
		{
			$tmp_date = '';	
		}
		else 
		{
			$tmp_date = date($date_format ,strtotime($date_string));
			if( $tmp_date == '1970-01-01')
			{
				$tmp_date = '';	
			}
		}
		
		
		
		return $tmp_date;
	}
	
	
	/**
	 * 
	 */
	function showTwDate($date_string ='',$default_value='')
	{
		
		if(isNull($date_string))
		{
			$tmp_date = $default_value;	
		} 
		else
		{
			$tmp_date = '民國'.(date('Y' ,strtotime($date_string))-1911).'年'.date('m' ,strtotime($date_string)).'月'.date('d' ,strtotime($date_string)).'日';		
			
		}
		return $tmp_date;
	}
	
	/**
	 * 
	 */
	function showTwDate2($date_string ='',$separate='/')
	{
		
		if(isNull($date_string))
		{
			$tmp_date = '';	
		} 
		else
		{
			$tmp_date = (date('Y' ,strtotime($date_string))-1911).$separate.date('m' ,strtotime($date_string)).$separate.date('d' ,strtotime($date_string)).'日';		
			
		}
		return $tmp_date;
	}
	
	
	function twDateToDbDate($date_string ='')
	{
		$date_string = str_replace("年", "年", $date_string);		
		if (isNull($date_string)) 
		{
			return NULL;
		}
		else if (strpos($date_string, "年") === false) 
		{
			return $date_string;
		}
				
		$date_string = str_replace("民國", "", $date_string);
		$year = substr($date_string,0, strrpos($date_string, "年")); 
		$year = (int)$year + 1911;
		
		
		$date_string = $year.substr($date_string, strrpos($date_string, "年")); 
		$date_string = str_replace("年", "-", $date_string);
		
		

		if (strpos($date_string, "日") === false) 
		{
		    $date_string = str_replace("月", "-01", $date_string);
		} 
		else 
		{
			$date_string = str_replace("月", "-", $date_string);
			$date_string = str_replace("日", " ", $date_string);
		}
		
		
		
		$date_string = str_replace("時", ":", $date_string);
		$date_string = str_replace("分", ":00", $date_string);
		
		if($year<1970)
		{
			$date_string = "1970-01-01";
		}
		
		
		return $date_string;
	}
	
	
	/**
	 * 由數字組成的日期字串轉成db日期字串
	 * ex: 1010317 -> 2012-3-17
	 * ex: 670820 -> 1978-8-20
	 */
	function twNumDateToDbDate($date_string ='')
	{		
		
		if(strlen($date_string)==7 )
		{
			$year =	substr($date_string, 0,3);
			$month =	substr($date_string, 3,2);
			$day =	substr($date_string, 5,2);
			
			if($month=='02')
			{
				if($day>=29)
				{
					$day = '28';
				}
			}
			
			$date_string = ($year+1911).'-'.$month.'-'.$day;
		}
		else if(strlen($date_string)==6 )
		{
			$year =	substr($date_string, 0,2);
			$month =	substr($date_string, 2,2);
			$day =	substr($date_string, 4,2);
			
			if($month=='02')
			{
				if($day>=29)
				{
					$day = '28';
				}
			}
			
			$date_string = ($year+1911).'-'.$month.'-'.$day;
		}
		else 
		{
			$date_string = "1970-01-01";
		}	
		
		
		return $date_string;
	}
	
	
	
	
	
	
	
	/**
	 * 
	 */
	function showEffectiveDate($start_date,$end_date,$forever)
	{
		$effective_date = showDateFormat($start_date)." ~  ".showDateFormat($end_date);
		if($forever == 1)
		{
			$effective_date = showDateFormat($start_date)." ~  永久有效";
		}
		return $effective_date;
	}
	
	

	/**
	 * 取得partial view
	 */
	function showOutputBox($box_name, $data = array(),$is_echo = TRUE)
	{		
		$CI	=& get_instance();
		$backend_name = $CI->config->item('backend_name');	

		if(file_exists(set_realpath('application/views/'.$backend_name.'/box').$box_name.'.php'))
		{	
		  if($is_echo)
          {
            echo $CI->load->view($backend_name.'/box/'.$box_name, $data, TRUE);
          }
          else
          {
            return $CI->load->view($backend_name.'/box/'.$box_name, $data, TRUE);
          }
					
	 	}
	}
	
	/**
	 * 取得partial cache view 
	 */
	function showCacheOutputBox($box_name,$cache_name = '', $data = array(),$is_echo = TRUE)
	{
	    $CI =& get_instance();		

        if($is_echo)
        {
            echo $CI->template->_getFixBoxCache($box_name,$data,$cache_name );
        }
        else
        {
            return $CI->template->_getFixBoxCache($box_name,$data,$cache_name );
        }
	}
	
	
	/**
	 *	產生固定的模組區塊快取 
	 */
	function _getFixBoxCache($view_name = 'box', $data = array(),$cache_name )
	{		
		$CI =& get_instance();		
		
		$return_block_str = '';
		$enable_cache = $CI->config->item('enable_cache');
		if($enable_cache)
		{
				$physical_file = set_realpath('application/views/box').$cache_name;					
				//$check_exist_cache_file = set_realpath('application/views/box').$cache_name;
				
				$re_create_file = TRUE;
				if(file_exists($physical_file))
				{
					//超過五分鐘重新產生
					$recreate_cache_minute = 5;
					if($CI->config->item('box_cache_minute'))
					{
						$recreate_cache_minute = (int)$CI->config->item('box_cache_minute');
					}
					
					if((now() - filemtime($physical_file)) < 60 * $recreate_cache_minute )
					{
						$re_create_file = FALSE;
						$return_block_str = $CI->load->view('box/mycache/'.$cache_name, null, TRUE);		
					}
					
				}
				
				if($re_create_file)
				{
					$return_block_str = $CI->load->view($view_name, $data, TRUE);
					// try to open the file
					$ourFileHandle = fopen($physical_file, 'w') or die("can't open file");									
					// obtain a file lock to stop corruptions occuring
					flock($ourFileHandle, 2); // LOCK_EX
					// write serialized data
					fputs($ourFileHandle, $return_block_str);
					// release the file lock
					flock($ourFileHandle, 3); // LOCK_UN
					fclose($ourFileHandle);
				}						
		}
		else
		{
			$return_block_str = $CI->load->view($view_name, $data, TRUE);
		}	
		
		return $return_block_str;
	}

	/**
	 * 產生cache box view
	 */
	function createCacheBoxView($view_content,$physical_file)
	{
		// try to open the file
		$ourFileHandle = fopen($physical_file, 'w') or die("can't open file");									
		// obtain a file lock to stop corruptions occuring
		flock($ourFileHandle, 2); // LOCK_EX
		// write serialized data
		fputs($ourFileHandle, $view_content);
		// release the file lock
		flock($ourFileHandle, 3); // LOCK_UN
		fclose($ourFileHandle);
	}
	
	

	function convertDate2Long($raw_date ,$output_format = 'Y-m-d H:i:s') 
	{
		if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') )
		{
			return false;
		} 
		
		$year = (int)substr($raw_date, 0, 4);
		$month = (int)substr($raw_date, 5, 2);
		$day = (int)substr($raw_date, 8, 2);
		$hour = (int)substr($raw_date, 11, 2);
		$minute = (int)substr($raw_date, 14, 2);
		$second = (int)substr($raw_date, 17, 2);
		
		return strftime(date($output_format, mktime($hour,$minute,$second,$month,$day,$year)));
	}


	function convertDate2Short($raw_date,$output_format ='Y-m-d') 
	{
		if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

		$year = substr($raw_date, 0, 4);
		$month = (int)substr($raw_date, 5, 2);
		$day = (int)substr($raw_date, 8, 2);
		$hour = (int)substr($raw_date, 11, 2);
		$minute = (int)substr($raw_date, 14, 2);
		$second = (int)substr($raw_date, 17, 2);

		if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
		  return date($output_format, mktime($hour, $minute, $second, $month, $day, $year));
		} else {
		  return ereg_replace('2037' . '$', $year, date($output_format, mktime($hour, $minute, $second, $month, $day, 2037)));
		}
	}

   function getFormatDate($output_format ='Y/m/d',$add_year = 0,$add_month = 0,$add_day = 0,$add_hour = 0,$add_minute = 0,$add_second = 0,$input_date = '') 
   {
		if(isNotNull($input_date)){
			    if ($input_date == '0000-00-00 00:00:00' ) return false;
				$year = substr($input_date, 0, 4);
				$month = (int)substr($input_date, 5, 2);
				$day = (int)substr($input_date, 8, 2);
				$hour = (int)substr($input_date, 11, 2);
				$minute = (int)substr($input_date, 14, 2);
				$second = (int)substr($input_date, 17, 2);				
		}else{
				$year = date('Y');
				$month = date('n');
				$day =  date('d');
				$hour = date('H');
				$minute =  date('i');
				$second = date('s');
		}	
		return date($output_format, mktime($hour+$add_hour, $minute+$add_minute, $second+$add_second, $month+$add_month, $day+$add_day, $year+$add_year));
   }

   

   function getRemainTime($check_datetime,$compare_datetime = '',$brief_display = FALSE)
   {
		$return_result = '';
		$brief_display_str = '';
		if(isNull($compare_datetime))
		{
			$compare_datetime = tep_get_date('Y/m/d H:i:s');
		}		
		$remain_sec = strtotime($check_datetime) - strtotime($compare_datetime); 
		if($remain_sec <= 0){
			$return_result = $brief_display_str = "<span class='alert_red'>已結束</span>";
		}elseif($remain_sec > 0 && $remain_sec < 60){
			$return_result = $remain_sec . '秒';			
			$brief_display_str = "<span class='alert_red'>< 1分</span>";
		}elseif($remain_sec > 60 && $remain_sec < 60 * 60){
			$min_value = round($remain_sec/60);
			$sec_value = $remain_sec-$min_value*60;
			$return_result = $min_value. '分' . $sec_value . '秒';
			$brief_display_str = "<span class='alert_red'>".$min_value. '分</span>';
		}elseif($remain_sec > 60*60 && $remain_sec < 60 * 60 * 24){
			$hour_value = round($remain_sec/(60*60));
			$min_value = round(($remain_sec-$day_value*60*60*24- $hour_value*60*60)/60);
			$sec_value = $remain_sec - $hour_value*60*60 -$min_value*60;			
			$return_result = $hour_value. '小時' .$min_value. '分' . $sec_value . '秒';
			$brief_display_str = $hour_value. '小時' .$min_value. '分';
		}else{
			$day_value = (int)($remain_sec/(60*60*24));
			$hour_value = (int)(($remain_sec-$day_value*60*60*24)/(60*60));
			$min_value = (int)(($remain_sec-$day_value*60*60*24- $hour_value*60*60)/60);
			$sec_value = $remain_sec - $day_value*60*60*24- $hour_value*60*60 -$min_value*60;			
			$return_result = $day_value. '天' . $hour_value. '小時' .$min_value. '分' . $sec_value . '秒';
			$brief_display_str = $day_value. '天' . $hour_value. '小時';
		}
		
		if($brief_display == TRUE){
			$return_result = $brief_display_str;
		}
		
		return $return_result;
   }
   
   /**
    *  use for sort array
    */   
   function sortArray($array, $id="id", $sort_ascending=true) 
   {
        $temp_array = array();
        while(count($array)>0) 
        {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) 
            {
                if (isset($item[$id])) 
                {
					if ($array[$lowest_id][$id]) 
					{
						if ($item[$id]<$array[$lowest_id][$id]) 
						{
							$lowest_id = $index;
						}
					}
                                
				}
                $index++;
            }
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
		
		if ($sort_ascending) 
		{
			return $temp_array;
		} 
		else 
		{
			return array_reverse($temp_array);
		}
    }

	function recursiveMkdir($path, $mode = 0777) 
	{				
		$path = str_replace('/',DIRECTORY_SEPARATOR,$path);
		$dirs = explode(DIRECTORY_SEPARATOR , $path);
		$count = count($dirs);
		$path = '.';
		for ($i = 0; $i < $count; ++$i) 
		{
			$path .= DIRECTORY_SEPARATOR . $dirs[$i];			
			if (!is_dir($path) && !mkdir($path, $mode)) {
				return false;
			}
		}
		return true;	
	}
	

	function removeDir($current_dir) 
	{
    
        if($dir = @opendir($current_dir)) 
        {
            while (($f = readdir($dir)) !== false) 
            {
                if($f > '0' and filetype($current_dir.$f) == "file") 
                {
                    unlink($current_dir.$f);
                } 
                elseif($f > '0' and filetype($current_dir.$f) == "dir") 
                {
                    remove_dir($current_dir.$f."\\");
                }
            }
            closedir($dir);
            rmdir($current_dir);
        }
    }
	

	/**
	 * 
	 */
	function unicode_str_2_utf8($str) 
	{
		//$str = ChgToHalfStr($str);
		$strlen1="";
		$str1=explode('&#',$str);
		$str3 ='';
		if(count($str1)>1){
		  foreach($str1 as $k =>$v){
			$str2=explode(';',$v);
			 if(count($str2)>1){
			  foreach($str2 as $k1 =>$v1){
			   if(is_numeric($v1)){
				$strlen1.='     ';
				$unicodeHtml = iconv("ucs-2", "utf-8", hex2bin(base_convert($v1, 10, 16))); 
			   }else{
				$unicodeHtml = iconv("big5","utf-8",$v1);
			   }
			   $str3.=$unicodeHtml;
			  }
			 }else{
			  $str3.=$v;
			  $str3=iconv("big5","utf-8",$str3);
			 }
			 $str4=$str3;
		  }
		}else{
		 $str4=iconv("big5","utf-8",$str);
		} 
		return ($str4);
	}
	
	
	/**
	 * 數字轉國字大寫文字函數 
	 */
	function num_upper($val)
	{
		$_cost_upper=array('0'=>'零','1'=>'壹','2'=>'貳','3'=>'參','4'=>'肆','5'=>'伍','6'=>'陸','7'=>'柒','8'=>'捌','9'=>'玖');
		$_cost_number = array('1'=>'元','2'=>'拾','3'=>'佰','4'=>'仟','5'=>'萬','6'=>'拾萬','7'=>'佰萬','8'=>'仟萬','9'=>'億');
		$new_num="";
		for ($ii=0 ; $ii < strlen($val) ; $ii++ ){
		   $new_num.=$_cost_upper[$val[$ii]];
		   $new_num.=$_cost_number[(strlen($val) - $ii)];
		}
		return $new_num;
   }

    

	
	function dprint($ary, $line=0)
	{
		echo '<pre style="text-align:left; white-space: pre-wrap;">';
		print_r($ary);
		if ($line > 0) {
			echo '&nbsp;( @'.$line.' )';
		}
		echo '</pre>';
	}
	
	
	



	function showBackendPager($pager)
	{
		$pager_html = '';
		if(is_array($pager) && ($pager['total_rows'] > $pager['per_page_rows']))
		{
			$btn_previous = '<a href="javascript:void()">&lt;</a>';
			$btn_next = '<a href="javascript:void()">&gt;</a>';
			
			$btn_per_pages = '';
			if($pager['has_previous'])
			{
				$btn_previous = '<a href="'.bUrl($pager["action"] , TRUE, array("page"),  array("page" => ($pager['page'] - 1))).'">&lt;</a>';
			}
			
			if($pager['has_next'])
			{
				$btn_next = '<a href="'.bUrl($pager["action"], TRUE ,array("page"),  array("page" => ($pager['page'] + 1))).'" >&gt;</a>';
			}
			
			foreach($pager['page_range'] as $per_page)
			{
				if($pager['page'] == $per_page)
				{
					$btn_per_pages .= '<div>'.$per_page.'</div>';
				}
				else
				{
					$btn_per_pages .= '<a href="'.bUrl($pager["action"], TRUE ,array("page"), array("page"=>$per_page)).'" title="'.$per_page.'">'.$per_page.'</a>';
				}
			}
			
			
			$pager_html =
			'
	        <div > '.$pager["total_count"].'，Page '.$pager["page"].' of '.$pager["page_num"].'</div>
	        
            <div class="pagination">
            	<a href="'.bUrl($pager["action"], TRUE ,array("page"), array("page"=> 1)).'" '.($pager['page']==1?'class="disabled"':'').'  >&laquo;</a>                

                '.$btn_previous.$btn_per_pages.$btn_next.'
                
                <a href="'.bUrl($pager["action"], TRUE ,array("page"), array("page"=> $pager["page_num"])).'"  '.($pager['page']==$pager["page_num"]?'class="disabled"':'').'  >&raquo;</a>
            </div>
			';
		}

		return $pager_html;
	}


	function showFrontendPager($pager)
	{
		$pager_html = '';
		if(isNotNull($pager) && is_array($pager) && ($pager['total_rows'] > $pager['per_page_rows']))
		{
			$btn_previous = '<a href="javascript:void()"><i class="fa fa-chevron-left"></i></a>';
			$btn_next = ' <a href="javascript:void()"><i class="fa fa-chevron-right"></i></a>';
			
			$btn_per_pages = '';
			if($pager['has_previous'])
			{
				$btn_previous = '<a href="'.fUrl($pager["action"] , TRUE, array("page"),  array("page" => ($pager['page'] - 1))).'"><i class="fa fa-chevron-left"></i></a>';
			}
			
			if($pager['has_next'])
			{
				$btn_next = ' <a href="'.fUrl($pager["action"], TRUE ,array("page"),  array("page" => ($pager['page'] + 1))).'" ><i class="fa fa-chevron-right"></i></a>';
			}
			
			foreach($pager['page_range'] as $key => $per_page)
			{
				$int_page = $per_page;
				$per_page = $per_page;
				if($pager['page'] == $per_page)
				{
					$btn_per_pages .= ' <div>'.$per_page.'</div>';
				}
				else
				{
					if($per_page === '&hellip;')
					{
						$int_page = intval(($pager['page_range'][$key-1] + $pager['page_range'][$key+1] ) / 2);
						
					}
					$btn_per_pages .= ' <a href="'.fUrl($pager["action"], TRUE ,array("page"), array("page"=>$int_page)).'" title="'.$per_page.'">'.$per_page.'</a>';
				}
			}
			
			
			$pager_html =
			'
			<div class="pager">
				'.$btn_previous.$btn_per_pages.$btn_next.'	
			</div>
			';
		}

		return $pager_html;
	}
	
	function alertAndReplace($str='', $url=FALSE)
	{
		if( ! $url)
		{
			$url = $_SERVER["PHP_SELF"];
		}
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<script>';
		if($str != '')
		{
			echo 'alert("'.$str.'");';
		}
		echo 'location.replace("'.$url.'");';
		echo '</script>';
		exit();
	}
      

      
      
      