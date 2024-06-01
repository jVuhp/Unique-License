<?php
session_start();
require_once '../config.php';
require_once '../function.php';

if ($_POST['apply'] == 1) {
	
	if ($_POST['action'] == 1) {
		$action = '1';
		$action_type = 'Inactive';
	} else {
		$action = '0';
		$action_type = 'Active';
	}
		$infoLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
		$infoLicense->bindParam(1, $_POST['key']);
		$infoLicense->execute();
		$typeLicense = $infoLicense->fetch(PDO::FETCH_ASSOC);
	
	$updateUseLicense = $connx->prepare("UPDATE `u_license` SET `use` = ? WHERE `u_license`.`license` = ?");
	$updateUseLicense->bindParam(1, $action);
	$updateUseLicense->bindParam(2, $_POST['key']);
	$updateUseLicense->execute();
	if (!empty($log_webhook)) {
			webhook($log_webhook, 'Unique - Log', 
				'They made a protection change to a license', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Changed at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Client', 
				'<@' . $typeLicense['udid'] . '>', 
				'License Key', 
				'``'. $typeLicense['license']  .'``', 
				'Status to', 
				'``' . $action_type . '``'
			);
	}
}

if ($_POST['apply'] == 2) {
	
	if ($_POST['action'] == 1) {
		$action = 'accept';
	} else if ($_POST['action'] == 2) {
		$action = 'process';
	} else if ($_POST['action'] == 3) {
		$action = 'denied';
	} else if ($_POST['action'] == 4) {
		
		$deleteStatusLicense = $connx->prepare("DELETE FROM `u_server` WHERE `u_server`.`license` = ? AND `ip` = ?");
		$deleteStatusLicense->bindParam(1, $_POST['key']);
		$deleteStatusLicense->bindParam(2, $_POST['ip']);
		$deleteStatusLicense->execute();
		
		return;
	} else if ($_POST['action'] == 5) {
		
		$addStatusLicense = $connx->prepare("INSERT INTO `u_server` (`id`, `license`, `ip`, `status`, `since`) VALUES (NULL, ?, ?, 'process', CURRENT_TIMESTAMP);");
		$addStatusLicense->bindParam(1, $_POST['key']);
		$addStatusLicense->bindParam(2, $_POST['ip']);
		$addStatusLicense->execute();
		
		return;
	} else if ($_POST['action'] == 6) {
		
		$infoLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
		$infoLicense->bindParam(1, $_POST['key']);
		$infoLicense->execute();
		$typeLicense = $infoLicense->fetch(PDO::FETCH_ASSOC);
		

		if ($typeLicense['ips'] == NULL) $discountReset = $typeLicense['resetips']; else $discountReset = $typeLicense['resetips'] - 1;
		if (unique_perm('unique.license.reset')) $discountReset = $typeLicense['resetips'];
		if (!empty($log_webhook)) {
			webhook($log_webhook, 'Unique - Log', 
				'The license reset its IPS', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Reseted at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Client', 
				'<@' . $typeLicense['udid'] . '>', 
				'License Key', 
				'``'. $typeLicense['license']  .'``', 
				'IPS', 
				'``' . count($typeLicense['ips']) . '/' . $typeLicense['maxIps'] . ' at 0/' . $typeLicense['maxIps'] . '``'
			);
		}
		$setIPLicense = $connx->prepare("UPDATE `u_license` SET `ips` = NULL, `time` = NULL, `resetips` = ? WHERE `u_license`.`license` = ?");
		$setIPLicense->bindParam(1, $discountReset);
		$setIPLicense->bindParam(2, $_POST['key']);
		$setIPLicense->execute();
		
		return;
	} else if ($_POST['action'] == 7) {
		
		if (unique_perm('unique.license.delete')) {
			$licenseInform = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
			$licenseInform->bindParam(1, $_POST['key']);
			$licenseInform->execute();
			$lic = $licenseInform->fetch(PDO::FETCH_ASSOC);
			
			$deleteLicense = $connx->prepare("DELETE FROM `u_license` WHERE `u_license`.`license` = ?");
			$deleteLicense->bindParam(1, $_POST['key']);
			$deleteLicense->execute();
			
			$deleteServers = $connx->prepare("DELETE FROM `u_server` WHERE `u_server`.`license` = ?");
			$deleteServers->bindParam(1, $_POST['key']);
			$deleteServers->execute();
			
			$deleteNotes = $connx->prepare("DELETE FROM `u_note` WHERE `u_note`.`lid` = ?");
			$deleteNotes->bindParam(1, $lic['id']);
			$deleteNotes->execute();
			if (!empty($log_webhook)) {
			webhook($log_webhook, 'Unique - Log', 
				'A license has been deleted by <@' . $_SESSION['u_user']['udid'] . '>', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Deleted at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Product', 
				'``' . $lic['product'] . '``', 
				'License Key', 
				'``'. $lic['license']  .'``', 
				'', 
				''
			);
			}
		}
		
		return;
	}
	
	$updateStatusLicense = $connx->prepare("UPDATE `u_server` SET `status` = ? WHERE `u_server`.`license` = ? AND `ip` = ?");
	$updateStatusLicense->bindParam(1, $action);
	$updateStatusLicense->bindParam(2, $_POST['key']);
	$updateStatusLicense->bindParam(3, $_POST['ip']);
	$updateStatusLicense->execute();
	
	
}


