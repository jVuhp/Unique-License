<?php 
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATA, DB_PORT);

require_once('execute/database.php');


function mysqli_result($res, $row, $field=0) { 
	$res->data_seek($row); $datarow = $res->fetch_array(); return $datarow[$field]; 
}
function unique_perm($perm) {
	require_once('config.php');
	$connx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
	
	$permtype = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = 'unique.*'");
	$permtype->bindParam(1, $_SESSION['u_user']['udid']);
	$permtype->execute();
	while ($perms = $permtype->fetch(PDO::FETCH_ASSOC)) return true;

	$permtype = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = ?");
	$permtype->bindParam(1, $_SESSION['u_user']['udid']);
	$permtype->bindParam(2, $perm);
	$permtype->execute();
	while ($perms = $permtype->fetch(PDO::FETCH_ASSOC)) {
		return true;

	}
	return false;
}
function unique_perm_other($id, $perm) {
	require_once('config.php');
	$connx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
	
	$permtype = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = ?");
	$permtype->bindParam(1, $id);
	$permtype->bindParam(2, $perm);
	$permtype->execute();
	while ($perms = $permtype->fetch(PDO::FETCH_ASSOC)) {
		return true;

	}
	return false;
}

function discordinfo() {
	$discordinformation = json_decode(file_get_contents('https://discordapp.com/api/users/623308343582130187'));
	$discord_info_name = $discordinformation->name;
	
	return $discord_info_name;
}

function ul_page($page, $name) {
	if ($page == $name) {
		return 'active';
	}
}


function edit_license($id, $key, $client, $maxips, $limitr, $product, $status, $expire, $expiretime, $bound) {
	require_once('config.php');
	$connx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
		if ($expire == 'Never') {
			$exp = '-1';
		} else {
			$exp = strtotime('+' . $expiretime . ' ' . $expire);
		}

		
		if ($maxips <= 0) {
			$max = 1;
		} else {
			$max = $maxips;
		}

		
		if ($status == '') {
			$status = 1;
		}
		
			
			$editLicense = $connx->prepare("UPDATE `u_license` SET `udid` = ?, `license` = ?, `product` = ?, `boundProduct` = ?, `expire` = ?, `maxIps` = ?, `status` = ?, `resetips` = ? WHERE `id` = ?");
			$editLicense->bindParam(1, $client);
			$editLicense->bindParam(2, $key);
			$editLicense->bindParam(3, $product);
			$editLicense->bindParam(4, $bound);
			$editLicense->bindParam(5, $exp);
			$editLicense->bindParam(6, $max);
			$editLicense->bindParam(7, $status);
			$editLicense->bindParam(8, $limitr);
			$editLicense->bindParam(9, $id);
			$editLicense->execute();
	return "<script> location.href ='./'; </script>";

}

function theme($in, $dark, $light) {
	if ($in == 'dark') { 
		$themes = $dark; 
	} else { 
		$themes = $light; 
	}
	
	return $themes;
}

function is_animated($image) {
	$ext = substr($image, 0, 2);
	if ($ext == "a_") {
		return ".gif";
	} else {
		return ".png";
	}
}


$url = 'https://vuhp.vanityproyect.fun/Unique/wrequest?v2=' . LICENSE_KEY . '&pl=UniqueSystem';

$response = file_get_contents($url);

if ($response === false) {
    echo 'Error.. Please contact the administrator of Vanity Proyect.';
} else {
    $data = json_decode($response, true);

    if ($data === null) {
        echo 'Error... Please contact the administrator of Vanity Proyect.';
    } else {
        $exito = $data['exito'];
        $mensaje = $data['mensaje'];
		if (!$exito) {
			echo $mensaje;
			exit();
		}
    }
}

