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

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			$(document).ready(function() {
				$('#elfinder').elfinder({
					url : '<?php echo bUrl("loadElfinder");?>',  // connector URL (REQUIRED)
					lang: 'zh_TW'
				});
			});
		</script>

</head>
<body>

	<!-- Element where elFinder will be created (REQUIRED) -->
	<div id="elfinder"></div>

</body>

