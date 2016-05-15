<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'description'));?>
<form action="<?php echo bUrl('updateContent')?>" method="post" id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php echo textOption("主題","title",$edit_data); ?>
    <div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="content">時間</label>
        <div class="col-xs-12 col-sm-6">
            <input name="start_date" type="text" class="inputs2" value="<?php echo showDateFormat(tryGetArrayValue( 'start_date', $edit_data))?>" onclick="WdatePicker()" />
        <div class="message">
            <?php echo  form_error('start_date');?>
        </div>
        </div>
    </div>
    <?php echo textOption("排序","sort",$edit_data); ?>   
    <input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <a class="btn" href="<?php echo bUrl('contentList',TRUE,array('sn')) ?>">
                <i class="icon-undo bigger-110"></i> Back
            </a>
            &nbsp; &nbsp; &nbsp;
            <button class="btn btn-info" type="Submit">
                <i class="icon-ok bigger-110"></i> Submit
            </button>
        </div>
    </div>
</form>
