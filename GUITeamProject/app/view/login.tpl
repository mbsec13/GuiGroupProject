<div class="container-fluid main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Login</li>
			</ul>
		</nav> <!-- End of breadcrumb -->

		<!-- In case an error occurred during login, display it -->
		<?php if (isset($_SESSION['loginError'])): ?>
	    	<div class="mx-auto">
	        	<div class="alert alert-danger">
	          		<strong>Error:</strong> <?= $_SESSION['loginError'] ?>
	        	</div>
	      	</div>
    	<?php unset($_SESSION['loginError']); endif; ?>
	</div>


	<div class="row mt-4">
		<div class="col-xs-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
			<form method="POST" action="<?= BASE_URL ?>/login/process/">
				<h4>Login</h4>
				<hr class="border-darkgray">
				<div class="form-group">
					<input type="text" name="username" class="form-control" placeholder="Username">
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control" placeholder="Password">
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6 pr-sm-2">
						<input type="submit" class="btn btn-success btn-block" value="Login">
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 pl-sm-2 pt-3 pt-sm-0">
						<a href="<?= BASE_URL ?>/signup/" class="btn btn-primary btn-block">Register</a>
					</div>
				</div>
			</form>
		</div>
	</div> <!-- End of Login -->
</div> <!-- End of container -->
