<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/prisoners">Prisoners</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $prisoner->name ?></li>
			</ul>
		</nav> <!-- End of breadcrumb -->
	</div>
	<div class="container">
		<div class="row profile">
			<div class="col-md-3">
				<div class="profile-sidebar mb-5">
					<div class="profile-person-pic">
						<img id="prisonerImage" class="img-fluid rounded-circle" src="<?= $prisoner->imageUrl ?>" alt="Default profile img">
					</div>
					<div class="profile-person-title">
						<div class="profile-person-name" id="prisonerName">
							<h2 class="mb-2"><?= $prisoner->name ?></h2>
						</div>
						<h6 id="prisCreator">Added by <a href="<?= BASE_URL ?>/user/<?= $prisoner->creator ?>"><?= $prisoner->creator ?></a></h6>
						<div class="profile-person-secondary">
							<?= $prisoner->rank ?>
						</div>
					</div>
					<div class="profile-person-menu">
						<ul class="nav flex-column">
							<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#camps-collapse" role="button" aria-expanded="true" aria-controls="camps-collapse">Camps</a></li>
							<li>
								<div id="camps-collapse" class="collapse">
									<?php if ($camps === NO_RESULTS): ?>
										<div class="mx-auto emptyMessage container">
											<div class="alert alert-info">
												This prisoner is not linked to any camps.
											</div>
										</div>
									<?php elseif (is_string($camps)): ?>
										<div class="mx-auto">
											<div class="alert alert-danger">
												<?= $camps ?>
											</div>
										</div>
									<?php else: ?>
										<?php foreach($camps as $camp): ?>
											<p class="side-menu-p ml-4">
												<a href="<?= BASE_URL ?>/camps/<?= $camp->id ?>"><?= $camp->name ?></a><br>
											</p>
										<?php endforeach ?>
									<?php endif; ?>
								</div>
							</li>
							<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#dates-collapse" role="button" aria-expanded="true" aria-controls="dates-collapse">Dates</a></li>
							<li>
								<div id="dates-collapse" class="collapse">
									<p class="side-menu-p ml-4">
										Date of Birth: <?= $prisoner->dateOfBirth ?>
										<br>
										Date of Death: <?= $prisoner->dateOfDeath ?>
									</p>
								</div>
							</li>
							<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#origin-collapse" role="button" aria-expanded="true" aria-controls="origin-collapse">Origin</a></li>
							<li>
								<div id="origin-collapse" class="collapse">
									<p class="side-menu-p ml-4">
										<?= $prisoner->countryOfOrigin ?>
									</p>
								</div>
							</li>
						</ul>
					</div>
					<?php if(isset($_SESSION['username'])): ?>
						<div class="profile-person-buttons">
							<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#edit-person-form" aria-expanded="false" aria-controls="edit-person-form">Edit</button>

							<?php if(isset($_SESSION['username'])): ?>
								<button class="btn btn-danger text-uppercase" data-toggle="collapse" data-target="#delete-person-form" aria-expanded="false" aria-controls="delete-person-form">Delete Prisoner</button>
							<?php endif; ?>

							<div id="edit-person-form" class="collapse mt-2 container">
								<form id='editPersonForm'>
									<div class="form-group text-center mt-2">
										<label>New Image URL</label>
										<input type='text' class="form-control" id='editImage'>
									</div>
									<div class="form-group text-center mt-2">
										<label>Prisoner Name</label>
										<input type='text' class="form-control" id='editName'>
									</div>
									<div class="form-group text-center mt-2">
										<label>Camps</label>
										<input type='text' class="form-control" id='addNewCamps'>
									</div>
									<div class="form-group text-center mt-2">
										<label>Date of Birth</label>
										<input type="date" class="form-control" id="editDofBirth">
									</div>
									<div class="form-group text-center mt-2">
										<label>Date of Death</label>
										<input type="date" class="form-control" id="editDofDeath">
									</div>
									<div class="form-group text-center mt-2">
										<label>Country of Origin</label>
										<input type='text' class="form-control" id='editOrigin'>
									</div>
									<div class="form-group text-center mt-2">
										<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#edit-person-form" aria-expanded="false" aria-controls="edit-person-form">
									</div>
								</form>
							</div>
							<div id="delete-person-form" class="collapse mt-2">
								<form id="removePrisoner">
									<label>Are you sure you want to delete this prisoner?</label>
									<div>
										<input type="submit" class="btn btn-danger text-uppercase" data-toggle="collapse" data-target="#delete-person-form" aria-expanded="false" aria-controls="delete-person-form" value="Yes">
										<input type="button" class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#delete-person-form" aria-expanded="false" aria-controls="delete-person-form" value="No">
									</div>
								</form>
							</div>
						</div>
					<?php endif ?>
				</div>
			</div>
			<div class="col-md-9">
				<div id="prisonerEventAdd">
					<div id='eventsList'>
						<h2 class="mb-2">Events</h2>
						<?php if ($events === NO_RESULTS): ?>
							<div class="mx-auto emptyMessage">
								<div class="alert alert-info">
									There are no events yet for this prisoner.
								</div>
							</div>
						<?php elseif (is_string($events)): ?>
							<div class="mx-auto">
								<div class="alert alert-danger">
									<?= $events ?>
								</div>
							</div>
						<?php else: ?>
							<?php foreach($events as $event): ?>
								<div>
									<h5 class="mb-0"><?= $event->dateHappened ?>: <?= $event->title ?></h5>
									<p><?= $event->description ?></p>
								</div> <!-- End of event -->
							<?php endforeach ?>
						<?php endif;?>
					</div>
					<?php if(isset($_SESSION['username'])): ?>
						<button class="btn btn-success text-uppercase mb-3" id='displayAddEventForm' data-toggle="collapse" data-target="#events-person-form" aria-expanded="false" aria-controls="events-person-form">Add New Event</button>
						<div id="events-person-form" class="collapse">
							<form class="text-center" id='addEventPrisoner'>
								<!-- Adding required property doesn't stop collapse from occuring -->
								<input type="date" id='prisonerUpdateDate' class="form-control mb-2">
								<input type="text" id='prisonerUpdateTitle' class="form-control mb-2" placeholder="Event Title" required>
								<textarea class="form-control min-h-100 mb-3" id='prisonerUpdateDetails' placeholder="Details"></textarea>
								<input class="btn btn-primary" type="submit" id='btnEditSubmit' value="Confirm" data-toggle="collapse" data-target="#events-person-form" aria-expanded="true" aria-controls="events-person-form">
							</form>
						</div>
					<?php endif ?>
				</div>
				<div id="comments-div" class="mb-4">
					<div class="title-box">
						<h2 class="mb-2">Comments</h2>
					</div>
					<div id="comment-box" class="message-box border-1-s border-gray h-300 mb-3">
						<ul id="comments-list">
							<?php if($prisComments === NO_RESULTS): ?>
								<li>
									<div class="alert alert-info">
										This prisoner hasn't received any comments yet.
									</div>
								</li>
							<?php else: ?>
								<?php foreach($prisComments as $comment): ?>
									<li class="mb-3">
										<div class="message">
											<div class="message-user-img">
												<img class="img rounded-circle h-30" src="<?= $comment->getCommentUserImage() ?>" alt="Default user image">
											</div>
											<div class="message-main">
												<a href="<?= BASE_URL ?>/user/<?= $comment->username ?>" class="message-user">
													<?= $comment->username ?>
												</a>
												<p class="message-text">
													<?= $comment->content ?>
													<br>
													<?php if($comment->username == $_SESSION['username']): ?>
													<a href="<?= BASE_URL ?>/deleteComment/<?= $comment->id ?>" class="message-footer">Delete</a>
												<?php endif ?>
												</p>
											</div>
										</div>
									</li> <!-- End of a comment -->
								<?php endforeach ?>
							<?php endif ?>
						</ul> <!-- End of comments list -->
					</div> <!-- End of comment-box -->
					<div>
						<form id="comment-form">
							<input type="text" name="comment" class="form-control mb-2" id="submitCommentValue" placeholder="Enter comment here">
							<input type="submit" class="btn btn-success" value="Comment">
						</form>
					</div>
				</div> <!-- End of comments-div -->
			</div>
		</div>
	</div>
</div> <!-- End of container-fluid -->
