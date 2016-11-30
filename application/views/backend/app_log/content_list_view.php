<style type="text/css">
	.spec {font-size:16px; color:#f00; font-weight:bold;}
	.normal {font-size:16px; color:#004080; font-weight:bold;}
	.red-font {color: #ff0080;}
	input[type="number"] {width: 80px; text-align:center; color: #369;}

</style>


<article class="well">              

    <div class="btn-group">        
	    <a class="btn  btn-sm btn-danger btn_margin" target="_blank" href="<?php echo bUrl("showPdfList",TRUE); ?>">
			<i class="icon-edit bigger-120"></i>PDF報表
		</a>      
    </div>                 
</article>

<div class="row">
	<div class="col-xs-12">	

		<form method="post" action="<?php echo bUrl('updateMailbox');?>">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table id="entry" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:150px">社區</th>		
							<th style="width:100px">住戶數</th>
							<th style="width:100px">app每日登入數量</th>							
							<th style="width:80px">app安裝數量</th>
							<th style="width:80px">app活耀用戶數量</th>		
							<th style="width:80px">社區後台24小時登入狀態</th>																				
						</tr>
					</thead>

					<tbody>
					<?php
					$i = 1;
					$hidden = '';

					foreach ($comm_list as $comm_info) 
					{
						echo 
						'
						<tr>								
							<td>'.$comm_info["name"].'</td>						
							<td>'.$comm_info["user_cnt"].'</td>		
							<td>'.$comm_info["app_daily_cnt"].'</td>										
							<td>'.$comm_info["app_cnt"].'</td>
							<td>'.$comm_info["app_active_cnt"].'</td>
							<td>'.$comm_info["is_24hr_logon"].'</td>
						</tr>
						';						
					}
					
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
	</div>
	</form>



<link href="<?php echo base_url('template/backend/css/dataTables/jquery.dataTables.css')?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('template/backend/js/dataTables/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript"> 
$(function(){
    $('#entry').dataTable({
		"language": {
            "url": "<?php echo base_url('template/backend/js/dataTables/lang/Chinese-traditional.json');?>"
        },
		"scrollY": "680px",
	    "scrollCollapse": true,
	    "paging": false, // 分頁模組
	    "info": true, // 分頁模組
		"ordering": false,
		// 表格完成加載繪製完成後執行此方法
		initComplete: function () {
/*
			$('.dataTables_scrollBody').css('margin-top','-18px');
			$('.dataTables_scrollBody thead tr').css('visibility','hidden');
			$('.dataTables_scrollHead thead tr th').click(function(event) {
				$('.dataTables_scrollBody').css('margin-top','-18px');
				$('.dataTables_scrollBody thead tr').css('visibility','hidden');
			});;
*/

		}
	});
});
</script>