<?php

include_once '../global.php';

class Feedback {

  const TABLE_NAME = 'Feedback';

  public $campId = 0;
  public $content = null;

  public function __construct($campId, $content) {
    $this->campId = $campId;
    $this->content = $content;
  }

  public function toArray() {
    return array(
      'campId' => $this->campId,
      'content' => $this->content
    );
  }

  // Load feedback from the database given an ID
  public static function load($campId) {
    $row = DB::selectQuery(
        ['*'],
        self::TABLE_NAME,
        ['campId' => $campId],
        'NULL',
        0,
        1
    );
    if (is_string($row)) return $row;

    $row = $row[0];
    return new Feedback(
      $row['campId'],
      $row['content']
    );
  }

  // Get all feedback in database
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

    $feedbackArray = [];
    foreach ($resultSet as $row) {
      $feedbackArray[] = new Feedback(
        $row['campId'],
        $row['content']
      );
    }

    return $feedbackArray;
  }

  public static function loadUnreviewedCamp() {
    $db = DB::instance();
    if ($db->connectError) return $db->connectError;

    $queryString = sprintf('SELECT * FROM %s WHERE id NOT IN (SELECT campId FROM %s) ORDER BY RAND() LIMIT 1;',
      Camp::TABLE_NAME,
      self::TABLE_NAME
    );
    $result = $db->query($queryString);

    if ($result) {
      // First load a random camp that has not yet been reviewed
      $row = $result->fetch_assoc();

      $camp = new Camp(
        $row['id'],
        $row['name'],
        $row['purpose'],
        $row['demographic'],
        $row['location'],
        $row['latitude'],
        $row['longitude'],
        $row['numberOfPrisoners'],
        $row['dateBegan'],
        $row['dateEnded'],
        $row['warden'],
        $row['imageUrl'],
        $row['creator']
      );

      $result->close();
      return $camp;
    }
    elseif ($db->error === NO_RESULTS) {
      // Otherwise, load a completely random camp
      $queryString = sprintf('SELECT * FROM %s ORDER BY RAND() LIMIT 1;',
        Camp::TABLE_NAME
      );
      $result = $db->query($queryString);

      if ($result) {
        // First load a random camp that has not yet been reviewed
        $row = $result->fetch_assoc();

        $camp = new Camp(
          $row['id'],
          $row['name'],
          $row['purpose'],
          $row['demographic'],
          $row['location'],
          $row['latitude'],
          $row['longitude'],
          $row['numberOfPrisoners'],
          $row['dateBegan'],
          $row['dateEnded'],
          $row['warden'],
          $row['imageUrl'],
          $row['creator']
        );

        $result->close();
        return $camp;
      }
      else return $db->error;
    }
    else return $db->error;
  }

  // Update the feedback object in the database
  public function update() {
    return DB::updateQuery($this->campId, 'campId', self::TABLE_NAME, $this->toArray());
  }

  // Insert the feedback object into the database
  public function insert() {
    $this->campId = DB::insertQuery(self::TABLE_NAME, $this->toArray());
    return $this->campId;
  }

  // Delete the feedback in the database
  public function delete() {
    return DB::deleteQuery($this->campId, 'campId', self::TABLE_NAME);
  }

}

?>
