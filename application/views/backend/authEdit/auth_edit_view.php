<?php
	$auth_menu_html = '';
	foreach ($auth_list as $key => $value)
	{
		$sub_menu_html = '';
		foreach ($value["module_list"] as $subkey => $subvalue)
		{
			$select_this = in_array($subvalue["sn"], $have_auth_list)?"checked":"";

			
			$sub_menu_html .=
			'<li>
            	
                	<label><input type="checkbox" name="auth[]" value="'.$subvalue["sn"].'" '.$select_this.'>'.$subvalue["title"].'</label>    
                
			</li>';
			
		}
		
		$auth_menu_html.=
		'
		<li>
			<div class="title_area">
				'.$value["module_category_title"].'
					<button type="button" class="btn select_all" onclick="authSelect(this,true)" >全選</button>
					<button type="button"  class="btn select_revers" onclick="authSelect(this,false)">全取消</button>
				
			</div>
            <ul>
             '.$sub_menu_html.'
            </ul>
            <div style="clear:both"></div>
        </li>
		';		  			  
	}
?>
<script>	
	function authSelect(obj,method){
		if(method){			
			$(obj).parent().parent().children('ul').find('input[type=checkbox]').attr('checked',true);
		}else{
			$(obj).parent().parent().children('ul').find('input[type=checkbox]').attr('checked',false);
		}		
	}
</script>

<form action="<?php echo $url["action"]?>" method="post" class="contentEditForm">
    <div id="option_bar">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td >
						<button type="button" class='btn back' onclick="history.back()">	<?php echo $this -> lang -> line('common_return'); ?></button>	
						
					</td>
				</tr>
			</table>
		</div>
    	<div id="permissions">
            
                <ul>
                    
				<?php echo $auth_menu_html?>
                    
                </ul>
			
    	</div>        
    
    <input type="hidden" name="web_admin_group_sn" value="<?php echo tryGetArrayValue('group_sn', $edit_data)?>" />
   <button class='btn back' type="button"  onclick="history.back()">
					<?php echo $this -> lang -> line('common_cancel'); ?>
				</button>
				<button type="submit" class='btn save'>
					<?php echo $this -> lang -> line('common_save'); ?>
				</button>
</form>