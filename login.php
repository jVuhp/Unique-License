<?php
session_start();
require_once('config.php');
require_once('function.php');

if(isset($_SESSION['u_user']['logged'])) {
	echo '<script>location.href = "./";</script>';
}

if(!isset($_SESSION['u_user'])) {

	if (isset($_POST['login'])) {
		$discord_url = "https://discord.com/api/oauth2/authorize?client_id=" . $client_id . "&redirect_uri=" . $redirect_uri . "/login&response_type=code&scope=identify%20guilds";
		echo '<script> location.href = "' . $discord_url . '"; </script>';
	}
?>

    <link rel="stylesheet" href="https://proyectojp.com/static/light/css/mdb.min.css" />
<div class="container" align="center">
	<form method="POST">
	<div class="card text-center border border-primary shadow-0 " style="margin-top: 30vh; width: 28rem;">
	  <div class="card-body">
		<h5 class="card-title">Welcome to Unique License!</h5>
		<p class="card-text">
		  Login to your account
		</p>
		<button type="submit" name="login" class="btn btn-primary"><i class="fab fa-discord"></i>  Log-in with Discord</button>
	  </div>
	  <div class="card-footer">We do not obtain any private data from your account.</div>
	</div>
	</form>
</div>
<?php 

}
if(isset($_GET['code'])){
	$discord_code = $_GET['code'];


	$payload = [
		'code'=>$discord_code,
		'client_id'=> $client_id,
		'client_secret'=> $client_secret,
		'grant_type'=>'authorization_code',
		'redirect_uri'=> $redirect_uri . '/login',
		'scope'=>'identify%20guids',
	];


	$payload_string = http_build_query($payload);
	$discord_token_url = "https://discordapp.com/api/oauth2/token";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $discord_token_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);

	if(!$result){
		echo curl_error($ch);
	}

	$result = json_decode($result,true);
	$access_token = $result['access_token'];

	$discord_users_url = "https://discordapp.com/api/users/@me";
	$header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_URL, $discord_users_url);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);

	$result = json_decode($result, true);



	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	$result1 = $connx->prepare("SELECT * FROM `u_user` WHERE `udid` = ?");
	$result1->execute([$result['id']]);
	if ($result1->RowCount() > 0) {
	$v_user_info = $result1->fetch(PDO::FETCH_ASSOC);
		
		$oIps = explode('/', $v_user_info['ips']);
		
		$updateAccount = $connx->prepare("UPDATE `u_user` SET `ips`= ?, `name` = ?, `avatar` = ? WHERE udid = ?");
		$updateAccount->bindParam(1, $ip);
		$updateAccount->bindParam(2, $result['username']);
		$updateAccount->bindParam(3, $result['avatar']);
		$updateAccount->bindParam(4, $result['id']);
		$updateAccount->execute();
		
		
		$_SESSION['u_user'] = [
			'name'=>$result['username'],
			'udid'=>$result['id'],
			'tag'=>$result['discriminator'],
			'avatar'=>$result['avatar'],
			'premium_type'=>$result['premium_type'],
			'public_flags'=>$result['public_flags'],
			'banner'=>$result['banner'],
			'accent_color'=>$result['accent_color'],
			'id'=>$v_user_info['id'],
			'theme'=>$v_user_info['theme'],
			'logged'=>true
		];
		
		
		$_SESSION['theme'] = $v_user_info['theme'];
	} else {
		
		
		$createAccount = $connx->prepare("INSERT INTO `u_user`(`udid`, `name`, `avatar`, `rank`, `theme`, `ips`) VALUES (?, ?, ?, 'user', 'light', ?)");
		$createAccount->bindParam(1, $result['id']);
		$createAccount->bindParam(2, $result['username']);
		$createAccount->bindParam(3, $result['avatar']);
		$createAccount->bindParam(4, $ip);
		$createAccount->execute();
		
		$new_user_id = $connx->lastInsertId();
		$new_user_theme = 'light';
		
		$results = $connx->prepare("SELECT * FROM `u_user`");
		$results->execute();
		if ($results->RowCount() == 0) {
		$users = $results->fetch(PDO::FETCH_ASSOC);
			
			
			$insertPerms = $connx->prepare("INSERT INTO `u_user_permissions` (`id`, `udid`, `permission`, `since`) VALUES 
			(NULL, :udid, 'unique.license.add', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.admin.users', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.license.server', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.admin.server', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.license.delete', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.license.edit', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.users', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.users.delete', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.users.edit', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.product', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.dashboard', CURRENT_TIMESTAMP), 
			(NULL, :udid, 'unique.product.add', CURRENT_TIMESTAMP), (NULL, :udid, 'unique.license.admin', CURRENT_TIMESTAMP)
			");
			$insertPerms->bindParam(':udid', $result['id']);
			$insertPerms->execute();
			
		}
		
		$_SESSION['u_user'] = [
			'name'=>$result['username'],
			'udid'=>$result['id'],
			'tag'=>$result['discriminator'],
			'avatar'=>$result['avatar'],
			'premium_type'=>$result['premium_type'],
			'public_flags'=>$result['public_flags'],
			'banner'=>$result['banner'],
			'accent_color'=>$result['accent_color'],
			'id'=>$new_user_id,
			'theme'=>$new_user_theme,
			'logged'=>true
		];
		
		$_SESSION['theme'] = $new_user_theme;
	}
	echo '<script>location.href = "./";</script>';
}

?>