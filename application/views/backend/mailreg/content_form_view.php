
<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<? echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
	<div class="form-group" >
		<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"><span class='require'>*</span> 收件人：</label>
		<div class="col-xs-12 col-sm-4">
			<?php echo $user_info["name"]?>
		</div>
		<input type="hidden" id="user_sn" name="user_sn" value="<?php echo $user_info["sn"]?>" >
		<input type="hidden" id="user_name" name="user_name" value="<?php echo $user_info["name"]?>" >
	</div>	
	
	<div class="form-group ">
		<label for="parent_sn" class="col-xs-12 col-sm-2 control-label no-padding-right">郵件類型 </label>
		<div class="col-xs-12 col-sm-4">
			<div class="btn-group">
              <select class="form-control" name="type">
              	<?php 
              	foreach ($mail_box_type_ary as $key => $value) 
              	{
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
              	?>	
              	
                 
              </select>
            </div>			
		</div>
		
	</div>
	
	
	<?php
	  echo textAreaOption("郵件敘述說明","desc",array());
	?>	
	
	
	
		
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("user",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上一頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送交
			</button>
			
		</div>
	</div>
</form>
	
<style type="text/css">
#names_list {
    float: left;
    list-style: none; font-family: '微軟正黑體';
	font-size:14px; color: #369;
    margin: 0;
    padding: 0; 
    max-height: 440px;
    overflow-y: auto;
}
#names_list li {
    padding: 10px;
    background: #FAFAFA; font-weight:bold;
    border-bottom: #F0F0F0 1px solid;width: 760px;
}
#names_list li:hover{background:#fff0f0; cursor: pointer;}
</style>

<script type="text/javascript"> 
$(function(){
	$("#search-box").click(function(){
	    
		$("#cust_sn").val('');

		$("#addr").val('').attr("readonly",false);

		$.ajax({
				type: "GET",
				url: "<?php echo bUrl('ajaxGetPeople');?>",
				data:'keyword='+$("#user_name").val(),
				beforeSend: function(){
					var input = $('#user_name');
					var inputValue = input.val();
					var nowLehgth = inputValue.length;
					input.css("background","#FFF url(http://phppot.com/demo/jquery-ajax-autocomplete-country-example/loaderIcon.gif) no-repeat 165px");
					if(inputValue != '' && nowLehgth >= 2) {
						input.css("background-image","none");
					} else {
						input.css("background-image","none");
						alert('請至少輸入二個字');
						return false;
					}

		},
		success: function(data){
			console.log(data);

			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});
	
	
	//form disable enter key
	$('#update_form').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
		e.preventDefault();
		return false;
	  }
	});
	
	
	
}); 


//To select country name
function selectCountry(user_sn, user_name) {
	$("#user_sn").val(user_sn);
	$("#user_name").val(user_name);
	$("#suggesstion-box").hide();
}
</script>	
  