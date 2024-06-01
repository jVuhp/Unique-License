<?php
session_start();
require_once '../config.php';
$unique = $_SESSION['u_user'];
$dataUsers = $connx->prepare("SELECT * FROM `u_user` WHERE `id` = ?");
$dataUsers->bindParam(1, $unique['id']);
$dataUsers->execute();
$themeLoad = $dataUsers->fetch(PDO::FETCH_ASSOC);

if ($_SESSION['theme'] == 'dark') {
	unset($_SESSION['theme']);
	$_SESSION['theme'] = 'light';
	$updateTheme = $connx->prepare("UPDATE `u_user` SET `theme` = 'light' WHERE `u_user`.`id` = ?");
	$updateTheme->bindParam(1, $unique['id']);
	$updateTheme->execute();
} else {
	unset($_SESSION['theme']);
	$_SESSION['theme'] = 'dark';
	$updateTheme = $connx->prepare("UPDATE `u_user` SET `theme` = 'dark' WHERE `u_user`.`id` = ?");
	$updateTheme->bindParam(1, $unique['id']);
	$updateTheme->execute();
}

?>