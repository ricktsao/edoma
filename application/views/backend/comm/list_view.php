
<style type="text/css">
	th, td {text-align:center}
</style>

<div class="page-header">
	<h1>
		社區管理
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			
		</small>
	</h1>
</div>



<form  role="search" >
<article class="well">
    <div class="btn-group">
		<a class="btn  btn-sm btn-success" href="<?php echo bUrl("editComm");?>">
			<i class="icon-edit bigger-120"></i>新增社區
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

										<th style='text-align: center'>社區名稱</th>
										<th>社區ID</th>
										<th>電　話</th>
										<th>手　機</th>
										<th>地　址</th>
										<th style="width:150px">操作</th>
										<th style="width:150px">SQL檔案</th>
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
										<?php echo mask($item['id'] , 1, 6); ?>
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

										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editComm",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
										</td>
										<td>
										<?php
										$filename = prepPassword($item['id']);
										$filename = './upload/comm_sql/'.$filename.'.sql';

										if ( !file_exists($filename) ) {
										?>
											<a class="btn  btn-minier btn-yellow" href="<?php echo bUrl("generateSql",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>產出
											</a>
										<?php
										} else {
										?>
											<a class="btn  btn-minier btn-success" href="<?php echo base_url().$filename ?>">
												<i class="icon-edit bigger-120"></i>下載
											</a>
										<?php
										}
										?>
										</td>
										<td>
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo tryGetData('status', $item)==1?"checked":"" ?> value="<?php echo tryGetData('sn', $item) ?>" onClick='javascript:launch(this);' />
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
					              	<td colspan="9">
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
            data: {'sn' : obj.value  },
            url: "<?php echo bUrl("launchComm");?>",
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


