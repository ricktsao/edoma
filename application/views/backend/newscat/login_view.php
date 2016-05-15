<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>E-DOMA e化你家 後台管理中心</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<link rel="stylesheet" href="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/css/login.css">
	<script src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/js/jquery-1.9.1.min.js"></script>
	<script language="javascript">

	//重新產生驗證碼
	function RebuildVerifyingCode( )
	{
		var verifying_code_url = $('#img_verifying_code').attr('src').split( "?" );
		verifying_code_url = verifying_code_url[0];		
		$('#img_verifying_code').attr('src',verifying_code_url + "?" + Math.random());
	}

	</script>
</head>

<body>
	<div id="primary">
        <form action="<?php echo bUrl("conformAccountPassword",FALSE)?>" method="post">	
            <img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/title.png" alt="" id="title">
            <img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/s.png" alt="" id="shild">
            <img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/logo.png" alt="" id="logo">
            <table>
                <tr>
                    <td colspan="3">
                        <div class="inputStyle type1">
                            <img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/icon1.png" alt="">
                            <input type="text" name="id" value="<?php echo tryGetArrayValue('id',$edit_data)?>" placeholder="用戶名">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="inputStyle type1">
                            <img src="<?php echo base_url();?>template/<?php echo $this->config->item('backend_name');?>/images/icon2.png" alt="">
                            <input type="password" name="password" value="<?php echo tryGetArrayValue('password',$edit_data)?>" placeholder="請輸入密碼">
                        </div>
                    </td>
                </tr>
               
                <tr>
                    <td >
                        <div class="inputStyle type2">                           
							<input type="text" name="vcode" placeholder="驗證碼" >
                        </div>
                    </td>
                    <td >
						<img style="width:130px;" id="img_verifying_code" align="absmiddle" src="<?php echo base_url()?>verifycodepic" style="cursor:pointer" onclick="RebuildVerifyingCode()">						
                    </td>
                    <td>
						<a href="javascript: void(0)" onclick="RebuildVerifyingCode()" >換一張</a>
					</td>
                </tr>				
                <tr>
                    <td colspan="3">                    	
						<input type="submit" class="btn" value="登入">
                    </td>
                </tr>
				<tr>
                    <td colspan="3" style="color:red;">
                    <?php echo form_error('id');?>
	            	<?php echo form_error('password');?>
	            	<?php echo form_error('vcode');?>
	            	<?php echo tryGetArrayValue('error_message',$edit_data);?>
                    </td>
                </tr>
            </table>
        </form>
    </div>


          

</body>
</html>
