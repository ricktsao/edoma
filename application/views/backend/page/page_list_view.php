


<form action="" id="update_form" method="post" class="contentForm">   
	
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
							
							<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editPage"); ?>">
								<i class="icon-edit bigger-120"></i>新增
							</a>							
							<div class="hr hr-18 dotted hr-double"></div>
							
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>										
										<th>序號</th>
										<th>單元</th>
										<th><i class="icon-time bigger-110 hidden-480"></i>有效日期</th>

										<th>編輯</th>
										<th>啟用/停用</th>
										<th class="center" style="width:80px">
											<label>
												<input type="checkbox" class="ace"  />
												<span class="lbl"></span>
											</label>
										</th>
									</tr>
								</thead>
								<tbody>
									<? for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo $list[$i]["title"]?></td>
										<td><?php echo showEffectiveDate($list[$i]["start_date"], $list[$i]["end_date"], $list[$i]["forever"]) ?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editPage",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>edit
											</a>
										</td>
										<td>					
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo $list[$i]["launch"]==1?"checked":"" ?> value="<?php echo $list[$i]["sn"] ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										</td>
										<td class="center">
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $list[$i]["sn"];?>" />
												<span class="lbl"></span>
											</label>
										</td>
									</tr>
									<? } ?>
									
									<tr>
										<td colspan="5"></td>	
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deletePage');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
									</tr>
									
									
								</tbody>
								
							</table>
							
						</div>
						
					</div>					
				</div>
				
			</div>
		</div>
	

</form>        

<script type="text/javascript"> 

	
	function launch(obj) {		
	
	 $.ajax({ 
            type : "POST",
            data: {'launch_sn' : obj.value  },
            url: "<?php echo bUrl("launchItem");?>",
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


