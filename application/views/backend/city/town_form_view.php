<form action="<?php echo bUrl("updateTown")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	<?php echo textOption("標題","town_name",$edit_data); ?>
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="city_code" value="<? echo tryGetData('city_code', $edit_data)?>" />
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("id")) ?>">
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
	
	
