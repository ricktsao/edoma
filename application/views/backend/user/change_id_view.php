<?php //echo validation_errors(); ?>

<style type="text/css">
	.dataTable th[class*=sorting_] { color: #808080; }
	.dataTables_empty { text-align: center; color: #993300; font-size: 16px;}
	.require, .error {color: #d16e6c;}
	.note {color: #993300; font-size:12px; padding: 5px;}
	.dataTable td {font-size:13px; font-family:verdana;}
	#add_form {background: #f7f7f7; border-top: #d1d1d1 1px dashed; padding:10px 5px 10px 5px}

	#parking_list ul {margin: 0px;}
	#parking_list li {
		list-style-type: none;
		padding: 3px;
		background: #ffffff;
		font-size:14px;
		color: #369;
		border: #d1d1d1 1px solid;
	}
	#parking_list li:hover {
		background: #f7f7f7;
		color: #c00;
		cursor: pointer;
	}
</style>

<div class="page-header">
	<h1>
		磁卡變更
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>
<?php echo validation_errors(); ?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">戶　別：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'>
				<?php
				$building_id = tryGetData('building_id', $user_data, NULL);
				if ( isNotNull($building_id) ) {
					echo building_id_to_text($building_id);
				}
				?>
				</span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">現有磁卡：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('id',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶姓名：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('name',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">行動電話：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('phone',$user_data); ?></span></div>
			</div>


			<form action="<?php echo bUrl("updateId")?>" method="post"  id="add_formx" role="form">
			<input type='hidden' name='user_sn' value='<?php echo tryGetData('sn', $user_data); ?>'>
			<input type='hidden' name='user_id' value='<?php echo tryGetData('id', $user_data); ?>'>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">新磁卡：</label>
				<div class="col-xs-12 col-sm-8"><input type='text' id='new_id' name='new_id' size=50></div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"></label>
				<div class="col-xs-12 col-sm-6">
				<button class="btn btn-success" type="Submit">
						<i class="icon-ok bigger-110"></i>
						確定重設
				</button>
				</div>
			</div>
		</form>