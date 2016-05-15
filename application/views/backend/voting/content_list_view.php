<form role="search" action="<?php echo bUrl('contentList');?>">
    <article class="well">
        <div class="btn-group">
            <a class="btn  btn-sm btn-purple" href="<?php echo bUrl('editContent',FALSE);?>">
                <i class="icon-edit bigger-120"></i>新增
            </a>
        </div>
        <div class="btn-group" style="display:none">
            <button type="submit" class="btn btn-primary btn-sm btn_margin"><i class="icon-search nav-search-icon"></i>搜尋</button>
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
                                        <th>標題</th>
                                        <th style="width:200px"><i class="icon-time bigger-110 hidden-480"></i>有效日期</th>
                                        <th style="width:120px">編輯</th>
                                    
                                        <th class="center" style="width:80px">
                                            <label>
                                                <input type="checkbox" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($i=0;$i<sizeof($list);$i++){ ?>
                                    <tr>
                                        <td>
                                            <?php echo ($i+1)+(($this->page-1) * 10);?>
                                        </td>
                                        <td>
                                            <?php echo $list[$i]["subject"];?>
                                        </td>
                                        <td>
                                            <?php echo $list[$i]["start_date"]." ~ ".$list[$i]["end_date"];?>
                                        </td>
                                        <td>
											<?php if($list[$i]["active"]==TRUE):?>
											  <a class="btn  btn-minier btn-info" href="<?php echo bUrl('votingRecord',TRUE,NULL,array('sn'=>$list[$i]['sn'])); ?>">
                                                <i class="icon-edit bigger-120"></i>觀看結果
                                            </a>

                                             <a class="btn  btn-minier btn-danger" href="<?php echo bUrl('showPdf',TRUE,NULL,array('sn'=>$list[$i]['sn'])); ?>">
                                                <i class="icon-edit bigger-120"></i>輸出PDF
                                            </a>
											<?php else:?>
                                            <a class="btn  btn-minier btn-info" href="<?php echo bUrl('editContent',TRUE,NULL,array('sn'=>$list[$i]['sn'])); ?>">
                                                <i class="icon-edit bigger-120"></i>edit
                                            </a>
                                        	<?php endif;?>
                                        </td>
                                      
                                        <td class="center">
                                            <label>
                                                <input type="checkbox" class="ace" name="del[]" value="<?php echo $list[$i]['sn'];?>" />
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4">
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
        type: "POST",
        data: {
            'content_sn': obj.value
        },
        url: "<?php echo bUrl("
        launchContent ");?>",
        timeout: 3000,
        error: function(xhr) {
            //不處理
        },
        success: function(result) {
            if (result == 1) {
                $(obj).prop("checked", true);
            } else {
                $(obj).prop("checked", false);
            }

        }
    });
}
</script>
