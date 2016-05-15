<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">報修日期</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo showDateFormat($repair_info["created"],"Y-m-d") ?>				
		</div>
	</div>
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">住戶姓名</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo $repair_info["user_name"] ?>				
		</div>
	</div>
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">維修範圍</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData($repair_info["type"],$this->config->item('repair_type')); ?>		
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">處理進度</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo tryGetData($repair_info["status"],$this->config->item('repair_status')); ?>	
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">報修內容</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo nl2br($repair_info["content"]); ?>
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">報修內容</label>
		<div class="col-xs-12 col-sm-6">
			<label style="width:100%;" class="middle">
				<input type="radio" class="middle" id="radio_flag_cooking_1" value="1" <?php echo ($repair_info["status"]==0 || $repair_info["status"]==1)?"checked":""; ?> name="status">				
				<label class="middle" for="radio_flag_cooking_1">已讀</label>				
				&nbsp;&nbsp;
				<input type="radio" class="middle" id="radio_flag_cooking_2" value="2" <?php echo $repair_info["status"]==2?"checked":""; ?> name="status">				
				<label class="middle" for="radio_flag_cooking_2">勘驗</label>
				&nbsp;&nbsp;
				<input type="radio" class="middle" id="radio_flag_cooking_3" value="3" <?php echo $repair_info["status"]==3?"checked":""; ?> name="status">				
				<label class="middle" for="radio_flag_cooking_3">估價</label>
				&nbsp;&nbsp;
				<input type="radio" class="middle" id="radio_flag_cooking_4" value="4" <?php echo $repair_info["status"]==4?"checked":""; ?> name="status">				
				<label class="middle" for="radio_flag_cooking_4">完工</label>&nbsp;&nbsp;	
			</label>
		</div>
	</div>
	
	
	<div class="form-group ">
		<label for="content1" class="col-xs-12 col-sm-2 control-label no-padding-right">回覆內容</label>
		<div class="col-xs-12 col-sm-6">
			<?php
			foreach ($reply_list as $key => $reply_info) 
        	{
				echo '<hr>['.$reply_info["created"].']';
				echo '<br>'.nl2br($reply_info["reply"]);
			}
			
			?>
			<textarea style="height:250px" class="autosize-transition form-control" name="reply" id="reply"></textarea>							
		</div>		
	</div>
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $repair_info)?>" />

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送出
			</button>
			
		</div>
	</div>
</form>
	
	

  