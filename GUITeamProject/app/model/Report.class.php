<?php

include_once '../global.php';

class Report {

  const TABLE_NAME = 'Report';

  public $id = 0;
  public $reporter = null;
  public $reportee = null;
  public $description = null;

  public function __construct($id, $reporter, $reportee, $description) {
    $this->id = $id;
    $this->reporter = $reporter;
    $this->reportee = $reportee;
    $this->description = $description;
  }

  public function toArray() {
    return array(
      'reporter' => $this->reporter,
      'reportee' => $this->reportee,
      'description' => $this->description
    );
  }

  // Load an report from the database given an ID
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
    return new Report(
      $row['id'],
      $row['reporter'],
      $row['reportee'],
      $row['description']
    );
  }

  // Get all reports in database
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

    $reportArray = [];
    foreach ($resultSet as $row) {
      $reportArray[] = new Report(
        $row['id'],
        $row['reporter'],
        $row['reportee'],
        $row['description']
      );
    }

    return $reportArray;
  }

  // Get all the reports made against a single user
  public function loadReportsOnUser($username) {
    $resultSet = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['reportee' => $username],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $reportArray = [];
    foreach ($resultSet as $row) {
      $reportArray[] = new Report(
        $row['id'],
        $row['reporter'],
        $row['reportee'],
        $row['description']
      );
    }

    return $reportArray;
  }

  // Save the report object
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the report object in the database
  private function update() {
    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the report object into the database
  private function insert() {
    return DB::insertQuery(self::TABLE_NAME, $this->toArray());
  }

  // Delete the report in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
