<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              

    <div class="btn-group">        
	    <a class="btn  btn-sm btn-danger btn_margin" target="_blank" href="<?php echo bUrl("showPdfList",TRUE); ?>">
			<i class="icon-edit bigger-120"></i>PDF報表
		</a>      
    </div>  

    <div class="btn-group" >     
    	<select name="status" onchange="window.location ='<?php echo bUrl("contentList",FALSE)?>?status='+this.value">
    		<option value="">處理狀態</option>
    		<option value="0" <?php echo $status==0?"selected":""; ?> >未回覆</option>
    		<option value="1" <?php echo $status==1?"selected":""; ?> >已回覆</option>    		
    	</select>   
         
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
										<th>社區</th>		
										<th>主旨</th>									
										<th>內容</th>
										<th style="width:100px">狀態</th>
										<th style="width:120px">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo tryGetData($list[$i]["comm_id"], $comm_map); ?></td>
										<td><?php echo $list[$i]["title"]; ?></td>
										<td>
											<?php echo nl2br($list[$i]["content"]);?>
																					
											<?php
											if(isNotNull($list[$i]["brief2"]))
											{
												echo '<hr>回覆:<br>';
												echo '<span style="color:red;">	';												
												echo nl2br($list[$i]["brief2"]);
												echo '<br>['.$list[$i]["update_date"].']';
												echo '</span>'; 
											}
											?>		
														
										</td>
										<td><?php echo $list[$i]["target"]==1?"<span style='color:blue'>已回覆</span>":"<span style='color:red'>未回覆</span>";?></td>
										<td>
											<?php if($list[$i]["target"]==0){ ?>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
										    <?php }else{echo '-';} ?>
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



