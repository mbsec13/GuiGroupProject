<?php

include_once '../global.php';

class Workload {

  const TABLE_NAME = 'Workload';

  public $activityId = 0;
  public $campId = 0;

  public function __construct($activityId, $campId) {
    $this->activityId = $activityId;
    $this->campId = $campId;
  }

  public function toArray() {
    return array(
      'activityId' => $this->activityId,
      'campId' => $this->campId
    );
  }

  // Returns a list of activities around which $campId was centered
  public static function loadActivitiesFromCamp($campId) {
    $resultSet = DB::selectQuery(
        ['activityId'],
        self::TABLE_NAME,
        ['campId' => $campId],
        'NULL',
        0,
        PHP_INT_MAX
    );
    if (is_string($resultSet)) return $resultSet;

    $activityArray = [];
    foreach ($resultSet as $row) {
      $activityArray[] = Activity::load($row['activityId']);
    }

    return $activityArray;
  }

  // Returns a list of camps at which $activityId was performed
  public static function loadCampsFromActivity($activityId) {
    $resultSet = DB::selectQuery(
        ['campId'],
        self::TABLE_NAME,
        ['activityId' => $activityId],
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

  public function delete() {
    return DB::deleteMapQuery($this->activityId, 'activityId', $this->campId, 'campId', self::TABLE_NAME);
  }

}

?>
