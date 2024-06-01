<?php
session_start();
require_once('config.php');
require_once('function.php');
$unique = $_SESSION['u_user'];
$dataUser = $connx->prepare("SELECT * FROM `u_user` WHERE `udid` = ?");
$dataUser->bindParam(1, $unique['udid']);
$dataUser->execute();
$member = $dataUser->fetch(PDO::FETCH_ASSOC);

$themeToggler = $member['theme'];

if ($unique['logged'] != true) {
	if ($pageload != 'Home') {
		echo '<script> location.href = "' . $redirect_uri . '"; </script>';
	}
}
function randomCodes($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta content="Versal" name="basetitle">
    <title><?php echo SITE_NAME . " | " . $pageload; ?></title>
	<meta name="description" content="Versal is an advanced Minecraft System that supports various types of upgrades among other things to make your server super professional either java or bedrock.">
	<meta name="image" content="https://proyectojp.com/static/img/Versal-logo-make.png">
	<!-- Schema.org for Google -->
	<meta itemprop="name" content="Versal Network | Website Official">
	<meta itemprop="description" content="Versal is an advanced Minecraft System that supports various types of upgrades among other things to make your server super professional either java or bedrock.">
	<meta itemprop="image" content="https://proyectojp.com/static/img/Versal-logo-make.png">
	<!-- Open Graph general (Facebook, Pinterest & LinkedIn) -->
	<meta property="og:title" content="<?php echo SITE_NAME; ?> Network">
	<meta property="og:description" content="Versal is an advanced Minecraft System that supports various types of upgrades among other things to make your server super professional either java or bedrock.">
	<meta property="og:image" content="https://proyectojp.com/static/img/Versal-logo-make.png">
	<meta property="og:url" content="<?php echo $redirect_uri; ?>">
	<meta property="og:site_name" content="Versal">
	<meta property="og:locale" content="Argentina">
	<meta property="og:type" content="website">
	<!-- Twitter -->
	<meta property="twitter:card" content="summary">
	<meta property="twitter:title" content="<?php echo SITE_NAME; ?> Network">
	<meta property="twitter:description" content="Versal is an advanced Minecraft System that supports various types of upgrades among other things to make your server super professional either java or bedrock.">
	<meta property="twitter:image:src" content="https://proyectojp.com/static/img/Versal-logo-make.png">
	
	<meta name="theme-color" content="#FF0000">
	
    <link rel="icon" href="<?php echo $redirect_uri; ?>/static/img/<?php echo IMAGE_LOGO; ?>" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"
    />
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
	<?php require_once('static/css/vuhp.php'); ?>
    <!-- MDB -->
	<?php if ($_SESSION['theme'] == 'dark') { ?>
    <link rel="stylesheet" href="https://proyectojp.com/static/dark/css/mdb.dark.min.css" />
	<?php } else { ?>
    <link rel="stylesheet" href="https://proyectojp.com/static/light/css/mdb.min.css" />
	<?php } ?>
  </head>
  <body>



<?php if ($_SESSION['u_user']['logged']) { ?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?> bg-<?php echo theme($_SESSION['theme'], 'dark', 'light'); ?>">
  <!-- Container wrapper -->
  <div class="container">
    <!-- Toggle button -->
    <button
      class="navbar-toggler"
      type="button"
      data-mdb-toggle="collapse"
      data-mdb-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <i class="fas fa-bars"></i>
    </button>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Navbar brand -->
      <a class="navbar-brand mt-2 mt-lg-0" href="https://vanityproyect.com/">
        <img
          src="<?php echo $redirect_uri; ?>/static/img/<?php echo IMAGE_LOGO; ?>"
          height="32"
          alt="<?php echo SITE_NAME; ?>"
          loading="lazy"
        />
      </a>
	  
	  
	  
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

		<li class="nav-item"><a class="nav-link <?php echo ul_page($pageload, 'Home'); ?>" href="<?php if ($pagereturn) echo "."; ?>./"><?php echo icon_config('home', $icon_navbar); ?> Home</a></li>
		<?php if (unique_perm('unique.license.admin')) { ?>
		<li class="nav-item"><a class="nav-link <?php echo ul_page($pageload, 'License'); ?>" href="<?php if ($pagereturn) echo "."; ?>./license"><?php echo icon_config('key', $icon_navbar); ?> License</a></li>
		<?php } ?>
		<?php if (unique_perm('unique.product')) { ?>
		<li class="nav-item"><a class="nav-link <?php echo ul_page($pageload, 'Product'); ?>" href="<?php if ($pagereturn) echo "."; ?>./product"><?php echo icon_config('box', $icon_navbar); ?> Product</a></li>
		<?php } ?>
		<?php if (unique_perm('unique.users')) { ?>
		<li class="nav-item"><a class="nav-link <?php echo ul_page($pageload, 'Users'); ?>" href="<?php if ($pagereturn) echo "."; ?>./users"><?php echo icon_config('user-group', $icon_navbar); ?> Users</a></li>
		<?php } ?>


      </ul>
    </div>

    <div class="d-flex align-items-center  me-auto mb-2 mb-lg-0">
      <div class="dropdown">
        <a class="dropdown-toggle d-flex lh-1 align-items-center hidden-arrow p-0" href="#" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"  aria-expanded="false"  >
          <img src="https://cdn.discordapp.com/avatars/<?php echo $unique['udid'] . '/' . $unique['avatar'] . is_animated($unique['avatar']); ?>" class="rounded-circle" width="32" alt="<?php echo $unique['name']; ?>" />
		  <div class="d-none d-xl-block ps-2">
			<div class="mt-1 small text-danger text-gradient"><?php echo $unique['name']; ?></div>
			<div class="mt-1 small text-muted"><?php echo $member['rank']; ?></div>
		  </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar" >
          <li>
            <a class="dropdown-item" href="#!" onclick="toggleTheme();">Theme <?php if ($_SESSION['theme'] == 'dark') { echo "Light"; } else { echo "Dark"; } ?></a>
          </li>
          <li>
            <a class="dropdown-item" href="#!" onClick="logoutSession();">Logout</a>
          </li>
        </ul>
      </div>

    </div>
  </div>
</nav>
<script>
function logoutSession() {
        $.ajax({
            type: "POST",
            url: '<?php if ($pagereturn) echo "../"; ?>execute/logout.php',
            data: $(this).serialize(),
            success: function(response)
            {
                setTimeout("location.href = '<?php echo $redirect_uri; ?>';", 0500);
				setTimeout(successAlert, 1000);
           }
       });
}
function toggleTheme() {
        $.ajax({
            type: "POST",
            url: '<?php if ($pagereturn) echo "../"; ?>execute/theme.php',
            data: $(this).serialize(),
            success: function(response)
            {
                setTimeout("location.href = '<?php echo $redirect_uri; ?>';", 0500);
				setTimeout(successAlert, 0000);
           }
       });
}

function errorAlert() {
  let loaderSection = document.querySelector('.alert-danger-check');
  
  
  if (loaderSection.classList.contains('alert-disabled')) {
	loaderSection.classList.remove('alert-disabled');
	setTimeout(errorAlert, 5000);
  } else {
	loaderSection.classList.add('alert-disabled');
  }
}

function successAlert() {
  let loaderSection = document.querySelector('.alert-success-check');
  
  
  if (loaderSection.classList.contains('alert-disabled')) {
	loaderSection.classList.remove('alert-disabled');
	setTimeout(successAlert, 5000);
  } else {
	loaderSection.classList.add('alert-disabled');
  }
}

</script>
<?php } ?>
<div class="row ml-auto pull-right" style="position:fixed; bottom: 10px !important; right: 25px !important; z-index: 9;">
    <div class="toast fade show bg-danger text-white alert-disabled alert-danger-check" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle fa-lg me-2"></i>
            <strong class="me-auto">Unique Alert</strong>
            <small>1 secs ago</small>
            <button type="button" class="btn-close btn-close-white" data-mdb-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
			Errors occurred in your performed action.
        </div>
    </div>
    <div class="toast fade show bg-success text-white alert-disabled alert-success-check" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check fa-lg me-2"></i>
            <strong class="me-auto">Unique Alert</strong>
            <small>1 secs ago</small>
            <button type="button" class="btn-close btn-close-white" data-mdb-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
			The action was done correctly!
        </div>
    </div>
</div>

