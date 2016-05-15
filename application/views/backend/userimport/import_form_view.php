<div class="page-header">
	<h1>
		批次匯入
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			可批次匯入社區住戶資料；請務必依據【住戶資料】的內容格式
		</small>
	</h1>
</div>

<link rel="stylesheet" href="http://css-spinners.com/css/spinner/spinner.css" type="text/css">
<article class="well">住戶資料檔案的格式請確實依照<a  href="<?php echo base_url("./upload/user/example.xlsx");?>">『範例檔案』</a>的格式，否則將無法上傳

</article>	
<span style="display: none" class="label label-sx label-warning">Hot於前端首頁只顯示1則(列表第一筆)</span>	


<form action="<?php echo bUrl("updateImport")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">

	<div class="form-group">
		<label class="col-sm-2 control-label no-padding-right" for="form-field-1" style="margin-top:12px">住戶資料檔案上傳：</label>		
		<div class="col-sm-8">
			<div class="widget-main">
				<input  name="xlsfile" type="file" id="id-input-file-2" />	
				<div id="error_msg" class="help-block" style="color:red"><?php echo  tryGetData("error", $edit_data);?></div>			
			</div>
			<div id="loading_zone" class="spinner-loader" style="display: none">
			  Loading…
			</div>
		</div>
	</div>
	
	
	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			
			<button class="btn btn-info" id="Submit" type="submit">
				<i class="icon-ok bigger-110"></i>
				確定上傳
			</button>
			
		</div>
	</div>
</form>
	

<script>

$(function(){
	$("#Submit").click(function(){
		var xlsfile = $("#id-input-file-2").val();
		if( xlsfile=='' )
		{
			$("#error_msg").html("請選擇上傳檔案");			
			return false;
		}
		else
		{
			$("#error_msg").html("");	
			$("#loading_zone").show();	
			
			
			return true;
		}
		
		
	});
});


jQuery(function($) {
	
	$('#id-input-file-2').ace_file_input({
		no_file:'請使用xlsx格式上傳',
		btn_choose:'選擇檔案',
		btn_change:'換另一個',
		droppable:false,
		onchange:null,
		thumbnail:false //| true | large
		//whitelist:'gif|png|jpg|jpeg'
		//blacklist:'exe|php'
		//onchange:''
		//
	});
	
	
				
});
</script>
	
	

  