function countsSQL($sql, $where = 'none', $load = '', $player = '') {
	$t = 0;
	require_once('config.php');
	$connx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);

	if ($where == 'none') {
		$countOfMySQL = $connx->prepare("SELECT COUNT(id) AS total FROM `" . $sql . "`");
	} else if ($player != '') {

		$countOfMySQL = $connx->prepare("SELECT COUNT(id) AS total FROM `" . $sql . "` WHERE `" . $where . "` = ? AND `" . $player . "` = ?");
		$countOfMySQL->bindParam(1, $load);
		$countOfMySQL->bindParam(2, $_SESSION['u_user']['id']);
	} else {
		$countOfMySQL = $connx->prepare("SELECT COUNT(id) AS total FROM `" . $sql . "` WHERE `" . $where . "` = ?");
		$countOfMySQL->bindParam(1, $load);
	}
	$countOfMySQL->execute();
	$count = $countOfMySQL->fetch(PDO::FETCH_ASSOC);
	$t = $count['total'];
	if ($t >= 0) {
		$total = $t;
	} 
	if ($t >= 1000) {
		$total = substr($t, 0, 1) . 'k';
	}
	if ($t >= 10000) {
		$total = substr($t, 0, 2) . 'k';
	}
	if ($t >= 100000) {
		$total = substr($t, 0, 3) . 'k';
	}
	if ($t >= 1000000) {
		$total = substr($t, 0, 1) . 'm ' . substr($t, 1, 3) . 'k';
	}
	return $total;

}
function countSQLs($sql, $where = '', $load = '', $where2 = '', $load2 = '', $type = 'AND') {
	$t = 0;
	require_once('config.php');
	$connx = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);

	$countOfMySQL = $connx->prepare("SELECT COUNT(id) AS total FROM `" . $sql . "` WHERE `" . $where . "` = ? AND `" . $player . "` = ?");
	$countOfMySQL->bindParam(1, $load);
	$countOfMySQL->bindParam(2, $load2);

	$countOfMySQL->execute();
	$count = $countOfMySQL->fetch(PDO::FETCH_ASSOC);
	$t = $count['total'];
	if ($t >= 0) {
		$total = $t;
	} 
	if ($t >= 1000) {
		$total = substr($t, 0, 1) . 'k';
	}
	if ($t >= 10000) {
		$total = substr($t, 0, 2) . 'k';
	}
	if ($t >= 100000) {
		$total = substr($t, 0, 3) . 'k';
	}
	if ($t >= 1000000) {
		$total = substr($t, 0, 1) . 'm ' . substr($t, 1, 3) . 'k';
	}
	return $total;

}

