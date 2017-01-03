<form  role="search" action="<?php echo bUrl('contentList');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("editContent",FALSE);?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>	
    </div>

    <div class="btn-group">        
	    <a class="btn  btn-sm btn-danger btn_margin" target="_blank" href="<?php echo bUrl("showPdfList",TRUE); ?>">
			<i class="icon-edit bigger-120"></i>PDF報表
		</a>      
    </div>  

    <div class="btn-group">
		<select name="is_cost">
			<option value="">收費篩選...</option>
			<option value="1" <?php echo tryGetData("is_cost",$query_data)=='1'?"selected":""; ?> >有收費</option>
			<option value="0" <?php echo tryGetData("is_cost",$query_data)=='0'?"selected":""; ?> >不收費</option>
		</select>
	</div>

    <div class="btn-group">
		<input type="text" name="mname" class="form-control" placeholder="廠商名稱" value="<?php echo tryGetData("mname",$query_data); ?>">
	</div>
    
    <div class="btn-group">
		<input type="text" name="tel" class="form-control" placeholder="電話" value="<?php echo tryGetData("tel",$query_data); ?>">
	</div>
    
    <div class="btn-group">
		<input type="text" name="s_date" class="form-control" onclick="WdatePicker()" placeholder="收費起算日" value="<?php echo tryGetData("s_date",$query_data); ?>">
	</div>
	
	<div class="btn-group">
		<input type="text" name="e_date" class="form-control" onclick="WdatePicker()" placeholder="收費結束日" value="<?php echo tryGetData("e_date",$query_data); ?>">
	</div>
   
    <div class="btn-group" style=" background: ">
        <button class="btn btn-white btn-warning btn-bold" type="submit">
			<i class="ace-icon fa fa-search  bigger-120 orange"></i>
			搜尋
		</button>
        
    </div>              
</article>	
<span style="display: none" class="label label-sx label-warning">Hot於前端首頁只顯示1則(列表第一筆)</span>		
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
										<th>課程主旨</th>									
										<th>廠商名稱</th>
										<th>聯絡電話一</th>
										<th>聯絡電話二</th>
										<th style="width:200px">收費起訖日</th>
										<th>收費金額</th>	

										<th style="width:120px">操作</th>
										<th style="width:120px">啟用/停用</th>										
										<th class="center" style="width:80px">
											<label>
												<input type="checkbox" class="ace"  />
												<span class="lbl"></span>
											</label>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<sizeof($list);$i++){ ?>
									<tr>
										<td><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td><?php echo $list[$i]["title"]; ?></td>
										<td><?php echo $list[$i]["filename"]; ?></td>
										<td><?php echo $list[$i]["brief"]; ?></td>
										<td><?php echo $list[$i]["brief2"]; ?></td>
										<td><?php echo showEffectiveDate($list[$i]["start_date"], $list[$i]["end_date"], $list[$i]["forever"]) ?></td>
										<td>
										<?php
											$cost = tryGetData("url", $list[$i]);
											if($cost != "")
											{
												echo $cost." 元";
											}
											else 
											{
											 	echo "不收費";
											}
											  
										?>
										</td>										

										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>	

											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("contentPhoto",TRUE,NULL,array("sn"=>$list[$i]["sn"])); ?>">
												<i class="icon-edit bigger-120"></i>圖片
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
									<?php } ?>
									
									<tr>
										<td colspan="9">
											
										</td>	
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteContent');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
									</tr>
									
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



