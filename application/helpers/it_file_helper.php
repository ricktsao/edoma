<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

if ( ! function_exists('file_core_name'))
{
	function file_core_name($file_name)
	{
		$exploded = explode('.', $file_name);
 
		// if no extension
		if (count($exploded) == 1)
		{
			return $file_name;
		}
 
		// remove extension
		array_pop($exploded);
 
		return implode('.', $exploded);
	}
}
 
/* 
  file extension 
  ex: file_extension('toto.jpg') -> 'jpg'
*/
 
if ( ! function_exists('file_extension'))
{
	function file_extension($path)
	{
		$extension = substr(strrchr($path, '.'), 1);
		return $extension;
	}
}
 
/* 
  file size 
  ex: file_size('toto.jpg') -> '3.3 MB'
*/
if ( ! function_exists('file_size'))
{
	function file_size($path)
	{
		$num = filesize($path);
 
		// code from byte_format()
		$CI =& get_instance();
		$CI->lang->load('number');
 
		$decimals = 1;
 
		if ($num >= 1000000000000) 
		{
			$num = round($num / 1099511627776, 1);
			$unit = $CI->lang->line('terabyte_abbr');
		}
		elseif ($num >= 1000000000) 
		{
			$num = round($num / 1073741824, 1);
			$unit = $CI->lang->line('gigabyte_abbr');
		}
		elseif ($num >= 1000000) 
		{
			$num = round($num / 1048576, 1);
			$unit = $CI->lang->line('megabyte_abbr');
		}
		elseif ($num >= 1000) 
		{
			$decimals = 0; // decimals are not meaningful enough at this point
 
			$num = round($num / 1024, 1);
			$unit = $CI->lang->line('kilobyte_abbr');
		}
		else
		{
			$unit = $CI->lang->line('bytes');
			return number_format($num).' '.$unit;
		}
 
		$str = number_format($num, $decimals).' '.$unit;
 
		$str = str_replace(' ', '&nbsp;', $str);
		return $str;
	}
}
 
 	
	
