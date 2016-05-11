<div class="page-header">
	<h1>
		住戶資料編輯
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

	<?php echo form_hidden("role", 'P');?>
	<?php echo form_hidden("comm_id", tryGetData('comm_id', $edit_data));?>
	<?php echo form_hidden("id", tryGetData('id', $edit_data, NULL));?>
	<?php echo form_hidden("app_id", tryGetData('app_id', $edit_data, NULL));?>

	<?php echo textOption("＊姓　名","name",$edit_data);?>
	<?php echo textOption("＊電　話","tel",$edit_data);?>
	<?php echo textOption("＊行動電話","phone",$edit_data);?>
	<?php echo textOption("＊住址","addr",$edit_data);?>


	
	<div class="form-group ">
		<label for="launch" class="col-xs-12 col-sm-2 control-label no-padding-right">性 別</label>
		<div class="col-xs-12 col-sm-4">
			<label class="middle" style="width:100%;">
			<?php echo gender_radio('gender', (int) tryGetData('gender', $edit_data, 1));?>
			</label>
		</div>
	</div>

	<?php echo pickDateOption($edit_data);?>

	<?php echo checkBoxOption("啟　用", "launch", $edit_data);?>
	
	
	
	
	
	
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



<script>


		$(function () {

			$(".chzn-select").chosen().change(function(){

				$('input[name=is_manager]').prop('checked',false);
				
				if(!$(this).val()){
					$('input[name=is_manager][value="0"]').prop("checked",true);
				
					return 
				}
				var _idx = $(this).val().indexOf("2");
			
				if(_idx>-1){
					$('input[name=is_manager][value=1]').prop("checked",true);
					
				}else{

					$('input[name=is_manager][value=0]').prop("checked",true);
					
				}
			});

			

			//chosen plugin inside a modal will have a zero width because the select element is originally hidden
			//and its width cannot be determined.
			//so we set the width after modal is show
			$('#modal-form').on('show', function () {
				$(this).find('.chzn-container').each(function(){
					$(this).find('a:first-child').css('width' , '200px');
					$(this).find('.chzn-drop').css('width' , '210px');
					$(this).find('.chzn-search input').css('width' , '200px');
				});
			})
		});
	</script>

                                                                                                                                 