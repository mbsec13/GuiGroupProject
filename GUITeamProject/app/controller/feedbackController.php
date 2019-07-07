<?php

include_once '../global.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a FeedbackController and route it
$fbc = new FeedbackController();
$fbc->route($action);

class FeedbackController {

  public function route($action) {

    switch ($action) {
      case 'display':
        $this->display();
        break;

      case 'process':
        $this->process();
        break;

    }
  }

  public function display() {
      // Get a random Camp for the user to review
      $camp = Feedback::loadUnreviewedCamp();
      $activities = Workload::loadActivitiesFromCamp($camp->id);
      if (is_string($camp)) $feedbackError = $camp;

      include_once SYSTEM_PATH.'/view/moderate.tpl';
    }

    public function process() {
      $content = $_POST['feedback'];
      if ($content == "") {
			header('Location: '.BASE_URL.'/crowd/'); exit();
		}
      $id = $_POST['id'];

      $feedback = new Feedback($id, $content);
      $feedbackTest = Feedback::load($id);
      if ($feedbackTest === NO_RESULTS) $success = $feedback->insert();
      else $success = $feedback->update();

      if (is_string($success)) {
        echo json_encode(array(
          'success' => 'failure',
          'error' => $success
        ));
        exit();
      }
      else {
        echo json_encode(array(
          'success' => 'success'
        ));
        exit();
      }
    }
}

?>
