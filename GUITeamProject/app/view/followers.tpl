<div class="container-fluid px-0 main-content">
  	<div class="container">
    	<nav aria-label="breadcrumb">
	    	<ul class="breadcrumb bg-white">
	        	<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
	        	<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Users</a></li>
	        	<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/user/<?= $user ?>"><?= $user ?></a></li>
	        	<li class="breadcrumb-item active" aria-current="page">Followers</li>
	      	</ul>
	    </nav> <!-- End of breadcrumb -->
	</div>

  	<?php if ($followers === NO_RESULTS): ?>
    	<div class="container mx-auto">
      		<div class="alert alert-info">
	        	<?php if ((isset($_SESSION['username'])) && ($user == $_SESSION['username'])): ?>
	        		Looks like nobody is following you yet.
	      		<?php else: ?>
	        		Looks like nobody is following this user yet.
	      		<?php endif ?>
      		</div>
    	</div>
  	<?php elseif (is_string($followers)): ?>
	    <div class="mx-auto">
	      	<div class="alert alert-danger">
	        	<?= $followers ?>
	      	</div>
	    </div>
  	<?php else: ?>
	  	<div class="container mb-4">
      		<div class="mx-4 min-h-400 align-middle">
        		<div class="row mb-2 text-center">
          			<?php foreach($followers as $f): ?>
          				<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 mb-4">
          					<div class="card card-shadow">
          						<img class="card-img-top" src="<?= $f->imageUrl ?>" alt="Card image cap">
	            				<div class="card-body">
	              					<h2 class="card-title"><?= $f->username ?></h2>
	              					<a href="<?= BASE_URL ?>/user/<?= $f->username ?>" class="btn btn-primary">View</a>
	            				</div>
          					</div>
          				</div> <!-- End of card -->
        			<?php endforeach ?>
        		</div>
      		</div>
	  	</div>
	<?php endif ?>
</div> <!-- End of container-fluid -->
