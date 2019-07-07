<div class="container-fluid px-0 main-content" style="min-height: 100vh">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Search</li>
			</ul>
		</nav> <!-- End of breadcrumb -->
		<!-- F O R C E R E L O A D -->
		<form id="searchForm" class="d-lg-block mb-3" method="GET">
			<div class="input-group mr-5">
				<input type="text" class="form-control border-radius-nr" placeholder="Search" name="terms">
				<button class="btn btn-outline-primary py-2 border-radius-nl mr-3" type="submit">
					<i class="fas fa-search font-size-15"></i>
				</button>
			</div>
		</form> <!-- End of search bar -->
	</div>

	<!--
		bl-purple: User
		bl-blue:   Camp
		bl-green:  Prisoner
	 -->
	<div class="container mb-4">
		<h4 class="text-center">Search Results</h4>
		<?php if($userArray === NO_RESULTS && $campArray === NO_RESULTS && $prisArray === NO_RESULTS): ?>
			<div class="alert alert-info">
				We could not find anything with your given search terms.
			</div>
		<?php endif; ?>

		<div class="row mb-2">
			<?php if ($userArray != NO_RESULTS): ?>
				<?php foreach($userArray as $user): ?>
					<div class="w-100 h-100 search-item bl-solid bl-purple search-item">
						<a href="<?= BASE_URL ?>/user/<?= $user->username ?>" class="w-100 h-100">
							<div class="row ml-3 p-2">
								<img class="float-left mr-3" src="<?= $user->imageUrl ?>" alt="User image">
								<h5 class="col mr-3 pt-3"><?= $user->username ?></h5>
								<h5 class="col d-none d-sm-block mr-3 pt-3">
									<?php if($user->nameHidden == 1): ?>
										<?= $user->first ?> <?= $user->last ?>
									<?php endif; ?>
								</h5>
								<h5 class="col d-none d-sm-none d-md-block mr-3 pt-3">
									<?php if($user->emailHidden == 1): ?>
										<?= $user->email ?>
									<?php endif; ?>
								</h5>
							</div>
						</a>
					</div> <!-- End of search item -->
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ($campArray != NO_RESULTS): ?>
				<?php foreach($campArray as $camp): ?>
					<div class="w-100 h-100 search-item bl-solid bl-blue search-item">
						<a href="<?= BASE_URL ?>/camps/<?= $camp->id ?>" class="w-100 h-100">
							<div class="row ml-3 p-2">
								<img class="float-left mr-3" src="<?= $camp->imageUrl ?>" alt="Camp image">
								<h5 class="col mr-3 pt-3"><?= $camp->name ?></h5>
								<h5 class="col d-none d-sm-block mr-3 pt-3"><?= $camp->location ?></h5>
								<h5 class="col d-none d-sm-none d-md-block mr-3 pt-3"><?= $camp->warden ?></h5>
							</div>
						</a>
					</div> <!-- End of search item -->
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ($prisArray != NO_RESULTS): ?>
				<?php foreach($prisArray as $prisoner): ?>
					<div class="w-100 h-100 search-item bl-solid bl-green search-item">
						<a href="<?= BASE_URL ?>/prisoners/<?= $prisoner->id ?>" class="w-100 h-100">
							<div class="row ml-3 p-2">
								<img class="float-left mr-3" src="<?= $prisoner->imageUrl ?>" alt="Prisoner image">
								<h5 class="col mr-3 pt-3"><?= $prisoner->name ?></h5>
								<h5 class="col d-none d-sm-block mr-3 pt-3"><?= $prisoner->countryOfOrigin ?></h5>
								<h5 class="col d-none d-sm-none d-md-block mr-3 pt-3"><?= $prisoner->dateOfBirth ?></h5>
							</div>
						</a>
					</div> <!-- End of search item -->
				<?php endforeach; ?>
			<?php endif; ?>
		</div> <!-- End of row -->
	</div> <!-- End of search results container -->
</div> <!-- End of container-fluid -->
