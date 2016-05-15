<form action="<?php echo bUrl('updateContent')?>" method="post" id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php echo textOption("投票主題","subject",$edit_data); ?>
    <?php echo textAreaOption("解說","description",$edit_data);?>
    <?php echo pickDateOption($edit_data);?>
    <?php echo checkBoxOption("匿名投票","allow_anony",$edit_data);?>
    <?php echo checkBoxOption("表單複選","is_multiple",$edit_data);?>
    <hr>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 control-label no-padding-right" for="subject">投票項目
        </label>
        <div class="col-xs-12 col-sm-6">
            <button class="btn btn-primary btn-sm" id="add_option">新增一筆</button>
        </div>
    </div>
    <div id="voting_list">
    	<?php for($i=0;$i<count($edit_data['voting_option']);$i++):?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 control-label no-padding-right" for="subject"></label>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="voting_option[]" class="width-40" value="<?php echo $edit_data['voting_option'][$i]['text']?>">
                <button class="btn btn-danger btn-xs">X</button>
            </div>
        </div>
    	<?php endfor;?>
    </div>
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
    <script>
    var votingList = $('#voting_list');
    $(function() {

        $('input[name=forever]').parent().remove();

        votingList.find('button').each(function() {
        	$(this).click(removeOption);
        })

        $('#add_option').click(function() {
            event.preventDefault();
            votingList.append(votingOptionCreate()).find('button').click(removeOption);
        })
    })


    function votingOptionCreate() {
        var _t = '<div class="form-group">';
        _t += '<label class="col-xs-12 col-sm-3 control-label no-padding-right" for="subject"></label>';
        _t += '<div class="col-xs-12 col-sm-6">';
        _t += '<input type="text"  name="voting_option[]" class="width-40" value="">';
        _t += ' <button class="btn btn-danger btn-xs">X</button>';
        _t += '</div></div>';

        return _t;
    }

    function removeOption() {
        event.preventDefault();
        $(this).parents('.form-group').remove();
    }
    </script>
</form>
