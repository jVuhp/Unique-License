<?php
require_once('../config.php');
require_once('../function.php');
require_once('../static/css/vuhp.php');


if ($_POST['apply'] == 'view' AND unique_perm('unique.product')) {

if ($_POST['search'] == '') { $searching = " "; } else $searching = "WHERE `name` = ? OR `direction` = ? ";
if ($_POST['where'] == 1) { $wheres = "ORDER BY since DESC"; } else $wheres = "";
if ($_POST['viewingTotal'] == 20) { $total = 20; } else if ($_POST['viewingTotal'] == 60) { $total = 60; } else if ($_POST['viewingTotal'] == 100) { $total = 100; }
else if ($_POST['viewingTotal'] == 200) { $total = 200; } else if ($_POST['viewingTotal'] == 500) { $total = 500; } else $total = 99999;
?>

<table class="leaderboard-table">
	<thead class="lb-thead">
		<tr>
			<th>Name</th>
			<th>Direction</th>
			<th>Since</th>
			<th></th>
		</tr>
	</thead>
	<tbody class="lb-tbody">
	<?php
	$productList = $connx->prepare("SELECT * FROM `u_product` " . $searching . $wheres . " LIMIT " . $total);
	if (!empty($_POST['search'])) $productList->execute([$_POST['search'], $_POST['search']]); else $productList->execute();
	if ($productList->RowCount() > 0) {
	while ($prList = $productList->fetch(PDO::FETCH_ASSOC)) {
	?>
		<tr>
			<td><?php echo $prList['name']; ?></td>
			<td><?php echo $prList['direction']; ?></td>
			<td><?php echo counttime($prList['since'], 'US', 'datetime'); ?></td>
			<td scope="col" align="right">
				<button class="badge bg-danger button-trans" onclick="actionToProductDel('<?php echo $prList['id']; ?>');"><i class="fa fa-xmark"></i> Delete</button>
			</td>
		</tr>
	<?php
	}
	} else echo '<tr><td colspan="5">No data in the table.</td></tr>';
	?>
	</tbody>

</table>



<?php

}

?>