if ($_POST['apply'] == 3) {
	$licenseData = $connx->prepare("SELECT * FROM `u_license` WHERE `license` = ?");
	$licenseData->bindParam(1, $_POST['key']);
	$licenseData->execute();
	$licList = $licenseData->fetch(PDO::FETCH_ASSOC);

	if ($licList['status'] == 0) {
		$status = 1;
	} else {
		$status = 0;
	}

	if ($_POST['apply'] == 'status') {
		
		$licenseUpdate = $connx->prepare("UPDATE `u_license` SET `status` = ? WHERE `u_license`.`license` = ?");
		$licenseUpdate->bindParam(1, $status);
		$licenseUpdate->bindParam(2, $_POST['key']);
		$licenseUpdate->execute();
		
	}
}


if (isset($_POST['keyLicense'])) {
	
	$key = $_POST['keyLicense'];
	$client = $_POST['discordID'];
	$maxips = $_POST['maxips'];
	$product = $_POST['product'];
	$status = $_POST['status'];
	$expire = $_POST['expire'];
	$expiretime = $_POST['expiretime'];
	$boundpr = $_POST['boundpr'];
	$limitresetact = $_POST['limitresetact'];
	$limitreset = $_POST['limitreset'];
	$rank_give = $_POST['giverank'];
	$send = $_POST['sendmessage'];
	$note_type = $_POST['notetype'];
	$note_title = $_POST['notetitle'];
	$note_description = $_POST['notedescription'];
	$sendmd = $_POST['sendmdmessage'];
	
	$error = 0;
		
	if (!preg_match("#^[a-zA-Z0-9]+$#", $client)) {
		$error = 1;
		echo json_encode(array('success' => 1));
		return;

	}
	
	if (empty($key)) {
		$error = 1;
		echo json_encode(array('success' => 1));
		return;

	}
	
	$verifyLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `license` = ?");
	$verifyLicense->bindParam(1, $key);
	$verifyLicense->execute();
	
	$result = $verifyLicense->fetch(PDO::FETCH_ASSOC);

	$existLicense = $verifyLicense->rowCount();
	
	if ($existLicense == 0) {
		
		if ($expire == 'Never') {
			$exp = '-1';
			$exp1 = 'Never';
		} else {
			$exp = strtotime('+' . $expiretime . ' ' . $expire);
			$exp1 = date('Y/m/d h:i a', $exp);
		}
		if ($limitresetact == 'Unlimited') {
			$limitr = '-1';
		} else {
			$limitr = $limitreset;
		}
		
		
		if ($maxips <= 0) {
			$max = 1;
		} else {
			$max = $maxips;
		}
		
		if ($boundpr == true) {
			$bound = 1;
		} else {
			$bound = 0;
		}
		
		if ($status == '') {
			$status = 1;
		}
		
		if ($rank_give != 0) {
			give_role($guild_id, $bot_token, $rank_give, $client);
		} 
		if ($error == 0) {
			
			$addLicense = $connx->prepare("INSERT INTO `u_license`(`udid`, `license`, `product`, `boundProduct`, `expire`, `maxIps`, `status`, `resetips`, `by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$addLicense->bindParam(1, $client);
			$addLicense->bindParam(2, $key);
			$addLicense->bindParam(3, $product);
			$addLicense->bindParam(4, $bound);
			$addLicense->bindParam(5, $exp);
			$addLicense->bindParam(6, $max);
			$addLicense->bindParam(7, $status);
			$addLicense->bindParam(8, $limitr);
			$addLicense->bindParam(9, $_SESSION['u_user']['name']);
			$addLicense->execute();
			
			if ($note_type == 1 OR $note_type == 2) {
				$addNote = $connx->prepare("INSERT INTO `u_note`(`uid`, `lid`, `type`, `title`, `descriptions`) VALUES (?, ?, ?, ?, ?)");
				$addNote->bindParam(1, $_SESSION['u_user']['id']);
				$addNote->bindParam(2, $connx->lastInsertId());
				$addNote->bindParam(3, $note_type);
				$addNote->bindParam(4, $note_title);
				$addNote->bindParam(5, $note_description);
				$addNote->execute();
			}
			
			$unique_avatar = json_decode(file_get_contents('https://discordlookup.mesavirep.xyz/v1/user/'. $client));
			$avatar = $unique_avatar->avatar->id;
			
			$imagenplayer = 'https://cdn.discordapp.com/avatars/' . $client . '/' . $avatar . is_animated($avatar);
			if ($send == 0 AND !empty($log_webhook)) {
				webhook($log_webhook, 'Unique - License', 
				'A new license has been generated for <@' . $client . '>', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'For', 
				'<@'.$client.'>', 
				'Product', 
				'``' . $product . '``', 
				'Expire in', 
				'``'. $exp1  .'``', 
				'', 
				'');
			}
if ($sendmd == 0 AND !empty($bot_token)) {
send_messageDC($bot_token, $client, 
'You can now manage your new license in our Unique system | <@' . $client . '>

> **License: **``' . $key . '``

> **Product: **``' . $product . '``

> **Created at: **``' . date('Y/m/d h:i a') . '``

> **Expiration: **``' . $exp1 . '``

> **By: **``' . $_SESSION['u_user']['name'] . '``'

);
}
			
			
			
			
			echo json_encode(array('success' => 2));
			return;
			
		}
	} else {
		$error = 1;
		echo json_encode(array('success' => 1));
		return;
	}
	
}


if ($_POST['apply'] == 8) {
	
	if ($_POST['action'] == 1) {
		$action = '1';
		$action_type = 'Inactive';
	} else {
		$action = '0';
		$action_type = 'Active';
	}
		$infoLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
		$infoLicense->bindParam(1, $_POST['key']);
		$infoLicense->execute();
		$typeLicense = $infoLicense->fetch(PDO::FETCH_ASSOC);
	
	$updateUseLicense = $connx->prepare("UPDATE `u_license` SET `use` = ? WHERE `u_license`.`license` = ?");
	$updateUseLicense->bindParam(1, $action);
	$updateUseLicense->bindParam(2, $_POST['key']);
	$updateUseLicense->execute();
			webhook($log_webhook, 'Unique - Log', 
				'They made a protection change to a license', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Changed at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Client', 
				'<@' . $typeLicense['udid'] . '>', 
				'License Key', 
				'``'. $typeLicense['license']  .'``', 
				'Status to', 
				'``' . $action_type . '``'
			);
	
}

if ($_POST['apply'] == 4) {
	
	if ($_POST['action'] == 1) {
		$action = 'accept';
	} else if ($_POST['action'] == 2) {
		$action = 'process';
	} else if ($_POST['action'] == 3) {
		$action = 'denied';
	} else if ($_POST['action'] == 4) {
		
		$deleteStatusLicense = $connx->prepare("DELETE FROM `u_server` WHERE `u_server`.`license` = ? AND `ip` = ?");
		$deleteStatusLicense->bindParam(1, $_POST['key']);
		$deleteStatusLicense->bindParam(2, $_POST['ip']);
		$deleteStatusLicense->execute();
		
		return;
	} else if ($_POST['action'] == 5) {
		
		$addStatusLicense = $connx->prepare("INSERT INTO `u_server` (`id`, `license`, `ip`, `status`, `since`) VALUES (NULL, ?, ?, 'process', CURRENT_TIMESTAMP);");
		$addStatusLicense->bindParam(1, $_POST['key']);
		$addStatusLicense->bindParam(2, $_POST['ip']);
		$addStatusLicense->execute();
		
		return;
	} else if ($_POST['action'] == 6) {
		
		$infoLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
		$infoLicense->bindParam(1, $_POST['key']);
		$infoLicense->execute();
		$typeLicense = $infoLicense->fetch(PDO::FETCH_ASSOC);
		
		if (unique_perm('unique.license.reset')) {
			$discountReset = $typeLicense['resetips'];
			webhook($log_webhook, 'Unique - Log', 
				'The license reset its IPS', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Reseted at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Client', 
				'<@' . $typeLicense['udid'] . '>', 
				'License Key', 
				'``'. $typeLicense['license']  .'``', 
				'IPS', 
				'``' . count($typeLicense['ips']) . '/' . $typeLicense['maxIps'] . ' at 0/' . $typeLicense['maxIps'] . '``'
			);
		} else {
			if ($typeLicense['ips'] == NULL) {
				$discountReset = $typeLicense['resetips'];
			} else {
				$discountReset = $typeLicense['resetips'] - 1;
			webhook($log_webhook, 'Unique - Log', 
				'The license reset its IPS', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Reseted at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Client', 
				'<@' . $typeLicense['udid'] . '>', 
				'License Key', 
				'``'. $typeLicense['license']  .'``', 
				'IPS', 
				'``' . count($typeLicense['ips']) . '/' . $typeLicense['maxIps'] . ' at 0/' . $typeLicense['maxIps'] . '``'
			);
			}
		}
		
		$setIPLicense = $connx->prepare("UPDATE `u_license` SET `ips` = NULL, `time` = NULL, `resetips` = ? WHERE `u_license`.`license` = ?");
		$setIPLicense->bindParam(1, $discountReset);
		$setIPLicense->bindParam(2, $_POST['key']);
		$setIPLicense->execute();
		
		return;
	} else if ($_POST['action'] == 7) {
		
		if (unique_perm('unique.license.delete')) {
			$licenseInform = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
			$licenseInform->bindParam(1, $_POST['key']);
			$licenseInform->execute();
			$lic = $licenseInform->fetch(PDO::FETCH_ASSOC);
			
			$deleteLicense = $connx->prepare("DELETE FROM `u_license` WHERE `u_license`.`license` = ?");
			$deleteLicense->bindParam(1, $_POST['key']);
			$deleteLicense->execute();
			
			$deleteServers = $connx->prepare("DELETE FROM `u_server` WHERE `u_server`.`license` = ?");
			$deleteServers->bindParam(1, $_POST['key']);
			$deleteServers->execute();
			
			$deleteNotes = $connx->prepare("DELETE FROM `u_note` WHERE `u_note`.`lid` = ?");
			$deleteNotes->bindParam(1, $lic['id']);
			$deleteNotes->execute();
			webhook($log_webhook, 'Unique - Log', 
				'A license has been deleted by <@' . $_SESSION['u_user']['udid'] . '>', 
				'', 
				'Verify the license by entering the panel with your account', 
				$redirect_uri, 
				'', 
				'', 
				$imagenplayer, 
				'By', 
				'<@' . $_SESSION['u_user']['udid'] . '>', 
				'Deleted at', 
				'``'.date('Y-m-d h:i:s').'``', 
				'Product', 
				'``' . $lic['product'] . '``', 
				'License Key', 
				'``'. $lic['license']  .'``', 
				'', 
				''
			);
		}
		
		return;
	}
	
	$updateStatusLicense = $connx->prepare("UPDATE `u_server` SET `status` = ? WHERE `u_server`.`license` = ? AND `ip` = ?");
	$updateStatusLicense->bindParam(1, $action);
	$updateStatusLicense->bindParam(2, $_POST['key']);
	$updateStatusLicense->bindParam(3, $_POST['ip']);
	$updateStatusLicense->execute();
	
	
}

?>