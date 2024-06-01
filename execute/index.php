<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require_once('../static/css/vuhp.php');


if ($_POST['apply'] == 1) {
if ($_POST['viewing'] == 'all') {
	if ($_POST['search'] == '') { 
		$searching = " "; 
	} else $searching = "WHERE `license` = ? OR `udid` = ? ";
} else {
	if ($_POST['search'] == '') { 
		$searching = "WHERE `udid` = ? "; 
	} else $searching = "WHERE `udid` = ? AND `license` = ? ";
}
if ($_POST['where'] == 1) { $wheres = "ORDER BY since DESC"; } else $wheres = "";
if ($_POST['viewingTotal'] == 20) { $total = 20; } else if ($_POST['viewingTotal'] == 60) { $total = 60; } else if ($_POST['viewingTotal'] == 100) { $total = 100; }
else if ($_POST['viewingTotal'] == 200) { $total = 200; } else if ($_POST['viewingTotal'] == 500) { $total = 500; } else $total = 99999;
?>

<div class="accordion" id="accordionExample" style="margin-top: 10px;">
					<?php 
					$result = $connx->prepare("SELECT * FROM `u_license` " . $searching . $wheres . " LIMIT " . $total);
					if ($_POST['viewing'] == 'all') {
						if (!empty($_POST['search'])) $result->execute([$_POST['search'], $_POST['search']]); else $result->execute();
					} else {
						if ($_POST['search'] == '') { 
							$result->execute([$_SESSION['u_user']['udid']]);
						} else { 
							$result->execute([$_SESSION['u_user']['udid'], $_POST['search']]);
						}
					}
					if ($result->RowCount() > 0) {
					while($licenses = $result->fetch(PDO::FETCH_ASSOC)) {
						
					$noteInfo = $connx->prepare("SELECT * FROM `u_note` WHERE `lid` = ?");
					$noteInfo->bindParam(1, $licenses['id']);
					$noteInfo->execute();
					$notes = $noteInfo->fetch(PDO::FETCH_ASSOC);
					
					$userInfo = $connx->prepare("SELECT * FROM `u_user` WHERE `id` = ?");
					$userInfo->bindParam(1, $notes['uid']);
					$userInfo->execute();
					$usr = $userInfo->fetch(PDO::FETCH_ASSOC);
					
						

						if ($licenses['status'] == 1) {
							$license_status = '<b class="text-success">Active</b>';
							$status_name = '<b class="text-success">License Active</b>';
						} else {
							$license_status = '<b class="text-danger">Inactive</b>';
							$status_name = '<b class="text-danger"License Inactive</b>';
						}
						
						//$ip_location_api = json_decode(file_get_contents('http://ip-api.com/json/'. $licenses['ips']));
						//$ip_location_checked = $ip_location_api->country;
					?>
					  <div id="headingOne<?php echo $licenses['id']; ?>">
						<h2>
						  <button class="btn btn-link active btn-block text-start ps-3 collapsed" data-mdb-toggle="collapse" data-mdb-target="#collapseOne<?php echo $licenses['id']; ?>" aria-expanded="false" aria-controls="collapseTwo">
							<div class="row">
								<div class="col">
									<span class="badge badge-info text-<?php 
									
									if ($licenses['status'] == '1') { 
										if ($licenses['expire'] != '-1' AND $licenses['expire'] < time()) { 
											echo "danger"; 
										} else { 
											echo "success"; 
										}
									} else { 
										echo "danger"; 
									} 
									
									
									?>">
									<?php 
									
									if ($licenses['status'] == '1') { 
										if ($licenses['expire'] != '-1' AND $licenses['expire'] < time()) { 
											echo "Expired"; 
										} else { 
											echo "Active"; 
										}
									} else { 
										echo "Inactive"; 
									} 
									
									
									?>
									
									</span> 
								</div>
								<div class="col" align="center">
									<?php echo $licenses['license']; ?>
								</div>
								<div class="col" align="right">
									<?php 
									if ($_SESSION['u_user']['udid'] == $licenses['udid']) {
										echo '<span class="bg-confirm">You</span>';
									} else { 
										echo $licenses['udid'];
									} ?>
								</div>
							</div>
							
						  </button>
						</h2>
					  </div>

					<div id="collapseOne<?php echo $licenses['id']; ?>" class="collapse ps-3" aria-labelledby="headingOne<?php echo $licenses['id']; ?>" data-mdb-parent="#accordionExample">
						<div class="row">
							<div class="col-md">
								<b class="text-info">Status:</b> 
								
									<?php 
									
									if ($licenses['status'] == '1') { 
										if ($licenses['expire'] != '-1' AND $licenses['expire'] < time()) { 
											echo "Inactive (expired)"; 
										} else { 
											echo "Active"; 
										}
									} else { 
										echo "Inactive"; 
									} 
									
									
									?>
								
								<br />
								<b class="text-info">Customer:</b> <?php echo $licenses['udid']; ?>
								<br />
								<b class="text-info">Since:</b> <?php echo counttime($licenses['since'], 'US', 'datetime'); ?>
								<br />
								<b class="text-info">Expire:</b> <?php if ($licenses['expire'] != '-1') { echo counttimedown($licenses['expire'], 'expired', 'time', 'US'); } else { echo "Never"; } ?>
								<br />
								<b class="text-info">By:</b> <?php echo $licenses['by']; ?>
								<br />
							</div>
							<div class="col-md">
								<b class="text-info">License:</b> <?php echo $licenses['license']; ?>
								<br />
								<b class="text-info">IP's:</b> <?php $arrIPs = explode('#', $licenses['ips']); if ($licenses['ips'] == NULL) { echo "0"; } else echo count($arrIPs); ?>/<?php echo $licenses['maxIps']; ?>
								<br />
								<b class="text-info">Product:</b> <?php echo $licenses['product']; if ($licenses['boundProduct'] == 0) { echo " (Optional)"; } ?>
								<br />
								
									<?php 
									
									if ($licenses['status'] == '1') { 
										if ($licenses['expire'] != '-1' AND $licenses['expire'] < time()) { 
											?>
											
											<?php
										} else { 
											?>
											<a href="#" class="btn btn-danger btn-sm" onclick="statusLicense('<?php echo $licenses['license']; ?>'); indexLoad();">Stop</a>
											<?php
										}
									} else { 
										?>
										<a href="#" class="btn btn-success btn-sm" onclick="statusLicense('<?php echo $licenses['license']; ?>'); indexLoad();">Start</a>
										<?php
									} 
									
									
									?>

								<?php 
								if ($licenses['expire'] == '-1' OR $licenses['expire'] > time()) { 
									?>
									<button onclick="actionToUses('6', '<?php echo $licenses['license']; ?>');" <?php if ($licenses['resetips'] <= 0 AND $licenses['resetips'] != '-1') { echo "disabled"; } ?> 
									class="btn btn-warning btn-sm">Reset<?php if ($licenses['resetips'] != '-1') { echo " (x" . $licenses['resetips'] . ")"; } ?></button>
									<?php
									if (unique_perm('unique.license.server')) {
										echo ' <a href="./server?q=' . $licenses['license'] . '" class="btn btn-primary btn-sm">Server</a>'; 
									}
								}
									if (unique_perm('unique.license.edit')) {
									?>
										<a href="./edit?q=<?php echo $licenses['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
									<?php
									}
									if (unique_perm('unique.license.delete')) {
									?>
										<button class="btn btn-danger btn-sm" onclick="actionToUses('7', '<?php echo $licenses['license']; ?>');">Delete</button>
									<?php
									}
								?>
								<br />
								<br />
							</div>
							<div class="col-md-12">
								<?php
								if ($licenses['status'] == '0') { 
								?>
								<p class="note note-primary"><strong>Note:</strong> <?php if ($_SESSION['u_user']['udid'] == $licenses['udid']) { echo 'Your license is turned off.'; } else { echo 'The license is turned off.'; } ?></p>
								<?php
								}
								if ($licenses['expire'] != '-1' AND $licenses['expire'] < time()) { 
								?>
								<p class="note note-danger"><strong>Alert:</strong> <?php if ($_SESSION['u_user']['udid'] == $licenses['udid']) { echo 'Your license has expired and is in an inactive or expired state'; } else { echo 'The license has expired and is in an inactive or expired state'; } ?></p>
								<?php
								}
								if (unique_perm('unique.license.note') AND $noteInfo->RowCount() > 0) { 
									if ($notes['type'] == 2) {
								?>
								<p class="note note-warning">
								<strong>Note saved:</strong> <br>
								<strong><?php echo $notes['title']; ?></strong> <br>
								<?php echo $notes['descriptions']; ?>
								
								<?php
								if ($_SESSION['u_user']['id'] != $notes['uid']) { 
								?>
								<br>
								<b class="text-danger">By: <?php echo $usr['name']; ?></b>
								
								<?php
								}
								?>
								</p>
								<?php
									} else if ($notes['type'] == 1) {
										if ($_SESSION['u_user']['id'] == $notes['uid']) { 
								?>
								<p class="note note-warning">
								<strong>Note saved:</strong> <br>
								<strong><?php echo $notes['title']; ?></strong> <br>
								<?php echo $notes['descriptions']; ?>
								
								</p>
								<?php
										}
									}
								}
								?>
							</div>
						</div>
					</div>

					<?php } ?>
					<?php } else { echo "<p>Sorry, You not have Licenses.</p>"; } ?>
</div>

						
<?php
}
?>