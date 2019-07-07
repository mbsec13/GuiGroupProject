<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$sc = new SiteController();
$sc->route($action);

class SiteController {

	// route to the appropriate class method for this action
	public function route($action) {
		switch($action) {

      case 'viewProfile':
        $user = $_GET['un'];
        $this->viewProfile($user);
        break;

      case 'settings':
        $user = $_GET['un'];
        $this->userSettings($user);
        break;

      case 'updateUser':
        $user = $_GET['un'];
        $update = $_GET['processAction'];
        $this->updateUser($user, $update);
        break;

			case 'viewFollowers':
				$user = $_GET['un'];
				$this->viewFollowers($user);
				break;

			case 'viewFollowing':
				$user = $_GET['un'];
				$this->viewFollowing($user);
				break;

			case 'promote':
				$user = $_GET['un'];
				$this->promote($user);
				break;

			case 'demote':
				$user = $_GET['un'];
				$this->demote($user);
				break;

			case 'follow':
				$user = $_GET['un'];
				$this->toggleFollow($user);
				break;

			case 'unfollow':
				$user = $_GET['un'];
				$this->toggleUnfollow($user);
				break;

			case 'report':
				$user = $_GET['un'];
				$this->report($user);
				break;

			case 'comment':
				$user = $_GET['un'];
				$this->addComment($user);
				break;

			case 'unfollowFromFollowing':
				$user = $_GET['un'];
				$this->unf($user);
				break;
    }
  }
  public function viewProfile($user){
		$currentUser = 0;
		if(isset($_SESSION['username'])){
    	$currentUser = User::load($_SESSION['username']);
		}
    $userData = User::load($user);
		if($userData === NO_RESULTS){
			$_SESSION['UserError'] = "Username ".$user. " does not exist.";
			header('Location: '.BASE_URL); exit();
		}
		else {
    $rankString;
    if ($userData->rank == 2){
      $rankString = "Registered User";
    }
    else if ($userData->rank == 3){
      $rankString = "Moderator";
    }
    else if ($userData->rank == 4){
      $rankString = "Admin";
    }
    else {
      $rankString = "Banned";
    }
		$following = -1;
		if(isset($_SESSION['username'])){
			$followArray = Follows::loadFollowees($_SESSION['username']);
			if ($followArray != NO_RESULTS){
				foreach($followArray as $f){
					if($f->username == $user){
						$following = 1;
						break;
					}
				}
			}
		}

		$userActivities = ActivityFeed::loadUserFeeds($user, 0, 10);
		$userComments = Comment::loadUserComments($user, 0, 10);
	}

    include_once SYSTEM_PATH.'/view/header.tpl';
    include_once SYSTEM_PATH.'/view/user.tpl';
    include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  public function userSettings($user){
    $userData = User::load($user);
    if($userData->username == $_SESSION['username']){
      include_once SYSTEM_PATH.'/view/header.tpl';
      include_once SYSTEM_PATH.'/view/userSettings.tpl';
      include_once SYSTEM_PATH.'/view/footer.tpl';
    }
    else{
      include_once SYSTEM_PATH.'/view/header.tpl';
      include_once SYSTEM_PATH.'/view/user.tpl';
      include_once SYSTEM_PATH.'/view/footer.tpl';
    }
  }

  public function updateUser($user, $update){
    $userData = User::load($user);

		/*
		Record a feed event for account update here, because the switch statement
		below has a header call for each case.  This way, the code is not repeated
		for each case.
		*/
		if (!is_string($userData)) {
			$activityFeed = new ActivityFeed(
				0,
				$_SESSION['username'],
				null,
				$userData->username,
				null,
				'edit_user',
				null
			);
			$activityFeedSuccess = $activityFeed->save();

			if (is_string($activityFeedSuccess)) {
				$_SESSION['updateUserError'] = $activityFeedSuccess;
				header('Location: '.BASE_URL.'/user/'.$userData->username.'/settings/'); exit();
			}
		}

    switch($update){
      case 'processbasic':
        $first = $_POST['first'];
        $last = $_POST['last'];
        $email = $_POST['email'];
        $pw = $_POST['password'];
        $cpw = $_POST['cPassword'];

        $userData->first = $first;
        $userData->last = $last;
        $userData->email = $email;

        if(!empty($pw)){
          if($pw != $cpw){
            $_SESSION['updateUserError'] = 'Passwords do not match';
            header('Location: '.BASE_URL.'/user/'.$userData->username.'/settings/'); exit();
          }
          else{
            $userData->pw = $pw;
          }
        }
        $success = $userData->update();
        if(!is_string($success)){
          header('Location: '.BASE_URL.'/user/'.$userData->username); exit();
        }
        else {
          $_SESSION['updateUserError'] = $success;
			    header('Location: '.BASE_URL.'/user/'.$userData->username.'/settings/'); exit();
				}
        break;

      case 'processbio':
        $bio = $_POST['about'];
        $url = $_POST['pictureURL'];

        $userData->bio = $bio;
        if (empty($url)){
          $url = BASE_URL."/public/img/profile-img.jpg";
        }
        $userData->imageUrl = $url;
        $success = $userData->update();
        if(!is_string($success)){
          header('Location: '.BASE_URL.'/user/'.$userData->username); exit();
        }
        else {
          $_SESSION['updateUserError'] = $success;
			    header('Location: '.BASE_URL.'/user/'.$userData->username.'/settings/'); exit();
				}
        break;

      case 'permissions':
        if (isset($_POST['name'])){
          $userData->nameHidden = 1;
        }
        else{
          $userData->nameHidden = -1;
        }
        if (isset($_POST['email'])){
          $userData->emailHidden = 1;
        }
        else{
          $userData->emailHidden = -1;
        }
        if (isset($_POST['dob'])){
          $userData->dateOfBirthHidden = 1;
        }
        else{
          $userData->dateOfBirthHidden = -1;
        }
        if (isset($_POST['gender'])){
          $userData->genderHidden = 1;
        }
        else{
          $userData->genderHidden = -1;
        }
        $success = $userData->update();
        if(!is_string($success)){
          header('Location: '.BASE_URL.'/user/'.$userData->username); exit();
        }
        else {
          $_SESSION['updateUserError'] = $success;
			    header('Location: '.BASE_URL.'/user/'.$userData->username.'/settings/'); exit();
				}
        break;
    }
  }

	public function viewFollowers($user){
		$followers = Follows::loadFollowers($user);
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/followers.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function viewFollowing($user){
		$following = Follows::loadFollowees($user);
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/following.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function promote($user){
			$userData = User::load($user);
			$userData->rank = ($userData->rank) + 1;
			$rankString;
			if ($userData->rank == 2){
				$rankString = "Registered User";
			}
			else if ($userData->rank == 3){
				$rankString = "Moderator";
			}
			else if ($userData->rank == 4){
				$rankString = "Admin";
			}
			else {
				$rankString = "Banned";
			}
			$success = $userData->update();
		  if (is_string($success)) {
		  	  // Failed to create event JSON
			  echo json_encode(array('success' => 'failure', 'error' => $success));
			  exit();
		  }

		  // Successful event creation JSON
		  echo json_encode(array('success' => 'success', 'rankString' => $rankString));
		  exit();
	  }

		public function demote($user){
				$userData = User::load($user);
				$userData->rank = ($userData->rank) - 1;
				$rankString;
		    if ($userData->rank == 2){
		      $rankString = "Registered User";
		    }
		    else if ($userData->rank == 3){
		      $rankString = "Moderator";
		    }
		    else if ($userData->rank == 4){
		      $rankString = "Admin";
		    }
		    else {
		      $rankString = "Banned";
		    }
				$success = $userData->update();
			  if (is_string($success)) {
			  	  // Failed to create event JSON
				  echo json_encode(array('success' => 'failure', 'error' => $success));
				  exit();
			  }

			  // Successful event creation JSON
			  echo json_encode(array('success' => 'success', 'rankString' => $rankString));
			  exit();
		  }

			public function toggleFollow($user){
				$newFollow = new Follows($_SESSION['username'] ,$user);
				$success = $newFollow->insert();
				if (is_string($success)) {
			  	  // Failed to create event JSON
				  echo json_encode(array('success' => 'failure', 'error' => $success));
				  exit();
			  }

				// Record a feed after the follow has occurred
				$activityFeed = new ActivityFeed(
					0,
					$_SESSION['username'],
					null,
					$user,
					null,
					'follow',
					null
				);
				$activityFeedSuccess = $activityFeed->save();
				if (is_string($activityFeedSuccess)) {
					echo json_encode(array('success' => 'failure', 'error' => $activityFeedSuccess));
				  exit();
				}

			  // Successful event creation JSON
			  echo json_encode(array('success' => 'success'));
			  exit();
		  }

			public function toggleUnfollow($user){
				$followArray = Follows::loadFollowees($_SESSION['username']);
				$success;
				foreach($followArray as $f){
					if($f->username == $user){
						$follower = new Follows($_SESSION['username'] ,$user);
						$success = $follower->delete();
						break;
					}
				}
				if (is_string($success)) {
			  	  // Failed to create event JSON
				  echo json_encode(array('success' => 'failure', 'error' => $success));
				  exit();
			  }

			  // Successful event creation JSON
			  echo json_encode(array('success' => 'success'));
			  exit();
		  }

			public function unf($user){
				$followArray = Follows::loadFollowees($_SESSION['username']);
				$success;
				foreach($followArray as $f){
					if($f->username == $user){
						$follower = new Follows($_SESSION['username'] ,$user);
						$success = $follower->delete();
						break;
					}
				}
				header('Location: '.BASE_URL.'/user/'.$_SESSION['username'].'/following/'); exit();
			}

			public function report($user){
				$reason = $_POST['reason'];
				$report = new Report(0, $_SESSION['username'], $user, $reason);
				$success = $report->save();
				if (is_string($success)) {
						// Failed to create event JSON
					echo json_encode(array('success' => 'failure', 'error' => $success));
					exit();
				}

				// Successful event creation JSON
				echo json_encode(array('success' => 'success'));
				exit();
			}

			public function addComment($user){
				$content = $_POST['comment'];
				$newComment = new Comment(0, $_SESSION['username'], null, null, $user, $content, null);
				$success = $newComment->save();
				if (is_string($success)) {
						// Failed to create event JSON
					echo json_encode(array('success' => 'failure', 'error' => $success));
					exit();
				}
				$feedEvent = new ActivityFeed(0, $_SESSION['username'], null, $user, null, "comment_user", null);
				$activitySuccess = $feedEvent->save();
				if(is_string($activitySuccess)){
					echo json_encode(array('success' => 'failure', 'error' => $activitySuccess));
					exit();
				}
				// Successful event creation JSON
				echo json_encode(array('success' => 'success'));
				exit();
			}

}
