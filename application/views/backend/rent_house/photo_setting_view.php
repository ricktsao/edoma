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
		物件照片設定
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12 form-horizontal">


			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">售屋標題：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('title',$house_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">面 積：</label>
				<div class="col-xs-12 col-sm-2"><span style='font-weight:bold'><?php echo tryGetData('area_ping',$house_data).' 坪'; ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">租 金：</label>
				<div class="col-xs-12 col-sm-2"><span style='font-weight:bold'><?php echo tryGetData('rent_price',$house_data).' 元'; ?></span></div>
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">押 金：</label>
				<div class="col-xs-12 col-sm-4"><span style='font-weight:bold'><?php echo tryGetData('deposit',$house_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶姓名：</label>
				<div class="col-xs-12 col-sm-2"><span style='font-weight:bold'><?php echo tryGetData('name',$house_data); ?></span></div>
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">行動電話：</label>
				<div class="col-xs-12 col-sm-4"><span style='font-weight:bold'><?php echo tryGetData('phone',$house_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">格 局：</label>
				<div class="col-xs-12 col-sm-2"><span style='font-weight:bold'>

				<?php
				echo sprintf('%d 房 %d 廳 %d 衛' 
							, tryGetData('room', $house_data)
							, tryGetData('livingroom', $house_data)
							, tryGetData('bathroom', $house_data)
							);
				?>
				</span></div>
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">樓 層：</label>
				<div class="col-xs-12 col-sm-4"><span style='font-weight:bold'>
				<?php
				echo sprintf('共 %d 樓，位於 %d 樓' 
							, tryGetData('total_level', $house_data)
							, tryGetData('locate_level', $house_data)
							);
				?>
				</span></div>
			</div>



		<div class="hr hr-16 hr-dotted"></div>
			
		<form action="<?php echo bUrl("updatePhoto")?>" method="post"  id="add_form" role="form" enctype="multipart/form-data">
		<input type='hidden' name='house_to_rent_sn' value='<?php echo tryGetData('sn', $house_data); ?>'>
		<input type='hidden' name='comm_id' value='<?php echo tryGetData('comm_id', $house_data); ?>'>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">新增照片：</label>
				<div class="col-xs-12 col-sm-6"><input type='file' id='filename' name='filename' size=20></div>
			</div>
			<div class="form-group">
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
					<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">物件照片：</label>
					<div class="col-xs-12 col-sm-8">
						<!-- <div style="float:right;" id="click_add_cust">
							<button class="btn btn-success">新增照片</button>
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
									<th>照片</th>
									<th>說明</th>
									<th>上傳日期</th>
									<th>上傳者</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if (sizeof($exist_photo_array) < 1) {
								echo '<tr><td colspan="6"><span class="note">查無任何照片，請由【新增照片】功能上傳物件照片</span></td></tr>';
							} else {
									$note_flag = false;
									foreach ($exist_photo_array as $key=>$photo) {

										$sn = tryGetData('sn', $photo, NULL);
										$house_to_rent_sn = tryGetData('house_to_rent_sn', $photo, NULL);
										$filename = tryGetData('filename', $photo, NULL);

										if ( isNull($filename) ) continue;

										// 縮圖
										//$thumb = 'thumb_'.$filename;
										$thumb = $filename;
										$comm_id = tryGetData('comm_id', $house_data);
										$thumb = base_url('upload/'.$comm_id.'/house_to_rent/'.$house_to_rent_sn.'/'.$thumb);
										$url = base_url('upload/'.$comm_id.'/house_to_rent/'.$house_to_rent_sn.'/'.$filename);
									?>
									<tr>
										<td class="center">
											<?php
											//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
											?>
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $sn.'!@'.$house_to_rent_sn.'!@'.$filename;?>" />
												<span class="lbl"></span>
											</label>
										</td>
										<td><?php echo '<a href="'.$url.'" title="檢視大圖" target=_blank><img border="0" width="150" src="'.$thumb.'?"></a>'; ?></td>
										<td><?php echo tryGetData('title', $photo, '-');?></td>
										
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
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deletePhoto');?>');">
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