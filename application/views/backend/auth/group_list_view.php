<form action="" id="update_form" method="post" class="contentForm">
   
    	<div id="option_bar">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td>
					 <button type="button" class='btn add' onclick="jUrl('<?php echo $url['edit']?>')"><?php echo $this -> lang -> line("common_insert"); ?></button>               	
                </td>
              </tr>
            </table>
        </div>
        
        <div class="list">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id='dataTable'>
              <tr class='first_row'>
                <td ><?php echo $this->lang->line("field_serial_number");?></td>
                <td><?php echo $this->lang->line("field_group_name");?></td>
                <td ><?php echo $this->lang->line("common_handle");?></td>            
                <td ><?php echo $this->lang->line("field_auth_setting");?></td>
                <td width="10%">
                	<button type="button" class='btn select_all'  onclick="SelectAll( 'del[]' )">
						<?php echo $this -> lang -> line("common_select_all"); ?>
					</button>
					<button type="button" class='btn select_revers'  onclick="ReverseSelect( 'del[]' )" >
						<?php echo $this -> lang -> line("common_reverse_select"); ?>
					</button>
                </td>
              </tr>
     		<tbody>
              <?php for($i=0;$i<sizeof($list);$i++){ ?>
              <tr>
                <td><?php echo $list[$i]["sn"]?></td>
                <td><?php echo $list[$i]["name"]?> </td>
                <td>
                	<button type="button" class='btn edit' onclick="self.location.href='<?php echo $url['edit']."/".$list[$i]["sn"] ?>'" ><?php echo $this -> lang -> line("common_handle"); ?> </button>
                </td>         
                <td>
					<button type="button" class='btn edit' onclick="self.location.href='<?php echo $url['auth']."/".$list[$i]["sn"] ?>'" ><?php echo $this -> lang -> line("admin_group_auth"); ?> </button>
				</td>
                <td><input name="del[]" id="del" value="<?php echo $list[$i]["sn"]?>" type="checkbox" ></td>
              </tr>
              <?php } ?>
			</tbody>
              <tr>
              	<td colspan="4">
				<?php echo showBackendPager($pager)?>
                </td>              
              	<td>
                	<button type="button" class='btn del' onclick="listViewAction('#update_form','<?php echo $url['del'] ?>','是否確定刪除')"> <?php echo $this -> lang -> line("common_delete"); ?> </button>

                </td>
              </tr>
             
            </table>
            
        </div>    

</form>        
