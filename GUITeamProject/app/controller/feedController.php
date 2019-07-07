<?php

include_once '../global.php';

/*
FEED CONTROLLER

Handles the procedural generation of list views of activity feeds
and comment lists on several pages, via AJAX GET requests.  This page does NOT
handle the initial loading of the pages with these lists on them.
*/

// Get the request action
$action = $_GET['action'];

// Create an instance and route the request
$fc = new FeedController();
$fc->route($action);

class FeedController {

  public function route($action) {
    switch ($action) {
      // Guard against unwanted URL directs

      case 'profileActivityFeed':
        if (!isset($_GET['index'])) {
          header('Location: '.BASE_URL.'/user/'.$_GET['username']);
          exit();
        }

        $this->profileActivityFeed();
        break;

      case 'profileComments':
        if (!isset($_GET['index'])) {
          header('Location: '.BASE_URL.'/user/'.$_GET['username']);
          exit();
        }

        $this->profileComments();
        break;

      case 'campComments':
        if (!isset($_GET['index'])) {
          header('Location: '.BASE_URL.'/camps/'.$_GET['camp']);
          exit();
        }

        $this->campComments();
        break;

      case 'prisonerComments':
        if (!isset($_GET['index'])) {
          header('Location: '.BASE_URL.'/prisoners/'.$_GET['prisoner']);
          exit();
        }

        $this->prisonerComments();
        break;

      case 'globalActivityFeed':
        if (!isset($_GET['index'])) {
          header('Location: '.BASE_URL);
          exit();
        }

        $this->globalActivityFeed();
        break;

    }
  }

  private function profileActivityFeed() {
    $index = $_GET['index'];
    $increment = $_GET['increment'];

    $feed = ActivityFeed::loadUserFeeds($_GET['username'], $index, $increment);
    if ($feed === NO_RESULTS) {
      echo json_encode(array(
        'success' => 'success',
        'feed' => []
      ));
      exit();
    }
    elseif (is_string($feed)) {
      echo json_encode(array(
        'success' => 'failure [feed is a string]',
        'error' => $feed
      ));
      exit();
    }
    else {
      /*
      JSON structure for feed array:

      [
        {
          (attributes of ActivityFeed in key-value format),
          'entity': {
            (attributes of the entity in key-value format, or empty object)
          }
        },
        ...
      ]
      */

      $feedArray = [];
      foreach ($feed as $fd) {
        $entity = null;
        if ($fd->type !== 'delete_camp' && $fd->type !== 'delete_prisoner') {
          $entity = $fd->getEntity(); // returns a string
		  
		  /*
          if (is_string($entity)) {
            echo json_encode(array(
              'success' => 'failure [entity is string]',
              'error' => $entity
            ));
            exit();
          }
		  */
        }
		
        $singleFeedArray = $fd->toArray();
        if ($entity !== null) {
			$singleFeedArray['entity'] = "<nothing>";
		} else {
			$singleFeedArray['entity'] = entity;
		}
		
		$singleFeedArray['entity'] = $fd->getEntity();

        $singleFeedArray['happened'] = time_elapsed_string($singleFeedArray['happened']);
		$singleFeedArray['getAction'] = $fd->getAction();
		$singleFeedArray['getDestType'] = $fd->getDestType();
		
        $feedArray[] = $singleFeedArray;
      }

      echo json_encode(array(
        'success' => 'success',
        'feed' => $feedArray
      ));
      exit();
    }
  }

  private function profileComments() {
    $index = $_GET['index'];
    $increment = $_GET['increment'];

    $comments = Comment::loadUserComments($_GET['username'], $index, $increment);
    if ($comments === NO_RESULTS) {
      echo json_encode(array(
        'success' => 'success',
        'comments' => []
      ));
      exit();
    }
    elseif (is_string($comments)) {
      echo json_encode(array(
        'success' => 'failure',
        'error' => $comments
      ));
      exit();
    }
    else {
      /*
      JSON structure for comments array:

      [
        {
          (attributes of the comment table in key-value format)
        },
        ...
      ]
      */

      $commentArray = [];
      foreach ($comments as $comment) {
        $singleCommentArray = $comment->toArray();
        $singleCommentArray['madeOn'] = time_elapsed_string($singleCommentArray['madeOn']);

        $commentArray[] = $singleCommentArray;
      }

      echo json_encode(array(
        'success' => 'success',
        'comments' => $commentArray
      ));
      exit();
    }
  }

