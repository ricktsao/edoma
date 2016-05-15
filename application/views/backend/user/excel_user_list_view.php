<?php
$file = "社區住戶資料_".date("YmdHi").'.xls';
$file = iconv('utf-8', 'big5', $file );
header("Content-type:application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=".$file.";");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header("Pragma: no-cache");
header("Expires: 0");
?>
<HTML xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"></head>
<style type='text/css'>
	* {font-size:18px; font-family: Verdana;}
	table {border:1px solid #ccc;}
	tr {height: 45px}
	th, td {text-align: center; border:1px solid #ccc; padding: 10px}
</style>
<body>

	<div>
		<table border="0">
			<thead>
				<tr>
					<th>序號</th>
					<th><?php echo $building_part_01;?></th>
					<th><?php echo $building_part_02;?></th>
					<th><?php echo $building_part_03;?></th>
					<th style='text-align: center'>姓名</th>
					<th>性別</th>
					<th style='text-align: center'>磁卡</th>
					<th>電話</th>
					<th>手機</th>
					<th>所有權人</th>
					<th>緊急聯絡人</th>
					<th>管委</th>
					
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				foreach ( $list as $item) {
					$building_id = tryGetData('building_id', $item, NULL);
					if ( isNotNull($building_id) ) {
						$building_parts = building_id_to_text($building_id, true);
					}
					if ($i % 2 == 1) {
						$bg = '#f7f7f7';
					} else {
						$bg = '#fff';
					}
				?>
				<tr style="background-color: <?php echo $bg;?>">
					<td style='text-align: center'><?php echo $i +(($this->page-1) * 10);?></td>

					<td style='text-align: center'><?php echo $building_parts[0];?></td>
					<td style='text-align: center'><?php echo $building_parts[1];?></td>
					<td style='text-align: center'><?php echo $building_parts[2];?></td>
					<td><?php echo tryGetData('name', $item);?></td>
					<td style='text-align: center'>
					<?php echo tryGetData($item['gender'], config_item('gender_array'), '-'); ?>
					</td>
					<td>
					<?php echo '<span style="color:#069">'.tryGetData('id', $item).'</span>';?>
					</td>
					<td>
					<?php echo '<span style="color:#069">'.tryGetData('tel', $item).'</span>';?>
					</td>
					<td>
					<?php echo '<span style="color:#069">'.tryGetData('phone', $item).'</span>';?>
					</td>
					<td>
					<?php
					if (tryGetData("is_owner", $item) == 1) {
						echo '是';
					} else {
						echo '否';
					}
					?>
					</td>
					<td>
					<?php
					if (tryGetData("is_contact", $item) == 1) {
						echo '是';
					} else {
						echo '否';
					}
					?>
					</td>
					<td>
					<?php
					if (tryGetData("is_manager", $item) == 1) {
						echo tryGetData("manager_title", $item);
					} else {
						echo '否';
					}
					?>
					</td>
					
				</tr>
				<?php
					$i++;
				}
				?>
			</tbody>								
		</table>							
	</div>

<?php
echo '</body></html>';
?>