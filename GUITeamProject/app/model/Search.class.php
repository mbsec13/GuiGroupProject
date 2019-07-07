<?php

include_once '../global.php';

class Search {

    // Match the search query against the users table
    public static function searchUsers($searchQuery) {
      if (empty($searchQuery)) return array();

        $db = DB::instance();
        if ($db->connectError) return $db->connectError;

        // Escape the query but don't add quotes
        $sanitizedSearchQuery = $db->escapeNoQuotes($searchQuery);

        // Prepare a SELECT query using the LIKE clause
        $databaseQuery = sprintf('SELECT * FROM %s WHERE username LIKE \'%%%s%%\';',
            User::TABLE_NAME,
            $sanitizedSearchQuery
        );
        $result = $db->query($databaseQuery);

        // Form the array with the matching results
        if ($result) {
            $userArray = [];
            while ($row = $result->fetch_assoc()) {
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

            $result->close();
            return $userArray;
        }
        else return $db->error;
    }

    // Match the search query against the camps table and activities
    public static function searchCamps($searchQuery) {
      if (empty($searchQuery)) return array();

        $db = DB::instance();
        if ($db->connectError) return $db->connectError;

        // Initiate the array here, we will populate it later
        $campArray = [];

        // Escape the query but don't add quotes
        $sanitizedSearchQuery = $db->escapeNoQuotes($searchQuery);

        // Prepare a SELECT query using the LIKE clause for the camps themselves
        $databaseQuery = sprintf('SELECT * FROM %s WHERE (
            name LIKE \'%%%s%%\'
            OR purpose LIKE \'%%%s%%\'
            OR demographic LIKE \'%%%s%%\'
            OR location LIKE \'%%%s%%\'
            OR warden LIKE \'%%%s%%\'
            );',
            Camp::TABLE_NAME,
            $sanitizedSearchQuery,
            $sanitizedSearchQuery,
            $sanitizedSearchQuery,
            $sanitizedSearchQuery,
            $sanitizedSearchQuery
        );
        $campResult = $db->query($databaseQuery);
        $campError = $db->error;

        // Prepare a SELECT query using the LIKE clause for activities
        $databaseQuery = sprintf('SELECT * FROM %s WHERE type LIKE \'%%%s%%\';',
            Activity::TABLE_NAME,
            $sanitizedSearchQuery
        );
        $activityResult = $db->query($databaseQuery);
        $activityError = $db->error;

        // Check if an error occurred, or if no results were returned for both
        if (!$campResult && !$activityResult) {
            if ($campError === NO_RESULTS && $activityError === NO_RESULTS) return NO_RESULTS;
            elseif ($campError === NO_RESULTS && $activityError !== NO_RESULTS) return $activityError;
            else return $campError;
        }

        // Populate the camp array with the camps themselves
        if ($campResult) {
            while ($row = $campResult->fetch_assoc()) {
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

            $campResult->close();
        }

        // Use the Workload map to find all camps associated with the returned activities
        if ($activityResult) {
            while ($row = $activityResult->fetch_assoc()) {
                $auxCampArray = Workload::loadCampsFromActivity($row['id']);

                if (!is_string($auxCampArray)) $campArray = array_merge($campArray, $auxCampArray);
            }

            $activityResult->close();
        }

        return $campArray;
    }

    // Match the search query against the prisoners and events tables
    public static function searchPrisoners($searchQuery) {
        if (empty($searchQuery)) return array();

        $db = DB::instance();
        if ($db->connectError) return $db->connectError;

        // Initiate the array here, we will populate it later
        $prisArray = [];

        // Escape the query but don't add quotes
        $sanitizedSearchQuery = $db->escapeNoQuotes($searchQuery);

        // Prepare a SELECT query using the LIKE clause for prisoners
        $databaseQuery = sprintf('SELECT * FROM %s WHERE name LIKE \'%%%s%%\';',
            Prisoner::TABLE_NAME,
            $sanitizedSearchQuery
        );
        $prisonerResult = $db->query($databaseQuery);
        $prisonerError = $db->error;

        // Prepare a SELECT query using the LIKE clause for events
        $databaseQuery = sprintf('SELECT * FROM %s WHERE title LIKE \'%%%s%%\';',
            Event::TABLE_NAME,
            $sanitizedSearchQuery
        );
        $eventResult = $db->query($databaseQuery);
        $eventError = $db->error;

        // Check if an error occurred, or if no results were returned for both
        if (!$prisonerResult && !$eventResult) {
            if ($prisonerError === NO_RESULTS && $eventError === NO_RESULTS) return NO_RESULTS;
            elseif ($prisonerError === NO_RESULTS && $eventError !== NO_RESULTS) return $eventError;
            else return $prisonerError;
        }

        // Populate the prisoner array
        if ($prisonerResult) {
            while ($row = $prisonerResult->fetch_assoc()) {
              $prisArray[] = new Prisoner(
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

            $prisonerResult->close();
        }

        // Populate the prisoner array with prisoners based on events
        if ($eventResult) {
            while ($row = $eventResult->fetch_assoc()) {
                $prisArray[] = Prisoner::load($row['prisonerId']);
            }

            $eventResult->close();
        }

        return $prisArray;
    }

}

?>
