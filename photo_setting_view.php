<?php //echo validation_errors(); ?>

<style type="text/css">
	.dataTable th[class*=sorting_] { color: #808080; }
	.dataTables_empty { text-align: center; color: #993300; font-size: 16px;}
	.require, .error {color: #d16e6c;}
	.note {color: #993300; font-size:12px; padding: 5px;}
	.dataTable td {font-size:13px; font-family:verdana;}
	#add_formx {background: #f7f7f7; border-top: #d1d1d1 1px dashed; padding:10px 5px 10px 5px}

	#parking_list ul {margin: 0px;}
	#parking_list li {
		list-style-type: none;
		padding: 3px;
		background: #ffffff;
		font-size:14px;
		color: #369;
		border: #d1d1d1 1px solid;
	}
	#parking_list li:hover {
		background: #f7f7f7;
		color: #c00;
		cursor: pointer;
	}
</style>

<div class="page-header">
	<h1>
		圖片設定
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">課程主旨：</label>
			<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('title',$content_info); ?></span></div>
		</div>
		
		<div class="hr hr-16 hr-dotted"></div>	
		
		<form action="<?php echo bUrl("updateContentPhoto")?>" method="post"  id="add_form" role="form" enctype="multipart/form-data">
		<input type='hidden' name='content_sn' value='<?php echo tryGetData('sn', $content_info); ?>'>		
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">新增圖片：</label>
				<div class="col-xs-12 col-sm-6"><input type='file' id='filename' name='img_filename' size=20></div>
			</div>
			<div class="form-group" style="display:none">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">說明：</label>
				<div class="col-xs-12 col-sm-6"><input type='text' id='title' name='title' size=50></div>
			</div>

			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url"></label>
				<div class="col-xs-12 col-sm-6">
				<button class="btn" type="button" id="search-reset" >
						<i class="icon-warning bigger-110"></i>
						重設
				</button>
				<button class="btn btn-success" type="Submit">
						<i class="icon-ok bigger-110"></i>
						確定新增
				</button>
			</div>
			</div>
		</form>

			<div class="form-group">
				<div class="table-responsive">
					<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">圖片：</label>
					<div class="col-xs-12 col-sm-8">
						<!-- <div style="float:right;" id="click_add_cust">
							<button class="btn btn-success">新增圖片</button>
						</div> -->
						<form method="post"  id="update_form" role="form">
						<input type="hidden" name="cases_sn" value="<?php //echo $cases_sn;?>">
						<table id="sample-table-2" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>										
									<th class="center" style="width:80px">
										<label>
											<input id="checkDelAll_custs" type="checkbox" class="ace"  />
											<span class="lbl"></span>
										</label>
									</th>
									<th>圖片</th>									
									<th>上傳日期</th>
									<th>上傳者</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if (sizeof($photo_list) < 1) 
							{
								echo '<tr><td colspan="4"><span class="note">查無任何圖片，請由【新增圖片】功能上傳圖片</span></td></tr>';
							} 
							else 
							{							
								foreach ($photo_list as $key=>$photo) 
								{
									$sn = tryGetData('sn', $photo, NULL);
									$content_sn = tryGetData('content_sn', $photo, NULL);
									$img_filename = tryGetData('img_filename', $photo, NULL);

									if ( isNull($img_filename) ) continue;


									$url = base_url('upload/content_photo/'.$content_sn.'/'.$img_filename);
								?>
								<tr>
									<td class="center">
										<?php
										//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
										?>
										<label>
											<input type="checkbox" class="ace" name="del[]" value="<?php echo $sn.'!@'.$content_sn.'!@'.$img_filename;?>" />
											<span class="lbl"></span>
										</label>
									</td>
									<td><?php echo '<a href="'.$url.'" title="檢視大圖" target=_blank><img border="0" width="150" src="'.$url.'?"></a>'; ?></td>
									
									
									<td><?php echo tryGetData('updated', $photo, '-');?></td>
									<td><?php echo tryGetData('updated_by', $photo, '-');?></td>
								</tr>
								<?php
								}
								?>
							</tbody>
							<?php
							}
							?>
								<tfoot>
									<tr>
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteContentPhoto');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
										<td colspan="7"></td>
									</tr>
								</tfoot>
						</table>
						</form>
					</div>
				</div>
			</div>


<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上頁
			</a>					
			
		</div>
	</div>


<script type="text/javascript"> 

//To select country name
function selectParking(parking_sn, parking_id, xlocation) {
	$("#parking_sn").val(parking_sn);
	$("#parking_id").val(parking_id);
	$("#location").val(xlocation).attr("readonly",true);
	$("#suggesstion-box").hide();
}


$(function(){

	$("#search-reset").click(function(){

			$("#cust_sn").val('');
			$("#parking_id").val('').attr("readonly",false);
			$("#addr").val('').attr("readonly",false);
	});

/*
    $("search-box").autocomplete('<?php echo bUrl('ajaxGetPeople');?>', {
        minChars: 2
    });
*/
	$('#suggesstion-box').hide();

	$("#search-box").click(function(){
	    
		$("#cust_sn").val('');

		$("#addr").val('').attr("readonly",false);

		$.ajax({
				type: "GET",
				url: "<?php echo bUrl('ajaxGetParking', false);?>",
				data:'keyword='+$("#parking_id").val(),
				beforeSend: function(){
					var input = $('#parking_id');
					var inputValue = input.val();
					var nowLehgth = inputValue.length;
					input.css("background","#FFF url(http://phppot.com/demo/jquery-ajax-autocomplete-country-example/loaderIcon.gif) no-repeat 165px");
					if(inputValue != '' && nowLehgth >= 2) {
						input.css("background-image","none");
					} else {
						input.css("background-image","none");
						alert('請至少輸入二個字');
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



	/*
	$('#add_cust').hide();

	$('#click_add_cust').click(function() {

		$('#add_cust').toggle();

		if($('#add_cust').is(':hidden')) {
			$(this).text('新增車位').attr('class','btn btn-success');
		} else {
			$(this).text('取消新增').attr('class','btn btn-success');
		}


	});
	*/
});

</script>