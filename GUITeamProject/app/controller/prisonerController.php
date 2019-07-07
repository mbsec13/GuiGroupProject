<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$sc = new PrisonerController();
$sc->route($action);

class PrisonerController {

	// route to the appropriate class method for this action
	public function route($action) {
		switch($action) {

      case 'addPrisProcess':
        $this->newPrisoner();
        break;

      case 'viewPrisoner':
      	$id = $_GET['id'];
        $this->viewPrisoner($id);
        break;

	  case 'deletePrisoner':
	  	$id = $_GET['id'];
		$this->deletePrisoner($id);
		break;

		case 'deletefrmlist':
			$id = $_GET['id'];
			$this->deleteFromList($id);
			break;

	  case 'addPrisonerEvent':
  	  	$id = $_GET['id'];
  		$this->addPrisonerEvent($id);
  		break;

	  case 'editPrisoner':
  	  	$id = $_GET['id'];
  		$this->editPrisoner($id);
  		break;

		case 'comment':
			$id = $_GET['id'];
			$this->addComment($id);
			break;
    }
  }

  /**
   * Create a prisoner in the database
   */
  public function newPrisoner(){
  	// Get POST variables
    $name = $_POST['name'];
    $rank = $_POST['rank'];
    $dateOfBirth = $_POST['dob'];
    $dateOfDeath = $_POST['dod'];
		$camp = $_POST['camp'];
    $origin = $_POST['origin'];
	$imageUrl = $_POST['image'];

    if ($name == "") { // If user didn't properly access add prisoner page, redirect
      header('Location: '.BASE_URL); exit();
    }

    // Get more POST variables
    $eventDate = $_POST['eventDate'];
    $eventName = $_POST['eventName'];
    $details = $_POST['eventDetails'];

    // Notifications for required inputs
    if(empty($name)) {
			$_SESSION['addPrisonerError'] = 'You must provide the prisoner\'s name.';
      header('Location: '.BASE_URL.'/prisoners/add'); exit();
    }
		if( empty($eventDate) && !empty($eventName) ) {
			$_SESSION['addPrisonerError'] = 'You must provide a valid event date.';
			header('Location: '.BASE_URL.'/prisoners/add'); exit();
		}
		if( !empty($eventDate) && empty($eventName) ) {
			$_SESSION['addPrisonerError'] = 'You must provide a valid event title.';
			header('Location: '.BASE_URL.'/prisoners/add'); exit();
		}

		// Creating instance of new prisoner
	if (empty($imageUrl)) $imageUrl = null;
    $prisoner = new Prisoner(0, $name, $rank, $dateOfBirth, $dateOfDeath, $origin, $_SESSION['username'], $imageUrl);

    // Saving prisoner related objects
    $prisSuccess = $prisoner->save();
    if (!is_string($prisSuccess)){
			if( !empty($eventDate) && !empty($eventName) ) {
				$event = new Event(
					0,
					Prisoner::loadIdByName($name),
					$eventName,
					$eventDate,
					$details
				);
        $event->save();
      }
			$campId = Camp::loadIdByName($camp);
			if (is_numeric($campId)){
				$housing = new Housing($prisSuccess, $campId);
				$hSuccess = $housing->insert();
			}

			// Record the edition of a new prisoner
			$activityFeed = new ActivityFeed(
				0,
				$_SESSION['username'],
				$prisSuccess,
				null,
				null,
				'add_prisoner',
				null
			);
			$activityFeedSuccess = $activityFeed->save();

      header('Location: '.BASE_URL.'/prisoners'); exit();
    }

		$_SESSION['addPrisonerError'] = $prisSuccess;
    header('Location: '.BASE_URL.'/prisoners/add'); exit();
  }

  /**
   * Viewing a prisoner from database
   */
  public function viewPrisoner($id){
  	// Retrieve prisoner via ID
    $prisoner = Prisoner::load($id);
    // Retrieve events related to prisoner
    $events = Event::loadPrisonerEvents($id);
		$camps = Housing::loadCampsFromPrisoner($id);
		$prisComments = Comment::loadPrisonerComments($id, 0, 10);

	// Failed to load prisoner
	if (is_string($prisoner)) {
		$_SESSION['viewPrisonerError'] = $prisoner;
		header('Location: '.BASE_URL.'/prisoners'); exit();
	}

    $pageTitle = "prisoner";
    include_once SYSTEM_PATH.'/view/header.tpl';
    include_once SYSTEM_PATH.'/view/prisoner.tpl';
    include_once SYSTEM_PATH.'/view/footer.tpl';
  }

