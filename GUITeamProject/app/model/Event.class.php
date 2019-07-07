<?php

include_once '../global.php';

class Event {

  const TABLE_NAME = 'Event';

  public $id = 0;
  public $prisonerId = null;
  public $title = null;
  public $dateHappened = null;
  public $description = null;

  public function __construct($id, $prisonerId, $title, $dateHappened, $description) {
    $this->id = $id;
    $this->prisonerId = $prisonerId;
    $this->title = $title;
    $this->dateHappened = $dateHappened;
    $this->description = $description;
  }

  public function toArray() {
    return array(
      'prisonerId' => $this->prisonerId,
      'title' => $this->title,
      'dateHappened' => $this->dateHappened,
      'description' => $this->description
    );
  }

  // Get a Event object from the database given an ID
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
    return new Event(
      $row['id'],
      $row['prisonerId'],
      $row['title'],
      $row['dateHappened'],
      $row['description']
    );
  }

  // Get all events from the database
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

    $eventArray = [];
    foreach ($resultSet as $row) {
      $eventArray[] = new Event(
        $row['id'],
        $row['prisonerId'],
        $row['title'],
        $row['dateHappened'],
        $row['description']
      );
    }

    return $eventArray;
  }

  // Retrieve the list of Events for a given $prisonerId
  public static function loadPrisonerEvents($prisonerId) {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['prisonerId' => $prisonerId],
        'dateHappened',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $eventArray = [];
    foreach ($resultSet as $row) {
      $eventArray[] = new Event(
        $row['id'],
        $row['prisonerId'],
        $row['title'],
        $row['dateHappened'],
        $row['description']
      );
    }

    return $eventArray;
  }

  // Save the Event object in the database
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the Event object in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the Event object into the database
  private function insert() {
    $this->id = DB::insertQuery(self::TABLE_NAME, $this->toArray());
    return $this->id;
  }

  // Delete the event in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
