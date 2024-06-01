<?php
session_start();
$action = $_GET['hwid'];

$pageload = 'License';

require_once('config.php');
require_once('function.php');

if ($action) {
	$pagereturn = true;
}

if (!unique_perm('unique.license.admin')) {
	echo "<script> history.back(-1);</script>";
}

require_once('header.php');

if (!$action) {
?>

<div class="container unique" style="margin-top: 30px;margin-bottom: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>  border-top-unique">

        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">Unique - Manage Licenses</h3>
                        <div class="markdown text-muted">
                          Manage all existing licenses in Unique System in real time.
                        </div>
						
                      </div>
                    </div>

              </div>
              <div class="col-12">
<?php			  
	$countLicense = $connection->query("SELECT COUNT(id) AS showing FROM `u_license`");
	$allLicenses = mysqli_result($countLicense, 0, 'showing');
	$myCountLicense = $connection->query("SELECT COUNT(id) AS showing FROM `u_license` WHERE `udid` = '" . $unique['udid'] . "'");
	$myLicenses = mysqli_result($myCountLicense, 0, 'showing');
?>

					<div class="row wrapper-staff" align="left">

						<div class="col" align="left">
							<?php if (unique_perm('unique.license.add')) { ?>
							<a href="./license/new" class="text-success btn-transparent" ><i class="fa fa-plus"></i> License</a>  |  
							<?php } ?>
							You are looking at 
							<select id="totalInList" class="select-option">
								<option value="20" selected>20</option>
								<option value="60">60</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="500">500</option>
								<option value="99999">All</option>
							</select> licenses out of <b class="text-info"><?php echo $allLicenses; ?></b> of the most 
							<select id="whereList" class="select-option">
								<option value="1" selected>New</option>
								<option value="">Old</option>
							</select> 
						</div>
						<div class="col" align="right">
							<input type="text" placeholder="Search 'License or Client'" class="form-control" id="searcher" style="max-width: 50%;">
						</div>
						
							<div id="load_index_result">
								<div style="min-height: 80vh; margin-top: 250px;" align="center">
									<span class="spinner spinner-large spinner-blue spinner-slow"></span>
								</div>
							</div>
				
					</div>
				</div>
			  </div>
            </div>
          </div>
        </div>
	</div>
<script>
var e = document.getElementById('totalInList');
var w = document.getElementById('whereList');
var s = document.getElementById('searcher');
function indexLoad() {
	var langs = '1';
	var viewingTotals = e.options[e.selectedIndex].value;
	var whereLists = w.options[w.selectedIndex].value;
	var searchs = s.value;
	var viewings = 'all';
    $.post( 'execute/index.php', { apply : langs, viewingTotal : viewingTotals, where : whereLists, search : searchs, viewing : viewings }, 
       function( response ) {
		   
		document.getElementById("load_index_result").innerHTML = response;

       }
    );
}
function statusLicense(license) {
	var langs = '3';
    $.post( 'execute/license.php', { apply : langs, key : license }, 
       function( response ) {
		response;
		document.onload = indexLoad();
		successAlert();
       }
    );
}


e.onchange = indexLoad;
w.onchange = indexLoad;
s.onchange = indexLoad;
s.oninput = indexLoad;
document.onload = indexLoad();

function actionToUses(type, keylicense) {
	var langs = '2';
    $.post( 'execute/license.php', { apply : langs, action : type, key : keylicense }, 
       function( response ) {
		   
		response;
		indexLoad();
       }
    );
}

 function boundChanges() {

		  if ($('#flexSwitchCheckDefaults').is(":checked")) {
			document.getElementById("useSwitchs").innerHTML = "YES";

		  } else {
			document.getElementById("useSwitchs").innerHTML = "NO";
		  }


 }


let opcion = document.getElementById("opciones");
let caja   = document.getElementById("deshabilitado");
        
opcion.addEventListener("change", () => {
    let elementoElegido = opcion.options[opcion.selectedIndex].value
    if (elementoElegido === "Never") {
        caja.disabled = true
    } else {
        caja.disabled = false
    } 
})

let opcionst = document.getElementById("opct");
let cajas   = document.getElementById("deshabilitad");
        
opcionst.addEventListener("change", () => {
    let elementoElegido = opcionst.options[opcionst.selectedIndex].value
    if (elementoElegido === "Unlimited") {
        cajas.disabled = true
    } else {
        cajas.disabled = false
    } 
})

</script>
<?php

} else {
	
	if ($action == 'new') {
?>


<div class="container" style="margin-top: 30px;margin-bottom: 30px;">
	<form id="createLicense" method="POST">
        <div class="col-12">
				
						<label for="licenseKeyValues" class="form-label">License Key <button class="btn btn-outline-primary btn-sm generatekey" id="generate" type="button"><i class="fa fa-arrows-rotate"></i></button></label>
						<div class="input-group mb-3">
							<input type="text" class="form-control-unique" name="keyLicense" id="licenseKeyValues" value="<?php echo licenseGenerator($length_key, $type_letter, $large_key); ?>" aria-label="Username" aria-describedby="basic-addon1"/>
						</div>
						
						<div class="accordion" id="accordionExample">
						  <div id="headingOne">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Client</h4>
								<p class="subtitle-unique text-muted">You can place the discord id of the buyer/winner of the license that you are creating.</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<div class="input-group mb-3">
								<input type="text" class="form-control-unique" name="discordID" placeholder="Discord ID or in Blank" aria-label="Username" aria-describedby="basic-addon1"/>
							</div>
						  </div>
						  
						  
						  
						  <div id="headingOne1">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Product</h4>
								<p class="subtitle-unique text-muted">You can limit this license to only work for a specific product. </p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne1" class="collapse" aria-labelledby="headingOne1" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<div class="input-group mb-3">
								<?php
								$productList = $connx->prepare("SELECT * FROM `u_product`");
								$productList->execute();
								if ($productList->RowCount() > 0) {
								?>
								<select class="select-width-100" name="product"  id="basic-addon1">
								<?php
									while ($prList = $productList->fetch(PDO::FETCH_ASSOC)) {
								?>
									<option value="<?php echo $prList['name']; ?>"><?php echo $prList['name']; ?> | (<?php echo $prList['direction']; ?>)</option>
								<?php
									}
								?>
								</select>
								<?php
								} else {
								?>
								<input type="text" class="form-control" name="product"  placeholder="Write Product Name (plugin.yml > name)" aria-label="Username" aria-describedby="basic-addon1"/>
								<?php
								}
								?>
						    </div>
						  </div>
						  
						  
						  <div id="headingOne2">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne2" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">IP/Reset Limit</h4>
								<p class="subtitle-unique text-muted">You can limit the number of IP addresses that can be used to verify this license, And the number of times you can restore.</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne2" class="collapse" aria-labelledby="headingOne2" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<label for="maxipscount" class="form-label">IP Limit</label>
							<div class="input-group mb-3">
								<input type="number" class="form-control-unique" name="maxips" id="maxipscount" value="5" aria-label="Username" aria-describedby="basic-addon1"/>
							</div>
							<label for="opct" class="form-label">Reset Limit</label>
							<div class="input-group mb-3">
								<select width="10%" id="opct" name="limitresetact" id="basic-addon1">
									<option value="Unlimited">Unlimited</option>
									<option value="Other" selected>Other</option>
								</select>
								<input type="number" class="form-control" name="limitreset"  id="deshabilitad" value="5" placeholder="Limit reset IPS in the license" aria-label="Username" aria-describedby="basic-addon1"/>
							</div>
						  </div>
						  
						  <div id="headingOne3">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne3" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Expiration</h4>
								<p class="subtitle-unique text-muted">You can set an expiration date for this license. After this date, the license will no longer be valid</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne3" class="collapse" aria-labelledby="headingOne3" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<div class="input-group mb-3">
								<select width="10%" id="opciones" name="expire" id="basic-addon1">
									<option value="Never" selected>Never</option>
									<option value="Years">Year</option>
									<option value="Months">Month</option>
									<option value="Days">Day</option>
									<option value="Minutes">Minute</option>
									<option value="Seconds">Second</option>
								</select>
								<input type="number" class="form-control" name="expiretime"  id="deshabilitado" disabled placeholder="Expire in" aria-label="Username" aria-describedby="basic-addon1"/>
							</div>
						  </div>
						  
						<?php
						if ($bot_token != '' AND unique_perm('unique.discord.action')) {
						?>
						  <div id="headingOne4">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne4" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Discord Action</h4>
								<p class="subtitle-unique text-muted">You can set role to client with this method</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne4" class="collapse" aria-labelledby="headingOne4" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<label for="basic-addon1" class="form-label">Give Role</label>
							<div class="input-group mb-3">
								<select class="select-width-100" name="giverank" id="basic-addon1">
									<option value="0">No give role</option>
									<option value="<?php echo $customer_id; ?>">Customer</option>
								</select>
							</div>
							<label for="basic-addon2" class="form-label">Webhook Message</label>
							<div class="input-group mb-3">
								<select class="select-width-100" name="sendmessage" id="basic-addon2">
									<option value="0">Send</option>
									<option value="1">No send</option>
								</select>
							</div>
							<label for="basic-addon2" class="form-label">To Client Private Message</label>
							<div class="input-group mb-3">
								<select class="select-width-100" name="sendmdmessage" id="basic-addon2">
									<option value="0">Send</option>
									<option value="1">No send</option>
								</select>
							</div>
						  </div>
						  
						<?php
						}
						?>
						  

						<?php
						if (unique_perm('unique.license.note')) {
						?>
						  <div id="headingOne5">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne5" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Note</h4>
								<p class="subtitle-unique text-muted">These notes are only visible to you. You can use them to keep track of important information about this license.</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne5" class="collapse" aria-labelledby="headingOne5" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
						  
							<label for="basic-addon1" class="form-label">Note Type</label>
							<div class="input-group mb-3">
								<select class="select-width-100" name="notetype" id="basic-addon1">
									<option value="0">Disable note</option>
									<option value="1">For me</option>
									<option value="2">For Staff</option>
								</select>
							</div>
							<label for="basic-addon2" class="form-label">Note Title</label>
							<div class="input-group mb-3">
								<input type="text" class="form-control-unique" name="notetitle"  id="deshabilitad" value="5" placeholder="Limit reset IPS in the license" aria-label="Username" aria-describedby="basic-addon1"/>
							</div>
							<label for="basic-addon2" class="form-label">Note Description</label>
							<div class="input-group mb-3">
								<textarea class="form-control" name="notedescription" placeholder="Write your note here.." style="height: 150px; width: 100%;"></textarea>
							</div>
							
						  </div>
						  
						<?php
						}
						?>

						  <div id="headingOne6">
							<h2>
							  <button class="btn-transparent text-start" type="button" data-mdb-toggle="collapse" data-mdb-target="#collapseOne6" aria-expanded="false" aria-controls="collapseOne">
								<h4 class="title-unique">Others</h4>
								<p class="subtitle-unique text-muted">You can ignore this category. Although they can make significant changes with some methods of the category.</p>
							  </button>
							</h2>
						  </div>

						  <div id="collapseOne6" class="collapse" aria-labelledby="headingOne6" data-mdb-parent="#accordionExample" style="margin-top: 5px; margin-bottom: 5px;">
							<div class="row">
								<div class="form-check form-switch">
								  <input class="form-check-input" type="checkbox" name="boundpr" onclick="boundChanges();" id="flexSwitchCheckDefaults" />
								  <label class="form-check-label" for="flexSwitchCheckDefaults">The product in the license is mandatory? [<b id="useSwitchs">NO</b>]</label>
								</div>
							</div>
						  </div>
						  

						  
						  
						</div>

					
		</div>
		<div class="col-12" style="margin-bottom: 10px;">
			<div class="row">
				<div class="col" align="left">
					<button type="button" class="btn btn-danger btn-sm" onclick="history.back(-1);"><i class="fa fa-xmark"></i> Cancel</button>
				</div>
				<div class="col" align="right">
					<button type="submit" name="submit_form" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Create</button>
				</div>
				
			</div>
		</div>
	</form>
</div>


<?php
	} else if ($action == 'edit') {
		echo "edit";
	}
?>

<script>
$(document).ready(function() {
    $('#createLicense').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '../execute/license.php',
            data: $(this).serialize(),
            success: function(response)
            {
				successAlert();
				setTimeout("location.href = '../license';", 1500);
                
           }
       });
     });
});
 
