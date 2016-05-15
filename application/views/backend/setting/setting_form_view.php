<form action="<?php echo bUrl("updateSetting")?>" method="post"  id="update_form" class="form-horizontal" role="form">
	<?php 
		foreach ($setting_list as $key => $item) 
		{
			if ($item["key"] == 'building_part_01' || $item["key"] == 'parking_part_01' || $item["key"] == 'mail_box_type') {
				echo '<div class="hr hr-16 hr-dotted"></div>';
			}

			switch($item["type"])
			{
				
				case 'text' :
					echo
					'<div class="form-group ">
						<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="'.$item["key"].'">'.$item["title"].'</label>
						<div class="col-xs-12 col-sm-4">
							<input type="text" id="'.$item["key"].'" name="'.$item["key"].'"  class="width-100" value="'.$item["value"].'"  />					
						</div>			
						'.$item["memo"].'		
					</div>';
					break;		
								
				case 'textarea' :					
					echo 
					
					'<div class="form-group">
						<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="'.$item["key"].'">'.$item["title"].'</label>
						<div class="col-xs-12 col-sm-6" >
							<textarea id="'.$item["key"].'" name="'.$item["key"].'" class="autosize-transition form-control" style="height:250px">'.$item["value"].'</textarea>
						'.$item["memo"].'			
						</div>						
					</div>';
					break;
			}
		}
	
	
	?>
	
	
	
		<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
			</button>
			
		</div>
	</div>

</form>        