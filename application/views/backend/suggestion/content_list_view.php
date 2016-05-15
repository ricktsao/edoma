<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm <?php echo ($status==0?"btn-yellow":"btn-grey")?> " href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>0));?>">
			<i class="icon-edit bigger-120"></i>未回覆(<?php echo $status_0_cnt;?>)
		</a>	
		<a class="btn  btn-sm <?php echo ($status==1?"btn-yellow":"btn-grey")?>" href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>1));?>">
			<i class="icon-edit bigger-120"></i>已回覆(<?php echo $status_1_cnt;?>)
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
										<th>日期</th>									
										<th>住戶姓名</th>									
										<th>意見主旨</th>									
										<th>意見內容</th>
										<th style="width:120px">回覆</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo showDateFormat($list[$i]["created"],"Y-m-d") ?></td>
										<td><?php echo tryGetData($list[$i]["user_sn"],$user_map); ?></td>
										<td><?php echo $list[$i]["title"]; ?></td>
										<td><?php echo nl2br($list[$i]["content"]); ?></td>										
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
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



