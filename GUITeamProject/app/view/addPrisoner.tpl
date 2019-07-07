<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/prisoners">Prisoner</a></li>
				<li class="breadcrumb-item active" aria-current="page">John Doe</li>
			</ul>
		</nav> <!-- End of breadcrumb -->

		<?php if (isset($_SESSION['addPrisonerError'])): ?>
			<div class="alert alert-danger">
				<strong>Error:</strong> <?= $_SESSION['addPrisonerError'] ?>
			</div>
		<?php unset($_SESSION['addPrisonerError']); endif; ?>
	</div>
	<div class="container">
		<form method="POST" action="<?= BASE_URL ?>/prisoners/add/process">
			<div class="row profile">
				<div class="col-md-3">
					<div class="profile-sidebar mb-5">
						<div class="profile-person-pic">
							<img class="img-fluid rounded-circle" src="https://www.guarddome.com/assets/images/profile-img.jpg" alt="Default profile img">
						</div>
						<input type="text" class="form-control" name="image" placeholder="Picture URL">
						<div class="profile-person-title">
							<div class="profile-person-name">
								<input type="text" class="form-control" name="name" placeholder="Name" required>
							</div>
							<div class="profile-person-secondary">
								<input type="text" class="form-control" name="rank" placeholder="Rank">
							</div>
						</div>
						<div class="profile-person-menu">
							<ul class="nav flex-column">
								<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#camps-collapse" role="button" aria-expanded="true" aria-controls="camps-collapse">Camps</a></li>
								<li>
									<div id="camps-collapse" class="show">
										<div class="form-group text-center mt-2">
											<input type="text" class="form-control" name="camp">
										</div>
									</div>
								</li>
								<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#dob-collapse" role="button" aria-expanded="true" aria-controls="dob-collapse">Date of Birth</a></li>
								<li>
									<div id="dob-collapse" class="show">
										<div class="form-group text-center mt-2">
											<input type="date" class="form-control" name="dob">
										</div>
									</div>
								</li>
								<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#dod-collapse" role="button" aria-expanded="true" aria-controls="dod-collapse">Date of Death</a></li>
								<li>
									<div id="dod-collapse" class="show">
										<div class="form-group text-center mt-2">
											<input type="date" class="form-control" name="dod">
										</div>
									</div>
								</li>
								<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#origin-collapse" role="button" aria-expanded="true" aria-controls="origin-collapse">Country of Origin</a></li>
								<li>
									<div id="origin-collapse" class="show">
										<div class="form-group text-center mt-2">
											<input type="text" class="form-control" name="origin">
										</div>
									</div>
								</li>
								<li><a class="ml-4" href="#!">Photos</a></li>
							</ul>
						</div> <!-- End of profile-person-menu -->
					</div> <!-- End of profile-sidebar -->
				</div>
				<div class="col-md-9">
					<h3>Events</h3>
					<!--
						Design decision:
						Having the ability to add numerous events in a person creation page encourages a model in which
						saving is sparse, which leads to a potential of loss of all data. The current design decision allows
						user work to be saved constantly, so probability of work loss is low.
					 -->
					<div>
						<input type="date" class="form-control mb-2" name="eventDate">
						<input type="text" class="form-control mb-2" name="eventName" placeholder="Event Title">
						<textarea class="form-control min-h-100 mb-3" name="eventDetails" placeholder="Details"></textarea>
					</div>
					<h6 class="mb-4">*More events can be added after creation<br></h6>
					<input class="btn btn-primary" type="submit" value="Create Person">
				</div>
			</div>
		</form> <!-- End of form -->
	</div>
</div> <!-- End of container-fluid -->
