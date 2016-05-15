<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">日期</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo showDateFormat($suggestion_info["created"],"Y-m-d") ?>				
		</div>
	</div>
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">住戶姓名</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo $suggestion_info["user_name"] ?>				
		</div>
	</div>
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">意見主旨</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo $suggestion_info["title"]; ?>		
		</div>
	</div>
	
	<div class="form-group ">
		<label for="title" class="col-xs-12 col-sm-2 control-label no-padding-right">意見內容</label>
		<div class="col-xs-12 col-sm-6">
			<?php echo nl2br(tryGetData("content",$suggestion_info)); ?>	
		</div>
	</div>
	<div class="form-group ">
		<label for="content1" class="col-xs-12 col-sm-2 control-label no-padding-right">回覆內容</label>
		<div class="col-xs-12 col-sm-6">
			<textarea style="height:250px" class="autosize-transition form-control" name="reply" id="reply"><?php echo tryGetData("reply",$suggestion_info);?></textarea>							
		</div>		
	</div>
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $suggestion_info)?>" />

	
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
	
	

  