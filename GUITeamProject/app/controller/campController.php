<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$sc = new CampController();
$sc->route($action);

class CampController {

	// route to the appropriate class method for this action
	public function route($action) {
		switch($action) {

			case 'addCampProcess':
				$this->newCamp();
				break;

			case 'viewCamp':
				$name = $_GET['name'];
				$this->viewCamp($name);
				break;

			case 'json':
				$this->campJson();
				break;

			case 'updateCamp':
				$name = $_GET['name'];
				$field = $_GET['field'];
				$this->updateCamp($name, $field);
				break;

			case 'addAct':
				$name = $_GET['name'];
				$this->addActivity($name);
				break;

			case 'delete':
				$name = $_GET['name'];
				$this->delete($name);
				break;

			case 'comment':
				$name = $_GET['name'];
				$this->addComment($name);
				break;
		}
  }
	//create a new camp and insert it into the database
  public function newCamp(){
		//get fields for the camp data
    $name = $_POST['name'];
    $purpose = $_POST['purpose'];
    $demographic = $_POST['demographic'];
    $location = $_POST['location'];
    $numberOfPrisoners = $_POST['num'];
    $dateBegan = $_POST['start'];
    $dateEnded = $_POST['stop'];
    $warden = $_POST['warden'];
		$imageUrl = $_POST['imageUrl'];

    if ($name == "") { // If user didn't properly access add camp page, redirect
      header('Location: '.BASE_URL); exit();
    }
		//get fields for activity, if one is added
    $type = $_POST['activityName'];
    $description = $_POST['activityDesc'];
		//if name and location are empty, show error
    if( empty($name) || empty($location) ) {
			$_SESSION['addCampError'] = 'You must input a valid camp name and location.';
      header('Location: '.BASE_URL.'/camps/add'); exit();
    }
		//if type of activity is empty, show error
		if ( empty($type) && !empty($description) ) {
			$_SESSION['addCampError'] = 'You must input a valid activity type.';
			header('Location: '.BASE_URL.'/camps/add'); exit();
		}
		//create a new camp and add it to the database
    $camp = new Camp(
			0,
			$name,
	    $purpose,
	    $demographic,
	    $location,
			null,
			null,
	    $numberOfPrisoners,
	    $dateBegan,
	    $dateEnded,
	    $warden,
			$imageUrl,
			$_SESSION['username']
		);
		$campSuccess = $camp->save();

    if( !is_string($campSuccess) ) {
			if ( !empty($type) ) {
				//If successful, and an activity was added, add activity to the database
				$activity = new Activity(0, $type, $description);
	      $actSuccess = $activity->save();

				if(!is_string($actSuccess)){
					//If activity is successful, link them together in the workload mapping table
					$work = new Workload($actSuccess, $campSuccess);
					$workSuccess = $work->insert();

					if (is_string($workSuccess)) {
						$_SESSION['addCampError'] = $workSuccess;
				    header('Location: '.BASE_URL.'/camps/add'); exit();
					}
				}
				else {
					$_SESSION['addCampError'] = $actSuccess;
			    header('Location: '.BASE_URL.'/camps/add'); exit();
				}
			}

			// Log the user's action in a feed
			$feed = new ActivityFeed(
				0,
				$_SESSION['username'],
				$campSuccess,
				null,
				null,
				'add_camp',
				null
			);
			$activityFeedSuccess = $feed->save();
			if (is_string($activityFeedSuccess)) {
				$_SESSION['addCampError'] = $activityFeedSuccess;
				header('Location: '.BASE_URL.'/camps/add'); exit();
			}

			header('Location: '.BASE_URL.'/camps'); exit();
    }

		$_SESSION['addCampError'] = $campSuccess;
    header('Location: '.BASE_URL.'/camps/add'); exit();
  }
	//Function to view a particular camp's page
	public function viewCamp($name){
		$camp = Camp::load($name);
		//If no camp is found, go to list view
		if (is_string($camp)) {
			$_SESSION['viewCampError'] = $camp;
			header('Location: '.BASE_URL.'/camps'); exit();
		}
		//Load the set of activities corresponding to the camp from the workload mapping table
		$activities = Workload::loadActivitiesFromCamp($camp->id);
		//load comments made on the camp
		$campComments = Comment::loadCampComments($name, 0, 10);
		//view the camp template
		$pageTitle = "camp";
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/camp.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}
	//change the values in a camp's fields
	public function updateCamp($name, $field){
		$campChange = Camp::load($name);
		//the field parameter determines which field is being edited. Each of these switch cases send back json.
		switch($field){
			//check if warden and purpose fields are empty, if not, update them
			case 'wardenAndPurpose':
			$warden = $_POST['warden'];
			$purpose = $_POST['purpose'];
			$name = $_POST['name'];
			$url = $_POST['url'];
			if($warden != '')
				$campChange->warden = $warden;
			if($purpose != '')
				$campChange->purpose = $purpose;
			if($name != '')
				$campChange->name = $name;
			if($url != '')
				$campChange->imageUrl = $url;
			$success = $campChange->save();
			if(!is_string($success)){
				$json = array(
					'success' => 'success');
			} else {
				$json = array('error' => 'Could not update.');
			}
			header('Content-Type: application/json'); // let client know it's Ajax
			echo json_encode($json); // print the JSON
			break;
			//check if location field is empty, if not, update it
			case 'Location':
				$location = $_POST['locate'];
				if($location != '')
					$campChange->location = $location;
				$success = $campChange->save();
				if(!is_string($success)){
					$json = array(
					'success' => 'success');
				} else {
					$json = array('error' => 'Could not update.');
				}
			header('Content-Type: application/json'); // let client know it's Ajax
			echo json_encode($json); // print the JSON
			break;
			//check if date fields are empty, if not, update them
			case 'LocationAndName':
			//check if location field is empty, if not, update it.
			//also check if name field is empty, if not, update it.
			$location = $_POST['locate'];
			if($location != '')
					$campChange->location = $location;
			$name = $_POST['nme'];
			if($name != '')
					$campChange->name = $name;
				
			$success = $campChange->save();
			if(!is_string($success)){
				$json = array(
				'success' => 'success');
			} else {
				$json = array('error' => 'Could not update.');
			}
			header('Content-Type: application/json'); // let client know it's Ajax
			echo json_encode($json); // print the JSON
			break;
			case 'Uptime':
			$begin = $_POST['begin'];
			$end = $_POST['end'];
			if(!empty($begin))
				$campChange->dateBegan = $begin;
			if(!empty($end))
				$campChange->dateEnded = $end;
			$success = $campChange->save();
			if(!is_string($success)){
				$json = array(
					'success' => 'success');
			} else {
				$json = array('error' => 'Could not update.');
			}
			header('Content-Type: application/json'); // let client know it's Ajax
			echo json_encode($json); // print the JSON
			break;
			//check if demographic field is empty, if not, update it
			case 'Demographic':
				$demo = $_POST['demographic'];
				if($demo != '')
					$campChange->demographic = $demo;
				$success = $campChange->save();
				if(!is_string($success)){
					$json = array(
					'success' => 'success');
				} else {
					$json = array('error' => 'Could not update.');
				}
			header('Content-Type: application/json'); // let client know it's Ajax
			echo json_encode($json); // print the JSON
			break;
			//in case none of the cases fire, return error message in json.
			default:
				$json = array('error' => 'not found.');
				header('Content-Type: application/json'); // let client know it's Ajax
				echo json_encode($json); // print the JSON
				break;
		}

		// Record the feed after the update to make sure that the update still happened
		if (!is_string($campChange)) {
			$activityFeed = new ActivityFeed(
				0,
				$_SESSION['username'],
				$campChange->id,
				null,
				null,
				'edit_camp',
				null
			);
			$activityFeedSuccess = $activityFeed->save();

			if (is_string($activityFeedSuccess)) {
				echo json_encode(array(
					'error' => $activityFeedSuccess
				));
				exit();
			}
		}

	}
	//add an activity to a camp page
	public function addActivity($name){
		$title = $_POST['title'];
		$description = $_POST['description'];
		//load the information for the camp
		$camp = Camp::load($name);
		//create a new activity with the input information
		$activity = new Activity(0, $title, $description);
		$actSuccess = $activity->save();
		//If successful, create a new workload object to map them together
		if(!is_string($actSuccess)){
			$work = new Workload($actSuccess, $camp->id);
			$workSuccess = $work->insert();
			if(!is_string($workSuccess)){
				$json = array('success' => 'success');
			} else {
				$json = array('error' => 'work success not a string.');
			}
		} else {
			$json = array('error' => $actSuccess);
		}

		// Record the feed after the edit
		if (!is_string($camp)) {
			$activityFeed = new ActivityFeed(
				0,
				$_SESSION['username'],
				$camp->id,
				null,
				null,
				'edit_camp',
				null
			);
			$activityFeedSuccess = $activityFeed->save();

			if (is_string($activityFeedSuccess)) {
				echo json_encode(array(
					'error' => $activityFeedSuccess
				));
				exit();
			}
		}

		//header('Content-Type: application/json'); // let client know it's Ajax
		echo json_encode($json); // print the JSON
	}
	//Delete function
	public function delete($name){
		//load the camp to be deleted, then delete it.
		$deleted = Camp::load($name);
		$success = $deleted->delete();
		if(!is_string($success)){
			$json = array(
			'success' => 'success');
		} else {
			$json = array('error' => 'Could not update.');
		}

		// Record the feed after the delete
		$activityFeed = new ActivityFeed(
			0,
			$_SESSION['username'],
			null,
			null,
			$deleted->name,
			'delete_camp',
			null
		);
		$activityFeedSuccess = $activityFeed->save();

		if (is_string($activityFeedSuccess)) {
			echo json_encode(array(
				'error' => $activityFeedSuccess
			));
			exit();
		}

		header('Content-Type: application/json'); // let client know it's Ajax
		echo json_encode($json); // print the JSON
	}

