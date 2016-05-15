<?php
	$left_menu_html = '';

	foreach ($left_menu_list as $key => $value)
	{
		$sub_menu_html = '';

		foreach ($value["module_list"] as $subkey => $subvalue)
		{
			$select_this='';
			if($subvalue["id"]==$this->module_info["id"]){
				$select_this="this";
			}
				
			$sub_menu_html .= '<li class="lv2"><a class="'.$select_this.'" href="'.$subvalue["url"].'">'.$subvalue["title"].'</a></li>';
		}
		
		$left_menu_html.='
			<li><a href="#" class="this">'.$value["module_category_title"].'</a>
				  <ul>					
					  '.$sub_menu_html.'
				  </ul>
			  </li>';			  			  
	}

?>
<div id="language" style="display: none">	
	<div id='lang_icon'></div>	

</div>


<div id="treeMenu">
  <ul>
	  <?php echo $left_menu_html?>
  </ul>
</div>