
<style>	
	h1 { font-size:1.8em; }
	.demo { overflow:auto; border:1px solid silver; min-height:100px; }
</style>
	
<h1>模組權限</h1>
<form id="treeForm" method="post" action="<?php echo bUrl("updateBackendAuth");?>">
	<div id="html" class="demo">
<?php


	echo '<ul>';
	foreach ($left_menu_list as $key => $module_item)
	{
		if($module_item["dir"]==1 && count($module_item_map[$module_item["sn"]]["item_list"]) > 0)		
		{
			echo '<li data-jstree=\'{ "opened" : true ,"icon" : "fa fa-folder-open fa-lg"}\' data-value="'.$module_item["sn"].'-">'.$module_item["title"];			
				echo '<ul>';
				foreach($module_item_map[$module_item["sn"]]["item_list"] as $item)
				{
					echo '<li data-jstree=\'{ "selected" : '.( (in_array($item["sn"], $auth_ary)?"true":"false") ).' ,"icon" : "fa fa-cogs fa-lg" }\'  data-value="'.$module_item["sn"].'-'.$item["sn"].'">'.$item["title"].'</li>';
				}
				echo '</ul>';
			echo '</li>';
		}
		else 
		{
			echo '<li data-jstree=\'{ "selected" : '.( (in_array($module_item["sn"], $auth_ary)?"true":"false") ).'  ,"icon" : "fa fa-folder-open fa-lg" }\' data-value="'.$module_item["sn"].'">'.$module_item["title"].'</li>';
		}
	} 
	echo '</ul>';
?>
		
	</div>
	<br>
	<input type="hidden" name="admin_group_sn" value="<?php echo $admin_group_sn;?>" >
	<input type="hidden" name="select_values"  id="select_values" >
	<input type="hidden" name="auths"  id="auths" >
	
	
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
	

  