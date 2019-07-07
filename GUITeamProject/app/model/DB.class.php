<?php

include_once '../global.php';

class DB {

    const ALL_ROWS_CONDITION = ['1' => '1'];

  private static $_instance = null;
  private $connection;

  public $connectError = null;
  public $error = null;

  public function __construct() {
    $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DATABASE);  // This will have to be populated with the database credentials
    if ($this->connection->connect_error) $this->connectError = $this->connection->connect_error;
  }

  public static function instance() {
    if (self::$_instance === null) {
      self::$_instance = new DB();
    }

    return self::$_instance;
  }

  public function query($queryString) {
    $result = $this->connection->query($queryString);

    // Clear the error if there exists one
    $this->error = null;

    if ($result) {

      // Count 0 rows as an error
      if (!is_bool($result)) {
        if ($result->num_rows == 0) {
          $this->error = NO_RESULTS;
          $result->close();
          $result = false;
        }
      }

    }
    else $this->error = $this->connection->error;

    return $result;
  }

  public static function selectQuery($attributeArray, $table, $conditionArray, $orderKey, $offset, $rowCount) {
    $db = self::instance();
    if ($db->connectError) return $db->connectError;

    $queryString = 'SELECT ';

    // Add the attributes to select clause
    $isFirst = true;
    foreach ($attributeArray as $attribute) {
        if ($isFirst) {
            $queryString .= $attribute;
            $isFirst = false;
        }
        else $queryString .= ', '.$attribute;
    }

    // Add the table name
    $queryString .= ' FROM '.$table;

    // Add the where condition
    $queryString .= ' WHERE '.$db->sanitizeConditionArray($conditionArray);

    // Order the results
    $queryString .= ' ORDER BY '.$orderKey.' DESC';

    // Limit the results
    $queryString .= sprintf(' LIMIT %d, %d;', $db->escape($offset), $db->escape($rowCount));

    $result = $db->query($queryString);
    if ($result) {
      $resultSet = [];
      while ($row = $result->fetch_assoc()) {
          $resultSet[] = $row;
      }

      $result->close();
      return $resultSet;
    }
    else return $db->error;
  }

  public function sanitizeConditionArray($conditionArray) {
      $condition = '';

      $isFirst = true;
      foreach ($conditionArray as $key => $value) {
          if ($isFirst) {
              $condition .= $key.' = '.$this->escape($value);
              $isFirst = false;
          }
          else $condition .= ', '.$key.' = '.$this->escape($value);
      }

      return $condition;
  }

  public static function updateQuery($primaryValue, $primaryKey, $table, $fieldArray) {
    $db = self::instance();
    if ($db->connectError) return $db->connectError;

    // Update the non-null fields first, then check if the nullable fields are null
    $queryString = sprintf('UPDATE %s SET ',
      $table
    );

    $isFirst = true;
    foreach ($fieldArray as $key => $value) {
      if (!empty($value)) {
        if ($isFirst) {
          $queryString .= $key.' = '.$db->escape($value).' ';
          $isFirst = false;
        }
        else {
          $queryString .= ', '.$key.' = '.$db->escape($value).' ';
        }
      }
    }

    $queryString .= 'WHERE '.$primaryKey.' = '.$db->escape($primaryValue);
    $result = $db->query($queryString);
    if ($result) return $db->getInsertID();
    else return $db->error;
  }

  public static function insertQuery($table, $fieldArray) {
    $db = self::instance();
    if ($db->connectError) return $db->connectError;

    // Insert the non-null fields first, then check if the nullable fields are null
    $queryString = sprintf('INSERT INTO %s SET ',
      $table
    );

    $isFirst = true;
    foreach ($fieldArray as $key => $value) {
      if (!empty($value)) {
        if ($isFirst) {
          $queryString .= $key.' = '.$db->escape($value).' ';
          $isFirst = false;
        }
        else {
          $queryString .= ', '.$key.' = '.$db->escape($value).' ';
        }
      }
    }

    $result = $db->query($queryString);
    if ($result) return $db->getInsertID();
    else return $db->error;
  }

  public static function deleteQuery($primaryValue, $primaryKey, $table) {
    $db = self::instance();
    if ($db->connectError) return $db->connectError;

    if (is_string($primaryValue))
      $queryString = sprintf('DELETE FROM %s WHERE %s = %s;',
        $table,
        $primaryKey,
        $db->escape($primaryValue)
      );
    else
      $queryString = sprintf('DELETE FROM %s WHERE %s = %d;',
        $table,
        $primaryKey,
        $db->escape($primaryValue)
      );

    $result = $db->query($queryString);
    if ($result) return true;
    else return $db->error;
  }

  public static function deleteMapQuery($valueOne, $keyOne, $valueTwo, $keyTwo, $table) {
    $db = self::instance();
    if ($db->connectError) return $db->connectError;

    if (is_string($valueOne) && is_string($valueTwo))
      $queryString = sprintf('DELETE FROM %s WHERE %s = %s AND %s = %s;',
        $table,
        $keyOne,
        $db->escape($valueOne),
        $keyTwo,
        $db->escape($valueTwo)
      );
    else
      $queryString = sprintf('DELETE FROM %s WHERE %s = %d AND %s = %d;',
        $table,
        $keyOne,
        $db->escape($valueOne),
        $keyTwo,
        $db->escape($valueTwo)
      );

    $result = $db->query($queryString);
    if ($result) return true;
    else return $db->error;
  }

  public function escape($rawString) {
    if ($rawString === null) return 'NULL'; // So the database throws an error
    else if (is_numeric($rawString)) return $this->connection->real_escape_string($rawString);
    else return '\''.$this->connection->real_escape_string($rawString).'\'';
  }

  public function getInsertID() {
    return $this->connection->insert_id;
  }

  // For escaping specifically passwords
 public function escapePassword($rawString) {
   return '\''.md5($this->connection->real_escape_string($rawString)).'\'';
 }

 // For some reason, if we need to escape something and not add quotes to it

  public function escapeNoQuotes($rawString) {
    return $this->connection->real_escape_string($rawString);
  }
}

?>
