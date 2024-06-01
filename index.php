<?php
session_start();
$pageload = 'Home';

require_once('config.php');

require_once('header.php');

if (!$_SESSION['u_user']['logged']) {
	echo '<script>location.href = "./login";</script>';
}

if ($_SESSION['u_user']['logged']) {
?>

<div class="container unique" style="margin-top: 30px;margin-bottom: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>  border-top-unique">

        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">Manage your Licenses</h3>
                        <div class="markdown text-muted">
                          Unique System, A system made specifically to protect your products to the maximum and provide you with a good experience and facilitate the administration of licenses.<br>
						  <?php echo $_SESSION['u_user']['udid'] . ' | ' . $_SESSION['u_user']['name'] . ' | ' . $_SESSION['u_user']['id'] . ' | ' . $_SESSION['u_user']['rank']; ?>
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

					<div class="row" align="left">

						<div class="col" align="left">
							<?php
							
								echo "You have " . $myLicenses . " Licenses";
							
							?>
						</div>
						<div class="col" align="center">
							<input type="text" placeholder="Search 'License or Client'" class="form-control" id="searcher">
						</div>
						<div class="col" align="right">
							<select id="whereList">
								<option value="1" selected>New</option>
								<option value="">Old</option>
							</select>
							<select id="totalInList">
								<option value="20" selected>20</option>
								<option value="60">60</option>
								<option value="100">100</option>
								<option value="200">200</option>
								<option value="500">500</option>
								<option value="99999">All</option>
							</select>
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

<script>

var e = document.getElementById('totalInList');
var w = document.getElementById('whereList');
var s = document.getElementById('searcher');
function indexLoad() {
	var langs = '1';
	var viewingTotals = e.options[e.selectedIndex].value;
	var whereLists = w.options[w.selectedIndex].value;
	var searchs = s.value;
	var viewings = 'o';
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
</script>
<?php

require_once('footer.php');

}
?>


