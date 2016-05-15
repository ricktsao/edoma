<?php
// PHP File Tree Demo
// For documentation and updates, visit http://abeautifulsite.net/notebook.php?article=21

// Main function file
//include("php_file_tree.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>PHP File Tree Demo</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link href="<?php echo site_url();?>template/backend/css/filetree.css" rel="stylesheet" type="text/css" media="screen" />
		
		<!-- Makes the file tree(s) expand/collapsae dynamically -->
		<script src="<?php echo site_url();?>template/backend/js/jquery-1.11.1.min.js" type="text/javascript"></script>
		<script src="<?php echo site_url();?>template/backend/js/php_file_tree_jquery.js" type="text/javascript"></script>
	</head>

	<body>		
		<?php
		
		// This links the user to http://example.com/?file=filename.ext
		//echo php_file_tree($_SERVER['DOCUMENT_ROOT'], "http://example.com/?file=[link]/");

		// This links the user to http://example.com/?file=filename.ext and only shows image files
		$allowed_extensions = array("gif", "jpg", "jpeg", "png");
		//echo php_file_tree($_SERVER['DOCUMENT_ROOT'], "http://example.com/?file=[link]/", $allowed_extensions);
		
		// This displays a JavaScript alert stating which file the user clicked on
		
		
		$doc_root =	$_SERVER['DOCUMENT_ROOT'];		
		$local_url_root = $_SERVER['HTTP_HOST'];
		
		$media_folder = "media";
		
		if(tryGetData("media_folder", $edit_data) != "")
		{
			$media_folder = tryGetData("media_folder", $edit_data);
		}
		
		if($local_url_root == 'web.chupei.com.tw' || $local_url_root == '118.163.146.74' || $local_url_root == 'web-01')
		{
			$file_path = "/upload/".$media_folder;
		}
		else 
		{
			$file_path = "/chupei/upload/".$media_folder;
		}
		
		

		//echo php_file_tree($_SERVER['DOCUMENT_ROOT']."/test_imgs", "javascript:parent.getFilename('[link]');");
		echo php_file_tree($doc_root.$file_path, "javascript:show_img('[link]','".$doc_root."','".$local_url_root."');");
		
		?>
		
		<div id="img_area">
			<input type="button" value="選擇" class="select_img" style="margin-bottom:10px;"/>
			<div id="img_view"></div>
			<input type="button" value="選擇" class="select_img" />
		</div>

	</body>
	
</html>
