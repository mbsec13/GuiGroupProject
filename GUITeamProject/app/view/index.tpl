
<div class="container-fluid px-0" style="min-height: 100vh">
	<div>
		<?php if (isset($_SESSION['UserError'])): ?>
			<div class="mx-auto">
				<div class="alert alert-danger">
					<strong>Error:</strong> <?= $_SESSION['UserError'] ?>
				</div>
			</div>
		<?php unset($_SESSION['UserError']); endif; ?>
	</div>

	<div id="index-main-content" class="container pt-5 text-center hidden" style="min-height: 90vh;">
		<div class="hidden">
			<h1>Axis Prisoners of War</h1>
			<h2>Learn about the experiences of Axis Prisoners of War detained in POW camps across America</h2>
			<a href="<?= BASE_URL ?>/login/" class="btn btn-outline-dark btn-round fs-16 border-1-s mb-5">GET STARTED</a>
		</div>
		
		<div class="text-center">
			<img class="mb-5 img-fluid" src="<?= BASE_URL ?>/public/img/index1.png" alt="Homepage image 1">
		</div>
		<a id="index-main-scroller" href="#index-intro"><i class="fas fa-chevron-down" style="font-size: 32px; margin-top: 50px" ></i></a>
	</div>
	
	<div id="index-intro" class="hidden container-fluid bg-darkgray text-white mb-5" style="padding-top: 200px;">
		<div class="container mb-5">
			<h2>Introduction</h2>
			<p>
				The Axis POW program grew to 425,000 prisoners over the years of the war. Prisoners were mostly being held in Britain and Africa before being brought over to America. Prisoners entered camps and were forced to give up their possessions, many of which were misplaced during the duration of the war and never returned to the right owner. While in the camps, prisoners participated in recreational activities, the labor program, and a reeducation program. While in America, the Axis POWs had many different interactions with American military and civilian persons. There were two types of Germans in POW programs: ones who were willing to give up their Nazism and those who were not and were strict on those who did for American traditions. Overall, America’s treatment of Axis Prisoners of War benefited both America and the Axis countries, Germany and Italy, by giving the Americans manpower for indirect war industries and then sent them back to their country healthy and strong in order to be able to enter the workforce back home. Each camp had their own unique events based on the makeup of the camp and the organization of the camp’s activities.
			</p>

			<div class="text-center" style="padding-top: 100px;">
				<a id="index-intro-scroller" href="#index-video"><i class="fas fa-chevron-down mb-5" style="font-size: 32px; margin-top: 50px" ></i></a>
			</div>
		</div>
	</div>

	<div id="index-video" class="hidden container">
		<iframe class="w-100 mb-4" height="500"
			src="https://www.youtube.com/embed/Tk92mdn3Ans">
		</iframe>
	</div>
	
	<div class="container">
		<?php if(isset($_SESSION['username'])): ?>
			<div id="activity-feed-div" class="mb-4 hidden">
				<div class="title-box">
					<h3>Activity Feed</h3>
				</div>
				<div id="activity-box" class="message-box border-1-s border-gray h-300">
					<ul id="activity-list">
						<?php if($followeeActivities === NO_RESULTS): ?>
							<div class="alert alert-info">
								This user hasn't done anything yet.
							</div>
						<?php else: ?>
							<?php foreach($followeeActivities as $acts): ?>
								<li class="mb-3">
									<div class="message">
										<div class="message-main">
											<?php if($acts->entityName == NULL): ?>
												<?php if($acts->getAction() != 'created an account' && $acts->getAction() != 'edited their profile'): ?>
													<?php $type = $acts->getDestType() ?>
													<?php if($type != 'user'): ?>
														<?php if($acts->getEntity() != NULL): ?>
															<?php $entity = $acts->getEntity() ?>
															<p class="message-text">
																[<?= time_elapsed_string($acts->happened) ?>] <?= $acts->username ?> <?= $acts->getAction() ?> <a href="<?= BASE_URL ?>/<?= $type ?>/<?= $acts->entityId ?>"><?= $entity ?></a>
															</p>
														<?php endif ?>
													<?php else: ?>
														<p class="message-text">
															[<?= time_elapsed_string($acts->happened) ?>] <?= $acts->username ?> <?= $acts->getAction() ?> <a href="<?= BASE_URL ?>/<?= $type ?>/<?= $acts->otherUser ?>"><?= $acts->otherUser ?></a>
														</p>
													<?php endif ?>
												<?php else: ?>
													<p class="message-text">
														[<?= time_elapsed_string($acts->happened) ?>] <?= $acts->username ?> <?= $acts->getAction() ?>
													</p>
												<?php endif ?>
											<?php else: ?>
												<p class="message-text">
													[<?= time_elapsed_string($acts->happened) ?>] <?= $acts->username ?> deleted <?= $acts->entityName ?>
												</p>
											<?php endif ?>
										</div>
									</div>
								</li> <!-- End of a message -->
							<?php endforeach ?>
						<?php endif ?>
					</ul>
				</div>
			</div> <!-- End of activity-feed-div -->
		<?php endif ?>
	</div>
</div> <!-- End of container-fluid -->
