<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!empty($_SERVER['HTTP_CLIENT_IP']))
    $ip=$_SERVER['HTTP_CLIENT_IP'];
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
else
    $ip=$_SERVER['REMOTE_ADDR'];
?>


<div style="text-align:center; height:500">
歡迎登入 <br/><br/>

Your IP:
<?php
if(!empty($_SERVER['HTTP_CLIENT_IP'])){
   $myip = $_SERVER['HTTP_CLIENT_IP'];
}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
   $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
   $myip= $_SERVER['REMOTE_ADDR'];
}
echo $myip;
?>
<br/><br/>

Login Time:<?php echo $this->session->userdata('admin_login_time');?>

</div>