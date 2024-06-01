<?php
$pageload = 'Users';

$userid = $_GET['q'];

require_once('config.php');
require_once('function.php');
require_once('header.php');

if (unique_perm('unique.users')) {
	
	if (!$userid) {
?>

<div class="container unique" style="margin-bottom: 30px; margin-top: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>  border-top-unique">

        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">Users List</h3>
                        <div class="markdown text-muted">
                          Look at the accounts registered in the database and edit them to your preferences.
                        </div>
                      </div>
                    </div>

              </div>
              <div class="col-12" style="margin-bottom: 30px;">
					<div class="row" align="left">

						<div class="col" align="left" style="margin-bottom: 30px;">
							<input type="text" placeholder="Search 'Name or Discord ID'" class="form-control" id="searcher">
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
								<option value="all">All</option>
							</select>
						</div>
						<div class="table-responsive">
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
	

</div>
<script>
function deleteUser(user) {
	var langs = 'delete';
    $.post( 'execute/users.php', { apply : langs, user : user }, 
       function( response ) {
		indexLoad();
       }
    );
}
var e = document.getElementById('totalInList');
var w = document.getElementById('whereList');
var s = document.getElementById('searcher');
function indexLoad() {
	var langs = 'view';
	var viewingTotals = e.options[e.selectedIndex].value;
	var whereLists = w.options[w.selectedIndex].value;
	var searchs = s.value;
    $.post( 'execute/users.php', { apply : langs, viewingTotal : viewingTotals, where : whereLists, search : searchs }, 
       function( response ) {
		   
		document.getElementById("load_index_result").innerHTML = response;

       }
    );
}

e.onchange = indexLoad;
w.onchange = indexLoad;
s.onchange = indexLoad;
s.oninput = indexLoad;

function actionToUser(act, id) {
	var langs = 'act';
    $.post( 'execute/users.php', { apply : langs, action : act, uid : id }, 
       function( response ) {
		response;
		indexLoad();
       }
    );
}


document.onload = indexLoad();
</script>








<?php
	} else {
		$playerList = $connx->prepare("SELECT * FROM `u_user` WHERE `id` = ?");
		$playerList->bindParam(1, $userid);
		$playerList->execute();
		if ($playerList->RowCount() > 0) {
		$userList = $playerList->fetch(PDO::FETCH_ASSOC);
?>
<div class="container unique" style="margin-bottom: 30px; margin-top: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>  border-top-unique">

        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">
						<img src="https://cdn.discordapp.com/avatars/<?php echo $userList['udid'] . '/' . $userList['avatar'] . is_animated($userList['avatar']); ?>" width="64" style="border-radius: 10px;">
						<?php echo $userList['name']; ?>
						</h3>
                      </div>
                    </div>

              </div>
              <div class="col-12" style="margin-bottom: 30px;">
					<div class="row" align="left">

						<div class="col" align="left" style="margin-bottom: 30px;">
							<div class="input-group">
								<input type="text" list="permissions" id="selectPermission" placeholder="unique." class="form-control">
								<datalist id="permissions">
										<option value="unique.product">View Product</option>
										<option value="unique.product.add">Add Product</option>
										<option value="unique.product.delete">Delete Product</option>
										<option value="unique.license">View License</option>
										<option value="unique.license.edit">Edit License</option>
										<option value="unique.license.add">Add License</option>
										<option value="unique.license.delete">Delete License</option>
										<option value="unique.license.reset">Reset Ips License</option>
										<option value="unique.license.server">Server License</option>
										<option value="unique.license.admin">Admin License</option>
										<option value="unique.users">View Users</option>
										<option value="unique.users.delete">Delete Users</option>
										<option value="unique.users.edit">Edit Users</option>
										<option value="unique.admin.server">Admin Server List</option>
								</datalist>
								<button class="btn btn-outline-success" id="saveSelection"><i class="fa fa-check"></i></button>
							</div>
						</div>
						<div class="col" align="center" style="margin-bottom: 30px;">
							
						</div>
						<div class="col" align="right">
							<div class="input-group mb-3">
								<span class="input-group-text" id="basic-addon1">Rank</span>
								<input type="text" class="form-control" id="roleWrited" placeholder="Write rank name for the user" value="<?php echo $userList['rank']; ?>" />
								<button class="btn btn-outline-success" onclick="actionUser('3');"><i class="fa fa-check"></i> Save</button>
							</div>
						</div>
						<div id="load_index_result">
							<div style="min-height: 80vh; margin-top: 250px;" align="center">
								<span class="spinner spinner-large spinner-blue spinner-slow"></span>
							</div>
						</div>
					</div>
			  </div>
			  <p class="text-muted">Click in the permission for remove</p>
            </div>
          </div>
        </div>
	</div>
</div>
<script>
var userUDID = '<?php echo $userList['udid']; ?>';
var userID = '<?php echo $userid; ?>';
function indexLoad() {
	var langs = 'viewperms';
	var id = userUDID;
    $.post( 'execute/users.php', { apply : langs, uid : id }, 
       function( response ) {
		   
		document.getElementById("load_index_result").innerHTML = response;

       }
    );
}

function removePermission(id) {
	var langs = 'perms';
	var act = 1;
    $.post( 'execute/users.php', { apply : langs, action : act, uid : id }, 
       function( response ) {
		response;
		indexLoad();
       }
    );
}

function actionUser(act) {
	var langs = 'perms';
	var role = document.getElementById('roleWrited').value;
	var id = userID;
    $.post( 'execute/users.php', { apply : langs, action : act, uid : id, rank : role }, 
       function( response ) {
		response;
		indexLoad();
       }
    );
}

var buttons = document.getElementById('saveSelection');
var textPerms = document.getElementById('selectPermission');
buttons.addEventListener("click", function() {
	var langs = 'perms';
	var act = '2';
	var id = userUDID;
	var permss = textPerms.value;
    $.post( 'execute/users.php', { apply : langs, action : act, uid : id, permission : permss }, 
       function( response ) {
		response;
		indexLoad();
		textPerms.value = '';
       }
    );
});


document.onload = indexLoad();
</script>

<?php } else echo "<script> history.go(-1); </script>";
	}
require_once('footer.php');
}
?>