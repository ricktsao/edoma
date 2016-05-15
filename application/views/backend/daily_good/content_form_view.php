

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("發起人姓名","title",$edit_data); ?>
	
	<?php
	  echo textOption("資料來源","brief",$edit_data);
	?>
	
	<?php
	  echo textAreaOption("資料內容","content",$edit_data);
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
	<?php echo textOption("排序","sort",$edit_data); ?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
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
	
	

  