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
    $get_actions = getActions($conn, $event_id, $date);
    $field_names = $get_actions[0];
    $actions = $get_actions[1];
    echo json_encode([$field_names, $actions]);
}

/**
 * @param $conn connection to the database
 * @param $event_id
 * @param $date
 * @return array[] list where the first element is the field name and the second element is all the actions
 */
function getActions($conn, $event_id, $date) {
    $result = queryDB($conn, $event_id, $date);
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
 * @return mixed results from the query
 */
function queryDB($conn, $event_id, $date) {
    $query = "CALL show_event(?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $event_id, $date);
    $stmt->execute();
    return $stmt->get_result();
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