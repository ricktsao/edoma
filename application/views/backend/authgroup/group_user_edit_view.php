<div class="row">
<div class="col-xs-12">
<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
			<h2><?php echo $group_info["title"]?> - 群組清單</h2>
			<form action="" id="update_form" method="post" class="contentForm">  
			<table id="sample-table-1" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>										
						<th class="center" style="width:80px">
							<label>
								<input id="checkDelAll" type="checkbox" class="ace"  />
								<span class="lbl"></span>
							</label>
						</th>
						
						<th>帳號</th>
						<th>姓名</th>
						<th>職稱</th>						
						<th>行動電話</th>
						<th><i class="icon-time bigger-110 hidden-480"></i>有效日期</th>
						
					</tr>
				</thead>
				<tbody>
					<?php for($i=0;$i<sizeof($user_list);$i++){ ?>
					<tr>
						<td class="center">
							<label>
								<input type="checkbox" class="ace" name="del[]" value="<?php echo $user_list[$i]["sn"];?>" />
								<span class="lbl"></span>
							</label>
						</td>
						
						<td><?php echo $user_list[$i]["id"]?></td>
						<td><?php echo $user_list[$i]["name"]?></td>
						<td><?php echo $user_list[$i]["title"]?></td>					
						<td><?php echo $user_list[$i]["phone"]?></td>
						<td><?php echo showEffectiveDate($user_list[$i]["start_date"], $user_list[$i]["end_date"], $user_list[$i]["forever"]) ?></td>
					</tr>
					<?php } ?>					
				</tbody>
				<tr>
					<td class="center">
						<a class="btn  btn-minier btn-inverse" href="javascript:Delete('<?php echo bUrl('deleteGroupUser');?>');">
							<i class="icon-trash bigger-120"></i>刪除
						</a>
					</td>
				<td colspan="7"></td></tr>
			</table>
			<input type="hidden" name="group_sn" value="<?php echo $group_sn; ?>">
			</form>
		</div>
		
	</div>		
	
	
	
	<form action="<?php echo bUrl("updateGroupUser");?>" method="post">
	<div class="col-xs-12">		
		<div class="table-responsive">
		  <h2>挑選清單</h2>
	      <table id="form1" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
	        <thead>
	          <tr>
	            <th class="center">
					<label class="pos-rel">
						<input id="checkboxAll" type="checkbox" class="ace" />
						<span class="lbl"></span>
					</label>
				</th>	           
	            <th>帳號</th>
	            <th>姓名</th>
	            <th>職稱</th>	           
	            <th>行動電話</th>
	            <th>有效日期</th>
	          </tr>
	        </thead>	
	      </table>
	    </div>
	</div>	
	
	
	<div class="clearfix form-actions">
		<div class="col-md-9">
			
			<button class="btn btn-info" type="Submit">
				<i class="icon-ok bigger-110"></i>
				加入群組
			</button>
		</div>
	</div>
	
	<input type="hidden" name="group_sn" value="<?php echo $group_sn; ?>">
	</form>
	
	<div class="col-xs-12">
		<h2>預覽清單</h2>
		<div class="table-responsive">
		  <table id="form3" class="table table-striped table-bordered table-hover">
	        <tr>
	            <th>編號</th>
	            <th>帳號</th>
	            <th>姓名</th>
	            <th>職稱</th>
	            <th>行動電話</th>
	            <th>有效日期</th>
	          </tr>
	      </table>	
	    </div>
	</div>
</div>
</div>
</div>

