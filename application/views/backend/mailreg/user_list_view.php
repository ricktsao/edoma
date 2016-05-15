
<style type="text/css">
	th, td {text-align:center}
</style>
<div class="page-header">
	<h1>
		<i class="ace-icon fa fa-angle-double-right"></i>請選擇收件人		
	</h1>
</div>
<form  role="search" >
<article class="well">
    
    <div class="btn-group">
	<?php
	// 戶別
	echo $building_part_01 .'：';
	echo form_dropdown('b_part_01', $building_part_01_array, $b_part_01);
	echo '&nbsp;&nbsp;';
	echo $building_part_02 .'：';
	echo form_dropdown('b_part_02', $building_part_02_array, $b_part_02);
	echo '&nbsp;&nbsp;';
	echo $building_part_03 .'：';
	echo '<input type="text" name="b_part_03" value="'.$b_part_03.'" size="1">';
	?>
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
										<th>操作</th>
										<th>戶　別</th>
										<th style='text-align: center'>姓　名</th>
										<th>性　別</th>
										
																				
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $list as $item) {
									?>
									<tr>
										<td style='text-align: center'>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("editContent",TRUE,NULL,array("user_sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>選擇
											</a>
										</td>
										<td style='text-align: center'>
										<?php 
										$building_id = tryGetData('building_id', $item, NULL);
										if ( isNotNull($building_id) ) {
											echo building_id_to_text($building_id);
										}
										?>
										</td>
										<td>
										<?php echo tryGetData('name', $item);?>
										</td>
										<td style='text-align: center'>
										<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
										</td>
										
										
										
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								<tr>
					              	<td colspan="4">
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
            data: {'user_sn' : obj.value  },
            url: "<?php echo bUrl("launchUser");?>",
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


