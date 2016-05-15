
<?php if(isNotNull($backend_message)){ ?>
<div class="alert alert-block alert-success">
	<button type="button" class="close" data-dismiss="alert">
		<i class="icon-remove"></i>
	</button>
	<?php echo $backend_message; ?>
</div>
<?php } ?>