<link href="<?php echo base_url('template/backend/css/dataTables/jquery.dataTables.css')?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('template/backend/js/dataTables/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript"> 
$(function(){	
	
    $('#form1').dataTable({      
      "ajax": {
        async: false, 
        url:"<?php echo bUrl("ajaxGetUserList");?>",
        type: "GET",
        data: function ( d ) {
             
        }
      }, // 接收吐回資料
      //"autoWidth": true, // width控制
      //"responsive": true, // RWD控制
      "deferRender": true, // 延遲渲染 true可加快加載速度
      //"stateSave": true, // 在頁面重新加載的時候恢復狀態（頁碼等內容）
      "processing": true, // 顯示處理中字樣, 大數據使用
      "language": {
          "sProcessing": "處理中...",
          "sLengthMenu": "每頁 _MENU_ 筆記錄",
          "sZeroRecords": "沒有找到記錄",
          //"sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
          //"sInfo": "第 _PAGE_ 頁 ( 總共 _TOTAL_ 筆， _PAGES_ 頁 )",
          "sInfo": "總共 _TOTAL_ 筆",
          "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
          "sInfoFiltered": "(從 _MAX_ 條記錄過濾)",
          "sInfoPostFix": "",
          "sSearch": "搜尋:",
          "sUrl": "",
          "sEmptyTable": "無任何資料",
          "sLoadingRecords": "載入中...",
          "sInfoThousands": ",",
          "oPaginate": {
              "sFirst": "首頁",
              "sPrevious": "上頁",
              "sNext": "下頁",
              "sLast": "末頁"
          },
          "oAria": {
              "sSortAscending": ": 以升序排列此列",
              "sSortDescending": ": 以降序排列此列"
          }
      },
      /*"scrollY": "440px",
      "scrollCollapse": true,*/
      "paging": true, // 分頁模組
      "info": true, // sInfo模組
      "columns": [
        {
            "data": "sn",
            "orderable": false,
            //"bSortable": false, // 不要有排序
            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                $(nTd).html('<label class="pos-rel"><input type="checkbox" class="ace" name="user_sn[]" value="' + sData + '" /><span class="lbl"></span></label>');
            }
        }, 
        { "data": "id" },
        { "data": "name" },
        { "data": "title" },        
        { "data": "phone" },
        { "data": "eff_date" }
      ],
      // 表格完成加載繪製完成後執行此方法
      initComplete: function () {
        // select個別搜尋
        this.api().columns().every( function () {
          var column = this;
          var select = $('<select class="form-control"><option value=""></option></select>')
            .appendTo( $(column.footer()).empty() )
            .on( 'change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search( val ? '^'+val+'$' : '', true, false )
                .draw();
            })

          column.data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' );
          });

        });
        $('#form1_wrapper .dataTables_scrollBody thead').hide();
        $('#form1_wrapper .dataTables_scrollHead thead th').click(function(event) {
        	$('#form1_wrapper .dataTables_scrollBody thead').hide();
        });;
        $('#form1_wrapper .dataTables_scrollBody tbody input[name="user_sn[]"]').each(function(){
        	$(this).parents('td').addClass('center').css('cursor','pointer');
        });

        initFormData();
      }
      
    });      

	
	
		/* DEL 全勾選 */
	    $("#checkDelAll").on("click", function () {
	        if ($(this).prop("checked")) {
	          $("input[name='del[]']").each(function(i){
	            $(this).prop('checked',true);	           
	          });
	        } else {
	          $("input[name='user_sn[]']").each(function(i){	           
	            $(this).prop('checked',false);	         
	          });   

	        }
	    });
	
	
	

});    

    function initFormData() {

    	/* 全勾選 */
	    $("#checkboxAll").on("click", function () {
	        if ($(this).prop("checked")) {
	          $("input[name='user_sn[]']").each(function(i){
	            var value = $(this).attr('value');
	            console.log('表單1第'+value+'筆被勾選');
	            $(this).prop('checked',true);  
	            if($('#form3 tr').hasClass('form1_'+value)) {
	              $('#form3 tr.form1_'+value).remove();
	            }
	            $(this).parents('tr').addClass('form1_'+value).clone().appendTo('#form3').find('td:first').replaceWith('<td>'+value+'</td>');
	          });  

	        } else {
	          $("input[name='user_sn[]']").each(function(i){
	            var value = $(this).attr('value');
	            console.log('表單1第'+value+'筆被取消');
	            $(this).prop('checked',false);
	            $('#form3').find('.form1_'+value).remove();
	          });   

	        }
	    }); 

	    // 個別勾選
	    $("input[name='user_sn[]']").each(function(i){
	      var value = $(this).attr('value'),
	          sArr = [];
	      $(this).click(function() {
	        
	        if($(this).prop("checked")){
	          console.log('表單1第'+value+'筆被勾選');
	          $(this).prop('checked',true); 
	          $(this).parents('tr').addClass('form1_'+value).clone().appendTo('#form3').find('td:first').replaceWith('<td>'+value+'</td>');

	        }
	        else {
	          console.log('表單1第'+value+'筆被取消');
	          $('#form3').find('.form1_'+value).remove();

	        }
	      });
	     
	    });

	    console.log('預覽清單有'+($("input[name='user_sn[]']").length)+'筆');
	}

	
	function launch(obj) 
	{		
	
	 $.ajax({ 
            type : "POST",
            data: {'user_sn' : obj.value  },
            url: "<?php echo bUrl("launchAdmin");?>",
            timeout: 3000 ,
            error: function( xhr ) 
            {
                //不處理
            },
            success : function(result) 
            {
            	if(result == 1)
            	{
            		$(obj).prop("checked", true);	
            	}
            	else
            	{
            		$(obj).prop("checked", false);
            	}
           		     
            }
        });	 
	}
</script>	

  