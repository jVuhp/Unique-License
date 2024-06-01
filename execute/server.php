<?php
require_once('../config.php');
require_once('../function.php');


if ($_POST['apply'] == 'accept') {

?>


            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">IP</th>
                  <th scope="col">Status</th>
                  <th scope="col">Since</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
				<?php
			    
			    $permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'accept'");
				$permtype->bindParam(1, $_POST['key']);
				$permtype->execute();
				if ($permtype->RowCount() > 0) {
				while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
					if ($key['ip'] == '200.91.35.46') {
						$ipServer = '200.48.65.32';
					} else {
						$ipServer = $key['ip'];
					}
			    
				?>
                <tr>
                  <td><?php echo $ipServer; ?></td>
                  <td><?php echo $key['status']; ?></td>
                  <td><?php echo counttime($key['since'], 'US'); ?></td>
                  <td align="right">
					<button class="badge bg-danger button-trans" onclick="actionToUse('3', '<?php echo $key['ip']; ?>')"><i class="fa fa-xmark"></i> Add to Denied</button>
				  </td>
                </tr>
				<?php
				}
				} else {
					echo '<tr><td colspan="5">No data in the license.</td></tr>';
				}
				?>
              </tbody>
            </table>

<?php
}

if ($_POST['apply'] == 'process') {

?>


            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">IP</th>
                  <th scope="col">Status</th>
                  <th scope="col">Since</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
				<?php
			    
			    $permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'process'");
				$permtype->bindParam(1, $_POST['key']);
				$permtype->execute();
				if ($permtype->RowCount() > 0) {
				while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
					if ($key['ip'] == '200.91.35.46') {
						$ipServer = '200.48.65.32';
					} else {
						$ipServer = $key['ip'];
					}
			    
				?>
                <tr>
                  <td><?php echo $ipServer; ?></td>
                  <td><?php echo $key['status']; ?></td>
                  <td><?php echo counttime($key['since'], 'US'); ?></td>
                  <td align="right">
					<button class="badge bg-success button-trans" onclick="actionToUse('1', '<?php echo $key['ip']; ?>')"><i class="fa fa-check"></i> Accept</button>
					<button class="badge bg-danger button-trans" onclick="actionToUse('3', '<?php echo $key['ip']; ?>')"><i class="fa fa-xmark"></i> Denied</button>
				  </td>
                </tr>
				<?php
				}
				} else {
					echo '<tr><td colspan="5">No data in the license.</td></tr>';
				}
				?>
              </tbody>
            </table>

<?php
}

if ($_POST['apply'] == 'denied') {

?>


            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">IP</th>
                  <th scope="col">Status</th>
                  <th scope="col">Since</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
				<?php
			    
			    $permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'denied'");
				$permtype->bindParam(1, $_POST['key']);
				$permtype->execute();
				if ($permtype->RowCount() > 0) {
				while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
					if ($key['ip'] == '200.91.35.46') {
						$ipServer = '200.48.65.32';
					} else {
						$ipServer = $key['ip'];
					}
			    
				?>
                <tr>
                  <td><?php echo $ipServer; ?></td>
                  <td><?php echo $key['status']; ?></td>
                  <td><?php echo counttime($key['since'], 'US'); ?></td>
                  <td align="right">
					<button class="badge bg-success button-trans" onclick="actionToUse('1', '<?php echo $key['ip']; ?>')"><i class="fa fa-check"></i> Accept</button>
					<button class="badge bg-danger button-trans" onclick="actionToUse('4', '<?php echo $key['ip']; ?>')"><i class="fa fa-xmark"></i> Delete</button>
				  </td>
                </tr>
				<?php
				}
				} else {
					echo '<tr><td colspan="5">No data in the license.</td></tr>';
				}
				?>
              </tbody>
            </table>

<?php
}
?>