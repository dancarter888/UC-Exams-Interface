<?php
require_once("../config/config.php");

// Creates a connection to the database using variables form the config file
$conn = new mysqli($hostname, $username, $password, $database);

// Catches any error connecting to the database
if ($conn->connect_error)
{
    fatalError($conn->connect_error);
    return;
}



// Checks the event id and date are set and then calls getActions with these parameters
// Then echos json of all the actions retrieved for Event.php to then process and add to
// the actions table
if (isset($_GET['event_id']) && isset($_GET['date']))
{
    $event_id = $_GET['event_id'];
    $date = $_GET['date'];
    if (isset($_GET['distinct'])) {
        $actions = getDistinctActions($conn, $event_id);
        echo json_encode([$actions[0], $actions[1]]);
    } else {
        $get_actions = getActions($conn, $event_id, $date);
        $field_names = $get_actions[0];
        $actions = $get_actions[1];
        echo json_encode([$field_names, $actions]);
    }
}

if (isset($_GET['action_id'])) {
    $action_id = $_GET['action_id'];
    $result = deleteAction($conn, $action_id);
    echo json_encode($result);
}

/**
 * @param $conn connection to the database
 * @param $event_id
 * @param $date
 * @return array[] list where the first element is the field name and the second element is all the actions
 */
function getActions($conn, $event_id, $date) {
    $query = "CALL show_event(?, ?)";
    $result = queryDB($conn, $event_id, $date, $query);
    $field_names = [];
    $rows = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }
    return [$field_names, $rows];
}

/**
 * @param $conn connection to the database
 * @param $event_id
 * @param $date
 * @return array[] list where the first element is the field name and the second element is all the actions
 */
function getDistinctActions($conn, $event_id) {
    $query = "CALL get_actions({$event_id});";
    $result = queryDBDistinct($conn, $query);
    $field_names = [];
    $rows = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }

    return [$field_names, $rows];
}

function deleteAction($conn, $action_id) {
    $query = "CALL delete_actions({$action_id});";
    $result = queryDBDistinct($conn, $query);
    $field_names = [];
    $rows = [];
    while ($field = $result->fetch_field()) {
        $field_names[] = $field->name;
    }
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }

    return [$field_names, $rows];
}

/**
 * Calls the stored procedure show_event with the given parameters using a prepared statement
 * @param $conn connection to the database
 * @param $event_id string event id of the event selected by the user
 * @param $date string date of the above event
 * @param $query string to query db with
 * @return mixed results from the query
 */
function queryDB($conn, $event_id, $date, $query) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $event_id, $date);
    $stmt->execute();
    return $stmt->get_result();
}

function queryDBDistinct($conn, $query) {
//    $stmt = $conn->prepare($query);
//    //$stmt->bind_param('is', $event_id);
//    $stmt->execute();
    return $conn->query($query);
}


/**
 * Sanitizes a given string and returns it.
 * @param $var
 * @return string
 */
function sanitizeString($var) {
    if (get_magic_quotes_gpc())
        $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

// Closes database connection
$conn->close();
?>