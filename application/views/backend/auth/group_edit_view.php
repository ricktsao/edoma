<form action="<?=$url['action']?>" method="post"  id="update_form" class="contentEditForm">
    	<div id="option_bar">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td >
						<button type="button" class='btn back' onclick="history.back()">	<?php echo $this -> lang -> line('common_return'); ?></button>	
						
					</td>
				</tr>
			</table>
		</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id='dataTable'>
          <tr>
            <td class="left"><span class="require">* </span><?php echo $this->lang->line("field_group_name");?>： </td>
            <td><input id="name" name="name" type="text" class="inputs" value="<?=tryGetArrayValue('name', $edit_data)?>" /><?php echo form_error('name');   ?></td>
          </tr>
          <tr>
            <td class="left"><?php echo $this->lang->line("field_accept_permission");?>：</td>
            <td><input name="accept_authority" id="accept_authority" value="1" <?=tryGetArrayValue('accept_authority', $edit_data)==1?"checked":""?> type="checkbox" ></td>
          </tr>         
          <tr>
            <td colspan="2" class='center'>
            	<button class='btn back' type="button"  onclick="history.back()">
					<?php echo $this -> lang -> line('common_cancel'); ?>
				</button>
				<button type="submit" class='btn save'>
					<?php echo $this -> lang -> line('common_save'); ?>
				</button>
            	</td>
          </tr>
        </table>
   
    <input type="hidden" name="sn" value="<?=tryGetArrayValue('sn', $edit_data)?>" />
</form>        
   