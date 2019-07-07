<div class="container-fluid px-0 main-content">
  	<div class="container">
    	<nav aria-label="breadcrumb">
      		<ul class="breadcrumb bg-white">
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Users</a></li>
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/user/<?= $userData->username ?>"><?= $userData->username ?></a></li>
        		<li class="breadcrumb-item active" aria-current="page">Settings</li>
      		</ul>
    	</nav> <!-- End of breadcrumb -->

    	<?php if (isset($_SESSION['updateUserError'])): ?>
      		<div class="mx-auto">
        		<div class="alert alert-danger">
          			<strong>Error:</strong> <?= $_SESSION['updateUserError'] ?>
        		</div>
      		</div>
    	<?php unset($_SESSION['updateUserError']); endif; ?>
  	</div>

  	<div class="container">
    	<div id="updateProfile" class="mb-5">
      		<h3>Profile</h3>
      		<form id="profile-form" action="<?= BASE_URL ?>/user/<?= $userData->username ?>/processbio" method="POST">
        		<label>Change your profile picture and about text here</label>
        		<input class="form-control mb-3" type="text" value="<?= $userData->imageUrl ?>" name="pictureURL">
        		<h5>About</h5>
        		<textarea class="form-control min-h-300 mb-2" name="about"><?= $userData->bio ?></textarea>
        		<input type="submit" class="btn btn-success" value="Update">
      		</form>
    	</div>
    	<div id="updateAccount" class="mb-5">
      		<h3>Account</h3>
      		<form id="account-form" action="<?= BASE_URL ?>/user/<?= $userData->username ?>/processbasic" method="POST">
			      <label>Update account information by entering values into respective field</label>
		        <input type="text" class="form-control mb-2" value="<?= $userData->first ?>" name="first" placeholder="First Name">
		        <input type="text" class="form-control mb-2" value="<?= $userData->last ?>" name="last" placeholder="Last Name">
		        <input type="text" class="form-control mb-4" value="<?= $userData->email ?>" name="email" placeholder="Email Address">
		        <input type="password" class="form-control mb-2" placeholder="New Password" name="password">
		        <input type="password" class="form-control mb-2" placeholder="Confirm New Password" name="cPassword">
		        <input type="submit" class="btn btn-success" value="Update">
  			</form>
		</div>
    	<div id="permissions-div" class="mb-5">
      		<h3>Permissions</h3>
      		<form id="updatePermissions" action="<?= BASE_URL ?>/user/<?= $userData->username ?>/permissions" method="POST">
        		<div class="w-100 mb-2">
		          	<label>Check all boxes for information you will show on your profile:</label><br>
		          	<?php if ($userData->nameHidden == 1): ?>
		            	<input type="checkbox" name="name" value="Name" checked> Name<br>
		          	<?php else: ?>
		            	<input type="checkbox" name="name" value="Name"> Name<br>
		          	<?php endif;?>

		          	<?php if ($userData->dateOfBirthHidden == 1): ?>
		            	<input type="checkbox" name="dob" value="Date of Birth" checked> Date of Birth<br>
		          	<?php else: ?>
		            	<input type="checkbox" name="dob" value="Date of Birth"> Date of Birth<br>
		          	<?php endif;?>

		          	<?php if ($userData->genderHidden == 1): ?>
		            	<input type="checkbox" name="gender" value="Gender" checked> Gender<br>
		          	<?php else: ?>
		            	<input type="checkbox" name="gender" value="Gender"> Gender<br>
		          	<?php endif;?>

		          	<?php if ($userData->emailHidden == 1): ?>
		            	<input type="checkbox" name="email" value="Email" checked> Email<br>
		          	<?php else: ?>
		            	<input type="checkbox" name="email" value="Email"> Email<br>
		          	<?php endif;?>
		        </div>
		        <input type="submit" class="btn btn-success" value="Update">
	      	</form>
    	</div>
    	<div id="delete-account-div" class="mb-5">
      		<h3>Delete Account</h3>
      		<form id="deleteUser">
        		<label>Delete your account here</label><br>
        		<input type="button" class="btn btn-danger mb-3 dropdown-toggle" data-toggle="collapse" href="#confirm-collapse" aria-expanded="true" aria-controls="confirm-collapse" value="Delete Account">
        		<div id="confirm-collapse" class="collapse">
          			<label>Are you sure you want to delete your account?</label>
          			<div id="report-buttons">
			            <input type="submit" class="btn btn-danger text-uppercase" data-toggle="collapse" data-target="#confirm-collapse" aria-expanded="false" aria-controls="confirm-collapse" value="Yes">
			            <input type="button" class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#confirm-collapse" aria-expanded="false" aria-controls="confirm-collapse" value="No">
          			</div>
        		</div>
      		</form>
    	</div>
  	</div>
</div> <!-- End of container-fluid -->
