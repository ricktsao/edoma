<div class="page-header">
	<h1>
		<i class="ace-icon fa fa-angle-double-right"></i>領收人請使用磁扣感應		
	</h1>
</div>
<form  role="search" method="post" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<input type='password' id="input_keycode" name='keycode' value='' style="width:500px" placeholder="請使用磁卡感應">
    </div>    

    
</article>	

</form>


 <script>
$(function() {
	$( "#input_keycode" ).focus();
})
</script>