  private function campComments() {
    $index = $_GET['index'];
    $increment = $_GET['increment'];

    $comments = Comment::loadCampComments($_GET['camp'], $index, $increment);
    if ($comments === NO_RESULTS) {
      echo json_encode(array(
        'success' => 'success',
        'comments' => []
      ));
      exit();
    }
    elseif (is_string($comments)) {
      echo json_encode(array(
        'success' => 'failure',
        'error' => $comments
      ));
      exit();
    }
    else {
      /*
      JSON structure for comments array:

      [
        {
          (attributes of the comment table in key-value format)
        },
        ...
      ]
      */

      $commentArray = [];
      foreach ($comments as $comment) {
        $singleCommentArray = $comment->toArray();
        $singleCommentArray['madeOn'] = time_elapsed_string($singleCommentArray['madeOn']);

        $commentArray[] = $singleCommentArray;
      }

      echo json_encode(array(
        'success' => 'success',
        'comments' => $commentArray
      ));
      exit();
    }
  }

  private function prisonerComments() {
    $index = $_GET['index'];
    $increment = $_GET['increment'];

    $comments = Comment::loadPrisonerComments($_GET['prisoner'], $index, $increment);
    if ($comments === NO_RESULTS) {
      echo json_encode(array(
        'success' => 'success',
        'comments' => []
      ));
      exit();
    }
    elseif (is_string($comments)) {
      echo json_encode(array(
        'success' => 'failure',
        'error' => $comments
      ));
      exit();
    }
    else {
      /*
      JSON structure for comments array:

      [
        {
          (attributes of the comment table in key-value format)
        },
        ...
      ]
      */

      $commentArray = [];
      foreach ($comments as $comment) {
        $singleCommentArray = $comment->toArray();
        $singleCommentArray['madeOn'] = time_elapsed_string($singleCommentArray['madeOn']);

        $commentArray[] = $singleCommentArray;
      }

      echo json_encode(array(
        'success' => 'success',
        'comments' => $commentArray
      ));
      exit();
    }
  }

  public function globalActivityFeed() {
    $index = $_GET['index'];
    $increment = $_GET['increment'];

    $feed = ActivityFeed::loadUserAndFolloweeFeeds($_SESSION['username'], $index, $increment);
    if ($feed === NO_RESULTS) {
      echo json_encode(array(
        'success' => 'success',
        'feed' => []
      ));
      exit();
    }
    elseif (is_string($feed)) {
      echo json_encode(array(
        'success' => 'failure',
        'error' => $feed
      ));
      exit();
    }
    else {
      /*
      JSON structure for feed array:

      [
        {
          (attributes of ActivityFeed in key-value format),
          'entity': {
            (attributes of the entity in key-value format, or empty object)
          }
        },
        ...
      ]
      */

      $feedArray = [];
      foreach ($feed as $fd) {
        $entity = null;
        if ($fd->type !== 'delete_camp' && $fd->type !== 'delete_prisoner') {
          $entity = $fd->getEntity();

          /*if (is_string($entity)) {
            echo json_encode(array(
              'success' => 'failure',
              'error' => $entity
            ));
            exit();
          }
		  */
        }
		
        $singleFeedArray = $fd->toArray();
        if ($entity !== null) {
			$singleFeedArray['entity'] = "<nothing>";
		} else {
			$singleFeedArray['entity'] = entity;
		}

		$singleFeedArray['entity'] = $fd->getEntity();

        $singleFeedArray['happened'] = time_elapsed_string($singleFeedArray['happened']);
		$singleFeedArray['getAction'] = $fd->getAction();
		$singleFeedArray['getDestType'] = $fd->getDestType();
		
		$feedArray[] = $singleFeedArray;
      }

      echo json_encode(array(
        'success' => 'success',
        'feed' => $feedArray
      ));
      exit();
    }
  }

}

?>
