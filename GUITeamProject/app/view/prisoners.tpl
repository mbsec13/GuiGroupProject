<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Prisoners</li>
			</ul>
		</nav> <!-- End of breadcrumb -->

		<!-- The database could not load the selected prisoner -->
	    <?php if (isset($_SESSION['viewPrisonerError'])): ?>
	      	<div class="mx-auto">
	        	<div class="alert alert-danger">
	          		<strong>Error loading prisoner:</strong> <?= $_SESSION['viewPrisonerError'] ?>
	        	</div>
	      	</div>
	    <?php unset($_SESSION['viewPrisonerError']); endif; ?>
	</div>

	<div class="container mb-4">
		<!--CHECK IF USERNAME == ADMIN-->
		<?php if(isset($_SESSION['username'])): ?>
		<div class="text-center">
			<a href="<?= BASE_URL ?>/prisoners/add" class="btn btn-success mb-3">Add Prisoner</a>
		</div>
		<?php endif; ?>

		<div class="hidden mx-4 min-h-400 align-middle">
			<div class="row mb-2 text-center">

				<?php if ($prisArray === NO_RESULTS): ?>
					<div class="mx-auto">
						<div class="alert alert-info">
							We could not find any prisoners in our records.  Why don't you try adding one?
						</div>
					</div>
				<?php elseif (is_string($prisArray)): ?>
					<div class="mx-auto">
						<div class="alert alert-danger">
							<?= $prisArray ?>
						</div>
					</div>
				<?php else: ?>

				<?php foreach($prisArray as $prisoner): ?>
					<div class="col-xs-6 col-sm-6 col-md-4">
						<div class="card card-shadow">
							<img class="card-img-top" src="<?= $prisoner->imageUrl ?>" alt="Card image cap">
							<div class="card-body">
								<h2 class="card-title"><?= $prisoner->name ?></h2>
								<p class="card-text"><?= $prisoner->countryOfOrigin ?></p>
								<a href="<?= BASE_URL ?>/prisoners/<?= $prisoner->id ?>" class="btn btn-primary">View</a>
								<?php if(isset($_SESSION['username'])):?>
								<?php $user = User::load($_SESSION['username']); ?>
								<?php if ($user->rank >= 3): ?>
									<div class="text-center mb-2">
										<button id="deletePrisonerButton" class="btn btn-danger" data-toggle="collapse" href="#delete-pris-div" aria-expanded="false" aria-controls="delete-pris-div">Delete</button>
									</div>
									<div id="delete-pris-div" class="collapse text-center">
										<form href="<?= BASE_URL ?>/prisoners/<?= $prisoner->id ?>/delete"
											<p class="d-block mb-2 pb-0">Are you sure you want to delete this prisoner?</p>
											<div>
												<input type="submit" class="btn btn-danger mb-2" data-toggle="collapse" href="#delete-pris-div" aria-expanded="true" aria-controls="delete-pris-div" value="Yes">
												<input type="button" class="btn btn-success mb-2" data-toggle="collapse" href="#delete-pris-div" aria-expanded="true" aria-controls="delete-pris-div" value="No">
											</div>
										</form>
									</div>
							<?php endif ?>
						<?php endif ?>
							</div>
						</div>
					</div> <!-- End of card -->
				<?php endforeach; ?>

				<?php endif; ?>

			</div> <!-- End of row -->
		</div>
	</div> <!-- End of prisoners container -->
</div> <!-- End of container-fluid -->
