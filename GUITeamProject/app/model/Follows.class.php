<?php

include_once '../global.php';

class Follows {

  const TABLE_NAME = 'Follows';

  public $follower = null;
  public $followee = null;

  public function __construct($follower, $followee) {
    $this->follower = $follower;
    $this->followee = $followee;
  }

  public function toArray() {
    return array(
      'follower' => $this->follower,
      'followee' => $this->followee
    );
  }

  // Loads a list of users who are followed by the given user
  public static function loadFollowees($follower) {
    $resultSet = DB::selectQuery(
        ['followee'],
        self::TABLE_NAME,
        ['follower' => $follower],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $userArray = [];
    foreach ($resultSet as $row) {
      $userArray[] = User::load($row['followee']);
    }

    return $userArray;
  }

  // Loads a list of users who follow the given user
  public static function loadFollowers($followee) {
    $resultSet = DB::selectQuery(
        ['follower'],
        self::TABLE_NAME,
        ['followee' => $followee],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $userArray = [];
    foreach ($resultSet as $row) {
      $userArray[] = User::load($row['follower']);
    }

    return $userArray;
  }

  /*
  I will not provide an update function because this is a mapping table.
  Referential integrity cannot be preserved in this case when changing foreign
  keys; therefore, if you wish to change a relationship, you must delete the
  previous key pair and insert a new one.
  */

  public function insert() {
    return DB::insertQuery(self::TABLE_NAME, $this->toArray());
  }

  // Delete the Follows object from the database
  public function delete() {
    return DB::deleteMapQuery($this->follower, 'follower', $this->followee, 'followee', self::TABLE_NAME);
  }

}

?>
