<?php

include_once '../global.php';

class Comment {

  const TABLE_NAME = 'Comment';

  public $id = 0;
  public $username = null;
  public $campId = null;
  public $prisonerId = null;
  public $profileId = null;
  public $content = null;
  public $madeOn = null;

  public function __construct($id, $username, $campId, $prisonerId, $profileId, $content, $madeOn) {
    $this->id = $id;
    $this->username = $username;
    $this->campId = $campId;
    $this->prisonerId = $prisonerId;
    $this->profileId = $profileId;
    $this->content = $content;
    $this->madeOn = $madeOn;
  }

  public function toArray() {
    return array(
      'username' => $this->username,
      'campId' => $this->campId,
      'prisonerId' => $this->prisonerId,
      'profileId' => $this->profileId,
      'content' => $this->content,
      'madeOn' => $this->madeOn
    );
  }

  // Load an comment from the database given an ID
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
    return new Comment(
      $row['id'],
      $row['username'],
      $row['campId'],
      $row['prisonerId'],
      $row['profileId'],
      $row['content'],
      $row['madeOn']
    );
  }

  // Get all comments in database
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

    $commentArray = [];
    foreach ($resultSet as $row) {
      $commentArray[] = new Comment(
        $row['id'],
        $row['username'],
        $row['campId'],
        $row['prisonerId'],
        $row['profileId'],
        $row['content'],
        $row['madeOn']
      );
    }

    return $commentArray;
  }

  // Load all comments from a user
  public static function loadUserComments($profileId, $offset, $rowCount) {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['profileId' => $profileId],
        'madeOn',
        $offset,
        $rowCount
    );
    if (is_string($resultSet)) return $resultSet;

	foreach ($resultSet as $row) {
		  $commentArray[] = new Comment(
			$row['id'],
			$row['username'],
			$row['campId'],
			$row['prisonerId'],
			$row['profileId'],
			$row['content'],
			$row['madeOn']
		  );
		}
	return $commentArray;
  }

  // Load list of comments made on a camp page
  public static function loadCampComments($campId, $offset, $rowCount) {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['campId' => $campId],
        'madeOn',
        $offset,
        $rowCount
    );
    if (is_string($resultSet)) return $resultSet;

    foreach ($resultSet as $row) {
      $commentArray[] = new Comment(
        $row['id'],
        $row['username'],
        $row['campId'],
        $row['prisonerId'],
        $row['profileId'],
        $row['content'],
        $row['madeOn']
      );
	}
    return $commentArray;
  }

  // Load list of comments made on a prisoner page
  public static function loadPrisonerComments($prisonerId, $offset, $rowCount) {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['prisonerId' => $prisonerId],
        'madeOn',
        $offset,
        $rowCount
    );
    if (is_string($resultSet)) return $resultSet;

    foreach ($resultSet as $row) {
      $commentArray[] = new Comment(
        $row['id'],
        $row['username'],
        $row['campId'],
        $row['prisonerId'],
        $row['profileId'],
        $row['content'],
        $row['madeOn']
      );
	}
    return $commentArray;
  }

  public function getCommentUserImage() {
      $resultSet = DB::selectQuery(
          ['imageUrl'],
          User::TABLE_NAME,
          ['username' => $this->username],
          'NULL',
          0,
          1
      );
      if (is_string($resultSet)) return null;

      $row = $resultSet[0];
      return $row['imageUrl'];
  }

  // Save the comment object
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the comment object in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the comment object into the database
  private function insert() {
    // Guard against empty comments to avoid spam
    if (!empty($this->content)) {
        $this->id = DB::insertQuery(self::TABLE_NAME, $this->toArray());
        return $this->id;
    }

    return 0;
  }

  // Delete the comment in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
