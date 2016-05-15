<?php 
//dprint($f_menu_list);
?>
<style>	
	h1 { font-size:1.8em; }
	.demo { overflow:auto; border:1px solid silver; min-height:100px; }
</style>
	
<h1>前台模組權限</h1>
<form id="treeForm" method="post" action="<?php echo bUrl("updateFrontendAuth");?>">
	<div id="html" class="demo">
<?php
	
	echo '<ul>';
	foreach ($f_menu_list as $key => $web_item)
	{
		if($web_item["dir"]==1 && count($web_menu_map[$web_item["sn"]]["item_list"]) > 0)		
		{
			echo '<li data-jstree=\'{ "opened" : true ,"icon" : "fa fa-folder-open fa-lg"}\' data-value="'.$web_item["sn"].'-">'.$web_item["title"];			
				echo '<ul>';
				foreach($web_menu_map[$web_item["sn"]]["item_list"] as $item)
				{
					echo '<li data-jstree=\'{ "selected" : '.( (in_array($item["sn"], $auth_ary)?"true":"false") ).' ,"icon" : "fa fa-cogs fa-lg" }\'  data-value="'.$web_item["sn"].'-'.$item["sn"].'">'.$item["title"].'</li>';
				}
				echo '</ul>';
			echo '</li>';
		}
		else 
		{
			echo '<li data-jstree=\'{ "selected" : '.( (in_array($web_item["sn"], $auth_ary)?"true":"false") ).'  ,"icon" : "fa fa-folder-open fa-lg" }\' data-value="'.$web_item["sn"].'">'.$web_item["title"].'</li>';
		}
	} 
	echo '</ul>';
?>
		
	</div>
	<br>
	
	<h1>前台特殊權限</h1>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
	
						<table id="sample-table-1" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>										
									<th class="center" style="width:80px">
										<label>
											<input type="checkbox" id="checkboxAll" class="ace"  />
											<span class="lbl"></span>
										</label>
									</th>
									<th>標題</th>									
									<th>描述</th>									
								</tr>
							</thead>
							<tbody>
							<?php 
							foreach ($f_func_list as $key => $item) 
							{
								echo 
								'
								<tr>
									<td class="center">
										<label>
											<input type="checkbox" value="'.$item["sn"].'" name="func_sn[]" '.(in_array($item["sn"], $f_auth_ary)?"checked":"").' class="ace">
											<span class="lbl"></span>											
										</label>
									</td>	
									<td>'.$item["title"].'</td>									
									<td>'.$item["description"].'</td>	
								</tr>';
								
							}
							
							foreach ($f_auth_ary as $key => $value) {
								echo '<input type="hidden" value="'.$value.'" name="old_func_sn[]" >';
							}
							
							?>									
							</tbody>								
						</table>
						
						
					</div>
					
				</div>					
			</div>	
		</div>
	</div>
	
	
	
	<input type="hidden" name="admin_group_sn" value="<?php echo $admin_group_sn;?>" >
	<input type="hidden" name="select_values"  id="select_values" >
	<input type="hidden" name="auths"  id="auths" >
	<input type="hidden" name="is_frontend"  value="1" >
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				Back
			</a>

			&nbsp; &nbsp; &nbsp;
			
			<input class="btn btn-primary" type="submit" value="Submit" />
			
		</div>
	</div>
	
	
</form>

<script>
	$(function () {
	  $("#html").jstree({
	  	"core" : {
		    "themes" : {
		      "variant" : "default" // item: small, large
		    }
		  },
	    "checkbox" : {
	      "keep_selected_style" : false
	    },
	    "plugins" : [ "checkbox" ]
	  });

	  $('#treeForm').submit(function(){
			//var list = $('li[aria-selected="true"]').map(function() { return $(this).val(); }).get();
			var select_list = $('li').map(function(){return $(this).attr('aria-selected');}).get();
			var val_list = $('li').map(function(){return $(this).attr('data-value');}).get();
			
			$('#select_values').val(select_list);
			$('#auths').val(val_list);
			
			//console.log(select_list,val_list);
			//var obj = $.merge(select_list,val_list);
			//alert(obj);
			
			
	  });
	  
	  
	  
	  // 
    $("#checkboxAll").on("click", function () {
        if ($(this).prop("checked")) {
          $("input[name='func_sn[]']").each(function(i){
            var value = $(this).attr('value');
            $(this).prop('checked',true);  
            
          });  

        } else {
          $("input[name='func_sn[]']").each(function(i){
            var value = $(this).attr('value');
            $(this).prop('checked',false);
          }); 
        }
    });
	  

	});

	// inline data demo
	$('#data').jstree({
		'core' : {
			'data' : [
				{ "text" : "Root node", "children" : [
						{ "text" : "Child node 1" },
						{ "text" : "Child node 2" }
				]}
			]
		}
	});

	// data format demo
	$('#frmt').jstree({
		'core' : {
			'data' : [
				{ 
					"text" : "Root node", 
					"state" : { "opened" : true }, 
					"children" : [
						{ 
							"text" : "Child node 1", 
							"state" : { "selected" : true },
							"icon" : "jstree-file"
						},
						{ "text" : "Child node 2", "state" : { "disabled" : true } }
					]
				}
			]
		}
	});


	
    

	</script>
	

  