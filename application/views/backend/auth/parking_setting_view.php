<?php //echo validation_errors(); ?>

<style type="text/css">
	.dataTable th[class*=sorting_] { color: #808080; }
	.dataTables_empty { text-align: center; color: #993300; font-size: 16px;}
	.require, .error {color: #d16e6c;}
	.note {color: #993300; font-size:12px; padding: 5px;}
	.dataTable td {font-size:13px; font-family:verdana;}
	#add_form {background: #f7f7f7; border-top: #d1d1d1 1px dashed; padding:10px 5px 10px 5px}

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
		車位設定
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12 form-horizontal">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">戶　別：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'>
				<?php
				$building_id = tryGetData('building_id', $user_data, NULL);
				if ( isNotNull($building_id) ) {
					echo building_id_to_text($building_id);
				}
				?>
				</span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶ID：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('id',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">住戶姓名：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('name',$user_data); ?></span></div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">行動電話：</label>
				<div class="col-xs-12 col-sm-8"><span style='font-weight:bold'><?php echo tryGetData('phone',$user_data); ?></span></div>
			</div>


			<div class="form-group">
				<div class="table-responsive">
					<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id">所屬車位：</label>
					<div class="col-xs-12 col-sm-8">
						<div style="float:right;" id="click_add_cust">
							<button class="btn btn-success">新增車位</button>
						</div>
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
									<th>車位別</th>
									<th>車號</th>
									<th>設定日期</th>
									<th>設定人</th>
								</tr>
							</thead>
							<tbody>
							<?php
							if (sizeof($exist_parking_array) < 1) {
								echo '<tr><td colspan="6"><span class="note">查無任何車位，請由【新增車位】功能設定住戶的車位資訊</span></td></tr>';
							} else {
									$note_flag = false;
									foreach ($exist_parking_array as $key=>$parking) {
									?>
									<tr>
										<td class="center">
											<?php
											//if ( sizeof($exist_lands_array) < 1 && sizeof($exists_custs_array) > 0) {
											?>
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $parking["parking_sn"].'!@'.$parking["user_sn"].'!@'.$parking["user_id"];?>" />
												<span class="lbl"></span>
											</label>
											<?php
											//} else {
											//	echo '-';
											//}
											?>
										</td>
										<td>
										<?php 
										$parking_id = tryGetData('parking_id', $parking, NULL);
										if ( isNotNull($parking_id) ) {
											echo parking_id_to_text($parking_id);
										}
										?>
										</td>
										<td><?php echo '<span style="font-size:16px">'.tryGetData('car_number', $parking, '-').'</span>';?></td>
										
										<td><?php echo tryGetData('updated', $parking, '-');?></td>
										<td><?php echo tryGetData('updated_by', $parking, '-');?></td>
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
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteUserParking');?>');">
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



			<div class="table-responsive" id="add_cust">
				<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="id"></label>
				<!-- <div class="col-xs-12 col-sm-10"> -->

				<form action="<?php echo bUrl("addUserParking")?>" method="post"  id="add_form" role="form">
				<input type='hidden' name='parking_sn' id='parking_sn' >
				<input type='hidden' name='user_sn' value='<?php echo tryGetData('sn', $user_data); ?>'>
				<input type='hidden' name='user_id' value='<?php echo tryGetData('id', $user_data); ?>'>
				
				<div class="form-group" >
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url"><span class='require'>*</span> 車位ID：</label>
					<div class="col-xs-12 col-sm-4">
						<input type='text' name='parking_id' size="15" id="parking_id">
						<button type="button" class="btn btn-minier btn-purple" id="search-box">
							<i class="ace-icon fa fa-key"></i> 搜尋
						</button>
						<div id="suggesstion-box"></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url">位置：</label>
					<div class="col-xs-12 col-sm-4"><input type='text' id='location' name='location' size=20></div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url">車號：</label>
					<div class="col-xs-12 col-sm-4"><input type='text' id='car_number' name='car_number' size=50></div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="url"></label>
					<div class="col-xs-12 col-sm-4">
					<button class="btn btn-minier" type="button" id="search-reset" >
							<i class="icon-warning bigger-110"></i>
							清除重設
					</button>
					<button class="btn btn-minier btn-success" type="Submit">
							<i class="icon-ok bigger-110"></i>
							確定新增
					</button>
				</div>
				</div>
				</form>
				<!-- </div> -->
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




	$('#add_cust').hide();

	$('#click_add_cust').click(function() {

		$('#add_cust').toggle();

		if($('#add_cust').is(':hidden')) {
			$(this).text('新增車位').attr('class','btn btn-success');
		} else {
			$(this).text('取消新增').attr('class','btn btn-success');
		}


	});
});

</script>