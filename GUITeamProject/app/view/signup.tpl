<div class="container-fluid main-content">
  	<div class="container">
    	<nav aria-label="breadcrumb">
      		<ul class="breadcrumb bg-white">
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        		<li class="breadcrumb-item active" aria-current="page">Signup</li>
      		</ul>
    	</nav> <!-- End of breadcrumb -->

    	<!-- If the user messed up, display an error message -->
	    <?php if (isset($_SESSION['signupError'])): ?>
	      	<div class="mx-auto">
	        	<div class="alert alert-danger">
	          		<strong>Error:</strong> <?= $_SESSION['signupError'] ?>
	        	</div>
	      	</div>
	    <?php unset($_SESSION['signupError']); endif; ?>
  	</div>

  	<div class="row mt-4 mb-5">
      	<div class="col-xs-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
      		<form method="POST" action="<?= BASE_URL ?>/signup/process/">
        		<h4>Signup</h4>
        		<hr class="border-darkgray">
        		<div class="form-group">
                	<input type="text" name="first" class="form-control" placeholder="First Name" required>
        		</div>
        		<div class="form-group">
                	<input type="text" name="last" class="form-control" placeholder="Last Name">
        		</div>
        		<div class="form-group">
                	<input type="text" name="username" class="form-control" placeholder="Username" required>
        		</div>
		        <div class="form-group">
		            <input type="password" name="password" class="form-control" placeholder="Password" required>
		        </div>
		        <div class="form-group">
		            <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required>
		        </div>
		        <div class="form-group">
		            <input type="email" name="email" class="form-control" placeholder="Email" required>
		        </div>
		        <div class="form-group">
		            <input type="date" name="date" class="form-control">
		        </div>
		        <div class="row">
		          	<div class="col-xs-6 col-sm-6 col-md-6 pr-sm-2">
		            	<select name="gender" class="form-control">
		              		<option>Male</option>
		              		<option>Female</option>
		              		<option>Other</option>
		            	</select>
		          	</div>
		          	<div class="col-xs-6 col-sm-6 col-md-6 pl-sm-2 pt-3 pt-sm-0">
		            	<select name="race" class="form-control">
		              		<option>White</option>
		              		<option>Black</option>
		              		<option>Latino</option>
		              		<option>Asian</option>
		              		<option>Other</option>
		            	</select>
		          	</div>
		        </div>

        		<div class="row mt-3">
          			<div class="col-xs-6 col-sm-6 col-md-6 pr-sm-2">
                  		<input type="submit" class="btn btn-success btn-block" value="Signup">
          			</div>
          			<div class="col-xs-6 col-sm-6 col-md-6 pl-sm-2 pt-3 pt-sm-0">
            			<a href="<?= BASE_URL ?>" class="btn btn-primary btn-block">Cancel</a>
          			</div>
        		</div>
      		</form>
    	</div>
  	</div> <!-- End of Signup -->
</div> <!-- End of container -->
