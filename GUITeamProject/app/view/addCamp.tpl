<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/camps">Camps</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add Camp</li>
			</ul>
		</nav> <!-- End of breadcrumb -->

		<!-- If the user messed up, display an error message -->
	    <?php if (isset($_SESSION['addCampError'])): ?>
	    	<div class="mx-auto">
	        	<div class="alert alert-danger">
	          		<strong>Error:</strong> <?= $_SESSION['addCampError'] ?>
	        	</div>
	      	</div>
	    <?php unset($_SESSION['addCampError']); endif; ?>
	</div>

	<form method="POST" action="<?= BASE_URL ?>/camps/add/process">
		<div class="h-400 bg-gray"></div>
		<div class="container">
			<div class="card border-gray camp-card mb-5 mx-auto ">
				<div class="card-header border-gray">
					<input class="form-control mt-3" type="text" name="name" placeholder="Camp Name" required>
				</div>
				<div class="card-body text-dark">
					<input class="form-control mb-3" type="text" name="warden" placeholder="Camp Head">
					<textarea class="form-control min-h-100" name = "purpose" placeholder="Camp Purpose"></textarea>
					<textarea class="form-control min-h-100" name='demographic' placeholder="Camp Demographic"></textarea>
				</div>
				<div class="card-footer border-gray bg-gray">
					<input class="form-control" type="text" name='imageUrl' placeholder="Header Image URL (recommended height of 400px)">
				</div>
			</div>
		</div>

		<div class="container-fluid py-5">
			<div class="container card-group">
				<div class="card border-gray">
					<div class="card-body">
						<h5 class="card-title">Location</h5>
						<textarea class="form-control min-h-100" name="location" placeholder="City, State"></textarea>
					</div>
				</div>
				<div class="card border-gray">
					<div class="card-body">
						<h5 class="card-title">Dates of Operation</h5>
						<input class="form-control mb-3" type="date" name='start'>
						<input class="form-control mb-3" type="date" name='stop'>
					</div>
				</div>
				<div class="card border-gray">
					<div class="card-body">
						<h5 class="card-title">Number of Prisoners</h5>
						<input class="form-control mb-3" type="number" name='num'>
					</div>
				</div>
			</div>
		</div> <!-- End of camp cards -->

		<div class="form-group text-center">
			<input class="btn btn-primary" type="submit" value="Create Camp">
		</div>

		<div class="container-fluid bg-dark text-white py-5">
			<div class="container mt-5">
				<h3>Activities</h3>
				<input type="text" class="form-control mt-2 mb-2" name="activityName" placeholder="Title">
				<textarea class="form-control min-h-100 mb-2" name="activityDesc" placeholder="Description"></textarea>
				<h6 class="mb-4">*More activities can be added after creation<br></h6>
				<input class="btn btn-primary" type="submit" value="Create Camp">
			</div>
		</div>
	</form>
</div> <!-- End of container-fluid -->
