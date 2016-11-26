
<?php showOutputBox("tinymce/tinymce_js_view", array('elements' => 'content'));?>
<form action="<?php echo bUrl("updateContent")?>" method="post"  id="update_form" enctype="multipart/form-data" class="form-horizontal" role="form">
	
		
	<div class="form-group ">
		<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="case_id">發佈社區</label>
		<div class="col-xs-12 col-sm-8">
									
			<select id="drop_city" name="city_code" >
				<option value="0">縣巿</option>
	            <?php
	            if(count($city_list)>0)
				{
					foreach ($city_list as $key => $item) 
		            {
		            	echo '<option '.(  tryGetData("city_code", $edit_data)==$item["id"]?"selected":"" ).' value="'.$item["id"].'">'.$item["title"].'</option>';					
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
	            	echo '<option '.(tryGetData("town_sn", $edit_data)==$item["town_sn"]?"selected":"" ).' value="'.$item["town_sn"].'">'.$item["town_name"].'</option>';					
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
	            	echo '<option '.(tryGetData("section_sn", $edit_data)==$item["sn"]?"selected":"" ).' value="'.$item["sn"].'">'.$item["section_code"].' '.$item["section_name"].'</option>';					
	            }
			}
			else 
			{
				echo '<option value="0">村里</option>';
			}
            ?>            	
            </select>  
            
		</div>
	</div>
	
		
	<?php
		$error_css= '';
		if(isNotNull(form_error("comms")))
		{
			$error_css = 'has-error';			
		}
	?>		
	<div class="form-group <?php echo $error_css;?>">
		<label for="url" class="col-xs-12 col-sm-2 control-label no-padding-right"></label>
		
		<div class="col-xs-12 col-sm-8">
			<select multiple="multiple" size="10" name="comms[]">
			<?php 
			
				$comm_ids = tryGetData('comm_id',$edit_data);
				$comm_id_ary = explode(",", $comm_ids);
				
              	foreach ($community_list as $key => $item) 
              	{
					echo '<option value="'.$item["id"].'"  '.(in_array($item["id"], $comm_id_ary)?"selected":"").'  >'.$item["name"].'</option>';
				}
            ?>	    

			</select>
			<div class="help-block col-xs-12 col-sm-reset inline"><p><?php echo form_error("comms")?></p></div>
		</div>
		
	</div>
	<input type="hidden" name="orig_comm_id" value="<?php echo tryGetData('comm_id',$edit_data)?>"  />
	
	<?php 
	echo textOption("主旨","title",$edit_data);
	?>
	 
	<?php 
	echo textOption("廠商名稱","content",$edit_data);
	?> 
	
	<?php 
	echo textOption("廠商電話1","brief",$edit_data);
	?>
	
	<?php 
	echo textOption("廠商電話2","brief2",$edit_data);
	?>
	
	<?php 
	echo textOption("地址","img_filename2",$edit_data);
	?>
	
	<div class="form-group ">
		<label class="col-xs-12 col-sm-2 control-label no-padding-right" for="url">收費金額</label>
		<div class="col-xs-12 col-sm-6">
			<input id="url" name="url" class="width-40" value="<?php echo tryGetData("url",$edit_data);?>" type="text"> 元			
		</div>
		
				
	</div>
	
	<?php
	//  echo textAreaOption("內容","content",$edit_data);
	?>	
	
	<div class="form-group ">
        <label class="col-xs-12 col-sm-2 control-label no-padding-right" for="content">圖片</label>
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
	
	
	
	
	<?php echo pickDateOption($edit_data);?>
	<?php
	 //echo textOption("排序","sort",$edit_data); 
	 ?>
	<?php echo checkBoxOption("啟用","launch",$edit_data);?>
	
	<input type="hidden" name="sort" value="<? echo tryGetData('sort', $edit_data)?>" />
	<input type="hidden" name="sn" value="<? echo tryGetData('sn', $edit_data)?>" />
	<input type="hidden" name="content_type" value="<? echo tryGetData('content_type', $edit_data)?>" />
		

	
	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("contentList",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				Back
			</a>		
		

			&nbsp; &nbsp; &nbsp;
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				Submit
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


