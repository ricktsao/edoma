
<style type="text/css">
	th, td {text-align:center}
</style>
<div class="page-header">
	<h1>
		車位設定
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			產生社區所有的車位編號
		</small>
	</h1>
</div>

<form method="post" action="<?php echo bUrl('index');?>">
<?php
echo form_hidden('comm_id', $comm_id);
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"><?php echo $parking_part_01 .'：';?></label>
			<div class="col-xs-12 col-sm-8">
			<?php
			echo form_dropdown('p_part_01', $parking_part_01_array, $p_part_01);
			?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"><?php echo $parking_part_02 .'：';?></label>
			<div class="col-xs-12 col-sm-8">
			<?php
			echo form_dropdown('p_part_02', $parking_part_02_array, $p_part_02);
			?>
			</div>
		</div> 
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">產出車位號：</label>
			<div class="col-xs-12 col-sm-8">
			<?php
			echo '從 <input type="text" name="start" value="'.$start.'" size="1"> 號 到 <input type="text" name="end" value="'.$end.'" size="1"> 號';
			?>
			</div>
		</div> 
		
		
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"></label>
			<div class="col-xs-12 col-sm-6">
			<button class="btn" type="button" id="search-reset" >
					<i class="icon-warning bigger-110"></i>
					重設
			</button>
			<button class="btn btn-success" type="Submit">
					<i class="icon-ok bigger-110"></i>
					確定產出
			</button>
			</div>
		</div>
		</div>
	</form>