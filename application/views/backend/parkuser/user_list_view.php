
<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		獨立車位所有人
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>



<form  role="search" >
<article class="well">
    <div class="btn-group">
		<a class="btn  btn-sm btn-success" href="<?php echo bUrl("editUser/?role=P");?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>
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

										<th style='text-align: center'>姓　名</th>
										<th>性　別</th>
										<th>電　話</th>
										<th>手　機</th>
										<th>地　址</th>
										<th style="width:150px">操作</th>
										<th>啟用/停用</th>
										
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
										/*$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											$building_parts = building_id_to_text($building_id, true);
										}*/
									?>
									<tr>
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td>
										<?php echo tryGetData('name', $item);?>
										</td>
										<td style='text-align: center'>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										<td>
										<?php echo tryGetData('tel', $item);?>
										</td>
										<td>
										<?php echo tryGetData('phone', $item);?>
										</td>
										<td>
										<?php echo tryGetData('addr', $item);?>
										</td>

										<td style="text-align:left; padding-left:10px;">
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editUser",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("setParking",TRUE,NULL,array("sn"=>tryGetData('sn', $item), "role"=>tryGetData('role', $item))); ?>">
												<i class="icon-edit bigger-120"></i>車位設定
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
					              	<td colspan="8">
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


