<div class="container-fluid px-0 main-content">
  	<div class="container">
    	<nav aria-label="breadcrumb">
      		<ul class="breadcrumb bg-white">
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Users</a></li>
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/user/<?= $user ?>"><?= $user ?></a></li>
        		<li class="breadcrumb-item active" aria-current="page">Following</li>
      		</ul>
    	</nav> <!-- End of breadcrumb -->
  	</div>

  	<?php if ($following === NO_RESULTS): ?>
    <div class="container mx-auto">
      <div class="alert alert-info">
        <?php if ((isset($_SESSION['username'])) && ($user === $_SESSION['username'])): ?>
        Looks like you haven't followed anyone yet.
      <?php else: ?>
        Looks like this user isn't following anyone yet.
      <?php endif ?>
      </div>
    </div>
  	<?php elseif (is_string($following)): ?>
    <div class="mx-auto">
      <div class="alert alert-danger">
        <?= $followers ?>
      </div>
    </div>
  	<?php else: ?>
		<div class="container mb-4">
	      	<div class="mx-4 min-h-400 align-middle">
	        	<div class="row mb-2 text-center">
	          		<?php foreach($following as $f): ?>
			          	<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 mb-4">
                            <div class="card card-shadow">
                                <img class="card-img-top" src="<?= $f->imageUrl ?>" alt="Card image cap">
                                <div class="card-body">
                                    <h2 class="card-title"><?= $f->username ?></h2>
                                    <a href="<?= BASE_URL ?>/user/<?= $f->username ?>" class="btn btn-primary mb-2">View</a>
                                    <?php if ((isset($_SESSION['username'])) && ($user === $_SESSION['username'])): ?>
                                        <a href="<?= BASE_URL ?>/user/<?= $f->username ?>/following/unfollow" class="btn btn-danger mb-2">Unfollow</a>
                                    <?php endif ?>
                                </div>
                            </div>
			          	</div> <!-- End of card -->
	          		<?php endforeach ?>
	        	</div>
	      	</div>
		</div>
  	<?php endif ?>
</div> <!-- End of container-fluid -->
