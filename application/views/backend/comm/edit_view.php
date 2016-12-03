<style type="text/css">
	.error {color:#c00;}
</style>
<div class="page-header">
	<h1>
		社區資料編輯
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>
</div>

<?php
  //if(validation_errors() != false) {
  //  echo "<div id='errors'>" . validation_errors() . "</div>" ;
  //}
?>
<form action="<?php echo bUrl("updateUser")?>" method="post"  id="update_form" class="form-horizontal" role="form">

<?php
echo form_hidden('old_city_code', tryGetData('city_code', $edit_data));
echo form_hidden('old_town_sn', tryGetData('town_sn', $edit_data));
echo form_hidden('old_village_sn', tryGetData('village_sn', $edit_data));
$tmp = '';
if (form_error('city_code') || form_error('town_sn') || form_error('village_sn') ) {
	$tmp = 'has-error';
}
?>
	<div class="form-group <?php echo $tmp;?>">
		<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="case_id">地理位置</label>
		<div class="col-xs-12 col-sm-5">
<?php
if (tryGetData('sn', $edit_data) > 0) {
	echo '<p>目前為 &raquo; '.$edit_data["village"];
	echo '<p>變更為 &raquo; ';
}
?>
			<select id="drop_city" name="city_code" >
				<option value="0">縣巿</option>
	            <?php
	            if(count($city_list)>0)
				{
					foreach ($city_list as $key => $item)
		            {
		            	//echo '<option '.(  tryGetData("city_code", $edit_data, 0)==$item["id"]?"selected":"" ).' value="'.$item["id"].'">'.$item["title"].'</option>';
		            	echo '<option value="'.$item["id"].'">'.$item["title"].'</option>';
		            }
				}
	            ?>
            </select>

            <select id="drop_town" name="town_sn" >
            <?php
            if(count($town_list)>0)
			{
				foreach ($town_list as $key => $item)
	            {
	            	//echo '<option '.(tryGetData("town_sn", $edit_data)==$item["town_sn"]?"selected":"" ).' value="'.$item["town_sn"].'">'.$item["town_name"].'</option>';
	            	echo '<option  value="'.$item["town_sn"].'">'.$item["town_name"].'</option>';
	            }
			}
			else
			{
				echo '<option value="0">鄉鎮區</option>';
			}
            ?>
            </select>

            <select id="drop_village" name="village_sn" >
            <?php
            if(count($village_list)>0)
			{
				foreach ($village_list as $key => $item)
	            {
	            	//echo '<option '.(tryGetData("village_sn", $edit_data)==$item["sn"]?"selected":"" ).' value="'.$item["sn"].'">'.$item["section_code"].' '.$item["section_name"].'</option>';
	            	echo '<option  value="'.$item["sn"].'">'.$item["section_code"].' '.$item["section_name"].'</option>';
	            }
			}
			else
			{
				echo '<option value="0">村里</option>';
			}
            ?>
            </select>
		</div>
		<div class="col-xs-12 col-sm-3">
            <?php
            echo form_error('city_code');
            echo form_error('town_sn');
            echo form_error('village_sn');
            ?>
		</div>
	</div>

	<?php echo textOption("＊社區名稱","name",$edit_data);?>
	<?php echo textOption("＊連絡人","contact",$edit_data);?>
	<?php echo textOption("連絡人 2","contact2",$edit_data);?>
	<?php echo textOption("連絡人 3","contact3",$edit_data);?>
	<?php echo textOption("行動電話","phone",$edit_data,'行動電話、電話請至少擇一填寫');?>
	<?php echo textOption("電話","tel",$edit_data);?>
	<?php echo textOption("電話 2","phone2",$edit_data);?>
	<?php echo textOption("電話 3","phone3",$edit_data);?>
	<?php echo textOption("電話 4","phone4",$edit_data);?>
	<?php echo textOption("電話 5","phone5",$edit_data);?>
	<?php echo textOption("電話 6","phone6",$edit_data);?>
	<?php echo textOption("＊住址", "addr", $edit_data);?>

	<?php //echo pickDateOption($edit_data);?>

	<?php echo checkBoxOption("啟　用", "status", $edit_data);?>

	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("index",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				返回
			</a>


			&nbsp; &nbsp; &nbsp;

			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				確定送出
			</button>

		</div>
	</div>

	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />
</form>


<script type="text/javascript">


//區域查詢連動選單 start
//------------------------------------------------------------------------------------


function resetCity()
{
	$("#drop_city option").remove();
}

function resetTown()
{
	$("#drop_town option").remove();

}

function resetVillage()
{
	$("#drop_village option").remove();
}



function queryCityList()
{
   $.ajax
    (
        {
            type: "GET",
            url: "<?php echo bUrl("ajaxGetCityList");?>",
            timeout: 3000 ,
            dataType: "json",
            error: function( xhr )
            {
                //不處理
            },
            success: function( vData )
            {
                //  移除全部的項目
                resetCity();
				resetTown();
				resetVillage();
                for(i=0;i<vData.length;i++)
                {
                    $('#drop_city').append('<option value="'+vData[i]["id"]+'" >'+vData[i]["title"]+'</option>');
                }

                $('#drop_city option:eq(0)').attr('selected', true);

                queryTownList();
            }
        }
    );
}


function queryTownList()
{
   $.ajax
    (
        {
            type: "GET",
            url: "<?php echo bUrl("ajaxGetTownList");?>",
            timeout: 3000 ,
            data: {'city_code' :  $('#drop_city').val()  },
            dataType: "json",
            error: function( xhr )
            {
                //不處理
            },
            success: function( vData )
            {
                //  移除全部的項目
                resetTown();
				resetVillage();
                for(i=0;i<vData.length;i++)
                {
                    $('#drop_town').append('<option value="'+vData[i]["sn"]+'" >'+vData[i]["town_name"]+'</option>');
                }

                $('#drop_town option:eq(0)').attr('selected', true);

                queryVillageList();
            }
        }
    );
}


function queryVillageList()
{
   $.ajax
    (
        {
            type: "GET",
            url: "<?php echo bUrl("ajaxGetVillageList");?>",
            timeout: 3000 ,
            data: {'city_code' :  $('#drop_city').val(),'town_sn': $('#drop_town').val() },
            dataType: "json",
            error: function( xhr )
            {
                //不處理
            },
            success: function( vData )
            {
                //  移除全部的項目
				resetVillage();
                for(i=0;i<vData.length;i++)
                {
                    $('#drop_village').append('<option value="'+vData[i]["sn"]+'" >'+vData[i]["sn"] + ' ' +vData[i]["village_name"]+'</option>');
                }
                queryCommunityList();
            }
        }
    );
}

function queryCommunityList()
{
	//var demo1 = $('select[name="comms[]"]').bootstrapDualListbox();

   $.ajax
    (
        {
            type: "GET",
            url: "<?php echo bUrl("ajaxGetCommunity");?>",
            timeout: 3000 ,
            data: {'city_code' :  $('#drop_city').val(),'town_sn': $('#drop_town').val(),'village_sn' : $('#drop_village').val() },
            dataType: "json",
            error: function( xhr )
            {
                //不處理
            },
            success: function( vData )
            {
                //  移除全部的項目
				demo1.find("option").remove();
                for(i=0;i<vData.length;i++)
                {

                	demo1.append('<option value="'+vData[i]["id"]+'" >'+vData[i]["name"]+'</option>');

                    //$('#drop_village').append('<option value="'+vData[i]["sn"]+'" >'+vData[i]["sn"] + ' ' +vData[i]["village_name"]+'</option>');
                }

                demo1.bootstrapDualListbox('refresh');

            }
        }
    );


}





$(function() {




    $('#drop_city').change(function()
    {
    	queryTownList();
    });


   $('#drop_town').change(function()
   {
   		queryVillageList();
   });


   $('#drop_village').change(function()
   {
   		queryCommunityList();
   });


});

//------------------------------------------------------------------------------------
//區域查詢連動選單 end
</script>