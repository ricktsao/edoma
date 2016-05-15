<?php showOutputBox("tinymce/tinymce_js_view");?>

<form action="<? echo bUrl("updatePage")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	
	<?php echo showText("單元名稱","title",$module_info["title"]); ?>

	<?php
	 if(tryGetData( 'sn',$edit_data)== '')
	 {
	 	//echo textOption("Page ID","id",$edit_data);
	 }
	 else 
	 {
		//echo  textDisplay("Page ID","id",$edit_data);
	 }	 
	?>
	
	<?php echo textAreaOption("內容","content",$edit_data); ?>	
	
	
	<?php 
	//echo pickDateOption($edit_data);
	?>
	
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("pageList",TRUE,array("sn")) ?>">
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
	
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />

