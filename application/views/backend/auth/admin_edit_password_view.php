
<form action="<? echo getBackendUrl("updatePassword")?>" method="post"  id="update_form">
    <div class="contentForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tr>
            <td class="left"><span class="require">* </span><?php echo $this->lang->line("field_password");?>： </td>
            <td><input name="old_password" type="password" class="inputs" /></td>
          </tr>
		  <tr>
            <td class="left"><span class="require">* </span>新密碼： </td>
            <td><input id="password" name="password" type="password" class="inputs" /><? echo  form_error('password');   ?></td>
          </tr>
          <tr>
            <td class="left"><span class="require">* </span>新密碼確認： </td>
            <td><input name="password_conf" type="password" class="inputs" /></td>
          </tr>
          <tr>
            <td class="left">&nbsp;</td>
            <td>
            	<input value="<?php echo $this->lang->line('common_save');?>" type="submit" class="btn"/>
            	<input value="<?php echo $this->lang->line('common_cancel');?>" type="button" class="btn" onclick="location.href='<? echo getBackendUrl("admin") ?>'"/>
            	</td>
          </tr>
        </table>
    </div>
</form>        