  /**
   * Deletes a prisoner from the database
   */
  public function deletePrisoner($id) {
	  $prisoner = Prisoner::load($id);

	  // Delete the prisoner with specified ID
	  $success = $prisoner->delete();
	  if (is_string($success)) {
	  	  // Failed to delete prisoner from database JSON
		  echo json_encode(array('success' => 'failure', 'error' => $success));
		  exit();
	  }

		// Record the feed after the prisoner is deleted
		$activityFeed = new ActivityFeed(
			0,
			$_SESSION['username'],
			null,
			null,
			$prisoner->name,
			'delete_prisoner',
			null
		);
		$activityFeedSuccess = $activityFeed->save();
		if (is_string($activityFeedSuccess)) {
			echo json_encode(array('success' => 'failure', 'error' => $activityFeedSuccess));
		  exit();
		}

	  // Successful prisoner deletion JSON
	  echo json_encode(array('success' => 'success'));
	  exit();
  }

  /**
   * Add event with corresponding prisoner id to database
   */
  public function addPrisonerEvent($id) {
  	  // Get POST variables
	  $date = $_POST['date'];
	  $title = $_POST['title'];
	  $description = $_POST['description'];

	  // Create new event object
		$event = new Event(
			0,
			$id,
			$title,
			$date,
			$description
		);

	  // Save event
	  $success = $event->save();
	  if (is_string($success)) {
	  	  // Failed to create event JSON
		  echo json_encode(array('success' => 'failure', 'error' => $success));
		  exit();
	  }

		// Record the feed after the event is added
		$activityFeed = new ActivityFeed(
			0,
			$_SESSION['username'],
			$id,
			null,
			null,
			'edit_prisoner',
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

  /**
   * Edit a prisoner with specified ID
   */
  public function editPrisoner($id) {
  	  // Get POST variables
	  $camps = $_POST['camps'];
	  $dateOfBirth = $_POST['dateofbirth'];
	  $dateOfDeath = $_POST['dateofdeath'];
	  $countryOfOrigin = $_POST['origin'];
	  $name = $_POST['name'];
	  $imageUrl = $_POST['imageUrl'];

	  // Failed to get prisoner from database
	  $prisoner = Prisoner::load($id);
	  if (is_string($prisoner)) {
		  echo json_encode(array('success' => 'failure', 'error' => $success));
		  exit();
	  }

	  // Update prisoner details
	  if (!empty($name)) $prisoner->name = $name;
	  if (!empty($imageUrl)) $prisoner->imageUrl = $imageUrl;
	  if (!empty($dateofBirth)) $prisoner->dateOfBirth = $dateOfBirth;
	  if (!empty($dateOfDeath)) $prisoner->dateOfDeath = $dateOfDeath;
	  if (!empty($countryOfOrigin)) $prisoner->countryOfOrigin = $countryOfOrigin;

	  // Save prisoner
	  $success = $prisoner->save();
	  if (is_string($success)) {
		  echo json_encode(array('success' => 'failure', 'error' => $success));
		  exit();
	  }

		// Because of the way the camps list is edited, the feed must be added here
		$activityFeed = new ActivityFeed(
			0,
			$_SESSION['username'],
			$id,
			null,
			null,
			'edit_prisoner',
			null
		);
		$activityFeedSuccess = $activityFeed->save();
		if (is_string($activityFeedSuccess)) {
			echo json_encode(array('success' => 'failure', 'error' => $activityFeedSuccess));
		  exit();
		}

	  // Update prisoner's camps list
	  if (!empty($camps)) {
		  $campId = Camp::loadIdByName($camps);
		  if ($campId === NO_RESULTS) {
			  echo json_encode(array('success' => 'failure', 'error' => 'Camp '.$camps.' does not exist.'));
			  exit();
		  }

		  if (!is_numeric($campId)) {
			  echo json_encode(array('success' => 'failure', 'error' => $campId));
			  exit();
		  }

		  $housing = new Housing($id, $campId);
		  $success = $housing->insert();
		  if (is_string($success)) {
			  echo json_encode(array('success' => 'failure', 'error' => $success));
			  exit();
		  }
			echo json_encode(array('success' => 'success', 'campId' => $campId));
		 exit();
	 }else {
		 echo json_encode(array('success' => 'success', 'campId' => 'Camp does not exist'));
		 exit();
	 }


  }

	public function addComment($id){
		$content = $_POST['comment'];
		$newComment = new Comment(0, $_SESSION['username'], null, $id, null, $content, null);
		$success = $newComment->save();
		if (is_string($success)) {
				// Failed to create event JSON
			echo json_encode(array('success' => 'failure', 'error' => $success));
			exit();
		}
		$feedEvent = new ActivityFeed(0, $_SESSION['username'], $id, null, null, "comment_prisoner", null);
		$activitySuccess = $feedEvent->save();
		if(is_string($activitySuccess)){
			echo json_encode(array('success' => 'failure', 'error' => $activitySuccess));
			exit();
		}
		// Successful event creation JSON
		echo json_encode(array('success' => 'success'));
		exit();
	}

	public function deleteFromList($id){
		$prisoner = Prisoner::load($id);
		$prisoner->delete();
	 	header('Location: '.BASE_URL.'/prisoners/'); exit();
	}
}
