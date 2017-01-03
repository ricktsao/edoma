<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">        
	    <a class="btn  btn-sm btn-success btn_margin" target="_blank" href="<?php echo bUrl("editContent",TRUE); ?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>      
    </div>          
</article>	
		
</form>

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
										<th style="width:100px">序號</th>
										<th style="width:120px">預定發送時間</th>		
										<th>內容</th>
										<th style="width:100px">創建者</th>
										<th style="width:100px">發送狀態</th>
										<th style="width:120px">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo $list[$i]["push_time"]; ?></td>
										<td><?php echo nl2br($list[$i]["message"]);?></td>
										<td><?php echo nl2br($list[$i]["created_by"]);?></td>
										<td><?php echo nl2br($list[$i]["flag_push"]);?></td>
										<td>
											<?php if($list[$i]["flag_push"]==0){?>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<a class="btn  btn-minier btn-danger" href="<?php echo bUrl("del",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>刪除
											</a>

											<?php }?>
										    
										</td>					
									</tr>
									<?php } ?>									
								</tbody>								
							</table>
							<?php echo showBackendPager($pager)?>
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



