

<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<?php echo textOption("標題","title",$edit_data); ?>
	
	
	<div class="form-group ">
		<label for="parent_sn" class="col-xs-12 col-sm-3 control-label no-padding-right">使用罐頭訊息 </label>
		<div class="col-xs-12 col-sm-4">
			<div class="btn-group">
              <select class="form-control" name="type" id="can_msg" >
				<option value="">若欲使用罐頭訊息請選擇...</option>
              	<?php 
              	foreach ($can_msg_list as $key => $item) 
              	{
					echo '<option value="'.$item["content"].'">'.showMoreStringSimple($item["content"]).'</option>';
				}
              	?>	                 
              </select>
            </div>			
		</div>		
	</div>
	
	<?php
	  echo textAreaOption("訊息內容","msg_content",$edit_data);
	?>	
	
	
	
	
	<?php
		$error_css= '';
		if(isNotNull(form_error("users")))
		{
			$error_css = 'has-error';			
		}
	?>	
	
	<div class="form-group <?php echo $error_css;?>">
		<label for="url" class="col-xs-12 col-sm-2 control-label no-padding-right">發佈對象</label>
		
		<div class="col-xs-12 col-sm-8">
			<select multiple="multiple" size="10" name="users[]">
			<?php 
              	foreach ($user_list as $key => $item) 
              	{
					echo '<option value="'.$item["sn"].'">'.$item["name"].'  '.$item["owner_addr"].'</option>';
				}
            ?>	    

			</select>
			<div class="help-block col-xs-12 col-sm-reset inline"><p><?php echo form_error("users")?></p></div>
		</div>
		
	</div>
	
	
	

	


	

		

	
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
	


<script>
	$(function () {

		$(".chzn-select").chosen();

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
		
		
		$('#can_msg').change(function()
	    {
	    	$("textarea#msg_content").val($('#can_msg').val());
	    	//$('#msg_content').text($('#can_msg').val());
	    	//alert('tste');
	    });
		
	});

	
	var demo1 = $('select[name="users[]"]').bootstrapDualListbox({
		filterPlaceHolder : '關鍵字',
		filterTextClear : '顯示全部',
        infoText : '共{0}人',
        moveAllLabel: 'Selected',
        infoTextFiltered: '<span class="label label-warning">找到</span> {0} 筆',
        infoTextEmpty: '訊息發送列表',
        //nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
      });

		demo1.bootstrapDualListbox("getContainer").find(".btn.moveall").append("(選擇全部)");
		demo1.bootstrapDualListbox("getContainer").find(".btn.removeall").append("(全部移除)");

	  /*
	$("#update_form").submit(function() {
      alert('請選擇發布對象');
      return false;
    });
	*/
  </script>