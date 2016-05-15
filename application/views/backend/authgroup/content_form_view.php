

<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("名稱","title",$edit_data); ?>
	<?php echo textOption("id","id",$edit_data); ?>
	<?php
	//  echo textAreaOption("內容","content",$edit_data);
	?>	
	
	
		
	<?php echo textOption("排序","sort",$edit_data); ?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
	
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
	
	

  