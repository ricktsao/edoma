<script>
	var setFancy={
		type:"iframe",
		minHeight:400			
	}

	var target_name;

	function getFilename(value){
		$.fancybox.close();
		
		$('input[name='+target_name+']').val(value);
		
		
		$('input[name='+target_name+']').val(value);
		$('#img_'+target_name).attr("src", value)
		$('#img_'+target_name).show();
		
		$('#btn_del_img_'+target_name).show();
		$('#del_image_'+target_name).val('');
		
		
	}

	$(function(){
		$('.files').click(function(){
			target_name=$(this).attr("name");
			//alert(target_name);		
			$.fancybox({href : '<?php echo backendUrl("filetree");?>'},setFancy);				
		})

	})
</script>

