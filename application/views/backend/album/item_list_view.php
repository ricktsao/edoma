<form role="search" action="<?php echo bUrl('contentList');?>">
    <article class="well">
		<button class="btn btn-sm btn-purple" type="button" onclick="window.location='<?php echo bUrl('contentList');?>'">回相簿列表</button>
		 <hr>
		<select id="album_sn"  name="album_sn" onchange="jUrl('<?php echo bUrl("itemList",FALSE).'?cat_sn='?>'+ $('#cat_sn').val()+'&album_sn='+$('#album_sn').val())">		
					<?php foreach ($album_list as $key => $item){?>
						<option value="<?php echo $item["sn"];?>"  <?php echo $album_sn==$item["sn"]?"selected":"" ?>><?php echo $item["title"];?></option>
					<?php } ?>						
					</select>   
        <div class="btn-group">         
             	<button type="button" class="btn btn-sm btn-purple" onclick="$('#add_zone').show();" >新增相片</button>
        </div>
      
      		<div id="add_zone" style="display:none">
					  <hr>
				<input type="file" name="fileUpload2" id="fileUpload2" class="fileUpload" multiple="multiple" />	
				<div id="details"></div>
				<div id="response"></div>
				<div id="previews"></div>	
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
                                     
                                        <th style="width:120px">照片</th>
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
                                            <?php echo $list[$i]["title"];?>
                                        </td>
                                      
                                         <td>
                                          <img Src="<?php echo $list[$i]["img_filename"]?>" style="width:200px"  ?>
                                        </td>
                                        <td>
                                            <a class="btn  btn-minier btn-info" href="<?php echo bUrl('editItem',TRUE,NULL,array('sn'=>$list[$i]['sn'])); ?>">
                                                <i class="icon-edit bigger-120"></i>edit
                                            </a>
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
                                            <a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('delItem');?>');">
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


<script>

	$(document).ready(function ()
	{
		$('.fileUpload').liteUploader(
		{
			script: '<?php echo bUrl("uploadAlbumItem",FALSE).'?album_sn='.$album_sn; ?>',
			allowedFileTypes: 'image/jpeg,image/png,image/gif',
			maxSizeInBytes: 25000000,
			customParams: {
				'custom': 'tester'
			},
			before: function ()
			{
				$('#details, #previews').empty();
				$('#response').html('Uploading...');

				return true;
			},
			each: function (file, errors)
			{
				var i, errorsDisp = '';

				if (errors.length > 0)
				{
					$('#response').html('One or more files did not pass validation');

					$.each(errors, function(i, error)
					{
						errorsDisp += '<br /><span class="error">' + error.type + ' error - Rule: ' + error.rule + '</span>';
					});
				}

				$('#details').append('<p>name: ' + file.name + ', type: ' + file.type + ', size:' + file.size + errorsDisp + '</p>');
			},
			success: function (response)
			{
				window.location = "<?php echo bUrl("itemList",FALSE).'?album_sn='.$album_sn; ?>";
			}
		});
	});

</script>      
