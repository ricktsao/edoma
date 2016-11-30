<form action="<?php echo bUrl("updateVillage")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	<?php echo textOption("標題","village_name",$edit_data); ?>
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="city_code" value="<? echo tryGetData('city_code', $edit_data)?>" />
	<input type="hidden" name="v_sn" value="<? echo tryGetData('v_sn', $edit_data)?>" />
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("villageList",TRUE,array("v_sn")) ?>">
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
	
	
