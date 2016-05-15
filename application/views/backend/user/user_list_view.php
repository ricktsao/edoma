
<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		<?php echo $headline;?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			可依需求切換住戶資料列表
		</small>
	</h1>
</div>

<article class="well">
    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("index/", false);?>">
			<i class="icon-edit bigger-120"></i>所有住戶列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listMgrs", false);?>">
			<i class="icon-edit bigger-120"></i>管委人員列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listConts", false);?>">
			<i class="icon-edit bigger-120"></i>緊急聯絡人列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-yellow" href="<?php echo bUrl("listOwns", false);?>">
			<i class="icon-edit bigger-120"></i>所有權人列表
		</a>
    </div>

    <div class="btn-group">
		<a class="btn  btn-sm btn-info" target="_blank" href="<?php echo bUrl("exportExcel", false);?>">
			<i class="icon-edit bigger-120"></i>住戶資料匯出
		</a>
    </div>

</article>


<form  role="search" >
<article class="well">
    <div class="btn-group">
		<a class="btn  btn-sm btn-success" href="<?php echo bUrl("editUser/?role=I");?>">
			<i class="icon-edit bigger-120"></i>新增住戶
		</a>
    </div>

    <div class="btn-group">
	<?php
	// 戶別
	echo $building_part_01 .'：';
	echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
	echo '&nbsp;&nbsp;';
	echo $building_part_02 .'：';
	echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
	echo '&nbsp;&nbsp;';
	echo $building_part_03 .'：';
	echo '<input type="text" name="b_part_03" value="'.$b_part_03.'" size="1">';
	?>
    </div>
	
    <div class="btn-group">
		關鍵字：<input type='text' name='keyword' value='<?php echo $given_keyword;?>'>
    </div>    

    <div class="btn-group">
		<button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>
    </div>
</article>	

</form>


<form action="" id="update_form" method="post" class="contentForm"> 
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>序號</th>
										<th><?php echo $building_part_01;?></th>
										<th><?php echo $building_part_02;?></th>
										<th width="80"><?php echo $building_part_03;?></th>
										<th style='text-align: center'>住戶姓名</th>
										<th style='text-align: center'>磁　卡</th>
										<th>APP開通</th>
										<th>所有權人</th>
										<th>緊急<br />聯絡人</th>
										<th>管委</th>
										<th colspan="3">操作</th>
										<th>啟用/停用</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
										$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											$building_parts = building_id_to_text($building_id, true);
										}
									?>
									<tr>
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo $building_parts[0];?></td>
										<td style='text-align: center'><?php echo $building_parts[1];?></td>
										<td style='text-align: center'><?php echo $building_parts[2];?></td>
										<td>
										<?php echo tryGetData('name', $item);?>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										<td>
										<?php echo tryGetData('id', $item);?>
										</td>
										<td>
										<?php
										if (isNotNull(tryGetData("app_id", $item, NULL))) {
											echo tryGetData("app_id", $item);
										} else echo '尚未開通';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_owner", $item) == 1) {
											echo '是';
										} else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_contact", $item) == 1) {
											echo '是';
										} else echo '否';
										?>
										</td>
										<td>
										<?php
										if (tryGetData("is_manager", $item) == 1) {
											$manager_title =  tryGetData("manager_title", $item);
											echo tryGetData($manager_title, $manager_title_array);
										} else echo '否';
										?>
										</td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
										</td>
										<td>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>車位設定
											</a>
										</td>
										<td>
											<a class="btn  btn-minier btn-pink" href="<?php echo bUrl("changeId",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>磁卡變更
											</a>
										</td>
										<td>
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo tryGetData('launch', $item)==1?"checked":"" ?> value="<?php echo tryGetData('sn', $item) ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										</td>
										
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								<tr>
					              	<td colspan="14">
									<?php echo showBackendPager($pager)?>
					                </td>
								</tr>
								
							</table>
							
						</div>
						
					</div>					
				</div>
				
			</div>
		</div>
	

</form>        

<script type="text/javascript"> 

	
	function launch(obj) 
	{		
	
	 $.ajax({ 
            type : "POST",
            data: {'user_sn' : obj.value  },
            url: "<?php echo bUrl("launchUser");?>",
            timeout: 3000 ,
            error: function( xhr ) 
            {
                //不處理
            },
            success : function(result) 
            {
            	if(result == 1)
            	{
            		$(obj).prop("checked", true);	
            	}
            	else
            	{
            		$(obj).prop("checked", false);
            	}
           		     
            }
        });	 
	}
</script>


