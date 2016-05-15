<form role="search" action="">
    <article class="well">
  	 <button class="btn btn-sm btn-purple" type="button" onclick="window.location='<?php echo bUrl("itemList",true,array('item_sn'))?>'">返回</button>       
    </article>
    <span style="display: none" class="label label-sx label-warning">Hot於前端首頁只顯示1則(列表第一筆)</span>
</form>
<form action="<?php echo bUrl('updateItem')?>" method="post" id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php echo textOption("主題","title",$edit_data); ?>
    <div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="content">照片</label>
        <div class="col-xs-12 col-sm-6">
            <input type="file" name="img_filename" size="20" /><br /><br />
				<input type="hidden" name="orig_img_filename" value="<?php echo tryGetData('orig_img_filename',$edit_data)?>"  />
				<?php if(isNotNull(tryGetData('img_filename',$edit_data))){ ?>
				<img  border="0" style="width:200px;" src="<?php echo tryGetData('img_filename',$edit_data); ?>"><br />		
				
            	<?php } ?>
        <div class="message">
            <?php echo  form_error('start_date');?>
        </div>
        </div>
    </div>
    <?php echo textOption("排序","sort",$edit_data); ?>   
    <input type="hidden" name="album_sn" value="<?php echo $album_sn?>" />
    <input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <a class="btn" href="<?php echo bUrl('itemList',TRUE,array('album_sn')) ?>">
                <i class="icon-undo bigger-110"></i> Back
            </a>
            &nbsp; &nbsp; &nbsp;
            <button class="btn btn-info" type="Submit">
                <i class="icon-ok bigger-110"></i> Submit
            </button>
        </div>
    </div>
</form>

