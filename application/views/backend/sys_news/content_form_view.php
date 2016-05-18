
<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
		
	<?php
		$error_css= '';
		if(isNotNull(form_error("comms")))
		{
			$error_css = 'has-error';			
		}
	?>		
	<div class="form-group <?php echo $error_css;?>">
		<label for="url" class="col-xs-12 col-sm-2 control-label no-padding-right">發佈社區</label>
		
		<div class="col-xs-12 col-sm-8">
			<select multiple="multiple" size="10" name="comms[]">
			<?php 
			
				$comm_ids = tryGetData('comm_id',$edit_data);
				$comm_id_ary = explode(",", $comm_ids);
				
              	foreach ($community_list as $key => $item) 
              	{
					echo '<option value="'.$item["id"].'"  '.(in_array($item["id"], $comm_id_ary)?"selected":"").'  >'.$item["name"].'</option>';
				}
            ?>	    

			</select>
			<div class="help-block col-xs-12 col-sm-reset inline"><p><?php echo form_error("comms")?></p></div>
		</div>
		
	</div>
	
	
	<?php echo textOption("標題","title",$edit_data); ?>
	
	<?php
	  echo textAreaOption("內容","content",$edit_data);
	?>	
	
	<div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="content">圖片</label>
        <div class="col-xs-12 col-sm-6">
            <input type="file" name="img_filename" size="20" /><br /><br />
				<input type="hidden" name="orig_img_filename" value="<?php echo tryGetData('orig_img_filename',$edit_data)?>"  />
				<?php if(isNotNull(tryGetData('img_filename',$edit_data))){ ?>
				<img  border="0" style="width:200px;" src="<?php echo tryGetData('img_filename',$edit_data); ?>"><br />		
				
            	<?php } ?>
        <div class="message">
            <?php echo  form_error('start_date');?>
        </div>
        </div>
    </div>
	
	
	
	
	<?php echo pickDateOption($edit_data);?>
	<?php
	 //echo textOption("排序","sort",$edit_data); 
	 ?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sort" value="<? echo tryGetData('sort', $edit_data)?>" />
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<? echo tryGetData('content_type', $edit_data)?>" />
		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				Back
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>
</form>
	
	
<script>
	$(function () {

		$(".chzn-select").chosen();

		//chosen plugin inside a modal will have a zero width because the select element is originally hidden
		//and its width cannot be determined.
		//so we set the width after modal is show
		$('#modal-form').on('show', function () {
			$(this).find('.chzn-container').each(function(){
				$(this).find('a:first-child').css('width' , '200px');
				$(this).find('.chzn-drop').css('width' , '210px');
				$(this).find('.chzn-search input').css('width' , '200px');
			});
		})
		
		
		$('#can_msg').change(function()
	    {
	    	$("textarea#msg_content").val($('#can_msg').val());
	    	//$('#msg_content').text($('#can_msg').val());
	    	//alert('tste');
	    });
		
	});

	
	var demo1 = $('select[name="comms[]"]').bootstrapDualListbox({
		filterPlaceHolder : '關鍵字',
		filterTextClear : '顯示全部',
        infoText : '共{0}個社區',
        moveAllLabel: 'Selected',
        infoTextFiltered: '<span class="label label-warning">找到</span> {0} 筆',        
        //nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
      });

		demo1.bootstrapDualListbox("getContainer").find(".btn.moveall").append("(選擇全部)");
		demo1.bootstrapDualListbox("getContainer").find(".btn.removeall").append("(全部移除)");

	  /*
	$("#update_form").submit(function() {
      alert('請選擇發布對象');
      return false;
    });
	*/
  </script>
  