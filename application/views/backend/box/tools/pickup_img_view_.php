



<?php
if ( ! is_array($elements))
{
	$elements["no"] = "1";
	$elements["name"] = "filename";
}
?>
<script type="text/javascript">
	$().ready(function() {    
	    $('#open_media<?php echo $elements["no"];?>').click(function() 
	    {   
		    load_elfinder<?php echo $elements["no"];?>();		    
	    })	  
	    
	    
	    $('#del_img<?php echo $elements["no"];?>').click(function() 
	    {   
	       $('#filename<?php echo $elements["no"];?>').val('');
	       $('#thumb<?php echo $elements["no"];?>').hide();
	       $('#del_img<?php echo $elements["no"];?>').hide();
	       $('#del_image<?php echo $elements["no"];?>').val('1');
	       
	    })  
	});


	function load_elfinder<?php echo $elements["no"];?>(id) {
	
	    var opt = {      // Must change variable name elfinder onlyMimes
	    url : '<?php echo bUrl("loadelfinder");?>',
	    lang : 'en',

	    closeOnEditorCallback : true,
	    docked : false,
	    dialog : { title : 'File Manager', height: 500 },
	    
	    
        onlyMimes: ["image"],
        resizable: false
	    
	    }
	
	
	    $('div#main_window<?php echo $elements["no"];?>').dialog({
	        modal: true,
	        width: "60%",
	        title: "Images OverView"
	    }).
	    elfinder({
	        //lang:'ru',//Select lang
	        url: '<?php echo bUrl("loadelfinder");?>',
	        onlyMimes: ["image"],
	        // display all images
	        getFileCallback: function (url) {
	            $('#filename<?php echo $elements["no"];?>').val(url);
	            $('#thumb<?php echo $elements["no"];?>').attr("src", url).show();
				$("#del_img<?php echo $elements["no"];?>").show();	
	            $('a.ui-dialog-titlebar-close[role="button"]').click()
	        },
	        resizable: false
	    }).elfinder('instance')
	}

</script>

<input id="filename<?php echo $elements["no"];?>" type="hidden" name="<?php echo $elements["name"];?>" value="<? echo tryGetArrayValue($elements["name"],$edit_data)?>" />
<input type="hidden" name="orig_<?php echo $elements["name"];?>" value="<? echo tryGetArrayValue("orig_".$elements["name"],$edit_data)?>" />
<input id="open_media<?php echo $elements["no"];?>" type="button" value="<?php echo $this->lang->line("common_pickup_image");?>" />
<input id="del_img<?php echo $elements["no"];?>" type="button" value="<?php echo $this->lang->line("common_delete");?>" <? echo tryGetArrayValue($elements["name"],$edit_data,'')==''?'style="display: none"':''  ?> />
<input id="del_image<?php echo $elements["no"];?>" type="hidden"  name="del_<?php echo $elements["name"];?>" value="0"  />
<div><img id="thumb<?php echo $elements["no"];?>" Src="<? echo tryGetArrayValue($elements["name"],$edit_data)?>" <?echo tryGetArrayValue($elements["name"],$edit_data,'')==''?'style="display:none"':''; ?>></div>
<div id="main_window<?php echo $elements["no"];?>"></div>

