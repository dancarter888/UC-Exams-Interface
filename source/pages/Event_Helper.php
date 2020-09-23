<?php
require_once("../config/config.php");

if (isset($_GET['id']))
{
    if ($_GET['id'] != "") {
        $event = getEvent($_GET['id']);
        echo json_encode($event);
    }
}

/**
 * Queries the database for a list of the events.
 * @return array an array of the event_id, event_name and status
 */
function getEvent($id) {
    $result = queryDB("CALL get_one_event_details(?);", $id);
    return $result;
}

function queryDB($query, $event_id) {
    $hostname = "127.0.0.1";
    $database = "tserver";
    $username = "root";
    $password = "mysql";
    $conn = new mysqli($hostname, $username, $password, $database);
    if ($conn->connect_error)
    {
        fatalError($conn->connect_error);
        return;
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $actions = [];

    for($i = 0; $i < $result->num_rows; $i++) {
        $action = $result->fetch_assoc();
        array_push($actions, $action);
    }
    return $actions;
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
?>