function counttime($date, $lang, $dates = 'datetime') {
	
	if ($dates == 'datetime') {
		$timestamp = strtotime($date);
	} else {
		$timestamp = $date;
	}
	if ($lang == 'ES') {
		$strTime=array("seg", "min", "hora", "dia", "mes", "año");
	} else if ($lang == 'US') {
		$strTime=array("sec", "min", "hour", "day", "month", "year");
	}
	$length=array("60","60","24","30","12","10");
	$currentTime=time();
	if($currentTime >= $timestamp) { 
		$diff = time()- $timestamp; 
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) { 
			$diff = $diff / $length[$i]; 
		} 
		$diff = round($diff); 
		if ($diff > 1) { 
			if ($lang == 'ES' AND $strTime[$i] == 'mes') {
				$timeName = $strTime[$i] . "es"; 
			} else {
				$timeName = $strTime[$i] . "s"; 
			}
		} else { 
			$timeName = $strTime[$i]; 
		} 
		
		if ($lang == 'ES') {
			return " hace ".$diff. " " .$timeName;
		} else if ($lang == 'US') {
			return $diff." ".$timeName. " ago";
		}
	}
}
function counttimedown($timing, $msg, $date = 'time', $lang) {
	
	if ($date == 'time') {
		$info = date('Y-m-d H:i:s', $timing);
	} else {
		$info = $timing;
	}
	
	$end_time = new DateTime($info);
	$current_time = new DateTime();
	$interval = $current_time->diff($end_time);
	
	if ($lang == 'ES') {
		$textand = 'y';
	} else if ($lang == 'US') {
		$textand = 'and';
	}
	
	if ($interval->format("%a") == '0') {
		$timers = $interval->format("%h h, %i m " . $textand . " %s s.");
	} else if ($interval->format("%h") == '0') {
		$timers = $interval->format("%i m " . $textand . " %s s.");
	} else if ($interval->format("%i") == '0') {
		$timers = $interval->format("%s s.");
	} else {
		$timers = $interval->format("%a d, %h h, %i m " . $textand . " %s s.");
	}
	
	if ($interval->invert) {
		echo $msg;
	} else {
		echo $timers;
	}
}
function webhook($whurl, $botname, $msg1, $title, $desc, $url, $color, $img, $imgthumb, $field_title, $field_desc, $field_title2, $field_desc2, $field_title3, $field_desc3, $field_title4, $field_desc4, $field_title5, $field_desc5) {
	//=======================================================================================================
// Create new webhook in your Discord channel settings and copy&paste URL
//=======================================================================================================

$webhookurl = $whurl;

//=======================================================================================================
// Compose message. You can use Markdown
// Message Formatting -- https://discordapp.com/developers/docs/reference#message-formatting
//========================================================================================================

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([
    // Message
    "content" => $msg1,
    
    // Username
    "username" => $botname,

    // Avatar URL.
    // Uncoment to replace image set in webhook
    "avatar_url" => "https://vuhp.vanityproyect.com/Unique/static/img/uniquelogo.png",

    // Text-to-speech
    "tts" => false,

    // File upload
    // "file" => "",

    // Embeds Array
    "embeds" => [
        [
            // Embed Title
            "title" => $title,

            // Embed Type
            "type" => "rich",

            // Embed Description
            "description" => $desc,

            // URL of title link
            "url" => $url,

            // Timestamp of embed must be formatted as ISO8601
            "timestamp" => $timestamp,

            // Embed left border color in HEX
            "color" => hexdec( "DC4C64" ),

            // Footer
            "footer" => [
                "text" => "Unique System",
                "icon_url" => "https://proyectojp.com/static/img/Versal-logo-make.png"
            ],

            // Image to send
            "image" => [
                "url" => $img
            ],

            // Thumbnail
            "thumbnail" => [
                "url" => $imgthumb
            ],


            // Additional Fields array
            "fields" => [
                // Field 1
                [
                    "name" => $field_title,
                    "value" => $field_desc,
                    "inline" => true
                ],
                // Field 2
                [
                    "name" => $field_title2,
                    "value" => $field_desc2,
                    "inline" => true
                ],
                // Field 3
                [
                    "name" => $field_title3,
                    "value" => $field_desc3,
                    "inline" => true
                ],
                // Field 4
                [
                    "name" => $field_title4,
                    "value" => $field_desc4,
                    "inline" => false
                ],
                // Field 5
                [
                    "name" => $field_title5,
                    "value" => $field_desc5,
                    "inline" => false
                ]
                // Etc..
            ]
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


$ch = curl_init( $webhookurl );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
// echo $response;
curl_close( $ch );
}


function give_role($serverid, $bottoken, $roleid, $userid) {
	
	$discordlink = "https://discord.com";

	$datas = json_encode(array("roles" => array($roleid)));
	$urle = $discordlink . "/api/guilds/" . $serverid . "/members/" . $userid . "/roles/" . $roleid;
	$headeres = array('Content-Type: application/json', 'Authorization: Bot ' . $bottoken);
	$curls = curl_init();
	curl_setopt($curls, CURLOPT_URL, $urle);
	curl_setopt($curls, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curls, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($curls, CURLOPT_HTTPHEADER, $headeres);
	curl_setopt($curls, CURLOPT_POSTFIELDS, $datas);
	$responses = curl_exec($curls);
	curl_close($curls);
	$result = json_decode($responses, true);

	return $result;
}

function icon_config($icon, $status) {
	if (!$status) return;
	return '<i class="fa fa-' . $icon . '"></i>';
}

function licenseGenerator($length = 50, $char = false, $large = 1) {
	if ($char) {
		$characters = 'ASDFGHJKLZXCVBNMQWERTYUIOP1234567890';
	} else {
		$characters = 'asdfghjklzxcvbnmqwertyuiop1234567890';
	}
    $charactersLength = strlen($characters);
    $randomString = '';
    $randomString1 = '';
    $randomString2 = '';
    $randomString3 = '';
    $randomString4 = '';
    $randomString5 = '';
    $randomString6 = '';
    $finalString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        $randomString1 .= $characters[rand(0, $charactersLength - 1)];
        $randomString2 .= $characters[rand(0, $charactersLength - 1)];
        $randomString3 .= $characters[rand(0, $charactersLength - 1)];
        $randomString4 .= $characters[rand(0, $charactersLength - 1)];
        $randomString5 .= $characters[rand(0, $charactersLength - 1)];
        $randomString6 .= $characters[rand(0, $charactersLength - 1)];
    }
	if ($large == 6) {
        $finalString = $randomString . '-' . $randomString1 . '-' . $randomString2 . '-' . $randomString3 . '-' . $randomString4 . '-' . $randomString5 . '-' . $randomString6;
    } else if ($large == 5) {
        $finalString = $randomString . '-' . $randomString1 . '-' . $randomString2 . '-' . $randomString3 . '-' . $randomString4 . '-' . $randomString5;
    } else if ($large == 4) {
        $finalString = $randomString . '-' . $randomString1 . '-' . $randomString2 . '-' . $randomString3 . '-' . $randomString4;
    } else if ($large == 3) {
        $finalString = $randomString . '-' . $randomString1 . '-' . $randomString2 . '-' . $randomString3;
    } else if ($large == 2) {
        $finalString = $randomString . '-' . $randomString1 . '-' . $randomString2;
    } else if ($large == 1) {
        $finalString = $randomString . '-' . $randomString1;
    } else {
		$finalString = $randomString . '-' . $randomString1 . '-' . $randomString2 . '-' . $randomString3;
	}
	
    return $finalString;
}

function send_messageDC($bottoken, $client, $message) {
	$token = $bottoken;  // Reemplaza con el token de tu bot
	$recipient_id = $client;  // Reemplaza con el ID del usuario al que deseas enviar el mensaje

	// Mensaje que deseas enviar

	// URL de la API de Discord para enviar mensajes privados
	$url = "https://discord.com/api/v10/users/@me/channels";

	// Cabeceras HTTP necesarias
	$headers = [
		"Authorization: Bot $token",
		"Content-Type: application/json",
	];

	// Datos del cuerpo de la solicitud
	$data = json_encode([
		'recipient_id' => $recipient_id,
	]);

	// Inicializa cURL y configura la solicitud
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Ejecuta la solicitud para obtener el canal privado
	$response = curl_exec($ch);

	// Verifica si la solicitud fue exitosa
	if ($response === false) {
		die('Error al enviar la solicitud cURL: ' . curl_error($ch));
	}

	// Decodifica la respuesta JSON para obtener el ID del canal privado
	$channel_data = json_decode($response, true);
	$channel_id = $channel_data['id'];

	// URL para enviar el mensaje al canal privado
	$message_url = "https://discord.com/api/v10/channels/$channel_id/messages";

	// Datos del cuerpo de la solicitud para enviar el mensaje
	$message_data = json_encode([
		'content' => $message,
    'embed' => [
        'title' => 'Título del Embed',
        'description' => 'Este es un mensaje con un embed.',
        'color' => hexdec('00FF00'), // Color del embed en formato decimal
    ],
    
	]);

	// Configura una nueva solicitud para enviar el mensaje
	curl_setopt($ch, CURLOPT_URL, $message_url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $message_data);

	// Ejecuta la solicitud para enviar el mensaje
	$message_response = curl_exec($ch);

	// Verifica si el mensaje se envió con éxito
	if ($message_response === false) {
		die('Error al enviar el mensaje: ' . curl_error($ch));
	}

	// Cierra la conexión cURL
	curl_close($ch);
}
?>