<!DOCTYPE html>

<head>
		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="<?php echo site_url()?>template/backend/elfinder/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo site_url()?>template/backend/elfinder/css/theme.css">
		
		<!-- elFinder JS (REQUIRED) -->
		<script src="<?php echo site_url()?>template/backend/elfinder/js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script src="<?php echo site_url()?>template/backend/elfinder/js/i18n/elfinder.zh_TW.js"></script>


		<!--tiny mce-->
		<script type="text/javascript" src="<? echo site_url()?>template/backend/js/tinymce/tinymce.js"></script>






		<script type="text/javascript">
		  var FileBrowserDialogue = {
		    init: function() {
		      // Here goes your code for setting your custom things onLoad.
		    },
		    mySubmit: function (URL) {
		      // pass selected file path to TinyMCE
		      parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);
		
		      // close popup window
		      parent.tinymce.activeEditor.windowManager.close();
		    }
		  }
		
		  $().ready(function() {
		    var elf = $('#elfinder').elfinder({
		      // set your elFinder options here
		      url: '<?php echo bUrl("loadElfinder");?>',  // connector URL
		      lang: 'zh_TW',             // language (OPTIONAL)
		      uploadMaxSize: '10M',
		      getFileCallback: function(file) { // editor callback
		// actually file.url - doesnt work for me, but file does. (elfinder 2.0-rc1)
		        FileBrowserDialogue.mySubmit(file); // pass selected file path to TinyMCE 
		      }
		    }).elfinder('instance');      
		  });
		</script>
</head>









<div id="elfinder"></div>