/*
縮圖專用 by Ted
*/
if ( ! function_exists('get_resize_img'))
{
	function get_resize_img($path,$new_width,$new_height,$r=255,$g=255,$b=255,$new_path='',$new_file_name='',$new_ext=''){
		$allow_ext = array('jpg','jpeg','png','gif');
		if( is_array($path) ){
			$filename = explode("/", basename($path["type"]));
			$file_ext = strtolower($filename[count($filename)-1]);
			$path = $path["tmp_name"];
		}else{
			$filename = explode(".", basename($path));
			$file_ext = strtolower($filename[count($filename)-1]);
		}
		if( !is_file($path) && !in_array($file_ext,$allow_ext) ) return false;
		switch($file_ext)
		{
			case "jpg":
			case "jpeg":
				$src_img = imagecreatefromjpeg($path);
				break;
			case "png":
				$src_img = imagecreatefrompng($path);
				break;
			case "gif":
				$src_img = imagecreatefromgif($path);
				break;
		}
		$width=imagesx($src_img);
		$height=imagesy($src_img);
		
		// Build the thumbnail
		if( $new_height && $new_width ){
			$new_ratio = $new_width / $new_height;
			$img_ratio = $width / $height;
			if ($new_ratio > $img_ratio && $height>$new_height ) {
				$img_height = $new_height;
				$img_width = $img_ratio * $new_height;
			} elseif( $new_ratio < $img_ratio && $width>$new_width ) {
				$img_height = $new_width / $img_ratio;
				$img_width = $new_width;
			}else{
				if( $width>$new_width ){
					$img_height = $new_height;
					$img_width = $new_width;
				}else{
					$img_height = $height;
					$img_width = $width;
				}
			}
		}elseif( !$new_height && !$new_width ){
			$new_height = $height;
			$new_width = $width;
			$img_height = $new_height;
			$img_width = $new_width;
		}else{
			if( !$new_height ){
				if( $width>$new_width ){
					$new_height = $height * ($new_width/$width);
				}else{
					$new_height = $height;
				}
			}else{
				if( $height>$new_height ){
					$new_width = $width * ($new_height/$height);
				}else{
					$new_width = $width;
				}
			}
			$img_height = $new_height;
			$img_width = $new_width;
		}
		
		$new_img = ImageCreateTrueColor($new_width, $new_height);
		$bg = imagecolorallocate($new_img, $r, $g, $b);
		imagefilledrectangle($new_img, 0, 0, $new_width-1, $new_height-1, $bg);
		imagecopyresampled($new_img, $src_img, ($new_width-$img_width)/2, ($new_height-$img_height)/2, 0, 0, $img_width, $img_height, $width, $height);
		
		if( !$new_ext ){
			$new_ext = $file_ext;
		}
		if( !$new_file_name ){
			unset($filename[count($filename)-1]);
			$new_file_name = implode(".",$filename);
		}
		$path = explode("/",$path);
		if( !$new_path ){
			unset($path[count($path)-1]);
			$new_path = implode("/",$path);
		}
		$dst = $new_path."/".$new_file_name.".".$new_ext;
		
		switch($new_ext)
		{
			case "jpg":
			case "jpeg":
				imagejpeg($new_img,$dst,80);
				break;
			case "png":
				imagepng($new_img,$dst);
				break;
			case "gif":
				imagegif($new_img,$dst);
				break;
		}
		return true;
	}
}


	/**
	 * 將DB圖片file name 轉成url 路徑
	 */	
	function img_show_list(&$list = array(),$img_name = "img_filename",$folder = "product")
	{
		if(isNotNull($list) && count($list) > 0)
		for($i=0; $i<count($list); $i++)
		{
			$list[$i]["orig_".$img_name] = $list[$i][$img_name];
			$list[$i][$img_name] = isNotNull($list[$i][$img_name])?base_url()."upload/website/".$folder."/".$list[$i][$img_name]:"";
		}
	}
 
	/**
	 * 圖片處理 
	 */
	function deal_content_img(&$arr_data = array(),$img_config = array(), $filename='img_filename',$folder = "product")
	{  
		$del_filename = tryGetData("del_".$filename,$arr_data);
		$new_filename = tryGetData($filename,$arr_data);
		$orig_filename = tryGetData("orig_".$filename,$arr_data);
		
		
		if($del_filename == "1")
		{
			$arr_data[$filename] = NULL;
			unlink(set_realpath("upload/website/".$folder).$orig_filename);
		}
		else if( $del_filename != "1")
		{
			$CI	=& get_instance();
			$CI->load->library('upload',$img_config);		
			
		
			if ( ! $CI->upload->do_upload($filename))
			{	
				//$arr_data["error"] = $CI->upload->display_errors();				
				//$arr_data[$filename] = "";
				//是否需log
			}
			else
			{
				$upload_data = $CI->upload->data();
				
				//dprint($upload_data);
				//exit;
				$arr_data[$filename] =  resize_img($upload_data['full_path'],$img_config['resize_setting']);				
				//$arr_data[$filename] = $upload_data["file_name"];
			}			
		}
	}
 
 
	/**
	 * 圖片處理 
	 */
	function deal_single_img(&$arr_data = array(),$img_config = array(), $edit_data = array(),$filename='img_filename',$folder = "product")
	{  
		$del_filename = tryGetData("del_".$filename,$edit_data);
		$new_filename = tryGetData($filename,$edit_data);
		$orig_filename = tryGetData("orig_".$filename,$edit_data);
		
		
		if($del_filename == "1")
		{
			$arr_data[$filename] = NULL;
			unlink(set_realpath("upload/website/".$folder).$orig_filename);
		}
		else if( $del_filename != "1")
		{
			$CI	=& get_instance();
			$CI->load->library('upload',$img_config);		
			
		
			if ( ! $CI->upload->do_upload($filename))
			{	
				//$arr_data["error"] = $CI->upload->display_errors();				
				//$arr_data[$filename] = "";
				//是否需log
			}
			else
			{
				$upload_data = $CI->upload->data();
				
				//dprint($upload_data);
				//exit;
				$arr_data[$filename] =  resize_img($upload_data['full_path'],$img_config['resize_setting']);				
				//$arr_data[$filename] = $upload_data["file_name"];
			}			
		}
	}
	
	
	function deal_single_img2(&$arr_data = array(),$img_config = array(), $edit_data = array(),$filename='img_filename',$folder = "product")
	{  
		
		$del_filename = tryGetData("del_".$filename,$edit_data);
		$new_filename = tryGetData($filename,$edit_data);
		$orig_filename = tryGetData("orig_img_filename2",$edit_data);
		
		
		if($del_filename == "1")
		{
			$arr_data[$filename] = NULL;
			unlink(set_realpath("upload/website/".$folder).$orig_filename);
		}
		else if( $del_filename != "1")
		{
			$CI	=& get_instance();
			$CI->load->library('upload',$img_config);		
			
		
			if ( ! $CI->upload->do_upload($filename))
			{	
				//$arr_data["error"] = $CI->upload->display_errors();				
				//$arr_data[$filename] = "";
				//是否需log
			}
			else
			{
				$upload_data = $CI->upload->data();
				
				//dprint($upload_data);
				//exit;
				$arr_data["img_filename2"] =  resize_img($upload_data['full_path'],$img_config['resize_setting']);				
				//$arr_data[$filename] = $upload_data["file_name"];
			}			
		}
	}
	
	/**
	 * 圖片處理 
	 */
	function deal_ajax_img(&$arr_data = array(),$img_config = array(),$filename='img_filename')
	{  
	
		$uploadedUrl = './upload/tmp/' . $_FILES['fileUpload2']['name'][$key];
		$arr_data[$filename] =  resize_img($uploadedUrl,$img_config['resize_setting']);		
	}
 


	 /**
	 * 圖片處理 
	 * 20120906 Hans
	 * 20160430 modify by Rick 增加縮圖比例判斷,目錄位置
	 */
	function resize_img($filename,$imgInfo,$sys_folder = 'website',$folder_name = '')
	{      	
		
		if (!is_dir(set_realpath("upload/".$sys_folder)))
		{
			mkdir(set_realpath("upload/".$sys_folder),0777);
		}  
	
		if(isNull($folder_name))
		{
			$dest_filename = date( "YmdHis" )."_".rand( 100000 , 999999 ).".".file_extension($filename);	
		}
		else
		{
			$dest_filename = $folder_name;
		}
		
		
		if(is_array($imgInfo))
		{
			
			foreach($imgInfo as $key => $value)
			{				
				if (!is_dir(set_realpath("upload/".$sys_folder."/".$key)))
				{
					mkdir(set_realpath("upload/".$sys_folder."/".$key),0777);
				}
				
				$dest_file = set_realpath("upload/".$sys_folder."/".$key).$dest_filename;
				//move_uploaded_file($filename,$dest_file);
				copy($filename,$dest_file);	
			
				$maxWidth = $value[0];
				$maxHeight= $value[1];
			
				//$iw=$value[0];
				//$ih=$value[1];			
				
				
				//$destInfo  = pathinfo($filename); 
				//dprint($destInfo);exit;
				$srcSize   = getimagesize($filename); //圖檔大小 
				$srcRatio  = $srcSize[0]/$srcSize[1]; // 計算寬/高 
				$destRatio = $maxWidth/$maxHeight; 
				if ($destRatio > $srcRatio) 
				{ 
					$ih = $maxHeight; 
					$iw = $maxHeight*$srcRatio; 
				} 
				else 
				{ 
					$iw = $maxWidth; 
					$ih = $maxWidth/$srcRatio; 
				} 
				
				
				//echo $dest_file;	
				//echo $iw;	
				//echo $ih;
				if(is_numeric($iw) && is_numeric($ih) && $iw > 0 && $ih > 0)
				{
					$config['image_library']  = 'gd2';
					$config['source_image']	  = $dest_file;
					$config['create_thumb']   = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['master_dim'] = 'width';
					$config['width']	      = $iw;
					$config['height']	      = $ih;	
					
					
					$CI	=& get_instance();
					$CI->load->library('image_lib');
					$CI->image_lib->initialize($config);
					$CI->image_lib->resize();
					$CI->image_lib->clear();
				}		
			}
		}
			
		return $dest_filename;
	}
 
 	
	/*  Convert image size. true color*/ 
	//$src        來源檔案 
	//$dest        目的檔案 
	//$maxWidth    縮圖寬度 
	//$maxHeight    縮圖高度 
	//$quality    JPEG品質 
	function ImageCopyResizedTrue($src,$dest,$maxWidth,$maxHeight,$quality=100) { 
	  //檢查檔案是否存在 
	  if (file_exists($src)  && isset($dest)) { 
	      // 目錄是否存在
	      $dir = dirname($dest);
	      if (!is_dir($dir)){
	        mkdir($dir,0777);
	      }
	      
	      $destInfo  = pathinfo($dest); 
	      $srcSize   = getimagesize($src); //圖檔大小 
	      $srcRatio  = $srcSize[0]/$srcSize[1]; // 計算寬/高 
	      $destRatio = $maxWidth/$maxHeight; 
	      if ($destRatio > $srcRatio) { 
	          $destSize[1] = $maxHeight; 
	          $destSize[0] = $maxHeight*$srcRatio; 
	      } 
	      else { 
	          $destSize[0] = $maxWidth; 
	          $destSize[1] = $maxWidth/$srcRatio; 
	      } 
	 
	      //GIF 檔不支援輸出，因此將GIF轉成JPEG 
	      if ($destInfo['extension'] == "gif") $dest = substr_replace($dest, 'jpg', -3); 
	 
	      //建立一個 True Color 的影像 
	      $destImage = imagecreatetruecolor($destSize[0],$destSize[1]); 
	 
	      //根據副檔名讀取圖檔 
	      switch ($srcSize[2]) { 
	          case 1: $srcImage = imageCreateFromGif($src); break; 
	          case 2: $srcImage = imageCreateFromJpeg($src); break; 
	          case 3: $srcImage = imageCreateFromPng($src); break; 
	          default: return false; break; 
	      } 
	 
	      //取樣縮圖 
	      imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1], 
	                          $srcSize[0],$srcSize[1]); 
	 
	      //輸出圖檔 
	      switch ($srcSize[2]) { 
	          case 1: case 2: imagejpeg($destImage,$dest,$quality); break; 
	          case 3: imagepng($destImage,$dest); break; 
	      } 
	      return true; 
	  } 
	  else { 
	      return false; 
	  } 
	}
	
	
	
	
 
 	/**
	 * 縮圖 
	 */
	function do_resize_img($filename,$dest_folder,$width =0,$height = 0,$is_random = TRUE)
	{
		
		if($is_random)
		{
			$dest_filename = $dest_folder."_".date( "YmdHis" )."_".rand( 100000 , 999999 ).".".file_extension($filename);
		}
		else 
		{
			$file_info =  pathinfo($filename);
			$dest_filename = $file_info['basename']; 
		}
		
		
		$dest_file = set_realpath("upload/website/".$dest_folder).$dest_filename;
		
	
		copy($filename,$dest_file);
		
		if($width !== 0 && $height !== 0)
		{
			$config['image_library']  = 'gd2';
			$config['source_image']	  = $dest_file;
			$config['create_thumb']   = FALSE;
			$config['maintain_ratio'] = TRUE;
			$config['width']	      = $width;
			$config['height']	      = $height;	
			
			$CI	=& get_instance();
			$CI->load->library('image_lib', $config); 			
			$CI->image_lib->resize();
		}		
		
		return $dest_filename;
	}
 
 
 
 
 	/**
	 * 圖片處理 
	 */
	function deal_img(&$edit_data = array(),$filename='img_filename',$folder = "product",$is_random = TRUE)
	{

		
		$CI	=& get_instance();  
	
		if (!file_exists("upload/website/".$folder)) {
		    mkdir("upload/website/".$folder, 0777, true);
		}
	
		$del_filename = $CI->input->post("del_image_".$filename,TRUE);
		$new_filename = tryGetData($filename,$edit_data);
		$new_filename = str_replace(" ", "%20", $new_filename);//防止檔名有空白處理
		$orig_filename = $CI->input->post("orig_".$filename,TRUE);
		

		
		if($del_filename == "1")
		{			
			$edit_data[$filename] = NULL;
			
			//echo set_realpath("upload/website/".$folder).$orig_filename;
			//exit;
			unlink(set_realpath("upload/website/".$folder).$orig_filename);
		}
		else if(isNotNull($new_filename) && strrpos($new_filename, $orig_filename) === FALSE && $del_filename != "1")
		{
			
			if(isNotNull($orig_filename))
			{
				unlink(set_realpath("upload/website/".$folder).$orig_filename);
			}
			$dest_filename = do_resize_img($new_filename,"".$folder,0,0,$is_random);	
			$edit_data[$filename] = $dest_filename;
		}	
	}
	
	
	
	/**
	 * 圖片刪除 
	 */
	function del_img($edit_data = array(),$filename='img_filename',$folder = "product")
	{
		$CI	=& get_instance();  	
		$del_filename = $CI->input->post("del_image_".$filename,TRUE);
		$orig_filename = $CI->input->post("orig_".$filename,TRUE);	
		$new_filename = tryGetData($filename,$edit_data);
		$new_filename = str_replace(" ", "%20", $new_filename);//防止檔名有空白處理
		
		
		if($del_filename == "1" && isNotNull($orig_filename))
		{
			@unlink(set_realpath("upload/website/".$folder).$orig_filename);				
		}
		
		if(isNotNull($new_filename) && strrpos($new_filename, $orig_filename) === FALSE)
		{	
			@unlink(set_realpath("upload/website/".$folder).$orig_filename);		
		}
	}



	/**
	 * 圖片處理 
	 */
	function deal_file(&$edit_data = array(),$filename='img_filename',$folder = "product",$is_random = TRUE)
	{

		
		$CI	=& get_instance();  
	
		if (!file_exists("upload/website/".$folder)) {
		    mkdir("upload/website/".$folder, 0777, true);
		}
	
		$del_filename = $CI->input->post("del_image_".$filename,TRUE);
		$new_filename = tryGetData($filename,$edit_data);
		$new_filename = str_replace(" ", "%20", $new_filename);//防止檔名有空白處理
		$orig_filename = $CI->input->post("orig_".$filename,TRUE);
		

		
		if($del_filename == "1")
		{			
			$edit_data[$filename] = NULL;
			
			//echo set_realpath("upload/website/".$folder).$orig_filename;
			//exit;
			unlink(set_realpath("upload/website/".$folder).$orig_filename);
		}
		else if(isNotNull($new_filename) && strrpos($new_filename, $orig_filename) === FALSE && $del_filename != "1")
		{
			
			if(isNotNull($orig_filename))
			{
				unlink(set_realpath("upload/website/".$folder).$orig_filename);
			}
			
			$file_info =  pathinfo(tryGetData($filename, $edit_data) );
			$dest_filename = $file_info['basename']; 
			$dest_file = set_realpath("upload/website/".$folder).$dest_filename;
			
			
			//$new_filename = 'http://ch0082/chupei/upload/media/新北市_新莊知識園區/1041221_新知段OK.xlsx';

			//$dest_file = 'E:\htdocs\chupei\upload\website\plan_file/1041221_新知段OK.xlsx';
			
			//dprint($new_filename);
			//dprint($dest_file);
			//exit;
			copy( $new_filename,$dest_file);
			$edit_data[$filename] = $dest_filename;
		}	
	}






	function php_file_tree($directory, $return_link, $extensions = array()) {
		// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
		// Remove trailing slash
		$code = '';
		if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
		$code .= php_file_tree_dir($directory, $return_link, $extensions);
		return $code;
	}

	function php_file_tree_dir($directory, $return_link, $extensions = array(), $first_call = true) {
		error_reporting(0);
		
		$php_file_tree = "";
		
		
		// Recursive function called by php_file_tree() to list directories/files
		
		// Get and sort directories/files
		if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
	
		natcasesort($file);
	
		// Make directories first
		$files = $dirs = array();
		foreach($file as $this_file) {
	
			if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
		}
		$file = array_merge($dirs, $files);
		
		// Filter unwanted extensions
		if( !empty($extensions) ) {
			foreach( array_keys($file) as $key ) {
				if( !is_dir("$directory/$file[$key]") ) {
					$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
					if( !in_array($ext, $extensions) ) unset($file[$key]);
				}
			}
		}
	
	
		
		if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
			$php_file_tree = "<ul";
			if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
			$php_file_tree .= ">";
			foreach( $file as $this_file ) {
				if( $this_file != "." && $this_file != ".." ) {
	
					if( is_dir("$directory/$this_file") ) {
						// Directory
						$php_file_tree .= "<li class=\"pft-directory\"><a href=\"#\">" . htmlspecialchars(mb_convert_encoding($this_file , "UTF-8", "big5")) . "</a>";
						$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false);
						$php_file_tree .= "</li>";
					} else {
						// File
						// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
						$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
						$link = str_replace("[link]", htmlspecialchars(mb_convert_encoding($directory , "UTF-8", "big5")) ."/". htmlspecialchars(mb_convert_encoding($this_file , "UTF-8", "big5")) , $return_link);
						$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\"><a href=\"$link\">" . htmlspecialchars(mb_convert_encoding($this_file , "UTF-8", "big5")) . "</a></li>";
					}
				}
			}
			$php_file_tree .= "</ul>";
		}
		return $php_file_tree;
	}

	// For PHP4 compatibility
	function php4_scandir($dir) {
	
		$dh  = opendir($dir);
	
		while( false !== ($filename = readdir($dh)) ) {
		    $files[] = $filename;
		}
		sort($files);
		return($files);
	}




 	/**
	 * 刪除資料夾及檔案
	 */
	function deleteDir($dirPath) 
	{
	    if (! is_dir($dirPath)) {
	        //throw new InvalidArgumentException("$dirPath must be a directory");
	        return;
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}


/* End of file MY_file_helper.php */
/* Location: ./system/application/helpers/MY_file_helper.php */