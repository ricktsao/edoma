<form action="" id="update_form" method="post" class="contentForm">   
		<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("contentList",FALSE);?>">
			<i class="icon-edit bigger-120"></i>回上一層
		</a>	

		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editTown",TRUE,array("sn"));?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>	
    </div> 
</article>	
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
						
							<form id="update_form">
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>										
									
										<th>城市名稱</th>
										<th style="width:220px">操作</th>
										<th style="width:120px"></th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										
										<td><?php echo $list[$i]['town_name']?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editTown",TRUE,NULL,array("sn"=>$list[$i]["sn"],"id"=>$list[$i]["city_code"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("villageList",TRUE,NULL,array("sn"=>$list[$i]["sn"],"id"=>$list[$i]["city_code"])); ?>">
												<i class="icon-edit bigger-120"></i>鄉鎮編輯
											</a>
										</td>
										<td class="center">
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $list[$i]["sn"];?>" />
												<span class="lbl"></span>
											</label>
										</td>
									</tr>
									<?php } ?>
									
									<tr>
										<td colspan="2">
											
										</td>	

										<td>
												<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteTown');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>


										</td>
										
									</tr>
									
								</tbody>								
							</table>
							<?php //echo showBackendPager($pager)?>
							</form>
						</div>
						
					</div>					
				</div>	
			</div>
		</div>
	

</form>        




