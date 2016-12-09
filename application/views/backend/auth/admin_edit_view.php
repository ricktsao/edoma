<?php
/*
  if(validation_errors() != false) {
    echo "<div id='errors'>" . validation_errors() . "</div>" ;
  }
  */
?>
<form action="<?php echo bUrl("updateAdmin")?>" method="post"  id="update_form" class="form-horizontal" role="form">

	<?php
		if(tryGetData('sn', $edit_data) > 0) {
			echo textDisplay("帳　號", "account", $edit_data);
			echo form_hidden( "account", $edit_data['account']);	
			echo passwordOption("密　碼","password",NULL);			
		} else {
			echo textOption("帳　號","account",$edit_data);
			echo passwordOption("密　碼","password",NULL);		
		}
	?>
	<?php echo textOption("姓　名","name",$edit_data);?>
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("admin",TRUE,array("sn")) ?>">
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
	
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
</form>