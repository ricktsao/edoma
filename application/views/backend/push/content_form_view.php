

<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
		
	<div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="date">發佈日期</label>
        <div class="col-xs-12 col-sm-4">
            <input type="text"  name="date" class="width-30" value="<?php echo tryGetData('date', $edit_data)?>" onclick="WdatePicker()">
        </div>
        <div class="help-block col-xs-12 col-sm-reset inline"></div>
    </div>
    <div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right">發佈時間</label>
        <div class="col-xs-12 col-sm-4">
            <select name="hh">
                <?php 
                    for($i=0;$i<24;$i++){
                        $select = "";
                        if(tryGetData('hh', $edit_data,"1")==$i){
                            $select="selected";
                        }

                        echo  "<option ${select} value='${i}'>${i}</option>";
                    }
                ?>
               
            </select>
            時
             <select name="mm">
                <?php 
                 for($i=0;$i<12;$i++){
                    $m = $i*5;
                    $select = "";
                    if(tryGetData('mm', $edit_data,"5")==$m){
                        $select="selected";
                    }

                    echo "<option ${select} value='${m}'>${m}</option>";
                }
            ?>
            </select>
            分

        </div>
        <div class="help-block col-xs-12 col-sm-reset inline"></div>
    </div>

    <?php echo textOption("訊息內容","message",$edit_data);?>
	<input type="hidden" name="sn" value="<?php echo tryGetData('sn', $edit_data)?>" />

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				回上頁
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				送出
			</button>
			
		</div>
	</div>
</form>
	
	
<script>
	$(function () {

		$(".chzn-select").chosen();

		//chosen plugin inside a modal will have a zero width because the select element is originally hidden
		//and its width cannot be determined.
		//so we set the width after modal is show
		$('#modal-form').on('show', function () {
			$(this).find('.chzn-container').each(function(){
				$(this).find('a:first-child').css('width' , '200px');
				$(this).find('.chzn-drop').css('width' , '210px');
				$(this).find('.chzn-search input').css('width' , '200px');
			});
		})
		
		
		$('#can_msg').change(function()
	    {
	    	$("textarea#msg_content").val($('#can_msg').val());
	    	//$('#msg_content').text($('#can_msg').val());
	    	//alert('tste');
	    });
		
	});

	
	var demo1 = $('select[name="comms[]"]').bootstrapDualListbox({
		filterPlaceHolder : '關鍵字',
		filterTextClear : '顯示全部',
        infoText : '共{0}個社區',
        moveAllLabel: 'Selected',
        infoTextFiltered: '<span class="label label-warning">找到</span> {0} 筆',        
        //nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
      });

		demo1.bootstrapDualListbox("getContainer").find(".btn.moveall").append("(選擇全部)");
		demo1.bootstrapDualListbox("getContainer").find(".btn.removeall").append("(全部移除)");

	  /*
	$("#update_form").submit(function() {
      alert('請選擇發布對象');
      return false;
    });
	*/
	
	
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
  