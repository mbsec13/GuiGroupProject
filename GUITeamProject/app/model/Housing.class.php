<?php

include_once '../global.php';

class Housing {

  const TABLE_NAME = 'Housing';

  public $prisonerId = 0;
  public $campId = 0;

  public function __construct($prisonerId, $campId) {
    $this->prisonerId = $prisonerId;
    $this->campId = $campId;
  }

  public function toArray() {
    return array(
      'prisonerId' => $this->prisonerId,
      'campId' => $this->campId
    );
  }

  // Returns a list of prisoners who stayed at camp $campId
  public static function loadPrisonersFromCamp($campId) {
    $resultSet = DB::selectQuery(
        ['prisonerId'],
        self::TABLE_NAME,
        ['campId' => $campId],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $prisonerArray = [];
    foreach ($resultSet as $row) {
      $prisonerArray[] = Prisoner::load($row['prisonerId']);
    }

    return $prisonerArray;
  }

  // Returns a list of camps at which $prisonerId stayed
  public static function loadCampsFromPrisoner($prisonerId) {
    $resultSet = DB::selectQuery(
        ['campId'],
        self::TABLE_NAME,
        ['prisonerId' => $prisonerId],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $campArray = [];
    foreach ($resultSet as $row) {
      $campArray[] = Camp::load($row['campId']);
    }

    return $campArray;
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

  // Delete he Housing object from the database
  public function delete() {
    return DB::deleteMapQuery($this->prisonerId, 'prisonerId', $this->campId, 'campId', self::TABLE_NAME);
  }

}

?>
