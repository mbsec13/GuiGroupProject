<?php

include_once '../global.php';

class Activity {
  const TABLE_NAME = 'Activity';

  public $id = 0;
  public $type = null;
  public $description = null;
  public $name = null;

  public function __construct($id, $type, $description) {
    $this->id = $id;
    $this->type = $type;
    $this->description = $description;
  }

  public function toArray() {
    return array(
      'type' => $this->type,
      'description' => $this->description
    );
  }

  // Load an activity from the database given an ID
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
    return new Activity(
      $row['id'],
      $row['type'],
      $row['description']
    );
  }

  // Get all activities in database
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

    $activityArray = [];
    foreach ($resultSet as $row) {
      $activityArray[] = new Activity(
        $row['id'],
        $row['type'],
        $row['description']
      );
    }

    return $activityArray;
  }

  // Save the activity object
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the activity object in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the activity object into the database
  private function insert() {
    $this->id = DB::insertQuery(self::TABLE_NAME, $this->toArray());
    return $this->id;
  }

  // Delete the activity in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }
}

?>
