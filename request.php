<?php

  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  if(!isset($_GET["v1"]) OR !isset($_GET["v2"])) exit("URL_ERROR");
  
  require "config.php";
  
  $rand_EW_SKey = $_GET["v1"];
  $key_EW_rand = $_GET["v2"];
  
  if(isset($_GET["pl"]))$pluginName = $_GET["pl"];
  else $pluginName = "PRODUCT_NOT_FOUND";

  $sKey = toBinary(CKAP_KEY);

  $rand = _xor($rand_EW_SKey, $sKey);
  $key = _xor($key_EW_rand, $rand);

  $usrIP = getUserIP();

  $stingKey = fromBinary($key);
  
  $passed = 0;
  $lock = 1;
  
  $result = $connx->prepare("SELECT * FROM `u_license` WHERE `license` = ?");
  $result->bindParam(1, $stingKey);
  $result->execute();
  if ($result->RowCount() > 0) {
	$licenses = $result->fetch(PDO::FETCH_ASSOC);
	
	
	$product = $connx->prepare("SELECT * FROM `u_product` WHERE `direction` = ?");
	$product->bindParam(1, $pluginName);
	$product->execute();
	$prInfo = $product->fetch(PDO::FETCH_ASSOC);
	if ($product->RowCount() > 0) {
		$pluginInfo = $prInfo['name'];
	} else {
		$pluginInfo = $licenses['product'];
	}
	
	if(time() < $licenses['expire'] or $licenses['expire'] == -1){
		if($licenses['boundProduct'] == 0 OR $licenses['product'] == $pluginInfo){
			
			
			
			$currIPs = $licenses['ips'];
			$lastRef = $licenses['time'];
			$ips = $licenses['maxIps'];
			
			$arrIPs = array();
			$arrRef = array();
			
			if($currIPs){
				#echo "<br/> Found CurrIPs";
				$arrIPs = explode('#', $currIPs);
				$arrRef = explode('#', $lastRef);
				
				// DELETE IP/TIME
				for ($entryId = count($arrIPs)-1; $entryId >= 0; $entryId--) {
				  if($arrRef[$entryId] < (time()-900)) {
					#echo "<br/> Deleted outdated IP ".$entryId." - ".$arrIPs[$entryId];
					#unset($arrRef[$entryId]);
					#unset($arrIPs[$entryId]);
				  } else {
					#echo "<br/>Diff of IP ".$arrIPs[$entryId]." is ".((time()-900));
				  }
				}
				// UPDATE TIME
				for ($entryId=0; $entryId < count($arrIPs); $entryId++) {
				  if ($arrIPs[$entryId] == $usrIP) {
					#print_r($arrRef);
					#echo "<br/> Updated IP-Time";
					$arrRef[$entryId] = time();
					#print_r($arrRef);
					$passed = 1;
				  }
				}

				if (!$passed AND count($arrIPs) < $ips) {
				  #echo "<br/> Added user-ip";
				  array_unshift($arrIPs, $usrIP);
				  array_unshift($arrRef, time());
				  $passed = 1;
				}
			} else {
				#echo "<br/> Force added user-ip";
				array_unshift($arrIPs, $usrIP);
				array_unshift($arrRef, time());
				$passed = 1;
			}
			
			$server = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ?");
			$server->bindParam(1, $licenses['license']);
			$server->execute();
			
			$srvInfo = $server->fetch(PDO::FETCH_ASSOC);
			if ($licenses['use'] == 0) {
				if ($srvInfo['ip'] != $usrIP) {
						
					$insIp = $connx->prepare("INSERT INTO `u_server` (`license`, `ip`, `status`) VALUES (?, ?, 'process')");
					$insIp->bindParam(1, $licenses['license']);
					$insIp->bindParam(2, $usrIP);
					$insIp->execute();
						
					$passed = 0;
					$lock = 0;
				} else {
					if ($srvInfo['status'] != 'accept') { $passed = 0; $lock = 0; }
				}
			}
			

			if ($licenses['status'] == 0) $passed = 0;
			
			if ($lock == 1) {
				$updateIp = $connx->prepare("UPDATE `u_license` SET `ips` = ?, `time` = ? WHERE `license`= ?");
				$updateIp->bindParam(1, implode("#", $arrIPs));
				$updateIp->bindParam(2, implode("#", $arrRef));
				$updateIp->bindParam(3, $stingKey);
				$updateIp->execute();
			}
			
			
			if($passed) echo _xor($rand, _xor($key, $sKey));
			else echo "NOT_VALID_IP";
		} else echo "INVALID_PLUGIN";
	} else echo "KEY_OUTDATED";
  } else echo "KEY_NOT_FOUND";

?>







<?php


  function _xor($text,$key){
    for($i=0; $i<strlen($text); $i++){
        $text[$i] = intval($text[$i])^intval($key[$i]);
    }
    return $text;
  }

  function getUserIP(){
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP)){
          $ip = $client;
      }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
          $ip = $forward;
      }else{
          $ip = $remote;
      }
      return $ip;
  }

  function toBinary($value='none'){
    $str = "";
    $a = 0;
    while ($a < strlen($value)) {
      $str .= sprintf( "%08d", decbin(ord(substr($value, $a, 1))));
      $a++;
    }
    return $str;
  }

  function fromBinary($value='00100001'){
    $str = "";
    $a = 0;
    while ($a < strlen($value)) {
      $str .= chr(bindec(substr($value, $a, 8)));
      $a = $a+8;
    }
    return $str;
  }

?>
