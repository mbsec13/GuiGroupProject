<script type="text/javascript">
	$(document).ready(function() {
		// Slideup effect on pinned camp card/dialog when it's close button clicked
		$('#pinnedCampCardCloseButton').on('click', function() {
			$('#pinnedCampCard').slideUp();
			//$("#pinnedCampCard").addClass("d-none");
			//$("#pinnedCampCard").hide();
			if ($('#pinnedActivityCard').is(':visible')) {
				$('#pinnedActivityCard').slideUp();
			}
		});
	});
</script>

<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="<?= BASE_URL ?>/public/js/visualization.js"></script>

<style>

.node rect {
  cursor: pointer;
  fill: #fff;
  fill-opacity: 0.5;
  stroke: #3182bd;
  stroke-width: 1.5px;
}

.node text {
  font: 10px sans-serif;
  pointer-events: none;
}

.link {
  fill: none;
  stroke: #9ecae1;
  stroke-width: 1.5px;
}

</style>

<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Camps</li>
			</ul>
		</nav> <!-- End of breadcrumb -->

		<!-- The database could not load the selected camp -->
		<?php if (isset($_SESSION['viewCampError'])): ?>
      		<div class="mx-auto">
        		<div class="alert alert-danger">
          			<strong>Error loading camp:</strong> <?= $_SESSION['viewCampError'] ?>
        		</div>
      		</div>
    	<?php unset($_SESSION['viewCampError']); endif; ?>
	</div>
	<div class="container-fluid d-none d-sm-none d-md-none d-lg-block">
		<div class="text-center">
			<a href="<?= BASE_URL ?>/camps/add" class="btn btn-success mb-3">Add Camp</a>
		</div>
		<div class="row">
			<div class="col-2">
				<div id="pinnedCampCard" class="card card-shadow mb-4 collapse d-none">
					<div class="card-header py-1 pr-1" style="background-color: rgba(0,0,0,.2)">
						<!--
							Using Bootstrap 4's collapse on pinnedCampCard produces an odd effect.
							Closing effect imitated via JQuery
						-->
						<span id="pinnedCampCardCloseButton" class="btn-close float-right d-inline-block">
							<i class="float-right far fa-times-circle"></i>
						</span>
					</div>
					<div>
						<img class="card-img-top" id="campImgList" src="../../public/img/default-camp.jpg" alt="Card image cap">
					</div>
					<div class="card-body text-center">
						<h2 class="card-title" id="campNameList">Camp Name</h2>
						<p class="card-text" id="campLocationList">Location</p>
						<div class="text-center mb-2">
							<a class="btn btn-primary" id="viewCampButton">View</a>
						</div>
						<?php if(isset($_SESSION['username'])): ?>
							<?php $user = User::load($_SESSION['username']); ?>
							<?php if($user->rank >= 2): ?>
								<div class="text-center mb-2">
									<button id="editCampButton" class="btn btn-success" data-toggle="collapse" href="#edit-camp-div" aria-expanded="false" aria-controls="edit-camp-div">Edit</button>
								</div>
								<div id="edit-camp-div" class="collapse">
									<form id="editCampForm">
										<input id="editCampName" class="form-control mb-2" type="text" name="name" placeholder="Camp Name">
										<input id="editCampLocation" class="form-control mb-2" type="text" name="location" placeholder="Location">
										<div class="text-center mb-4">
											<input class="btn btn-primary" type="submit" name="submitEditCampButton" value="Confirm" data-toggle="collapse" href="#edit-camp-div" aria-expanded="false" aria-controls="edit-camp-div">
										</div>
									</form>
								</div>
								<div class="text-center mb-2">
									<button id="addActivityButton" class="btn btn-success" data-toggle="collapse" href="#pinnedActivityCard" aria-expanded="false" aria-controls="pinnedActivityCard">Add Activity</button>
								</div>
							<?php endif; ?>
							<?php if($user->rank >= 3): ?>
								<div class="text-center mb-2">
									<button id="deleteCampButton" class="btn btn-danger" data-toggle="collapse" href="#delete-camp-div" aria-expanded="false" aria-controls="delete-camp-div">Delete</button>
								</div>
								<div id="delete-camp-div" class="collapse text-center">
									<form id="deleteCampForm">
										<p class="d-block mb-2 pb-0">Are you sure you want to delete this camp?</p>
										<div>
											<input type="submit" id="deleteCampYes" class="btn btn-danger mb-2" data-toggle="collapse" href="#delete-camp-div" aria-expanded="true" aria-controls="delete-camp-div" value="Yes">
											<input type="button" id="deleteCampNo" class="btn btn-success mb-2" data-toggle="collapse" href="#delete-camp-div" aria-expanded="true" aria-controls="delete-camp-div" value="No">
										</div>
									</form>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<div id="pinnedActivityCard" class="mb-5 collapse mx-auto">
					<div class="card card-shadow">
						<div class="card-header py-1 pr-1" style="background-color: rgba(0,0,0,.2)">
							<span class="btn-close float-right d-inline-block" data-toggle="collapse" href="#pinnedActivityCard" aria-expanded="true" aria-controls="pinnedActivityCard">
								<i class="float-right far fa-times-circle"></i>
							</span>
						</div>
						<div class="card-body">
							<h2>Create Activity</h2>
							<form id="createActivityFromCamps">
								<input id="activityType" class="form-control mb-2" type="text" name="type" placeholder="Type">
								<textarea id="activityDescription" class="form-control min-h-100 mb-2" type="text" name="description" placeholder="Description"></textarea>
								<input class="btn btn-success w-100" type="submit" name="createActivityButton" value="Create">
							</form>
						</div>
					</div>
				</div>
			</div> <!-- End of card -->

			<div class="col-8 min-h-400 text-center" id="campsHolder">
				<!-- Visuals go here -->
			</div>
		</div> <!-- End of row -->
	</div>

	<div class="container mb-4 d-block d-sm-block d-md-block d-lg-none"> <!--CHECK IF USERNAME == ADMIN-->
		<?php if(isset($_SESSION['username'])): ?>
			<div class="text-center">
				<a href="<?= BASE_URL ?>/camps/add" class="btn btn-success mb-3">Add Camp</a>
			</div>
		<?php endif; ?>

		<div class="mx-4 min-h-400 align-middle hidden">
			<div class="row mb-2 text-center">
				<?php if ($campArray === NO_RESULTS): ?>
					<div class="mx-auto">
						<div class="alert alert-info">
							We could not find any camps in our records.  Why don't you try adding one?
						</div>
					</div>
				<?php elseif (is_string($campArray)): ?>
					<div class="mx-auto">
						<div class="alert alert-danger">
							<?= $campArray ?>
						</div>
					</div>
				<?php else: ?>

					<?php foreach($campArray as $camp): ?>
						<div class="col-xs-6 col-sm-6 col-md-4 mb-4">
							<div class="card card-shadow">
								<img class="card-img-top" src="<?= $camp->imageUrl ?>" alt="Card image cap">
								<div class="card-body">
									<h2 class="card-title"><?= $camp->name ?></h2>
									<p class="card-text"><?= $camp->location ?></p>
									<div class="text-center mb-2">
										<a href="<?= BASE_URL ?>/camps/<?= $camp->id ?>" class="btn btn-primary">View</a>
									</div>
								</div>
							</div>
						</div> <!-- End of card -->
					<?php endforeach; ?>
				<?php endif; ?>
			</div> <!-- End of row -->
		</div>
	</div> <!-- End of camps container -->
</div> <!-- End of container-fluid -->