	public function addComment($name){
		$content = $_POST['comment'];
		$newComment = new Comment(0, $_SESSION['username'], $name, null, null, $content, null);
		$success = $newComment->save();
		if (is_string($success)) {
				// Failed to create event JSON
			echo json_encode(array('success' => 'failure', 'error' => $success));
			exit();
		}
		$feedEvent = new ActivityFeed(0, $_SESSION['username'], $name, null, null, "comment_camp", null);
		$activitySuccess = $feedEvent->save();
		if(is_string($activitySuccess)){
			echo json_encode(array('success' => 'failure', 'error' => $activitySuccess));
			exit();
		}
		// Successful event creation JSON
		echo json_encode(array('success' => 'success'));
		exit();
	}

	//get the latitudes and longitudes for each camp added to the databse, so the information will show up on the map
	public function campJson() {
		$jsonArray = [];
		$jsonArray['results'] = [];
		$jsonArray['results']['ids'] = [];
		$jsonArray['results']['latitudes'] = [];
		$jsonArray['results']['longitudes'] = [];
		//get all camps in the database
		$campArray = Camp::loadAll();
		//if no results, exit
		if ($campArray === NO_RESULTS) {
			$jsonArray['success'] = true;
			$jsonArray['error'] = null;
			echo json_encode($jsonArray);
			exit();
		}
		//if error occurs, exit
		if (is_string($campArray)) {
			$jsonArray['success'] = false;
			$jsonArray['error'] = $campArray;
			echo json_encode($jsonArray);
			exit();
		}
		//map each camp to a retrieved latitude and longitde
		foreach ($campArray as $camp) {
			$jsonArray['results']['ids'][] = $camp->id;
			$jsonArray['results']['latitudes'][] = $camp->latitude;
			$jsonArray['results']['longitudes'][] = $camp->longitude;
		}

		$jsonArray['success'] = true;
		$jsonArray['error'] = null;
		echo json_encode($jsonArray);
		exit();
	}
}
