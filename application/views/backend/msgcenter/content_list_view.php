<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editContent",FALSE);?>">
			<i class="icon-edit bigger-120"></i>編輯訊息
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
										<th style="width:80px">序號</th>
										<th style="width:200px">訊息標題</th>										
										<th>訊息內容</th>										
										<th>發送人員</th>									
										<th style="width:200px"><i class="icon-time bigger-110 hidden-480"></i>發送時間</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($msg_list as $key => $item) 
								{									
									echo
									'
									<tr>
										<td>'.(($key+1)+(($this->page-1) * 10)).'</td>										
										<td>'.$item["title"].'</td>
										<td>'.nl2br($item["msg_content"]).'</td>
										<td>										
											<div class="tooltiptd" style="display:inline-block;">
												<span class="tooltiptitle">共'.$item["to_user_count"].'人</span>												
												<div class="tooltip" style="width:500%;">
													<ul>
														'. $item["to_user_name"].'
													</ul>
												</div>
											
											</div>
										</td>
										<td>'.showDateFormat($item["created"]).'</td>
									</tr>
									';
								}
								
								?>		
									
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



