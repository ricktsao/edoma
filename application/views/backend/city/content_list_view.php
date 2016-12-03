<form action="" id="update_form" method="post" class="contentForm">   
	
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="table-responsive">
						
							<form id="update_form">
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>										
										<th style="width:100px">城市代碼</th>
										<th>城市名稱</th>
										<th style="width:220px">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo $list[$i]['id']?></td>
										<td><?php echo $list[$i]['title']?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("id"=>$list[$i]["id"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<a class="btn  btn-minier btn-success" href="<?php echo bUrl("townList",TRUE,NULL,array("id"=>$list[$i]["id"])); ?>">
												<i class="icon-edit bigger-120"></i>區編輯
											</a>
										</td>
									</tr>
									<?php } ?>
									
									<tr>
										<td colspan="4">
											
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

<script type="text/javascript"> 

	
	function launch(obj) {		
	
	 $.ajax({ 
            type : "POST",
            data: {'content_sn' : obj.value  },
            url: "<?php echo bUrl("launchContent");?>",
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



