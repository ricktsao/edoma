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


<article class="well">
<?php echo '請點此下載 DB SQL 檔案'.$filename;?>
</article>


	<div class="clearfix form-actions">
		<div class="col-md-offset-3 col-md-9">
			<a class="btn" href="<?php echo bUrl("index",TRUE,array("sn")) ?>">
				<i class="icon-undo bigger-110"></i>
				返回
			</a>			
		</div>
	</div>
