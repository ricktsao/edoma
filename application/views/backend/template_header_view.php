<div id="logo"><a href="<?php echo backendUrl();?>" title="logo"><img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/logo.png" alt="logo" title="logo" border="0" /></a></div>
<div id="stay">
	<table border="0" cellspacing="0" cellpadding="0">
      <tr>       
        <td class="name"><?php echo $this->session->userdata('user_id');?></td>
        <td class="separator">|</td>
        <td class="name"><a href='<?php echo backendUrl("authEdit");?>'>更改密碼</a></td>
        <td class="separator">|</td>
        <td class="login"><a href="<?php echo bUrl("logout")?>" title="登出"><div ></div>登出</a></td>
      </tr>
   </table>
    
</div>


        
