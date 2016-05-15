<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm <?php echo ($status==0?"btn-yellow":"btn-grey")?> " href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>0));?>">
			<i class="icon-edit bigger-120"></i>報修中(<?php echo $status_0_cnt;?>)
		</a>	
		<a class="btn  btn-sm <?php echo ($status==1?"btn-yellow":"btn-grey")?>" href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>1));?>">
			<i class="icon-edit bigger-120"></i>已讀(<?php echo $status_1_cnt;?>)
		</a>	
		<a class="btn  btn-sm <?php echo ($status==2?"btn-yellow":"btn-grey")?>" href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>2));?>">
			<i class="icon-edit bigger-120"></i>勘驗(<?php echo $status_2_cnt;?>)
		</a>
		<a class="btn  btn-sm <?php echo ($status==3?"btn-yellow":"btn-grey")?>" href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>3));?>">
			<i class="icon-edit bigger-120"></i>估價(<?php echo $status_3_cnt;?>)
		</a>
		<a class="btn  btn-sm <?php echo ($status==4?"btn-yellow":"btn-grey")?>" href="<?php echo bUrl("contentList",true,array("all"=>"all"),array("status"=>4));?>">
			<i class="icon-edit bigger-120"></i>完工(<?php echo $status_4_cnt;?>)
		</a>
    </div>   
    <div class="btn-group" style="display:none">
        
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
						
							<form id="update_form">
							<table id="sample-table-1" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>										
										<th style="width:100px">序號</th>
										<th>報修日期</th>									
										<th>住戶姓名</th>									
										<th>維修範圍</th>
										<th>報修內容 </th>
										<th>處理進度 </th>
										<th style="width:120px">處理</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo showDateFormat($list[$i]["created"],"Y-m-d") ?></td>
										<td><?php echo tryGetData($list[$i]["user_sn"],$user_map); ?></td>
										<td><?php echo tryGetData($list[$i]["type"],$this->config->item('repair_type')); ?></td>
										<td><?php echo nl2br($list[$i]["content"]); ?></td>
										<td><?php echo tryGetData($list[$i]["status"],$this->config->item('repair_status')); ?></td>										
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



