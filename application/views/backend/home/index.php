<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!empty($_SERVER['HTTP_CLIENT_IP']))
    $ip=$_SERVER['HTTP_CLIENT_IP'];
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
else
    $ip=$_SERVER['REMOTE_ADDR'];
?>



<script type="text/javascript" charset="utf-8">


	$().ready(function() {		
	    var opt = {      // Must change variable name elfinder onlyMimes
	    url : '<?php echo getBackendUrl("loadelfinder");?>',
	    lang : 'en',
	   
	   /* getFileCallback : function(url) {
	    			$('#field').val(url);
	    			$('#finder_browse').hide();
	    		},*/
	    closeOnEditorCallback : true,
	    docked : false,
	    dialog : { title : 'File Manager', height: 500 },
	    
	    
        onlyMimes: ["image"],
        // display all images
        getFileCallback: function (url) {
            $('input#' + id).val(url);
            $('#thumb').attr("src", url).show();

            $('a.ui-dialog-titlebar-close[role="button"]').click()
        },
        resizable: false
	    
	    }
    
	    $('#open').click(function() 
	    {                       
	    	 // Must change the button's id
		    //$('#finder_browse').elfinder(opt)                // Must update the form field id
		    //$('#finder_browse').elfinder($(this).attr('id'));   // Must update the form field id
		    
		    load_elfinder("field");
	    })
	    
	    
	});
	

function load_elfinder(id) {

    $('div#main').dialog({
        modal: true,
        width: "60%",
        title: "Images OverView"
    }).
    elfinder({
        //lang:'ru',//Select lang
        url: '<?php echo getBackendUrl("loadelfinder");?>',
        onlyMimes: ["image"],
        // display all images
        getFileCallback: function (url) {
            $('input#' + id).val(url);
            $('#thumb').attr("src", url).show();

            $('a.ui-dialog-titlebar-close[role="button"]').click()
        },
        resizable: false
    }).elfinder('instance')
}


	
</script>


<div style="text-align:center; height:500">
Welcome <br/><br/>

Your IP:<?php echo $ip;?><br/><br/>

Login Time:<?php echo $this->session->userdata('admin_login_time');?>



<!-- Element where elFinder will be created (REQUIRED) -->

<div id="finder_browse"></div>
<input id="field" type="text" name="img" value="" />
<input id="open" type="button" value="browse..." />
<div><img id='thumb' style='display:none'></div>

<div id="main"></div>
</div>