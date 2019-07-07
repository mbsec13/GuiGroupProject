<?php

include_once '../global.php';

class Camp {

  const TABLE_NAME = 'Camp';

  public $id = 0;
  public $name = null;
  public $purpose = null;
  public $demographic = null;
  public $location = null;
  public $latitude = null;  // This and longitude will not be obtained from the user
  public $longitude = null;
  public $numberOfPrisoners = null;
  public $dateBegan = null;
  public $dateEnded = null;
  public $warden = null;
  public $imageUrl = null;
  public $creator = null;
  public $children = null;

  public function __construct($id, $name, $purpose, $demographic, $location, $latitude, $longitude, $numberOfPrisoners, $dateBegan, $dateEnded, $warden, $imageUrl, $creator) {
    $this->id = $id;
    $this->name = $name;
    $this->purpose = $purpose;
    $this->demographic = $demographic;
    $this->location = $location;
    $this->latitude = $latitude;
    $this->longitude = $longitude;
    $this->numberOfPrisoners = $numberOfPrisoners;
    $this->dateBegan = $dateBegan;
    $this->dateEnded = $dateEnded;
    $this->warden = $warden;
    $this->imageUrl = $imageUrl;
    $this->creator = $creator;
  }

  public function toArray() {
    return array(
      'name' => $this->name,
      'purpose' => $this->purpose,
      'demographic' => $this->demographic,
      'location' => $this->location,
      'latitude' => $this->latitude,
      'longitude' => $this->longitude,
      'numberOfPrisoners' => $this->numberOfPrisoners,
      'dateBegan' => $this->dateBegan,
      'dateEnded' => $this->dateEnded,
      'warden' => $this->warden,
      'imageUrl' => $this->imageUrl,
      'creator' => $this->creator
    );
  }

  // Load a camp from the database given an ID
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
    return new Camp(
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
  }

  // Get all camps in database
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

    $campArray = [];
    foreach ($resultSet as $row) {
      $campArray[] = new Camp(
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
    }

    return $campArray;
  }

  // Load a Camp object from the database given a name
  public static function loadIdByName($name) {
      $row = DB::selectQuery(
          ['id'],
          self::TABLE_NAME,
          ['name' => $name],
          'NULL',
          0,
          1
      );
      if (is_string($row)) return $row;

      return $row[0]['id'];
  }

  // Save the camp in the database
  public function save() {
    if ($this->id === 0) return $this->insert();
    else return $this->update();
  }

  // Update the Camp in the database
  private function update() {
    $db = DB::instance();
    if ($db->connectError) return $db->connectError;

    // Query the Google Geocoding API to update the location
    $formattedLocation = preg_replace('~[ ]~', '', $db->escapeNoQuotes($this->location));
    $formattedLocation = str_replace(',', '+', $formattedLocation);
    $requestUrl = sprintf('https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s',
        $formattedLocation,
        'AIzaSyAx0BtA19HQ-qYKfn6c_RVW2H02xXv9nkk'
    );
    $geocodingJson = file_get_contents($requestUrl);

    // Input the lat and long into the object
    $geocodingObject = json_decode($geocodingJson, true);
    if ($geocodingObject['status'] === 'OK') {
        $this->latitude = $geocodingObject['results'][0]['geometry']['location']['lat'];
        $this->longitude = $geocodingObject['results'][0]['geometry']['location']['lng'];
    }

    return DB::updateQuery($this->id, 'id', self::TABLE_NAME, $this->toArray());
  }

  // Insert the camp into the database
  private function insert() {
    $db = DB::instance();
    if ($db->connectError) return $db->connectError;

    // Query the Google Geocoding API to update the location
    $formattedLocation = preg_replace('~[ ]~', '', $db->escapeNoQuotes($this->location));
    $formattedLocation = str_replace(',', '+', $formattedLocation);
    $requestUrl = sprintf('https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s',
        $formattedLocation,
        'AIzaSyAx0BtA19HQ-qYKfn6c_RVW2H02xXv9nkk'
    );
    $geocodingJson = file_get_contents($requestUrl);

    // Input the lat and long into the object
    $geocodingObject = json_decode($geocodingJson, true);
    if ($geocodingObject['status'] === 'OK') {
      $this->latitude = $geocodingObject['results'][0]['geometry']['location']['lat'];
      $this->longitude = $geocodingObject['results'][0]['geometry']['location']['lng'];
    }

    $this->id = DB::insertQuery(self::TABLE_NAME, $this->toArray());
    return $this->id;
  }

  // Delete the camp in the database
  public function delete() {
    return DB::deleteQuery($this->id, 'id', self::TABLE_NAME);
  }

}

?>
