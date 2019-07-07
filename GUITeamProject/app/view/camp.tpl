<div class="container-fluid px-0 main-content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ul class="breadcrumb bg-white">
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= BASE_URL ?>/camps">Camps</a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $camp->name ?></li>
			</ul>
		</nav> <!-- End of breadcrumb -->
	</div>

	<!-- Placeholder heading image -->
	<div id="imageUrl">
	<img class="no-aspect-t h-400" src="<?= $camp->imageUrl ?>" alt="photo of Camp">
	</div>
	<div class="container">
		<div class="card border-gray camp-card mb-5 mx-auto ">
			<div class="card-header border-gray">
				<h1 id="campName"><?= $camp->name ?></h1>
				<h6 id="campCreator">Added by <a href="<?= BASE_URL ?>/user/<?= $camp->creator ?>"><?= $camp->creator ?></a></h6>
			</div>
			<div class="card-body text-dark">
				<p class="card-text" id="wardenAndPurpose">
					<?= $camp->warden ?>
					<br>
					<?= $camp->purpose ?>
				</p>
			</div>
			<?php if(isset($_SESSION['username'])): ?>
				<div>
					<div class="card-footer border-gray bg-gray text-center">
						<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#header-card-person-form" aria-expanded="false" aria-controls="header-card-person-form">Edit</button>
						<div id="header-card-person-form" class="collapse">
							<form class="text-center" id="editCampForm1">
								<input type="text" class="form-control my-2" placeholder="Camp Name" id="campNameInput">
								<input type="text" class="form-control mb-2" placeholder="Camp Head" id="campHeadInput">
								<textarea class="form-control min-h-100 mb-3" placeholder="Camp Purpose" id="campPurposeInput"></textarea>
								<input type="text" class="form-control mb-2" placeholder="URL" id="campUrl">
								<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#header-card-person-form" aria-expanded="true" aria-controls="header-card-person-form">
							</form>
						</div>
					</div>
				</div> <!-- End of edit div -->
			<?php endif ?>
		</div>
	</div> <!-- End of camp header card container -->

	<div class="container-fluid py-5">
		<div class="container card-group">
			<div class="card border-gray">
				<div class="card-body">
					<h2 class="card-title">Location</h2>
					<p class="card-text" id="campLocation">
						<?= $camp->location ?>
					</p>
				</div>
				<?php if(isset($_SESSION['username'])): ?>
					<div>
						<div class="card-footer border-gray bg-gray text-center">
							<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#location-card-person-form" aria-expanded="false" aria-controls="location-card-person-form">Edit</button>
							<div id="location-card-person-form" class="collapse">
								<form class="text-center" id="editLocationForm">
									<textarea class="form-control min-h-100 mt-2 mb-3" placeholder="City, State" id="campLocationInput"></textarea>
									<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#location-card-person-form" aria-expanded="true" aria-controls="location-card-person-form">
								</form>
							</div>
						</div>
					</div> <!-- End of edit div -->
				<?php endif ?>
			</div>
			<div class="card border-gray">
				<div class="card-body">
					<h2 class="card-title">Operation</h2>
					<p class="card-text" id="dateBeginAndEnd">
						Start: <?= $camp->dateBegan ?>
						<br>
						Stop: <?= $camp->dateEnded ?>
					</p>
				</div>
				<?php if(isset($_SESSION['username'])): ?>
					<div>
						<div class="card-footer border-gray bg-gray text-center">
							<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#operation-card-person-form" aria-expanded="false" aria-controls="operation-card-person-form">Edit</button>
							<div id="operation-card-person-form" class="collapse">
								<form class="text-center" id="dateBeginAndEndForm">
									<input class="form-control mt-2 mb-3" type="date" id="dateBegin">
									<input class="form-control mt-2 mb-3" type="date" id="dateEnd">
									<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#operation-card-person-form" aria-expanded="true" aria-controls="operation-card-person-form">
								</form>
							</div>
						</div>
					</div> <!-- End of edit div -->
				<?php endif ?>
			</div>
			<div class="card border-gray">
				<div class="card-body">
					<h2 class="card-title">Demographic</h2>
					<p class="card-text" id="demographicText">
						<?= $camp->demographic ?>
					</p>
				</div>
				<?php if(isset($_SESSION['username'])): ?>
					<div>
						<div class="card-footer border-gray bg-gray text-center">
							<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#demo-card-person-form" aria-expanded="false" aria-controls="demo-card-person-form">Edit</button>
							<div id="demo-card-person-form" class="collapse">
								<form class="text-center" id="demographicForm">
									<textarea class="form-control min-h-100 mt-2 mb-3" id="demographicInput"></textarea>
									<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#demo-card-person-form" aria-expanded="true" aria-controls="demo-card-person-form">
								</form>
							</div>
						</div>
					</div> <!-- End of edit div -->
				<?php endif ?>
			</div>
		</div>
	</div> <!-- End of camp cards -->

	<div class="container-fluid bg-dark text-white py-5">
		<div class="container mt-5">
			<h2>Activities</h2>
			<?php if ($activities === NO_RESULTS): ?>
				<div class="mx-auto emptyMessage">
					<div class="alert alert-info">
						We could not find any activities for this camp.
					</div>
				</div>
			<?php elseif (is_string($activities)): ?>
				<div class="mx-auto">
					<div class="alert alert-danger">
						<?= $activities ?>
					</div>
				</div>
			<?php else: ?>
				<?php foreach($activities as $act): ?>
					<div>
						<h5><?= $act->type ?></h5>
						<p><?= $act->description ?></p>
					</div>
				<?php endforeach ?>
			<?php endif; ?>

			<div id='appendMe'></div>
			<?php if(isset($_SESSION['username'])): ?>
				<button class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#activities-form" aria-expanded="false" aria-controls="activities-form" id="activityPrependMe">Add New Activity</button>
				<div id="activities-form" class="collapse">
					<form class="text-center" id="typeAndDescriptionForm">
						<input type="text" class="form-control mt-2 mb-2" id="titleInput" placeholder="Title">
						<textarea class="form-control min-h-100 mb-2" id="descriptionInput" placeholder="Description"></textarea>
						<input class="btn btn-primary" type="submit" value="Confirm" data-toggle="collapse" data-target="#activities-form" aria-expanded="true" aria-controls="activities-form">
					</form>
				</div>
			<?php endif ?>
		</div>
	</div> <!-- End of activities container -->

	<div class="container py-5 text-center">
		<?php if(isset($_SESSION['username']) && $_SESSION['rank'] >= 3): ?>
			<button class="btn btn-danger text-uppercase mt-5" data-toggle="collapse" data-target="#delete-camp-form" aria-expanded="false" aria-controls="delete-camp-form">Delete Camp</button>
			<div id="delete-camp-form" class="collapse mt-2">
				<form id="removeCamp">
					<label>Are you sure you want to delete this camp?</label>
					<div>
						<!--IF THE VALUE == YES THEN CHANGE WINDOW LOCATION TO CAMPS-->
						<input type="submit" class="btn btn-danger text-uppercase" data-toggle="collapse" data-target="#delete-camp-form" aria-expanded="false" aria-controls="delete-camp-form" value="Yes">
						<input type="button" class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#delete-camp-form" aria-expanded="false" aria-controls="delete-camp-form" value="No">
					</div>
				</form>
			</div>
		<?php endif ?>
	</div>

	<div id="comments-div" class="mb-4 container">
		<div class="title-box">
			<h2>Comments</h2>
		</div>
		<div id="comment-box" class="message-box border-1-s border-gray h-300 mb-3">
			<ul id="comments-list">
				<?php if($campComments === NO_RESULTS): ?>
					<li>
						<div class="alert alert-info">
							This camp hasn't received any comments yet.
						</div>
					</li>
				<?php else: ?>
					<?php foreach($campComments as $comment): ?>
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
		</div>
		<div>
			<form id="comment-form">
				<input type="text" name="comment" id="submitCommentValue" class="form-control mb-2" placeholder="Enter comment here">
				<input type="submit" class="btn btn-success" value="Comment">
			</form>
		</div>
	</div> <!-- End of comments-div -->
</div> <!-- End of container-fluid -->
