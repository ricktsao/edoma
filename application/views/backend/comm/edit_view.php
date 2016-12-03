<div class="page-header">
	<h1>
		社區資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<?php
  //if(validation_errors() != false) {
  //  echo "<div id='errors'>" . validation_errors() . "</div>" ;
  //}
?>
<form action="<?php echo bUrl("updateUser")?>" method="post"  id="update_form" class="form-horizontal" role="form">


	<?php echo textOption("＊社區名稱","name",$edit_data);?>
	<?php echo textOption("電　話","tel",$edit_data);?>
	<?php echo textOption("行動電話","phone",$edit_data);?>
	<?php echo textOption("住　址","addr",$edit_data);?>

	<?php //echo pickDateOption($edit_data);?>

	<?php echo checkBoxOption("啟　用", "status", $edit_data);?>

	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("index",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				返回
			</a>


			&nbsp; &nbsp; &nbsp;

			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				確定送出
			</button>

		</div>
	</div>

	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
</form>