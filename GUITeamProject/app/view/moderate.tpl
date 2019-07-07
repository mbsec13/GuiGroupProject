<!doctype html>
<html lang="en">
<head>
	<title>Moderation HIT</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {

			$('#mturk-form').submit(function(e) {
				e.preventDefault();

				var feedback = $('#mturk-textarea').val();
				var id = $('#mturk-hidden').val();

				if (feedback.length <= 100) {
					return;
				}
				console.log(feedback);

				var splt = feedback.split(" ");

				if (splt.length < 10) {
					for (var i = 0; i < splt.length; i++) {
						console.log(splt[i]);
					}
					alert("Please input valid feedback.")
					return;
				}

				$.ajax({
					url: '<?= BASE_URL ?>/crowd/process',
					type: 'POST',
					data: {id: id, feedback: feedback},
					success: function(data, textStatus, jqXHR) {
						$('body > *').hide();

						if (data.success == 'success') {
							$('body').append('<p>Your feedback has been successfully submitted.<br/>Your code is AezvafQktZClWNpMYfpS</p>');
						}
						else {
							$('body').append('<p>An error occurred: ' + data.error + '</p>');
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						$('body > *').hide();
						$('body').append('<p>An error occurred: ' + errorThrown + '</p>');
					},
					dataType: 'json'
				});
			});

		});
	</script>
</head>
<body>
	<div class="container">
		<h1>Instructions</h1>

		<?php if (isset($feedbackError)): ?>
			<!-- TODO:  HANDLE ERROR -->
			<p>Sorry, this HIT is currently unavailable because there is unreviewed content to moderate or an error has occurred.</p>
		<?php else: ?>
			<p>
				The website containing this information is a crowd-sourced website which acts as a database, containing information about German and Italian soldiers in United States prisoner-of-war camps. Due to the crowd-sourcing nature of the website, users may enter offensive or pointless information for key items (camps).
				<br><br>
				We have displayed contents of a random camp for you, so please view the contents of the camp carefully. If any content is not about the camp or it is offensive or pointless, please note it in your feedback. Otherwise, leave other feedback regarding the camp content. Your feedback must be at least 100 characters long.
				<br>
				*Some entries may be empty or invisible, in that case, that field has no input.
			</p> <!-- End of instructions -->

			<dl>
				<dt>Camp Name</dt>
				<dd><?= $camp->name ?></dd>

				<dt>Camp Image</dt>
				<dd><img class="no-aspect-t h-400" src="<?= $camp->imageUrl ?>" alt="Image of <?= $camp->name ?>"></dd>

				<dt>Camp Head</dt>
				<dd><?= $camp->warden ?></dd>

				<dt>Camp Purpose</dt>
				<dd><?= $camp->purpose ?></dd>

				<dt>Camp Location</dt>
				<dd><?= $camp->location ?></dd>

				<dt>Camp Operation</dt>
				<dd>
					Start: <?= $camp->dateBegan ?><br>
					End: <?= $camp->dateEnded ?>
				</dd>

				<dt>Camp Demographic</dt>
				<dd><?= $camp->demographic ?></dd>

				<dt>Camp Activities</dt>
				<?php if (is_array($activities)): ?>
					<?php foreach($activities as $activity): ?>
						<dd>
							Activity Type: <?= $activity->type ?><br>
							Activity Description: <?= $activity->description ?>
						</dd>
					<?php endforeach; ?>
				<?php endif; ?>
			</dl> <!-- End of worker's reading content -->

			<form id="mturk-form" action="<?= BASE_URL ?>/crowd/process/" method="POST">
				<h1>Feedback</h1>
				<textarea id="mturk-textarea" required class="w-100 min-h-400 form-control mb-2" name="feedback" minlength=100 placeholder="Your feedback here..."></textarea>
				<input id="mturk-hidden" name="id" type="hidden" value="<?= $camp->id ?>"/>
				<input id="mturk-submit-btn" class="btn btn-success mb-5 w-100" type="submit" value="Submit">
			</form> <!-- End of mturk-form -->
		<?php endif; ?>
	</div>
</body>
</html>
