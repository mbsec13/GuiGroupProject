<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Axis POWs</title>
	<link rel="icon" href="https://image.flaticon.com/icons/svg/27/27501.svg"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Alegreya" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/styles.css">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/profile.css">
	<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script> var BASE_URL = "<?= BASE_URL ?>" </script>
	<script src="<?= BASE_URL ?>/public/js/javascript.js"></script>
	<script src="<?= BASE_URL ?>/public/js/animations.js"></script>
	<script>
	var SESSION_username = "heyo";
	</script>
		<?php if(isset($_SESSION['username'])): ?>
	<script>
		$(document).ready(function() {
				SESSION_username = "<?= $_SESSION['username'] ?>";
		});
	</script>
	<?php endif; ?>
</head>
<body>
	<nav class="navbar navbar-expand-md bg-dark navbar-dark">
		<a href="<?= BASE_URL ?>/" class="navbar-brand">Axis Prisoners of War</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div id="collapsibleNavbar" class="collapse navbar-collapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/map/">Map</a></li>
				<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/camps/">Camps</a></li>
				<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/prisoners/">Prisoners</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="otherDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Other
					</a>
					<div class="dropdown-menu" aria-labelledby="otherDropdownMenuLink">
						<a class="dropdown-item" href="<?= BASE_URL ?>/aboutus/">About Us</a>
						<a class="dropdown-item" href="<?= BASE_URL ?>/activities/">Activities</a>
						<a class="dropdown-item" href="<?= BASE_URL ?>/interactions/">Interaction with Americans</a>
						<a class="dropdown-item" href="<?= BASE_URL ?>/remnants/">Remnants of Nazism</a>
						<a class="dropdown-item" href="<?= BASE_URL ?>/resources/">Resources</a>
						<a class="dropdown-item d-none d-none d-md-block" href="<?= BASE_URL ?>/search/">Search</a>
					</div>
				</li>
			</ul>
			<ul class="navbar-nav">
				<li class="nav-item">
					<form id="searchForm" class="d-none d-lg-block" method="GET" action="<?= BASE_URL ?>/search/">
						<div class="input-group mr-5">
							<input type="text" class="form-control border-radius-nr" placeholder="Search" name="terms">
				            <button class="btn btn-outline-primary py-2 border-radius-nl mr-3" type="submit">
								<i class="fas fa-search font-size-15"></i>
							</button>
						</div>
					</form> <!-- End of search bar-->
				</li>
				<li class="nav-item"><a class="nav-link d-block d-sm-block d-md-none" href="<?= BASE_URL ?>/search/">Search</a></li>
				<?php if(isset($_SESSION['username'])): ?>
					<li>
						<div class="mr-3"><span class="navbar-text d-none d-md-block">Hello, <?= $_SESSION['username'] ?></span></div>
					</li>
					<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/user/<?= $_SESSION['username'] ?>">Profile</a></li>
					<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout/">Logout</a></li>
				<?php endif; ?>
				<?php if(!isset($_SESSION['username'])): ?>
					<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login/">Login</a></li>
					<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/signup/">Signup</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav> <!-- End of nav -->
