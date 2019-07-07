<?php

include_once '../global.php';

class User {

  const TABLE_NAME = 'User';

  public $username = null;
  public $pw = null;
  public $first = null;
  public $last = null;
  public $email = null;
  public $dateOfBirth = null;
  public $gender = null;
  public $nameHidden = -1;
  public $emailHidden = -1;
  public $dateOfBirthHidden = -1;
  public $genderHidden = -1;
  public $rank = 2;
  public $bio = null;
  public $imageUrl = null;

  public function __construct(
      $username,
      $pw,
      $first,
      $last,
      $email,
      $dateOfBirth,
      $gender,
      $nameHidden,
      $emailHidden,
      $dateOfBirthHidden,
      $genderHidden,
      $rank,
      $bio,
      $imageUrl) {
    $this->username = $username;
    $this->pw = $pw;
    $this->first = $first;
    $this->last = $last;
    $this->email = $email;
    $this->dateOfBirth = $dateOfBirth;
    $this->gender = $gender;
    $this->nameHidden = $nameHidden;
    $this->emailHidden = $emailHidden;
    $this->dateOfBirthHidden = $dateOfBirthHidden;
    $this->genderHidden = $genderHidden;
    $this->rank = $rank;
    $this->bio = $bio;
    $this->imageUrl = $imageUrl;
  }

  public function toArray() {
    return array(
      'username' => $this->username,
      'password' => $this->pw,
      'firstName' => $this->first,
      'lastName' => $this->last,
      'email' => $this->email,
      'dateOfBirth' => $this->dateOfBirth,
      'gender' => $this->gender,
      'nameHidden' => $this->nameHidden,
      'emailHidden' => $this->emailHidden,
      'dateOfBirthHidden' => $this->dateOfBirthHidden,
      'genderHidden' => $this->genderHidden,
      'rank' => $this->rank,
      'bio' => $this->bio,
      'imageUrl' => $this->imageUrl
    );
  }

  // Get a User object given a username, or return database error if username does not exist
  public static function load($username) {
    $row = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['username' => $username],
        'NULL',
        0,
        1
    );
    if (is_string($row)) return $row;

    $row = $row[0];
    return new User(
      $row['username'],
      $row['password'],
      $row['firstName'],
      $row['lastName'],
      $row['email'],
      $row['dateOfBirth'],
      $row['gender'],
      $row['nameHidden'],
      $row['emailHidden'],
      $row['dateOfBirthHidden'],
      $row['genderHidden'],
      $row['rank'],
      $row['bio'],
      $row['imageUrl']
    );
  }

  public function update() {
      return DB::updateQuery($this->username, 'username', self::TABLE_NAME, $this->toArray());
  }

  // Insert the User object into the database
  public function insert() {
    $this->pw = md5($this->pw);
    return DB::insertQuery(self::TABLE_NAME, $this->toArray());
  }

  // Gets all users from the database
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

    $userArray = [];
    foreach ($resultSet as $row) {
      $userArray[] = new User(
          $row['username'],
          $row['password'],
          $row['firstName'],
          $row['lastName'],
          $row['email'],
          $row['dateOfBirth'],
          $row['gender'],
          $row['nameHidden'],
          $row['emailHidden'],
          $row['dateOfBirthHidden'],
          $row['genderHidden'],
          $row['rank'],
          $row['bio'],
          $row['imageUrl']
      );
    }

    return $userArray;
  }
}
