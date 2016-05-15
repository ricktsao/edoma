<?php

$admin_auth = $this->session->userdata("user_auth");
//dprint($admin_auth);
?>

<ul class="nav nav-list">	
	
	<?php foreach ($left_menu_list as $key => $module_item): ?>
		
		<?php  if(in_array($module_item["id"], $admin_auth) &&  $module_item["dir"]==1){ ?>		
			
			<li <?php echo  $module_sn == $module_item["sn"]?'class="open"':''  ?>>	
				<a href="#" class="dropdown-toggle">
					<i class="<?php echo $module_item["icon_text"]?>"></i>
					<span class="menu-text"> <?php echo $module_item["title"]; ?>  </span>	
					<b class="arrow icon-angle-down"></b>
				</a>
				
				<ul class="submenu" <?php echo  $module_parent_sn == $module_item["sn"]?'style="display: block;"':''  ?>>
					
				
				<?php 
					foreach($module_item_map[$module_item["sn"]]["item_list"] as $item): 	
					if(in_array($item["id"], $admin_auth)){
				?>
					<li <?php echo  $module_id== $item["id"]?'class="active"':''  ?>>
						<a href="<?php echo $item["url"]?>">
							<i class="icon-double-angle-right"></i>						
							<?php echo $item["title"]?>
						</a>
					</li>			
				<?php 
					}
					endforeach; 
				?>
				
				</ul>
				
			</li>
		<?php  } else if(in_array($module_item["id"], $admin_auth)){ ?>
			
			<li <?php echo  $module_id== $module_item["id"]?'class="active"':''  ?>>
				<a href="<?php echo $module_item["url"]?>">
					<i class="<?php echo $module_item["icon_text"]?>"></i>
					<span class="menu-text"> <?php echo $module_item["title"]?> </span>
				</a>
			</li>
		
		<?php  } ?>
	
	<?php  
	//dprint($item["module_category_sn"]); 
	?>
		

	<?php endforeach; ?>


</ul>