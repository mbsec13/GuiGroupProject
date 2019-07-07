<?php

include_once '../global.php';

class Prisoner {

  const TABLE_NAME = 'Prisoner';

  public $id = 0;
  public $name = null;
  public $rank = null;
  public $dateOfBirth = null;
  public $dateOfDeath = null;
  public $countryOfOrigin = null;
  public $creator = null;
  public $imageUrl = null;

  public function __construct($id, $name, $rank, $dateOfBirth, $dateOfDeath, $countryOfOrigin, $creator, $imageUrl) {
    $this->id = $id;
    $this->name = $name;
    $this->rank = $rank;
    $this->dateOfBirth = $dateOfBirth;
    $this->dateOfDeath = $dateOfDeath;
    $this->countryOfOrigin = $countryOfOrigin;
    $this->creator = $creator;
    $this->imageUrl = $imageUrl;
  }

  public function toArray() {
    return array(
      'id' => $this->id,
      'name' => $this->name,
      'rank' => $this->rank,
      'dateOfBirth' => $this->dateOfBirth,
      'dateOfDeath' => $this->dateOfDeath,
      'countryOfOrigin' => $this->countryOfOrigin,
      'creator' => $this->creator,
      'imageUrl' => $this->imageUrl
    );
  }

  // Get a Prisoner object from the database given a ID
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
    return new Prisoner(
      $row['id'],
      $row['name'],
      $row['rank'],
      $row['dateOfBirth'],
      $row['dateOfDeath'],
      $row['countryOfOrigin'],
      $row['creator'],
      $row['imageUrl']
    );
  }

  // Get a Prisoner object from the database given a name
  public static function loadIdByName($name) {
    $resultSet = DB::selectQuery(
        ['id'],
        self::TABLE_NAME,
        ['name' => $name],
        'NULL',
        0,
        1
    );
    if (is_string($resultSet)) return $resultSet;

    return $resultSet[0]['id'];
  }

  // Gets all prisoners from the database
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

    $prisonerArray = [];
    foreach ($resultSet as $row) {
      $prisonerArray[] = new Prisoner(
        $row['id'],
        $row['name'],
        $row['rank'],
        $row['dateOfBirth'],
        $row['dateOfDeath'],
        $row['countryOfOrigin'],
        $row['creator'],
        $row['imageUrl']
      );
    }

    return $prisonerArray;
  }

  // Save the prisoner in the database
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the prisoner details in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Inserts the Prisoner object into the database
  private function insert() {
    $this->id = DB::insertQuery(self::TABLE_NAME, $this->toArray());
    return $this->id;
  }

  // Deletes the prisoner form the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
