

<?php
if ( ! is_array($elements))
{
	$elements["title"] = "圖片";
	$elements["name"] = "filename";
}

$recommend_wh = "";
if( isNotNull(tryGetData("width", $elements)) && isNotNull(tryGetData("height", $elements)) )
{
	$recommend_wh = "建議尺寸 ".tryGetData("width", $elements)." X ".tryGetData("height", $elements);
}

?>



<script>
	var setFancy={
		type:"iframe",
		minHeight:400			
	}

	var target_name = "<?php echo $elements["name"];?>";

	function getFilename(value){
		
		$.fancybox.close();
		$('input[name='+target_name+']').val(value);
		$('#<?php echo 'img_'.$elements["name"];?>').attr("src", value)
		$('#<?php echo 'img_'.$elements["name"];?>').show();
		
		$('<?php echo '#btn_del_img_'.$elements["name"];?>').show();
		$('<?php echo '#del_image_'.$elements["name"];?>').val('');
	}

	$(function(){
		$('.files').click(function(){
			//target_name=$(this).attr("name");					
			$.fancybox({href : '<?php echo backendUrl("filetree","index",TRUE,array("all"=>"all"),array("media_folder"=>"plan"));?>'},setFancy);
				
		})
		
		
		$('<?php echo '#btn_del_img_'.$elements["name"];?>').click(function() 
	    {   
	    	$('<?php echo '#img_'.$elements["name"];?>').hide();
	    	$('input[name=<?php echo $elements["name"];?>]').val('');
	    	$('<?php echo '#btn_del_img_'.$elements["name"];?>').hide();
	    	$('<?php echo '#del_image_'.$elements["name"];?>').val('1');	       
	    })  
		

	})
</script>


<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo $elements["title"];?> </label>

	<div class="col-sm-9">
		
	
		<a class="btn btn-success btn-xs files">
			<i class="icon-picture icon-2x icon-only"></i>
			請選擇
		</a>
	
		<a id="<?php echo 'btn_del_img_'.$elements["name"];?>" class="btn btn-inverse btn-xs" <?php echo isNull($elements["img_value"])?'style="display: none"':''; ?>>
			<i class="icon-trash icon-2x icon-only"></i>
			刪除
		</a>
		<?php echo $recommend_wh;?>
		<input type="hidden" name="<?php echo $elements["name"];?>" id="<?php echo $elements["id"];?>" value="<?php echo $elements["orig_value"];?>"  />
		<input type="hidden" name="del_image_<?php echo $elements["name"];?>" id="del_image_<?php echo $elements["name"];?>"  />
		<input type="hidden" name="orig_<?php echo $elements["name"];?>" value="<?php echo $elements["orig_value"];?>"   />
		<hr>
		<br/>			
		<a href="<?php echo $elements["img_value"];?>" target="_blank">
		<img id="<?php echo 'img_'.$elements["name"];?>" src="<?php echo $elements["img_value"];?>" style="max-width:500px;max-height:800px; <?php  echo isNull($elements["img_value"])?'display: none':''; ?>"     />
		</a>
	</div>
</div>






