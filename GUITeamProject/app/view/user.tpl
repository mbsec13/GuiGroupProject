<div class="container-fluid px-0 main-content">
  	<div class="container">
    	<nav aria-label="breadcrumb">
      		<ul class="breadcrumb bg-white">
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Users</a></li>
        		<li class="breadcrumb-item active" aria-current="page"><?= $user ?></li>
      		</ul>
    	</nav> <!-- End of breadcrumb -->
  	</div>

  	<div class="container">
    	<div class="row profile">
      		<div class="col-md-3">
        		<div class="profile-sidebar mb-5">
          			<div class="profile-person-pic">
            			<img class="img-fluid rounded-circle" src="<?= $userData->imageUrl ?>" alt="Default user image">
          			</div>
          			<div class="profile-person-title">
            			<div class="profile-person-name">
              				<h2 class="mb-2"><?= $userData->username ?></h2>
            			</div>
            			<?php if ($userData->nameHidden == 1): ?>
            				<div class="profile-person-secondary">
              					<?= $userData->first ?> <?= $userData->last ?>
            				</div>
            			<?php endif ?>
          			</div>
          			<div class="profile-person-buttons">
            			<?php if((isset($_SESSION['username'])) && ($currentUser->username != $userData->username)): ?>
            				<?php if($following == -1): ?>
				              	<button class="btn btn-success" id="followUser">Follow</button>
				              	<button class="btn btn-danger" id="unfollowUser" style="display: none;">Unfollow</button>
				            <?php else: ?>
				              <button class="btn btn-success" id="followUser" style="display: none;">Follow</button>
				              <button class="btn btn-danger" id="unfollowUser">Unfollow</button>
            				<?php endif ?>
            			<?php endif ?>
					</div>
          			<div class="profile-person-menu">
            			<ul class="nav flex-column">
              				<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#rank-collapse" role="button" aria-expanded="true" aria-controls="rank-collapse"><i class="fas fa-user"></i> Rank</a></li>
              				<li class="side-menu-p ml-4">
                				<div id="rank-collapse" class="collapse">
				                  	<p id="rc-content">
				                    	<!-- Should load in string based on rank, not hard coded -->
				                    	<?= $rankString ?>
				                  	</p>
                  					<?php if((isset($_SESSION['username'])) && ($currentUser->rank == 4)): ?>
                    					<?php if($userData->rank != 4): ?>
                      						<button id="promote-btn" class="btn btn-success">
	                        					<?php if($userData->rank == 1): ?>
	                        						Un-Ban
	                        					<?php else: ?>
	                        						Promote
	                        					<?php endif ?>
                        					</button>
                    					<?php endif ?>
                						<?php if($userData->rank != 1): ?>
                  							<button id="demote-btn" class="btn btn-danger">
                    							<?php if($userData->rank == 2): ?>
                    								Ban
                    							<?php else: ?>
                    								Demote
                    							<?php endif ?>
                    						</button>
                    					<?php endif ?>
                  					<?php endif ?>
                				</div>
              				</li>
              				<li><a class="dropdown-toggle ml-4" data-toggle="collapse" href="#info-collapse" role="button" aria-expanded="true" aria-controls="info-collapse"><i class="fas fa-info-circle"></i> Information</a></li>
              				<li>
                				<div id="info-collapse" class="collapse">
                  					<p class="side-menu-p ml-4">
                    					<?php if ($userData->dateOfBirthHidden == 1): ?>
                    						Date of Birth: <?= $userData->dateOfBirth ?><br>
                    					<?php endif ?>
                    					<?php if ($userData->genderHidden == 1): ?>
                    						Gender: <?= $userData->gender ?><br>
                    					<?php endif ?>
                    					<?php if ($userData->emailHidden == 1): ?>
                    						Email: <?= $userData->email ?>
                    					<?php endif ?>
                  					</p>
                				</div>
              				</li>
              				<li><a class="ml-4" href="<?= BASE_URL ?>/user/<?= $userData->username ?>/followers"><i class="fas fa-users"></i> Followers</a></li>
              				<li class="mb-3"><a class="ml-4" href="<?= BASE_URL ?>/user/<?= $userData->username ?>/following"><i class="fas fa-address-book"></i> Following</a></li>
              				<?php if((isset($_SESSION['username'])) && ($currentUser->username == $userData->username)): ?>
                				<li><a class="ml-4" href="<?= BASE_URL ?>/user/<?= $userData->username ?>/settings"><i class="fas fa-cogs"></i> User Settings</a></li>
              				<?php endif ?>
              				<li><a class="ml-4 dropdown-toggle ml-4" data-toggle="collapse" href="#report-collapse" role="button" aria-expanded="true" aria-controls="report-collapse"><i class="fas fa-flag"></i> Report</a></li>
                            <li id="resultDisplay" style="display: none;"><p class="side-menu-p ml-4">User has been reported.</p></li>
              				<li class="side-menu-p ml-4">
                				<div id="report-collapse" class="collapse container">
                  					<form id="reportUser">
                    					<label>Are you sure you want to report this person?</label>
  										<textarea class="form-control min-h-100 mb-2" name = "reportBox" placeholder="Reason for reporting" id="reasonForReporting"></textarea>
                    					<div id="report-buttons">
					                      	<input type="submit" class="btn btn-danger text-uppercase" data-toggle="collapse" data-target="#report-collapse" aria-expanded="false" aria-controls="report-collapse" value="Yes">
					                      	<input type="button" class="btn btn-success text-uppercase" data-toggle="collapse" data-target="#report-collapse" aria-expanded="false" aria-controls="report-collapse" value="No">
                    					</div>
                  					</form>
                				</div>
              				</li>
            			</ul>
          			</div>
        		</div>
      		</div> <!-- End of sidebar container -->

      		<div class="col-md-9">
        		<div class="about-container" class="mb-4">
          			<h2 class="mb-2">Bio</h2>
          			<p style="word-break: break-all;">
            			<?= $userData->bio ?>
          			</p>
        		</div>

        		<div id="activity-feed-div" class="mb-4">
          			<div class="title-box">
            			<h2 class="mb-2">Activity Feed</h2>
         			 </div>
          			<div id="activity-box" class="message-box border-1-s border-gray h-300">
            			<ul id="activity-list">
              				<?php if($userActivities === NO_RESULTS): ?>
                                <li>
                  				  <div class="alert alert-info">
                    					This user hasn't done anything yet.
                  				  </div>
                                </li>
              				<?php else: ?>
              					<?php foreach($userActivities as $acts): ?>
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

        		<div id="comments-div" class="mb-4">
          			<div class="title-box">
            			<h2 class="mb-2">Comments</h2>
          			</div>
          			<div id="comment-box" class="message-box border-1-s border-gray h-300 mb-3">
            			<ul id="comments-list">
	              			<?php if($userComments === NO_RESULTS): ?>
                                <li>
	                			    <div class="alert alert-info">
	                  			        This user hasn't received any comments yet.
	                			    </div>
                                </li>
	              			<?php else: ?>
	              				<?php foreach($userComments as $comment): ?>
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
	                      							<?php if($comment->profileId == $_SESSION['username'] || $comment->username == $_SESSION['username']): ?>
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
              				<input type="text" name="comment" class="form-control mb-2" placeholder="Enter comment here" id="submitCommentValue">
              				<input type="submit" class="btn btn-success" value="Comment">
            			</form>
          			</div>
        		</div> <!-- End of comments-div -->
      		</div>
    	</div>
  	</div>
</div> <!-- End of container-fluid -->