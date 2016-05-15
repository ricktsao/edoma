<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php
	  echo textAreaOption("訊息內容","content",$edit_data);
	?>	
	
	
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<?php echo tryGetData('content_type', $edit_data)?>" />
	<input type="hidden" name="sort" value="<?php echo tryGetData('sort', $edit_data)?>" />
	<input type="hidden" name="forever" value="<?php echo tryGetData('forever', $edit_data)?>" />		

	
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
	
	

  