function actionToUses(type, keylicense) {
	var langs = '2';
    $.post( '../execute/license.php', { apply : langs, action : type, key : keylicense }, 
       function( response ) {
		   
		response;
		indexLoad();
       }
    );
}

 function boundChanges() {

		  if ($('#flexSwitchCheckDefaults').is(":checked")) {
			document.getElementById("useSwitchs").innerHTML = "YES";

		  } else {
			document.getElementById("useSwitchs").innerHTML = "NO";
		  }


 }


let opcion = document.getElementById("opciones");
let caja   = document.getElementById("deshabilitado");
        
opcion.addEventListener("change", () => {
    let elementoElegido = opcion.options[opcion.selectedIndex].value
    if (elementoElegido === "Never") {
        caja.disabled = true
    } else {
        caja.disabled = false
    } 
})

let opcionst = document.getElementById("opct");
let cajas   = document.getElementById("deshabilitad");
        
opcionst.addEventListener("change", () => {
    let elementoElegido = opcionst.options[opcionst.selectedIndex].value
    if (elementoElegido === "Unlimited") {
        cajas.disabled = true
    } else {
        cajas.disabled = false
    } 
})

function randomString(len, charSet) {
    charSet = charSet || '0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

let opcions = document.getElementById("generate");

let length_key = '<?php echo $length_key; ?>';
let large_key = '<?php echo $large_key; ?>';
let type_letter = '<?php echo $type_letter; ?>';


$(document).ready(function() {
	$('#generate').click(function() {
		
		if (type_letter == true) {
			var charts = 'ASDFGHJKLZXCVBNMQWERTYUIOP1234567890';
		} else  {
			var charts = 'asdfghjklqwertyuiopzxcvbnm1234567890';
		}
		
		var line1 = randomString(length_key, charts);
		var line2 = randomString(length_key, charts);
		var line3 = randomString(length_key, charts);
		var line4 = randomString(length_key, charts);
		var line5 = randomString(length_key, charts);
		var line6 = randomString(length_key, charts);
		var line7 = randomString(length_key, charts);
		var line8 = randomString(length_key, charts);
		
		if (large_key == 6) {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4 + "-" + line5 + "-" + line6 + "-" + line7 + "-" + line8;
		} else if (large_key == 5) {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4 + "-" + line5 + "-" + line6 + "-" + line7;
		} else if (large_key == 4) {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4 + "-" + line5 + "-" + line6;
		} else if (large_key == 3) {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4 + "-" + line5;
		} else if (large_key == 2) {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4;
		} else if (large_key == 1) {
			var generated = line1 + "-" + line2 + "-" + line3;
		} else {
			var generated = line1 + "-" + line2 + "-" + line3 + "-" + line4 + "-" + line5;
		}
		document.getElementById("licenseKeyValues").value = generated;
		
	});
});
</script>

<?php
}
require_once('footer.php');

?>