
<style type="text/css">
	th, td {text-align:center}
</style>
<div class="page-header">
	<h1>
		售屋資料
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			<!-- 請務必依據【住戶資料】的內容格式 -->
		</small>
	</h1>
</div>

<form  role="search" action="<?php echo bUrl('index');?>">
<article class="well">              
    <div class="btn-group">
		<a class="btn  btn-sm btn-purple" href="<?php echo bUrl("edit", false);?>">
			<i class="icon-edit bigger-120"></i>新增
		</a>
    </div>　　
    <div class="btn-group">
		關鍵字：<input type='text' name='keyword' value='<?php echo $given_keyword;?>'>
    </div>　
    <div class="btn-group">
		格局：
		<input type='text' size='1' name='room' value='<?php echo $given_room;?>'>房
		<input type='text' size='1' name='livingroom' value='<?php echo $given_livingroom;?>'>廳
		<input type='text' size='1' name='bathroom' value='<?php echo $given_bathroom;?>'>衛
		<input type='text' size='1' name='balcony' value='<?php echo $given_balcony;?>'>陽台
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
										<th>序號</th>
										<th style="width:250px">售屋標題</th>
										<th>格局</th>
										<th>售價</th>
										<th>坪數</th>
										<th>型態</th>
										<th>類別</th>
										<th style="width:120px">日期</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php
									//for($i=0;$i<sizeof($list);$i++) {
									$i = 0;
									foreach ( $dataset as $item) {
									?>
									<tr>
										<td style='text-align: center'><?php echo ($i+1)+(($this->page-1) * 10);?></td>
										<td style='text-align: center'><?php echo tryGetData('title', $item, '-');?></td>
										<td>
										<?php
										echo sprintf('%d 房　<br />  %d 廳　<br />  %d 衛　<br /> %d 陽台' 
													, tryGetData('room', $item)
													, tryGetData('livingroom', $item)
													, tryGetData('bathroom', $item)
													, tryGetData('balcony', $item)
													);
										?>
										</td>
										<td>
										<?php
										echo '總價：'.tryGetData('total_price', $item).' 萬元<br />';
										echo '單價：'.tryGetData('unit_price', $item).' 萬元/坪';
										?>
										</td>
										<td>
										<?php echo tryGetData('area_ping', $item).' 坪';?>
										</td>
										<td>
										<?php echo tryGetData(tryGetData('house_type', $item), config_item('house_type_array'));?>
										</td>
										<td>
										<?php echo tryGetData(tryGetData('rent_type', $item), config_item('rent_sale_type_array'));?>
										</td>
										<td><?php echo showEffectiveDate($item["start_date"], $item["end_date"], $item["forever"]) ?></td>
										<td>
											<a class="btn  btn-minier btn-info" href="<?php echo bUrl("edit",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>編輯
											</a>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("photoSetting",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>物件照片
											</a>
										</td>
										<?php
										/*
										<td>					
											<div class="col-xs-3">
												<label>
													<input name="switch-field-1" class="ace ace-switch" type="checkbox"  <?php echo tryGetData('launch', $item)==1?"checked":"" ?> value="<?php echo tryGetData('sn', $item) ?>" onClick='javascript:launch(this);' />
													<span class="lbl"></span>
												</label>
											</div>
										</td>
										*/
										?>
										
									</tr>
									<?php
										$i++;
									}
									?>
										
									
								</tbody>
								
							</table>
							
						</div>
						
					</div>					
				</div>
				
			</div>
		</div>