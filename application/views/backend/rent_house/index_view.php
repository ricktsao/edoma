
<style type="text/css">
	th, td {text-align:center}
</style>
<div class="page-header">
	<h1>
		租屋資料
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
										<th style="width:250px">租屋標題</th>
										<th>格局</th>
										<th>月租金</th>
										<th>坪數</th>
										<th style="width:150px">型態</th>
										<th>類別</th>
										<th style="width:120px">日期</th>
										<th>操作</th>
										<th class="center" style="width:80px">
											<label>
												<input type="checkbox" class="ace"  />
												<span class="lbl"></span>
											</label>
										</th>
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
										<td style='text-align: center'>
                                        <?php
                                        if ( tryGetData('is_post', $item, 0) == 1) {
                                            echo '<span class="label label-lg label-pink arrowed-right arrowed-in">聯賣</span>&nbsp;';
                                        }
                                        echo tryGetData('title', $item, '-');
                                        ?>

                                        </td>
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
										<?php echo tryGetData('rent_price', $item).' 元';?>
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
                                        <?php

                                        if ( tryGetData('is_post', $item, 0) == 1) {
                                        ?>
                                            <a class="btn  btn-minier btn-pink" href="<?php echo bUrl("check",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
                                                <i class="icon-edit bigger-120"></i>審核
                                            </a>
                                        <?php
                                        } else {
                                        ?>
                                            <a class="btn  btn-minier btn-info" href="<?php echo bUrl("edit",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
                                                <i class="icon-edit bigger-120"></i>編輯
                                            </a>
                                        <?php
                                        }
                                        ?>
											<a class="btn  btn-minier btn-purple" href="<?php echo bUrl("photoSetting",TRUE,NULL,array("sn"=>tryGetData('sn', $item))); ?>">
												<i class="icon-edit bigger-120"></i>物件照片
											</a>
										</td>
										<td class="center">
											<label>
												<input type="checkbox" class="ace" name="del[]" value="<?php echo $item["sn"];?>" />
												<span class="lbl"></span>
											</label>
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
								<tfoot>
								<tr>
										<td colspan='9'>&nbsp;</td>
										<td class="center">
											<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteHouse');?>');">
												<i class="icon-trash bigger-120"></i>刪除
											</a>
										</td>
								</tr>
								</tfoot>
							</table>

						</div>

					</div>
				</div>

			</div>
		</div>