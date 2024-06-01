<?php
session_start();
require_once('../config.php');
require_once('../function.php');
require_once('../static/css/vuhp.php');


if ($_POST['apply'] == 'view' AND unique_perm('unique.users')) {

	$search = $_POST['search'];
	if ($_POST['search'] == '') { $searching = " "; } else $searching = "WHERE `name` LIKE ? OR `udid` LIKE ? ";
	if ($_POST['where'] == 1) { $wheres = "ORDER BY since DESC"; } else $wheres = "";
	if ($_POST['viewingTotal'] == 20) { $total = 20; } else if ($_POST['viewingTotal'] == 60) { $total = 60; } else if ($_POST['viewingTotal'] == 100) { $total = 100; } else if ($_POST['viewingTotal'] == 200) { $total = 200; } else if ($_POST['viewingTotal'] == 500) { $total = 500; } else $total = 99999;
	
	echo '<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
	<th scope="col"></th><th scope="col">DISCORD ID</th><th scope="col">USER</th><th scope="col">ROLE</th><th scope="col"></th></tr></thead><tbody class="t-tbody">';

	$usersList = $connx->prepare("SELECT * FROM `u_user` " . $searching . $wheres . " LIMIT ".$total);
	if (!empty($search)) $usersList->execute(['%' . $search . '%', '%' . $search . '%']);
	if (empty($search)) $usersList->execute();
	if ($usersList->RowCount() > 0) {
		while ($userList = $usersList->fetch(PDO::FETCH_ASSOC)) {
			$udid = str_replace($search, '<b class="text-danger">' . $search . '</b>', $userList['udid']);
			$name = str_replace($search, '<b class="text-danger">' . $search . '</b>', $userList['name']);
			
			if (!empty($userList['avatar'])) $avatar_user = 'https://cdn.discordapp.com/avatars/' . $userList['udid'] . '/' . $userList['avatar'] . is_animated($userList['avatar']);
			if (empty($userList['avatar'])) $avatar_user = 'https://vuhp.vanityproyect.fun/Unique/static/img/uniquelogo.png';

	?>
								<tr class="">
								  <td scope="col" width="5%"><img src="<?php echo $avatar_user; ?>" width="32" style="border-radius: 10px;"></td>
								  <td scope="col" width="20%"><span class="name"><?php  echo $udid; ?></span></td>
								  <td scope="col" width="20%"><span class="name"><?php  echo $name; ?></span></td>
								  <td scope="col" width="5%"><?php echo $userList['rank']; ?></td>
								  <td scope="col" width="10%" align="center">
									
									<div class="dropdown" align="right">
										<a class="dropdown-toggle  hidden-arrow p-0" href="#" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown">
										  <span class="btn btn-outline-info btn-sm"><i class="fa fa-sliders"></i></span>
										</a>
										
										<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar" >
										  <li><a class="dropdown-item" href="<?php echo $redirect_uri; ?>/users?q=<?php echo $userList['id']; ?>"><i class="text-warning fa fa-pen-to-square"></i> Edit</a></li>
										  <li><a class="dropdown-item" href="<?php echo $redirect_uri; ?>/" onclick="deleteUser(<?php echo $userList['id']; ?>);"><i class="text-danger fa fa-trash"></i> Delete</a></li>
										</ul>
									</div>
								  </td>
								</tr>
	<?php
		}
	} else echo '<tr><td colspan="7">No results found.</td></tr>';
	echo '</tbody></table>';

}

if ($_POST['apply'] == 'act') {
	
	if ($_POST['action'] == 1) {
		$delUser = $connx->prepare("DELETE FROM `u_user` WHERE `id` = ?");
		$delUser->bindParam(1, $_POST['uid']);
		$delUser->execute();
	}
	if ($_POST['action'] == 2) {
		
	}
	
	
}

if ($_POST['apply'] == 'perms') {
	
	if ($_POST['action'] == 1) {
		$delPermission = $connx->prepare("DELETE FROM `u_user_permissions` WHERE `u_user_permissions`.`id` = ?");
		$delPermission->bindParam(1, $_POST['uid']);
		$delPermission->execute();
	}
	if ($_POST['action'] == 2) {
		$insertPermission = $connx->prepare("INSERT INTO `u_user_permissions`(`udid`, `permission`) VALUES (?, ?)");
		$insertPermission->bindParam(1, $_POST['uid']);
		$insertPermission->bindParam(2, $_POST['permission']);
		$insertPermission->execute();
	}
	if ($_POST['action'] == 3) {
		$updateUser = $connx->prepare("UPDATE `u_user` SET `rank` = ? WHERE `u_user`.`id` = ?");
		$updateUser->bindParam(1, $_POST['rank']);
		$updateUser->bindParam(2, $_POST['uid']);
		$updateUser->execute();
	}
	
	
}
if ($_POST['apply'] == 'product') {

	if ($_POST['action'] == 1) {
		$setProduct = $connx->prepare("INSERT INTO `u_product`(`name`, `direction`, `priority`) VALUES (?, ?, ?)");
		$setProduct->bindParam(1, $_POST['name']);
		$setProduct->bindParam(2, $_POST['direction']);
		$setProduct->bindParam(3, $_POST['priority']);
		$setProduct->execute();
	}
	if ($_POST['action'] == 2) {
		$delProduct = $connx->prepare("DELETE FROM `u_product` WHERE `u_product`.`id` = ?");
		$delProduct->bindParam(1, $_POST['id']);
		$delProduct->execute();
	}
	
	
}
if ($_POST['apply'] == 'delete') {

	$delUser = $connx->prepare("DELETE FROM `u_user` WHERE `u_user`.`id` = ?");
	$delUser->bindParam(1, $_POST['user']);
	$delUser->execute();
	
}

if ($_POST['apply'] == 'viewperms') {
	$listPermissions = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ?");
	$listPermissions->bindParam(1, $_POST['uid']);
	$listPermissions->execute();
	while ($lPerm = $listPermissions->fetch(PDO::FETCH_ASSOC)) {
?>


<span class="badge bg-secondary active" onclick="removePermission('<?php echo $lPerm['id']; ?>');"><?php echo $lPerm['permission']; ?></span>


<?php
	}
	
}

?>