<?php

include_once '../global.php';

class ActivityFeed {

  const TABLE_NAME = 'Activity_Feed';

  public $id = 0;
  public $username = null;
  public $entityId = null;
  public $otherUser = null;
  public $entityName = null;
  public $type = null;
  public $happened = null;

  public function __construct($id, $username, $entityId, $otherUser, $entityName, $type, $happened) {
    $this->id = $id;
    $this->username = $username;
    $this->entityId = $entityId;
    $this->otherUser = $otherUser;
    $this->entityName = $entityName;
    $this->type = $type;
    $this->happened = $happened;
  }

  public function toArray() {
    return array(
      'username' => $this->username,
      'entityId' => $this->entityId,
      'otherUser' => $this->otherUser,
      'entityName' => $this->entityName,
      'type' => $this->type,
      'happened' => $this->happened
    );
  }

  // Load a single feed from the database given an ID
  public static function load($id) {
      $row = DB::selectQuery(
          ['*'],
          self::TABLE_NAME,
          ['id' => $id],
          'NULL',
          0,
          1
      );
      if (is_string($row)) return $row;

      $row = $row[0];
      return new ActivityFeed(
        $row['id'],
        $row['username'],
        $row['entityId'],
        $row['otherUser'],
        $row['entityName'],
        $row['type'],
        $row['happened']
      );
  }

  // Get all feeds in database, regardless of user
  public static function loadAll() {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        DB::ALL_ROWS_CONDITION,
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $activityFeedArray = [];
    foreach ($resultSet as $row) {
      $activityFeedArray[] = new ActivityFeed(
        $row['id'],
        $row['username'],
        $row['entityId'],
        $row['otherUser'],
        $row['entityName'],
        $row['type'],
        $row['happened']
      );
    }

    return $activityFeedArray;
  }

  public static function loadUserFeeds($username, $offset, $rowCount) {
      $resultSet = DB::selectQuery(
          ['*'],
          self::TABLE_NAME,
          ['username' => $username],
          'happened',
          $offset,
          $rowCount
      );
      if (is_string($resultSet)) return $resultSet;

      $activityFeedArray = [];
      foreach ($resultSet as $row) {
        $activityFeedArray[] = new ActivityFeed(
          $row['id'],
          $row['username'],
          $row['entityId'],
          $row['otherUser'],
          $row['entityName'],
          $row['type'],
          $row['happened']
        );
      }

      return $activityFeedArray;
  }

  public static function loadUserAndFolloweeFeeds($username, $offset, $rowCount) {
    // We will not be able to use the abstracted query methods, as we need to match to a list
    $db = DB::instance();
    if ($db->connectError) return $db->connectError;

    $followsSet = Follows::loadFollowees($username);
    if ($followsSet !== NO_RESULTS && is_string($followsSet)) return $followsSet;

    // Will contain the list of names for which we need to look for feeds
    $usernameList = [];
    if ($followsSet !== NO_RESULTS) {
      foreach ($followsSet as $follows) {
        $usernameList[] = $follows->username;
      }
    }
    $usernameList[] = $username;

    // Write the query
    $queryString = sprintf('SELECT * FROM %s WHERE username IN (', self::TABLE_NAME);
    $isFirst = true;
    foreach ($usernameList as $usr) {
      if ($isFirst) {
        $queryString .= $db->escape($usr);
        $isFirst = false;
      }
      else $queryString .= ', '.$db->escape($usr);
    }
    $queryString .= sprintf(') ORDER BY happened DESC LIMIT %d, %d;',
        $db->escape($offset),
        $db->escape($rowCount)
    );

    // Execute query and return an array of feeds
    $resultSet = $db->query($queryString);
    if ($resultSet) {
      $activityFeedArray = [];
      foreach ($resultSet as $row) {
        $activityFeedArray[] = new ActivityFeed(
          $row['id'],
          $row['username'],
          $row['entityId'],
          $row['otherUser'],
          $row['entityName'],
          $row['type'],
          $row['happened']
        );
      }

      return $activityFeedArray;
    }
    else return $db->error;
  }

  public function getEntity() {
    /*
    Possible cases for user actions:
      Adding a camp
      Editing a camp
      Deleting a camp
      Adding a prisoner
      Editing a prisoner
      Deleting a prisoner
      Commenting on a camp
      Commenting on a prisoner
      Following someone
      Editing user account
      Creating a user account

    In the case of deleting an entity, we do not need to return the object within
    the database, because that is illogical as it no longer exists.  The model
    relies on the business logic to use the entity name field to handle this.
    */

    switch ($this->type) {
      case 'add_camp': $e = Camp::load($this->entityId);
        if(is_string($e)){
          return NULL;
        }
        return $e->name;
      case 'edit_camp': $e = Camp::load($this->entityId);
      if(is_string($e)){
        return NULL;
      }
        return $e->name;
      case 'add_prisoner': $e = Prisoner::load($this->entityId);
      if(is_string($e)){
        return NULL;
      }
        return $e->name;
      case 'edit_prisoner': $e = Prisoner::load($this->entityId);
      if(is_string($e)){
        return NULL;
      }
        return $e->name;
      case 'comment_user': $e = User::load($this->otherUser);
      if(is_string($e)){
        return NULL;
      }
        return $e->username;
      case 'comment_camp': $e = Camp::load($this->entityId);
      if(is_string($e)){
        return NULL;
      }
        return $e->name;
      case 'comment_prisoner': $e = Prisoner::load($this->entityId);
      if(is_string($e)){
        return NULL;
      }
        return $e->name;
      case 'follow': $e = User::load($this->otherUser);
      if(is_string($e)){
        return NULL;
      }
        return $e->username;
      case 'edit_user':$e = User::load($this->otherUser);
      if(is_string($e)){
        return NULL;
      }
        return $e->username;
      case 'add_user': $e = User::load($this->otherUser);
      if(is_string($e)){
        return NULL;
      }
        return $e->username;
    }
  }

  public function getDestType() {
    switch ($this->type) {
      case 'add_camp': return 'camps';
      case 'edit_camp': return 'camps';
      case 'add_prisoner': return 'prisoners';
      case 'edit_prisoner': return 'prisoners';
      case 'comment_user': return 'user';
      case 'comment_camp': return 'camps';
      case 'comment_prisoner': return 'prisoners';
      case 'follow': return 'user';
      case 'edit_user': return 'user';
      case 'add_user': return 'user';
    }
  }
  public function getAction() {
    switch ($this->type) {
      case 'add_camp': return 'added';
      case 'edit_camp': return 'edited';
      case 'add_prisoner': return 'added';
      case 'edit_prisoner': return 'edited';
      case 'comment_user': return 'commented on';
      case 'comment_camp': return 'commented on';
      case 'comment_prisoner': return 'commented on';
      case 'follow': return 'is now following';
      case 'edit_user': return 'edited their profile';
      case 'add_user': return 'created an account.';
    }
  }

  // Save the feed object
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the feed object in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the feed object into the database
  private function insert() {
    // Make sure that a new timestamp is set to default
    $activityFeedArray = $this->toArray();
    if ($activityFeedArray['happened'] === null) unset($activityFeedArray['happened']);

    $this->id = DB::insertQuery(self::TABLE_NAME, $activityFeedArray);
    return $this->id;
  }

  // Delete the feed in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
