<?php
/**
 * A file for handling POST and GET requests associated with the event page.
 */

require_once("../config/config.php");

// Creates a connection to the database using variables from the config file
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
    } else if (isset($_GET['start'])) {
        $startTime = getStartTime($conn, $event_id);
        echo json_encode($startTime[0][0]);
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

if (isset($_GET['event_id']) && isset($_GET['clustername']) && isset($_GET['timeoffset']) && isset($_GET['activation']))  {
    $event_id = $_GET['event_id'];
    $cluster_name = $_GET['clustername'];
    $time_offset = $_GET['timeoffset'];
    $activation = $_GET['activation'];
    $result = addAction($conn, $event_id, $cluster_name, $time_offset, $actions);
    echo json_encode($result);
}

function getStartTime($conn, $event_id) {
    $query = "CALL get_event_start_time(?)";
    $result = queryDBStartTime($conn, $event_id, $query);

    $rows = [];
    while ($row = $result->fetch_row()) {
        $rows[] = $row;
    }
    return $rows;
}


/**
 * Queries the database for all of the actions associated with an event, the result includes the same action
 * but for different rooms/groups.
 *
 * @param $conn connection The database connection
 * @param $event_id int the id of the associated event
 * @param $date string the data of the associated event
 * @return array[] list where the first element is the field name and the second element is all the actions
 */
function getActions($conn, $event_id, $date) {
    $query = "CALL show_event(?, ?)";
    $result = queryDB($conn, $event_id, $date, $query);

    return processResult($result);
}

/**
 * Queries the database for all of the distinct actions associated with an event, the result the actions and not the
 * associated rooms/groups. Each action will only appear once.
 *
 * @param $conn connection The database connection
 * @param $event_id int the id of the associated event
 * @return array[] list where the first element is the field name and the second element is all the actions
 */
function getDistinctActions($conn, $event_id) {
    $query = "CALL get_actions({$event_id});";
    $result = queryDBDistinct($conn, $query);

    return processResult($result);
}

/**
 * Queries the database to delete the action with the given id.
 *
 * @param $conn connection The database connection
 * @param $action_id int the id of the action to delete
 * @return array[] list with the result of the query
 */
function deleteAction($conn, $action_id) {
    $query = "CALL delete_actions({$action_id});";
    $result = queryDBDistinct($conn, $query);

    return processResult($result);
}

/**
 * Query the database to add an action.
 *
 * @param $conn connection The database connection
 * @param $event_id int The id of the event the action is associated with
 * @param $cluster_name string The name of the action cluster
 * @param $time_offset string The time offset of the action
 * @param $activation int whether to activate or deactivate the action
 */
function addAction($conn, $event_id, $cluster_name, $time_offset, $activation) {
    $query = "CALL add_action(?, ?, ?, ?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issi', $event_id, $time_offset, $cluster_name, $activation);
    $stmt->execute();
    $stmt->close();
}

/**
 * Calls the stored procedure show_event with the given parameters using a prepared statement
 *
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
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function queryDBStartTime($conn, $event_id, $query) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function queryDBDistinct($conn, $query) {

    return $conn->query($query);
}

/**
 * Processes the result of a database query into a format to be sent back.
 *
 * @param $result result The result of the database query
 * @return array[] An array with the field names and the rows.
 */
function processResult($result) {
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
 * Echos an mysql error.
 *
 * @param string $error The error passed.
 */
function fatalError($error)
{
    $message = mysql_error();
    echo <<< _END
Something went wrong :/
<p>$error: $message</p>
_END;
}

// Closes database connection
$conn->close();
?>