<?php
session_start();
$pageload = 'Server';

$pagedor = $_GET['q'];

require_once('config.php');
require_once('header.php');



if ($_SESSION['u_user']['logged'] AND unique_perm('unique.license.server')) {
	$licenseVerifys = $connection->query("SELECT * FROM `u_license` WHERE `license` = '" . $pagedor . "'");
	$verifyUDID = mysqli_result($licenseVerifys, 0, 'udid');
	$verifyUse = mysqli_result($licenseVerifys, 0, 'use');
	if ($_SESSION['u_user']['udid'] != $verifyUDID AND unique_perm('unique.admin.server') == false) {
		echo '<script> history.back(); </script>';
	}
	
?>

<div class="container unique" style="margin-top: 30px;margin-bottom: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?> border-top-unique" style="margin-bottom: 20px;">

        <div class="page-body" style="margin-top: 20px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">Limit your License</h3>
                        <div class="markdown text-muted">
                          Limit your license in case of leaks or others, you will have total security that no one else but you can use it!
                        </div>
						
                      </div>
                    </div>

              </div>
            </div>
          </div>
        </div>
    </div>
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?> border-top-unique">
        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12">


					<div class="row" align="left">

						<div class="col" align="left">

						<div class="form-check form-switch">
						<?php
						if ($verifyUse == 0) {
						?>
						  <input class="form-check-input" type="checkbox" name="boundpr" onclick="boundChange();" id="flexSwitchCheckDefault" />
						  <label class="form-check-label" for="flexSwitchCheckDefault">Permit all IPS use your license? [<b id="useSwitch">NO</b>]</label>
						<?php 
						} else {
						?>
						  <input class="form-check-input" type="checkbox" name="boundpr" onclick="boundChange();" checked id="flexSwitchCheckDefault" />
						  <label class="form-check-label" for="flexSwitchCheckDefault">Permit all IPS use your license? [<b id="useSwitch">YES</b>]</label>
						<?php
						}
						?>
						</div>
						</div>
						<div class="col" align="right">

							<?php if (unique_perm('unique.license.add')) { ?>
							<button class="btn btn-success" data-mdb-toggle="modal" data-mdb-target="#addLicense"><i class="fa fa-plus"></i></button>
							<?php } ?>
						</div>
										
						  <section style="margin-bottom: 30px;">

							<ul class="nav nav-tabs nav-fill mb-3" id="ex-2" role="tablist">
							  <li class="nav-item" role="presentation">
								<a class="nav-link" id="ex-2-tab-1" onclick="indexLoad();" data-mdb-toggle="pill" href="#ex-2-tabs-1" role="tab" aria-controls="pills-1" aria-selected="true">Accept</a>
							  </li>
							  <li class="nav-item" role="presentation">
								<a class="nav-link active" id="ex-2-tab-2" onclick="process();" data-mdb-toggle="pill" href="#ex-2-tabs-2" role="tab" aria-controls="pills-2" aria-selected="true">On Hold</a>
							  </li>
							  <li class="nav-item" role="presentation">
								<a class="nav-link" id="ex-2-tab-3" onclick="denied();" data-mdb-toggle="pill" href="#ex-2-tabs-3" role="tab" aria-controls="pills-3" aria-selected="true">Denied</a>
							  </li>
							</ul>

							<div class="tab-content" id="ex-2-content">
								<div class="tab-pane fade show" id="ex-2-tabs-1" role="tabpanel" aria-labelledby="pills-1-tab">
									<div id="load_server_accept"><div style="min-height: 50vh; margin-top: 250px;" align="center"><span class="spinner spinner-large spinner-blue spinner-slow"></span></div></div>
								</div>
								<div class="tab-pane fade show active" id="ex-2-tabs-2" role="tabpanel" aria-labelledby="pills-2-tab">
									<div id="load_server_process"><div style="min-height: 50vh; margin-top: 250px;" align="center"><span class="spinner spinner-large spinner-blue spinner-slow"></span></div></div>
								</div>
								<div class="tab-pane fade show" id="ex-2-tabs-3" role="tabpanel" aria-labelledby="pills-3-tab">
									<div id="load_server_denied"><div style="min-height: 50vh; margin-top: 250px;" align="center"><span class="spinner spinner-large spinner-blue spinner-slow"></span></div></div>
								</div>
							  
							</div>
						  </section>
				
					</div>
				</div>
			  </div>
            </div>
          </div>
        </div>
	</div>
<?php if (unique_perm('unique.license.server')) { ?>
<div class="modal top fade" id="addLicense" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="false" data-mdb-keyboard="true">
		<form id="createLicense" method="post">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Server</h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="wrapper-staff">
				<div class="col">
					<label for="basic-url" class="form-label">IP of the Server</label>
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1">IP</span>
						<input type="text" class="form-control" id="ipserver" placeholder="127.0.0.1" aria-label="Username" aria-describedby="basic-addon1"/>
					</div>
				</div>
				
			</div>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="submit_license_action" id="applyandgens">Add</button>
      </div>
    </div>
  </div>
		</form>
</div>
<?php } ?>

<script>
var keylicense = '<?php echo $pagedor; ?>';
$(document).ready(function() {
    $('#createLicense').submit(function(e) {
		var langs = '4';
		var type = '5';
		var server = document.getElementById("ipserver").value;
		$.post( 'execute/license.php', { apply : langs, action : type, key : keylicense, ip : server }, 
		   function( response ) {
			   
			response;

		   }
		);
     });
});

function boundChange() {

	if ($('#flexSwitchCheckDefault').is(":checked")) {
		document.getElementById("useSwitch").innerHTML = "YES";
		changeBound('1');

	} else {
		document.getElementById("useSwitch").innerHTML = "NO";
		changeBound('0');
	}


}
 
function changeBound(type) {
	var langs = '8';
    $.post( 'execute/license.php', { apply : langs, action : type, key : keylicense }, 
       function( response ) {
		   
		response;

       }
    );
}
 
function actionToUse(type, ipuse) {
	var langs = '4';
    $.post( 'execute/license.php', { apply : langs, action : type, key : keylicense, ip : ipuse }, 
       function( response ) {
		   
		response;
		indexLoad();
		process();
		denied();
       }
    );
}
 
function indexLoad() {
	var langs = 'accept';
    $.post( 'execute/server.php', { apply : langs, key : keylicense }, 
       function( response ) {
		   
		document.getElementById("load_server_accept").innerHTML = response;

       }
    );
}
function process() {
	var langs = 'process';
    $.post( 'execute/server.php', { apply : langs, key : keylicense }, 
       function( response ) {
		   
		document.getElementById("load_server_process").innerHTML = response;

       }
    );
}


function denied() {
	var langs = 'denied';
    $.post( 'execute/server.php', { apply : langs, key : keylicense }, 
       function( response ) {
		   
		document.getElementById("load_server_denied").innerHTML = response;

       }
    );
}
document.onload = process();
</script>
<?php

require_once('footer.php');
}

?>
