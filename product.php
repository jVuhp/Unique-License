<?php
session_start();
$pageload = 'Product';

require_once('config.php');
require_once('function.php');
require_once('header.php');

if ($_SESSION['u_user']['logged'] AND unique_perm('unique.product')) {
?>
<?php			  
	$countProducts = $connection->query("SELECT COUNT(id) AS showing FROM `u_product`");
	$allProducts = mysqli_result($countProducts, 0, 'showing');
?>
<div class="container unique" style="margin-top: 30px;">
	<div class="page-wrapper bg bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>  border-top-unique">

        <div class="page-body" style="margin-top: 40px;">
          <div class="container">
            <div class="row row-deck row-cards">
              <div class="col-12" style="margin-bottom: 30px;">

                    <div class="row align-items-center">
                      <div class="col-10">
                        <h3 class="h1">Manage Product's</h3>
                        <div class="markdown text-muted">
                          See your products, delete them, create a new one and even edit them with total freedom thanks to Unique System!
                        </div>
                      </div>
                    </div>

              </div>
              <div class="col-12" style="margin-bottom: 30px;">
					<div class="row" align="left">

						<div class="col" align="left" style="margin-bottom: 30px;">
							<?php
							echo "There are " . $allProducts . " Products";
							?>
						</div>
						<div class="col" align="center" style="margin-bottom: 30px;">
							<input type="text" placeholder="Search 'Name or Direction'" class="form-control" id="searcher">
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
							<?php if (unique_perm('unique.product.add')) { ?>
							<button class="btn btn-success" data-mdb-toggle="modal" data-mdb-target="#addProduct"><i class="fa fa-plus"></i></button>
							<?php } ?>
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
<?php if (unique_perm('unique.product.add')) { ?>
<div class="modal top fade" id="addProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="false" data-mdb-keyboard="true">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		
		<div class="row">
			<div class="col">
				<div class="input-group mb-3">
					<span class="input-group-text" id="basic-addon1">Product Name</span>
					<input type="text" class="form-control" id="nm" placeholder="Product Name" aria-label="Username" aria-describedby="basic-addon1"/>
				</div>
				<div class="input-group mb-3">
					<span class="input-group-text" id="basic-addon1">Plugin Name</span>
					<input type="text" class="form-control" id="directions" placeholder="Product Direction (plugin.yml > name)" aria-label="Username" aria-describedby="basic-addon1"/>
				</div>
				<div class="input-group mb-3">
					<span class="input-group-text" id="basic-addon1">Priority</span>
					<input type="number" class="form-control" id="prior" placeholder="Priority" aria-label="Username" aria-describedby="basic-addon1"/>
				</div>
			</div>

		</div>
		
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary" onclick="actionToProduct();">Create</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script>
var e = document.getElementById('totalInList');
var w = document.getElementById('whereList');
var s = document.getElementById('searcher');
function indexLoad() {
	var langs = 'view';
	var viewingTotals = e.options[e.selectedIndex].value;
	var whereLists = w.options[w.selectedIndex].value;
	var searchs = s.value;
    $.post( 'execute/product.php', { apply : langs, viewingTotal : viewingTotals, where : whereLists, search : searchs }, 
       function( response ) {
		   
		document.getElementById("load_index_result").innerHTML = response;

       }
    );
}
function actionToProduct() {
	var langs = 'product';
	var act = '1';
	var nam = document.getElementById('nm').value;
	var directions = document.getElementById('directions').value;
	var prior = document.getElementById('prior').value;
    $.post( 'execute/users.php', { apply : langs, action : act, name : nam, direction : directions, priority : prior }, 
       function( response ) {
		response;
		indexLoad();
       }
    );
}
function actionToProductDel(idpr) {
	var langs = 'product';
	var act = '2';
    $.post( 'execute/users.php', { apply : langs, action : act, id : idpr }, 
       function( response ) {
		response;
		indexLoad();
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

require_once 'footer.php';
}

?>