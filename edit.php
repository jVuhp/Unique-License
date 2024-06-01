<?php
session_start();
$pageload = 'Editing License';
$pagedor = $_GET['q'];

require_once('config.php');
require_once('function.php');
require_once('header.php');

if (unique_perm('unique.license.edit')) { 

$licenseInfo = $connx->prepare("SELECT * FROM `u_license` WHERE `id` = ?");
$licenseInfo->bindParam(1, $pagedor);
$licenseInfo->execute();
if ($licenseInfo->RowCount() > 0) {
$licenses = $licenseInfo->fetch(PDO::FETCH_ASSOC);
?>
<div class="container unique">
<form method="post">
  <div class="modal-dialog modal-xl ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit License</h5>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="wrapper-staff">
				<div class="col">
					<label for="basic-url" class="form-label">License Key</label>
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1">Key</span>
						<input type="text" class="form-control" name="keyLicenses" id="licenseKeyValue1" value="<?php echo $licenses['license']; ?>" />
						<button class="btn btn-outline-primary generatekeys" id="generate1" onclick="randomLicense();" type="button">
							Random
						</button>
					</div>
					<label for="basic-url" class="form-label">Client (Discord ID)</label>
					<div class="input-group mb-3">
						<span class="input-group-text" >Client</span>
						<input type="text" class="form-control" name="discordID" id="client" value="<?php echo $licenses['udid'];  ?>" placeholder="623308343582130187"/>
					</div>
					<label for="basic-url" class="form-label">Max IPs</label>
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1">IPs</span>
						<input type="number" class="form-control" name="maxips" id="mxips" value="<?php echo $licenses['maxIps']; ?>"/>
					</div>
					<label for="basic-url" class="form-label">Reset IPs Limit</label>
					<div class="input-group mb-3"> 
						<select width="10%" id="opct1" name="limitresetact" id="limitreset<?php echo $licenses['id']; ?>" onclick="selectReset()">
							<option value="Unlimited" <?php if ($licenses['resetips'] == '-1') { echo "selected"; } ?>>Unlimited</option>
							<option value="Other" <?php if ($licenses['resetips'] != '-1') { echo "selected"; } ?>>Other</option>
						</select>
						<input type="number" class="form-control" name="limitreset"  id="deshabilitad1" 
						<?php if ($licenses['resetips'] == '-1') { echo 'disabled'; } ?>
						value="<?php if ($licenses['resetips'] != '-1') { echo $licenses['resetips']; } ?>" 
						placeholder="Limit reset IPS in the license" aria-label="Username" aria-describedby="basic-addon1"/>
					</div>
				</div>
				
				<div class="col">
					<label for="basic-url" class="form-label">Select Product</label>
					<div class="input-group mb-3">
						<?php
						$productList = $connx->prepare("SELECT * FROM `u_product`");
						$productList->execute();
						if ($productList->RowCount() > 0) {
						?>
						<select class="select-width-100" name="product"  id="product" >
						<?php
							while ($prList = $productList->fetch(PDO::FETCH_ASSOC)) {
						?>
							<option value="<?php echo $prList['name']; ?>"  <?php if ($licenses['product'] == $prList['name']) { echo "selected"; } ?>><?php echo $prList['name']; ?> | (<?php echo $prList['direction']; ?>)</option>
						<?php
							}
						?>
						</select>
						<?php
						} else {
						?>
						<input type="text" class="form-control" name="product" value="<?php echo $licenses['product']; ?>" placeholder="Write Product Name (plugin.yml > name)" aria-label="Username" aria-describedby="basic-addon1"/>
						<?php
						}
						?>
						
					</div>
					<label for="basic-url" class="form-label">License Status</label>
					<div class="input-group mb-3">
						<select class="select-width-100" name="status" id="status" >
							<option value="1" <?php if ($licenses['status'] == 1) { echo "selected"; } ?>>Active</option>
							<option value="0" <?php if ($licenses['status'] == 0) { echo "selected"; } ?>>Inactive</option>
						</select>
					</div>
					<label for="basic-url" class="form-label">Expire</label>
					<div class="input-group mb-3"> 
						<select width="10%" id="opciones1" name="expire" id="basic-addon1" onclick="expireSelect()">
							<option value="Never" <?php if ($licenses['expire'] == '-1') { echo "selected"; } ?>>Never</option>
							<option value="Years" <?php if ($licenses['expire'] != '-1') { echo "selected"; } ?>>Year</option>
							<option value="Months">Month</option>
							<option value="Days">Day</option>
							<option value="Minutes">Minute</option>
							<option value="Seconds">Second</option>
						</select>
						<input type="text" class="form-control" name="expiretime"  id="deshabilitado1" <?php if ($licenses['expire'] == '-1') { echo "disabled"; } ?> placeholder="Expire in" aria-label="Username" aria-describedby="basic-addon1"/>
					</div>
					<div class="form-check form-switch">
					  <input class="form-check-input" type="checkbox" name="boundpr" <?php if ($licenses['boundProduct'] == 1) { echo "checked"; } ?> onclick="boundChanger();" id="flexSwitchCheckDefaults" />
					  <label class="form-check-label" for="flexSwitchCheckDefaults">Use product in the license? [<b id="useSwitchs"><?php if ($licenses['boundProduct'] == 1) { echo "YES"; } else echo "NO"; ?></b>]</label>
					</div>
				</div>
			</div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="welback();">
          Back
        </button>
        <button type="submit" class="btn btn-primary" name="submit_license_edit">Save Change</button>
      </div>
    </div>
  </div>
</form>
</div>
<script>
//License Edit

function welback() {
	history.go(-1);
}

function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

 function boundChanger() {

		  if ($('#flexSwitchCheckDefaults').is(":checked")) {
			document.getElementById("useSwitchs").innerHTML = "YES";

		  } else {
			document.getElementById("useSwitchs").innerHTML = "NO";
		  }


 }

function expireSelect() {
	let opcion1 = document.getElementById("opciones1");
	let caja1   = document.getElementById("deshabilitado1");
			
	opcion1.addEventListener("change", () => {
		let elementoElegido = opcion1.options[opcion1.selectedIndex].value
		if (elementoElegido === "Never") {
			caja1.disabled = true
		} else {
			caja1.disabled = false
		} 
	})
}
function selectReset() {
	let opcion2 = document.getElementById("opct1");
	let caja2   = document.getElementById("deshabilitad1");
			
	opcion2.addEventListener("change", () => {
		let elementoElegido = opcion2.options[opcion2.selectedIndex].value
		if (elementoElegido === "Unlimited") {
			caja2.disabled = true
		} else {
			caja2.disabled = false
		} 
	})
}
function randomLicense() {
	$('#generate1').click(function() {
		var line1 = randomString(5, 'PICKCHARSFROMTHISSET0123456789');
		var line2 = randomString(5, 'PICKCHARSFROMTHISSET0123456789');
		var line3 = randomString(5, 'PICKCHARSFROMTHISSET0123456789');
		var line4 = randomString(5, 'PICKCHARSFROMTHISSET0123456789');
		
		document.getElementById("licenseKeyValue1").value = line1 + "-" + line2 + "-" + line3 + "-" + line4;
		
	});

}
</script>

<?php

if (isset($_POST['submit_license_edit'])) {
	
	if ($_POST['limitresetact'] == 'Unlimited') {
		$limitr = '-1';
	} else {
		$limitr = $_POST['limitreset'];
	}
		
	if ($_POST['boundpr'] == true) {
		$bound = 1;
	} else {
		$bound = 0;
	}
	
	echo edit_license(
	$pagedor, 
	$_POST['keyLicenses'], 
	$_POST['discordID'], 
	$_POST['maxips'], 
	$limitr, 
	$_POST['product'], 
	$_POST['status'], 
	$_POST['expire'], 
	$_POST['expiretime'], 
	$bound);
}


} else {
	
}
}
require_once('footer.php');
?>